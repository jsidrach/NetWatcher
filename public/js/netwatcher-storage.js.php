<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Required: Common, AjaxQueueHandler

// Base URL for the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?>;

// Sets the events
$(document).ready(function () {
  // Storage module
  Storage.init();

  // Chart.js configuration
  Chart.defaults.global.responsive = true;
});

//
// Storage
//
(function( Storage, $, undefined ) {

  // Internal variables
  // TODO

  // Initializes the module
  Storage.init = function() {
    // TODO
  };

  // Functions
  // TODO
  
}( window.Storage = window.Storage || {}, jQuery ));