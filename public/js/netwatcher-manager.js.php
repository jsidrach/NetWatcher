<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base URL of the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?> + '/';

// Global vars
// Count down to refresh (in seconds)
var countdownSecs = 11;

// Sets the events
$(document).ready(function () {
  // Connection error page
  if ($('#connectionError').length) {
    countdownTimer();
    setInterval('countdownTimer()', 1000);
  }
  // HugePages off page
  else if ($('#hugePagesOff').length) {
    $('#rebootingModal').on('shown.bs.modal', rebootWebService);
  }
  // Init FPGA
  else if ($('#selectMode').length) {
    var initPlayer = $('#initPlayer');
    var initRecorder = $('#initRecorder');
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
  }
  // Configure the FPGA to start recording
  else if($('#recordCaptureNameControl').length) {
    $('#recordCaptureName').on('input', function () {
      var checkTest = checkRecordName();
      setRecordCaptureNameFeedback(checkTest);
      setRecordCaptureOK(checkTest && checkRecordBytes());
    });
    $('#recordCaptureBytes').on('input', function () {
      var checkTest = checkRecordBytes();
      setRecordCaptureBytesFeedback(checkTest);
      setRecordCaptureOK(checkTest && checkRecordName());
    });
    $('#recordCaptureStart').on('click', function () {
      startRecording();
    });
    $('#recordCaptureStart').prop('disabled', !(checkRecordBytes() && checkRecordName()));
  }
  // TODO: Rest of pages
});

//
// Connection error page
//

// Countdown to refresh the page
function countdownTimer() {
  countdownSecs--;
  $('#connectionErrorCountdown').text(countdownSecs);
  if (countdownSecs <= 0) {
    clearInterval(countdownTimer);
    location.reload(true);
  }
};

//
// HugePages off page
//

// Reboot the web service and show the progress
function rebootWebService() {
  var progressBar = $('#rebootingProgress');
  var progressLabel = $('#rebootingLabel');
  var rebootURL = baseURL + 'system/reboot';

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
          location.reload(true);
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


//
// Init player/recorder
//

// Init the FPGA (as player if player == true, recorder otherwise)
function initFPGA(player) {
  var progressBar = $('#initProgress');
  var progressLabel = $('#initLabel');
  var initURL = baseURL + (player ? 'player' : 'recorder') + '/init';

  if (player) {
    $('#initModal').find('.modal-title').text($('#initModal').find('.modal-title').text() + ' ' + <?php echo '\'' . _('as a player') . '\'' ?>);
  } else {
    $('#initModal').find('.modal-title').text($('#initModal').find('.modal-title').text() + ' ' + <?php echo '\'' . _('as a recorder') . '\'' ?>);
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
        progressLabel.text(<?php echo '\'' . _('Error sending the request') . '\'' ?>);
        setTimeout(function () {
          location.reload(true)
        }, 2000);
      }, 2000);
    }
  });
};

// Installs the driver
function installDriver(player) {
  var progressBar = $('#initProgress');
  var progressLabel = $('#initLabel');
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
          location.reload(true);
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
          location.reload(true);
        }, 2000);
      }, 2000);
    }
  });
};

//
// Configure the FPGA to start recording
//

// Sets the capture name visual feedback
function setRecordCaptureNameFeedback(value) {
  setFeedback(value, $('#recordCaptureNameControl'), $('#recordCaptureNameIcon'));
};

// Sets the bytes to capture visual feedback
function setRecordCaptureBytesFeedback(value) {
  setFeedback(value, $('#recordCaptureBytesControl'), $('#recordCaptureBytesIcon'));
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
  var name = $('#recordCaptureName').val();
  if(name.length < 1) {
    return false;
  }
  if(name.length > 50) {
    return false;
  }

  // Name regexp
  var regexpName = /[-a-zA-Z0-9_ \(\)]+\.{0,1}/g;
  while(name.match(regexpName)) {
    name = name.replace(regexpName, '');
  }
  return name.length == 0;
};

// Checks the bytes to capture
function checkRecordBytes() {
  return /^[1-9]+[0-9]*$/.test($('#recordCaptureBytes').val());
};

// Sets the OK button enabled property
function setRecordCaptureOK(value) {
  $('#recordCaptureStart').prop('disabled', !value);
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
  $('#recordCaptureName').prop('disabled', true);
  $('#recordCaptureBytes').prop('disabled', true);
  $('input:radio[name=recordCapturePort]').prop('disabled', true);
  $('input:radio[name=recordCaptureBytes]').prop('disabled', true);

  // Do the petition
  var startURL = baseURL
                   + $('#recordCaptureName').val() + '/'
                   + $('input:radio[name=recordCapturePort]:checked').val() + '/'
                   + $('#recordCaptureBytes').val() + $('input:radio[name=recordCaptureBytes]:checked').val();
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
        location.reload(true);
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


//
// Auxiliary functions
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