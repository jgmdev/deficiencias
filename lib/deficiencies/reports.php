<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

use Cms\Enumerations\FieldType;

/**
 * Functions to handle deficiency reports.
 */
class Reports
{
    /**
     * Add a new deficiency report to the database.
     * @param \Deficiencies\Deficiency $data
     * @return int Id of the created report.
     */
    public static function Add(\Deficiencies\Deficiency $data)
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        $insert = new \Cms\DBAL\Query\Insert('deficiencies');
        $insert->Insert('type', $data->type, FieldType::INTEGER)
            ->Insert('latitude', $data->latitude, FieldType::REAL)
            ->Insert('longitude', $data->longitude, FieldType::REAL)
            ->Insert('status', $data->status, FieldType::INTEGER)
            ->Insert('resolution_status', $data->resolution_status, FieldType::INTEGER)
            ->Insert('comments', $data->comments, FieldType::TEXT)
            ->Insert('reports_count', 1, FieldType::INTEGER)
            ->Insert('reopened_count', 0, FieldType::INTEGER)
            ->Insert('report_timestamp', time(), FieldType::INTEGER)
            ->Insert('report_day', date('d', time()), FieldType::INTEGER)
            ->Insert('report_month', date('n', time()), FieldType::INTEGER)
            ->Insert('report_year', date('Y', time()), FieldType::INTEGER)
            ->Insert('line1', $data->address->line1, FieldType::TEXT)
            ->Insert('line2', $data->address->line2, FieldType::TEXT)
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
            ->Insert('username', $data->username, FieldType::TEXT)
            ->Insert('priority', $data->priority, FieldType::INTEGER)
            ->Insert('assigned_to', $data->assigned_to, FieldType::TEXT)
            ->Insert('last_update', $data->last_update, FieldType::INTEGER)
            ->Insert('last_update_by', $data->last_update_by, FieldType::TEXT)
            ->Insert('work_comments', $data->work_comments, FieldType::TEXT)
        ;
        
        $db->Insert($insert);
        
        return $db->LastInsertID();
    }
    
    /**
     * Increment the reports count of a deficiency.
     * @param type $id
     */
    public static function AddConfirm($id)
    {
        $update = new \Cms\DBAL\Query\Update('deficiencies');
        $update->Increment('reports_count')
            ->WhereEqual('id', $id, FieldType::INTEGER)
        ;

        $db = \Cms\System::GetRelationalDatabase();
        $db->Update($update);
    }
    
    /**
     * Edit a reported deficiency.
     * @param int $id
     * @param \Deficiencies\Deficiency $data
     */
    public static function Edit($id, \Deficiencies\Deficiency $data)
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        $update = new \Cms\DBAL\Query\Update('deficiencies');
        $update->Update('type', $data->type, FieldType::INTEGER)
            ->Update('latitude', $data->latitude, FieldType::REAL)
            ->Update('longitude', $data->longitude, FieldType::REAL)
            ->Update('status', $data->status, FieldType::INTEGER)
            ->Update('resolution_status', $data->resolution_status, FieldType::INTEGER)
            ->Update('comments', $data->comments, FieldType::TEXT)
            ->Update('work_comments', $data->work_comments, FieldType::TEXT)
            ->Update('last_update', $data->last_update, FieldType::INTEGER)
            ->Update('last_update_by', $data->last_update_by, FieldType::TEXT)
            ->Update('line1', $data->address->line1, FieldType::TEXT)
            ->Update('line2', $data->address->line2, FieldType::TEXT)
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
            ->Update('priority', $data->priority, FieldType::INTEGER)
            ->Update('assigned_to', $data->assigned_to, FieldType::TEXT)
            ->Update('reopened_count', $data->reopened_count, FieldType::INTEGER)
            ->Update('reports_count', $data->reports_count, FieldType::INTEGER)
            ->WhereEqual('id', $id, FieldType::INTEGER)
        ;
        
        $db->Update($update);
    }
    
    /**
     * Gets a deficiency data.
     * @param int $id
     * @return \Deficiencies\Deficiency|bool
     */
    public static function GetData($id)
    {
        $select = new \Cms\DBAL\Query\Select('deficiencies');
        $select->SelectAll()
            ->WhereEqual('id', $id, FieldType::INTEGER)
        ;

        $db = \Cms\System::GetRelationalDatabase();
        $db->Select($select);

        $data = $db->FetchArray();
        
        if(!is_array($data))
            return $data;
        
        $deficiency = new Deficiency();
        $deficiency->assigned_to = $data['assigned_to'];
        $deficiency->comments = $data['comments'];
        $deficiency->id = $data['id'];
        $deficiency->last_update = $data['last_update'];
        $deficiency->last_update_by = $data['last_update_by'];
        $deficiency->latitude = $data['latitude'];
        $deficiency->longitude = $data['longitude'];
        $deficiency->photo = $data['photo'];
        $deficiency->priority = $data['priority'];
        $deficiency->reopened_count = $data['reopened_count'];
        $deficiency->report_day = $data['report_day'];
        $deficiency->report_month = $data['report_month'];
        $deficiency->report_timestamp = $data['report_timestamp'];
        $deficiency->report_year = $data['report_year'];
        $deficiency->reports_count = $data['reports_count'];
        $deficiency->resolution_status = $data['resolution_status'];
        $deficiency->status = $data['status'];
        $deficiency->type = $data['type'];
        $deficiency->username = $data['username'];
        $deficiency->work_comments = $data['work_comments'];
        $deficiency->address->city = $data['city'];
        $deficiency->address->country = $data['country'];
        $deficiency->address->line1 = $data['line1'];
        $deficiency->address->line2 = $data['line2'];
        $deficiency->address->zipcode = $data['zipcode'];
        
        return $deficiency;
    }
    
    /**
     * Delete a deficiency from the database.
     * @param int $id
     */
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
