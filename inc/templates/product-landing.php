<?php
/**
 * Product landing template metadata.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'id'          => 'product-landing',
	'name'        => 'Product Landing',
	'description' => 'Focused landing page for a product or collection.',
	'version'     => '0.1.0',
	'author'      => 'Craft Commerce Kit',
	'components'  => array(
		'hero',
		'trust-block',
		'image-text',
		'collection-grid',
		'cta',
	),
);
