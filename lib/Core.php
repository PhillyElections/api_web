<?php

namespace lib;

use lib\Config;
use PDO;

class Core {
	public $dbh; // handle of the db connexion
	private static $instance;

	private function __construct() {
		// building data source name from config
		$dsn = 'mysql:host=' . Config::read( 'db.host' ) .
			   ';dbname=' . Config::read( 'db.basename' ) .
			   ';port=' . Config::read( 'db.port' ) .
			   ';connect_timeout=15';
		// getting DB user from config
		$user = Config::read( 'db.user' );
		// getting DB password from config
		$password = Config::read( 'db.password' );

		$this->dbh = new PDO( $dsn, $user, $password );

		$this->setFunctions();
	}

	public static function getInstance() {
		if ( ! isset( self::$instance ) ) {
			$object = __CLASS__;
			self::$instance = new $object();
		}
		return self::$instance;
	}

	private function setFunctions() {
		$statement = $this->dbh->prepare(
			"
            DROP FUNCTION IF EXISTS proper;
            DELIMITER |
            CREATE FUNCTION proper( str VARCHAR(128) )
            RETURNS VARCHAR(128)
            BEGIN
            DECLARE c CHAR(1);
            DECLARE s VARCHAR(128);
            DECLARE i INT DEFAULT 1;
            DECLARE bool INT DEFAULT 1;
            DECLARE punct CHAR(17) DEFAULT ' ()[]{},.-_!@;:?/';
            SET s = LCASE( str );
            WHILE i <= LENGTH( str ) DO   
                BEGIN
            SET c = SUBSTRING( s, i, 1 );
            IF LOCATE( c, punct ) > 0 THEN
            SET bool = 1;
            ELSEIF bool=1 THEN
            BEGIN
            IF c >= 'a' AND c <= 'z' THEN
            BEGIN
            SET s = CONCAT(LEFT(s,i-1),UCASE(c),SUBSTRING(s,i+1));
            SET bool = 0;
            END;
            ELSEIF c >= '0' AND c <= '9' THEN
            SET bool = 0;
            END IF;
            END;
            END IF;
            SET i = i+1;
            END;
            END WHILE;
            RETURN s;
            END;
            |
            DELIMITER ;
            "
		);

		$statement->execute();
	}

}
