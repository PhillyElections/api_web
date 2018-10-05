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
    protected $fields = 
        '`id`,
        `office_level`,
        `leadership_role`,
        `office`,
        `office_label`,
        `district_label`,
        `district`,
        `first_name`,
        `middle_name`,
        `last_name`,
        `suffix`,
        `party`,
        `first_elected`,
        `next_election`,
        `website`,
        `email`,
        `main_contact_address_1`,
        `main_contact_address_2`,
        `main_contact_city`,
        `main_contact_state`,
        `main_contact_zip`,
        if(`main_contact_phone_1`,`main_contact_phone_1`,"") `main_contact_phone_1`,
        if(`main_contact_phone_2`,`main_contact_phone_2`,"") `main_contact_phone_2`,
        `main_contact_fax`,
        `local_contact_1_address_1`,
        `local_contact_1_address_2`,
        `local_contact_1_city`,
        `local_contact_1_state`,
        `local_contact_1_zip`,
        if(`local_contact_1_phone_1`,`local_contact_1_phone_1`,"") `local_contact_1_phone_1`,
        if(`local_contact_1_phone_2`,`local_contact_1_phone_2`,"") `local_contact_1_phone_2`,
        if(`local_contact_1_fax`, `local_contact_1_fax`,"") `local_contact_1_fax`,
        `local_contact_2_address_1`,
        `local_contact_2_address_2`,
        `local_contact_2_city`,
        `local_contact_2_state`,
        `local_contact_2_zip`,
        if(`local_contact_2_phone_1`,`local_contact_2_phone_1`,"") `local_contact_2_phone_1`,
        if(`local_contact_2_phone_2`,`local_contact_2_phone_2`,"") `local_contact_2_phone_2`,
        if(`local_contact_2_fax`,`local_contact_2_fax`,"") `local_contact_2_fax`,
        `local_contact_3_address_1`,
        `local_contact_3_address_2`,
        `local_contact_3_city`,
        `local_contact_3_state`,
        `local_contact_3_zip`,
        if(`local_contact_3_phone_1`,`local_contact_3_phone_1`,"") `local_contact_3_phone_1`,
        if(`local_contact_3_phone_2`,`local_contact_3_phone_2`,"") `local_contact_3_phone_2`,
        if(`local_contact_3_fax`,`local_contact_3_fax`,"") `local_contact_3_fax`,
        `display_order`,
        `published`';

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     * @param mixed $precinct
     */
    public function __construct($data = false)
    {
        $this->core = \lib\Core::getInstance();
        if (!$data) {
            return;
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
            $sql = ' SELECT ' . $this->fields . ' FROM `elected_officials` WHERE `published` = 1 ORDER BY office_level, office, display_order ';

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->bindParam(':precinct', $this->precinct, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($data)) {
                   $status = 'success';
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
        $sql = ' SELECT ' . $this->fields . ' FROM `elected_officials` WHERE `published` = 1 ORDER BY office_level, office, display_order ';

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
