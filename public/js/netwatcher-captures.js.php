<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base url for all the calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?> + '/captures/';

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
  $('#confirmDelete').click(deleteCapture);

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
    $('#captureNamePanel').removeClass('panel-primary').addClass('panel-info');
    selectedCaptureName = null;
    selectedCaptureType = null;
    $('#convertedName').val('');
    $('#newName').val('');
  } else {    
    $('#captureNamePanel').removeClass('panel-info').addClass('panel-primary');
  }
}

// Refreshes the table data
function refreshData() {
  // Set the petition url
  var capturesURL = baseURL;
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
    dataType: 'json',
    success: function (resp) {
      // Set the table data
      tableCaptures.bootstrapTable('load', resp.captures);
    },
    error: function (e) {
      // Notification of the error (timeout most of the times)
      notificationError(<?php echo '\''._('Connection error').'\''; ?>);
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
  if(selectedCaptureName == null) {
    notificationError(<?php echo '\''._('No capture is selected').'\''; ?>);
    return;
  }
  var convertedName = $('#convertedName').val();
  if(!validName(convertedName)) {
    notificationError(<?php echo '\''._('Converted capture\\\'s name is not valid').'\''; ?>);
    return;
  }
  var convertURL = baseURL;
  // Type of convert
  if(selectedCaptureType == 'simple') {
    convertURL += 'simple/pcap/';
  } else {
    convertURL += 'pcap/simple/';
  }
  convertURL += selectedCaptureName + '/' + convertedName;

  var stringOK = <?php echo '\''._('Capture converted successfully').'\''; ?>;
  var stringERR = <?php echo '\''._('Capture could not be converted').'\''; ?>;

  // Put the convert request
  $.ajax({
    type: 'PUT',
    url: convertURL,
    dataType: 'json',
    timeout: '100000',
    headers: { 'timestamp': Date.now() },
    success: function (resp) {
      handleResponse(resp, stringOK, stringERR);
    },
    error: function (e) {
      handleResponse(null, null, stringERR);
    }
  });
  $.bootstrapGrowl(<?php echo '\''._('Capture queued for conversion').'\''; ?> , {
    type: 'info'
  });
}

// Rename the selected capture
function renameCapture() {
  if(selectedCaptureName == null) {
    notificationError(<?php echo '\''._('No capture is selected').'\''; ?>);
    return;
  }
  var newName = $('#newName').val();
  if(!validName(newName)) {
    notificationError(<?php echo '\''._('New name is not valid').'\''; ?>);
    return;
  }
  var renameURL = baseURL + 'rename/' + selectedCaptureName + '/' + newName;
  var stringOK = <?php echo '\''._('Capture renamed successfully').'\''; ?>;
  var stringERR = <?php echo '\''._('Capture could not be renamed').'\''; ?>;

  // Put the rename request
  $.ajax({
    type: 'PUT',
    url: renameURL,
    dataType: 'json',
    timeout: '1500',
    headers: { 'timestamp': Date.now() },
    success: function (resp) {
      handleResponse(resp, stringOK, stringERR);
    },
    error: function (e) {
      handleResponse(null, null, stringERR);
    }
  });
}

// Delete the selected capture
function deleteCapture() {
  if(selectedCaptureName == null) {
    notificationError(<?php echo '\''._('No capture is selected').'\''; ?>);
    return;
  }

  var deleteURL = baseURL + 'delete/' + selectedCaptureName;
  var stringOK = <?php echo '\''._('Capture deleted successfully').'\''; ?>;
  var stringERR = <?php echo '\''._('Capture could not be deleted').'\''; ?>;
  console.log(deleteURL);

  // Get the new data
  $.ajax({
    type: 'DELETE',
    url: deleteURL,
    dataType: 'json',
    timeout: '1500',
    headers: { 'timestamp': Date.now() },
    success: function (resp) {
      handleResponse(resp, stringOK, stringERR);
    },
    error: function (e) {
      handleResponse(null, null, stringERR);
    }
  });
}

// Handles a petition response
function handleResponse(resp, stringOK, stringERR) {
  if(resp == null) {
    notificationError(stringERR);
  }
  else {
    try {
      if(resp.code == 'success') {
        $.bootstrapGrowl(stringOK , {
          type: 'success'
        });
      } else {
        notificationError(stringERR);
      }
    } catch (e) {
      notificationError(stringERR);
    }
  }
  toggleRightPanels(false);
  refreshData();
}

// Creates an error notification
function notificationError(stringERR) {
  // Notification of the error
  $.bootstrapGrowl(stringERR , {
   type: 'danger'
  });
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