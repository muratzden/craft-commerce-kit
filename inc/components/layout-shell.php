<?php
/**
 * Global layout shell helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_brand_presets' ) ) {
	/**
	 * Return registered brand presets.
	 *
	 * @return array
	 */
	function cck_get_brand_presets() {
		$tilla_pack = function_exists( 'cck_get_tilla_leather_brand_pack' ) ? cck_get_tilla_leather_brand_pack() : array();

		$presets = array(
			'tilla-leather' => array(
				'id'          => 'tilla-leather',
				'label'       => __( 'Tillaya / Leather Atelier', 'craft-commerce-kit' ),
				'brand_name'  => cck_array_get( $tilla_pack, 'brand_name', __( 'CCK Test Store', 'craft-commerce-kit' ) ),
				'brand_url'   => home_url( '/' ),
				'about'       => cck_array_get( $tilla_pack, 'hero_text', __( 'A premium WooCommerce starter kit for refined artisan commerce.', 'craft-commerce-kit' ) ),
				'header_nav'  => array(
					array( 'label' => __( 'Atelier', 'craft-commerce-kit' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Mağaza', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Örnek sayfa', 'craft-commerce-kit' ), 'url' => home_url( '/ornek-sayfa/' ) ),
				),
				'footer_nav'  => array(
					array( 'label' => __( 'Blog', 'craft-commerce-kit' ), 'url' => home_url( '/blog/' ) ),
					array( 'label' => __( 'Hakkında', 'craft-commerce-kit' ), 'url' => home_url( '/hakkinda/' ) ),
					array( 'label' => __( 'Shop', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Patterns', 'craft-commerce-kit' ), 'url' => home_url( '/patterns/' ) ),
					array( 'label' => __( 'Themes', 'craft-commerce-kit' ), 'url' => home_url( '/themes/' ) ),
				),
				'footer_meta' => __( 'Secure checkout · Fast shipping · Premium support', 'craft-commerce-kit' ),
			),
			'ceramic' => array(
				'id'          => 'ceramic',
				'label'       => __( 'Ceramic Studio', 'craft-commerce-kit' ),
				'brand_name'  => __( 'Ceramic Studio', 'craft-commerce-kit' ),
				'brand_url'   => home_url( '/' ),
				'about'       => __( 'Quiet, tactile storefront layouts for ceramic brands.', 'craft-commerce-kit' ),
				'header_nav'  => array(
					array( 'label' => __( 'Studio', 'craft-commerce-kit' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Collections', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Journal', 'craft-commerce-kit' ), 'url' => home_url( '/journal/' ) ),
				),
				'footer_nav'  => array(
					array( 'label' => __( 'Collections', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Stockists', 'craft-commerce-kit' ), 'url' => home_url( '/stockists/' ) ),
					array( 'label' => __( 'Journal', 'craft-commerce-kit' ), 'url' => home_url( '/journal/' ) ),
					array( 'label' => __( 'Care', 'craft-commerce-kit' ), 'url' => home_url( '/care/' ) ),
				),
				'footer_meta' => __( 'Hand-thrown pieces · Small batch · Thoughtful packing', 'craft-commerce-kit' ),
			),
			'hotel' => array(
				'id'          => 'hotel',
				'label'       => __( 'Hotel Atelier', 'craft-commerce-kit' ),
				'brand_name'  => __( 'Hotel Atelier', 'craft-commerce-kit' ),
				'brand_url'   => home_url( '/' ),
				'about'       => __( 'Elegant commerce layouts for hospitality and destination brands.', 'craft-commerce-kit' ),
				'header_nav'  => array(
					array( 'label' => __( 'Rooms', 'craft-commerce-kit' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Dining', 'craft-commerce-kit' ), 'url' => home_url( '/dining/' ) ),
					array( 'label' => __( 'Experiences', 'craft-commerce-kit' ), 'url' => home_url( '/experiences/' ) ),
				),
				'footer_nav'  => array(
					array( 'label' => __( 'Rooms', 'craft-commerce-kit' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Dining', 'craft-commerce-kit' ), 'url' => home_url( '/dining/' ) ),
					array( 'label' => __( 'Experiences', 'craft-commerce-kit' ), 'url' => home_url( '/experiences/' ) ),
					array( 'label' => __( 'Concierge', 'craft-commerce-kit' ), 'url' => home_url( '/concierge/' ) ),
				),
				'footer_meta' => __( 'White-glove service · Seamless booking · Trusted delivery', 'craft-commerce-kit' ),
			),
			'coffee' => array(
				'id'          => 'coffee',
				'label'       => __( 'Coffee Roastery', 'craft-commerce-kit' ),
				'brand_name'  => __( 'Coffee Roastery', 'craft-commerce-kit' ),
				'brand_url'   => home_url( '/' ),
				'about'       => __( 'Warm, efficient storefront systems for coffee brands.', 'craft-commerce-kit' ),
				'header_nav'  => array(
					array( 'label' => __( 'Beans', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Brew Guide', 'craft-commerce-kit' ), 'url' => home_url( '/brew-guide/' ) ),
					array( 'label' => __( 'Subscriptions', 'craft-commerce-kit' ), 'url' => home_url( '/subscriptions/' ) ),
				),
				'footer_nav'  => array(
					array( 'label' => __( 'Beans', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Subscriptions', 'craft-commerce-kit' ), 'url' => home_url( '/subscriptions/' ) ),
					array( 'label' => __( 'Brew Guide', 'craft-commerce-kit' ), 'url' => home_url( '/brew-guide/' ) ),
					array( 'label' => __( 'Wholesale', 'craft-commerce-kit' ), 'url' => home_url( '/wholesale/' ) ),
				),
				'footer_meta' => __( 'Fresh roast schedule · Easy reorder · B2B friendly', 'craft-commerce-kit' ),
			),
			'jewelry' => array(
				'id'          => 'jewelry',
				'label'       => __( 'Jewelry Atelier', 'craft-commerce-kit' ),
				'brand_name'  => __( 'Jewelry Atelier', 'craft-commerce-kit' ),
				'brand_url'   => home_url( '/' ),
				'about'       => __( 'Refined presentation for bespoke and made-to-order jewelry.', 'craft-commerce-kit' ),
				'header_nav'  => array(
					array( 'label' => __( 'Collections', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Bespoke', 'craft-commerce-kit' ), 'url' => home_url( '/bespoke/' ) ),
					array( 'label' => __( 'Journal', 'craft-commerce-kit' ), 'url' => home_url( '/journal/' ) ),
				),
				'footer_nav'  => array(
					array( 'label' => __( 'Collections', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Bespoke', 'craft-commerce-kit' ), 'url' => home_url( '/bespoke/' ) ),
					array( 'label' => __( 'Journal', 'craft-commerce-kit' ), 'url' => home_url( '/journal/' ) ),
					array( 'label' => __( 'Appointments', 'craft-commerce-kit' ), 'url' => home_url( '/appointments/' ) ),
				),
				'footer_meta' => __( 'Private appointments · Gift-ready packaging · Secure checkout', 'craft-commerce-kit' ),
			),
			'fashion' => array(
				'id'          => 'fashion',
				'label'       => __( 'Fashion House', 'craft-commerce-kit' ),
				'brand_name'  => __( 'Fashion House', 'craft-commerce-kit' ),
				'brand_url'   => home_url( '/' ),
				'about'       => __( 'A flexible storefront shell for modern fashion collections.', 'craft-commerce-kit' ),
				'header_nav'  => array(
					array( 'label' => __( 'Collections', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Lookbook', 'craft-commerce-kit' ), 'url' => home_url( '/lookbook/' ) ),
					array( 'label' => __( 'Stories', 'craft-commerce-kit' ), 'url' => home_url( '/stories/' ) ),
				),
				'footer_nav'  => array(
					array( 'label' => __( 'Collections', 'craft-commerce-kit' ), 'url' => home_url( '/shop/' ) ),
					array( 'label' => __( 'Lookbook', 'craft-commerce-kit' ), 'url' => home_url( '/lookbook/' ) ),
					array( 'label' => __( 'Stories', 'craft-commerce-kit' ), 'url' => home_url( '/stories/' ) ),
					array( 'label' => __( 'Stores', 'craft-commerce-kit' ), 'url' => home_url( '/stores/' ) ),
				),
				'footer_meta' => __( 'Seasonal drops · Easy returns · Tailored support', 'craft-commerce-kit' ),
			),
		);

		return apply_filters( 'cck_brand_presets', $presets );
	}
}

if ( ! function_exists( 'cck_get_active_brand_preset_id' ) ) {
	/**
	 * Return the active brand preset identifier.
	 *
	 * @return string
	 */
	function cck_get_active_brand_preset_id() {
		$preset_id = get_option( 'cck_active_brand_preset', 'tilla-leather' );
		$preset_id = sanitize_key( apply_filters( 'cck_active_brand_preset_id', $preset_id ) );

		return '' !== $preset_id ? $preset_id : 'tilla-leather';
	}
}

if ( ! function_exists( 'cck_get_active_brand_preset' ) ) {
	/**
	 * Return the active brand preset definition.
	 *
	 * @return array
	 */
	function cck_get_active_brand_preset() {
		$presets   = cck_get_brand_presets();
		$preset_id = cck_get_active_brand_preset_id();

		if ( ! isset( $presets[ $preset_id ] ) ) {
			$preset_id = 'tilla-leather';
		}

		$preset = isset( $presets[ $preset_id ] ) ? $presets[ $preset_id ] : array();

		return array_merge(
			array(
				'id'          => $preset_id,
				'label'       => __( 'Leather Atelier', 'craft-commerce-kit' ),
				'brand_name'  => get_bloginfo( 'name' ),
				'brand_url'   => home_url( '/' ),
				'about'       => __( 'A premium WooCommerce starter kit for refined artisan commerce.', 'craft-commerce-kit' ),
				'header_nav'  => array(),
				'footer_nav'  => array(),
				'footer_meta' => __( 'Secure checkout · Premium support · Fast delivery', 'craft-commerce-kit' ),
			),
			is_array( $preset ) ? $preset : array()
		);
	}
}

if ( ! function_exists( 'cck_get_layout_action_counts' ) ) {
	/**
	 * Get header action counts.
	 *
	 * @return array
	 */
	function cck_get_layout_action_counts() {
		$counts = array(
			'account'  => 0,
			'wishlist' => 0,
			'cart'     => 0,
		);

		if ( function_exists( 'WC' ) && WC() && WC()->cart ) {
			$counts['cart'] = (int) WC()->cart->get_cart_contents_count();
		}

		return apply_filters( 'cck_header_action_counts', $counts );
	}
}

if ( ! function_exists( 'cck_get_layout_action_urls' ) ) {
	/**
	 * Get header action URLs.
	 *
	 * @return array
	 */
	function cck_get_layout_action_urls() {
		$account_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
		$wishlist_url = home_url( '/my-account/wishlist/' );
		$cart_url     = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );

		return apply_filters(
			'cck_header_action_urls',
			array(
				'account'  => $account_url ? $account_url : home_url( '/my-account/' ),
				'wishlist' => $wishlist_url,
				'cart'     => $cart_url ? $cart_url : home_url( '/cart/' ),
			)
		);
	}
}

if ( ! function_exists( 'cck_render_layout_action_icon' ) ) {
	/**
	 * Render a layout action icon.
	 *
	 * @param string $icon Icon name.
	 * @return string
	 */
	function cck_render_layout_action_icon( $icon ) {
		$icon = sanitize_key( $icon );

		if ( 'user' === $icon ) {
			return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="3.5"></circle><path d="M5.5 19c0-3.6 2.9-6.5 6.5-6.5s6.5 2.9 6.5 6.5"></path></svg>';
		}

		return function_exists( 'cck_render_svg_icon' ) ? cck_render_svg_icon( $icon ) : '';
	}
}

if ( ! function_exists( 'cck_component_header_actions' ) ) {
	/**
	 * Render header action icons.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_header_actions( array $args = array() ) {
		$args    = shortcode_atts( array(), $args, 'cck_header_actions' );
		$counts  = cck_get_layout_action_counts();
		$urls    = cck_get_layout_action_urls();
		$labels  = array(
			'account'  => __( 'Account', 'craft-commerce-kit' ),
			'wishlist' => __( 'Wishlist', 'craft-commerce-kit' ),
			'cart'     => __( 'Cart', 'craft-commerce-kit' ),
		);

		ob_start();
		?>
		<div class="cck-site-header__actions">
			<a class="cck-header-action cck-header-action--account" href="<?php echo esc_url( $urls['account'] ); ?>" aria-label="<?php echo esc_attr( $labels['account'] ); ?>">
				<span class="cck-header-action__icon" aria-hidden="true"><?php echo cck_render_layout_action_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $labels['account'] ); ?></span>
			</a>

			<a class="cck-header-action cck-header-action--wishlist" href="<?php echo esc_url( $urls['wishlist'] ); ?>" aria-label="<?php echo esc_attr( $labels['wishlist'] ); ?>">
				<span class="cck-header-action__icon" aria-hidden="true"><?php echo cck_render_layout_action_icon( 'heart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="cck-header-action__count"<?php echo $counts['wishlist'] > 0 ? ' data-count="' . esc_attr( (string) $counts['wishlist'] ) . '"' : ''; ?>><?php echo $counts['wishlist'] > 0 ? esc_html( (string) $counts['wishlist'] ) : ''; ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $labels['wishlist'] ); ?></span>
			</a>

			<a class="cck-header-action cck-header-action--cart" href="<?php echo esc_url( $urls['cart'] ); ?>" aria-label="<?php echo esc_attr( $labels['cart'] ); ?>">
				<span class="cck-header-action__icon" aria-hidden="true"><?php echo cck_render_layout_action_icon( 'bag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="cck-header-action__count"<?php echo $counts['cart'] > 0 ? ' data-count="' . esc_attr( (string) $counts['cart'] ) . '"' : ''; ?>><?php echo $counts['cart'] > 0 ? esc_html( (string) $counts['cart'] ) : ''; ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $labels['cart'] ); ?></span>
			</a>
		</div>
		<?php

		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'cck_component_header' ) ) {
	/**
	 * Render the global site header.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_header( array $args = array() ) {
		$preset = cck_get_active_brand_preset();
		$args   = wp_parse_args(
			$args,
			array(
				'brand_name' => cck_array_get( $preset, 'brand_name', get_bloginfo( 'name' ) ),
				'brand_url'  => cck_array_get( $preset, 'brand_url', home_url( '/' ) ),
				'nav'        => cck_array_get( $preset, 'header_nav', array() ),
			)
		);
		$nav_items = is_array( $args['nav'] ) ? $args['nav'] : array();

		ob_start();
		?>
		<header class="cck-component cck-site-header" role="banner">
			<div class="cck-site-header__inner">
				<a class="cck-site-header__brand" href="<?php echo esc_url( $args['brand_url'] ); ?>" rel="home">
					<?php echo esc_html( $args['brand_name'] ); ?>
				</a>

				<nav class="cck-site-header__nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'craft-commerce-kit' ); ?>">
					<?php foreach ( $nav_items as $nav_item ) : ?>
						<?php
						$label = cck_array_get( $nav_item, 'label', '' );
						$url   = cck_array_get( $nav_item, 'url', '' );
						if ( '' === $label || '' === $url ) {
							continue;
						}
						?>
						<a class="cck-site-header__nav-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a>
					<?php endforeach; ?>
				</nav>

				<?php echo cck_component_header_actions(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</header>
		<?php

		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'cck_component_brand_preset' ) ) {
	/**
	 * Render the active brand preset card.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_brand_preset( array $args = array() ) {
		$preset = cck_get_active_brand_preset();
		$args   = wp_parse_args(
			$args,
			array(
				'preset_id'  => cck_array_get( $preset, 'id', 'tilla-leather' ),
				'brand_name' => cck_array_get( $preset, 'brand_name', get_bloginfo( 'name' ) ),
				'label'      => cck_array_get( $preset, 'label', __( 'Leather Atelier', 'craft-commerce-kit' ) ),
			)
		);

		ob_start();
		?>
		<section class="cck-component cck-brand-preset" data-preset="<?php echo esc_attr( $args['preset_id'] ); ?>">
			<div class="cck-brand-preset__inner">
				<p class="cck-eyebrow"><?php esc_html_e( 'Brand preset', 'craft-commerce-kit' ); ?></p>
				<h2><?php echo esc_html( $args['label'] ); ?></h2>
				<p><?php echo esc_html( $args['brand_name'] ); ?></p>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'cck_component_layout_assets' ) ) {
	/**
	 * Render the layout assets metadata card.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_layout_assets( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'stylesheet' => CCK_PLUGIN_URL . 'assets/css/cck-layout.css',
				'status'     => __( 'Shared header/footer assets', 'craft-commerce-kit' ),
			)
		);

		ob_start();
		?>
		<section class="cck-component cck-layout-assets">
			<div class="cck-layout-assets__inner">
				<p class="cck-eyebrow"><?php esc_html_e( 'Layout assets', 'craft-commerce-kit' ); ?></p>
				<h2><?php echo esc_html( $args['status'] ); ?></h2>
				<p class="cck-layout-assets__path"><?php echo esc_html( $args['stylesheet'] ); ?></p>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'cck_enqueue_layout_assets' ) ) {
	/**
	 * Enqueue the shared header/footer stylesheet.
	 *
	 * @return void
	 */
	function cck_enqueue_layout_assets() {
		static $enqueued = false;

		if ( $enqueued || is_admin() ) {
			return;
		}

		$enqueued   = true;
		$css_path   = CCK_PLUGIN_DIR . 'assets/css/cck-layout.css';
		$css_version = file_exists( $css_path ) ? filemtime( $css_path ) : CCK_VERSION;

		wp_enqueue_style(
			'craft-commerce-kit-layout',
			CCK_PLUGIN_URL . 'assets/css/cck-layout.css',
			array(),
			$css_version
		);
	}
}

if ( ! function_exists( 'cck_layout_body_classes' ) ) {
	/**
	 * Add global layout body classes.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	function cck_layout_body_classes( $classes ) {
		if ( ! is_array( $classes ) ) {
			$classes = array();
		}

		$preset = cck_get_active_brand_preset_id();

		$classes[] = 'cck-layout-active';
		$classes[] = 'cck-brand-preset--' . sanitize_html_class( $preset );

		return array_values( array_unique( $classes ) );
	}
}

if ( ! function_exists( 'cck_should_render_global_chrome' ) ) {
	/**
	 * Determine whether CCK should render its own global header/footer chrome.
	 *
	 * @return bool
	 */
	function cck_should_render_global_chrome() {
		return (bool) apply_filters( 'cck_should_render_global_chrome', false );
	}
}

if ( ! function_exists( 'cck_render_global_header' ) ) {
	/**
	 * Echo the global site header.
	 *
	 * @return void
	 */
	function cck_render_global_header() {
		if ( ! cck_should_render_global_chrome() ) {
			return;
		}

		cck_enqueue_layout_assets();

		echo cck_render_component( 'header' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'cck_render_global_footer' ) ) {
	/**
	 * Echo the global site footer.
	 *
	 * @return void
	 */
	function cck_render_global_footer() {
		if ( ! cck_should_render_global_chrome() ) {
			return;
		}

		cck_enqueue_layout_assets();

		echo function_exists( 'cck_component_footer' ) ? cck_component_footer() : cck_render_component( 'footer' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
