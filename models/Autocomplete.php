<?php

namespace models;

use lib\Core;
use PDO;

class Autocomplete
{

    protected $callback;
    protected $core;
    protected $criteria;
    protected $fields;
    protected $params;
    protected $table;

    function __construct()
    {
        $this->core = \lib\Core::getInstance();
        $this->table = 'api_block_range';

        // set properties from _GET
        $this->setup();
    }

    // Get all stuff
    public function fetch()
    {
        $r = array();

        $sql = ' SELECT DISTINCT ' . $this->fields . ' FROM ' . $this->table . ' WHERE ' . $this->criteria . ' ORDER BY street_name LIMIT 0, 10 ';

        $stmt = $this->core->dbh->prepare($sql);
        var_dump($stmt);
        var_dump($this->params);

        if ($stmt->execute($this->params)) {
            return $this->callback . '(' . json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)) . ')';
        }
        echo 'failed';
        return false;
    }

    protected function setup()
    {
        $this->callback = urldecode($_GET['callback']);
        $address = urldecode($_GET['address']);
        $parts   = explode(' ', $address);

        $this->fields   = 'prefix_dir, TRIM(LEADING \'0\' FROM street_name) as street, type_dir, ';
        $this->criteria = 'house_range_start <= :a1 and house_range_end >= :a2';

        switch (count($parts)) {
            case 1:
                $this->criteria .= '';
                $this->params = array(
                    ':a1' => $parts[0],
                    ':a2' => $parts[0],
                );
                break;
            case 2:
                $this->criteria .= ' and street_name LIKE :a3 ';
                $this->params = array(
                    ':a1' => $parts[0],
                    ':a2' => $parts[0],
                    ':a3' => $parts[1] . '%',
                );
                break;
            default:
                break;
        }
    }
}