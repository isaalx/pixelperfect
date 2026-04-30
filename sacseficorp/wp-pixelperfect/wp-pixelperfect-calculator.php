<?php
/**
 * Plugin Name: PixelPerfect Loan Calculator
 * Description: Calculadora de prestamos con shortcode para WordPress.
 * Version: 1.1.0
 * Author: PixelPerfect
 */

if (!defined('ABSPATH')) {
    exit;
}

function pp_calc_register_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_register_style(
        'pp-calc-style',
        $plugin_url . 'calc-style.css',
        array(),
      '1.1.0'
    );

    wp_register_script(
        'pp-calc-script',
        $plugin_url . 'calculator.js',
        array(),
      '1.1.0',
        true
    );
}
    add_action('init', 'pp_calc_register_assets');

    function pp_calc_enqueue_editor_assets() {
      wp_enqueue_style('pp-calc-style');
      wp_enqueue_script('pp-calc-script');
    }
    add_action('elementor/editor/before_enqueue_styles', 'pp_calc_enqueue_editor_assets');
    add_action('elementor/editor/before_enqueue_scripts', 'pp_calc_enqueue_editor_assets');
    add_action('elementor/preview/enqueue_styles', 'pp_calc_enqueue_editor_assets');
    add_action('elementor/preview/enqueue_scripts', 'pp_calc_enqueue_editor_assets');

function pp_calc_render_shortcode($atts = array(), $content = null) {
    wp_enqueue_style('pp-calc-style');
    wp_enqueue_script('pp-calc-script');

    ob_start();
    ?>
    <div class="loan-calculator-container pp-loan-calculator-container">
      <div class="loan-inputs">
        <h3>Ingresa los datos</h3>
        <form class="pp-loan-form" onsubmit="return false;">
          <label>Monto del prestamo ($)</label>
          <input type="number" data-field="amount" placeholder="Ej. 10000" min="1" />
          <small>Numero valido</small>

          <label>Tasa de interes anual (%)</label>
          <input type="number" data-field="rate" placeholder="Ej. 13" min="0" step="0.01" />
          <small>Numero valido</small>

          <label>Plazo (anos)</label>
          <input type="number" data-field="years" placeholder="Ej. 20" min="1" />
          <small>Numero valido</small>

          <button type="button" data-action="calculate" class="pp-calc-calculate-btn">Calcular</button>
        </form>
      </div>

      <div class="loan-results">
        <h3>Resultados</h3>
        <div class="result-section">
          <p class="large-font">Pago Mensual</p>
          <p><span data-output="monthly-payment" class="font-result">$0.00</span></p>
        </div>
        <div class="result-section">
          <p class="large-font">Total Intereses</p>
          <p><span data-output="total-interest" class="font-result">$0.00</span></p>
        </div>
        <div class="result-section">
          <p class="large-font">Total a Pagar</p>
          <p><span data-output="total-payment" class="font-result">$0.00</span></p>
        </div>

        <h4>Tabla de Amortizacion (Primeros 12 meses)</h4>
        <table data-table="amortization">
          <thead>
            <tr>
              <th>Mes</th>
              <th>Pago</th>
              <th>Capital</th>
              <th>Interes</th>
              <th>Saldo</th>
            </tr>
          </thead>
          <tbody>
            <tr><td colspan="5">-</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('pixelperfect_calculadora', 'pp_calc_render_shortcode');
