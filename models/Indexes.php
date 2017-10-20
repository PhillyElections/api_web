<?php
/**
 * Pollingplaces model.
 *
 * Pollingplaces API's model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */

namespace models;

use PDO;

/**
 * ShapeFederalHouse model.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class Indexes
{
    protected $core;
    protected $queried;
    protected $table_name;

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $queried
     */
    public function __construct($queried)
    {
        $this->core = \lib\Core::getInstance();
        $this->queried = sprintf('%04d', $queried);
        $this->table_name = '`precincts`';
        $this->queried_index = '`precinct`';
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A json object.
     */
    public function fetch()
    {
        $features = false;
        $status = 'failure';

        if ($this->queried) {
            $sql = ' SELECT * FROM ' . $this->table_name . ' WHERE ' . $this->queried_index . ' = :a ';

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->bindParam(':a', $this->queried, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($data)) {
                    //$utils = \lib\Utils::getInstance();
                    $features=array();
                    $features[0] = $features[0]['attributes'] = $data[0];
                    $status = 'success';
                }
            }
        }

        return json_encode(array('status'=>$status, 'features'=>$features));
    }

    public function fetchSome()
    {
    }

    public function fetchAll()
    {
    }
}
