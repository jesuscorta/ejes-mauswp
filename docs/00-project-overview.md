# 00 | Project Overview

## Qué es

`mauswp` es un theme clásico de WordPress creado para reconstruir la parte corporativa de `ejespararemolques.com` con Gutenberg + ACF, evitando depender de Elementor.

La estrategia actual es:

- maquetación corporativa por bloques ACF
- CSS centralizado en Tailwind compilado
- JS ligero sin bundler complejo
- plantillas PHP clásicas para páginas, archivos y entradas

## Stack real del proyecto

- WordPress 6.x
- PHP 8+
- ACF Pro
- WooCommerce
- Gravity Forms
- Tailwind CSS 3
- Swiper 12

## Dependencias funcionales importantes

### Obligatorias para el theme

- `ACF Pro`
  - registra bloques
  - carga campos JSON de `acf-json/`
  - crea la página `Opciones del tema`

### Dependencias condicionales

- `WooCommerce`
  - mega menú de catálogo
  - bloque `Productos Destacados`
  - taxonomía `product_cat`

- `Gravity Forms`
  - bloque `Formulario de Contacto`
  - usa el primer formulario activo si no se define otro por filtro

## Filosofía de implementación

- aprovechar patrones ya presentes en el theme
- mantener los bloques muy concretos al negocio
- evitar abstracciones genéricas innecesarias
- usar fallbacks razonables cuando falta contenido
- dejar la home y páginas corporativas editables por Gutenberg

## Comandos habituales

```bash
npm install
npm run dev
npm run build
```

## Archivos base a conocer primero

- `functions.php`
- `inc/setup.php`
- `inc/assets.php`
- `inc/acf-blocks.php`
- `inc/theme-options.php`
- `inc/allowed-blocks.php`
- `header.php`
- `footer.php`
- `assets/src/css/app.css`
- `assets/src/js/app.js`

## Plantillas relevantes

- `front-page.php`
  - home editable por bloques

- `page-blocks.php`
  - plantilla limpia para páginas construidas con bloques
  - importante para páginas como `Nosotros`

- `page.php`
  - plantilla de página estándar con caja blanca y cabecera editorial

- `single.php`
  - entradas de noticias

- `archive.php`
  - archivos de noticias
