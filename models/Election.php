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
    protected $date;
    protected $message;
    /**
     * Constructor: get core, call setup to process request.
     *
     * @param mixed $address
     * @param mixed $precinct
     */
    public function __construct($date = '')
    {
        print_r($date);
        if (!$date) $date = date("Y-m-d");
        try {
            $this->date = new \DateTime("midnight $date");
        } catch (Exception $e) {
            $this->message = "Had trouble reading your date.  Preferred format is: YYYY-MM-DD.";
        }
    }

    /**
     * Fetch results based on setup().
     *
     * @return     boolean  A json object.
     */
    public function fetch()
    {
        if ($this->message) {
            return json_encode(array('message'=>$this->message));
        }
        return json_encode($this->getNextElection());
    }

    /**
     * determine next election
     *
     */
    private function getNextElection() {

        $year = $this->date->format("Y");
        $election_year = $year % 4;

        $first_monday_november = new \DateTime("first monday of november");
        $general = $first_monday_november->modify("this tuesday");

        if ($election_year == 0) {
            $primary = new \DateTime("fourth tuesday of april");
        } else {
            $primary = new \DateTime("third tuesday of may");
        }

        if ($this->date > $general) {
            $year++;
            $next_primary = new \DateTime("third tuesday of may $year");
            return array('type'=>'primary', 'date'=>$next_primary->format("Y-m-d"));
        }

        if ($this->date > $primary) {
            return array('type'=>'general', 'date'=>$general->format("Y-m-d"));
        }

        return array('type'=>'primary', 'date'=>$primary->format("Y-m-d"));
    }
}