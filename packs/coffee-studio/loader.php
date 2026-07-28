<?php
/**
 * Coffee Studio experience loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

$cck_coffee_studio_manifest = require __DIR__ . '/manifest.php';

cck_register_experience(
	'coffee-studio',
	$cck_coffee_studio_manifest
);

require_once __DIR__ . '/experience.php';

unset( $cck_coffee_studio_manifest );
