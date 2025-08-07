<?php
/**
 * Clinic Schema
 *
 * Generates schema markup for clinic/location pages
 *
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Medical clinic schema markup generator
 */
class Clinic_Schema {

	/**
	 * Yoast SEO context object
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context
	 */
	private $context;

	/**
	 * Constructor
	 *
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context The Yoast SEO context object.
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
		return is_singular( 'location' );
	}

	/**
	 * Generate the clinic schema
	 *
	 * @return array Schema.org compliant MedicalBusiness/MedicalClinic data
	 */
	public function generate() {
		$loc_id = get_the_ID();
		$org_id = $this->context->site_url . '#organization';

		$schema_data = array();

		// Main clinic schema.
		$clinic = array(
			'@type'            => array( 'MedicalBusiness', 'MedicalClinic' ),
			'@id'              => get_permalink( $loc_id ) . '#clinic',
			'name'             => get_the_title(),
			'url'              => get_permalink( $loc_id ),
			'branchOf'         => array( '@id' => $org_id ),
			'medicalSpecialty' => 'PlasticSurgery',
			'priceRange'       => '$1,000-$20,000',
			'paymentAccepted'  => array( 'Cash', 'Credit Card', 'Affirm', 'United Medical Credit', 'Alphaeon Credit' ),
		);

		// Description.
		$clinic['description'] = $this->get_description( $loc_id );

		// Image.
		$clinic['image'] = $this->get_image( $loc_id );

		// Contact information.
		$tel = get_field( 'phone_number', $loc_id );
		if ( is_string( $tel ) && '' !== $tel ) {
			$clinic['telephone'] = $tel;
		}

		// Address.
		$address = $this->get_address( $loc_id );
		if ( null !== $address ) {
			$clinic['address'] = $address;
		}

		// Geo coordinates.
		$geo = $this->get_geo_coordinates( $loc_id );
		if ( null !== $geo ) {
			$clinic['geo'] = $geo;
		}

		// Google Maps link.
		$maps_url = get_field( 'location_maps_link', $loc_id );
		if ( is_string( $maps_url ) && '' !== $maps_url ) {
			$clinic['hasMap'] = $maps_url;
		}

		// Opening hours.
		$clinic['openingHoursSpecification'] = $this->get_opening_hours( $loc_id );

		// Available services.
		$clinic['availableService'] = $this->get_available_services();

		// Aggregate rating.
		$rating = $this->get_rating( $loc_id );
		if ( null !== $rating ) {
			$clinic['aggregateRating'] = $rating;
		}

		// Employees (surgeons).
		$employees = $this->get_employees( $loc_id );
		if ( count( $employees ) > 0 ) {
			$clinic['employee'] = $employees;
		}

		$schema_data[] = $clinic;

		// Add separate VideoObject schema if video exists.
		$video = $this->get_featured_video( $loc_id );
		if ( null !== $video ) {
			$schema_data[] = $video;
		}

		return $schema_data;
	}

	/**
	 * Get clinic description
	 *
	 * @param int $loc_id The location post ID.
	 * @return string
	 */
	private function get_description( $loc_id ) {
		$desc = get_post_meta( $loc_id, '_yoast_wpseo_metadesc', true );
		if ( is_string( $desc ) && '' !== $desc ) {
			return $desc;
		}

		return 'Mia Aesthetics ' . get_the_title() . ' specializes in affordable cosmetic surgery procedures. Flexible financing options available.';
	}

	/**
	 * Get clinic image
	 *
	 * @param int $loc_id The location post ID.
	 * @return string
	 */
	private function get_image( $loc_id ) {
		// Prioritize featured image first for business listings.
		if ( has_post_thumbnail( $loc_id ) ) {
			$featured_image = get_the_post_thumbnail_url( $loc_id, 'full' );
			if ( $featured_image ) {
				return $featured_image;
			}
		}

		// Fall back to video thumbnail from video_details group.
		$video_details = get_field( 'video_details', $loc_id );
		if ( is_array( $video_details ) || is_object( $video_details ) ) {
			// Use custom thumbnail if available.
			if ( isset( $video_details['video_thumbnail'] ) && '' !== $video_details['video_thumbnail'] ) {
				$custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
				if ( $custom_thumbnail ) {
					return $custom_thumbnail;
				}
			}

			// Fall back to YouTube thumbnail if video_id exists.
			if ( isset( $video_details['video_id'] ) && '' !== $video_details['video_id'] ) {
				return sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $video_details['video_id'] );
			}
		}

		// Default logo as last resort.
		return get_template_directory_uri() . '/assets/images/mia-logo.png';
	}

	/**
	 * Get clinic address from Google Maps field
	 *
	 * @param int $loc_id The location post ID.
	 * @return array|null
	 */
	private function get_address( $loc_id ) {
		$location_map = get_field( 'location_map', $loc_id );

		if ( ! is_array( $location_map ) || count( $location_map ) === 0 ) {
			return null;
		}

		// Build street address from components.
		$street_number = $location_map['street_number'] ?? '';
		$street_name   = $location_map['street_name'] ?? '';
		$street        = trim( $street_number . ' ' . $street_name );

		$city  = $location_map['city'] ?? '';
		$state = $location_map['state'] ?? $location_map['state_short'] ?? ''; // Try full state name first, then abbreviation.
		$zip   = $location_map['post_code'] ?? '';

		// Special handling for locations where Google Maps doesn't populate city correctly.
		// For Brooklyn/NYC addresses, Google sometimes doesn't populate city.
		if ( '' === $city && '' !== $state && ( 'NY' === $state || 'New York' === $state ) && false !== stripos( $street, 'atlantic' ) ) {
			$city = 'Brooklyn';
		}

		// Only create address if we have the minimum required fields.
		if ( '' !== $street && '' !== $city && '' !== $state ) {
			return array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $street,
				'addressLocality' => $city,
				'addressRegion'   => $state,
				'postalCode'      => '' !== $zip ? $zip : '', // Include zip if available.
				'addressCountry'  => 'US',
			);
		}

		return null;
	}

	/**
	 * Get geo coordinates from Google Maps field
	 *
	 * @param int $loc_id The location post ID.
	 * @return array|null
	 */
	private function get_geo_coordinates( $loc_id ) {
		$location_map = get_field( 'location_map', $loc_id );

		if ( ! is_array( $location_map ) || ! isset( $location_map['lat'] ) || ! isset( $location_map['lng'] ) || '' === $location_map['lat'] || '' === $location_map['lng'] ) {
			return null;
		}

		return array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (string) $location_map['lat'],
			'longitude' => (string) $location_map['lng'],
		);
	}

	/**
	 * Get opening hours from ACF business_hours repeater field
	 *
	 * @param int $loc_id The location post ID.
	 * @return array
	 */
	private function get_opening_hours( $loc_id ) {
		$business_hours = get_field( 'business_hours', $loc_id );

		if ( ! is_array( $business_hours ) || count( $business_hours ) === 0 ) {
			// Fallback to default hours.
			return array(
				array(
					'@type'     => 'OpeningHoursSpecification',
					'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
					'opens'     => '09:00',
					'closes'    => '18:00',
				),
			);
		}

		$opening_hours = array();

		foreach ( $business_hours as $hours_row ) {
			$day   = $hours_row['day'] ?? '';
			$hours = $hours_row['hours'] ?? '';

			if ( '' === $day || '' === $hours ) {
				continue;
			}

			// Parse hours - handle various formats like "9:00 AM - 5:00 PM" or "09:00-17:00".
			$parsed_times = $this->parse_hours_string( $hours );

			if ( null !== $parsed_times ) {
				$opening_hours[] = array(
					'@type'     => 'OpeningHoursSpecification',
					'dayOfWeek' => ucfirst( strtolower( $day ) ), // Ensure proper capitalization.
					'opens'     => (string) $parsed_times['opens'],
					'closes'    => (string) $parsed_times['closes'],
				);
			}
		}

		return array() === $opening_hours ? $this->get_default_hours() : $opening_hours;
	}

	/**
	 * Parse hours string into opens/closes times
	 *
	 * @param string $hours_string The hours string to parse.
	 * @return array|null
	 */
	private function parse_hours_string( $hours_string ) {
		// Handle "Closed" case.
		if ( stripos( $hours_string, 'closed' ) !== false ) {
			return null;
		}

		// Common patterns: "9:00 AM - 5:00 PM", "09:00-17:00", "9AM-5PM", etc.
		$patterns = array(
			'/(\d{1,2}):(\d{2})\s*(AM|PM)?\s*[-–—]\s*(\d{1,2}):(\d{2})\s*(AM|PM)?/i',
			'/(\d{1,2}):(\d{2})\s*[-–—]\s*(\d{1,2}):(\d{2})/i',
			'/(\d{1,2})\s*(AM|PM)\s*[-–—]\s*(\d{1,2})\s*(AM|PM)/i',
			'/(\d{1,2})\s*[-–—]\s*(\d{1,2})/i',
		);

		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $hours_string, $matches ) ) {
				return $this->convert_to_24_hour( $matches );
			}
		}

		return null;
	}

	/**
	 * Convert parsed time matches to 24-hour format
	 *
	 * @param array $matches The regex matches from time parsing.
	 * @return array
	 */
	private function convert_to_24_hour( $matches ) {
		$opens  = '';
		$closes = '';

		// Handle different match patterns.
		if ( count( $matches ) >= 7 && isset( $matches[2] ) && isset( $matches[5] ) ) {
			// Pattern: "9:00 AM - 5:00 PM" (has minutes).
			$open_hour   = intval( $matches[1] );
			$open_min    = $matches[2];
			$open_period = strtoupper( $matches[3] ?? '' );

			$close_hour   = intval( $matches[4] );
			$close_min    = $matches[5];
			$close_period = strtoupper( $matches[6] ?? '' );

			// Convert to 24-hour.
			if ( 'PM' === $open_period && 12 !== $open_hour ) {
				$open_hour += 12;
			}

			if ( 'AM' === $open_period && 12 === $open_hour ) {
				$open_hour = 0;
			}

			if ( 'PM' === $close_period && 12 !== $close_hour ) {
				$close_hour += 12;
			}

			if ( 'AM' === $close_period && 12 === $close_hour ) {
				$close_hour = 0;
			}

			$opens  = sprintf( '%02d:%s', $open_hour, $open_min );
			$closes = sprintf( '%02d:%s', $close_hour, $close_min );

		} elseif ( count( $matches ) >= 5 && isset( $matches[2] ) && isset( $matches[4] ) && is_numeric( $matches[2] ) ) {
			// Pattern: "09:00-17:00" (24-hour format with minutes).
			$opens  = sprintf( '%02d:%s', intval( $matches[1] ), $matches[2] );
			$closes = sprintf( '%02d:%s', intval( $matches[3] ), $matches[4] );

		} elseif ( count( $matches ) >= 5 && isset( $matches[2] ) && isset( $matches[4] ) ) {
			// Pattern: "9AM-5PM" (hour only with AM/PM).
			$open_hour    = intval( $matches[1] );
			$open_period  = strtoupper( $matches[2] );
			$close_hour   = intval( $matches[3] );
			$close_period = strtoupper( $matches[4] );

			// Convert to 24-hour.
			if ( 'PM' === $open_period && 12 !== $open_hour ) {
				$open_hour += 12;
			}

			if ( 'AM' === $open_period && 12 === $open_hour ) {
				$open_hour = 0;
			}

			if ( 'PM' === $close_period && 12 !== $close_hour ) {
				$close_hour += 12;
			}

			if ( 'AM' === $close_period && 12 === $close_hour ) {
				$close_hour = 0;
			}

			$opens  = sprintf( '%02d:00', $open_hour );
			$closes = sprintf( '%02d:00', $close_hour );

		} elseif ( count( $matches ) >= 3 ) {
			// Pattern: "9-17" (24-hour format, hour only).
			$opens  = sprintf( '%02d:00', intval( $matches[1] ) );
			$closes = sprintf( '%02d:00', intval( $matches[2] ) );
		}

		return array(
			'opens'  => $opens,
			'closes' => $closes,
		);
	}

	/**
	 * Get default opening hours
	 *
	 * @return array
	 */
	private function get_default_hours() {
		return array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => '09:00',
				'closes'    => '18:00',
			),
		);
	}

	/**
	 * Get available services
	 *
	 * @return array
	 */
	private function get_available_services() {
		$services = array(
			'Breast Augmentation',
			'Brazilian Butt Lift',
			'Liposuction',
			'Tummy Tuck',
			'Mommy Makeover',
			'Rhinoplasty',
			'Face Lift',
		);

		return array_map(
			function ( $service ) {
				return array(
					'@type'         => 'MedicalProcedure',
					'name'          => $service,
					'procedureType' => 'Surgical',
				);
			},
			$services
		);
	}

	/**
	 * Get aggregate rating
	 *
	 * @param int $loc_id The location post ID.
	 * @return array|null
	 */
	private function get_rating( $loc_id ) {
		$rating = get_field( 'average_rating', $loc_id );
		if ( ( is_string( $rating ) && '' !== $rating ) || is_numeric( $rating ) ) {
			return array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $rating,
				'bestRating'  => '5',
				'reviewCount' => get_field( 'review_count', $loc_id ) ?? 0,
			);
		}

		return null;
	}

	/**
	 * Get employees (surgeons at this location)
	 *
	 * @param int $loc_id The location post ID.
	 * @return array
	 */
	private function get_employees( $loc_id ) {
		$surgeons = get_posts(
			array(
				'post_type'      => 'surgeon',
				'posts_per_page' => -1,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Necessary for surgeon-location relationship, result cached in schema
				'meta_query'     => array(
					array(
						'key'     => 'surgeon_location',
						'value'   => (string) $loc_id,
						'compare' => '=',
					),
				),
			)
		);

		if ( count( $surgeons ) === 0 ) {
			return array();
		}

		return array_map(
			function ( $surgeon ) {
				return array( '@id' => get_permalink( $surgeon->ID ) . '#physician' );
			},
			$surgeons
		);
	}

	/**
	 * Get featured video from video_details group field
	 *
	 * @param int $loc_id The location post ID.
	 * @return array|null
	 */
	private function get_featured_video( $loc_id ) {
		$video_details = get_field( 'video_details', $loc_id );

		if ( ! is_array( $video_details ) || ! isset( $video_details['video_id'] ) || '' === $video_details['video_id'] ) {
			return null;
		}

		$video_id          = $video_details['video_id'];
		$video_title       = ( ! isset( $video_details['video_title'] ) || '' === $video_details['video_title'] ) ? get_the_title() . ' - Featured Video' : $video_details['video_title'];
		$video_description = ( ! isset( $video_details['video_description'] ) || '' === $video_details['video_description'] ) ? 'Learn more about Mia Aesthetics ' . get_the_title() . ' location' : $video_details['video_description'];

		// Generate YouTube URLs from video ID.
		$watch_url = 'https://www.youtube.com/watch?v=' . $video_id;
		$embed_url = 'https://www.youtube.com/embed/' . $video_id;

		// Use custom thumbnail if available, otherwise use YouTube thumbnail.
		$thumbnail_url = sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $video_id );
		if ( isset( $video_details['video_thumbnail'] ) && '' !== $video_details['video_thumbnail'] ) {
			$custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
			if ( $custom_thumbnail ) {
				$thumbnail_url = $custom_thumbnail;
			}
		}

		return array(
			'@type'        => 'VideoObject',
			'@id'          => get_permalink( $loc_id ) . '#video',
			'name'         => $video_title,
			'description'  => $video_description,
			'url'          => $watch_url,
			'embedUrl'     => $embed_url,
			'thumbnailUrl' => $thumbnail_url,
			'uploadDate'   => get_the_date( 'c', $loc_id ), // Use location post date as fallback.
			'publisher'    => array(
				'@type' => 'Organization',
				'name'  => 'Mia Aesthetics',
				'url'   => home_url(),
			),
		);
	}
}
