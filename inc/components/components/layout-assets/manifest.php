<?php
/**
 * Layout Assets component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
        'id'          => 'layout-assets',
        'name'        => __( 'Layout Assets', 'craft-commerce-kit' ),
        'description' => __( 'Global shared header and footer layout asset metadata.', 'craft-commerce-kit' ),
        'version'     => '1.0.0',
        'category'    => 'ui',
        'icon'        => 'admin-appearance',
        'preview'     => array(
                'attributes' => array(
                        'stylesheet' => CCK_PLUGIN_URL . 'assets/css/cck-layout.css',
                        'status'     => __( 'Shared header/footer assets', 'craft-commerce-kit' ),
                ),
        ),
        'callback'    => 'cck_component_package_render_layout_assets',
        'supports'    => array( 'visibility' ),
        'settings'    => array(
                'stylesheet' => array(
                        'type'              => 'url',
                        'label'             => __( 'Stylesheet', 'craft-commerce-kit' ),
                        'default'           => CCK_PLUGIN_URL . 'assets/css/cck-layout.css',
                        'sanitize_callback' => 'esc_url_raw',
                ),
                'status'     => array(
                        'type'              => 'text',
                        'label'             => __( 'Status', 'craft-commerce-kit' ),
                        'default'           => __( 'Shared header/footer assets', 'craft-commerce-kit' ),
                        'sanitize_callback' => 'sanitize_text_field',
                ),
        ),
);
