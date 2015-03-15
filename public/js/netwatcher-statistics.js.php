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
  Statistics.init();
});

//
// Statistics
//
(function( Statistics, $, undefined ) {

  // Internal variables
  // TODO

  // Initializes the module
  Statistics.init = function() {
    // TODO
  };



}( window.Statistics = window.Statistics || {}, jQuery ));

