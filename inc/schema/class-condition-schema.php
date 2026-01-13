<?php
/**
 * Condition Schema
 *
 * Generates MedicalCondition schema markup for condition pages
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
 * MedicalCondition schema markup generator for condition pages
 */
class Condition_Schema {

	use Video_Schema_Trait;

	/**
	 * Yoast SEO context object
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context
	 */
	private $context;

	/**
	 * Get video title fallback for conditions
	 *
	 * @return string The fallback video title.
	 */
	protected function get_video_title_fallback(): string {
		return get_the_title() . ' - Condition Overview';
	}

	/**
	 * Get video description fallback for conditions
	 *
	 * @return string The fallback video description.
	 */
	protected function get_video_description_fallback(): string {
		return 'Learn more about ' . get_the_title() . ' at Mia Aesthetics';
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
		return is_singular( 'condition' );
	}

	/**
	 * Generate the medical condition schema
	 *
	 * @return array<int, array<string, mixed>> Schema.org compliant MedicalCondition data
	 */
	public function generate(): array {
		$condition_id = get_the_ID();
		if ( false === $condition_id || 0 === $condition_id ) {
			return array();
		}

		$org_id = $this->context->site_url . '#organization';

		$schema_data = array();

		$condition = array(
			'@type' => 'MedicalCondition',
			'@id'   => get_permalink( $condition_id ) . '#condition',
			'name'  => get_the_title(),
			'url'   => get_permalink( $condition_id ),
		);

		// Description from Yoast meta or excerpt.
		$condition['description'] = $this->get_description( $condition_id );

		// Image.
		$image_url = $this->get_image( $condition_id );
		if ( '' !== $image_url ) {
			$condition['image'] = $image_url;
		}

		// Associated anatomy (body location).
		$body_location = $this->get_body_location( $condition_id );
		if ( '' !== $body_location ) {
			$condition['associatedAnatomy'] = array(
				'@type' => 'AnatomicalStructure',
				'name'  => $body_location,
			);
		}

		// Signs and symptoms from overview.
		$signs_symptoms = $this->get_signs_symptoms( $condition_id );
		if ( count( $signs_symptoms ) > 0 ) {
			$condition['signOrSymptom'] = array_map(
				static function ( $symptom ) {
					return array(
						'@type' => 'MedicalSignOrSymptom',
						'name'  => $symptom,
					);
				},
				$signs_symptoms
			);
		}

		// Possible treatment - link to organization.
		$condition['possibleTreatment'] = array(
			'@type'       => 'MedicalTherapy',
			'name'        => 'Cosmetic Surgery',
			'description' => 'Surgical and non-surgical treatment options available at Mia Aesthetics',
			'provider'    => array( '@id' => $org_id ),
		);

		// Medical specialty.
		$condition['relevantSpecialty'] = array(
			'@type' => 'MedicalSpecialty',
			'name'  => 'Plastic Surgery',
		);

		$schema_data[] = $condition;

		// Add VideoObject if featured video exists.
		$video = $this->get_featured_video( $condition_id );
		if ( null !== $video ) {
			$schema_data[] = $video;
		}

		return $schema_data;
	}

	/**
	 * Get condition description
	 *
	 * @param int $condition_id The condition post ID.
	 * @return string The condition description.
	 */
	private function get_description( $condition_id ) {
		// Try Yoast meta description first.
		$desc = get_post_meta( $condition_id, '_yoast_wpseo_metadesc', true );
		if ( is_string( $desc ) && '' !== $desc ) {
			return $desc;
		}

		// Fall back to post excerpt.
		$post = get_post( $condition_id );
		if ( null !== $post && '' !== $post->post_excerpt ) {
			return wp_strip_all_tags( $post->post_excerpt );
		}

		// Generate default description.
		return 'Learn about ' . get_the_title() . ' and treatment options available at Mia Aesthetics.';
	}

	/**
	 * Get condition image
	 *
	 * @param int $condition_id The condition post ID.
	 * @return string The condition image URL.
	 */
	private function get_image( $condition_id ) {
		if ( has_post_thumbnail( $condition_id ) ) {
			$featured_image = get_the_post_thumbnail_url( $condition_id, 'full' );
			if ( false !== $featured_image ) {
				return $featured_image;
			}
		}

		// Default logo as fallback.
		return get_template_directory_uri() . '/assets/images/mia-logo.png';
	}

	/**
	 * Get body location based on condition
	 *
	 * @param int $condition_id The condition post ID.
	 * @return string The body location.
	 */
	private function get_body_location( $condition_id ) {
		$post = get_post( $condition_id );
		if ( null === $post ) {
			return '';
		}

		$slug  = $post->post_name;
		$title = strtolower( $post->post_title );

		// Map common condition keywords to body locations.
		$mappings = array(
			'breast'     => 'Breast',
			'gynecomast' => 'Chest',
			'abdomen'    => 'Abdomen',
			'abdominal'  => 'Abdomen',
			'stomach'    => 'Abdomen',
			'skin'       => 'Skin',
			'face'       => 'Face',
			'facial'     => 'Face',
			'arm'        => 'Arms',
			'thigh'      => 'Thighs',
			'buttock'    => 'Buttocks',
			'nose'       => 'Nose',
			'eyelid'     => 'Eyelids',
			'neck'       => 'Neck',
			'chin'       => 'Chin',
		);

		foreach ( $mappings as $keyword => $location ) {
			if ( false !== stripos( $slug, $keyword ) || false !== stripos( $title, $keyword ) ) {
				return $location;
			}
		}

		return '';
	}

	/**
	 * Get signs and symptoms from overview details
	 *
	 * @param int $condition_id The condition post ID.
	 * @return array<int, string> List of signs/symptoms.
	 */
	private function get_signs_symptoms( $condition_id ) {
		$overview_items = get_field( 'overview_details', $condition_id );
		if ( ! is_array( $overview_items ) || 0 === count( $overview_items ) ) {
			return array();
		}

		$symptoms = array();
		foreach ( $overview_items as $item ) {
			if ( isset( $item['overview_item'] ) && '' !== trim( $item['overview_item'] ) ) {
				$symptoms[] = wp_strip_all_tags( $item['overview_item'] );
			}
		}

		// Limit to first 5 items for schema.
		return array_slice( $symptoms, 0, 5 );
	}
}
