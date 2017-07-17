<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 8/19/14
 * Time: 9:20 AM
 */

class Promolizers_Imprint_Types{
    var $table_name = '';
    function __construct(){
        global $wpdb;
        $this->table_name = $wpdb->prefix."imprint_types";
        add_action( 'promolizers_tab_imprint_type', array( $this, 'imprint_type' ) );
        add_action( 'imprint_type_save_data', array( $this, 'save_data' ) );
    }

    /**
     * Load setting page
     */
    function imprint_type(){
        include (__PROMOPATH__."admin/imprint-types.php");
    }

    /**
     * Save data
     */
    function save_data(){
        global $wpdb;
        // Auto-generate the label or slug if only one of both was provided
        $data = array(
            'type_name' => $_POST['type_name'],
            'type_slug' => $_POST['type_slug'],
            'description' => $_POST['description'],
            'setup_charge' => $_POST['setup_charge'] ? $_POST['setup_charge'] : '0',
            'max_color' => $_POST['max_color'] ? $_POST['max_color'] : '1',
            'max_location' => $_POST['max_location'] ? $_POST['max_location'] : '1',
        );
        $uploadurl = _izw_upload_image($type = 'imprint-type');
        if( $uploadurl != ''){
            $data['image'] = $uploadurl;
        }
        $error = '';
        if( empty($_POST['type_name'] ) ){
            $error = __( 'Please enter "Type Name".',__TEXTDOMAIN__ );
        }
        if(empty($error)){
            $slug = sanitize_title($_POST['type_name']);
            if(!empty($_POST['type_slug'])){
                $slug = sanitize_title( $_POST['type_slug'] );
            }
            $data['type_slug'] = $slug;
        }
        switch($_GET['action']){
            case 'add':
                if ( wp_verify_nonce( $_POST['add_imprint_type'], 'product_imprint_type' ) ) {
                    izw_add_data($this->table_name, $data,'imprint type','type_slug', $error);
                }
                break;
            case 'edit':
                $ID = $_GET['ptid'] ? $_GET['ptid'] : 0;
                if ( wp_verify_nonce( $_POST['edit_imprint_type'], 'product_imprint_type' ) ) {
                    izw_update_data($this->table_name, $data, $ID, 'imprint type', 'type_slug',$error);
                }
                break;
            case 'delete':
                $nonce = $_REQUEST['_wpnonce'];
                $ID = $_GET['ptid'] ? $_GET['ptid'] : 0;
                if ( wp_verify_nonce( $nonce, 'delete_imprint_type'.$ID ) ) {
                    izw_delete_data($this->table_name, $ID, 'imprint type');
                }
                break;
        }
    }
}
new Promolizers_Imprint_Types();