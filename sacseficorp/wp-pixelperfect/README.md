# PixelPerfect

Plugin de WordPress que incluye:

- Calculadora de prestamos por shortcode.
- Card menu por shortcode enlazado a elementos existentes via `data-marker-content`.
- Grid menu por shortcode con tarjetas enlazadas y iconos personalizados.
- Reporte de reclamos por shortcode con paneles editables desde el admin.

## Version

- 1.5.0

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
	<a href="#"><label>Tasas y Comisiones<label><label></label></a>
	<a href="#"><label>Cálculo de la tasa Efectiva/nominal</label><label></label></a>
	<a href="/calculadora"><label>Calculadora</label><label>&gt;</label></a>
[/wp-pixelperfect-card-menu]
```

Comportamiento:

- El shortcode renderiza un `<div>` oculto con el contenido interno del shortcode como plantilla.
- Se genera un selector dinamico con el parametro recibido, por ejemplo: `[data-marker-content="tasas-comisiones-pp"]`.
- La configuracion se inyecta via `wp_add_inline_script(..., 'before')` como un objeto `{ selector, contentId }` empujado al array global `window.ppCardMenuConfigs`.
- Se admiten multiples instancias del shortcode en la misma pagina.
- Cuando el usuario hace hover sobre un elemento existente en la pagina que coincida con el selector, se reemplaza el contenido del elemento con el contenido de la plantilla, aplicando fondo `#000D54`, texto blanco y padding.

### Grid menu

```text
[wp-pixelperfect-grid-menu menu="menu_name"]
```

Rendenriza una grilla responsive de tarjetas a partir de items de un menu de WordPress.

Comportamiento:

- **Titulo de la tarjeta**: Se obtiene del campo "Title Attribute" del item del menu.
- **Descripción**: Se obtiene del campo "Descripción" del item del menu.
- **Icono**: Se obtiene de la imagen popup_bg_image del item del menu.
- **Link**: Toda la tarjeta es clickeable y redirige a la URL del item del menu.
- **Responsive**: Se adapta automáticamente a 3 columnas en desktop, 2 en tablet y 1 en mobile.
- **Colores**: Cada tarjeta tiene una paleta de colores personalizada según su posición en la grilla.
- Al retirar el cursor, el contenido y estilos originales del elemento son restaurados.

Nota:

- Debe existir en el DOM un elemento con el atributo `data-marker-content` que coincida exactamente con el valor enviado al shortcode.

### Reporte de reclamos

```text
[reporte-reclamos]
```

Comportamiento:

- Renderiza 3 paneles acordeón colapsables, cada uno con un encabezado de color configurable y 5 estadísticas: Total reclamos, En proceso, Finalizados, Tiempo de resolución y Indicador de eficiencia.
- Los paneles comienzan colapsados y se expanden/cierran al hacer clic.
- Los valores se gestionan desde el panel de administración: **Reportes > Reclamos**.

### Accordion style

```text
[wp-pixelperfect-accordion-style]
```

Comportamiento:

- Encola el archivo `pp-accordion-custom.css` para aplicar estilos personalizados a la clase `.e-n-accordion-item`.
- Si se usa como shortcode envolvente, renderiza tambien el contenido interno.

## Estructura de archivos

- `wp-pixelperfect.php`: archivo principal del plugin (version y metadata), carga los modulos.
- `wp-pixelperfect-calculator.php`: logica y shortcode de calculadora.
- `wp-pixelperfect-card-menu.php`: logica y shortcode de card menu.
- `wp-pixelperfect-grid-menu.php`: logica y shortcode de grid menu.
- `wp-pixelperfect-accordion-style.php`: logica y shortcode para estilos del accordion.
- `wp-pixelperfect-reporte-reclamos.php`: logica, shortcode y formulario de edicion del reporte de reclamos.
- `calc-style.css`: estilos de la calculadora.
- `calculator.js`: logica de calculadora.
- `pp-card-custom.css`: estilos del popup del card menu.
- `pp-card-custom.js`: logica hover/popup del card menu.
- `pp-grid-menu-custom.css`: estilos de la grilla del grid menu.
- `pp-accordion-custom.css`: estilos personalizados para `.e-n-accordion-item`.
- `pp-reporte-reclamos.css`: estilos de los paneles del reporte de reclamos.

## Cambios

### 1.5.0

- Se agrega shortcode `[wp-pixelperfect-grid-menu menu="menu_name"]` que renderiza una grilla responsive de tarjetas a partir de items de un menú de WordPress.
- Las tarjetas muestran: título desde Title Attribute, descripción desde el campo Description del item, e icono desde la imagen popup_bg_image.
- Cada tarjeta es completamente clickeable y redirige a la URL del item del menu.
- Diseño responsive: 3 columnas en desktop, 2 en tablet, 1 en mobile.
- Se agregan paletas de colores personalizadas para cada tarjeta según su posición en la grilla.
- Se agregan archivos `wp-pixelperfect-grid-menu.php` y `pp-grid-menu-custom.css`.

### 1.4.0

- Se agrega shortcode `[reporte-reclamos]` que renderiza 3 paneles acordeón colapsables con estadísticas de reclamos.
- Se agrega menú de administración **Reportes** con submenú **Reclamos** para editar el título (mes/año), color de encabezado y los 5 valores de cada panel.
- Se agregan archivos `wp-pixelperfect-reporte-reclamos.php` y `pp-reporte-reclamos.css`.

### 1.3.2

- Calculadora: el campo de plazo cambia de años a meses (`data-field="months"`), permitiendo ingresar directamente el numero de meses del prestamo.
- Calculadora: se agrega nota al pie `* Pago mensual no incluye seguros.` debajo de la tabla de amortizacion.

### 1.3.1

- Calculadora: se encapsulan los estilos con el selector padre `.loan-calculator-container` para evitar colisiones de estilos globales en el sitio.
- Calculadora: se elimina el contenedor `<span class="input-symbol-dollar">` alrededor del campo de monto en el shortcode y en `index.html`, simplificando el marcado del input.

### 1.3.0

- Card menu: el contenido del shortcode ahora se renderiza como una plantilla `<div>` oculta en el DOM.
- Card menu: la configuracion se pasa como objeto `{ selector, contentId }` al array global `window.ppCardMenuConfigs`, permitiendo multiples instancias por pagina.
- Card menu: el comportamiento de hover ahora reemplaza el contenido del elemento objetivo con el contenido de la plantilla (fondo `#000D54`, texto blanco, padding y borde redondeado) y restaura el estado original al salir.

### 1.2.0

- Se unifica el plugin con un unico archivo principal: `wp-pixelperfect.php`.
- Se agrega shortcode `wp-pixelperfect-card-menu`.
- Se agregan assets `pp-card-custom.css` y `pp-card-custom.js`.
- Se implementa configuracion por `data-marker-content` con script inline antes del JS principal.
- Autor actualizado a `PixelPerfect, Isaac Gómez`.

## Licencia

Uso interno / privado. Ajustar segun necesidades del proyecto.
