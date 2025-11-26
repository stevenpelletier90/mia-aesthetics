# CLAUDE.md

Project guide for the Mia Aesthetics WordPress theme. Written for AI-assisted development.

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

## Development Commands

```bash
npm run build:vendor   # Build vendor assets
npm install && composer install

# Linting
npm run lint           # JS + CSS
npm run phpcs          # PHP CodeSniffer
npm run phpstan        # Static analysis
npm run format         # Prettier formatting

composer qa            # Run all PHP checks
```

## Design System

### Color Tokens (`assets/css/base.css`)

```css
--mia-black: #1b1b1b;        /* Primary backgrounds, body text */
--mia-pure-black: #000000;   /* Deep backgrounds, gradients */
--mia-gold: #c8b273;         /* Primary accent, CTAs */
--mia-gold-dark: #be9e42;    /* Hover states */
--mia-gold-light: #f9f5ee;   /* Light section backgrounds */
--mia-white: #ffffff;        /* Text on dark, cards */
--mia-gray-muted: #666;      /* Muted text */
--mia-gold-10 through --mia-gold-50  /* Opacity variants */
```

### Typography

```css
--font-heading: 'Montserrat', sans-serif; /* Headlines, nav, buttons - weight 600 */
--font-body: 'Open Sans', sans-serif; /* Body text - weight 400 */
```

### Border Radius

```css
--radius-none: 0; /* Buttons */
--radius-sm: 4px; /* Cards, inputs */
--radius-md: 8px; /* Stat boxes */
--radius-lg: 12px; /* Feature sections */
--radius-full: 50%; /* Circles */
```

### Button Classes (`assets/css/components/button.css`)

| Class                       | Use Case                          |
| --------------------------- | --------------------------------- |
| `.btn-primary`              | Gold/Black - Primary CTAs         |
| `.btn-secondary`            | Black/White - Secondary actions   |
| `.btn-outline-primary`      | Gold outline - Light backgrounds  |
| `.btn-outline-primary-alt2` | White outline - Dark backgrounds  |
| `.btn-blur`                 | Glassmorphism - Over images/video |
| `.btn-gradient`             | Black gradient - Premium feel     |

## Project Architecture

### File Organization

```bash
assets/
  css/
    base.css              <- Design tokens
    archives/             <- Archive styles
    components/           <- Reusable components
    pages/                <- Page template styles
    singles/              <- Single post type styles
  js/
    (mirrors css structure)
  vendor/                 <- Bootstrap, FontAwesome

inc/
  enqueue.php             <- Asset loading
  template-helpers.php    <- Display functions
  media-helpers.php       <- Image handling
  menu-helpers.php        <- Navigation
  schema.php              <- JSON-LD structured data
```

### Adding Template Assets

1. Create: `assets/css/pages/page-{name}.css`
2. Register in `mia_get_template_asset_map()` in `inc/enqueue.php`:

```php
'page-my-template' => array(
  'css' => 'pages/page-my-template.css',
  'js'  => 'pages/page-my-template.js',
),
```

### Custom Post Types

`surgeon`, `procedure`, `non-surgical`, `location`, `case`, `special`, `condition`, `fat-transfer`

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

**Common Fields:** `faq_section`, `surgeon_headshot`, `surgeon_location`, `specialties`, `video_details`, `show_consultation_cta`

## PHP Standards

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

**PHP:** Unescaped output, ACF without null checks, hardcoded URLs, missing `@package`

**WordPress:** Direct DB queries (use WP_Query), enqueueing outside `wp_enqueue_scripts`

## Reference Links

- [Bootstrap 5](https://getbootstrap.com/docs/5.3/)
- [FontAwesome](https://fontawesome.com/icons)
- [ACF](https://www.advancedcustomfields.com/resources/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WCAG Quick Reference](https://www.w3.org/WAI/WCAG21/quickref/)
