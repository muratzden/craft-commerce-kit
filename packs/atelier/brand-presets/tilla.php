<?php
/**
 * Tilla Leather Atelier brand binding.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

$cck_tilla_brand = function_exists( 'cck_get_brand' )
	? cck_get_brand( 'tilla-leather' )
	: array();

if (
	empty( $cck_tilla_brand ) &&
	function_exists( 'cck_get_tilla_leather_brand' )
) {
	$cck_tilla_brand = cck_get_tilla_leather_brand();
}

$cck_tilla_brand = array_merge(
	is_array( $cck_tilla_brand ) ? $cck_tilla_brand : array(),
	array(
		'id'         => 'tilla-leather',
		'name'       => 'Tilla Leather',
		'experience' => 'atelier',
	)
);

cck_register_brand(
	'tilla-leather',
	$cck_tilla_brand
);

unset( $cck_tilla_brand );