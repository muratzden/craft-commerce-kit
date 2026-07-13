<?php
/**
 * Atelier product grid section.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

return array(
	'component'  => 'product-grid',
	'attributes' => array(
		'eyebrow' => 'Latest Products',
		'title'   => 'Fresh from the storefront.',
		'limit'   => 4,
		'type'    => 'latest',
	),
);
