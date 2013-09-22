<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Signals\Type;

/**
 * Available theme signals.
 */
class ThemeSignal
{
    const ADD_MESSAGE = 'THEME_ADD_MESSAGE';
    const ADD_TAB = 'THEME_ADD_TAB';
    const ADD_STYLE = 'THEME_ADD_STYLE';
    const ADD_SCRIPT = 'THEME_ADD_SCRIPT';
    const ADD_RAW_STYLE = 'THEME_ADD_RAW_STYLE';
    const ADD_RAW_SCRIPT = 'THEME_ADD_RAW_SCRIPT';
    const GET_STYLES = 'THEME_GET_STYLES';
    const GET_SCRIPTS = 'THEME_GET_SCRIPTS';
    const GET_RAW_STYLES = 'THEME_GET_RAW_STYLES';
    const GET_RAW_SCRIPTS = 'THEME_GET_RAW_SCRIPTS';
    const CONTENT_TEMPLATE = 'THEME_CONTENT_TEMPLATE';
    const PAGE_TEMPLATE = 'THEME_PAGE_TEMPLATE';
    const RENDER = 'THEME_RENDER';
}

?>
