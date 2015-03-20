<?php
/**
 * Index page
 *
 * Loads the libraries and calls the router to handle the request
 *
 * @package Core
 */
/* Autoload libraries */
require_once ('vendor/autoload.php');
/* Dispatch the request */
Core\Router::dispatch();
?>