<?php
/**
 * WooCommerce storefront presentation layer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_wc_get_product_object' ) ) {
	/**
	 * Resolve a product object.
	 *
	 * @param mixed $product Product object or ID.
	 * @return WC_Product|null
	 */
	function cck_wc_get_product_object( $product = null ) {
		if ( $product instanceof WC_Product ) {
			return $product;
		}

		if ( is_numeric( $product ) && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( absint( $product ) );
		}

		if ( ! $product instanceof WC_Product ) {
			$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
		}

		return $product instanceof WC_Product ? $product : null;
	}
}

if ( ! function_exists( 'cck_wc_is_storefront_request' ) ) {
	/**
	 * Detect WooCommerce storefront requests.
	 *
	 * @return bool
	 */
	function cck_wc_is_storefront_request() {
		if ( is_admin() || wp_doing_ajax() || ! cck_is_woocommerce_active() ) {
			return false;
		}

		return function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
	}
}

if ( ! function_exists( 'cck_enqueue_woocommerce_storefront_assets' ) ) {
	/**
	 * Enqueue storefront assets on WooCommerce templates.
	 *
	 * @return void
	 */
	function cck_enqueue_woocommerce_storefront_assets() {
		if ( ! cck_wc_is_storefront_request() ) {
			return;
		}

		cck_enqueue_frontend_assets();
	}
}

if ( ! function_exists( 'cck_wc_get_shop_layout' ) ) {
	/**
	 * Get the active shop layout name.
	 *
	 * @return string
	 */
	function cck_wc_get_shop_layout() {
		return sanitize_key( apply_filters( 'cck_wc_shop_layout', 'luxury' ) );
	}
}

if ( ! function_exists( 'cck_wc_get_shop_columns' ) ) {
	/**
	 * Get the active shop grid column count.
	 *
	 * @return int
	 */
	function cck_wc_get_shop_columns() {
		$layout = cck_wc_get_shop_layout();
		$map    = array(
			'2-column'  => 2,
			'3-column'  => 3,
			'4-column'  => 3,
			'masonry'   => 3,
			'editorial' => 3,
			'luxury'    => 3,
		);

		$columns = isset( $map[ $layout ] ) ? $map[ $layout ] : 3;

		return absint( apply_filters( 'cck_wc_shop_columns', $columns, $layout ) );
	}
}

if ( ! function_exists( 'cck_wc_get_product_card_demo_image_map' ) ) {
	/**
	 * Map demo product slugs to bundled demo images.
	 *
	 * @return array<string,string>
	 */
	function cck_wc_get_product_card_demo_image_map() {
		return array(
			'executive-messenger-bag' => 'leather-tote-bag.webp',
			'classic-briefcase'       => 'leather-laptop-sleeve.webp',
			'leather-laptop-sleeve'   => 'leather-laptop-sleeve.webp',
			'everyday-wallet'         => 'leather-wallet.webp',
			'slim-card-holder'        => 'leather-card-holder.webp',
			'artisan-guitar-strap'    => 'leather-belt-bag.webp',
			'travel-organizer'        => 'leather-passport-holder.webp',
			'signature-journal-cover' => 'leather-notebook-cover.webp',
		);
	}
}

if ( ! function_exists( 'cck_wc_get_product_card_demo_image_asset' ) ) {
	/**
	 * Resolve a bundled demo image asset for a product card.
	 *
	 * @param mixed $product Product object, array, ID, slug, or seed.
	 * @return array{file:string,path:string,url:string}|array
	 */
	function cck_wc_get_product_card_demo_image_asset( $product ) {
		if ( ! defined( 'CCK_PLUGIN_DIR' ) || ! defined( 'CCK_PLUGIN_URL' ) ) {
			return array();
		}

		$slug_map = cck_wc_get_product_card_demo_image_map();
		$seed     = '';
		$product_id = 0;

		if ( $product instanceof WC_Product ) {
			$product_id = absint( $product->get_id() );
			$seed       = sanitize_key( $product->get_slug() );
		} elseif ( is_array( $product ) ) {
			$product_id = absint( cck_array_get( $product, 'id', 0 ) );
			$seed       = sanitize_key( cck_array_get( $product, 'slug', cck_array_get( $product, 'title', '' ) ) );
		} elseif ( is_numeric( $product ) ) {
			$product_id = absint( $product );
			$seed       = (string) $product_id;
		} elseif ( is_string( $product ) ) {
			$seed = sanitize_key( $product );
		}

		$file = '';

		if ( '' !== $seed && isset( $slug_map[ $seed ] ) ) {
			$file = sanitize_file_name( $slug_map[ $seed ] );
		}

		if ( '' === $file ) {
			$image_files = array_values( array_unique( array_filter( array_map( 'sanitize_file_name', array_values( $slug_map ) ) ) ) );

			if ( empty( $image_files ) ) {
				return array();
			}

			$hash = $product_id > 0 ? $product_id : absint( sprintf( '%u', crc32( $seed ) ) );
			$file = $image_files[ $hash % count( $image_files ) ];
		}

		if ( '' === $file ) {
			return array();
		}

		$path = trailingslashit( CCK_PLUGIN_DIR ) . 'assets/demo/product-images/' . $file;

		if ( ! file_exists( $path ) ) {
			return array();
		}

		return array(
			'file' => $file,
			'path' => $path,
			'url'  => trailingslashit( CCK_PLUGIN_URL ) . 'assets/demo/product-images/' . rawurlencode( $file ),
		);
	}
}

if ( ! function_exists( 'cck_wc_build_demo_image_html' ) ) {
	/**
	 * Build a resilient demo image HTML fragment for a product.
	 *
	 * @param WC_Product $product Product object.
	 * @param array      $asset   Demo asset definition.
	 * @param string     $class   Image class list.
	 * @return string
	 */
	function cck_wc_build_demo_image_html( WC_Product $product, array $asset, $class = 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image' ) {
		if ( empty( $asset['url'] ) ) {
			return '';
		}

		$asset_size = array();

		if ( ! empty( $asset['path'] ) ) {
			$asset_size = @getimagesize( $asset['path'] );
		}

		$width  = is_array( $asset_size ) && ! empty( $asset_size[0] ) ? ' width="' . absint( $asset_size[0] ) . '"' : '';
		$height = is_array( $asset_size ) && ! empty( $asset_size[1] ) ? ' height="' . absint( $asset_size[1] ) . '"' : '';

		return sprintf(
			'<img src="%1$s" alt="%2$s" class="%3$s" loading="lazy" decoding="async"%4$s%5$s />',
			esc_url( $asset['url'] ),
			esc_attr( $product->get_name() ),
			esc_attr( $class ),
			$width,
			$height
		);
	}
}

if ( ! function_exists( 'cck_wc_get_product_badges' ) ) {
	/**
	 * Get product card badge HTML fragments.
	 *
	 * @param WC_Product $product Product object.
	 * @return array
	 */
	function cck_wc_get_product_badges( WC_Product $product ) {
		$badges = array();

		if ( $product->is_on_sale() ) {
			$badges[] = '<span class="cck-product-card__badge cck-product-card__badge--sale">' . esc_html__( 'Sale', 'craft-commerce-kit' ) . '</span>';
		}

		if ( $product->is_featured() ) {
			$badges[] = '<span class="cck-product-card__badge cck-product-card__badge--featured">' . esc_html__( 'Featured', 'craft-commerce-kit' ) . '</span>';
		}

		$new_threshold = 30;
		$product_age   = current_time( 'timestamp', true ) - (int) get_post_time( 'U', true, $product->get_id() );

		if ( $product_age <= ( DAY_IN_SECONDS * $new_threshold ) ) {
			$badges[] = '<span class="cck-product-card__badge cck-product-card__badge--new">' . esc_html__( 'New', 'craft-commerce-kit' ) . '</span>';
		}

		$stock_quantity = $product->get_stock_quantity();
		if ( $product->managing_stock() && null !== $stock_quantity && $stock_quantity > 0 && $stock_quantity <= 5 ) {
			$badges[] = '<span class="cck-product-card__badge cck-product-card__badge--limited">' . esc_html__( 'Limited', 'craft-commerce-kit' ) . '</span>';
		}

		return array_slice( array_unique( $badges ), 0, 2 );
	}
}

if ( ! function_exists( 'cck_wc_render_product_card_from_definition' ) ) {
	/**
	 * Render a product card from a normalized definition array.
	 *
	 * @param array $card Product card definition.
	 * @return string
	 */
	function cck_wc_render_product_card_from_definition( array $card ) {
		if ( empty( $card['id'] ) ) {
			return '';
		}

		$context = isset( $card['context'] ) ? sanitize_key( $card['context'] ) : 'archive';

		ob_start();
		?>
		<article class="cck-product-card cck-product-card--wc cck-product-card--<?php echo esc_attr( $context ); ?>">
			<div class="cck-product-card__media">
				<?php if ( ! empty( $card['badge_html'] ) ) : ?>
					<div class="cck-product-card__badges">
						<?php echo wp_kses_post( $card['badge_html'] ); ?>
					</div>
				<?php endif; ?>

				<a class="cck-product-card__image-link" href="<?php echo esc_url( cck_array_get( $card, 'url', '#' ) ); ?>">
					<div class="cck-product-card__image">
						<?php echo wp_kses_post( cck_array_get( $card, 'image_html', '' ) ); ?>
					</div>
				</a>
			</div>

			<div class="cck-product-card__content">
				<h3 class="cck-product-card__title">
					<a href="<?php echo esc_url( cck_array_get( $card, 'url', '#' ) ); ?>"><?php echo esc_html( cck_array_get( $card, 'title', '' ) ); ?></a>
				</h3>

				<?php if ( ! empty( $card['short_description'] ) ) : ?>
					<p class="cck-product-card__description"><?php echo esc_html( $card['short_description'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $card['rating_html'] ) || ! empty( $card['price_html'] ) ) : ?>
					<div class="cck-product-card__meta">
						<?php if ( ! empty( $card['rating_html'] ) ) : ?>
							<div class="cck-product-card__rating"><?php echo wp_kses_post( cck_array_get( $card, 'rating_html', '' ) ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $card['price_html'] ) ) : ?>
							<div class="cck-product-card__price"><?php echo wp_kses_post( cck_array_get( $card, 'price_html', '' ) ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="cck-product-card__slots" aria-label="<?php esc_attr_e( 'Product actions', 'craft-commerce-kit' ); ?>">
					<?php echo cck_wc_kses_product_card_action_html( cck_array_get( $card, 'wishlist_html', '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo cck_wc_kses_product_card_action_html( cck_array_get( $card, 'quick_view_html', '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<div class="cck-product-card__actions">
					<?php echo cck_wc_kses_product_card_action_html( cck_array_get( $card, 'add_to_cart_html', '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</article>
		<?php

		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'cck_wc_cardify_action_html' ) ) {
	/**
	 * Turn a WooCommerce action link into a compact icon action.
	 *
	 * @param string $html  Raw action markup.
	 * @param string $icon  Icon slug.
	 * @param string $label Visible/accessibility label.
	 * @param string $class Extra CSS class.
	 * @return string
	 */
	function cck_wc_cardify_action_html( $html, $icon, $label, $class ) {
		$html  = trim( (string) $html );
		$icon  = cck_render_svg_icon( $icon );
		$label = esc_html( $label );

		if ( '' === $html ) {
			return sprintf(
				'<button type="button" class="cck-product-card__slot-button %1$s" aria-label="%2$s">%3$s<span>%4$s</span></button>',
				esc_attr( $class ),
				esc_attr( $label ),
				$icon,
				$label
			);
		}

		if ( preg_match( '/^<(a|button)([^>]*)>(.*)<\\/\\1>$/s', $html, $matches ) ) {
			$tag      = $matches[1];
			$attrs    = $matches[2];
			$content  = trim( $matches[3] );
			$icon_html = '<span class="cck-product-card__slot-icon">' . $icon . '</span>';
			$text_html = '<span>' . esc_html( wp_strip_all_tags( $content ) ) . '</span>';
			$attrs    = preg_replace( '/class=("|\')(.*?)\\1/i', 'class="$2 ' . esc_attr( $class ) . '"', $attrs, 1, $count );

			if ( 1 !== $count ) {
				$attrs .= ' class="' . esc_attr( $class ) . '"';
			}

			return sprintf( '<%1$s%2$s>%3$s%4$s</%1$s>', $tag, $attrs, $icon_html, $text_html );
		}

		return sprintf(
			'<button type="button" class="cck-product-card__slot-button %1$s" aria-label="%2$s">%3$s<span>%4$s</span></button>',
			esc_attr( $class ),
			esc_attr( $label ),
			$icon,
			$label
		);
	}
}

if ( ! function_exists( 'cck_wc_cardify_add_to_cart_html' ) ) {
	/**
	 * Convert the loop add-to-cart output into a compact icon control.
	 *
	 * @param string $html Raw WooCommerce button HTML.
	 * @return string
	 */
	function cck_wc_cardify_add_to_cart_html( $html ) {
		$html = trim( (string) $html );

		if ( '' === $html ) {
			return '';
		}

		$icon = '<span class="cck-product-card__slot-icon">' . cck_render_svg_icon( 'bag' ) . '</span>';

		if ( preg_match( '/^<(a|button)([^>]*)>(.*)<\\/\\1>$/s', $html, $matches ) ) {
			$tag     = $matches[1];
			$attrs   = $matches[2];
			$content = trim( wp_strip_all_tags( $matches[3] ) );
			$attrs   = preg_replace( '/class=("|\')(.*?)\\1/i', 'class="$2 cck-product-card__action-button cck-product-card__action-button--cart"', $attrs, 1, $count );

			if ( 1 !== $count ) {
				$attrs .= ' class="cck-product-card__action-button cck-product-card__action-button--cart"';
			}

			return sprintf(
				'<%1$s%2$s>%3$s<span>%4$s</span></%1$s>',
				$tag,
				$attrs,
				$icon,
				esc_html( $content )
			);
		}

		return $html;
	}
}

if ( ! function_exists( 'cck_wc_render_product_card_action_link' ) ) {
	/**
	 * Render a compact product card action button from the product object.
	 *
	 * @param WC_Product $product Product object.
	 * @return string
	 */
	function cck_wc_render_product_card_action_link( $product ) {
		$product = cck_wc_get_product_object( $product );

		if ( ! $product ) {
			return '';
		}

		$button_text = $product->add_to_cart_text();
		$button_url  = $product->add_to_cart_url();

		if ( '' === $button_text ) {
			$button_text = __( 'Add to cart', 'craft-commerce-kit' );
		}

		if ( '' === $button_url ) {
			$button_url = $product->get_permalink();
		}

		$classes = array(
			'button',
			'cck-product-card__action-button',
			'cck-product-card__action-button--cart',
		);

		if ( $product->supports( 'ajax_add_to_cart' ) ) {
			$classes[] = 'ajax_add_to_cart';
			$classes[] = 'add_to_cart_button';
		}

		$classes[] = 'product_type_' . sanitize_html_class( $product->get_type() );

		$attributes = array(
			'href'       => $button_url,
			'class'      => implode( ' ', array_filter( $classes ) ),
			'aria-label' => $button_text,
			'rel'        => 'nofollow',
		);

		if ( $product->is_purchasable() ) {
			$attributes['data-product_id']  = $product->get_id();
			$attributes['data-product_sku'] = $product->get_sku();
			$attributes['data-quantity']    = 1;
		}

		$attribute_html = array();

		foreach ( $attributes as $key => $value ) {
			$attribute_html[] = sprintf( '%1$s="%2$s"', esc_attr( $key ), esc_attr( $value ) );
		}

		return sprintf(
			'<a %1$s>%2$s<span>%3$s</span></a>',
			implode( ' ', $attribute_html ),
			'<span class="cck-product-card__slot-icon">' . cck_render_svg_icon( 'bag' ) . '</span>',
			esc_html( $button_text )
		);
	}
}

if ( ! function_exists( 'cck_wc_kses_product_card_action_html' ) ) {
	/**
	 * Allow compact product card actions to keep safe inline SVG icons.
	 *
	 * @param string $html Raw markup.
	 * @return string
	 */
	function cck_wc_kses_product_card_action_html( $html ) {
		$allowed = wp_kses_allowed_html( 'post' );
		$allowed['button'] = array(
			'type'             => true,
			'class'            => true,
			'aria-label'       => true,
			'aria-describedby' => true,
			'rel'              => true,
			'data-product_id'  => true,
			'data-product_sku' => true,
			'data-quantity'    => true,
		);
		$allowed['svg'] = array(
			'viewBox'     => true,
			'viewbox'     => true,
			'aria-hidden' => true,
			'focusable'   => true,
			'class'       => true,
			'role'        => true,
			'xmlns'       => true,
		);
		$allowed['path'] = array(
			'd' => true,
		);
		$allowed['circle'] = array(
			'cx' => true,
			'cy' => true,
			'r'  => true,
		);
		$allowed['rect'] = array(
			'x'      => true,
			'y'      => true,
			'width'  => true,
			'height' => true,
			'rx'     => true,
		);
		$allowed['span'] = array(
			'class' => true,
		);

		return wp_kses( $html, $allowed );
	}
}

if ( ! function_exists( 'cck_wc_get_product_card_definition' ) ) {
	/**
	 * Get the data definition for a product card.
	 *
	 * @param mixed  $product Product object or ID.
	 * @param string $context Card context.
	 * @return array
	 */
	function cck_wc_get_product_card_definition( $product = null, $context = 'archive' ) {
		$product = cck_wc_get_product_object( $product );

		if ( ! $product ) {
			return array();
		}

		$context   = sanitize_key( $context );
		$image_id  = absint( $product->get_image_id() );
		$image_html = '';
		$image_url = '';

		if ( $image_id ) {
			$attachment_path = get_attached_file( $image_id );
			$attachment_path = is_string( $attachment_path ) ? $attachment_path : '';

			if ( '' !== $attachment_path && file_exists( $attachment_path ) ) {
				$image_html = wp_get_attachment_image(
					$image_id,
					'woocommerce_thumbnail',
					false,
					array(
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
				$image_url  = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
			}
		}

		if ( '' === $image_html ) {
			$demo_asset = cck_wc_get_product_card_demo_image_asset( $product );

			if ( ! empty( $demo_asset ) ) {
				$asset_size = @getimagesize( $demo_asset['path'] );

				$image_html = sprintf(
					'<img src="%1$s" alt="%2$s" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" loading="lazy" decoding="async"%3$s%4$s />',
					esc_url( $demo_asset['url'] ),
					esc_attr( $product->get_name() ),
					is_array( $asset_size ) && ! empty( $asset_size[0] ) ? ' width="' . absint( $asset_size[0] ) . '"' : '',
					is_array( $asset_size ) && ! empty( $asset_size[1] ) ? ' height="' . absint( $asset_size[1] ) . '"' : ''
				);
				$image_url = $demo_asset['url'];
			}
		}

		if ( '' === $image_html && function_exists( 'wc_placeholder_img' ) ) {
			$image_html = wc_placeholder_img( 'woocommerce_thumbnail' );
		}

		if ( '' === $image_url && function_exists( 'wc_placeholder_img_src' ) ) {
			$image_url = wc_placeholder_img_src( 'woocommerce_thumbnail' );
		}
		$short_desc  = wp_strip_all_tags( (string) $product->get_short_description() );
		$short_desc  = '' !== $short_desc ? wp_trim_words( $short_desc, 14 ) : '';
		$price_html  = $product->get_price_html();
		$rating_html = function_exists( 'wc_get_rating_html' ) ? wc_get_rating_html( (float) $product->get_average_rating(), (int) $product->get_rating_count() ) : '';
		$badge_html       = implode( '', cck_wc_get_product_badges( $product ) );
		$wishlist_slot    = apply_filters( 'cck_wc_product_card_wishlist_slot', '', $product, $context );
		$quick_view_slot  = apply_filters( 'cck_wc_product_card_quick_view_slot', '', $product, $context );

		if ( '' === $wishlist_slot ) {
			$wishlist_slot = cck_wc_cardify_action_html( '', 'heart', __( 'Wishlist', 'craft-commerce-kit' ), 'cck-product-card__slot-button--wishlist' );
		}

		if ( '' === $quick_view_slot ) {
			$quick_view_slot = cck_wc_cardify_action_html( '', 'eye', __( 'Quick view', 'craft-commerce-kit' ), 'cck-product-card__slot-button--quick-view' );
		}

		$add_to_cart_html = cck_wc_render_product_card_action_link( $product );

		if ( '' === $add_to_cart_html && function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			ob_start();
			woocommerce_template_loop_add_to_cart();
			$add_to_cart_html = cck_wc_cardify_add_to_cart_html( trim( ob_get_clean() ) );
		}

		return apply_filters(
			'cck_wc_product_card_definition',
			array(
				'context'           => $context,
				'id'                => $product->get_id(),
				'url'               => $product->get_permalink(),
				'title'             => $product->get_name(),
				'short_description' => $short_desc,
				'badge_html'        => $badge_html,
				'image_html'        => $image_html,
				'image_url'         => $image_url,
				'price_html'        => $price_html,
				'rating_html'       => $rating_html,
				'add_to_cart_html'  => $add_to_cart_html,
				'wishlist_html'     => $wishlist_slot,
				'quick_view_html'   => $quick_view_slot,
				'is_sale'           => $product->is_on_sale(),
				'is_featured'       => $product->is_featured(),
			),
			$product,
			$context
		);
	}
}

if ( ! function_exists( 'cck_wc_render_product_card_markup' ) ) {
	/**
	 * Render a premium WooCommerce product card.
	 *
	 * @param mixed  $product Product object or ID.
	 * @param string $context Context.
	 * @return string
	 */
	function cck_wc_render_product_card_markup( $product = null, $context = 'archive' ) {
		$card = cck_wc_get_product_card_definition( $product, $context );

		if ( empty( $card['id'] ) ) {
			return '';
		}

		ob_start();
		?>
		<article class="cck-product-card cck-product-card--wc cck-product-card--<?php echo esc_attr( sanitize_key( $card['context'] ) ); ?>">
			<div class="cck-product-card__media">
				<?php if ( ! empty( $card['badge_html'] ) ) : ?>
					<div class="cck-product-card__badges">
						<?php echo wp_kses_post( $card['badge_html'] ); ?>
					</div>
				<?php endif; ?>

				<a class="cck-product-card__image-link" href="<?php echo esc_url( $card['url'] ); ?>">
					<div class="cck-product-card__image">
						<?php echo wp_kses_post( $card['image_html'] ); ?>
					</div>
				</a>
			</div>

			<div class="cck-product-card__content">
				<h3 class="cck-product-card__title">
					<a href="<?php echo esc_url( $card['url'] ); ?>"><?php echo esc_html( $card['title'] ); ?></a>
				</h3>

				<?php if ( ! empty( $card['short_description'] ) ) : ?>
					<p class="cck-product-card__description"><?php echo esc_html( $card['short_description'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $card['rating_html'] ) || ! empty( $card['price_html'] ) ) : ?>
					<div class="cck-product-card__meta">
						<?php if ( ! empty( $card['rating_html'] ) ) : ?>
							<div class="cck-product-card__rating"><?php echo wp_kses_post( $card['rating_html'] ); ?></div>
						<?php endif; ?>

				<?php if ( ! empty( $card['price_html'] ) ) : ?>
							<div class="cck-product-card__price"><?php echo wp_kses_post( $card['price_html'] ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="cck-product-card__slots" aria-label="<?php esc_attr_e( 'Product actions', 'craft-commerce-kit' ); ?>">
					<?php echo cck_wc_kses_product_card_action_html( $card['wishlist_html'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo cck_wc_kses_product_card_action_html( $card['quick_view_html'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<div class="cck-product-card__actions">
					<?php echo cck_wc_kses_product_card_action_html( $card['add_to_cart_html'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</article>
		<?php

		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'cck_wc_render_loop_product_card' ) ) {
	/**
	 * Echo the archive product card.
	 *
	 * @return void
	 */
	function cck_wc_render_loop_product_card() {
		echo cck_wc_render_product_card_markup( get_the_ID(), 'archive' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'cck_wc_render_shop_archive_open' ) ) {
	/**
	 * Open the premium shop archive shell.
	 *
	 * @return void
	 */
	function cck_wc_render_shop_archive_open() {
		if ( ! cck_wc_is_storefront_request() ) {
			return;
		}

		echo '<section class="cck-wc-shell cck-wc-archive cck-wc-archive--' . esc_attr( cck_wc_get_shop_layout() ) . '"><div class="cck-container cck-wc-shell__inner cck-wc-archive__inner">';
	}
}

if ( ! function_exists( 'cck_wc_render_shop_archive_close' ) ) {
	/**
	 * Close the premium shop archive shell.
	 *
	 * @return void
	 */
	function cck_wc_render_shop_archive_close() {
		if ( ! cck_wc_is_storefront_request() ) {
			return;
		}

		echo '</div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_gallery_open' ) ) {
	/**
	 * Open the gallery component shell.
	 *
	 * @return void
	 */
	function cck_wc_render_gallery_open() {
		if ( ! is_product() ) {
			return;
		}

		echo '<section class="cck-wc-section cck-wc-gallery"><div class="cck-container cck-wc-section__inner cck-wc-gallery__inner">';
		echo '<div class="cck-wc-section__eyebrow">' . esc_html__( 'Gallery', 'craft-commerce-kit' ) . '</div>';
		echo '<div class="cck-wc-gallery__grid">';
		echo '<div class="cck-wc-gallery__stage">';
	}
}

if ( ! function_exists( 'cck_wc_render_gallery_badge' ) ) {
	/**
	 * Render gallery badge content.
	 *
	 * @return void
	 */
	function cck_wc_render_gallery_badge() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$badge = $product->is_on_sale() ? __( 'Sale', 'craft-commerce-kit' ) : ( $product->is_featured() ? __( 'Featured', 'craft-commerce-kit' ) : __( 'New', 'craft-commerce-kit' ) );

		echo '<div class="cck-wc-gallery__badge"><span>' . esc_html( $badge ) . '</span></div>';
	}
}

if ( ! function_exists( 'cck_wc_render_gallery_slots' ) ) {
	/**
	 * Render gallery thumbnails and future media slots.
	 *
	 * @return void
	 */
	function cck_wc_render_gallery_slots() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$asset_ids = array();
		$primary_id = absint( $product->get_image_id() );

		if ( $primary_id ) {
			$asset_ids[] = $primary_id;
		}

		$gallery_ids = method_exists( $product, 'get_gallery_image_ids' ) ? (array) $product->get_gallery_image_ids() : array();
		$asset_ids   = array_merge( $asset_ids, array_map( 'absint', $gallery_ids ) );
		$asset_ids   = array_values( array_unique( array_filter( $asset_ids ) ) );

		if ( empty( $asset_ids ) ) {
			$asset = cck_wc_get_product_card_demo_image_asset( $product );

			if ( empty( $asset['url'] ) ) {
				return;
			}

			$asset_ids = array( 0 );
		}

		echo '<div class="cck-product-media__thumbs" aria-label="' . esc_attr__( 'Product gallery thumbnails', 'craft-commerce-kit' ) . '">';

		$visible_assets = array_slice( $asset_ids, 0, 5 );

		foreach ( $visible_assets as $asset_id ) {
			$thumb_html = '';

			if ( $asset_id ) {
				$attachment_path = get_attached_file( $asset_id );
				$attachment_path = is_string( $attachment_path ) ? $attachment_path : '';

				if ( '' !== $attachment_path && file_exists( $attachment_path ) ) {
					$thumb_html = wp_get_attachment_image(
						$asset_id,
						'woocommerce_thumbnail',
						false,
						array(
							'class'    => 'cck-product-media__thumb-image',
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					);
				}
			}

			if ( '' === $thumb_html ) {
				$asset = cck_wc_get_product_card_demo_image_asset( $product );

				if ( ! empty( $asset['url'] ) ) {
					$thumb_html = sprintf(
						'<img src="%1$s" alt="%2$s" class="cck-product-media__thumb-image" loading="lazy" decoding="async" />',
						esc_url( $asset['url'] ),
						esc_attr( $product->get_name() )
					);
				}
			}

			if ( '' !== $thumb_html ) {
				echo '<button type="button" class="cck-product-media__thumb" aria-label="' . esc_attr( $product->get_name() ) . '">';
				echo wp_kses_post( $thumb_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</button>';
			}
		}

		$future_slots = max( 0, 5 - count( $visible_assets ) );

		for ( $index = 0; $index < $future_slots; $index++ ) {
			echo '<button type="button" class="cck-product-media__thumb cck-product-media__thumb--future" aria-label="' . esc_attr__( 'Daha fazla medya', 'craft-commerce-kit' ) . '">';
			echo '<span class="cck-product-media__thumb-image" aria-hidden="true">' . cck_render_svg_icon( 'arrow-right' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</button>';
		}

		echo '</div>';
	}
}

if ( ! function_exists( 'cck_wc_render_gallery_close' ) ) {
	/**
	 * Close the gallery component shell.
	 *
	 * @return void
	 */
	function cck_wc_render_gallery_close() {
		if ( ! is_product() ) {
			return;
		}

		echo '</div></div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_single_product_summary' ) ) {
	/**
	 * Render the premium product summary component.
	 *
	 * @return void
	 */
	function cck_wc_render_single_product_summary() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$product_id   = $product->get_id();
		$description   = wp_strip_all_tags( (string) $product->get_short_description() );
		$description   = '' !== $description ? wp_trim_words( $description, 28 ) : '';
		$features      = array();
		$attributes    = $product->get_attributes();
		$feature_index = 0;

		foreach ( $attributes as $attribute ) {
			if ( $feature_index >= 3 ) {
				break;
			}

			if ( ! is_object( $attribute ) || ! method_exists( $attribute, 'get_name' ) ) {
				continue;
			}

			$label = wc_attribute_label( $attribute->get_name() );
			$value = '';

			if ( $attribute->is_taxonomy() ) {
				$terms = wc_get_product_terms( $product_id, $attribute->get_name(), array( 'fields' => 'names' ) );
				$value = implode( ', ', array_slice( array_map( 'sanitize_text_field', $terms ), 0, 2 ) );
			} else {
				$options = $attribute->get_options();
				$value = implode( ', ', array_slice( array_map( 'sanitize_text_field', $options ), 0, 2 ) );
			}

			if ( '' !== $label && '' !== $value ) {
				$features[] = array(
					'label' => $label,
					'value' => $value,
				);
				$feature_index++;
			}
		}

		echo '<section class="cck-wc-section cck-wc-summary"><div class="cck-container cck-wc-section__inner cck-wc-summary__inner">';
		echo '<div class="cck-wc-summary__eyebrow cck-wc-summary__eyebrow--craft">' . esc_html__( 'EL YAPIMI ÜRETİM', 'craft-commerce-kit' ) . '</div>';
		echo '<h1 class="cck-wc-summary__title">' . esc_html( $product->get_name() ) . '</h1>';

		if ( $product->get_average_rating() ) {
			echo '<div class="cck-wc-summary__rating">' . wp_kses_post( wc_get_rating_html( (float) $product->get_average_rating(), (int) $product->get_rating_count() ) ) . '</div>';
		}

		echo '<div class="cck-wc-summary__price">' . wp_kses_post( $product->get_price_html() ) . '</div>';

		if ( '' !== $description ) {
			echo '<p class="cck-wc-summary__description">' . esc_html( $description ) . '</p>';
		}

		echo '<p class="cck-wc-summary__microcopy">' . esc_html__( 'Sessiz lüks deri işçiliği', 'craft-commerce-kit' ) . '</p>';

		echo '<div class="cck-product-options" aria-label="' . esc_attr__( 'Product purchase options', 'craft-commerce-kit' ) . '">';
		echo '<div class="cck-product-option-group cck-product-leather-color">';
		echo '<div class="cck-product-option-group__head"><span>' . esc_html__( 'Deri rengi', 'craft-commerce-kit' ) . '</span></div>';
		echo '<div class="cck-product-swatch-list" role="list">';
		$swatches = array(
			array( 'label' => __( 'Koyu Kahve', 'craft-commerce-kit' ), 'tone' => 'brown', 'active' => true ),
			array( 'label' => __( 'Camel', 'craft-commerce-kit' ), 'tone' => 'camel', 'active' => false ),
			array( 'label' => __( 'Siyah', 'craft-commerce-kit' ), 'tone' => 'black', 'active' => false ),
		);
		foreach ( $swatches as $swatch ) {
			echo '<button type="button" class="cck-product-swatch cck-product-swatch--' . esc_attr( $swatch['tone'] ) . ( ! empty( $swatch['active'] ) ? ' is-active' : '' ) . '" aria-pressed="' . esc_attr( ! empty( $swatch['active'] ) ? 'true' : 'false' ) . '">';
			echo '<span class="cck-product-swatch__circle" aria-hidden="true"></span>';
			echo '<span class="cck-product-swatch__label">' . esc_html( $swatch['label'] ) . '</span>';
			echo '</button>';
		}
		echo '</div>';
		echo '</div>';

		echo '<div class="cck-product-option-group cck-product-personalization">';
		echo '<div class="cck-product-option-group__head"><span>' . esc_html__( 'Kişiselleştirme', 'craft-commerce-kit' ) . '</span></div>';
		echo '<div class="cck-product-option-row">';
		echo '<span class="cck-product-option-row__label">' . esc_html__( 'Lazer baskı', 'craft-commerce-kit' ) . '</span>';
		echo '<input type="text" class="cck-product-option-row__input" placeholder="' . esc_attr__( 'İsim veya kısa not', 'craft-commerce-kit' ) . '" maxlength="18" />';
		echo '<span class="cck-product-option-row__helper">' . esc_html__( 'Maks. 18 karakter', 'craft-commerce-kit' ) . '</span>';
		echo '</div>';
		echo '</div>';

		echo '<div class="cck-product-option-group cck-product-delivery">';
		echo '<button type="button" class="cck-product-option-row cck-product-option-row--button">';
		echo '<span class="cck-product-option-row__label">' . esc_html__( 'Tahmini teslimat', 'craft-commerce-kit' ) . '</span>';
		echo '<span class="cck-product-option-row__chev" aria-hidden="true">' . cck_render_svg_icon( 'arrow-right' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</button>';
		echo '</div>';

		echo '<div class="cck-product-option-group cck-product-gift">';
		echo '<button type="button" class="cck-product-option-row cck-product-option-row--button">';
		echo '<span class="cck-product-option-row__label">' . esc_html__( 'Hediye paketi & notu', 'craft-commerce-kit' ) . '</span>';
		echo '<span class="cck-product-option-row__chev" aria-hidden="true">' . cck_render_svg_icon( 'arrow-right' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</button>';
		echo '</div>';

		do_action( 'cck_product_purchase_options', $product );
		echo '</div>';

		if ( ! empty( $features ) ) {
			echo '<div class="cck-wc-summary__features">';
			foreach ( $features as $feature ) {
				echo '<div class="cck-wc-summary__feature"><span>' . esc_html( $feature['label'] ) . '</span><strong>' . esc_html( $feature['value'] ) . '</strong></div>';
			}
			echo '</div>';
		}

		echo '<div class="cck-wc-summary__actions">';
		woocommerce_template_single_add_to_cart();
		echo '<button type="button" class="cck-button cck-button--secondary cck-wc-summary__slot" aria-label="' . esc_attr__( 'Wishlist', 'craft-commerce-kit' ) . '">' . cck_render_svg_icon( 'heart' ) . '<span>' . esc_html__( 'Wishlist', 'craft-commerce-kit' ) . '</span></button>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<button type="button" class="cck-button cck-button--ghost cck-wc-summary__slot" aria-label="' . esc_attr__( 'Quick view', 'craft-commerce-kit' ) . '">' . cck_render_svg_icon( 'eye' ) . '<span>' . esc_html__( 'Quick view', 'craft-commerce-kit' ) . '</span></button>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';

		$categories = wp_strip_all_tags( wc_get_product_category_list( $product->get_id(), ', ' ) );

		echo '<div class="cck-wc-summary__meta">';
		echo '<div><strong>' . esc_html__( 'SKU', 'craft-commerce-kit' ) . '</strong><span>' . esc_html( $product->get_sku() ? $product->get_sku() : __( '—', 'craft-commerce-kit' ) ) . '</span></div>';
		echo '<div><strong>' . esc_html__( 'Stok Durumu', 'craft-commerce-kit' ) . '</strong><span>' . esc_html( $product->is_in_stock() ? __( 'Stokta var', 'craft-commerce-kit' ) : __( 'Stokta yok', 'craft-commerce-kit' ) ) . '</span></div>';
		echo '<div><strong>' . esc_html__( 'Kategori', 'craft-commerce-kit' ) . '</strong><span>' . esc_html( '' !== $categories ? $categories : __( '—', 'craft-commerce-kit' ) ) . '</span></div>';
		echo '</div>';

		echo '</div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_product_features_section' ) ) {
	/**
	 * Render features and specifications sections.
	 *
	 * @return void
	 */
	function cck_wc_render_product_features_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$attributes = $product->get_attributes();
		$spec_rows   = array();

		foreach ( $attributes as $attribute ) {
			if ( ! is_object( $attribute ) || ! method_exists( $attribute, 'get_name' ) ) {
				continue;
			}

			$label = wc_attribute_label( $attribute->get_name() );
			$value = '';

			if ( $attribute->is_taxonomy() ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
				$value = implode( ', ', array_map( 'sanitize_text_field', $terms ) );
			} else {
				$value = implode( ', ', array_map( 'sanitize_text_field', $attribute->get_options() ) );
			}

			if ( '' !== $label && '' !== $value ) {
				$spec_rows[] = array(
					'label' => $label,
					'value' => $value,
				);
			}
		}

		$features = array();
		$short_description = wp_strip_all_tags( (string) $product->get_short_description() );
		if ( '' !== $short_description ) {
			$sentences = preg_split( '/[.!?]+/', $short_description );
			$sentences = array_filter( array_map( 'trim', is_array( $sentences ) ? $sentences : array() ) );
			$features = array_slice( $sentences, 0, 3 );
		}

		echo '<section class="cck-wc-section cck-wc-features"><div class="cck-container cck-wc-section__inner">';
		echo '<div class="cck-section-heading"><p class="cck-eyebrow">' . esc_html__( 'Features', 'craft-commerce-kit' ) . '</p><h2>' . esc_html__( 'Designed for premium presentation.', 'craft-commerce-kit' ) . '</h2></div>';
		echo '<div class="cck-wc-grid cck-wc-features__grid">';

		if ( ! empty( $features ) ) {
			foreach ( $features as $feature ) {
				echo '<article class="cck-wc-card cck-wc-feature-card"><span class="cck-wc-card__icon">' . cck_render_svg_icon( 'spark' ) . '</span><h3>' . esc_html( $feature ) . '</h3></article>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		} else {
			echo '<article class="cck-wc-card cck-wc-empty-card"><h3>' . esc_html__( 'No feature copy provided yet.', 'craft-commerce-kit' ) . '</h3><p>' . esc_html__( 'This product is falling back to a clean empty state until product content is added.', 'craft-commerce-kit' ) . '</p></article>';
		}

		echo '</div>';

		echo '<div class="cck-section-heading cck-wc-specs__heading"><p class="cck-eyebrow">' . esc_html__( 'Specifications', 'craft-commerce-kit' ) . '</p><h2>' . esc_html__( 'Product details at a glance.', 'craft-commerce-kit' ) . '</h2></div>';
		echo '<div class="cck-wc-specs">';

		if ( ! empty( $spec_rows ) ) {
			foreach ( $spec_rows as $row ) {
				echo '<dl class="cck-wc-spec"><dt>' . esc_html( $row['label'] ) . '</dt><dd>' . esc_html( $row['value'] ) . '</dd></dl>';
			}
		} else {
			echo '<article class="cck-wc-card cck-wc-empty-card"><h3>' . esc_html__( 'No specifications available.', 'craft-commerce-kit' ) . '</h3><p>' . esc_html__( 'Add product attributes to populate this section.', 'craft-commerce-kit' ) . '</p></article>';
		}

		echo '</div></div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_product_story_section' ) ) {
	/**
	 * Render the story section.
	 *
	 * @return void
	 */
	function cck_wc_render_product_story_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$content = get_post_field( 'post_content', $product->get_id() );
		$content = is_string( $content ) ? wp_strip_all_tags( $content ) : '';

		echo '<section class="cck-wc-section cck-wc-story"><div class="cck-container cck-wc-section__inner">';
		echo '<div class="cck-wc-story__panel">';
		echo '<div class="cck-section-heading"><p class="cck-eyebrow">' . esc_html__( 'Story', 'craft-commerce-kit' ) . '</p><h2>' . esc_html__( 'A product story with editorial presence.', 'craft-commerce-kit' ) . '</h2></div>';

		if ( '' !== $content ) {
			echo '<p class="cck-wc-story__text">' . esc_html( wp_trim_words( $content, 40 ) ) . '</p>';
		} else {
			echo '<p class="cck-wc-story__text">' . esc_html__( 'No long-form product story has been provided yet.', 'craft-commerce-kit' ) . '</p>';
		}

		echo '</div></div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_product_reviews_section' ) ) {
	/**
	 * Render a premium reviews section.
	 *
	 * @return void
	 */
	function cck_wc_render_product_reviews_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$per_page = 4;
		$paged    = max( 1, absint( get_query_var( 'cpage' ) ) );
		$args     = array(
			'post_id' => $product->get_id(),
			'status'  => 'approve',
			'number'  => $per_page,
			'offset'  => ( $paged - 1 ) * $per_page,
			'type'    => 'review',
			'orderby' => 'comment_date_gmt',
			'order'   => 'DESC',
		);
		$reviews  = get_comments( $args );
		$total    = (int) get_comments(
			array(
				'post_id' => $product->get_id(),
				'status'  => 'approve',
				'type'    => 'review',
				'count'   => true,
			)
		);

		echo '<section class="cck-wc-section cck-wc-reviews"><div class="cck-container cck-wc-section__inner">';
		echo '<div class="cck-section-heading"><p class="cck-eyebrow">' . esc_html__( 'Reviews', 'craft-commerce-kit' ) . '</p><h2>' . esc_html__( 'Verified customer feedback.', 'craft-commerce-kit' ) . '</h2></div>';
		echo '<div class="cck-wc-reviews__summary">';
		echo wp_kses_post( wc_get_rating_html( (float) $product->get_average_rating(), (int) $product->get_rating_count() ) );
		echo '<span class="cck-wc-reviews__meta">' . esc_html( sprintf( _n( '%s review', '%s reviews', (int) $product->get_review_count(), 'craft-commerce-kit' ), number_format_i18n( (int) $product->get_review_count() ) ) ) . '</span>';
		echo '</div>';

		if ( ! empty( $reviews ) ) {
			echo '<div class="cck-wc-review-grid">';
			foreach ( $reviews as $review ) {
				$rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );
				$user   = get_user_by( 'id', (int) $review->user_id );
				$verified = $user && function_exists( 'wc_customer_bought_product' ) && wc_customer_bought_product( $review->comment_author_email, $review->user_id, $product->get_id() );

				echo '<article class="cck-wc-review-card">';
				echo '<div class="cck-wc-review-card__head">';
				echo get_avatar( $review, 56, '', '', array( 'class' => 'cck-wc-review-card__avatar' ) );
				echo '<div><strong>' . esc_html( $review->comment_author ) . '</strong>';
				echo '<div class="cck-wc-review-card__rating">' . wp_kses_post( wc_get_rating_html( $rating ) ) . '</div></div>';
				echo '</div>';
				echo '<p>' . esc_html( wp_trim_words( wp_strip_all_tags( (string) $review->comment_content ), 28 ) ) . '</p>';
				echo '<div class="cck-wc-review-card__meta">';
				echo '<span>' . esc_html( get_comment_date( '', $review ) ) . '</span>';
				echo '<span>' . esc_html__( 'Verified buyer', 'craft-commerce-kit' ) . '</span>';
				if ( $verified ) {
					echo '<span class="cck-wc-review-card__badge">' . esc_html__( 'Verified', 'craft-commerce-kit' ) . '</span>';
				}
				echo '</div>';
				echo '</article>';
			}
			echo '</div>';
		} else {
			echo '<article class="cck-wc-card cck-wc-empty-card"><h3>' . esc_html__( 'No reviews yet.', 'craft-commerce-kit' ) . '</h3><p>' . esc_html__( 'When customers leave reviews, they will appear here in premium cards.', 'craft-commerce-kit' ) . '</p></article>';
		}

		if ( $total > $per_page ) {
			echo '<div class="cck-wc-pagination">' . paginate_comments_links( array( 'total' => (int) ceil( $total / $per_page ), 'current' => $paged, 'echo' => false ) ) . '</div>';
		}

		echo '</div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_product_faq_section' ) ) {
	/**
	 * Render a FAQ section.
	 *
	 * @return void
	 */
	function cck_wc_render_product_faq_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$faqs = get_post_meta( $product->get_id(), '_cck_faq', true );
		$faqs = is_array( $faqs ) ? $faqs : array();

		echo '<section class="cck-wc-section cck-wc-faq"><div class="cck-container cck-wc-section__inner">';
		echo '<div class="cck-section-heading"><p class="cck-eyebrow">' . esc_html__( 'FAQ', 'craft-commerce-kit' ) . '</p><h2>' . esc_html__( 'Common product questions.', 'craft-commerce-kit' ) . '</h2></div>';

		if ( ! empty( $faqs ) ) {
			echo '<div class="cck-wc-faq__list">';
			foreach ( $faqs as $faq ) {
				$question = isset( $faq['question'] ) ? sanitize_text_field( $faq['question'] ) : '';
				$answer   = isset( $faq['answer'] ) ? wp_strip_all_tags( $faq['answer'] ) : '';

				if ( '' === $question || '' === $answer ) {
					continue;
				}

				echo '<details class="cck-wc-faq__item"><summary>' . esc_html( $question ) . '</summary><p>' . esc_html( $answer ) . '</p></details>';
			}
			echo '</div>';
		} else {
			echo '<article class="cck-wc-card cck-wc-empty-card"><h3>' . esc_html__( 'No FAQ content yet.', 'craft-commerce-kit' ) . '</h3><p>' . esc_html__( 'Add FAQ meta or a future FAQ component to populate this area.', 'craft-commerce-kit' ) . '</p></article>';
		}

		echo '</div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_product_cta_section' ) ) {
	/**
	 * Render product CTA section.
	 *
	 * @return void
	 */
	function cck_wc_render_product_cta_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		echo '<section class="cck-wc-section cck-wc-cta"><div class="cck-container cck-wc-section__inner">';
		echo '<div class="cck-wc-cta__panel">';
		echo '<div class="cck-section-heading"><p class="cck-eyebrow">' . esc_html__( 'CTA', 'craft-commerce-kit' ) . '</p><h2>' . esc_html__( 'Ready to add this piece to your storefront?', 'craft-commerce-kit' ) . '</h2></div>';
		echo '<p class="cck-wc-cta__text">' . esc_html__( 'Keep the transaction logic native to WooCommerce while elevating the presentation with CCK’s visual system.', 'craft-commerce-kit' ) . '</p>';
		echo '<div class="cck-wc-cta__actions">';
		echo '<a class="cck-button cck-button--primary" href="' . esc_url( get_permalink( $product->get_id() ) ) . '">' . esc_html__( 'Explore product', 'craft-commerce-kit' ) . '</a>';
		echo '<a class="cck-button cck-button--secondary" href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">' . esc_html__( 'Continue shopping', 'craft-commerce-kit' ) . '</a>';
		echo '</div>';
		echo '</div></div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_related_product_section' ) ) {
	/**
	 * Render a related product grid section.
	 *
	 * @param string $section_id Section ID.
	 * @param string $eyebrow Section eyebrow.
	 * @param string $title Section title.
	 * @param array  $product_ids Product IDs to render.
	 * @param string $empty_title Empty state title.
	 * @param string $empty_text Empty state text.
	 * @return void
	 */
	function cck_wc_render_related_product_section( $section_id, $eyebrow, $title, array $product_ids, $empty_title, $empty_text ) {
		if ( ! is_product() ) {
			return;
		}

		$product_ids = array_values( array_unique( array_filter( array_map( 'absint', $product_ids ) ) ) );

		echo '<section class="cck-wc-section cck-wc-' . esc_attr( sanitize_key( $section_id ) ) . '"><div class="cck-container cck-wc-section__inner">';
		echo '<div class="cck-section-heading"><p class="cck-eyebrow">' . esc_html( $eyebrow ) . '</p><h2>' . esc_html( $title ) . '</h2></div>';

		if ( empty( $product_ids ) ) {
			echo '<article class="cck-wc-card cck-wc-empty-card"><h3>' . esc_html( $empty_title ) . '</h3><p>' . esc_html( $empty_text ) . '</p></article>';
			echo '</div></section>';

			return;
		}

		echo '<div class="cck-wc-grid cck-wc-related__grid">';

		foreach ( array_slice( $product_ids, 0, 4 ) as $product_id ) {
			$product_html = cck_wc_render_product_card_markup( $product_id, $section_id );

			if ( '' !== $product_html ) {
				echo wp_kses_post( $product_html );
			}
		}

		echo '</div></div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_product_related_products_section' ) ) {
	/**
	 * Render product related products.
	 *
	 * @return void
	 */
	function cck_wc_render_product_related_products_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$related_ids = function_exists( 'wc_get_related_products' ) ? wc_get_related_products( $product->get_id(), 4, $product->get_upsell_ids() ) : array();

		cck_wc_render_related_product_section(
			'related',
			__( 'Related Products', 'craft-commerce-kit' ),
			__( 'Recommended pieces that stay in the same world.', 'craft-commerce-kit' ),
			$related_ids,
			__( 'No related products yet.', 'craft-commerce-kit' ),
			__( 'This catalog entry does not have a related product set yet.', 'craft-commerce-kit' )
		);
	}
}

if ( ! function_exists( 'cck_wc_render_product_upsells_section' ) ) {
	/**
	 * Render product upsells.
	 *
	 * @return void
	 */
	function cck_wc_render_product_upsells_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		cck_wc_render_related_product_section(
			'upsells',
			__( 'Upsells', 'craft-commerce-kit' ),
			__( 'A more premium step up from this product.', 'craft-commerce-kit' ),
			$product->get_upsell_ids(),
			__( 'No upsells yet.', 'craft-commerce-kit' ),
			__( 'This product does not have upsells assigned yet.', 'craft-commerce-kit' )
		);
	}
}

if ( ! function_exists( 'cck_wc_render_product_cross_sells_section' ) ) {
	/**
	 * Render product cross-sells.
	 *
	 * @return void
	 */
	function cck_wc_render_product_cross_sells_section() {
		if ( ! is_product() ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		cck_wc_render_related_product_section(
			'cross-sells',
			__( 'Cross-sells', 'craft-commerce-kit' ),
			__( 'Complementary pieces that make the set feel complete.', 'craft-commerce-kit' ),
			$product->get_cross_sell_ids(),
			__( 'No cross-sells yet.', 'craft-commerce-kit' ),
			__( 'This product does not have cross-sells assigned yet.', 'craft-commerce-kit' )
		);
	}
}

if ( ! function_exists( 'cck_wc_render_cart_shell_open' ) ) {
	/**
	 * Open the cart shell.
	 *
	 * @return void
	 */
	function cck_wc_render_cart_shell_open() {
		if ( ! is_cart() ) {
			return;
		}

		echo '<section class="cck-wc-shell cck-wc-cart"><div class="cck-container cck-wc-shell__inner"><div class="cck-wc-page-header"><p class="cck-eyebrow">' . esc_html__( 'Cart', 'craft-commerce-kit' ) . '</p><h1>' . esc_html__( 'Your selected pieces', 'craft-commerce-kit' ) . '</h1></div>';
	}
}

if ( ! function_exists( 'cck_wc_render_cart_shell_close' ) ) {
	/**
	 * Close the cart shell.
	 *
	 * @return void
	 */
	function cck_wc_render_cart_shell_close() {
		if ( ! is_cart() ) {
			return;
		}

		echo '</div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_checkout_shell_open' ) ) {
	/**
	 * Open the checkout shell.
	 *
	 * @return void
	 */
	function cck_wc_render_checkout_shell_open() {
		if ( ! is_checkout() ) {
			return;
		}

		echo '<section class="cck-wc-shell cck-wc-checkout"><div class="cck-container cck-wc-shell__inner"><div class="cck-wc-page-header"><p class="cck-eyebrow">' . esc_html__( 'Checkout', 'craft-commerce-kit' ) . '</p><h1>' . esc_html__( 'Premium checkout shell', 'craft-commerce-kit' ) . '</h1></div>';
	}
}

if ( ! function_exists( 'cck_wc_render_checkout_shell_close' ) ) {
	/**
	 * Close the checkout shell.
	 *
	 * @return void
	 */
	function cck_wc_render_checkout_shell_close() {
		if ( ! is_checkout() ) {
			return;
		}

		echo '</div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_account_shell_open' ) ) {
	/**
	 * Open the my account shell.
	 *
	 * @return void
	 */
	function cck_wc_render_account_shell_open() {
		if ( ! is_account_page() ) {
			return;
		}

		echo '<section class="cck-wc-shell cck-wc-account"><div class="cck-container cck-wc-shell__inner"><div class="cck-wc-page-header"><p class="cck-eyebrow">' . esc_html__( 'My Account', 'craft-commerce-kit' ) . '</p><h1>' . esc_html__( 'Customer dashboard', 'craft-commerce-kit' ) . '</h1></div>';
	}
}

if ( ! function_exists( 'cck_wc_render_account_shell_close' ) ) {
	/**
	 * Close the my account shell.
	 *
	 * @return void
	 */
	function cck_wc_render_account_shell_close() {
		if ( ! is_account_page() ) {
			return;
		}

		echo '</div></section>';
	}
}

if ( ! function_exists( 'cck_wc_wrap_storefront_content' ) ) {
	/**
	 * Wrap cart, checkout, and account content in a premium shell.
	 *
	 * @param string $content Page content.
	 * @return string
	 */
	function cck_wc_wrap_storefront_content( $content ) {
		if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( ! is_cart() && ! is_checkout() && ! is_account_page() ) {
			return $content;
		}

		$context = is_cart() ? 'cart' : ( is_checkout() ? 'checkout' : 'account' );
		$title   = is_cart() ? __( 'Your selected pieces', 'craft-commerce-kit' ) : ( is_checkout() ? __( 'Premium checkout shell', 'craft-commerce-kit' ) : __( 'Customer dashboard', 'craft-commerce-kit' ) );
		$eyebrow = is_cart() ? __( 'Cart', 'craft-commerce-kit' ) : ( is_checkout() ? __( 'Checkout', 'craft-commerce-kit' ) : __( 'My Account', 'craft-commerce-kit' ) );

		return sprintf(
			'<section class="cck-wc-shell cck-wc-%1$s"><div class="cck-container cck-wc-shell__inner"><div class="cck-wc-page-header"><p class="cck-eyebrow">%2$s</p><h1>%3$s</h1></div><div class="cck-wc-shell__content">%4$s</div></div></section>',
			esc_attr( $context ),
			esc_html( $eyebrow ),
			esc_html( $title ),
			$content
		);
	}
}

if ( ! function_exists( 'cck_wc_strip_single_product_extra_sections' ) ) {
	/**
	 * Remove remaining upsell / related product sections from product content.
	 *
	 * @param string $content Page content.
	 * @return string
	 */
	function cck_wc_strip_single_product_extra_sections( $content ) {
		if ( is_admin() || ! is_product() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( false === strpos( $content, 'up-sells' ) && false === strpos( $content, 'woocommerce/product-collection' ) && false === strpos( $content, 'cross-sells' ) && false === strpos( $content, 'related products' ) ) {
			return $content;
		}

		$previous_errors = libxml_use_internal_errors( true );
		$document        = new DOMDocument( '1.0', 'UTF-8' );
		$loaded          = $document->loadHTML( '<?xml encoding="utf-8" ?><div id="cck-product-content-filter">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		if ( ! $loaded ) {
			libxml_clear_errors();
			libxml_use_internal_errors( $previous_errors );
			return $content;
		}

		$xpath = new DOMXPath( $document );
		$nodes  = $xpath->query(
			'//*[@data-block-name="woocommerce/product-collection"] | //section[contains(concat(" ", normalize-space(@class), " "), " up-sells ")] | //section[contains(concat(" ", normalize-space(@class), " "), " upsells ")] | //section[contains(concat(" ", normalize-space(@class), " "), " cross-sells ")] | //section[contains(concat(" ", normalize-space(@class), " "), " related ")]'
		);

		if ( $nodes instanceof DOMNodeList && $nodes->length > 0 ) {
			$remove = array();

			foreach ( $nodes as $node ) {
				$remove[] = $node;
			}

			foreach ( array_reverse( $remove ) as $node ) {
				if ( $node->parentNode ) {
					$node->parentNode->removeChild( $node );
				}
			}
		}

		$wrapper = $document->getElementById( 'cck-product-content-filter' );
		$cleaned = '';

		if ( $wrapper instanceof DOMElement ) {
			foreach ( $wrapper->childNodes as $child ) {
				$cleaned .= $document->saveHTML( $child );
			}
		}

		libxml_clear_errors();
		libxml_use_internal_errors( $previous_errors );

		return '' !== $cleaned ? $cleaned : $content;
	}
}

if ( ! function_exists( 'cck_wc_filter_single_product_output_buffer' ) ) {
	/**
	 * Filter final single-product HTML and remove any remaining recommendation sections.
	 *
	 * @param string $html Full page HTML.
	 * @return string
	 */
	function cck_wc_filter_single_product_output_buffer( $html ) {
		if ( is_admin() || ! is_product() ) {
			return $html;
		}

		$patterns = array(
			'#<section[^>]+class="[^"]*\bup-sells\b[^"]*">.*?</section>#si',
			'#<section[^>]+class="[^"]*\bupsells\b[^"]*">.*?</section>#si',
			'#<section[^>]+class="[^"]*\bcross-sells\b[^"]*">.*?</section>#si',
			'#<section[^>]+class="[^"]*\brelated\b[^"]*">.*?</section>#si',
			'#<div[^>]+data-block-name="woocommerce/product-collection"[^>]*>.*?</div>\s*#si',
		);

		return preg_replace( $patterns, '', $html );
	}
}

if ( ! function_exists( 'cck_wc_start_single_product_output_buffer' ) ) {
	/**
	 * Start a single-product output buffer so we can remove the remaining recommendation sections safely.
	 *
	 * @return void
	 */
	function cck_wc_start_single_product_output_buffer() {
		if ( is_admin() || ! is_product() ) {
			return;
		}

		ob_start( 'cck_wc_filter_single_product_output_buffer' );
	}
}

if ( ! function_exists( 'cck_register_woocommerce_storefront_hooks' ) ) {
	/**
	 * Register WooCommerce presentation hooks.
	 *
	 * @return void
	 */
	function cck_register_woocommerce_storefront_hooks() {
		if ( ! cck_is_woocommerce_active() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', 'cck_enqueue_woocommerce_storefront_assets', 20 );
		add_filter( 'body_class', 'cck_wc_body_classes' );
		add_filter( 'loop_shop_columns', 'cck_wc_loop_shop_columns', 20 );

		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		add_action( 'woocommerce_before_shop_loop', 'cck_wc_render_shop_archive_open', 1 );
		add_action( 'woocommerce_after_shop_loop', 'cck_wc_render_shop_archive_close', 99 );
		add_action( 'woocommerce_before_shop_loop_item', 'cck_wc_render_loop_product_card', 10 );

		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

		add_action( 'woocommerce_before_single_product_summary', 'cck_wc_render_gallery_open', 1 );
		add_action( 'woocommerce_before_single_product_summary', 'cck_wc_render_gallery_badge', 10 );
		add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		add_action( 'woocommerce_before_single_product_summary', 'cck_wc_render_gallery_close', 99 );

		add_action( 'woocommerce_single_product_summary', 'cck_wc_render_single_product_summary', 1 );
		add_action( 'woocommerce_after_single_product_summary', 'cck_wc_render_product_service_strip', 26 );

		add_filter( 'woocommerce_single_product_image_thumbnail_html', 'cck_wc_render_single_product_fallback_image_html', 10, 2 );
		add_filter( 'woocommerce_cart_item_thumbnail', 'cck_wc_render_cart_item_thumbnail', 10, 3 );
		add_filter( 'woocommerce_widget_cart_item_image', 'cck_wc_render_widget_cart_item_image', 10, 2 );
		add_filter( 'render_block', 'cck_wc_strip_duplicate_archive_blocks', 10, 2 );
		add_filter( 'the_content', 'cck_wc_strip_single_product_extra_sections', 20 );
		add_filter( 'the_content', 'cck_wc_wrap_storefront_content', 9 );
		add_action( 'template_redirect', 'cck_wc_start_single_product_output_buffer', 0 );
		add_action( 'wp', 'cck_wc_remove_default_loop_hooks', 20 );
	}
}

if ( ! function_exists( 'cck_wc_render_product_service_strip' ) ) {
	/**
	 * Render a premium service/trust strip.
	 *
	 * @return void
	 */
	function cck_wc_render_product_service_strip() {
		if ( ! is_product() ) {
			return;
		}

		echo '<section class="cck-wc-section cck-wc-service-strip cck-trust"><div class="cck-container cck-wc-section__inner">';
		echo '<div class="cck-trust__grid">';

		$items = array(
			array( 'icon' => 'shield', 'title' => __( 'Ücretsiz Kargo', 'craft-commerce-kit' ), 'text' => __( '1.000 TL ve üzeri siparişlerde ücretsiz teslimat.', 'craft-commerce-kit' ) ),
			array( 'icon' => 'leaf', 'title' => __( 'Kolay İade', 'craft-commerce-kit' ), 'text' => __( '14 gün içinde kolay iade ve değişim.', 'craft-commerce-kit' ) ),
			array( 'icon' => 'star', 'title' => __( 'Güvenli Ödeme', 'craft-commerce-kit' ), 'text' => __( 'Tüm ödemeleriniz SSL koruması altında.', 'craft-commerce-kit' ) ),
			array( 'icon' => 'bag', 'title' => __( '2 Yıl Garanti', 'craft-commerce-kit' ), 'text' => __( 'Tüm ürünlerde iki yıl garanti desteği.', 'craft-commerce-kit' ) ),
		);

		foreach ( $items as $item ) {
			echo '<article class="cck-trust__item"><span class="cck-trust__icon">' . cck_render_svg_icon( $item['icon'] ) . '</span><h3>' . esc_html( $item['title'] ) . '</h3><p>' . esc_html( $item['text'] ) . '</p></article>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div></div></section>';
	}
}

if ( ! function_exists( 'cck_wc_render_cart_item_thumbnail' ) ) {
	/**
	 * Render a resilient cart item thumbnail.
	 *
	 * @param string $thumbnail Existing thumbnail HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string
	 */
	function cck_wc_render_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
		unset( $cart_item_key );

		if ( empty( $cart_item['data'] ) || ! $cart_item['data'] instanceof WC_Product ) {
			return $thumbnail;
		}

		$definition = cck_wc_get_product_card_definition( $cart_item['data'] );

		if ( empty( $definition['image_html'] ) ) {
			return $thumbnail;
		}

		return $definition['image_html'];
	}
}

if ( ! function_exists( 'cck_wc_render_single_product_fallback_image_html' ) ) {
	/**
	 * Replace broken single-product gallery images with bundled demo assets.
	 *
	 * @param string $html Existing image HTML.
	 * @param int    $attachment_id Gallery attachment ID.
	 * @return string
	 */
	function cck_wc_render_single_product_fallback_image_html( $html, $attachment_id = 0 ) {
		if ( ! is_product() ) {
			return $html;
		}

		if ( $attachment_id ) {
			$attachment_path = get_attached_file( absint( $attachment_id ) );
			$attachment_path = is_string( $attachment_path ) ? $attachment_path : '';

			if ( '' !== $attachment_path && file_exists( $attachment_path ) ) {
				return $html;
			}
		}

		global $product;

		if ( ! $product instanceof WC_Product ) {
			return $html;
		}

		$asset = cck_wc_get_product_card_demo_image_asset( $product );

		if ( ! empty( $asset ) ) {
			$fallback = cck_wc_build_demo_image_html( $product, $asset );

			if ( '' !== $fallback ) {
				return sprintf(
					'<div class="woocommerce-product-gallery__image"><a href="%1$s">%2$s</a></div>',
					esc_url( $asset['url'] ),
					$fallback
				);
			}
		}

		if ( function_exists( 'wc_placeholder_img' ) ) {
			return wc_placeholder_img( 'woocommerce_thumbnail' );
		}

		return $html;
	}
}

if ( ! function_exists( 'cck_wc_render_widget_cart_item_image' ) ) {
	/**
	 * Render a resilient mini-cart thumbnail.
	 *
	 * @param string $thumbnail Existing thumbnail HTML.
	 * @param array  $cart_item Cart item data.
	 * @return string
	 */
	function cck_wc_render_widget_cart_item_image( $thumbnail, $cart_item ) {
		if ( empty( $cart_item['data'] ) || ! $cart_item['data'] instanceof WC_Product ) {
			return $thumbnail;
		}

		$definition = cck_wc_get_product_card_definition( $cart_item['data'] );

		if ( empty( $definition['image_html'] ) ) {
			return $thumbnail;
		}

		return $definition['image_html'];
	}
}

if ( ! function_exists( 'cck_wc_remove_default_loop_hooks' ) ) {
	/**
	 * Remove WooCommerce default loop callbacks after WooCommerce has finished registering them.
	 *
	 * @return void
	 */
	function cck_wc_remove_default_loop_hooks() {
		if ( ! cck_is_woocommerce_active() ) {
			return;
		}

		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	}
}

if ( ! function_exists( 'cck_wc_strip_duplicate_archive_blocks' ) ) {
	/**
	 * Strip default WooCommerce archive product blocks when the CCK card renderer is already handling the item.
	 *
	 * @param string $block_content Rendered block content.
	 * @param array  $block Parsed block.
	 * @return string
	 */
	function cck_wc_strip_duplicate_archive_blocks( $block_content, $block ) {
		if ( ! cck_wc_is_storefront_request() ) {
			return $block_content;
		}

		$block_name = isset( $block['blockName'] ) ? trim( (string) $block['blockName'] ) : '';
		if ( is_product() ) {
			$product_strip_blocks = array(
				'core/post-excerpt',
				'woocommerce/add-to-cart-form',
				'woocommerce/product-meta',
				'woocommerce/product-collection',
			);

			if ( in_array( $block_name, $product_strip_blocks, true ) ) {
				return '';
			}
		}

		if ( is_product() && 'woocommerce/product-image-gallery' === $block_name ) {
			ob_start();
			cck_wc_render_gallery_slots();
			$thumbs = trim( (string) ob_get_clean() );

			if ( '' !== $thumbs ) {
				return $block_content . $thumbs;
			}
		}

		$strip_blocks = array(
			'core/post-title',
			'woocommerce/product-image',
			'woocommerce/product-title',
			'woocommerce/product-price',
			'woocommerce/product-button',
		);

		if ( ! in_array( $block_name, $strip_blocks, true ) ) {
			return $block_content;
		}

		return '';
	}
}

if ( ! function_exists( 'cck_wc_body_classes' ) ) {
	/**
	 * Add storefront body classes.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	function cck_wc_body_classes( $classes ) {
		if ( ! cck_wc_is_storefront_request() ) {
			return $classes;
		}

		$classes[] = 'cck-woo-active';
		$classes[] = 'cck-woo-layout-' . cck_wc_get_shop_layout();
		$classes[] = 'cck-woo-columns-' . cck_wc_get_shop_columns();

		if ( is_product() ) {
			$classes[] = 'cck-product-page';
		}

		return array_unique( $classes );
	}
}

if ( ! function_exists( 'cck_wc_loop_shop_columns' ) ) {
	/**
	 * Filter shop columns.
	 *
	 * @param int $columns Default columns.
	 * @return int
	 */
	function cck_wc_loop_shop_columns( $columns ) {
		if ( ! cck_wc_is_storefront_request() ) {
			return absint( $columns );
		}

		return cck_wc_get_shop_columns();
	}
}

if ( cck_is_woocommerce_active() ) {
	cck_register_woocommerce_storefront_hooks();
}
