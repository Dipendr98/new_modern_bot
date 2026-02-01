<?php
/**
 * Router script for PHP built-in server
 * This handles routing when using php -S command
 */

// Get the requested URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the file exists and is not a directory, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Serve the requested resource as-is
}

// Otherwise, route to index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
