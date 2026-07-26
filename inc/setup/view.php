<?php
/**
 * Setup wizard view.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_setup_page' ) ) {
	function cck_render_setup_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__(
					'Sorry, you are not allowed to access this page.',
					'craft-commerce-kit'
				)
			);
		}

		$profile = cck_get_brand_profile();
		$colors  = isset( $profile['tokens']['colors'] ) && is_array( $profile['tokens']['colors'] )
			? $profile['tokens']['colors']
			: array();

		$status = isset( $_GET['cck_setup_status'] )
			? sanitize_key( wp_unslash( $_GET['cck_setup_status'] ) )
			: '';
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Craft Commerce Kit Setup', 'craft-commerce-kit' ); ?></h1>
			<p><?php esc_html_e( 'Create the brand profile that will power your storefront and block editor.', 'craft-commerce-kit' ); ?></p>

			<?php if ( 'invalid' === $status ) : ?>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'Enter a valid brand name and brand ID.', 'craft-commerce-kit' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="cck_complete_setup">
				<?php wp_nonce_field( 'cck_complete_setup' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="cck-brand-name"><?php esc_html_e( 'Brand name', 'craft-commerce-kit' ); ?></label>
						</th>
						<td>
							<input id="cck-brand-name" name="cck_brand_profile[brand_name]" type="text" class="regular-text" value="<?php echo esc_attr( $profile['brand_name'] ); ?>" required>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="cck-brand-id"><?php esc_html_e( 'Brand ID', 'craft-commerce-kit' ); ?></label>
						</th>
						<td>
							<input id="cck-brand-id" name="cck_brand_profile[id]" type="text" class="regular-text" value="<?php echo esc_attr( $profile['id'] ); ?>" placeholder="sibel-atolyesi">
							<p class="description"><?php esc_html_e( 'Leave blank to generate it from the brand name.', 'craft-commerce-kit' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="cck-experience"><?php esc_html_e( 'Experience', 'craft-commerce-kit' ); ?></label>
						</th>
						<td>
							<select id="cck-experience" name="cck_brand_profile[experience]">
								<option value="atelier" <?php selected( $profile['experience'], 'atelier' ); ?>>
									<?php esc_html_e( 'Atelier', 'craft-commerce-kit' ); ?>
								</option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="cck-eyebrow"><?php esc_html_e( 'Eyebrow', 'craft-commerce-kit' ); ?></label></th>
						<td><input id="cck-eyebrow" name="cck_brand_profile[eyebrow]" type="text" class="regular-text" value="<?php echo esc_attr( $profile['eyebrow'] ); ?>"></td>
					</tr>

					<tr>
						<th scope="row"><label for="cck-headline"><?php esc_html_e( 'Headline', 'craft-commerce-kit' ); ?></label></th>
						<td><input id="cck-headline" name="cck_brand_profile[headline]" type="text" class="large-text" value="<?php echo esc_attr( $profile['headline'] ); ?>"></td>
					</tr>

					<tr>
						<th scope="row"><label for="cck-text"><?php esc_html_e( 'Description', 'craft-commerce-kit' ); ?></label></th>
						<td><textarea id="cck-text" name="cck_brand_profile[text]" class="large-text" rows="4"><?php echo esc_textarea( $profile['text'] ); ?></textarea></td>
					</tr>

					<tr>
						<th scope="row"><label for="cck-cta-label"><?php esc_html_e( 'CTA label', 'craft-commerce-kit' ); ?></label></th>
						<td><input id="cck-cta-label" name="cck_brand_profile[cta_label]" type="text" class="regular-text" value="<?php echo esc_attr( $profile['cta_label'] ); ?>"></td>
					</tr>

					<tr>
						<th scope="row"><label for="cck-cta-url"><?php esc_html_e( 'CTA URL', 'craft-commerce-kit' ); ?></label></th>
						<td><input id="cck-cta-url" name="cck_brand_profile[cta_url]" type="url" class="regular-text" value="<?php echo esc_attr( $profile['cta_url'] ); ?>"></td>
					</tr>

					<?php
					$color_fields = array(
						'background' => __( 'Background color', 'craft-commerce-kit' ),
						'surface'    => __( 'Surface color', 'craft-commerce-kit' ),
						'text'       => __( 'Text color', 'craft-commerce-kit' ),
						'accent'     => __( 'Accent color', 'craft-commerce-kit' ),
					);

					foreach ( $color_fields as $color_key => $color_label ) :
						$color_value = isset( $colors[ $color_key ] ) ? $colors[ $color_key ] : '';
						?>
						<tr>
							<th scope="row">
								<label for="cck-color-<?php echo esc_attr( $color_key ); ?>">
									<?php echo esc_html( $color_label ); ?>
								</label>
							</th>
							<td>
								<input
									id="cck-color-<?php echo esc_attr( $color_key ); ?>"
									class="cck-setup-color-input"
									name="cck_brand_profile[tokens][colors][<?php echo esc_attr( $color_key ); ?>]"
									type="color"
									value="<?php echo esc_attr( $color_value ); ?>"
									data-code-target="cck-color-code-<?php echo esc_attr( $color_key ); ?>"
								>
								<code id="cck-color-code-<?php echo esc_attr( $color_key ); ?>"><?php echo esc_html( strtoupper( $color_value ) ); ?></code>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>

				<?php submit_button( __( 'Save and complete setup', 'craft-commerce-kit' ) ); ?>
			</form>

			<script>
				document.addEventListener('DOMContentLoaded', function () {
					document.querySelectorAll('.cck-setup-color-input').forEach(function (input) {
						var targetId = input.getAttribute('data-code-target');
						var code = targetId ? document.getElementById(targetId) : null;

						if (!code) {
							return;
						}

						var updateCode = function () {
							code.textContent = String(input.value || '').toUpperCase();
						};

						input.addEventListener('input', updateCode);
						input.addEventListener('change', updateCode);
						updateCode();
					});
				});
			</script>
		</div>
		<?php
	}
}