=== OnSale Page for WooCommerce ===
Contributors: wpgenie2, freemius
Donate link: https://wpgenie.org/store/
Tags: woocommerce, sale, onsale, discount, catalog
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OnSale Page is an extension for Woocommerce which enables you to have real on sale page with paging, sorting and filtering.

== Description ==

**OnSale Page for WooCommerce** is an extension that creates a dedicated page for displaying products on sale with full catalog functionality.

WooCommerce includes an on-sale widget and shortcode, but these lack the pagination, sorting, and filtering options available on standard WooCommerce catalog pages. **OnSale Page for WooCommerce** addresses this limitation by providing a complete shop-style page specifically for sale products. 

= Features =

* Full pagination across multiple pages 
* Product sorting options (price, popularity, date, etc.) 
* Category and attribute filtering 
* Standard WooCommerce catalog page layout and functionality 
* Custom content support: add promotional text or banners above/below sale products 
* Compatible with Gutenberg and Classic Editor 
* Seamless integration with existing WooCommerce settings and themes

= Use Cases =

* Create a permanent "Sale" or "Deals" section in your store
* Display seasonal promotions with proper navigation
* Allow customers to browse and filter sale items like any other catalog page
* Improve discoverability of discounted products

= PRO features =

All features of the free version, plus:

* Elementor integration: Build custom sale page layouts with Elementor page builder
* Additional page types:Create dedicated pages for Featured Products and Grouped Products with the same catalog functionality
* Category-specific sale pages: Display sale products within specific categories using URL parameters (e.g., /product-category/clothing/?onsale)
* Premium support: Direct access to our ticket support system at https://wpgenie.org/support/

= Support =

You can contact us at our website [wpgenie.org](http://wpgenie.org/) if you have problems or questions.


== Installation ==

= Minimum Requirements =

* WordPress 5.0 or greater
* WooCommerce 5.0 or greater


= Setup =

This section describes how to install OnSale Page for WooCommerce plugin and get it working.

1. Upload the plugin files to the /wp-content/plugins/ directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Create new page.
4. Go to Woocommerce Settings -> Products -> tab Display.
5. Under Shop & Product Pages you will notice "Onsale Page".
6. Select page you created for on sale page.
7. Save settings.
8. Go to new on sale page and you will see all on sale products there.

If you have any problems contact us at http://wpgenie.org/




== Frequently Asked Questions ==

= Can I add content to on sale page? =

Yes, from version v1.1.0 you can add content. If you add text / content on your on sale page it will be displayed along with on sale products. Gutenberg and classic editor supported.

= I'm using latest WooCommerce and I don't see any product on sale? =

If you have problem with displaying on sale products empty wp_wc_product_meta_lookup table and regenerate it using Status -> Tools -> Regenerate Product lookup tables tool.

= I cannot reach on sale page? =

Go to WordPress settings -> Permalinks and click Save Cahnges button on the bottom without doing any changes. You will see notification "Permalink structure updated." Should be all good now.

= How can I show only specific category of products that are on sale? =

Load page with ?product_cat query parameter for example http://onsale.test/product-category/clothing/accessories/?onsale


= What are PRO features? =

Pro features: Elementor support, new pages for Featured and Grouped products, on sale in category with http://onsale.test/product-category/clothing/accessories/?onsale and access to our support ticket system on https://wpgenie.org/support/

= How I can use Elementor? =

You need to go to Templates > Theme Builder, then click "Add New", select Products Archive, insert template from Library, style it and
then once completed click "Publish", add condition "On sale page", "Group products page" or "Featured products page". Save and close then publish.

= Have a question? =

If you want answer here please send us your questions to info@wpgenie.org



== Screenshots ==

1. OnSale Page plugin options
2. Two new types of pages (in PRO version)
3. Display conditions screenshot 1 - Elementor support (in Pro version)
4. Display conditions screenshot 2 - Elementor support (in Pro version)

== Changelog ==
= 2.0.1 =
* Fix: layered nav error

= 2.0.0 =
* Pro features introduced: Elementor support
* Pro features introduced: new pages for Featured and Grouped products
* Pro features introduced: on sale in category with special query arg ?onsale - https://onsale.test/product-category/clothing/accessories/?onsale
* Add: hooks now as functions instead of class for easier unhooking
* Add: Rank Math and Yoast support

= 1.1.3 =
* Fix: WPMU compatibility

= 1.1.2 =
* Add: HPOS compatibility
* Add: versions

= 1.1.1 =
* Fix: WPMU compatibility

= 1.1.0 =
* Add: versions
* Add: display on sale page content if exists
* Fix: Rank Math seo fixes

= 1.0.12 =
* Add: versions
* Fix: seo canonical url

= 1.0.11 =
* Add: versions

= 1.0.10 =
* Add: wc_onsale_page_product_ids_on_sale filter

= 1.0.9 =
* Add: Yoast SEO compatibility

= 1.0.8 =
* Add: is_woocommerce_sale_page()

= 1.0.7 =
* Add: WooCommerce OnSale Page Layered Nav Widget

= 1.0.6 =
* Fix: Issue with current item in menu
* Fix: Issue with language switcher in WPML

= 1.0.5 =
* Fix: Issue with page title on sale page

= 1.0.4 =
* Fix: Issue with 404 when there is no products on sale

= 1.0.3 =
* Add: WPML support

= 1.0.2 =
* Fix: product not showing when Shop Page Display is set to show categories
* Fix: not showing option Shop Page Display in WooCommerce settings

= 1.0.1 =
* Fix: notice when on sale page is not set

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.0.1 =
Fix: notice when on sale page is not set

= 1.0 =
Initial realease
