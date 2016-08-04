<?php
/**
 * Contain Mvc\app\Controller
 * Main controller class
 */

namespace Mvc\app;



use Mvc\helpers\Config;

class Controller
{
    protected $route;
    protected $data;
    protected $template_path;
    protected $method_path;
    protected $default_method_path;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Controller constructor.
     * @param array $route
     * @internal param Router $router
     */
    public function __construct($route = array())
    {
        $this->data = array();
        $this->route = $route;
        $this->generatePath();
    }

    /**
     * Generate method path with suffix
     */
    protected function generatePath()
    {
        $this->template_path = VIEWROOT . DS . 'templates' . DS;;
        $this->default_method_path = VIEWROOT . DS . $this->route['controller']  . DS . $this->route['method'] . '.php';
        $this->method_path = VIEWROOT . DS . $this->route['controller']  . DS . $this->route['method'] . $this->route['suffix'] . '.php';
    }

    protected function checkAuth()
    {
        // Redirect to home if user have not privilege.
        if ($_SESSION['role'] != $this->route['permission'])
        {
            $_SESSION['flash_message'] = 'Access forbiden!';
            header( 'Location: ' . Config::getConfig('path') );
            exit;
        }
    }


}