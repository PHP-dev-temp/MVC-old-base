<?php
/**
 * Contain Mvc\app\View
 * Main view class
 */
namespace Mvc\app;


class View
{
    private static $response;
    private static $data;

    /**
     * View constructor.
     * @param $data
     */
    public function __construct($data)
    {
        self::$response = '';
        self::$data = $data;
    }

    /**
     * @return mixed
     */
    public static function getResponse()
    {
        foreach (self::$data['view'] as $part_link)
        {
            self::addView($part_link);
        }
        return self::$response;
    }

    /**
     * @param $viewPart
     */
    private static function addView($viewPart)
    {
        ob_start();
        $data = self::$data;
        include $viewPart;
        self::$response .= ob_get_clean();
    }
}