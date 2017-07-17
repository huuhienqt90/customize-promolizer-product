<div id="col-container">
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' or ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['page'] == 'promolizers-settings' && $_GET['action'] == 'delete')){
        do_action('imprint_type_save_data');
    }
    if($_SERVER['REQUEST_METHOD'] == 'GET' and $_GET['action'] == 'edit'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            do_action('imprint_type_save_data');
        }
        include_once (__PROMOPATH__."admin/edit/edit-imprint-type.php");
    }else{
        global $woocommerce;
        ?>
        <div id="col-right">
            <div class="col-wrap">
                <?php
                include_once (__PROMOPATH__."admin/data/class-data-imprint-type.php");
                $data = new Data_Imprint_Type();
                $data->prepare_items();
                $data->display();
                ?>
            </div>
        </div>
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3><?php _e( "Add New Imprint Type", __TEXTDOMAIN__); ?></h3>
                    <form action="<?php echo add_query_arg( array('action'=>'add'), $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field( 'product_imprint_type', 'add_imprint_type' );?>
                        <?php do_action( 'promolizer_before_add_imprint_type'); ?>
                        <div class="form-field">
                            <label for="type_name"><?php _e( "Name", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="type_name" id="type_name" type="text" value="">
                        </div>

                        <div class="form-field">
                            <label for="type_slug"><?php _e( "Slug", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="type_slug" id="type_slug" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field">
                            <label for="description"><?php _e( "Description", __TEXTDOMAIN__ ); ?>: </label>
                            <textarea name="description" id="description"></textarea>
                        </div>

                        <div class="form-field">
                            <label for="image"><?php _e( "Image", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="image" id="image" type="hidden" value="">
                            <div class="izwImageThubnail">
                                <img src="<?php echo $woocommerce->plugin_url(),"/assets/images/placeholder.png"; ?>">
                            </div>
                            <input type="file" name="_izw_image" />
                            <div class="clear"></div>
                        </div>

                        <div class="form-field">
                            <label for="setup_charge"><?php _e( "Default Setup Charge", __TEXTDOMAIN__ ); ?>: </label>
                            <?php echo get_woocommerce_currency_symbol()," "; ?><input name="setup_charge" id="setup_charge" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field">
                            <label for="max_color"><?php _e( "Max Colors", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="max_color" id="max_color" class="wid80" type="text" value="1" maxlength="28">
                        </div>

                        <div class="form-field">
                            <label for="max_location"><?php _e( "Max Locations", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="max_location" id="max_location" class="wid80" type="text" value="1" maxlength="28">
                        </div>
                        <?php do_action( 'promolizer_after_add_imprint_type'); ?>
                        <?php submit_button( __( 'Add Imprint Type', __TEXTDOMAIN__ ), 'primary', 'izw_add_imprint_type') ?>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>