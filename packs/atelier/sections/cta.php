<?php
/**
 * Atelier CTA section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'   => 'cta',
	'bindings'   => array(
		'title'        => 'brand.headline',
		'text'         => 'brand.text',
		'button_label' => 'brand.cta_label',
		'button_url'   => 'brand.cta_url',
	),
	'attributes' => array(
		'title'        => 'Ready to craft something exceptional?',
		'text'         => 'Build reusable WooCommerce experiences with Craft Commerce Kit.',
		'button_label' => 'Explore the Collection',
		'button_url'   => '/shop/',
	),
);
