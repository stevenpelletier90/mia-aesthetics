<?php
/**
 * Surgeon Schema
 *
 * Generates schema markup for surgeon pages
 *
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load the video schema trait.
require_once __DIR__ . '/trait-video-schema.php';

/**
 * Surgeon Schema markup generator for individual surgeon pages
 */
class Surgeon_Schema {

	use Video_Schema_Trait;

	/**
	 * Yoast SEO context object
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context
	 */
	private $context;

	/**
	 * Get video title fallback for surgeons
	 *
	 * @return string The fallback video title.
	 */
	protected function get_video_title_fallback(): string {
		return 'Dr. ' . get_the_title() . ' - Featured Video';
	}

	/**
	 * Get video description fallback for surgeons
	 *
	 * @return string The fallback video description.
	 */
	protected function get_video_description_fallback(): string {
		return 'Learn more about Dr. ' . get_the_title() . ' at Mia Aesthetics';
	}

	/**
	 * Constructor
	 *
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context The Yoast SEO context.
	 */
	public function __construct( $context ) {
		$this->context = $context;
	}

	/**
	 * Determines if this schema piece is needed
	 *
	 * @return bool
	 */
	public function is_needed() {
		return is_singular( 'surgeon' );
	}

	/**
	 * Generate the surgeon schema
	 *
	 * @return array<int, array<string, mixed>> Schema.org compliant Person/Physician data
	 */
	public function generate(): array {
		$surgeon_id = get_the_ID();
		if ( false === $surgeon_id || 0 === $surgeon_id ) {
			return array();
		}
		$org_id = $this->context->site_url . '#organization';

		$schema_data = array();

		// Get associated clinic.
		$clinic_obj = get_field( 'surgeon_location', $surgeon_id );
		$clinic_id  = null !== $clinic_obj ? get_permalink( $clinic_obj->ID ) . '#clinic' : null;

		$surgeon = array(
			'@type'            => array( 'Person', 'Physician' ),
			'@id'              => get_permalink( $surgeon_id ) . '#physician',
			'name'             => get_the_title(),
			'jobTitle'         => 'Board Certified Plastic Surgeon',
			'medicalSpecialty' => 'PlasticSurgery',
			'url'              => get_permalink( $surgeon_id ),
			'affiliation'      => array( '@id' => $org_id ),
		);

		// Link to clinic.
		if ( null !== $clinic_id ) {
			$surgeon['worksFor']   = array( '@id' => $clinic_id );
			$surgeon['department'] = array( '@id' => $clinic_id );
		}

		// Contact info from associated location.
		if ( null !== $clinic_obj ) {
			$address = $this->get_address( $clinic_obj->ID );
			if ( null !== $address ) {
				$surgeon['address'] = $address;
			}

			$telephone = get_field( 'phone_number', $clinic_obj->ID );
			if ( is_string( $telephone ) && '' !== $telephone ) {
				$surgeon['telephone'] = $telephone;
			}

			$surgeon['priceRange'] = '$1,000-$20,000';
		}

		// Description.
		$surgeon['description'] = $this->get_description( $surgeon_id );

		// Image.
		$image_url = $this->get_image( $surgeon_id );
		if ( '' !== $image_url ) {
			$surgeon['image'] = $image_url;
		}

		// Professional credentials.
		if ( null !== get_field( 'board_certified', $surgeon_id ) && true === get_field( 'board_certified', $surgeon_id ) ) {
			$surgeon['hasCredential'] = array(
				'@type'              => 'EducationalOccupationalCredential',
				'credentialCategory' => 'Board Certification',
				'recognizedBy'       => array(
					'@type' => 'Organization',
					'name'  => 'American Board of Plastic Surgery',
				),
			);
		}

		// Specialties.
		$specialties = $this->get_specialties( $surgeon_id );
		if ( array() !== $specialties ) {
			$surgeon['knowsAbout'] = $specialties;
		}

		// Education.
		$school = get_field( 'medical_school', $surgeon_id );
		if ( null !== $school && '' !== $school ) {
			$surgeon['alumniOf'] = array(
				'@type' => 'EducationalOrganization',
				'name'  => $school,
			);
		}

		$schema_data[] = $surgeon;

		// Add separate VideoObject schema if video exists.
		$video = $this->get_featured_video( $surgeon_id );
		if ( null !== $video ) {
			$schema_data[] = $video;
		}

		return $schema_data;
	}

	/**
	 * Get surgeon description
	 *
	 * @param int $surgeon_id The surgeon post ID.
	 * @return string The surgeon description.
	 */
	private function get_description( $surgeon_id ) {
		$desc = get_post_meta( $surgeon_id, '_yoast_wpseo_metadesc', true );
		if ( null !== $desc && '' !== $desc ) {
			return $desc;
		}

		return 'Dr. ' . get_the_title() . ' is a board-certified plastic surgeon at Mia Aesthetics specializing in cosmetic and reconstructive procedures.';
	}

	/**
	 * Get surgeon image
	 *
	 * @param int $surgeon_id The surgeon post ID.
	 * @return string The surgeon image URL.
	 */
	private function get_image( $surgeon_id ) {
		$featured_image = $this->get_featured_image( $surgeon_id );
		if ( null !== $featured_image ) {
			return $featured_image;
		}

		$video_image = $this->get_video_image( $surgeon_id );
		if ( null !== $video_image ) {
			return $video_image;
		}

		// Default logo as last resort.
		return get_template_directory_uri() . '/assets/images/mia-logo.png';
	}

	/**
	 * Get featured image URL
	 *
	 * @param int $surgeon_id The surgeon post ID.
	 * @return string|null
	 */
	private function get_featured_image( $surgeon_id ) {
		if ( ! has_post_thumbnail( $surgeon_id ) ) {
			return null;
		}

		$featured_image = get_the_post_thumbnail_url( $surgeon_id, 'full' );
		return false !== $featured_image ? $featured_image : null;
	}

	/**
	 * Get surgeon specialties
	 *
	 * @param int $surgeon_id The surgeon post ID.
	 * @return array<int, mixed> Array of surgeon specialties.
	 */
	private function get_specialties( $surgeon_id ): array {
		$specialties = array();

		for ( $i = 1; $i <= 3; $i++ ) {
			$specialty = get_field( 'specialty_' . $i, $surgeon_id );
			if ( null !== $specialty && '' !== $specialty ) {
				$specialties[] = $specialty;
			}
		}

		return $specialties;
	}

	/**
	 * Get address from surgeon's associated location
	 *
	 * @param int $loc_id The location post ID.
	 * @return array<string, string>|null
	 */
	private function get_address( int $loc_id ): ?array {
		$location_map = get_field( 'location_map', $loc_id );

		if ( ! is_array( $location_map ) || count( $location_map ) === 0 ) {
			return null;
		}

		$street_number = $location_map['street_number'] ?? '';
		$street_name   = $location_map['street_name'] ?? '';
		$street        = trim( $street_number . ' ' . $street_name );

		$city  = $location_map['city'] ?? '';
		$state = $location_map['state_short'] ?? $location_map['state'] ?? '';
		$zip   = $location_map['post_code'] ?? '';

		if ( '' !== $street && '' !== $city && '' !== $state ) {
			return array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $street,
				'addressLocality' => $city,
				'addressRegion'   => $state,
				'postalCode'      => $zip,
				'addressCountry'  => 'US',
			);
		}

		return null;
	}
}
