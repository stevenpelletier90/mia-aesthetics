# CLAUDE.md

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
- Uses npm dependencies (Bootstrap, FontAwesome, Glide) via vendor assets
- Semantic HTML5 with structured data (Schema.org) for medical practices

**Target Audience:**

- Patients researching cosmetic procedures (high consideration purchases)
- Mobile-first users (60%+ mobile traffic typical for medical searches)
- Demographics: Primarily 25-55 years old, seeking procedure information and surgeon credentials

## Available Commands

```bash
# Asset management
npm run build:vendor   # Copy vendor assets from node_modules
npm run purge:css      # Remove unused CSS classes

# Linting and Quality
npm run lint:js        # Lint JavaScript files with ESLint
npm run lint:css       # Lint CSS files with Stylelint
npm run phpcs          # PHP CodeSniffer - check coding standards
npm run phpcs:fix      # PHP CodeSniffer - automatically fix issues
npm run phpstan        # PHPStan static analysis
```

## Asset Structure

- CSS sources: `assets/css/` (base, layout, components, templates, utilities)
- JavaScript sources: `assets/js/` (organized by template type)
- Vendor assets: `assets/vendor/` (Bootstrap, FontAwesome, Glide from node_modules)
- WordPress enqueuing handles conditional loading per template

## WordPress Asset Enqueue

- Main logic: `inc/enqueue.php`
- Handles source CSS and JavaScript files directly
- Versioning: `filemtime`-based cache busting
- Conditional loading: per-template CSS/JS mapped in `mia_get_template_mappings()` and loaded based on context (front-page, archives, singles, etc.)
- Global assets: fonts, Bootstrap, base, header, footer; component CSS loaded on-demand

## Quality Standards

- ESLint config: `eslint.config.js` (browser globals for assets; Node for `scripts/`)
- Stylelint config: `.stylelintrc.json` (CSS linting with standard rules)
- PurgeCSS config: `purgecss.config.js` (removes unused CSS classes)
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

## Development Patterns & WordPress Best Practices

### WordPress Coding Patterns

**ACF Field Retrieval:**

```php
// Always check for ACF and provide fallbacks
$field_value = function_exists('get_field') ? get_field('field_name') : '';
if (!empty($field_value)) {
    echo wp_kses_post($field_value);
}

// For repeater fields
if (have_rows('repeater_field')) {
    while (have_rows('repeater_field')) {
        the_row();
        $sub_field = get_sub_field('sub_field_name');
    }
}
```

**Security Patterns:**

```php
// Always sanitize and validate user input
$user_input = sanitize_text_field($_POST['field_name']);

// Use nonces for form security
wp_nonce_field('action_name', 'nonce_name');
if (!wp_verify_nonce($_POST['nonce_name'], 'action_name')) {
    wp_die('Security check failed');
}

// Escape output based on context
echo esc_html($text);
echo esc_url($url);
echo wp_kses_post($content); // For rich content
```

**Template Organization:**

- Root templates: `page-*.php`, `single-*.php`, `archive-*.php`
- Reusable logic under `inc/` (enqueue, schema, queries, helpers, theme support)
- Components: reusable UI in `/components/`
- Template parts: `get_template_part('components/component-name', null, $args)`

### CSS Architecture

**Modular CSS Structure:**

- `base.css`: Typography, colors, utility classes
- `components/`: Reusable UI components
- `pages/`: Page-specific styles
- `templates/`: Template-specific styles

**Naming Conventions:**

- BEM-style for complex components: `.procedure-card__title--featured`
- Utility classes: `.text-gold`, `.bg-dark`, `.shadow-lg`
- WordPress-specific: `.wp-block-*`, `.page-id-*`, `.post-type-*`

### Schema.org Implementation

**Medical Practice Schema:**

- Organization schema for the practice
- Person schema for surgeons
- MedicalOrganization for clinic locations
- FAQPage schema for procedure information
- Review and Rating schemas for testimonials

Location: `inc/schema/` (class-based JSON‑LD implementation)

## Security & Compliance Guidelines

### Medical Website Security

**Data Protection:**

- Never log or store patient information in plain text
- Use HTTPS everywhere (especially forms and patient inquiries)
- Implement proper form validation and sanitization
- Regular security updates for WordPress core, themes, and plugins

**WordPress Security Best Practices:**

- Use nonces for all forms and AJAX requests
- Sanitize all user inputs: `sanitize_text_field()`, `sanitize_email()`, `sanitize_url()`
- Escape all outputs: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- Validate user permissions: `current_user_can()`, `is_user_logged_in()`
- Use prepared statements for custom database queries

### Medical Advertising Compliance

**Content Guidelines:**

- Before/after photos must include disclaimers about individual results
- Avoid superlative claims without substantiation ("best," "guaranteed")
- Include appropriate risk disclosures for procedures
- Ensure all testimonials are genuine and include disclaimers

**Accessibility Requirements (WCAG 2.1 AA):**

- Minimum contrast ratios: 4.5:1 for normal text, 3:1 for large text
- Keyboard navigation for all interactive elements
- Alt text for all images, especially procedure photos
- Proper heading hierarchy (h1 → h2 → h3)
- Form labels associated with inputs
- Focus indicators for keyboard users

## Performance & Accessibility

- Stylelint: ensures CSS code quality and consistency
- Conditional enqueuing: loads the minimum CSS/JS per context
- Image optimization: WebP with fallbacks, lazy loading for procedure galleries
- Critical CSS for above-the-fold content
- Accessibility: skip link present; focus-visible styles in base CSS
- Core Web Vitals optimization: LCP < 2.5s, FID < 100ms, CLS < 0.1

## Troubleshooting & Common Issues

### WordPress-Specific Issues

**Theme Problems:**

- CSS not updating: edit in `assets/css/`, purge browser caches, check file permissions
- PHP errors: enable `WP_DEBUG` in wp-config.php, check error logs
- ACF fields not displaying: verify field names, check `function_exists('get_field')`
- Template hierarchy issues: use `template_hierarchy` hook or query monitor plugin

**Performance Issues:**

- Slow page loads: check plugin conflicts, enable caching, optimize images
- High memory usage: review custom queries, check for infinite loops
- Large CSS bundles: run `npm run purge:css` to remove unused classes

**Development Workflow:**

- Local development: use Local by Flywheel or XAMPP with WordPress
- Version control: exclude `wp-config.php`, uploads directory, cache files
- Staging deployment: test all ACF fields and custom post types
- Production deployment: run linting, test forms, verify SSL certificates

**Common WordPress Errors:**

```php
// Fix "Cannot modify header information" errors
ob_start(); // Add to top of problematic files

// Fix "Call to undefined function" errors
if (function_exists('the_function_name')) {
    the_function_name();
}

// Fix database errors in custom queries
$results = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$wpdb->posts} WHERE post_title = %s",
    $search_term
));
```

### Medical Website Specific Issues

**Content & Compliance:**

- Before/after images not loading: check file permissions, image optimization
- Form submissions failing: verify nonces, check email configuration
- Schema markup errors: use Google's Rich Results Test tool
- Accessibility violations: run axe-core or WAVE accessibility checker

**SEO & Schema Issues:**

- Missing structured data: verify JSON-LD output in page source
- Duplicate content: check canonical URLs, meta descriptions
- Missing alt text: audit all procedure images and diagrams

### Code Quality Issues

**Linting and Quality:**

- ESLint errors: run `npm run lint:js` to identify JavaScript issues
- Stylelint errors: run `npm run lint:css` to check CSS standards
- PHP coding standards: run `npm run phpcs` to check WordPress coding standards
- PHP static analysis: run `npm run phpstan` to detect potential issues
- Auto-fix PHP issues: run `npm run phpcs:fix` to automatically fix coding standard violations
- Security vulnerabilities: regularly update dependencies, scan with security plugins

## CRITICAL TEMPLATE VERIFICATION RULES

Never flag these as missing from static HTML templates; they’re injected by PHP:

- FAQ sections
- Pricing information
- H1 headings

## Development Workflow & Best Practices

### Code Quality Standards

- **Security First**: Always sanitize inputs, escape outputs, use nonces
- **Accessibility**: WCAG 2.1 AA compliance for all new features
- **Performance**: Optimize images, minimize HTTP requests, use conditional loading
- **SEO**: Structured data, semantic HTML, proper meta tags
- **Mobile-First**: Design and test mobile experience first

### Development Process

1. **Planning**: Review wireframes, confirm requirements, check compliance needs
2. **Development**: Follow WordPress coding standards, use version control
3. **Testing**: Cross-browser testing, accessibility audit, performance check
4. **Quality Assurance**: Run all linting (`npm run lint:js`, `npm run lint:css`, `npm run phpcs`, `npm run phpstan`), test forms, verify schema
5. **Documentation**: Update comments, document new ACF fields, update CLAUDE.md if needed

### Task Approach Guidelines

- **Precision**: Medical websites require accuracy and attention to detail
- **Compliance**: Always consider medical advertising regulations and accessibility
- **User Experience**: Focus on patient journey and conversion optimization
- **Code Quality**: Maintainable, secure, performant code that follows WordPress standards
- **Scope Management**: Do only what's requested; avoid scope creep
- **File Preference**: Edit existing files rather than creating new ones
- **Documentation**: This CLAUDE.md is the single canonical doc (create additional docs only if explicitly requested)

---

**Last Updated**: January 2025 — Comprehensive update for AI-assisted development with medical aesthetics focus
