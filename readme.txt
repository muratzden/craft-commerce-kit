=== Craft Commerce Kit ===
Contributors: muratozden
Tags: woocommerce, ecommerce, components, storefront, shortcodes
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 0.9.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Theme-independent premium craft website templates for WooCommerce.

== Description ==

Craft Commerce Kit is a standalone, theme-independent WooCommerce plugin for premium craft and artisan website templates.

The plugin provides reusable shortcodes, component manifests, layout manifests, an admin workspace, and WordPress-native template override support for craft-focused WooCommerce presentation. It does not automatically modify themes, WooCommerce templates, products, carts, checkout pages, or existing site content.

Frontend assets are loaded only when a Craft Commerce Kit shortcode, component, or layout renders.

== Installation ==

1. Upload the `craft-commerce-kit` folder to the `/wp-content/plugins/` directory.
2. Activate Craft Commerce Kit through the Plugins screen in WordPress.
3. Open Craft Commerce Kit in the WordPress admin menu.
4. Add shortcodes manually to pages, posts, blocks, widgets, or templates.

No setup action runs on activation.

== Frequently Asked Questions ==

= Does this plugin require WooCommerce? =

WooCommerce is recommended for commerce-focused components. Non-product UI components can render without WooCommerce.

= Does this plugin change WooCommerce add-to-cart behavior? =

No. Craft Commerce Kit does not bind JavaScript to WooCommerce native add-to-cart selectors and does not modify cart, checkout, product, or archive behavior automatically.

= Does this plugin require a specific theme? =

No. Craft Commerce Kit is theme-independent and uses scoped `.cck-*` CSS classes.

= Are settings saved from the Components screen? =

No. Component settings forms are preview-only in this release.

== Screenshots ==

1. Craft Commerce Kit admin dashboard.
2. Components registry screen.
3. Layout Manager screen.
4. Template override file structure.

== Shortcodes ==

Core component shortcodes:

* `[cck_hero]`
* `[cck_section_title]`
* `[cck_trust_block]`
* `[cck_image_text]`
* `[cck_cta]`
* `[cck_collection_grid]`

WooCommerce shortcode, available only when WooCommerce is active:

* `[cck_product_trust_notes]`

Component and layout shortcodes:

* `[cck_layout id="homepage"]`
* `[cck_component id="hero"]`
* `[cck_component id="usp"]`
* `[cck_component id="product_grid"]`

== Template Override ==

Craft Commerce Kit supports WordPress-native template overrides from the active theme.

Theme override structure:

`craft-commerce-kit/components/{component-id}/render.php`

`craft-commerce-kit/layouts/{layout-id}/manifest.php`

Component render lookup priority:

1. Child theme component render file.
2. Parent theme component render file.
3. Plugin default component render file.

Layout manifest lookup priority:

1. Active theme layout manifest file.
2. Plugin default layout manifest file.

Users should override templates in a theme instead of editing plugin files.

== Changelog ==

= 0.9.0 =
* Added WordPress-native template override system.
* Added component template override support.
* Added layout manifest override support.
* Added reusable template locator helpers.

= 0.8.0 =
* Added Layout Manager section to the admin console.
* Added registered layout listing.
* Added layout component sequence preview.
* Added copyable layout shortcode display.
* Improved visibility of Layout Engine features.

= 0.7.0 =
* Added Layout Engine foundation.
* Added layout registry.
* Added layout renderer.
* Added homepage layout manifest.
* Added [cck_layout] shortcode.
* Added support for rendering registered components inside layouts.

= 0.5.0 =
* Added preview-only Component Settings UI generated automatically from manifest settings definitions.

= 0.4.1 =
* Added foundation freeze architecture helpers, manifest validation, debug logging, component interface standard, and render hooks.

= 0.4.0 =
* Added component definition settings, manifest defaults, settings helpers, and shortcode override rendering.

= 0.2.0 =
* Added component framework architecture with package manifests, automatic registry scanning, and on-demand render loading.

= 0.1.0 =
* Foundation release with admin workspace, shortcode components, template registry, and component engine foundation.