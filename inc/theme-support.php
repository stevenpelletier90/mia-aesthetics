<?php
/**
 * Theme setup & Gutenberg enhancements.
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'after_setup_theme', 'mia_setup' );
/**
 * Register theme supports, menus, and sizes.
 */
function mia_setup() {

    /* ---- Core --------------------------------------------------------- */
    add_theme_support( 'title-tag' );          // SEO‑friendly <title>.
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
        'navigation-widgets',
    ] );

    /* ---- Branding ----------------------------------------------------- */
    add_theme_support( 'custom-logo', [
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    /* ---- Block Editor ------------------------------------------------- */
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-line-height' );
    add_theme_support( 'custom-units' );
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'disable-custom-colors' );
    add_theme_support( 'disable-custom-font-sizes' );

    /* ---- Yoast SEO ---------------------------------------------------- */
    if ( function_exists( 'yoast_breadcrumb' ) ) {
        add_theme_support( 'yoast-seo-breadcrumbs' );
    }

    /* ---- Navigation --------------------------------------------------- */
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'mia' ),
        'footer'  => __( 'Footer Navigation', 'mia' ),
        'social'  => __( 'Social Links', 'mia' ),
    ] );

    /* ---- Images ------------------------------------------------------- */
    add_image_size( 'hero-mobile',    640,  400, true );
    add_image_size( 'hero-tablet',   1024,  600, true );
    add_image_size( 'hero-desktop',  1920,  800, true );
    add_image_size( 'card-thumb',     600,  400, true );
    add_image_size( 'profile',        600,  600, true );
    add_image_size( 'gallery-small',  300,  225, true );
    add_image_size( 'gallery-medium', 450,  338, true );
    add_image_size( 'gallery-large',  600,  450, true );

    /* ---- Content width (classic embeds) ------------------------------ */
    $GLOBALS['content_width'] = 1200;
}

/**
 * Load editor‑only styles.
 */
add_action( 'after_setup_theme', 'mia_editor_styles' );
function mia_editor_styles() {
    add_editor_style( [
        'assets/css/_fonts.css',
        'assets/css/_base.css',
        'assets/bootstrap/css/bootstrap.min.css',
    ] );
}

/**
 * Add View Source button to admin bar
 */
add_action( 'admin_bar_menu', 'mia_admin_bar_view_source', 100 );
function mia_admin_bar_view_source( $wp_admin_bar ) {
    if ( ! is_admin() && ! is_user_logged_in() ) {
        return;
    }
    
    // Get current URL and replace domain
    $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $source_url = str_replace( $_SERVER['HTTP_HOST'], 'miaaesthetics.com', $current_url );
    
    $wp_admin_bar->add_node( [
        'id'    => 'view-source',
        'title' => 'View Source',
        'href'  => $source_url,
        'meta'  => [
            'target' => '_blank',
            'rel'    => 'noopener noreferrer'
        ]
    ] );
}

/**
 * Enable Google Maps API for ACF Pro
 */
add_action( 'acf/init', 'mia_acf_init' );
function mia_acf_init() {
    acf_update_setting( 'google_api_key', 'AIzaSyAiXSTjbyqjv_b9yGrxVyXYRmZQZ4GXBJ4' );
}
