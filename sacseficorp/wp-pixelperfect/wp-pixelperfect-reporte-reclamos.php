<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ---------------------------------------------------------------
   Defaults & option key
--------------------------------------------------------------- */
define('PP_RECLAMOS_OPTION', 'pp_reporte_reclamos_data');

function pp_reclamos_default_data() {
    return array(
        array(
            'label'               => 'Enero 2026',
            'color'               => '#E8891D',
            'total_reclamos'      => '0',
            'en_proceso'          => '0',
            'finalizados'         => '0',
            'tiempo_resolucion'   => '0',
            'indicador_eficiencia'=> '0%',
        ),
        array(
            'label'               => 'Febrero 2026',
            'color'               => '#6B2FA0',
            'total_reclamos'      => '0',
            'en_proceso'          => '0',
            'finalizados'         => '0',
            'tiempo_resolucion'   => '0',
            'indicador_eficiencia'=> '0%',
        ),
        array(
            'label'               => 'Marzo 2026',
            'color'               => '#3A8C3F',
            'total_reclamos'      => '0',
            'en_proceso'          => '0',
            'finalizados'         => '0',
            'tiempo_resolucion'   => '0',
            'indicador_eficiencia'=> '0%',
        ),
    );
}

function pp_reclamos_get_data() {
    $stored = get_option(PP_RECLAMOS_OPTION, array());
    $defaults = pp_reclamos_default_data();
    if (empty($stored) || !is_array($stored)) {
        return $defaults;
    }
    // Ensure we always have 3 panels
    $panels = array();
    for ($i = 0; $i < 3; $i++) {
        $panel = isset($stored[$i]) ? $stored[$i] : array();
        $panels[] = wp_parse_args($panel, $defaults[$i]);
    }
    return $panels;
}

/* ---------------------------------------------------------------
   Assets
--------------------------------------------------------------- */
function pp_reclamos_register_assets() {
    wp_register_style(
        'pp-reporte-reclamos-style',
        plugin_dir_url(__FILE__) . 'pp-reporte-reclamos.css',
        array(),
        '1.0.0'
    );
}
add_action('init', 'pp_reclamos_register_assets');

/* ---------------------------------------------------------------
   Shortcode [reporte-reclamos]
--------------------------------------------------------------- */
function pp_reclamos_render_shortcode($atts = array()) {
    wp_enqueue_style('pp-reporte-reclamos-style');

    $panels = pp_reclamos_get_data();
    $uid    = wp_unique_id('pp-reclamos-');

    ob_start();
    ?>
    <div class="pp-reclamos-wrapper" id="<?php echo esc_attr($uid); ?>">
        <?php foreach ($panels as $index => $panel) :
            $panel_id  = esc_attr($uid . '-panel-' . $index);
            $btn_id    = esc_attr($uid . '-btn-' . $index);
            $color     = sanitize_hex_color($panel['color']);
        ?>
        <div class="pp-reclamos-accordion">
            <button
                class="pp-reclamos-header"
                id="<?php echo $btn_id; ?>"
                aria-expanded="false"
                aria-controls="<?php echo $panel_id; ?>"
                style="background-color: <?php echo esc_attr($color); ?>;"
            >
                <span class="pp-reclamos-header-label"><?php echo esc_html($panel['label']); ?></span>
                <span class="pp-reclamos-chevron" aria-hidden="true"></span>
            </button>
            <div
                class="pp-reclamos-body"
                id="<?php echo $panel_id; ?>"
                role="region"
                aria-labelledby="<?php echo $btn_id; ?>"
                hidden
            >
                <div class="pp-reclamos-stats">
                    <div class="pp-reclamos-stat">
                        <span class="pp-reclamos-stat-value"><?php echo esc_html($panel['total_reclamos']); ?></span>
                        <span class="pp-reclamos-stat-label">Total reclamos</span>
                    </div>
                    <div class="pp-reclamos-stat">
                        <span class="pp-reclamos-stat-value"><?php echo esc_html($panel['en_proceso']); ?></span>
                        <span class="pp-reclamos-stat-label">En proceso</span>
                    </div>
                    <div class="pp-reclamos-stat">
                        <span class="pp-reclamos-stat-value"><?php echo esc_html($panel['finalizados']); ?></span>
                        <span class="pp-reclamos-stat-label">Finalizados</span>
                    </div>
                    <div class="pp-reclamos-stat">
                        <span class="pp-reclamos-stat-value"><?php echo esc_html($panel['tiempo_resolucion']); ?></span>
                        <span class="pp-reclamos-stat-label">Tiempo de resolución (días y horas)</span>
                    </div>
                    <div class="pp-reclamos-stat">
                        <span class="pp-reclamos-stat-value"><?php echo esc_html($panel['indicador_eficiencia']); ?></span>
                        <span class="pp-reclamos-stat-label">Indicador de eficiencia</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <script>
    (function() {
        var wrapper = document.getElementById('<?php echo esc_js($uid); ?>');
        if (!wrapper) return;
        wrapper.querySelectorAll('.pp-reclamos-header').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var expanded = this.getAttribute('aria-expanded') === 'true';
                var bodyId   = this.getAttribute('aria-controls');
                var body     = document.getElementById(bodyId);
                if (!body) return;
                if (expanded) {
                    this.setAttribute('aria-expanded', 'false');
                    body.setAttribute('hidden', '');
                } else {
                    this.setAttribute('aria-expanded', 'true');
                    body.removeAttribute('hidden');
                }
            });
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('reporte-reclamos', 'pp_reclamos_render_shortcode');

/* ---------------------------------------------------------------
   Admin menu: Reportes > Reclamos
--------------------------------------------------------------- */
function pp_reclamos_add_admin_menu() {
    // Top-level menu "Reportes"
    add_menu_page(
        __('Reportes', 'pp-reclamos'),
        __('Reportes', 'pp-reclamos'),
        'manage_options',
        'pp-reportes',
        'pp_reclamos_admin_page',
        'dashicons-chart-bar',
        30
    );

    // Submenu "Reclamos" (same callback as parent so first item label differs)
    add_submenu_page(
        'pp-reportes',
        __('Reclamos', 'pp-reclamos'),
        __('Reclamos', 'pp-reclamos'),
        'manage_options',
        'pp-reportes',
        'pp_reclamos_admin_page'
    );
}
add_action('admin_menu', 'pp_reclamos_add_admin_menu');

/* ---------------------------------------------------------------
   Save handler
--------------------------------------------------------------- */
function pp_reclamos_save_data() {
    if (!isset($_POST['pp_reclamos_nonce'])) {
        return;
    }
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pp_reclamos_nonce'])), 'pp_reclamos_save')) {
        wp_die(__('Security check failed.', 'pp-reclamos'));
    }
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permission to do this.', 'pp-reclamos'));
    }

    $defaults = pp_reclamos_default_data();
    $panels   = array();

    for ($i = 0; $i < 3; $i++) {
        $raw = isset($_POST['pp_reclamos'][$i]) ? $_POST['pp_reclamos'][$i] : array();

        $label    = isset($raw['label'])    ? sanitize_text_field(wp_unslash($raw['label']))    : $defaults[$i]['label'];
        $color    = isset($raw['color'])    ? sanitize_hex_color(wp_unslash($raw['color']))     : $defaults[$i]['color'];
        $total    = isset($raw['total_reclamos'])       ? sanitize_text_field(wp_unslash($raw['total_reclamos']))       : '0';
        $proceso  = isset($raw['en_proceso'])           ? sanitize_text_field(wp_unslash($raw['en_proceso']))           : '0';
        $final    = isset($raw['finalizados'])          ? sanitize_text_field(wp_unslash($raw['finalizados']))          : '0';
        $tiempo   = isset($raw['tiempo_resolucion'])    ? sanitize_text_field(wp_unslash($raw['tiempo_resolucion']))    : '0';
        $eficiencia = isset($raw['indicador_eficiencia']) ? sanitize_text_field(wp_unslash($raw['indicador_eficiencia'])) : '0%';

        $panels[] = array(
            'label'               => $label,
            'color'               => $color ? $color : $defaults[$i]['color'],
            'total_reclamos'      => $total,
            'en_proceso'          => $proceso,
            'finalizados'         => $final,
            'tiempo_resolucion'   => $tiempo,
            'indicador_eficiencia'=> $eficiencia,
        );
    }

    update_option(PP_RECLAMOS_OPTION, $panels);
}
add_action('admin_post_pp_reclamos_save', 'pp_reclamos_save_data');

/* ---------------------------------------------------------------
   Admin page (edit form)
--------------------------------------------------------------- */
function pp_reclamos_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $saved = false;
    if (isset($_GET['pp_saved']) && $_GET['pp_saved'] === '1') {
        $saved = true;
    }

    $panels = pp_reclamos_get_data();
    $action_url = esc_url(admin_url('admin-post.php'));
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Reporte de Reclamos', 'pp-reclamos'); ?></h1>
        <p><?php esc_html_e('Edita los valores que se muestran en el shortcode [reporte-reclamos].', 'pp-reclamos'); ?></p>

        <?php if ($saved) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e('Los datos han sido guardados correctamente.', 'pp-reclamos'); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo $action_url; ?>">
            <?php wp_nonce_field('pp_reclamos_save', 'pp_reclamos_nonce'); ?>
            <input type="hidden" name="action" value="pp_reclamos_save">
            <input type="hidden" name="_wp_http_referer" value="<?php echo esc_attr(wp_unslash($_SERVER['REQUEST_URI'] ?? '')); ?>">

            <?php foreach ($panels as $i => $panel) :
                $color = sanitize_hex_color($panel['color']);
            ?>
            <div style="background:#fff; border:1px solid #c3c4c7; border-left:4px solid <?php echo esc_attr($color); ?>; border-radius:4px; padding:20px 24px; margin-bottom:24px;">
                <h2 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">
                    <?php echo esc_html(sprintf(__('Panel %d', 'pp-reclamos'), $i + 1)); ?>
                </h2>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="pp_reclamos_label_<?php echo $i; ?>"><?php esc_html_e('Título (mes/año)', 'pp-reclamos'); ?></label></th>
                        <td>
                            <input
                                type="text"
                                id="pp_reclamos_label_<?php echo $i; ?>"
                                name="pp_reclamos[<?php echo $i; ?>][label]"
                                value="<?php echo esc_attr($panel['label']); ?>"
                                class="regular-text"
                                required
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="pp_reclamos_color_<?php echo $i; ?>"><?php esc_html_e('Color del encabezado', 'pp-reclamos'); ?></label></th>
                        <td>
                            <input
                                type="color"
                                id="pp_reclamos_color_<?php echo $i; ?>"
                                name="pp_reclamos[<?php echo $i; ?>][color]"
                                value="<?php echo esc_attr($color); ?>"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="pp_reclamos_total_<?php echo $i; ?>"><?php esc_html_e('Total reclamos', 'pp-reclamos'); ?></label></th>
                        <td>
                            <input
                                type="text"
                                id="pp_reclamos_total_<?php echo $i; ?>"
                                name="pp_reclamos[<?php echo $i; ?>][total_reclamos]"
                                value="<?php echo esc_attr($panel['total_reclamos']); ?>"
                                class="small-text"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="pp_reclamos_proceso_<?php echo $i; ?>"><?php esc_html_e('En proceso', 'pp-reclamos'); ?></label></th>
                        <td>
                            <input
                                type="text"
                                id="pp_reclamos_proceso_<?php echo $i; ?>"
                                name="pp_reclamos[<?php echo $i; ?>][en_proceso]"
                                value="<?php echo esc_attr($panel['en_proceso']); ?>"
                                class="small-text"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="pp_reclamos_final_<?php echo $i; ?>"><?php esc_html_e('Finalizados', 'pp-reclamos'); ?></label></th>
                        <td>
                            <input
                                type="text"
                                id="pp_reclamos_final_<?php echo $i; ?>"
                                name="pp_reclamos[<?php echo $i; ?>][finalizados]"
                                value="<?php echo esc_attr($panel['finalizados']); ?>"
                                class="small-text"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="pp_reclamos_tiempo_<?php echo $i; ?>"><?php esc_html_e('Tiempo de resolución (días y horas)', 'pp-reclamos'); ?></label></th>
                        <td>
                            <input
                                type="text"
                                id="pp_reclamos_tiempo_<?php echo $i; ?>"
                                name="pp_reclamos[<?php echo $i; ?>][tiempo_resolucion]"
                                value="<?php echo esc_attr($panel['tiempo_resolucion']); ?>"
                                class="small-text"
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="pp_reclamos_eficiencia_<?php echo $i; ?>"><?php esc_html_e('Indicador de eficiencia', 'pp-reclamos'); ?></label></th>
                        <td>
                            <input
                                type="text"
                                id="pp_reclamos_eficiencia_<?php echo $i; ?>"
                                name="pp_reclamos[<?php echo $i; ?>][indicador_eficiencia]"
                                value="<?php echo esc_attr($panel['indicador_eficiencia']); ?>"
                                class="small-text"
                            >
                            <p class="description"><?php esc_html_e('Puede incluir el símbolo %, ej: 80%', 'pp-reclamos'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            <?php endforeach; ?>

            <?php submit_button(__('Guardar cambios', 'pp-reclamos')); ?>
        </form>
    </div>
    <?php
}

/* ---------------------------------------------------------------
   Redirect back to admin page with success notice after save
--------------------------------------------------------------- */
function pp_reclamos_save_redirect() {
    if (!isset($_POST['pp_reclamos_nonce'])) {
        return;
    }
    // Nonce already verified in pp_reclamos_save_data via admin_post hook,
    // but we need a redirect after save. We handle it here.
}

// Override the admin_post hook to redirect after save
remove_action('admin_post_pp_reclamos_save', 'pp_reclamos_save_data');

function pp_reclamos_save_and_redirect() {
    if (!isset($_POST['pp_reclamos_nonce'])) {
        wp_safe_redirect(admin_url('admin.php?page=pp-reportes'));
        exit;
    }
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pp_reclamos_nonce'])), 'pp_reclamos_save')) {
        wp_die(esc_html__('Security check failed.', 'pp-reclamos'));
    }
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to do this.', 'pp-reclamos'));
    }

    $defaults = pp_reclamos_default_data();
    $panels   = array();

    for ($i = 0; $i < 3; $i++) {
        // phpcs:disable WordPress.Security.NonceVerification -- already verified above
        $raw = isset($_POST['pp_reclamos'][$i]) ? $_POST['pp_reclamos'][$i] : array();
        // phpcs:enable

        $label      = isset($raw['label'])                ? sanitize_text_field(wp_unslash($raw['label']))                : $defaults[$i]['label'];
        $color      = isset($raw['color'])                ? sanitize_hex_color(wp_unslash($raw['color']))                 : $defaults[$i]['color'];
        $total      = isset($raw['total_reclamos'])       ? sanitize_text_field(wp_unslash($raw['total_reclamos']))       : '0';
        $proceso    = isset($raw['en_proceso'])           ? sanitize_text_field(wp_unslash($raw['en_proceso']))           : '0';
        $final      = isset($raw['finalizados'])          ? sanitize_text_field(wp_unslash($raw['finalizados']))          : '0';
        $tiempo     = isset($raw['tiempo_resolucion'])    ? sanitize_text_field(wp_unslash($raw['tiempo_resolucion']))    : '0';
        $eficiencia = isset($raw['indicador_eficiencia']) ? sanitize_text_field(wp_unslash($raw['indicador_eficiencia'])) : '0%';

        $panels[] = array(
            'label'               => $label,
            'color'               => $color ? $color : $defaults[$i]['color'],
            'total_reclamos'      => $total,
            'en_proceso'          => $proceso,
            'finalizados'         => $final,
            'tiempo_resolucion'   => $tiempo,
            'indicador_eficiencia'=> $eficiencia,
        );
    }

    update_option(PP_RECLAMOS_OPTION, $panels);
    wp_safe_redirect(admin_url('admin.php?page=pp-reportes&pp_saved=1'));
    exit;
}
add_action('admin_post_pp_reclamos_save', 'pp_reclamos_save_and_redirect');
