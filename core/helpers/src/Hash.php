<?php
/**
 * Contain Mvc\helpers\Hash
 * Handle hashing system
 */

namespace Mvc\helpers;


class Hash
{
    /**
     * @param $password
     * @return bool|string
     */
    public static function password($password)
    {
        return password_hash(
            $password,
            Config::getConfig('hash')['algorithm'],
            ['cost' => Config::getConfig('hash')['cost']]
        );
    }

    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function passwordCheck($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * @param $input
     * @return string
     */
    public static function hash($input)
    {
        return hash('sha256', $input);
    }

    /**
     * @param $know
     * @param $user
     * @return bool
     */
    public static function hashCheck($know, $user)
    {
        return hash_equals($know, $user);
    }

    /**
     * @param $len
     * @return string
     */
    public static function randStrGen($len)
    {
        $result = '';
        $chars = "abcdefghijklmnopqrstuvwxyzQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
        $charArray = str_split($chars);
        for($i = 0; $i < $len; $i++)
        {
            $randItem = array_rand($charArray);
            $result .= '' . $charArray[$randItem];
        }
        return $result;
    }
}