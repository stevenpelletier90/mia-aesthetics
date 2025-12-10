# WordPress Development Skills Portfolio

A technical reference document mapping practical implementation experience to industry terminology, with concrete examples from the Mia Aesthetics enterprise theme.

---

## Quick Reference: "What I Call It" vs. "What It's Called"

| What You Might Say | Proper Term | Example |
|-------------------|-------------|---------|
| "A function that runs when X happens" | **Action Hook** | `add_action( 'wp_head', 'my_function' )` |
| "A function that changes something" | **Filter Hook** | `add_filter( 'body_class', 'my_function' )` |
| "Checking what page you're on" | **Conditional Tags** | `is_single()`, `is_page_template()` |
| "Getting data from ACF" | **Field Retrieval** | `get_field( 'field_name' )` |
| "Making sure output is safe" | **Output Escaping** | `esc_html()`, `esc_url()` |
| "Loading CSS/JS files" | **Asset Enqueueing** | `wp_enqueue_style()`, `wp_enqueue_script()` |
| "Custom content types" | **Custom Post Types (CPTs)** | `surgeon`, `procedure`, `location` |
| "Getting posts from the database" | **WP_Query** | `new WP_Query( $args )` |
| "Changing query before it runs" | **pre_get_posts hook** | Modify archive queries |
| "Saving data when post is updated" | **save_post hook** | Cache invalidation |

---

## Core Skill: Hooks & Filters (The WordPress Event System)

**What they are:** Functions that "hook into" WordPress at specific points to add or modify functionality without editing core files.

### Action Hooks I've Implemented

Actions **do something** when triggered:

```php
// Load assets when WordPress renders the page
add_action( 'wp_enqueue_scripts', 'mia_enqueue_assets' );

// Inject scripts into <head>
add_action( 'wp_head', 'mia_head_scripts', 1 );

// Modify admin on initialization
add_action( 'admin_init', 'mia_disable_comments_post_types' );

// Clear cache when content changes
add_action( 'save_post', 'mia_clear_menu_cache' );

// Modify queries before execution
add_action( 'pre_get_posts', 'mia_modify_archive_queries' );

// Initialize ACF Pro settings
add_action( 'acf/init', 'mia_acf_init' );

// Customize admin bar
add_action( 'admin_bar_menu', 'mia_admin_bar_view_source', 100 );
```

### Filter Hooks I've Implemented

Filters **modify data** and return it:

```php
// Add custom CSS classes to <body>
add_filter( 'body_class', 'mia_template_body_classes' );

// Disable comments site-wide
add_filter( 'comments_open', '__return_false', 20 );

// Allow SVG uploads for admins
add_filter( 'upload_mimes', 'mia_aesthetics_allow_svg_uploads' );

// Extend Yoast SEO's Schema.org graph
add_filter( 'wpseo_schema_graph_pieces', 'register_schema_pieces', 10, 2 );

// Custom excerpt length
add_filter( 'excerpt_length', 'mia_custom_excerpt_length' );
```

### Hook Statistics from Mia Aesthetics Theme

- **15+ Action Hooks** implemented
- **25+ Filter Hooks** implemented
- Custom hooks created for extensibility

---

## Core Skill: Backend Development

### Custom Post Types (8 Total)

```php
// Post types with specific query configurations
$configurations = array(
    'surgeon'      => array( 'orderby' => 'menu_order', 'order' => 'ASC' ),
    'procedure'    => array( 'hierarchical' => true ),
    'location'     => array( 'hierarchical' => true, 'post_parent' => 0 ),
    'case'         => array( 'orderby' => 'date', 'order' => 'DESC' ),
    'special'      => array( 'meta_query' => /* active only */ ),
    'condition'    => array(),
    'non-surgical' => array(),
    'fat-transfer' => array(),
);
```

### Conditional Asset Loading System

```php
// Only load components when needed - reduces page weight
function mia_enqueue_conditional_components(): void {
    if ( should_show_consultation_cta() ) {
        mia_load_component_assets( 'consultation-cta' );
    }

    if ( mia_has_consultation_form() ) {
        mia_load_component_assets( 'consultation-form' );
    }

    if ( mia_needs_faq() ) {
        mia_load_component_assets( 'faq' );
    }

    // Google Maps only on specific templates
    if ( mia_needs_location_search_careers() ) {
        mia_enqueue_google_maps( 'initGoogleMapsCareers' );
    }
}
```

### Template-to-Asset Mapping (30+ Templates)

```php
function mia_get_template_asset_map(): array {
    return array(
        'front-page'        => array( 'css' => 'pages/front-page.css', 'js' => 'pages/front-page.js' ),
        'archive-surgeon'   => array( 'css' => 'archives/archive-surgeon.css' ),
        'single-location'   => array( 'css' => 'singles/single-location.css' ),
        // ... 30+ mappings
    );
}
```

---

## Core Skill: Database Integration

### WP_Query Optimization

```php
// Performance-optimized query pattern
$query = new WP_Query( array(
    'post_type'              => 'surgeon',
    'post_status'            => 'publish',
    'posts_per_page'         => -1,
    'fields'                 => 'ids',           // Only fetch IDs, not full objects
    'no_found_rows'          => true,            // Skip SQL_CALC_FOUND_ROWS
    'update_post_meta_cache' => false,           // Skip meta cache if not needed
    'update_post_term_cache' => false,           // Skip term cache if not needed
) );
```

**Why this matters:** Default WP_Query runs `SQL_CALC_FOUND_ROWS` for pagination even when you don't need it. Setting `no_found_rows => true` eliminates this overhead.

### pre_get_posts for Archive Modification

```php
add_action( 'pre_get_posts', function( WP_Query $query ): void {
    // Only modify frontend main queries
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // Surgeons: alphabetical by menu order
    if ( is_post_type_archive( 'surgeon' ) ) {
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );
        $query->set( 'posts_per_page', -1 );
    }

    // Locations: only parent locations
    if ( is_post_type_archive( 'location' ) ) {
        $query->set( 'post_parent', 0 );
        $query->set( 'orderby', 'title' );
    }
});
```

### Meta Queries for Business Logic

```php
// Only show active specials (not expired)
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

### Caching Layer

```php
// Object cache pattern
$cache_key = 'mia_count_' . $post_type . '_' . md5( wp_json_encode( $args ) );
$count = wp_cache_get( $cache_key );

if ( false === $count ) {
    $count = run_expensive_query();
    wp_cache_set( $cache_key, $count, '', 2 * HOUR_IN_SECONDS );
}

// Automatic invalidation on content changes
add_action( 'save_post', 'mia_clear_menu_cache' );
add_action( 'delete_post', 'mia_clear_menu_cache' );
```

---

## Core Skill: ACF Pro Integration (Type-Safe Patterns)

### Field Retrieval with Validation

```php
// Never trust ACF data - always validate
$field = get_field( 'field_name' );

// String fields
if ( is_string( $field ) && '' !== $field ) {
    echo esc_html( $field );
}

// Array fields (repeaters, groups)
if ( is_array( $field ) && count( $field ) > 0 ) {
    foreach ( $field as $item ) {
        // Process items
    }
}

// Image fields (return format: ID)
$image_id = get_field( 'image' );
if ( is_numeric( $image_id ) ) {
    echo wp_get_attachment_image( (int) $image_id, 'large' );
}
```

### Complex Field Structures

```php
// FAQ section with nested validation
$faq_section = get_field( 'faq_section' );

if ( null !== $faq_section && isset( $faq_section['faqs'] ) && is_array( $faq_section['faqs'] ) ) {
    $valid_faqs = array_filter(
        $faq_section['faqs'],
        static function ( $faq ) {
            return isset( $faq['question'] )
                && '' !== $faq['question']
                && isset( $faq['answer'] )
                && '' !== $faq['answer'];
        }
    );

    if ( count( $valid_faqs ) > 0 ) {
        // Render FAQ accordion
    }
}
```

---

## Core Skill: Security Implementation

### Output Escaping (Context-Aware)

```php
// Text content - escape HTML entities
echo esc_html( $user_input );

// URLs - validate and escape
echo esc_url( $url );
href="<?php echo esc_url( home_url( '/path/' ) ); ?>"

// HTML attributes
alt="<?php echo esc_attr( $text ); ?>"

// Trusted HTML (post content)
echo wp_kses_post( $html_content );
```

### Input Sanitization

```php
// From superglobals
$host = isset( $_SERVER['HTTP_HOST'] )
    ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) )
    : '';

// User input
$clean_text = sanitize_text_field( $input );
$clean_textarea = sanitize_textarea_field( $input );
$clean_email = sanitize_email( $input );

// URL validation
if ( false !== filter_var( $url, FILTER_VALIDATE_URL ) ) {
    $safe_url = esc_url_raw( $url );
}
```

### Capability Checks

```php
// SVG uploads restricted to administrators only
function mia_aesthetics_allow_svg_uploads( $mimes ) {
    if ( current_user_can( 'manage_options' ) ) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
```

---

## Core Skill: PHP Type Safety (PHPStan Level 8)

### Return Type Declarations

```php
function mia_needs_breadcrumb(): bool { }
function mia_get_current_template_key(): string { }
function mia_enqueue_google_maps( string $callback ): void { }
```

### Strict Comparisons (No empty())

```php
// PHPStan Level 8 forbids empty() - be explicit

// Check for empty string
if ( '' === $value ) { }

// Check array has items
if ( 0 === count( $array ) ) { }

// Check null or false
if ( null === $var || false === $var ) { }

// Type + value check
if ( is_string( $value ) && '' !== $value ) { }
```

### PHPDoc for Complex Types

```php
/**
 * Get video field data.
 *
 * @param int|null $post_id Post ID or null for current.
 * @return array<string, string>|null Video data or null if not found.
 */
function mia_get_video_field( $post_id = null ): ?array { }
```

---

## Core Skill: Schema.org / Structured Data

### Custom Schema Classes (OOP Architecture)

```php
namespace Mia_Aesthetics\Schema;

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
            '@type'            => array( 'Person', 'Physician' ),
            '@id'              => get_permalink() . '#surgeon',
            'name'             => get_the_title(),
            'image'            => $this->get_headshot_url(),
            'worksFor'         => array( '@id' => home_url( '/#organization' ) ),
            'medicalSpecialty' => 'PlasticSurgery',
        );
    }
}
```

### Yoast SEO Integration

```php
// Extend Yoast's schema graph
add_filter( 'wpseo_schema_graph_pieces', function( $pieces, $context ) {
    $pieces[] = new Surgeon_Schema( $context );
    $pieces[] = new Clinic_Schema( $context );
    $pieces[] = new FAQ_Schema( $context );
    return $pieces;
}, 10, 2 );
```

---

## Performance Optimization Techniques

### LCP (Largest Contentful Paint) Optimization

```html
<!-- Hero image: high priority, no lazy loading -->
<img src="hero.jpg"
    fetchpriority="high"
    data-no-lazy="1"
    width="1920"
    height="600">
```

### Responsive Images with srcset

```php
// WordPress generates srcset automatically
echo wp_get_attachment_image(
    $image_id,
    'medium_large',
    false,
    array(
        'sizes'   => '(max-width: 767px) 100vw, 600px',
        'loading' => 'lazy',
    )
);
```

### Custom Image Sizes

```php
add_image_size( 'hero-mobile', 640, 400, true );
add_image_size( 'hero-desktop', 1920, 800, true );
add_image_size( 'card-thumb', 600, 400, true );
add_image_size( 'gallery-small', 300, 225, true );
```

---

## Summary: Skills Matrix

| Skill Category | Demonstrated Techniques |
|---------------|------------------------|
| **Hooks & Filters** | 15+ actions, 25+ filters, custom hooks |
| **Backend Development** | 8 CPTs, conditional loading, template mapping |
| **Database Integration** | WP_Query optimization, pre_get_posts, meta queries, caching |
| **ACF Pro** | Type-safe retrieval, repeaters, groups, options pages |
| **Security** | Output escaping, input sanitization, capability checks |
| **Type Safety** | PHPStan Level 8, return types, strict comparisons |
| **Schema.org** | Custom classes, Yoast integration, MedicalClinic/Physician |
| **Performance** | LCP optimization, responsive images, conditional assets |
| **Accessibility** | ARIA landmarks, semantic HTML, WCAG 2.1 AA |

---

## Standards Compliance

| Standard | Implementation |
|----------|---------------|
| PHPStan Level 8 + Bleeding Edge | All PHP code passes |
| WordPress Coding Standards (PHPCS) | Full compliance |
| WCAG 2.1 AA | Accessibility patterns throughout |
| Core Web Vitals | LCP, CLS, FID optimized |
