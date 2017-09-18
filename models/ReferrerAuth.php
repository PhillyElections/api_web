<?php
/**
 * ReferrerAuth model.
 *
 * ReferrerAuth model
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
class ReferrerAuth
{
    protected $core;
    protected $referrer;
    protected $api;
    protected $table = 'referrers';

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     * @param mixed $request
     * @param mixed $referrer
     * @param mixed $api
     */
    public function __construct($referrer, $api)
    {
        $this->core = \lib\Core::getInstance();
        $this->api = &$api;
        $this->referrer = &$referrer;
    }

    /**
     * authenticate based on __construc() values.
     *
     * @return     boolean  A JsonP function.
     */
    public function authenticate()
    {
        $sql = ' SELECT COUNT(`name`) FROM `' . $this->table . '` WHERE `api` = :api ';

        $stmt = $this->core->dbh->prepare($sql);
        $stmt->bindParam(':api', $this->api, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // if we have no rows restricting this API when we fetch 0
            if ((int) $stmt->fetchColumn() == 0) {
                // then we can go ahead and process
                return true;
            }
        }

        // Ok, we're still here, so we need to reinitialize for the real check.
        $sql = ' SELECT COUNT(`name`) FROM `' . $this->table . '` WHERE `api` = :api AND `name` = :referrer ';

        $stmt = $this->core->dbh->prepare($sql);
        $stmt->bindParam(':api', $this->api, PDO::PARAM_STR);
        $stmt->bindParam(':referrer', $this->referrer, PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ((int) $stmt->fetchColumn() >= 1) {
                // then we can go ahead and process
                return true;
            }
        }

        return false;
    }
}
