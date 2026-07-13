<?php
/**
 * Image text component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_image_text' ) ) {
	/**
	 * Render image and text.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_image_text( array $args = array() ) {
		$args = shortcode_atts(
			array(
				'title'        => '',
				'text'         => '',
				'button_label' => '',
				'button_url'   => '',
				'image_url'    => function_exists( 'cck_get_demo_asset' ) ? cck_get_demo_asset( 'story.webp', __( 'Story image', 'craft-commerce-kit' ) )['url'] : '',
				'reverse'      => 'false',
			),
			$args,
			'cck_image_text'
		);

		$is_reverse = filter_var( $args['reverse'], FILTER_VALIDATE_BOOLEAN );

		ob_start();
		?>
		<section class="cck-section cck-image-text<?php echo $is_reverse ? ' cck-image-text--reverse' : ''; ?>">
			<div class="cck-container cck-image-text__inner">
				<div class="cck-image-text__media">
					<?php if ( '' !== $args['image_url'] ) : ?>
						<img src="<?php echo esc_url( $args['image_url'] ); ?>" alt="<?php echo esc_attr__( 'Story image', 'craft-commerce-kit' ); ?>" loading="lazy" decoding="async">
					<?php else : ?>
						<div class="cck-placeholder cck-placeholder--image-text" aria-hidden="true"></div>
					<?php endif; ?>
				</div>
				<div class="cck-image-text__content">
					<?php if ( '' !== $args['title'] ) : ?>
						<h2><?php echo esc_html( $args['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( '' !== $args['text'] ) : ?>
						<p><?php echo esc_html( $args['text'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $args['button_label'] ) : ?>
						<a class="cck-button cck-button--primary" href="<?php echo esc_url( $args['button_url'] ); ?>"><?php echo esc_html( $args['button_label'] ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
