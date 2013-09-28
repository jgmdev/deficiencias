<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Handlers;

use Cms\System;

class Session implements \SessionHandlerInterface
{

    private $save_path;
    
    private $default_save_path;
    
    private $session_name;
    
    private $current_working_dir;
    
    private $original_data;
    
    private $file;
    
    private $file_locked;
    
    public function __construct()
    {
        $this->current_working_dir = getcwd();
        
        $this->save_path = System::GetDataPath() . "sessions";
        
        $this->original_data = '';
        
        $this->file = null;
        
        $this->file_locked = false;
        
        if(!is_dir($this->save_path))
        {
            mkdir($this->save_path, 0777);
        }
    }

    public function open($save_path, $session_name)
    {
        chdir($this->current_working_dir);
        
        $this->default_save_path = $save_path;
        
        $this->session_name = $session_name;
        
        if(isset($_COOKIE[$session_name]))
        {
            $this->locksession();
            
            //Increase cookie expiration time
            setcookie($session_name, $_COOKIE[$session_name], time() + (((60*60) * 24) * 365), '/');
        }

        return true;
    }

    public function close()
    {
        chdir($this->current_working_dir);
        
        $this->unlocksession();
        
        return true;
    }

    public function read($id)
    {
        chdir($this->current_working_dir);
        
        if(file_exists("$this->save_path/$id"))
        {
            $this->locksession();
            
            $data = file_get_contents("$this->save_path/$id");
            
            if(!$this->original_data)
                $this->original_data = $data;
            
            return $data;
        }
        
        return '';
    }

    public function write($id, $data)
    {
        chdir($this->current_working_dir);
        
        $file = "$this->save_path/$id";
        
        if(!$data)
        {
            if(file_exists($file))
            {
                unlink($file);
                setcookie($this->session_name, "", 1, "/");
            }
            
            return true;
        }
        elseif($this->original_data != $data)
        {
            $this->locksession();
            return file_put_contents($file, $data) === false ? false : true;
        }
        else
        {
            if(file_exists($file))
            {
                $this->locksession();
                touch($file);
            }
        }
        
        return true;
    }

    public function destroy($id)
    {
        chdir($this->current_working_dir);
        
        $file = "$this->save_path/$id";
        if(file_exists($file))
        {
            $this->unlocksession();
            
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        chdir($this->current_working_dir);
        
        foreach(glob("$this->save_path/*") as $file)
        {
            if(!file_exists($file))
                continue;
            
            if(filemtime($file) + $maxlifetime < time())
            {
                unlink($file);
            }
        }

        return true;
    }
    
    public function locksession()
    {
        if(!$this->file_locked && isset($_COOKIE[$this->session_name]))
        {
            $file = $this->save_path . "/" . $_COOKIE[$this->session_name];
            
            if(file_exists($file))
            {
                $this->file = fopen($file, "r+");
                
                while(!flock($this->file, LOCK_EX)){}
                
                $this->file_locked = true;
            }
        }
    }
    
    public function unlocksession()
    {
        if($this->file_locked)
        {
            flock($this->file, LOCK_UN);
            fclose($this->file);
            $this->file_locked = false;
        }
    }
}

?>
