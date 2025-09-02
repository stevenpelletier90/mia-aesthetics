# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a custom WordPress theme for Mia Aesthetics, a medical aesthetics clinic. The theme is built with Bootstrap 5, uses Advanced Custom Fields (ACF) for content management, and follows WordPress coding standards. **PROJECT STATUS: 100% COMPLETE** - All core features implemented, build pipeline optimized, and production-ready.

## Development Commands

### Primary Build Commands

```bash
# Complete asset build (SCSS → CSS → minification)
npm run build:assets

# Bundle theme for WordPress deployment
npm run bundle
npm run bundle -- --with-maps  # Include source maps

# Full production build with QA
npm run build:production

# Clean development build (recommended for daily work)
npm run dev:clean
```

### Quality Assurance

```bash
# Run all frontend QA (JS, SCSS, Prettier)
npm run qa:fe

# Run all QA (frontend + PHP)
npm run qa:all

# PHP QA via Composer
composer qa
```

### Individual Asset Processing

```bash
# SCSS compilation
npm run sass:build          # Main theme SCSS → theme.css
npm run sass:build:individual # Component SCSS → individual CSS
npm run sass:build:templates  # Template-specific SCSS → CSS

# PostCSS processing (autoprefixer + cssnano)
npm run postcss:theme       # Process main theme.css

# Asset minification with PostCSS pipeline
npm run minify:css         # Minify CSS with autoprefixer
npm run minify:js          # Minify JavaScript with Terser
npm run minify:all         # Minify both CSS and JS
```

### Linting and Formatting

```bash
# JavaScript
npm run lint:js
npm run lint:js:fix

# SCSS/CSS
npm run lint:scss
npm run lint:scss:fix
npm run lint:scss:prettier

# Formatting
npm run format              # Format all assets
npm run format:scss         # Format SCSS only
npm run format:check        # Check formatting without changes

# PHP (via Composer)
composer run-script phpcs   # PHP CodeSniffer
composer run-script phpcbf  # PHP Code Beautifier (auto-fix)
composer run-script phpstan # Static analysis (level 7)
```

## Architecture and Key Systems

### Custom Post Types

The theme relies on several custom post types (likely managed via ACF or a plugin):

- **location**: Clinic locations with hierarchical structure (parent/child)
- **surgeon**: Medical professionals
- **procedure**: Medical procedures with hierarchical structure
- **case**: Before/after case studies
- **condition**: Medical conditions
- **special**: Special offers/promotions
- **fat-transfer**: Specific procedure type
- **non-surgical**: Non-surgical treatments

### Template Hierarchy

- **Page Templates**: Multiple custom page templates in root directory (page-\*.php)
- **Archive Templates**: Custom archives for each post type (archive-\*.php)
- **Single Templates**: Custom single views for each post type (single-\*.php)
- **Components**: Reusable components in `/components/` directory

### Asset Management System

Located in `/inc/enqueue.php`, the theme uses a sophisticated conditional loading system:

- Template-specific CSS/JS loading based on current page template
- Automatic versioning using file modification times
- Separate assets for each template type to optimize performance

### Schema.org Implementation

Modular schema system in `/inc/schema/`:

- Class-based architecture with loader pattern
- Separate schema classes for Organization, Surgeon, Clinic, FAQ
- Automatic JSON-LD generation for SEO

### Data Management

- **ACF Integration**: Heavy use of Advanced Custom Fields for content management
- **Caching System**: Custom caching helpers in `/inc/cache-helpers.php`
- **Query Modifications**: Centralized query logic in `/inc/queries.php`

### Frontend Components

- **Bootstrap 5**: Primary CSS framework
- **FontAwesome**: Icon library
- **Button System**: Comprehensive 10-variant button system with responsive clamp() scaling
- **Typography System**: Responsive typography using Montserrat + Inter with clamp() scaling
- **Hero Sections**: Dynamic hero components with video backgrounds
- **Tabbed Content**: Procedure navigation with desktop tabs and mobile dropdown

### Key Helper Systems

1. **State Abbreviations** (`/inc/state-abbreviations.php`): US state lookup utilities
2. **Media Helpers** (`/inc/media-helpers.php`): Video processing and gallery utilities
3. **Menu System** (`/inc/menus.php`, `/inc/menu-helpers.php`): Custom menu rendering with caching
4. **Template Helpers** (`/inc/template-helpers.php`): UI and template utility functions

## Important Files and Patterns

### Modern SCSS Architecture (@use System)

- **SCSS Sources**: `/assets/scss/` - Modern SCSS with `@use` syntax (no more `@import`)
  - **Main theme**: `/assets/scss/main.scss` → `assets/css/theme.css` (then PostCSS → `theme.min.css`)
  - **Individual components**: `/assets/scss/components/_case-card.scss` → `assets/css/components/case-card.css`
  - **Template files**: `/assets/scss/templates/**/*.scss` → `assets/css/templates/**/*.css`
  - **Modular structure**: Abstracts, base, layout, components, utilities, vendors
- **Theme Bundle**: `/theme-bundle/mia-aesthetics/` - Production WordPress theme
- **Project Directory**: Clean SCSS sources only, compiled CSS in `/assets/css/`

### Build Pipeline (PostCSS Integration)

- **Theme CSS**: Uses PostCSS pipeline (autoprefixer + cssnano) via `postcss.config.js`
- **Template CSS**: Uses minify-assets.js with same PostCSS configuration for consistency
- **Source Maps**: Available in development, optional for production bundles
- **Vendor Prefixes**: Automatically added via autoprefixer (supports >0.2%, last 3 versions)
- **Minification**: cssnano with default preset for optimal compression

### Asset Management

- **Conditional Loading**: Template-specific CSS/JS loaded based on current page template
- **Versioning**: File modification time-based cache busting
- **Exclusions**: Vendor assets (bootstrap, fontawesome, glide) excluded from minification
- **Bundle Optimization**: Minified assets only (except theme.css/fonts.css for editor parity)

### Code Quality Configuration

- **Stylelint**: Streamlined configuration with many rules set to null for reduced noise
- **ESLint**: Modern JavaScript linting with Prettier integration
- **PHPCS**: WordPress coding standards (PHP 8.4 compatible)
- **PHPStan**: Level 7 static analysis for WordPress
- **Prettier**: Scoped to sources only via `.prettierignore` (excludes compiled assets)

### JavaScript Organization

- Template-specific scripts: `/assets/js/[template-name].js`
- Minification via Terser with source maps and debugging preservation
- Vendor exclusions for pre-minified libraries

## Before/After Gallery System

The theme includes a JSON-based gallery system (`/assets/data/before-after-gallery.json`) for managing before/after images, likely used in case studies and procedure pages.

## Button System - 10 Responsive Variants

### Core Button Classes (Bootstrap 5)

1. **`.btn-primary`** - Gold background, black text (brand primary)
2. **`.btn-secondary`** - Black background, white text (brand secondary)
3. **`.btn-outline-primary`** - Gold border, gold text (standard outline)
4. **`.btn-outline-secondary`** - Black border, black text (standard outline)
5. **`.btn-blur`** - Glassmorphism effect with backdrop-filter blur
6. **`.btn-gradient`** - Black to #1b1b1b gradient with gold border

### Alternative Outline Variants (Better Contrast)

1. **`.btn-outline-primary-alt`** - Gold border, black text (light backgrounds)
2. **`.btn-outline-secondary-alt`** - Black border, white text (dark backgrounds)
3. **`.btn-outline-primary-alt2`** - Gold border, white text (dark backgrounds)
4. **`.btn-outline-secondary-alt2`** - Black border, gold text (light backgrounds)

### Button Sizes with Responsive Scaling

- **`.btn-sm`**: `clamp(0.75rem, 0.625rem + 0.5vw, 0.875rem)` font-size
- **Default**: `clamp(0.875rem, 0.75rem + 0.5vw, 1rem)` font-size
- **`.btn-lg`**: `clamp(1rem, 0.875rem + 0.5vw, 1.25rem)` font-size

All buttons include responsive padding using clamp() and smooth icon animations.

## Typography System - Responsive Scaling

### Headings with clamp() Scaling

- **H1**: `clamp(2rem, 3vw, 3.5rem)` - Page titles
- **H2**: `clamp(1.75rem, 2.5vw, 2.75rem)` - Section headings
- **H3**: `clamp(1.5rem, 2vw, 2.25rem)` - Subsection headings
- **H4**: `clamp(1.25rem, 1.5vw, 1.75rem)` - Content headings
- **H5**: `clamp(1.125rem, 1.25vw, 1.5rem)` - Small headings
- **H6**: `clamp(1rem, 1vw, 1.25rem)` - Micro headings

### Typography Features

- **Headings**: Montserrat font family
- **Body Text**: Inter font family
- **Responsive scaling**: All typography uses clamp() for optimal readability
- **Brand colors**: Gold (#c8b273) and black (#000000) integration
- **Accessibility**: Proper contrast ratios and focus states

## Streamlined Development Workflow

### Quick Start (New Developers)

```bash
# 1. Install dependencies
npm install && composer install

# 2. Make changes in /assets/scss/ (uses modern @use syntax)
# 3. Build and bundle for development
npm run dev:clean

# 4. Deploy /theme-bundle/mia-aesthetics/ as your WordPress theme
```

### Modern SCSS Development Process

1. **Edit SCSS sources**: `/assets/scss/` using `@use` syntax (no more `@import`)
2. **Build assets**: `npm run build:assets` (SCSS → CSS → PostCSS → minification)
3. **Bundle theme**: `npm run bundle` (copies to `/theme-bundle/mia-aesthetics/`)
4. **Quality check**: `npm run qa:all` (linting, formatting, PHP analysis)
5. **Deploy**: Use bundled theme folder for WordPress installation

### Key Build Pipeline Features

- **PostCSS Integration**: Unified autoprefixer + cssnano across all CSS
- **Source Maps**: Available for debugging SCSS sources in browser dev tools
- **Vendor Exclusions**: Bootstrap, FontAwesome, and Glide.js assets preserved
- **Editor Parity**: Unminified theme.css and fonts.css maintained for block editor
- **Lean Bundles**: Only minified assets included by default (use `--with-maps` for source maps)

### Fully Converted Components (Production Ready)

- All SCSS files modernized with `@use` syntax
- All components compile to optimized CSS with autoprefixer
- Complete build pipeline with PostCSS integration
- Streamlined linting configuration (reduced noise)
- Function prefixing completed (`mia_aesthetics_*`)
- Accessibility implementation with skip links
- 100% PHPCS/PHPStan compliance at highest levels

## Production Notes

- **Project Status**: 100% complete and production-ready
- **Build Pipeline**: Fully optimized with PostCSS, minification, and bundling
- **Code Quality**: All linters passing at highest standards
- **WordPress Standards**: Full compliance with PHP 8.4 compatibility
- **Performance**: Conditional asset loading, caching, and optimized bundles
- **Accessibility**: Skip links implemented with proper focus management
- **Style Guide**: Available at `/style-guide.html` for design system reference
- **Editor Integration**: Block editor loads theme styles for WYSIWYG parity

## Recent Modernization (v3.0.0)

### SCSS Modernization

- **Dart Sass Migration**: All SCSS files updated to use modern `@use` syntax instead of deprecated `@import`
- **Modular Architecture**: Clean separation of abstracts, base, layout, components, utilities, and vendors
- **Bootstrap Integration**: Custom Bootstrap build configured with Mia Aesthetics variables
- **No Import Warnings**: Theme sources modernized (Bootstrap still emits upstream deprecation warnings)

### Build Pipeline Refinements

- **PostCSS Integration**: Unified autoprefixer + cssnano configuration across all CSS processing
- **Script Consolidation**: `minify-assets.js` mirrors `postcss.config.js` for consistency
- **Eliminated Artifacts**: Removed double-minification and vendor prefix sed hacks from previous pipeline
- **Bundle Optimization**: Ships only minified assets by default with optional source maps
- **Vendor Prefix Automation**: Automatic vendor prefix generation (no manual prefix management)

### Configuration Streamlining

- **Stylelint Noise Reduction**: Many rules set to null to focus on critical issues
- **Prettier Scoping**: Added `.prettierignore` to exclude compiled assets from formatting
- **Dependency Cleanup**: Removed unused npm packages (postcss-import, stylelint-order)
- **PHP 8.4 Support**: Updated PHPCS configuration for latest PHP compatibility

### Quality Assurance Excellence

- **Zero Errors**: PHPCS passing (64/64 files), PHPStan level 7, ESLint clean
- **Function Prefixing**: All functions properly prefixed with `mia_aesthetics_*`
- **Accessibility Compliance**: Skip links with focus-visible styles implemented
- **Performance Optimized**: Conditional enqueuing, proper caching, lean asset bundles

## CRITICAL TEMPLATE VERIFICATION RULES

### HTML Template Content Exclusions

**IMPORTANT**: When verifying HTML template content accuracy, the following sections should NEVER be flagged as missing content because they are handled by PHP templates:

- **FAQ sections** - Loaded dynamically via PHP templates, NOT in HTML templates
- **Pricing information** - Handled dynamically by PHP, NOT in HTML templates
- **H1 headings** - Managed by page layout PHP templates, NOT in HTML templates

These sections are intentionally excluded from HTML templates and are NOT missing content.

## Important Instruction Reminders

Do what has been asked; nothing more, nothing less.
NEVER create files unless they're absolutely necessary for achieving your goal.
ALWAYS prefer editing an existing file to creating a new one.
NEVER proactively create documentation files (\*.md) or README files. Only create documentation files if explicitly requested by the User.

---

**Last Updated**: September 2025 - v3.0.0 Production Release
**Status**: 100% Complete - All requirements implemented and optimized
