<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 8/18/14
 * Time: 11:26 AM
 */

class General_Settings {
    function __construct(){
        add_action( 'promolizers_tab_general', array( $this, 'general_settings' ) );
        add_action( 'general_update_setings', array( $this, 'general_save') );
    }

    /**
     * Out put general settings page
     */
    function general_settings(){
        include (__PROMOPATH__."admin/general-settings.php");
    }

    /**
     * Save option of general settings
     */
    function general_save(){
        if(isset($_POST['izw_shipping_flat'])){
            if(!isset($_POST['izw_flat_rate_shipping']) || !isset($_POST['izw_flat_rate_shipping'])){
                update_option('izw_flat_rate_shipping','0');
            }
            foreach($_POST as $key=>$value){
                if($key == 'izw_shipping_flat') continue;
                $options = get_option($key,true);
                if(!empty($options) or sizeof($options) > 0){
                    update_option($key,$value);
                }else{
                    add_option($key,$value);
                }
            }
        }
        if(isset($_POST['izw_cart'])){
            if(!isset($_POST['izw_cart_size']) || !isset($_POST['izw_cart_imprint_location'])){
                delete_option('izw_cart_size');
                delete_option('izw_cart_imprint_location');
            }
            foreach($_POST as $key=>$value){
                if($key == 'izw_cart') continue;
                update_option($key,$value);
            }
        }
        if(isset($_POST['izw_amazon_s3'])){
            foreach($_POST as $key=>$value){
                if($key == 'izw_amazon_s3') continue;
                if(!empty($value)){
                    $options = get_option($key);
                    if(!empty($options) or sizeof($options) > 0){
                        update_option($key,$value);
                    }else{
                        add_option($key,$value);
                    }
                }else{
                    delete_option($key);
                }
            }
        }
    }
}
new General_Settings();
