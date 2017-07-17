<?php
    global $izwpsp;
?>
<div class="product_color_page">
    <form class="cart" id="addtoCartBox" method="post" action="" enctype='multipart/form-data'>
        <ul id="progressbar">
            <li class="active"><?php _e( "Product Colors", __TEXTDOMAIN__ ); ?></li>
            <li><?php _e( "List Color", __TEXTDOMAIN__ ); ?></li>
            <li><?php _e( "Product Types", __TEXTDOMAIN__ ); ?></li>
            <li><?php _e( "Custom Field", __TEXTDOMAIN__ ); ?></li>
        </ul>
        <div>
            <div class="izw_content_single">
                <!--Design Step 1 by JerryTran-->
                <fieldset>
                    <div class="save_info_product" style="display:none">
                        <?php if(!empty($izwpsp['DT_izw_min'])){$min_izwpsp = min($izwpsp['DT_izw_min']);}?>
                        <input name="min" value="<?php echo $min_izwpsp;?>" />
                        <input name="max" value="<?php if(!empty($izwpsp['DT_izw_max'])){echo max($izwpsp['DT_izw_max']);}?>" />
                    </div>
                    <div class="quantity">
                        <table style="width:54%">
                            <tr>
                                <td><?php _e( "QTY", __TEXTDOMAIN__ ); ?></td>
                                <?php
                                if(!empty($izwpsp['DT_izw_min']) && (is_array($izwpsp['DT_izw_min']))){
                                foreach($izwpsp['DT_izw_min'] as $rows): ?>
                                    <td><?php echo $rows; ?></td>
                                <?php endforeach;
                                }else{
                                 _e( "Please choose", __TEXTDOMAIN__ );
                                }
                                ?>
                            </tr>
                            <tr style="color:#f7b738;">
                                <td><?php _e( "MSRP", __TEXTDOMAIN__ ); ?></td>
                                <?php
                                if(!empty($izwpsp['DT_izw_msrp'])  && (is_array($izwpsp['DT_izw_msrp']))){
                                foreach($izwpsp['DT_izw_msrp'] as $key=>$values): ?>
                                    <td><?php echo $values[$izwpsp['DTimprint_types_dnmprc']]; ?></td>
                                <?php endforeach;}
                                else{
                                    _e( "Please choose", __TEXTDOMAIN__ );
                                }?>
                            </tr>
                            <?php $class = ''; if(check_onsale_product()) $class = ' class="izwSale"'; ?>
                                <tr<?php echo $class; ?>>
                                    <td><?php _e( "Price", __TEXTDOMAIN__ ); ?></td>
                                    <?php
                                    if(!empty($izwpsp['DT_izw_price'])  && (is_array($izwpsp['DT_izw_price']))){
                                    foreach($izwpsp['DT_izw_price'] as $key=>$values): ?>
                                        <td><?php echo $values[$izwpsp['DTimprint_types_dnmprc']]; ?></td>
                                    <?php endforeach;}
                                    else{
                                        _e( "Please choose", __TEXTDOMAIN__ );
                                    }?>
                                </tr>
                                <?php if(check_onsale_product()): ?>

                                    <tr>
                                        <td><?php _e( "Sale", __TEXTDOMAIN__ ); ?></td>
                                        <?php
                                        foreach($izwpsp['DT_izw_sale'] as $key=>$values): ?>
                                            <td><?php echo $values[$izwpsp['DTimprint_types_dnmprc']]; ?></td>
                                        <?php endforeach;?>
                                    </tr>

                                <?php endif; ?>
                            <?php if(!empty($izwpsp['DT_izw_override_shipping']) && $izwpsp['DT_izw_override_shipping'] == '1'): ?>
                            <tr>
                                <td><?php _e( "Shipping", __TEXTDOMAIN__ ); ?></td>
                                <?php
                                if(!empty($izwpsp['DT_izw_shipping'])  && (is_array($izwpsp['DT_izw_shipping']))){
                                foreach($izwpsp['DT_izw_shipping'] as $rows): ?>
                                    <td><?php echo $rows; ?></td>
                                <?php endforeach;}
                                else{
                                    _e( "Please choose", __TEXTDOMAIN__ );
                                }?>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="quantity_product">
                        <div class="required-product-addon product-addon product-addon-total-quantity">
                            <h3 class="addon-name"><?php _e( "Total Product Quantity ", __TEXTDOMAIN__ ); ?><abbr class="required" title="required">*</abbr></h3>
                            <div class="form-row-wide">
                                <p>
                                    <label for="amount"><?php _e( "Quantity: ", __TEXTDOMAIN__ ); ?></label>
                                    <span id="amount"><?php echo $min_izwpsp;?></span>
                                    <input autocomplete="off" name="imprint_quantity" id="inputAmount" value="<?php echo $min_izwpsp;?>" type="hidden" />
                                </p>
                                <div id="qty-slider" class="addon addon-total-quantity"></div>
                                <input autocomplete="off" id="qty-text" name="quantity" class="addon" type="text" value="<?php echo $min_izwpsp;?>" readonly/>
                            </div>
                        </div>
                        <div id="ProductColor1" class="required-product-addon product-addon product-addon-color-group">
                            <h3 class="addon-name"><?php _e( "Product Color(s)", __TEXTDOMAIN__ ); ?>
                                <abbr class="required" title="required">*</abbr>
                            </h3>
                            <p class="description"><i><?php _e( "You may select up to 3 products colors", __TEXTDOMAIN__ ); ?></i></p>
                            <div class="chose_color">
                                <p class="form-row form-row-wide">
                                    <?php
                                    //var_dump($izwpsp['product_colors']);
                                    if(!empty($izwpsp['product_colors'])  && (is_array($izwpsp['product_colors']))){
                                        foreach ($izwpsp['product_colors'] as $key):
                                            $class = 'addon addon-color-swatch';
                                            if( !empty($key->hex_code2) ){
                                                $class .= ' color-swatch-two-color';
                                                ?>
                                                <style type="text/css">
                                                    .product-addon #<?php echo $key->color_slug;?>{
                                                        padding: 0;
                                                        border-width: 2.3em 2.3em 0 0;
                                                        border-color: <?php echo $key->hex_code1;?> <?php echo $key->hex_code2;?>;
                                                    }
                                                </style>
                                                <?php
                                            }
                                            ?>
                                            <span id="<?php echo $key->color_slug;?>" class="<?php echo $class; ?>" data-string="<?php echo $key->color_slug;?>" style="background:<?php echo $key->hex_code1;?>"></span>
                                    <?php endforeach;}
                                    else{ ?>
                                    <?php   _e( "Please Choose Product Color!", __TEXTDOMAIN__ );}?>
                                </p>
                            </div>
                            <div class="colorinput"></div>
                        </div>
                    </div>
                    <input class="next action-button button alt" type="button" value="Promolize it!" name="next" />
                </fieldset>
                <!--End Step 1 by JerryTran -->

                <!--Design Step 2 by JerryTran -->
                <fieldset>
                    <div class="required-product-addon product-addon product-addon-imprint-type">
                        <h3 class="addon-name"><?php _e( "Imprint Type ", __TEXTDOMAIN__ ); ?><abbr class="required" title="required">*</abbr></h3>
                        <p class="form-row form-row-wide">
                            <?php
                            if(!empty($izwpsp['imprint_types'])  && (is_array($izwpsp['imprint_types']))){
                                foreach ($izwpsp['imprint_types'] as $type): ?>
                                    <div class="embroidery_imprint_type">
                                        <span id="it-embroidery" class="addon addon-imprint-type <?php if($type->ID == $izwpsp['DTimprint_types_dnmprc']) echo "selected";?>" title="<?php echo $type->type_name;?>" data-string="<?php echo $type->type_slug;?>" data-color="<?php echo $type->max_color;?>" data-location="<?php echo $type->max_location;?>">
                                             <img src="<?php echo $type->image; ?>"/>
                                        </span>
                                        <p class="price_change_<?php echo $type->ID;?>">
                                            <?php if(check_onsale_product()) echo "$",$izwpsp['DT_izw_sale'][0][$type->ID]," /item";
                                                    else echo "$",$izwpsp['DT_izw_price'][0][$type->ID]," /item"; ?></p>
                                        <h4><?php if(!empty($type->description)) echo $type->description;else echo $type->type_name;?></h4>
                                    </div>
                        <?php if($type->ID == $izwpsp['DTimprint_types_dnmprc']){?>
                                <input type="hidden" value="<?php echo $type->type_slug;?>" name="imprintType" data-color="<?php echo $type->max_color;?>" data-location="<?php echo $type->max_location;?>">
                        <?php } ?>
                                <?php endforeach;}
                            else{
                                _e( "Please Choose Imprint type!", __TEXTDOMAIN__ );
                            }
                            ?>
                        </p>
                        <input type="hidden" name="imprintType" value="" />
                    </div>
                    <?php if($izwpsp['check_location']): ?>
                    <div class="required-product-addon product-addon product-addon-imprint-location">
                        <h3 class="addon-name"><?php _e( "Imprint Location ", __TEXTDOMAIN__ ); ?><abbr class="required" title="required">*</abbr></h3>
                        <div class="imprint_type">
                            <?php
                            if(!empty($izwpsp['imprint_locations'])  && (is_array($izwpsp['imprint_locations']))){
                                foreach ($izwpsp['imprint_locations'] as $location):?>
                                     <div class="form-row form-row-wides">
                                        <span id="il-lt-sleeve" class="addon addon-imprint-location" title="<?php echo $location->location_name;?>" data-string="<?php echo $location->location_slug;?>">
                                            <img src="<?php echo $location->image ;?>"/>
                                        </span>
                                        <p><?php echo $location->location_name;?></p>
                                    </div>
                                <?php endforeach;
                            }else{
                                _e( "Please Choose Imprint Location!", __TEXTDOMAIN__ );
                            }?>
                        </div>
                        <div class="imprintLocation"></div>
                    </div>
                    <?php else: ?>
                        <div class="not-product-addon-imprint-location product-addon-imprint-location"></div>
                    <?php endif; ?>
                    <input class="previous action-button button alt" type="button" value="Previous" name="previous" />
                    <input class="next action-button button alt" type="button" value="Next" name="next" />
                </fieldset>
                <!--End Step 2 by JerryTran-->

                <!--Design Step 3 by JerryTran-->
                <fieldset>
                    <div id="list_color">
                    </div>
                        <input class="previous action-button button alt" type="button" value="Previous" name="previous">
                        <input class="next action-button button alt" type="button" value="Next" name="next">
                </fieldset>
                <!--End Step 3 by JerryTran -->

                <!--Design Step 4 by JerryTran-->
                <fieldset>
                    <div class="required-product-addon product-addon product-addon-imprint-design">
                    </div>
                    <input class="previous action-button button alt" type="button" value="Previous" name="previous" />
                    <?php do_action('woocommerce_external_add_to_cart2');?>
                </fieldset>
                <!--End Step 4 by JerryTran -->
            </div>

            <div class="izw_sidebar">
                <div class="selected_option_box">
                    <h3><?php _e( "Selected option box", __TEXTDOMAIN__ );?></h3>
                    <div class="info_step">
                        <div class="info_step1"></div>
                        <div class="info_step2"></div>
                        <div class="info_step3"></div>
                    </div>
                </div>
                <div class="sub_total">
                    <div class="sub_total_table">
                        <?php if(check_onsale_product()) $min = $izwpsp['DT_izw_sale'][0][ $izwpsp['DTimprint_types_dnmprc']];else $min = $izwpsp['DT_izw_price'][0][ $izwpsp['DTimprint_types_dnmprc']];?>
                        <h4><?php _e("Sub Total", __TEXTDOMAIN__);?><span class="totalstep1">
                                <?php echo "$",number_format(($min * $min_izwpsp),2);?></span></h4>
                        <p class="pricestep1"><?php echo "(",$min,"$ * ",$min_izwpsp,")";?></p>
                        <h4><?php _e("Saving", __TEXTDOMAIN__);?>
                            <span class="savingstep1">
                                <?php echo "($".number_format((($izwpsp['DT_izw_msrp'][0][$izwpsp['DTimprint_types_dnmprc']] - $min) * $min_izwpsp),2).")";?>
                            </span></h4>

                        <h4><?php _e("Setup", __TEXTDOMAIN__);?><span class="setup">
                                <?php $setup = get_step_imprint_types($izwpsp['DTimprint_types_dnmprc']);?>
                                <?php echo "$",$setup;?></span></h4>
                        <h4 class="romoship">
                            <span>
                                 <?php if($izwpsp['DT_izw_override_shipping'])
                                        {$shipping = $izwpsp['DT_izw_shipping'][0];
                                        echo "$",number_format($shipping,2);
                                        }else {$shipping = 0;echo "Free";}?>
                            </span>
                            <input type="hidden" value="<?php echo $shipping;?>" name="romoship"/>
                        </h4>
                        <p style="margin: 0px 9%; width: 80%; border: 2px solid rgb(231, 231, 231);"></p>
                        <h4 style="color:red;"><?php _e("Total", __TEXTDOMAIN__);?>
                            <span class="totalproduct"><?php echo "$",number_format((($min * $min_izwpsp) + $setup + $shipping),2); ?></span></h4>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>