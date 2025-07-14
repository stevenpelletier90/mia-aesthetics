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

class Surgeon_Schema {
    
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
        return is_singular( 'surgeon' );
    }
    
    /**
     * Generate the surgeon schema
     * 
     * @return array Schema.org compliant Person/Physician data
     */
    public function generate() {
        $surgeon_id = get_the_ID();
        $org_id     = $this->context->site_url . '#organization';
        
        $schema_data = [];
        
        // Get associated clinic
        $clinic_obj = get_field( 'surgeon_location', $surgeon_id );
        $clinic_id  = $clinic_obj ? get_permalink( $clinic_obj->ID ) . '#clinic' : null;
        
        $surgeon = [
            '@type'            => [ 'Person', 'Physician' ],
            '@id'              => get_permalink( $surgeon_id ) . '#physician',
            'name'             => get_the_title(),
            'jobTitle'         => 'Board Certified Plastic Surgeon',
            'medicalSpecialty' => 'PlasticSurgery',
            'url'              => get_permalink( $surgeon_id ),
            'affiliation'      => [ '@id' => $org_id ],
        ];
        
        // Link to clinic
        if ( $clinic_id ) {
            $surgeon['worksFor']   = [ '@id' => $clinic_id ];
            $surgeon['department'] = [ '@id' => $clinic_id ];
        }
        
        // Description
        $surgeon['description'] = $this->get_description( $surgeon_id );
        
        // Image
        $image_url = $this->get_image( $surgeon_id );
        if ( $image_url ) {
            $surgeon['image'] = $image_url;
        }
        
        // Professional credentials
        if ( get_field( 'board_certified', $surgeon_id ) ) {
            $surgeon['hasCredential'] = [
                '@type' => 'EducationalOccupationalCredential',
                'credentialCategory' => 'Board Certification',
                'recognizedBy' => [
                    '@type' => 'Organization',
                    'name' => 'American Board of Plastic Surgery'
                ]
            ];
        }
        
        // Specialties
        $specialties = $this->get_specialties( $surgeon_id );
        if ( ! empty( $specialties ) ) {
            $surgeon['knowsAbout'] = $specialties;
        }
        
        // Education
        if ( $school = get_field( 'medical_school', $surgeon_id ) ) {
            $surgeon['alumniOf'] = [
                '@type' => 'EducationalOrganization',
                'name' => $school
            ];
        }
        
        $schema_data[] = $surgeon;
        
        // Add separate VideoObject schema if video exists
        $video = $this->get_featured_video( $surgeon_id );
        if ( $video ) {
            $schema_data[] = $video;
        }
        
        
        return $schema_data;
    }
    
    /**
     * Get surgeon description
     * 
     * @param int $surgeon_id
     * @return string
     */
    private function get_description( $surgeon_id ) {
        if ( $desc = get_post_meta( $surgeon_id, '_yoast_wpseo_metadesc', true ) ) {
            return $desc;
        }
        
        return 'Dr. ' . get_the_title() . ' is a board-certified plastic surgeon at Mia Aesthetics specializing in cosmetic and reconstructive procedures.';
    }
    
    /**
     * Get surgeon image
     * 
     * @param int $surgeon_id
     * @return string|null
     */
    private function get_image( $surgeon_id ) {
        // Prioritize featured image first for surgeon profiles
        if ( has_post_thumbnail( $surgeon_id ) ) {
            $featured_image = get_the_post_thumbnail_url( $surgeon_id, 'full' );
            if ( $featured_image ) {
                return $featured_image;
            }
        }
        
        // Fall back to video thumbnail from video_details group
        $video_details = get_field( 'video_details', $surgeon_id );
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
     * Get featured video from video_details group field
     * 
     * @param int $surgeon_id
     * @return array|null
     */
    private function get_featured_video( $surgeon_id ) {
        $video_details = get_field( 'video_details', $surgeon_id );
        
        if ( empty( $video_details ) || empty( $video_details['video_id'] ) ) {
            return null;
        }
        
        $video_id = $video_details['video_id'];
        $video_title = !empty( $video_details['video_title'] ) ? $video_details['video_title'] : 'Dr. ' . get_the_title() . ' - Featured Video';
        $video_description = !empty( $video_details['video_description'] ) ? $video_details['video_description'] : 'Learn more about Dr. ' . get_the_title() . ' at Mia Aesthetics';
        
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
            '@id'          => get_permalink( $surgeon_id ) . '#video',
            'name'         => $video_title,
            'description'  => $video_description,
            'url'          => $watch_url,
            'embedUrl'     => $embed_url,
            'thumbnailUrl' => $thumbnail_url,
            'uploadDate'   => get_the_date( 'c', $surgeon_id ), // Use surgeon post date as fallback
            'publisher'    => [
                '@type' => 'Organization',
                'name'  => 'Mia Aesthetics',
                'url'   => home_url()
            ]
        ];
    }
    
    /**
     * Get surgeon specialties
     * 
     * @param int $surgeon_id
     * @return array
     */
    private function get_specialties( $surgeon_id ) {
        $specialties = [];
        
        for ( $i = 1; $i <= 3; $i++ ) {
            if ( $specialty = get_field( 'specialty_' . $i, $surgeon_id ) ) {
                $specialties[] = $specialty;
            }
        }
        
        return $specialties;
    }
    
}