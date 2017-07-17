<?php
$url = admin_url( 'edit.php?post_type=product&page='.$_REQUEST['page'] );
$current_title = apply_filters('promolizers_current_title', __('General Settings', __TEXTDOMAIN__));
$current_tab 		= ( empty( $_GET['tab'] ) ) ? 'general' : sanitize_text_field( urldecode( $_GET['tab'] ) );
$current_section 	= ( empty( $_REQUEST['section'] ) ) ? '' : sanitize_text_field( urldecode( $_REQUEST['section'] ) );
$tabs = array(
    array(
        'name' => 'general',
        'title' => 'General'
    ),
    array(
        'name' => 'product_color',
        'title' => 'Product Colors'
    ),
    array(
        'name' => 'imprint_type',
        'title' => 'Imprint Types'
    ),
    array(
        'name' => 'imprint_location',
        'title' => 'Imprint Locations'
    ),
    array(
        'name' => 'imprint_colors',
        'title' => 'Imprint/Thread Colors'
    ),
);
$tabs = apply_filters('promolizeres_tab_args', $tabs);
$sections = array();
$sections = apply_filters('promolizeres_'. $current_tab. '_tab_args', $sections);
?>
<div class="wrap izw_promolizers">
    <h2><?php echo $current_title; ?></h2>
    <?php if (sizeof($tabs) > 0) { ?>
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <?php do_action('promolizers_before_tabs') ?>
        <?php
            foreach ($tabs as $tab) {
                $class = 'nav-tab';
                if ($tab['name'] == $current_tab) {
                    $class .= ' nav-tab-active';
                }
                ?>
                <a href="<?php echo add_query_arg( array('tab' => $tab['name']), $url ); ?>"
                   class="<?php echo $class; ?>"><?php echo $tab['title']; ?></a>
            <?php
            }
        ?>
        <?php do_action('promolizers_after_tabs') ?>
    </h2>
    <?php } ?>
    <?php
        $messages = array();
        $messages = apply_filters('promolizers_message_args',$messages);
        if( sizeof( $messages ) > 0 ){
            foreach($messages as $message){
                echo $message['message'];
            }
        }
    ?>
    <?php do_action('promolizers_message'); ?>
    <?php if(sizeof($tabs)>0): ?>
        <ul>
        <?php do_action('promolizers_before_sections') ?>
        <?php
        foreach ($sections as $section) {
            $class = '';
            if ($section['name'] == $current_section) {
                $class .= 'current';
            }
            ?>
            <li><a href="<?php echo add_query_arg( array('tab' => $current_tab, 'section' =>$section['name']), $url ); ?>" class="<?php echo $class; ?>"><?php echo $section['title']; ?></a> | </li>
        <?php
        }
        ?>
        <?php do_action('promolizers_after_sections') ?>
        </ul>
    <?php endif; ?>
    <?php do_action( 'promolizers_tabs' ); ?>
    <?php do_action( 'promolizers_tab_'. $current_tab ); ?>
    <?php do_action( 'promolizers_'. $current_tab. "_sections" ); ?>
    <?php do_action( 'promolizers_'. $current_tab. "_section_". $current_section ); ?>
</div>
<?php global $woocommerce; ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#remove_image').click(function () {
            $('#image').val('');
            $('.izwImageThubnail img').attr('src','<?php echo $woocommerce->plugin_url(),"/assets/images/placeholder.png"; ?>');
            $('#remove_image').hide();
            $('#upload_image_button').show();
            return false;
        });
    });
</script>