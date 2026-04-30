# PixelPerfect Loan Calculator

Plugin de WordPress que agrega una calculadora de prestamos con tabla de amortizacion (primeros 12 meses) mediante shortcode.

## Caracteristicas

- Calcula pago mensual.
- Calcula total de intereses.
- Calcula total a pagar.
- Genera tabla de amortizacion de los primeros 12 meses.
- Soporta multiples instancias del shortcode en la misma pagina.
- Carga CSS y JavaScript solo cuando se renderiza el shortcode.

## Requisitos

- WordPress 5.0 o superior.
- PHP 7.4 o superior (recomendado PHP 8.x).

## Instalacion

1. Copia la carpeta del plugin dentro de `wp-content/plugins/`.
2. Verifica que el archivo principal sea `wp-pixelperfect-calculator.php`.
3. En el panel de WordPress, ve a **Plugins** y activa **PixelPerfect Loan Calculator**.

## Uso

Inserta este shortcode en cualquier pagina o entrada:

```text
[pixelperfect_calculadora]
```

Al publicarse, se mostrara la calculadora con sus campos de entrada, resultados y tabla de amortizacion.

## Estructura de archivos

- `wp-pixelperfect-calculator.php`: archivo principal del plugin, registra assets y shortcode.
- `calculator.js`: logica de calculo y render de resultados/tabla.
- `calc-style.css`: estilos visuales de la calculadora.
- `index.html`: maqueta original de referencia.

## Funcionamiento tecnico

- El shortcode se registra con `add_shortcode('pixelperfect_calculadora', ...)`.
- Los assets se registran con `wp_register_style` y `wp_register_script`.
- Los assets se encolan dentro del render del shortcode con `wp_enqueue_style` y `wp_enqueue_script`.
- La logica JavaScript inicializa cada bloque `.loan-calculator-container` para evitar conflictos entre instancias.

## Personalizacion

Puedes modificar:

- Textos del formulario y resultados en `wp-pixelperfect-calculator.php`.
- Colores, tipografia y espaciados en `calc-style.css`.
- Formula y formato de moneda en `calculator.js`.

## Solucion de problemas

- Si no se muestra la calculadora, valida que el plugin este activado.
- Si no carga estilos o scripts, revisa permisos de archivos y ruta del plugin.
- Si usas cache, limpia cache de plugin/CDN/navegador despues de cambios.

## Version

- 1.0.0

## Licencia

Uso interno / privado. Ajustar segun necesidades del proyecto.
