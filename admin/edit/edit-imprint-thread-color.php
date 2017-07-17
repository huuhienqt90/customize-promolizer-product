<h3><?php _e( "Edit Imprint/Thread Colors", __TEXTDOMAIN__); ?></h3>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    do_action('imprint_colors_save_data');
}
global $wpdb;
$ID = $_GET['cid'] ? $_GET['cid'] : 0;
$product_color = $wpdb->prefix."imprint_colors";
$data = $wpdb->get_row("SELECT * FROM `{$product_color}` WHERE `ID` = '{$ID}'");
?>
<form action="" method="post">
    <?php wp_nonce_field( 'imprint_color', 'edit_imprint_color' ) ?>
    <table class="form-table">
        <tbody>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="color_name"><?php _e( "Name", __TEXTDOMAIN__ ); ?>:</label>
            </th>
            <td>
                <input name="color_name" id="color_name" type="text" value="<?php echo $data->color_name; ?>">
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="color_slug"><?php _e( "Slug", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <input name="color_slug" id="color_slug" type="text" value="<?php echo $data->color_slug; ?>" maxlength="28">
            </td>
        </tr>

        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="pantone_color"><?php _e( "Pantone Color", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <input name="pantone_color" id="pantone_color" type="text" value="<?php echo $data->pantone_color; ?>">
            </td>
        </tr>

        <tr class="form-field form-required">
            <th scope="row" valign="top">
                <label for="hex_code"><?php _e( "Hex Code", __TEXTDOMAIN__ ); ?>: </label>
            </th>
            <td>
                <input name="hex_code" id="hex_code" class="hex_code" type="text" value="<?php echo $data->hex_code; ?>" maxlength="7">
            </td>
        </tr>
        </tbody>
    </table>
    <?php submit_button( 'Update', 'primary', 'izw_update') ?>
    <div class="clear"></div>
</form>