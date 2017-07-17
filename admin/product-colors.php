<div id="col-container">
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' or ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['page'] == 'promolizers-settings' && $_GET['action'] == 'delete')){
        do_action('product_color_save_data');
    }
    if($_SERVER['REQUEST_METHOD'] == 'GET' and $_GET['action'] == 'edit'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            do_action('product_color_save_data');
        }
        include_once (__PROMOPATH__."/admin/edit/edit-product-colors.php");
    }else{
        ?>
        <div id="col-right">
            <div class="col-wrap">
                <?php
                include_once (__PROMOPATH__."/admin/data/class-data-product-color.php");
                $data = new Data_Product_Color();
                $data->prepare_items();
                $data->display();
                ?>
            </div>
        </div>
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3><?php _e( "Add New Product Color", __TEXTDOMAIN__); ?></h3>
                    <form action="<?php echo add_query_arg( array('action'=>'add'), $_SERVER['REQUEST_URI'] ); ?>" method="post">
                        <?php wp_nonce_field( 'product_color', 'add_color' );?>
                        <?php do_action( 'promolizer_before_product_color_field' ); ?>
                        <div class="form-field">
                            <label for="color_name"><?php _e( "Name", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="color_name" id="color_name" type="text" value="">
                        </div>

                        <div class="form-field">
                            <label for="color_slug"><?php _e( "Slug", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="color_slug" id="color_slug" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field">
                            <label for="pantone_color1"><?php _e( "Pantone Color", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="pantone_color1" id="pantone_color1" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field">
                            <label for="hex_code1"><?php _e( "Hex Code", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="hex_code1" id="hex_code1" class="hex_code" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field two-color">
                            <label for="two-color">
                                <input type="checkbox" id="two-color" name="two-color" value="1"/>
                                <?php _e( "Two-colors", __TEXTDOMAIN__ ); ?>
                            </label>
                        </div>

                        <div class="form-field ColorTwo">
                            <label for="pantone_color2"><?php _e( "Pantone Color 2", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="pantone_color2" id="pantone_color2" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field ColorTwo">
                            <label for="hex_code2"><?php _e( "Hex Code 2", __TEXTDOMAIN__ ); ?></label>
                            <input name="hex_code2" id="hex_code2" class="hex_code" type="text" value="" maxlength="28">
                        </div>
                        <?php do_action( 'promolizer_after_product_color_field' ); ?>
                        <?php submit_button( __( 'Add Product Color', __TEXTDOMAIN__ ), 'primary', 'izw_add_color') ?>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>