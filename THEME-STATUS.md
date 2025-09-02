# Mia Aesthetics Theme — Status Summary (Updated)

This document reflects the current, verified state based on a fresh pass of the QA scripts and a top‑down review of the repository.

## Current QA Summary
- PHP: Passing
  - PHPCS: 0 errors (64/64 files)
  - PHPStan: OK (level 7)
- JS: Passing
  - ESLint: OK
- SCSS: Failing
  - Stylelint: ~1,600 findings across SCSS (ordering, vendor prefixes, deprecated rules)
  - Prettier check: Fails (includes compiled CSS under `assets/css/` and SCSS sources)

Commands run
- `npm run qa:all` → JS OK; SCSS failing; PHP OK
- `composer qa` → OK

## Completed
- Build + bundle pipeline unified
  - Compiled CSS outputs to `assets/css/**`, includes `assets/css/fonts.css`
  - Minifies CSS/JS; bundle includes all CSS/JS + vendor assets from node_modules
  - Verified with `npm run build:assets` and `npm run bundle`
- Enqueue strategy
  - Per‑template mapping; production uses minified assets; versioning via filemtime
  - Glide.js CDN fallback to avoid 404s
- WordPress features
  - Minimal `theme.json` tokens; schema integration classes; editor loads theme CSS

## Not Yet Completed (requires adjustment)
- Sass modernization
  - Repository still uses `@import` broadly; Dart Sass deprecations visible during build
- Function prefixing
  - Menu helper/render functions in `inc/menu-helpers.php` and uses in `header.php` remain unprefixed (should be `mia_aesthetics_*`)
- Accessibility: Skip link
  - No global skip link present after `wp_body_open()` in `header.php`
- Duplicate SCSS
  - `page-hero-canvas.scss` and `page-hero-canvas-no-bc.scss` are effectively the same; not consolidated
- PHPCS PHPCompatibility range
  - `phpcs.xml` still targets `8.1-8.3` (local runtime is PHP 8.4)
- SCSS linting baseline
  - Stylelint reports many low‑signal issues (property order, vendor prefix), and a deprecated rule warning
- Prettier scope
  - Prettier checks compiled CSS under `assets/css/` which should be ignored (sources live in `assets/scss/`)

## Actions To Greenlight QA
1) Prettier scope
- Add `.prettierignore` for `assets/css/` (or update scripts to format only SCSS/JS sources)

2) Stylelint clean‑up
- Run `npm run lint:scss:fix`
- Add/adjust `.stylelintrc` to reduce noise (ordering, vendor prefixes handled by PostCSS), and replace deprecated `scss/at-import-no-partial-leading-underscore` with `load-no-partial-leading-underscore`

3) Migrate Sass `@import` → `@use/@forward`
- Replace imports across `assets/scss/**`; keep explicit namespaces; adopt curated Bootstrap imports

4) Prefix helpers
- Prefix functions in `inc/menu-helpers.php`/`inc/menus.php` and update calls in `header.php`

5) Add skip link
- Insert `<a href="#primary" class="skip-link">Skip to content</a>` after `wp_body_open()` and include minimal focus-visible CSS

6) Consolidate duplicate SCSS
- Unify page‑hero canvas variants into a single stylesheet

## Recommended Performance Improvements
- Curate Bootstrap imports (reduce theme CSS size)
- Font Awesome optimization (subset or inline SVGs)
- Preload Inter/Montserrat WOFF2
- Add `poster` and mobile lazy conditions to background video

## Build/Bundle Verification
- Build: `npm run build:assets`
- Bundle: `npm run bundle`
- Latest bundle included: 300+ files, with template CSS/JS and 10 vendor assets (Bootstrap, FA, Glide)

## Next Steps (proposed order)
1. Fix Prettier scope and run `npm run format`
2. Run `npm run lint:scss:fix` and adjust `.stylelintrc`
3. Add skip link
4. Prefix menu helper functions and update references
5. Consolidate duplicate SCSS for page‑hero canvas
6. Migrate Sass to `@use/@forward`

This reflects the real, reproducible status as of the latest QA run. If you’ve already landed some of these items locally, I can re‑run QA after pulling those changes and update this status again.
