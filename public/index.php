<?php
// Variable $zf2 = Path to ZendFramework
$ZendFramework = __DIR__ . '/../ZendFramework';

// Add $zf2 app path to php include_path
set_include_path(get_include_path() . PATH_SEPARATOR . $ZendFramework);

/**
 * All paths relative to root
 */
chdir(dirname(__DIR__));

// Decline static file requests
if (php_sapi_name() === 'cli-server' && 
    is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))
{
    return false;
}

// Autoloading
require 'init_autoloader.php';

// Run the application
// Settings are derived from the file below

// Use the autoloader
Zend\Mvc\Application::init(require 'config/application.config.php')->run();