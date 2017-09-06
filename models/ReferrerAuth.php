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
    protected $table = 'referrers';

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     * @param mixed $request
     */
    public function __construct($request)
    {
        $this->core = \lib\Core::getInstance();
        $this->request = &$request;
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A JsonP function.
     */
    public function authenticate()
    {
        $referrer = $this->request->getHeader('host')[0];
        $value = false;
        $sql = ' SELECT COUNT(name) FROM ' . $this->table . ' WHERE name = :a1 ';

        $stmt = $this->core->dbh->prepare($sql);
        $stmt->bindParam(':a1', $referrer, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $value = $stmt->fetchColumn(PDO::FETCH_ASSOC);
            d($value);
            exit;
        }

        return $value;
    }
}
