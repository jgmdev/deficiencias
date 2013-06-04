<?php

/**
 * @Entity(repositoryClass="Address") @Table(name="address")
 */
class Address {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    
    /**
     * @Column(type="string")
     */
    protected $line1;
    
    /**
     * @Column(type="string")
     */
    protected $line2;
    
    /**
     * @Column(type="integer")
     */
    protected $zipcode;
    
    /**
     * @Column(type="string")
     */
    protected $city;
    
    /**
     * @Column(type="string")
     */
    protected $country;
}


?>