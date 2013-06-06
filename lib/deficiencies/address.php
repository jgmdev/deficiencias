<?php
/**
 * @author Omar Soto <omarpr@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Deficiencies;

/**
 * @Entity(repositoryClass="Address") @Table(name="address")
 */
class Address 
{
    /**
     * @Column(type="string")
     */
    public $line1;
    
    /**
     * @Column(type="string")
     */
    public $line2;
    
    /**
     * @Column(type="integer")
     */
    public $zipcode;
    
    /**
     * @Column(type="string")
     */
    public $city;
    
    /**
     * @Column(type="string")
     */
    public $country;
}


?>