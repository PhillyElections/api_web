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
        $this->actual_request = $date;
        if (!$date) $date = date("Y-m-d");
        if (\DateTime::createFromFormat('Y-m-d', $date) == FALSE) {
            $this->message = "Had trouble reading your date.  Preferred format is: YYYY-MM-DD.";
            return;
        }
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

        $first_monday_november = new \DateTime("first monday of november $year");
        $general = $first_monday_november->modify("this tuesday");

        if ($this->date > $general) {
            $year++;
        }

        if ($year % 4 == 0) {
            $primary = new \DateTime("fourth tuesday of april $year");
        } else {
            $primary = new \DateTime("third tuesday of may $year");
        }

        if ($this->date > $primary) {
            return array('election_type'=>'general', 'election_date'=>$general->format("Y-m-d"), 'from_date'=>$this->date->format("Y-m-d"), 'actual_request'=>$this->actual_request);
        }

        return array('election_type'=>'primary', 'election_date'=>$primary->format("Y-m-d"), 'from_date'=>$this->date->format("Y-m-d"), 'actual_request'=>$this->actual_request);
    }
}