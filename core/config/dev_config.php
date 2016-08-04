<?php
/**
 * Development configuration
 */

use Mvc\helpers\Config;

// Errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Remove uri part for my localhost. Must be changed!!!
Config::setConfig('remove_from_path', array('MyMVC', 'public'));
Config::setConfig('path', '/MyMVC/public');

// Set multi language support
Config::setConfig('languages', array('en', 'sr'));
Config::setConfig('multi_languages', true);

// Set default settings
Config::setConfig('default', array(
    'language' => 'en',
));

// Set database
Config::setConfig('db.driver', 'mysql');
Config::setConfig('db.host', 'localhost');
Config::setConfig('db.name', 'mymvc');
Config::setConfig('db.username', 'username');
Config::setConfig('db.password', 'password');
Config::setConfig('db.charset', 'utf8mb4');
Config::setConfig('db.collation', 'utf8_unicode_ci');
Config::setConfig('db.prefix', '');

// Auth settings
Config::setConfig('auth.session', 'user_id');
Config::setConfig('auth.remember', 'mvc_user_r');

//Mail settings
Config::setConfig('mail.smtp_auth', true);
Config::setConfig('mail.smtp_secure', 'tls');
Config::setConfig('mail.host', 'smtp.gmail.com');
Config::setConfig('mail.username', '');
Config::setConfig('mail.password', '');
Config::setConfig('mail.port', 587);
Config::setConfig('mail.html', 'true');

// csrf
Config::setConfig('csrf.session', 'csrf_token');

