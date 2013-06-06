<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Deficiencies;

/**
 * List of deficiency status.
 */
class DeficiencyStatus
{
    const UNFIXED = 0;
    const FIXED = 1;
    const IN_PROCESS = 2;
    
    public static function getAll() {
        $def_status = array(
                            UNFIXED => t('Unfixed'),
                            FIXED => t('Fixed'),
                            IN_PROCESS => t('In process'),
                            );
        
        return $def_status;
    }
    
    public function getStatus($id) {
        $def_status = $this->getAll();
        return (isset($def_status[$id])) ? $def_status[$id] : null;
    }
}

?>
