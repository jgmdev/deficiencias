<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Signals;

/**
 * Assist on the management of signals send at a global scope
 * thru the whole system.
 */
class SignalHandler
{
    /**
     * @var array
     */
    private static $listeners;
    
    /**
     * Disable constructor
     */
    private function __construct(){}
    
    /**
     * Calls all callbacks listening for a given signal type.
     * The $var1-$var6 are optional parameters passed to the callback.
     * @see \Cms\Signals\Type
     * @param int $signal_type
     */
    public static function Send($signal_type, &$var1=null, &$var2=null, &$var3=null, &$var4=null, &$var5=null, &$var6=null)
    {
        self::InitListeners();
        
        if(!isset(self::$listeners[$signal_type]))
            return;
        
        foreach(self::$listeners[$signal_type] as $callback_data)
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
    public static function Listen($signal_type, $callback, $priority=10)
    {
        self::InitListeners();
        
        if(!isset(self::$listeners[$signal_type]))
            self::$listeners[$signal_type] = array();
        
        self::$listeners[$signal_type][] = array(
            'callback'=>$callback,
            'priority'=>$priority
        );
        
         self::$listeners[$signal_type] = \Cms\Data::Sort(self::$listeners[$signal_type], 'priority');
    }
    
    /**
     * Remove a callback from listening a given signal type.
     * @see \Cms\Signals\Type
     * @param string $signal_type
     * @param function $callback
     */
    public static function Unlisten($signal_type, $callback)
    {
        self::InitListeners();
        
        if(!isset(self::$listeners[$signal_type]))
            return;
        
        if(is_array(self::$listeners[$signal_type]))
        {
            foreach(self::$listeners[$signal_type] as $position=>$callback_data)
            {
                $stored_callback = $callback_data['callback'];
                
                if($callback == $stored_callback)
                {
                    unset(self::$listeners[$signal_type][$position]);
                    break;
                }
            }
        }
        
        if(count(self::$listeners[$signal_type]) <= 0)
            unset(self::$listeners[$signal_type]);
    }
    
    /**
     * Initialize the $listeners variable as array.
     */
    private static function InitListeners()
    {
        if(!is_array(self::$listeners))
            self::$listeners = array();
    }
}
?>
