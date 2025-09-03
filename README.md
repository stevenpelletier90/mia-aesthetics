# Mia Aesthetics WordPress Theme — Quick Commands

This is a compact, practical command reference for day‑to‑day work: CSS minification, QA, and bundling for deployment.

## Setup

- Install deps: `npm install && composer install`

## Build

- Minify all CSS and JS assets:
  - `npm run build:assets`

## Bundle (ZIP‑ready theme folder)

- Create a lean production bundle (minified runtime assets only):
  - `npm run bundle`
- Include source maps in the bundle (optional):
  - `npm run bundle -- --with-maps`
- Output folder to zip: `theme-bundle/mia-aesthetics/`

Notes

- The bundle includes both regular and minified `.css`/`.js` files.
- Individual CSS files are loaded conditionally by template for optimal performance.
- The theme uses CSS-only architecture - no SCSS compilation needed.

### Troubleshooting: CSS changes not appearing

- Edit CSS files directly in `/assets/css/` directory
- Run `npm run minify:css` to generate minified versions
- In production, the theme may use minified assets, so ensure both versions are updated
- Set `WP_DEBUG=true` locally to force non-minified files during development

## QA (Linting/Formatting/Static Analysis)

- Frontend QA (JS + CSS + Prettier check):
  - `npm run qa:fe`
- Full QA (Frontend + PHP):
  - `npm run qa:all`
- Individual:
  - JS lint: `npm run lint:js`
  - Format CSS/JS sources: `npm run format`
  - PHP (PHPCS + PHPStan): `composer qa`

## Development Workflow

- Clean dev build (copy files to bundle for quick testing):
  - `npm run dev:clean`
- Minify CSS only:
  - `npm run minify:css`
- Minify JS only:
  - `npm run minify:js`

## Environment

- Node: LTS recommended
- PHP: 8.4+ (PHPCS PHPCompatibility configured)

## Deploy

1. Run: `npm run build:assets && npm run bundle`
2. Zip the folder: `theme-bundle/mia-aesthetics/`
3. Upload in WP Admin → Appearance → Themes → Add New → Upload

## CSS-Only Architecture

- CSS files are organized in `/assets/css/` by purpose:
  - **Base**: `base.css`, `fonts.css` (core styles)
  - **Layout**: `layout/header.css`, `layout/footer.css` (site structure)
  - **Components**: `components/*.css` (reusable UI components)
  - **Templates**: `templates/**/*.css` (page-specific styles)
  - **Utilities**: `utilities/*.css` (helper classes)
- Edit CSS directly - no preprocessing needed
- Individual files are conditionally loaded per template for performance
