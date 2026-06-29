=== Craft Commerce Kit ===
Contributors: muratozden
Tags: woocommerce, ecommerce, components, storefront, shortcodes
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Theme-independent WooCommerce UI components for premium storefront experiences.

== Description ==

Craft Commerce Kit is a theme-independent WooCommerce UI framework for premium artisan, boutique, handmade, craft, and quiet luxury commerce brands.

The first implementation brand pack is Tilla Leather. The plugin does not depend on Flatsome and does not modify any parent theme, child theme, page, product, checkout, cart, or WooCommerce template automatically.

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

* `[cck_component id="hero"]`
* `[cck_component id="usp"]`
* `[cck_component id="product_grid"]`

The component engine currently includes Hero, USP, and Product Grid components. Frontend assets are loaded only when a CCK shortcode or component renders.

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

== Changelog ==

= 0.1.0 =
* Foundation release with admin workspace, shortcode components, template registry, and component engine foundation.
