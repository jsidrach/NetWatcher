// Sets the events
$(document).ready(function () {
  Settings.init();
});

//
// Settings
//
(function( Settings, $, undefined ) {

  // Internal variables
  // Server IP
  var serverIp;
  // Server IP form
  var serverIpForm;
  // Server IP icon
  var serverIpIcon;

  // Initializes the module
  Settings.init = function() {
    // Set the internal variables
    serverIp = $('#serverIp');
    serverIpForm = $('#serverIpForm');
    serverIpIcon = $('#ipIcon');
    // Set the events
    serverIp.on('input', function () {
      checkIp();
    });
    checkIp();
  };

  // Checks the ip
  function checkIp() {
    var pingURL = serverIp.val() + '/info/ping';
    // Get the new data
    $.ajax({
      type: 'GET',
      url: pingURL,
      dataType: 'jsonp',
      data: '',
      timeout: '500',
      success: function (resp) {
        setServerIpInput(true);
      },
      error: function (e) {
        setServerIpInput(false);
      }
    });
  };

  // Sets the input to error or success
  function setServerIpInput(value) {
    if (value) {
      serverIpForm.removeClass('has-warning').addClass('has-success');
      serverIpIcon.removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
    } else {
      serverIpForm.removeClass('has-success').addClass('has-warning');
      serverIpIcon.removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
    }
  };

}( window.Settings = window.Settings || {}, jQuery ));