<?php
/**
 * Contain app routes
 * All routes must be here
 */

use Mvc\helpers\Config;
use Mvc\router\Router;

// home page
Router::addRoute(array(
    'name' => 'home',
    'uri' => '/',
    'language' => Config::getConfig('default')['language'],
    'controller' => 'Page',
    'method' => 'index',
    'controller_uri' => '',
    'method_uri' => '',
    'permission' => 'guest',
    'params' => array(),
));

// contact page
Router::addRoute(array(
    'name' => 'contact',
    'uri' => '/contact',
    'language' => Config::getConfig('default')['language'],
    'controller' => 'Page',
    'method' => 'contact',
    'controller_uri' => '',
    'method_uri' => 'contact',
    'permission' => 'guest',
    'params' => array(),
));

// about page
Router::addRoute(array(
    'name' => 'about',
    'uri' => '/about',
    'language' => Config::getConfig('default')['language'],
    'controller' => 'Page',
    'method' => 'about',
    'controller_uri' => '',
    'method_uri' => 'about',
    'permission' => 'guest',
    'params' => array(),
));

// register user
Router::addRoute(array(
    'name' => 'register',
    'uri' => '/register',
    'language' => Config::getConfig('default')['language'],
    'controller' => 'User',
    'method' => 'register',
    'controller_uri' => '',
    'method_uri' => 'register',
    'permission' => 'guest',
    'params' => array(),
));

// activate registered user
Router::addRoute(array(
    'name' => 'activate',
    'uri' => '/user/activate',
    'language' => Config::getConfig('default')['language'],
    'controller' => 'User',
    'method' => 'activate',
    'controller_uri' => 'user',
    'method_uri' => 'activate',
    'permission' => 'guest',
    'params' => array(),
));

// login user
Router::addRoute(array(
    'name' => 'login',
    'uri' => '/login',
    'language' => Config::getConfig('default')['language'],
    'controller' => 'User',
    'method' => 'login',
    'controller_uri' => '',
    'method_uri' => 'login',
    'permission' => 'guest',
    'params' => array(),
));

// logout user
Router::addRoute(array(
    'name' => 'logout',
    'uri' => '/logout',
    'language' => Config::getConfig('default')['language'],
    'controller' => 'User',
    'method' => 'logout',
    'controller_uri' => '',
    'method_uri' => 'logout',
    'permission' => 'user',
    'params' => array(),
));

