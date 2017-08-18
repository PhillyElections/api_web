<?php
/**
 * Configuration class.
 *
 * Configuration loader (local/production)
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/lib
 */

namespace lib;

/**
 * Configuration class.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/lib
 */
class Config
{
    public static $confArray;

    public static function read($name)
    {
        if (isset(self::$confArray[APPLICATION_ENV][$name])) {
            return self::$confArray[APPLICATION_ENV][$name];
        }

        return self::$confArray['local'][$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[APPLICATION_ENV][$name] = $value;
    }
}
