<?php

/**
 * @Entity(repositoryClass="Deficiency") @Table(name="deficiency")
 */
class Deficiency {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    
    /**
     * @Column(type="integer")
     */
    protected $type;
    
    /**
     * @OneToOne(targetEntity="Address")
     * @JoinColumn(name="address_id", referencedColumnName="id")
     **/
    protected $address;
    
    /**
     * @Column(type="string")
     */
    protected $username;
    
    /**
     * @Column(type="string")
     */
    protected $latitude;
    
    /**
     * @Column(type="string")
     */
    protected $longitude;
    
    /**
     * @Column(type="string")
     */
    protected $photo;
    
    /**
     * @Column(type="string")
     */
    protected $comments;
    
    /**
     * @Column(type="integer")
     */
    protected $reports_count;
    
    /**
     * @Column(type="string")
     */
    protected $status;
    
    /**
     * @Column(type="datetime")
     */
    protected $last_update;
}


?>