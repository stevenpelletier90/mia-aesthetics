<?php
/**
 * FAQ Schema
 * 
 * Generates FAQ schema markup for any page with FAQ content
 * 
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FAQ_Schema {
    
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
        // Check if current page has FAQ content
        $faq_section = get_field( 'faq_section', get_the_ID() );
        return ! empty( $faq_section ) && ! empty( $faq_section['faqs'] );
    }
    
    /**
     * Generate the FAQ schema
     * 
     * @return array|null Schema.org compliant FAQPage data
     */
    public function generate() {
        $post_id = get_the_ID();
        $faq_section = get_field( 'faq_section', $post_id );
        
        if ( empty( $faq_section ) || empty( $faq_section['faqs'] ) ) {
            return [];
        }
        
        $questions = [];
        
        foreach ( $faq_section['faqs'] as $faq_item ) {
            if ( empty( $faq_item['question'] ) || empty( $faq_item['answer'] ) ) {
                continue;
            }
            
            $questions[] = [
                '@type' => 'Question',
                'name'  => wp_strip_all_tags( $faq_item['question'] ),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => wp_strip_all_tags( $faq_item['answer'] )
                ]
            ];
        }
        
        if ( empty( $questions ) ) {
            return [];
        }
        
        $faq_schema = [
            '@type'      => 'FAQPage',
            '@id'        => get_permalink( $post_id ) . '#faq',
            'mainEntity' => $questions
        ];
        
        // Add title if available
        if ( !empty( $faq_section['title'] ) ) {
            $faq_schema['name'] = wp_strip_all_tags( $faq_section['title'] );
        }
        
        // Add URL for the FAQ page
        $faq_schema['url'] = get_permalink( $post_id );
        
        return $faq_schema;
    }
}
