# Mia Aesthetics WordPress Theme — Quick Commands

This is a compact, practical command reference for day‑to‑day work: build, QA, and bundling for deployment.

## Setup

- Install deps: `npm install && composer install`

## Build

- Build all assets (SCSS → CSS, PostCSS, minify CSS/JS):
  - `npm run build:assets`

## Bundle (ZIP‑ready theme folder)

- Create a lean production bundle (minified runtime assets only):
  - `npm run bundle`
- Include source maps in the bundle (optional):
  - `npm run bundle -- --with-maps`
- Output folder to zip: `theme-bundle/mia-aesthetics/`

Notes

- The bundle includes only `.min.css`/`.min.js` for the front end.
- Two unminified files are kept for the block editor: `assets/css/theme.css` and `assets/css/fonts.css` (loaded via `add_editor_style()`), so editor content resembles the front end. Front end still uses minified assets via enqueue logic.

## QA (Linting/Formatting/Static Analysis)

- Frontend QA (JS + SCSS + Prettier check):
  - `npm run qa:fe`
- Full QA (Frontend + PHP):
  - `npm run qa:all`
- Individual:
  - JS lint: `npm run lint:js`
  - SCSS lint: `npm run lint:scss`
  - SCSS format check: `npm run lint:scss:prettier`
  - Run Prettier fix (SCSS/JS/CSS sources): `npm run format`
  - PHP (PHPCS + PHPStan): `composer qa`

## Development Workflow

- Clean dev build (compile all CSS and copy vendors to bundle for quick testing):
  - `npm run dev:clean`
- Build only theme.css (main) + PostCSS minify:
  - `npm run build:theme`

## Environment

- Node: LTS recommended
- PHP: 8.4+ (PHPCS PHPCompatibility configured)

## Deploy

1. Run: `npm run build:assets && npm run bundle`
2. Zip the folder: `theme-bundle/mia-aesthetics/`
3. Upload in WP Admin → Appearance → Themes → Add New → Upload
