<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Required: Common, AjaxQueueHandler

// Base URL for the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\''; ?> + '/';

// Sets the events
$(document).ready(function () {
  // Storage module
  Storage.init();
});

//
// Storage
//
(function( Storage, $, undefined ) {

  // Internal variables
  // Loading panel
  var loadingPanel;
  // Loading dots
  var loadingDots;
  // Connection error
  var connectionError;
  // RAID stats
  var raidStats;
  // RAID stats chart
  var raidStatsChart;
  // RAID global write speed
  var raidSpeed;
  // Space stats
  var spaceStats;
  // Space stats chart
  var spaceStatsChart;
  // Total space
  var spaceTotal;
  // Used space
  var spaceUsed;
  // Free space
  var spaceFree;
  // Used space (%)
  var spaceUsedP;
  // Free space (%)
  var spaceFreeP;
  // RAID not configured
  var raidOff;
  // RAID slow panel
  var raidSlow;
  // RAID format progress bar
  var formatProgress;
  // RAID format progress label
  var formatLabel;

  // Initializes the module
  Storage.init = function() {
    // Set the internal variables
    loadingPanel = $('#loadingDiskStats');
    loadingDots = $('#gatheringDots');
    connectionError = $('#storageConnectionError');
    raidStats = $('#raidStats');
    raidStatsChart = $('#raidStatsChart');
    raidSpeed = $('#raidSpeed');
    spaceStats = $('#spaceStats');
    spaceStatsChart = $('#spaceStatsChart');
    spaceTotal = $('#spaceTotal');
    spaceUsed = $('#spaceUsed');
    spaceFree = $('#spaceFree');
    spaceUsedP = $('#spaceUsedP');
    spaceFreeP = $('#spaceFreeP');
    raidOff = $('#raidNotConfigured');
    raidSlow = $('#raidSlow');
    formatProgress = $('#formatProgress');
    formatProgress.css('width', '100%');
    formatLabel = $('#formatLabel');

    // Dismiss format RAID
    $('#dismissFormat').on('click', function() {
      raidSlow.fadeOut();
    });
    // Format RAID event
    $('#formatRaidModal').on('shown.bs.modal', formatRAID);

    // Stats
    getStorageStats();
  };

  // Retrieve the storage stats
  function getStorageStats() {
    // Dot animation
    var dotAnimation = setInterval(function() {
      var numDots = (loadingDots.text().length % 13) + 1;
      loadingDots.text(Array(numDots + 1).join('.'));
    }, 750);

    // Petition
    $.ajax({
      type: 'GET',
      url: baseURL + 'storage/stats',
      dataType: 'json',
      timeout: 3000000,
      success: function (resp) {
        // Stop dot animation
        clearInterval(dotAnimation);
        loadingPanel.fadeOut().promise().done(function(){
          // RAID on
          if(resp.raid_stats.raid_active) {
            raidStats.fadeIn().promise().done(function() {
              fillRaidStats(resp);
              spaceStats.fadeIn().promise().done(function() {
                fillSpaceStats(resp);
              });
            });
          }
          // RAID off
          else {
            spaceStats.fadeIn().promise().done(function() {
              fillSpaceStats(resp);
              raidOff.fadeIn();
            });
          }
        });
      },
      error: function (e) {
        if(AjaxQueueHandler.userLeft()) {
          return;
        }
        // Stop dot animation
        clearInterval(dotAnimation);
        // Error
        loadingPanel.fadeOut().promise().done(function(){
          connectionError.fadeIn();
        });
      }
    });
  };

  // Fill space stats
  function fillSpaceStats(resp) {
    // Chart
    var ctx = spaceStatsChart.get(0).getContext('2d');
    var GB = 1024*1024*1024;
    var chartData = [
      {
        value: (resp.used_space/GB).toFixed(2),
        color: '#F7464A',
        highlight: '#FF5A5E',
        label: <?php echo '\'' . _('Used Space') . '\''; ?> + ' (GB)'
      },
      {
        value: ((resp.total_space - resp.used_space)/GB).toFixed(2),
        color: '#46BFBD',
        highlight: '#5AD3D1',
        label: <?php echo '\'' . _('Free Space') . '\''; ?> + ' (GB)'
      }
    ];
    new Chart(ctx).Pie(chartData);

    // Detailed stats
    spaceTotal.text(Common.parseBytes(resp.total_space));
    spaceUsed.text(Common.parseBytes(resp.used_space));
    spaceFree.text(Common.parseBytes(resp.total_space - resp.used_space));
    spaceUsedP.text((100*resp.used_space/resp.total_space).toFixed() + '%');
    spaceFreeP.text((100*(resp.total_space - resp.used_space)/resp.total_space).toFixed() + '%');
  };

  // Fill RAID stats
  function fillRaidStats(resp) {
    // Chart
    var ctx = raidStatsChart.get(0).getContext('2d');
    var GB = 1024*1024*1024;
    var chartData = {};
    chartData.labels = [];
    var color = 'rgba(';
    // Slow speed (speed < 10Gbits/sec) (10/8 * 1024 * 1024 * 1024)
    if(resp.raid_stats.write_speed < (1.25 * GB)) {
      // RAID slow panel
      raidSlow.fadeIn();
      color = color + '247,70,74';
    }
    // Acceptable speed (10Gb/s < speed < 12Gb/s) (12/8 * 1024 * 1024 * 1024)
    else if (resp.raid_stats.write_speed < (1.5 * GB)) {
      color = color + '204,0,0';
    }
    // Good speed (speed > 12Gb/s)
    else {
      color = color + '93,169,237';
    }
    chartData.datasets = [
      {
        label: <?php echo '\'' . _('Write Speed') . '\''; ?> + ' (MB/s)',
        fillColor: color + ',0.5)',
        strokeColor: color + ',0.8)',
        highlightFill: color + ',0.75)',
        highlightStroke: color + ',1)',
        data: []
      }
    ];
    resp.raid_stats.disks.forEach(function(disk) {
      chartData.labels.push(disk.name);
      chartData.datasets[0].data.push((disk.write_speed/(1024*1024)).toFixed(2));
    });
    new Chart(ctx).Bar(chartData);

    // Detailed stats
    raidSpeed.css('color', color + ',1)');
    raidSpeed.css('font-weight', 'Bold');
    raidSpeed.text(Common.parseBytes(resp.raid_stats.write_speed) + '/s');
  };

  // Formats the RAID
  function formatRAID() {
    // Set the text
    formatLabel.text(<?php echo '\'' . _('Request in progress...') . '\''; ?>);

    // Petition
    $.ajax({
      type: 'DELETE',
      url: baseURL + 'storage/raid',
      dataType: 'json',
      headers: {
        'timestamp': Date.now()
      },
      timeout: 3000000,
      success: function (resp) {
        // Success
        formatProgress.removeClass('progress-bar-info active').addClass('progress-bar-success');
        formatLabel.text(<?php echo '\'' . _('RAID successfully re-created') . '\''; ?>);
        formatProgress.parent().after(' <a href="storage" class="label label-success">' + <?php echo '\'' . _('Reload') . '\''; ?> + '</a>');
      },
      error: function (e) {
        if(AjaxQueueHandler.userLeft()) {
          return;
        }
        // Error
        formatProgress.removeClass('progress-bar-info active').addClass('progress-bar-danger');
        formatLabel.text(<?php echo '\'' . _('Error formatting the RAID. The FPGA may be in use') . '\''; ?>);
        formatProgress.parent().after(' <a href="storage" class="label label-danger">' + <?php echo '\'' . _('Reload') . '\''; ?> + '</a>');
      }
    });
  };

}( window.Storage = window.Storage || {}, jQuery ));