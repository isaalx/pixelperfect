# PixelPerfect

Plugin de WordPress que incluye:

- Calculadora de prestamos por shortcode.
- Card menu por shortcode enlazado a elementos existentes via `data-marker-content`.

## Version

- 1.2.0

## Requisitos

- WordPress 5.0 o superior.
- PHP 7.4 o superior (recomendado PHP 8.x).

## Instalacion

1. Copia la carpeta del plugin dentro de `wp-content/plugins/`.
2. Verifica que el archivo principal sea `wp-pixelperfect.php`.
3. En el panel de WordPress, ve a **Plugins** y activa **PixelPerfect**.

## Shortcodes

### Calculadora

```text
[pixelperfect_calculadora]
```

Renderiza la calculadora con resultados y tabla de amortizacion de los primeros 12 meses.

### Card menu

```text
[wp-pixelperfect-card-menu data-marker-content="tasas-comisiones-pp"]
	<p>Contenido del popup</p>
[/wp-pixelperfect-card-menu]
```

Comportamiento:

- El shortcode no renderiza nada visible por defecto.
- El shortcode registra una plantilla oculta con el contenido interno.
- Se genera un selector dinamico con el parametro recibido, por ejemplo: `[data-marker-content="tasas-comisiones-pp"]`.
- El JS se configura con `wp_add_inline_script(..., 'before')` para inyectar ese selector antes de cargar `pp-card-custom.js`.
- Cuando el usuario hace hover sobre un elemento existente en la pagina que coincida con ese selector, se muestra un popup flotante con el contenido del shortcode y fondo `#000D54`.

Nota:

- Debe existir en el DOM un elemento con el atributo `data-marker-content` que coincida exactamente con el valor enviado al shortcode.

## Estructura de archivos

- `wp-pixelperfect.php`: archivo principal del plugin (version y metadata), carga los modulos.
- `wp-pixelperfect-calculator.php`: logica y shortcode de calculadora.
- `wp-pixelperfect-card-menu.php`: logica y shortcode de card menu.
- `calc-style.css`: estilos de la calculadora.
- `calculator.js`: logica de calculadora.
- `pp-card-custom.css`: estilos del popup del card menu.
- `pp-card-custom.js`: logica hover/popup del card menu.

## Cambios

### 1.2.0

- Se unifica el plugin con un unico archivo principal: `wp-pixelperfect.php`.
- Se agrega shortcode `wp-pixelperfect-card-menu`.
- Se agregan assets `pp-card-custom.css` y `pp-card-custom.js`.
- Se implementa configuracion por `data-marker-content` con script inline antes del JS principal.
- Autor actualizado a `PixelPerfect, Isaac Gómez`.

## Licencia

Uso interno / privado. Ajustar segun necesidades del proyecto.
