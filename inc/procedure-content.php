<?php
/**
 * Procedure Content Mapping
 *
 * Maps procedure post IDs to PHP content files.
 * Post IDs are used instead of slugs because they never change
 * (URLs can be modified, but post IDs remain constant).
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get procedure content file mapping (post ID => filename).
 *
 * @return array<int, string> Map of post IDs to content filenames.
 */
function mia_get_procedure_content_map(): array {
	return array(
		// Body Procedures.
		171   => 'bbl.php',
		185   => 'arm-lift.php',
		182   => 'awake-lipo.php',
		220   => 'tummy-tuck.php',
		172   => 'lipo-360.php',
		179   => 'liposuction.php',
		178   => 'skinny-bbl.php',
		192   => 'circumferential-body-lift.php',
		211   => 'mommy-makeover.php',
		209   => 'mia-corset.php',
		218   => 'thigh-lift.php',
		193   => 'vaginal-rejuvenation.php',

		// Breast Procedures.
		20    => 'breast-augmentation.php',
		222   => 'breast-lift.php',
		224   => 'breast-reduction.php',
		225   => 'breast-implant-revision.php',
		231   => 'male-breast.php',

		// Male Procedures.
		205   => 'male-bbl.php',
		206   => 'male-liposuction.php',
		208   => 'male-tummy-tuck.php',

		// Face Procedures.
		173   => 'facelift.php',
		174   => 'mini-facelift.php',
		191   => 'rhinoplasty.php',
		223   => 'brow-lift.php',
		216   => 'blepharoplasty.php',
		194   => 'neck-lift.php',
		221   => 'buccal-fat.php',
		183   => 'chin-lipo.php',
		33272 => 'lip-lift.php',
		30228 => 'otoplasty.php',
	);
}
