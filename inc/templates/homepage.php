<?php
/**
 * Homepage template metadata.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'homepage',
	'name'        => 'Homepage',
	'description' => 'Default premium homepage.',
	'version'     => '0.1.0',
	'author'      => 'Craft Commerce Kit',
	'components'  => array(
		'hero',
		'collection-grid',
		'trust-block',
		'image-text',
		'cta',
	),
);
