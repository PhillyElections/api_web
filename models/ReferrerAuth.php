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
use Psr\Http\Message\ServerRequestInterface as Request;

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
    protected $table = 'referrers';

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     */
    public function __construct()
    {
        d(1);
        exit;
        $this->core = \lib\Core::getInstance();
        $this->request = new Request();
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A JsonP function.
     */
    public function authenticate()
    {
        $referrer = (string) $this->request->getHeader('host');
        $value = false;
        $sql = ' SELECT COUNT(name) FROM ' . $this->table . ' WHERE name = :a1 ';
        d($referrer);
        exit;
        $stmt = $this->core->dbh->prepare($sql);
        $stmt->bindParam(':a1', $referrer, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $value = $stmt->fetch(PDO::FETCH_ASSOC);
            d($value);
            exit;
        }

        return $value;
    }
}
