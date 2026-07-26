<?php
/**
 * Shared brand profile form.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_render_brand_profile_form' ) ) {
	/**
	 * Render the shared brand profile form.
	 *
	 * @param array $args Form configuration.
	 * @return void
	 */
	function cck_render_brand_profile_form( array $args = array() ) {
		$defaults = array(
			'profile'      => cck_get_brand_profile(),
			'action'       => '',
			'nonce_action' => '',
			'submit_label' => __( 'Save Brand Settings', 'craft-commerce-kit' ),
			'form_class'       => '',
			'brand_id_readonly' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$profile = is_array( $args['profile'] )
			? wp_parse_args(
				$args['profile'],
				cck_get_brand_profile_defaults()
			)
			: cck_get_brand_profile_defaults();

		$colors = isset( $profile['tokens']['colors'] ) &&
			is_array( $profile['tokens']['colors'] )
				? wp_parse_args(
					$profile['tokens']['colors'],
					cck_get_brand_profile_defaults()['tokens']['colors']
				)
				: cck_get_brand_profile_defaults()['tokens']['colors'];

		$action       = sanitize_key( $args['action'] );
		$nonce_action = sanitize_key( $args['nonce_action'] );
		$form_class       = sanitize_html_class( $args['form_class'] );
		$brand_id_readonly = ! empty( $args['brand_id_readonly'] );

		if ( '' === $action || '' === $nonce_action ) {
			return;
		}
		?>
		<form
			method="post"
			action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
			<?php echo '' !== $form_class ? 'class="' . esc_attr( $form_class ) . '"' : ''; ?>
		>
			<input
				type="hidden"
				name="action"
				value="<?php echo esc_attr( $action ); ?>"
			>

			<?php wp_nonce_field( $nonce_action ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="cck-brand-name">
							<?php esc_html_e( 'Brand name', 'craft-commerce-kit' ); ?>
						</label>
					</th>
					<td>
						<input
							id="cck-brand-name"
							name="cck_brand_profile[brand_name]"
							type="text"
							class="regular-text"
							value="<?php echo esc_attr( $profile['brand_name'] ); ?>"
							required
						>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="cck-brand-id">
							<?php esc_html_e( 'Brand ID', 'craft-commerce-kit' ); ?>
						</label>
					</th>
					<td>
						<input
							id="cck-brand-id"
							name="cck_brand_profile[id]"
							type="text"
							class="regular-text"
							value="<?php echo esc_attr( $profile['id'] ); ?>"
							<?php echo $brand_id_readonly ? 'readonly="readonly"' : ''; ?>
						>
						<p class="description">
							<?php if ( $brand_id_readonly ) : ?>
								<?php esc_html_e( 'The Brand ID is locked after setup to preserve runtime references.', 'craft-commerce-kit' ); ?>
							<?php else : ?>
								<?php esc_html_e( 'Leave blank to generate an ID from the brand name.', 'craft-commerce-kit' ); ?>
							<?php endif; ?>
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="cck-brand-experience">
							<?php esc_html_e( 'Experience', 'craft-commerce-kit' ); ?>
						</label>
					</th>
					<td>
						<select
							id="cck-brand-experience"
							name="cck_brand_profile[experience]"
						>
							<option
								value="atelier"
								<?php selected( $profile['experience'], 'atelier' ); ?>
							>
								<?php esc_html_e( 'Atelier', 'craft-commerce-kit' ); ?>
							</option>
						</select>
					</td>
				</tr>

				<?php
				$text_fields = array(
					'eyebrow'   => __( 'Eyebrow', 'craft-commerce-kit' ),
					'headline'  => __( 'Headline', 'craft-commerce-kit' ),
					'cta_label' => __( 'CTA label', 'craft-commerce-kit' ),
				);

				foreach ( $text_fields as $field_key => $field_label ) :
					?>
					<tr>
						<th scope="row">
							<label for="cck-brand-<?php echo esc_attr( $field_key ); ?>">
								<?php echo esc_html( $field_label ); ?>
							</label>
						</th>
						<td>
							<input
								id="cck-brand-<?php echo esc_attr( $field_key ); ?>"
								name="cck_brand_profile[<?php echo esc_attr( $field_key ); ?>]"
								type="text"
								class="<?php echo esc_attr( 'headline' === $field_key ? 'large-text' : 'regular-text' ); ?>"
								value="<?php echo esc_attr( $profile[ $field_key ] ); ?>"
							>
						</td>
					</tr>
				<?php endforeach; ?>

				<tr>
					<th scope="row">
						<label for="cck-brand-text">
							<?php esc_html_e( 'Description', 'craft-commerce-kit' ); ?>
						</label>
					</th>
					<td>
						<textarea
							id="cck-brand-text"
							name="cck_brand_profile[text]"
							class="large-text"
							rows="5"
						><?php echo esc_textarea( $profile['text'] ); ?></textarea>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="cck-brand-cta-url">
							<?php esc_html_e( 'CTA URL', 'craft-commerce-kit' ); ?>
						</label>
					</th>
					<td>
						<input
							id="cck-brand-cta-url"
							name="cck_brand_profile[cta_url]"
							type="url"
							class="regular-text"
							value="<?php echo esc_attr( $profile['cta_url'] ); ?>"
						>
					</td>
				</tr>

				<?php
				$color_fields = array(
					'background' => __( 'Background', 'craft-commerce-kit' ),
					'surface'    => __( 'Surface', 'craft-commerce-kit' ),
					'text'       => __( 'Text', 'craft-commerce-kit' ),
					'accent'     => __( 'Accent', 'craft-commerce-kit' ),
				);

				foreach ( $color_fields as $color_key => $color_label ) :
					$color_value = isset( $colors[ $color_key ] )
						? $colors[ $color_key ]
						: '#000000';
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
								class="cck-brand-color-input"
								name="cck_brand_profile[tokens][colors][<?php echo esc_attr( $color_key ); ?>]"
								type="color"
								value="<?php echo esc_attr( $color_value ); ?>"
								data-code-target="cck-color-code-<?php echo esc_attr( $color_key ); ?>"
							>
							<code id="cck-color-code-<?php echo esc_attr( $color_key ); ?>">
								<?php echo esc_html( strtoupper( $color_value ) ); ?>
							</code>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>

			<?php submit_button( $args['submit_label'] ); ?>
		</form>

		<script>
			document.addEventListener('DOMContentLoaded', function () {
				document.querySelectorAll('.cck-brand-color-input').forEach(function (input) {
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
		<?php
	}
}