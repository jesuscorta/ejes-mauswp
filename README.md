# MausWP Ejes

Theme clásico WordPress creado desde cero para refactorizar progresivamente la parte corporativa de `ejespararemolques.com` sin depender de Elementor. La base está orientada a PHP 8+, WordPress 6.x, ACF Pro, Gutenberg y Tailwind CSS.

## Objetivo de esta base

- Separar claramente bootstrap del theme, setup, assets y preparación de bloques.
- Crear plantillas limpias para home, páginas normales, noticias, archivos y 404.
- Dejar una estructura preparada para migrar contenido corporativo antes de abordar tienda o WooCommerce.

## Comandos npm

```bash
npm install
npm run dev
npm run build
```

## Estructura

```text
.
├── 404.php
├── archive.php
├── assets
│   ├── dist
│   │   └── app.css
│   └── src
│       ├── css
│       │   └── app.css
│       └── js
│           └── app.js
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── inc
│   ├── acf-blocks.php
│   ├── allowed-blocks.php
│   ├── assets.php
│   ├── helpers.php
│   └── setup.php
├── index.php
├── package.json
├── page.php
├── postcss.config.js
├── README.md
├── single.php
├── style.css
├── tailwind.config.js
└── template-parts
    ├── blocks
    ├── components
    └── layout
```

## Compilación de Tailwind

- Archivo fuente: `assets/src/css/app.css`
- Archivo compilado: `assets/dist/app.css`
- Modo desarrollo: `npm run dev`
- Build optimizada: `npm run build`

El `content` de Tailwind escanea:

- `./*.php`
- `./inc/**/*.php`
- `./template-parts/**/*.php`
- `./assets/src/js/**/*.js`

## Notas de implementación

- `functions.php` solo carga archivos desde `/inc`.
- `inc/setup.php` registra soportes del theme, menús y limpieza básica del `head`.
- `inc/assets.php` encola CSS y JS con `filemtime()` y comprobación previa de existencia.
- `inc/acf-blocks.php` deja preparado el registro de bloques ACF sin romper si ACF Pro no está activo.
- `inc/allowed-blocks.php` restringe el editor a un conjunto inicial de bloques core.

## Próximos pasos recomendados

1. Instalar dependencias con `npm install` y compilar Tailwind.
2. Activar el theme en WordPress y asignar menús `primary` y `footer`.
3. Crear estilos editoriales para contenido largo y páginas corporativas.
4. Definir primeros bloques ACF reutilizables: hero, ventajas, sectores y CTA.
5. Migrar por fases `/`, `/nosotros/`, `/noticias/`, `/contacto/` y `/tipos-enganches-tractor/`.
