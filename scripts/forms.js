//Starts collapsing all fieldsets
function start_collapse()
{
	$("fieldset.collapsible legend")
		.click(function(){
		
			//Show Content
			if($(this).parent().hasClass("collapsed"))
			{
				$(this).parent().removeClass("collapsed");
				$(this).parent().children().filter(function(index){
						if(index > 0){
							return true;
						}
						
						return false;
					}
				).show("fast");
				$(this).children("a").removeClass("expand");
				$(this).children("a").addClass("collapse");
			}
			
			//Hide Content
			else
			{
				$(this).parent().children().filter(function(index){
						if(index > 0){
							return true;
						}
						
						return false;
					}
				).hide("fast", function(){
						$(this).parent().addClass("collapsed");
					}
				);
				$(this).children("a").removeClass("collapse");
				$(this).children("a").addClass("expand");
			}
		});
}

$(document).ready(function() {
	/* Start collapsing */
	start_collapse();
});