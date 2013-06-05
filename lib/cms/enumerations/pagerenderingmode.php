<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Enumerations;

/**
 * Different ways that a page can be rendered by \Cms\Theme::Render()
 */
class PageRenderingMode
{
    const NORMAL = "html";
    const API = "api";
    const JAVASCRIPT = "js";
    const STYLE = "css";
}
?>
