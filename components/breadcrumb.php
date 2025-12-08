<?php
/**
 * Breadcrumb Component
 *
 * Displays breadcrumb navigation using Yoast SEO.
 * This component is called via get_template_part() and expects
 * breadcrumb data to be passed through $args.
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get breadcrumb data from args.
$breadcrumbs = $args['breadcrumbs'] ?? '';

// Exit early if no breadcrumbs to display.
if ( '' === $breadcrumbs || ! is_string( $breadcrumbs ) ) {
	return;
}

// Get filtered classes for customization.
$nav_class       = apply_filters( 'mia_aesthetics_breadcrumbs_nav_class', 'breadcrumb-nav' );
$container_class = apply_filters( 'mia_aesthetics_breadcrumbs_container_class', 'container' );
?>

<nav aria-label="<?php echo esc_attr__( 'Breadcrumb', 'mia-aesthetics' ); ?>" class="<?php echo esc_attr( $nav_class ); ?>">
	<div class="<?php echo esc_attr( $container_class ); ?>">
		<span class="visually-hidden"><?php echo esc_html__( 'You are here:', 'mia-aesthetics' ); ?></span>
		<?php echo wp_kses_post( $breadcrumbs ); ?>
	</div>
</nav>