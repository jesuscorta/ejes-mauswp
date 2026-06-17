# Auditoría WPO — MausWP Theme

**Fecha:** 17/06/2026  
**Versión auditada:** Commit `0f98dd0` — main

---

## Resumen ejecutivo

| | Cantidad |
|---|---|
| 🔴 Críticos | 11 |
| 🟡 Moderados | 19 |
| 🟢 Bajos / Informativos | 0 |
| **Total hallazgos** | **30** |

**Top 3 problemas más urgentes:**
1. `mauswp_get_product_archive_price_bounds()` — query sin límite + sin caché (se cae en catálogos medianos).
2. **Cero mecanismos de caché** en todo el theme — cada valor se recalcula en cada request.
3. **Mega menú N+1 queries** (`get_terms()` anidados) — se ejecuta en todas las páginas vía header.php.

---

## 1. Assets y bundling

### 1.1 🔴 JS no se empaqueta ni minifica

- **Archivo:** `inc/assets.php:54-65`
- **Problema:** `app.js` se encola directamente desde `assets/src/js/app.js` (~812 líneas). No hay bundling, minificación ni tree‑shaking.
- **Impacto:** ~25 KB de JS sin minificar en cada página. Todas las funciones `init*()` se ejecutan aunque el DOM no tenga el elemento correspondiente.
- **Solución:** Añadir un build step (esbuild, Rollup o Vite) para empaquetar, minificar y hacer tree‑shake. Encolar desde `assets/dist/`.

### 1.2 🔴 Swiper se carga completo (no tree‑shaken)

- **Archivo:** `inc/assets.php:41-52`
- **Problema:** `swiper-bundle.min.js` incluye todos los módulos (Navigation, Pagination, Autoplay…). El theme solo usa Navigation.
- **Impacto:** ~140 KB gzipped de JS innecesario.
- **Solución:** Usar módulos ESM de Swiper: `import { Navigation } from 'swiper/modules'` + `import Swiper from 'swiper'`.

### 1.3 🟡 JS no está diferido

- **Archivo:** `inc/assets.php:45,57`
- **Problema:** Swiper y app.js se encolan en footer pero sin `defer` ni `async`.
- **Impacto:** Bloquean el parser hasta el footer.
- **Solución:** Añadir `'strategy' => 'defer'` en `wp_enqueue_script` (WP 6.3+).

### 1.4 🟡 Tailwind `content` no incluye `woocommerce/`

- **Archivo:** `tailwind.config.js:3-10`
- **Problema:** La carpeta `woocommerce/` (con el override de checkout) no se escanea para clases Tailwind.
- **Impacto:** Las clases usadas en `woocommerce/checkout/form-checkout.php` pueden ser purgadas.
- **Solución:** Añadir `'./woocommerce/**/*.php'` al array `content`.

### 1.5 🟡 Sin `safelist` para clases dinámicas

- **Archivo:** `tailwind.config.js`
- **Problema:** No hay safelist. Si en el futuro se generan clases Tailwind dinámicas en PHP, serán purgadas.
- **Impacto:** Bajo actualmente — el theme usa CSS custom properties para valores dinámicos.
- **Solución:** Monitorizar y añadir safelist si se introducen clases dinámicas.

---

## 2. Imágenes

### 2.1 🔴 Logo del header sin `srcset` responsivo

- **Archivo:** `header.php:136`
- **Problema:** `<img>` con un solo `src`, sin `srcset`, `sizes` ni `loading="lazy"`.
- **Impacto:** El logo está above the fold en todas las páginas. No se beneficia de imágenes responsivas.
- **Solución:** Usar `wp_get_attachment_image()` con `loading="eager"` + `fetchpriority="high"`.

### 2.2 🔴 Hero usa `background-image` en vez de `<img>`

- **Archivo:** `blocks/hero/render.php:88-93`
- **Problema:** Las imágenes del slider se ponen con `style="background-image: url(...)"`. El navegador descarga todas las slides en la carga inicial, incluso las no visibles. Sin lazy loading ni responsive sizing.
- **Impacto:** Alto — el hero es candidato a LCP. Múltiples imágenes a resolución completa descargadas eager.
- **Solución:** Usar `<img>` con `loading="lazy"` para slides no activas y `fetchpriority="high"` + `loading="eager"` para la primera.

### 2.3 🟡 Falta `decoding="async"` en la mayoría de imágenes

- **Archivos:** `blocks/expertise-grid/render.php:111`, `blocks/about-hero/render.php:49`, `blocks/angled-product-callout/render.php:51`, `footer.php:42`, `category-archive.php:116`
- **Problema:** Solo algunas imágenes usan `loading="lazy"`. Ninguna usa `decoding="async"`.
- **Impacto:** La decodificación de imágenes no se hace off‑main‑thread.
- **Solución:** Añadir `decoding="async"` a todas las imágenes below the fold.

### 2.4 🟡 Falta `fetchpriority="high"` en imágenes LCP

- **Archivos:** `blocks/hero/render.php:88`, bloque about‑hero, logo del header.
- **Problema:** Ningún atributo `fetchpriority` en el theme.
- **Impacto:** El navegador no puede priorizar la imagen LCP, retrasando la carga percibida.
- **Solución:** Añadir `fetchpriority="high"` a la imagen principal del hero o al logo.

### 2.5 🟡 `get_post_meta` para alt en bucles de producto

- **Archivos:** `category-archive.php:206`, `single-product.php:168`, `blocks/product-showcase/render.php:71`
- **Problema:** Se usa `get_post_meta($image_id, '_wp_attachment_image_alt', true)` y luego `<img>` manual. Esto pierde la ventaja de `wp_get_attachment_image()`.
- **Impacto:** Una query extra de post meta por imagen + pérdida de srcset responsivo automático.
- **Solución:** Sustituir el `<img>` manual por `wp_get_attachment_image()`.

---

## 3. Tipografía

### 3.1 🔴 No hay estrategia de carga de fuentes

- **Archivo:** `tailwind.config.js:34-35`
- **Problema:** `fontFamily` define `Manrope` y `Athelas`, pero **no existe ningún `@font-face` en el CSS del tema**. Tampoco se ve import de Google Fonts.
- **Impacto:** Las fuentes hacen fallback a system‑ui/Arial. O bien las carga un plugin externo no controlado por el tema.
- **Solución:** Self‑hostear las fuentes con `@font-face` + `font-display: swap`, o añadir `preconnect` al CDN si se cargan externamente.

### 3.2 🔴 Sin `font-display: swap`

- **Archivo:** Todo el CSS compilado
- **Problema:** No se encuentra la propiedad CSS `font-display` en ningún `@font-face`.
- **Impacto:** Si las fuentes se cargan externamente, no hay control sobre FOIT/FOUT.
- **Solución:** Añadir `font-display: swap` a todas las declaraciones `@font-face`.

---

## 4. PHP y consultas a base de datos

### 4.1 🔴 `mauswp_get_product_archive_price_bounds()` — query sin límite

- **Archivo:** `inc/helpers.php:322-372`
- **Problema:** `wc_get_products(['limit' => -1, 'return' => 'ids'])` carga **todos** los IDs de producto, luego itera llamando `get_post_meta` para `_price`. En una tienda con 500+ productos son cientos de queries.
- **Impacto:** Severo — query no acotada y N + 1 metadatos. Timeout u OOM en catálogos grandes.
- **Solución:** Usar SQL directo: `SELECT MIN(meta_value), MAX(meta_value) FROM wp_postmeta WHERE meta_key = '_price' AND post_id IN (SELECT ID FROM wp_posts WHERE post_type='product' AND post_status='publish')`. Cachear el resultado en un transient.

### 4.2 🔴 `mauswp_get_catalog_mega_menu_categories()` — N + 1 queries de términos

- **Archivo:** `inc/helpers.php:117-160`
- **Problema:** Primer `get_terms()` para categorías padre. Luego, **dentro del bucle**, otro `get_terms()` para los hijos de cada padre. Con 9 padres = 10 llamadas a `get_terms()`.
- **Impacto:** Medio — se ejecuta en header.php en cada página. 10+ queries de taxonomía por request sin caché.
- **Solución:** Precargar todos los términos en 2 queries: una para padres y otra para hijos (`parent__in`). Agrupar hijos en PHP. Cachear el array resultado en un transient.

### 4.3 🔴 `orderby => 'rand'` en productos relacionados

- **Archivo:** `single-product.php:148`
- **Problema:** `ORDER BY RAND()` causa full table scan + filesort en MySQL.
- **Impacto:** Alto en tiendas con 100+ productos — se ejecuta en cada single de producto.
- **Solución:** Usar `orderby => 'date'` o `'modified'`, o pre‑computar productos relacionados y guardarlos como post meta.

### 4.4 🔴 Cero mecanismos de caché

- **Archivos:** Todos los `inc/*.php`, template parts, bloques.
- **Problema:** No existe `set_transient()`, `get_transient()`, `wp_cache_set()` ni `wp_cache_get()` en todo el theme.
- **Impacto:** Severo — price bounds, mega menú, search results y related posts se recalculan en cada request.
- **Solución:** Añadir transients en:
  - Price bounds (invalidar en `save_post_product`)
  - Mega menú (invalidar en `saved_product_cat`)
  - AJAX popular products (TTL 1 hora)
  - Related posts (TTL 12 horas)

### 4.5 🟡 `get_terms()` para filtros sin caché

- **Archivo:** `category-archive.php:78-85`
- **Problema:** `get_terms()` sin límite ni caché en cada página de categoría/tienda.
- **Impacto:** Bajo — `get_terms` tiene caché interno de WP, pero la primera carga es costosa con muchos términos.
- **Solución:** Añadir `'number' => 50`.

### 4.6 🟡 `mauswp_get_related_posts()` sin caché

- **Archivo:** `inc/helpers.php:427-461`
- **Problema:** Nuevo `WP_Query` en cada single post, sin caché.
- **Impacto:** Bajo — acotado a 4 posts, pero ocurre en cada carga de single.php.
- **Solución:** Transient con TTL razonable.

### 4.7 🟡 Búsqueda AJAX — 3 WP_Queries por keystroke

- **Archivo:** `inc/search-ajax.php:82-201`
- **Problema:** Cada búsqueda ejecuta 3 queries separadas. Con 300ms de debounce sigue siendo una carga alta si hay muchos usuarios buscando.
- **Impacto:** Medio — picos de carga AJAX bajo tráfico.
- **Solución:** Consolidar en una sola query con `tax_query` + `meta_query`, o cachear resultados por término.

---

## 5. WooCommerce

### 5.1 🟡 Scripts y estilos de WC no se desencolan en páginas no‑WC

- **Archivos:** No existe lógica de dequeue.
- **Problema:** woo‑commerce.css, cart fragments, select2… se cargan en todas las páginas, incluso el blog o las páginas institucionales.
- **Impacto:** ~40 KB extra de CSS + llamadas AJAX innecesarias en páginas no‑WC.
- **Solución:** Desencolar estilos/scripts de WC en páginas que no los necesiten. Usar `is_woocommerce()`, `is_cart()`, `is_checkout()` para condicionar.

### 5.2 🟡 Cart fragments no se desactivan fuera del carrito

- **Archivos:** No existe código relacionado.
- **Problema:** WooCommerce ejecuta fragmentos de carrito (admin‑ajax.php) en cada página por defecto.
- **Impacto:** Una petición HTTP extra en cada página para usuarios no logueados.
- **Solución:** `add_action('wp_enqueue_scripts', function() { if (!is_cart() && !is_checkout()) { wp_dequeue_script('wc-cart-fragments'); } }, 99);`

---

## 6. HTML y renderizado

### 6.1 🔴 Sin resource hints (preconnect, preload, dns‑prefetch)

- **Archivo:** `header.php:77-82`
- **Problema:** El `<head>` solo tiene charset, viewport, `wp_head()` y el nonce de búsqueda. Sin ningún hint de recursos.
- **Impacto:** Alto — sin conexión temprana a orígenes externos, sin preload de assets críticos.
- **Solución:** Añadir via `wp_head`:
  - `preconnect` a CDN de fuentes si se usan
  - `preload` de la imagen LCP
  - `dns-prefetch` a analytics si aplica

### 6.2 🔴 Sin critical CSS

- **Archivos:** Todo el CSS
- **Problema:** El CSS completo (164 KB) bloquea el renderizado en el `<head>`. No hay inline de estilos críticos.
- **Impacto:** Alto — todo el contenido above the fold espera a que se descargue el CSS completo.
- **Solución:** Extraer CSS crítico (above‑fold) e inlinearlo en `<head>`. Cargar la hoja completa con `media="print" onload="this.media='all'"`.

### 6.3 🔴 `wp_nonce_field()` en `<head>` — HTML inválido

- **Archivo:** `header.php:81`
- **Problema:** Un `<input type="hidden">` dentro del `<head>` es HTML inválido. Puede causar problemas de renderizado.
- **Solución:** Mover el nonce al markup del panel de búsqueda dentro del `<body>`, o usar exclusivamente `wp_localize_script` para pasarlo.

---

## 7. WordPress específico

### 7.1 🟡 jQuery Migrate no se desactiva

- **Archivo:** `inc/setup.php`
- **Problema:** Si un plugin carga jQuery, jquery‑migrate viene incluido (~10 KB extra).
- **Solución:** `wp_deregister_script('jquery-migrate')` si jQuery no es necesario.

### 7.2 🟡 XML‑RPC no desactivado

- **Archivo:** `inc/setup.php`
- **Problema:** XML‑RPC es un vector de ataques de fuerza bruta y no suele ser necesario.
- **Solución:** `add_filter('xmlrpc_enabled', '__return_false');`

### 7.3 🟡 REST API — endpoint de usuarios expuesto

- **Archivos:** Sin código relacionado.
- **Problema:** `/wp-json/wp/v2/users` expone nombres de usuario, facilitando ataques de enumeración.
- **Solución:** Desregistrar el endpoint si no se usa.

### 7.4 🟡 ACF — opciones autoload

- **Archivo:** `inc/theme-options.php`
- **Problema:** 5 sub‑páginas de opciones ACF con 40+ campos. Cada uno se almacena como `autoload = yes` en `wp_options`.
- **Impacto:** La query `SELECT option_name, option_value FROM wp_options WHERE autoload = 'yes'` crece con cada campo ACF.
- **Solución:** Evaluar si se pueden consolidar campos o usar `acf/update_value` para marcar algunos como no‑autoload.

---

## 8. Deploy

### 8.1 🟡 `sshpass` en lugar de claves SSH

- **Archivo:** `.github/workflows/deploy.yml:30-31`
- **Problema:** La contraseña se pasa en texto plano al comando `sshpass`. Aunque está en un secreto de GitHub, es menos seguro que usar clave privada SSH.
- **Solución:** Usar `secrets.SSH_PRIVATE_KEY` con `ssh-agent`.

### 8.2 🟡 `rsync --delete` sin `--exclude` extra

- **Archivo:** `.github/workflows/deploy.yml:31`
- **Problema:** `--delete` elimina archivos remotos que no existan localmente. Si `acf-json/` o algún upload están en el theme, podrían borrarse.
- **Solución:** Añadir `--exclude "acf-json/"` para prevenir pérdida de configuración sincronizada.

---

## 9. Dependencias

### 9.1 🟡 Swiper es la dependencia más pesada

- **Archivo:** `package.json:16`
- **Problema:** `swiper@^12.1.4` ocupa ~3.8 MB en `node_modules`.
- **Impacto:** Solo afecta al tiempo de build en CI, no a producción.
- **Solución:** Si se mantiene Swiper, usar los módulos ESM para empaquetar solo lo necesario. Alternativas más ligeras: Keen‑Slider (~10 KB), Embla Carousel (~10 KB).

---

## 10. Lo que ya está bien hecho

- CSS compilado con Tailwind y minificado ✅
- `no_found_rows => true` en el bloque latest‑posts ✅
- `pre_get_posts` para limitar posts_per_page en noticias ✅
- Limpieza de head (RSD, WLW, generator, emoji…) ✅
- Viewport meta correcto ✅
- Skip‑to‑content link ✅
- `node_modules` excluido del rsync ✅
- `npm ci` para builds reproducibles ✅
- Solo un override de WooCommerce (form-checkout) ✅
- SVG assets ligeros (~850 B cada uno) ✅

---

## Plan de acción recomendado

| Prioridad | Acción | Impacto estimado |
|---|---|---|
| 🔴 **P1** | Cachear price bounds + mega menú | Evita OOM/timeout en catálogos grandes |
| 🔴 **P2** | Reemplazar `orderby rand` en relacionados | Reduce carga en single product |
| 🔴 **P3** | Bundling + minificación de JS | Reduce ~25 KB por página |
| 🟡 **P4** | Resource hints + critical CSS | Mejora LCP en ~0.5–1.5s |
| 🟡 **P5** | `decoding="async"` y `fetchpriority` en imágenes | Mejora LCP y reduce main‑thread blocking |
| 🟡 **P6** | Diferir JS no crítico | Mejora FID/TBT |
| 🟡 **P7** | Desencolar WC en páginas no‑WC | Ahorra ~40 KB CSS + 1 petición AJAX |
| 🟡 **P8** | Tree‑shaking de Swiper | Reduce hasta 60% del bundle de Swiper |
| 🟡 **P9** | Mover nonce fuera del `<head>` | Corrige HTML inválido |
