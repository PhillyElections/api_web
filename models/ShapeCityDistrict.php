<?php
/**
 * ShapeCityDistrict model.
 *
 * ShapeCityDistrict API's model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */

namespace models;

use PDO;

/**
 * ShapeCityDistrict model.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class ShapeCityDistrict
{
    protected $core;
    protected $queried;
    protected $queried_index;
    protected $table_name;

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $queried
     */
    public function __construct($queried)
    {
        $this->core = \lib\Core::getInstance();
        $this->queried = $queried;
        $this->table_name = '`shapes_city_districts`';
        $this->queried_index = '`district`';
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
            $sql = ' SELECT ST_AsText(SHAPE) as SHAPE FROM ' . $this->table_name . ' WHERE ' . $this->queried_index . ' = :a ';

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->bindParam(':a', $this->queried, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($data)) {
                    //$utils = \lib\Utils::getInstance();
                    $features=array();
                    $features[0] = $features[0]['attributes'] = [];
                    $features[0]['attributes']['queried'] = $this->queried;

                    $features[0]['geometry'] = $features[0]['geometry']['coordinates'] = [];
                    $features[0]['geometry']['coordinates'][0] = \lib\Utils::polygonString2Array($data[0]['SHAPE']);
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
