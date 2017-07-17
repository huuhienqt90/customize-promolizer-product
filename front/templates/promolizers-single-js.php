<?php
/**
 * Created by PhpStorm.
 * User: thach
 * Date: 9/16/14
 * Time: 10:09 AM
 */
global $izwpsp;
?>

<script type="text/javascript">
    // JS STEP1     JS STEP1
    var sumquaty = <?php echo (int)min($izwpsp['DT_izw_min']);?>;//gia tri max quaty
    var position = 0;
    jQuery(document).ready(function($){
        $("#qty-slider").slider({
            range: "min",
            value: <?php echo (int)min($izwpsp['DT_izw_min']);?>,
            min: <?php echo (int)min($izwpsp['DT_izw_min']);?>,
            max: <?php echo (int)max($izwpsp['DT_izw_max']);?>,
            step: 12,
            slide: function(event, ui){
                $("#amount").text(parseInt(ui.value));
                $("#qty-text").val(parseInt(ui.value));
                $("#inputAmount").val(parseInt(ui.value));
                jquery_sum_product(parseInt(ui.value));
                sumquaty = parseInt(ui.value);
            }
        });
$("form.cart").on("fs_shown", function(event, fsIndex){
// JS STEP2     JS STEP2
        if (fsIndex && fsIndex == 1){
            $(".addon-imprint-type").click(function(){
                imprinttype = $(this).data('string');
                <?php foreach($izwpsp['imprint_types'] as $type): ?>
                    if('<?php echo $type->type_slug;?>' == imprinttype){
                        //lay gia cua imprint type
                        var $price = parseFloat(get_price_imprint_types(position,<?php echo $type->ID;?>));
                        var $msrp = parseFloat(get_price_msrp(position,<?php echo $type->ID;?>));
                        //lay gia tri setup
                        $setup = parseFloat('<?php echo $type->setup_charge;?>');
                    }
                <?php endforeach;?>
                <?php if($izwpsp['DT_izw_override_shipping']){?>
                    <?php foreach($izwpsp['DT_izw_shipping'] as $key=>$val){?>
                        if(<?php echo $key;?> == position) $shipping = parseFloat('<?php echo $val;?>');
                    <?php }?>
                <?php }else $shipping = 0;?>
                display_total_product(sumquaty,$price,$msrp,$setup,$shipping);
            });
        }

        // JS STEP3     JS STEP3
        if (fsIndex && fsIndex == 2){
        $("#list_color").empty();
        imprinttype = $('input[name="imprintType"]').val();//lay imprint type kieu
            var listcolorHtml ='';
            for (var i = 0; i < selColors.length; i++){
                if(i==0){listcolorHtml = '';}
                if(selColors.length != 1){
                    listcolorHtml += '<div id="colorID_' + selColors[i] + '" class="colorTitle"><h3>' + toTitleCase(selColors[i]) + '</h3></div>';
                }

                listcolorHtml += '<div class="colorContent" id="ColorIDContent_' + selColors[i] + '">';

                if(imprinttype == "embroidery") {
                     listcolorHtml += '<label for="thread-color"><?php _e( "Thread Color: ", __TEXTDOMAIN__ ); ?><abbr class="required" title="required">*</abbr></label>\
                    <p style="margin-bottom: 7px;">You Should Only select a thread color if you would like add custom text you order.</p>';
                }else {listcolorHtml += '<p for="thread-color"><?php _e( "Imprint color: ", __TEXTDOMAIN__ );?><abbr class="required" title="required">*</abbr></p>';}

                listcolorHtml += '<div class="check_all">\
                    <div class="screen_check">';

    <?php if(!empty($izwpsp['imprint_colors'])){foreach ($izwpsp['imprint_colors'] as $key => $color):?>
        <?php if(($key % 4) == 0){?>
            listcolorHtml += '<p class="form-row form-row-wide product-addon"><span id="<?php echo $color->color_slug;?>" class="addon addon-color-swatch" data-string="<?php echo $color->color_slug;?>" style="background:<?php echo $color->hex_code;?>"></span>';
        <?php }elseif(($key % 4) == 3){?>
                listcolorHtml += '<span id="<?php echo $color->color_slug;?>" class="addon addon-color-swatch" data-string="<?php echo $color->color_slug;?>" style="background:<?php echo $color->hex_code;?>"></span></p>';
        <?php }else{?>
            listcolorHtml += '<span id="<?php echo $color->color_slug;?>" class="addon addon-color-swatch" data-string="<?php echo $color->color_slug;?>" style="background:<?php echo $color->hex_code;?>"></span>';
    <?php }endforeach;} else{?> listcolorHtml += 'Please Choose Imprint color!';<?php }?>

                listcolorHtml += '</p>\
                    </div></div>';

                if(imprinttype != "embroidery"){
                listcolorHtml += '<div class="screen_check_color">\
                        <div class="vehicle">\
                            <input type="checkbox" class="vehicle_check" value="Car" name="vehicle">Specify Your own color(s)<br>\
                        </div>\
                        <div class="checkedcolor">\
                            <div class="addcolor">\
                                <div class="addcolor1">\
                                    <div class="input"><input type="radio" value="Pantone" name="checkedcolor[' + selColors[i] + '][1]" checked="true" />Pantone/PMS</div>\
                                    <div class="input"><input type="radio" value="RGB" name="checkedcolor[' + selColors[i] + '][1]">RGB color</div>\
                                    <div class="input"><input type="radio" value="Hex" name="checkedcolor[' + selColors[i] + '][1]">Hex color</div>\
                                    <div class="input"><input type="radio" value="Custom" name="checkedcolor[' + selColors[i] + '][1]">Custom</div>\
                                    <label class="labelSize">Pantone:</label>\
                                    <input type="text"  class="stepinputcolor" value="" name="pantone[]" readonly>\
                                    <span class="step3color">C</span><br>\
                                </div>\
                            </div>\
                            <button type="button" class="delete_color_class">Delete Color</button>\
                            <button type="button" class="add_color_class">Add Color</button>\
                        </div>\
                    </div>';}
                else{
                listcolorHtml += '<div class="screen_check_color">\
                        <input class="vehicle_none" type="checkbox" name="none_' + selColors[i] + '" value="Car">None(Select this only if you plan on uploading an artwork file and do not want to add custom text)<br>\
                        <div class="vehicle">\
                            <input type="checkbox" class="vehicle_check_embroidery" value="Car" name="vehicle_' + selColors[i] + '">Specify Your own color(s)<br>\
                        </div>\
                        <div class="checkedcolor">\
                                <div class="input"><input type="radio" value="Pantone" name="checkedcolor[' + selColors[i] + '][1]" checked>Pantone/PMS</div>\
                                <div class="input"><input type="radio" value="RGB" name="checkedcolor[' + selColors[i] + '][1]">RGB color</div>\
                                <div class="input"><input type="radio" value="Hex" name="checkedcolor[' + selColors[i] + '][1]">Hex color</div>\
                                <div class="input"><input type="radio" value="Custom" name="checkedcolor[' + selColors[i] + '][1]">Custom</div>\
                                <label class="labelSize">Pantone:</label>\
                                <input type="text"  class="stepinputcolor" value="" name="pantone[]" readonly>\
                                <label class="step3color">C</label><br>\
                        </div>\
                 </div>';
                    }

                listcolorHtml += '<div class="ColorSize">\
                        <p class="form-row form-row-wide product-addon">\
                            <input type="hidden" value="0" id="amount' + i + '"/>\
                            <label for="thread-color">Size:</label>\
                            <span class="amount-color-step3" id="amount-' + selColors[i] + '"></span>\
                        </p>\
                        <label class="labelSize">Small:</label><div id="slider-small-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-small-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][small]" readonly/><br />\
                        <label class="labelSize">Medium:</label><div id="slider-medium-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-medium-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][medium]" readonly/><br />\
                        <label class="labelSize">Large:</label><div id="slider-large-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-large-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][large]" readonly/><br />\
                        <label class="labelSize">X-Large:</label><div id="slider-xlarge-' + selColors[i] + '" class="addon addon-total-quantity"></div>\
                        <input id="text-xlarge-' + selColors[i] + '" class="addon color_changes" type="text" value=0 name="step3size[' + selColors[i] + '][xlarge]" readonly/><br />\
                    </div>\
                    <div id="ThreadColorInput' + selColors[i] + '"></div>\
                    <div class="clear"></div>\
                </div>';
            }
        $("#list_color").html(listcolorHtml);
        var size = ["small", "medium", "large", "xlarge"];
        for(i in selColors){
            sum[i] = 0;
             size.forEach(function (val,j, theArray){
                showscript(selColors[i],val,size,sumquaty,i);
            });
        };

        for (i = 0; i < selColors.length; i++){
            $colorswatches2 = $('#ColorIDContent_' + selColors[i] + ' p span.addon-color-swatch');
            $colorname2 = $('#ColorIDContent_' + selColors[i] + ' h3.addon-name');
            $colorinput2 = $('#ThreadColorInput' + selColors[i]);
            $colorarray2 = 'threadColor[' + selColors[i] + ']';
            currentSelColors[selColors[i]]= new Array();
            save_array_vehicle[selColors[i]]= new Array();
            save_num[selColors[i]] = [];// dem so luong Imprint color da lua chon
            $countadd[selColors[i]] = [];// so luong add_color
            imprinttype_js_step3(selColors[i],imprinttype,imprintsetcolor,$colorswatches2,$colorname2,$colorinput2,$colorarray2);
            //imprintsetcolor so luong color duoc chon toi da cho buoc 3
        }

        $('#list_color .colorContent:not(:first)').hide();
        $("#list_color .colorTitle").click(function(){
            $('#list_color .colorContent').slideUp('normal');
            if($(this).next('#list_color .colorContent').is(':hidden') == true){
                $(this).next('#list_color .colorContent').slideDown('normal');
            }
        });

        }
    });


    //
    //      FUNCTION
    //
    function jquery_sum_product($ui){
        <?php $max = count($izwpsp['DT_izw_max']); for($i = 0;$i < $max ;$i++){?>
            if((<?php echo (int)$izwpsp['DT_izw_min'][$i];?> <= $ui) && (<?php echo (int)$izwpsp['DT_izw_max'][$i];?> > $ui)){
                <?php $price = get_price_DT($i,$izwpsp['DTimprint_types_dnmprc']);?>
                <?php if($izwpsp['DT_izw_override_shipping']) $shipping = $izwpsp['DT_izw_shipping'][$i];else $shipping = 0;?>
                <?php $setup = get_step_imprint_types($izwpsp['DTimprint_types_dnmprc']);?>
                display_total_product($ui,<?php echo $price;?>,<?php echo $izwpsp['DT_izw_msrp'][$i][$izwpsp['DTimprint_types_dnmprc']];?>,<?php echo $setup;?>,<?php echo $shipping;?>);
                get_price_imprint_types(<?php echo $i;?>,-1);
                position = <?php echo $i;?>;
            }
    <?php }?>
    }

    function display_total_product($ui,$price,$msrp,$setup,$shipping){
        //$ui           so luong
        //$price        gia
        //$msrp         gia msrp
        //$setup        gia setup
        //$shipping     gia shipping
        //.totalstep1
        $('.sub_total_table h4 .totalstep1').html("$" + ($price * $ui).toFixed(2));
        $('.sub_total_table .pricestep1').html("($" + $price + " x " + $ui + ")");
        $('.sub_total_table .savingstep1').html("($" + (($msrp - $price)*$ui).toFixed(2) + ")");
        $('.sub_total_table .totalproduct').html("($" + (($price * $ui) + $setup + $shipping).toFixed(2) + ")");
        $('.sub_total_table .romoship span').html("$" + ($shipping).toFixed(2));
        $('input[name = "romoship"]').val(($shipping).toFixed(2));
        $('.sub_total_table .setup').html("$" + ($setup).toFixed(2));
    }

    function get_price_imprint_types($i,$posi){
        //$i        vi tri cot
        //$posi     vi tri imprint
        <?php if(check_onsale_product()):?>
            <?php foreach($izwpsp['DT_izw_sale'] as $key=>$price):?>
                if($i == <?php echo $key;?>){
                    <?php foreach($price as $k=>$v){?>
                        if($posi == -1){
                            $(".price_change_<?php echo $k; ?>").html("$<?php echo $v;?> /item");
                        }
                        if($posi == <?php echo $k; ?>) return "<?php echo $v; ?>";
                    <?php }//price ?>
                }
            <?php endforeach;//DT_izw_price ?>
        <?php else :?>
            <?php foreach($izwpsp['DT_izw_price'] as $key=>$price):?>
                if($i == <?php echo $key;?>){
                    <?php foreach($price as $k=>$v){?>
                        if($posi == -1){
                            $(".price_change_<?php echo $k; ?>").html("$<?php echo $v;?> /item");
                        }
                        if($posi == <?php echo $k; ?>) return "<?php echo $v; ?>";
                    <?php }//price ?>
                }
            <?php endforeach;//DT_izw_price ?>
        <?php endif; ?>
    }

    function get_price_msrp($i,$posi){
        //$i        vi tri cot
        //$posi     vi tri imprint
        <?php foreach($izwpsp['DT_izw_msrp'] as $key=>$price):?>
            if($i == <?php echo $key;?>){
                <?php foreach($price as $k=>$v){?>
                    if($posi == <?php echo $k; ?>) return "<?php echo $v; ?>";
                <?php }//price ?>
            }
        <?php endforeach;//DT_izw_msrp ?>
    }

});
</script>