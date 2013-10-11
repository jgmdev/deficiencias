<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;


/**
 * Permissions required to access administrative tasks of deficiencies.
 */
class Permissions
{
    const VIEW = 'deficiencies_view';
    const EDIT = 'deficiencies_edit';
    const DELETE = 'deficiencies_delete';
    const ASSIGN = 'deficiencies_assign';
    const ADMINISTRATOR = 'deficiencies_administrator';
    const ATTENDANT = 'deficiencies_attendant';
}
?>
