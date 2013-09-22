<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations;

/**
 * Standard validation errors.
 */
class ValidatorError
{
    const MIN_LENGHT = 'min_lenght';
    const MAX_LENGHT = 'max_lenght';
    const VALID_VALUE = 'valid_value';
    const PATTERN = 'pattern';
    const EMAIL = 'email';
    const BOOLEAN = 'boolean';
}
?>
