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
    
    public static function Database()
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        //Deficiency Table
        $deficiency_table = new \Cms\DBAL\Query\Table('deficiencies');
        
        $deficiency_table->AddIntegerField('id')
            ->AddIntegerField('type')
            ->AddTextField('username')
            ->AddRealField('latitude')
            ->AddRealField('longitude')
            ->AddTextField('photo')
            ->AddTextField('comments')
            ->AddIntegerField('reports_count')
            ->AddIntegerField('status')
            ->AddIntegerField('report_timestamp')
            ->AddIntegerField('report_day')
            ->AddIntegerField('report_month')
            ->AddIntegerField('report_year')
            ->AddIntegerField('last_update')
            ->AddPrimaryKey('id')
        ;
        
        $db->CreateTable($deficiency_table);
        
        //Address Table
        $address_table = new \Cms\DBAL\Query\Table('address');
        
        $address_table->AddIntegerField('deficiency_id')
            ->AddTextField('line1')
            ->AddTextField('line2')
            ->AddTextField('zipcode')
            ->AddTextField('city')
            ->AddTextField('country')
        ;
        
        $db->CreateTable($address_table);
    }
}
?>
