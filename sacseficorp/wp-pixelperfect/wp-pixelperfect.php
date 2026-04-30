<?php
/**
 * Plugin Name: PixelPerfect
 * Description: Plugin de PixelPerfect para WordPress.
 * Version: 1.4.0
 * Author: PixelPerfect, Isaac Gómez
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'wp-pixelperfect-calculator.php';
require_once plugin_dir_path(__FILE__) . 'wp-pixelperfect-card-menu.php';
require_once plugin_dir_path(__FILE__) . 'wp-pixelperfect-accordion-style.php';
require_once plugin_dir_path(__FILE__) . 'wp-pixelperfect-reporte-reclamos.php';
