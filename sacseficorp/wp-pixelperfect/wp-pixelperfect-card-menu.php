<?php
if (!defined('ABSPATH')) {
    exit;
}

function pp_card_register_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_register_style(
        'pp-card-style',
        $plugin_url . 'pp-card-custom.css',
        array(),
        '1.0.0'
    );

    wp_register_script(
        'pp-card-script',
        $plugin_url . 'pp-card-custom.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('init', 'pp_card_register_assets');

function pp_card_enqueue_editor_assets() {
    wp_enqueue_style('pp-card-style');
    wp_enqueue_script('pp-card-script');
}
add_action('elementor/editor/before_enqueue_styles', 'pp_card_enqueue_editor_assets');
add_action('elementor/editor/before_enqueue_scripts', 'pp_card_enqueue_editor_assets');
add_action('elementor/preview/enqueue_styles', 'pp_card_enqueue_editor_assets');
add_action('elementor/preview/enqueue_scripts', 'pp_card_enqueue_editor_assets');

function pp_card_render_shortcode($atts = array(), $content = null) {
    wp_enqueue_style('pp-card-style');
    wp_enqueue_script('pp-card-script');

    $atts = shortcode_atts(
        array(
            'data-marker-content' => '',
        ),
        $atts,
        'wp-pixelperfect-card-menu'
    );

    $marker_content = esc_attr($atts['data-marker-content']);

    ob_start();
    ?>
    <div class="pp-card-menu-container" data-marker-content="<?php echo $marker_content; ?>">
        <?php echo wp_kses_post($content); ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('wp-pixelperfect-card-menu', 'pp_card_render_shortcode');
