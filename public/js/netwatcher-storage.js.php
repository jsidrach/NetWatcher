<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once ('../../vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base URL for the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?>;

// Sets the events
$(document).ready(function () {
  // Ajax Queue Handler
  AjaxQueueHandler.init();

  // Storage module
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

//
// Ajax Queue Handler
//
(function( AjaxQueueHandler, $, undefined ) {

  // Internal variables
  // Pool of requests
  var pool;
  // User left the page
  var userLeftPage;

  // Initializes the module
  AjaxQueueHandler.init = function() {
    pool = [];
    userLeftPage = false;
    $.ajaxSetup({
      beforeSend: function(jqXHR) {
        pool.push(jqXHR);
      },
      complete: function(jqXHR) {
        var index = pool.indexOf(jqXHR);
        if (index > -1) {
          pool.splice(index, 1);
        }
      }
    });
    $(window).on('beforeunload', function () {
      userLeftPage = true;
      abortAll();
    });
  };

  // User left the page
  AjaxQueueHandler.userLeft = function() {
    return userLeftPage;
  };

  // Abort all the ajax requests
  function abortAll () {
    $.each(pool, function(idx, jqXHR) {
      jqXHR.abort();
    });
    pool = [];
  };

}( window.AjaxQueueHandler = window.AjaxQueueHandler || {}, jQuery ));