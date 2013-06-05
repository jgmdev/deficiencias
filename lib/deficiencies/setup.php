<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

class Setup
{
    /**
     * Disable Constructor
     */
    private function __construct() {}
    
    public function Database()
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        //Deficiency Table
        $deficiency_table = new \Cms\DBAL\Query\Table('deficiencies');
        
        $deficiency_table->AddIntegerField('id')
            ->AddIntegerField('type')
            ->
        ;
    }
}
?>
