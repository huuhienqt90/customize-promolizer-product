var $jQ = jQuery;
var check_step = new Array();
//var check_step[0] = false;

$jQ(document).ready(function()
{

    var current_fs, next_fs, previous_fs;
	var left, opacity, scale;
	var animating;

	//var form = $jQ(".single-product .summary form.cart");
	var form = $jQ(".product_color_page form.cart");
	var formOuterHeightNoBorder = form.outerHeight(true) - 1;

	var liWidth = calculatePBChildWidth();

	$jQ("#progressbar").children().css("width", liWidth + "%");
//    fsCount = fieldsets.length;
//    for(var i=0;i < fsCount;i++){
//        check_step[i]=false;
//    }
	$jQ(".next").click(function()
	{
		if(animating)
			return false;
		animating = true;
		form.css("min-height", (formOuterHeightNoBorder + 3) + "px");

		current_fs = $jQ(this).parent();
		next_fs = $jQ(this).parent().next();
        var fsIndex = $jQ("form.cart fieldset").index(current_fs);
        //alert(check_step[fsIndex]);
		//$jQ(".single-product .summary form.cart #progressbar li").eq($jQ("fieldset").index(next_fs)).addClass("active");
		$jQ(".product_color_page form.cart #progressbar li").eq($jQ("fieldset").index(next_fs)).addClass("active");

		current_fs.animate({opacity: 0},
		{
			step: function(now, mx)
			{
				scale = 1 - (1 - now) * 0.2;
				left = (now * 50)+"%";
				opacity = 1 - now;
				//current_fs.css({'transform': 'scale('+scale+')'});
				next_fs.css({'left': left, 'opacity': opacity});
			},
			duration: 800,
			complete: function()
			{
				current_fs.hide();
				animating = false;
				next_fs.show();
				form.css("min-height", "");
				//$jQ(this).event.trigger({ type: "fieldsetShown", message: "", time: new Date() });
				//$jQ(this).event.trigger("fieldsetShown");
				//$jQ.event.trigger("fieldsetShown");
                //if(!check_step[fsIndex]){
                form.trigger("fs_shown", $jQ("form.cart fieldset").index($jQ(this)) + 1);
                    //check_step[fsIndex]=true;
                //}
			},
			easing: 'easeInOutBack'
		});
	});

	$jQ(".previous").click(function()
	{
       // alert("previous");
		if(animating)
			return false;
		animating = true;

		current_fs = $jQ(this).parent();
		previous_fs = $jQ(this).parent().prev();

		$jQ(".product_color_page form.cart #progressbar li").eq($jQ("fieldset").index(current_fs)).removeClass("active");

		current_fs.animate({opacity: 0},
		{
			step: function(now, mx)
			{
				scale = 0.8 + (1 - now) * 0.2;
				left = ((1-now) * 50)+"%";
				opacity = 1 - now;
				current_fs.css({'left': left});
				//previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
				previous_fs.css({'opacity': opacity});
			},
			duration: 800,
			complete: function()
			{
				current_fs.hide();
				animating = false;
				previous_fs.show();
                form.off("fs_shown", $jQ("form.cart fieldset").index($jQ(this)));
			},
			easing: 'easeInOutBack'
		});
	});

	$jQ(".submit").click(function(e)
	{
		e.preventDefault();

		current_fs = $jQ(this).parent();
		var pText = $jQ("p");

		form.slideUp(600, function()
		{
			form.css("min-height", "");
			form.remove();
			pText.text("Done!");
		});
	});

});


//
// FUNCTIONS
//

function calculatePBChildWidth()
{
	var $liCount = $jQ("#progressbar").children().length;
	return (1 / $liCount) * 100;
}