<?php
/**
 * Experience publishing helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_published_experiences_option_key' ) ) {
	/**
	 * Get the option key used to store published experience page mappings.
	 *
	 * @return string
	 */
	function cck_get_published_experiences_option_key() {
		return 'cck_published_experiences';
	}
}

if ( ! function_exists( 'cck_get_published_experiences' ) ) {
	/**
	 * Get the published experience registry.
	 *
	 * @return array
	 */
	function cck_get_published_experiences() {
		$stored     = get_option( cck_get_published_experiences_option_key(), array() );
		$normalized = array();

		if ( ! is_array( $stored ) ) {
			return array();
		}

		foreach ( $stored as $experience_id => $entry ) {
			$experience_id = sanitize_key( $experience_id );

			if ( '' === $experience_id ) {
				continue;
			}

			if ( is_numeric( $entry ) ) {
				$entry = array(
					'page_id' => absint( $entry ),
				);
			}

			if ( ! is_array( $entry ) ) {
				continue;
			}

			$page_id = absint( cck_array_get( $entry, 'page_id', 0 ) );

			if ( $page_id <= 0 ) {
				continue;
			}

			$published_at = absint( cck_array_get( $entry, 'published_at', 0 ) );
			$updated_at   = absint( cck_array_get( $entry, 'updated_at', 0 ) );
			$page         = get_post( $page_id );

			if ( ! $page instanceof WP_Post || 'page' !== $page->post_type ) {
				continue;
			}

			$normalized[ $experience_id ] = array(
				'page_id'      => $page_id,
				'page_title'   => $page->post_title,
				'page_status'   => sanitize_key( $page->post_status ),
				'page_url'     => get_permalink( $page_id ),
				'published_at' => $published_at > 0 ? $published_at : current_time( 'timestamp' ),
				'updated_at'   => $updated_at > 0 ? $updated_at : current_time( 'timestamp' ),
			);
		}

		return $normalized;
	}
}

if ( ! function_exists( 'cck_get_published_experience' ) ) {
	/**
	 * Get a single published experience entry.
	 *
	 * @param string $experience_id Experience slug.
	 * @return array
	 */
	function cck_get_published_experience( $experience_id ) {
		$experience_id = sanitize_key( $experience_id );

		if ( '' === $experience_id ) {
			return array();
		}

		$published = cck_get_published_experiences();

		return isset( $published[ $experience_id ] ) ? $published[ $experience_id ] : array();
	}
}

if ( ! function_exists( 'cck_get_published_experience_page_id' ) ) {
	/**
	 * Get the published page ID for an experience.
	 *
	 * @param string $experience_id Experience slug.
	 * @return int
	 */
	function cck_get_published_experience_page_id( $experience_id ) {
		$published = cck_get_published_experience( $experience_id );

		return absint( cck_array_get( $published, 'page_id', 0 ) );
	}
}

if ( ! function_exists( 'cck_get_published_experience_page_url' ) ) {
	/**
	 * Get the published page URL for an experience.
	 *
	 * @param string $experience_id Experience slug.
	 * @return string
	 */
	function cck_get_published_experience_page_url( $experience_id ) {
		$published = cck_get_published_experience( $experience_id );

		return isset( $published['page_url'] ) ? esc_url_raw( $published['page_url'] ) : '';
	}
}

if ( ! function_exists( 'cck_get_experience_preview_url' ) ) {
	/**
	 * Get the hidden admin preview URL for an experience.
	 *
	 * @param string $experience_id Experience slug.
	 * @return string
	 */
	function cck_get_experience_preview_url( $experience_id ) {
		$experience_id = sanitize_key( $experience_id );

		if ( '' === $experience_id ) {
			return '';
		}

		return add_query_arg(
			array(
				'page'       => 'craft-commerce-kit-experience-preview',
				'experience' => $experience_id,
			),
			admin_url( 'admin.php' )
		);
	}
}

if ( ! function_exists( 'cck_get_experience_publish_state' ) ) {
	/**
	 * Get the publication state for an experience.
	 *
	 * @param string $experience_id Experience slug.
	 * @return array
	 */
	function cck_get_experience_publish_state( $experience_id ) {
		$experience_id = sanitize_key( $experience_id );
		$published     = cck_get_published_experience( $experience_id );
		$page_id       = absint( cck_array_get( $published, 'page_id', 0 ) );
		$page          = $page_id > 0 ? get_post( $page_id ) : null;
		$page_url      = $page instanceof WP_Post ? get_permalink( $page_id ) : '';
		$is_published  = $page instanceof WP_Post && 'page' === $page->post_type && 'publish' === $page->post_status;
		$show_on_front = get_option( 'show_on_front', 'posts' );
		$homepage_id   = absint( get_option( 'page_on_front', 0 ) );
		$is_homepage   = $is_published && 'page' === $show_on_front && $homepage_id === $page_id;
		$status        = $is_homepage ? __( 'Homepage', 'craft-commerce-kit' ) : ( $is_published ? __( 'Published', 'craft-commerce-kit' ) : __( 'Draft', 'craft-commerce-kit' ) );

		return array(
			'experience_id' => $experience_id,
			'page_id'       => $page_id,
			'page_title'    => $page instanceof WP_Post ? $page->post_title : '',
			'page_url'      => $page_url,
			'is_published'  => $is_published,
			'is_homepage'   => $is_homepage,
			'status'        => $status,
			'published_at'  => absint( cck_array_get( $published, 'published_at', 0 ) ),
			'updated_at'    => absint( cck_array_get( $published, 'updated_at', 0 ) ),
		);
	}
}

if ( ! function_exists( 'cck_get_published_experience_homepage_experience_id' ) ) {
	/**
	 * Get the experience ID that currently owns the homepage, if any.
	 *
	 * @return string
	 */
	function cck_get_published_experience_homepage_experience_id() {
		$published  = cck_get_published_experiences();
		$homepage_id = absint( get_option( 'page_on_front', 0 ) );

		if ( $homepage_id <= 0 || 'page' !== get_option( 'show_on_front', 'posts' ) ) {
			return '';
		}

		foreach ( $published as $experience_id => $entry ) {
			if ( absint( cck_array_get( $entry, 'page_id', 0 ) ) === $homepage_id ) {
				return sanitize_key( $experience_id );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'cck_get_experience_publish_overview_data' ) ) {
	/**
	 * Get overview data for published experiences.
	 *
	 * @return array
	 */
	function cck_get_experience_publish_overview_data() {
		$published          = cck_get_published_experiences();
		$homepage_experience = cck_get_published_experience_homepage_experience_id();
		$homepage_page_id   = absint( get_option( 'page_on_front', 0 ) );
		$homepage_label     = __( 'Not set', 'craft-commerce-kit' );
		$homepage_page_title = '';
		$last_published_id  = '';
		$last_published_ts  = 0;
		$last_published_label = __( 'Not published yet', 'craft-commerce-kit' );

		if ( $homepage_page_id > 0 ) {
			$page = get_post( $homepage_page_id );

			if ( $page instanceof WP_Post ) {
				$homepage_page_title = $page->post_title;
				$homepage_label      = $homepage_experience;

				if ( '' === $homepage_label ) {
					$homepage_label = $page->post_title;
				}
			}
		}

		foreach ( $published as $experience_id => $entry ) {
			$timestamp = max(
				absint( cck_array_get( $entry, 'updated_at', 0 ) ),
				absint( cck_array_get( $entry, 'published_at', 0 ) )
			);

			if ( $timestamp > $last_published_ts ) {
				$last_published_ts  = $timestamp;
				$last_published_id  = sanitize_key( $experience_id );
				$last_published_label = trim( sprintf(
					'%1$s%s',
					ucwords( str_replace( array( '-', '_' ), ' ', sanitize_key( $experience_id ) ) ),
					$timestamp > 0 ? ' · ' . wp_date( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ), $timestamp ) : ''
				) );
			}
		}

		return array(
			'published_experiences'   => count( $published ),
			'homepage_experience_id'  => $homepage_experience,
			'homepage_label'          => '' !== $homepage_experience ? ucwords( str_replace( array( '-', '_' ), ' ', $homepage_experience ) ) : $homepage_label,
			'homepage_page_title'     => $homepage_page_title,
			'last_published_id'       => $last_published_id,
			'last_published_label'    => $last_published_label,
			'last_published_timestamp'=> $last_published_ts,
		);
	}
}

if ( ! function_exists( 'cck_get_experience_publish_page_title' ) ) {
	/**
	 * Get a safe page title for a published experience.
	 *
	 * @param string $experience_id Experience slug.
	 * @return string
	 */
	function cck_get_experience_publish_page_title( $experience_id ) {
		$experience_id = sanitize_key( $experience_id );
		$definition    = function_exists( 'cck_get_experience_definition' ) ? cck_get_experience_definition( $experience_id ) : array();
		$name          = '';

		if ( ! empty( $definition['name'] ) ) {
			$name = sanitize_text_field( $definition['name'] );
		}

		if ( '' === $name ) {
			$name = ucwords( str_replace( array( '-', '_' ), ' ', $experience_id ) );
		}

		$name = preg_replace( '/\s*Experience$/i', '', $name );

		return trim( $name );
	}
}

if ( ! function_exists( 'cck_publish_experience' ) ) {
	/**
	 * Publish or update an experience page.
	 *
	 * @param string $experience_id Experience slug.
	 * @return array
	 */
	function cck_publish_experience( $experience_id ) {
		$experience_id = sanitize_key( $experience_id );

		if ( '' === $experience_id || ! function_exists( 'cck_get_experience_definition' ) ) {
			return array(
				'success' => false,
				'error'   => __( 'Unknown experience.', 'craft-commerce-kit' ),
			);
		}

		$definition = cck_get_experience_definition( $experience_id );

		if ( empty( $definition ) ) {
			return array(
				'success' => false,
				'error'   => __( 'Unknown experience.', 'craft-commerce-kit' ),
			);
		}

		$page_title = cck_get_experience_publish_page_title( $experience_id );
		$page_id    = cck_get_published_experience_page_id( $experience_id );
		$page       = $page_id > 0 ? get_post( $page_id ) : null;
		$slug       = $experience_id;
		$content    = sprintf( '[cck_experience id="%s"]', $experience_id );
		$args       = array(
			'post_title'   => $page_title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_type'    => 'page',
			'post_status'  => 'publish',
		);

		if ( $page instanceof WP_Post && 'page' === $page->post_type ) {
			$args['ID'] = $page->ID;
			$result     = wp_update_post( $args, true );
			$page_id    = $page->ID;
		} else {
			$existing_page = get_page_by_path( $slug, OBJECT, 'page' );

			if ( $existing_page instanceof WP_Post ) {
				$args['ID'] = $existing_page->ID;
				$result     = wp_update_post( $args, true );
				$page_id    = $existing_page->ID;
			} else {
				$result  = wp_insert_post( $args, true );
				$page_id = is_wp_error( $result ) ? 0 : absint( $result );
			}
		}

		if ( is_wp_error( $result ) ) {
			return array(
				'success' => false,
				'error'   => $result->get_error_message(),
			);
		}

		if ( $page_id <= 0 ) {
			return array(
				'success' => false,
				'error'   => __( 'Publish failed.', 'craft-commerce-kit' ),
			);
		}

		update_post_meta( $page_id, '_cck_experience_id', $experience_id );

		$now      = current_time( 'timestamp' );
		$existing = cck_get_published_experiences();

		$existing[ $experience_id ] = array(
			'page_id'      => $page_id,
			'page_title'   => $page_title,
			'page_status'  => 'publish',
			'page_url'     => get_permalink( $page_id ),
			'published_at' => isset( $existing[ $experience_id ]['published_at'] ) ? absint( $existing[ $experience_id ]['published_at'] ) : $now,
			'updated_at'   => $now,
		);

		update_option( cck_get_published_experiences_option_key(), $existing, false );

		return array(
			'success'    => true,
			'experience' => $experience_id,
			'page_id'    => $page_id,
			'page_url'   => get_permalink( $page_id ),
			'created'    => ! $page instanceof WP_Post,
			'updated'    => true,
		);
	}
}

if ( ! function_exists( 'cck_set_homepage_experience' ) ) {
	/**
	 * Set an experience page as the homepage when the site already uses a static page front.
	 *
	 * @param string $experience_id Experience slug.
	 * @return array
	 */
	function cck_set_homepage_experience( $experience_id ) {
		$experience_id = sanitize_key( $experience_id );
		$publish       = cck_publish_experience( $experience_id );

		if ( empty( $publish['success'] ) ) {
			return $publish;
		}

		if ( 'page' !== get_option( 'show_on_front', 'posts' ) ) {
			return array(
				'success' => false,
				'error'   => __( 'Homepage changes are available only when the site already uses a static front page.', 'craft-commerce-kit' ),
			);
		}

		update_option( 'page_on_front', absint( $publish['page_id'] ) );

		return array(
			'success'    => true,
			'experience' => $experience_id,
			'page_id'    => absint( $publish['page_id'] ),
			'page_url'   => isset( $publish['page_url'] ) ? $publish['page_url'] : get_permalink( absint( $publish['page_id'] ) ),
			'homepage'   => true,
		);
	}
}

if ( ! function_exists( 'cck_get_experience_publish_redirect_url' ) ) {
	/**
	 * Get the redirect URL after an experience publish action.
	 *
	 * @param string $experience_id Experience slug.
	 * @return string
	 */
	function cck_get_experience_publish_redirect_url( $experience_id = '' ) {
		$args = array(
			'page' => 'craft-commerce-kit-experiences',
		);

		$experience_id = sanitize_key( $experience_id );

		if ( '' !== $experience_id ) {
			$args['experience'] = $experience_id;
		}

		return add_query_arg( $args, admin_url( 'admin.php' ) );
	}
}

if ( ! function_exists( 'cck_handle_experience_publish_request' ) ) {
	/**
	 * Handle experience publish POST requests.
	 *
	 * @return void
	 */
	function cck_handle_experience_publish_request() {
		cck_require_admin_capability();

		$experience_id = isset( $_POST['experience_id'] ) ? sanitize_key( wp_unslash( $_POST['experience_id'] ) ) : '';
		$nonce_action   = 'cck_publish_experience_' . $experience_id;

		check_admin_referer( $nonce_action );

		$result   = cck_publish_experience( $experience_id );
		$args     = array(
			'page' => 'craft-commerce-kit-experiences',
		);
		$notice   = ! empty( $result['success'] ) ? 'published' : 'publish_error';

		if ( '' !== $experience_id ) {
			$args['experience'] = $experience_id;
		}

		$args['cck_notice'] = $notice;

		if ( ! empty( $result['success'] ) && ! empty( $result['page_id'] ) ) {
			$args['page_id'] = absint( $result['page_id'] );
		}

		wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
		exit;
	}
}

if ( ! function_exists( 'cck_handle_experience_homepage_request' ) ) {
	/**
	 * Handle homepage update requests.
	 *
	 * @return void
	 */
	function cck_handle_experience_homepage_request() {
		cck_require_admin_capability();

		$experience_id = isset( $_POST['experience_id'] ) ? sanitize_key( wp_unslash( $_POST['experience_id'] ) ) : '';
		$nonce_action   = 'cck_set_homepage_experience_' . $experience_id;

		check_admin_referer( $nonce_action );

		$result = cck_set_homepage_experience( $experience_id );
		$args   = array(
			'page' => 'craft-commerce-kit-experiences',
		);

		if ( '' !== $experience_id ) {
			$args['experience'] = $experience_id;
		}

		$args['cck_notice'] = ! empty( $result['success'] ) ? 'homepage_set' : 'homepage_error';

		if ( ! empty( $result['success'] ) && ! empty( $result['page_id'] ) ) {
			$args['page_id'] = absint( $result['page_id'] );
		}

		wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
		exit;
	}
}

add_action( 'admin_post_cck_publish_experience', 'cck_handle_experience_publish_request' );
add_action( 'admin_post_cck_set_homepage_experience', 'cck_handle_experience_homepage_request' );
