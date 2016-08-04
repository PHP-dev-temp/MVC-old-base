<?php
/**
 * Contain Mvc\Crouter\Router
 * Routing handle
 */

namespace Mvc\router;


use Mvc\helpers\Config;

class Router
{
    // Store all routes
    private static $routes = array();

    // Store current route
    private static $route = array();

    // Store temporary uri array
    private static $current_uri = array();

    /**
     * @param array $route
     * @throws \Exception
     */
    public static function addRoute($route = array())
    {
        // Validate new route
        if (isset($route['name']) && isset($route['uri']))
        {
            $duplicate_route = false;
            foreach (self::$routes as $stored_route)
            {
                // Check if route is unique
                if (($stored_route['name'] == $route['name']) || ($stored_route['uri'] == $route['uri']))
                {
                    $duplicate_route = true;
                    break;
                }
            }
            if (!$duplicate_route)
            {
                // OK add the route.
                self::$routes[] = $route;
            }
            else
            {
                throw new \Exception('Route '. $route['name'] . ' is duplicate. Uri: ' . $route['uri']);
            }
        }
        else
        {
            throw new \Exception ('Something wrong with new route!');
        }
    }

    /**
     * @return array
     */
    public static function setCurrentRoute()
    {
        self::$current_uri = array();
        $url_array = array_filter(explode('/',urldecode(trim(urldecode($_SERVER['REQUEST_URI']), '/'))));
        foreach ($url_array as $key => $val)
        {
            if (empty($val)) continue;
            if (isset(Config::getConfig('remove_from_path')[$key]))  if (Config::getConfig('remove_from_path')[$key] == $val) continue;
            self::$current_uri[] = $val;
        }

        // Set defaults
        $tmp_route['language'] = Config::getConfig('default')['language'];
        $tmp_route['suffix'] = '';
        $tmp_route['method_uri'] = '';
        $tmp_route['controller_uri'] = '';
        $tmp_route['params'] = array();

        if (count(self::$current_uri))
        {
            // Get language from first path_parts and suffix
            if (Config::getConfig('multi_languages'))
            {
                // Check if it is valid language
                if (in_array(strtolower(current(self::$current_uri)), Config::getConfig('languages')))
                {
                    $tmp_route['language'] = current(self::$current_uri);
                    if ($tmp_route['language'] != Config::getConfig('default')['language']) $tmp_route['suffix'] = '_' . $tmp_route['language'];
                    array_shift(self::$current_uri);
                }
            }

            // Get controller if exist in uri, else leave default.
            if (current(self::$current_uri))
            {
                // Check if is it valid controller
                foreach (self::$routes as $route)
                {
                    if (current(self::$current_uri) == $route['controller_uri'])
                    {
                        $tmp_route['controller_uri'] = $route['controller_uri'];
                        array_shift(self::$current_uri);
                        break;
                    }
                }
            }

            // Get method if exist in routes.
            if (current(self::$current_uri))
            {
                // Check if it is registered method
                foreach (self::$routes as $route)
                {
                    if (current(self::$current_uri) == $route['method_uri'] && $tmp_route['controller_uri'] == $route['controller_uri'])
                    {
                        $tmp_route['method_uri'] = $route['method_uri'];
                        array_shift(self::$current_uri);
                        break;
                    }
                }
            }

            // Get params - all rest
            $tmp_route['params'] = self::$current_uri;
        }

        // Generate route
        foreach (self::$routes as $route)
        {
            if ($tmp_route['controller_uri'] == $route['controller_uri'] && $tmp_route['method_uri'] == $route['method_uri'])
            {
                $tmp_route['controller'] = $route['controller'];
                $tmp_route['method'] = $route['method'];
                $tmp_route['name'] = $route['name'];
                $tmp_route['uri'] = $route['uri'];
                $tmp_route['permission'] = $route['permission'];

                // Tmp route is complete
                break;
            }
        }
        self::$route = $tmp_route;
        return self::$route;
    }

    /**
     * @return array
     */
    public static function getCurrentRoute()
    {
        return self::$route;
    }

    /**
     * @return null
     * @internal param $route_name
     */
    public static function getAllRoutes()
    {
        $uri = array();
        foreach (self::$routes as $route)
        {
            $uri[$route['name']] = $route['uri'];
        }
        return $uri;
    }

    /**
     * Router constructor.
     */
    public function __construct()
    {
        require_once COREROOT . DS . 'router' . DS . 'routes.php';
    }

}
