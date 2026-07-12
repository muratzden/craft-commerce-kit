<?php
/**
 * Atelier experience loader.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

$cck_atelier_manifest = require __DIR__ . '/manifest.php';

cck_register_experience(
	'atelier',
	$cck_atelier_manifest
);

require_once __DIR__ . '/brand-presets/loader.php';
require_once __DIR__ . '/experience.php';
