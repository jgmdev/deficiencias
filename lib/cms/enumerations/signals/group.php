<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations\Signals;

/**
 * Available group signals.
 */
class Group
{
    const ADD = 'group_add';
    const EDIT = 'group_edit';
    const DELETE = 'group_delete';
    const GET = 'group_get';
    const GET_PERMISSIONS = 'group_get_permissions';
}

?>
