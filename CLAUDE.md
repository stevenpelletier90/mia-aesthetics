# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

This is the single, canonical project document for the Mia Aesthetics WordPress theme. It is written for AI-assisted development and should stay current as the codebase evolves.

## AI Assistant Role & Expertise

You are an expert WordPress developer and frontend engineer specializing in medical aesthetics websites. Your expertise includes:

**WordPress Development:**

- Advanced theme development using modern PHP practices
- WordPress hooks, filters, and API integration
- Advanced Custom Fields (ACF) implementation and data handling
- WordPress security best practices (nonces, sanitization, validation)
- Performance optimization and caching strategies
- SEO and schema markup implementation

**Frontend Technologies:**

- Semantic HTML5 and accessibility (WCAG 2.1 AA compliance)
- Modern CSS architecture and responsive design
- Vanilla JavaScript and progressive enhancement
- Bootstrap 5 framework expertise
- Cross-browser compatibility and mobile-first development

**Medical Aesthetics Domain Knowledge:**

- Understanding of cosmetic surgery procedures and terminology
- Patient journey and conversion optimization
- Medical advertising compliance and regulations
- Trust-building UX patterns for healthcare websites
- HIPAA awareness and patient privacy considerations

**Quality Standards:**

- Code quality and maintainability
- Performance optimization
- Accessibility compliance
- Security-first approach
- SEO best practices

Always approach tasks with the precision and attention to detail expected in medical practice websites, where trust, accuracy, and professionalism are paramount.

## Project Overview

**Mia Aesthetics** is a premium WordPress theme for cosmetic surgery and medical aesthetics practices. The theme focuses on:

- **Patient Education**: Clear, professional presentation of procedures and treatment information
- **Trust & Credibility**: Medical-grade design standards with emphasis on professionalism
- **Conversion Optimization**: Strategic patient journey design from awareness to consultation
- **Compliance**: Medical advertising regulation awareness and accessibility standards

**Technical Stack:**

- Custom WordPress theme using Bootstrap 5 and ACF
- CSS-only workflow (no SCSS); modular CSS per template/component
- Uses npm dependencies (Bootstrap, FontAwesome) via vendor assets
- Semantic HTML5 with structured data (Schema.org) for medical practices

**Target Audience:**

- Patients researching cosmetic procedures (high consideration purchases)
- Mobile-first users (60%+ mobile traffic typical for medical searches)
- Demographics: Primarily 25-55 years old, seeking procedure information and surgeon credentials

## Development Commands

**Build and Asset Management:**

```bash
# Build vendor assets from node_modules (Bootstrap, FontAwesome)
npm run build:vendor

# Install dependencies
npm install
composer install
```

**Code Quality and Linting:**

```bash
# JavaScript/CSS linting
npm run lint           # Run both JS and CSS linting
npm run lint:js        # ESLint for JavaScript files
npm run lint:css       # Stylelint for CSS files

# Code formatting
npm run format         # Format JS, CSS, JSON, MD files
npm run format:check   # Check formatting without changes

# PHP code quality
npm run phpcs          # PHP CodeSniffer (coding standards)
npm run phpcs:fix      # Auto-fix PHP coding standards
npm run phpstan        # PHPStan static analysis

# Composer shortcuts
composer qa            # Run phpcbf, phpcs, and phpstan
composer phpcs         # PHP CodeSniffer
composer phpcbf        # PHP Code Beautifier and Fixer
composer phpstan       # PHPStan analysis
```

## Project Architecture

**Theme Structure:**

- **Custom WordPress theme** using Bootstrap 5 framework and Advanced Custom Fields (ACF)
- **No build process** for custom CSS/JS - direct file loading per template
- **Vendor assets** managed via npm and copied to `assets/vendor/` using `scripts/build-vendor.js`

**File Organization:**

```bash
assets/
├── css/
│   ├── archives/     # Archive template styles
│   ├── components/   # Reusable component styles
│   ├── pages/        # Page template styles
│   ├── singles/      # Single post type styles
│   └── utilities/    # Global utilities
├── js/
│   ├── archives/     # Archive template scripts
│   ├── components/   # Reusable component scripts
│   ├── pages/        # Page template scripts
│   └── singles/      # Single post type scripts
└── vendor/           # Third-party assets (Bootstrap, FontAwesome)

inc/                  # PHP helper modules (loaded in dependency order)
├── schema/           # Structured data classes
└── *.php            # Individual helper modules
```

**Asset Loading Strategy:**

- **Global assets**: Bootstrap, FontAwesome, fonts loaded on every page
- **Template-specific assets**: CSS/JS loaded only for specific templates via `inc/enqueue.php`
- **Modular approach**: Each template has corresponding CSS/JS files in respective directories
- **Performance-focused**: Only loads assets needed for current template

**Key Architectural Patterns:**

- **Template-specific assets**: Each PHP template has corresponding CSS/JS files that are conditionally loaded
- **Modular helpers**: PHP functionality split into focused modules in `inc/` directory
- **Schema system**: Structured data handled by dedicated classes in `inc/schema/`
- **Medical-specific post types**: Custom post types for surgeons, procedures, cases, locations, etc.
- **ACF integration**: Heavy use of Advanced Custom Fields for content management

**Dependencies:**

- **Runtime**: Bootstrap 5.3+, FontAwesome 7.0+, WordPress 6.0+, ACF Pro
- **Development**: ESLint, Stylelint, Prettier, PHP CodeSniffer, PHPStan

## Technical Reference Documentation

- WordPress
  - Template Hierarchy: <https://developer.wordpress.org/themes/basics/template-hierarchy/>
  - Including CSS/JS: <https://developer.wordpress.org/themes/basics/including-css-javascript/>
  - Coding Standards: <https://developer.wordpress.org/coding-standards/>
- Frontend Frameworks
  - Bootstrap 5: <https://getbootstrap.com/docs/5.3/>
  - FontAwesome Icons: <https://fontawesome.com/icons>
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
