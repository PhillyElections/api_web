<?php
/**
 * Indexes model.
 *
 * Indexes API's model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */

namespace models;

use PDO;

/**
 * Indexes Model 
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
        $this->queried = $queried;
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

    public function fetchList()
    {
	if ($this->queried && in_array($this->queried, array('division','ward', 'council', 'parep', 'pasenate', 'uscongress'))) {
   	    switch ($this->queried) {
                case 'division':
                    return $this->fetchAllDivs();
                break;
                case 'ward':
                    return $this->fetchAllWards();
                break;
                case 'council':
                    return $this->fetchAllCouncil();
                break;
                case 'pasenate':
                    return $this->fetchAllStateSenate();
                break;
                case 'parep':
                    return $this->fetchAllStateHouse();
                break;
                case 'uscongress':
                    return $this->fetchAllUSCongress();
                break;
            }
	}
	return json_encode(array('status'=>'404','message'=>'Nothing to see here.'));
    }    

    public function fetchAllDivs() {
        $sql = ' SELECT distinct precinct as division_id, ward, division FROM ' . $this->table_name . '  ';
        $query = $this->core->dbh->query($sql);
        return json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }

    public function fetchAllWards() {
        $sql = ' SELECT distinct ward FROM ' . $this->table_name . '  ';
        $query = $this->core->dbh->query($sql);
        return json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }

    public function fetchAllCouncil() {
        $sql = ' SELECT distinct city_district as council_district FROM ' . $this->table_name . ' ';
        $query = $this->core->dbh->query($sql);
        return json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }

    public function fetchAllStateSenate() {
        $sql = ' SELECT distinct state_senate as state_senate_district FROM ' . $this->table_name . ' ';
        $query = $this->core->dbh->query($sql);
        return json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }

    public function fetchAllStateHouse() {
        $sql = ' SELECT distinct state_house as state_representative_district FROM ' . $this->table_name . ' ';
        $query = $this->core->dbh->query($sql);
        return json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }

    public function fetchAllUsCongress() {
        $sql = ' SELECT distinct federal_house as congressional_district FROM ' . $this->table_name . ' ';
        $query = $this->core->dbh->query($sql);
        return json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }
}
