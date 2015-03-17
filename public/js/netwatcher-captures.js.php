<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base url for all the calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?>;

// Resize the table headers on window resize
$(window).on('resize', function () {
  tableCaptures.bootstrapTable('resetView');
});

// Sets the events
$(document).ready(function () {
  Captures.init();
});

//
// Captures
//
(function( Captures, $, undefined ) {

  // Internal variables
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
  // Auto refresh checkbox
  var autoRefresh;
  // Refresh petition url
  var capturesURL;
  // Capture name
  var captureName;
  // Capture name panel
  var captureNamePanel;
  // All captures radio button
  var allCaptures;
  // Simple captures radio button
  var simpleCaptures;
  // Pcap captures radio button
  var pcapCaptures;
  // Refresh button
  var refreshButton;
    // Convert button
  var convertButton;
  // Rename button
  var renameButton;
  // Delete button
  var deleteButton;
  // Confirm delete button
  var confirmDeleteButton;
  // New name input
  var newNameInput;
  // Converted name input
  var convertedNameInput;
  // Rename input feedback
  var renameInputFeedback;
  // Convert input feedback
  var convertInputFeedback;
  // Regexp for the name
  var regexpName;

  // Initializes the module
  Captures.init = function() {
    // Resize the table headers on window resize
    $(window).on('resize', function () {
      tableCaptures.bootstrapTable('resetView');
    });
    // Set the internal variables
    capturesURL = baseURL + '/captures/';
    tableCaptures = $('#tableCaptures');
    noCaptureText = $('#captureName').text();
    captureName = $('#captureName');
    captureNamePanel = $('#captureNamePanel');
    autoRefresh = $('#autoRefresh');
    allCaptures = $('#allCaptures');
    simpleCaptures = $('#simpleCaptures');
    pcapCaptures = $('#pcapCaptures');
    refreshButton = $('button[name="refresh"]');
    convertButton = $('#convertOK');
    renameButton = $('#renameOK');
    deleteButton = $('#deleteCapture');
    confirmDeleteButton = $('#confirmDelete');
    newNameInput = $('#newName');
    convertedNameInput = $('#convertedName');
    renameInputFeedback = $('#renameInputFeedback');
    convertInputFeedback = $('#convertInputFeedback');
    regexpName = /[-a-zA-Z0-9_ \(\)]+\.{0,1}/g;

    // Disable right panels
    toggleRightPanels(false);
    // Set AutoRefresh with the check button
    autoRefresh.change(autoRefreshHandler);    
    // Refresh button
    refreshButton.off('click').on('click', refreshData);
    // Refresh when radio buttons are pressed
    allCaptures.click(refreshData);
    simpleCaptures.click(refreshData);
    pcapCaptures.click(refreshData);
    // Select row from captures table
    tableCaptures.on('click', 'tbody tr', selectCapture);
    // Convert capture
    convertButton.click(convertCapture);
    // Rename capture
    renameButton.click(renameCapture);
    // Delete capture
    confirmDeleteButton.click(deleteCapture);

    // Enable the buttons only when the input is OK
    newNameInput.on('input', function() {
      var valid = validName($(this).val());
      renameButton.prop('disabled', !valid);
      setFeedback(valid, renameInputFeedback);
    });
    convertedNameInput.on('input', function() {
      var valid = validName($(this).val());
      convertButton.prop('disabled', !valid);
      setFeedback(valid, convertInputFeedback);
    });
    convertButton.attr('disabled', true);
    renameButton.attr('disabled', true);

    // Initial refresh of data
    refreshData();
  };

  // Enables/Disables the right panels
  function toggleRightPanels(value) {
    newNameInput.attr('disabled', !value);
    convertedNameInput.attr('disabled', !value);
    deleteButton.attr('disabled', !value);
    if (!value) {
      captureName.text(noCaptureText);
      captureNamePanel.removeClass('panel-primary').addClass('panel-info');
      selectedCaptureName = null;
      selectedCaptureType = null;
      newNameInput.val('');
      convertedNameInput.val('');
      convertButton.attr('disabled', true);
      renameButton.attr('disabled', true);
      disableFeedback(renameInputFeedback);
      disableFeedback(convertInputFeedback);
    } else {
      captureNamePanel.removeClass('panel-info').addClass('panel-primary');
    }
  };

  // Refreshes the table data
  function refreshData() {
    // Set the petition url
    var refreshURL = capturesURL;
    if (simpleCaptures.is(':checked')) {
      refreshURL += 'simple';
    } else if (pcapCaptures.is(':checked')) {
      refreshURL += 'pcap';
    } else {
      refreshURL += 'all';
    }

    // Get the new data
    $.ajax({
      type: 'GET',
      url: refreshURL,
      dataType: 'json',
      success: function (resp) {
        // Set the table data
        tableCaptures.bootstrapTable('load', resp.captures);
      },
      error: function (e) {
        // Notification of the error (timeout most of the times)
        notificationError(<?php echo '\'' . _('Connection error') . '\''; ?>);
        setAutoRefresh(false);
      }
    });
  };

  // AutoRefresh check button handler
  function autoRefreshHandler() {
    if (autoRefresh.is(':checked')) {
      setAutoRefresh(true);
    } else {
      setAutoRefresh(false);
    }
  };

  // Sets the autorefresh
  function setAutoRefresh(value) {
    if(interval != null) {
      clearInterval(interval);
    }
    if (value) {
      refreshData();
      interval = setInterval(refreshData, 5000);
    }
    autoRefresh.prop('checked', value);
  };

  // Selects a capture from the table
  function selectCapture() {
    selectedCaptureName = $(this).find('td:nth-child(1)').text();
    selectedCaptureType = $(this).find('td:nth-child(2)').text();
    // Only select proper captures
    if(!selectedCaptureType.length) {
      return;
    }
    captureName.text(selectedCaptureName);
    toggleRightPanels(true);
  };

  // Convert the selected capture
  function convertCapture() {
    if (selectedCaptureName == null) {
      notificationError(<?php echo '\'' . _('No capture is selected') . '\''; ?>);
      return;
    }
    var convertedName = convertedNameInput.val();
    if (!validName(convertedName)) {
      notificationError(<?php echo '\'' . _('Converted capture\\\'s name is not valid') . '\''; ?>);
      return;
    }
    var convertURL = capturesURL;
    // Type of convert
    if (selectedCaptureType == 'simple') {
      convertURL += 'simple/pcap/';
    } else {
      convertURL += 'pcap/simple/';
    }
    convertURL += selectedCaptureName + '/' + convertedName;

    var stringOK = <?php echo '\'' . _('Capture converted successfully') . '\''; ?>;
    var stringERR = <?php echo '\'' . _('Capture could not be converted') . '\''; ?>;

    // Put the convert request
    $.ajax({
      type: 'PUT',
      url: convertURL,
      dataType: 'json',
      timeout: '100000',
      headers: {
        'timestamp': Date.now()
      },
      success: function (resp) {
        handleResponse(resp, stringOK, stringERR);
      },
      error: function (e) {
        handleResponse(null, null, stringERR);
      }
    });
    $.bootstrapGrowl(<?php echo '\'' . _('Capture queued for conversion') . '\''; ?>, {
        type: 'info'
      });
  };

  // Rename the selected capture
  function renameCapture() {
    if (selectedCaptureName == null) {
      notificationError( <?php echo '\'' . _('No capture is selected') . '\''; ?>);
      return;
    }
    var newName = newNameInput.val();
    if (!validName(newName)) {
      notificationError(<?php echo '\'' . _('New name is not valid') . '\''; ?>);
      return;
    }
    var renameURL = capturesURL + 'rename/' + selectedCaptureName + '/' + newName;
    var stringOK = <?php echo '\'' . _('Capture renamed successfully') . '\''; ?>;
    var stringERR = <?php echo '\'' . _('Capture could not be renamed (it may be in use)') . '\''; ?>;

    // Put the rename request
    $.ajax({
      type: 'PUT',
      url: renameURL,
      dataType: 'json',
      timeout: '1500',
      headers: {
        'timestamp': Date.now()
      },
      success: function (resp) {
        handleResponse(resp, stringOK, stringERR);
      },
      error: function (e) {
        handleResponse(null, null, stringERR);
      }
    });
  };

  // Delete the selected capture
  function deleteCapture() {
    if (selectedCaptureName == null) {
      notificationError( <?php echo '\'' . _('No capture is selected') . '\''; ?>);
      return;
    }

    var deleteURL = capturesURL + 'delete/' + selectedCaptureName;
    var stringOK = <?php echo '\'' . _('Capture deleted successfully') . '\''; ?>;
    var stringERR = <?php echo '\'' . _('Capture could not be deleted (it may be in use)') . '\''; ?>;

    // Get the new data
    $.ajax({
      type: 'DELETE',
      url: deleteURL,
      dataType: 'json',
      timeout: '1500',
      headers: {
        'timestamp': Date.now()
      },
      success: function (resp) {
        handleResponse(resp, stringOK, stringERR);
      },
      error: function (e) {
        handleResponse(null, null, stringERR);
      }
    });
  };

  // Handles a petition response
  function handleResponse(resp, stringOK, stringERR) {
    if (resp == null) {
      notificationError(stringERR);
    } else {
      try {
        if (resp.code == 'success') {
          $.bootstrapGrowl(stringOK, {
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
  };

  // Creates an error notification
  function notificationError(stringERR) {
    // Notification of the error
    $.bootstrapGrowl(stringERR, {
      type: 'danger'
    });
  };

  // Sets the feedback of an input
  function setFeedback(value, input) {
    if (value) {
      input.removeClass('has-error').addClass('has-success');
    } else {
      input.removeClass('has-success').addClass('has-error');
    }
  };

  // Disables the feedback of an input
  function disableFeedback(input) {
    input.removeClass('has-error has-success');
  };

  // Checks if a name is valid (syntactically)
  function validName(name) {
    if(name.length < 1) {
      return false;
    }
    if(name.length > 50) {
      return false;
    }
    while(name.match(regexpName)) {
      name = name.replace(regexpName, '');
    }
    return name.length == 0;
  };

}( window.Captures = window.Captures || {}, jQuery ));