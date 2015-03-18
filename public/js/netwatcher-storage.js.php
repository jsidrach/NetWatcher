<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base URL for the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?>;

// Sets the events
$(document).ready(function () {
  Storage.init();
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

