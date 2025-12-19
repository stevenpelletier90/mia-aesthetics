<?php
/**
 * Fat Transfer Schema
 *
 * Generates SurgicalProcedure schema markup for fat transfer procedure pages
 *
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SurgicalProcedure schema markup generator for fat transfer procedure pages
 */
class FatTransfer_Schema {

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
		return is_singular( 'fat-transfer' );
	}

	/**
	 * Generate the fat transfer procedure schema
	 *
	 * @return array<int, array<string, mixed>> Schema.org compliant SurgicalProcedure data
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

		// Body location based on fat transfer type.
		$body_location = $this->get_body_location( $procedure_id );
		if ( '' !== $body_location ) {
			$procedure['bodyLocation'] = $body_location;
		}

		// How the procedure is performed.
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
		return 'Learn about ' . get_the_title() . ' fat transfer procedure at Mia Aesthetics. Our board-certified plastic surgeons provide expert care with natural-looking results.';
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
	 * Get body location based on fat transfer type
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

		// Map common fat transfer keywords to body locations.
		$mappings = array(
			'breast'  => 'Breast',
			'buttock' => 'Buttocks',
			'butt'    => 'Buttocks',
			'bbl'     => 'Buttocks',
			'face'    => 'Face',
			'facial'  => 'Face',
			'hand'    => 'Hands',
			'hip'     => 'Hips',
		);

		foreach ( $mappings as $keyword => $location ) {
			if ( false !== stripos( $slug, $keyword ) || false !== stripos( $title, $keyword ) ) {
				return $location;
			}
		}

		// Default for general fat transfer.
		return 'Body';
	}

	/**
	 * Get how the procedure is performed from content or overview
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return string Brief description of how procedure is performed.
	 */
	private function get_how_performed( $procedure_id ) {
		// Try overview details first.
		$overview_items = get_field( 'overview_details', $procedure_id );
		if ( is_array( $overview_items ) && count( $overview_items ) > 0 ) {
			$overview_text = '';
			foreach ( $overview_items as $item ) {
				if ( isset( $item['overview_item'] ) && '' !== trim( $item['overview_item'] ) ) {
					$overview_text .= wp_strip_all_tags( $item['overview_item'] ) . ' ';
				}
			}
			if ( '' !== $overview_text ) {
				$excerpt    = substr( trim( $overview_text ), 0, 200 );
				$last_space = strrpos( $excerpt, ' ' );
				if ( false !== $last_space && $last_space > 150 ) {
					$excerpt = substr( $excerpt, 0, $last_space );
				}
				return $excerpt . '...';
			}
		}

		// Fall back to content.
		$post = get_post( $procedure_id );
		if ( null === $post ) {
			return '';
		}

		$content = wp_strip_all_tags( $post->post_content );
		if ( '' === $content ) {
			return '';
		}

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
			: get_the_title() . ' - Procedure Overview';

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
