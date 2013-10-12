<?php
/**
 * @author Omar Soto <omarpr@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Deficiencies;

/**
 * Represents the physical address of a reported deficiency.
 */
class Address 
{
    /**
     * @var string
     */
    public $line1;
    
    /**
     * @var string
     */
    public $line2;
    
    /**
     * @var string
     */
    public $zipcode;
    
    /**
     * @var string
     */
    public $city;
    
    /**
     * @var string
     */
    public $country;
}


?>