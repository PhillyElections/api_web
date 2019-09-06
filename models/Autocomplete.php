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
        $sql = ' SELECT DISTINCT ' . $this->fields . 
		' FROM ' . $this->table . 
		' WHERE ' . $this->criteria . ' ORDER BY street_name LIMIT ' . $this->limit . ' ';
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

	$b = 'B';
        $oe = $number % 2 ? 'O' : 'E';

        $street = implode('', $parts);
        $direction = strtoupper(array_shift($parts));

        $direction = isset($this->directions[$direction]) ? $this->directions[$direction] : false;
        $dir_street_criteria = '';
        if ($direction) {
            array_unshift($parts, $direction);
            $dir_street = implode('', $parts);
            $dir_street_criteria = '';
        }

        $this->fields = 'oeb, \'' . $number .'\' as number, prefix_dir, TRIM(LEADING \'0\' FROM street_name) street, suffix_type, city, zip, proper(TRIM(REPLACE(CONCAT_WS(\' \', \'' . $number .'\', prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type), \'  \', \' \'))) as address, left(precinct,4) as precinct ';

        if ($direction) {
            // yes, $direction implies $street -- necessary duplication for simplicity here
            // we removed zip from criteria
            // $this->criteria = ' oeb in (\'' . $b . '\', \'' . $oe  . '\') AND zip > 1 AND range_start <= ' . $number . ' AND range_end >= ' . $number . ' AND (CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $dir_street . '%\' OR CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' OR CONCAT(TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' )';
            $this->criteria = ' oeb in (\'' . $b . '\', \'' . $oe  . '\') AND range_start <= ' . $number . ' AND range_end >= ' . $number . ' AND (CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $dir_street . '%\' OR CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' OR CONCAT(TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' )';
            $this->params = array(
		':a1' => array('value'=>$b,'type'=>PDO::PARAM_STR),
                ':a2' => array('value'=>$oe,'type'=>PDO::PARAM_STR),
                ':a3' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a4' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a5' => array('value'=>$dir_street . '%','type'=>PDO::PARAM_STR),
                ':a6' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
                ':a7' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
            );
        } elseif ($street) {
            // we removed zip
            // $this->criteria = ' oeb in (\'' . $b . '\', \'' . $oe  . '\') AND zip > 1 AND range_start <= ' . $number . ' AND range_end >= ' . $number . ' AND (CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' OR CONCAT(TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' )';
            $this->criteria = ' oeb in (\'' . $b . '\', \'' . $oe  . '\') AND range_start <= ' . $number . ' AND range_end >= ' . $number . ' AND (CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' OR CONCAT(TRIM(LEADING \'0\' FROM street_name), suffix_type) LIKE \'' . $street . '%\' )';
            $this->params = array(
                ':a1' => array('value'=>$b,'type'=>PDO::PARAM_STR),
                ':a2' => array('value'=>$oe,'type'=>PDO::PARAM_STR),
                ':a3' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a4' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a5' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
                ':a6' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
            );
        } else {
            // we removed zip 
            // $this->criteria = ' oeb in (\'' . $b . '\', \'' . $oe  . '\') AND zip > 1 AND range_start <= ' . $number . ' AND range_end >= ' . $number . ' ';
            $this->criteria = ' oeb in (\'' . $b . '\', \'' . $oe  . '\') AND range_start <= ' . $number . ' AND range_end >= ' . $number . ' ';
            $this->params = array(
                ':a1' => array('value'=>$b,'type'=>PDO::PARAM_STR),
                ':a2' => array('value'=>$oe,'type'=>PDO::PARAM_STR),
                ':a3' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a4' => array('value'=>$number,'type'=>PDO::PARAM_INT),
            );
        }
    }
}
