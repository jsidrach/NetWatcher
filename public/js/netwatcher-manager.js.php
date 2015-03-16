<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base URL of the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?> + '/';

// URL of the base page
var pageURL = 'manager';

// Sets the events
$(document).ready(function () {
  // Connection error page
  if ($('#connectionError').length) {
    ConnectionError.init();
  }
  // HugePages off page
  else if ($('#hugePagesOff').length) {
    HugePagesOff.init();
  }
  // Select Mode
  else if ($('#selectMode').length) {
    SelectMode.init();
  }
  // Configure the FPGA to start recording
  else if($('#recordCaptureNameControl').length) {
    ConfigureRecorder.init();
  }
  // Recording
  else if($('#recordingControl').length) {
    Recording.init();
  }
  // Configure the FPGA to reproduce a capture
  else if($('#startPlaying').length) {
    ConfigurePlayer.init();
  }
  // Playing a capture
  else if($('#playerControl').length) {
    Playing.init();
  }
});


//
// Connection error
//
(function( ConnectionError, $, undefined ) {

  // Internal variables
  // Countdown interval
  var countdownInterval;
  // Countdown text
  var countdownText;
  // Countdown to refresh in seconds
  var countdownSecs = 11;

  // Initializes the module
  ConnectionError.init = function() {
    countdownText = $('#connectionErrorCountdown');
    countdownInterval = setInterval(countdownTimer, 1000);
    countdownTimer();
  };

  // Countdown to refresh the page
  function countdownTimer() {
    countdownSecs--;
    countdownText.text(countdownSecs);
    if (countdownSecs <= 0) {
      clearInterval(countdownInterval);
      window.location = pageURL;
    }
  };

}( window.ConnectionError = window.ConnectionError || {}, jQuery ));

//
// HugePages off
//
(function( HugePagesOff, $, undefined ) {

  // Internal variables
  // Progress Bar
  var progressBar;
  // Progress Bar Label
  var progressLabel;
  // Reboot petition URL
  var rebootURL;

  // Initializes the module
  HugePagesOff.init = function() {
    progressBar = $('#rebootingProgress');
    progressLabel = $('#rebootingLabel');
    rebootURL = baseURL + 'system/reboot';
    $('#rebootingModal').on('shown.bs.modal', rebootWebService);
  };

  // Reboot the web service and show the progress
  function rebootWebService() {
    progressBar.css('width', '100%');
    // Make the reboot request
    $.ajax({
      type: 'PUT',
      url: rebootURL,
      headers: {
        'timestamp': Date.now()
      },
      dataType: 'json',
      success: function (resp) {
        // Request received. Waiting for server to boot again
        setTimeout(function () {
          // Request OK. Waiting for the server to reboot
          progressBar.removeClass('progress-bar-info').addClass('progress-bar-success');
          progressLabel.text(<?php echo '\'' . _('Waiting for the server to reboot...') . '\'' ?>);
          setTimeout(waitUntilUp(function () {
            window.location = pageURL;
          }), 10000);
        }, 2000);
      },
      error: function (e) {
        setTimeout(function () {
          // Error on the request. Error and refresh
          progressBar.removeClass('progress-bar-info').addClass('progress-bar-danger');
          progressLabel.text(<?php echo '\'' . _('Error sending the request') . '\'' ?>);
          setTimeout(function () {
            location.reload(true)
          }, 2000);
        }, 2000);
      }
    });
  };

}( window.HugePagesOff = window.HugePagesOff || {}, jQuery ));

//
// Select Mode (player/recorder)
//
(function( SelectMode, $, undefined ) {

  // Internal Variables
  // Init player button
  var initPlayer;
  // Init recorder button
  var initRecorder;
  // Progress bar
  var progressBar;
  // Progress bar label
  var progressLabel;
  // Modal's title
  var initModalTitle;

  // Initializes the module
  SelectMode.init = function() {
    // Set the variables
    initPlayer = $('#initPlayer');
    initRecorder = $('#initRecorder');
    progressBar = $('#initProgress');
    progressLabel = $('#initLabel');
    initModalTitle = $('#initModal').find('.modal-title');
    // Set the events
    initPlayer.on('click', function () {
      initFPGA(true);
    });
    initRecorder.on('click', function () {
      initFPGA(false);
    });
    // Color change on hover
    initPlayer.hover(
      function () {
        $(this).removeClass('alert-info').addClass('alert-success');
      },
      function () {
        $(this).removeClass('alert-success').addClass('alert-info');
      }
    );
    initRecorder.hover(
      function () {
        $(this).removeClass('alert-info').addClass('alert-success');
      },
      function () {
        $(this).removeClass('alert-success').addClass('alert-info');
      }
    );
  };

  // Init the FPGA (as player if player == true, recorder otherwise)
  function initFPGA(player) {
    var initURL = baseURL + (player ? 'player' : 'recorder') + '/init';
    if (player) {
      initModalTitle.text(initModalTitle.text() + ' ' + <?php echo '\'' . _('as a player') . '\'' ?>);
    } else {
      initModalTitle.text(initModalTitle.text() + ' ' + <?php echo '\'' . _('as a recorder') . '\'' ?>);
    }

    // Sending request
    progressBar.css('width', '50%');
    progressLabel.text(<?php echo '\'' . _('Programming the FPGA...') . '\'' ?>);
    // Make the init request
    $.ajax({
      type: 'POST',
      url: initURL,
      headers: {
        'timestamp': Date.now()
      },
      dataType: 'json',
      timeout: 300000,
      success: function (resp) {
        // FPGA programmed. Waiting for the server to reboot
        progressBar.css('width', '70%');
        progressLabel.text(<?php echo '\'' . _('FPGA programmed. Rebooting the system...') . '\'' ?>);
        setTimeout(function () {
          waitUntilUp(function() { installDriver(player); });
        }, 10000);
      },
      error: function (e) {
        setTimeout(function () {
          progressBar.css('width', '100%');
          progressBar.removeClass('progress-bar-info').addClass('progress-bar-danger');
          progressLabel.text(<?php echo '\'' . _('The FPGA cannot be programmed if it is active (stop the player/recorder)') . '\'' ?>);
          setTimeout(function () {
            location.reload(true)
          }, 2000);
        }, 2000);
      }
    });
  };

  // Installs the driver
  function installDriver(player) {
    var installURL = baseURL + (player ? 'player' : 'recorder') + '/install';

    // Sending the install request
    progressBar.css('width', '85%');
    progressLabel.text(<?php echo '\'' . _('Installing the driver...') . '\'' ?>);

    $.ajax({
      type: 'POST',
      url: installURL,
      headers: {
        'timestamp': Date.now()
      },
      dataType: 'json',
      timeout: 300000,
      success: function (resp) {
        setTimeout(function () {
          // FPGA driver installed      
          progressBar.css('width', '100%');
          progressBar.removeClass('progress-bar-info').addClass('progress-bar-success');
          progressLabel.text(<?php echo '\'' . _('Driver successfully installed. Refreshing...') . '\'' ?>);

          // Reload the page
          setTimeout(function () {
            window.location = pageURL;
          }, 2000);
        }, 2000);
      },
      error: function (e) {
        setTimeout(function () {
          progressBar.css('width', '100%');
          progressBar.removeClass('progress-bar-info').addClass('progress-bar-danger');
          progressLabel.text(<?php echo '\'' . _('Error sending the request') . '\'' ?>);
          // Reload the page
          setTimeout(function () {
            window.location = pageURL;
          }, 2000);
        }, 2000);
      }
    });
  };

}( window.SelectMode = window.SelectMode || {}, jQuery ));

//
// Configure the FPGA to start recording
//
(function( ConfigureRecorder, $, undefined ) {

  // Internal Variables
  // Input capture name
  var captureName;
  // Input capture bytes
  var captureBytes;
  // Input capture bytes type
  var captureBytesType;
  // Input capture port
  var capturePort;
  // Start recording button
  var startRecordingButton;
  // Input capture name feedback panel
  var captureNameControl;
  // Input capture name feedback icon
  var captureNameIcon;
  // Input capture bytes feedback panel
  var captureBytesControl;
  // Input capture name feedback icon
  var captureBytesIcon;
  // Name regexp 
  var nameRegexp;
  // Bytes regexp
  var bytesRegexp;

  // Initializes the module
  ConfigureRecorder.init = function() {
    // Set the internal variables
    captureName = $('#recordCaptureName');
    captureBytes = $('#recordCaptureBytes');
    captureBytesType = $('input:radio[name=recordCaptureBytes]');
    capturePort = $('input:radio[name=recordCapturePort]');
    startRecordingButton = $('#recordCaptureStart');
    captureNameControl = $('#recordCaptureNameControl');
    captureNameIcon = $('#recordCaptureNameIcon');
    captureBytesControl = $('#recordCaptureBytesControl');
    captureBytesIcon = $('#recordCaptureBytesIcon');
    nameRegexp = /[-a-zA-Z0-9_ \(\)]+\.{0,1}/g;
    bytesRegexp = /^[1-9]+[0-9]*$/;

    // Set the events
    captureName.on('input', function () {
      var checkTest = checkRecordName();
      setRecordCaptureNameFeedback(checkTest);
      setRecordCaptureOK(checkTest && checkRecordBytes());
    });
    captureBytes.on('input', function () {
      var checkTest = checkRecordBytes();
      setRecordCaptureBytesFeedback(checkTest);
      setRecordCaptureOK(checkTest && checkRecordName());
    });
    startRecordingButton.on('click', function () {
      startRecording();
    });
    startRecordingButton.prop('disabled', !(checkRecordBytes() && checkRecordName()));
  };

  // Sets the capture name visual feedback
  function setRecordCaptureNameFeedback(value) {
    setFeedback(value, captureNameControl, captureNameIcon);
  };

  // Sets the bytes to capture visual feedback
  function setRecordCaptureBytesFeedback(value) {
    setFeedback(value, captureBytesControl, captureBytesIcon);
  };

  // Sets the feedback of an input
  function setFeedback(value, input, icon) {
    if (value) {
      if (input.hasClass('has-error')) {
        input.removeClass('has-error');
      }
      input.addClass('has-success');
      if (icon.hasClass('glyphicon-remove')) {
        icon.removeClass('glyphicon-remove');
      }
      icon.addClass('glyphicon-ok');
    } else {
      if (input.hasClass('has-success')) {
        input.removeClass('has-success');
      }
      input.addClass('has-error');
      if (icon.hasClass('glyphicon-ok')) {
        icon.removeClass('glyphicon-ok');
      }
      icon.addClass('glyphicon-remove');
    }
  };

  // Checks the name of the capture
  function checkRecordName() {
    var name = captureName.val();
    if(name.length < 1) {
      return false;
    }
    if(name.length > 50) {
      return false;
    }
    while(name.match(nameRegexp)) {
      name = name.replace(nameRegexp, '');
    }
    return name.length == 0;
  };

  // Checks the bytes to capture
  function checkRecordBytes() {
    return bytesRegexp.test(captureBytes.val());
  };

  // Sets the OK button enabled property
  function setRecordCaptureOK(value) {
    startRecordingButton.prop('disabled', !value);
  };

  // Starts recording
  function startRecording() {
    // Checks the parameters
    if(!checkRecordName()) {
      notificationError(<?php echo '\'' . _('Invalid capture name') . '\'' ?>);
      return;
    }
    if(!checkRecordBytes()) {
      notificationError(<?php echo '\'' . _('Invalid number of bytes to record') . '\'' ?>);
      return;
    }

    // Disable the inputs
    captureName.prop('disabled', true);
    captureBytes.prop('disabled', true);
    capturePort.prop('disabled', true);
    captureBytesType.prop('disabled', true);

    // Do the petition
    var startURL = baseURL + 'recorder/start/'
                     + captureName.val() + '/'
                     + capturePort.filter(':checked').val() + '/'
                     + captureBytes.val() + captureBytesType.filter(':checked').val();
    $.ajax({
      type: 'POST',
      url: startURL,
      headers: {
        'timestamp': Date.now()
      },
      dataType: 'json',
      success: function (resp) {
        // Recording
        $.bootstrapGrowl(<?php echo '\'' . _('Recording now! Reloading...') . '\'' ?>, {
          type: 'info'
        });
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      },
      error: function (e) {
        notificationError(<?php echo '\'' . _('Invalid state or capture name already taken. Reloading...') . '\'' ?>);
        setTimeout(function () {
          location.reload(true)
        }, 3000);
      }
    });
  };

}( window.ConfigureRecorder = window.ConfigureRecorder || {}, jQuery ));

//
// Recording
//
(function( Recording, $, undefined ) {

  // Initializes the module
  Recording.init = function() {
    setRefreshRecording();
    $('#confirmStop').on('click', stopRecording);
  };

  // Internal variables
  var elapsedTime;
  var lastCapturedBytes;
  var elapsedInterval;
  var refreshInterval;

  // Sets the api calls for refreshing the info
  function setRefreshRecording() {
    // Counter refresh
    elapsedTime = parseInt($('#recordingElapsedTime').text());
    lastCapturedBytes = 0;
    elapsedInterval = setInterval(refreshElapsedTime, 1000);

    // Data refresh
    refreshInterval = setInterval(function() {
      var statusURL = baseURL + 'info/status';
      $.ajax({
        type: 'GET',
        url: statusURL,
        dataType: 'json',
        timeout: 2500,
        success: function (resp) {
          // Process new data
          if(resp.status == 'recording') {
            processRecordingData(resp);
            clearInterval(elapsedInterval);
            elapsedInterval = setInterval(refreshElapsedTime, 1000);
          }
          // Recording has ended
          else {
            $('#recordingTitle').text(<?php echo '\'' . _('Capture recording has ended') . '\'' ?>)
            $('#stopRecording').prop('disabled', true);
            $('#recordingCurrentRate').text('--');
            clearInterval(refreshInterval);
            clearInterval(elapsedInterval);
          }
        },
        error: function (e) {
          // Error on the request. Refresh
          $('#stopRecording').prop('disabled', true);
          $('#recordingCurrentRate').text('--');
          clearInterval(refreshInterval);
          clearInterval(elapsedInterval);
          notificationError(<?php echo '\'' . _('Connection Error. Reloading...') . '\'' ?>);
          setTimeout(function () {
            location.reload(true)
          }, 3000);
        }
      });
    }, 3000);
  };

  // Refresh the elapsed time
  function refreshElapsedTime() {
    $('#recordingElapsedTime').text(parseSeconds(++elapsedTime));
  };

  // Process the recording data and renders the new info in the page
  function processRecordingData(data) {
    var elapsed = data.elapsed_time - elapsedTime;
    var recordedBytes = data.bytes_captured - lastCapturedBytes;
    lastCapturedBytes = data.bytes_captured;
    elapsedTime = data.elapsed_time;
    $('#recordingName').text(data.capture);
    $('#recordingPort').text(data.port);
    $('#recordingElapsedTime').text(parseSeconds(data.elapsed_time));
    $('#recordingBytesCaptured').text(parseBytes(data.bytes_captured));
    $('#recordingBytesTotal').text(parseBytes(data.bytes_total));
    $('#recordingAverageRate').text(parseBytes(data.bytes_captured/data.elapsed_time) + '/s');
    if(recordedBytes != data.bytes_captured) {
      $('#recordingCurrentRate').text(parseBytes(recordedBytes/elapsed) + '/s');
    }
    var percent = Math.floor(100 * data.bytes_captured / data.bytes_total);
    $('#recordingLabel').text(percent + '%');
    $('#recordingProgress').css('width', percent + '%');
  };

  // Stops recording
  function stopRecording() {
    $('#recordingTitle').text(<?php echo '\'' . _('Stopping...') . '\'' ?>)
    $('#stopRecording').prop('disabled', true);
    $('#recordingCurrentRate').text('--');
    clearInterval(refreshInterval);
    clearInterval(elapsedInterval);
    var stopURL = baseURL + 'recorder/stop';
    $.ajax({
      type: 'POST',
      url: stopURL,
      dataType: 'json',
      headers: {
      'timestamp': Date.now()
      },
      success: function (resp) {
        $('#recordingTitle').text(<?php echo '\'' . _('Capture recording has been stopped. Reloading....') . '\'' ?>)
        setTimeout(function () {
          location.reload(true)
        }, 3000);
      },
      error: function (e) {
        // Error on the request. Refresh
        notificationError(<?php echo '\'' . _('Connection Error. Reloading...') . '\'' ?>);
        setTimeout(function () {
          location.reload(true)
        }, 3000);
      }
    });
  };

  // Parses a number of bytes into a string
  function parseBytes(bytes) {
    var suffix = '';
    ['K', 'M', 'G'].forEach(function(scale) {
      if(bytes >= 1024) {
        suffix = scale;
        bytes = bytes/1024;
      }
    });
    return bytes.toFixed(2) + ' ' + suffix + 'B';
  };

  // Parses a number of seconds into a string
  function parseSeconds(seconds) {
    var date = '';
    [86400, 3600, 60, 1].forEach(function(scale) {
      var digits = Math.floor(seconds/scale);
      if((date != '') || (seconds >= scale)) {
        date = date + (digits < 10 ? '0' + digits : digits) + ':';
        seconds = seconds % scale;
      }
    });
    return date.slice(0, -1);
  };

}( window.Recording = window.Recording || {}, jQuery ));

//
// Configure the FPGA to reproduce a capture
//
(function( ConfigurePlayer, $, undefined ) {

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
  // Refresh button
  var refreshButton;
  // TODO: Add the rest (form controls?)

  // Initializes the module
  ConfigurePlayer.init = function() {
    // Resize the table headers on window resize
    $(window).on('resize', function () {
      tableCaptures.bootstrapTable('resetView');
    });
    // Set the internal variables
    capturesURL = baseURL + 'captures/simple';
    tableCaptures = $('#tableCaptures');
    noCaptureText = $('#captureName').text();
    captureName = $('#captureName');
    captureNamePanel = $('#captureNamePanel');
    autoRefresh = $('#autoRefresh');
    refreshButton = $('button[name="refresh"]');
    // Disable right panels
    toggleRightPanels(false);
    // Set AutoRefresh with the check button
    autoRefresh.change(autoRefreshHandler);    
    // Refresh button
    refreshButton.off('click').on('click', refreshData);
    // Select row from captures table
    tableCaptures.on('click', 'tbody tr', selectCapture);

    // Enable the buttons only when the input is OK
    // TODO

    // Initial refresh of data
    refreshData();
  };

  // Enables/Disables the right panels
  function toggleRightPanels(value) {
    // TODO Add more
    if (!value) {
      captureName.text(noCaptureText);
      captureNamePanel.removeClass('panel-primary').addClass('panel-info');
      selectedCaptureName = null;
      selectedCaptureType = null;
    } else {
      captureNamePanel.removeClass('panel-info').addClass('panel-primary');
    }
  };

  // Refreshes the table data
  function refreshData() {
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

}( window.ConfigurePlayer = window.ConfigurePlayer || {}, jQuery ));

//
// Playing a capture
//
(function( Playing, $, undefined ) {

  // Initializes the module
  Playing.init = function() {
    // TODO
  };

}( window.Playing = window.Playing || {}, jQuery ));

//
// Auxiliary common functions
//

// Waits until the server is up again
function waitUntilUp(callback) {
  var pingURL = baseURL + 'info/ping';
  var timer = setInterval(function () {
    $.ajax({
      type: 'GET',
      url: pingURL,
      dataType: 'json',
      timeout: 1000,
      success: function (resp) {
        // Server up. Callback
        clearInterval(timer);
        callback();
      }
    });
  }, 3000);
};

// Creates an error notification
function notificationError(stringERR) {
  // Notification of the error
  $.bootstrapGrowl(stringERR, {
    type: 'danger'
  });
};