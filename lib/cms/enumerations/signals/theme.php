<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations\Signals;

/**
 * Available theme signals.
 */
class Theme
{
    const ADD_MESSAGE = 'theme_add_message';
    const ADD_TAB = 'theme_add_tab';
    const ADD_STYLE = 'theme_add_style';
    const ADD_SCRIPT = 'theme_add_script';
    const ADD_RAW_STYLE = 'theme_add_raw_style';
    const ADD_RAW_SCRIPT = 'theme_add_raw_script';
    const GET_STYLES = 'theme_get_styles';
    const GET_SCRIPTS = 'theme_get_scripts';
    const GET_RAW_STYLES = 'theme_get_raw_styles';
    const GET_RAW_SCRIPTS = 'theme_get_raw_scripts';
    const CONTENT_TEMPLATE = 'theme_content_template';
    const PAGE_TEMPLATE = 'theme_page_template';
    const RENDER = 'theme_render';
}

?>
