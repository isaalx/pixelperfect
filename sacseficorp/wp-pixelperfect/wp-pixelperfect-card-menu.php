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
        '1.2.0'
    );

    wp_register_script(
        'pp-card-script',
        $plugin_url . 'pp-card-custom.js',
        array(),
        '1.2.0',
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
    $atts = shortcode_atts(
        array(
            'data-marker-content' => '',
        ),
        $atts,
        'wp-pixelperfect-card-menu'
    );

    $marker_content = sanitize_text_field($atts['data-marker-content']);
    if ($marker_content === '') {
        return '';
    }

    wp_enqueue_style('pp-card-style');
    wp_enqueue_script('pp-card-script');

    $template_id = wp_unique_id('pp-card-popup-template-');
    $target_selector = '[data-marker-content="' . $marker_content . '"]';
    $config = array(
        'selector' => $target_selector,
        'contentId' => $template_id,
    );

    wp_add_inline_script(
        'pp-card-script',
        'window.ppCardMenuConfigs = window.ppCardMenuConfigs || []; window.ppCardMenuConfigs.push(' . wp_json_encode($config) . ');',
        'before'
    );

    $popup_content = do_shortcode($content);

    ob_start();
    ?>
    <div id="<?php echo esc_attr($template_id); ?>" class="pp-card-popup-template" hidden>
        <?php echo wp_kses_post($popup_content); ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('wp-pixelperfect-card-menu', 'pp_card_render_shortcode');
