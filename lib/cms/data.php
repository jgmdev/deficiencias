<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms;

class Data
{
    /**
     * Path to the file we are working on.
     * @var string 
     */
    protected $file;
    
    /**
     * Representation of current data
     * @var array
     */
    protected $data;
    
    /**
     * Initializes and optionally parses a file.
     * @param type $file
     */
    public function __construct($file=null)
    {
        $this->data = array();
        
        if($file)
            $this->Parse($file);
    }
    
    /**
     * Parses the file database system.
     * @param string $file The path of the file to parse.
     * @return array All the rows with a subarray of it fields
     * in the format row[id] = array(field_name=>value) or false if error.
     */
    public function Parse($file)
    {	
        $this->file = $file;
        
        //In case file is been write wait to not get empty content
        $this->WaitIfLock();

        if(!file_exists($file))
        {
            $this->data = array();
        }

        $arrFile = file($file);

        $row = array();

        $insideRow = false;
        $insideField = false;
        $currentRow = "";
        $currentField = "";

        for($i=0; $i<count($arrFile); ++$i)
        {
            if($insideField)
            {
                if(substr(trim($arrFile[$i]),0,6) == "field;")
                {
                    $insideField = false;

                    $row[$currentRow][$currentField] = rtrim($row[$currentRow][$currentField]);
                }
                else
                {
                    $field =  $arrFile[$i];

                    $stripped = preg_replace("/^(\t\t)/", "", $field, 1);

                    if($stripped != $field)
                        $field = $stripped;
                    else
                        $field = preg_replace("/^(        )/", "", $field, 1);

                    $field = str_replace("\\field;", "field;", $field);

                    if($row[$currentRow][$currentField] != "")
                    {
                        $row[$currentRow][$currentField] .= $field . "";
                    }
                    else
                    {
                        $field = trim($field, "\t");
                        $row[$currentRow][$currentField] .= $field;
                    }
                }
            }
            else if($insideRow)
            {
                if(substr(trim($arrFile[$i]),0,6) == "field:")
                {
                    $arrField = explode(":", $arrFile[$i]);
                    $currentField = trim($arrField[1]);
                    $insideField = true;

                    $row[$currentRow][$currentField] = "";
                }
                else if(substr(trim($arrFile[$i]),0,4) == "row;")
                {
                    $insideRow = false;
                }
            }
            else if(!$insideRow)
            {
                if(substr(trim($arrFile[$i]),0,4) == "row:")
                {
                    $arrRow = explode(":", $arrFile[$i]);
                    $currentRow = trim($arrRow[1]);
                    $insideRow = true;
                }
            }
        }

        unset($arrFile);

        $this->data = $row;
    }

    /**
     * Writes a php database file with the correct format.
     * @param array $data With the format array[row_number] = array("field_name"=>"field_value")
     * used to populate the content of the file.
     * @throws \Cms\Exception\FileSystem\WriteFileException
     * @throws \Cms\Exception\FileSystem\InvalidFileException
     */
    public function Write($data)
    {
        if(!$this->file)
            throw new Exception\FileSystem\InvalidFileException;

        //Wait if file is been modified
        $this->WaitIfLock();

        //Check if a file could not be lock and keep trying until locked
        while(!$this->Lock())
        {
            continue;
        }

        //For security we place this at the top of the file to make it unreadable by
        //external users
        $content = "<?php exit; ?>\n\n\n";

        foreach($data as $row => $fields)
        {
            $content .= "row: $row\n\n";

            foreach($fields as $name => $value)
            {
                if(!is_string($value))
                    $value = serialize($value);
                else
                    $value = str_replace(array("\n", "field;"), array("\n\t\t", "\\field;"), $value);

                $content .= "\tfield: $name\n";
                $content .= "\t\t" . trim($value);
                $content .= "\n\tfield;\n\n";
            }

            $content .= "row;\n\n\n";
        }

        if(!file_put_contents($this->file, $content))
        {
            //Unlock file
            $this->Unlock();

            throw new Exception\FileSystem\WriteFileException;
        }

        //Unlock file
        $this->Unlock();
    }
    
    /**
     * Gets all rows from a data file.
     * @return array
     */
    public function GetAllRows()
    {
        //In case file is been write wait to not get empty content
        $this->WaitIfLock();
        
        $this->Parse($this->file);

        return $this->data;
    }

    /**
     * Gets a row and all its fields from a data file.
     * @param integer $position The number or id of the row to retrieve.
     * @return Array in the format fields["name"] = "value"
     */
    public function GetRow($position)
    {
        //In case file is been write wait to not get empty content
        $this->WaitIfLock();
        
        $this->Parse($this->file);

        return $this->data[$position];
    }

    /**
     * Appends a new row to a database file and creates the file if doesnt exist.
     * @param array $fields Fields in the format fields["name"] = "value"
     * @return bool False if failed to add data otherwise true.
     */
    public function AddRow($fields)
    {	
        $this->Parse($this->file);

        $this->data[] = $fields;

        $this->Write($this->data);      
    }

    /**
     * Delete a row from a database file and all its fields.
     * @param integer $position The position or id of the row to delete.
     */
    public function DeleteRow($position)
    {
        $this->Parse($this->file);

        unset($this->data[$position]);

        $this->Write($this->data);
    }

    /**
     * Deletes a row from a database file when a field matches a specific value.
     * @param string $field_name Name of the field to match.
     * @param string $value Value of the field.
     */
    public function DeleteRowByField($field_name, $value)
    {
        $this->Parse($this->file);

        foreach($this->data as $position=>$fields)
        {
            if($fields[$field_name] == $value)
            {
                $this->DeleteRow($position);
                return;
            }
        }
    }

    /**
     * Edits all the fields from a row on a database file.
     * @param integer $position The position or id of the row to edit.
     * @param array $new_data Fields in the format fields["name"] = "value"
     * with the new data to be written to the row.
     */
    public function EditRow($position, $new_data)
    {
        $this->Parse($this->file);

        $this->data[$position] = $new_data;

        $this->Write($this->data);
    }

    /**
     * Locks a file for write protection.
     * @return bool true on success false on fail.
     */
    private function Lock()
    {
        //Lock file to block file from modifications.
        $file_lock = $this->file . ".lock";

        //Create lock file
        if(file_exists($file_lock))
        {
            return false;
        }
        else
        {
            file_put_contents($file_lock, "");
        }

        return true;
    }

    /**
     * Unlocks a write protected file.
     * @param string $file The path of the file to unlock.
     */
    private function Unlock()
    {
        //Lock file to block file from modifications.
        $file_lock = $this->file . ".lock";

        //Delete lock file
        unlink($file_lock);
    }

    /**
     * Checks if a file is been modified and waits until is modified.
     * @param string $file the file to check.
     */
    private function WaitIfLock()
    {
        //Lock file to block file from modifications until is modified here first.
        $file_lock = $this->file . ".lock";

        //Check if $file is not been modified already.
        if(file_exists($file_lock))
        {
            //Wait until the file is written by the other process
            while(file_exists($file_lock))
            {
                continue;
            }
        }
    }

    /**
     * Sorts an array returned by the data_parser function using bubble sort.
     *
     * @param array $data_array The array to sort in the format returned by data_parser().
     * @param string $field_name The field we are using to sort the array by.
     * @param mixed $sort_method The type of sorting, default is ascending. 
     *
     * @return array The same array but sorted by the given field name.
     */
    public static function Sort($data_array, $field_name, $sort_method = SORT_ASC)
    {
        $sorted_array = array();

        if(is_array($data_array))
        {
            $field_to_sort_by = array();
            $new_id_position = array();

            foreach($data_array as $key=>$fields)
            {
                $field_to_sort_by[$key] = $fields[$field_name];
                $new_id_position[$key] = $key;
            }

            array_multisort($field_to_sort_by, $sort_method, $new_id_position, $sort_method);

            foreach($new_id_position as $id)
            {
                $sorted_array[$id] = $data_array[$id];
            }
        }

        return $sorted_array;
    }
}

?>
