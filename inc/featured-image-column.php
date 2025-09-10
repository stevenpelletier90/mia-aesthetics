<?php
/**
 * Lean featured‑image column for every thumbnail‑enabled post type.
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue admin styles for featured image column only on post listing pages
 *
 * @param string $hook The current admin page hook.
 */
function mia_admin_enqueue_styles( $hook ): void {
	// Only load admin CSS on post listing pages where featured image columns appear.
	if ( 'edit.php' === $hook ) {
		wp_enqueue_style( 'mia-admin', get_template_directory_uri() . '/assets/css/utilities/admin.css', array(), '1.0.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'mia_admin_enqueue_styles' );

add_action( 'admin_init', 'mia_featured_image_columns_init' );

/**
 * Initialize featured image columns for admin post listings
 *
 * @return void
 */
function mia_featured_image_columns_init(): void {
	/*
	---------------------------------------------------------------------
	 * Register the column, render its cells, and make it sortable.
	 * ------------------------------------------------------------------
	 */
	foreach ( get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'thumbnail' ) ) {

			// 1. Add header.
			add_filter(
				sprintf( 'manage_%s_posts_columns', $type ),
				static function ( $cols ) {
					$label = sprintf(
						'<span class="dashicons dashicons-format-image" aria-hidden="true"></span>
						 <span class="screen-reader-text">%s</span>',
						esc_html__( 'Featured', 'mia-aesthetics' )
					);
					// Place after Title.
					$offset = array_search( 'title', array_keys( $cols ), true );
					if ( false === $offset ) {
						$offset = 0;
					}
					return array_slice( $cols, 0, $offset + 1, true )
						+ array( 'thumb' => $label )
						+ array_slice( $cols, $offset + 1, null, true );
				}
			);

			// 2. Render each cell.
			add_action(
				sprintf( 'manage_%s_posts_custom_column', $type ),
				static function ( $column, $post_id ): void {
					if ( 'thumb' !== $column ) {
						return;
					}

					$id = get_post_thumbnail_id( $post_id );
					if ( 0 !== $id && false !== $id ) {
						echo wp_kses_post(
							wp_get_attachment_image(
								$id,
								array( 40, 40 ),
								false,
								array(
									'class'   => 'admin-thumbnail',
									'loading' => 'lazy',
								)
							)
						);
					} else {
						printf(
							'<span class="dashicons dashicons-format-image opacity-30 font-size-20" aria-hidden="true"></span><span class="screen-reader-text">%s</span>',
							esc_html__( 'No featured image set', 'mia-aesthetics' )
						);
					}
				},
				10,
				2
			);

			// 3. Make sortable.
			add_filter(
				sprintf( 'manage_edit-%s_sortable_columns', $type ),
				static fn ( $cols ) => $cols + array( 'thumb' => 'has_thumb' )
			);
		}
	}

	// 4. Alter main query when the user clicks to sort.
	add_action(
		'pre_get_posts',
		static function ( $q ): void {
			if ( ! is_admin() || ! $q->is_main_query() ) {
				return;
			}

			if ( 'has_thumb' === $q->get( 'orderby' ) ) {
				$q->set( 'meta_key', '_thumbnail_id' );
				$q->set( 'orderby', 'meta_value_num' );
				$q->set( 'order', $q->get( 'order' ) ? $q->get( 'order' ) : 'DESC' );
			}
		}
	);

	/*
	---------------------------------------------------------------------
	 * Custom bulk action: remove featured image.
	 * (Setting a thumbnail from Media Library involves JS; omit here.)
	 * ------------------------------------------------------------------
	 */
	$screen_ids = array_map(
		static fn ( $pt ) => 'edit-' . $pt,
		array_filter(
			get_post_types( array( 'public' => true ) ),
			static fn ( $pt ) => post_type_supports( $pt, 'thumbnail' )
		)
	);

	foreach ( $screen_ids as $screen ) {
		add_filter(
			'bulk_actions-' . $screen,
			static fn ( $acts ) => $acts + array( 'remove_thumb' => __( 'Remove Featured Image', 'mia-aesthetics' ) )
		);

		add_filter(
			'handle_bulk_actions-' . $screen,
			static function ( $redirect, $action, $ids ) {
				if ( 'remove_thumb' !== $action ) {
					return $redirect;
				}

				if ( ! current_user_can( 'edit_posts' ) ) {
					return $redirect;
				}

				foreach ( $ids as $id ) {
					delete_post_thumbnail( $id );
				}

				return add_query_arg(
					array( 'thumbs_removed' => count( $ids ) ),
					$redirect
				);
			},
			10,
			3
		);
	}

	add_action(
		'admin_notices',
		static function (): void {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is admin notice display only, no actions taken
			if ( ! isset( $_GET['thumbs_removed'] ) || '' === $_GET['thumbs_removed'] || ! current_user_can( 'edit_posts' ) ) {
				return;
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is admin notice display only, no actions taken
			$count = (int) $_GET['thumbs_removed'];
			printf(
				'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
				esc_html(
					sprintf(
						/* translators: %s = number of posts */
						_n( 'Removed featured image from %s item.', 'Removed featured images from %s items.', $count, 'mia-aesthetics' ),
						number_format_i18n( $count )
					)
				)
			);
		}
	);

	/*
	---------------------------------------------------------------------
	 * A tiny dashicon‑inline style so we avoid an extra stylesheet request.
	 * Only load on post list screens where the column appears.
	 * ------------------------------------------------------------------
	 */
	add_action(
		'admin_enqueue_scripts',
		static function ( $hook ): void {
			if ( 'edit.php' === $hook ) {
				wp_add_inline_style(
					'dashicons',
					'.column-thumb { width:60px;text-align:center; }'
				);
			}
		}
	);
}
