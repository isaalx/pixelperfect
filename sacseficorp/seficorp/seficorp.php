<?php
/*
 * Plugin Name:       SEFICORP
 * Plugin URI:        https://pixelperfectlab.com/
 * Description:       Administrador de documentos SEFICORP
 * Version:           2.0.1
 * Author:            Gerson Hernández, Alex Gómez
 * License:           GPL v2 or later
 * Text Domain:       seficorp
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Registrar el Custom Post Type ORIGINAL
function ce_register_custom_post_type() {
    $args = array(
        'labels' => array(
            'name'          => 'SEFICORP',
            'singular_name' => 'SEFICORP',
        ),
        'public'        => true,
        'menu_icon'     => 'dashicons-media-archive',
        'supports'      => array('title'),
        'show_in_rest'  => true,
    );
    register_post_type('documento', $args);
}
add_action('init', 'ce_register_custom_post_type');

// Registrar el Custom Post Type NUEVO
function ce_register_custom_post_type_2() {
    $args = array(
        'labels' => array(
            'name'          => 'SEFICORP 2',
            'singular_name' => 'SEFICORP 2',
        ),
        'public'        => true,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => array('title'),
        'show_in_rest'  => true,
    );
    register_post_type('documento2', $args);
}
add_action('init', 'ce_register_custom_post_type_2');

// Agregar Metaboxes ORIGINALES
function ce_add_meta_boxes() {
    add_meta_box(
        'ce_icon_meta',
        'Icono del Documento',
        'ce_icon_meta_callback',
        'documento',
        'side'
    );

    add_meta_box(
        'ce_files_meta',
        'Archivos del Documento',
        'ce_files_meta_callback',
        'documento',
        'normal'
    );
}
add_action('add_meta_boxes', 'ce_add_meta_boxes');

// Agregar Metaboxes NUEVOS
function ce_add_meta_boxes_2() {
    add_meta_box(
        'ce_icon_meta_2',
        'Icono del Documento',
        'ce_icon_meta_callback_2',
        'documento2',
        'side'
    );

    add_meta_box(
        'ce_files_meta_2',
        'Archivos del Documento',
        'ce_files_meta_callback_2',
        'documento2',
        'normal'
    );
}
add_action('add_meta_boxes', 'ce_add_meta_boxes_2');

// Callback del Metabox de Icono ORIGINAL
function ce_icon_meta_callback($post) {
    $icon = get_post_meta($post->ID, '_ce_icon', true);
    echo '<div>
            <input type="hidden" id="ce_icon" name="ce_icon" value="' . esc_attr($icon) . '" />
            <button class="button" id="ce_icon_button">Seleccionar Imagen</button>
            <div id="ce_icon_preview" style="margin-top:10px;">
                ' . ($icon ? '<img src="' . esc_url($icon) . '" style="max-width:100%; height:auto;" />' : '') . '
            </div>
          </div>';
}

// Callback del Metabox de Icono NUEVO
function ce_icon_meta_callback_2($post) {
    $icon = get_post_meta($post->ID, '_ce_icon_2', true);
    echo '<div>
            <input type="hidden" id="ce_icon_2" name="ce_icon_2" value="' . esc_attr($icon) . '" />
            <button class="button" id="ce_icon_button_2">Seleccionar Imagen</button>
            <div id="ce_icon_preview_2" style="margin-top:10px;">
                ' . ($icon ? '<img src="' . esc_url($icon) . '" style="max-width:100%; height:auto;" />' : '') . '
            </div>
          </div>';
}

// Callback del Metabox de Archivos ORIGINAL
function ce_files_meta_callback($post) {
    $files = get_post_meta($post->ID, '_ce_files', true);
    $files = is_array($files) ? $files : [];

    echo '<div id="ce_files_list">';
    foreach ($files as $index => $file) {
    $file_url  = isset($file['url']) ? $file['url'] : '';
    $file_name = isset($file['name']) ? $file['name'] : '';
    $file_type = isset($file['type']) ? $file['type'] : 'doc'; // por defecto doc

    echo '<div class="ce-file-item" style="margin-bottom:10px;">';

    echo '<input type="text" name="ce_files[' . $index . '][name]" value="' . esc_attr($file_name) . '" placeholder="Nombre" style="width:80%; margin-bottom:6px;" />';

    echo '<div style="display:flex; gap:10px; align-items:center; margin-bottom:6px;">';
        echo '<select name="ce_files[' . $index . '][type]" class="ce_type_select" data-index="' . $index . '">';
            echo '<option value="doc"' . selected($file_type, 'doc', false) . '>Documento</option>';
            echo '<option value="link"' . selected($file_type, 'link', false) . '>Link</option>';
        echo '</select>';

        echo '<input type="text" class="ce_url_input" name="ce_files[' . $index . '][url]" value="' . esc_attr($file_url) . '" placeholder="URL o #ancla" style="width:60%" />';
    echo '</div>';

    echo '<button class="button ce_file_button" data-index="' . $index . '">Seleccionar Archivo</button>';

    if (!empty($file_url)) {
        echo '<a href="' . esc_url($file_url) . '" target="_blank" style="margin-left:10px;">Ver</a>';
    }

    echo '<button class="button remove-file" data-index="' . $index . '" style="margin-left:10px;">Eliminar</button>';
    echo '</div>';
}

    echo '</div>';
    echo '<p style="margin:10px 0 0;">';
    $force_modal = get_post_meta($post->ID, '_ce_force_modal', true) === '1';
    echo '<label><input type="checkbox" name="ce_force_modal" value="1" ' . checked($force_modal, true, false) . ' /> Forzar modal aunque solo exista 1 archivo</label>';
    echo '</p>';
    echo '<button class="button" id="add_file_button">Agregar Otro Archivo</button>';
}

// Callback del Metabox de Archivos NUEVO
function ce_files_meta_callback_2($post) {
    $files = get_post_meta($post->ID, '_ce_files_2', true);
    $files = is_array($files) ? $files : [];

    echo '<div id="ce_files_list_2">';
    foreach ($files as $index => $file) {
        $file_url = isset($file['url']) ? esc_url($file['url']) : '';
        $file_name = isset($file['name']) ? esc_attr($file['name']) : '';
        echo '<div class="ce-file-item-2" style="margin-bottom:10px;">
                <input type="text" name="ce_files_2[' . $index . '][name]" value="' . $file_name . '" placeholder="Nombre del archivo" style="width:80%" />
                <input type="hidden" name="ce_files_2[' . $index . '][url]" value="' . $file_url . '" />
                <button class="button ce_file_button_2" data-index="' . $index . '">Seleccionar Archivo</button>
                ' . ($file_url ? '<a href="' . $file_url . '" target="_blank" style="margin-left:10px;">Ver archivo</a>' : '') . '
                <button class="button remove-file-2" data-index="' . $index . '" style="margin-left:10px;">Eliminar</button>
              </div>';
    }
    echo '</div>';
    echo '<p style="margin:10px 0 0;">';
    $force_modal = get_post_meta($post->ID, '_ce_force_modal_2', true) === '1';
    echo '<label><input type="checkbox" name="ce_force_modal_2" value="1" ' . checked($force_modal, true, false) . ' /> Forzar modal aunque solo exista 1 archivo</label>';
    echo '</p>';
    echo '<button class="button" id="add_file_button_2">Agregar Otro Archivo</button>';
}

// Guardar Metadatos ORIGINALES
function ce_save_meta_data($post_id) {
    // Guardar Icono
    if (array_key_exists('ce_icon', $_POST)) {
        update_post_meta($post_id, '_ce_icon', sanitize_text_field($_POST['ce_icon']));
    }

    // Guardar Archivos
    if (array_key_exists('ce_files', $_POST) && is_array($_POST['ce_files'])) {
        $clean = [];
        foreach ($_POST['ce_files'] as $item) {
            $name = isset($item['name']) ? sanitize_text_field($item['name']) : '';
            $type = (isset($item['type']) && $item['type'] === 'link') ? 'link' : 'doc';
            $url  = isset($item['url']) ? trim($item['url']) : '';

            // permite #ancla
            if ($url !== '' && $url[0] === '#') {
                $url = sanitize_text_field($url);
            } else {
                $url = esc_url_raw($url);
            }

            if ($name !== '' || $url !== '') {
                $clean[] = ['name' => $name, 'type' => $type, 'url' => $url];
            }
        }
        update_post_meta($post_id, '_ce_files', $clean);
    }

    $force_modal = (isset($_POST['ce_force_modal']) && $_POST['ce_force_modal'] === '1') ? '1' : '0';
    update_post_meta($post_id, '_ce_force_modal', $force_modal);
}
add_action('save_post', 'ce_save_meta_data');

// Guardar Metadatos NUEVOS
function ce_save_meta_data_2($post_id) {
    // Guardar Icono 2
    if (array_key_exists('ce_icon_2', $_POST)) {
        update_post_meta($post_id, '_ce_icon_2', sanitize_text_field($_POST['ce_icon_2']));
    }

    // Guardar Archivos 2
    if (array_key_exists('ce_files_2', $_POST)) {
        update_post_meta($post_id, '_ce_files_2', $_POST['ce_files_2']);
    }

    $force_modal = (isset($_POST['ce_force_modal_2']) && $_POST['ce_force_modal_2'] === '1') ? '1' : '0';
    update_post_meta($post_id, '_ce_force_modal_2', $force_modal);
}
add_action('save_post', 'ce_save_meta_data_2');

// Cargar scripts de Media Uploader
function ce_enqueue_admin_scripts($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script('ce-admin-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), null, true);
        wp_enqueue_script('ce-admin-order-script', plugin_dir_url(__FILE__) . 'order.js', array('jquery', 'jquery-ui-sortable'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'ce_enqueue_admin_scripts');

// Función para generar un ID único
function generateUniqueId($length = 10) {
    do {
        $id = random_int(pow(10, $length-1), pow(10, $length)-1);
    } while (idExists($id));

    return $id;
}

// Simula la verificación de si el ID existe
function idExists($id) {
    return false;
}

// Shortcode ORIGINAL para Mostrar Documentos
function ce_display_documents() {
    $args = array(
        'post_type' => 'documento',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<div class="gobierno-corporativo">';
        $output .= '<div class="container">';
        $output .= '<div class="row gx-4">';

        while ($query->have_posts()) {
            $query->the_post();

            $icon = get_post_meta(get_the_ID(), '_ce_icon', true);
            $files = get_post_meta(get_the_ID(), '_ce_files', true);
            $force_modal = get_post_meta(get_the_ID(), '_ce_force_modal', true) === '1';

            $output .= '<div class="col-12 col-md-4 mt-5 mb-5">';
            $output .= '<div class="item">';

            if ($icon) {
                $output .= '<img class="item-icon" src="' . esc_url($icon) . '" alt="seficorp" />';
            }

            if ($files) {
                $id = generateUniqueId(10);
                $totalFiles = count($files);
                if ($totalFiles > 1 || $force_modal) {
                    $file = $files[0];
                    $output .= '<h2 class="mb-0">' . esc_html(get_the_title()) . '</h2>';


                    $url  = isset($file['url']) ? $file['url'] : '';
                    $type = isset($file['type']) ? $file['type'] : 'doc';

                    $href   = ($url !== '' && $url[0] === '#') ? esc_attr($url) : esc_url($url);
                    $target = ($type === 'doc') ? ' target="_blank"' : '';

                    $output .= '<a href="#" class="item-link justify-content-end open-modal" data-modal="' . $id . '">';

                        $output .= '<div class="item-open">';
                            $output .= '<span>Abrir</span>';
                            $output .= '<img src="https://sacseficorp.com/wp-content/uploads/2025/09/PDFARCHIVO.png" alt="seficorp" />';
                        $output .= '</div>';
                    $output .= '</a>';

                    $output .= '<div class="modal-gc" id="' . $id . '">';
                        $output .= '<div class="modal-content-gc">';
                            $output .= '<span class="close-gc">x</span>';
                            $output .= '<div class="gobierno-corporativo">';
                                $output .= '<div class="container">';
                                    $output .= '<div class="row gx-4">';
                                        foreach ($files as $file) {
                                            $output .= '<div class="col-12 col-lg-4 mt-5 mb-5">';
                                                $output .= '<div class="item item-gc">';
                                                    $output .= '<img class="item-icon" src="' . esc_url($icon) . '" alt="seficorp" />';
                                                    $output .= '<h2 class="mb-0">' . esc_html($file['name']) . '</h2>';
                                                    $url  = isset($file['url']) ? $file['url'] : '';
                                                    $type = isset($file['type']) ? $file['type'] : 'doc';

                                                    $href   = ($url !== '' && $url[0] === '#') ? esc_attr($url) : esc_url($url);
                                                    $target = ($type === 'doc') ? ' target="_blank"' : '';

                                                    $output .= '<a href="' . $href . '" class="item-link justify-content-end"' . $target . '>';
                                                        $output .= '<div class="item-open">';
                                                            $output .= '<span>Abrir</span>';
                                                            $output .= '<img src="https://sacseficorp.com/wp-content/uploads/2025/09/PDFARCHIVO.png" alt="seficorp" />';
                                                        $output .= '</div>';
                                                    $output .= '</a>';
                                                $output .= '</div>';
                                            $output .= '</div>';
                                        }
                                    $output .= '</div>';
                                $output .= '</div>';
                            $output .= '</div>';
                        $output .= '</div>';
                    $output .= '</div>';
                } else {
                    $file = $files[0];

$url  = isset($file['url']) ? $file['url'] : '';
$type = isset($file['type']) ? $file['type'] : 'doc';

$href   = ($url !== '' && $url[0] === '#') ? esc_attr($url) : esc_url($url);
$target = ($type === 'doc') ? ' target="_blank"' : '';

$output .= '<h2 class="mb-0">' . esc_html($file['name']) . '</h2>';
$output .= '<a href="' . $href . '" class="item-link justify-content-end"' . $target . '>';

                        $output .= '<div class="item-open">';
                            $output .= '<span>Abrir</span>';
                            $output .= '<img src="https://sacseficorp.com/wp-content/uploads/2025/09/PDFARCHIVO.png" alt="seficorp" />';
                        $output .= '</div>';
                    $output .= '</a>';
                }
            }
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    } else {
        $output = '<p>No se encontraron documentos.</p>';
    }

    wp_reset_postdata();
    return $output;
}
add_shortcode('display_documents', 'ce_display_documents');

// Shortcode NUEVO para Mostrar Documentos 2
function ce_display_documents_2() {
    $args = array(
        'post_type' => 'documento2',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<div class="gobierno-corporativo">';
        $output .= '<div class="container">';
        $output .= '<div class="row gx-4">';

        while ($query->have_posts()) {
            $query->the_post();

            $icon = get_post_meta(get_the_ID(), '_ce_icon_2', true);
            $files = get_post_meta(get_the_ID(), '_ce_files_2', true);
            $force_modal = get_post_meta(get_the_ID(), '_ce_force_modal_2', true) === '1';

            $output .= '<div class="col-12 col-md-4 mt-5 mb-5">';
            $output .= '<div class="item">';

            if ($icon) {
                $output .= '<img class="item-icon" src="' . esc_url($icon) . '" alt="seficorp" />';
            }
               // $output .= '<h3 class="titulo-doc">' . get_the_title() . '</h3>';
            if ($files) {
                $id = generateUniqueId(10);
                $totalFiles = count($files);
                if ($totalFiles > 1 || $force_modal) {
                    $file = $files[0];
                    $output .= '<h2 class="mb-0">' . esc_html(get_the_title()) . '</h2>';


                    $url  = isset($file['url']) ? $file['url'] : '';
                    $type = isset($file['type']) ? $file['type'] : 'doc';

                    $href   = ($url !== '' && $url[0] === '#') ? esc_attr($url) : esc_url($url);
                    $target = ($type === 'doc') ? ' target="_blank"' : '';

                    $output .= '<a href="#" class="item-link justify-content-end open-modal" data-modal="' . $id . '">';

                        $output .= '<div class="item-open">';
                            $output .= '<span>Abrir</span>';
                            $output .= '<img src="https://sacseficorp.com/wp-content/uploads/2025/09/PDFARCHIVO.png" alt="seficorp" />';
                        $output .= '</div>';
                    $output .= '</a>';

                    $output .= '<div class="modal-gc" id="' . $id . '">';
                        $output .= '<div class="modal-content-gc">';
                            $output .= '<span class="close-gc">x</span>';
                            $output .= '<div class="gobierno-corporativo">';
                                $output .= '<div class="container">';
                                    $output .= '<div class="row gx-4">';
                                        foreach ($files as $file) {
                                            $output .= '<div class="col-12 col-lg-4 mt-5 mb-5">';
                                                $output .= '<div class="item item-gc">';
                                                    $output .= '<img class="item-icon" src="' . esc_url($icon) . '" alt="seficorp" />';
                                                    $output .= '<h2 class="mb-0">' . esc_html($file['name']) . '</h2>';
                                                    $url  = isset($file['url']) ? $file['url'] : '';
$type = isset($file['type']) ? $file['type'] : 'doc';

$href   = ($url !== '' && $url[0] === '#') ? esc_attr($url) : esc_url($url);
$target = ($type === 'doc') ? ' target="_blank"' : '';

$output .= '<a href="' . $href . '" class="item-link justify-content-end"' . $target . '>';
                                                        $output .= '<div class="item-open">';
                                                            $output .= '<span>Abrir</span>';
                                                            $output .= '<img src="https://sacseficorp.com/wp-content/uploads/2025/09/PDFARCHIVO.png" alt="seficorp" />';
                                                        $output .= '</div>';
                                                    $output .= '</a>';
                                                $output .= '</div>';
                                            $output .= '</div>';
                                        }
                                    $output .= '</div>';
                                $output .= '</div>';
                            $output .= '</div>';
                        $output .= '</div>';
                    $output .= '</div>';
                } else {
                    $file = $files[0];

$url  = isset($file['url']) ? $file['url'] : '';
$type = isset($file['type']) ? $file['type'] : 'doc';

$href   = ($url !== '' && $url[0] === '#') ? esc_attr($url) : esc_url($url);
$target = ($type === 'doc') ? ' target="_blank"' : '';

$output .= '<h2 class="mb-0">' . esc_html($file['name']) . '</h2>';
$output .= '<a href="' . $href . '" class="item-link justify-content-end"' . $target . '>';

                        $output .= '<div class="item-open">';
                            $output .= '<span>Abrir</span>';
                            $output .= '<img src="https://sacseficorp.com/wp-content/uploads/2025/09/PDFARCHIVO.png" alt="seficorp" />';
                        $output .= '</div>';
                    $output .= '</a>';
                }
            }
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    } else {
        $output = '<p>No se encontraron documentos.</p>';
    }

    wp_reset_postdata();
    return $output;
}
add_shortcode('display_documents2', 'ce_display_documents_2');

// Soporte para orden personalizado ORIGINAL
function ce_add_custom_order_meta() {
    add_post_type_support('documento', 'page-attributes');
}
add_action('init', 'ce_add_custom_order_meta');

// Soporte para orden personalizado NUEVO
function ce_add_custom_order_meta_2() {
    add_post_type_support('documento2', 'page-attributes');
}
add_action('init', 'ce_add_custom_order_meta_2');

// Columnas ordenables ORIGINAL
function ce_add_sortable_columns($columns) {
    $columns['menu_order'] = 'Orden';
    return $columns;
}
add_filter('manage_edit-documento_columns', 'ce_add_sortable_columns');

// Columnas ordenables NUEVO
function ce_add_sortable_columns_2($columns) {
    $columns['menu_order'] = 'Orden';
    return $columns;
}
add_filter('manage_edit-documento2_columns', 'ce_add_sortable_columns_2');

// Contenido de columnas ORIGINAL
function ce_custom_column_content($column, $post_id) {
    if ($column === 'menu_order') {
        echo get_post_field('menu_order', $post_id);
    }
}
add_action('manage_documento_posts_custom_column', 'ce_custom_column_content', 10, 2);

// Contenido de columnas NUEVO
function ce_custom_column_content_2($column, $post_id) {
    if ($column === 'menu_order') {
        echo get_post_field('menu_order', $post_id);
    }
}
add_action('manage_documento2_posts_custom_column', 'ce_custom_column_content_2', 10, 2);

// Actualizar orden de posts ORIGINAL
function ce_update_post_order() {
    if (isset($_POST['order']) && is_array($_POST['order'])) {
        foreach ($_POST['order'] as $item) {
            wp_update_post(array(
                'ID' => intval($item['id']),
                'menu_order' => intval($item['position'])
            ));
        }
    }
    wp_die();
}
add_action('wp_ajax_ce_update_post_order', 'ce_update_post_order');

// Actualizar orden de posts NUEVO
function ce_update_post_order_2() {
    if (isset($_POST['order']) && is_array($_POST['order'])) {
        foreach ($_POST['order'] as $item) {
            wp_update_post(array(
                'ID' => intval($item['id']),
                'menu_order' => intval($item['position'])
            ));
        }
    }
    wp_die();
}
add_action('wp_ajax_ce_update_post_order_2', 'ce_update_post_order_2');

// Hacer columnas ordenables ORIGINAL
function ce_make_order_column_sortable($columns) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
}
add_filter('manage_edit-documento_sortable_columns', 'ce_make_order_column_sortable');

// Hacer columnas ordenables NUEVO
function ce_make_order_column_sortable_2($columns) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
}
add_filter('manage_edit-documento2_sortable_columns', 'ce_make_order_column_sortable_2');

// Orderby menu_order ORIGINAL
function ce_orderby_menu_order($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if (isset($query->query['orderby']) && $query->query['orderby'] == 'menu_order') {
        $query->set('orderby', 'menu_order');
        $query->set('order', (isset($_GET['order']) && $_GET['order'] == 'desc') ? 'DESC' : 'ASC');
    }
}
add_action('pre_get_posts', 'ce_orderby_menu_order');

// Script frontend para modales
function ce_enqueue_frontend_scripts() {
    wp_enqueue_script(
        'ce-frontend',
        plugin_dir_url(__FILE__) . 'frontend.js',
        array('jquery'),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'ce_enqueue_frontend_scripts');
