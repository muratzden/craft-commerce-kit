<?php
/**
 * Hero component.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_hero' ) ) {
	/**
	 * Render a hero component.
	 *
	 * @param array $args Component args.
	 * @return string
	 */
	function cck_component_hero( array $args = array() ) {
		$args = shortcode_atts(
			array(
				'eyebrow'         => '',
				'title'           => '',
				'text'            => '',
				'primary_label'   => '',
				'primary_url'     => '',
				'secondary_label' => '',
				'secondary_url'   => '',
				'image_url'       => '',
			),
			$args,
			'cck_hero'
		);

		ob_start();
		?>
		<section class="cck-section cck-hero">
			<div class="cck-container cck-hero__inner">
				<div class="cck-hero__content">
					<?php if ( '' !== $args['eyebrow'] ) : ?>
						<p class="cck-eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $args['title'] ) : ?>
						<h1><?php echo esc_html( $args['title'] ); ?></h1>
					<?php endif; ?>
					<?php if ( '' !== $args['text'] ) : ?>
						<p class="cck-hero__text"><?php echo esc_html( $args['text'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $args['primary_label'] || '' !== $args['secondary_label'] ) : ?>
						<div class="cck-buttons">
							<?php if ( '' !== $args['primary_label'] ) : ?>
								<a class="cck-button cck-button--primary" href="<?php echo esc_url( $args['primary_url'] ); ?>"><?php echo esc_html( $args['primary_label'] ); ?></a>
							<?php endif; ?>
							<?php if ( '' !== $args['secondary_label'] ) : ?>
								<a class="cck-button cck-button--secondary" href="<?php echo esc_url( $args['secondary_url'] ); ?>"><?php echo esc_html( $args['secondary_label'] ); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="cck-hero__media">
					<?php if ( '' !== $args['image_url'] ) : ?>
						<img src="<?php echo esc_url( $args['image_url'] ); ?>" alt="" loading="lazy">
					<?php else : ?>
						<div class="cck-placeholder" aria-hidden="true"></div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
