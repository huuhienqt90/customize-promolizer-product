/*
 * TODO: Add support in the document.ready event for 'fieldset indexes' in order to validate each fieldset one at a time rather than all fieldsets at once.
 */

var $jQ = jQuery;
var failureStep = ""; // Set the default message to blank

var fieldsets;
var namecolors;
var fsCount;

$jQ(document).ready(function()
{
    $jQ(".single_add_to_cart_button.button.alt").click(function(){
        $jQ("#addtoCartBox").submit();
    });
	fieldsets = $jQ("form.cart fieldset");
    namecolors = $jQ("form.cart fieldset .colorContent");
	fsCount = fieldsets.length;
	var currentFSIndex = 0;
	var currentFS;

	//var nextBtn = $jQ(".next");
	////$nextBtn.on("click", function()
	//nextBtn.click(function(e)
	$jQ(".next").click(function(e)
	{
		currentFS = $jQ(this).parent();
        var fsIndex = $jQ("form.cart fieldset").index(currentFS);
		currentFSIndex = fieldsets.index(currentFS);

		var $errorDiv = $jQ("form.cart div.error");
		var divText = "<div class=\"error\"><p>";

		//if (!ValidateFormInput()) // Form validation failed
		//if (!ValidateFieldset(currentFSIndex))
		if (!ValidateFieldset(currentFS))
		{
			if ($errorDiv && $errorDiv != null) // If it failed previously and an error is already displayed, remove the current error message.
				$errorDiv.remove();

			if (failureStep.length > 0)
				$jQ("form.cart #progressbar").after(divText + failureStep + "</p></div>"); // Display the error text below the progress bar
			else
				$jQ("form.cart #progressbar").after(divText + "Unknown error" + "</p></div>"); // Display unknown error text below the progress bar

            window.setTimeout(function () {
                $jQ('form.cart .error').fadeOut(2000,function(){$JQ(this).remove();});
            }, 5000);
			e.stopImmediatePropagation();
		}

		else // Form validation was successful
		{
			if ($errorDiv && $errorDiv != null)
				$errorDiv.remove(); failureStep = "";
			//$jQ("form.cart #progressbar").after(divText + "Successful submission!" + "</p></div>"); // Display success text below the progress bar
		}
	});

	$jQ(".previous").click(function(e)
	{
		var $errorDiv = $jQ("form.cart div.error");
		if ($errorDiv && $errorDiv != null)
			$errorDiv.remove();
	});
});

/*function ValidateFieldset(currentFieldsetIndex)
{
	var currentFS = $jQ("form.cart fieldset").eq(currentFieldsetIndex);
	ValidateFieldset(currentFS);
}*/

function ValidateFieldset(currentFS)
{
	var fsIndex = $jQ("form.cart fieldset").index(currentFS);

	if (fsIndex >= 0 && (fsIndex < fsCount))
	{
		if (fsIndex == 0)
		{
			var qty = $jQ("#qty-slider").slider("value");
			var minQty = $jQ("#qty-slider").slider("option", "min");
			var maxQty = $jQ("#qty-slider").slider("option", "max");
			var selectedColorSwatches = $jQ("#ProductColor1 .addon-color-swatch.selected");
			if (!ValidateQuantity(minQty, maxQty, qty))
			{
				failureStep = "You must select a quantity between " + minQty.toString() + " and " + maxQty.toString() + ".";
				return false;
			}

			if (!ValidateColorSwatches(selectedColorSwatches))
			{
				failureStep = "You must select between 1 and 3 colors.";
				return false;
			}

			failureStep = "Success";
			return true;
		}
        if (fsIndex == 1)
        {
            var selectedImprintTypes = $jQ(".addon-imprint-type.selected");
            var selectedImprintLocations = $jQ(".addon-imprint-location.selected");

            if (!ValidateImprintType(selectedImprintTypes))
            {
                failureStep = "You must select one imprint type.";
                return false;
            }

            if(! $jQ(".product-addon-imprint-location").hasClass('not-product-addon-imprint-location')){
                var check = selectedImprintTypes.attr("data-string");
                if(check == "location"){
                    if (!selectedImprintLocations || (selectedImprintLocations.length <= 0 || selectedImprintLocations.length > 2))
                    {
                        failureStep="You must select imprint location.";
                        return false;
                    }
                }else{
                if (!ValidateImprintLocation(selectedImprintLocations))
                    {
                        failureStep = "You must select one imprint location.";
                        return false;
                    }
                }
            }
            failureStep = "Success";
            return true;
        }

        if (fsIndex == 2)
        {
            //sumquaty
            //kiem tra tong so luong phai max moi nhay buoc ke tiep
            if( array_sum(sum) < sumquaty) {
                failureStep = "Please must choice max total color";
                return false;
            }
            failureStep = "Success";
            return true;
        }
        if (fsIndex == 3)
        {
            failureStep = "Success";
            return true;
        }
		if (fsIndex == (fsCount - 1)) // Current fieldset is the last one in the collection
		{
			alert("Last fieldset!");
		}
	}
}

function ValidateFormInput()
{
	var form = $jQ(".single-product .summary form.cart");

	var qty = $jQ("#qty-slider").slider("value");
	//var minQty = $jQ("#qty-slider").slider("min");
	//var maxQty = $jQ("#qty-slider").slider("max");
	var minQty = $jQ("#qty-slider").slider("option", "min");
	var maxQty = $jQ("#qty-slider").slider("option", "max");

	//var colorSwatches = $jQ(".addon-color-swatch");
	var selectedColorSwatches = $jQ(".addon-color-swatch.selected");
	var selectedImprintTypes = $jQ(".addon-imprint-type.selected");
	var selectedImprintLocations = $jQ(".addon-imprint-location.selected");

	if (!ValidateQuantity(minQty, maxQty, qty))
	{
		//failureStep = "ValidateQuantity";
		failureStep = "You must select a quantity between " + minQty.toString() + " and " + maxQty.toString() + ".";
		return false;
	}

	if (!ValidateColorSwatches(selectedColorSwatches))
	{
		//failureStep = "ValidateColorSwatches";
		failureStep = "You must select between 1 and 3 colors.";
		return false;
	}

	if (!ValidateImprintType(selectedImprintTypes))
	{
		failureStep = "You must select an imprint type.";
		return false;
	}

	if (!ValidateImprintLocation(selectedImprintLocations))
	{
		failureStep = "You must select one imprint location.";
		return false;
	}

	failureStep = "Success";
	return true;
}

function ValidateQuantity(minAllowed, maxAllowed, curVal)
{
	if (!minAllowed || minAllowed <= 0) // Check values 0 and below
		return false;
	/*if (!maxAllowed || (minAllowed >= maxAllowed)) // Check to make sure minAllowed is never greater-than-or-equal-to maxAllowed. They can't even be equal cause that would mean we forced an exact quantity for a product.
		return false;*/
	if (!maxAllowed || (maxAllowed <= minAllowed)) // Check to make sure maxAllowed is never less-than-or-equal-to minAllowed. They can't even be equal cause that would mean we forced an exact quantity for a product.
		return false;
	/*if (!curVal || curVal <= 0)
		return false;*/
	//if (!curVal || (!parseInt(curVal.toString()) || (curVal < minAllowed || curVal > maxAllowed))) // Check to make sure curVal is never less-than minAllowed and never more-than maxAllowed.
	if (!curVal || (curVal < minAllowed || curVal > maxAllowed)) // Check to make sure curVal is never less-than minAllowed and never more-than maxAllowed.
		return false;
	return true;
}

function ValidateColorSwatches(colorSwatches)
{
	if (!colorSwatches || (colorSwatches.length <= 0 || colorSwatches.length > 3))
		return false;
	return true;
}

/*function ValidateImprintType()
{
	var selectedCount = $jQ("form.cart input[type=\"radio\"].addon-radio:selected").length;
	if (selectedCount < 1)
		return false;
	return true;
}*/

function ValidateImprintType(imprintTypes)
{
	if (!imprintTypes || (imprintTypes.length <= 0 || imprintTypes.length > 1))
		return false;
	return true;
}
function ValidateThreadColor(ThreadColor)
{
    if (!ThreadColor || (ThreadColor.length <= 0 || ThreadColor.length > 3))
        return false;
    return true;
}
function ValidateImprintLocation(imprintLocations)
{
	if (!imprintLocations || (imprintLocations.length <= 0 || imprintLocations.length > 1))
		return false;
	return true;
}