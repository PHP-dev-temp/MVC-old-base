<?php
/**
 * index file
 */

use Mvc\app\App;
use Mvc\helpers\Config;

// Include init.php
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'init.php';

$config = new Config();
$app = new App();

// Run application
$app->run();