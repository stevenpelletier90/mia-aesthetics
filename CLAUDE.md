# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a custom WordPress theme for Mia Aesthetics, a medical aesthetics clinic. The theme is built with Bootstrap 5, uses Advanced Custom Fields (ACF) for content management, and follows WordPress coding standards. **PROJECT STATUS: 100% COMPLETE** - All core features implemented, build pipeline optimized, and production-ready.

## Development Commands

### Primary Build Commands

```bash
# Complete asset build (CSS minification)
npm run build:assets

# Bundle theme for WordPress deployment
npm run bundle

# Full production build with QA
npm run build:production

# Clean development build (recommended for daily work)
npm run dev:clean
```

### Quality Assurance

```bash
# Run all frontend QA (JS, CSS, Prettier)
npm run qa:fe

# Run all QA (frontend + PHP)
npm run qa:all

# PHP QA via Composer
composer qa
```

### Individual Asset Processing

```bash
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

# Formatting
npm run format              # Format all assets (CSS + JS)
npm run format:js           # Format JavaScript only  
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

### CSS-Only Architecture

- **CSS Sources**: `/assets/css/` - Direct CSS files with modular organization
  - **Base styles**: `base.css`, `theme.css`, `fonts.css` - Core theme styling
  - **Layout components**: `/assets/css/layout/` - Header, footer, hero sections
  - **UI components**: `/assets/css/components/` - Reusable UI components (case-card, FAQ, forms, CTAs)
  - **Template files**: `/assets/css/templates/` - Page-specific, archive, and single post styles
  - **Utility styles**: `/assets/css/utilities/` - Helper classes and specialized functionality
- **Theme Bundle**: `/theme-bundle/mia-aesthetics/` - Production WordPress theme
- **Project Directory**: Individual CSS files edited directly, minified versions generated automatically

### Build Pipeline (CSS Minification)

- **CSS Processing**: Direct CSS minification using PostCSS pipeline (autoprefixer + cssnano)
- **Individual Files**: Each CSS file minified separately via `minify-assets.js` script
- **Vendor Prefixes**: Automatically added via autoprefixer (supports >0.2%, last 3 versions)
- **Minification**: cssnano with default preset for optimal compression
- **No Compilation**: CSS files edited directly, no preprocessing step required

### Asset Management

- **Conditional Loading**: Individual CSS files loaded based on current page template via `/inc/enqueue.php`
- **Versioning**: File modification time-based cache busting
- **Exclusions**: Vendor assets (bootstrap, fontawesome, glide) excluded from minification
- **Individual Files**: Each template and component has its own CSS file for optimal performance
- **Bundle Optimization**: Minified CSS files generated for production use

### Code Quality Configuration

- **ESLint**: Modern JavaScript linting with Prettier integration
- **PHPCS**: WordPress coding standards (PHP 8.4 compatible)
- **PHPStan**: Level 7 static analysis for WordPress
- **Prettier**: CSS and JavaScript formatting with `.prettierignore` (excludes minified assets)

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

# 2. Make changes in /assets/css/ (direct CSS editing)
# 3. Build and bundle for development
npm run dev:clean

# 4. Deploy /theme-bundle/mia-aesthetics/ as your WordPress theme
```

### CSS-Only Development Process

1. **Edit CSS files**: `/assets/css/` - Edit individual CSS files directly (no preprocessing)
2. **Build assets**: `npm run build:assets` (CSS minification with PostCSS autoprefixer)
3. **Bundle theme**: `npm run bundle` (copies to `/theme-bundle/mia-aesthetics/`)
4. **Quality check**: `npm run qa:all` (linting, formatting, PHP analysis)
5. **Deploy**: Use bundled theme folder for WordPress installation

### Key Build Pipeline Features

- **PostCSS Integration**: Unified autoprefixer + cssnano across all CSS files
- **Direct Editing**: No compilation step - edit CSS files directly in `/assets/css/`
- **Vendor Exclusions**: Bootstrap, FontAwesome, and Glide.js assets preserved
- **Individual Files**: Each component and template has its own CSS file for optimal loading
- **Lean Bundles**: Minified assets generated for production use

### CSS-Only Architecture (Production Ready)

- All CSS files organized modularly (base, layout, components, templates, utilities)
- Individual CSS files optimized with autoprefixer and minification
- Complete build pipeline with PostCSS integration
- Streamlined development workflow with direct CSS editing
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

## Recent Simplification (v4.0.0)

### CSS-Only Migration

- **SCSS Removal**: Reverted from SCSS back to direct CSS editing for simpler workflow
- **Modular CSS**: Individual CSS files organized by function (base, layout, components, templates, utilities)
- **Bootstrap Integration**: Uses pre-compiled Bootstrap CSS with custom overrides
- **Direct Editing**: No preprocessing step - edit CSS files directly in `/assets/css/`

### Build Pipeline Simplification

- **PostCSS Integration**: Unified autoprefixer + cssnano configuration for CSS minification only
- **Script Consolidation**: `minify-assets.js` handles both CSS and JS minification consistently
- **Eliminated Compilation**: Removed SCSS compilation step for faster development workflow
- **Individual File Processing**: Each CSS file minified separately for optimal loading
- **Vendor Prefix Automation**: Automatic vendor prefix generation via autoprefixer

### Configuration Streamlining

- **Reduced Dependencies**: Removed SCSS-related packages and configuration
- **Prettier Scoping**: Added `.prettierignore` to exclude minified assets from formatting
- **Simplified Workflow**: Direct CSS editing with minification-only build process  
- **PHP 8.4 Support**: Maintained PHPCS configuration for latest PHP compatibility

### Quality Assurance Excellence

- **Zero Errors**: PHPCS passing (64/64 files), PHPStan level 7, ESLint clean
- **Function Prefixing**: All functions properly prefixed with `mia_aesthetics_*`
- **Accessibility Compliance**: Skip links with focus-visible styles implemented
- **Performance Optimized**: Conditional enqueuing, proper caching, lean asset bundles

## Technical Reference Documentation

Quick reference links for all packages and technologies used in this project:

### WordPress Core Documentation

- **[Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)** - WordPress template file structure and naming
- **[Template Partials](https://developer.wordpress.org/themes/basics/template-files/#template-partials)** - Header, footer, and component organization
- **[Coding Standards](https://developer.wordpress.org/coding-standards/)** - WordPress PHP, CSS, and JavaScript standards
- **[Theme Development](https://developer.wordpress.org/themes/)** - Complete theme development guide
- **[Function Reference](https://developer.wordpress.org/reference/)** - WordPress core functions and hooks
- **[Enqueuing Scripts & Styles](https://developer.wordpress.org/themes/basics/including-css-javascript/)** - Asset loading best practices

### Frontend Frameworks

- **[Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)** - Components, utilities, and grid system
- **[Bootstrap 5 Components](https://getbootstrap.com/docs/5.3/components/)** - UI components (navbar, dropdown, offcanvas, etc.)
- **[Bootstrap 5 Utilities](https://getbootstrap.com/docs/5.3/utilities/)** - Spacing, positioning, and layout utilities
- **[FontAwesome Icons](https://fontawesome.com/icons)** - Icon library and usage examples
- **[Glide.js Documentation](https://glidejs.com/)** - Carousel/slider component documentation

### Build Tools & Processing

- **[PostCSS Documentation](https://postcss.org/)** - CSS processing and plugin system
- **[Autoprefixer](https://github.com/postcss/autoprefixer)** - Automatic vendor prefix generation
- **[CSSNano](https://cssnano.co/)** - CSS optimization and minification
- **[PurgeCSS](https://purgecss.com/)** - Remove unused CSS for production builds
- **[Terser](https://terser.org/)** - JavaScript minification and optimization

### Development Tools

- **[ESLint](https://eslint.org/)** - JavaScript linting and code quality
- **[Stylelint](https://stylelint.io/)** - CSS linting and style guide enforcement
- **[Prettier](https://prettier.io/)** - Code formatting for CSS and JavaScript
- **[PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)** - PHP code standards checking
- **[PHPStan](https://phpstan.org/)** - PHP static analysis tool

### Content Management

- **[Advanced Custom Fields](https://www.advancedcustomfields.com/resources/)** - Custom field management and templating
- **[ACF Field Types](https://www.advancedcustomfields.com/resources/field-types/)** - Available field types and usage
- **[Schema.org](https://schema.org/)** - Structured data markup standards
- **[JSON-LD](https://json-ld.org/)** - Linked data format for SEO

### Web Standards & Performance

- **[Web Content Accessibility Guidelines (WCAG)](https://www.w3.org/WAI/WCAG21/quickref/)** - Accessibility standards
- **[MDN CSS Reference](https://developer.mozilla.org/en-US/docs/Web/CSS)** - CSS properties and modern features
- **[MDN JavaScript Reference](https://developer.mozilla.org/en-US/docs/Web/JavaScript)** - Modern JavaScript features and APIs
- **[Can I Use](https://caniuse.com/)** - Browser compatibility tables

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

**Last Updated**: September 2025 - v4.0.0 CSS-Only Release
**Status**: 100% Complete - Simplified CSS-only workflow, all requirements implemented and optimized
