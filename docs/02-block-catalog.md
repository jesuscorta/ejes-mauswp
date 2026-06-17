# 02 | Catálogo de bloques

## Lista de bloques ACF permitidos

- `acf/mauswp-hero`
- `acf/mauswp-about-hero`
- `acf/mauswp-features-strip`
- `acf/mauswp-expertise-grid`
- `acf/mauswp-angled-product-callout`
- `acf/mauswp-media-text`
- `acf/mauswp-editorial-offset`
- `acf/mauswp-latest-posts`
- `acf/mauswp-product-showcase`
- `acf/mauswp-contact-form`

## 1. Hero

Carpeta:

- `blocks/hero/`

Campos:

- `slides` repeater
- `background_image`
- `title`
- `cta_link`

Comportamiento:

- carrusel manual con flechas y dots
- ocupa toda la anchura
- JS propio en `initHeroCarousel()`

Uso:

- portada

## 2. Hero Nosotros

Carpeta:

- `blocks/about-hero/`

Campos:

- `background_image`
- `title`
- `subtitle`
- `subtitle_position`
- `cta_label`
- `cta_anchor`
- `description`

Comportamiento:

- hero compacto
- CTA por ancla, pensado para llevar al bloque de contacto
- permite subtítulo encima o debajo del título

Uso:

- páginas corporativas como `Nosotros`

## 3. 4 Ventajas

Carpeta:

- `blocks/features-strip/`

Campos:

- `items` repeater
- `icon`
- `title`

Comportamiento:

- banda de items destacados
- visualmente compacta

Uso:

- debajo del hero

## 4. Especialidades

Carpeta:

- `blocks/expertise-grid/`

Campos:

- `eyebrow`
- `title`
- `intro`
- `items` repeater
- `image`
- `highlight`
- `title`
- `description`

Comportamiento:

- tarjetas compactas en grid
- pensado para sustituir secciones con fotos grandes y poco valor
- la `intro` no tiene texto por defecto en render; solo se muestra si se rellena

Uso:

- sectores
- áreas de especialización
- bloques de `Nosotros`

## 5. Llamada productos

Carpeta:

- `blocks/angled-product-callout/`

Campos:

- `eyebrow`
- `title`
- `content`
- `cta_label`
- `cta_anchor`
- `image`

Comportamiento:

- bloque de transición visual antes del contacto
- actualmente ya no lleva corte diagonal, aunque el nombre interno todavía conserve `angled`
- usa una sola imagen de producto
- CTA por ancla al bloque de contacto

Uso:

- antes del formulario de contacto

## 6. Imagen + Texto

Carpeta:

- `blocks/media-text/`

Campos:

- `image`
- `eyebrow`
- `title`
- `content`
- `cta_link`

Comportamiento:

- bloque editorial clásico a dos columnas

## 7. Editorial Desplazado

Carpeta:

- `blocks/editorial-offset/`

Campos:

- `image`
- `eyebrow`
- `title`
- `content`
- `cta_link`

Comportamiento:

- composición visual más expresiva
- panel de texto + imagen

## 8. Últimas Entradas

Carpeta:

- `blocks/latest-posts/`

Campos:

- `eyebrow`
- `title`
- `intro`
- `cta_link`

Comportamiento:

- consulta los 4 últimos posts
- 1 destacado + 3 compactos
- no depende de selección manual

## 9. Productos Destacados

Carpeta:

- `blocks/product-showcase/`

Campos:

- `scope`
- `product_category`

Comportamiento:

- consulta productos reales de WooCommerce
- máximo 8 productos
- slider con Swiper
- 4 visibles en desktop
- 2 en tablet
- 1.15 en móvil

Notas:

- el texto de cabecera del bloque está hardcodeado en el render
- el filtro puede ser `todo` o `categoría`

## 10. Formulario de Contacto

Carpeta:

- `blocks/contact-form/`

Comportamiento:

- layout en dos columnas
- izquierda: email, teléfono, Facebook y WhatsApp
- derecha: Gravity Forms
- usa opciones globales del tema para los datos de contacto
- si no se fija un formulario por filtro, coge el primer formulario activo de Gravity Forms

Ancla recomendada:

- `contacto`

Esto es importante porque varios CTAs del sitio enlazan por ancla a ese bloque.
