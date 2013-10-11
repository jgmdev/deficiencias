<?php
/**
 * @author Omar Soto <omarpr@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Deficiencies;

/**
 * @Entity(repositoryClass="Deficiency") @Table(name="deficiency")
 */
class Deficiency {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    public $id;
    
    /**
     * @Column(type="integer")
     */
    public $type;
    
    /**
     * Physical address of the report.
     * @var \Deficiencies\Address;
     */
    public $address;
    
    /**
     * @Column(type="string")
     */
    public $username;
    
    /**
     * @Column(type="string")
     */
    public $latitude;
    
    /**
     * @Column(type="string")
     */
    public $longitude;
    
    /**
     * @Column(type="string")
     */
    public $photo;
    
    /**
     * @Column(type="string")
     */
    public $comments;
    
    /**
     * @var string
     */
    public $work_comments;
    
    /**
     * @Column(type="integer")
     */
    public $reports_count;
    
    /**
     * @Column(type="string")
     */
    public $status;
    
    /**
     * @var int
     */
    public $resolution_status;
    
    /**
     * @var int
     */
    public $priority;
    
    /**
     * @var string
     */
    public $assigned_to;
    
    /**
     * @var int
     */
    public $reopened_count;
    
    /**
     * @Column(type="datetime")
     */
    public $report_timestamp;
    
    public $report_day;
    
    public $report_month;
    
    public $report_year;
    
    /**
     * @Column(type="datetime")
     */
    public $last_update;
    
    /**
     * @var string
     */
    public $last_update_by;
    
    public function __construct()
    {
        $this->address = new Address;
    }
}


?>