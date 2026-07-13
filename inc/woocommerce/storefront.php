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
			'4-column'  => 4,
			'masonry'   => 4,
			'editorial' => 3,
			'luxury'    => 4,
		);

		$columns = isset( $map[ $layout ] ) ? $map[ $layout ] : 4;

		return absint( apply_filters( 'cck_wc_shop_columns', $columns, $layout ) );
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
		$image_id  = $product->get_image_id();
		$image_html = $image_id ? wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'loading' => 'lazy', 'decoding' => 'async' ) ) : '';
		$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : '';
		$short_desc  = wp_strip_all_tags( (string) $product->get_short_description() );
		$short_desc  = '' !== $short_desc ? wp_trim_words( $short_desc, 14 ) : '';
		$price_html  = $product->get_price_html();
		$rating_html = function_exists( 'wc_get_rating_html' ) ? wc_get_rating_html( (float) $product->get_average_rating(), (int) $product->get_rating_count() ) : '';
		$badge_html  = '';

		if ( $product->is_on_sale() ) {
			$badge_html = '<span class="cck-product-card__badge cck-product-card__badge--sale">' . esc_html__( 'Sale', 'craft-commerce-kit' ) . '</span>';
		} elseif ( $product->is_featured() ) {
			$badge_html = '<span class="cck-product-card__badge cck-product-card__badge--featured">' . esc_html__( 'Featured', 'craft-commerce-kit' ) . '</span>';
		}

		$wishlist_slot = apply_filters( 'cck_wc_product_card_wishlist_slot', '', $product, $context );
		$quick_view_slot = apply_filters( 'cck_wc_product_card_quick_view_slot', '', $product, $context );

		if ( '' === $wishlist_slot ) {
			$wishlist_slot = sprintf(
				'<button type="button" class="cck-product-card__slot-button cck-product-card__slot-button--wishlist" aria-label="%1$s">%2$s<span>%3$s</span></button>',
				esc_attr__( 'Add to wishlist', 'craft-commerce-kit' ),
				cck_render_svg_icon( 'heart' ),
				esc_html__( 'Wishlist', 'craft-commerce-kit' )
			);
		}

		if ( '' === $quick_view_slot ) {
			$quick_view_slot = sprintf(
				'<button type="button" class="cck-product-card__slot-button cck-product-card__slot-button--quick-view" aria-label="%1$s">%2$s<span>%3$s</span></button>',
				esc_attr__( 'Quick view', 'craft-commerce-kit' ),
				cck_render_svg_icon( 'eye' ),
				esc_html__( 'Quick view', 'craft-commerce-kit' )
			);
		}

		ob_start();
		if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			woocommerce_template_loop_add_to_cart();
		}
		$add_to_cart_html = trim( ob_get_clean() );

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

				<div class="cck-product-card__slots" aria-label="<?php esc_attr_e( 'Product actions', 'craft-commerce-kit' ); ?>">
					<?php echo wp_kses_post( $card['wishlist_html'] ); ?>
					<?php echo wp_kses_post( $card['quick_view_html'] ); ?>
				</div>
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

				<div class="cck-product-card__actions">
					<?php echo wp_kses_post( $card['add_to_cart_html'] ); ?>
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
	 * Render gallery future slots.
	 *
	 * @return void
	 */
	function cck_wc_render_gallery_slots() {
		if ( ! is_product() ) {
			return;
		}

		echo '<div class="cck-wc-gallery__slots" aria-label="' . esc_attr__( 'Gallery tools', 'craft-commerce-kit' ) . '">';
		echo '<span class="cck-wc-gallery__slot">' . cck_render_svg_icon( 'eye' ) . '<span>' . esc_html__( 'Zoom', 'craft-commerce-kit' ) . '</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<span class="cck-wc-gallery__slot">' . cck_render_svg_icon( 'arrow-right' ) . '<span>' . esc_html__( 'Fullscreen', 'craft-commerce-kit' ) . '</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<span class="cck-wc-gallery__slot cck-wc-gallery__slot--muted">' . esc_html__( 'Video slot', 'craft-commerce-kit' ) . '</span>';
		echo '<span class="cck-wc-gallery__slot cck-wc-gallery__slot--muted">' . esc_html__( '3D slot', 'craft-commerce-kit' ) . '</span>';
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
		echo '<div class="cck-wc-summary__eyebrow">' . esc_html__( 'Product', 'craft-commerce-kit' ) . '</div>';
		echo '<h1 class="cck-wc-summary__title">' . esc_html( $product->get_name() ) . '</h1>';

		if ( $product->get_average_rating() ) {
			echo '<div class="cck-wc-summary__rating">' . wp_kses_post( wc_get_rating_html( (float) $product->get_average_rating(), (int) $product->get_rating_count() ) ) . '</div>';
		}

		echo '<div class="cck-wc-summary__price">' . wp_kses_post( $product->get_price_html() ) . '</div>';

		if ( '' !== $description ) {
			echo '<p class="cck-wc-summary__description">' . esc_html( $description ) . '</p>';
		}

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

		echo '<div class="cck-wc-summary__meta">';
		echo '<div><strong>' . esc_html__( 'SKU', 'craft-commerce-kit' ) . '</strong><span>' . esc_html( $product->get_sku() ? $product->get_sku() : __( '—', 'craft-commerce-kit' ) ) . '</span></div>';
		echo '<div><strong>' . esc_html__( 'Stock', 'craft-commerce-kit' ) . '</strong><span>' . esc_html( $product->is_in_stock() ? __( 'In stock', 'craft-commerce-kit' ) : __( 'Out of stock', 'craft-commerce-kit' ) ) . '</span></div>';
		echo '<div><strong>' . esc_html__( 'Type', 'craft-commerce-kit' ) . '</strong><span>' . esc_html( wc_get_product_types() && isset( wc_get_product_types()[ $product->get_type() ] ) ? wc_get_product_types()[ $product->get_type() ] : ucfirst( $product->get_type() ) ) . '</span></div>';
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
		add_action( 'woocommerce_before_single_product_summary', 'cck_wc_render_gallery_slots', 30 );
		add_action( 'woocommerce_before_single_product_summary', 'cck_wc_render_gallery_close', 99 );

		add_action( 'woocommerce_single_product_summary', 'cck_wc_render_single_product_summary', 1 );
		add_action( 'woocommerce_after_single_product_summary', 'cck_wc_render_product_features_section', 5 );
		add_action( 'woocommerce_after_single_product_summary', 'cck_wc_render_product_story_section', 10 );
		add_action( 'woocommerce_after_single_product_summary', 'cck_wc_render_product_reviews_section', 15 );
		add_action( 'woocommerce_after_single_product_summary', 'cck_wc_render_product_faq_section', 20 );
		add_action( 'woocommerce_after_single_product_summary', 'cck_wc_render_product_cta_section', 25 );

		add_action( 'woocommerce_before_cart', 'cck_wc_render_cart_shell_open', 1 );
		add_action( 'woocommerce_after_cart', 'cck_wc_render_cart_shell_close', 99 );
		add_action( 'woocommerce_before_checkout_form', 'cck_wc_render_checkout_shell_open', 1 );
		add_action( 'woocommerce_after_checkout_form', 'cck_wc_render_checkout_shell_close', 99 );
		add_action( 'woocommerce_before_main_content', 'cck_wc_render_account_shell_open', 1 );
		add_action( 'woocommerce_after_main_content', 'cck_wc_render_account_shell_close', 99 );
		add_filter( 'the_content', 'cck_wc_wrap_storefront_content', 9 );
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
