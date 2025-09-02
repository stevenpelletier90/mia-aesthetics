# Repository Guidelines

## Project Structure & Module Organization

- Root PHP templates live at `/` (e.g., `archive-*.php`, `single-*.php`, `page-*.php`).
- Reusable logic in `inc/` (helpers, queries, enqueue, theme support) and UI pieces in `components/`.
- Front-end assets in `assets/` (`css/`, `js/`, fonts, data). Minified files are generated on demand; WordPress-ready bundles are output under `theme-bundle/`.
- Lint/type configs: `phpcs.xml`, `phpstan.neon`, `eslint.config.js`, `.stylelintrc.json`, `.prettierignore`.

## Build, Test, and Development Commands

- `npm run lint`: Runs JS (ESLint), SCSS (Stylelint + Prettier check), and PHP (PHPCS + PHPStan).
- `npm run format`: Formats `assets/**/*.{css,js,scss}` via Prettier (compiled CSS excluded by `.prettierignore`).
- `composer qa`: Auto-fixes trivial PHP issues (PHPCBF), then runs PHPCS and PHPStan.
- `npm run build:assets`: Builds SCSS → CSS, runs PostCSS (autoprefixer + cssnano), and minifies CSS/JS.
- `npm run bundle`: Generates a distributable theme into `theme-bundle/mia-aesthetics/` (ships only minified runtime assets by default; add `--with-maps` to include source maps).

## Coding Style & Naming Conventions

- PHP: Follow WordPress Coding Standards (WPCS). Prefer long array syntax and WordPress escaping APIs (`esc_html`, `esc_attr`, `wp_kses_post`).
- Prefix: Use `mia_aesthetics_` for functions/hooks to avoid collisions.
- JS: ESLint recommended rules; no unused vars; prefer `const`; browser globals allowed in `assets/js/`. Node scripts under `scripts/` are linted too.
- CSS/JS formatting: Prettier for sources. Indentation: 2 spaces for JS/CSS; PHP per WPCS.
- Files: WordPress template naming (`archive-*.php`, `single-*.php`); keep helpers in `inc/`.

## Testing Guidelines

- Static analysis: `composer phpstan` (level 7) with WP stubs; fix reported issues or add narrow ignores when intentional.
- Linting is required to pass (PHPCS + ESLint + Stylelint + Prettier check). Aim for zero warnings before opening a PR.
- Manual QA: verify key templates (home, archive, single, search, 404) and critical CTAs render as expected. No E2E test runner is wired into scripts.

## Commit & Pull Request Guidelines

- Commits: Imperative mood, scoped and descriptive (e.g., "archive: fix pagination bounds"). Group related changes.
- PRs: Include summary, reasoning, and before/after screenshots for template/UI changes. Link issues. Add testing notes and a quick QA checklist.
- CI surrogate: Run `npm run qa:all` locally and ensure `npm run bundle` succeeds. Do not commit ZIP bundles; committing `theme-bundle/` contents is discouraged unless explicitly requested.

## Security & Configuration Tips

- Target PHP 8.4 (see `phpcs.xml` and local runtime). Always escape output, validate/sanitize input, and use nonces for state-changing actions.
- Exclude third-party/vendor code from scans; keep dependencies updated with minimal footprint in bundles.
