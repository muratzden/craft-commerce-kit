<?php
/**
 * Gutenberg integration loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

require_once CCK_PLUGIN_DIR . 'inc/gutenberg/component-adapter.php';

if ( ! function_exists( 'cck_register_gutenberg_editor_assets' ) ) {
	/**
	 * Register shared Gutenberg editor assets.
	 *
	 * @return void
	 */
	function cck_register_gutenberg_editor_assets() {
		$script_path = CCK_PLUGIN_DIR . 'assets/js/gutenberg/block-editor-controls.js';

		wp_register_script(
			'cck-block-editor-controls',
			CCK_PLUGIN_URL . 'assets/js/gutenberg/block-editor-controls.js',
			array(
				'wp-element',
				'wp-components',
				'wp-block-editor',
			),
			file_exists( $script_path )
				? filemtime( $script_path )
				: CCK_VERSION,
			true
		);
	}
}

add_action( 'init', 'cck_register_gutenberg_editor_assets', 5 );

if ( ! function_exists( 'cck_enqueue_gutenberg_editor_assets' ) ) {
	/**
	 * Load shared component styles, active design tokens and
	 * WordPress media assets in the block editor.
	 *
	 * @return void
	 */
	function cck_enqueue_gutenberg_editor_assets() {
		$style_path = CCK_PLUGIN_DIR . 'assets/css/cck.css';

		wp_enqueue_style(
			'cck-components',
			CCK_PLUGIN_URL . 'assets/css/cck.css',
			array(),
			file_exists( $style_path )
				? filemtime( $style_path )
				: CCK_VERSION
		);

		if ( function_exists( 'cck_get_design_tokens_css' ) ) {
			wp_add_inline_style(
				'cck-components',
				cck_get_design_tokens_css( '.editor-styles-wrapper' )
			);
		}

		wp_enqueue_media();
	}
}

add_action(
	'enqueue_block_editor_assets',
	'cck_enqueue_gutenberg_editor_assets'
);

if ( ! function_exists( 'cck_register_gutenberg_blocks' ) ) {
	/**
	 * Register Craft Commerce Kit Gutenberg blocks.
	 *
	 * @return void
	 */
	function cck_register_gutenberg_blocks() {
		$blocks = array(
			'usp'           => CCK_PLUGIN_DIR . 'blocks/usp',
			'section-title' => CCK_PLUGIN_DIR . 'blocks/section-title',
			'cta'           => CCK_PLUGIN_DIR . 'blocks/cta',
			'image-text'    => CCK_PLUGIN_DIR . 'blocks/image-text',
			'hero'          => CCK_PLUGIN_DIR . 'blocks/hero',
		);

		foreach ( $blocks as $component_id => $block_path ) {
			if ( ! file_exists( $block_path . '/block.json' ) ) {
				continue;
			}

			$block_type = register_block_type(
				$block_path,
				array(
					'attributes'      => cck_get_block_attributes_from_manifest(
						$component_id
					),
					'render_callback' => static function ( $attributes ) use ( $component_id ) {
						return cck_render_component_block(
							$component_id,
							$attributes
						);
					},
				)
			);

			if (
				$block_type instanceof WP_Block_Type &&
				! empty( $block_type->editor_script_handles )
			) {
				wp_add_inline_script(
					$block_type->editor_script_handles[0],
					'window.cckBlockEditorSettings = window.cckBlockEditorSettings || {};'
						. 'window.cckBlockEditorSettings['
						. wp_json_encode( $block_type->name )
						. '] = '
						. wp_json_encode(
							cck_get_block_editor_settings_from_manifest(
								$component_id
							)
						)
						. ';',
					'before'
				);
			}
		}
	}
}

add_action( 'init', 'cck_register_gutenberg_blocks' );
