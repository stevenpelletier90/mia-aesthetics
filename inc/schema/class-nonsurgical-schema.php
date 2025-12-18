<?php
/**
 * Non-Surgical Schema
 *
 * Generates NoninvasiveProcedure schema markup for non-surgical treatment pages
 *
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * NoninvasiveProcedure schema markup generator for non-surgical treatment pages
 */
class NonSurgical_Schema {

	/**
	 * Yoast SEO context object
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context
	 */
	private $context;

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
		return is_singular( 'non-surgical' );
	}

	/**
	 * Generate the non-surgical procedure schema
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

		// Use MedicalProcedure with procedureType for noninvasive procedures.
		$procedure = array(
			'@type'         => 'MedicalProcedure',
			'@id'           => get_permalink( $procedure_id ) . '#procedure',
			'name'          => get_the_title(),
			'url'           => get_permalink( $procedure_id ),
			'procedureType' => 'http://schema.org/NoninvasiveProcedure',
		);

		// Description from Yoast meta or excerpt.
		$procedure['description'] = $this->get_description( $procedure_id );

		// Image.
		$image_url = $this->get_image( $procedure_id );
		if ( '' !== $image_url ) {
			$procedure['image'] = $image_url;
		}

		// Body location based on treatment type.
		$body_location = $this->get_body_location( $procedure_id );
		if ( '' !== $body_location ) {
			$procedure['bodyLocation'] = $body_location;
		}

		// How the procedure is performed.
		$how_performed = $this->get_how_performed( $procedure_id );
		if ( '' !== $how_performed ) {
			$procedure['howPerformed'] = $how_performed;
		}

		// Non-surgical typically has minimal preparation.
		$procedure['preparation'] = 'Consultation required. Specific preparation instructions provided during your appointment.';

		// Follow-up care.
		$procedure['followup'] = 'Post-treatment care instructions provided by your specialist';

		// Status - procedure is available.
		$procedure['status'] = 'http://schema.org/EventScheduled';

		// Medical specialty.
		$procedure['relevantSpecialty'] = array(
			'@type' => 'MedicalSpecialty',
			'name'  => 'Dermatology',
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
		return 'Learn about ' . get_the_title() . ' non-surgical treatment at Mia Aesthetics. Achieve your aesthetic goals with minimal downtime.';
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
	 * Get body location based on treatment slug
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return string The body location.
	 */
	private function get_body_location( $procedure_id ) {
		$post = get_post( $procedure_id );
		if ( null === $post ) {
			return '';
		}

		$slug  = $post->post_name;
		$title = strtolower( $post->post_title );

		// Map common non-surgical treatment keywords to body locations.
		$mappings = array(
			'skin'   => 'Skin',
			'face'   => 'Face',
			'body'   => 'Body',
			'plasma' => 'Skin',
			'laser'  => 'Skin',
			'botox'  => 'Face',
			'filler' => 'Face',
			'lip'    => 'Lips',
		);

		foreach ( $mappings as $keyword => $location ) {
			if ( false !== stripos( $slug, $keyword ) || false !== stripos( $title, $keyword ) ) {
				return $location;
			}
		}

		return '';
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
	 * Get pricing offers for the treatment
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return array<string, mixed>|null Offer schema data or null.
	 */
	private function get_offers( $procedure_id ): ?array {
		$price = get_field( 'non_surgical_price', $procedure_id );
		if ( ! is_string( $price ) || '' === $price ) {
			return null;
		}

		// Extract numeric value from price string (e.g., "$500" -> "500").
		$numeric_price = preg_replace( '/[^0-9.]/', '', $price ) ?? '';
		if ( '' === $numeric_price ) {
			return null;
		}

		return array(
			'@type'           => 'Offer',
			'price'           => $numeric_price,
			'priceCurrency'   => 'USD',
			'priceValidUntil' => gmdate( 'Y-12-31' ),
			'availability'    => 'http://schema.org/InStock',
			'seller'          => array(
				'@type' => 'Organization',
				'name'  => 'Mia Aesthetics',
				'url'   => home_url(),
			),
		);
	}

	/**
	 * Get featured video from video_details group field
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return array<string, mixed>|null Featured video data or null.
	 */
	private function get_featured_video( $procedure_id ): ?array {
		$video_details = get_field( 'video_details', $procedure_id );

		if ( ! is_array( $video_details ) || ! isset( $video_details['video_id'] ) || '' === $video_details['video_id'] ) {
			return null;
		}

		$video_id    = $video_details['video_id'];
		$video_title = isset( $video_details['video_title'] ) && '' !== $video_details['video_title']
			? $video_details['video_title']
			: get_the_title() . ' - Treatment Overview';

		$video_description = isset( $video_details['video_description'] ) && '' !== $video_details['video_description']
			? $video_details['video_description']
			: 'Learn more about ' . get_the_title() . ' at Mia Aesthetics';

		$thumbnail_url = sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $video_id );
		if ( isset( $video_details['video_thumbnail'] ) && '' !== $video_details['video_thumbnail'] ) {
			$custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
			if ( false !== $custom_thumbnail ) {
				$thumbnail_url = $custom_thumbnail;
			}
		}

		return array(
			'@type'        => 'VideoObject',
			'@id'          => get_permalink( $procedure_id ) . '#video',
			'name'         => $video_title,
			'description'  => $video_description,
			'url'          => 'https://www.youtube.com/watch?v=' . $video_id,
			'embedUrl'     => 'https://www.youtube.com/embed/' . $video_id,
			'thumbnailUrl' => $thumbnail_url,
			'uploadDate'   => get_the_date( 'c', $procedure_id ),
			'publisher'    => array(
				'@type' => 'Organization',
				'name'  => 'Mia Aesthetics',
				'url'   => home_url(),
			),
		);
	}
}
