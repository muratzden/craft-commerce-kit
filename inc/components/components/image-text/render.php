<?php
/**
 * Image Text component renderer.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_component_package_render_image_text' ) ) {
/**
 * Render the Image Text component.
 *
 * @param array $atts     Sanitized component values.
 * @param array $manifest Component manifest data.
 * @return string
 */
function cck_component_package_render_image_text( $atts = array(), $manifest = array() ) {
unset( $manifest );

$default_image = '';

if ( function_exists( 'cck_get_demo_asset' ) ) {
$asset = cck_get_demo_asset(
'story.webp',
__( 'Story image', 'craft-commerce-kit' )
);

$default_image = is_array( $asset ) && ! empty( $asset['url'] )
? $asset['url']
: '';
}

$atts = wp_parse_args(
is_array( $atts ) ? $atts : array(),
array(
'title'        => '',
'text'         => '',
'button_label' => '',
'button_url'   => '',
'image_url'    => $default_image,
'reverse'      => false,
'surface'      => 'transparent',
)
);

$is_reverse = filter_var(
$atts['reverse'],
FILTER_VALIDATE_BOOLEAN
);

$allowed_surfaces = array(
'transparent',
'background',
'surface',
'surface-alt',
'dark',
);

$surface = in_array( $atts['surface'], $allowed_surfaces, true )
? $atts['surface']
: 'transparent';

$classes = array(
'cck-section',
'cck-image-text',
'cck-surface',
'cck-surface--' . $surface,
);

if ( $is_reverse ) {
$classes[] = 'cck-image-text--reverse';
}

ob_start();
?>
<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
<div class="cck-container cck-image-text__inner">
<div class="cck-image-text__media">
<?php if ( '' !== $atts['image_url'] ) : ?>
<img
src="<?php echo esc_url( $atts['image_url'] ); ?>"
alt="<?php echo esc_attr__( 'Story image', 'craft-commerce-kit' ); ?>"
loading="lazy"
decoding="async"
>
<?php else : ?>
<div
class="cck-placeholder cck-placeholder--image-text"
aria-hidden="true"
></div>
<?php endif; ?>
</div>

<div class="cck-image-text__content">
<?php if ( '' !== $atts['title'] ) : ?>
<h2><?php echo esc_html( $atts['title'] ); ?></h2>
<?php endif; ?>

<?php if ( '' !== $atts['text'] ) : ?>
<p><?php echo esc_html( $atts['text'] ); ?></p>
<?php endif; ?>

<?php if ( '' !== $atts['button_label'] && '' !== $atts['button_url'] ) : ?>
<a
class="cck-button cck-button--primary"
href="<?php echo esc_url( $atts['button_url'] ); ?>"
>
<?php echo esc_html( $atts['button_label'] ); ?>
</a>
<?php endif; ?>
</div>
</div>
</section>
<?php

return trim( ob_get_clean() );
}
}