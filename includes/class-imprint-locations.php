<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 8/19/14
 * Time: 9:20 AM
 */

class Promolizers_Imprint_Locations {
    var $table_name = '';
    function __construct(){
        global $wpdb;
        $this->table_name = $wpdb->prefix."imprint_locations";
        add_action( 'promolizers_tab_imprint_location', array( $this, 'imprint_location' ) );
        add_action( 'imprint_location_save_data', array( $this, 'save_data' ) );
    }

    /**
     * Save data
     */
    function save_data(){
        $data = array(
            'location_name' => $_POST['location_name'],
            'location_slug' => $_POST['location_slug'],
        );
        $uploadurl = _izw_upload_image($type = 'imprint-location');
        if( $uploadurl != ''){
            $data['image'] = $uploadurl;
        }
        if( empty($_POST['location_name'] ) ){
            $error = __( 'Please enter "Location Name".',__TEXTDOMAIN__ );
        }
        // Auto-generate the label or slug if only one of both was provided
        if(empty($error)){
            $slug = sanitize_title($_POST['location_name']);
            if(!empty($_POST['location_slug'])){
                $slug = sanitize_title( $_POST['location_slug'] );
            }
            $data['location_slug'] = $slug;
        }
        switch($_GET['action']){
            case 'add':
                if ( wp_verify_nonce( $_POST['add_location'], 'imprint_location' ) ) {
                    izw_add_data($this->table_name, $data,'imprint location','location_slug', $error);
                }
                break;
            case 'edit':
                $ID = $_GET['plid'] ? $_GET['plid'] : 0;
                if ( wp_verify_nonce( $_POST['edit_location'], 'imprint_location' ) ) {
                    izw_update_data($this->table_name, $data, $ID, 'imprint location', 'location_slug',$error);
                }
                break;
            case 'delete':
                $nonce = $_REQUEST['_wpnonce'];
                $ID = $_GET['plid'] ? $_GET['plid'] : 0;
                if ( wp_verify_nonce( $nonce, 'delete_location'.$ID ) ) {
                    izw_delete_data($this->table_name, $ID, 'imprint location');
                }
                break;
        }
    }

    /**
     * Out put imprint location page
     */
    function imprint_location(){
        include (__PROMOPATH__."admin/imprint-locations.php");
    }
}
new Promolizers_Imprint_Locations();