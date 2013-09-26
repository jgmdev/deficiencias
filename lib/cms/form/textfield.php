<?php
/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms\Form;

use Cms\Enumerations\FormFieldType;

class TextField extends Field
{
    public function __construct($label, $name, $value='', $description='', $placeholder='', $required=false, $readonly=false, $size=0)
    {
        parent::__construct($label, $name, $value, $description, $placeholder, FormFieldType::TEXT, $required, $readonly, $size);
    }
    
    public function GetHtml()
    {
        $html = '';
        
        if($this->IsArray())
        {
            $request_value = $this->GetRequestValue();
            
            $html .= '<div class="fields-container" id="'.$this->id.'-fields">' . "\n";
            
            $id = 0;
            $original_id = $this->id;
            
            if(is_array($request_value))
            {
                foreach($request_value as $value)
                {
                    if(trim($value) != "")
                    {
                        $this->id .= '-' . $id;
                        $html .= '<div class="field">' . "\n";
                        $html .= $this->GetSingleHtml($value);
                        $html .= '<a class="remove" style="cursor: pointer;" onclick="$(this).parent().remove();">[-]</a>' . "\n";
                        $html .= $this->GenerateLimit();
                        $html .= '</div>' . "\n";
                        $id++;
                        $this->id = $original_id;
                    }
                }
            }
            else
            {
                $this->id .= '-' . $id;
                $html .= '<div class="field">' . "\n";
                $html .= $this->GetSingleHtml();
                $html .= '<a class="remove" style="cursor: pointer;" onclick="$(this).parent().remove();">[-]</a>' . "\n";
                $html .= $this->GenerateLimit();
                $html .= '</div>' . "\n";
                $this->id = $original_id;
                $id++;
            }
            
            $html .= '<div class="count" style="display: none">'.($id-1).'</div>' . "\n";
            $html .= '</div>' . "\n";
            
            if($this->type != FormFieldType::HIDDEN)
            {
                $html .= "<hr />";

                $html .= '<a class="add-more" id="'.$this->id."-add".'" style="cursor: pointer; display: block; text-align: right;">Add More</a>';

                \Cms\Theme::AddRawScript(
                    '$(document).ready(function(){' . "\n" .
                    "\t" . '$("#'.$this->id.'-add").click(function(){' . "\n" .
                    "\t\t" . 'var element_id = \''.$this->id.'-\'+(parseInt($("#'.$this->id.'-fields .count").html()) + 1)+\'-limit\';' . "\n" .
                    "\t\t" . 'var container = $(\'<div class="field"></div>\')' . "\n" .
                    "\t\t" . 'var element = $(\''.trim($this->GetSingleHtml()).'\');' . "\n" .
                    "\t\t" . 'var remove = $(\'<a class="remove" style="cursor: pointer;" onclick="$(this).parent().remove();">[-]&nbsp;</a>\')' . "\n" .
                    "\t\t" . 'var chars_limit = $(\'<span class="chars-left" id="\'+element_id+\'-limit">'.$this->size.'</span>\');' . "\n" .
                    "\t\t" . 'var chars_limit_label = $(\'<span class="chars-left-label">&nbsp;'.t('characters left').'</span>\');' . "\n" .
                    "\t\t" . 'element.attr("id", element_id);' . "\n" .
                    "\t\t" . '$("#'.$this->id.'-fields .count").html(parseInt($("#'.$this->id.'-fields .count").html()) + 1);' . "\n" .
                    "\t\t" . 'container.hide();' . "\n" .
                    "\t\t" . 'remove.css("display", "inline");' . "\n" .
                    "\t\t" . 'chars_limit.css("display", "inline");' . "\n" .
                    "\t\t" . 'chars_limit_label.css("display", "inline");' . "\n" .
                    "\t\t" . '$(container).append(element);' . "\n" .
                    "\t\t" . '$(container).append(remove);' . "\n" .
                    "\t\t" . '$(container).append(chars_limit);' . "\n" .
                    "\t\t" . '$(container).append(chars_limit_label);' . "\n" .
                    "\t\t" . '$("#'.$this->id.'-fields").append(container);' . "\n" .
                    "\t\t" . 'container.fadeIn("slow");' . "\n" .
                    "\t\t" . '$("#" + element_id).limit("'.$this->size.'", "#"+element_id+"-limit");' . "\n" .
                    "\t" . '});' . "\n" .
                    '});'
                );
            }
        }
        else
        {
            $request_value = $this->GetRequestValue();
            
            if($request_value)
                $html .= $this->GetSingleHtml($request_value);
            else
                $html .= $this->GetSingleHtml($this->value);
            
            $this->description = $this->GenerateLimit();
        }
        
        if($this->description)
            $html .= '<div class="description">'.$this->description.'</div>' . "\n";
        
        return $html;
    }
    
    private function GenerateLimit()
    {
        $html = '';
        
        if($this->size > 0)
        {
            \Cms\Theme::AddScript('scripts/optional/jquery.limit.js');
                
            \Cms\Theme::AddRawScript(
                '$(document).ready(function(){' . "\n" .
                "\t" . '$("#'.$this->id.'").limit("'.$this->size.'", "#'.$this->id.'-limit");' . "\n" .
                '});'
            ); 
            
            $html .= "\n" . 
                '<span class="chars-left" id="'.$this->id.'-limit">' . 
                $this->size . 
                '</span> ' .
                '<span class="chars-left-label">' . 
                t('characters left') . 
                '</span>' . "\n"
            ;
        }
        
        return $html;
    }
}
?>
