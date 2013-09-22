/*
Version 1.2

Example 1

<span  id="charsLeft"></span> chars left.
<textarea id="myTextarea"></textarea>
<script type="text/javascript">
 $('#myTextarea').limit('140','#charsLeft');
</script>

Example 2

<textarea id="myTextarea"></textarea>
<script type="text/javascript">
 $('#myTextarea').limit('140');
</script>
*/
(function($){ 
     $.fn.extend({  
         limit: function(limit,element) {
            try{
        		var interval, f;
        		var self = $(this);
        				
        		$(this).focus(function(){
        			interval = window.setInterval(substring,100);
        		});
        		
        		$(this).blur(function(){
        			clearInterval(interval);
        			substring();
        		});
        		
        		substringFunction = "function substring(){ var val = $(self).val();var length = val.length;if(length > limit){$(self).val($(self).val().substring(0,limit));}";
        		if(typeof element != 'undefined')
        			substringFunction += "if($(element).html() != limit-length){$(element).html((limit-length<=0)?'0':limit-length);}"
        			
        		substringFunction += "}";
        		
        		eval(substringFunction);
        		
        		substring();
    		}
            catch(error){}
        } 
    }); 
})(jQuery);