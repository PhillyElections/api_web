<?php

namespace models;
use lib\Core;
use PDO;

class Autocomplete {

	protected $address;
	protected $callback;
	protected $core;
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

		$fields   = 'prefix_dir, TRIM(LEADING \'0\' FROM street_name) as street, type_dir';
		$table    = ;
		$criteria = 'house_range_start <= :a1 and house_range_end >= :a2';
		$query    = ' SELECT DISTINCT ${fields} FROM ${table} WHERE ${criteria} ORDER BY street_name LIMIT 0, 10 ';

		$sql = 'SELECT * FROM stuff';
		$stmt = $this->core->dbh->prepare( $sql );

		if ( $stmt->execute() ) {
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

		$this->
	}
}
switch ( count( $parts ) ) {
	case 0:
		break;
	case 1:
		$criteria .= '';
		$params = array(
			':a1' => $parts[0],
			':a2' => $parts[0],
		);
		break;
	case 2:
		$criteria .= ' and street_name LIKE :a3 ';
		$params = array(
			':a1' => $parts[0],
			':a2' => $parts[0],
			':a3' => $parts[1] . '%',
		);
	default:
		break;
}
$statement = $pdo->prepare();

$statement->execute( $params );
$rows = $statement->fetchAll();
