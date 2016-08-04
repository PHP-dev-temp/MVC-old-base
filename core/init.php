<?php
/**
 * Init application
 */

// Session
session_start();

// Constants
define ('DS', DIRECTORY_SEPARATOR);
define ('BASEROOT', dirname(dirname(__FILE__)));
define ('COREROOT', __DIR__);
define ('CONFIGROOT', __DIR__ . DIRECTORY_SEPARATOR . 'config');
define ('PUBLICROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'public');
define ('VIEWROOT', __DIR__ . DIRECTORY_SEPARATOR . 'views');

include COREROOT . DS . 'helpers' . DS. 'src' . DS . 'Config.php';
include 'autoload.php';