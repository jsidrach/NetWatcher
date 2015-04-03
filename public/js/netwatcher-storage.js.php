<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Required: Common, AjaxQueueHandler

// Base URL for the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?> + '/';

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
  // TODO: format raid

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
            // TODO: format raid
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
    var chartData = [
      {
        value: (resp.used_space/(1024*1024*1024)).toFixed(2),
        color:'#F7464A',
        highlight: '#FF5A5E',
        label: <?php echo '\'' . _('Used Space') . '\''; ?> + ' (GB)'
      },
      {
        value: ((resp.total_space - resp.used_space)/(1024*1024*1024)).toFixed(2),
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
    var chartData = {};
    chartData.labels = [];
    chartData.datasets = [
      {
        label: <?php echo '\'' . _('Write Speed') . '\''; ?> + ' (MB/s)',
        fillColor: 'rgba(247,70,74,0.5)',
        strokeColor: 'rgba(247,70,74,0.8)',
        highlightFill: 'rgba(247,70,74,0.75)',
        highlightStroke: 'rgba(247,70,74,1)',
        data: []
      }
    ];
    resp.raid_stats.disks.forEach(function(disk) {
      chartData.labels.push(disk.name);
      chartData.datasets[0].data.push((disk.write_speed/(1024*1024)).toFixed(2));
    });
    new Chart(ctx).Bar(chartData);

    // Detailed stats
    // TODO: Color depending on the speed
    raidSpeed.text(Common.parseBytes(resp.raid_stats.write_speed) + '/s');
  };

}( window.Storage = window.Storage || {}, jQuery ));