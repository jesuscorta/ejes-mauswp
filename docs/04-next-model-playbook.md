# 04 | Playbook para el siguiente modelo

## Qué leer primero al entrar

1. `docs/README.md`
2. `docs/01-architecture.md`
3. `header.php`
4. `assets/src/css/app.css`
5. el bloque o plantilla concreta que se vaya a tocar

## Flujo recomendado para cambios

### Si el cambio es de bloque ACF

Revisar:

1. `blocks/<bloque>/block.json`
2. `blocks/<bloque>/render.php`
3. `acf-json/group_mauswp_<...>.json`
4. `inc/allowed-blocks.php`
5. estilos en `assets/src/css/app.css`

Después:

```bash
php -l blocks/<bloque>/render.php
npm run build
```

### Si el cambio es de header o footer

Revisar:

1. `header.php` o `footer.php`
2. `inc/helpers.php`
3. `inc/theme-options.php`
4. `assets/src/js/app.js`
5. `assets/src/css/app.css`

### Si el cambio depende de catálogo

Confirmar:

- si usa WooCommerce
- si depende de `product_cat`
- si el contenido viene de opciones del tema o de ACF del bloque

## Convenciones del proyecto

- los fallbacks deben ser razonables y no romper el frontend
- no abrir el editor a bloques core arbitrarios sin necesidad
- preferir campos ACF concretos antes que opciones genéricas difíciles de mantener
- usar anclas explícitas para CTAs internos
- mantener la UI corporativa sobria y utilitaria

## Plantillas a elegir según caso

- `front-page.php`
  - solo para la home

- `page-blocks.php`
  - para páginas corporativas hechas por bloques
  - importante para `Nosotros`

- `page.php`
  - para páginas estándar con cabecera editorial y caja blanca

## Sitios donde es fácil romper cosas

### Header

- el mega menú depende de marcas y selectores concretos:
  - `data-catalog-mega-root`
  - `data-catalog-mega-trigger`
  - `data-catalog-mega-panel`

- el menú móvil depende de:
  - `data-site-header`
  - `data-mobile-menu-toggle`
  - `data-mobile-menu-panel`

### CSS full-width

El proyecto usa una estrategia personalizada para `.alignfull`:

- evita `100vw`
- compensa con márgenes calculados

No cambiar esto a la ligera porque ya hubo problemas de scroll horizontal.

### Contacto

Varios bloques enlazan al contacto por ancla. Si se cambia el `id` o el nombre del ancla del bloque de contacto, hay que revisar:

- `Hero Nosotros`
- `Llamada productos`
- cualquier CTA manual en contenido

## Mejoras lógicas pendientes

No son obligatorias, pero sí razonables:

1. pasar contacto del footer a opciones del tema
2. revisar descripciones desactualizadas en `block.json`
3. documentar una convención de nombres para bloques nuevos
4. decidir si `page.php` debe detectar automáticamente páginas por bloques
5. extraer parte del CSS por componentes si el theme sigue creciendo

## Resumen ejecutivo para otro modelo

Este proyecto ya no está en fase de bootstrap. Ya tiene:

- arquitectura base estable
- header bastante personalizado
- mega menú funcional
- varios bloques ACF corporativos
- integración de WooCommerce y Gravity Forms en puntos concretos

La prioridad al continuar no es reinventar la base. Es mantener coherencia con lo que ya existe y ampliar por bloques de forma controlada.
