<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once('../../lib/vendor/autoload.php');
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
  if($('#connectionError').length) {
    countdownTimer();
    setInterval('countdownTimer()', 1000);
  }
  // HugePages off page
  else if($('#hugePagesOff').length) {
    $('#rebootingModal').on('shown.bs.modal', rebootWebService);
  }
});

//
// Connection error page
//

// Countdown to refresh the page
function countdownTimer() {
  countdownSecs--;
  $('#connectionErrorCountdown').text(countdownSecs);
  if(countdownSecs <= 0) {
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

  // Make the reboot request
  $.ajax({
    type: 'PUT',
    url: rebootURL,
    headers: { 'timestamp': Date.now() },
    dataType: 'json',
    success: function (resp) {
      // Request received. Waiting for server to boot again
      setTimeout(function () {
        // Request OK. Waiting for the server to reboot
        progressBar.removeClass('progress-bar-info').addClass('progress-bar-success');
        progressLabel.text(<?php echo '\'' . _('Waiting for the server to reboot...') . '\'' ?>);
        setTimeout(waitUntilUp(function() {location.reload(true);}), 7500);
      }, 2000);
    },
    error: function (e) {
      setTimeout(function () {
        // Error on the request. Error and refresh
        progressBar.removeClass('progress-bar-info').addClass('progress-bar-danger');
        progressLabel.text(<?php echo '\'' . _('Error sending request') . '\'' ?>);
        setTimeout(function () {location.reload(true)}, 2000);
      }, 2000);
    }
  });
};

// Waits until the server is up again
function waitUntilUp(callback) {
  var pingURL = baseURL + 'info/ping';
  setInterval(function() {
    $.ajax({
      type: 'GET',
      url: pingURL,
      dataType: 'json',
      success: function (resp) {
        // Server up. Callback
        callback();
      }
    });
  }, 2000);
};


/*
Enviar peticion /player/init | recorder/init
Programando el <player/recorder>... <AUMENTAR EL TIMEOUT>
Reiniciando el sistema... > Esperar 10 segundos, empezar a hacer pings
Cuando se responda el ping
Enviar peticion /driver/install
Instalando el driver...
OK
Refresh
*/