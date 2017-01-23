<?php
namespace QPractice\Woo_Custom_Product_Redirects;
use QPractice\Woo_Custom_Product_Redirects as Functions;

class Handle_Redirects {

	public function init() {
		add_filter( 'woocommerce_payment_successful_result', array( __CLASS__, 'maybe_modify_redirect_property' ), 10, 2 );
		add_filter( 'woocommerce_get_checkout_order_received_url', array( __CLASS__, 'maybe_modify_redirect_url' ), 10, 2 );
		add_filter( 'woocommerce_checkout_no_payment_needed_redirect', array( __CLASS__, 'maybe_modify_redirect_url' ), 10, 2 );

		if ( ! empty( $_GET['order_id'] ) && ! empty( $_GET['key'] ) ) {
			add_filter( 'request', array( __CLASS__, 'set_wp_query_var' ) );
			add_filter( 'woocommerce_thankyou_order_received_text', '__return_empty_string' );
			remove_filter( 'the_title', 'wc_page_endpoint_title' );
		}
	}

	/**
	 * If we find a product redirect, set the $results redirect property to that URL.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $result   Results of payment processing.
	 * @param  int   $order_id Order ID
	 *
	 * @return array           Maybe-modified results array.
	 */
	public static function maybe_modify_redirect_property( $result, $order_id ) {
		$redirect = self::get_redirect_url( $order_id, isset( $result['redirect'] ) ? $result['redirect'] : '' );
		if ( $redirect ) {
			$result['redirect'] = $redirect;
		}

		return $result;
	}

	/**
	 * If we find a product redirect, set the return URL property to that URL.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $return_url The return URL for the successful order.
	 * @param  object $order      The order object.
	 *
	 * @return string             Maybe-modified return URL.
	 */
	public static function maybe_modify_redirect_url( $return_url, $order ) {
		$redirect = self::get_redirect_url( $order->id, $return_url );
		if ( $redirect ) {
			$return_url = $redirect;
		}

		return $return_url;
	}

	/**
	 * Get the highest priority redirect URL for this order (from the products), or fall back to Woo setting.
	 * Appends any existing query variables and adds an 'order_id' query variable to the URL.
	 *
	 * @param  int|object $order The order object or ID.
	 * @return string     The redirect URL, if found.
	 */
	public static function get_redirect_url( $order, $prev_url = '' ) {
		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}

		$items = $order->get_items();

		$redirect_stack = array();

		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$product = $order->get_product_from_item( $item );

				// No product object found.
				if ( ! $product ) {
					continue;
				}

				// Get redirect for this product.
				if ( $maybe_redirect = Functions\get_product_redirect( $product->id ) ) {
					$redirect_stack[] = $maybe_redirect;
				}
			}
		}

		$redirect = Functions\get_highest_priority_redirect( $redirect_stack );

		if ( $redirect && Functions\is_relative_url( $redirect ) ) {

			if ( $prev_url ) {
				$redirect = Functions\transfer_query_vars( $prev_url, $redirect );
			}

			$redirect = esc_url_raw( add_query_arg( 'order_id', $order->id, $redirect ) );
		}

		return $redirect;
	}

	/**
	 * Sets the 'order-received' query var so that the woocommerce_checkout shortcode
	 * works properly.
	 *
	 * @since 0.1.0`
	 *
	 * @param array  $query_vars Array of registered query variables.
	 * @return array             Modified array of registered query variables.
	 */
	public static function set_wp_query_var( $query_vars ) {
		$query_vars['order-received'] = absint( $_GET['order_id'] );

		return $query_vars;
	}

}
