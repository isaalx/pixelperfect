<?php
if (!defined('ABSPATH')) {
    exit;
}

function pp_accordion_style_register_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_register_style(
        'pp-accordion-style',
        $plugin_url . 'pp-accordion-custom.css',
        array(),
        '1.0.0'
    );
}
add_action('init', 'pp_accordion_style_register_assets');

function pp_accordion_style_render_shortcode($atts = array(), $content = null) {
    wp_enqueue_style('pp-accordion-style');

    if ($content === null) {
        return '';
    }

    return do_shortcode($content);
}
add_shortcode('wp-pixelperfect-accordion-style', 'pp_accordion_style_render_shortcode');
