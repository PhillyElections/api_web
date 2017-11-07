<?php
/**
 * Autocomplete model.
 *
 * Autocomplete API's model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */

namespace models;

use PDO;

/**
 * Autocomplete model.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class Autocomplete
{
    protected $core;
    protected $criteria;
    protected $directions = array(
        'NORTH'=>'N',
        'NORT'=>'N',
        'NOR'=>'N',
        'NO'=>'N',
        'SOUTH'=>'S',
        'SOUT'=>'S',
        'SOU'=>'S',
        'SO'=>'S',
        'EAST'=>'E',
        'EAS'=>'E',
        'EA'=>'E',
        'WEST'=>'W',
        'WES'=>'W',
        'WE'=>'W',
    );
    protected $fields;
    protected $limit = '0, 10';
    protected $params;
    protected $table = 'block_range';

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     */
    public function __construct($address)
    {
        $this->core = \lib\Core::getInstance();

        // process address.
        $this->setup($address);
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A json object.
     */
    public function fetch()
    {
        $data = false;
        $status = 'failure';
        $sql = ' SELECT DISTINCT ' . $this->fields . ' FROM ' . $this->table . ' WHERE ' . $this->criteria . ' ORDER BY street_name LIMIT ' . $this->limit . ' ';
        d($sql);
        $stmt = $this->core->dbh->prepare($sql);
        foreach ($this->params as $key => $pair) {
            $stmt->bindParam($key, $pair['value'], $pair['type']);
        }

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($data)) {
                $status = 'success';
            }
        }

        return json_encode(array('status'=>$status, 'data'=>$data));
    }

    /**
     * Prepare the current address.
     *
     * @param mixed $address
     */
    protected function setup($address)
    {
        $parts = explode(' ', urldecode($address));

        if (count($parts)>4) {
            die('{"status":"failure","message":"No thanks.  Not even touching that."}');
        }

        $number = array_shift($parts);

        $oeb = ' oeb IN ' . ($number % 2 ? '(\'O\', \'B\' )' : '(\'E\', \'B\' )');

        $street = implode('', $parts);
        $direction = array_shift($parts);

        $direction = isset($this->directions[$direction]) ? $this->directions[$direction] : false;
        $dir_street_criteria = '';
        if ($direction) {
            array_unshift($parts, $direction);
            $dir_street = implode('', $parts);
            $dir_street_criteria = '';
        }

        $this->fields = ' \'' . $number .'\' as number, prefix_dir, TRIM(LEADING \'0\' FROM street_name) street, suffix_type, city, zip, proper(TRIM(REPLACE(CONCAT_WS(\' \', \'' . $number .'\', prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type), \'  \', \' \'))) as address, left(precinct,4) as precinct ';
        if ($direction) {
            // yes, $direction implies $street -- necessary duplication for simplicity here
            $this->criteria = $oeb . ' AND zip > 1 AND range_start <= :a2 AND range_end >= :a3 AND (CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE :a4 OR CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE :a5 OR CONCAT(TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE :a6)';
            $this->params = array(
                ':a2' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a3' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a4' => array('value'=>$dir_street . '%','type'=>PDO::PARAM_STR),
                ':a5' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
                ':a6' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
            );
        } elseif ($street) {
            $this->criteria = $oeb . ' AND zip > 1 AND range_start <= :a2 AND range_end >= :a3 AND (CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE :a4 OR CONCAT(TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE :a5)';
            $this->params = array(
                ':a2' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a3' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a4' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
                ':a5' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
            );
        } else {
            $this->criteria = $oeb . ' AND zip > 1 AND range_start <= :a1 AND range_end >= :a2';
            $this->params = array(
                ':a1' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a2' => array('value'=>$number,'type'=>PDO::PARAM_INT),
            );
        }
    }
}
