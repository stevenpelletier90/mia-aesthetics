<?php
/**
 * Theme bootstrap for Mia Aesthetics.
 *
 * Loads all core theme features and helper modules.
 * Each include is responsible for a specific area of theme functionality.
 */

// Load helper modules (see inc/ for details)
// 1. CORE FOUNDATION (WordPress features, no dependencies)
require_once get_template_directory() . '/inc/theme-support.php';         // WordPress theme support features (MUST be first)
require_once get_template_directory() . '/inc/state-abbreviations.php';   // US state abbreviation lookup and helper (pure utility)

// 2. UTILITIES (minimal dependencies, used by other modules)
require_once get_template_directory() . '/inc/media-helpers.php';         // Video processing, image handling, gallery utilities
require_once get_template_directory() . '/inc/cache-helpers.php';         // Cache management and clearing (used by queries)

// 3. TEMPLATE UTILITIES (depends on utilities above)
require_once get_template_directory() . '/inc/template-helpers.php';      // Template/UI helpers (uses state abbreviations)

// 4. FEATURE MODULES (moderate dependencies)
require_once get_template_directory() . '/inc/featured-image-column.php'; // Admin featured image column

// 5. QUERY MODIFICATIONS (depends on caching utilities)
require_once get_template_directory() . '/inc/queries.php';               // Custom query modifications and filters

// 6. NAVIGATION (depends on utilities and state functions)
require_once get_template_directory() . '/inc/menu-helpers.php';          // Menu rendering helpers and caching (uses state abbreviations)
require_once get_template_directory() . '/inc/menus.php';                 // Menu structure and rendering (uses menu helpers)

// 7. SEO AND METADATA (depends on multiple utility modules)
require_once get_template_directory() . '/inc/schema.php';               // Modular schema system entry point

// 8. ASSET MANAGEMENT (MUST be last to properly detect template context)
require_once get_template_directory() . '/inc/enqueue.php';               // Asset enqueueing with versioning and conditional loading
