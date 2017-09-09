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
class Pollingplaces
{
    protected $core;
    protected $precinct;

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     * @param mixed $precinct
     */
    public function __construct($precinct)
    {
        $this->core = \lib\Core::getInstance();

        $params = array();
        parse_str($precinct, $params);

        // store precinct.
        if (isset($params['ward']) && isset($params['division'])) {
            $this->precinct = sprintf('%02d', $params['ward']) . sprintf('%02d', $params['division']);
        } else if (is_numeric($precinct)) {
            $this->precinct = sprintf('%04d', $precinct);
        }
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
        if ($this->precinct) {
            $sql = ' SELECT `ward`, `division`, `precinct`, `pin_address`, `display_address`, `zip_code`, `location`, `display_location`, `building`, `parking`, `lat`, `lng`, `elat`, `elng`, `alat`, `alng` FROM `pollingplaces`, `precincts` WHERE `published` = 1 AND `pollingplaces`.`id`=`precincts`.`pollingplace_id` AND `precincts`.`precinct` = :precinct ';

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->bindParam(':precinct', $this->precinct, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($data)) {
                    $features=array();
                    $features['attributes'] = $data;
                    $status = 'success';
                }
            }
        }

        return json_encode(array('status'=>$status, 'features'=>$features));
    }
}
