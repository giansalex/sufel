<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/08/2017
 * Time: 20:55
 */

namespace Sufel\App\Utils;

/**
 * Class Validator
 * @package Sufel\App\Utils
 */
class Validator
{
    /**
     * @param array $arr
     * @param array $params
     * @return bool
     */
    public static function existFields($arr, $params)
    {
        foreach ($params as $param) {
            if (!isset($arr[$param])) {
                return false;
            }
        }

        return true;
    }
}