<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Deficiencies;

/**
 * List of type of deficiencies. 
 */
class Types
{
    const HOLE = 0;
    const WATER_TUBE = 1;
    const SIGNAL_LIGHT = 2;
    const COLLAPSE = 3;
    const POWER_LINE = 4;
    CONST SEWERS = 5;
    const LIGHTING = 6;
    const WATER_LEAKS = 7;
    
    public static function getAll() {
        $def_types = array(
            self::HOLE => t('Hole'),
            self::WATER_TUBE => t('Water tube'),
            self::SIGNAL_LIGHT => t('Signal light'),
            self::COLLAPSE => t('Collapse'),
            self::POWER_LINE => t('Power line'),
            self::SEWERS => t('Sewers'),
            self::LIGHTING => t('Lighting'),
            self::WATER_LEAKS => t('Water leaks')
        );
        
        return $def_types;
    }
    
    public static function getType($id) {
        $def_types = self::getAll();
        return (isset($def_types[$id])) ? $def_types[$id] : null;
    }
}

?>
