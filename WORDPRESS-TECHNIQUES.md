# WordPress Development Techniques - Mia Aesthetics Theme

A comprehensive summary of WordPress development patterns, techniques, and best practices implemented in this professional theme.

---

## Table of Contents

1. [Hooks & Filters](#1-hooks--filters)
2. [Custom Post Types & Taxonomies](#2-custom-post-types--taxonomies)
3. [ACF Pro Integration](#3-acf-pro-integration)
4. [Asset Enqueue System](#4-asset-enqueue-system)
5. [Security Practices](#5-security-practices)
6. [PHP Type Safety](#6-php-type-safety)
7. [Query Optimization](#7-query-optimization)
8. [Caching Strategies](#8-caching-strategies)
9. [Schema.org & SEO](#9-schemaorg--seo)
10. [Responsive Images](#10-responsive-images)
11. [Admin Customization](#11-admin-customization)
12. [Performance Optimization](#12-performance-optimization)
13. [Template Hierarchy](#13-template-hierarchy)
14. [Accessibility](#14-accessibility)
15. [Summary Statistics](#15-summary-statistics)

---

## 1. Hooks & Filters

### Action Hooks Implemented

| Hook                    | Function                            | Purpose                            |
| ----------------------- | ----------------------------------- | ---------------------------------- |
| `wp_enqueue_scripts`    | `mia_enqueue_assets()`              | Conditional asset loading          |
| `wp_head`               | `mia_head_scripts()`                | Third-party script injection       |
| `after_setup_theme`     | `mia_setup()`                       | Theme supports, image sizes, menus |
| `after_setup_theme`     | `mia_editor_styles()`               | Gutenberg editor styles            |
| `admin_bar_menu`        | `mia_admin_bar_view_source()`       | Custom admin bar links             |
| `admin_init`            | `mia_disable_comments_post_types()` | Remove comment support             |
| `admin_init`            | `mia_featured_image_columns_init()` | Register featured image column     |
| `admin_menu`            | `mia_disable_comments_admin_menu()` | Hide comments menu                 |
| `admin_enqueue_scripts` | `mia_admin_enqueue_styles()`        | Admin-only CSS                     |
| `pre_get_posts`         | `mia_modify_archive_queries()`      | Customize post type archives       |
| `pre_get_posts`         | `mia_modify_taxonomy_queries()`     | Customize taxonomy queries         |
| `pre_get_posts`         | `mia_search_filter()`               | Include CPTs in search             |
| `pre_get_posts`         | `mia_exclude_pages_from_search()`   | Exclude specific pages             |
| `save_post`             | Cache clearing functions            | Invalidate menu/footer caches      |
| `delete_post`           | Cache clearing functions            | Invalidate caches on delete        |
| `acf/init`              | `mia_acf_init()`                    | Initialize ACF Pro settings        |

### Filter Hooks Implemented

| Hook                           | Function                              | Purpose                         |
| ------------------------------ | ------------------------------------- | ------------------------------- |
| `upload_mimes`                 | `mia_aesthetics_allow_svg_uploads()`  | SVG upload support (admin-only) |
| `wp_prepare_attachment_for_js` | `mia_aesthetics_fix_svg_display()`    | Fix SVG in media library        |
| `wp_check_filetype_and_ext`    | `mia_aesthetics_check_svg_filetype()` | Recognize SVG file types        |
| `gform_confirmation_anchor`    | `__return_false`                      | Disable Gravity Forms anchor    |
| `body_class`                   | `mia_template_body_classes()`         | Custom body classes             |
| `body_class`                   | `mia_archive_body_classes()`          | Post type archive classes       |
| `excerpt_length`               | `mia_custom_excerpt_length()`         | Context-aware excerpts          |
| `excerpt_more`                 | `mia_excerpt_more()`                  | Custom excerpt ending           |
| `protected_title_format`       | `mia_protected_title_format()`        | Remove "Protected:" prefix      |
| `private_title_format`         | `mia_private_title_format()`          | Remove "Private:" prefix        |
| `comments_open`                | `__return_false`                      | Disable comments globally       |
| `pings_open`                   | `__return_false`                      | Disable pingbacks               |
| `comments_array`               | `__return_empty_array`                | Hide existing comments          |
| `query_vars`                   | `mia_add_query_vars()`                | Register custom query vars      |
| `wpseo_schema_graph_pieces`    | Schema registration                   | Custom Schema.org with Yoast    |
| `mia_aesthetics_logo_args`     | Logo customization                    | Allow 3rd-party logo filtering  |

---

## 2. Custom Post Types & Taxonomies

### Custom Post Types (8)

| Post Type      | Features                                               |
| -------------- | ------------------------------------------------------ |
| `surgeon`      | Menu order, ACF fields (headshot, location, Instagram) |
| `procedure`    | Hierarchical, parent/child categories                  |
| `non-surgical` | Categorized treatments                                 |
| `location`     | Hierarchical, parent/child offices                     |
| `case`         | Before/after studies, chronological                    |
| `special`      | Time-limited promotions, meta queries                  |
| `condition`    | Cosmetic conditions treated                            |
| `fat-transfer` | Fat transfer procedures                                |

### Archive Query Configuration

```php
// Centralized in inc/queries.php
$configurations = array(
    'surgeon'  => array( 'orderby' => 'menu_order', 'order' => 'ASC' ),
    'location' => array( 'orderby' => 'title', 'order' => 'ASC', 'post_parent' => 0 ),
    'case'     => array( 'orderby' => 'date', 'order' => 'DESC' ),
    'special'  => array( 'meta_query' => /* active specials only */ ),
);
```

---

## 3. ACF Pro Integration

### Type-Safe Field Retrieval

```php
// Always null-check and validate type
$field = get_field( 'field_name' );
if ( null !== $field && is_array( $field ) ) {
    // Use the field
}

// Repeater validation
if ( isset( $faq_section['faqs'] ) && is_array( $faq_section['faqs'] ) && count( $faq_section['faqs'] ) > 0 ) {
    foreach ( $faq_section['faqs'] as $faq ) {
        if ( isset( $faq['question'] ) && is_string( $faq['question'] ) && '' !== $faq['question'] ) {
            // Process FAQ
        }
    }
}

// Image field (returns ID)
$image_id = get_field( 'image' );
if ( $image_id && is_numeric( $image_id ) ) {
    $url = wp_get_attachment_image_url( (int) $image_id, 'large' );
}
```

### Common ACF Fields

| Field                         | Type          | Usage                            |
| ----------------------------- | ------------- | -------------------------------- |
| `faq_section`                 | Repeater      | Questions/answers with accordion |
| `surgeon_headshot`            | Image (ID)    | Surgeon profile photos           |
| `surgeon_location`            | Post Object   | Links surgeon to location        |
| `specialties`                 | Repeater      | Surgeon specializations          |
| `video_details`               | Group         | YouTube ID, thumbnail, title     |
| `show_consultation_cta`       | Boolean       | Per-page CTA control             |
| `cta_display_override`        | Select        | show/hide/default options        |
| `breadcrumb_display_override` | Select        | Per-post breadcrumb control      |
| `google_maps_api_key`         | Text (Option) | Site-wide API key                |

---

## 4. Asset Enqueue System

### Conditional Component Loading

```php
// inc/enqueue.php
function mia_enqueue_conditional_components(): void {
    // Only load when needed
    if ( should_show_consultation_cta() ) {
        mia_load_component_assets( 'consultation-cta' );
    }

    if ( mia_has_consultation_form() ) {
        mia_load_component_assets( 'consultation-form' );
    }

    if ( mia_needs_faq() ) {
        mia_load_component_assets( 'faq' );
    }

    if ( mia_needs_breadcrumb() ) {
        mia_load_component_assets( 'breadcrumb' );
    }

    // Google Maps only on specific pages
    if ( mia_needs_location_search_careers() ) {
        mia_load_component_assets( 'location-search-careers' );
        mia_enqueue_google_maps( 'initGoogleMapsCareers' );
    }
}
```

### Smart Component Loading

```php
function mia_load_component_assets( string $component ): void {
    // CSS always loaded
    wp_enqueue_style(
        "mia-{$component}",
        get_template_directory_uri() . "/assets/css/components/{$component}.css",
        array( 'mia-base' ),
        MIA_THEME_VERSION
    );

    // JS only if file exists (prevents 404 errors)
    $js_file = get_template_directory() . "/assets/js/components/{$component}.js";
    if ( file_exists( $js_file ) ) {
        wp_enqueue_script(
            "mia-{$component}",
            get_template_directory_uri() . "/assets/js/components/{$component}.js",
            array(),
            MIA_THEME_VERSION,
            true
        );
    }
}
```

### Template-to-Asset Mapping

```php
function mia_get_template_asset_map(): array {
    return array(
        // Page Templates
        'front-page'           => array( 'css' => 'pages/front-page.css', 'js' => 'pages/front-page.js' ),
        'page-careers'         => array( 'css' => 'pages/page-careers.css', 'js' => 'pages/page-careers.js' ),

        // Archives
        'archive-surgeon'      => array( 'css' => 'archives/archive-surgeon.css', 'js' => 'archives/archive-surgeon.js' ),
        'archive-procedure'    => array( 'css' => 'archives/archive-procedure.css', 'js' => 'archives/archive-procedure.js' ),

        // Singles
        'single-surgeon'       => array( 'css' => 'singles/single-surgeon.css', 'js' => 'singles/single-surgeon.js' ),
        'single-location'      => array( 'css' => 'singles/single-location.css', 'js' => 'singles/single-location.js' ),
        // ... 30+ template mappings
    );
}
```

---

## 5. Security Practices

### Output Escaping

```php
// Text content
echo esc_html( $text );

// URLs
echo esc_url( $url );
href="<?php echo esc_url( home_url( '/path/' ) ); ?>"

// HTML attributes
alt="<?php echo esc_attr( $text ); ?>"
class="<?php echo esc_attr( $class_name ); ?>"

// Allowed HTML (posts)
echo wp_kses_post( $html );
```

### Input Sanitization

```php
// From $_SERVER superglobals
$http_host = isset( $_SERVER['HTTP_HOST'] )
    ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) )
    : '';

// ACF field sanitization
$video_id = sanitize_text_field( $val['video_id'] );
$title = sanitize_text_field( $val['video_title'] ?? '' );
$description = sanitize_textarea_field( $val['video_description'] ?? '' );

// URL validation
if ( false !== filter_var( $val['url'], FILTER_VALIDATE_URL ) ) {
    return esc_url_raw( $val['url'] );
}
```

### File Security

```php
// SVG uploads restricted to administrators
if ( current_user_can( 'manage_options' ) ) {
    $mimes['svg'] = 'image/svg+xml';
}

// Direct access prevention (all inc files)
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// File existence checks
if ( file_exists( $js_file ) ) {
    wp_enqueue_script( /* ... */ );
}
```

---

## 6. PHP Type Safety

### PHPStan Level 8 + Bleeding Edge

```php
// Return type declarations
function mia_needs_breadcrumb(): bool { }
function mia_get_current_template_key(): string { }
function mia_enqueue_google_maps( string $callback ): void { }

// PHPDoc for complex types
/**
 * @param array<string, mixed> $args Arguments.
 * @return array<string, string>|null
 */
function example( array $args ) { }
```

### Strict Comparisons (No `empty()`)

```php
// Check for empty string
if ( '' === $value ) { }

// Check array emptiness
if ( 0 === count( $array ) ) { }

// Check null/false
if ( null === $var || false === $var ) { }

// Type + value check
if ( is_string( $value ) && '' !== $value ) { }

// Numeric validation
if ( is_numeric( $id ) ) {
    $id = (int) $id;
}
```

### Type Validation Patterns

```php
// Array type checking
if ( is_array( $items ) && count( $items ) > 0 ) { }

// Object property checking
if ( is_object( $obj ) && property_exists( $obj, 'ID' ) ) { }

// Instance checking
if ( $post_obj instanceof WP_Post ) { }
```

---

## 7. Query Optimization

### Performance-Optimized Queries

```php
// For counting only
$query = new WP_Query( array(
    'post_type'              => $post_type,
    'post_status'            => 'publish',
    'posts_per_page'         => -1,
    'fields'                 => 'ids',           // Only fetch IDs
    'no_found_rows'          => true,            // Skip pagination count
    'update_post_meta_cache' => false,           // Skip meta cache
    'update_post_term_cache' => false,           // Skip term cache
) );
```

### Pre_get_posts Modifications

```php
add_action( 'pre_get_posts', function( $query ) {
    // Only modify frontend main queries
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( is_post_type_archive( 'surgeon' ) ) {
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );
        $query->set( 'posts_per_page', -1 );
    }
});
```

### Meta Query for Active Content

```php
// Only show specials that haven't expired
$query->set( 'meta_query', array(
    'relation' => 'OR',
    array(
        'key'     => 'special_end_date',
        'value'   => gmdate( 'Y-m-d' ),
        'compare' => '>=',
        'type'    => 'DATE',
    ),
    array(
        'key'     => 'special_end_date',
        'compare' => 'NOT EXISTS',
    ),
));
```

---

## 8. Caching Strategies

### Object Cache Pattern

```php
$cache_key = sprintf( 'mia_count_%s_', $post_type ) . md5( $json_args );
$count = wp_cache_get( $cache_key );

if ( false === $count ) {
    $count = get_post_count();
    $duration = apply_filters( 'mia_post_count_cache_duration', 2 * HOUR_IN_SECONDS );
    wp_cache_set( $cache_key, $count, '', $duration );
}

return $count;
```

### Transient Pattern

```php
$cache_key = 'mia_excluded_search_pages';
$data = get_transient( $cache_key );

if ( false === $data ) {
    $data = expensive_operation();
    set_transient( $cache_key, $data, 12 * HOUR_IN_SECONDS );
}

return $data;
```

### Cache Invalidation on Content Changes

```php
add_action( 'save_post', 'mia_clear_menu_cache' );
add_action( 'delete_post', 'mia_clear_menu_cache' );
add_action( 'trash_post', 'mia_clear_menu_cache' );
add_action( 'untrash_post', 'mia_clear_menu_cache' );

function mia_clear_menu_cache( $post_id ): void {
    $post_type = get_post_type( $post_id );
    if ( in_array( $post_type, array( 'procedure', 'location', 'surgeon' ), true ) ) {
        delete_transient( 'mia_menu_structure' );
    }
}
```

---

## 9. Schema.org & SEO

### Yoast SEO Integration

```php
// inc/schema.php
namespace Mia_Aesthetics\Schema;

class Schema_Loader {
    public static function init(): void {
        add_filter( 'wpseo_schema_graph_pieces', array( __CLASS__, 'register_schema_pieces' ), 10, 2 );
    }

    public static function register_schema_pieces( $pieces, $context ): array {
        $pieces[] = new Surgeon_Schema( $context );
        $pieces[] = new Clinic_Schema( $context );
        $pieces[] = new FAQ_Schema( $context );
        return $pieces;
    }
}
```

### Custom Schema Classes

```php
class Surgeon_Schema {
    private $context;

    public function __construct( $context ) {
        $this->context = $context;
    }

    public function is_needed(): bool {
        return is_singular( 'surgeon' );
    }

    public function generate(): array {
        return array(
            '@type'       => array( 'Person', 'Physician' ),
            '@id'         => get_permalink() . '#surgeon',
            'name'        => get_the_title(),
            'image'       => $this->get_headshot_url(),
            'worksFor'    => array( '@id' => home_url( '/#organization' ) ),
            'medicalSpecialty' => 'PlasticSurgery',
        );
    }
}
```

### Schema File Structure

```bash
inc/schema/
├── class-schema-loader.php        → Main loader
├── class-surgeon-schema.php       → Person/Physician
├── class-clinic-schema.php        → MedicalClinic
├── class-organization-schema.php  → Organization
└── class-faq-schema.php           → FAQPage
```

---

## 10. Responsive Images

### WordPress srcset Generation

```php
// Automatic srcset with wp_get_attachment_image()
echo wp_get_attachment_image(
    $image_id,
    'medium_large',
    false,
    array(
        'class'   => 'img-fluid',
        'alt'     => 'Description',
        'sizes'   => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
        'loading' => 'lazy',
    )
);
```

### Custom Image Sizes

```php
// inc/theme-support.php
add_image_size( 'hero-mobile', 640, 400, true );
add_image_size( 'hero-tablet', 1024, 600, true );
add_image_size( 'hero-desktop', 1920, 800, true );
add_image_size( 'card-thumb', 600, 400, true );
add_image_size( 'profile', 600, 600, true );
add_image_size( 'gallery-small', 300, 225, true );
add_image_size( 'gallery-medium', 450, 338, true );
add_image_size( 'gallery-large', 600, 450, true );
```

### Picture Element for Art Direction

```html
<picture>
  <source media="(min-width: 768px)" srcset="/uploads/desktop.jpg 1920w, /uploads/wide.jpg 2560w" sizes="100vw" />
  <img src="/uploads/mobile.jpg" alt="Description" width="800" height="600" fetchpriority="high" data-no-lazy="1" />
</picture>
```

---

## 11. Admin Customization

### Featured Image Column

```php
// inc/featured-image-column.php
foreach ( get_post_types( array( 'public' => true ), 'names' ) as $type ) {
    if ( post_type_supports( $type, 'thumbnail' ) ) {
        // Add column header
        add_filter( "manage_{$type}_posts_columns", function( $cols ) {
            $offset = array_search( 'title', array_keys( $cols ), true );
            return array_slice( $cols, 0, $offset + 1, true )
                + array( 'thumb' => 'Image' )
                + array_slice( $cols, $offset + 1, null, true );
        });

        // Add column content
        add_action( "manage_{$type}_posts_custom_column", function( $col, $id ) {
            if ( 'thumb' === $col ) {
                echo get_the_post_thumbnail( $id, array( 60, 60 ) );
            }
        }, 10, 2 );
    }
}
```

### Admin Bar Customization

```php
// Add staging/production comparison link
add_action( 'admin_bar_menu', 'mia_admin_bar_view_source', 100 );

function mia_admin_bar_view_source( WP_Admin_Bar $wp_admin_bar ): void {
    if ( ! is_admin() && ! is_user_logged_in() ) {
        return;
    }

    $current_url = /* get current URL */;
    $source_url = str_replace( $http_host, 'miaaesthetics.com', $current_url );

    $wp_admin_bar->add_node( array(
        'id'    => 'view-source',
        'title' => 'View Source',
        'href'  => $source_url,
        'meta'  => array( 'target' => '_blank' ),
    ) );
}
```

### Disable Comments Site-Wide

```php
// Remove comment support from all post types
add_action( 'admin_init', function(): void {
    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
});

// Remove admin menu
add_action( 'admin_menu', function(): void {
    remove_menu_page( 'edit-comments.php' );
});

// Remove from admin bar
add_action( 'admin_bar_menu', function( WP_Admin_Bar $bar ): void {
    $bar->remove_menu( 'comments' );
}, 999 );

// Close on frontend
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array' );
```

---

## 12. Performance Optimization

### LCP Optimization

```html
<!-- Exclude hero from lazy loading -->
<img src="hero.jpg" fetchpriority="high" data-no-lazy="1" width="800" height="600" />
```

### Third-Party Script Loading

```php
// Load tracking scripts early but non-blocking
add_action( 'wp_head', 'mia_head_scripts', 1 );

function mia_head_scripts(): void {
    ?>
    <script>
        window.attributersettings = { cookieLife: 30 }
    </script>
    <script src="https://example.com/attributer.js"></script>
    <script src="https://cdn.userway.org/widget.js" data-account="xxx"></script>
    <?php
}
```

### Conditional Google Maps

```php
// Only load on pages that need it
if ( mia_needs_location_search_careers() ) {
    mia_enqueue_google_maps( 'initGoogleMapsCareers' );
}

if ( mia_needs_virtual_consultation_maps() ) {
    mia_enqueue_google_maps( 'initVirtualConsultationGeocoder' );
}
```

---

## 13. Template Hierarchy

### File Structure

```bash
Theme Root/
├── front-page.php              → Homepage
├── single-{post-type}.php      → Single views (8 CPTs)
├── archive-{post-type}.php     → Archive views (8 CPTs)
├── page-{slug}.php             → Specific pages
├── header.php                  → Bootstrap 5 navbar + offcanvas
├── footer.php                  → Multi-column footer
├── hero-section.php            → Homepage hero (included)
└── components/
    ├── breadcrumb.php
    ├── faq.php
    ├── case-card.php
    ├── consultation-cta.php
    └── consultation-form.php
```

### Template Part with Arguments

```php
$args = array(
    'show_heading'  => true,
    'faq_section'   => $faq_section,
    'valid_faqs'    => $valid_faqs,
    'accordion_id'  => 'faq-' . get_the_ID(),
);

ob_start();
get_template_part( 'components/faq', null, $args );
$output = ob_get_clean();
```

---

## 14. Accessibility

### ARIA Implementation

```html
<!-- Navigation landmarks -->
<nav aria-label="Primary navigation" id="site-navigation">
  <button aria-expanded="false" aria-controls="siteMenu" aria-label="Open menu">
    <span class="navbar-toggler-icon"></span>
  </button>
</nav>

<!-- Offcanvas menu -->
<div class="offcanvas" id="siteMenu" aria-labelledby="siteMenuLabel" tabindex="-1">
  <div class="offcanvas-title" id="siteMenuLabel">Menu</div>
</div>

<!-- Tab panels -->
<div role="tablist" aria-label="Procedure categories">
  <button role="tab" aria-selected="true" aria-controls="body-content">Body</button>
</div>
<div role="tabpanel" id="body-content" aria-labelledby="body-tab">Content</div>
```

### Decorative vs. Meaningful Images

```html
<!-- Decorative (hidden from AT) -->
<i class="fa-solid fa-arrow-right" aria-hidden="true"></i>

<!-- Meaningful (descriptive alt) -->
<img src="surgeon.jpg" alt="Dr. John Smith, Board Certified Plastic Surgeon" />

<!-- Background decorative -->
<img src="pattern.jpg" alt="" role="presentation" />
```

---

## 15. Summary Statistics

### Codebase Metrics

| Metric             | Count |
| ------------------ | ----- |
| Action Hooks       | 15+   |
| Filter Hooks       | 25+   |
| Custom Post Types  | 8     |
| Template Files     | 30+   |
| Helper Functions   | 50+   |
| Schema Classes     | 4     |
| Custom Image Sizes | 8     |
| Component CSS/JS   | 10+   |

### Standards Compliance

| Standard                           | Status |
| ---------------------------------- | ------ |
| PHPStan Level 8 + Bleeding Edge    | ✅     |
| WordPress Coding Standards (PHPCS) | ✅     |
| WCAG 2.1 AA Accessibility          | ✅     |
| Schema.org Structured Data         | ✅     |
| Core Web Vitals Optimization       | ✅     |

### Plugin Compatibility

| Plugin        | Integration                            |
| ------------- | -------------------------------------- |
| ACF Pro       | Deep integration, type-safe            |
| Yoast SEO     | Schema.org extension                   |
| WP Rocket     | LCP optimization, lazy load exclusions |
| Imagify       | WebP, responsive images                |
| Gravity Forms | Form rendering, anchor disable         |

---

## Key Differentiators

This theme demonstrates:

1. **Enterprise-grade architecture** - Modular, maintainable, scalable
2. **Type-safe PHP** - PHPStan Level 8 compliance
3. **Performance-first** - Conditional loading, query optimization, caching
4. **Accessibility-focused** - WCAG 2.1 AA patterns throughout
5. **SEO-optimized** - Custom Schema.org, semantic HTML
6. **Security-hardened** - Proper escaping, sanitization, capability checks
7. **Developer experience** - Clear documentation, consistent patterns
