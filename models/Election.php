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
    protected $primary;
    protected $general;

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

        // set general first
        $this->setGeneral($year);

        // if this->date is greater than general, we increment year and re-set general
        if ($this->date > $general) {
            $year++;
            $this->setGeneral($year);
        }

        // presidential primaries are 4th tuesday of april, all else third tuesday in may
        if ($year % 4 == 0) {
            $this->setPresidentialPrimary($year);
        } else {
            $this->setPrimary($year);
        }

        // if the date is greater than primary, return general
        if ($this->date > $primary) {
            return $this->getReturnArray($this->general, 'general');
        }

        // default: return primary
        return $this->getReturnArray($this->primary, 'primary');
    }

    private function getReturnArray($election, $type) {
        return array('election_type'=>$type, 'election_date'=>$election->format("Y-m-d"), 'from_date'=>$this->date->format("Y-m-d"), 'actual_request'=>$this->actual_request);
    }

    private function setPresidentialPrimary($year) {
        $this->primary = new \DateTime("fourth tuesday of april $year");
    }

    private function setPrimary($year) {
        $this->primary = new \DateTime("third tuesday of may $year");
    }

    private function setGeneral($year) {
        $first_monday_november = new \DateTime("first monday of november $year");
        $this->general = $first_monday_november->modify("this tuesday");
    }

}