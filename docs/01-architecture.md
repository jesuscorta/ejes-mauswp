# 01 | Arquitectura del theme

## Bootstrap

`functions.php` solo hace una cosa: cargar módulos desde `inc/`.

Orden actual:

1. `inc/setup.php`
2. `inc/assets.php`
3. `inc/helpers.php`
4. `inc/acf-blocks.php`
5. `inc/theme-options.php`
6. `inc/allowed-blocks.php`

## Setup del theme

Definido en `inc/setup.php`.

### Soportes activos

- `title-tag`
- `post-thumbnails`
- `align-wide`
- `html5`
- `editor-styles`

### Menús registrados

- `primary`
- `footer`
- `footer_legal`

### Limpieza de `wp_head`

El theme elimina salidas comunes no necesarias:

- generator
- emoji scripts/styles
- shortlink
- oEmbed discovery
- RSD / WLW

## Assets

Definidos en `inc/assets.php`.

### CSS

- fuente: `assets/src/css/app.css`
- compilado: `assets/dist/app.css`
- editor: también usa `assets/dist/app.css`

### JS

- archivo encolado directamente: `assets/src/js/app.js`
- no hay bundler JS

### Swiper

Se encola desde:

- `node_modules/swiper/swiper-bundle.min.css`
- `node_modules/swiper/swiper-bundle.min.js`

## Registro de bloques ACF

Definido en `inc/acf-blocks.php`.

### Cómo funciona

- busca `blocks/*/block.json`
- registra cada bloque con `register_block_type()`
- guarda y carga JSON ACF desde `acf-json/`

### Implicación práctica

Para crear un bloque nuevo normalmente hacen falta cuatro piezas:

1. `blocks/<nombre>/block.json`
2. `blocks/<nombre>/render.php`
3. `acf-json/group_mauswp_<...>.json`
4. alta en `inc/allowed-blocks.php`

## Restricción de bloques

Definida en `inc/allowed-blocks.php`.

El editor no está abierto a todos los bloques de WordPress. Solo permite:

- bloques ACF aprobados por el proyecto
- un conjunto corto de bloques core

Esto es importante: si un bloque nuevo no aparece en el editor, revisar aquí primero.

## Opciones globales del theme

Definidas por PHP en `inc/theme-options.php`.

### Grupo actual

Solo existe un grupo ACF en opciones para cabecera y catálogo:

- logo cabecera
- mensaje barra superior
- teléfono
- email
- URL Facebook
- imagen mega menú catálogo
- categorías excluidas del mega menú

No está versionado en `acf-json/` porque se registra con `acf_add_local_field_group()` en PHP.

## Helpers

Definidos en `inc/helpers.php`.

### Funciones relevantes

- `mauswp_fallback_menu()`
  - fallback de navegación si no hay menú asignado

- `mauswp_mark_catalog_menu_item()`
  - detecta el item `Catálogo` del menú principal
  - le añade clase `menu-item--catalog-trigger`

- `mauswp_get_catalog_mega_menu_categories()`
  - construye el dataset del mega menú
  - usa categorías padre de `product_cat`
  - carga hasta 5 hijas por categoría
  - permite excluir categorías desde opciones del tema

## Header

Definido en `header.php`.

### Piezas activas

- top bar con mensaje en marquee infinito
- teléfono, email y Facebook en top bar
- logo configurable desde opciones
- menú principal desktop
- CTA `Catálogo`
- mega menú de catálogo en desktop
- menú móvil con panel desplegable

### Comportamientos importantes

- el CTA `Catálogo` es el disparador visible del mega menú
- el item `Catálogo` del menú principal se oculta en desktop por CSS
- el mega menú usa click, no hover
- el menú móvil usa JS y altura calculada respecto al header

## Footer

Definido en `footer.php`.

### Importante

La navegación de footer sí usa menús de WordPress, pero los datos de contacto del footer están todavía hardcodeados y no salen de opciones del tema.

## JS actual

Definido en `assets/src/js/app.js`.

Inicializa cuatro cosas:

1. `initMobileMenu()`
2. `initCatalogMegaMenu()`
3. `initHeroCarousel()`
4. `initProductSwiper()`

No hay framework JS ni módulo de estado. Todo está resuelto con query selectors y listeners directos.
