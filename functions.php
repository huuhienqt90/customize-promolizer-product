<?php
/**
 * Created by PhpStorm.
 * User: LuckyStar
 * Date: 8/25/14
 * Time: 9:32 AM
 */
/**
 * Check slug exists
 *
 * @param string $slug
 * @param string $table_name
 * @param string $slug_where
 * @param int $ID
 * @return null|string
 */
function izw_check_slug($slug = '',$table_name = '',$slug_where = '', $ID = 0){
    global $wpdb;
    if($ID){
        $return = $wpdb->get_var("SELECT COUNT(*) FROM `{$table_name}` WHERE `{$slug_where}` = '{$slug}' AND `ID` <> '{$ID}'");
    }else{
        $return = $wpdb->get_var("SELECT COUNT(*) FROM `{$table_name}` WHERE `{$slug_where}` = '{$slug}'");
    }
    return $return;
}

/**
 * Show Message in settings page
 *
 * @param string $message
 * @param string $class
 * @param bool $echo
 * @return string
 */
function izw_show_message( $message = '', $class = 'updated', $echo = true){
    if($echo){
        ?>
        <div id="woocommerce_errors" class="<?php echo $class; ?> fade"><p><?php echo $message; ?></p></div>
    <?php
    }else{
        $return = '<div id="woocommerce_errors" class="'. $class. ' fade"><p>'. $message. '</p></div>';
        return $return;
    }
}

/**
 * Add date to table
 *
 * @param string $table_name
 * @param array $data
 * @param string $name
 * @param string $slug_name
 * @param string $error
 */
function izw_add_data($table_name = '', $data= array(), $name= '',$slug_name = 'color_slug', $error = ''){
    global $wpdb;
    if( !izw_check_slug($data[$slug_name],$table_name,$slug_name ) ){
        if(!empty($error)) {
            izw_show_message( $error, $class = 'error');
        }else{
            $wpdb->insert($table_name,$data);
            if($wpdb->insert_id){
                izw_show_message( __( sprintf( 'The %s was update success.', $name ), __TEXTDOMAIN__ ) );
            }
        }
    }else{
        $message = __("Slug “{$data['color_slug']}” is already in use. Change it, please.", __TEXTDOMAIN__);
        izw_show_message( $message, $class = 'error');
    }
}

/**
 * Update data
 *
 * @param string $table_name
 * @param array $data
 * @param int $ID
 * @param string $name
 * @param string $slug_name
 * @param string $error
 */
function izw_update_data($table_name = '', $data= array(), $ID = 0, $name= '', $slug_name = 'color_slug',$error = ''){
    global $wpdb;
    if( !izw_check_slug($data[$slug_name],$table_name,$slug_name,$ID ) ){
        $where = array( 'ID' => $ID );
        if(!empty($error) && $error != '') {
            izw_show_message( $error, $class = 'error');
        }else{
            $update = $wpdb->update(
                $table_name,
                $data,
                $where
            );
            if($update){
                izw_show_message( __( sprintf( 'The %s was update success.', $name ), __TEXTDOMAIN__ ) );
            }
        }
    }else{
        $message = __("Slug “{$data[$slug_name]}” is already in use. Change it, please.", __TEXTDOMAIN__);
        izw_show_message( $message, $class = 'error');
    }
}

/**
 * Delete Data
 * @param string $table_name
 * @param int $ID
 * @param string $name
 */
function izw_delete_data($table_name = '', $ID = 0, $name= ''){
    global $wpdb;
    $wpdb->delete( $table_name, array( 'ID' => $ID ) );
    izw_show_message( __( sprintf( 'The %s was delete success.', $name ), __TEXTDOMAIN__ ) );
}

/**
 * Get Step Imprint Types
 * @param $DTimprint_types_dnmprc
 * @return float
 */
function get_step_imprint_types($DTimprint_types_dnmprc){
    global $izwpsp;
    foreach($izwpsp['imprint_types'] as $val){
        if($val->ID == $DTimprint_types_dnmprc) return floatval($val->setup_charge);
    }
}

/**
 * Get price of imprint type
 *
 * @param $DTimprint_types_dnmprc
 * @param $ui
 * @return mixed
 */
function get_price_imprint_types($DTimprint_types_dnmprc,$ui){
    global $izwpsp;
    for($i = 0; $i < count($izwpsp['DT_izw_max']) ; $i++){
        if($i == count($izwpsp['DT_izw_max'])-1){
            if($izwpsp['DT_izw_max'][$i] <= $ui)
                if($izwpsp['DT_izw_onsale']) $price = $izwpsp['DT_izw_sale'][$i][$izwpsp['DTimprint_types_dnmprc']];else $price = $izwpsp['DT_izw_price'][$i][$izwpsp['DTimprint_types_dnmprc']];
        }elseif(($izwpsp['DT_izw_max'][$i] <= $ui)&&($izwpsp['DT_izw_max'][$i+1] > $ui));
        if($izwpsp['DT_izw_onsale']) $price = $izwpsp['DT_izw_sale'][$i][$izwpsp['DTimprint_types_dnmprc']];else $price = $izwpsp['DT_izw_price'][$i][$izwpsp['DTimprint_types_dnmprc']];
    }
    return $price;
}

/**
 * Get price of product
 *
 * @param $i
 * @param $posi
 * @return mixed
 */
function get_price_DT($i,$posi){
    //$posi ~ $izwpsp['DTimprint_types_dnmprc']
    global $izwpsp;
    if(check_onsale_product()) $price = $izwpsp['DT_izw_sale'][$i][$posi];
    else $price = $izwpsp['DT_izw_price'][$i][$posi];
    return $price;
}

/**
 * Check sale product
 *
 * @return bool
 */
function check_onsale_product(){
    global $izwpsp;
    if(!empty($izwpsp['DT_izw_onsale'])){
        if(!empty($izwpsp['DT_izw_sale_from']) && !empty($izwpsp['DT_izw_sale_to'])){
            if($izwpsp['DT_izw_onsale'] && strtotime($izwpsp['DT_izw_sale_from'])<= strtotime(date("Y-m-d")) && strtotime($izwpsp['DT_izw_sale_to'])>= strtotime(date("Y-m-d"))) $return = true;else $return = false;
        }elseif(!empty($izwpsp['DT_izw_sale_from']) && empty($izwpsp['DT_izw_sale_to'])){
            if($izwpsp['DT_izw_onsale'] && strtotime($izwpsp['DT_izw_sale_from'])<= strtotime(date("Y-m-d")) ) $return = true;else $return = false;
        }elseif(empty($izwpsp['DT_izw_sale_from']) && !empty($izwpsp['DT_izw_sale_to'])){
            if($izwpsp['DT_izw_onsale'] && strtotime($izwpsp['DT_izw_sale_to'])>= strtotime(date("Y-m-d"))) $return = true;else $return = false;
        }else{
            if($izwpsp['DT_izw_onsale']) $return = true;else $return = false;
        }
    }else $return = false;
    return $return;
}

/**
 * Upload image imprint type and imprint location
 * @param string $type
 * @return string
 */
function _izw_upload_image( $type = 'imprint-type' ){
    $directory = '';
    switch($type){
        case "imprint-type":
            $directory = "imprint-type";
            break;
        case "imprint-location":
            $directory = "imprint-location";
            break;
        default:
            $directory = "imprint-type";
            break;
    }
    $directory = apply_filters("izw_upload_image",$directory, $type);

    $directoryUpload = WP_CONTENT_DIR."/uploads/".$directory;
    if (!file_exists($directoryUpload)) {
        mkdir($directoryUpload, 0777, true);
    }

    if(isset($_FILES['_izw_image']) && !empty($_FILES['_izw_image']['name']) ){
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["_izw_image"]["name"]);
        $extension = end($temp);


        if ((($_FILES["_izw_image"]["type"] == "image/gif")
                || ($_FILES["_izw_image"]["type"] == "image/jpeg")
                || ($_FILES["_izw_image"]["type"] == "image/jpg")
                || ($_FILES["_izw_image"]["type"] == "image/pjpeg")
                || ($_FILES["_izw_image"]["type"] == "image/x-png")
                || ($_FILES["_izw_image"]["type"] == "image/png"))
            && in_array($extension, $allowedExts)) {
            if ($_FILES["_izw_image"]["error"] <= 0) {
                if (!file_exists($directoryUpload."/" . sanitize_file_name( $_FILES["_izw_image"]["name"] ))) {
                    move_uploaded_file($_FILES["_izw_image"]["tmp_name"],
                        $directoryUpload."/" . sanitize_file_name( $_FILES["_izw_image"]["name"] ));
                }
                return home_url()."/wp-content/uploads/".$directory."/".sanitize_file_name( $_FILES["_izw_image"]["name"] );
            }
        }
    }
    return '';
}