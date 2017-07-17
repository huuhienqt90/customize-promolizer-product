<div id="col-container">
    <?php
    global $woocommerce;
    if($_SERVER['REQUEST_METHOD'] == 'POST' or ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['page'] == 'promolizers-settings' && $_GET['action'] == 'delete')){
        do_action('imprint_location_save_data');
    }
    if($_SERVER['REQUEST_METHOD'] == 'GET' and $_GET['action'] == 'edit'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            do_action('imprint_location_save_data');
        }
        include_once (__PROMOPATH__."/admin/edit/edit-imprint-location.php");
    }else{
        ?>
        <div id="col-right">
            <div class="col-wrap">
                <?php
                include_once (__PROMOPATH__."/admin/data/class-data-imprint-location.php");
                $data = new Data_Imprint_Location();
                $data->prepare_items();
                $data->display();
                ?>
            </div>
        </div>
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3><?php _e( "Add New Imprint Location", __TEXTDOMAIN__); ?></h3>
                    <form action="<?php echo add_query_arg( array('action'=>'add'), $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field( 'imprint_location', 'add_location' );?>
                        <?php do_action( 'promolizer_before_imprint_location_field' ); ?>
                        <div class="form-field">
                            <label for="location_name"><?php _e( "Name", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="location_name" id="location_name" type="text" value="">
                        </div>
                        <div class="form-field">
                            <label for="location_slug"><?php _e( "Slug", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="location_slug" id="location_slug" type="text" value="">
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
                        <?php do_action( 'promolizer_after_imprint_location_field' ); ?>
                        <?php submit_button( __( 'Add Imprint Location', __TEXTDOMAIN__ ), 'primary', 'izw_add_location') ?>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>