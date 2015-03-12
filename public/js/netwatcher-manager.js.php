<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base URL of the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?> + '/';

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
  // Recording
  else if($('#recordingControl').length) {
    setRefreshRecording();
    // TODO: Stop button
  }
  // TODO: Rest of pages
});

//
// Connection error page
//

// Global vars for the recording page (error_ prefix)
// Count down to refresh (in seconds)
var error_countdownSecs = 11;

// Countdown to refresh the page
function countdownTimer() {
  error_countdownSecs--;
  $('#connectionErrorCountdown').text(error_countdownSecs);
  if (error_countdownSecs <= 0) {
    clearInterval(error_countdownSecs);
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
// Recording
//

// Global vars for the recording page (recording_ prefix)
var recording_elapsedTime;

// Sets the api calls for refreshing the info
function setRefreshRecording() {
  // Counter refresh
  var elapsedTime = setInterval(refreshElapsedTime, 1000);

  // Data refresh
  var refreshInterval = setInterval(function() {
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
          clearInterval(elapsedTime);
          elapsedTime = setInterval(refreshElapsedTime, 1000);
        }
        // Recording has ended
        else {
          $('#recordingTitle').text(<?php echo '\'' . _('Capture recorded successfully') . '\'' ?>)
          $('#stopRecording').prop('disabled', true);
          $('#recordingCurrentRate').text('--');
          clearInterval(refreshInterval);
          clearInterval(elapsedTime);
        }
      },
      error: function (e) {
        // Error on the request. Refresh
        $('#stopRecording').prop('disabled', true);
        $('#recordingCurrentRate').text('--');
        clearInterval(refreshInterval);
        clearInterval(elapsedTime);
        notificationError(<?php echo '\'' . _('Connection Error. Reloading...') . '\'' ?>);
        setTimeout(function () {
          location.reload(true)
        }, 3000);
      }
    });
  }, 3000);
};

function refreshElapsedTime() {
  // TODO
};

function processRecordingData(data) {
  // TODO
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

/*
  - Cuando llega una respuesta del status:
    - Si es del estado bueno:
      - Actualizar nombre, puerto, elapsed Time, captured bytes, total, average
      - Con la diferencia de tiempos y de bytes, calcular la tasa inmediata
    - Si el estado no es bueno:
      - Notificacion de finalizacion
      - Tasa inmediata --
      - Parar elapsed time
      - disable stop
      - Parar de actualizar
    - Si connection error:
      - Notificacion. Reload en 10, 9, ...
      - Parar elapsed time
      - disable stop
  - Botón de stop? Controlar el modal

      width: 250,
    delay: 4000,
    allow_dismiss: true,
  */

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