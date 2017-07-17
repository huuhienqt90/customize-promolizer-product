<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 8/21/14
 * Time: 8:56 AM
 */
class IZW_Custom_Product_Data{
    function __construct(){
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'custom_product_data'), 10,1 );
        add_action( 'woocommerce_product_data_panels', array( $this, 'custom_product_data_panel') );
        add_action( 'save_post', array( $this, 'save' ),999,2 );

        add_action( 'wp_ajax_update_imprint_type', array( $this, 'update_imprint_type' ) );
        add_action( 'wp_ajax_nopriv_update_imprint_type', array( $this, 'update_imprint_type' ) );

        add_action( 'wp_ajax_update_imprint_type_table', array( $this, 'update_imprint_type_table' ) );
        add_action( 'wp_ajax_nopriv_update_imprint_type_table', array( $this, 'update_imprint_type_table' ) );
    }

    /**
     * Add Custom Tab In Product Of Woocommerce
     * @param $tabData
     * @return mixed
     */
    function custom_product_data($tabData){
        global $post;
        $tabData['izw_custom'] = array(
            'label'  => __( 'Custom', 'woocommerce' ),
            'target' => 'custom_product_data',
            'class'  => array('show_if_simple'),
        );
        return $tabData;
    }

    /**
     * Add Panel for custom tab
     */
    function custom_product_data_panel(){
        global $wpdb,$post,$pagenow;
        //Table Name
        $productColorTable          = $wpdb->prefix."product_colors";
        $imprint_typesTable         = $wpdb->prefix."imprint_types";
        $imprint_locationsTable     = $wpdb->prefix."imprint_locations";
        $imprint_colorsTable        = $wpdb->prefix."imprint_colors";
        $imprintTypes               = $wpdb->get_results("SELECT * FROM {$imprint_typesTable}");
        $productColor               = $wpdb->get_results("SELECT * FROM {$productColorTable}");
        $imprintLocations           = $wpdb->get_results("SELECT * FROM {$imprint_locationsTable}");
        $imprintColors             = $wpdb->get_results("SELECT * FROM {$imprint_colorsTable}");

        //Get Data Value
        $data = array(
            //'imprintTypes' => $imprintTypes,
            //'productColor'=> $productColor,
            //'imprintLocations' => $imprintLocations,
            //'imprintColors' => $imprintColors,
            '_izw_enable' => '',
            '_izw_item' => '',
            '_izw_sku' => '',
            '_izw_supplier' => '',
            '_izw_supplier_skus' => '',
            '_izw_supplier_des' => '',
            '_izw_product_colors' => '',
            '_izw_imprint_types' => '',
            '_izw_imprint_type_dnmprc' => '',
            '_izw_imprint_locations' => '',
            '_izw_onsale' => '',
            '_izw_sale_from' => '',
            '_izw_sale_to' => '',
            '_izw_override_shipping' => '',
            '_izw_min' => '',
            '_izw_max' => '',
            '_izw_msrp' => '',
            '_izw_price' => '',
            '_izw_sale' => '',
            '_izw_shipping' => '',
            '_izw_imprint_type_default' => '',
            '_izw_imprint_colors' => '',
        );
        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = unserialize( get_post_meta( $post->ID, $key, true ) );
            }
            if( !is_array($data[$key]) && empty($data[$key]) &&
                (
                    $key == '_izw_msrp' ||
                    $key == '_izw_price' ||
                    $key == '_izw_sale'
                )
            ){
                $data[$key] = array();
                if(!empty($imprintTypes))
                    for($i=0; $i<=4; $i++){
                        foreach($imprintTypes as $rows){
                            $data[$key][$i][$rows->ID] = '2.00';
                        }
                    }
            }elseif(!is_array($data[$key]) && empty($data[$key]) && $key == '_izw_shipping'){
                $data[$key] = array();
                for($i=0; $i<=4; $i++){
                    $data[$key][$i] = '2.00';
                }
            }elseif(!is_array($data[$key]) && empty($data[$key]) && $key == '_izw_max'){
                $data[$key] = array();
                for($i=0; $i<=4; $i++){
                    $data[$key][$i] = 48 * ($i+2);
                }
            }
        }
        if($pagenow == "post-new.php"){
            $max = $wpdb->get_var("SELECT MAX(`meta_value`) FROM {$wpdb->postmeta} WHERE `meta_key` = '_izw_item'");
            $data['_izw_item'] = (int)$max+1;
        }
        $data = apply_filters( 'izw_promolizers_custom_tabs_field_args', $data, $post->ID);
        ?>
        <div id="custom_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
        <p class="form-field">
            <label for="_izw_enable"><?php _e( "Show this on front-end ", __TEXTDOMAIN__ ); ?>:</label>
            <input type="checkbox" style="width:auto;" name="_izw_enable" id="_izw_enable" value="1" <?php checked( @$data['_izw_enable'],1); ?>>
        </p>
        <p class="form-field">
            <label for="_izw_item"><?php _e( "Item ", __TEXTDOMAIN__ ); ?>#:</label>
            <input type="text" class="short" name="_izw_item" id="_izw_item" value="<?php echo !empty($data['_izw_item']) ? $data['_izw_item'] : '1001'; ?>" placeholder="">
        </p>
        <p class="form-field">
            <label for="_izw_sku"><?php _e( "Sku ", __TEXTDOMAIN__ ); ?>#:</label>
            <input type="text" class="short" name="_izw_supplier" id="_izw_supplier" value="<?php echo !empty($data['_izw_supplier']) ? $data['_izw_supplier'] : 'LDS'; ?>" placeholder="" maxlength="3"> -
            <input type="text" class="short" name="_izw_sku" id="_izw_sku" value="<?php echo !empty($data['_izw_sku']) ? $data['_izw_sku'] : 'EBU167834S'; ?>" placeholder="">
        </p>
        <p class="form-field">
            <label for="_izw_supplier_skus"><?php _e( "Supplier SKUs ", __TEXTDOMAIN__ ); ?>:</label>
            <textarea name="_izw_supplier_skus" id="_izw_supplier_skus"><?php echo !empty($data['_izw_supplier_skus']) ? $data['_izw_supplier_skus'] : ''; ?></textarea>
        </p>
        <p class="form-field">
            <label for="_izw_supplier_des"><?php _e( "Supplier Description ", __TEXTDOMAIN__ ); ?>:</label>
            <textarea name="_izw_supplier_des" id="_izw_supplier_des"><?php echo !empty($data['_izw_supplier_des']) ? $data['_izw_supplier_des'] : ''; ?></textarea>
        </p>
        <p class="form-field">
            <label for="_izw_product_colors"><?php _e( "Available product colors ", __TEXTDOMAIN__ ); ?>:</label>
            <select name="_izw_product_colors[]" id="_izw_product_colors" multiple>
                <?php
                foreach($productColor as $color){
                    $selected = '';
                    if( is_array( $data['_izw_product_colors'] ) && in_array( $color->ID, $data['_izw_product_colors'] ) ) $selected = ' selected="selected" ';
                    ?>
                    <option value="<?php echo $color->ID; ?>"<?php echo $selected; ?>><?php echo $color->color_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p class="form-field">
            <label for="_izw_imprint_types"><?php _e( "Available imprint types ", __TEXTDOMAIN__ ); ?>:</label>
            <select name="_izw_imprint_types[]" id="_izw_imprint_types" multiple>
                <?php
                foreach($imprintTypes as $type){
                    $selected = '';
                    if( is_array( $data['_izw_imprint_types'] ) && in_array( $type->ID, $data['_izw_imprint_types'] ) ) $selected = ' selected="selected" ';
                    ?>
                    <option value="<?php echo $type->ID; ?>"<?php echo $selected; ?>><?php echo $type->type_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p class="form-field">
            <label for="_izw_imprint_locations"><?php _e( "Available imprint locations ", __TEXTDOMAIN__ ); ?>:</label>
            <select name="_izw_imprint_locations[]" id="_izw_imprint_locations" multiple>
                <?php
                foreach($imprintLocations as $location){
                    $selected = '';
                    if( is_array( $data['_izw_imprint_locations'] ) && in_array( $location->ID, $data['_izw_imprint_locations'] ) ) $selected = ' selected="selected" ';
                    ?>
                    <option value="<?php echo $location->ID; ?>"<?php echo $selected; ?>><?php echo $location->location_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <p class="form-field">
            <label for="_izw_imprint_colors"><?php _e( "Available imprint colors ", __TEXTDOMAIN__ ); ?>:</label>
            <select name="_izw_imprint_colors[]" id="_izw_imprint_colors" multiple>
                <?php
                foreach($imprintColors as $color){
                    $selected = '';
                    if( is_array( $data['_izw_imprint_colors'] ) && in_array( $color->ID, $data['_izw_imprint_colors'] ) ) $selected = ' selected="selected" ';
                    ?>
                    <option value="<?php echo $color->ID; ?>"<?php echo $selected; ?>><?php echo $color->color_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <div class="izw_checkbox">
            <p>
                <input type="checkbox" name="_izw_onsale" id="_izw_onsale" <?php checked(1,$data['_izw_onsale']); ?> value="1" />
                <label for="_izw_onsale"><?php _e( "On sale", __TEXTDOMAIN__ ); ?></label>
                <input name="_izw_sale_from" readonly id="_izw_sale_from" value="<?php echo !empty($data['_izw_sale_from']) ? $data['_izw_sale_from'] : ''; ?>" placeholder="From... YYYY-MM-DD" />
                <img class="_izw_date_from" style="margin-right: 20px;" src="<?php echo __PLUGINURL__."assets/admin/images/calendar.png"; ?>" width="16">
                <input name="_izw_sale_to" readonly id="_izw_sale_to" value="<?php echo !empty($data['_izw_sale_to']) ? $data['_izw_sale_to'] : ''; ?>" placeholder="To... YYYY-MM-DD" />
                <img class="_izw_date_to" src="<?php echo __PLUGINURL__."assets/admin/images/calendar.png"; ?>" width="16">
            </p>
            <p>
                <input type="checkbox" name="_izw_override_shipping" <?php checked(1,$data['_izw_override_shipping']); ?> id="_izw_override_shipping" value="1" />
                <label for="_izw_override_shipping"><?php _e( "Override global shipping settings", __TEXTDOMAIN__ ); ?></label>
            </p>
        </div>
        <h3><?php _e( "Dynamic Pricing", __TEXTDOMAIN__ ); ?></h3>
        <p class="form-field" style="float: left;">
            <label for="_izw_imprint_type_dnmprc"><?php _e( "Imprint type: ") ?></label>
            <select name="_izw_imprint_type_dnmprc" id="_izw_imprint_type_dnmprc">
                <?php
                foreach($imprintTypes as $type){
                    if(!in_array($type->ID,$data['_izw_imprint_types'])) continue;
                    ?>
                    <option value="<?php echo $type->ID; ?>"<?php selected($type->ID,$data['_izw_imprint_type_dnmprc']) ; ?>><?php echo $type->type_name; ?></option>
                <?php
                }
                ?>
            </select>
        </p>
        <div class="clear"></div>
        <div class="dynamic-pricing-table">
            <table id="dynamic-pricing" class="dynamic-pricing" width="100%" cellspacing="0" cellpadding="0">
                <thead>
                <tr class="column_name">
                    <?php for($i=1;$i<=count($data['_izw_max']); $i++): ?>
                        <td><?php _e( "Column", __TEXTDOMAIN__ ); echo " ", $i; ?></td>
                    <?php endfor; ?>
                    <td>
                        <?php if(count($data['_izw_max'])<=5) $style='style="display: none;cursor: pointer;"'; else $style = 'cursor: pointer;'; ?>
                        <img class="removeColumn" id="removeColumn" src="<?php echo __PLUGINURL__."assets/admin/images/admin-circle-minus-16.png"; ?>" title="<?php _e( "Remove Column", __TEXTDOMAIN__) ; ?>">
                        <img class="addColumn" id="addColumn" src="<?php echo __PLUGINURL__."assets/admin/images/admin-circle-plus-16.png"; ?>" title="<?php _e( "Add Column", __TEXTDOMAIN__) ; ?>">
                    </td>
                </tr>
                <tr class="quantity">
                    <?php $i=0; $min = !empty($data['_izw_min']) ? min($data['_izw_min']) : 48; foreach($data['_izw_max'] as $key=>$values): ?>
                        <td>
                            <?php if($i == 0): ?>
                                <?php _e( "Min Qty", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_min[<?php echo $i; ?>]" value="<?php echo $min; ?>"/>
                            <?php endif; ?>
                            <?php _e( "Max Qty", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_max[<?php echo $key; ?>]" value="<?php echo $values ? $values : (($min-1) * ($i+2)); ?>"/>
                        </td>
                        <?php $i++; endforeach; ?>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                <?php do_action( 'promolizer_before_custom_field' ); ?>
                <?php $i=1; if(!empty($imprintTypes)) foreach($imprintTypes as $type):
                    if(!is_array($data['_izw_imprint_types'])) break;
                    if(!in_array($type->ID,$data['_izw_imprint_types'])) continue;
                    $class = 'hide';
                    if( empty( $data['_izw_imprint_type_dnmprc'] ) && $i == 1 ) $class = 'active';
                    if( !empty( $data['_izw_imprint_type_dnmprc'] ) && $data['_izw_imprint_type_dnmprc'] == $type->ID ) $class = 'active';
                    ?>
                    <tr class="msrp ip_<?php echo $type->ID, " {$class}"; ?>" data-imprintID="<?php echo $type->ID; ?>">
                        <?php foreach($data['_izw_msrp'] as $key=>$values):?>
                            <td>
                                <?php _e( "MSRP", __TEXTDOMAIN__); ?><input type="text" name="_izw_msrp[<?php echo $key; ?>][<?php echo $type->ID; ?>]" value="<?php echo $values[$type->ID] ? $values[$type->ID] : '2.00'; ?>"/>
                            </td>
                        <?php endforeach;?>
                        <td></td>
                    </tr>
                    <tr class="price ip_<?php echo $type->ID, " {$class}"; ?>" data-imprintID="<?php echo $type->ID; ?>">
                        <?php foreach($data['_izw_price'] as $key=>$values):?>
                            <td>
                                <?php _e( "Price", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_price[<?php echo $key; ?>][<?php echo $type->ID; ?>]" value="<?php echo $values[$type->ID] ? $values[$type->ID] : '2.00'; ?>"/>
                            </td>
                        <?php endforeach;?>
                        <td></td>
                    </tr>
                    <tr class="sale ip_<?php echo $type->ID, $class == 'active' ? " ".$class." " : ' ', "hide"; ?>" data-imprintID="<?php echo $type->ID; ?>">
                        <?php foreach($data['_izw_sale'] as $key=>$values):?>
                            <td>
                                <?php _e( "Sale", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_sale[<?php echo $key; ?>][<?php echo $type->ID; ?>]" value="<?php echo $values[$type->ID] ? $values[$type->ID] : '2.00'; ?>"/>
                            </td>
                        <?php endforeach;?>
                        <td></td>
                    </tr>
                    <?php $i++; endforeach; ?>
                <?php do_action( 'promolizer_after_custom_field' ); ?>
                </tbody>
                <tfoot>
                <tr class="shipping hide">
                    <?php $i=0; foreach($data['_izw_shipping'] as $rows): ?>
                        <td>
                            <?php _e( "Shipping", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_shipping[<?php echo $i; ?>]" value="<?php echo $rows ? $rows : '2.00'; ?>"/>
                        </td>
                        <?php $i++; endforeach; ?>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <input type="hidden" name="countColumn" id="countColumn" class="countColumn" value="<?php echo $i; ?>"/>
        <div class="flright">
            <?php submit_button( 'Save Pricing', 'primary', 'izw_save_pricing' ) ?>
        </div>
        <div class="clear"></div>
        <hr>
        <p class="form-field">
            <label for="_izw_imprint_type_default">Default imprint type: </label>
            <select name="_izw_imprint_type_default" id="_izw_imprint_type_default">
                <?php
                foreach($imprintTypes as $type){
                    if(!in_array($type->ID,$data['_izw_imprint_types'])) continue;
                    ?>
                    <option value="<?php echo $type->ID; ?>"<?php selected($data['_izw_imprint_type_default'],$type->ID) ?>><?php echo $type->type_name; ?></option>
                <?php
                }
                ?>
            </select>
                    <span class="flleft">
                        <input type="submit" name="izw_update" id="izw_update" class="button button-primary" value="Update">
                    </span>
        </p>
        <div class="clear"></div>
        </div>
        </div>
        <style type="text/css">
            .woocommerce_options_panel input[type=text]{
                float: none;
            }
            .woocommerce_options_panel label, .woocommerce_options_panel legend{
                width: 160px;
            }
            .woocommerce_options_panel div.izw_checkbox label {
                width: auto;
                margin: 0;
                float: none;
                margin-right: 100px;
            }
            .woocommerce_options_panel div.izw_checkbox{
                padding: 5px 20px 5px 0 !important;
            }
            img._izw_date_from,img._izw_date_to{
                cursor: pointer;
            }
            .woocommerce_options_panel table tr td input[type=text] {
                width: 40px;
            }
            .dynamic-pricing{
                width: 100%;
            }
            .dynamic-pricing td{
                border-top: 1px solid #000000;
                border-right: 1px solid #000000;
                padding: 2px;
            }
            .dynamic-pricing th{
                border-right: 1px solid #000000;
            }
            .dynamic-pricing td:last-child,.dynamic-pricing th:last-child{
                border-right: none;
            }
            .dynamic-pricing .price td,.dynamic-pricing .sale td{
                border-top: none;
            }
            .dynamic-pricing tr.hide {
                display: none;
            }
            .woocommerce_options_panel .chosen-container-multi {
                width: 98% !important;
                float: left;
            }
        </style>
    <?php
    }

    /**
     * Save data
     *
     * @param $post_id
     * @param $post
     */
    function save( $post_id, $post ){
        //Custom Box Save Data
        $data = array(
            '_izw_enable'               => !empty( $_POST['_izw_enable'] ) ? $_POST['_izw_enable'] : '0',
            '_izw_item'                 => !empty( $_POST['_izw_item'] ) ? $_POST['_izw_item'] : '',
            '_izw_sku'                  => !empty( $_POST['_izw_sku'] ) ? $_POST['_izw_sku'] : '',
            '_izw_supplier'             => !empty( $_POST['_izw_supplier'] ) ? $_POST['_izw_supplier'] : '',
            '_izw_supplier_skus'        => !empty( $_POST['_izw_supplier_skus'] ) ? $_POST['_izw_supplier_skus'] : '',
            '_izw_supplier_des'         => !empty( $_POST['_izw_supplier_des'] ) ? $_POST['_izw_supplier_des'] : '',
            '_izw_product_colors'       => !empty( $_POST['_izw_product_colors'] ) ? $_POST['_izw_product_colors'] : '',
            '_izw_imprint_types'        => !empty( $_POST['_izw_imprint_types'] ) ? $_POST['_izw_imprint_types'] : '',
            '_izw_imprint_locations'    => !empty( $_POST['_izw_imprint_locations'] ) ? $_POST['_izw_imprint_locations'] : '',
            '_izw_imprint_colors'       => !empty( $_POST['_izw_imprint_colors'] ) ? $_POST['_izw_imprint_colors'] : '',
            '_izw_onsale'               => !empty( $_POST['_izw_onsale'] ) ? $_POST['_izw_onsale'] : '',
            '_izw_sale_from'            => !empty( $_POST['_izw_sale_from'] ) ? $_POST['_izw_sale_from'] : '',
            '_izw_sale_to'              => !empty( $_POST['_izw_sale_to'] ) ? $_POST['_izw_sale_to'] : '',
            '_izw_override_shipping'    => !empty( $_POST['_izw_override_shipping'] ) ? $_POST['_izw_override_shipping'] : '',
            '_izw_imprint_type_dnmprc'  => !empty( $_POST['_izw_imprint_type_dnmprc'] ) ? $_POST['_izw_imprint_type_dnmprc'] : '',
            '_izw_min'                  => !empty( $_POST['_izw_min'] ) ? $_POST['_izw_min'] : '',
            '_izw_max'                  => !empty( $_POST['_izw_max'] ) ? $_POST['_izw_max'] : '',
            '_izw_msrp'                 => !empty( $_POST['_izw_msrp'] ) ? $_POST['_izw_msrp'] : '',
            '_izw_price'                => !empty( $_POST['_izw_price'] ) ? $_POST['_izw_price'] : '',
            '_izw_sale'                 => !empty( $_POST['_izw_sale'] ) ? $_POST['_izw_sale'] : '',
            '_izw_shipping'             => !empty( $_POST['_izw_shipping'] ) ? $_POST['_izw_shipping'] : '',
            '_izw_imprint_type_default' => !empty( $_POST['_izw_imprint_type_default'] ) ? $_POST['_izw_imprint_type_default'] : '',
        );
        $countColumn = count($data['_izw_max']);
        for($i=0; $i<$countColumn; $i++){
            if($i > 0){
                $data['_izw_min'][$i] = $data['_izw_max'][$i-1] + 1;
            }
        }
        $data = apply_filters( 'izw_save_custom_product_data',$data);
        update_post_meta( $post_id, '_sku', $data['_izw_sku'] );
        if(is_array($data['_izw_price'])){
            update_post_meta( $post_id, '_regular_price', min($data['_izw_price'][0]) );
        };
        if( !empty($data['_izw_onsale']) && $data['_izw_onsale'] && is_array($data['_izw_sale'])){
            update_post_meta( $post_id, '_sale_price', min($data['_izw_sale'][0]) );
        };
        if( !empty($data['_izw_onsale']) && $data['_izw_onsale'] && !empty($data['_izw_sale_from'])){
            update_post_meta( $post_id, '_sale_price_dates_from', $data['_izw_sale_from'] );
        };
        if( !empty($data['_izw_onsale']) && $data['_izw_onsale'] && !empty($data['_izw_sale_to'])){
            update_post_meta( $post_id, '_sale_price_dates_to', $data['_izw_sale_to'] );
        };
        foreach ($data as $key=>$value){
            $hasdata = get_post_meta( $post_id, $key, true );
            do_action( 'promolizer_save_custom_product_data', $key, $value, $post_id );
            if( !empty( $value ) && !empty( $hasdata ) ){
                update_post_meta( $post_id, $key, serialize( $value ) );
            }elseif( !empty( $value ) ){
                add_post_meta( $post_id, $key, serialize( $value ) );
            }elseif( !empty($hasdata) && empty( $value ) ){
                delete_post_meta( $post_id, $key );
            }
        }
    }

    /**
     * Update imprint type when change value available
     */
    function update_imprint_type(){
        global $wpdb;
        $imprint_typesTable         = $wpdb->prefix."imprint_types";
        if(isset($_POST['IPID']) && !empty($_POST['IPID'])){
            $IPID = implode(",",$_POST['IPID']);
            $imprint_types = $wpdb->get_results("SELECT * FROM {$imprint_typesTable} WHERE `ID` IN ({$IPID})");
            foreach($imprint_types as $rows){
                ?>
                <option value="<?php echo $rows->ID; ?>"><?php echo $rows->type_name; ?></option>
            <?php
            }
        }
        die();
    }

    /**
     * Update dynamic pricing when change available imprint type
     */
    function update_imprint_type_table(){
        global $wpdb;
        if(isset($_POST['IPID']) && !empty($_POST['IPID'])){
            $IPID = implode(",",$_POST['IPID']);
            $imprint_typesTable         = $wpdb->prefix."imprint_types";
            $imprint_types = $wpdb->get_results("SELECT * FROM {$imprint_typesTable} WHERE `ID` IN ({$IPID})");
            $product_id = $_POST['PID'];


            //Get Data Value
            $data = array(
                'imprintTypes' => $imprint_types,
                '_izw_min' => '',
                '_izw_max' => '',
                '_izw_msrp' => '',
                '_izw_price' => '',
                '_izw_sale' => '',
                '_izw_shipping' => '',
            );
            foreach($data as $key=>$values){
                if(empty($values)){
                    $data[$key] = unserialize( get_post_meta( $product_id, $key, true ) );
                }
                if( !is_array($data[$key]) && empty($data[$key]) &&
                    (
                        $key == '_izw_msrp' ||
                        $key == '_izw_price' ||
                        $key == '_izw_sale'
                    )
                ){
                    $data[$key] = array();
                    for($i=0; $i<=4; $i++){
                        foreach($imprintTypes as $rows){
                            $data[$key][$i][$rows->ID] = '2.00';
                        }
                    }
                }elseif(!is_array($data[$key]) && empty($data[$key]) && $key == '_izw_shipping'){
                    $data[$key] = array();
                    for($i=0; $i<=4; $i++){
                        $data[$key][$i] = '2.00';
                    }
                }elseif(!is_array($data[$key]) && empty($data[$key]) && $key == '_izw_max'){
                    $data[$key] = array();
                    for($i=0; $i<=4; $i++){
                        $data[$key][$i] = 48 * ($i+2);
                    }
                }
            }
            $data = apply_filters( 'izw_promolizers_custom_tabs_field_ajax_args', $data, $product_id);
            $showsale = (isset($_POST['_izw_onsale']) && $_POST['_izw_onsale']) ? 1 : 0;
            ?>

            <thead>
            <tr class="column_name">
                <?php for($i=1;$i<=count($data['_izw_max']); $i++): ?>
                    <td><?php _e( "Column", __TEXTDOMAIN__ ); echo " ", $i; ?></td>
                <?php endfor; ?>
                <td>
                    <?php if(count($data['_izw_max'])<=5) $style='style="display: none;"'; else $style = ''; ?>
                    <img class="removeColumn" id="removeColumn" src="<?php echo __PLUGINURL__."assets/admin/images/admin-circle-minus-16.png"; ?>" title="<?php _e( "Remove Column", __TEXTDOMAIN__) ; ?>">
                    <img class="addColumn" id="addColumn" src="<?php echo __PLUGINURL__."assets/admin/images/admin-circle-plus-16.png"; ?>" title="<?php _e( "Add Column", __TEXTDOMAIN__) ; ?>">
                </td>
            </tr>
            <tr class="quantity">
                <?php $i=0; $min = !empty($data['_izw_min']) ? min($data['_izw_min']) : 48; foreach($data['_izw_max'] as $key=>$values): ?>
                    <td>
                        <?php if($i == 0): ?>
                            <?php _e( "Min Qty", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_min[<?php echo $i; ?>]" value="<?php echo $min; ?>"/>
                        <?php endif; ?>
                        <?php _e( "Max Qty", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_max[<?php echo $key; ?>]" value="<?php echo $values ? $values : (($min-1) * ($i+2)); ?>"/>
                    </td>
                    <?php $i++; endforeach; ?>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach($imprintTypes as $type):
                $class = 'hide';
                if( empty( $data['_izw_imprint_type_dnmprc'] ) && $i == 1 ) $class = 'active';
                ?>
                <tr class="msrp ip_<?php echo $type->ID, " {$class}"; ?>" data-imprintID="<?php echo $type->ID; ?>">
                    <?php foreach($data['_izw_msrp'] as $key=>$values):?>
                        <td>
                            <?php _e( "MSRP", __TEXTDOMAIN__); ?><input type="text" name="_izw_msrp[<?php echo $key; ?>][<?php echo $type->ID; ?>]" value="<?php echo !empty($values[$type->ID]) ? $values[$type->ID] : '2.00'; ?>"/>
                        </td>
                    <?php endforeach;?>
                    <td></td>
                </tr>
                <tr class="price ip_<?php echo $type->ID, " {$class}"; ?>" data-imprintID="<?php echo $type->ID; ?>">
                    <?php foreach($data['_izw_price'] as $key=>$values):?>
                        <td>
                            <?php _e( "Price", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_price[<?php echo $key; ?>][<?php echo $type->ID; ?>]" value="<?php echo !empty($values[$type->ID]) ? $values[$type->ID] : '2.00'; ?>"/>
                        </td>
                    <?php endforeach;?>
                    <td></td>
                </tr>
                <tr class="sale ip_<?php echo $type->ID, $class == 'active' ? " ".$class." " : ' ', "hide"; ?>" data-imprintID="<?php echo $type->ID; ?>">
                    <?php foreach($data['_izw_sale'] as $key=>$values):?>
                        <td>
                            <?php _e( "Sale", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_sale[<?php echo $key; ?>][<?php echo $type->ID; ?>]" value="<?php echo !empty($values[$type->ID]) ? $values[$type->ID] : '2.00'; ?>"/>
                        </td>
                    <?php endforeach;?>
                    <td></td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
            <tfoot>
            <tr class="shipping hide">
                <?php $i=0; foreach($data['_izw_shipping'] as $rows): ?>
                    <td>
                        <?php _e( "Shipping", __TEXTDOMAIN__); ?>: <input type="text" name="_izw_shipping[<?php echo $i; ?>]" value="<?php echo $rows ? $rows : '2.00'; ?>"/>
                    </td>
                    <?php $i++; endforeach; ?>
                <td></td>
            </tr>
            </tfoot>
        <?php
        }
        die();
    }
}
new IZW_Custom_Product_Data();