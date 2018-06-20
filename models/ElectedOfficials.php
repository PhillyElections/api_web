<?php
/**
 * ElectedOfficials model.
 *
 * ElectedOfficials API's model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */

namespace models;

use PDO;

/**
 * ElectedOfficials model.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class ElectedOfficials
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
//        parse_str($precinct, $params);

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
            $sql = ' SELECT * FROM `elected_officials` WHERE `published` = 1 ORDER BY office_level, office, display_order ';

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->bindParam(':precinct', $this->precinct, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($data)) {
/*                    $features=array();
                    $features['attributes'] = $data;
*/                    $status = 'success';
                }
            }
        }

        return json_encode(array('status'=>$status, 'data'=>$data));
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A json object.
     */
    public function fetchAll()
    {
        $features = false;
        $status = 'failure';
        $data = array();
        $sql = ' SELECT * FROM `elected_officials` WHERE `published` = 1 ORDER BY office_level, office, display_order ';

        $stmt = $this->core->dbh->prepare($sql);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($data)) {
                $status = 'success';
            }
        }

        return json_encode(array('status'=>$status, 'data'=>$data));
    }
}
