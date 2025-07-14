<?php
/**
 * Lean featured‑image column for every thumbnail‑enabled post type.
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'mia_featured_image_columns_init' );

function mia_featured_image_columns_init() {

	/* ---------------------------------------------------------------------
	 * Register the column, render its cells, and make it sortable.
	 * ------------------------------------------------------------------ */
	foreach ( get_post_types( [ 'public' => true ] ) as $type ) {
		if ( post_type_supports( $type, 'thumbnail' ) ) {

			// 1. Add header.
			add_filter(
				"manage_{$type}_posts_columns",
				static function ( $cols ) {
					$label = sprintf(
						'<span class="dashicons dashicons-format-image" aria-hidden="true"></span>
						 <span class="screen-reader-text">%s</span>',
						esc_html__( 'Featured', 'mia' )
					);
					// Place after Title.
					$offset = array_search( 'title', array_keys( $cols ), true );
					return array_slice( $cols, 0, $offset + 1, true )
						+ [ 'thumb' => $label ]
						+ array_slice( $cols, $offset + 1, null, true );
				}
			);

			// 2. Render each cell.
			add_action(
				"manage_{$type}_posts_custom_column",
				static function ( $column, $post_id ) {
					if ( 'thumb' !== $column ) {
						return;
					}

					$id = get_post_thumbnail_id( $post_id );
					if ( $id ) {
						echo wp_kses_post(
							wp_get_attachment_image( $id, [ 40, 40 ], false, [
								'style' => 'width:40px;height:40px;object-fit:cover;border-radius:4px;',
								'loading' => 'lazy',
							] )
						);
					} else {
						printf(
							'<span class="dashicons dashicons-format-image" style="opacity:.3;font-size:20px;" aria-hidden="true"></span><span class="screen-reader-text">%s</span>',
							esc_html__( 'No featured image set', 'mia' )
						);
					}
				},
				10,
				2
			);

			// 3. Make sortable.
			add_filter(
				"manage_edit-{$type}_sortable_columns",
				static fn( $cols ) => $cols + [ 'thumb' => 'has_thumb' ]
			);
		}
	}

	// 4. Alter main query when the user clicks to sort.
	add_action(
		'pre_get_posts',
		static function ( $q ) {
			if ( ! is_admin() || ! $q->is_main_query() ) {
				return;
			}
			if ( 'has_thumb' === $q->get( 'orderby' ) ) {
				$q->set( 'meta_key', '_thumbnail_id' );
				$q->set( 'orderby', 'meta_value_num' );
				$q->set( 'order', $q->get( 'order' ) ?: 'DESC' );
			}
		}
	);

	/* ---------------------------------------------------------------------
	 * Custom bulk action: remove featured image.
	 * (Setting a thumbnail from Media Library involves JS; omit here.)
	 * ------------------------------------------------------------------ */
	$screen_ids = array_map(
		static fn( $pt ) => 'edit-' . $pt,
		array_filter(
			get_post_types( [ 'public' => true ] ),
			static fn( $pt ) => post_type_supports( $pt, 'thumbnail' )
		)
	);

	foreach ( $screen_ids as $screen ) {
		add_filter(
			"bulk_actions-{$screen}",
			static fn( $acts ) => $acts + [ 'remove_thumb' => __( 'Remove Featured Image', 'mia' ) ]
		);

		add_filter(
			"handle_bulk_actions-{$screen}",
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
					[ 'thumbs_removed' => count( $ids ) ],
					$redirect
				);
			},
			10,
			3
		);
	}

	add_action(
		'admin_notices',
		static function () {
			if ( empty( $_GET['thumbs_removed'] ) ) {
				return;
			}
			$count = (int) $_GET['thumbs_removed'];
			printf(
				'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
				esc_html(
					sprintf(
						/* translators: %s = number of posts */
						_n( 'Removed featured image from %s item.', 'Removed featured images from %s items.', $count, 'mia' ),
						number_format_i18n( $count )
					)
				)
			);
		}
	);

	/* ---------------------------------------------------------------------
	 * A tiny dashicon‑inline style so we avoid an extra stylesheet request.
	 * Only load on post list screens where the column appears.
	 * ------------------------------------------------------------------ */
	add_action( 'admin_enqueue_scripts', static function( $hook ) {
		if ( 'edit.php' === $hook ) {
			wp_add_inline_style(
				'dashicons',
				'.column-thumb { width:60px;text-align:center; }'
			);
		}
	} );
}
