<div id="col-container">
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' or ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['page'] == 'promolizers-settings' && $_GET['action'] == 'delete')){
        do_action('imprint_colors_save_data');
    }
    if($_SERVER['REQUEST_METHOD'] == 'GET' and $_GET['action'] == 'edit'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            do_action('imprint_colors_save_data');
        }
        include_once (__PROMOPATH__."/admin/edit/edit-imprint-thread-color.php");
    }else{
        ?>
        <div id="col-right">
            <div class="col-wrap">
                <?php
                include_once (__PROMOPATH__."/admin/data/class-data-imprint-thread-color.php");
                $data = new Data_Imprint_Color();
                $data->prepare_items();
                $data->display();
                ?>
            </div>
        </div>
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3><?php _e( "Add New Imprint/Thread Colors", __TEXTDOMAIN__); ?></h3>
                    <form action="<?php echo add_query_arg( array('action'=>'add'), $_SERVER['REQUEST_URI'] ); ?>" method="post">
                        <?php wp_nonce_field( 'imprint_color', 'add_imprint_color' );?>
                        <?php do_action( 'promolizer_before_imprint_color_field' ); ?>
                        <div class="form-field">
                            <label for="color_name"><?php _e( "Name", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="color_name" id="color_name" type="text" value="">
                        </div>

                        <div class="form-field">
                            <label for="color_slug"><?php _e( "Slug", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="color_slug" id="color_slug" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field">
                            <label for="pantone_color"><?php _e( "Pantone Color", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="pantone_color" id="pantone_color" type="text" value="" maxlength="28">
                        </div>

                        <div class="form-field">
                            <label for="hex_code"><?php _e( "Hex Code", __TEXTDOMAIN__ ); ?>: </label>
                            <input name="hex_code" id="hex_code" class="hex_code" type="text" value="" maxlength="28">
                        </div>
                        <?php do_action( 'promolizer_after_imprint_color_field' ); ?>

                        <?php submit_button( __( 'Add Imprint/Thread Colors', __TEXTDOMAIN__ ), 'primary', 'izw_add_imprint_color') ?>
                        <div class="clear"></div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>