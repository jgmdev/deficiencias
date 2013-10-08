<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form\Filter;

use Cms\Form\Filter;

/**
 * Removes the html from a string.
 */
class Html extends Filter
{

    /**
     * A string of tags in the format <tag1> <tag2>, etc...
     * @var string
     */
    private $allowed_tags;
    
    /**
     * Default constructor.
     * @param string $value Default value to filter.
     * @param string $allowed_tags String of tags in the format <tag1> <tag2>, etc...
     */
    public function __construct($value = '', $allowed_tags='')
    {
        parent::__construct($value);
        
        $this->allowed_tags = $allowed_tags;
    }

    /**
     * Strips html from the given value.
     * @param string $value
     */
    public function GetFiltered($value = '')
    {
        $value = parent::GetFiltered($value);
        
        $value = $this->StripHtmlTags($value, $this->allowed_tags);
        
        return $value;
    }
    
    /**
     * Set the list of allowed html tags.
     * @param string $allowed_tags String of tags in the format <tag1> <tag2>, etc...
     */
    public function SetAllowedTags($allowed_tags)
    {
        $this->allowed_tags = $allowed_tags;
    }

    private function StripHtmlTags($text, $allowed_tags = '')
    {
        //Allow object and embed
        if('' . stripos($allowed_tags, 'object') . '' != '' ||
            '' . stripos($allowed_tags, 'embed') . '' != ''
        )
        {
            $text = preg_replace(
                    array(
                // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                // Add line breaks before & after blocks
                '@<((br)|(hr))@iu',
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
                    ), array(
                ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
                    ), $text);
        }
        // PHP's strip_tags() function will remove tags, but it
        // doesn't remove scripts, styles, and other unwanted
        // invisible text between tags.  Also, as a prelude to
        // tokenizing the text, we need to insure that when
        // block-level tags (such as <p> or <div>) are removed,
        // neighboring words aren't joined.
        else
        {
            $text = preg_replace(
                    array(
                // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
                // Add line breaks before & after blocks
                '@<((br)|(hr))@iu',
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
                    ), array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
                    ), $text);
        }

        // Remove all remaining tags and comments and return.
        return strip_tags($text, $allowed_tags);
    }

}

?>
