<?php
/**
 * @author Omar Soto <omarpr@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Deficiencies;

/**
 * A deficiency that has been reported.
 */
class Deficiency {
    
    /**
     * @var int
     */
    public $id;
    
    /**
     * Type of deficiency like signal light, power line, etc...
     * @var int
     */
    public $type;
    
    /**
     * Physical address of the deficiency.
     * @var \Deficiencies\Address;
     */
    public $address;
    
    /**
     * Person who created the report.
     * @var string
     */
    public $username;
    
    /**
     * @var float
     */
    public $latitude;
    
    /**
     * @var float
     */
    public $longitude;
    
    /**
     * @var string
     */
    public $photo;
    
    /**
     * @var string
     */
    public $comments;
    
    /**
     * @var string
     */
    public $work_comments;
    
    /**
     * The amount of times this deficiency has been reported.
     * @var int
     */
    public $reports_count;
    
    /**
     * Current administrative status.
     * @var int
     */
    public $status;
    
    /**
     * Status of the deficiency like unfixed, fixed, etc...
     * @var int
     */
    public $resolution_status;
    
    /**
     * @var int
     */
    public $priority;
    
    /**
     * Username of the person in charge of giving follow up to the report.
     * @var string
     */
    public $assigned_to;
    
    /**
     * If the report was closed but a same one was submitted this value
     * is incremented.
     * @var int
     */
    public $reopened_count;
    
    /**
     * The full timestamp of the date the report was created.
     * @var int
     */
    public $report_timestamp;
    
    /**
     * Day the report was opened
     * @var int
     */
    public $report_day;
    
    /**
     * Month the report was opened
     * @var int
     */
    public $report_month;
    
    /**
     * Year the report was opened
     * @var int
     */
    public $report_year;
    
    /**
     * A
     * @var int
     */
    public $last_update;
    
    /**
     * Username of the last person that modified the report.
     * @var string
     */
    public $last_update_by;
    
    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->address = new Address;
    }
    
    public function GetPicturePath()
    {
        
    }
}


?>