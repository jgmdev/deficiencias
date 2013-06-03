<?php

/**
 * @Entity(repositoryClass="Deficiencias") @Table(name="deficiencias")
 */
class Deficiencias {
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
    protected $latitud;
    
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