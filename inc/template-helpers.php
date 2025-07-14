<?php
/**
 * Template Helper Functions for Mia Aesthetics Theme
 * 
 * Provides utility functions for templates, UI components, and display helpers.
 * Includes logo handling, image utilities, formatting functions, and reusable components.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Logo and Branding Functions
 */

/**
 * Get the site logo URL with fallback support
 * 
 * @param bool $fallback Whether to use fallback if custom logo not set
 * @return string|false Logo URL or false if not found
 */
function mia_get_logo_url($fallback = true) {
    // Try custom logo first
    $custom_logo_id = get_theme_mod('custom_logo');
    
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($logo_url) {
            return $logo_url;
        }
    }
    
    // Fallback to known logo location if enabled
    if ($fallback) {
        $fallback_path = '/2024/11/miaaesthetics-logo.svg';
        $upload_dir = wp_upload_dir();
        $logo_path = $upload_dir['basedir'] . $fallback_path;
        
        if (file_exists($logo_path)) {
            return $upload_dir['baseurl'] . $fallback_path;
        }
    }
    
    return false;
}

/**
 * Output the site logo with proper attributes
 * 
 * @param array $args Logo arguments
 */
function mia_the_logo($args = []) {
    $defaults = [
        'height' => '50',
        'width' => '200',
        'class' => 'd-inline-block',
        'alt' => get_bloginfo('name') . ' Logo',
        'fetchpriority' => false,
        'loading' => false,
        'link' => true,
        'link_class' => 'navbar-brand',
        'aria_label' => 'Homepage'
    ];
    
    $args = wp_parse_args($args, $defaults);
    $logo_url = mia_get_logo_url();
    
    // Build logo HTML
    if ($logo_url) {
        $attributes = [
            'src' => esc_url($logo_url),
            'alt' => esc_attr($args['alt']),
            'height' => esc_attr($args['height']),
            'width' => esc_attr($args['width']),
            'class' => esc_attr($args['class'])
        ];
        
        if ($args['fetchpriority']) {
            $attributes['fetchpriority'] = 'high';
        }
        
        if ($args['loading']) {
            $attributes['loading'] = esc_attr($args['loading']);
        }
        
        $img_tag = '<img';
        foreach ($attributes as $key => $value) {
            $img_tag .= ' ' . $key . '="' . $value . '"';
        }
        $img_tag .= ' />';
        
        // Wrap in link if requested
        if ($args['link']) {
            $link_attrs = [
                'href' => esc_url(home_url('/')),
                'class' => esc_attr($args['link_class']),
                'aria-label' => esc_attr($args['aria_label'])
            ];
            
            echo '<a';
            foreach ($link_attrs as $key => $value) {
                echo ' ' . $key . '="' . $value . '"';
            }
            echo '>' . $img_tag . '</a>';
        } else {
            echo $img_tag;
        }
    } else {
        // Fallback to text logo
        $text = '<span class="navbar-brand-text">' . esc_html(get_bloginfo('name')) . '</span>';
        
        if ($args['link']) {
            echo '<a href="' . esc_url(home_url('/')) . '" class="' . esc_attr($args['link_class']) . '">' . $text . '</a>';
        } else {
            echo $text;
        }
    }
}

/**
 * Image Helper Functions
 */

/**
 * Get attachment ID by filename with caching
 * 
 * @param string $filename The filename to search for
 * @return int|false Attachment ID or false if not found
 */
function mia_get_attachment_id_by_filename($filename) {
    if (empty($filename)) {
        return false;
    }
    
    // Create cache key
    $cache_key = 'mia_attachment_id_' . md5($filename);
    $attachment_id = wp_cache_get($cache_key, 'mia_theme');
    
    if ($attachment_id === false) {
        global $wpdb;
        
        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} 
            WHERE meta_key = '_wp_attached_file' 
            AND meta_value LIKE %s 
            LIMIT 1",
            '%' . $wpdb->esc_like($filename)
        ));
        
        // Cache result for 2 hours (attachment files rarely change)
        wp_cache_set($cache_key, $attachment_id ?: 0, 'mia_theme', 2 * HOUR_IN_SECONDS);
    }
    
    return $attachment_id ?: false;
}

/**
 * Get image URL by filename from uploads directory
 * 
 * @param string $filename The filename to search for
 * @param string $subdir Optional subdirectory (e.g., '2025/04')
 * @return string|false Image URL or false if not found
 */
function mia_get_image_url_by_filename($filename, $subdir = '') {
    if (empty($filename)) {
        return false;
    }
    
    $upload_dir = wp_upload_dir();
    
    // Check direct path first
    if ($subdir) {
        $file_path = $upload_dir['basedir'] . '/' . trim($subdir, '/') . '/' . $filename;
        
        if (file_exists($file_path)) {
            return $upload_dir['baseurl'] . '/' . trim($subdir, '/') . '/' . $filename;
        }
    }
    
    // Use cached attachment lookup
    $attachment_id = mia_get_attachment_id_by_filename($filename);
    
    if ($attachment_id) {
        return wp_get_attachment_url($attachment_id);
    }
    
    return false;
}

/**
 * Get responsive image data with srcset
 * 
 * @param string $filename Base filename
 * @param string $subdir Subdirectory in uploads
 * @param string $size WordPress image size
 * @return array|false Array with src, srcset, sizes or false
 */
function mia_get_responsive_image_data($filename, $subdir = '', $size = 'full') {
    if (empty($filename)) {
        return false;
    }
    
    // Create cache key including all parameters
    $cache_key = 'mia_responsive_image_' . md5($filename . '|' . $subdir . '|' . $size);
    $image_data = wp_cache_get($cache_key, 'mia_theme');
    
    if ($image_data === false) {
        // Try to find attachment ID using shared function
        $attachment_id = mia_get_attachment_id_by_filename($filename);
        
        if ($attachment_id) {
            $image_data = [
                'id' => $attachment_id,
                'src' => wp_get_attachment_image_url($attachment_id, $size),
                'srcset' => wp_get_attachment_image_srcset($attachment_id, $size),
                'sizes' => wp_get_attachment_image_sizes($attachment_id, $size)
            ];
        } else {
            // Fallback to manual construction
            $src = mia_get_image_url_by_filename($filename, $subdir);
            
            if ($src) {
                $image_data = [
                    'id' => 0,
                    'src' => $src,
                    'srcset' => $src . ' 1x',
                    'sizes' => '100vw'
                ];
            } else {
                $image_data = null;
            }
        }
        
        // Cache result for 2 hours
        wp_cache_set($cache_key, $image_data ?: 0, 'mia_theme', 2 * HOUR_IN_SECONDS);
    }
    
    return $image_data ?: false;
}

/**
 * Output responsive image HTML
 * 
 * @param string $filename Filename or attachment ID
 * @param array $args Image arguments
 */
function mia_responsive_image($filename, $args = []) {
    $defaults = [
        'size' => 'full',
        'class' => 'img-fluid',
        'alt' => '',
        'loading' => 'lazy',
        'subdir' => ''
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    // Handle attachment ID
    if (is_numeric($filename)) {
        echo wp_get_attachment_image($filename, $args['size'], false, [
            'class' => $args['class'],
            'alt' => $args['alt'],
            'loading' => $args['loading']
        ]);
        return;
    }
    
    // Handle filename
    $image_data = mia_get_responsive_image_data($filename, $args['subdir'], $args['size']);
    
    if ($image_data) {
        $attributes = [
            'src' => esc_url($image_data['src']),
            'srcset' => esc_attr($image_data['srcset']),
            'sizes' => esc_attr($image_data['sizes']),
            'class' => esc_attr($args['class']),
            'alt' => esc_attr($args['alt']),
            'loading' => esc_attr($args['loading'])
        ];
        
        echo '<img';
        foreach ($attributes as $key => $value) {
            if (!empty($value)) {
                echo ' ' . $key . '="' . $value . '"';
            }
        }
        echo ' />';
    }
}

/**
 * Formatting Helper Functions
 */

/**
 * Format city, state, ZIP for display
 * 
 * @param string $city City name
 * @param string $state State name or abbreviation
 * @param string $zip ZIP code
 * @return string Formatted location string
 */
function mia_format_city_state_zip($city = '', $state = '', $zip = '') {
    $parts = [];
    
    // Add city
    if (!empty($city)) {
        $parts[] = trim($city);
    }
    
    // Add state abbreviation
    if (!empty($state)) {
        $parts[] = mia_get_state_abbr(trim($state));
    }
    
    // Combine city and state
    $location = '';
    if (!empty($parts)) {
        $location = implode(', ', $parts);
    }
    
    // Add ZIP code
    if (!empty($zip)) {
        $location = $location ? $location . ' ' . trim($zip) : trim($zip);
    }
    
    return $location;
}

/**
 * Format phone number for display
 * 
 * @param string $phone Phone number
 * @param bool $link Whether to return as tel: link
 * @return string Formatted phone number
 */
function mia_format_phone($phone, $link = false) {
    // Remove non-numeric characters
    $clean = preg_replace('/[^0-9]/', '', $phone);
    
    // Format as (XXX) XXX-XXXX
    if (strlen($clean) === 10) {
        $formatted = sprintf(
            '(%s) %s-%s',
            substr($clean, 0, 3),
            substr($clean, 3, 3),
            substr($clean, 6)
        );
        
        if ($link) {
            return '<a href="tel:+1' . $clean . '">' . $formatted . '</a>';
        }
        
        return $formatted;
    }
    
    // Return original if not 10 digits
    if ($link && !empty($clean)) {
        return '<a href="tel:' . $phone . '">' . $phone . '</a>';
    }
    
    return $phone;
}

/**
 * UI Component Functions
 */

/**
 * Display FAQ section with Bootstrap accordion
 * 
 * @param bool $show_heading Whether to show section heading
 * @return string HTML output
 */
function mia_display_faqs($show_heading = true) {
    $faq_section = get_field('faq_section');
    
    // Check for valid FAQ data
    if (empty($faq_section) || empty($faq_section['faqs']) || !is_array($faq_section['faqs'])) {
        return '';
    }
    
    $faqs = $faq_section['faqs'];
    
    // Filter out empty FAQs and check if we have any valid ones
    $valid_faqs = array_filter($faqs, function($faq) {
        return !empty($faq['question']) && !empty($faq['answer']);
    });
    
    // If no valid FAQs, return empty
    if (empty($valid_faqs)) {
        return '';
    }
    
    $accordion_id = 'faq-accordion-' . get_the_ID();
    
    ob_start();
    ?>
    <section class="faqs-section my-5" <?php if ($show_heading) echo 'aria-labelledby="faq-heading-' . get_the_ID() . '"'; ?>>
        <?php if ($show_heading): ?>
            <?php
            $section_title = !empty($faq_section['title']) 
                ? $faq_section['title'] 
                : 'Frequently Asked Questions';
            ?>
            <h2 id="faq-heading-<?php echo get_the_ID(); ?>" class="mb-4">
                <?php echo esc_html($section_title); ?>
            </h2>
            
            <?php if (!empty($faq_section['description'])): ?>
                <div class="faq-description mb-4">
                    <?php echo wp_kses_post($faq_section['description']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="accordion" id="<?php echo esc_attr($accordion_id); ?>">
            <?php foreach ($valid_faqs as $index => $faq): ?>
                <?php
                $item_id = 'faq-' . get_the_ID() . '-' . $index;
                $heading_id = 'heading-' . $item_id;
                $collapse_id = 'collapse-' . $item_id;
                $is_first = ($index === 0);
                ?>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                        <button class="accordion-button <?php echo $is_first ? '' : 'collapsed'; ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#<?php echo esc_attr($collapse_id); ?>"
                                aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr($collapse_id); ?>">
                            <?php echo esc_html($faq['question']); ?>
                        </button>
                    </h3>
                    <div id="<?php echo esc_attr($collapse_id); ?>"
                         class="accordion-collapse collapse <?php echo $is_first ? 'show' : ''; ?>"
                         data-bs-parent="#<?php echo esc_attr($accordion_id); ?>">
                        <div class="accordion-body">
                            <?php echo wp_kses_post($faq['answer']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

/**
 * Generate consistent button HTML
 * 
 * @param string $text Button text
 * @param string $url Button URL
 * @param array $args Additional arguments
 * @return string Button HTML
 */
function mia_button($text, $url = '#', $args = []) {
    $defaults = [
        'variant' => 'primary',
        'size' => '',
        'icon' => '',
        'icon_position' => 'after',
        'class' => '',
        'target' => '',
        'attributes' => []
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    // Build classes
    $classes = ['btn'];
    $classes[] = 'btn-' . $args['variant'];
    
    if (!empty($args['size'])) {
        $classes[] = 'btn-' . $args['size'];
    }
    
    if (!empty($args['class'])) {
        $classes[] = $args['class'];
    }
    
    // Build attributes
    $attributes = [
        'class' => implode(' ', $classes)
    ];
    
    if (!empty($args['target'])) {
        $attributes['target'] = $args['target'];
        if ($args['target'] === '_blank') {
            $attributes['rel'] = 'noopener noreferrer';
        }
    }
    
    // Merge custom attributes
    $attributes = array_merge($attributes, $args['attributes']);
    
    // Build icon HTML
    $icon_html = '';
    if (!empty($args['icon'])) {
        $icon_html = '<i class="' . esc_attr($args['icon']) . '"></i>';
    }
    
    // Build button content
    $content = '';
    if ($args['icon_position'] === 'before' && $icon_html) {
        $content .= $icon_html . ' ';
    }
    
    $content .= esc_html($text);
    
    if ($args['icon_position'] === 'after' && $icon_html) {
        $content .= ' ' . $icon_html;
    }
    
    // Output button or link
    if ($url === '#' || empty($url)) {
        $tag = 'button';
        $attributes['type'] = 'button';
    } else {
        $tag = 'a';
        $attributes['href'] = esc_url($url);
    }
    
    $html = '<' . $tag;
    foreach ($attributes as $key => $value) {
        $html .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
    }
    $html .= '>' . $content . '</' . $tag . '>';
    
    return $html;
}

/**
 * Display social media links
 * 
 * @param array $args Display arguments
 */
function mia_social_links($args = []) {
    $defaults = [
        'class' => 'social-links',
        'item_class' => 'social-link',
        'icon_prefix' => 'fab fa-',
        'platforms' => ['facebook', 'instagram', 'twitter', 'youtube', 'tiktok']
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    echo '<div class="' . esc_attr($args['class']) . '">';
    
    foreach ($args['platforms'] as $platform) {
        $url = get_field($platform . '_url', 'option');
        
        if (!empty($url)) {
            $icon_class = $args['icon_prefix'] . $platform;
            
            // Special cases for icon names
            if ($platform === 'twitter') {
                $icon_class = $args['icon_prefix'] . 'x-twitter';
            }
            
            echo '<a href="' . esc_url($url) . '" 
                    class="' . esc_attr($args['item_class']) . '" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    aria-label="' . esc_attr(ucfirst($platform)) . '">';
            echo '<i class="' . esc_attr($icon_class) . '"></i>';
            echo '</a>';
        }
    }
    
    echo '</div>';
}

/**
 * Breadcrumb Component
 *
 * Centralized wrapper for Yoast breadcrumbs so styling can be changed in one place.
 *
 * Usage in templates:
 * <?php if ( function_exists( 'yoast_breadcrumb' ) ) : ?>
 *     <?php mia_breadcrumbs(); ?>
 * <?php endif; ?>
 *
 * Adjust HTML/CSS once here to affect all templates.
 *
 * @return void
 */
function mia_breadcrumbs() {
    if ( ! function_exists( 'yoast_breadcrumb' ) ) {
        return;
    }

    // Yoast outputs breadcrumb trail as string.
    $breadcrumbs = yoast_breadcrumb( '', '', false );

    if ( empty( $breadcrumbs ) ) {
        return;
    }

    // Standard wrapper for consistency & accessibility.
    echo '<nav aria-label="Breadcrumb" class="breadcrumb-nav">';
    echo '<div class="container">';
    echo '<span class="visually-hidden">You are here:</span>';
    echo $breadcrumbs;
    echo '</div></nav>';
}

/**
 * Template Filter Functions
 */

/**
 * Add custom body classes for template identification
 */
function mia_template_body_classes($classes) {
    // Add class for pages with gallery shortcode
    if (is_page()) {
        global $post;
        if ($post && has_shortcode($post->post_content, 'gallery')) {
            $classes[] = 'has-gallery';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'mia_template_body_classes');

/**
 * Utility Functions
 */

/**
 * Check if current page should show sidebar
 * 
 * @return bool
 */
function mia_has_sidebar() {
    // No sidebar on these pages
    if (is_front_page() || is_404() || is_page_template(['page-blank-canvas.php', 'page-hero-canvas.php'])) {
        return false;
    }
    
    // Check theme option
    $show_sidebar = get_theme_mod('show_sidebar', true);
    
    return apply_filters('mia_has_sidebar', $show_sidebar);
}

/**
 * Get the current page/post title for hero sections
 * 
 * @return string
 */
function mia_get_hero_title() {
    if (is_front_page()) {
        return get_bloginfo('name');
    }
    
    if (is_home()) {
        return get_the_title(get_option('page_for_posts'));
    }
    
    if (is_archive()) {
        return get_the_archive_title();
    }
    
    if (is_search()) {
        return sprintf('Search Results for: %s', get_search_query());
    }
    
    if (is_404()) {
        return 'Page Not Found';
    }
    
    return get_the_title();
}

/**
 * Backwards compatibility for display_page_faqs function
 */
if (!function_exists('display_page_faqs')) {
    function display_page_faqs($show_heading = true) {
        return mia_display_faqs($show_heading);
    }
}
