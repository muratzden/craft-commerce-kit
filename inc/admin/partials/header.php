<?php
/**
 * Admin header partial.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="cck-admin-header">
	<div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Craft Commerce Kit', 'craft-commerce-kit' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Theme-independent premium craft website templates for WooCommerce.', 'craft-commerce-kit' ); ?></p>
	</div>
	<div class="cck-admin-header__meta" aria-label="<?php esc_attr_e( 'Plugin summary', 'craft-commerce-kit' ); ?>">
		<span class="cck-admin-badge">
			<?php
			/* translators: %s: Plugin version. */
			echo esc_html( sprintf( __( 'Version %s', 'craft-commerce-kit' ), CCK_VERSION ) );
			?>
		</span>
		<span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Plugin Active', 'craft-commerce-kit' ); ?></span>
		<?php if ( ! empty( $meta ) && is_array( $meta ) ) : ?>
			<?php foreach ( $meta as $meta_item ) : ?>
				<?php
				$label = isset( $meta_item['label'] ) ? $meta_item['label'] : '';
				$value = isset( $meta_item['value'] ) ? $meta_item['value'] : '';
				?>
				<?php if ( '' !== $label || '' !== $value ) : ?>
					<span class="cck-admin-badge">
						<?php echo esc_html( trim( $label . ' ' . $value ) ); ?>
					</span>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
