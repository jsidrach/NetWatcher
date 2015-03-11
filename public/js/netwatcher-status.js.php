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
  // Put the convert request
  $.ajax({
    type: 'GET',
    url: baseURL + '/info/delay',
    dataType: 'json',
    timeout: '10000',
    headers: {
      'timestamp': Date.now()
    },
    success: function (resp) {
      try {
        if ((resp.code == 'success') && !(typeof resp.delay === 'undefined') && !(typeof resp.maxDelay === 'undefined')) {
          var delay = parseInt(resp.delay);
          var maxDelay = parseInt(resp.maxDelay);
          if (isNaN(delay) || isNaN(maxDelay)) {
            setDelayError();
            return;
          }
          setDelayOK(delay, maxDelay);
        } else {
          setDelayError();
        }
      } catch (e) {
        setDelayError();
      }
    },
    error: function (e) {
      setDelayError(-1);
    }
  });
});

// Sets the delay test to ok if the delay is less than 10 seconds
function setDelayOK(delay, maxDelay) {
  var status = $('#checkTimestamp');
  var info = $('#checkTimestampInfo');
  var desc = $('#checkTimestampDesc');
  if ((delay <= maxDelay) || (maxDelay <= 0)) {
    status.removeClass('panel-info').addClass('panel-success');
    info.text(<?php echo '\'' . _('Passed') . '\''; ?>);
    setStatsPassed(true);
  } else {
    status.removeClass('panel-info').addClass('panel-danger');
    info.text(<?php echo '\'' . _('Failed') . '\''; ?> );
    setStatsPassed(false);
  }
  desc.text(desc.text() + delay + 's');

};

// Sets the delay test to error
function setDelayError() {
  var status = $('#checkTimestamp');
  var info = $('#checkTimestampInfo');
  var desc = $('#checkTimestampDesc');
  status.removeClass('panel-info').addClass('panel-danger');
  info.text(<?php echo '\'' . _('Failed') . '\''; ?>);
  desc.html(desc.text() + <?php echo '\'<code>' . _('No Response') . '</code>\''; ?>);
  setStatsPassed(false);
};

// Sets the top stats with the new result
function setStatsPassed(value) {
  var totalCount = parseInt($('#progressLabel-total').text().split(':')[1]);
  var label;
  var progressBar;
  if (value) {
    var label = $('#progressLabel-success');
    var progressBar = $('#progressBar-success');
  } else {
    var label = $('#progressLabel-danger');
    var progressBar = $('#progressBar-danger');
  }
  var count = parseInt(label.text().split(':')[1]) + 1;
  label.text(label.text().split(':')[0] + ': ' + count);
  var percentage = (100 * count / totalCount).toFixed(2);
  progressBar.attr('style', 'width: ' + percentage + '%');
};