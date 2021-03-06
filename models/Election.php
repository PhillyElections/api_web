<?php

/**
 * Election model.
 *
 * Election API model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */

namespace models;

/**
 * Election model: return the correct upcoming primary or general from any given date (YYYY-MM-DD)
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
     * Constructor: try to read the date, set message on failure.
     *
     * @param mixed $date
     */
    public function __construct($date = '')
    {
        $this->actual_request = $date;
        if (!$date) {
            $date = date("Y-m-d");
        }

        if (\DateTime::createFromFormat('Y-m-d', $date) == false) {
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
     * Fetch an election.
     *
     * @return     boolean  A json object.
     */
    public function fetch()
    {
        if ($this->message) {
            return json_encode(array('message' => $this->message));
        }
        return json_encode($this->getNextElection());
    }

    /**
     * Get next election
     *
     */
    private function getNextElection()
    {

        $year = $this->date->format("Y");
        // set general first
        $this->setGeneral($year);

        // if date is greater than general, we increment year and re-set general
        if ($this->date > $this->general) {
            $year++;
            $this->setGeneral($year);
        }

        // presidential primaries are 4th tuesday of april, all else third tuesday in may
        if ($year % 4 == 0) {
            $this->setPresidentialPrimary($year);
        } else {
            $this->setPrimary($year);
        }
        $twentyTwentyOverrideDate = new \DateTime('2020-06-02');
        // if date is greater than primary, return general
        if ($this->date > $this->primary && $this->date > $twentyTwentyOverrideDate) {
            return $this->getReturnArray($this->general, 'general');
        }

        // default: return primary
        return $this->getReturnArray($this->primary, 'primary');
    }

    private function getReturnArray($election, $type)
    {
        // 2020 override code
        // if the returned election date is the canonical correct date of the 2020 primary...
        if ($election->format("Y-m-d") == '2020-04-28') {
            // return the alternate date in the array
            return array(
                'election_type' => $type,
                'election_date' => '2020-06-02',
                'from_date' => $this->date->format("Y-m-d"),
                'actual_request' => $this->actual_request,
            );
        }

        // original code
        return array(
            'election_type' => $type,
            'election_date' => $election->format("Y-m-d"),
            'from_date' => $this->date->format("Y-m-d"),
            'actual_request' => $this->actual_request,
        );
    }

    private function setPresidentialPrimary($year)
    {
        $this->primary = new \DateTime("fourth tuesday of april $year");
    }

    private function setPrimary($year)
    {
        $this->primary = new \DateTime("third tuesday of may $year");
    }

    private function setGeneral($year)
    {
        $first_monday_november = new \DateTime("first monday of november $year");
        $this->general = $first_monday_november->modify("this tuesday");
    }
}
