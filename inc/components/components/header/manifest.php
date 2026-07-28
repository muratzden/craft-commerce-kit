<?php
/**
 * Header component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
        'id'          => 'header',
        'name'        => __( 'Header', 'craft-commerce-kit' ),
        'description' => __( 'Global storefront header.', 'craft-commerce-kit' ),
        'version'     => '1.0.0',
        'category'    => 'ui',
        'icon'        => 'menu',
        'preview'     => array(
                'attributes' => array(
                        'brand_name' => __( 'Craft Commerce Kit', 'craft-commerce-kit' ),
                        'brand_url'  => home_url( '/' ),
                ),
        ),
        'callback'    => 'cck_component_package_render_header',
        'supports'    => array( 'background', 'spacing', 'typography', 'visibility' ),
        'settings'    => array(
                'brand_name' => array(
                        'type'              => 'text',
                        'label'             => __( 'Brand Name', 'craft-commerce-kit' ),
                        'default'           => __( 'Craft Commerce Kit', 'craft-commerce-kit' ),
                        'sanitize_callback' => 'sanitize_text_field',
                ),
                'brand_url'  => array(
                        'type'              => 'url',
                        'label'             => __( 'Brand URL', 'craft-commerce-kit' ),
                        'default'           => '/',
                        'sanitize_callback' => 'esc_url_raw',
                ),
        ),
);
