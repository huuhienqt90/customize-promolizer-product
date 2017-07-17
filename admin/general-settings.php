<form name="" action="" method="post">
    <?php
    $currencySymbol = get_woocommerce_currency_symbol();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        do_action('general_update_setings');
    }
    $options = array(
        'izw_flat_rate_shipping'    => get_option( 'izw_flat_rate_shipping', true ),
        'izw_flat_rate_amount'      => get_option( 'izw_flat_rate_amount', true ),
        'izw_shipping_order_total'  => get_option( 'izw_shipping_order_total', true ),
        'izw_cart_size'             => get_option( 'izw_cart_size', true ),
        'izw_cart_imprint_location' => get_option( 'izw_cart_imprint_location', true ),
    );
    $options = apply_filters( 'promolizer_general_settings_arg', $options );
    extract($options);
    ?>
    <h3><?php _e( 'Shipping', __TEXTDOMAIN__); ?></h3>
    <?php do_action( 'promolizer_before_general_shipping_setting_field' ); ?>
    <label for="izw_flat_rate_shipping">
        <input name="izw_flat_rate_shipping" type="checkbox" id="izw_flat_rate_shipping" <?php if( $izw_flat_rate_shipping == '1'  && $izw_flat_rate_shipping ) echo 'checked="checked" ' ?> value="1">
        <?php _e( 'Flat-rate Shipping', __TEXTDOMAIN__ ); ?>
    </label>
    <p class="description"><?php _e( 'Check this if you want flat-rate shipping for all products.', __TEXTDOMAIN__ ); ?></p>
    <label for="izw_flat_rate_amount">
        Amount: <?php echo $currencySymbol; ?>
        <input type="text" name="izw_flat_rate_amount" id="izw_flat_rate_amount" value="<?php echo $izw_flat_rate_amount ? number_format($izw_flat_rate_amount,2, '.', '') : number_format('20',2, '.', ''); ?>">
    </label>
    <hr class="30percent hrleft" />
    <label for="izw_shipping_order_total">
        Waive shipping for orders over: <?php echo $currencySymbol; ?>
        <input type="text" name="izw_shipping_order_total" id="izw_shipping_order_total" value="<?php echo $izw_shipping_order_total ? number_format($izw_shipping_order_total,2, '.', '') : number_format('1000',2, '.', ''); ?>" />
    </label>
    <?php do_action( 'promolizer_after_general_shipping_setting_field' ); ?>
    <?php submit_button( 'Save Shipping', 'primary', 'izw_shipping_flat') ?>
    <div class="clear"></div>
</form>

<hr class="100percent hrleft" />
<form name="" action="" method="post">
    <h3><?php _e( 'Cart', __TEXTDOMAIN__ ); ?></h3>
    <?php
        $taxonomies = array(
            'product_cat',
        );

        $args = array(
            'orderby'           => 'name',
            'order'             => 'ASC',
            'hide_empty'        => false,
            'exclude'           => array(),
            'exclude_tree'      => array(),
            'include'           => array(),
            'number'            => '',
            'fields'            => 'all',
            'slug'              => '',
            'parent'            => '',
            'hierarchical'      => true,
            'child_of'          => 0,
            'get'               => '',
            'name__like'        => '',
            'description__like' => '',
            'pad_counts'        => false,
            'offset'            => '',
            'search'            => '',
            'cache_domain'      => 'core'
        );
        $terms = get_terms( $taxonomies, $args );
    ?>
    <?php do_action( 'promolizer_before_general_cart_setting_field' ); ?>
    <label for="izw_cart_size">
        Show size selectors for these categories:
    </label>
    <p>
        <?php
        if ( !empty( $terms ) && !is_wp_error( $terms ) ){
            echo '<select name="izw_cart_size[]" id="izw_cart_size" multiple >';
            foreach ( $terms as $term ) {
                $selected = '';
                if( is_array( $izw_cart_size ) && in_array( $term->term_id, $izw_cart_size ) ) $selected = ' selected="selected" ';
                ?>
                <option <?php echo $selected; ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name ; ?></option>
                <?php
            }
            echo '</select>';
        }
        ?>
    </p>
    <label for="izw_cart_imprint_location">
        Show imprint location selectors for these categories:
    </label>
    <p>
        <?php
            if ( !empty( $terms ) && !is_wp_error( $terms ) ){
                echo '<select name="izw_cart_imprint_location[]" id="izw_cart_imprint_location" multiple >';
                foreach ( $terms as $term ) {
                    $selected = '';
                    if( is_array( $izw_cart_imprint_location ) && in_array( $term->term_id, $izw_cart_imprint_location ) ) $selected = ' selected="selected" ';
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name ; ?></option>
                <?php
                }
                echo '</select>';
            }
        ?>
    </p>
    <?php do_action( 'promolizer_after_general_cart_setting_field' ); ?>
    <?php submit_button( 'Save Cart', 'primary', 'izw_cart') ?>
    <div class="clear"></div>
</form>
<form name="" action="" method="post">
    <?php
    $options = array(
        '_izw_amazon_s3_key'    => get_option( '_izw_amazon_s3_key' ),
        '_izw_amazon_s3_secret'      => get_option( '_izw_amazon_s3_secret' ),
        '_izw_amazon_s3_bucket'  => get_option( '_izw_amazon_s3_bucket' ),
    );
    $options = apply_filters( 'promolizer_general_amazon_settings_arg', $options );
    extract($options);
    ?>
    <h3><?php _e( 'Amazon S3 Settings', __TEXTDOMAIN__); ?></h3>
    <table class="form-table">
        <tbody>
        <?php do_action( 'promolizer_before_general_amazon_setting_field' ); ?>
            <tr>
                <th scope="row"><label for="_izw_amazon_s3_key"><?php _e( 'Amazon S3 Key', __TEXTDOMAIN__ ); ?></label></th>
                <td><input name="_izw_amazon_s3_key" id="_izw_amazon_s3_key" value="<?php echo !empty($options['_izw_amazon_s3_key']) ? $options['_izw_amazon_s3_key'] : ''; ?>" class="regular-text" type="text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="_izw_amazon_s3_secret"><?php _e( 'Amazon S3 Secret', __TEXTDOMAIN__ ); ?></label></th>
                <td><input name="_izw_amazon_s3_secret" id="_izw_amazon_s3_secret" value="<?php echo !empty($options['_izw_amazon_s3_secret']) ? $options['_izw_amazon_s3_secret'] : ''; ?>" class="regular-text" type="text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="_izw_amazon_s3_bucket"><?php _e( 'Amazon S3 Bucket Name', __TEXTDOMAIN__ ); ?></label></th>
                <td><input name="_izw_amazon_s3_bucket" id="_izw_amazon_s3_bucket" value="<?php echo !empty($options['_izw_amazon_s3_bucket']) ? $options['_izw_amazon_s3_bucket'] : 'promolizers'; ?>" class="regular-text" type="text"></td>
            </tr>
        <?php do_action( 'promolizer_after_general_amazon_setting_field' ); ?>
        </tbody>
    </table>
    <?php submit_button( 'Update', 'primary', 'izw_amazon_s3') ?>
    <div class="clear"></div>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#izw_cart_size").chosen({width: "100%"});
        $("#izw_cart_imprint_location").chosen({width: "100%"});
        if($("#izw_flat_rate_shipping").is(":checked")){
            $('label[for="izw_flat_rate_amount"]').show();
        }else{
            $('label[for="izw_flat_rate_amount"]').hide();
        }
        $("#izw_flat_rate_shipping").change(function(){
            if($(this).is(":checked")){
                $('label[for="izw_flat_rate_amount"]').show();
            }else{
                $('label[for="izw_flat_rate_amount"]').hide();
            }
        });
    });
</script>