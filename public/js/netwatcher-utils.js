// NetWatcher Client-Javascript Utils

// Sets the events
$(document).ready(function () {
  // Ajax Queue Handler
  AjaxQueueHandler.init();
});

//
// Auxiliary Common module
//
(function( Common, $, undefined ) {

  // Parses a number of bytes into a string
  Common.parseBytes = function (bytes) {
    var suffix = '';
    ['K', 'M', 'G', 'T'].forEach(function(scale) {
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
    $.notify({
      message: stringERR 
    },{
      type: 'danger'
    });
  };

}( window.Common = window.Common || {}, jQuery ));

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
  function abortAll() {
    $.each(pool, function(idx, jqXHR) {
      jqXHR.abort();
    });
    pool = [];
  };

}( window.AjaxQueueHandler = window.AjaxQueueHandler || {}, jQuery ));