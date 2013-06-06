<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

use Cms\Enumerations\FieldType;

/**
 * Function to handle reports
 */
class Reports
{
    public static function Add(\Deficiencies\Deficiency $data)
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        $insert = new \Cms\DBAL\Query\Insert('deficiencies');
        $insert->Insert('type', $data->type, FieldType::INTEGER)
            ->Insert('latitude', $data->latitude, FieldType::REAL)
            ->Insert('longitude', $data->longitude, FieldType::REAL)
            ->Insert('status', \Deficiencies\DeficiencyStatus::UNFIXED, FieldType::INTEGER)
            ->Insert('comments', $data->comments, FieldType::TEXT)
            ->Insert('reports_count', 1, FieldType::INTEGER)
            ->Insert('report_timestamp', time(), FieldType::INTEGER)
            ->Insert('report_day', date('d', time()), FieldType::INTEGER)
            ->Insert('report_month', date('n', time()), FieldType::INTEGER)
            ->Insert('report_year', date('Y', time()), FieldType::INTEGER)
            ->Insert('last_update', 0, FieldType::INTEGER)
            ->Insert('line1', $data->address->line1, FieldType::TEXT)
            ->Insert('city', $data->address->city, FieldType::TEXT)
            ->Insert('country', $data->address->country, FieldType::TEXT)
            ->Insert('zipcode', $data->address->zipcode, FieldType::TEXT)
        ;
        
        $db->Insert($insert);
    }
    
    public static function Edit($id, $data)
    {
        
    }
    
    public static function GetData($id)
    {
        
    }
    
    public static function Delete($id)
    {
        
    }
}

?>
