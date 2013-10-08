<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations\Signals;

/**
 * Available user signals.
 */
class User
{
    const ADD = 'user_add';
    const EDIT = 'user_edit';
    const DELETE = 'user_delete';
    const GET = 'user_get';
    const GENERATE_PAGE = 'user_generate_page';
}

?>
