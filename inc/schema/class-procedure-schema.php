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

/**
 * MedicalProcedure schema markup generator for individual procedure pages
 */
class Procedure_Schema {

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
			'@type'      => 'SurgicalProcedure',
			'@id'        => get_permalink( $procedure_id ) . '#procedure',
			'name'       => get_the_title(),
			'url'        => get_permalink( $procedure_id ),
			'providedBy' => array( '@id' => $org_id ),
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

		// Preparation instructions.
		$procedure['preparation'] = array(
			'@type' => 'MedicalWebPage',
			'url'   => home_url( '/surgery-preparation/' ),
			'name'  => 'Surgery Preparation Guidelines',
		);

		// Follow-up care.
		$procedure['followup'] = 'Post-operative care instructions provided by your surgeon';

		// Status - procedure is available.
		$procedure['status'] = 'http://schema.org/EventScheduled';

		// Medical specialty.
		$procedure['relevantSpecialty'] = array(
			'@type' => 'MedicalSpecialty',
			'name'  => 'Plastic Surgery',
		);

		// Add potential risks (generic for cosmetic surgery).
		$procedure['possibleComplication'] = $this->get_possible_complications();

		// Related procedures.
		$related = $this->get_related_procedures( $procedure_id );
		if ( count( $related ) > 0 ) {
			$procedure['relatedProcedure'] = $related;
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
	 * Get possible complications for cosmetic surgery
	 *
	 * @return array<int, string> List of possible complications.
	 */
	private function get_possible_complications() {
		return array(
			'Infection',
			'Bleeding',
			'Scarring',
			'Anesthesia risks',
			'Asymmetry',
		);
	}

	/**
	 * Get related procedures
	 *
	 * @param int $procedure_id The procedure post ID.
	 * @return array<int, array<string, string>> Related procedure references.
	 */
	private function get_related_procedures( $procedure_id ) {
		$related = get_field( 'related_procedures', $procedure_id );
		if ( ! is_array( $related ) || 0 === count( $related ) ) {
			return array();
		}

		$related_schema = array();
		foreach ( $related as $related_post ) {
			$related_id = is_object( $related_post ) && property_exists( $related_post, 'ID' ) ? $related_post->ID : (int) $related_post;
			if ( 0 === $related_id ) {
				continue;
			}

			$permalink = get_permalink( $related_id );
			if ( false === $permalink ) {
				continue;
			}

			$related_schema[] = array(
				'@type' => 'SurgicalProcedure',
				'@id'   => $permalink . '#procedure',
				'name'  => get_the_title( $related_id ),
				'url'   => $permalink,
			);
		}

		return $related_schema;
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

		$video_id          = $video_details['video_id'];
		$video_title       = $this->get_video_title( $video_details );
		$video_description = $this->get_video_description( $video_details );
		$thumbnail_url     = $this->get_video_thumbnail_url( $video_details, $video_id );

		// Generate YouTube URLs from video ID.
		$watch_url = 'https://www.youtube.com/watch?v=' . $video_id;
		$embed_url = 'https://www.youtube.com/embed/' . $video_id;

		return array(
			'@type'        => 'VideoObject',
			'@id'          => get_permalink( $procedure_id ) . '#video',
			'name'         => $video_title,
			'description'  => $video_description,
			'url'          => $watch_url,
			'embedUrl'     => $embed_url,
			'thumbnailUrl' => $thumbnail_url,
			'uploadDate'   => get_the_date( 'c', $procedure_id ),
			'publisher'    => array(
				'@type' => 'Organization',
				'name'  => 'Mia Aesthetics',
				'url'   => home_url(),
			),
		);
	}

	/**
	 * Get video title with fallback
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string
	 */
	private function get_video_title( $video_details ) {
		if ( isset( $video_details['video_title'] ) && '' !== $video_details['video_title'] ) {
			return $video_details['video_title'];
		}

		return get_the_title() . ' - Procedure Overview';
	}

	/**
	 * Get video description with fallback
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string
	 */
	private function get_video_description( $video_details ) {
		if ( isset( $video_details['video_description'] ) && '' !== $video_details['video_description'] ) {
			return $video_details['video_description'];
		}

		return 'Learn more about ' . get_the_title() . ' at Mia Aesthetics';
	}

	/**
	 * Get video thumbnail URL
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @param string               $video_id Video ID.
	 * @return string
	 */
	private function get_video_thumbnail_url( $video_details, $video_id ) {
		// Use custom thumbnail if available.
		if ( isset( $video_details['video_thumbnail'] ) && '' !== $video_details['video_thumbnail'] ) {
			$custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
			if ( false !== $custom_thumbnail ) {
				return $custom_thumbnail;
			}
		}

		// Fall back to YouTube thumbnail.
		return sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $video_id );
	}
}
