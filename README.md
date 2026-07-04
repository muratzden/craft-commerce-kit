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

Craft Commerce Kit is a standalone, theme-independent WooCommerce plugin for building premium craft and artisan website templates.

The first static Brand Pack is Tilla Leather. The plugin does not depend on Flatsome and does not modify any parent theme, child theme, page, product, checkout, cart, or WooCommerce template automatically.

== Installation ==

1. Copy the `craft-commerce-kit` folder into `wp-content/plugins/`.
2. Activate **Craft Commerce Kit** in WordPress admin.
3. Open **Craft Commerce Kit** in WordPress admin to review the workspace.
4. Add shortcodes manually to pages, posts, blocks, widgets, or templates.

No setup action runs on activation. All usage is manual.

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

== Components ==

Component Engine shortcodes:

* `[cck_layout id="homepage"]`
* `[cck_component id="hero"]`
* `[cck_component id="usp"]`
* `[cck_component id="product_grid"]`

The component framework currently includes Hero, USP, and Product Grid packages. Each package contains a `manifest.php` file for metadata, supports, preview data, and settings definitions, plus a `render.php` file for output. Frontend assets are loaded only when a CCK shortcode or component renders.



== Architecture ==

Craft Commerce Kit uses a small component framework foundation.

Registry: scans component package manifests from `inc/components/components/*/manifest.php` and builds the available component list automatically.

Manifest: stores component metadata, supports, preview information, and settings definitions for future admin screens and import/export workflows.

Renderer: validates the manifest, reads defaults, applies shortcode overrides, loads the matching render file, and returns safe HTML output.

Settings: each component can define typed settings with labels, descriptions, defaults, required flags, and sanitize callbacks. The admin Components screen can render these settings automatically as a preview-only form.

Hooks: `cck_component_manifest`, `cck_component_defaults`, `cck_before_render_component`, and `cck_after_render_component` are available for future extensions and premium component packs. Layout registry and renderer APIs prepare component-based page composition for future visual workflows.

== Component Framework ==

Components are discovered automatically from package manifests in `inc/components/components/*/manifest.php`.

Each component package contains:

* `manifest.php` for id, name, description, category, version, and supports metadata.
* `render.php` for the component renderer.

The framework API prepares the plugin for future settings, live preview, and import/export workflows without implementing those features yet.

Available helper functions:

* `cck_get_component()`
* `cck_get_component_manifest()`
* `cck_get_component_settings()`
* `cck_get_component_defaults()`
* `cck_render_component()`






== Template Override ==

Craft Commerce Kit supports WordPress-native template overrides from the active theme.

Theme override structure:

```text
craft-commerce-kit/
    components/
        hero/
            render.php
    layouts/
        homepage/
            manifest.php
```

Component render lookup priority:

1. Child theme: `craft-commerce-kit/components/{component-id}/render.php`
2. Parent theme: `craft-commerce-kit/components/{component-id}/render.php`
3. Plugin default component render file

Layout manifest lookup priority:

1. Active theme: `craft-commerce-kit/layouts/{layout-id}/manifest.php`
2. Plugin default layout manifest

Users should override templates in a theme instead of editing plugin files.

== Layout Manager ==

The Layout Manager makes renderable component sequences easier to inspect inside the admin console.

It shows the Layout Registry output, Layout Manifest metadata, shortcode usage, and component sequence for each layout.

Example shortcode:

`[cck_layout id="homepage"]`

== Sprint 07 -- Layout Engine Foundation ==

Sprint 07 adds the Layout Engine foundation for rendering registered components inside layouts.

Shortcode:

`[cck_layout id="homepage"]`

Layout files:

* `inc/layouts/layout-registry.php`
* `inc/layouts/layout-renderer.php`
* `inc/layouts/layouts/homepage.php`

== Layout Engine ==

The Layout Engine renders component-based layouts from layout manifests. Layouts are discovered automatically from `inc/layouts/layouts/*.php`.

The first registered layout is Homepage and can be rendered with:

`[cck_layout id="homepage"]`

The layout renderer is prepared for future JSON-style component definitions, but this release does not read JSON, save layouts, or add a drag-and-drop editor.

== Component Settings UI ==

The Components admin screen renders a preview-only settings form from each component manifest. Supported field types are text, textarea, url, number, checkbox, and select.

Settings are not saved yet. Persistence will be introduced in a future sprint.

== Component Examples ==

Hero:

`[cck_hero eyebrow="Handmade" title="Crafted by hand. Built to age." text="Quiet luxury handmade leather goods." primary_label="Shop Collection" primary_url="/shop/" secondary_label="Visit the Workshop" secondary_url="/workshop/"]`

Collection grid:

`[cck_collection_grid columns="3" items="Bags,/product-category/bags/|Wallets,/product-category/wallets/|Belts,/product-category/belts/"]`

Component engine hero:

`[cck_component id="hero"]`

== Tilla Leather Implementation ==

Tilla preset shortcodes:

* `[cck_tilla_hero]`
* `[cck_tilla_cta]`
* `[cck_tilla_home]`

`[cck_tilla_home]` returns a complete homepage skeleton made only from Craft Commerce Kit components. It is safe to paste into a page because the plugin does not overwrite existing page content.

== Theme Independence ==

Craft Commerce Kit is theme-independent. It uses scoped `.cck-*` CSS classes and does not require Flatsome, a parent theme change, or a child theme change.

The plugin avoids global destructive CSS and does not use `!important`.

== WooCommerce Compatibility ==

Craft Commerce Kit works with WooCommerce when WooCommerce is active. It does not bind JavaScript to WooCommerce native add-to-cart selectors and does not auto-hook into product pages, cart, checkout, account pages, or product archives.

== Safe Deactivation ==

Deactivating the plugin removes its shortcodes, assets, and admin page. It does not delete pages, products, settings, themes, or WooCommerce data.

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
