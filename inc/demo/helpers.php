<?php
/**
 * Demo catalog helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_demo_catalog_path' ) ) {
	function cck_demo_catalog_path( $file ) {
		$file = ltrim( str_replace( array( '\\', '..' ), array( '/', '' ), sanitize_text_field( cck_to_string( $file ) ) ), '/' );

		if ( '' === $file ) {
			return '';
		}

		return CCK_PLUGIN_DIR . 'assets/demo/catalog/' . $file;
	}
}

if ( ! function_exists( 'cck_demo_catalog_url' ) ) {
	function cck_demo_catalog_url( $file ) {
		$file = ltrim( str_replace( array( '\\', '..' ), array( '/', '' ), sanitize_text_field( cck_to_string( $file ) ) ), '/' );

		if ( '' === $file ) {
			return '';
		}

		return CCK_PLUGIN_URL . 'assets/demo/catalog/' . $file;
	}
}

if ( ! function_exists( 'cck_demo_catalog_files' ) ) {
	function cck_demo_catalog_files() {
		return array(
			'executive-messenger-bag.png',
			'classic-briefcase.png',
			'leather-laptop-sleeve.png',
			'everyday-wallet.png',
			'slim-card-holder.png',
			'artisan-guitar-strap.png',
			'travel-organizer.png',
			'signature-journal-cover.png',
		);
	}
}

if ( ! function_exists( 'cck_demo_catalog_find_attachment_id' ) ) {
	function cck_demo_catalog_find_attachment_id( $filename ) {
		static $cache = array();
		$filename = sanitize_file_name( cck_to_string( $filename ) );

		if ( '' === $filename ) {
			return 0;
		}

		if ( isset( $cache[ $filename ] ) ) {
			return absint( $cache[ $filename ] );
		}

		$query = new WP_Query(
			array(
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_query'             => array(
					array(
						'key'   => '_cck_demo_asset',
						'value' => $filename,
					),
				),
			)
		);

		$cache[ $filename ] = ! empty( $query->posts[0] ) ? absint( $query->posts[0] ) : 0;

		return absint( $cache[ $filename ] );
	}
}

if ( ! function_exists( 'cck_demo_catalog_get_or_create_term_id' ) ) {
	function cck_demo_catalog_get_or_create_term_id( $taxonomy, $name, $slug = '' ) {
		$taxonomy = sanitize_key( $taxonomy );
		$name     = sanitize_text_field( cck_to_string( $name ) );
		$slug     = '' !== $slug ? sanitize_title( $slug ) : sanitize_title( $name );

		if ( '' === $taxonomy || '' === $name ) {
			return 0;
		}

		$existing = term_exists( $slug, $taxonomy );

		if ( $existing && ! is_wp_error( $existing ) ) {
			return absint( is_array( $existing ) ? $existing['term_id'] : $existing );
		}

		$term = wp_insert_term(
			$name,
			$taxonomy,
			array(
				'slug' => $slug,
			)
		);

		if ( is_wp_error( $term ) ) {
			$existing = get_term_by( 'slug', $slug, $taxonomy );

			return $existing ? absint( $existing->term_id ) : 0;
		}

		return absint( $term['term_id'] );
	}
}

if ( ! function_exists( 'cck_demo_catalog_collect_term_ids' ) ) {
	function cck_demo_catalog_collect_term_ids( $taxonomy, array $terms ) {
		$ids = array();

		foreach ( $terms as $term ) {
			if ( is_array( $term ) ) {
				$name = isset( $term['name'] ) ? $term['name'] : '';
				$slug = isset( $term['slug'] ) ? $term['slug'] : '';
			} else {
				$name = $term;
				$slug = '';
			}

			$term_id = cck_demo_catalog_get_or_create_term_id( $taxonomy, $name, $slug );

			if ( $term_id ) {
				$ids[] = $term_id;
			}
		}

		return array_values( array_unique( array_map( 'absint', $ids ) ) );
	}
}

if ( ! function_exists( 'cck_demo_catalog_attachment_file' ) ) {
	function cck_demo_catalog_attachment_file( $filename ) {
		$filename = sanitize_file_name( cck_to_string( $filename ) );

		if ( '' === $filename ) {
			return '';
		}

		$path = cck_demo_catalog_path( $filename );

		return file_exists( $path ) ? $path : '';
	}
}

if ( ! function_exists( 'cck_demo_catalog_import_attachment' ) ) {
	function cck_demo_catalog_import_attachment( $filename, $title ) {
		$filename = sanitize_file_name( cck_to_string( $filename ) );
		$title    = sanitize_text_field( cck_to_string( $title ) );

		if ( '' === $filename ) {
			return 0;
		}

		$existing = cck_demo_catalog_find_attachment_id( $filename );

		if ( $existing ) {
			if ( '' !== $title ) {
				update_post_meta( $existing, '_wp_attachment_image_alt', $title );
				wp_update_post(
					array(
						'ID'         => $existing,
						'post_title' => $title,
					)
				);
			}

			return $existing;
		}

		$file = cck_demo_catalog_attachment_file( $filename );

		if ( '' === $file || ! file_exists( $file ) ) {
			return 0;
		}

		$upload_dir = wp_upload_dir();

		if ( ! empty( $upload_dir['error'] ) || empty( $upload_dir['path'] ) ) {
			return 0;
		}

		$target = trailingslashit( $upload_dir['path'] ) . $filename;

		if ( ! file_exists( $target ) ) {
			copy( $file, $target );
		}

		$filetype   = wp_check_filetype( $target );
		$attachment = array(
			'post_mime_type' => isset( $filetype['type'] ) ? $filetype['type'] : 'image/png',
			'post_title'     => '' !== $title ? $title : pathinfo( $filename, PATHINFO_FILENAME ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attachment_id = wp_insert_attachment( $attachment, $target );

		if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';

		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $target );
		wp_update_attachment_metadata( $attachment_id, $attachment_data );
		update_post_meta( $attachment_id, '_cck_demo_asset', $filename );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

		return absint( $attachment_id );
	}
}

