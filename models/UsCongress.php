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
 * Pollingplaces model.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class UsCongress
{
    protected $core;
    protected $geoid;

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $geoid
     */
    public function __construct($geoid)
    {
        $this->core = \lib\Core::getInstance();
        $this->geoid = sprintf('%04d', $geoid);
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

        if ($this->geoid) {
            $sql = ' SELECT `OGR_FID`, ST_AsText(`SHAPE`) as rings, `statefp`, `cd115fp`, `affgeoid`, `geoid`, `lsad`, `cdsessn`, `aland`, `awater`  FROM `urep_shapes` WHERE `geoid` = :a ';

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->bindParam(':a', $this->geoid, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($data)) {
                    d($data);
                    $utils = \lib\Utils::getInstance();
                    $features=array();
                    $features['attributes'] = [];
                    $features['attributes']['DISTRICT'] = $data['cd115fp'];
                    $utils->polygonString2Array($data['rings']);
                    $features['geometry'] = $data;
                    $status = 'success';
                }
            }
        }

        return json_encode(array('status'=>$status, 'features'=>$features));
    }
}
