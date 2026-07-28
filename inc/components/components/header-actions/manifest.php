<?php
/**
 * Header Actions component manifest.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
        'id'          => 'header-actions',
        'name'        => __( 'Header Actions', 'craft-commerce-kit' ),
        'description' => __( 'Global account, wishlist, and cart actions for the storefront header.', 'craft-commerce-kit' ),
        'version'     => '1.0.0',
        'category'    => 'ui',
        'icon'        => 'cart',
        'preview'     => array(
                'attributes' => array(),
        ),
        'callback'    => 'cck_component_package_render_header_actions',
        'supports'    => array( 'spacing', 'visibility' ),
        'settings'    => array(),
);
