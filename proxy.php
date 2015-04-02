<?php
/**
 * Simplified proxy for the FPGA API ajax petitions
 *
 * Warning: this is not a general proxy, and it does not foward parameters and such
 * It only forwards simple calls for the FPGA Web Service, with the following syntax:
 *      proxy.php?url=/info/status
 */

/**
 * Autoload libraries
 */
require_once ('vendor/autoload.php');

/**
 * Number of subparts of the URL
 */
define('RELATIVE_SUBPATHS', 0);
/* Loads the config */
\Core\Config::load();

/* Get the request */
if (! isset($_REQUEST[PROXY_ID])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
    header('Status: 404 Not Found');
    $_SERVER['REDIRECT_STATUS'] = 404;
    return;
}

/* Request options */
$opts = array(
    'http' => array(
        'method' => $_SERVER['REQUEST_METHOD'],
        'timeout' => - 1,
        'ignore_errors' => true
    )
);

/* Timestamp */
$headers = getallheaders();
if (isset($headers['timestamp'])) {
    $opts['http']['header'] = 'timestamp: ' . $headers['timestamp'];
}

$context = stream_context_create($opts);

/* Optimization */
session_write_close();

/* Call the FPGA Web Service */
$result = file_get_contents(\Core\Config::$REMOTE_SERVER_IP . $_REQUEST[PROXY_ID], false, $context);

/* Output the content */
header($http_response_header[0]);
echo $result;

/* Log the call */
\Core\Logger::logProxy('[Code: ' . $http_response_header[0] . '][Method: ' . $_SERVER['REQUEST_METHOD'] . '][URL: ' . $_REQUEST[PROXY_ID] . ']');