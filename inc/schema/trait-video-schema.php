<?php
/**
 * Video Schema Trait
 *
 * Shared video handling methods for schema classes.
 * Eliminates duplicate video-related code across surgeon, procedure, clinic, and other schema classes.
 *
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait for video schema handling
 *
 * Provides common video-related methods used across multiple schema classes.
 * Classes using this trait should override get_video_title_fallback() and
 * get_video_description_fallback() to customize fallback text.
 */
trait Video_Schema_Trait {

	/**
	 * Get video title fallback when no custom title is set
	 *
	 * Override this method in classes to customize the fallback title.
	 *
	 * @return string The fallback video title.
	 */
	protected function get_video_title_fallback(): string {
		return get_the_title() . ' - Featured Video';
	}

	/**
	 * Get video description fallback when no custom description is set
	 *
	 * Override this method in classes to customize the fallback description.
	 *
	 * @return string The fallback video description.
	 */
	protected function get_video_description_fallback(): string {
		return 'Learn more about ' . get_the_title() . ' at Mia Aesthetics';
	}

	/**
	 * Get featured video schema from video_details group field
	 *
	 * @param int $post_id The post ID.
	 * @return array<string, mixed>|null Featured video schema data or null.
	 */
	protected function get_featured_video( int $post_id ): ?array {
		$video_details = get_field( 'video_details', $post_id );

		if ( ! is_array( $video_details ) || ! isset( $video_details['video_id'] ) || '' === $video_details['video_id'] ) {
			return null;
		}

		$video_id          = $video_details['video_id'];
		$video_title       = $this->get_video_title( $video_details );
		$video_description = $this->get_video_description( $video_details );
		$thumbnail_url     = $this->get_video_thumbnail_url( $video_details, $video_id );

		// Generate YouTube URLs from video ID.
		$watch_url = 'https://www.youtube.com/watch?v=' . $video_id;
		$embed_url = 'https://www.youtube.com/embed/' . $video_id;

		return array(
			'@type'        => 'VideoObject',
			'@id'          => get_permalink( $post_id ) . '#video',
			'name'         => $video_title,
			'description'  => $video_description,
			'url'          => $watch_url,
			'embedUrl'     => $embed_url,
			'thumbnailUrl' => $thumbnail_url,
			'uploadDate'   => get_the_date( 'c', $post_id ),
			'publisher'    => array(
				'@type' => 'Organization',
				'name'  => 'Mia Aesthetics',
				'url'   => home_url(),
			),
		);
	}

	/**
	 * Get video title with fallback
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string The video title.
	 */
	protected function get_video_title( array $video_details ): string {
		if ( isset( $video_details['video_title'] ) && '' !== $video_details['video_title'] ) {
			return $video_details['video_title'];
		}

		return $this->get_video_title_fallback();
	}

	/**
	 * Get video description with fallback
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string The video description.
	 */
	protected function get_video_description( array $video_details ): string {
		if ( isset( $video_details['video_description'] ) && '' !== $video_details['video_description'] ) {
			return $video_details['video_description'];
		}

		return $this->get_video_description_fallback();
	}

	/**
	 * Get video thumbnail URL
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @param string               $video_id Video ID.
	 * @return string The thumbnail URL.
	 */
	protected function get_video_thumbnail_url( array $video_details, string $video_id ): string {
		// Use custom thumbnail if available.
		if ( isset( $video_details['video_thumbnail'] ) && '' !== $video_details['video_thumbnail'] ) {
			$custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
			if ( false !== $custom_thumbnail ) {
				return $custom_thumbnail;
			}
		}

		// Fall back to YouTube thumbnail.
		return sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $video_id );
	}

	/**
	 * Get video-based image (custom thumbnail or YouTube thumbnail)
	 *
	 * Used as a fallback when no featured image exists.
	 *
	 * @param int $post_id The post ID.
	 * @return string|null The video image URL or null.
	 */
	protected function get_video_image( int $post_id ): ?string {
		$video_details = get_field( 'video_details', $post_id );
		if ( ! is_array( $video_details ) ) {
			return null;
		}

		$custom_thumbnail = $this->get_video_custom_thumbnail( $video_details );
		if ( null !== $custom_thumbnail ) {
			return $custom_thumbnail;
		}

		return $this->get_youtube_thumbnail( $video_details );
	}

	/**
	 * Get custom video thumbnail
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string|null The custom thumbnail URL or null.
	 */
	protected function get_video_custom_thumbnail( array $video_details ): ?string {
		if ( ! isset( $video_details['video_thumbnail'] ) || '' === $video_details['video_thumbnail'] ) {
			return null;
		}

		$custom_thumbnail = wp_get_attachment_image_url( $video_details['video_thumbnail'], 'full' );
		return false !== $custom_thumbnail ? $custom_thumbnail : null;
	}

	/**
	 * Get YouTube thumbnail
	 *
	 * @param array<string, mixed> $video_details Video details from ACF.
	 * @return string|null The YouTube thumbnail URL or null.
	 */
	protected function get_youtube_thumbnail( array $video_details ): ?string {
		if ( ! isset( $video_details['video_id'] ) || '' === $video_details['video_id'] ) {
			return null;
		}

		return sprintf( 'https://img.youtube.com/vi/%s/maxresdefault.jpg', $video_details['video_id'] );
	}
}
