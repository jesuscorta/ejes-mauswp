# 03 | Estado actual y caveats

## Estado funcional actual

### Home

- preparada para ser editable por bloques con `front-page.php`
- hero principal y bloques corporativos ya existen
- bloque de productos usa WooCommerce real

### Página Nosotros

- existe la plantilla `Página por bloques` en `page-blocks.php`
- se ha estado construyendo con bloques ACF específicos
- hay bloques creados expresamente para esta página:
  - `Hero Nosotros`
  - `Especialidades`
  - `Llamada productos`
  - `Formulario de Contacto`

## Decisiones recientes importantes

### Header

- el logo se toma desde opciones del tema
- el top bar usa marquee continuo
- el mega menú de catálogo se abre con click
- el CTA visible `Catálogo` es el disparador del mega menú

### Contacto

- el bloque de contacto está pensado como destino de ancla
- muchas llamadas a la acción usan `#contacto`

### Catálogo

- el mega menú usa categorías reales de WooCommerce
- las categorías visibles pueden excluirse desde opciones del tema
- la imagen lateral del mega menú también sale de opciones del tema

## Caveats y deuda técnica real

### 1. Descripciones o nombres internos desactualizados

- `blocks/angled-product-callout/block.json` aún describe el bloque como si tuviera fondo inclinado, pero ya no lo tiene.
- el nombre interno `angled-product-callout` ya no representa del todo el diseño actual.

### 2. Footer sin integración completa con opciones del tema

En `footer.php` el texto y el contacto siguen hardcodeados:

- dirección
- teléfono
- email

Si se quiere consistencia real con el header, esta es una mejora pendiente clara.

### 3. JS sin build ni modularización

`assets/src/js/app.js` se sirve tal cual. Esto simplifica el proyecto, pero implica:

- sin TypeScript
- sin lint visible en el flujo
- sin separación por componentes

Para el estado actual es suficiente, pero conviene saberlo antes de ampliar comportamiento.

### 4. CSS centralizado y largo

`assets/src/css/app.css` concentra casi todo el sistema visual y componentes.

Consecuencia:

- es rápido para iterar
- pero cada cambio exige leer con atención para no colisionar con reglas existentes

### 5. Dependencias fuertes en plugins

Sin estos plugins parte del theme pierde funcionalidad:

- ACF Pro: bloques y opciones
- WooCommerce: catálogo, producto destacado, mega menú
- Gravity Forms: bloque de contacto

### 6. El menú principal está parcialmente desacoplado del mega menú

El item `Catálogo` del menú se marca con clase, pero el panel real lo abre el CTA externo del header. Esto funciona, pero es una convención del proyecto, no un patrón universal.

## Reglas operativas útiles

- si un bloque no aparece en el editor, revisar `inc/allowed-blocks.php`
- si un campo ACF no aparece, revisar:
  - JSON en `acf-json/`
  - sincronización en ACF
  - si el grupo está en PHP o en JSON
- después de tocar `assets/src/css/app.css`, recompilar con `npm run build`
- después de tocar bloques PHP, validar con `php -l`

## Qué no asumir

- no asumir que el footer ya está conectado a opciones
- no asumir que todo lo editable está en ACF
- no asumir que el catálogo usa plantillas WooCommerce personalizadas; aquí solo se han tocado bloques y navegación del theme
