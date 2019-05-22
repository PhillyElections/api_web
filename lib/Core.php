<?php
/**
 * Core functionality.
 *
 * Basically this is the shared PDO object.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/lib
 */

namespace lib;

use PDO;

/**
 * Class for core functionality.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/lib
 */
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
    private function __construct($override=false)
    {
        // building data source name from config.
	if (!$override) {
	// apis user config
	        $dsn = 'mysql:host=' . Config::read('db.host') .
        	       ';dbname=' . Config::read('db.basename') .
        	       ';port=' . Config::read('db.port') .
	               ';connect_timeout=15';
	        // getting DB user from config.
        	$user = Config::read('db.user');
	        // getting DB password from config.
        	$password = Config::read('db.password');
	} else {
	// old-dev user config
                $dsn = 'mysql:host=' . Config::read('db.host') .
                       ';dbname=' . Config::read('db.basename_two') .
                       ';port=' . Config::read('db.port') .
                       ';connect_timeout=15';
                // getting DB user from config.
                $user = Config::read('db.user_two');
                // getting DB password from config.
                $password = Config::read('db.password_two');
	}
        $this->dbh = new PDO($dsn, $user, $password);
    }

    /**
     * Gets the (singleton) instance.
     *
     * @return     object  The instance.
     */
    public static function getInstance($override=false)
    {
        if (! isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object($override);
        }

        return self::$instance;
    }
}
