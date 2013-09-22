<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Signals\Type;

/**
 * Available group signals.
 */
class FormSignal
{
    const SUBMIT = 'FORM_SUBMIT';
    const SUBMIT_ERROR = 'FORM_SUBMIT_ERROR';
    const RENDER = 'FORM_RENDER';
}

?>
