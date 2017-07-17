<?php
/**
 * Plugin Name: Customize Promolizer Product
 * Plugin URI: https://bitbucket.org/huuhienqt90/customize-promolizer-product
 * Description: Customize Promolizer Product
 * Author: Hien(Hamilton) H.HO
 * Author URI: https://bitbucket.org/huuhienqt90
 * Version: 1.0.1
 * Text Domain: customize-promolizer-product
 * License: GPL
 */

define( '__TEXTDOMAIN__', 'customize-promolizer-product' );
define( '__PROMOPATH__', plugin_dir_path( __FILE__ ) );
define( '__PLUGINURL__', plugin_dir_url( __FILE__ ) );
define( '__DBVERSION', '1.0.1' );
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    exit( __( "Please Install and Active plugin Woocommerce to use this plugin "));
}

class CustomizePromolizerProduct{

    /**
     * Constructor class
     */
    public function __construct(){
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'init', array( $this, 'init') );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_style' ) );
        add_action( 'promolizers_sections', array( $this, 'register_plugin_scripts' ) );
        register_activation_hook( __FILE__, array( $this, 'activation' ) );
        register_deactivation_hook(__FILE__, array( $this, 'deactivation' ) );
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
        add_action( 'wp_insert_post', array( $this, 'auto_increment_item' ), 10, 3 );
        add_filter( 'woocommerce_is_sold_individually', array( $this, 'wc_remove_all_quantity_fields' ), 999 );
    }

    /**
     * Remove all quantity fields
     */
    public function wc_remove_all_quantity_fields(  ) {
        return true ;
    }

    /**
     * Add Submenu Page
     */
    public function admin_menu(){
        add_submenu_page( 'edit.php?post_type=product', 'Global Settings ', 'Global Settings ', 'manage_options', 'promolizers-settings', array( $this, 'admin_sttings_page' ) );
    }

    /**
     * Include Settings File
     */
    public function admin_sttings_page(){
        include_once ( __PROMOPATH__."/admin/settings.php" );
    }

    /**
     * Register Script For Front-End
     */
    public function register_plugin_style(){
        wp_enqueue_script('jquery','http://code.jquery.com/jquery-1.11.0.js');
        wp_enqueue_script('jquery-ui','http://code.jquery.com/ui/1.11.1/jquery-ui.js');
        //wp_enqueue_script('jquery-ui-slider',includes_url('/js/jquery/ui/jquery.ui.slider.min.js'));
        wp_enqueue_script('form-validation', __PLUGINURL__ . 'assets/front/js/form-validation.js');
        wp_enqueue_script('form', __PLUGINURL__ . 'assets/front/js/form.js');
        wp_enqueue_script('form2', __PLUGINURL__ . 'assets/front/js/form2.js');
        wp_enqueue_script('iris',admin_url( 'js/iris.min.js' ));
        wp_register_style( 'wp-front', __PLUGINURL__. 'assets/front/css/style-front.css' );
        wp_enqueue_style( 'wp-front' );
        wp_register_style( 'wp-jquery-ui', __PLUGINURL__. 'assets/front/css/jquery-ui.css' );
        wp_enqueue_style( 'wp-jquery-ui' );
    }

    /**
     * Includes File
     */
    public function init(){
        include_once (__PROMOPATH__."/functions.php");
        include_once (__PROMOPATH__."/includes/class-general-settings.php");
        include_once (__PROMOPATH__."/includes/class-product-colors.php");
        include_once (__PROMOPATH__."/includes/class-imprint-types.php");
        include_once (__PROMOPATH__."/includes/class-imprint-locations.php");
        include_once (__PROMOPATH__."/admin/product/custom-product-data.php");
        include_once (__PROMOPATH__."/includes/class-imprint-thread-colors.php");
        include_once (__PROMOPATH__."/front/class-display-single.php");
        include_once (__PROMOPATH__."/front/templates/class-add-to-cart-step4.php");
    }

    /**
     * Register Script On Back-End
     */
    public function register_plugin_scripts(){
        global $woocommerce;
        wp_enqueue_script( 'jquery' );
        wp_enqueue_media();
        wp_register_style( 'wp-datepicker', __PLUGINURL__. 'assets/admin/css/jquery-ui.css' );
        wp_enqueue_style( 'wp-datepicker' );
        wp_enqueue_script( 'wp-datepicker', __PLUGINURL__. 'assets/admin/js/jquery-ui.js' );
        wp_register_style( 'promolizers-admin', __PLUGINURL__. 'assets/admin/css/style.css' );
        wp_enqueue_style( 'promolizers-admin' );

        $suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        wp_register_script( 'ajax-chosen', WC()->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery' . $suffix . '.js', array('jquery', 'chosen'), WC_VERSION );

        wp_register_script( 'chosen', WC()->plugin_url() . '/assets/js/chosen/chosen.jquery' . $suffix . '.js', array('jquery'), WC_VERSION );

        wp_enqueue_script( 'ajax-chosen' );
        wp_enqueue_script( 'chosen' );
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script(
                'iris',
                admin_url( 'js/iris.min.js' ),
                array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
                false,
                1
        );
        $posttype = @get_post_type( get_the_ID() );
        if((isset($_REQUEST['page']) && $_REQUEST['page'] == 'promolizers-settings') or ($posttype == 'product')){
                wp_enqueue_script(
                        'promolizer-admin',
                        __PLUGINURL__."assets/admin/js/admin.js"
                );
        }
        wp_enqueue_style( 'woocommerce_chosen_styles', $woocommerce->plugin_url() . '/assets/css/chosen.css' );
        wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
    }

    /**
     * Create Table When Plugin Activate
     */
    public function activation(){
        global $wpdb;
        $version = get_option('promolizer_db_version');
        if($version != __DBVERSION){
            $productColorTable = $wpdb->prefix.'product_colors';
            $productColorSQL = "CREATE TABLE IF NOT EXISTS `{$productColorTable}` (\n
                                                        `ID` int(11) NOT NULL AUTO_INCREMENT,\n
                                                        `color_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        `color_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        `hex_code1` varchar(7) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        `pantone_color1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        `hex_code2` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,\n
                                                        `pantone_color2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,\n
                                                        PRIMARY KEY (`ID`)\n
                                                    );";

            $productTypesTable = $wpdb->prefix.'imprint_types';
            $productTypesSQL = "CREATE TABLE IF NOT EXISTS `{$productTypesTable}` (
                                                        `ID` int(11) NOT NULL AUTO_INCREMENT,
                                                        `type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                        `type_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                        `description` text COLLATE utf8_unicode_ci NOT NULL,
                                                        `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                        `setup_charge` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                        PRIMARY KEY (`ID`)
                                                    );";

            $productLocationsTable = $wpdb->prefix.'imprint_locations';
            $productLocationsSQL = "CREATE TABLE IF NOT EXISTS `{$productLocationsTable}` (
                                                        `ID` int(11) NOT NULL AUTO_INCREMENT,
                                                        `location_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                        `location_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                        `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                                                        PRIMARY KEY (`ID`)
                                                    );";
            $productImprintThreadTable = $wpdb->prefix.'imprint_colors';
            $productImprintThreadSQL = "CREATE TABLE IF NOT EXISTS `{$productImprintThreadTable}` (\n
                                                        `ID` int(11) NOT NULL AUTO_INCREMENT,\n
                                                        `color_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        `color_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        `pantone_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        `hex_code` varchar(7) COLLATE utf8_unicode_ci NOT NULL,\n
                                                        PRIMARY KEY (`ID`)\n
                                                    );";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $productColorSQL );
            dbDelta( $productTypesSQL );
            dbDelta( $productLocationsSQL );
            dbDelta( $productImprintThreadSQL );

            $ADDColumn = "ALTER TABLE `{$productTypesTable}`
                            ADD  `max_color` INT( 11 ) NOT NULL ,
                            ADD  `max_location` INT( 11 ) NOT NULL";
            $wpdb->query($ADDColumn);
            update_option( 'promolizer_db_version', __DBVERSION );
        }
    }

    /**
     * Deactivation Action
     */
    public function deactivation(){
        do_action('customize_promolizer_product_deactive');
    }

    /**
     * Load Class Add To Cart Data
     */
    public function plugins_loaded(){
        include_once (__PROMOPATH__."/front/class-promolizers-cart-data.php");
    }

    /**
     * Update item auto increment when insert product
     *
     * @param $post_ID
     * @param $post
     * @param $update
     */
    public function auto_increment_item( $post_ID, $post, $update ){
        if($post->post_type == 'product' && !$update){
                $max = (int)get_option( '_izw_current_item' );
                $max = $max ? $max : 1001;
                update_post_meta( $post_ID, '_izw_item', $max + 1 );
                update_option( '_izw_current_item', $max + 1 );
        }
    }
}

$GLOBALS['izwpromolizers'] = new CustomizePromolizerProduct();