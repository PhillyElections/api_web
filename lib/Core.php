<?php
/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/lib
 */

namespace lib;

use PDO;

class Core
{
    /**
     * Handle of the DB connection.
     *
     * @var object   PDO
     */
    public $dbh;

    /**
     * Singleton.
     *
     * @var object   singleton instance
     */
    private static $instance;

    /**
     * Setup our PDO.
     */
    private function __construct()
    {
        // building data source name from config.
        $dsn = 'mysql:host=' . Config::read('db.host') .
               ';dbname=' . Config::read('db.basename') .
               ';port=' . Config::read('db.port') .
               ';connect_timeout=15';
        // getting DB user from config.
        $user = Config::read('db.user');
        // getting DB password from config.
        $password = Config::read('db.password');

        $this->dbh = new PDO($dsn, $user, $password);
    }

    /**
     * Gets the (singleton) instance.
     *
     * @return     object  The instance.
     */
    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object();
        }

        return self::$instance;
    }
}
