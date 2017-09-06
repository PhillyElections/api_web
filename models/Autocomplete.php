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
 * Autocomplete class.
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
        // grab our PDO
        $this->core = \lib\Core::getInstance();

        // process $address.
        $this->prefetch($address);
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A JsonP function.
     */
    public function fetch()
    {
        $json = false;
        $sql = ' SELECT DISTINCT ' . $this->fields . ' FROM `' . $this->table . '` WHERE ' . $this->criteria . ' ORDER BY street_name LIMIT ' . $this->limit . ' ';

        $stmt = $this->core->dbh->prepare($sql);
        foreach ($this->params as $key => $pair) {
            $stmt->bindParam($key, $pair['value'], $pair['type']);
        }

        if ($stmt->execute()) {
            //$json = $this->callback . '({"status":"success","data":' . json_encode($stmt->fetchAll()) . ');';
            //$json = $this->callback . '(' . json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)) . ');';
            $json = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }

        return $json;
    }

    /**
     * Prepare the current $address.
     *
     * @param mixed $address
     */
    protected function prefetch($address)
    {
        //        $this->callback = urldecode($_REQUEST['callback']);
        $parts = explode(' ', urldecode($address));

        if (count($parts)>4) {
            die('{"status":"failure","message":"No thanks.  Not even touching that."}');
        }

        $number = array_shift($parts);

        $oeb = ' `oeb` IN ' . ($number % 2 ? '(\'O\', \'B\' )' : '(\'E\', \'B\' )') . ' ';
        $street = implode('', $parts);

        //        $this->fields = 'prefix_dir, proper(TRIM(LEADING \'0\' FROM street_name)) as street, proper(suffix_type) as suffix_type, zip';
        $this->fields = ' TRIM(REPLACE(CONCAT_WS(\' \', \'' . $number .'\', `sprefix_dir`, TRIM(LEADING \'0\' FROM `street_name`), `suffix_type`), \'  \', \' \')) as `label`, `precinct` as `division` ';

        if ($street) {
            $this->criteria = $oeb . ' AND `range_start` <= :a2 AND `range_end` >= :a3 AND (CONCAT(`prefix_dir`, TRIM(LEADING \'0\' FROM `street_name`), `suffix_type`) LIKE :a4 OR CONCAT(TRIM(LEADING \'0\' FROM `street_name`), `suffix_type`) LIKE :a5)';
            $this->params = array(
                ':a2' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a3' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a4' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
                ':a5' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
            );
        } else {
            $this->criteria = $oeb . ' AND range_start <= :a1 AND range_end >= :a2';
            $this->params = array(
                ':a1' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a2' => array('value'=>$number,'type'=>PDO::PARAM_INT),
            );
        }
    }
}
