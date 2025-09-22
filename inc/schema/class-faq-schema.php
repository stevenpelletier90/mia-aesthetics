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

/**
 * FAQ Schema markup generator
 */
class FAQ_Schema {

	/**
	 * Determines if this schema piece is needed
	 *
	 * @return bool
	 */
	public function is_needed() {
		// Check if current page has FAQ content.
		$faq_section = get_field( 'faq_section', get_the_ID() );
		return is_array( $faq_section ) && isset( $faq_section['faqs'] ) && is_array( $faq_section['faqs'] ) && count( $faq_section['faqs'] ) > 0;
	}

	/**
	 * Generate the FAQ schema
	 *
	 * @return array<string, mixed> Schema.org compliant FAQPage data
	 */
	public function generate(): array {
		$post_id = get_the_ID();
		if ( false === $post_id || 0 === $post_id ) {
			return array();
		}
		$faq_section = get_field( 'faq_section', $post_id );

		if ( ! is_array( $faq_section ) || ! isset( $faq_section['faqs'] ) || ! is_array( $faq_section['faqs'] ) || count( $faq_section['faqs'] ) === 0 ) {
			return array();
		}

		$questions = array();

		foreach ( $faq_section['faqs'] as $faq_item ) {
			if ( ! isset( $faq_item['question'] ) || ! isset( $faq_item['answer'] ) || '' === $faq_item['question'] || '' === $faq_item['answer'] ) {
				continue;
			}

			$questions[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $faq_item['question'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $faq_item['answer'] ),
				),
			);
		}

		if ( array() === $questions ) {
			return array();
		}

		$faq_schema = array(
			'@type'      => 'FAQPage',
			'@id'        => get_permalink( $post_id ) . '#faq',
			'mainEntity' => $questions,
		);

		// Add title if available.
		if ( isset( $faq_section['title'] ) && '' !== $faq_section['title'] ) {
			$faq_schema['name'] = wp_strip_all_tags( $faq_section['title'] );
		}

		// Add URL for the FAQ page.
		$faq_schema['url'] = get_permalink( $post_id );

		return $faq_schema;
	}
}
