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

class Clinic_Schema {
    
    /**
     * @var \Yoast\WP\SEO\Context\Meta_Tags_Context
     */
    private $context;
    
    /**
     * Constructor
     * 
     * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context
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
        
        $schema_data = [];
        
        // Main clinic schema
        $clinic = [
            '@type'            => [ 'MedicalBusiness', 'MedicalClinic' ],
            '@id'              => get_permalink( $loc_id ) . '#clinic',
            'name'             => get_the_title(),
            'url'              => get_permalink( $loc_id ),
            'branchOf'         => [ '@id' => $org_id ],
            'medicalSpecialty' => 'PlasticSurgery',
            'priceRange'       => '$1,000-$20,000',
            'paymentAccepted'  => ['Cash', 'Credit Card', 'Affirm', 'United Medical Credit', 'Alphaeon Credit'],
        ];
        
        // Description
        $clinic['description'] = $this->get_description( $loc_id );
        
        // Image
        $image_url = $this->get_image( $loc_id );
        if ( $image_url ) {
            $clinic['image'] = $image_url;
        }
        
        // Contact information
        if ( $tel = get_field( 'phone_number', $loc_id ) ) {
            $clinic['telephone'] = $tel;
        }
        
        // Address
        $address = $this->get_address( $loc_id );
        if ( $address ) {
            $clinic['address'] = $address;
        }
        
        // Geo coordinates
        $geo = $this->get_geo_coordinates( $loc_id );
        if ( $geo ) {
            $clinic['geo'] = $geo;
        }
        
        // Google Maps link
        if ( $maps_url = get_field( 'location_maps_link', $loc_id ) ) {
            $clinic['hasMap'] = $maps_url;
        }
        
        // Opening hours
        $clinic['openingHoursSpecification'] = $this->get_opening_hours( $loc_id );
        
        // Available services
        $clinic['availableService'] = $this->get_available_services();
        
        // Aggregate rating
        $rating = $this->get_rating( $loc_id );
        if ( $rating ) {
            $clinic['aggregateRating'] = $rating;
        }
        
        // Employees (surgeons)
        $employees = $this->get_employees( $loc_id );
        if ( ! empty( $employees ) ) {
            $clinic['employee'] = $employees;
        }
        
        $schema_data[] = $clinic;
        
        // Add separate VideoObject schema if video exists
        $video = $this->get_featured_video( $loc_id );
        if ( $video ) {
            $schema_data[] = $video;
        }
        
        
        return $schema_data;
    }
    
    /**
     * Get clinic description
     * 
     * @param int $loc_id
     * @return string
     */
    private function get_description( $loc_id ) {
        if ( $desc = get_post_meta( $loc_id, '_yoast_wpseo_metadesc', true ) ) {
            return $desc;
        }
        
        return 'Mia Aesthetics ' . get_the_title() . ' specializes in affordable cosmetic surgery procedures. Flexible financing options available.';
    }
    
    /**
     * Get clinic image
     * 
     * @param int $loc_id
     * @return string|null
     */
    private function get_image( $loc_id ) {
        // Prioritize featured image first for business listings
        if ( has_post_thumbnail( $loc_id ) ) {
            $featured_image = get_the_post_thumbnail_url( $loc_id, 'full' );
            if ( $featured_image ) {
                return $featured_image;
            }
        }
        
        // Fall back to video thumbnail from video_details group
        $video_details = get_field( 'video_details', $loc_id );
        if ( $video_details ) {
            // Use custom thumbnail if available
            if ( !empty( $video_details['video_thumbnail'] ) ) {
                $custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
                if ( $custom_thumbnail ) {
                    return $custom_thumbnail;
                }
            }
            // Fall back to YouTube thumbnail if video_id exists
            if ( !empty( $video_details['video_id'] ) ) {
                return "https://img.youtube.com/vi/{$video_details['video_id']}/maxresdefault.jpg";
            }
        }
        
        // Default logo as last resort
        return get_template_directory_uri() . '/assets/images/mia-logo.png';
    }
    
    /**
     * Get clinic address from Google Maps field
     * 
     * @param int $loc_id
     * @return array|null
     */
    private function get_address( $loc_id ) {
        $location_map = get_field( 'location_map', $loc_id );
        
        if ( ! $location_map ) {
            return null;
        }
        
        // Build street address from components
        $street_number = $location_map['street_number'] ?? '';
        $street_name = $location_map['street_name'] ?? '';
        $street = trim( $street_number . ' ' . $street_name );
        
        $city = $location_map['city'] ?? '';
        $state = $location_map['state'] ?? $location_map['state_short'] ?? ''; // Try full state name first, then abbreviation
        $zip = $location_map['post_code'] ?? '';
        
        // Only create address if we have the minimum required fields
        if ( $street && $city && $state ) {
            return [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $street,
                'addressLocality' => $city,
                'addressRegion'   => $state,
                'postalCode'      => $zip ?: '', // Include zip if available
                'addressCountry'  => 'US'
            ];
        }
        
        return null;
    }
    
    /**
     * Get geo coordinates from Google Maps field
     * 
     * @param int $loc_id
     * @return array|null
     */
    private function get_geo_coordinates( $loc_id ) {
        $location_map = get_field( 'location_map', $loc_id );
        
        if ( ! $location_map || empty( $location_map['lat'] ) || empty( $location_map['lng'] ) ) {
            return null;
        }
        
        return [
            '@type'     => 'GeoCoordinates',
            'latitude'  => (string) $location_map['lat'],
            'longitude' => (string) $location_map['lng']
        ];
    }
    
    /**
     * Get opening hours from ACF business_hours repeater field
     * 
     * @param int $loc_id
     * @return array
     */
    private function get_opening_hours( $loc_id ) {
        $business_hours = get_field( 'business_hours', $loc_id );
        
        if ( empty( $business_hours ) ) {
            // Fallback to default hours
            return [
                [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    'opens'     => '09:00',
                    'closes'    => '18:00'
                ]
            ];
        }
        
        $opening_hours = [];
        
        foreach ( $business_hours as $hours_row ) {
            $day = $hours_row['day'] ?? '';
            $hours = $hours_row['hours'] ?? '';
            
            if ( empty( $day ) || empty( $hours ) ) {
                continue;
            }
            
            // Parse hours - handle various formats like "9:00 AM - 5:00 PM" or "09:00-17:00"
            $parsed_times = $this->parse_hours_string( $hours );
            
            if ( $parsed_times ) {
                $opening_hours[] = [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => ucfirst( strtolower( $day ) ), // Ensure proper capitalization
                    'opens'     => (string) $parsed_times['opens'],
                    'closes'    => (string) $parsed_times['closes']
                ];
            }
        }
        
        return empty( $opening_hours ) ? $this->get_default_hours() : $opening_hours;
    }
    
    /**
     * Parse hours string into opens/closes times
     * 
     * @param string $hours_string
     * @return array|null
     */
    private function parse_hours_string( $hours_string ) {
        // Handle "Closed" case
        if ( stripos( $hours_string, 'closed' ) !== false ) {
            return null;
        }
        
        // Common patterns: "9:00 AM - 5:00 PM", "09:00-17:00", "9AM-5PM", etc.
        $patterns = [
            '/(\d{1,2}):(\d{2})\s*(AM|PM)?\s*[-–—]\s*(\d{1,2}):(\d{2})\s*(AM|PM)?/i',
            '/(\d{1,2}):(\d{2})\s*[-–—]\s*(\d{1,2}):(\d{2})/i',
            '/(\d{1,2})\s*(AM|PM)\s*[-–—]\s*(\d{1,2})\s*(AM|PM)/i',
            '/(\d{1,2})\s*[-–—]\s*(\d{1,2})/i'
        ];
        
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
     * @param array $matches
     * @return array
     */
    private function convert_to_24_hour( $matches ) {
        $opens = '';
        $closes = '';
        
        // Handle different match patterns
        if ( count( $matches ) >= 7 && isset( $matches[2] ) && isset( $matches[5] ) ) {
            // Pattern: "9:00 AM - 5:00 PM" (has minutes)
            $open_hour = intval( $matches[1] );
            $open_min = $matches[2];
            $open_period = strtoupper( $matches[3] ?? '' );
            
            $close_hour = intval( $matches[4] );
            $close_min = $matches[5];
            $close_period = strtoupper( $matches[6] ?? '' );
            
            // Convert to 24-hour
            if ( $open_period === 'PM' && $open_hour !== 12 ) $open_hour += 12;
            if ( $open_period === 'AM' && $open_hour === 12 ) $open_hour = 0;
            if ( $close_period === 'PM' && $close_hour !== 12 ) $close_hour += 12;
            if ( $close_period === 'AM' && $close_hour === 12 ) $close_hour = 0;
            
            $opens = sprintf( '%02d:%s', $open_hour, $open_min );
            $closes = sprintf( '%02d:%s', $close_hour, $close_min );
            
        } elseif ( count( $matches ) >= 5 && isset( $matches[2] ) && isset( $matches[4] ) && is_numeric( $matches[2] ) ) {
            // Pattern: "09:00-17:00" (24-hour format with minutes)
            $opens = sprintf( '%02d:%s', intval( $matches[1] ), $matches[2] );
            $closes = sprintf( '%02d:%s', intval( $matches[3] ), $matches[4] );
            
        } elseif ( count( $matches ) >= 5 && isset( $matches[2] ) && isset( $matches[4] ) ) {
            // Pattern: "9AM-5PM" (hour only with AM/PM)
            $open_hour = intval( $matches[1] );
            $open_period = strtoupper( $matches[2] );
            $close_hour = intval( $matches[3] );
            $close_period = strtoupper( $matches[4] );
            
            // Convert to 24-hour
            if ( $open_period === 'PM' && $open_hour !== 12 ) $open_hour += 12;
            if ( $open_period === 'AM' && $open_hour === 12 ) $open_hour = 0;
            if ( $close_period === 'PM' && $close_hour !== 12 ) $close_hour += 12;
            if ( $close_period === 'AM' && $close_hour === 12 ) $close_hour = 0;
            
            $opens = sprintf( '%02d:00', $open_hour );
            $closes = sprintf( '%02d:00', $close_hour );
            
        } elseif ( count( $matches ) >= 3 ) {
            // Pattern: "9-17" (24-hour format, hour only)
            $opens = sprintf( '%02d:00', intval( $matches[1] ) );
            $closes = sprintf( '%02d:00', intval( $matches[2] ) );
        }
        
        return [
            'opens' => $opens,
            'closes' => $closes
        ];
    }
    
    /**
     * Get default opening hours
     * 
     * @return array
     */
    private function get_default_hours() {
        return [
            [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens'     => '09:00',
                'closes'    => '18:00'
            ]
        ];
    }
    
    /**
     * Get available services
     * 
     * @return array
     */
    private function get_available_services() {
        $services = [
            'Breast Augmentation',
            'Brazilian Butt Lift',
            'Liposuction',
            'Tummy Tuck',
            'Mommy Makeover',
            'Rhinoplasty',
            'Face Lift'
        ];
        
        return array_map( function( $service ) {
            return [
                '@type' => 'MedicalProcedure',
                'name'  => $service,
                'procedureType' => 'Surgical'
            ];
        }, $services );
    }
    
    /**
     * Get aggregate rating
     * 
     * @param int $loc_id
     * @return array|null
     */
    private function get_rating( $loc_id ) {
        if ( $rating = get_field( 'average_rating', $loc_id ) ) {
            return [
                '@type'       => 'AggregateRating',
                'ratingValue' => $rating,
                'bestRating'  => '5',
                'reviewCount' => get_field( 'review_count', $loc_id ) ?: 0
            ];
        }
        
        return null;
    }
    
    /**
     * Get employees (surgeons at this location)
     * 
     * @param int $loc_id
     * @return array
     */
    private function get_employees( $loc_id ) {
        $surgeons = get_posts([
            'post_type'      => 'surgeon',
            'posts_per_page' => -1,
            'meta_key'       => 'surgeon_location',
            'meta_value'     => $loc_id
        ]);
        
        if ( empty( $surgeons ) ) {
            return [];
        }
        
        return array_map( function( $surgeon ) {
            return [ '@id' => get_permalink( $surgeon->ID ) . '#physician' ];
        }, $surgeons );
    }
    
    /**
     * Get featured video from video_details group field
     * 
     * @param int $loc_id
     * @return array|null
     */
    private function get_featured_video( $loc_id ) {
        $video_details = get_field( 'video_details', $loc_id );
        
        if ( empty( $video_details ) || empty( $video_details['video_id'] ) ) {
            return null;
        }
        
        $video_id = $video_details['video_id'];
        $video_title = !empty( $video_details['video_title'] ) ? $video_details['video_title'] : get_the_title() . ' - Featured Video';
        $video_description = !empty( $video_details['video_description'] ) ? $video_details['video_description'] : 'Learn more about Mia Aesthetics ' . get_the_title() . ' location';
        
        // Generate YouTube URLs from video ID
        $watch_url = "https://www.youtube.com/watch?v={$video_id}";
        $embed_url = "https://www.youtube.com/embed/{$video_id}";
        
        // Use custom thumbnail if available, otherwise use YouTube thumbnail
        $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
        if ( !empty( $video_details['video_thumbnail'] ) ) {
            $custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
            if ( $custom_thumbnail ) {
                $thumbnail_url = $custom_thumbnail;
            }
        }
        
        return [
            '@type'        => 'VideoObject',
            '@id'          => get_permalink( $loc_id ) . '#video',
            'name'         => $video_title,
            'description'  => $video_description,
            'url'          => $watch_url,
            'embedUrl'     => $embed_url,
            'thumbnailUrl' => $thumbnail_url,
            'uploadDate'   => get_the_date( 'c', $loc_id ), // Use location post date as fallback
            'publisher'    => [
                '@type' => 'Organization',
                'name'  => 'Mia Aesthetics',
                'url'   => home_url()
            ]
        ];
    }
    
}