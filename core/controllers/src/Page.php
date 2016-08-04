<?php
/**
 * Contain Mvc\controllers\Page
 * Page controller
 */

namespace Mvc\controllers;

use Mvc\app\Controller;
use Mvc\helpers\Config;

class Page extends Controller
{
    private $all_routes;

    public function __construct(array $route, $all_routes = array())
    {
        parent::__construct($route);
        $this->all_routes = $all_routes;
    }

    public function index()
    {
        // Call model do generate $data
        $this->data['menu_links'][] = array(
            'text' => 'Register new user',
            'link' => Config::getConfig('path') . $this->all_routes['register'],
        );
        if (isset($_SESSION['user_id']))
        {
            $this->data['text'] = 'Hello ' . $_SESSION['username'] . '!';
            $this->data['menu_links'][] = array(
                'text' => 'Logout',
                'link' => Config::getConfig('path') . $this->all_routes['logout'],
            );
        }
        else
        {
            $this->data['text'] = 'Hello World!';
            $this->data['menu_links'][] = array(
                'text' => 'Login',
                'link' => Config::getConfig('path') . $this->all_routes['login'],
            );
        }

        // Generate View from parts
        $this->data['view'] = array(
            $this->template_path . 'header.php',
            $this->default_method_path,
            $this->template_path . 'footer.php',
        );
    }

    public function index_sr()
    {
        $this->index();
    }
}