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
                            HOLE => t('Hole'),
                            WATER_TUBE => t('Water tube'),
                            SIGNAL_LIGHT => t('Signal light'),
                            COLLAPSE => t('Collapse'),
                            POWER_LINE => t('Power line')
                            );
        
        return $def_types;
    }
    
    public static function getType($id) {
        $def_types = $this->getAll();
        return (isset($def_types[$id])) ? $def_types[$id] : null;
    }
}

?>
