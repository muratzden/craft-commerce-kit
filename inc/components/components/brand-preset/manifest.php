<?php
/**
 * Brand Preset component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
        'id'          => 'brand-preset',
        'name'        => __( 'Brand Preset', 'craft-commerce-kit' ),
        'description' => __( 'Global brand preset metadata card.', 'craft-commerce-kit' ),
        'version'     => '1.0.0',
        'category'    => 'ui',
        'icon'        => 'admin-customizer',
        'preview'     => array(
                'attributes' => array(
                        'preset_id'  => 'craft-commerce-kit',
                        'brand_name' => __( 'Craft Commerce Kit', 'craft-commerce-kit' ),
                        'label'      => __( 'Leather Atelier', 'craft-commerce-kit' ),
                ),
        ),
        'callback'    => 'cck_component_package_render_brand_preset',
        'supports'    => array( 'background', 'spacing', 'typography', 'visibility' ),
        'settings'    => array(
                'preset_id'  => array(
                        'type'              => 'text',
                        'label'             => __( 'Preset ID', 'craft-commerce-kit' ),
                        'default'           => 'craft-commerce-kit',
                        'sanitize_callback' => 'sanitize_key',
                ),
                'brand_name' => array(
                        'type'              => 'text',
                        'label'             => __( 'Brand Name', 'craft-commerce-kit' ),
                        'default'           => __( 'Craft Commerce Kit', 'craft-commerce-kit' ),
                        'sanitize_callback' => 'sanitize_text_field',
                ),
                'label'      => array(
                        'type'              => 'text',
                        'label'             => __( 'Label', 'craft-commerce-kit' ),
                        'default'           => __( 'Leather Atelier', 'craft-commerce-kit' ),
                        'sanitize_callback' => 'sanitize_text_field',
                ),
        ),
);
