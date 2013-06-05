<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations;

/**
 * Enumeration used to identify a message displayed on a page @see \Cms\Theme::AddMessage()
 */
class MessageType
{
    const NORMAL = 'normal';
    const ERROR = 'error';
}

?>
