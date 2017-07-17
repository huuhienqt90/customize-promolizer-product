<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 8/19/14
 * Time: 9:20 AM
 */

class Promolizers_Imprint_Colors{
    var $table_name = '';
    function __construct(){
        global $wpdb;
        $this->table_name = $wpdb->prefix."imprint_colors";
        add_action( 'promolizers_tab_imprint_colors', array( $this, 'imprint_thread_colors' ) );
        add_action( 'imprint_colors_save_data', array( $this, 'save_data' ) );
    }

    /**
     * Save data
     */
    function save_data(){
        $data = array(
            'color_name' => $_POST['color_name'],
            'color_slug' => $_POST['color_slug'],
            'pantone_color' => $_POST['pantone_color'],
            'hex_code' => $_POST['hex_code'],
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
                if ( wp_verify_nonce( $_POST['add_imprint_color'], 'imprint_color' ) ) {
                    izw_add_data($this->table_name, $data,'Imprint/Thread Colors','color_slug', $error);
                }
                break;
            case 'edit':
                $ID = $_GET['cid'] ? $_GET['cid'] : 0;
                if ( wp_verify_nonce( $_POST['edit_imprint_color'], 'imprint_color' ) ) {
                    izw_update_data($this->table_name, $data, $ID, 'Imprint/Thread Colors', 'color_slug',$error);
                }
                break;
            case 'delete':
                $nonce = $_REQUEST['_wpnonce'];
                $ID = $_GET['cid'] ? $_GET['cid'] : 0;
                if ( wp_verify_nonce( $nonce, 'delete_imprint_color'.$ID ) ) {
                    izw_delete_data($this->table_name, $ID, 'Imprint/Thread Colors');
                }
                break;
        }
    }

    /**
     * Load settings page
     */
    function imprint_thread_colors(){
        include (__PROMOPATH__."admin/imprint-thread-colors.php");
    }
}
new Promolizers_Imprint_Colors();