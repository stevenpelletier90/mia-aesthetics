# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a custom WordPress theme for Mia Aesthetics, a medical aesthetics clinic. The theme is built with Bootstrap 5, uses Advanced Custom Fields (ACF) for content management, and follows WordPress coding standards.

## Development Commands

### Linting and Code Quality
```bash
# Run all linters
npm run lint

# JavaScript linting
npm run lint:js
npm run lint:js:fix  # Auto-fix issues

# CSS linting and formatting
npm run lint:css
npm run format:css  # Auto-format CSS

# PHP linting (WordPress standards)
composer run-script phpcs
composer run-script phpcbf  # Auto-fix PHP issues

# PHP static analysis
composer run-script phpstan
```

### CSS Processing
```bash
# Minify all CSS files
npm run minify:css

# Test minification on a single file
npm run minify:css:test
```

### Formatting
```bash
# Format all assets
npm run format

# Check formatting without changes
npm run format:check
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
- **Page Templates**: Multiple custom page templates in root directory (page-*.php)
- **Archive Templates**: Custom archives for each post type (archive-*.php)
- **Single Templates**: Custom single views for each post type (single-*.php)
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
- **Custom Buttons**: Mia button system with variants (gold, black) and sizes
- **Hero Sections**: Dynamic hero components with video backgrounds
- **Tabbed Content**: Procedure navigation with desktop tabs and mobile dropdown

### Key Helper Systems
1. **State Abbreviations** (`/inc/state-abbreviations.php`): US state lookup utilities
2. **Media Helpers** (`/inc/media-helpers.php`): Video processing and gallery utilities
3. **Menu System** (`/inc/menus.php`, `/inc/menu-helpers.php`): Custom menu rendering with caching
4. **Template Helpers** (`/inc/template-helpers.php`): UI and template utility functions

## Important Files and Patterns

### CSS Organization
- Base styles: `/assets/css/base.css`
- Template-specific styles: `/assets/css/[template-name].css`
- Component styles: Named to match their purpose (e.g., `consultation-cta.css`)

### JavaScript Organization
- Template-specific scripts: `/assets/js/[template-name].js`
- Follows same naming convention as CSS files

### WordPress Standards
- Uses WordPress coding standards for PHP (enforced via PHPCS)
- PHPStan configured for WordPress with strict rules
- Proper escaping and sanitization required

## Before/After Gallery System
The theme includes a JSON-based gallery system (`/assets/data/before-after-gallery.json`) for managing before/after images, likely used in case studies and procedure pages.

## Development Notes

- Always run `composer install` and `npm install` before starting development
- Use `composer run-script phpcs` before committing PHP changes
- The theme expects ACF Pro to be installed and configured
- Custom post types are managed externally (likely via plugin or ACF)
- File modifications automatically bust cache via versioning system