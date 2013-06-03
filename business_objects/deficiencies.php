<?php

/**
 * @Entity(repositoryClass="Deficiencias") @Table(name="deficiencias")
 */
class Deficiencies {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    
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
    protected $status;
    
    /**
     * @Column(type="last_update")
     */
    protected $last_update;
}


?>