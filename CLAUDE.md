# CLAUDE.md

This is the single, canonical project document for the Mia Aesthetics WordPress theme. It is written for AI-assisted development and should stay current as the codebase evolves.

## Project Overview

- Custom WordPress theme using Bootstrap 5 and ACF
- CSS-only workflow (no SCSS); modular CSS per template/component
- Full build system with CSS/JS processing and theme bundling
- Uses npm dependencies (Bootstrap, FontAwesome, Glide) via vendor assets

## Available Commands

```bash
# Build commands
npm run build:css      # Process and minify CSS files
npm run build:js       # Process and minify JavaScript files
npm run build:vendor   # Copy vendor assets from node_modules
npm run build:all      # Run all build commands
npm run bundle         # Build and bundle theme for deployment
npm run bundle:dev     # Bundle with source maps for development

# CSS utilities
npm run purge:css      # Remove unused CSS classes
```

## Asset Structure

- CSS sources: `assets/css/` (base, layout, components, templates, utilities)
- JavaScript sources: `assets/js/` (organized by template type)
- Vendor assets: `assets/vendor/` (Bootstrap, FontAwesome, Glide from node_modules)
- WordPress enqueuing handles .min file preference when available

## Build System & Bundling

The theme includes a complete build system via `scripts/` directory:

- `build-css.js`: Processes CSS with PostCSS, autoprefixer, and minification
- `build-js.js`: Minifies JavaScript with Terser
- `build-vendor.js`: Copies vendor assets from node_modules to `assets/vendor/`
- `bundle-theme.js`: Creates `theme-bundle/mia-aesthetics/` for WordPress deployment
- Provides easy drag-and-drop SFTP deployment with curated vendor assets

## WordPress Asset Enqueue

- Main logic: `inc/enqueue.php`
- Automatic `.min` preference: when `SCRIPT_DEBUG` is false, the theme prefers `.min.css`/`.min.js` if present; falls back to non-minified if not
- Versioning: `filemtime`-based cache busting
- Conditional loading: per-template CSS/JS mapped in `mia_get_template_mappings()` and loaded based on context (front-page, archives, singles, etc.)
- Global assets: fonts, Bootstrap, base, header, footer; component CSS loaded on-demand

## Quality Standards

- ESLint config: `eslint.config.js` (browser globals for assets; Node for `scripts/`)
- Stylelint config: `.stylelintrc.json` (minified/vendor ignored)
- Prettier sources: `.prettierignore` excludes compiled/minified assets
- PHPCS: `phpcs.xml` (WordPress Coding Standards + PHPCompatibility for PHP 8.4)
- PHPStan: `phpstan.neon` (level 7 with WP/ACF/Yoast stubs)

## Technical Reference Documentation

- WordPress
  - Template Hierarchy: <https://developer.wordpress.org/themes/basics/template-hierarchy/>
  - Including CSS/JS: <https://developer.wordpress.org/themes/basics/including-css-javascript/>
  - Coding Standards: <https://developer.wordpress.org/coding-standards/>
- Frontend Frameworks
  - Bootstrap 5: <https://getbootstrap.com/docs/5.3/>
  - FontAwesome Icons: <https://fontawesome.com/icons>
  - Glide.js: <https://glidejs.com/>
- Build Tools & Processing
  - PostCSS: <https://postcss.org/>
  - Autoprefixer: <https://github.com/postcss/autoprefixer>
  - CSSNano: <https://cssnano.co/>
  - Terser: <https://terser.org/>
- Dev Tooling
  - ESLint: <https://eslint.org/>
  - Stylelint: <https://stylelint.io/>
  - Prettier: <https://prettier.io/>
  - PHP CodeSniffer: <https://github.com/squizlabs/PHP_CodeSniffer>
  - PHPStan: <https://phpstan.org/>
- Content & SEO
  - Advanced Custom Fields: <https://www.advancedcustomfields.com/resources/>
  - Schema.org: <https://schema.org/>
  - JSON‑LD: <https://json-ld.org/>
- Web Standards
  - WCAG: <https://www.w3.org/WAI/WCAG21/quickref/>
  - MDN CSS: <https://developer.mozilla.org/en-US/docs/Web/CSS>
  - MDN JavaScript: <https://developer.mozilla.org/en-US/docs/Web/JavaScript>
  - Can I Use: <https://caniuse.com/>

## Architecture & Key Systems

- Root templates: `page-*.php`, `single-*.php`, `archive-*.php`
- Reusable logic under `inc/` (enqueue, schema, queries, helpers, theme support)
- Components: reusable UI in `/components/`
- Schema.org: class-based JSON‑LD in `inc/schema/` (Organization, Surgeon, Clinic, FAQ)
- Helpers: state abbreviations, media utilities, menus, template helpers

## Performance & Accessibility

- Stylelint: ensures CSS code quality and consistency
- Conditional enqueuing: loads the minimum CSS/JS per context
- Optional next steps: trim Bootstrap surface, subset Font Awesome, preload key WOFF2 fonts
- Accessibility: skip link present; focus-visible styles in base CSS

## MCP Quick Start (Claude Code)

Model Context Protocol (MCP) servers help Claude Code index your project and run utilities. These optional commands configure common servers.

Scopes

- Local (default): current project only
- Project: shared via `.mcp.json`
- User: available across all projects on the machine

Filesystem Server

```bash
# WSL/Bash (local)
claude mcp add filesystem -- npx -y @modelcontextprotocol/server-filesystem "."

# Windows PowerShell (local)
claude mcp add filesystem -- cmd /c -- npx -y @modelcontextprotocol/server-filesystem "."
```

Git Server

```bash
# WSL/Bash
claude mcp add git -- uvx mcp-server-git

# Windows PowerShell
claude mcp add git -- cmd /c -- uvx mcp-server-git
```

Puppeteer (optional) and Sequential Thinking

```bash
# WSL/Bash
claude mcp add puppeteer -- npx -y @modelcontextprotocol/server-puppeteer
claude mcp add sequential-thinking -- npx -y @modelcontextprotocol/server-sequential-thinking

# Windows PowerShell
claude mcp add puppeteer -- cmd /c -- npx -y @modelcontextprotocol/server-puppeteer
claude mcp add sequential-thinking -- cmd /c -- npx -y @modelcontextprotocol/server-sequential-thinking
```

Management

```bash
claude mcp list
claude mcp get <server>
claude mcp remove <server>
```

Notes

- PowerShell typically needs the `cmd /c --` wrapper before `npx`/`uvx`
- Use project scope (`-s project`) to share configs via `.mcp.json`
- Security: only add MCP servers you trust

## Troubleshooting

- CSS not updating: edit in `assets/css/`, run `npm run build:css`, purge browser caches
- Build errors: check `scripts/` directory contains all build files

## CRITICAL TEMPLATE VERIFICATION RULES

Never flag these as missing from static HTML templates; they’re injected by PHP:

- FAQ sections
- Pricing information
- H1 headings

## Important Instruction Reminders

- Do only what’s requested; avoid scope creep
- Prefer editing existing files over creating new ones
- Do not create extra documentation—this CLAUDE.md is the single canonical doc (create docs only if explicitly requested)

---

Last Updated: September 2025 — Updated to reflect current codebase state with active build system
