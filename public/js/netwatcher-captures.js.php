<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
/*  - Cargar al principio > deshabilitar paneles de la derecha
  - radios: al hacer click, ver estado, hacer nueva petición a /all | /simple | /pcap
    - Capturar la respuesta, actualizar datos tabla
      - Si no llega respuesta, notificación error no responde, autorefresh off
  - Autorefresh
    - Off: quitar peticiones
    - On: timer cada X sec, capturar respuesta, actualizar datos tabla
        - Si no llega respuesta, notificación error no responde, autorefresh off
  - Refresh
    - nueva peticion datos tabla, si no responde autorefresh off y notificacion error
  - Search (automático?)
  - Probar ordenación
  - Seleccionar 1 de tabla: guardar nombre en panel azul, guardar tipo en algún lado */
// REFRESH BUTTON
// SET TIMEOUT FOR LARGER QUERIES (convertir)!!
?>

// Base URL for the calls
var baseURL = <?php echo '\'' . \Core\ Config::$REMOTE_SERVER_IP . '\''; ?> ;
// Interval timer+callback
var interval = null;
// Captures table
var tableCaptures;

// Sets the events
$(document).ready(function () {
  // Set the document vars
  tableCaptures = $('#tableCaptures');

  // Disable right panels
  toggleRightPanels(false);

  // Refresh when radio buttons are pressed
  $('#allCaptures').click(refreshData);
  $('#simpleCaptures').click(refreshData);
  $('#pcapCaptures').click(refreshData);
  // Set AutoRefresh with the check button
  $('#autoRefresh').change(autoRefreshHandler);
  // Refresh button
  $('button[name="refresh"]').off('click').on('click', refreshData);

  // Initial refresh of data
  refreshData();
});

// Resize the table headers on window resize
$(window).on('resize', function () {
  tableCaptures.bootstrapTable('resetView');
});


// Enables/Disables the right panels
function toggleRightPanels(value) {
  $('#convertedName').attr('disabled', !value);
  $('#convertOK').attr('disabled', !value);
  $('#newName').attr('disabled', !value);
  $('#renameOK').attr('disabled', !value);
  $('#deleteCapture').attr('disabled', !value);
}

// Refreshes the table data
function refreshData() {
  // Set the petition url
  var capturesURL = baseURL + '/captures/';
  if ($('#simpleCaptures').is(':checked')) {
    capturesURL += 'simple';
  } else if ($('#pcapCaptures').is(':checked')) {
    capturesURL += 'pcap';
  } else {
    capturesURL += 'all';
  }

  // Get the new data
  $.ajax({
    type: 'GET',
    url: capturesURL,
    dataType: 'jsonp',
    data: '',
    success: function (resp) {
      // Set the table data
      tableCaptures.bootstrapTable('load', resp.captures);
    },
    error: function (e) {
      // Notification of the error (timeout most of the times)
      $.bootstrapGrowl( <?php echo '\''._('Connection error').
        '\''; ?> , {
          type: 'danger'
        });
      setAutoRefresh(false);
    }
  });
}

// AutoRefresh check button handler
function autoRefreshHandler() {
  if ($('#autoRefresh').is(':checked')) {
    setAutoRefresh(true);
  } else {
    setAutoRefresh(false);
  }
}

// Sets the autorefresh
function setAutoRefresh(value) {
  clearInterval(interval);
  if (value) {
    refreshData();
    interval = setInterval(refreshData, 5000);
  }
  $('#autoRefresh').prop('checked', value);
}