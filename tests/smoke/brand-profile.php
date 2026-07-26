<?php
/**
 * Brand profile smoke tests.
 *
 * Run with:
 * wp eval-file C:\Workspace\Plugins\craft-commerce-kit\craft-commerce-kit\tests\smoke\brand-profile.php
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	echo "This smoke test must be run with WP-CLI.\n";
	return;
}

if (
	! function_exists( 'cck_validate_brand_profile' ) ||
	! function_exists( 'cck_save_brand_profile' ) ||
	! function_exists( 'cck_get_brand_profile' )
) {
	WP_CLI::error( 'Brand profile helpers are not loaded.' );
}

if ( ! class_exists( 'CCK_Brand_Profile_Smoke_Result' ) ) {
	/**
	 * Store smoke-test counters without relying on eval-file scope.
	 */
	class CCK_Brand_Profile_Smoke_Result {
		/**
		 * Passed assertion count.
		 *
		 * @var int
		 */
		public static $passes = 0;

		/**
		 * Failed assertion count.
		 *
		 * @var int
		 */
		public static $failures = 0;
	}
}

if ( ! function_exists( 'cck_smoke_assert' ) ) {
	/**
	 * Record a smoke-test assertion.
	 *
	 * @param bool   $condition Assertion result.
	 * @param string $label     Assertion label.
	 * @return void
	 */
	function cck_smoke_assert( $condition, $label ) {
		if ( $condition ) {
			++CCK_Brand_Profile_Smoke_Result::$passes;
			WP_CLI::log( 'PASS: ' . $label );
			return;
		}

		++CCK_Brand_Profile_Smoke_Result::$failures;
		WP_CLI::warning( 'FAIL: ' . $label );
	}
}

$original_profile_exists = false !== get_option(
	'cck_brand_profile',
	false
);

$original_profile = get_option(
	'cck_brand_profile',
	array()
);

$original_setup_exists = false !== get_option(
	'cck_setup_completed',
	false
);

$original_setup = get_option(
	'cck_setup_completed',
	0
);

try {
	$base_profile = array(
		'id'         => 'smoke-test-brand',
		'brand_name' => 'Smoke Test Brand',
		'experience' => 'atelier',
		'eyebrow'    => 'Handmade',
		'headline'   => 'Built to last.',
		'text'       => 'Smoke-test profile description.',
		'cta_label'  => 'Shop',
		'cta_url'    => 'https://example.com/shop/',
		'tokens'     => array(
			'colors' => array(
				'background' => '#f7f1e7',
				'surface'    => '#fffdf8',
				'text'       => '#2a1b13',
				'accent'     => '#9b5c32',
			),
		),
	);

	$missing_name = $base_profile;
	$missing_name['brand_name'] = '';

	$result = cck_validate_brand_profile( $missing_name );

	cck_smoke_assert(
		is_wp_error( $result ) &&
		'missing_brand_name' === $result->get_error_code(),
		'Empty brand name returns missing_brand_name.'
	);

	$auto_slug = $base_profile;
	$auto_slug['id'] = '';
	$auto_slug['brand_name'] = 'Smoke Test Leather';

	$result = cck_validate_brand_profile( $auto_slug );

	cck_smoke_assert(
		is_array( $result ) &&
		'smoke-test-leather' === $result['id'],
		'Empty Brand ID is generated from the brand name.'
	);

	$invalid_url = $base_profile;
	$invalid_url['cta_url'] = 'javascript:alert(1)';

	$result = cck_validate_brand_profile( $invalid_url );

	cck_smoke_assert(
		is_wp_error( $result ) &&
		'invalid_cta_url' === $result->get_error_code(),
		'Unsafe CTA URL returns invalid_cta_url.'
	);

	update_option(
		'cck_brand_profile',
		$base_profile,
		false
	);

	$invalid_color = $base_profile;
	$invalid_color['tokens']['colors']['accent'] = 'not-a-color';

	$result = cck_validate_brand_profile( $invalid_color );

	cck_smoke_assert(
		is_array( $result ) &&
		'#9b5c32' === $result['tokens']['colors']['accent'],
		'Invalid accent color preserves the current valid accent.'
	);

	$invalid_experience = $base_profile;
	$invalid_experience['experience'] = 'unknown-experience';

	$result = cck_validate_brand_profile(
		$invalid_experience
	);

	cck_smoke_assert(
		is_array( $result ) &&
		'atelier' === $result['experience'],
		'Unsupported experience falls back to atelier.'
	);

	$result = cck_save_brand_profile( $base_profile );
	$saved  = get_option( 'cck_brand_profile', array() );

	cck_smoke_assert(
		true === $result &&
		is_array( $saved ) &&
		'smoke-test-brand' === $saved['id'] &&
		'Smoke Test Brand' === $saved['brand_name'],
		'Valid profile saves successfully.'
	);

	$result = cck_save_brand_profile( $invalid_url );

	cck_smoke_assert(
		is_wp_error( $result ) &&
		'invalid_cta_url' === $result->get_error_code(),
		'Save helper propagates validation errors.'
	);
} finally {
	if ( $original_profile_exists ) {
		update_option(
			'cck_brand_profile',
			$original_profile,
			false
		);
	} else {
		delete_option( 'cck_brand_profile' );
	}

	if ( $original_setup_exists ) {
		update_option(
			'cck_setup_completed',
			$original_setup,
			false
		);
	} else {
		delete_option( 'cck_setup_completed' );
	}
}

WP_CLI::log( '' );
WP_CLI::log(
	sprintf(
		'Result: %d passed, %d failed.',
		CCK_Brand_Profile_Smoke_Result::$passes,
		CCK_Brand_Profile_Smoke_Result::$failures
	)
);

if ( CCK_Brand_Profile_Smoke_Result::$failures > 0 ) {
	WP_CLI::error( 'Brand profile smoke tests failed.' );
}

WP_CLI::success( 'Brand profile smoke tests passed.' );