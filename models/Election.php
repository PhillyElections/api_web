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
class Election
{
    protected $core;
    protected $election;

    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     * @param mixed $precinct
     */
    public function __construct()
    {
        // nothing to see here
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A json object.
     */
    public function fetch()
    {
        return json_encode($this->getNextElection());
    }

    /**
     * determine next election
     *
     */
    private function getNextElection() {
        $now = new \DateTime("midnight today");
        $year = date("Y");
        $election_year = $year % 4;

        $first_monday_november = new \DateTime("first monday of november");
        $general = $first_monday_november->modify("this tuesday");

        if ($election_year == 0) {
            $primary = new \DateTime("fourth tuesday of april");
        } else {
            $primary = new \DateTime("third tuesday of may");
        }

        if ($now > $primary) {
            $year++;
            $next_primary = new \DateTime("third tuesday of may $year");
            return array('type'=>'primary', 'date'=>$next_primary->format("Y-m-d"));
        }

        if ($now > $primary) {
            return array('type'=>'general', 'date'=>$general->format("Y-m-d"));
        }

        return array('type'=>'primary', 'date'=>$primary->format("Y-m-d"));
    }
}