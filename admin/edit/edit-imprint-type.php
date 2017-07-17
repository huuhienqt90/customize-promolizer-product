<h3><?php _e( "Edit Imprint T", __TEXTDOMAIN__); ?></h3>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    do_action('imprint_type_save_data');
}
global $wpdb,$woocommerce;
$ID = $_GET['ptid'] ? $_GET['ptid'] : 0;
$imprint_types = $wpdb->prefix."imprint_types";
$data = $wpdb->get_row("SELECT * FROM `{$imprint_types}` WHERE `ID` = '{$ID}'");
?>
<form action="" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field( 'product_imprint_type', 'edit_imprint_type' ) ?>
    <table class="form-table">
        <tbody>
        <?php do_action( 'promolizer_before_edit_imprint_type', $data); ?>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="type_name"><?php _e( "Name", __TEXTDOMAIN__ ); ?>:</label>
            </th>
            <td>
                <input name="type_name" id="type_name" type="text" value="<?php echo $data->type_name; ?>">
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="type_slug"><?php _e( "Slug", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <input name="type_slug" id="type_slug" type="text" value="<?php echo $data->type_slug; ?>">
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="description"><?php _e( "Description", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <textarea name="description" id="description"><?php echo $data->description; ?></textarea>
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="image"><?php _e( "Image", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <input name="image" id="image" type="hidden" value="<?php echo $data->image; ?>" />
                <div class="izwImageThubnail">
                    <img src="<?php echo !empty($data->image) ? $data->image : $woocommerce->plugin_url()."/assets/images/placeholder.png"; ?>">
                </div>
                <input type="file" name="_izw_image" />
                <div class="clear"></div>
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="setup_charge"><?php _e( "Default Setup Charge", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <?php echo get_woocommerce_currency_symbol()," "; ?><input name="setup_charge" id="setup_charge" type="text" value="<?php echo $data->setup_charge; ?>">
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="max_color"><?php _e( "Max Colors", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <input name="max_color" id="max_color" class="wid80" type="text" value="<?php echo $data->max_color; ?>">
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="max_location"><?php _e( "Max Locations", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <input name="max_location" id="max_location" class="wid80" type="text" value="<?php echo $data->max_location; ?>">
            </td>
        </tr>
        <?php do_action( 'promolizer_after_edit_imprint_type', $data); ?>

        </tbody>
    </table>
    <?php submit_button( 'Update', 'primary', 'izw_update') ?>
    <div class="clear"></div>
</form>