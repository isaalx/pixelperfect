<?php
if (!defined('ABSPATH')) {
    exit;
}

function pp_grid_menu_register_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_register_style(
        'pp-grid-menu-style',
        $plugin_url . 'pp-grid-menu-custom.css',
        array(),
        '1.5.0'
    );
}
add_action('init', 'pp_grid_menu_register_assets');

function pp_grid_menu_enqueue_editor_assets() {
    wp_enqueue_style('pp-grid-menu-style');
}
add_action('elementor/editor/before_enqueue_styles', 'pp_grid_menu_enqueue_editor_assets');
add_action('elementor/editor/before_enqueue_scripts', 'pp_grid_menu_enqueue_editor_assets');
add_action('elementor/preview/enqueue_styles', 'pp_grid_menu_enqueue_editor_assets');
add_action('elementor/preview/enqueue_scripts', 'pp_grid_menu_enqueue_editor_assets');

function pp_grid_menu_get_menu_items($menu_name) {
    $menu_items = wp_get_nav_menu_items($menu_name);

    if (!$menu_items || is_wp_error($menu_items)) {
        return array();
    }

    return $menu_items;
}

function pp_grid_menu_render_shortcode($atts = array(), $content = null) {
    $atts = shortcode_atts(
        array(
            'menu' => '',
        ),
        $atts,
        'wp-pixelperfect-grid-menu'
    );

    $menu_name = sanitize_text_field($atts['menu']);
    if ($menu_name === '') {
        return '<p>' . esc_html__('No menu specified', 'wp-pixelperfect') . '</p>';
    }

    $menu_items = pp_grid_menu_get_menu_items($menu_name);
    if (empty($menu_items)) {
        return '<p>' . esc_html__('Menu not found or is empty', 'wp-pixelperfect') . '</p>';
    }

    wp_enqueue_style('pp-grid-menu-style');

    ob_start();
    ?>
    <div class="pp-grid-menu-container">
        <?php foreach ($menu_items as $item) :
            $title = !empty($item->attr_title) ? $item->attr_title : $item->title;
            $description = !empty($item->description) ? $item->description : '';
            $item_url = !empty($item->url) ? $item->url : '#';
            $item_target = !empty($item->target) ? $item->target : '';
            $item_rel = '';

            if ($item_target === '_blank') {
                $item_rel = 'noopener noreferrer';
            }

            $icon_url = '';

            // 1. Try values that may already be injected into the nav item object.
            if (!empty($item->popup_bg_image)) {
                $icon_url = is_numeric($item->popup_bg_image)
                    ? wp_get_attachment_url((int) $item->popup_bg_image)
                    : $item->popup_bg_image;
            } elseif (!empty($item->background_image)) {
                $icon_url = is_numeric($item->background_image)
                    ? wp_get_attachment_url((int) $item->background_image)
                    : $item->background_image;
            }

            // 2. Try custom meta keys saved on the menu item itself.
            if (empty($icon_url)) {
                foreach (array(
                    '_menu_item_popup_bg_image',
                    'menu_item_popup_bg_image',
                    'popup_bg_image',
                    '_menu_item_background_image',
                    '_menu_item_image_id',
                    '_nav_menu_item_image_id',
                    'menu-item-image',
                ) as $meta_key) {
                    $icon_meta = get_post_meta($item->ID, $meta_key, true);
                    if (empty($icon_meta)) {
                        continue;
                    }

                    $icon_url = is_numeric($icon_meta)
                        ? wp_get_attachment_url((int) $icon_meta)
                        : $icon_meta;

                    if (!empty($icon_url)) {
                        break;
                    }
                }
            }

            // 3. Fallback: featured image of linked page/post.
            if (empty($icon_url) && !empty($item->object_id)) {
                $thumbnail_id = get_post_thumbnail_id($item->object_id);
                if ($thumbnail_id) {
                    $icon_url = wp_get_attachment_url($thumbnail_id);
                }
            }

            $icon_url = !empty($icon_url) ? esc_url_raw($icon_url) : '';
            ?>
        <a
            class="pp-grid-menu-card"
            href="<?php echo esc_url($item_url); ?>"
            <?php if ($item_target !== '') : ?>target="<?php echo esc_attr($item_target); ?>"<?php endif; ?>
            <?php if ($item_rel !== '') : ?>rel="<?php echo esc_attr($item_rel); ?>"<?php endif; ?>
        >
            <div class="pp-grid-menu-card-top"
            <?php if (!empty($icon_url)) : ?>
            style="background-image: url('<?php echo esc_url($icon_url); ?>');"
            <?php endif; ?>
            >
                <h3 class="pp-grid-menu-card-title"><?php echo esc_html($title); ?></h3>
            </div>
            <div class="pp-grid-menu-card-bottom">
                <p class="pp-grid-menu-card-description"><?php echo esc_html($description); ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('wp-pixelperfect-grid-menu', 'pp_grid_menu_render_shortcode');
