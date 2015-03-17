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
  else if($('#playingControl').length) {
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
          setTimeout(Common.waitUntilUp(function () {
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
            window.location = pageURL;
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
          Common.waitUntilUp(function() { installDriver(player); });
        }, 10000);
      },
      error: function (e) {
        setTimeout(function () {
          progressBar.css('width', '100%');
          progressBar.removeClass('progress-bar-info').addClass('progress-bar-danger');
          progressLabel.text(<?php echo '\'' . _('The FPGA cannot be programmed if it is active (stop the player/recorder)') . '\'' ?>);
          setTimeout(function () {
            window.location = pageURL;
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
    startRecordingButton.on('click', startRecording);
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
      input.removeClass('has-error glyphicon-remove').addClass('has-success glyphicon-ok');
    } else {
      input.removeClass('has-success glyphicon-ok').addClass('has-error glyphicon-remove');
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
      Common.notificationError(<?php echo '\'' . _('Invalid capture name') . '\'' ?>);
      return;
    }
    if(!checkRecordBytes()) {
      Common.notificationError(<?php echo '\'' . _('Invalid number of bytes to record') . '\'' ?>);
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
        Common.notificationError(<?php echo '\'' . _('Invalid state or capture name already taken. Reloading...') . '\'' ?>);
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      }
    });
  };

}( window.ConfigureRecorder = window.ConfigureRecorder || {}, jQuery ));

//
// Recording
//
(function( Recording, $, undefined ) {

  // Internal variables
  // Title of the heading
  var recordingTitle;
  // Elapsed time label
  var elapsedTimeLabel;
  // Name of the capture
  var recordingName;
  // Port
  var recordingPort;
  // Bytes captured label
  var recordingBytesCaptured;
  // Bytes total label
  var recordingBytesTotal;
  // Average rate label
  var recordingAverageRate; 
  // Current rate label
  var currentRate;
  // % of the progress bar
  var recordingLabel;
  // Progress bar
  var recordingProgress;
  // Stop recording button
  var stopRecordingButton;
  // Confirm stop recording button
  var confirmStopRecording;
  // Elapsed time in seconds
  var elapsedTime;
  // Last request captured bytes
  var lastCapturedBytes;
  // Elapsed time clock handler
  var elapsedInterval;
  // Refresh interval handler
  var refreshInterval;

  // Initializes the module
  Recording.init = function() {
    // Set the internal variables
    recordingTitle = $('#recordingTitle');
    elapsedTimeLabel = $('#recordingElapsedTime');
    recordingName = $('#recordingName');
    recordingPort = $('#recordingPort');
    recordingBytesCaptured = $('#recordingBytesCaptured');
    recordingBytesTotal = $('#recordingBytesTotal');
    recordingAverageRate = $('#recordingAverageRate');
    currentRate = $('#recordingCurrentRate');
    recordingLabel = $('#recordingLabel');
    recordingProgress = $('#recordingProgress');
    stopRecording = $('#stopRecording');
    confirmStopRecording = $('#confirmStop');

    // Set the events
    setRefreshRecording();
    confirmStopRecording.on('click', stopRecording);
  };

  // Sets the api calls for refreshing the info
  function setRefreshRecording() {
    // Counter refresh
    elapsedTime = parseInt(elapsedTimeLabel.text());
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
            recordingTitle.text(<?php echo '\'' . _('Capture recording has ended') . '\'' ?>)
            stopRecordingButton.prop('disabled', true);
            currentRate.text('--');
            clearInterval(refreshInterval);
            clearInterval(elapsedInterval);
          }
        },
        error: function (e) {
          // Error on the request. Refresh
          stopRecordingButton.prop('disabled', true);
          currentRate.text('--');
          clearInterval(refreshInterval);
          clearInterval(elapsedInterval);
          Common.notificationError(<?php echo '\'' . _('Connection Error. Reloading...') . '\'' ?>);
          setTimeout(function () {
            window.location = pageURL;
          }, 3000);
        }
      });
    }, 3000);
  };

  // Refresh the elapsed time
  function refreshElapsedTime() {
    elapsedTimeLabel.text(Common.parseSeconds(++elapsedTime));
  };

  // Process the recording data and renders the new info in the page
  function processRecordingData(data) {
    var elapsed = data.elapsed_time - elapsedTime;
    var recordedBytes = data.bytes_captured - lastCapturedBytes;
    lastCapturedBytes = data.bytes_captured;
    elapsedTime = data.elapsed_time;
    recordingName.text(data.capture);
    recordingPort.text(data.port);
    elapsedTimeLabel.text(Common.parseSeconds(data.elapsed_time));
    recordingBytesCaptured.text(Common.parseBytes(data.bytes_captured));
    recordingBytesTotal.text(Common.parseBytes(data.bytes_total));
    recordingAverageRate.text(Common.parseBytes(data.bytes_captured/data.elapsed_time) + '/s');
    if(recordedBytes != data.bytes_captured) {
      currentRate.text(Common.parseBytes(recordedBytes/elapsed) + '/s');
    }
    var percent = Math.floor(100 * data.bytes_captured / data.bytes_total);
    recordingLabel.text(percent + '%');
    recordingProgress.css('width', percent + '%');
  };

  // Stops recording
  function stopRecording() {
    recordingTitle.text(<?php echo '\'' . _('Stopping...') . '\'' ?>)
    stopRecordingButton.prop('disabled', true);
    currentRate.text('--');
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
        recordingTitle.text(<?php echo '\'' . _('Capture recording has been stopped. Reloading....') . '\'' ?>)
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      },
      error: function (e) {
        // Error on the request. Refresh
        Common.notificationError(<?php echo '\'' . _('Connection Error. Reloading...') . '\'' ?>);
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      }
    });
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
  // Loop checkbox
  var loopCheckbox;
  // Output mask
  var outputMask;
  // Interframe gap checkbox
  var ifgCheckbox;
  // Interframe gap input
  var ifgInput;
  // Interframe gap input control
  var ifgControl;
  // Interframe gap input control icon
  var ifgIcon;
  // Interframe gap regexp
  var ifgRegexp;
  // Start button
  var startButton;

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
    loopCheckbox = $('#playLoop');
    outputMask = $('input:radio[name=playCaptureMask]');
    ifgCheckbox = $('#playIFGCheck');
    ifgInput = $('#playIFG');
    ifgControl = $('#playIFGControl');
    ifgIcon = $('#playIFGIcon');
    ifgRegexp = /^[1-9]+[0-9]*$/;
    startButton = $('#startPlayingGO');
    // Disable right panels
    toggleRightPanels(false);
    ifgInput.prop('disabled', true);
    // Set AutoRefresh with the check button
    autoRefresh.change(autoRefreshHandler);    
    // Refresh button
    refreshButton.off('click').on('click', refreshData);
    // Select row from captures table
    tableCaptures.on('click', 'tbody tr', selectCapture);

    // Right panel events
    ifgInput.on('input', ifgInputValidator);
    ifgCheckbox.on('click', ifgCheckboxClick);
    startButton.on('click', startPlaying);

    // Initial refresh of data
    refreshData();
  };

  // Enables/Disables the right panels
  function toggleRightPanels(value) {
    loopCheckbox.prop('disabled', !value);
    outputMask.prop('disabled', !value);
    ifgCheckbox.prop('disabled', !value);
    startButton.prop('disabled', !value);
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
        Common.notificationError(<?php echo '\'' . _('Connection error') . '\''; ?>);
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

  // Turns the input on/off
  function ifgCheckboxClick() {
    var checked = ifgCheckbox.prop('checked');
    ifgInput.prop('disabled', !checked);
    startButton.prop('disabled', checked);
    if(!checked) {
      ifgInput.val('');      
      ifgControl.removeClass('has-success has-error');
      ifgIcon.removeClass('glyphicon-ok glyphicon-remove has-success has-error');
    }
  };

  // Checks the interframe gap input
  function ifgInputValidator() {
    if(ifgRegexp.test(ifgInput.val())) {
      ifgControl.removeClass('has-error').addClass('has-success');
      ifgIcon.removeClass('glyphicon-remove has-error').addClass('glyphicon-ok has-success');
      startButton.prop('disabled', false);
    } else {
      ifgControl.removeClass('has-success').addClass('has-error');
      ifgIcon.removeClass('glyphicon-ok has-success').addClass('glyphicon-remove has-error');
      startButton.prop('disabled', true);
    }
  };

  // Start playing a capture
  function startPlaying() {
    // Parameters should be valid at this point since start button is disabled until everything is allright
    // Disable the inputs
    loopCheckbox.prop('disabled', true);
    outputMask.prop('disabled', true);
    ifgCheckbox.prop('disabled', true);
    ifgInput.prop('disabled', true);
    startButton.prop('disabled', true);
    tableCaptures.off('click');

    // Petition
    // player/start/capturename/mask/ifg
    // player/start/capturename/mask/ifg/loop
    var ifg = (ifgCheckbox.prop('checked') ? ifgInput.val() : 0);
    var loop = loopCheckbox.prop('checked') ? 'loop/' : '';
    var startURL = baseURL + 'player/start/'
                     + loop
                     + selectedCaptureName + '/'
                     + outputMask.filter(':checked').val() + '/'
                     + ifg;
    $.ajax({
      type: 'POST',
      url: startURL,
      headers: {
        'timestamp': Date.now()
      },
      dataType: 'json',
      success: function (resp) {
        // Recording
        $.bootstrapGrowl(<?php echo '\'' . _('Playing now! Reloading...') . '\'' ?>, {
          type: 'info'
        });
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      },
      error: function (e) {
        Common.notificationError(<?php echo '\'' . _('Invalid state or capture. Reloading...') . '\'' ?>);
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      }
    });
  };

}( window.ConfigurePlayer = window.ConfigurePlayer || {}, jQuery ));

//
// Playing a capture
//
(function( Playing, $, undefined ) {

  // Internal variables
  // Title of the heading
  var playingTitle;
  // Elapsed time label
  var elapsedTimeLabel;
  // Name of the capture
  var playingName;
  // Capture size label
  var playingSize;
  // Capture date label
  var playingDate;
  // Packets sent label
  var playingPacketsSent;
  // Loop label
  var playingLoop;
  // Interframe Gap label
  var playingIFG;
  // Mask label
  var playingMask;
  // Stop recording button
  var stopPlayingButton;
  // Confirm stop playing button
  var confirmStopPlaying;
  // Elapsed time in seconds
  var elapsedTime;
  // Elapsed time clock handler
  var elapsedInterval;
  // Refresh interval handler
  var refreshInterval;

  // Initializes the module
  Playing.init = function() {
    // Set the internal variables
    playingTitle = $('#playingTitle');
    elapsedTimeLabel = $('#playingElapsedTime');
    playingName = $('#playingName');
    playingSize = $('#playingSize');
    playingDate = $('#playingDate');
    playingPacketsSent = $('#playingPacketsSent');
    playingLoop = $('#playingLoop');
    playingIFG = $('#playingIFG');
    playingMask = $('#playingMask');
    stopPlayingButton = $('#stopPlaying');
    confirmStopPlaying = $('#confirmStop');

    // Set the events
    setRefreshPlaying();
    confirmStopPlaying.on('click', stopPlaying);
  };

  // Sets the api calls for refreshing the info
  function setRefreshPlaying() {
    // Counter refresh
    elapsedTime = parseInt(elapsedTimeLabel.text());
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
            processPlayingData(resp);
            clearInterval(elapsedInterval);
            elapsedInterval = setInterval(refreshElapsedTime, 1000);
          }
          // Recording has ended
          else {
            playingTitle.text(<?php echo '\'' . _('Capture reproduction has ended') . '\'' ?>)
            stopPlayingButton.prop('disabled', true);
            clearInterval(refreshInterval);
            clearInterval(elapsedInterval);
          }
        },
        error: function (e) {
          // Error on the request. Refresh
          stopPlayingButton.prop('disabled', true);
          clearInterval(refreshInterval);
          clearInterval(elapsedInterval);
          Common.notificationError(<?php echo '\'' . _('Connection Error. Reloading...') . '\'' ?>);
          setTimeout(function () {
            window.location = pageURL;
          }, 3000);
        }
      });
    }, 3000);
  };

  // Refresh the elapsed time
  function refreshElapsedTime() {
    elapsedTimeLabel.text(Common.parseSeconds(++elapsedTime));
  };

  // Process the playing data and renders the new info in the page
  function processPlayingData(data) {
    elapsedTime = data.elapsed_time;
    playingName.text(data.capture);
    playingDate.text(data.date);
    playingSize.text(Common.parseBytes(data.size));
    playingPacketsSent.text(data.packets_sent);
    elapsedTimeLabel.text(Common.parseSeconds(data.elapsed_time));
    playingIFG.text((data.interframe_gap == 0) ? <?php echo '\'' . _('Original captured rate') . '\'' ?> : data.interframe_gap);
    playingLoop.text(data.loop ? <?php echo '\'' . _('Yes') . '\'' ?> : <?php echo '\'' . _('No') . '\'' ?> );
    var mask = '';
    for (var i = 0; i <= data.mask; i++) {
      mask = mask + i + '-';
    }
    playingMask.text(mask.slice(0, -1));
  };

  // Stops playing
  function stopPlaying() {
    playingTitle.text(<?php echo '\'' . _('Stopping...') . '\'' ?>)
    stopPlayingButton.prop('disabled', true);
    clearInterval(refreshInterval);
    clearInterval(elapsedInterval);
    var stopURL = baseURL + 'player/stop';
    $.ajax({
      type: 'POST',
      url: stopURL,
      dataType: 'json',
      headers: {
      'timestamp': Date.now()
      },
      success: function (resp) {
        playingTitle.text(<?php echo '\'' . _('Capture reproduction has been stopped. Reloading....') . '\'' ?>)
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      },
      error: function (e) {
        // Error on the request. Refresh
        Common.notificationError(<?php echo '\'' . _('Connection Error. Reloading...') . '\'' ?>);
        setTimeout(function () {
          window.location = pageURL;
        }, 3000);
      }
    });
  };

}( window.Playing = window.Playing || {}, jQuery ));

//
// Auxiliary Common module
//
(function( Common, $, undefined ) {

  // Parses a number of bytes into a string
  Common.parseBytes = function (bytes) {
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
  Common.parseSeconds = function (seconds) {
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

  // Waits until the server is up again
  Common.waitUntilUp = function (callback) {
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
  Common.notificationError = function (stringERR) {
    // Notification of the error
    $.bootstrapGrowl(stringERR, {
      type: 'danger'
    });
  };

}( window.Common = window.Common || {}, jQuery ));