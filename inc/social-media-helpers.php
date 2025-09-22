<?php
/**
 * Social Media Helper Functions
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get social media URL from ACF settings
 *
 * @param string $platform The social media platform key.
 * @return string|false The URL or false if not set.
 */
function get_social_media_url( $platform ) {
	$field_name = $platform . '_url';
	$url        = get_field( $field_name, 'option' );

		return null !== $url && false !== $url && '' !== $url ? $url : false;
}

/**
 * Output social media links with icons
 *
 * @param string $class_name CSS class for the links (default: 'social-icon').
 * @return void
 */
function mia_social_media_links( $class_name = 'social-icon' ): void {
	$platforms = array(
		'facebook'  => array(
			'label' => 'Facebook',
			'icon'  => 'fab fa-facebook-f',
		),
		'instagram' => array(
			'label' => 'Instagram',
			'icon'  => 'fab fa-instagram',
		),
		'tiktok'    => array(
			'label' => 'TikTok',
			'icon'  => 'fab fa-tiktok',
		),
		'twitter'   => array(
			'label' => 'Twitter',
			'icon'  => 'fab fa-twitter',
		),
		'snapchat'  => array(
			'label' => 'SnapChat',
			'icon'  => 'fab fa-snapchat-ghost',
		),
		'youtube'   => array(
			'label' => 'YouTube',
			'icon'  => 'fab fa-youtube',
		),
	);

	// Add LinkedIn only on careers page.
	if ( is_page( 'careers' ) ) {
		$platforms['linkedin'] = array(
			'label' => 'LinkedIn',
			'icon'  => 'fab fa-linkedin-in',
		);
	}

	foreach ( $platforms as $platform => $data ) {
		$url = get_social_media_url( $platform );
		if ( $url ) {
			?>
			<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $data['label'] ); ?>" class="<?php echo esc_attr( $class_name ); ?>">
				<i class="<?php echo esc_attr( $data['icon'] ); ?>" aria-hidden="true"></i>
			</a>
			<?php
		}
	}
}
