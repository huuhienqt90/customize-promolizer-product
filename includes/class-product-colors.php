<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 8/19/14
 * Time: 9:20 AM
 */

class Promolizers_Product_Colors {
    var $table_name = '';
    function __construct(){
        global $wpdb;
        $this->table_name = $wpdb->prefix."product_colors";
        add_action( 'promolizers_tab_product_color', array( $this, 'product_colors' ) );
        add_action( 'product_color_save_data', array( $this, 'save_data' ) );
    }

    /**
     * Save data
     */
    function save_data(){
        global $wpdb;
        $data = array(
            'color_name' => $_POST['color_name'],
            'color_slug' => $_POST['color_slug'],
            'hex_code1' => $_POST['hex_code1'],
            'pantone_color1' => $_POST['pantone_color1'],
            'hex_code2' => $_POST['hex_code2'],
            'pantone_color2' => $_POST['pantone_color2'],
        );
        $error = '';
        if( empty($_POST['color_name'] ) ){
            $error = __( 'Please enter "Color Name".',__TEXTDOMAIN__ );
        }
        if(empty($error)){
            $slug = sanitize_title($_POST['color_name']);
            if(!empty($_POST['color_slug'])){
                $slug = sanitize_title( $_POST['color_slug'] );
            }
            $data['color_slug'] = $slug;
        }
        switch($_GET['action']){
            case 'add':
                if ( wp_verify_nonce( $_POST['add_color'], 'product_color' ) ) {
                    izw_add_data($this->table_name, $data,'product color','color_slug', $error);
                }
                break;
            case 'edit':
                $ID = $_GET['cid'] ? $_GET['cid'] : 0;
                if ( wp_verify_nonce( $_POST['edit_color'], 'product_color' ) ) {
                    echo $this->table_name, $data, $ID, 'product color', 'color_slug',$error;
                    izw_update_data($this->table_name, $data, $ID, 'product color', 'color_slug',$error);
                }
                break;
            case 'delete':
                $nonce = $_REQUEST['_wpnonce'];
                $ID = $_GET['cid'] ? $_GET['cid'] : 0;
                if ( wp_verify_nonce( $nonce, 'delete_color'.$ID ) ) {
                    izw_delete_data($this->table_name, $ID, 'product color');
                }
                break;
        }
    }

    /**
     * Load setting page
     */
    function product_colors(){
        include (__PROMOPATH__."admin/product-colors.php");
    }
}
new Promolizers_Product_Colors();