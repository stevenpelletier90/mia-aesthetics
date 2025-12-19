<?php
/**
 * Schema Loader
 *
 * Main class responsible for loading and initializing all schema components
 *
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema loader for registering custom schema pieces
 */
class Schema_Loader {

	/**
	 * Initialize the schema system
	 *
	 * @return void
	 */
	public static function init(): void {
		// Load schema classes.
		self::load_dependencies();

		// Initialize organization schema enhancements.
		Organization_Schema::init();

		// Register custom schema pieces with Yoast.
		add_filter( 'wpseo_schema_graph_pieces', array( self::class, 'register_schema_pieces' ), 11, 2 );
	}

	/**
	 * Load required schema classes
	 *
	 * @return void
	 */
	private static function load_dependencies(): void {
		$schema_dir = get_template_directory() . '/inc/schema/';

		require_once $schema_dir . 'class-organization-schema.php';
		require_once $schema_dir . 'class-surgeon-schema.php';
		require_once $schema_dir . 'class-clinic-schema.php';
		require_once $schema_dir . 'class-procedure-schema.php';
		require_once $schema_dir . 'class-non-surgical-schema.php';
		require_once $schema_dir . 'class-fat-transfer-schema.php';
		require_once $schema_dir . 'class-condition-schema.php';
		require_once $schema_dir . 'class-faq-schema.php';
	}

	/**
	 * Register custom schema pieces with Yoast
	 *
	 * @param array<int, mixed>                       $pieces Existing schema pieces.
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context The Yoast context object.
	 * @return array<int, mixed> Modified schema pieces
	 */
	public static function register_schema_pieces( $pieces, $context ): array {
		if ( is_singular( 'surgeon' ) ) {
			$pieces[] = new Surgeon_Schema( $context );
		}

		if ( is_singular( 'location' ) ) {
			$pieces[] = new Clinic_Schema( $context );
		}

		if ( is_singular( 'procedure' ) ) {
			$pieces[] = new Procedure_Schema( $context );
		}

		if ( is_singular( 'non-surgical' ) ) {
			$pieces[] = new NonSurgical_Schema( $context );
		}

		if ( is_singular( 'fat-transfer' ) ) {
			$pieces[] = new FatTransfer_Schema( $context );
		}

		if ( is_singular( 'condition' ) ) {
			$pieces[] = new Condition_Schema( $context );
		}

		// FAQ Schema can be added to any page type.
		$faq_schema = new FAQ_Schema();
		if ( $faq_schema->is_needed() ) {
			$pieces[] = $faq_schema;
		}

		return $pieces;
	}
}
