<?php
/**
 * Component ayar formu önizleme rendererı.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_component_setting_input_name' ) ) {
	/**
	 * Ayar alanı için güvenli input name değeri üretir.
	 *
	 * @param string $component_id Component kimliği.
	 * @param string $setting_id   Ayar kimliği.
	 * @return string
	 */
	function cck_get_component_setting_input_name( $component_id, $setting_id ) {
		return 'cck_component_settings[' . sanitize_key( $component_id ) . '][' . sanitize_key( $setting_id ) . ']';
	}
}

if ( ! function_exists( 'cck_render_component_settings_preview' ) ) {
	/**
	 * Manifest settings verisinden kaydetmesiz admin form önizlemesi üretir.
	 *
	 * @param array $manifest Component manifest verisi.
	 * @return string
	 */
	function cck_render_component_settings_preview( $manifest ) {
		$component_id = cck_manifest_get( $manifest, 'id', '' );
		$settings     = cck_manifest_get( $manifest, 'settings', array() );

		if ( empty( $component_id ) || empty( $settings ) || ! is_array( $settings ) ) {
			return '<p class="cck-admin-muted">' . esc_html__( 'No settings defined.', 'craft-commerce-kit' ) . '</p>';
		}

		ob_start();
		?>
		<div class="cck-admin-settings-preview" aria-label="<?php /* translators: %s: Component name. */ echo esc_attr( sprintf( __( '%s settings preview', 'craft-commerce-kit' ), cck_manifest_get( $manifest, 'name', $component_id ) ) ); ?>">
			<?php foreach ( $settings as $setting_id => $setting ) : ?>
				<?php echo cck_render_component_setting_field( $component_id, $setting_id, $setting ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Alan renderer tüm çıktıları escape eder. ?>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'cck_render_component_setting_field' ) ) {
	/**
	 * Tek bir ayar alanı için admin field HTML'i üretir.
	 *
	 * @param string $component_id Component kimliği.
	 * @param string $setting_id   Ayar kimliği.
	 * @param array  $setting      Ayar tanımı.
	 * @return string
	 */
	function cck_render_component_setting_field( $component_id, $setting_id, $setting ) {
		$setting_id  = sanitize_key( $setting_id );
		$type        = sanitize_key( cck_array_get( $setting, 'type', 'text' ) );
		$label       = cck_to_string( cck_array_get( $setting, 'label', $setting_id ) );
		$description = cck_to_string( cck_array_get( $setting, 'description', '' ) );
		$value       = cck_array_get( $setting, 'default', '' );
		$required    = cck_to_bool( cck_array_get( $setting, 'required', false ) );
		$field_id    = 'cck-setting-' . sanitize_key( $component_id ) . '-' . $setting_id;
		$name        = cck_get_component_setting_input_name( $component_id, $setting_id );

		ob_start();
		?>
		<div class="cck-admin-setting-field cck-admin-setting-field--<?php echo esc_attr( $type ); ?>">
			<label for="<?php echo esc_attr( $field_id ); ?>">
				<span><?php echo esc_html( $label ); ?></span>
				<?php if ( $required ) : ?>
					<em><?php esc_html_e( 'Required', 'craft-commerce-kit' ); ?></em>
				<?php endif; ?>
			</label>
			<?php echo cck_render_component_setting_control( $type, $field_id, $name, $value, $setting ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Control renderer tüm çıktıları escape eder. ?>
			<?php if ( '' !== $description ) : ?>
				<p><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'cck_render_component_setting_control' ) ) {
	/**
	 * Desteklenen field type için input kontrolü üretir.
	 *
	 * @param string $type    Field type.
	 * @param string $field_id Field id değeri.
	 * @param string $name    Input name değeri.
	 * @param mixed  $value   Varsayılan değer.
	 * @param array  $setting Ayar tanımı.
	 * @return string
	 */
	function cck_render_component_setting_control( $type, $field_id, $name, $value, $setting ) {
		$supported_types = array( 'text', 'textarea', 'url', 'number', 'checkbox', 'select' );

		if ( ! in_array( $type, $supported_types, true ) ) {
			$type = 'text';
		}

		if ( 'textarea' === $type ) {
			return sprintf(
				'<textarea id="%1$s" name="%2$s" rows="3" readonly>%3$s</textarea>',
				esc_attr( $field_id ),
				esc_attr( $name ),
				esc_textarea( cck_to_string( $value ) )
			);
		}

		if ( 'checkbox' === $type ) {
			return sprintf(
				'<input id="%1$s" name="%2$s" type="checkbox" value="1" disabled %3$s>',
				esc_attr( $field_id ),
				esc_attr( $name ),
				checked( cck_to_bool( $value ), true, false )
			);
		}

		if ( 'select' === $type ) {
			return cck_render_component_setting_select( $field_id, $name, $value, cck_array_get( $setting, 'options', array() ) );
		}

		return sprintf(
			'<input id="%1$s" name="%2$s" type="%3$s" value="%4$s" readonly>',
			esc_attr( $field_id ),
			esc_attr( $name ),
			esc_attr( $type ),
			esc_attr( cck_to_string( $value ) )
		);
	}
}

if ( ! function_exists( 'cck_render_component_setting_select' ) ) {
	/**
	 * Select field kontrolünü üretir.
	 *
	 * @param string $field_id Field id değeri.
	 * @param string $name     Input name değeri.
	 * @param mixed  $value    Varsayılan değer.
	 * @param array  $options  Select seçenekleri.
	 * @return string
	 */
	function cck_render_component_setting_select( $field_id, $name, $value, $options ) {
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		ob_start();
		?>
		<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" disabled>
			<?php foreach ( $options as $option_value => $option_label ) : ?>
				<option value="<?php echo esc_attr( cck_to_string( $option_value ) ); ?>" <?php selected( cck_to_string( $value ), cck_to_string( $option_value ) ); ?>><?php echo esc_html( cck_to_string( $option_label ) ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		return ob_get_clean();
	}
}
