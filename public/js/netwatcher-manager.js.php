<?php
Header("content-type: application/x-javascript");
/* Autoload libraries */
require_once('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>

// Base URL of the ajax calls
var baseURL = <?php echo '\'' . PROXY_PATH . '\'' ?> + '/';