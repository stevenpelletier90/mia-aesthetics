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

/**
 * Organization Schema markup enhancements for medical organizations
 */
class Organization_Schema {

	/**
	 * Initialize organization schema modifications
	 *
	 * @return void
	 */
	public static function init(): void {
		add_filter( 'wpseo_schema_organization', array( self::class, 'enhance_organization_schema' ), 11, 2 );
	}

	/**
	 * Enhance Yoast's organization schema with medical-specific properties
	 *
	 * @param array<string, mixed>                    $data The organization schema data.
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context The Yoast context object (unused but required by filter).
	 * @return array<string, mixed> Modified organization data
	 */
	public static function enhance_organization_schema( $data, $context ): array {
		// Change type to MedicalOrganization which supports medicalSpecialty.
		$data['@type'] = array( 'Organization', 'MedicalOrganization' );

		// Set our custom properties (following Yoast's documentation pattern).
		$data['alternateName']    = 'Affordable Cosmetic Surgery';
		$data['slogan']           = 'Beauty Redefined';
		$data['foundingDate']     = '2017-01-01';
		$data['legalName']        = 'Mia Aesthetics, LLC';
		$data['medicalSpecialty'] = 'PlasticSurgery';

		// Use context to make schema more dynamic.
		if ( isset( $context->site_url ) ) {
			$data['url'] = $context->site_url;
		}

		// Remove the image property to prevent duplicate logo display.
		// Yoast sets both logo and image to the same value, so we only need logo.
		unset( $data['image'] );

		return $data;
	}
}
