<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations\Signals;

/**
 * Available group signals.
 */
class Form
{
    const SUBMIT = 'form_submit';
    const SUBMIT_ERROR = 'form_submit_error';
    const RENDER = 'form_render';
}

?>
