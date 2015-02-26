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
}

//
// HugePages off page
//

// Reboot the web service and show the progress
function rebootWebService() {
  // TODO
  // Envía request de reiniciar
  // Error: pone barra a error, espera 2 sec y reload page
  // No error: bone la barra a verde, hace peticiones hasta que alguna responda
  // Cuando una responda, reload page
}