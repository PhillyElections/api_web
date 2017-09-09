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
    protected $request;
    protected $api;
    protected $table = 'referrers';

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     * @param mixed $request
     */
    public function __construct($request, $api)
    {
        $this->core = \lib\Core::getInstance();
        $this->api = &$api;
        $this->request = &$request;
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
        $referrer = $this->request->getHeader('host')[0];
        $sql = ' SELECT COUNT(`name`) FROM `' . $this->table . '` WHERE `api` = :api AND `name` = :referrer ';

        $stmt = $this->core->dbh->prepare($sql);
        $stmt->bindParam(':api', $this->api, PDO::PARAM_STR);
        $stmt->bindParam(':referrer', $referrer, PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ((int) $stmt->fetchColumn() >= 1) {
                // then we can go ahead and process
                return true;
            }
        }

        return false;
    }
}
