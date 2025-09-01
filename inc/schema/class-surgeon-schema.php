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

/**
 * Surgeon Schema markup generator for individual surgeon pages
 */
class Surgeon_Schema {

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
	 * Get video-based image (custom thumbnail or YouTube thumbnail)
	 *
	 * @param int $surgeon_id The surgeon post ID.
	 * @return string|null
	 */
	private function get_video_image( $surgeon_id ) {
		$video_details = get_field( 'video_details', $surgeon_id );
		if ( null === $video_details ) {
			return null;
		}

		$custom_thumbnail = $this->get_video_custom_thumbnail( $video_details );
		if ( null !== $custom_thumbnail ) {
			return $custom_thumbnail;
		}

		return $this->get_youtube_thumbnail( $video_details );
	}

	/**
	 * Get custom video thumbnail
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string|null
	 */
	private function get_video_custom_thumbnail( $video_details ) {
		if ( ! isset( $video_details['video_thumbnail'] ) || '' === $video_details['video_thumbnail'] ) {
			return null;
		}

		$custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
		return false !== $custom_thumbnail ? $custom_thumbnail : null;
	}

	/**
	 * Get YouTube thumbnail
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string|null
	 */
	private function get_youtube_thumbnail( $video_details ) {
		if ( ! isset( $video_details['video_id'] ) || '' === $video_details['video_id'] ) {
			return null;
		}

		return sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $video_details['video_id'] );
	}

	/**
	 * Get featured video from video_details group field
	 *
	 * @param int $surgeon_id The surgeon post ID.
	 * @return array<string, mixed>|null Featured video data or null.
	 */
	private function get_featured_video( $surgeon_id ): ?array {
		$video_details = get_field( 'video_details', $surgeon_id );

		if ( null === $video_details || ! isset( $video_details['video_id'] ) || '' === $video_details['video_id'] ) {
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
			'@id'          => get_permalink( $surgeon_id ) . '#video',
			'name'         => $video_title,
			'description'  => $video_description,
			'url'          => $watch_url,
			'embedUrl'     => $embed_url,
			'thumbnailUrl' => $thumbnail_url,
			'uploadDate'   => get_the_date( 'c', $surgeon_id ), // Use surgeon post date as fallback.
			'publisher'    => array(
				'@type' => 'Organization',
				'name'  => 'Mia Aesthetics',
				'url'   => home_url(),
			),
		);
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
	 * Get video title with fallback
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string
	 */
	private function get_video_title( $video_details ) {
		if ( isset( $video_details['video_title'] ) && '' !== $video_details['video_title'] ) {
			return $video_details['video_title'];
		}

		return 'Dr. ' . get_the_title() . ' - Featured Video';
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

		return 'Learn more about Dr. ' . get_the_title() . ' at Mia Aesthetics';
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
