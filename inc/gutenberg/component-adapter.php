<?php
/**
 * Gutenberg component adapter.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_block_attribute_type' ) ) {
        /**
         * Convert a component setting type to a Gutenberg attribute type.
         *
         * @param string $setting_type Component setting type.
         * @return string
         */
        function cck_get_block_attribute_type( $setting_type ) {
                $setting_type = sanitize_key( $setting_type );

                if ( 'number' === $setting_type ) {
                        return 'number';
                }

                if ( 'checkbox' === $setting_type ) {
                        return 'boolean';
                }

                return 'string';
        }
}

if ( ! function_exists( 'cck_get_block_attributes_from_manifest' ) ) {
        /**
         * Build Gutenberg block attributes from component manifest settings.
         *
         * @param string $component_id Component ID.
         * @return array
         */
        function cck_get_block_attributes_from_manifest( $component_id ) {
                $attributes = array();
                $settings   = cck_get_component_settings( $component_id );

                foreach ( $settings as $setting_id => $setting ) {
                        $attributes[ $setting_id ] = array(
                                'type'    => cck_get_block_attribute_type( cck_array_get( $setting, 'type', 'text' ) ),
                                'default' => cck_array_get( $setting, 'default', '' ),
                        );
                }

                return $attributes;
        }
}

if ( ! function_exists( 'cck_get_block_editor_settings_from_manifest' ) ) {
        /**
         * Build block editor control definitions from component settings.
         *
         * @param string $component_id Component ID.
         * @return array
         */
        function cck_get_block_editor_settings_from_manifest( $component_id ) {
                $controls = array();
                $settings = cck_get_component_settings( $component_id );

                foreach ( $settings as $setting_id => $setting ) {
                        $controls[ $setting_id ] = array(
                                'type'        => cck_array_get( $setting, 'type', 'text' ),
                                'label'       => cck_array_get( $setting, 'label', $setting_id ),
                                'description' => cck_array_get( $setting, 'description', '' ),
                                'default'     => cck_array_get( $setting, 'default', '' ),
                                'required'    => cck_to_bool( cck_array_get( $setting, 'required', false ) ),
                                'options'     => cck_array_get( $setting, 'options', array() ),
                        );
                }

                return $controls;
        }
}

if ( ! function_exists( 'cck_render_component_block' ) ) {
        /**
         * Render a Gutenberg block through the existing component renderer.
         *
         * @param string $component_id Component ID.
         * @param array  $attributes   Block attributes.
         * @return string
         */
        function cck_render_component_block( $component_id, $attributes = array() ) {
                $component_id       = sanitize_key( $component_id );
                $attributes         = is_array( $attributes ) ? $attributes : array();
                $wrapper_attributes = get_block_wrapper_attributes();

                if ( '' === $component_id ) {
                        return '';
                }

                return sprintf(
                        '<div %1$s>%2$s</div>',
                        $wrapper_attributes,
                        cck_render_component( $component_id, $attributes )
                );
        }
}
