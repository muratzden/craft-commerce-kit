<?php
/**
 * Admin pages.
 *
 * @package CraftCommerceKit
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cck_register_admin_page' ) ) {
	/**
	 * Register admin pages.
	 *
	 * @return void
	 */
	function cck_register_admin_page() {
		add_menu_page(
			__( 'Craft Commerce Kit', 'craft-commerce-kit' ),
			__( 'Craft Commerce Kit', 'craft-commerce-kit' ),
			'manage_options',
			'craft-commerce-kit',
			'cck_render_admin_page',
			'dashicons-layout',
			58
		);

		add_submenu_page( 'craft-commerce-kit', __( 'Dashboard', 'craft-commerce-kit' ), __( 'Dashboard', 'craft-commerce-kit' ), 'manage_options', 'craft-commerce-kit', 'cck_render_admin_page' );
		add_submenu_page( 'craft-commerce-kit', __( 'Components', 'craft-commerce-kit' ), __( 'Components', 'craft-commerce-kit' ), 'manage_options', 'craft-commerce-kit-components', 'cck_render_components_page' );
		add_submenu_page( 'craft-commerce-kit', __( 'Templates', 'craft-commerce-kit' ), __( 'Templates', 'craft-commerce-kit' ), 'manage_options', 'craft-commerce-kit-templates', 'cck_render_templates_page' );
		add_submenu_page( 'craft-commerce-kit', __( 'Layouts', 'craft-commerce-kit' ), __( 'Layouts', 'craft-commerce-kit' ), 'manage_options', 'craft-commerce-kit-layouts', 'cck_render_layouts_page' );
		add_submenu_page( 'craft-commerce-kit', __( 'Brand', 'craft-commerce-kit' ), __( 'Brand', 'craft-commerce-kit' ), 'manage_options', 'craft-commerce-kit-brand', 'cck_render_brand_page' );
		add_submenu_page( 'craft-commerce-kit', __( 'Commerce', 'craft-commerce-kit' ), __( 'Commerce', 'craft-commerce-kit' ), 'manage_options', 'craft-commerce-kit-commerce', 'cck_render_commerce_page' );
		add_submenu_page( 'craft-commerce-kit', __( 'System', 'craft-commerce-kit' ), __( 'System', 'craft-commerce-kit' ), 'manage_options', 'craft-commerce-kit-system', 'cck_render_system_page' );
	}
}

if ( ! function_exists( 'cck_get_admin_nav_items' ) ) {
	/**
	 * Get workspace navigation items.
	 *
	 * @return array
	 */
	function cck_get_admin_nav_items() {
		return array(
			'craft-commerce-kit'            => __( 'Dashboard', 'craft-commerce-kit' ),
			'craft-commerce-kit-components' => __( 'Components', 'craft-commerce-kit' ),
			'craft-commerce-kit-templates'  => __( 'Templates', 'craft-commerce-kit' ),
			'craft-commerce-kit-layouts'    => __( 'Layouts', 'craft-commerce-kit' ),
			'craft-commerce-kit-brand'      => __( 'Brand', 'craft-commerce-kit' ),
			'craft-commerce-kit-commerce'   => __( 'Commerce', 'craft-commerce-kit' ),
			'craft-commerce-kit-system'     => __( 'System', 'craft-commerce-kit' ),
		);
	}
}

if ( ! function_exists( 'cck_get_current_admin_page' ) ) {
	/**
	 * Get current CCK admin page slug.
	 *
	 * @return string
	 */
	function cck_get_current_admin_page() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		$page   = 'craft-commerce-kit';

		if ( $screen && ! empty( $screen->id ) ) {
			$page = str_replace( 'craft-commerce-kit_page_', '', $screen->id );
			$page = str_replace( 'toplevel_page_', '', $page );
		}

		return array_key_exists( $page, cck_get_admin_nav_items() ) ? $page : 'craft-commerce-kit';
	}
}

if ( ! function_exists( 'cck_render_admin_workspace_open' ) ) {
	/**
	 * Render shared workspace opening markup.
	 *
	 * @param string $title Page title.
	 * @param string $description Page description.
	 * @return void
	 */
	function cck_render_admin_workspace_open( $title, $description ) {
		$current_page = cck_get_current_admin_page();
		?>
		<div class="wrap cck-admin-dashboard">
			<div class="cck-admin-header">
				<div>
					<h1><?php esc_html_e( 'Craft Commerce Kit', 'craft-commerce-kit' ); ?></h1>
					<p><?php esc_html_e( 'Theme-independent Commerce Experience Framework', 'craft-commerce-kit' ); ?></p>
				</div>
				<div class="cck-admin-header__meta" aria-label="<?php esc_attr_e( 'Plugin summary', 'craft-commerce-kit' ); ?>">
					<span class="cck-admin-badge"><?php echo esc_html( sprintf( __( 'Version %s', 'craft-commerce-kit' ), CCK_VERSION ) ); ?></span>
					<span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Plugin Active', 'craft-commerce-kit' ); ?></span>
				</div>
			</div>
			<div class="cck-admin-workspace">
				<aside class="cck-admin-sidebar" aria-label="<?php esc_attr_e( 'Craft Commerce Kit navigation', 'craft-commerce-kit' ); ?>">
					<nav class="cck-admin-nav">
						<?php foreach ( cck_get_admin_nav_items() as $slug => $label ) : ?>
							<a class="cck-admin-nav-item <?php echo esc_attr( $current_page === $slug ? 'is-active' : '' ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=' . $slug ) ); ?>">
								<?php echo esc_html( $label ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
				</aside>
				<main class="cck-admin-content">
					<header class="cck-admin-page-title">
						<h2><?php echo esc_html( $title ); ?></h2>
						<p><?php echo esc_html( $description ); ?></p>
					</header>
					<div class="cck-admin-page">
		<?php
	}
}

if ( ! function_exists( 'cck_render_admin_workspace_close' ) ) {
	/**
	 * Render shared workspace closing markup.
	 *
	 * @return void
	 */
	function cck_render_admin_workspace_close() {
		?>
					</div>
				</main>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cck_get_admin_components' ) ) {
	/**
	 * Get admin component registry.
	 *
	 * @return array
	 */
	function cck_get_admin_components() {
		return function_exists( 'cck_get_component_registry' ) ? cck_get_component_registry() : array();
	}
}



if ( ! function_exists( 'cck_get_admin_component_label' ) ) {
	/**
	 * Get component label for admin display.
	 *
	 * @param array|string $component Component metadata or label.
	 * @return string
	 */
	function cck_get_admin_component_label( $component ) {
		if ( is_array( $component ) ) {
			if ( ! empty( $component['name'] ) ) {
				return $component['name'];
			}

			if ( ! empty( $component['label'] ) ) {
				return $component['label'];
			}

			return isset( $component['id'] ) ? $component['id'] : '';
		}

		return (string) $component;
	}
}

if ( ! function_exists( 'cck_get_component_category_label' ) ) {
	/**
	 * Get component category label.
	 *
	 * @param string $category Component category.
	 * @return string
	 */
	function cck_get_component_category_label( $category ) {
		$labels = array(
			'ui'       => __( 'UI Component', 'craft-commerce-kit' ),
			'commerce' => __( 'Commerce Component', 'craft-commerce-kit' ),
		);

		return isset( $labels[ $category ] ) ? $labels[ $category ] : ucfirst( $category );
	}
}

if ( ! function_exists( 'cck_get_component_status_label' ) ) {
	/**
	 * Get component status label.
	 *
	 * @param string $status Component status.
	 * @return string
	 */
	function cck_get_component_status_label( $status ) {
		$labels = array(
			'active'   => __( 'Active', 'craft-commerce-kit' ),
			'planned'  => __( 'Planned', 'craft-commerce-kit' ),
			'disabled' => __( 'Disabled', 'craft-commerce-kit' ),
		);

		return isset( $labels[ $status ] ) ? $labels[ $status ] : ucfirst( $status );
	}
}

if ( ! function_exists( 'cck_get_admin_shortcodes' ) ) {
	/**
	 * Get admin shortcode registry.
	 *
	 * @return array
	 */
	function cck_get_admin_shortcodes() {
		$shortcodes = array(
			array( 'code' => '[cck_hero]', 'description' => __( 'Displays a premium hero section with eyebrow, headline, text, actions, and optional image.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_section_title]', 'description' => __( 'Displays a reusable section heading with optional eyebrow, text, and alignment.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_trust_block]', 'description' => __( 'Displays a grid of trust notes for craft and commerce pages.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_image_text]', 'description' => __( 'Displays a responsive image and text storytelling section.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_cta]', 'description' => __( 'Displays a focused call-to-action block.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_collection_grid]', 'description' => __( 'Displays a linked collection grid using pipe and comma formatted items.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_tilla_hero]', 'description' => __( 'Displays the Tilla Leather hero preset.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_tilla_cta]', 'description' => __( 'Displays the Tilla Leather call-to-action preset.', 'craft-commerce-kit' ) ),
			array( 'code' => '[cck_tilla_home]', 'description' => __( 'Displays the Tilla Leather homepage demo skeleton.', 'craft-commerce-kit' ) ),
		);

		if ( cck_is_woocommerce_active() ) {
			$shortcodes[] = array( 'code' => '[cck_product_trust_notes]', 'description' => __( 'Displays product trust notes when WooCommerce is active.', 'craft-commerce-kit' ) );
		}

		return $shortcodes;
	}
}

if ( ! function_exists( 'cck_render_admin_page' ) ) {
	/**
	 * Render dashboard page.
	 *
	 * @return void
	 */
	function cck_render_admin_page() {
		$components         = cck_get_admin_components();
		$shortcodes         = cck_get_admin_shortcodes();
		$templates          = function_exists( 'cck_get_templates' ) ? cck_get_templates() : array();
		$theme              = wp_get_theme();
		$theme_name         = $theme->exists() ? $theme->get( 'Name' ) : __( 'Unknown', 'craft-commerce-kit' );
		$woocommerce_active = cck_is_woocommerce_active();
		$overview_items     = array(
			array( 'label' => __( 'Plugin Version', 'craft-commerce-kit' ), 'value' => CCK_VERSION ),
			array( 'label' => __( 'WooCommerce', 'craft-commerce-kit' ), 'value' => $woocommerce_active ? __( 'Active', 'craft-commerce-kit' ) : __( 'Inactive', 'craft-commerce-kit' ) ),
			array( 'label' => __( 'Active Theme', 'craft-commerce-kit' ), 'value' => $theme_name ),
			array( 'label' => __( 'PHP Version', 'craft-commerce-kit' ), 'value' => PHP_VERSION ),
			array( 'label' => __( 'WordPress Version', 'craft-commerce-kit' ), 'value' => get_bloginfo( 'version' ) ),
			array( 'label' => __( 'Brand Pack', 'craft-commerce-kit' ), 'value' => __( 'Tilla Leather', 'craft-commerce-kit' ) ),
			array( 'label' => __( 'Registered Components', 'craft-commerce-kit' ), 'value' => count( $components ) ),
			array( 'label' => __( 'Registered Shortcodes', 'craft-commerce-kit' ), 'value' => count( $shortcodes ) ),
		);
		$roadmap_items      = array(
			'v0.2' => __( 'Component Manager', 'craft-commerce-kit' ),
			'v0.3' => __( 'Brand Packs', 'craft-commerce-kit' ),
			'v0.4' => __( 'Theme Adapters', 'craft-commerce-kit' ),
			'v0.5' => __( 'Visual Builder', 'craft-commerce-kit' ),
			'v1.0' => __( 'Stable Release', 'craft-commerce-kit' ),
		);

		cck_render_admin_workspace_open( __( 'Dashboard', 'craft-commerce-kit' ), __( 'Workspace overview for Craft Commerce Kit.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'System Overview', 'craft-commerce-kit' ); ?></h2></div>
			<div class="cck-admin-overview">
				<?php foreach ( $overview_items as $item ) : ?>
					<div class="cck-admin-overview__item">
						<span class="cck-admin-overview__check" aria-hidden="true">✓</span>
						<span class="cck-admin-overview__label"><?php echo esc_html( $item['label'] ); ?></span>
						<strong><?php echo esc_html( $item['value'] ); ?></strong>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="cck-admin-grid">
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Brand Pack', 'craft-commerce-kit' ); ?></h2></div>
				<p class="cck-admin-kicker"><?php esc_html_e( 'Active Brand Pack', 'craft-commerce-kit' ); ?></p>
				<p class="cck-admin-feature-name"><?php esc_html_e( 'Tilla Leather', 'craft-commerce-kit' ); ?></p>
				<p><?php esc_html_e( 'Future versions will support multiple Brand Packs.', 'craft-commerce-kit' ); ?></p>
			</div>
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Templates', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-badge"><?php echo esc_html( count( $templates ) ); ?></span></div>
				<p><?php esc_html_e( 'Template registry is available for future import workflows.', 'craft-commerce-kit' ); ?></p>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=craft-commerce-kit-templates' ) ); ?>"><?php esc_html_e( 'View Templates', 'craft-commerce-kit' ); ?></a>
			</div>
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Components', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-badge"><?php echo esc_html( count( $components ) ); ?></span></div>
				<ul class="cck-admin-checklist">
					<?php foreach ( $components as $component ) : ?>
						<li><input type="checkbox" checked disabled aria-label="<?php echo esc_attr( cck_get_admin_component_label( $component ) ); ?>"><span><?php echo esc_html( cck_get_admin_component_label( $component ) ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="cck-admin-card cck-admin-card--wide">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Shortcodes', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-badge"><?php echo esc_html( count( $shortcodes ) ); ?></span></div>
				<table class="cck-admin-table">
					<thead><tr><th scope="col"><?php esc_html_e( 'Shortcode', 'craft-commerce-kit' ); ?></th><th scope="col"><?php esc_html_e( 'Description', 'craft-commerce-kit' ); ?></th><th scope="col"><?php esc_html_e( 'Action', 'craft-commerce-kit' ); ?></th></tr></thead>
					<tbody>
						<?php foreach ( $shortcodes as $shortcode ) : ?>
							<tr><td><code><?php echo esc_html( $shortcode['code'] ); ?></code></td><td><?php echo esc_html( $shortcode['description'] ); ?></td><td><button type="button" class="button cck-admin-copy" data-cck-copy="<?php echo esc_attr( $shortcode['code'] ); ?>"><?php esc_html_e( 'Copy', 'craft-commerce-kit' ); ?></button></td></tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Demo', 'craft-commerce-kit' ); ?></h2></div>
				<div class="cck-admin-demo-code"><code>[cck_tilla_home]</code><button type="button" class="button button-primary cck-admin-copy" data-cck-copy="[cck_tilla_home]"><?php esc_html_e( 'Copy', 'craft-commerce-kit' ); ?></button></div>
			</div>
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Roadmap', 'craft-commerce-kit' ); ?></h2></div>
				<ol class="cck-admin-roadmap">
					<?php foreach ( $roadmap_items as $version => $label ) : ?>
						<li><span><?php echo esc_html( $version ); ?></span><strong><?php echo esc_html( $label ); ?></strong></li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}

if ( ! function_exists( 'cck_render_components_page' ) ) {
	/**
	 * Components admin sayfas?n? render eder.
	 *
	 * @return void
	 */
	function cck_render_components_page() {
		$components = cck_get_admin_components();

		cck_render_admin_workspace_open( __( 'Components', 'craft-commerce-kit' ), __( 'Reusable storefront components registered in Craft Commerce Kit.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Registered Components', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-badge"><?php echo esc_html( count( $components ) ); ?></span></div>
			<table class="cck-admin-table">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Component', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Category', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Version', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Supports', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Settings', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Shortcode', 'craft-commerce-kit' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $components as $component ) : ?>
						<?php
						$component_id   = isset( $component['id'] ) ? sanitize_key( $component['id'] ) : '';
						$label          = cck_get_admin_component_label( $component );
						$description    = isset( $component['description'] ) ? $component['description'] : '';
						$category       = isset( $component['category'] ) ? $component['category'] : 'ui';
						$version        = isset( $component['version'] ) ? $component['version'] : CCK_VERSION;
						$supports       = isset( $component['supports'] ) && is_array( $component['supports'] ) ? $component['supports'] : array();
						$settings       = isset( $component['settings'] ) && is_array( $component['settings'] ) ? $component['settings'] : array();
						$shortcode      = '[cck_component id="' . $component_id . '"]';
						$supports_label = empty( $supports ) ? __( 'None', 'craft-commerce-kit' ) : implode( ', ', $supports );
						?>
						<tr>
							<td><strong><?php echo esc_html( $label ); ?></strong><br><span><?php echo esc_html( $description ); ?></span></td>
							<td><?php echo esc_html( cck_get_component_category_label( $category ) ); ?></td>
							<td><?php echo esc_html( $version ); ?></td>
							<td><?php echo esc_html( $supports_label ); ?></td>
							<td><?php echo esc_html( count( $settings ) ); ?></td>
							<td><code><?php echo esc_html( $shortcode ); ?></code><br><button type="button" class="button cck-admin-copy" data-cck-copy="<?php echo esc_attr( $shortcode ); ?>"><?php esc_html_e( 'Copy', 'craft-commerce-kit' ); ?></button></td>
						</tr>
						<tr class="cck-admin-settings-row">
							<td colspan="6">
								<div class="cck-admin-settings-panel">
									<h3><?php esc_html_e( 'Settings Form Preview', 'craft-commerce-kit' ); ?></h3>
									<p><?php esc_html_e( 'Preview only. Saving will be available in a future sprint.', 'craft-commerce-kit' ); ?></p>
									<?php echo cck_render_component_settings_preview( $component ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Settings renderer t?m ??kt?lar? escape eder. ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}

if ( ! function_exists( 'cck_render_templates_page' ) ) {
	/**
	 * Render templates page.
	 *
	 * @return void
	 */
	function cck_render_templates_page() {
		$templates = function_exists( 'cck_get_templates' ) ? cck_get_templates() : array();

		cck_render_admin_workspace_open( __( 'Templates', 'craft-commerce-kit' ), __( 'Template registry for future import workflows.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-template-grid">
			<?php foreach ( $templates as $template ) : ?>
				<?php $components = isset( $template['components'] ) && is_array( $template['components'] ) ? $template['components'] : array(); ?>
				<div class="cck-admin-card cck-admin-template-card">
					<div class="cck-admin-card__heading"><h2><?php echo esc_html( isset( $template['name'] ) ? $template['name'] : $template['id'] ); ?></h2><span class="cck-admin-status cck-admin-status--active"><?php esc_html_e( 'Available', 'craft-commerce-kit' ); ?></span></div>
					<p><?php echo esc_html( isset( $template['description'] ) ? $template['description'] : '' ); ?></p>
					<div class="cck-admin-template-meta"><span><?php echo esc_html( sprintf( __( 'Version %s', 'craft-commerce-kit' ), isset( $template['version'] ) ? $template['version'] : '0.1.0' ) ); ?></span><span><?php echo esc_html( sprintf( __( '%d Components', 'craft-commerce-kit' ), count( $components ) ) ); ?></span></div>
					<div class="cck-admin-component-tags">
						<?php foreach ( $components as $component ) : ?>
							<span><?php echo esc_html( $component ); ?></span>
						<?php endforeach; ?>
					</div>
					<div class="cck-admin-template-actions"><button type="button" class="button" disabled><?php esc_html_e( 'Preview', 'craft-commerce-kit' ); ?></button><button type="button" class="button button-primary" disabled><?php esc_html_e( 'Import', 'craft-commerce-kit' ); ?></button></div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}


if ( ! function_exists( 'cck_render_layouts_page' ) ) {
	/**
	 * Layouts admin sayfasini render eder.
	 *
	 * @return void
	 */
	function cck_render_layouts_page() {
		$layouts = function_exists( 'cck_get_layout_registry' ) ? cck_get_layout_registry() : array();

		cck_render_admin_workspace_open( __( 'Layouts', 'craft-commerce-kit' ), __( 'Component-based layouts available for shortcode rendering.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Registered Layouts', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-badge"><?php echo esc_html( count( $layouts ) ); ?></span></div>
			<table class="cck-admin-table">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Layout', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Version', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Components', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Used Components', 'craft-commerce-kit' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Shortcode', 'craft-commerce-kit' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $layouts as $layout ) : ?>
						<?php
						$layout_id      = isset( $layout['id'] ) ? sanitize_key( $layout['id'] ) : '';
						$name           = isset( $layout['name'] ) ? $layout['name'] : $layout_id;
						$description    = isset( $layout['description'] ) ? $layout['description'] : '';
						$version        = isset( $layout['version'] ) ? $layout['version'] : CCK_VERSION;
						$components     = isset( $layout['components'] ) && is_array( $layout['components'] ) ? $layout['components'] : array();
						$component_ids  = array();
						$shortcode      = '[cck_layout id="' . $layout_id . '"]';

						foreach ( $components as $component ) {
							$normalized = cck_normalize_layout_component( $component );

							if ( ! empty( $normalized['id'] ) ) {
								$component_ids[] = $normalized['id'];
							}
						}
						?>
						<tr>
							<td><strong><?php echo esc_html( $name ); ?></strong><br><span><?php echo esc_html( $description ); ?></span></td>
							<td><?php echo esc_html( $version ); ?></td>
							<td><?php echo esc_html( count( $component_ids ) ); ?></td>
							<td><?php echo esc_html( implode( ', ', $component_ids ) ); ?></td>
							<td><code><?php echo esc_html( $shortcode ); ?></code><br><button type="button" class="button cck-admin-copy" data-cck-copy="<?php echo esc_attr( $shortcode ); ?>"><?php esc_html_e( 'Copy', 'craft-commerce-kit' ); ?></button></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}

if ( ! function_exists( 'cck_render_brand_page' ) ) {
	/**
	 * Render brand page.
	 *
	 * @return void
	 */
	function cck_render_brand_page() {
		cck_render_admin_workspace_open( __( 'Brand', 'craft-commerce-kit' ), __( 'Brand Pack information and future brand runtime foundation.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-grid">
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Brand Pack', 'craft-commerce-kit' ); ?></h2></div>
				<p class="cck-admin-kicker"><?php esc_html_e( 'Current Brand', 'craft-commerce-kit' ); ?></p>
				<p class="cck-admin-feature-name"><?php esc_html_e( 'Tilla Leather', 'craft-commerce-kit' ); ?></p>
			</div>
			<div class="cck-admin-card">
				<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Future', 'craft-commerce-kit' ); ?></h2></div>
				<ul class="cck-admin-list"><li><?php esc_html_e( 'Multiple Brand Packs', 'craft-commerce-kit' ); ?></li><li><?php esc_html_e( 'Brand Runtime', 'craft-commerce-kit' ); ?></li><li><?php esc_html_e( 'TILLA-OS Integration', 'craft-commerce-kit' ); ?></li></ul>
			</div>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}

if ( ! function_exists( 'cck_render_commerce_page' ) ) {
	/**
	 * Render commerce page.
	 *
	 * @return void
	 */
	function cck_render_commerce_page() {
		cck_render_admin_workspace_open( __( 'Commerce', 'craft-commerce-kit' ), __( 'Commerce Experience placeholders for future WooCommerce UI layers.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-card__heading"><h2><?php esc_html_e( 'Commerce Experience', 'craft-commerce-kit' ); ?></h2><span class="cck-admin-status cck-admin-status--muted"><?php esc_html_e( 'Coming in Sprint 06', 'craft-commerce-kit' ); ?></span></div>
			<ul class="cck-admin-list"><li><?php esc_html_e( 'Product Components', 'craft-commerce-kit' ); ?></li><li><?php esc_html_e( 'Archive Components', 'craft-commerce-kit' ); ?></li><li><?php esc_html_e( 'Checkout Components', 'craft-commerce-kit' ); ?></li></ul>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}

if ( ! function_exists( 'cck_render_system_page' ) ) {
	/**
	 * Render system page.
	 *
	 * @return void
	 */
	function cck_render_system_page() {
		$theme              = wp_get_theme();
		$templates          = function_exists( 'cck_get_templates' ) ? cck_get_templates() : array();
		$woocommerce_active = cck_is_woocommerce_active();
		$items              = array(
			array( 'label' => __( 'Plugin Version', 'craft-commerce-kit' ), 'value' => CCK_VERSION ),
			array( 'label' => __( 'WordPress Version', 'craft-commerce-kit' ), 'value' => get_bloginfo( 'version' ) ),
			array( 'label' => __( 'PHP Version', 'craft-commerce-kit' ), 'value' => PHP_VERSION ),
			array( 'label' => __( 'WooCommerce', 'craft-commerce-kit' ), 'value' => $woocommerce_active ? __( 'Active', 'craft-commerce-kit' ) : __( 'Inactive', 'craft-commerce-kit' ) ),
			array( 'label' => __( 'Theme', 'craft-commerce-kit' ), 'value' => $theme->exists() ? $theme->get( 'Name' ) : __( 'Unknown', 'craft-commerce-kit' ) ),
			array( 'label' => __( 'Loaded Components', 'craft-commerce-kit' ), 'value' => count( cck_get_admin_components() ) ),
			array( 'label' => __( 'Loaded Templates', 'craft-commerce-kit' ), 'value' => count( $templates ) ),
		);

		cck_render_admin_workspace_open( __( 'System', 'craft-commerce-kit' ), __( 'Runtime overview for the current WordPress environment.', 'craft-commerce-kit' ) );
		?>
		<div class="cck-admin-card cck-admin-card--wide">
			<div class="cck-admin-overview">
				<?php foreach ( $items as $item ) : ?>
					<div class="cck-admin-overview__item"><span class="cck-admin-overview__check" aria-hidden="true">✓</span><span class="cck-admin-overview__label"><?php echo esc_html( $item['label'] ); ?></span><strong><?php echo esc_html( $item['value'] ); ?></strong></div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		cck_render_admin_workspace_close();
	}
}

