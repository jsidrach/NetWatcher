<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base URL for the calls
var baseURL = <?php echo '\'' . \Core\ Config::$REMOTE_SERVER_IP . '\''; ?> ;
// Interval timer+callback
var interval = null;
// Captures table
var tableCaptures;

// Not a capture is selected
var noCaptureText;

// Selected capture name
var selectedCaptureName = null;
// Selected capture type
var selectedCaptureType = null;

// Resize the table headers on window resize
$(window).on('resize', function () {
  tableCaptures.bootstrapTable('resetView');
});

// Sets the events
$(document).ready(function () {
  // Set the document vars
  tableCaptures = $('#tableCaptures');

  // Set the noCaptureText var
  noCaptureText = $('#captureName').text();

  // Disable right panels
  toggleRightPanels(false);

  // Refresh when radio buttons are pressed
  $('#allCaptures').click(refreshData);
  $('#simpleCaptures').click(refreshData);
  $('#pcapCaptures').click(refreshData);
  // Set AutoRefresh with the check button
  $('#autoRefresh').change(autoRefreshHandler);
  // Refresh button
  $('button[name="refresh"]').off('click').on('click', refreshData);

  // Select row from captures table
  tableCaptures.on('click', 'tbody tr', selectCapture);

  // Convert capture
  $('#convertOK').click(convertCapture);
  // Rename capture
  $('#renameOK').click(renameCapture);
  // Delete capture
  $('#deleteCapture').click(deleteCapture);

  // Initial refresh of data
  refreshData();
});

// Enables/Disables the right panels
function toggleRightPanels(value) {
  $('#convertedName').attr('disabled', !value);
  $('#convertOK').attr('disabled', !value);
  $('#newName').attr('disabled', !value);
  $('#renameOK').attr('disabled', !value);
  $('#deleteCapture').attr('disabled', !value);
  if(!value) {
    $('#captureName').text(noCaptureText);
    selectedCaptureName = null;
    selectedCaptureType = null;
    $('#convertedName').val('');
    $('#newName').val('');
  }
}

// Refreshes the table data
function refreshData() {
  // Set the petition url
  var capturesURL = baseURL + '/captures/';
  if ($('#simpleCaptures').is(':checked')) {
    capturesURL += 'simple';
  } else if ($('#pcapCaptures').is(':checked')) {
    capturesURL += 'pcap';
  } else {
    capturesURL += 'all';
  }

  // Get the new data
  $.ajax({
    type: 'GET',
    url: capturesURL,
    dataType: 'jsonp',
    data: '',
    success: function (resp) {
      // Set the table data
      tableCaptures.bootstrapTable('load', resp.captures);
    },
    error: function (e) {
      // Notification of the error (timeout most of the times)
      $.bootstrapGrowl( <?php echo '\''._('Connection error').'\''; ?> , {
       type: 'danger'
      });
      setAutoRefresh(false);
    }
  });
}

// AutoRefresh check button handler
function autoRefreshHandler() {
  if ($('#autoRefresh').is(':checked')) {
    setAutoRefresh(true);
  } else {
    setAutoRefresh(false);
  }
}

// Sets the autorefresh
function setAutoRefresh(value) {
  clearInterval(interval);
  if (value) {
    refreshData();
    interval = setInterval(refreshData, 5000);
  }
  $('#autoRefresh').prop('checked', value);
}

// Selects a capture from the table
function selectCapture() {
  selectedCaptureName = $(this).find('td:nth-child(1)').text();
  selectedCaptureType = $(this).find('td:nth-child(2)').text();
  $('#captureName').text(selectedCaptureName);
  toggleRightPanels(true);
}

// Convert the selected capture
function convertCapture() {
  if(!validName($('#newName').val())) {
    $.bootstrapGrowl( <?php echo '\''._('Converted capture\\\'s name is not valid').'\''; ?> , {
     type: 'danger'
    });
    return;
  }

  // TODO: Query
}

// Rename the selected capture
function renameCapture() {
  if(!validName($('#newName').val())) {
    $.bootstrapGrowl( <?php echo '\''._('New name is not valid').'\''; ?> , {
     type: 'danger'
    });
    return;
  }

  // TODO: Query
}

// Delete the selected capture
function deleteCapture() {
  if(confirm( <?php echo '\''._('Selected capture will be permanently deleted, are you sure you want to delete it?').'\''; ?>)) {
    // TODO: Query
  }
}

// Checks if a name is valid (syntactically)
function validName(name) {
  if(name.length < 1) {
    return false;
  }
  var flag = true;
  // Name is valid if it does not have the following substrings:
  ['\\/', '\\.\\.', '\\$', '\\~'].every(function (entry) {
    if (name.search(entry) != -1) {
      flag = false;
      return false;
    }
    return true;
  });
  return flag;
};