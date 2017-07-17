// NEW
var $jQ = jQuery;
var selectedCount = 0;
var selColors = [];//luu gia tri color dc chon o buoc 1
var selColorsMulti = new Array();
var selImprintType;
var selImprintLocation;
var sumquaty;//gia tri max quaty
var imprinttype='';//imprint type
var imprintsetcolor = '';//number color
var imprintsetlocation='';//nuber location
var currentSelColors = new Array();//mang 2 chieu luu toan bo gia tri color duoc chon o buoc 3
var save_array_vehicle =new Array();//mang 2 chieu luu gia tri color(add_color)
var save_num = new Array();// dem so luong Imprint color da lua chon
var $countadd = new Array();// luu so luong add_color da lua chon cho moi color
var selectedCountLocation = 0;//dem so luong selec cua location
var sum = new Array();// total one product-color step3(4 size) //check array_map(sum) == sumquaty
//
// DOCUMENT READY EVENT
//
function toTitleCase(str){
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
$jQ(document).ready(function($)
{
    $('input[name="imprintType"]').val($('.selected.addon-imprint-type').data("string"));
    $('input[name="imprintType"]').attr("data-color",$('.selected.addon-imprint-type').data("color"));
    $('input[name="imprintType"]').attr("data-location",$('.selected.addon-imprint-type').data("location"));
    imprintTypechoise = $('input[name="imprintType"]').val();
    imprintsetlocation = $('input[name="imprintType"]').data('location')//so luong location
    imprintsetcolor = $('input[name="imprintType"]').data("color");//so luong color
// BEGIN JS STEP1
//    sumquaty = parseInt($('input[name="min"]').val());
//    $min = parseInt($('input[name="min"]').val());
//    $max = parseInt($("input[name='max']").val());
//	$("#qty-slider").slider({
//		range: "min",
//		value: $min,
//		min: $min,
//		max: $max,
//		step: 12,
//		slide: function(event, ui){
//			$("#amount").text(ui.value);
//			$("#qty-text").val(ui.value);
//			$("#inputAmount").val(ui.value);
//            sumquaty = ui.value;
//		}
//	});
	var colorSwatches = $(".addon-color-swatch");
    clickColor(colorSwatches,$("#ProductColor1 h3.addon-name"),$('.colorinput'),'color',3);


// BEGIN STEP2     3    4
$jQ("form.cart").on("fs_shown", function(event, fsIndex){

    var imprintTypes = $jQ(".addon-imprint-type");
    selColors = [];//save number select color step1
    $jQ("#ProductColor1 .addon-color-swatch.selected").each(function(index){
        selColors.push($jQ(this).attr("data-string"));
    });

    if (fsIndex && fsIndex == 1){
        reset_html(fsIndex);
        $jQ(".info_step1").append('Product color(s):' + selColors.toString() + '<br />Quantily:' + sumquaty);
        var imprintTypechoise='';
        imprintTypes.click(function(){
            imprintLocations.removeClass("selected");
            //delete all class selected of imprint Location
            //selectedCountLocation = 0;
            $jQ('input[name="imprintType"]').val($jQ(this).data("string"));
            $jQ('input[name="imprintType"]').attr("data-color",$jQ(this).data("color"));
            $jQ('input[name="imprintType"]').attr("data-location",$jQ(this).data("location"));
            imprinttype = $jQ('input[name="imprintType"]').val();
            imprintsetcolor = $jQ(this).data("color");//so luong color
            imprintsetlocation = ($jQ(this).data("location"));//so luong location
            var currentImprintType = $jQ(this);
            imprintTypes.removeClass("selected");
            currentImprintType.addClass("selected");
//            if (currentImprintType.hasClass("selected")) currentImprintType.removeClass("selected");
//                else {
//                    //toggleSelectedAddon(currentImprintType, imprintTypes);
//                    imprintTypes.removeClass("selected");
//                    currentImprintType.addClass("selected");
//                }
            imprintTypechoise=$jQ('input[name="imprintType"]').val();
        });

        var imprintLocations = $jQ(".addon-imprint-location");
        var currentImprintLocation;
        imprintLocations.click(function(){
             if(! imprintTypes.hasClass("selected")){
                alert("You Can Choise Imprint Type !");
            }else{
                if(imprintTypechoise!='location'){
                    $jQ('input[name="imprintLocation"]').val($jQ(this).data("string"));
                        currentImprintLocation = $jQ(this);
                        imprintLocations.removeClass("selected");
                        currentImprintLocation.addClass("selected");
                        //selectedCountLocation = 1;
//                        if (currentImprintLocation.hasClass("selected")){currentImprintLocation.removeClass("selected");selectedCountLocation = 0;}
//                        else{
//                            toggleSelectedAddon(currentImprintLocation, imprintLocations);
//                            selectedCountLocation = 1;
//                        }
                 }else{
                    currentImprintLocation = $jQ(this);
                    selectedCountLocation = $jQ(".addon-imprint-location.selected").length;
                    if (currentImprintLocation.hasClass("selected")){
                        currentImprintLocation.removeClass("selected");
                        selectedCountLocation--;
                    }else if(selectedCountLocation < imprintsetlocation){//$jQ('input[name="imprintType"]').data("location")
                        currentImprintLocation.addClass("selected");
                        selectedCountLocation++;
                    }
                }
                $jQ('.imprintLocation').html("");
                $jQ(".addon-imprint-location.selected").each(function(index)
                {
                    $jQ('.imprintLocation').append('<input type="hidden" name="imprintLocation[]" value="' + $jQ(this).attr("data-string") + '" />');
                 });
            }
        });
    }

//  STEP3
    if (fsIndex && fsIndex == 2){
        reset_html(fsIndex);
        imLoca = $jQ('input[name="imprintLocation[]"]').map(function(){return $jQ(this).val();}).get();
        $jQ(".info_step2").append('Imprint Type:' + imprinttype + '<br />Imprint Location(s):' + imLoca.toString());
    }

//  STEP 4
    if(fsIndex==3){
        //save currentSelColors in input #ThreadColorInput = html
        for (i = 0; i < selColors.length; i++){
            var IDInsert = $jQ('#ThreadColorInput' + selColors[i]);
            var NameInsert = 'threadColor[' + selColors[i] + ']';
            //alert(currentSelColors[selColors[i]].toString());
            if(currentSelColors[selColors[i]].length)
                save_array_input(IDInsert,currentSelColors[selColors[i]],NameInsert);
        }

        reset_html(fsIndex);
        $jQ(".info_step3").append('Imprint Color(s):' + info_step3_html(selColors) );
        //call html step4
        step4 = html_step4();
        $jQ(".product-addon-imprint-design").html(step4);
        $jQ('input[name="additional"]').click(function(){
            if($jQ('input[name="additional"]').is(":checked")) $jQ(".locationmulti").css("display","block");
                else $jQ(".locationmulti").css("display","none");
        });
    }
	});
});

// END DOCUMENT READY EVENT



//
//  FUNCTIONS   FUNCTIONS
//

function showscript($selColors,$size,$sizearray,$sumquaty,$i){
    //$selColors='red',$size='small'
	$jQ("#slider-"+$size+"-"+$selColors).slider({
      range: "min",//kieu truoc hay sau
      value: 0,
      min: 0,
      max: $sumquaty,
      slide: function( event, ui ) {
	  var total=0;
	    $sizearray.forEach(function(val, k, theArray){
                if(val == $size) {total += parseInt(ui.value);}
                else {total += parseInt($jQ("#slider-" + val + "-" + $selColors).slider( "value" ));}
		});
        sum[$i] = total;
		//if (total > $sumquaty) return false;
        if(array_sum(sum) > $sumquaty){
            sum[$i] = $sumquaty - array_sum_not_key($i,sum);
            return false;
        }
        var remainig = $sumquaty - total;
        $jQ(".amount-color-step3").html("<span class='total' style='margin: 0 5px;'>"+"<b>" + (array_sum(sum)) +"</b>" +"</span>"+ "of"+" <span class='max' style='margin: 0 5px;'>"+ "<b>" +$sumquaty +"</b>" + "</span>selected" +"(<span class='remainig' style='color:red;'>" + ($sumquaty - (array_sum(sum))) + "</span>remainig)</div>");
        //$jQ("#amount-" + $selColors).html("<span class='total' style='margin: 0 5px;'>"+"<b>" + (array_sum(sum)) +"</b>" +"</span>"+ "of"+" <span class='max' style='margin: 0 5px;'>"+ "<b>" +$sumquaty +"</b>" + "</span>selected" +"(<span class='remainig' style='color:red;'>" + ($sumquaty - (array_sum(sum))) + "</span>remainig)</div>");
        $jQ("#amount" + $i).val(total);
        $jQ("#text-" + $size + "-" + $selColors).val(ui.value);
      }
    });
}

function array_sum($sum){
    var total = 0;
    for($k = 0; $k < $sum.length ; $k++){
        total += $sum[$k];}
    return total;
}

function array_sum_not_key($i,$sum){
    var total = 0;
    for($k = 0; $k < $sum.length ; $k++){
        if($k != $i) total += $sum[$k];}
    return total;
}


function clickColor(colorSwatches,ParentElement,IDInsert,NameInsert,$count){
    //$count=3
    var currentSwatch;
    var $array_clickcolor = [];
    selectedCount = 0;

    colorSwatches.click(function(){
        currentSwatch = $jQ(this);
        var $color = currentSwatch.data('string');
        if (currentSwatch.hasClass("selected")){
            currentSwatch.removeClass("selected");
            selectedCount--;
            delete_val_array($array_clickcolor,$color);
        }else if(selectedCount < $count){
            currentSwatch.addClass("selected");
            selectedCount++;
            $array_clickcolor.push($color);
        }
    //ParentElement.html("Product Color(s) (" + selectedCount + " of " + $count + ") <abbr class=\"required\" title=\"required\">*</abbr>");
    save_array_input(IDInsert,$array_clickcolor,NameInsert);
    });
}

function clickColor_embroidery(colorSwatches,ParentElement,IDInsert,NameInsert,$count,$selColors){
    var currentSwatch;
    var $selectedCount = 0;
    colorSwatches.click(function(){
        if(colorSwatches.hasClass("selected")){}else currentSelColors[$selColors]=[];
        //if(currentSelColors[$selColors].length > 0) currentSelColors[$selColors] = [];
        $jQ('.vehicle_none').prop("checked", false);
        $jQ('.vehicle_check_embroidery').prop("checked", false);
        $jQ('.checkedcolor').css("display","none");
        currentSwatch = $jQ(this);
        var $color = currentSwatch.data('string');
        if (currentSwatch.hasClass("selected")){
            currentSwatch.removeClass("selected");
            $selectedCount--;
            currentSelColors[$selColors]=[];
        }else if(currentSelColors[$selColors].length < $count){
            currentSwatch.addClass("selected");
            currentSelColors[$selColors].push($color);
            $selectedCount++;
        }
        //alert(currentSelColors[$selColors]);
    //ParentElement.html("Product Color(s) (" + selectedCount + " of " + $count + ") <abbr class=\"required\" title=\"required\">*</abbr>");
    });
}

function step3clickColor(colorSwatches,ParentElement,IDInsert,NameInsert,$count,$selColors){
//currentSelColors  set array of more color(1 or 3), it save info select of step3
    var currentSwatch;
    colorSwatches.click(function(){
//        if($jQ($ColorIDContent + ' .vehicle_check').is(":checked")) $count_add = save_array_vehicle.length;
//            else $count_add = 0;
        currentSwatch = $jQ(this);
        var $color = currentSwatch.data('string');
        if (currentSwatch.hasClass("selected")){
            currentSwatch.removeClass("selected");
            save_num[$selColors]--;
            delete_val_array(currentSelColors[$selColors],$color);
        }else if(currentSelColors[$selColors].length < $count){// + $countadd[$selColors]
            save_num[$selColors]++;
            currentSwatch.addClass("selected");
            currentSelColors[$selColors].push($color);
        }
//alert(currentSelColors[$selColors]);
        if(save_num[$selColors] >= $count){
            $jQ('#ColorIDContent_' + $selColors + ' .vehicle_check').prop("disabled",true);
            $jQ('#ColorIDContent_' + $selColors + ' .vehicle_check').prop("checked", false)
            $jQ('#ColorIDContent_' + $selColors + ' .checkedcolor').css("display","none");
        }else $jQ('#ColorIDContent_' + $selColors + ' .vehicle_check').prop("disabled",false);
    });
}

function toggleSelectedAddon(addonOption, addonSelector){
	addonSelector.removeClass("selected"); // Remove 'selected' class from all addon options for that selector
	addonOption.addClass("selected");
}

function imprinttype_js_step3($selColors,$imprinttype,$setcolor,$colorswatches2,$colorname2,$colorinput2,$colorarray2){
    //$setcolor = imprintsetcolor
    var $ColorIDContent = '#ColorIDContent_' + $selColors;
    if($setcolor == 0) $setcolor = 999999;
    switch($imprinttype){
        case "embroidery":
            $jQ($ColorIDContent + ' .vehicle_check_embroidery').click(function(){
                if($jQ($ColorIDContent + ' .vehicle_check_embroidery').is(":checked")){
                    $countadd[$selColors] = 1;
                    $jQ( this ).attr('checked',true);
                    $jQ($ColorIDContent + ' .vehicle_none').attr( 'checked',false);
                    $jQ($ColorIDContent + ' .checkedcolor').css("display","block");
                    $jQ($ColorIDContent + " .vehicle").css("bottom","0px");
                    $jQ($ColorIDContent + ' .vehicle_none').prop("checked", false);
                    $jQ($ColorIDContent + ' .addon-color-swatch').removeClass("selected");
                    currentSelColors[$selColors]=[];
                    var jqchoice=$jQ($ColorIDContent + ' .checkedcolor input[name="[checkedcolor' + $selColors + '][1]"]');
                    var jqclick=$jQ($ColorIDContent + ' .checkedcolor .step3color');
                    var jqinput=$jQ($ColorIDContent + ' .checkedcolor .stepinputcolor');
                    jquery_iris(jqchoice,jqclick,jqinput,$selColors);
                }else{
                    $jQ($ColorIDContent + " .checkedcolor").css("display","none");
                    $jQ($ColorIDContent + " .vehicle").css("bottom","64px");
                }
                //selectedCount=0;
            });
            $jQ($ColorIDContent + ' .vehicle_none').click(function(){
                if($jQ($ColorIDContent + ' .vehicle_none').is(":checked")){
                    $jQ( this ).attr( 'checked',true);
                    $jQ($ColorIDContent + ' .vehicle_check_embroidery').attr( 'checked',false);
                    $jQ($ColorIDContent + " .checkedcolor").css("display","none");
                    $jQ($ColorIDContent + ' .vehicle_check_embroidery').prop("checked", false);
                    $jQ($ColorIDContent + ' .addon-color-swatch').removeClass("selected");
                    currentSelColors[$selColors]=[];
                    currentSelColors[$selColors].push("none");
                }
                //selectedCount=0;
            });
            //select color chi chon duoc 1 color
            clickColor_embroidery($colorswatches2,$colorname2,$colorinput2,$colorarray2,1,$selColors);
            break;
        default:
            step3clickColor($colorswatches2,$colorname2,$colorinput2,$colorarray2,$setcolor,$selColors);
            //su ly nut lua chon
            //save_array_vehicle=[];

            check_click = false;
            $jQ($ColorIDContent + ' .vehicle_check').click(function(){
                if($jQ($ColorIDContent + ' .vehicle_check').is(":checked")){
                    $countadd[$selColors] = 1;
                    $jQ($ColorIDContent + " .checkedcolor").css("display","block");
                    $jQ($ColorIDContent + " .vehicle").css("bottom","0px");
                    check_click = true;
                    if(currentSelColors[$selColors].length < $setcolor){
                        click_add_color($selColors,$setcolor);
                        click_delete_color($selColors,$setcolor);
                        var jqchoice=$jQ($ColorIDContent + ' .addcolor1 input[name="checkedcolor[' + $selColors + '][1]"]');
                        var jqclick=$jQ($ColorIDContent + ' .addcolor1 .step3color');
                        var jqinput=$jQ($ColorIDContent + ' .addcolor1 .stepinputcolor');
                        jquery_iris(jqchoice,jqclick,jqinput,$selColors,$setcolor);
                    }
                }else if(check_click){
                    $jQ($ColorIDContent + " .checkedcolor").css("display","none");
                    $jQ(".vehicle").css("bottom","64px");
                    //xoa phan tu mang currentSelColors[$selColors]
                    //for(array of save_array_vehicle[$selColors]){     chi su dung dc cho firefox
                    save_array_vehicle[$selColors].forEach(array,k,function(){
                        delete_val_array(currentSelColors[$selColors],array);
                    });
                    check_click=false;
                    $jQ($ColorIDContent + " .checkedcolor").html("");
                    $jQ($ColorIDContent + " .checkedcolor").append('\
                    <div class="checkedcolor">\
                        <div class="addcolor">\
                            <div class="addcolor1">\
                                <input type="radio" value="Pantone" name="checkedcolor[' + $selColors + '][1]" checked="true" />Pantone/PMS\
                                <input type="radio" value="RGB" name="checkedcolor[' + $selColors + '][1]">RGB color\
                                <input type="radio" value="Hex" name="checkedcolor[' + $selColors + '][1]">Hex color\
                                <input type="radio" value="Custom" name="checkedcolor[' + $selColors + '][1]">Custom<br>\
                                <label class="labelSize">Pantone:</label>\
                                <input type="text"  class="stepinputcolor" value="" name="pantone[]" readonly>\
                                <b class="step3color">C</span><br>\
                            </div>\
                        </div>\
                        <button type="button" class="delete_color_class">Delete Color</button>\
                        <button type="button" class="add_color_class">Add Color</button>\
                    </div>');
                }
            });

    }
}

//Bottom add color and delete color
function click_add_color($selColors,$setcolor){
    $jQ('#ColorIDContent_' + $selColors +  " .add_color_class").click(function(){
       // alert(currentSelColors[$selColors].length + $countadd[$selColors]);1+1  4
    var varold = $jQ('#ColorIDContent_' + $selColors + ' .addcolor' + $countadd[$selColors] + " .stepinputcolor").val();
    if(save_num[$selColors] + $countadd[$selColors] < $setcolor){
        if(varold==""){
            alert("You Can Enter Val After!");
        }else{
            $countadd[$selColors]++;
            $jQ('#ColorIDContent_' + $selColors + ' .addcolor').append('<div class="addcolor'+ $countadd[$selColors] +'">' +
                    '<input type="radio" name="checkedcolor[' + $selColors + "][" + $countadd[$selColors] + ']" value="Pantone" checked="checked" />Pantone/PMS' +
                    '<input type="radio" name="checkedcolor[' + $selColors + "][" + $countadd[$selColors] + ']" value="RGB">RGB color' +
                    '<input type="radio" name="checkedcolor[' + $selColors + "][" + $countadd[$selColors] + ']" value="Hex">Hex color' +
                    '<input type="radio" name="checkedcolor[' + $selColors + "][" + $countadd[$selColors] + ']" value="Custom">Custom<br>' +
                    '<label class="labelSize">Pantone:</label>' +
                    '<input type="text" class="stepinputcolor" value="" name="pantone[]" readonly>' +
                    '<label class="step3color">C</label><br>' +
                '</div>'
            );
            $jQ('#ColorIDContent_' + $selColors +  " .delete_color_class").css("display","block");
            currentSelColors[$selColors].push("");
            var jqchoice = $jQ('#ColorIDContent_' + $selColors + ' .addcolor' + $countadd[$selColors] + ' input[name="checkedcolor[' + $selColors + "][" + $countadd[$selColors] + ']"]');
            var jqclick = $jQ('#ColorIDContent_' + $selColors + ' .addcolor' + $countadd[$selColors] + ' .step3color');
            var jqinput = $jQ('#ColorIDContent_' + $selColors + ' .addcolor' + $countadd[$selColors] + ' .stepinputcolor');
            jquery_iris(jqchoice,jqclick,jqinput,$selColors,$setcolor);
        }
    }else {failureStep = "Sorry You Can't Click Add Color";
            return false;}
    });
}

function click_delete_color($selColors,$setcolor){
    $jQ('#ColorIDContent_' + $selColors + " .delete_color_class").click(function(){
        delete_val_array(save_array_vehicle[$selColors],$jQ('#ColorIDContent_' + $selColors + " .addcolor"+$countadd[$selColors]+" .stepinputcolor").val());
        delete_val_array(currentSelColors[$selColors],$jQ('#ColorIDContent_' + $selColors + " .addcolor"+$countadd[$selColors]+" .stepinputcolor").val());
        $jQ('#ColorIDContent_' + $selColors + ' .checkedcolor .addcolor'+$countadd[$selColors]).remove();
        $countadd[$selColors]--;
        if(currentSelColors[$selColors].length + $countadd[$selColors] < $setcolor  )$jQ('#ColorIDContent_' + $selColors + " .add_color_class").css("display","block");
        if($countadd[$selColors]==1) $jQ('#ColorIDContent_' + $selColors + " .delete_color_class").css("display","none");
    });
}

function jquery_iris(jqchoice,jqclick,jqinput,$selColors,$setcolor){
    //jqclick=$jQ(".step3color")
    //jqchoice=$jQ('input[name="checkedcolor-red-1"]');
    checkedcolortype = jqchoice.val();//default panton
    jqchoice.click(function(){
        checkedcolortype = $jQ(this).val();//panton rgb hex custom
        if(checkedcolortype =="Custom"){
            jqinput.prop("readonly",false);
            html_old = jqinput.val();
            jqinput.change(function(e){
                 if(currentSelColors[$selColors].length <= $setcolor){
                    html = jqinput.val();
                    //html = $jQ("#ColorIDContent_" + $selColors + " .addcolor" + $countadd[$selColors] + " .stepinputcolor").val();
                    delete_val_array(currentSelColors[$selColors],html_old);
                    delete_val_array(save_array_vehicle[$selColors],html_old);
                    currentSelColors[$selColors].push(html);
                    save_array_vehicle[$selColors].push(html);
                    html_old = html;
                }
            });
        }else jqinput.prop("readonly",true);
    });
    // JQUERY IRIS
    var check_click_iris=false;
    var options = {
        color: false,
        mode: 'hsl',
        controls: {
            horiz: 's', // horizontal defaults to saturation
            vert: 'l', // vertical defaults to lightness
            strip: 'h' // right strip defaults to hue
        },
        hide: true, // hide the color picker by default
        border: true, // draw a border around the collection of UI elements
        target: false, // a DOM element / jQuery selector that the element will be appended within. Only used when called on an input.
        width: 200, // the width of the collection of UI elements
        palettes: true, // show a palette of basic colors beneath the square.
        change: function(event, ui){
            check_click_iris = true;
            jqinput.prop("readonly",true);
            //jqclick.css("display","none");
            switch(checkedcolortype){
                case "RGB":
                        var r = ui.color.toRgb().r.toString();
                        var g = ui.color.toRgb().g.toString();
                        var b = ui.color.toRgb().b.toString();
                        html = "rgb (" + r + ", " + g + ", " + b + " )";
                    break;
                case "Hex":
                        html = ui.color.toString();
                    break;
                case "Pantone":
                        html = ui.color.toInt();
                     break;
                default: //Custom
                        //html = jqinput.val();
            };
                jqinput.val(html);
        }
    };
//jqchoice.change(function(){
    if(checkedcolortype!="Custom"){
        jqclick.iris(options);
        $jQ(':not(#ColorIDContent_' + $selColors + ' .iris-picker.iris-mozilla)').click(function(e){
            jqclick.iris('hide');
            //if(check_click_iris && ! check_val_array(html,currentSelColors[$selColors])){
            if(check_click_iris){
                if(currentSelColors[$selColors].length < $setcolor){
                    currentSelColors[$selColors].push(html);
                    save_array_vehicle[$selColors].push(html);
                }
                //alert(currentSelColors[$selColors]);
            }
            check_click_iris = false;
        });
        jqclick.click(function(){
            delete_val_array(currentSelColors[$selColors],jqinput.val());
            delete_val_array(save_array_vehicle[$selColors],jqinput.val());
            jqclick.iris('hide');
            jqclick.iris('show');
            return false;
        });
    }
//});
}

//  FUNTION STEP 4
function html_step4(){
    var $return = '';
    var imprinttype=$jQ('input[name="imprintType"]').val();
    $setcolor=$jQ('input[name="imprintType"]').data("color");
    switch(imprinttype) {
        case "location":
            $return = step4_location();
            break;
        case "embroidery":
            if($jQ(".vehicle_none").is(":checked")) $return = step4_custom_text();
                else $return = step4_normal();
            break;
        default:
            $return = step4_normal();
    }
    return $return;
}

function step4_normal(){
    return '<h3 class="addon-name">Design <abbr class="required" title="required">*</abbr></h3>\
        <p class="form-row form-row-wide">\
            <label for="browse">Upload Art:</label></br>\
            <input id="browse" type="file" name="browse[]" />\
        </p>\
        <p class="form-row form-row-wide">\
            <label for="custom-text">Custom Text:</label></br>\
            <input id="custom-text" type="text" name="custom-text" />\
        </p>\
        <p class="additional">\
            <label for="additional">Additional Information:</label><br>\
            <textarea id="additional" name="additional" style="width: 300px;"></textarea>\
        </p>';
}

function step4_custom_text(){
    return '<h3 class="addon-name">Design <abbr class="required" title="required">*</abbr></h3>\
        <p class="form-row form-row-wide">\
            <label for="browse">Upload Art:</label></br>\
            <input id="browse" type="file" name="browse[]" />\
        </p>\
        <p class="additional">\
            <label for="additional">Additional Information:</label><br>\
            <textarea id="additional" name="additional" style="width: 300px;"></textarea>\
        </p>';
}

function step4_location(){
    return '<h3 class="addon-name">Design <abbr class="required" title="required">*</abbr></h3>\
        <p class="form-row form-row-wide">\
            <label for="browse">Upload Art/Logo(1st Location):</label></br>\
            <input id="browse" type="file" name="browse[]" />\
        </p>\
		 <input type="checkbox" name="additional" value="additional">Upload additional images for 2nd imprint location\
        <p class="form-row form-row-wide locationmulti">\
            <label for="browse">Upload Art/Logo(2st Location):</label></br>\
            <input id="browse" type="file" name="browse[]" />\
        </p>\
        <p class="form-row form-row-wide">\
            <label for="custom-text">Custom Text:</label></br>\
            <input id="custom-text" type="text" name="custom-text" />\
        </p>\
        <p class="additional">\
            <label for="additional">Additional Information:</label><br>\
            <textarea id="additional" name="additional" style="width: 300px;"></textarea>\
        </p>';
}

//  function array jquery
function save_array_input(IDInsert,currentSelColors,NameInsert){
    //IDInsert  vi tri save
    //currentSelColors  val array save
    //NameInsert    name array can save
    IDInsert.html("");
    $jQ.each(currentSelColors , function(key,val){
         IDInsert.append('<input type="hidden" name="' + NameInsert + '[]" value="' + val + '" />');
    });
}
// delete $val in $array
function delete_val_array($array,$val){
    for(var key in $array){
        if($array[key] == $val) $array.splice(key,1);
    }
}
//check html on array currentSelColors
function check_val_array(html,currentSelColors){
    for(var key in currentSelColors){
        if(currentSelColors[key] == html) return true;
    }
    return false;
}

function reset_html(fsIndex){
    for(var i = 1;i <= $jQ("form.cart fieldset").length; i++)
        if( i >= fsIndex ) $jQ(".info_step" + i).html("");
}

function info_step3_html(selColors){
    var $return="";
    for (i = 0; i < selColors.length; i++){
        $return+= '(' + selColors[i] + ') - ' + currentSelColors[selColors[i]].toString();
    }
    $return+= "Sizes:";
    for (i = 0; i < selColors.length; i++){
        $return+= '(' + selColors[i] + ') S - ' + $jQ("#text-small-" + selColors[i]).val() + "<br />" +
                "M - " + $jQ("#text-medium-" + selColors[i]).val() + "<br />" +
                "L - " + $jQ("#text-large-" + selColors[i]).val() + "<br />" +
                "XL - " + $jQ("#text-xlarge-" + selColors[i]).val();
    }
    return $return;
    //currentSelColors
}
// END FUNCTIONS