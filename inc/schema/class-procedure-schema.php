<?php
/**
 * Procedure Schema
 *
 * Generates MedicalProcedure schema markup for procedure pages
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
 * MedicalProcedure schema markup generator for individual procedure pages
 */
class Procedure_Schema {

	use Video_Schema_Trait;

	/**
	 * Yoast SEO context object
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context
	 */
	private $context;

	/**
	 * Get video title fallback for procedures
	 *
	 * @return string The fallback video title.
	 */
	protected function get_video_title_fallback(): string {
		return get_the_title() . ' - Procedure Overview';
	}

	/**
	 * Get video description fallback for procedures
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
		return is_singular( 'procedure' );
	}

	/**
	 * Generate the procedure schema
	 *
	 * @return array<int, array<string, mixed>> Schema.org compliant MedicalProcedure data
	 */
	public function generate(): array {
		$procedure_id = get_the_ID();
		if ( false === $procedure_id || 0 === $procedure_id ) {
			return array();
		}

		$org_id = $this->context->site_url . '#organization';

		$schema_data = array();

		$procedure = array(
			'@type' => 'SurgicalProcedure',
			'@id'   => get_permalink( $procedure_id ) . '#procedure',
			'name'  => get_the_title(),
			'url'   => get_permalink( $procedure_id ),
		);

		// Description from Yoast meta or excerpt.
		$procedure['description'] = $this->get_description( $procedure_id );

		// Image.
		$image_url = $this->get_image( $procedure_id );
		if ( '' !== $image_url ) {
			$procedure['image'] = $image_url;
		}

		// Body location based on procedure category.
		$body_location = $this->get_body_location( $procedure_id );
		if ( '' !== $body_location ) {
			$procedure['bodyLocation'] = $body_location;
		}

		// How the procedure is performed (from content excerpt).
		$how_performed = $this->get_how_performed( $procedure_id );
		if ( '' !== $how_performed ) {
			$procedure['howPerformed'] = $how_performed;
		}

		// Preparation instructions (text description).
		$procedure['preparation'] = 'Consultation required. See surgery preparation guidelines at ' . home_url( '/surgery-preparation/' );

		// Follow-up care.
		$procedure['followup'] = 'Post-operative care instructions provided by your surgeon';

		// Status - procedure is available.
		$procedure['status'] = 'http://schema.org/EventScheduled';

		// Medical specialty.
		$procedure['relevantSpecialty'] = array(
			'@type' => 'MedicalSpecialty',
			'name'  => 'Plastic Surgery',
		);

		// Pricing information.
		$offers = $this->get_offers( $procedure_id );
		if ( null !== $offers ) {
			$procedure['offers'] = $offers;
		}

		$schema_data[] = $procedure;

		// Add VideoObject if featured video exists.
		$video = $this->get_featured_video( $procedure_id );
		if ( null !== $video ) {
			$schema_data[] = $video;
		}

		return $schema_data;
	}

	/**
	 * Get procedure description
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return string The procedure description.
	 */
	private function get_description( $procedure_id ) {
		// Try Yoast meta description first.
		$desc = get_post_meta( $procedure_id, '_yoast_wpseo_metadesc', true );
		if ( is_string( $desc ) && '' !== $desc ) {
			return $desc;
		}

		// Fall back to post excerpt.
		$post = get_post( $procedure_id );
		if ( null !== $post && '' !== $post->post_excerpt ) {
			return wp_strip_all_tags( $post->post_excerpt );
		}

		// Generate default description.
		return 'Learn about ' . get_the_title() . ' at Mia Aesthetics. Our board-certified plastic surgeons provide expert care with affordable pricing and flexible financing options.';
	}

	/**
	 * Get procedure image
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return string The procedure image URL.
	 */
	private function get_image( $procedure_id ) {
		if ( has_post_thumbnail( $procedure_id ) ) {
			$featured_image = get_the_post_thumbnail_url( $procedure_id, 'full' );
			if ( false !== $featured_image ) {
				return $featured_image;
			}
		}

		// Default logo as fallback.
		return get_template_directory_uri() . '/assets/images/mia-logo.png';
	}

	/**
	 * Get body location based on procedure category or slug
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return string The body location.
	 */
	private function get_body_location( $procedure_id ) {
		// Check ACF procedure category field.
		$category = get_field( 'procedure_category', $procedure_id );
		if ( is_string( $category ) && '' !== $category ) {
			return $this->map_category_to_body_location( $category );
		}

		// Fall back to slug-based detection.
		$post = get_post( $procedure_id );
		if ( null === $post ) {
			return '';
		}

		$slug = $post->post_name;

		// Map common procedure slugs to body locations.
		$slug_mappings = array(
			'breast'         => 'Breast',
			'augment'        => 'Breast',
			'tummy'          => 'Abdomen',
			'abdomen'        => 'Abdomen',
			'lipo'           => 'Body',
			'bbl'            => 'Buttocks',
			'butt'           => 'Buttocks',
			'face'           => 'Face',
			'rhinoplasty'    => 'Nose',
			'nose'           => 'Nose',
			'eyelid'         => 'Eyelid',
			'blepharoplasty' => 'Eyelid',
			'neck'           => 'Neck',
			'chin'           => 'Chin',
			'arm'            => 'Arm',
			'thigh'          => 'Thigh',
			'brow'           => 'Forehead',
		);

		foreach ( $slug_mappings as $keyword => $location ) {
			if ( false !== stripos( $slug, $keyword ) ) {
				return $location;
			}
		}

		return '';
	}

	/**
	 * Map procedure category to schema body location
	 *
	 * @param string $category The procedure category.
	 * @return string The body location.
	 */
	private function map_category_to_body_location( $category ) {
		$category_lower = strtolower( $category );

		$mappings = array(
			'body'   => 'Body',
			'breast' => 'Breast',
			'face'   => 'Face',
			'men'    => 'Body',
		);

		return $mappings[ $category_lower ] ?? '';
	}

	/**
	 * Get how the procedure is performed from content
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return string Brief description of how procedure is performed.
	 */
	private function get_how_performed( $procedure_id ) {
		$post = get_post( $procedure_id );
		if ( null === $post ) {
			return '';
		}

		// Get clean content excerpt.
		$content = wp_strip_all_tags( $post->post_content );
		if ( '' === $content ) {
			return '';
		}

		// Limit to first 200 characters for schema.
		$excerpt    = substr( $content, 0, 200 );
		$last_space = strrpos( $excerpt, ' ' );
		if ( false !== $last_space && $last_space > 150 ) {
			$excerpt = substr( $excerpt, 0, $last_space );
		}

		return $excerpt . '...';
	}

	/**
	 * Get pricing offers for the procedure
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return array<string, mixed>|null Offer schema data or null.
	 */
	private function get_offers( $procedure_id ): ?array {
		$price = get_field( 'procedure_price', $procedure_id );
		if ( ! is_string( $price ) || '' === $price ) {
			return null;
		}

		// Extract numeric value from price string (e.g., "$4,500" -> "4500").
		$numeric_price = preg_replace( '/[^0-9.]/', '', $price ) ?? '';
		if ( '' === $numeric_price ) {
			return null;
		}

		return array(
			'@type'           => 'Offer',
			'price'           => $numeric_price,
			'priceCurrency'   => 'USD',
			'priceValidUntil' => gmdate( 'Y-12-31' ), // Valid through end of current year.
			'availability'    => 'http://schema.org/InStock',
			'seller'          => array(
				'@type' => 'Organization',
				'name'  => 'Mia Aesthetics',
				'url'   => home_url(),
			),
		);
	}
}
