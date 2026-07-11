# Developer Hooks

Delivery Promise for WooCommerce exposes filters and actions so other plugins
(including WPRuby shipping plugins) can extend or override its behaviour without
editing core files.

All hooks use the `wpruby_delivery_promise_` prefix.

## Filters

### `wpruby_delivery_promise_default_settings`
Filter the default settings before they are merged with stored values.

```php
add_filter( 'wpruby_delivery_promise_default_settings', function ( array $defaults ) {
    $defaults['transit_max'] = 5;
    return $defaults;
} );
```

### `wpruby_delivery_promise_calculation_context`
Filter the calculation context (input) before processing. Useful to inject
country/zone/shipping data from another source.

```php
add_filter( 'wpruby_delivery_promise_calculation_context', function ( array $context ) {
    return $context;
} );
```

### `wpruby_delivery_promise_matched_rule`
Filter the rule matched for a given item context. Return `null` to force defaults
or a `WPRuby\DeliveryPromise\Domain\Rule` instance to force a rule.

```php
add_filter( 'wpruby_delivery_promise_matched_rule', function ( $rule, array $context ) {
    return $rule;
}, 10, 2 );
```

### `wpruby_delivery_promise_calculated_estimate`
Filter the final `Estimate` object (output) after calculation.

```php
add_filter( 'wpruby_delivery_promise_calculated_estimate', function ( $estimate, $context, $resolved ) {
    return $estimate;
}, 10, 3 );
```

### `wpruby_delivery_promise_message`
Filter the formatted display message before output.

```php
add_filter( 'wpruby_delivery_promise_message', function ( $message, $template, $estimate, $context, $replacements ) {
    return $message;
}, 10, 5 );
```

### `wpruby_delivery_promise_range_separator`
Filter the separator placed between the two dates of a range (default `" – "`).

### `wpruby_delivery_promise_shipping_method_label`
Filter the shipping method label used in messages. Carrier integrations can
return a richer service name (e.g. "Royal Mail Tracked 48").

```php
add_filter( 'wpruby_delivery_promise_shipping_method_label', function ( $label, $rate_id ) {
    return $label;
}, 10, 2 );
```

### `wpruby_delivery_promise_template_path`
Filter the path to a frontend template, for theme/plugin overrides.

```php
add_filter( 'wpruby_delivery_promise_template_path', function ( $path, $template, $vars ) {
    return $path;
}, 10, 3 );
```

### `wpruby_delivery_promise_email_ids`
Filter which WooCommerce email ids show the saved estimate. Defaults to
`customer_processing_order` and `customer_completed_order`.

## Actions

### `wpruby_delivery_promise_render_rules`
Fired when the admin Rules tab is rendered (internal; used to render the rules UI).

### `wpruby_delivery_promise_order_meta`
Fires after the estimate is written to an order's meta.

```php
add_action( 'wpruby_delivery_promise_order_meta', function ( $order, $estimate, $meta ) {
    // $order:    WC_Order
    // $estimate: WPRuby\DeliveryPromise\Domain\Estimate
    // $meta:     array of the stored Y-m-d strings + shipping label
}, 10, 3 );
```

## Order meta keys

| Meta key                                   | Description                 |
| ------------------------------------------ | --------------------------- |
| `_wpruby_delivery_promise_dispatch_min`    | Dispatch range start (Y-m-d)|
| `_wpruby_delivery_promise_dispatch_max`    | Dispatch range end (Y-m-d)  |
| `_wpruby_delivery_promise_delivery_min`    | Delivery range start (Y-m-d)|
| `_wpruby_delivery_promise_delivery_max`    | Delivery range end (Y-m-d)  |
| `_wpruby_delivery_promise_shipping_method` | Shipping method label       |
| `_wpruby_delivery_promise_message`         | Stored rule message override|

These values are frozen at order creation and are never recalculated, so changing
rules later does not affect existing orders.

## Template placeholders

`{dispatch_date}`, `{dispatch_range}`, `{delivery_date}`, `{delivery_range}`,
`{min_delivery_date}`, `{max_delivery_date}`, `{cutoff_time}`, `{shipping_method}`
