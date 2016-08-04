<?php
/**
 * Contain Mvc\helpers\Config
 * Manipulate configurations
 */

namespace Mvc\helpers;

Class Config
{
	// Store all configs
	private static $config = array();

	/**
	 * Config constructor.
     */
	public function __construct()
    {
        // Load default app config
        $this->loadConfigFromFile(CONFIGROOT . DS . 'app_config.php');

        // Load config specified for environment (dev, prod).
        $env = file_get_contents(CONFIGROOT . DS . 'env.txt');
        $this->loadConfigFromFile(CONFIGROOT . DS . $env . '_config.php');
    }

	/**
	 * @param $path
	 * @throws \Exception
	 */
	private function loadConfigFromFile($path)
	{
		if (file_exists($path))
		{
			include_once $path;
		}
		else
		{
			throw new \Exception ('Config file not found in path: ' . $path);
		}		
	}

    /**
     * @param $key
     * @param $value
     * @internal param array $config
     */
	public static function setConfig($key, $value)
	{
		self::$config[$key] = $value;
	}

    /**
     * @param $key
     * @return array
     */
	public static function getConfig($key)
	{
		return self::$config[$key] ? self::$config[$key] : null;
	}
}