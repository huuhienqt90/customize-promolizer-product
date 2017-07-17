<?php
/**
 * Created by PhpStorm.
 * User: TP_Duc
 * Date: 8/25/14
 * Time: 2:41 PM
 */

class IZW_Display_Single {
    function __construct(){
        add_action( 'woocommerce_single_product_summary', array( $this, 'output' ), 30 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'izw_define_products' ), 25 );
    }

    /**
     * Display template on single page
     */
    function output(){
        global $post;
        $_izw_enable = get_post_meta($post->ID,'_izw_enable',true);
        if(empty($_izw_enable) || !$_izw_enable) return;
        wc_get_template( 'promolizers-single.php', array( ), '', __PROMOPATH__."/front/templates/" );
        require_once(__PROMOPATH__."front/templates/promolizers-single-js.php");
    }

    /**
     * Defined variable for single template display
     */
    function izw_define_products(){
        global $wpdb,$post,$izwpsp,$product;
        $izwpsp = array();
        $product_id = $post->ID;
        $izw_size = get_option('izw_cart_size');
        $izw_imprint_locations = get_option('izw_cart_imprint_location');
        $izw_size = is_array($izw_size) ? $izw_size : array();
        $izw_imprint_locations = is_array($izw_imprint_locations) ? $izw_imprint_locations : array();
        $product_cat = wp_get_post_terms( $product_id, 'product_cat' );

        $checkSize = false;
        $checkLocation = false;
        foreach($product_cat as $rows){
            if(in_array($rows->term_id,$izw_size)) {$checkSize = true;}
            if(in_array($rows->term_id,$izw_imprint_locations)) {$checkLocation = true;}
            if($checkSize && $checkLocation) break;
        }

        $izwpsp['check_size'] = $checkSize;
        $izwpsp['check_location'] = $checkLocation;
        //Table Name
        $productColorTable          = $wpdb->prefix."product_colors";
        $imprint_typesTable         = $wpdb->prefix."imprint_types";
        $imprint_locationsTable     = $wpdb->prefix."imprint_locations";
        $imprint_colorsTable        = $wpdb->prefix."imprint_colors";
        $DTproduct_colors =  get_post_meta( $product_id, '_izw_product_colors',true);
        if(!empty($DTproduct_colors)){
            $DTproduct_colors           = implode(",", unserialize( get_post_meta( $product_id, '_izw_product_colors',true) ) );
            $izwpsp['product_colors'] = $wpdb->get_results("SELECT * FROM {$productColorTable} WHERE `ID` IN ({$DTproduct_colors})");

        }
        $DTimprint_types = get_post_meta( $product_id, '_izw_imprint_types', true );
        if(!empty($DTimprint_types)){
            $DTimprint_types           = implode(",", unserialize( get_post_meta( $product_id, '_izw_imprint_types', true ) ) );
            $izwpsp['imprint_types'] = $wpdb->get_results("SELECT * FROM {$imprint_typesTable} WHERE `ID` IN ({$DTimprint_types})");
        }
        $DTimprint_locations = get_post_meta( $product_id, '_izw_imprint_locations', true );
        if(!empty($DTimprint_locations)){
            $DTimprint_locations           = implode(",", unserialize( get_post_meta( $product_id, '_izw_imprint_locations', true ) ) );
            $izwpsp['imprint_locations'] = $wpdb->get_results("SELECT * FROM {$imprint_locationsTable} WHERE `ID` IN ({$DTimprint_locations})");
        }
        $DTimprint_colors = get_post_meta( $product_id, '_izw_imprint_colors', true );
        if(!empty($DTimprint_colors)){
            $DTimprint_colors           = implode(",", unserialize( get_post_meta( $product_id, '_izw_imprint_colors', true ) ) );
            $izwpsp['imprint_colors'] = $wpdb->get_results("SELECT * FROM {$imprint_colorsTable} WHERE `ID` IN ({$DTimprint_colors}) ");
        }
        //Get Data Value
        $DT_izw_onsale = get_post_meta( $product_id, '_izw_onsale', true );
        if(!empty($DT_izw_onsale)){

            $izwpsp['DT_izw_onsale']              = unserialize( get_post_meta( $product_id, '_izw_onsale', true ) );
        }
        $DT_izw_sale_from = get_post_meta( $product_id, '_izw_sale_from', true);
        if(!empty($DT_izw_sale_from)){

            $izwpsp['DT_izw_sale_from']               = unserialize( get_post_meta( $product_id, '_izw_sale_from', true ) );
        }
        $DT_izw_sale_to =  get_post_meta( $product_id, '_izw_sale_to', true );
        if(!empty($DT_izw_sale_to)){

            $izwpsp['DT_izw_sale_to']              = unserialize( get_post_meta( $product_id, '_izw_sale_to', true ) );
        }
        $DT_izw_override_shipping = get_post_meta( $product_id, '_izw_override_shipping', true );
        if(!empty($DT_izw_override_shipping)){
            $izwpsp['DT_izw_override_shipping']               = unserialize( get_post_meta( $product_id, '_izw_override_shipping', true ) );
        }
        $DT_izw_min = get_post_meta( $product_id, '_izw_min', true );
        if(!empty($DT_izw_min)){
            $izwpsp['DT_izw_min']               = unserialize( get_post_meta( $product_id, '_izw_min', true ) );
        }
        $DT_izw_max = get_post_meta( $product_id, '_izw_max', true );
        if(!empty($DT_izw_max)){
            $izwpsp['DT_izw_max']              = unserialize( get_post_meta( $product_id, '_izw_max', true ) );
        }
        $DT_izw_msrp =  get_post_meta( $product_id, '_izw_msrp', true );
        if(!empty($DT_izw_msrp)){
            $izwpsp['DT_izw_msrp']               = unserialize( get_post_meta( $product_id, '_izw_msrp', true ) );

        }
        $DTimprint_types_dnmprc =  get_post_meta( $product_id, '_izw_imprint_type_dnmprc', true ) ;
        if(!empty($DTimprint_types_dnmprc)){
            $izwpsp['DTimprint_types_dnmprc']         = unserialize( get_post_meta( $product_id, '_izw_imprint_type_dnmprc', true ) );
        }
        $DT_izw_price               = get_post_meta( $product_id, '_izw_price', true ) ;
        if(!empty($DT_izw_price)){
            $izwpsp['DT_izw_price']               = unserialize( get_post_meta( $product_id, '_izw_price', true ) );
        }
        $DT_izw_sale               =  get_post_meta( $product_id, '_izw_sale', true ) ;
        if(!empty($DT_izw_sale)){
            $izwpsp['DT_izw_sale']             = unserialize( get_post_meta( $product_id, '_izw_sale', true ) );
        }

        $DT_izw_shipping               = get_post_meta( $product_id, '_izw_shipping', true ) ;
        if(!empty($DT_izw_shipping)){
            $izwpsp['DT_izw_shipping']               = unserialize( get_post_meta( $product_id, '_izw_shipping', true ) );
        }

        $izwpsp = apply_filters( 'izw_promolizers_single_product',$izwpsp,$post);
    }

}
new IZW_Display_Single();