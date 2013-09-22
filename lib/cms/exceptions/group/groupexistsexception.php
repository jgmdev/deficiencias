<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Exceptions\Group;

/**
 * Exception thrown when trying to create a group with
 * a machine name of a group that already exists.
 */
class GroupExistsException extends \Exception {}

?>
