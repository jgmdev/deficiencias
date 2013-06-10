<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Deficiencies;

/**
 * List of type of deficiencies. 
 */
class DeficiencyTypes
{
    const HOLE = 0;
    const WATER_TUBE = 1;
    const SIGNAL_LIGHT = 3;
    const COLLAPSE = 4;
    const POWER_LINE = 5;
    
    public static function getAll() {
        $def_types = array(
            self::HOLE => t('Hole'),
            self::WATER_TUBE => t('Water tube'),
            self::SIGNAL_LIGHT => t('Signal light'),
            self::COLLAPSE => t('Collapse'),
            self::POWER_LINE => t('Power line')
        );
        
        return $def_types;
    }
    
    public static function getType($id) {
        $def_types = self::getAll();
        return (isset($def_types[$id])) ? $def_types[$id] : null;
    }
}

?>
