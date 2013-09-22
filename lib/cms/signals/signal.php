<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Signals;

/**
 * Signal management that can be implemented at a per object basic.
 */
class Signal
{
    /**
     * @var array
     */
    private $listeners;
    
    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->listeners = array();
    }
    
    /**
     * Calls all callbacks listening for a given signal type.
     * The $var1-$var6 are optional parameters passed to the callback.
     * @see \Cms\Signals\Type
     * @param int $signal_type
     */
    private function Send($signal_type, &$var1=null, &$var2=null, &$var3=null, &$var4=null, &$var5=null, &$var6=null)
    {
        foreach($this->listeners[$signal_type] as $callback_data)
        {
            $callback = $callback_data['callback'];
            
            if($var1 != null && $var2 != null && $var3 != null && $var4 != null && $var5 != null && $var6 != null)
                $callback($var1, $var2, $var3, $var4, $var5, $var6);
            elseif($var1 != null && $var2 != null && $var3 != null && $var4 != null && $var5 != null)
                $callback($var1, $var2, $var3, $var4, $var5);
            elseif($var1 != null && $var2 != null && $var3 != null && $var4 != null)
                $callback($var1, $var2, $var3, $var4);
            elseif($var1 != null && $var2 != null && $var3 != null)
                $callback($var1, $var2, $var3);
            elseif($var1 != null && $var2 != null)
                $callback($var1, $var2);
            elseif($var1 != null)
                $callback($var1);
            else
                $callback();
        }
    }
    
    /**
     * Add a callback that listens to a specific signal.
     * @see \Cms\Signals\Type
     * @param string $signal_type
     * @param function $callback
     * @param int $priority
     */
    public function Listen($signal_type, $callback, $priority=10)
    {
        if(!isset($this->listeners[$signal_type]))
            $this->listeners[$signal_type] = array();
        
        $this->listeners[$signal_type][] = array(
            'callback'=>$callback,
            'priority'=>$priority
        );
        
        $this->listeners[$signal_type] = \Cms\Data::Sort($this->listeners[$signal_type], 'priority');
    }
    
    /**
     * Remove a callback from listening a given signal type.
     * @see \Cms\Signals\Type
     * @param string $signal_type
     * @param function $callback
     */
    public function Unlisten($signal_type, $callback)
    {
        foreach($this->listeners[$signal_type] as $position=>$callback_data)
        {
            $stored_callback = $callback_data['callback'];
            
            if($callback == $stored_callback)
            {
                unset($this->listeners[$signal_type][$position]);
                return;
            }
        }
    }
}
?>
