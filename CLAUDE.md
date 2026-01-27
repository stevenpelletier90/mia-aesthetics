# CLAUDE.md

Project guide for the Mia Aesthetics WordPress theme. Written for AI-assisted development.

<!-- Last verified: 2026-01-23 -->

## Project Overview

**Mia Aesthetics** is a premium WordPress theme for cosmetic surgery practices.

**Technical Stack:**

- WordPress theme with Bootstrap 5 and ACF Pro
- CSS-only workflow (no SCSS); modular CSS per template
- Vendor assets (Bootstrap, FontAwesome) via npm

**Focus Areas:**

- Patient education and trust-building
- Conversion optimization (awareness → consultation)
- Medical advertising compliance
- WCAG 2.1 AA accessibility

## Environment Requirements

- **PHP:** 8.1+ (compatible through 8.4)
- **WordPress:** 6.x
- **Node:** 18+
- **Composer:** 2.x
- **Hosting:** WP Engine (staging + production environments)

## Development Commands

```bash
# Setup
npm install && composer install

# CSS Linting (Stylelint)
npx stylelint "assets/css/**/*.css"           # Check CSS
npx stylelint "assets/css/**/*.css" --fix     # Auto-fix CSS

# JS Linting (ESLint)
npx eslint "assets/js/**/*.js"                # Check JS

# Formatting (Prettier)
npx prettier --check .                         # Check formatting
npx prettier --write .                         # Auto-format

# PHP CodeSniffer
./vendor/bin/phpcs                            # Check PHP
./vendor/bin/phpcbf                           # Auto-fix PHP

# PHPStan
./vendor/bin/phpstan analyse --memory-limit=2G
```

## Design System

### Color Tokens (`assets/css/base.css`)

```css
/* Brand Colors */
--mia-black: #1b1b1b;        /* Primary backgrounds, body text */
--mia-pure-black: #000000;   /* Deep backgrounds, gradients */
--mia-gold: #c8b273;         /* Primary accent, CTAs */
--mia-gold-dark: #be9e42;    /* Hover states */
--mia-gold-light: #f9f5ee;   /* Light section backgrounds */
--mia-white: #ffffff;        /* Text on dark, cards */
--mia-gray-dark: #222;       /* Dark gray backgrounds */
--mia-gray-muted: #666;      /* Muted text */

/* Link Colors */
--mia-link: #0066cc;         /* Default link color */
--mia-link-visited: #551a8b; /* Visited links */
--mia-link-hover: #004499;   /* Link hover state */

/* Gold Opacity Variants */
--mia-gold-10 through --mia-gold-50  /* 10%-50% opacity */

/* Z-Index Scale */
--z-sticky: 1020;            /* Sticky elements */
--z-tooltip: 1070;           /* Tooltips */
--z-header: 9999;            /* Site header */
```

### Typography

```css
--font-heading: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
--font-body: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
```

- **Headings:** Montserrat, weight 600
- **Body:** Open Sans, weight 400

### Border Radius

```css
--radius-none: 0; /* Buttons */
--radius-sm: 4px; /* Cards, inputs */
--radius-md: 8px; /* Stat boxes */
--radius-lg: 12px; /* Feature sections */
--radius-full: 50%; /* Circles */
--radius-DEFAULT: 4px; /* Default fallback */
```

### Button Classes (`assets/css/components/button.css`)

| Class                         | Use Case                             |
| ----------------------------- | ------------------------------------ |
| `.btn-primary`                | Gold/Black - Primary CTAs            |
| `.btn-secondary`              | Black/White - Secondary actions      |
| `.btn-outline-primary`        | Gold outline - Light backgrounds     |
| `.btn-outline-primary-alt`    | Gold outline, black text - Light BGs |
| `.btn-outline-primary-alt2`   | Gold outline, white text - Dark BGs  |
| `.btn-outline-secondary`      | Black outline - Light backgrounds    |
| `.btn-outline-secondary-alt`  | Black outline, white text - Dark BGs |
| `.btn-outline-secondary-alt2` | Black outline, gold text - Dark BGs  |
| `.btn-blur`                   | Glassmorphism - Over images/video    |
| `.btn-gradient`               | Black gradient - Premium feel        |
| `.btn-sm`                     | Small button size                    |
| `.btn-lg`                     | Large button size                    |

## Project Architecture

### File Organization

```bash
assets/
  css/
    base.css              <- Design tokens, typography, utilities
    header.css            <- Header/navigation styles
    footer.css            <- Footer styles
    fonts.css             <- Font face declarations
    components/           <- Reusable component styles
      button.css          <- Button variants
      breadcrumb.css      <- Breadcrumb navigation
      faq.css             <- FAQ accordion
      consultation-*.css  <- CTA and form styles
      case-card.css       <- Case study cards
      location-search.css <- Location finder
    templates/            <- All template styles (pages, singles, archives)
  js/
    (mirrors css structure)
  vendor/                 <- Bootstrap, FontAwesome

components/               <- PHP template parts
  breadcrumb.php          <- Breadcrumb component
  faq.php                 <- FAQ section
  consultation-cta.php    <- Consultation CTA block
  consultation-form.php   <- Consultation form
  case-card.php           <- Case study card
  careers-cta.php         <- Careers CTA block
  consent-modal.php       <- Consent modal

inc/
  enqueue.php             <- Asset loading
  template-helpers.php    <- Display functions
  media-helpers.php       <- Image handling
  menu-helpers.php        <- Navigation rendering
  menus.php               <- Menu registration
  queries.php             <- Custom WP_Query helpers
  cache-helpers.php       <- Caching utilities
  theme-support.php       <- Theme features
  cta-display.php         <- CTA display logic
  breadcrumb-display.php  <- Breadcrumb display logic
  display-control-helpers.php <- Display toggles
  location-search.php     <- Location search AJAX
  social-media-helpers.php <- Social media functions
  schema/                 <- JSON-LD structured data
    class-schema-loader.php
    class-*-schema.php    <- Post type schema classes
    trait-video-schema.php
```

### Adding Template Assets

1. Create: `assets/css/templates/page-{name}.css`
2. Register in `mia_get_template_asset_map()` in `inc/enqueue.php`:

```php
'page-my-template' => array(
  'css' => 'templates/page-my-template.css',
  'js'  => 'templates/page-my-template.js',
),
```

### Custom Post Types

`surgeon`, `procedure`, `non-surgical`, `location`, `case`, `special`, `condition`, `fat-transfer`

### Key Files

| File                   | Purpose                                           |
| ---------------------- | ------------------------------------------------- |
| `functions.php`        | Theme bootstrap - loads all inc/ modules in order |
| `style.css`            | Theme metadata (name, version, description)       |
| `header.php`           | Global header, navigation, offcanvas menu         |
| `footer.php`           | Global footer, scripts                            |
| `front-page.php`       | Homepage template                                 |
| `inc/enqueue.php`      | All CSS/JS asset loading + template asset map     |
| `inc/menu-helpers.php` | Navigation rendering (mega menus, dropdowns)      |
| `inc/schema.php`       | JSON-LD structured data loader                    |

## WordPress Patterns

### Template Hierarchy

```bash
front-page.php          -> Homepage
single-{post-type}.php  -> Single post type
archive-{post-type}.php -> Archive listings
page-{slug}.php         -> Specific pages
```

### Standard Template

```php
<?php
/**
 * Template description
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary">
  <?php while ( have_posts() ) : the_post(); ?>
    <section class="page-content py-5">
      <div class="container">
        <?php the_content(); ?>
      </div>
    </section>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
```

### Section Pattern

```html
<section class="[name]-section">
  <div class="container">
    <p class="tagline">Uppercase Label</p>
    <h2 class="section-heading">Title</h2>
    <p class="section-description">Description</p>
  </div>
</section>
```

## ACF Integration

```php
// Always null-check ACF fields
$field = get_field( 'field_name' );
if ( null !== $field && is_array( $field ) ) {
  // Use the field
}

// Image field (returns ID)
$image_id = get_field( 'image' );
if ( $image_id && is_numeric( $image_id ) ) {
  $url = wp_get_attachment_image_url( (int) $image_id, 'large' );
}
```

**Common Fields by Post Type:**

- **Surgeon:** `surgeon_headshot`, `surgeon_location`, `specialty_1`-`specialty_5`, `board_certified`, `medical_school`
- **Location:** `phone_number`, `location_map`, `location_maps_link`, `business_hours`, `city_guide`, `average_rating`, `review_count`
- **Procedure/Treatment:** `procedure_price`, `non_surgical_price`, `related_procedures`, `overview_details`, `faq_section`
- **Case:** `case_information`
- **Display Control:** `show_consultation_form`, `cta_display_override`, `breadcrumb_display_override`
- **Options Pages:** `cta_defaults`, `breadcrumb_defaults`, `hero_banner_*`

## PHP Standards

### Static Analysis (PHPStan Level 8 + Bleeding Edge)

The project uses maximum PHPStan strictness. All code must pass:

```bash
./vendor/bin/phpstan analyse --memory-limit=2G   # Level 8 + bleeding edge + strict rules
./vendor/bin/phpcs                               # WordPress Coding Standards
```

**Key rules enforced:**

- No `empty()` - use explicit comparisons instead
- No implicit boolean checks on mixed types
- No short ternary (`?:`) - use full ternary or null coalesce
- Strict null safety for all function parameters

### Type-Safe Patterns

```php
// ACF fields - always check type explicitly
$field = get_field( 'field_name' );
if ( is_string( $field ) && '' !== $field ) {
  // Use the field
}

// Arrays - check type and count
$items = get_field( 'items' );
if ( is_array( $items ) && count( $items ) > 0 ) {
  // Use the array
}

// preg_replace returns string|null - always fallback
$clean = preg_replace( '/pattern/', '', $input ) ?? '';

// WordPress functions returning string|false
$template = get_page_template_slug();
if ( is_string( $template ) && '' !== $template ) {
  // Use the template
}

// Never use empty() - be explicit
if ( '' === $value )           // Instead of: empty($value)
if ( 0 === count( $array ) )   // Instead of: empty($array)
if ( null === $var || false === $var )  // Instead of: !$var
```

### Security

```php
// Always escape output
echo esc_html( $text );
echo esc_url( $url );
echo esc_attr( $attribute );
echo wp_kses_post( $html );

// Sanitize input
$clean = sanitize_text_field( $_POST['field'] );

// Nonce verification
wp_nonce_field( 'action_name', 'nonce_field' );
```

### File Headers

```php
<?php
/**
 * Description
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
```

## Frontend Aesthetics

Reference: [Claude Cookbook - Frontend Aesthetics](https://github.com/anthropics/claude-cookbooks/blob/main/coding/prompting_for_frontend_aesthetics.ipynb)

### Core Principles

1. **Staggered reveals** - One orchestrated page load beats scattered micro-interactions
2. **Atmosphere over flat** - Layer gradients, patterns, grain textures
3. **Respect `prefers-reduced-motion`**

```css
/* Staggered animation */
.element:nth-child(1) {
  animation-delay: 0.1s;
}
.element:nth-child(2) {
  animation-delay: 0.15s;
}

/* Dot pattern */
.section::before {
  background-image: radial-gradient(circle at 1px 1px, rgba(200, 178, 115, 0.08) 1px, transparent 0);
  background-size: 24px 24px;
}
```

## Anti-Patterns

**CSS:** Inline styles, hardcoded colors, Bootstrap blue buttons, `border-radius > 4px` on buttons, generic AI aesthetics (Inter, Roboto, purple gradients)

**PHP:**

- `empty($var)` - use explicit type checks instead
- `if ($var)` on mixed types - use `null !== $var` or type checks
- `$var ?: 'default'` - use full ternary `$var !== '' ? $var : 'default'`
- Unescaped output, ACF without null checks, hardcoded URLs, missing `@package`

**WordPress:** Direct DB queries (use WP_Query), enqueueing outside `wp_enqueue_scripts`

## Gotchas

- **Windows paths:** Use `./vendor/bin/` prefix for PHP tools (`phpcs`, `phpstan`)
- **PHPStan memory:** Requires 2GB memory limit (`--memory-limit=2G` already configured)
- **Dropdown toggles:** Navigation dropdowns should link to real pages, not `href="#"` (causes SEO issues)
- **ACF option fields:** Use `get_field('field_name', 'option')` for options pages
- **Template assets:** Must register in `mia_get_template_asset_map()` or CSS/JS won't load
- **Bootstrap buttons:** Always override with `.btn-primary` etc. to avoid default blue
- **WP Engine cache:** Purge page cache after theme updates (WP Engine dashboard or admin bar)

## Reference Links

- [Bootstrap 5](https://getbootstrap.com/docs/5.3/)
- [FontAwesome](https://fontawesome.com/icons)
- [ACF](https://www.advancedcustomfields.com/resources/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WCAG Quick Reference](https://www.w3.org/WAI/WCAG21/quickref/)
