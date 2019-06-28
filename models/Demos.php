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
 * Demos model. 
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class Demos
{
    protected $core;

    /**
     * Constructor: get core, call setup to process request.
     *
     */
    public function __construct()
    {
        // call core with override set 
	$this->core = \lib\Core::getInstance(true);
    }

    /**
     * Fetch all results.
     *
     * @return     boolean  A json object.
     */
    public function fetch()
    {
        $features = false;
        $status = 'failure: ';

        $sql = ' 
        SELECT 
            `id`, `scheduler_id`, `setup`, `start`, `end`, `event_name`, `organization_name`, `location_name`, `display_title`, `address_street`, `address_extra`, `zip`, `contact`, `email`, `phone`, `ada_confirmed`, `special_ballot_needed`, `special_ballot_worker_id`, `staffer1_id`, `staffer2_id`, `staffer3_id`, lpad(`precinct`,4,"0") as `precinct`, `district`, `lat`, `lng`
		FROM 
			`jos_pv_demos_events` 
		WHERE 
			`published` = 1 
		;';

        $stmt = $this->core->dbh->prepare($sql);
        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($data)) {
                $features=array();
                foreach ($data as $datum) {
                    $lat=$datum['lat'];
                    $lng=$datum['lng'];
                    unset($datum['id'], $datum['lat'], $datum['lng']);
                    $features[] = array(
                        'coordinates'=>array($lat,$lng), 
                        'attributes'=>$datum
                    );
                }
                $status = 'success';
            }
        }
        return json_encode(array('status'=>$status, 'features'=>$features));
    }

    /**
     * Fetch Old results.
     *
     * @return     boolean  A json object.
     */
    public function fetchPast()
    {
        $features = false;
        $status = 'failure: ';

        $sql = ' 
        SELECT 
            `id`, `scheduler_id`, `setup`, `start`, `end`, `event_name`, `organization_name`, `location_name`, `display_title`, `address_street`, `address_extra`, `zip`, `contact`, `email`, `phone`, `ada_confirmed`, `special_ballot_needed`, `special_ballot_worker_id`, `staffer1_id`, `staffer2_id`, `staffer3_id`, lpad(`precinct`,4,"0") as `precinct`, `district`, `lat`, `lng`
        FROM 
            `jos_pv_demos_events` 
        WHERE 
            `published` = 1 
        AND 
            `start` <= now()
        ;';

        $stmt = $this->core->dbh->prepare($sql);
        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($data)) {
                $features=array();
                foreach ($data as $datum) {
                    $lat=$datum['lat'];
                    $lng=$datum['lng'];
                    unset($datum['id'], $datum['lat'], $datum['lng']);
                    $features[] = array(
                        'coordinates'=>array($lat,$lng), 
                        'attributes'=>$datum
                    );
                }
                $status = 'success';
            }
        }
        return json_encode(array('status'=>$status, 'features'=>$features));
    }

    /**
     * Fetch New results.
     *
     * @return     boolean  A json object.
     */
    public function fetchFuture()
    {
        $features = false;
        $status = 'failure: ';

        $sql = ' 
        SELECT 
            `id`, `scheduler_id`, `setup`, `start`, `end`, `event_name`, `organization_name`, `location_name`, `display_title`, `address_street`, `address_extra`, `zip`, `contact`, `email`, `phone`, `ada_confirmed`, `special_ballot_needed`, `special_ballot_worker_id`, `staffer1_id`, `staffer2_id`, `staffer3_id`, lpad(`precinct`,4,"0") as `precinct`, `district`, `lat`, `lng`
        FROM 
            `jos_pv_demos_events` 
        WHERE 
            `published` = 1 
        AND 
            LENGTH(`display_title`) > 1
        AND 
            `start` > now()
        ;';

        $stmt = $this->core->dbh->prepare($sql);
        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($data)) {
                $features=array();
                foreach ($data as $datum) {
                    $lat=$datum['lat'];
                    $lng=$datum['lng'];
                    unset($datum['id'], $datum['lat'], $datum['lng']);
                    $features[] = array(
                        'coordinates'=>array($lat,$lng), 
                        'attributes'=>$datum
                    );
                }
                $status = 'success';
            }
        }
        return json_encode(array('status'=>$status, 'features'=>$features));
    }
}
