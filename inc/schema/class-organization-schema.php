<?php
/**
 * Organization Schema
 * 
 * Handles modifications to Yoast's organization schema
 * 
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Organization_Schema {
    
    /**
     * Initialize organization schema modifications
     */
    public static function init() {
        add_filter( 'wpseo_schema_organization', [ __CLASS__, 'enhance_organization_schema' ], 11, 2 );
    }
    
    /**
     * Enhance Yoast's organization schema with medical-specific properties
     * 
     * @param array $data The organization schema data
     * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context
     * @return array Modified organization data
     */
    public static function enhance_organization_schema( $data, $context ) {
        // Change type to MedicalOrganization which supports medicalSpecialty
        $data['@type'] = [ 'Organization', 'MedicalOrganization' ];
        
        // Set our custom properties (following Yoast's documentation pattern)
        $data['alternateName']    = 'Affordable Cosmetic Surgery';
        $data['slogan']           = 'Beauty Redefined';
        $data['foundingDate']     = '2018-01-01';
        $data['legalName']        = 'Mia Aesthetics, LLC';
        $data['medicalSpecialty'] = 'PlasticSurgery';
        
        // Remove the image property to prevent duplicate logo display
        // Yoast sets both logo and image to the same value, so we only need logo
        unset( $data['image'] );
        
        return $data;
    }
}