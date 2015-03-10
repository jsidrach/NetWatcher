// Sets the events
$(document).ready(function () {
  $('#serverIp').on('input', function () {
    checkIp();
  });
  checkIp();
});

// Checks the ip
function checkIp() {
  var pingURL = $('#serverIp').val() + '/info/ping';
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
}

// Sets the input to error or success
function setServerIpInput(value) {
  var serverIpForm = $('#serverIpForm');
  var serverIpIcon = $('#ipIcon');
  if (value) {
    if (serverIpForm.hasClass('has-warning')) {
      serverIpForm.removeClass('has-warning');
    }
    serverIpForm.addClass('has-success');
    if (serverIpIcon.hasClass('glyphicon-warning-sign')) {
      serverIpIcon.removeClass('glyphicon-warning-sign');
    }
    serverIpIcon.addClass('glyphicon-ok');
  } else {
    if (serverIpForm.hasClass('has-success')) {
      serverIpForm.removeClass('has-success');
    }
    serverIpForm.addClass('has-warning');
    if (serverIpIcon.hasClass('glyphicon-ok')) {
      serverIpIcon.removeClass('glyphicon-ok');
    }
    serverIpIcon.addClass('glyphicon-warning-sign');
  }
};