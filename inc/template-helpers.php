<?php
/**
 * Template Helper Functions for Mia Aesthetics Theme
 *
 * Provides utility functions for templates, UI components, and display helpers.
 * Includes logo handling, image utilities, formatting functions, and reusable components.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logo and Branding Functions
 */

/**
 * Get the site logo URL with fallback support
 *
 * @param bool $fallback Whether to use fallback if custom logo not set.
 * @return string|false Logo URL or false if not found
 */
function mia_aesthetics_get_logo_url( $fallback = true ) {
	// Try custom logo first.
	$custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( 0 !== $custom_logo_id && is_numeric( $custom_logo_id ) ) {
		$logo_url = wp_get_attachment_image_url( (int) $custom_logo_id, 'full' );
		if ( false !== $logo_url && '' !== $logo_url ) {
			return $logo_url;
		}
	}

	// Fallback to known logo location if enabled.
	if ( $fallback ) {
		$fallback_path = '/2024/11/miaaesthetics-logo.svg';
		$upload_dir    = wp_upload_dir();
		$logo_path     = $upload_dir['basedir'] . $fallback_path;

		if ( file_exists( $logo_path ) ) {
			return $upload_dir['baseurl'] . $fallback_path;
		}
	}

	return false;
}

/**
 * Output the site logo with proper attributes
 *
 * @param array<string, string> $args Logo arguments.
 * @return void
 */
function mia_aesthetics_the_logo( $args = array() ): void {
	$defaults = array(
		'height'        => '50',
		'width'         => '200',
		'class'         => 'd-inline-block',
		/* translators: %s: Site name. */
		'alt'           => sprintf( __( '%s Logo', 'mia-aesthetics' ), get_bloginfo( 'name' ) ),
		'fetchpriority' => false,
		'loading'       => false,
		'link'          => true,
		'link_class'    => 'navbar-brand',
		'aria_label'    => esc_attr__( 'Homepage', 'mia-aesthetics' ),
	);

		// Allow 3rd-parties to customize logo defaults/args.
		$args = apply_filters( 'mia_aesthetics_logo_args', wp_parse_args( $args, $defaults ) );
	$logo_url = mia_aesthetics_get_logo_url();

	// Build logo HTML.
	if ( false !== $logo_url ) {
		$attributes = array(
			'src'    => esc_url( $logo_url ),
			'alt'    => esc_attr( $args['alt'] ),
			'height' => esc_attr( $args['height'] ),
			'width'  => esc_attr( $args['width'] ),
			'class'  => esc_attr( $args['class'] ),
		);

		if ( $args['fetchpriority'] ) {
			$attributes['fetchpriority'] = 'high';
		}

		if ( $args['loading'] ) {
			$attributes['loading'] = esc_attr( $args['loading'] );
		}

		$img_tag = '<img';
		foreach ( $attributes as $key => $value ) {
			$img_tag .= ' ' . $key . '="' . $value . '"';
		}
		$img_tag .= ' />';

		// Wrap in link if requested.
		if ( $args['link'] ) {
			$link_attrs = array(
				'href'       => esc_url( home_url( '/' ) ),
				'class'      => esc_attr( $args['link_class'] ),
				'aria-label' => esc_attr( $args['aria_label'] ),
			);

			echo '<a';
			foreach ( $link_attrs as $key => $value ) {
				echo ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
			echo '>' . wp_kses_post( $img_tag ) . '</a>';
		} else {
			echo wp_kses_post( $img_tag );
		}
	} else {
		// Fallback to text logo.
		$text = '<span class="navbar-brand-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';

		if ( $args['link'] ) {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="' . esc_attr( $args['link_class'] ) . '">' . wp_kses_post( $text ) . '</a>';
		} else {
			echo wp_kses_post( $text );
		}
	}
}

/**
 * UI Component Functions
 */

/**
 * Display FAQ section with Bootstrap accordion
 *
 * @param bool $show_heading Whether to show section heading.
 * @return string HTML output
 */
function mia_aesthetics_display_faqs( $show_heading = true ) {
	$faq_section = get_field( 'faq_section' );

	// Check for valid FAQ data.
	if ( null === $faq_section || ! isset( $faq_section['faqs'] ) || ! is_array( $faq_section['faqs'] ) ) {
		return '';
	}

	$faqs = $faq_section['faqs'];

	// Filter out empty FAQs and check if we have any valid ones.
	$valid_faqs = array_filter(
		$faqs,
		static function ( $faq ) {
			return isset( $faq['question'] ) && '' !== $faq['question'] && isset( $faq['answer'] ) && '' !== $faq['answer'];
		}
	);

	// If no valid FAQs, return empty.
	if ( array() === $valid_faqs ) {
		return '';
	}

	$accordion_id = 'faq-accordion-' . get_the_ID();

	// Pass data to component.
	$args = array(
		'show_heading' => $show_heading,
		'faq_section'  => $faq_section,
		'valid_faqs'   => $valid_faqs,
		'accordion_id' => $accordion_id,
	);

	ob_start();
	get_template_part( 'components/faq', null, $args );
	$output = ob_get_clean();
	return false !== $output ? $output : '';
}


/**
 * Template Filter Functions
 */

/**
 * Add custom body classes for template identification
 *
 * @param array<int, string> $classes Array of body classes.
 * @return array<int, string> Modified array of body classes.
 */
function mia_template_body_classes( $classes ) {
	// Add class for pages with gallery shortcode (filterable list of shortcodes).
	if ( is_page() ) {
		$shortcodes = apply_filters( 'mia_gallery_body_class_shortcodes', array( 'gallery' ) );
		$post_obj   = get_post();
		if ( $post_obj instanceof WP_Post && is_array( $shortcodes ) && array() !== $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				if ( is_string( $shortcode ) && '' !== $shortcode && has_shortcode( (string) $post_obj->post_content, $shortcode ) ) {
					$classes[] = 'has-gallery';
					break;
				}
			}
		}
	}

	return $classes;
}
add_filter( 'body_class', 'mia_template_body_classes' );
