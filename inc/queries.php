<?php
/**
 * Query Modifications and Filters for Mia Aesthetics Theme
 * 
 * Handles all query modifications, custom filters, and archive behaviors.
 * Centralizes query logic for better maintainability and performance.
 * 
 * @package Mia_Aesthetics
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modify main queries for custom post type archives
 */
function mia_modify_archive_queries($query) {
    // Only modify main queries on the frontend
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Location archive modifications
    if (is_post_type_archive('location')) {
        $query->set('posts_per_page', -1);      // Show all locations
        $query->set('post_parent', 0);          // Only top-level locations
        $query->set('orderby', 'title');        // Alphabetical order
        $query->set('order', 'ASC');
    }
    
    // Surgeon archive modifications
    elseif (is_post_type_archive('surgeon')) {
        $query->set('posts_per_page', -1);      // Show all surgeons
        $query->set('orderby', 'menu_order');   // Manual order
        $query->set('order', 'ASC');
    }
    
    // Procedure archive modifications
    elseif (is_post_type_archive('procedure')) {
        $query->set('posts_per_page', -1);      // Show all procedures
        $query->set('post_parent', 0);          // Only top-level procedures
        $query->set('orderby', 'menu_order title'); // Manual order, then alphabetical
        $query->set('order', 'ASC');
    }
    
    // Case archive modifications
    elseif (is_post_type_archive('case')) {
        $query->set('posts_per_page', -1);      // Show all cases
        $query->set('orderby', 'date');         // Most recent first
        $query->set('order', 'DESC');
    }
    
    // Condition archive modifications
    elseif (is_post_type_archive('condition')) {
        $query->set('posts_per_page', -1);      // Show all conditions
        $query->set('orderby', 'title');        // Alphabetical order
        $query->set('order', 'ASC');
    }
    
    // Special archive modifications
    elseif (is_post_type_archive('special')) {
        $query->set('posts_per_page', 6);       // Paginate specials
        $query->set('orderby', 'menu_order date'); // Manual order, then date
        $query->set('order', 'DESC');
        
        // Only show active specials
        $query->set('meta_query', [
            'relation' => 'OR',
            [
                'key' => 'special_end_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            ],
            [
                'key' => 'special_end_date',
                'compare' => 'NOT EXISTS'
            ]
        ]);
    }
    
    // Non-surgical archive modifications
    elseif (is_post_type_archive('non-surgical')) {
        $query->set('posts_per_page', -1);      // Show all non-surgical
        $query->set('orderby', 'menu_order title'); // Manual order, then alphabetical
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'mia_modify_archive_queries');

/**
 * Modify taxonomy archive queries
 */
function mia_modify_taxonomy_queries($query) {
    // Only modify main queries on the frontend
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Case category taxonomy
    if (is_tax('case-category')) {
        $query->set('posts_per_page', -1);      // Show all cases
        $query->set('orderby', 'date');         // Most recent first
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'mia_modify_taxonomy_queries');

/**
 * Ensure correct body classes for custom post type archives
 */
function mia_archive_body_classes($classes) {
    // Get all registered post types
    $post_types = get_post_types(['public' => true], 'names');
    
    foreach ($post_types as $post_type) {
        if (is_post_type_archive($post_type)) {
            $class_name = 'post-type-archive-' . $post_type;
            if (!in_array($class_name, $classes)) {
                $classes[] = $class_name;
            }
        }
    }
    
    // Add specific classes for single post types
    if (is_singular()) {
        $post_type = get_post_type();
        if ($post_type) {
            $class_name = 'single-type-' . $post_type;
            if (!in_array($class_name, $classes)) {
                $classes[] = $class_name;
            }
        }
    }
    
    return $classes;
}
add_filter('body_class', 'mia_archive_body_classes', 999);

/**
 * Customize excerpt length based on context
 */
function mia_custom_excerpt_length($length) {
    // Shorter excerpts for archive pages
    if (is_archive() || is_home()) {
        return 20;
    }
    
    // Even shorter for search results
    if (is_search()) {
        return 15;
    }
    
    // Default length for other contexts
    return 30;
}
add_filter('excerpt_length', 'mia_custom_excerpt_length');

/**
 * Customize excerpt more text
 */
function mia_excerpt_more($more) {
    // Don't add "more" text in admin
    if (is_admin()) {
        return $more;
    }
    
    return '...';
}
add_filter('excerpt_more', 'mia_excerpt_more');

/**
 * Remove protected/private prefixes from titles
 */
function mia_remove_title_prefixes($title) {
    // Remove "Protected: " prefix
    $title = str_replace('Protected: ', '', $title);
    
    // Remove "Private: " prefix
    $title = str_replace('Private: ', '', $title);
    
    return $title;
}
add_filter('the_title', 'mia_remove_title_prefixes');

/**
 * Modify search query to include custom post types
 */
function mia_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Include relevant post types in search
        $searchable_types = [
            'post',
            'page',
            'procedure',
            'condition',
            'surgeon',
            'location',
            'non-surgical'
        ];
        
        $query->set('post_type', $searchable_types);
        
        // Limit search results
        $query->set('posts_per_page', 20);
    }
}
add_action('pre_get_posts', 'mia_search_filter');

/**
 * Exclude certain pages from search results (with caching)
 */
function mia_exclude_pages_from_search($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Get cached excluded page IDs
        $cache_key = 'mia_excluded_search_pages';
        $exclude_ids = get_transient($cache_key);
        
        if (false === $exclude_ids) {
            // Get IDs of pages to exclude with a single query
            $exclude_slugs = [
                'thank-you',
                'privacy-policy',
                'terms-of-service',
                'sitemap'
            ];
            
            $pages = get_posts([
                'post_type' => 'page',
                'post_name__in' => $exclude_slugs,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false
            ]);
            
            $exclude_ids = $pages ?: [];
            
            // Cache for 1 day (pages rarely change)
            set_transient($cache_key, $exclude_ids, DAY_IN_SECONDS);
        }
        
        if (!empty($exclude_ids)) {
            $query->set('post__not_in', $exclude_ids);
        }
    }
}
add_action('pre_get_posts', 'mia_exclude_pages_from_search');

/**
 * Add custom query vars
 */
function mia_add_query_vars($vars) {
    // Add custom query variables if needed
    $vars[] = 'surgeon_location';
    $vars[] = 'procedure_type';
    
    return $vars;
}
add_filter('query_vars', 'mia_add_query_vars');

/**
 * Modify queries based on custom query vars
 */
function mia_handle_custom_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Filter surgeons by location
        if (get_query_var('surgeon_location')) {
            $location_id = intval(get_query_var('surgeon_location'));
            if ($location_id) {
                $query->set('meta_query', [
                    [
                        'key' => 'surgeon_location',
                        'value' => $location_id,
                        'compare' => '='
                    ]
                ]);
            }
        }
        
        // Filter procedures by type
        if (get_query_var('procedure_type')) {
            $procedure_type = sanitize_text_field(get_query_var('procedure_type'));
            if ($procedure_type) {
                $query->set('meta_query', [
                    [
                        'key' => 'procedure_type',
                        'value' => $procedure_type,
                        'compare' => '='
                    ]
                ]);
            }
        }
    }
}
add_action('pre_get_posts', 'mia_handle_custom_queries');

/**
 * Optimize queries by removing unnecessary joins and cache operations
 */
function mia_optimize_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Remove unnecessary meta cache on archives that don't use meta fields
        if (is_post_type_archive()) {
            $post_type = get_query_var('post_type');
            
            // Post types that don't need meta cache in archives
            $no_meta_types = ['surgeon', 'procedure', 'condition'];
            if (in_array($post_type, $no_meta_types) && !$query->get('meta_query')) {
                $query->set('update_post_meta_cache', false);
            }
        }
        
        // Remove term cache updates if not needed
        if (!is_tax() && !is_category() && !is_tag()) {
            $query->set('update_post_term_cache', false);
        }
        
        // Optimize found_rows calculation when not needed
        if (is_post_type_archive() && !is_paged()) {
            $post_type = get_query_var('post_type');
            // Archives that show all items don't need found_rows
            $no_pagination_types = ['location', 'surgeon', 'procedure', 'condition', 'non-surgical'];
            if (in_array($post_type, $no_pagination_types)) {
                $query->set('no_found_rows', true);
            }
        }
    }
}
add_action('pre_get_posts', 'mia_optimize_queries', 999);

/**
 * Add pagination support for custom queries
 */
function mia_pagination_rewrite_rules() {
    // Add rewrite rules for custom post type pagination
    $post_types = ['special'];
    
    foreach ($post_types as $post_type) {
        add_rewrite_rule(
            $post_type . '/page/([0-9]+)/?$',
            'index.php?post_type=' . $post_type . '&paged=$matches[1]',
            'top'
        );
    }
}
add_action('init', 'mia_pagination_rewrite_rules');

/**
 * Fix pagination on custom post type archives
 */
function mia_fix_pagination($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive(['special'])) {
            if (get_query_var('paged')) {
                $query->set('paged', get_query_var('paged'));
            } elseif (get_query_var('page')) {
                $query->set('paged', get_query_var('page'));
            }
        }
    }
}
add_action('pre_get_posts', 'mia_fix_pagination');

/**
 * Get all non-surgical procedures grouped by category
 * Uses persistent transient caching for better performance
 */
function mia_get_non_surgical_by_category() {
    $cache_key = 'mia_non_surgical_grouped';
    $cached_results = get_transient($cache_key);
    
    if (false !== $cached_results) {
        return $cached_results;
    }
    
    $all_procedures = new WP_Query([
        'post_type' => 'non-surgical',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_meta_cache' => true, // Needed for ACF procedure_category field
        'update_post_term_cache' => false
    ]);
    
    $grouped = [
        'injectable' => [],
        'skin' => [],
        'body' => [],
        'wellness' => []
    ];
    
    $counts = [
        'injectable' => 0,
        'skin' => 0,
        'body' => 0,
        'wellness' => 0
    ];
    
    if (!empty($all_procedures->posts)) {
        foreach ($all_procedures->posts as $post_id) {
            $categories = get_field('procedure_category', $post_id);
            
            // Build post data array for caching
            $post_data = [
                'ID' => $post_id,
                'post_title' => get_the_title($post_id),
                'permalink' => get_permalink($post_id),
                'excerpt' => get_the_excerpt($post_id)
            ];
            
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    if (isset($grouped[$category])) {
                        $grouped[$category][] = $post_data;
                        $counts[$category]++;
                    }
                }
            } elseif (is_string($categories) && isset($grouped[$categories])) {
                $grouped[$categories][] = $post_data;
                $counts[$categories]++;
            }
        }
    }
    
    $cached_results = [
        'grouped' => $grouped,
        'counts' => $counts
    ];
    
    // Cache for 6 hours (procedures don't change frequently)
    set_transient($cache_key, $cached_results, 6 * HOUR_IN_SECONDS);
    
    return $cached_results;
}

/**
 * Clear query-related caches when relevant posts are updated
 */
function mia_clear_query_caches($post_id) {
    $post_type = get_post_type($post_id);
    
    // Clear non-surgical grouping cache
    if ($post_type === 'non-surgical') {
        delete_transient('mia_non_surgical_grouped');
    }
    
    // Clear search exclusion cache if a page is updated
    if ($post_type === 'page') {
        delete_transient('mia_excluded_search_pages');
    }
}
add_action('save_post', 'mia_clear_query_caches');
add_action('delete_post', 'mia_clear_query_caches');
add_action('trash_post', 'mia_clear_query_caches');
add_action('untrash_post', 'mia_clear_query_caches');
