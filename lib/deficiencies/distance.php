<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Deficiencies;

/**
 * Function to calculate distance.
 */
class Distance
{

    const KILOMETERS = 'K';
    const MILES = 'M';
    const NAUTIC_MILES = 'N';

    //Disable constructor.
    private function __construct(){}

    /**
     * Calculate the distance in miles between a pair of coordinates.
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    public function GetMiles($lat1, $lon1, $lat2, $lon2)
    {
        return self::Get($lat1, $lon1, $lat2, $lon2, self::MILES);
    }

    /**
     * Calculate the distance in kilometers between a pair of coordinates.
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    public function GetKilometers($lat1, $lon1, $lat2, $lon2)
    {
        return self::Get($lat1, $lon1, $lat2, $lon2, self::KILOMETERS);
    }
    
    /**
     * Calculate the distance in nautic miles between a pair of coordinates.
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    public function GetNauticMiles($lat1, $lon1, $lat2, $lon2)
    {
        return self::Get($lat1, $lon1, $lat2, $lon2, self::NAUTIC_MILES);
    }

    /**
     * Calculate the distance between a pair of coordinates.
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @param string $unit
     * @return float
     */
    private static function Get($lat1, $lon1, $lat2, $lon2, $unit = self::MILES)
    {
        $theta = $lon1 - $lon2;

        $dist = sin(deg2rad($lat1)) *
            sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            cos(deg2rad($theta))
        ;

        $dist = acos($dist);
        $dist = rad2deg($dist);

        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if($unit == self::KILOMETERS)
        {
            return ($miles * 1.609344);
        }
        elseif($unit == self::NAUTIC_MILES)
        {
            return ($miles * 0.8684);
        }
        else
        {
            return $miles;
        }
    }

}

?>
