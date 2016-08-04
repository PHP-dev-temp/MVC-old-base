<?php
/**
 * Autoload classes
 */

use Mvc\helpers\Config;

spl_autoload_register(function ($full_class_name)
{
	$file_found = false;

	// Get clean class name
    $class_name = explode('\\', $full_class_name);
	$class_name = '\\' . end($class_name);

    // Load all directories from config
	$dir_paths = Config::getConfig('dir');
	
	foreach ($dir_paths as $dir_path)
	{
		// Create $file_path
		$file_path = COREROOT . DS . $dir_path . DS . 'src' . $class_name . '.php';
		if (file_exists($file_path))
		{
			require_once ($file_path);
			$file_found = true;
		}		
	}
	if (!$file_found) throw new Exception ('Class file not found in path: ' . $class_name);

    // Include vendors manually
    require_once (BASEROOT . DS . 'vendors/PHPMailer/class.phpmailer.php');
    require_once (BASEROOT . DS . 'vendors/PHPMailer/class.phpmaileroauth.php');
    require_once (BASEROOT . DS . 'vendors/PHPMailer/class.phpmaileroauthgoogle.php');
    require_once (BASEROOT . DS . 'vendors/PHPMailer/class.smtp.php');
});