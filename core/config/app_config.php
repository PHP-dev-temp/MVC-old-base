<?php
/**
 * App configuration
 */

use Mvc\helpers\Config;

Config::setConfig('dir', array('app', 'router', 'controllers', 'models', 'views', 'helpers'));

Config::setConfig('hash', array(
    'algorithm' => PASSWORD_BCRYPT,
    'cost' => 10,
));

