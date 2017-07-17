<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 9/5/14
 * Time: 3:49 PM
 */
namespace Promolizers\Front\Promolizers_Cart_Data;

include_once (__PROMOPATH__."/includes/aws/aws-autoloader.php");
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Promolizers_Cart_Data {
    function __construct(){
        // Add to cart
        add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 50, 1 );

        // Add item data to the cart
        add_filter('woocommerce_add_cart_item_data', array( $this, 'woocommerce_add_cart_item_data' ),20,2);

        // Get item data to display
        add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 20, 2 );

        // Load cart data per page load
        add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 30, 2 );

        // Add meta to order
        add_action( 'woocommerce_add_order_item_meta', array( $this, 'order_item_meta' ), 20, 2 );

        // order again functionality
        add_filter( 'woocommerce_order_again_cart_item_data', array( $this, 're_add_cart_item_data' ), 20, 3 );

        //add_filter('woocommerce_get_price', array( $this, 'return_custom_price' ), 999, 2);

        add_action('init', array( $this, 'start_session' ), 1);

        add_action( 'woocommerce_thankyou', array( $this, 'upload_file_to_amazon'), 10, 1 );

        add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 10, 3 );

        add_filter( 'woocommerce_cart_shipping_packages', array( $this, 'change_shipping_cost' ), 10, 1);

        add_action( 'woocommerce_before_calculate_totals', array( $this, 'woo_add_cart_fee' ) );
    }

    /**
     * Register Session Not Exists
     */
    function start_session(){
        if(!session_id()) {
            session_start();
        }
    }

    /**
     * Upload File To Amazon S3
     * @param $orderid
     */
    function upload_file_to_amazon($orderid){
        if(isset($_SESSION['DesignData'])){
            if( count( $_SESSION['DesignData'] ) > 0 ){
                try {
                    // Create an Amazon S3 client object
                    $amazonkey = get_option('_izw_amazon_s3_key');
                    $amazonsecret = get_option('_izw_amazon_s3_secret');
                    $amazonbuket = get_option('_izw_amazon_s3_bucket');
                    $client = S3Client::factory(array(
                        'key'    => !empty($amazonkey) ? $amazonkey : '',
                        'secret' => !empty($amazonsecret) ? $amazonsecret : ''
                    ));
                    /**
                     * Folder Name and bucket name
                     */
                    $orderFolder = 'order' . $orderid;
                    $bucketName = !empty($amazonbuket) ? $amazonbuket : 'promolizers';

                    /**
                     * Get all file selected
                     */
                    foreach($_SESSION['DesignData'] as $data){

                        $args = array('Bucket' => $bucketName,
                            'Key' => 'customer-uploads/' . $orderFolder . '/' . basename($data['name']),
                            'Body' => fopen($data['tmp_name'], 'r'));
                        /**
                         * Upload File to amazon s3 server
                         */
                        $result = $client->putObject($args);
                    }
                    /**
                     * Remove session
                     */
                    unset($_SESSION['DesignData']);
                } catch (S3Exception $e) {
                    /**
                     * Display error if can't upload file
                     */
                    echo "There was an error uploading the file.\n";
                }
            }
        }
    }
    /**
     * Add item data to cart
     *
     * @access public
     * @param mixed $cart_item_meta
     * @param mixed $product_id
     * @return $cart_item_meta
     */
    function return_custom_price( $price, $product ) {
        global $post, $woocommerce;
        return $price;
    }
    public function woocommerce_add_cart_item_data($cart_item_meta, $product_id){
        global $wpdb;
        //Table Name
        $imprint_typesTable         = $wpdb->prefix."imprint_types";
        $imprint_locationsTable     = $wpdb->prefix."imprint_locations";
        $imprint_colorsTable     = $wpdb->prefix."imprint_colors";

        //Add promolizers plugin variable
        if ( empty( $cart_item_meta['promolizers_plugin'] ) )
            $cart_item_meta['promolizers_plugin'] = array();


        //Set variable default
        $data = array(
            'imprint_type' => array(),
            'imprint_location' => array(),
            'imprint_color' => array(),
            'imprint_size' => array(),
            'custom_text' => array(),
        );
        if( isset( $_POST['imprintType'] ) && !empty( $_POST['imprintType'] ) ){
            $imprintType = $_POST['imprintType'];
            $imprintTypeName = $wpdb->get_var("SELECT `type_name` FROM {$imprint_typesTable} WHERE `type_slug` = '{$imprintType}'");
            $data['imprint_type'] = array(
                'name' => 'Imprint Types',
                'value' => $imprintTypeName,
                'price' => 0,
            );
        }
        if( isset( $_POST['imprintLocation'] ) && !empty( $_POST['imprintLocation'] ) ){
            $imprintLocation = '';
            $count = count($_POST['imprintLocation']);
            $i=1;
            foreach($_POST['imprintLocation'] as $rows){
                if($i == $count){
                    $imprintLocation .= "'{$rows}'";
                }else{
                    $imprintLocation .= "'{$rows}',";
                }
                $i++;
            }
            $imprintLocationName = $wpdb->get_col("SELECT `location_name` FROM {$imprint_locationsTable} WHERE `location_slug` IN ({$imprintLocation})");
            $data['imprint_location'] = array(
                'name' => 'Imprint Locations',
                'value' => implode(", ",$imprintLocationName),
                'price' => 0,
            );
        }
        if( isset($_POST['color'] ) && !empty( $_POST['color'] ) ){
            $imprint_color = '';
            $count = count($_POST['color']);
            $i=1;
            foreach($_POST['color'] as $rows){
                if($i == $count){
                    $imprint_color .= "'{$rows}'";
                }else{
                    $imprint_color .= "'{$rows}',";
                }
                $i++;
            }
            $imprint_colorName = $wpdb->get_col("SELECT `color_name` FROM {$imprint_colorsTable} WHERE `color_slug` IN ({$imprint_color})");
            $data['imprint_color'] = array(
                'name' => 'Imprint Colors',
                'value' => implode(", ",$imprint_colorName),
                'price' => 0,
            );
        }
        if( isset( $_POST['step3size'] ) ){
            $total = 0;
            $size = $_POST['step3size'];
            $sizeString = '';
            if(is_array($size)){
                foreach($size as $colorname=>$colorValue){
                    $sizeString .= $colorname.": ";
                    if(is_array($colorValue)){
                        $i=1;
                        $count = count($colorValue);
                        foreach($colorValue as $size=>$quantity){
                            $total = $total + (int)$quantity;
                            if($i==$count) {
                                $sizeString .= $size."({$quantity})";
                                $sizeString .= "; ";
                            }else{
                                $sizeString .= $size."({$quantity}),";
                            }
                            $i++;
                        }
                    }
                }
            }
            $data['imprint_size'] = array(
                'name' => 'Imprint Size',
                'value' => $sizeString,
                'price' => 0,
            );
        }
        if( isset( $_POST['custom-text'] ) && !empty( $_POST['custom-text'] ) ){
            $data['custom_text'] = array(
                'name' => 'Custom Text',
                'value' => $_POST['custom-text'],
                'price' => 0,
            );
        }
        if( isset( $_POST['additional'] ) && !empty( $_POST['additional'] ) ){
            $data['additional'] = array(
                'name' => 'Additional',
                'value' => $_POST['additional'],
                'price' => 0,
            );
        }

        //Set amazon s3 upload session
        if( isset($_FILES['browse'] ) ){
            $countFiles = count( $_FILES['browse']['name'] );
            $FileName = $_FILES['browse']['name'];
            $_SESSION['DesignData'] = array();
            for( $i=0; $i < $countFiles; $i++ ){
                if( !empty( $FileName[$i] ) && !$_FILES['browse']['error'][$i]){
                    $_SESSION['DesignData'][] = array(
                        'name' => $FileName[$i],
                        'tmp_name' => $_FILES['browse']['tmp_name'][$i],
                        'size' => $_FILES['browse']['size'][$i],
                        'error' => $_FILES['browse']['error'][$i],
                    );
                }
            }
        }

        //Add filter to change
        $data = apply_filters( 'promolizers_add_cart_item_data', $data, $cart_item_meta );

        foreach( $data as $key => $values ){
            if( sizeof( $values ) > 0 ){
                $cart_item_meta['promolizers_plugin'][$key] = array(
                    'name' 		=> $values['name'],
                    'value'		=> $values['value'],
                    'price' 	=> $values['price']
                );
            }
        }

        $quantity = $_POST['quantity'];
        $data = array(
            '_izw_onsale' => '',
            '_izw_sale_from' => '',
            '_izw_sale_to' => '',
            '_izw_override_shipping' => '',
            '_izw_max' => '',
            '_izw_min' => '',
            '_izw_price' => '',
            '_izw_sale' => '',
            '_izw_shipping' => '',
        );
        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = unserialize( get_post_meta( $product_id, $key, true ) );
            }
        }
        for($i=0; $i<count($data['_izw_max']); $i++){
            if($quantity >= $data['_izw_min'][$i] && $quantity <= $data['_izw_max'][$i]) break;
        }

        //Add shipping costs
        if(!empty($data['_izw_override_shipping']) && $data['_izw_override_shipping'] ){
            $cart_item_meta['izw_product_shipping'] = $data['_izw_shipping'][$i];
        }

        //Add imprint_quantity
        $cart_item_meta['izw_qty'] = $quantity;
        $cart_item_meta['izw_imprintID'] = $_POST['imprintType'];
        return $cart_item_meta;
    }

    /**
     * Add to cart
     *
     * @access public
     * @param mixed $cart_item
     * @return $cart_item
     */
    public function add_cart_item( $cart_item ) {
        global $wpdb;
        $imprint_typesTable         = $wpdb->prefix."imprint_types";
        if ( ! empty( $cart_item['promolizers_plugin'] ) ) {
            $imprintType = $_POST['imprintType'];
            $data = array(
                '_izw_onsale' => '',
                '_izw_sale_from' => '',
                '_izw_sale_to' => '',
                '_izw_override_shipping' => '',
                '_izw_max' => '',
                '_izw_min' => '',
                '_izw_price' => '',
                '_izw_sale' => '',
                '_izw_shipping' => '',
            );
            foreach($data as $key=>$values){
                if(empty($values)){
                    $data[$key] = unserialize( get_post_meta( $cart_item['product_id'], $key, true ) );
                }
            }
            for($i=0; $i<count($data['_izw_max']); $i++){
                if($cart_item['izw_qty'] >= $data['_izw_min'][$i] && $cart_item['izw_qty'] <= $data['_izw_max'][$i]) break;
            }

            //Add shipping costs
            if(!empty($data['_izw_override_shipping']) && $data['_izw_override_shipping'] ){
                $cart_item_meta['izw_product_shipping'] = $data['_izw_shipping'][$i];
            }

            $imprintTypeID = $wpdb->get_var("SELECT `ID` FROM {$imprint_typesTable} WHERE `type_slug` = '{$cart_item['izw_imprintID']}'");
            $price = 0;
            if($data['_izw_onsale'] && strtotime($data['_izw_sale_from'])<= strtotime(date("Y-m-d")) && strtotime($data['_izw_sale_to'])>= strtotime(date("Y-m-d"))){
                $price = $data['_izw_sale'][$i][$imprintTypeID];
            }else{
                $price = $data['_izw_price'][$i][$imprintTypeID];
            }
            $cart_item['data']->set_price((float)$price);


            $extra_cost = 0;
            foreach ( $cart_item['promolizers_plugin'] as $product_step )
                if ( $product_step['price'] > 0 )
                    $extra_cost += $product_step['price'];
            $cart_item['data']->adjust_price( $extra_cost );
        }
        return $cart_item;
    }

    /**
     * Get item data
     *
     * @access public
     * @param mixed $other_data
     * @param mixed $cart_item
     * @return $other_data
     */
    public function get_item_data( $other_data, $cart_item ) {
        $currencySymbol = get_woocommerce_currency_symbol();
        $currency_pos = get_option('woocommerce_currency_pos');
        $stringFormat = '';
        switch($currency_pos){
            case "left":
                $stringFormat = ' (%2$s%1$s)';
                break;
            case "right":
                $stringFormat = ' (%1$s%2$s)';
                break;
            case "left_space":
                $stringFormat = ' (%2$s %1$s)';
                break;
            case "right_space":
                $stringFormat = ' (%1$s %2$s)';
                break;
            default:
                $stringFormat = ' (%1$s %2$s)';
                break;
        }
        $stringFormat = apply_filters('promolizer_price_format_cart_page',$stringFormat);

        if ( ! empty( $cart_item['promolizers_plugin'] ) ) {

            foreach ( $cart_item['promolizers_plugin'] as $product_step ) {

                $name = $product_step['name'];
                if($product_step['price'] > 0){
                    $other_data[] = array(
                        'name'    => $name,
                        'value'   => $product_step['value'].sprintf($stringFormat, $product_step['price'], $currencySymbol),
                        'display' => isset( $product_step['display'] ) ? $product_step['display'] : ''
                    );
                }else{
                    $other_data[] = array(
                        'name'    => $name,
                        'value'   => $product_step['value'],
                        'display' => isset( $product_step['display'] ) ? $product_step['display'] : ''
                    );
                }
            }
        }

        return $other_data;
    }

    /**
     * Get cart item from session
     *
     * @access public
     * @param mixed $cart_item
     * @param mixed $values
     * @return $values
     */
    public function get_cart_item_from_session( $cart_item, $values ) {
        if ( ! empty( $values['promolizers_plugin'] ) ) {
            $cart_item['promolizers_plugin'] = $values['promolizers_plugin'];
        }
        if ( ! empty( $values['izw_product_shipping'] ) ) {
            $cart_item['izw_product_shipping'] = $values['izw_product_shipping'];
        }
        if ( ! empty( $values['izw_qty'] ) ) {
            $cart_item['izw_qty'] = $values['izw_qty'];
        }
        if ( ! empty( $values['izw_imprintID'] ) ) {
            $cart_item['izw_imprintID'] = $values['izw_imprintID'];
        }
        $cart_item = $this->add_cart_item( $cart_item );
        return $cart_item;
    }

    /**
     * Add meta to orders
     *
     * @access public
     * @param mixed $item_id
     * @param mixed $values
     * @return void
     */
    public function order_item_meta( $item_id, $values ) {
        if ( ! empty( $values['promolizers_plugin'] ) ) {
            foreach ( $values['promolizers_plugin'] as $product_step ) {

                $name = $product_step['name'];
                woocommerce_add_order_item_meta( $item_id, $name, $product_step['value'] );
            }
        }
    }

    /**
     * Re-add cart item data
     *
     * @access public
     * @param mixed $cart_item_meta
     * @param mixed $product
     * @param mixed $order
     * @return $cart_item_meta
     */
    public function re_add_cart_item_data( $cart_item_meta, $product, $order ) {
        global $wpdb;
        //Table Name
        $imprint_typesTable         = $wpdb->prefix."imprint_types";
        $imprint_locationsTable     = $wpdb->prefix."imprint_locations";

        //Add promolizers plugin variable
        if ( empty( $cart_item_meta['promolizers_plugin'] ) )
            $cart_item_meta['promolizers_plugin'] = array();


        //Set variable default
        $data = array(
            'imprint_type' => array(),
            'imprint_location' => array(),
            'imprint_color' => array(),
            'imprint_size' => array(),
            'custom_text' => array(),
        );
        if( isset( $_POST['imprintType'] ) && !empty( $_POST['imprintType'] ) ){
            $imprintType = $_POST['imprintType'];
            $imprintTypeName = $wpdb->get_var("SELECT `type_name` FROM {$imprint_typesTable} WHERE `type_slug` = '{$imprintType}'");
            $data['imprint_type'] = array(
                'name' => 'Imprint Types',
                'value' => $imprintTypeName,
                'price' => 0,
            );
        }
        if( isset( $_POST['imprintLocation'] ) && !empty( $_POST['imprintLocation'] ) ){
            $imprintLocation = implode(",",$_POST['imprintLocation']);
            $imprintLocationName = $wpdb->get_results("SELECT `location_name` FROM {$imprint_locationsTable} WHERE `location_slug` IN ({$imprintLocation})");
            $data['imprint_location'] = array(
                'name' => 'Imprint Locations',
                'value' => implode(", ",$imprintLocationName),
                'price' => 0,
            );
        }
        if( isset($_POST['color'] ) && !empty( $_POST['color'] ) ){
            $imprint_color = implode(",",$_POST['color']);
            $imprint_colorName = $wpdb->get_var("SELECT `color_name` FROM {$imprint_locationsTable} WHERE `color_slug` IN ({$imprint_color})");
            $data['imprint_color'] = array(
                'name' => 'Imprint Colors',
                'value' => implode(", ",$imprint_colorName),
                'price' => 0,
            );
        }
        if( isset( $_POST['step3size'] ) ){
            $total = 0;
            $size = $_POST['step3size'];
            $sizeString = '';
            if(is_array($size)){
                foreach($size as $colorname=>$colorValue){
                    $sizeString .= $colorname.": ";
                    if(is_array($colorValue)){
                        $i=1;
                        $count = count($colorValue);
                        foreach($colorValue as $size=>$quantity){
                            $total = $total + (int)$quantity;
                            if($i==$count) {
                                $sizeString .= $size."({$quantity})";
                                $sizeString .= "; ";
                            }else{
                                $sizeString .= $size."({$quantity}),";
                            }
                            $i++;
                        }
                    }
                }
            }
            $data['imprint_size'] = array(
                'name' => 'Imprint Size',
                'value' => $sizeString,
                'price' => 0,
            );
        }
        if( isset( $_POST['custom-text'] ) && !empty( $_POST['custom-text'] ) ){
            $data['custom_text'] = array(
                'name' => 'Custom Text',
                'value' => $_POST['custom-text'],
                'price' => 0,
            );
        }

        //Set amazon s3 upload session
        if( isset($_FILES['browse'] ) ){
            $countFiles = count( $_FILES['browse']['name'] );
            $FileName = $_FILES['browse']['name'];
            $_SESSION['DesignData'] = array();
            for( $i=0; $i < $countFiles; $i++ ){
                if( !empty( $FileName[$i] ) && !$_FILES['browse']['error'][$i]){
                    $_SESSION['DesignData'][] = array(
                        'name' => $FileName[$i],
                        'tmp_name' => $_FILES['browse']['tmp_name'][$i],
                        'size' => $_FILES['browse']['size'][$i],
                        'error' => $_FILES['browse']['error'][$i],
                    );
                }
            }
        }

        //Add filter to change
        $data = apply_filters( 'promolizers_add_cart_item_data', $data, $cart_item_meta );

        foreach( $data as $key => $values ){
            if( sizeof( $values ) > 0 ){
                $cart_item_meta['promolizers_plugin'][$key] = array(
                    'name' 		=> $values['name'],
                    'value'		=> $values['value'],
                    'price' 	=> $values['price']
                );
            }
        }

        $quantity = $_POST['quantity'];
        $data = array(
            '_izw_onsale' => '',
            '_izw_sale_from' => '',
            '_izw_sale_to' => '',
            '_izw_override_shipping' => '',
            '_izw_max' => '',
            '_izw_min' => '',
            '_izw_price' => '',
            '_izw_sale' => '',
            '_izw_shipping' => '',
        );
        foreach($data as $key=>$values){
            if(empty($values)){
                $data[$key] = unserialize( get_post_meta( $product['product_id'], $key, true ) );
            }
        }
        for($i=0; $i<count($data['_izw_max']); $i++){
            if($quantity >= $data['_izw_min'][$i] && $quantity <= $data['_izw_max'][$i]) break;
        }

        //Add shipping costs
        if(!empty($data['_izw_override_shipping']) && $data['_izw_override_shipping'] ){
            $cart_item_meta['izw_product_shipping'] = $data['_izw_shipping'][$i];
        }

        //Add imprint_quantity
        $cart_item_meta['izw_qty'] = $quantity;
        $cart_item_meta['izw_imprintID'] = $_POST['imprintType'];
        return $cart_item_meta;
    }

    /**
     * Validation When Add To Cart
     *
     * @param $passed
     * @param $product_id
     * @param $qty
     * @return bool
     */
    function add_to_cart_validation($passed, $product_id, $qty){
        global $woocommerce;

        if(isset($_POST['step3size'])){
            $total = 0;
            $size = $_POST['step3size'];
            if(is_array($size)){
                foreach($size as $colorname=>$colorValue){
                    if(is_array($colorValue)){
                        foreach($colorValue as $size=>$quantity){
                            $total = $total + (int)$quantity;
                        }
                    }
                }
            }
            if($total != $_POST['quantity']){
                $passed = false;
                $woocommerce->add_error( sprintf( __('The total size is not same quantity', 'woocommerce')) );
            }
        }

        return $passed;
    }

    /**
     * Change Shipping Cost
     *
     * @param $packages
     * @return mixed
     */
    function change_shipping_cost($packages){
        global $woocommerce;
        foreach ( $woocommerce->cart->get_cart() as $item )
            if ( $item['data']->needs_shipping() )
                if ( isset( $item['izw_product_shipping'] ) )
                    $packages[0]['contents_cost'] += $item['izw_product_shipping'];
        return $packages;
    }

    /**
     * Add A fee for cart
     */
    function woo_add_cart_fee() {
        global $woocommerce;
        $shipping = 0;
        foreach(WC()->cart->get_cart() as $rows){
            $shipping += (float)$rows['izw_product_shipping'];
        }
        if( $shipping > 0 )
            $woocommerce->cart->add_fee( __('Promo Shipping', 'woocommerce'), $shipping );
    }
}
new Promolizers_Cart_Data();