<?php
/**
 * Hero component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_hero' ) ) {
	/**
	 * Render the Hero component.
	 *
	 * @param array $atts     Sanitized component values.
	 * @param array $manifest Component manifest data.
	 * @return string
	 */
	function cck_component_package_render_hero( $atts = array(), $manifest = array() ) {
		unset( $manifest );

		$atts = shortcode_atts(
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
			is_array( $atts ) ? $atts : array(),
			'cck_hero'
		);

		if ( function_exists( 'cck_component_platform_render_hero_adapter' ) ) {
			$output = cck_component_platform_render_hero_adapter( $atts );

			if ( '' !== $output ) {
				return $output;
			}
		}

		ob_start();
		?>
		<section class="cck-section cck-hero">
			<div class="cck-container">
				<div class="cck-hero__inner">
					<div class="cck-hero__content">
						<?php if ( '' !== $atts['eyebrow'] ) : ?>
							<div class="cck-hero__eyebrow-wrap">
								<span class="cck-eyebrow">
									<?php echo esc_html( $atts['eyebrow'] ); ?>
								</span>
							</div>
						<?php endif; ?>

						<?php if ( '' !== $atts['title'] ) : ?>
							<h1 class="cck-hero__title">
								<?php echo esc_html( $atts['title'] ); ?>
							</h1>
						<?php endif; ?>

						<?php if ( '' !== $atts['text'] ) : ?>
							<div class="cck-hero__description">
								<p class="cck-hero__text">
									<?php echo esc_html( $atts['text'] ); ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ( '' !== $atts['primary_label'] || '' !== $atts['secondary_label'] ) : ?>
							<div class="cck-hero__actions">
								<?php if ( '' !== $atts['primary_label'] ) : ?>
									<a
										class="cck-button cck-button--primary"
										href="<?php echo esc_url( $atts['primary_url'] ); ?>">
										<?php echo esc_html( $atts['primary_label'] ); ?>
									</a>
								<?php endif; ?>

								<?php if ( '' !== $atts['secondary_label'] ) : ?>
									<a
										class="cck-button cck-button--secondary"
										href="<?php echo esc_url( $atts['secondary_url'] ); ?>">
										<?php echo esc_html( $atts['secondary_label'] ); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="cck-hero__media">
						<div class="cck-hero__media-frame">
							<?php if ( '' !== $atts['image_url'] ) : ?>
								<img
									class="cck-hero__image"
									src="<?php echo esc_url( $atts['image_url'] ); ?>"
									alt=""
									loading="lazy"
									decoding="async">
							<?php else : ?>
								<div class="cck-placeholder" aria-hidden="true"></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php

		return trim( ob_get_clean() );
	}
}
