<?php
/* Autoload libraries */
require_once ('lib/vendor/autoload.php');

/**
 * Number of subparts of the URL
 */
define('RELATIVE_SUBPATHS', 0);
/* Loads the config */
\Core\Config::load();

/**
 * Slight modifications of:
 * AJAX Cross Domain (PHP) Proxy 0.8
 * by Iacovos Constantinou (http://www.iacons.net)
 * Released under CC-GNU GPL
 */

/**
 * Enables or disables filtering for cross domain requests.
 * Recommended value: true
 */
define('CSAJAX_FILTERS', true);

/**
 * If set to true, $valid_requests should hold only domains i.e.
 * a.example.com, b.example.com, usethisdomain.com
 * If set to false, $valid_requests should hold the whole URL ( without the parameters ) i.e. http://example.com/this/is/long/url/
 * Recommended value: false (for security reasons - do not forget that anyone can access your proxy)
 */
define('CSAJAX_FILTER_DOMAIN', false);

/**
 * A set of valid cross domain requests
 */
$valid_requests = array(
    \Core\Config::$REMOTE_SERVER_IP
);

// Identify request headers
$request_headers = array();
foreach ($_SERVER as $key => $value) {
    if (substr($key, 0, 5) == 'HTTP_') {
        $headername = str_replace('_', ' ', substr($key, 5));
        $headername = str_replace(' ', '-', ucwords(strtolower($headername)));
        if (! in_array($headername, array(
            'Host',
            'X-Proxy-Url'
        ))) {
            $request_headers[] = "$headername: $value";
        }
    }
}

// Identify request method, url and params
$request_method = $_SERVER['REQUEST_METHOD'];
if ('GET' == $request_method) {
    $request_params = $_GET;
} elseif ('POST' == $request_method) {
    $request_params = $_POST;
    if (empty($request_params)) {
        $data = file_get_contents('php://input');
        if (! empty($data)) {
            $request_params = $data;
        }
    }
} elseif ('PUT' == $request_method || 'DELETE' == $request_method) {
    $request_params = file_get_contents('php://input');
} else {
    $request_params = null;
}

// Get URL from `CSURL` in GET or POST data, before falling back to X-Proxy-URL header.
if (isset($_REQUEST[CSURL])) {
    // Add api url
    $request_url = \Core\Config::$REMOTE_SERVER_IP . urldecode($_REQUEST[CSURL]);
} else {
    if (isset($_SERVER['HTTP_X_PROXY_URL'])) {
        // Add api url
        $request_url = \Core\Config::$REMOTE_SERVER_IP . urldecode($_SERVER['HTTP_X_PROXY_URL']);
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        header('Status: 404 Not Found');
        $_SERVER['REDIRECT_STATUS'] = 404;
        require (ERROR_404);
        exit();
    }
}

// Parse URL
$p_request_url = parse_url($request_url);
// CSURL may exist in GET request methods
if (is_array($request_params) && array_key_exists(CSURL, $request_params)) {
    unset($request_params[CSURL]);
}

// Ignore requests for proxy :)
if (preg_match('!' . $_SERVER['SCRIPT_NAME'] . '!', $request_url) || empty($request_url) || count($p_request_url) == 1) {
    \Core\Logger::logWarning('Proxy: Invalid request - make sure that CSURL variable is not empty');
    exit();
}

// Check against valid requests
if (CSAJAX_FILTERS) {
    $parsed = $p_request_url;
    if (CSAJAX_FILTER_DOMAIN) {
        if (! in_array($parsed['host'], $valid_requests)) {
            \Core\Logger::logWarning('Proxy: Invalid domain - ' . $parsed['host'] . ' does not included in valid requests');
            exit();
        }
    } else {
        $check_url = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '';
        $check_url .= isset($parsed['user']) ? $parsed['user'] . ($parsed['pass'] ? ':' . $parsed['pass'] : '') . '@' : '';
        $check_url .= isset($parsed['host']) ? $parsed['host'] : '';
        $check_url .= isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $check_url .= isset($parsed['path']) ? $parsed['path'] : '';
    }
}

// append query string for GET requests
if ($request_method == 'GET' && count($request_params) > 0 && (! array_key_exists('query', $p_request_url) || empty($p_request_url['query']))) {
    $request_url .= '?' . http_build_query($request_params);
}

// Encode spaces
$request_url = str_replace(' ', '%20', $request_url);

// Let the request begin
$ch = curl_init($request_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers); // (Re-)send headers
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response
curl_setopt($ch, CURLOPT_HEADER, true); // Enabled response headers

// Add data for POST, PUT or DELETE requests
if ('POST' == $request_method) {
    $post_data = is_array($request_params) ? http_build_query($request_params) : $request_params;
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
} elseif ('PUT' == $request_method || 'DELETE' == $request_method) {
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_params);
}

// Retrieve response (headers and content)
$response = curl_exec($ch);
curl_close($ch);

// Split response to header and content
list ($response_headers, $response_content) = preg_split('/(\r\n){2}/', $response, 2);

// (Re-)send the headers
$response_headers = preg_split('/(\r\n){1}/', $response_headers);
foreach ($response_headers as $key => $response_header) {
    // Rewrite the `Location` header, so clients will also use the proxy for redirects.
    if (preg_match('/^Location:/', $response_header)) {
        list ($header, $value) = preg_split('/: /', $response_header, 2);
        $response_header = 'Location: ' . $_SERVER['REQUEST_URI'] . '?' . CSURL . '=' . $value;
    }
    if (! preg_match('/^(Transfer-Encoding):/', $response_header)) {
        header($response_header, false);
    }
}

// Finally, output the content
print($response_content);