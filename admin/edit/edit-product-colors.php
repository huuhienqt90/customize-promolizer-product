    <h3><?php _e( "Edit Product Color", __TEXTDOMAIN__); ?></h3>
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        do_action('product_color_save_data');
    }
    global $wpdb;
    $ID = $_GET['cid'] ? $_GET['cid'] : 0;
    $product_color = $wpdb->prefix."product_colors";
    $data = $wpdb->get_row("SELECT * FROM `{$product_color}` WHERE `ID` = '{$ID}'");
    ?>
    <form action="" method="post">
        <?php wp_nonce_field( 'product_color', 'edit_color' ) ?>
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
                    <label for="pantone_color1"><?php _e( "Pantone Color", __TEXTDOMAIN__ ); ?>: </label>
                </th>
                <td>
                    <input name="pantone_color1" id="pantone_color1" type="text" value="<?php echo $data->pantone_color1; ?>">
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row" valign="top">
                    <label for="hex_code1"><?php _e( "Hex Code", __TEXTDOMAIN__ ); ?>: </label>
                </th>
                <td>
                    <input name="hex_code1" id="hex_code1" class="hex_code" type="text" value="<?php echo $data->hex_code1; ?>" maxlength="7">
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row" valign="top">
                    <label for="two-color">
                        <input type="checkbox" id="two-color" name="two-color" value="1"/>
                        <?php _e( "Two-colors", __TEXTDOMAIN__ ); ?>
                    </label>
                </th>
                <td>

                </td>
            </tr>
            <tr class="form-field form-required ColorTwo">
                <th scope="row" valign="top">
                    <label for="pantone_color2"><?php _e( "Pantone Color 2", __TEXTDOMAIN__ ); ?>: </label>
                </th>
                <td>
                    <input name="pantone_color2" id="pantone_color2" type="text" value="<?php echo $data->pantone_color2; ?>">
                </td>
            </tr>
            <tr class="form-field form-required ColorTwo">
                <th scope="row" valign="top">
                    <label for="hex_code2"><?php _e( "Hex Code 2", __TEXTDOMAIN__ ); ?>: </label>
                </th>
                <td>
                    <input name="hex_code2" id="hex_code2" class="hex_code" type="text" value="<?php echo $data->hex_code2; ?>" maxlength="7">
                </td>
            </tr>

            </tbody>
        </table>
        <?php submit_button( 'Update', 'primary', 'izw_update') ?>
        <div class="clear"></div>
    </form>
    <style type="text/css">
        <?php if(!empty($data->hex_code1)): ?>
        #hex_code1{
            background: <?php echo $data->hex_code1; ?>;
        }
        <?php endif; ?>
        <?php if(!empty($data->hex_code2)): ?>
        #hex_code2{
            background: <?php echo $data->hex_code2; ?>;
        }
        <?php endif; ?>
    </style>