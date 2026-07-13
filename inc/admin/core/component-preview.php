<?php
/**
 * Component preview helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_preview_url' ) ) {
	/**
	 * Get the admin URL for a component preview.
	 *
	 * @param string $component_id Component ID.
	 * @return string
	 */
	function cck_get_component_preview_url( $component_id ) {
		$component_id = sanitize_key( $component_id );

		if ( '' === $component_id ) {
			return '';
		}

		return add_query_arg(
			array(
				'page'      => 'craft-commerce-kit-component-preview',
				'component' => $component_id,
			),
			admin_url( 'admin.php' )
		);
	}
}

if ( ! function_exists( 'cck_get_component_preview_request_id' ) ) {
	/**
	 * Get the requested component ID from the current admin request.
	 *
	 * @return string
	 */
	function cck_get_component_preview_request_id() {
		return isset( $_GET['component'] ) ? sanitize_key( wp_unslash( $_GET['component'] ) ) : '';
	}
}

if ( ! function_exists( 'cck_get_component_preview_shortcode_examples' ) ) {
	/**
	 * Get shortcode examples for a component.
	 *
	 * @param string $component_id Component ID.
	 * @return array
	 */
	function cck_get_component_preview_shortcode_examples( $component_id ) {
		$component_id = sanitize_key( $component_id );

		if ( '' === $component_id ) {
			return array();
		}

		$direct_shortcodes = array(
			'hero'            => 'cck_hero',
			'cta'             => 'cck_cta',
			'trust-block'     => 'cck_trust_block',
			'section-title'   => 'cck_section_title',
			'image-text'      => 'cck_image_text',
			'collection-grid' => 'cck_collection_grid',
		);
		$examples       = array();

		if ( isset( $direct_shortcodes[ $component_id ] ) ) {
			$examples[] = '[' . $direct_shortcodes[ $component_id ] . ']';
		}

		$examples[] = '[cck_component id="' . $component_id . '"]';

		return array_values( array_unique( $examples ) );
	}
}

if ( ! function_exists( 'cck_get_component_preview_experience_usage' ) ) {
	/**
	 * Determine which experiences use a component.
	 *
	 * @param string $component_id Component ID.
	 * @return array
	 */
	function cck_get_component_preview_experience_usage( $component_id ) {
		$component_id = sanitize_key( $component_id );

		if ( '' === $component_id || ! function_exists( 'cck_get_experiences' ) ) {
			return array();
		}

		$matches = array();
		$aliases = function_exists( 'cck_get_admin_component_alias_map' ) ? cck_get_admin_component_alias_map() : array();
		$aliased_component_ids = array( $component_id );

		foreach ( $aliases as $official_id => $alias_list ) {
			$official_id = sanitize_key( $official_id );
			$alias_list  = is_array( $alias_list ) ? $alias_list : array();

			if ( $official_id === $component_id ) {
				$aliased_component_ids = array_merge( $aliased_component_ids, $alias_list );
				break;
			}

			if ( in_array( $component_id, array_map( 'sanitize_key', $alias_list ), true ) ) {
				$aliased_component_ids[] = $official_id;
				break;
			}
		}

		$aliased_component_ids = array_values(
			array_unique(
				array_filter(
					array_map( 'sanitize_key', $aliased_component_ids )
				)
			)
		);

		foreach ( cck_get_experiences() as $experience_id => $experience ) {
			$experience_id = sanitize_key( $experience_id );

			if ( '' === $experience_id ) {
				continue;
			}

			$sections = function_exists( 'cck_get_experience_layout_sections' ) ? cck_get_experience_layout_sections( $experience_id ) : array();

			foreach ( $sections as $section_id ) {
				$section_file = CCK_PLUGIN_DIR . 'packs/' . $experience_id . '/sections/' . sanitize_key( $section_id ) . '.php';

				if ( ! file_exists( $section_file ) ) {
					continue;
				}

				$section = require $section_file;

				if ( ! is_array( $section ) || empty( $section['component'] ) ) {
					continue;
				}

				$section_component = sanitize_key( cck_array_get( $section, 'component', '' ) );
				$manifest          = function_exists( 'cck_get_component_manifest' ) ? cck_get_component_manifest( $section_component ) : array();
				$resolved_id       = is_array( $manifest ) && ! empty( $manifest['id'] ) ? sanitize_key( $manifest['id'] ) : $section_component;

				if ( in_array( $resolved_id, $aliased_component_ids, true ) ) {
					$matches[ $experience_id ] = ucwords( str_replace( array( '-', '_' ), ' ', $experience_id ) );
					break;
				}
			}
		}

		return $matches;
	}
}

if ( ! function_exists( 'cck_render_component_preview_markup' ) ) {
	/**
	 * Render a production component preview safely.
	 *
	 * @param string $component_id Component ID.
	 * @param array  $defaults     Default attributes.
	 * @return array
	 */
	function cck_render_component_preview_markup( $component_id, array $defaults = array() ) {
		$component_id = sanitize_key( $component_id );

		if ( '' === $component_id || ! function_exists( 'cck_render_component' ) ) {
			return array(
				'success' => false,
				'html'    => '',
				'error'   => __( 'Preview unavailable.', 'craft-commerce-kit' ),
			);
		}

		$definition = array(
			'component'  => $component_id,
			'attributes' => $defaults,
		);

		$error_message = '';
		$previous_handler = set_error_handler(
			static function ( $severity, $message ) use ( &$error_message ) {
				if ( ! ( error_reporting() & $severity ) ) {
					return false;
				}

				$error_message = is_string( $message ) ? $message : '';

				throw new RuntimeException( $error_message );
			}
		);

		try {
			$html = cck_render_component( $definition );
		} catch ( Throwable $throwable ) {
			$html          = '';
			$error_message = $throwable->getMessage();
		}

		if ( null !== $previous_handler ) {
			restore_error_handler();
		}

		$html = is_string( $html ) ? trim( $html ) : '';

		if ( '' === $html ) {
			return array(
				'success' => false,
				'html'    => '',
				'error'   => '' !== $error_message ? $error_message : __( 'Preview unavailable.', 'craft-commerce-kit' ),
			);
		}

		return array(
			'success' => true,
			'html'    => $html,
			'error'   => '',
		);
	}
}

if ( ! function_exists( 'cck_get_component_preview_data' ) ) {
	/**
	 * Build preview data for a component.
	 *
	 * @param string $component_id Component ID.
	 * @return array
	 */
	function cck_get_component_preview_data( $component_id ) {
		$component_id = sanitize_key( $component_id );
		$manifest     = function_exists( 'cck_get_component_manifest' ) ? cck_get_component_manifest( $component_id ) : array();
		$is_valid     = is_array( $manifest ) && ! empty( $manifest['id'] ) && $component_id === sanitize_key( $manifest['id'] );

		if ( ! $is_valid ) {
			return array(
				'component_id'        => $component_id,
				'is_valid'           => false,
				'notice'             => __( 'Unknown component.', 'craft-commerce-kit' ),
				'component_name'     => '',
				'component_id_label' => $component_id,
				'callback'           => '',
				'callback_callable'  => false,
				'supports'           => array(),
				'defaults'           => array(),
				'schema'             => array(),
				'shortcodes'         => cck_get_component_preview_shortcode_examples( $component_id ),
				'experience_usage'   => array(),
				'preview'            => array(
					'success' => false,
					'html'    => '',
					'error'   => __( 'Preview unavailable.', 'craft-commerce-kit' ),
				),
			);
		}

		$defaults = function_exists( 'cck_get_component_defaults' ) ? cck_get_component_defaults( $component_id ) : array();
		$schema    = cck_manifest_get( $manifest, 'schema', array() );
		$callback  = cck_manifest_get( $manifest, 'callback', '' );
		$callback  = is_string( $callback ) ? $callback : '';
		$preview   = cck_render_component_preview_markup( $component_id, $defaults );

		return array(
			'component_id'      => $component_id,
			'is_valid'         => true,
			'notice'           => '',
			'component_name'   => cck_manifest_get( $manifest, 'name', $component_id ),
			'component_id_label' => $component_id,
			'description'      => cck_manifest_get( $manifest, 'description', '' ),
			'version'          => cck_manifest_get( $manifest, 'version', '' ),
			'callback'         => $callback,
			'callback_callable' => is_callable( $callback ),
			'supports'         => cck_manifest_get( $manifest, 'supports', array() ),
			'defaults'         => is_array( $defaults ) ? $defaults : array(),
			'schema'           => is_array( $schema ) ? $schema : array(),
			'shortcodes'       => cck_get_component_preview_shortcode_examples( $component_id ),
			'experience_usage' => cck_get_component_preview_experience_usage( $component_id ),
			'preview'          => $preview,
			'manifest'         => $manifest,
		);
	}
}
