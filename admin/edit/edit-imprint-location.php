    <h3><?php _e( "Edit Imprint Location", __TEXTDOMAIN__); ?></h3>
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        do_action('imprint_location_save_data');
    }
    global $wpdb;
    $ID = $_GET['plid'] ? $_GET['plid'] : 0;
    $imprint_location = $wpdb->prefix."imprint_locations";
    $data = $wpdb->get_row("SELECT * FROM `{$imprint_location}` WHERE `ID` = '{$ID}'");
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <?php wp_nonce_field( 'imprint_location', 'edit_location' ) ?>
        <table class="form-table">
            <tbody>
            <tr class="form-field form-required">
                <th scope="row" valign="top">
                    <label for="location_name"><?php _e( "Name", __TEXTDOMAIN__ ); ?>:</label>
                </th>
                <td>
                    <input name="location_name" id="location_name" type="text" value="<?php echo $data->location_name; ?>">
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row" valign="top">
                    <label for="location_slug"><?php _e( "Slug", __TEXTDOMAIN__ ); ?>: </label>
                </th>
                <td>
                    <input name="location_slug" id="location_slug" type="text" value="<?php echo $data->location_slug; ?>">
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
            </tbody>
        </table>
        <?php submit_button( 'Update', 'primary', 'izw_update') ?>
        <div class="clear"></div>
    </form>