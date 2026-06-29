# Craft Commerce Kit

Craft Commerce Kit is a theme-independent WooCommerce UI framework for premium artisan, boutique, handmade, craft, and quiet luxury commerce brands.

The first implementation brand pack is Tilla Leather. The plugin does not depend on Flatsome and does not modify any parent theme, child theme, page, product, checkout, cart, or WooCommerce template automatically.

## Installation

1. Copy the `craft-commerce-kit` folder into `wp-content/plugins/`.
2. Activate **Craft Commerce Kit** in WordPress admin.
3. Go to **Tools -> Craft Commerce Kit** to review status and available shortcodes.
4. Add shortcodes manually to pages, posts, blocks, widgets, or templates.

No setup action runs on activation. All usage is manual.

## Shortcodes

Core component shortcodes:

- `[cck_hero]`
- `[cck_section_title]`
- `[cck_trust_block]`
- `[cck_image_text]`
- `[cck_cta]`
- `[cck_collection_grid]`

WooCommerce shortcode, available only when WooCommerce is active:

- `[cck_product_trust_notes]`

## Component Examples

Hero:

```text
[cck_hero eyebrow="Handmade" title="Crafted by hand. Built to age." text="Quiet luxury handmade leather goods." primary_label="Shop Collection" primary_url="/shop/" secondary_label="Visit the Workshop" secondary_url="/workshop/"]
```

Trust block:

```text
[cck_trust_block items="Handmade|Honest materials|Small-batch production|Built to age"]
```

Collection grid:

```text
[cck_collection_grid columns="3" items="Bags,/product-category/bags/|Wallets,/product-category/wallets/|Belts,/product-category/belts/"]
```

Image and text:

```text
[cck_image_text title="Material honesty, shaped by hand." text="Use this block for workshop photography or brand storytelling." button_label="Visit the Workshop" button_url="/workshop/" reverse="true"]
```

CTA:

```text
[cck_cta title="A piece made to stay with you." text="Explore handmade leather goods designed with patience, purpose, and material honesty." button_label="Shop Collection" button_url="/shop/"]
```

## Tilla Leather Implementation

Tilla preset shortcodes:

- `[cck_tilla_hero]`
- `[cck_tilla_cta]`
- `[cck_tilla_home]`

`[cck_tilla_home]` returns a complete homepage skeleton made only from Craft Commerce Kit components. It is safe to paste into a page because the plugin does not overwrite existing page content.

The Tilla Leather brand pack also overrides the default design tokens through the `cck_design_tokens` filter.

## Theme Independence

Craft Commerce Kit is theme-independent. It uses scoped `.cck-*` CSS classes and does not require Flatsome, a parent theme change, or a child theme change.

The plugin avoids global destructive CSS and does not use `!important`.

## WooCommerce Compatibility

Craft Commerce Kit works with WooCommerce when WooCommerce is active. The first WooCommerce layer adds the optional `[cck_product_trust_notes]` shortcode only.

It does not auto-hook into product pages, cart, checkout, account pages, or product archives.

## Safe Deactivation

Deactivating the plugin removes its shortcodes, assets, and admin page. It does not delete pages, products, settings, themes, or WooCommerce data.
