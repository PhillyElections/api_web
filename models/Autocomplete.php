<?php

namespace models;
use lib\Core;
use PDO;

class Autocomplete {

	protected $address;
	protected $callback;
	protected $core;
	protected $criteria;
	protected $fields;
	protected $params;
	protected $table;

	function __construct() {
		$this->core = \lib\Core::getInstance();
		$this->table = 'api_block_range';

		// set properties from _GET
		$this->setup();
	}

	// Get all stuff
	public function fetch() {
		$r = array();

		$sql = ' SELECT DISTINCT ${self::fields} FROM ${self::table} WHERE ${self::criteria} ORDER BY street_name LIMIT 0, 10 ';

		$sql = 'SELECT * FROM stuff';
		$stmt = $this->core->dbh->prepare( $sql );

		if ( $stmt->execute( $this->params ) ) {
			$r = $stmt->fetchAll( PDO::FETCH_ASSOC );
		} else {
			$r = 0;
		}
		return $r;
	}

	protected function setup() {
		$this->callback = urldecode( $_GET['callback'] );
		$address = urldecode( $_GET['address'] )
		$parts    = explode( ' ', $address );

		$fields   = 'prefix_dir, TRIM(LEADING \'0\' FROM street_name) as street, type_dir, ';
		$criteria = 'house_range_start <= :a1 and house_range_end >= :a2';

		switch ( count( $parts ) ) {
			case 0:
				break;
			case 1:
				$criteria .= '';
				$this->params = array(
					':a1' => $parts[0],
					':a2' => $parts[0],
				);
				break;
			case 2:
				$criteria .= ' and street_name LIKE :a3 ';
				$this->params = array(
					':a1' => $parts[0],
					':a2' => $parts[0],
					':a3' => $parts[1] . '%',
				);
			default:
				break;
		}
	}

}
