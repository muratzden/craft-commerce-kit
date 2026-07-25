<?php
/**
 * Gutenberg integration loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_usp_block' ) ) {
        /**
         * Render the USP block through the existing component renderer.
         *
         * @param array $attributes Block attributes.
         * @return string
         */
        function cck_render_usp_block( $attributes ) {
                $attributes         = is_array( $attributes ) ? $attributes : array();
                $wrapper_attributes = get_block_wrapper_attributes();

                return sprintf(
                        '<div %1$s>%2$s</div>',
                        $wrapper_attributes,
                        cck_render_component( 'usp', $attributes )
                );
        }
}

if ( ! function_exists( 'cck_register_gutenberg_blocks' ) ) {
        /**
         * Register Craft Commerce Kit Gutenberg blocks.
         *
         * @return void
         */
        function cck_register_gutenberg_blocks() {
                register_block_type(
                        CCK_PLUGIN_DIR . 'blocks/usp',
                        array(
                                'render_callback' => 'cck_render_usp_block',
                        )
                );
        }
}

add_action( 'init', 'cck_register_gutenberg_blocks' );
