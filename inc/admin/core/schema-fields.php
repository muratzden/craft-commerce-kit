<?php
/**
 * Schema-driven admin field helpers.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_get_schema_field_type' ) ) {
	/**
	 * Normalize a schema field type to a supported control type.
	 *
	 * @param array $field Field definition.
	 * @return string
	 */
	function cck_get_schema_field_type( array $field ) {
		$type = sanitize_key( cck_array_get( $field, 'type', 'text' ) );
		$supported = array( 'text', 'textarea', 'url', 'email', 'number', 'checkbox', 'select' );

		if ( in_array( $type, $supported, true ) ) {
			return $type;
		}

		if ( in_array( $type, array( 'integer', 'float' ), true ) ) {
			return 'number';
		}

		if ( 'boolean' === $type ) {
			return 'checkbox';
		}

		return 'text';
	}
}

if ( ! function_exists( 'cck_get_schema_field_default' ) ) {
	/**
	 * Get the normalized default value for a schema field.
	 *
	 * @param array $field Field definition.
	 * @return mixed
	 */
	function cck_get_schema_field_default( array $field ) {
		return cck_array_get( $field, 'default', '' );
	}
}

if ( ! function_exists( 'cck_sanitize_schema_field_value' ) ) {
	/**
	 * Sanitize a schema field value.
	 *
	 * @param mixed $value Field value.
	 * @param array $field Field definition.
	 * @return mixed
	 */
	function cck_sanitize_schema_field_value( $value, array $field ) {
		$type = cck_get_schema_field_type( $field );
		$callback = cck_array_get( $field, 'sanitize_callback', '' );
		$value = is_array( $value ) || is_object( $value ) ? '' : wp_unslash( $value );

		if ( 'checkbox' === $type ) {
			return cck_to_bool( $value ) ? '1' : '0';
		}

		if ( is_callable( $callback ) ) {
			return call_user_func( $callback, $value );
		}

		if ( 'textarea' === $type ) {
			return sanitize_textarea_field( $value );
		}

		if ( 'email' === $type ) {
			return sanitize_email( $value );
		}

		if ( 'url' === $type ) {
			return esc_url_raw( $value );
		}

		if ( 'number' === $type ) {
			return false !== strpos( (string) $value, '.' ) ? (string) floatval( $value ) : (string) absint( $value );
		}

		if ( 'select' === $type ) {
			return sanitize_text_field( $value );
		}

		return sanitize_text_field( $value );
	}
}

if ( ! function_exists( 'cck_get_schema_field_name' ) ) {
	/**
	 * Build a field name for a nested schema editor.
	 *
	 * @param string $prefix Field prefix.
	 * @param string $component_index Component row index.
	 * @param string $field_id Field key.
	 * @return string
	 */
	function cck_get_schema_field_name( $prefix, $component_index, $field_id ) {
		return sanitize_key( $prefix ) . '[' . cck_to_string( $component_index ) . '][' . sanitize_key( $field_id ) . ']';
	}
}

if ( ! function_exists( 'cck_render_schema_field_control' ) ) {
	/**
	 * Render an editable schema field control.
	 *
	 * @param string $field_name Field name.
	 * @param string $field_id Field id.
	 * @param array  $field Field definition.
	 * @param mixed  $value Current value.
	 * @return string
	 */
	function cck_render_schema_field_control( $field_name, $field_id, array $field, $value ) {
		$type = cck_get_schema_field_type( $field );
		$value = is_scalar( $value ) || null === $value ? (string) $value : '';
		$options = cck_array_get( $field, 'options', array() );

		ob_start();
		if ( 'textarea' === $type ) :
			?>
			<textarea id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>" rows="4"><?php echo esc_textarea( $value ); ?></textarea>
		<?php elseif ( 'checkbox' === $type ) : ?>
			<label class="cck-schema-checkbox" for="<?php echo esc_attr( $field_id ); ?>">
				<input id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>" type="checkbox" value="1" <?php checked( cck_to_bool( $value ), true ); ?>>
				<span><?php esc_html_e( 'Toggle value', 'craft-commerce-kit' ); ?></span>
			</label>
		<?php elseif ( 'select' === $type ) : ?>
			<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>">
				<?php foreach ( is_array( $options ) ? $options : array() as $option_value => $option_label ) : ?>
					<option value="<?php echo esc_attr( cck_to_string( $option_value ) ); ?>" <?php selected( cck_to_string( $value ), cck_to_string( $option_value ) ); ?>><?php echo esc_html( cck_to_string( $option_label ) ); ?></option>
				<?php endforeach; ?>
			</select>
		<?php else : ?>
			<input id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>" type="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php
		endif;

		return ob_get_clean();
	}
}

if ( ! function_exists( 'cck_render_schema_field_editor' ) ) {
	/**
	 * Render a schema-driven editor field wrapper.
	 *
	 * @param string $field_name Field name.
	 * @param string $field_id Field id.
	 * @param array  $field Field definition.
	 * @param mixed  $value Current value.
	 * @return string
	 */
	function cck_render_schema_field_editor( $field_name, $field_id, array $field, $value ) {
		$label = cck_to_string( cck_array_get( $field, 'label', $field_id ) );
		$description = cck_to_string( cck_array_get( $field, 'description', '' ) );
		$type = cck_get_schema_field_type( $field );
		$field_input_id = sanitize_key( $field_id );
		$required = cck_to_bool( cck_array_get( $field, 'required', false ) );
		$control = cck_render_schema_field_control( $field_name, $field_input_id, $field, $value );

		ob_start();
		?>
		<div class="cck-schema-editor-field cck-schema-editor-field--<?php echo esc_attr( $type ); ?>">
			<label for="<?php echo esc_attr( $field_input_id ); ?>">
				<span><?php echo esc_html( $label ); ?></span>
				<?php if ( $required ) : ?>
					<em><?php esc_html_e( 'Required', 'craft-commerce-kit' ); ?></em>
				<?php endif; ?>
			</label>
			<?php echo wp_kses_post( $control ); ?>
			<?php if ( '' !== $description ) : ?>
				<p class="description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'cck_get_admin_component_options' ) ) {
	/**
	 * Get registered component options for selector controls.
	 *
	 * @return array<string,string>
	 */
	function cck_get_admin_component_options() {
		$registry = function_exists( 'cck_get_component_registry' ) ? cck_get_component_registry() : array();
		$options  = array();

		foreach ( $registry as $component_id => $component ) {
			$label = '';

			if ( ! empty( $component['name'] ) ) {
				$label = cck_to_string( $component['name'] );
			} elseif ( ! empty( $component['label'] ) ) {
				$label = cck_to_string( $component['label'] );
			} else {
				$label = ucwords( str_replace( array( '-', '_' ), ' ', sanitize_key( $component_id ) ) );
			}

			$options[ sanitize_key( $component_id ) ] = $label;
		}

		return $options;
	}
}

if ( ! function_exists( 'cck_render_manual_layout_component_row' ) ) {
	/**
	 * Render a reusable manual layout row.
	 *
	 * @param string $row_index Component row index.
	 * @param array  $component Component definition.
	 * @param array  $registry Component registry.
	 * @return string
	 */
	function cck_render_manual_layout_component_row( $row_index, array $component = array(), array $registry = array() ) {
		$row_index = cck_to_string( $row_index );

		if ( '' === $row_index ) {
			return '';
		}

		if ( empty( $registry ) ) {
			$registry = function_exists( 'cck_get_component_registry' ) ? cck_get_component_registry() : array();
		}

		$options = cck_get_admin_component_options();

		if ( empty( $options ) ) {
			return '';
		}

		$current_type = sanitize_key( cck_array_get( $component, 'type', cck_array_get( $component, 'component', array_key_first( $options ) ) ) );

		if ( '' === $current_type || ! isset( $options[ $current_type ] ) ) {
			$current_type = array_key_first( $options );
		}

		$attributes = cck_array_get( $component, 'attributes', array() );
		$attributes = is_array( $attributes ) ? $attributes : array();
		$current_manifest = function_exists( 'cck_get_component_manifest' ) ? cck_get_component_manifest( $current_type ) : array();
		$current_name = ! empty( $current_manifest['name'] ) ? cck_to_string( $current_manifest['name'] ) : $current_type;
		$current_callback = ! empty( $current_manifest['callback'] ) ? cck_to_string( $current_manifest['callback'] ) : '';

		ob_start();
		?>
		<article class="cck-layout-row" data-layout-row data-layout-row-index="<?php echo esc_attr( $row_index ); ?>" data-layout-row-current-type="<?php echo esc_attr( $current_type ); ?>">
			<header class="cck-layout-row__head">
				<div class="cck-layout-row__head-main">
					<span class="cck-admin-badge"><?php echo esc_html( $current_name ); ?></span>
					<select name="<?php echo esc_attr( 'cck_manual_layout[components][' . $row_index . '][type]' ); ?>" data-layout-row-type>
						<?php foreach ( $options as $option_value => $option_label ) : ?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $current_type, $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="cck-layout-row__actions">
					<button type="button" class="button" data-layout-move="up"><?php esc_html_e( 'Move Up', 'craft-commerce-kit' ); ?></button>
					<button type="button" class="button" data-layout-move="down"><?php esc_html_e( 'Move Down', 'craft-commerce-kit' ); ?></button>
					<button type="button" class="button button-link-delete" data-layout-remove><?php esc_html_e( 'Remove', 'craft-commerce-kit' ); ?></button>
				</div>
			</header>
			<?php if ( '' !== $current_callback ) : ?>
				<p class="description cck-layout-row__callback"><code><?php echo esc_html( $current_callback ); ?></code></p>
			<?php endif; ?>

			<div class="cck-layout-row__fields">
				<?php foreach ( $options as $option_value => $option_label ) : ?>
					<?php
					$manifest = function_exists( 'cck_get_component_manifest' ) ? cck_get_component_manifest( $option_value ) : array();
					$settings = is_array( $manifest ) ? cck_manifest_get( $manifest, 'settings', array() ) : array();
					?>
					<fieldset class="cck-layout-fieldset" data-layout-fields="<?php echo esc_attr( $option_value ); ?>" <?php echo $current_type === $option_value ? '' : 'hidden'; ?>>
						<legend>
							<span><?php echo esc_html( $option_label ); ?></span>
							<?php if ( ! empty( $manifest['callback'] ) ) : ?>
								<code><?php echo esc_html( $manifest['callback'] ); ?></code>
							<?php endif; ?>
						</legend>

						<?php if ( empty( $settings ) ) : ?>
							<p class="cck-admin-muted"><?php esc_html_e( 'No schema available.', 'craft-commerce-kit' ); ?></p>
						<?php else : ?>
							<div class="cck-layout-field-grid">
								<?php foreach ( $settings as $field_id => $field ) : ?>
									<?php
									$field_name  = 'cck_manual_layout[components][' . $row_index . '][attributes][' . sanitize_key( $field_id ) . ']';
									$field_value = array_key_exists( $field_id, $attributes ) ? $attributes[ $field_id ] : cck_get_schema_field_default( $field );
									?>
									<?php echo cck_render_schema_field_editor( $field_name, 'cck-manual-layout-' . $row_index . '-' . sanitize_key( $field_id ), is_array( $field ) ? $field : array(), $field_value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</fieldset>
				<?php endforeach; ?>
			</div>
		</article>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'cck_sanitize_schema_attribute_values' ) ) {
	/**
	 * Sanitize a schema-driven attribute map.
	 *
	 * @param array $schema Schema definition.
	 * @param mixed $values Raw attribute values.
	 * @return array
	 */
	function cck_sanitize_schema_attribute_values( array $schema, $values ) {
		$values = is_array( $values ) ? $values : array();
		$sanitized = array();

		foreach ( $schema as $field_id => $field ) {
			$field_id = sanitize_key( $field_id );
			$field = is_array( $field ) ? $field : array();

			if ( array_key_exists( $field_id, $values ) ) {
				$sanitized[ $field_id ] = cck_sanitize_schema_field_value( $values[ $field_id ], $field );
			} else {
				$sanitized[ $field_id ] = cck_get_schema_field_default( $field );
			}
		}

		return $sanitized;
	}
}
