<?php
/**
 * Schema Loader
 * 
 * Main class responsible for loading and initializing all schema components
 * 
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Schema_Loader {
    
    /**
     * Initialize the schema system
     */
    public static function init() {
        // Load schema classes
        self::load_dependencies();
        
        // Initialize organization schema enhancements
        Organization_Schema::init();
        
        // Register custom schema pieces with Yoast
        add_filter( 'wpseo_schema_graph_pieces', [ __CLASS__, 'register_schema_pieces' ], 11, 2 );
    }
    
    /**
     * Load required schema classes
     */
    private static function load_dependencies() {
        $schema_dir = get_template_directory() . '/inc/schema/';
        
        require_once $schema_dir . 'class-organization-schema.php';
        require_once $schema_dir . 'class-surgeon-schema.php';
        require_once $schema_dir . 'class-clinic-schema.php';
        require_once $schema_dir . 'class-faq-schema.php';
    }
    
    /**
     * Register custom schema pieces with Yoast
     * 
     * @param array $pieces Existing schema pieces
     * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context
     * @return array Modified schema pieces
     */
    public static function register_schema_pieces( $pieces, $context ) {
        if ( is_singular( 'surgeon' ) ) {
            $pieces[] = new Surgeon_Schema( $context );
        }
        
        if ( is_singular( 'location' ) ) {
            $pieces[] = new Clinic_Schema( $context );
        }
        
        // FAQ Schema can be added to any page type
        $faq_schema = new FAQ_Schema( $context );
        if ( $faq_schema->is_needed() ) {
            $pieces[] = $faq_schema;
        }
        
        return $pieces;
    }
}