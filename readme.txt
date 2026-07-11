=== Delivery Promise for WooCommerce – Product Page Delivery Estimates ===
Contributors: wpruby
Tags: woocommerce delivery date, woocommerce delivery estimate, estimated delivery, shipping estimate, delivery promise
Requires at least: 5.6
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Show simple WooCommerce delivery and dispatch estimates on product pages using processing days, transit days, and working days.

== Description ==

Delivery Promise for WooCommerce (Lite) helps stores display simple delivery or dispatch estimates on WooCommerce product pages.

Example messages:

* Order today and get it between July 15 – July 18.
* Usually ships in 2–4 business days.

Lite calculates estimates locally from your store settings — no external APIs, no license key, and no advanced rule engine.

**Lite includes:**

* Product page delivery estimate
* Processing and transit day settings
* Working days (Monday–Sunday)
* Optional cutoff time
* Up to 5 excluded dates
* Customizable message template with placeholders
* Plain or highlighted display style
* Placement options on the product page

**Pro adds:** advanced delivery rules, cutoff countdown timers, product/category lead times, shipping-method estimates, cart/checkout/order/email display, order promise snapshots, delivery promise tester, and unlimited holidays.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/delivery-promise-for-woocommerce`, or install through the WordPress plugins screen.
2. Activate the plugin through the Plugins screen.
3. Ensure WooCommerce is installed and active.
4. Go to **WooCommerce → Delivery Promise** to configure settings.
5. Enable Delivery Promise and set your processing days, transit days, and working days.
6. View an in-stock product page to see the estimate.

== Frequently Asked Questions ==

= Does this show estimates on product pages? =

Yes. Lite displays simple delivery or dispatch estimates on WooCommerce product pages.

= Does Lite support cart and checkout estimates? =

No. Cart, checkout, order, and email delivery promises are available in Pro.

= Does Lite support product-specific lead times? =

No. Product, category, and variation lead times are available in Pro.

= Does Lite include countdown timers? =

No. Cutoff countdowns and "Order within" timers are available in Pro.

= Does Lite use external APIs? =

No. Lite calculates estimates locally from your settings.

= Can Lite and Pro run together? =

No. If Delivery Promise Pro is active, Lite will not output storefront estimates. Deactivate Lite when using Pro to avoid duplicate messages.

== Screenshots ==

1. Admin settings — General tab
2. Admin settings — Estimate tab
3. Admin settings — Display tab with preview
4. Product page delivery estimate

== Changelog ==

= 1.0.0 =
* Initial Lite release for WordPress.org
* Product page delivery estimates with processing days, transit days, and working days
* Simple admin UI with General, Estimate, Display, and Upgrade to Pro tabs
* Pro conflict detection when Delivery Promise Pro is active

== Upgrade Notice ==

= 1.0.0 =
Initial Lite release for WordPress.org.
