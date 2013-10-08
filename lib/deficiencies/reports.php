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
        
        date_default_timezone_set('UTC');
        
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
            ->Insert(
                'city', 
                str_ireplace(
                    array("á", "é", "í", "ó", "ú", "ä", "ë", "ï", "ö", "ü", "ñ",
                    "Á", "É", "Í", "Ó", "Ú", "Ä", "Ë", "Ï", "Ö", "Ü", "Ñ"), 
                    array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n",
                    "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n"), 
                    $data->address->city
                ),
                FieldType::TEXT
            )
            ->Insert('country', $data->address->country, FieldType::TEXT)
            ->Insert('zipcode', $data->address->zipcode, FieldType::TEXT)
            ->Insert('photo', $data->photo, FieldType::TEXT)
        ;
        
        $db->Insert($insert);
        
        return $db->LastInsertID();
    }
    
    public static function AddConfirm($id)
    {
        $update = new \Cms\DBAL\Query\Update('deficiencies');
        $update->Increment('reports_count')
            ->WhereEqual('id', $id, FieldType::INTEGER)
        ;

        $db = \Cms\System::GetRelationalDatabase();
        $db->Update($update);
    }
    
    public static function Edit($id, $data)
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        date_default_timezone_set('UTC');
        
        $update = new \Cms\DBAL\Query\Update('deficiencies');
        $update->Update('type', $data->type, FieldType::INTEGER)
            ->Update('latitude', $data->latitude, FieldType::REAL)
            ->Update('longitude', $data->longitude, FieldType::REAL)
            ->Update('status', \Deficiencies\DeficiencyStatus::UNFIXED, FieldType::INTEGER)
            ->Update('comments', $data->comments, FieldType::TEXT)
            ->Update('last_update', time(), FieldType::INTEGER)
            ->Update('line1', $data->address->line1, FieldType::TEXT)
            ->Update(
                'city', 
                str_ireplace(
                    array("á", "é", "í", "ó", "ú", "ä", "ë", "ï", "ö", "ü", "ñ",
                    "Á", "É", "Í", "Ó", "Ú", "Ä", "Ë", "Ï", "Ö", "Ü", "Ñ"), 
                    array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n",
                    "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n"), 
                    $data->address->city
                ),
                FieldType::TEXT
            )
            ->Update('country', $data->address->country, FieldType::TEXT)
            ->Update('zipcode', $data->address->zipcode, FieldType::TEXT)
            ->Update('photo', $data->photo, FieldType::TEXT)
            ->WhereEqual('id', $id, FieldType::INTEGER)
        ;
        
        $db->Update($update);
    }
    
    public static function GetData($id)
    {
        $select = new \Cms\DBAL\Query\Select('deficiencies');
        $select->SelectAll()
            ->WhereEqual('id', $id, FieldType::INTEGER)
        ;

        $db = \Cms\System::GetRelationalDatabase();
        $db->Select($select);

        return $db->FetchArray();
    }
    
    public static function Delete($id)
    {
        $delete = new \Cms\DBAL\Query\Delete('deficiencies');
        $delete->WhereEqual('id', $id, FieldType::INTEGER);
        
        $db = \Cms\System::GetRelationalDatabase();
        $db->Delete($delete);
    }
    
    /**
     * Checks if a deficiency already exists.
     * @param \Deficiencies\Deficiency $data
     * @return boolean
     */
    public static function Exists(\Deficiencies\Deficiency $data)
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        $city = str_ireplace(
            array("á", "é", "í", "ó", "ú", "ä", "ë", "ï", "ö", "ü", "ñ",
            "Á", "É", "Í", "Ó", "Ú", "Ä", "Ë", "Ï", "Ö", "Ü", "Ñ"), 
            array("a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n",
            "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n"), 
            $data->address->city
        );
        
        $select = new \Cms\DBAL\Query\Select('deficiencies');
        $select->SelectAll()
            ->WhereEqual('line1', $data->address->line1, FieldType::TEXT)
            ->AndOp()
            ->WhereEqual('city', $city, FieldType::TEXT)
            ->AndOp()
            ->WhereEqual('type', $data->type, FieldType::INTEGER)
        ;
        
        $db->Select($select);
        
        if($db->FetchArray())
            return true;
        
        return false;
    }
}

?>
