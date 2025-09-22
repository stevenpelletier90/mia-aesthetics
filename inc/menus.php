<?php
/**
 * Menu structure and rendering for Mia Aesthetics theme.
 *
 * Defines the main navigation menu data structure and rendering entry point.
 * All menu sections and items are defined here for consistency and maintainability.
 *
 * @package Mia_Aesthetics
 */

/**
 * Returns the main menu data structure for all procedures and sections.
 * Centralizes menu definitions to avoid duplication and simplify updates.
 *
 * @return array<string, mixed> Main menu structure
 */
function mia_aesthetics_get_menu_structure(): array {
	return array(
		'procedures'   => array(
			'title'    => 'Procedures',
			'url'      => home_url( '/cosmetic-plastic-surgery/' ),
			'sections' => array(
				'body'   => array(
					'title' => 'Body',
					'url'   => home_url( '/cosmetic-plastic-surgery/body/' ),
					'items' => array(
						array(
							'title' => 'Mia Waist Corset™',
							'slug'  => 'mia-corset',
						),
						array(
							'title' => 'Awake Lipo',
							'slug'  => 'awake-liposuction',
						),
						array(
							'title' => 'Body Lift',
							'slug'  => 'circumferential-body-lift',
						),
						array(
							'title' => 'Brazilian Butt Lift (BBL)',
							'slug'  => 'brazilian-butt-lift-bbl',
						),
						array(
							'title' => 'Lipo 360',
							'slug'  => 'lipo-360',
						),
						array(
							'title' => 'Liposuction',
							'slug'  => 'liposuction',
						),
						array(
							'title' => 'Tummy Tuck',
							'slug'  => 'tummy-tuck',
						),
						array(
							'title' => 'Mommy Makeover',
							'slug'  => 'mommy-makeover',
						),
						array(
							'title' => 'Arm Lift',
							'slug'  => 'arm-lift',
						),
						array(
							'title' => 'Thigh Lift',
							'slug'  => 'thigh-lift',
						),
						array(
							'title' => 'Vaginal Rejuvenation',
							'slug'  => 'labiaplasty-labia-reduction-vaginal-rejuvenation',
						),
					),
				),
				'breast' => array(
					'title' => 'Breast',
					'url'   => home_url( '/cosmetic-plastic-surgery/breast/' ),
					'items' => array(
						array(
							'title' => 'Breast Augmentation',
							'slug'  => 'augmentation-implants',
						),
						array(
							'title' => 'Breast Reduction',
							'slug'  => 'reduction',
						),
						array(
							'title' => 'Breast Lift',
							'slug'  => 'lift',
						),
						array(
							'title' => 'Breast Implant Revision',
							'slug'  => 'implant-revision-surgery',
						),
					),
				),
				'face'   => array(
					'title' => 'Face',
					'url'   => home_url( '/cosmetic-plastic-surgery/face/' ),
					'items' => array(
						array(
							'title' => 'Brow Lift',
							'slug'  => 'brow-lift',
						),
						array(
							'title' => 'Buccal Fat Removal',
							'slug'  => 'buccal-cheek-fat-removal',
						),
						array(
							'title' => 'Blepharoplasty',
							'slug'  => 'eyelid-lift-blepharoplasty',
						),
						array(
							'title' => 'Chin Lipo',
							'slug'  => 'chin-lipo',
						),
						array(
							'title' => 'Facelift',
							'slug'  => 'facelift',
						),
						array(
							'title' => 'Mini Facelift',
							'slug'  => 'mini-facelift',
						),
						array(
							'title' => 'Neck Lift',
							'slug'  => 'neck-lift',
						),
						array(
							'title' => 'Otoplasty',
							'slug'  => 'ear-pinning-otoplasty',
						),
						array(
							'title' => 'Rhinoplasty',
							'slug'  => 'nose-job-rhinoplasty',
						),
					),
				),
				'men'    => array(
					'title' => 'Men',
					'url'   => home_url( '/cosmetic-plastic-surgery/men/' ),
					'items' => array(
						array(
							'title'  => 'Male BBL',
							'slug'   => 'male-bbl',
							'parent' => 'body',
						),
						array(
							'title'  => 'Male Breast Procedures',
							'slug'   => 'male-breast-procedures',
							'parent' => 'breast',
						),
						array(
							'title'  => 'Male Liposuction',
							'slug'   => 'male-liposuction',
							'parent' => 'body',
						),
						array(
							'title'  => 'Male Tummy Tuck',
							'slug'   => 'male-tummy-tuck',
							'parent' => 'body',
						),
					),
				),
			),
		),
		'non-surgical' => array(
			'title'      => 'Non-Surgical',
			'url'        => home_url( '/non-surgical/' ),
			'categories' => array(
				'injectable' => 'Injectable Treatments',
				'skin'       => 'Skin Treatments',
				'body'       => 'Body Contouring',
				'wellness'   => 'Wellness Treatments',
			),
		),
	);
}

/**
 * Renders the main navigation menu for desktop or mobile.
 * Delegates to section-specific renderers as needed.
 *
 * @param string $type 'desktop' or 'mobile'.
 * @return void
 */
function mia_aesthetics_render_menu( $type = 'desktop' ): void {
	$menu      = mia_aesthetics_get_menu_structure();
	$is_mobile = 'mobile' === $type;

	foreach ( $menu as $key => $section ) {
		if ( 'procedures' === $key ) {
			mia_aesthetics_render_procedures_menu( $section, $is_mobile );
		} elseif ( 'non-surgical' === $key ) {
			mia_aesthetics_render_non_surgical_menu( $is_mobile );
		}

		// Add other menu sections here as needed.
	}
}

// (Other menu rendering functions are defined in inc/menu-helpers.php)
