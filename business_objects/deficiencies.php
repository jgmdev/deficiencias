<?php

/**
 * @Entity(repositoryClass="Deficiencias") @Table(name="deficiencias")
 */
class Deficiencies {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $username;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $latitude;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $longitud;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $photo;
}


?>