<?php

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    add_action( 'woocommerce_external_add_to_cart2', 'woocommerce_external_add_to_cart2', 1 );
    function woocommerce_external_add_to_cart2(){
        global $product;
        ?>
        <div class="variations_button">
            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
            <button type="submit" class="single_add_to_cart_button button alt">
                <?php echo $product->single_add_to_cart_text(); ?>
            </button>
        </div>
    <?php

}