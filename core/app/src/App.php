<?php
/**
 * Contain Mvc\app\App
 * Main application class
 */

namespace Mvc\app;

use Mvc\helpers\Config;
use Mvc\models\User;
use Mvc\router\Router;

class App
{
    public $route;
    public $all_routes;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $router = new Router();
        $this->route = $router::setCurrentRoute();
        $this->all_routes = $router::getAllRoutes();
        if (!isset($_SESSION['user_id']))
        {
            if(isset($_COOKIE[Config::getConfig('auth.remember')])) {
                $cred = $_COOKIE[Config::getConfig('auth.remember')];
                $user = new User();
                $user->setUserFromCookie($cred);
                unset ($user);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function run()
    {
        // Get user permission
        if (!isset($_SESSION['user_id'])) $_SESSION['role'] = 'guest';

        // Get controller
        $controller_class_name = '\\Mvc\\controllers\\' . $this->route['controller'];

        // Get method
        $controller_method_name = $this->route['method'] . $this->route['suffix'];

        // Calling controller's method
        $controller_object = new $controller_class_name($this->route, $this->all_routes);
        if (method_exists($controller_object, $controller_method_name))
        {
            // Call controller's method
            $controller_object->$controller_method_name();
            $data = $controller_object->getData();

            // Generate view
            $view = new View($data);
            echo $view::getResponse();
        }
        else
        {
            throw new \Exception('Method ' . $controller_method_name . ' or class ' . $controller_class_name . ' does not exist');
        }

    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }
}