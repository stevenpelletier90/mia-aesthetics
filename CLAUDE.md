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

## Button System - 10 Responsive Variants

### Core Button Classes (Bootstrap 5)
1. **`.btn-primary`** - Gold background, black text (brand primary)
2. **`.btn-secondary`** - Black background, white text (brand secondary)
3. **`.btn-outline-primary`** - Gold border, gold text (standard outline)
4. **`.btn-outline-secondary`** - Black border, black text (standard outline)
5. **`.btn-blur`** - Glassmorphism effect with backdrop-filter blur
6. **`.btn-gradient`** - Black to #1b1b1b gradient with gold border

### Alternative Outline Variants (Better Contrast)
7. **`.btn-outline-primary-alt`** - Gold border, black text (light backgrounds)
8. **`.btn-outline-secondary-alt`** - Black border, white text (dark backgrounds)
9. **`.btn-outline-primary-alt2`** - Gold border, white text (dark backgrounds)
10. **`.btn-outline-secondary-alt2`** - Black border, gold text (light backgrounds)

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

## Development Notes

- Always run `composer install` and `npm install` before starting development
- Use `composer run-script phpcs` before committing PHP changes
- The theme expects ACF Pro to be installed and configured
- Custom post types are managed externally (likely via plugin or ACF)
- File modifications automatically bust cache via versioning system
- **Style Guide**: Available at `/style-guide.html` for design system reference

# CRITICAL TEMPLATE VERIFICATION RULES

## HTML Template Content Exclusions
**IMPORTANT**: When verifying HTML template content accuracy, the following sections should NEVER be flagged as missing content because they are handled by PHP templates:

- **FAQ sections** - Loaded dynamically via PHP templates, NOT in HTML templates
- **Pricing information** - Handled dynamically by PHP, NOT in HTML templates  
- **H1 headings** - Managed by page layout PHP templates, NOT in HTML templates

These sections are intentionally excluded from HTML templates and are NOT missing content.