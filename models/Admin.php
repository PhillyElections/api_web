<?php
/**
 * Admin model.
 *
 * API admin model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */

namespace models;

/**
 * Admin class.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class Admin
{
    protected $core;

    /**
     * Constructor: get core, call setup to process request.
     */
    public function __construct()
    {
        $this->core = \lib\Core::getInstance();

        // process _REQUEST.
        $this->setup();
    }

    public function getContent()
    {
        return array('title' => 'Admin Title', 'header' => 'Admin Header', 'body' => 'Admin Body');
    }
}
