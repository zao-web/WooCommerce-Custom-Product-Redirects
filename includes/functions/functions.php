<?php
namespace QPractice\Woo_Custom_Product_Redirects;
use QPractice\Woo_Custom_Product_Redirects as Functions;

/**
 * Gets the redirects default setting.
 *
 * @since  0.1.0
 *
 * @return string  The redirect default value.
 */
function redirect_default() {
	return \WC_Admin_Settings::get_option( 'wooproduct_redirects_default' );
}

/**
 * Get the redirect value and priority for a product.
 *
 * @since  0.1.0
 *
 * @param  int  $product_id Product ID.
 *
 * @return array|false      Array with 'url' and 'priority' if product has a stored redirect.
 */
function get_product_redirect( $product_id ) {
	$maybe_redirect = get_post_meta( $product_id, 'product_redirect', true );

	// Sad trombone, no redirect for this product.
	if ( $maybe_redirect ) {
		return array(
			'url'      => $maybe_redirect,
			'priority' => intval( get_post_meta( $product_id, 'product_redirect_priority', true ) ),
		);
	}

	return false;
}

/**
 * Sorts an array of redirect arrays (produced by
 * QPractice\Woo_Custom_Product_Redirects\get_product_redirect())
 * and returns the lowest priority item OR the Woo redirect_default value.
 *
 * @since  0.1.0
 *
 * @param  array $redirect_stack Array of redirect arrays. @see QPractice\Woo_Custom_Product_Redirects\get_product_redirect()
 *
 * @return string                The found/chosen redirect.
 */
function get_highest_priority_redirect( array $redirect_stack ) {
	// Get the fallback Woo setting.
	$redirect_url = Functions\redirect_default();

	if ( empty( $redirect_stack ) || ! is_array( $redirect_stack ) ) {
		return $redirect_url;
	}

	// Sort by priority.
	usort( $redirect_stack, __NAMESPACE__ . '\\sort_array_by_priority_key' );

	$pick = array_shift( $redirect_stack );

	if ( isset( $pick['url'] ) ) {
		$redirect_url = esc_url_raw( $pick['url'] );
	}

	return $redirect_url;
}

/**
 * A usort callback for sorting an array by the 'priority' key value.
 *
 * @since  0.1.0
 *
 * @param  array  $a Array A
 * @param  array  $b Array B
 *
 * @return int       The sort value.
 */
function sort_array_by_priority_key( $a, $b ) {
	$a = intval( $a['priority'] );
	$b = intval( $b['priority'] );

	if ( $a == $b ) {
		return 0;
	}

	return $a < $b ? -1 : 1;
}

/**
 * Transfer query params and fragment from one url to another.
 *
 * @since  0.1.0
 *
 * @param  string  $from_url URL to transfer from.
 * @param  string  $to_url   URL to transfer to.
 *
 * @return string            Modified "to" URL.
 */
function transfer_query_vars( $from_url, $to_url ) {
	$parts = parse_url( $from_url );

	if ( isset( $parts['query'] ) ) {
		$to_url .= ( false !== strpos( $to_url, '?' ) ? '&' : '?' ) . $parts['query'];
	}

	if ( isset( $parts['fragment'] ) && false === strpos( $to_url, '#' ) ) {
		$to_url .= '#' . $parts['fragment'];
	}

	return $to_url;
}

/**
 * Determine if given URL is a relative URL.
 *
 * @since  0.1.0
 *
 * @param  string  $url URL to check.
 *
 * @return boolean      Whether URL is a relative one.
 */
function is_relative_url( $url ) {
	static $local_host = null;
	if ( null === $local_host ) {
		$local = parse_url( site_url() );
		$local_host = $local['host'];
	}

	$components = parse_url( esc_url( $url ) );
	return empty( $components['host'] ) || 0 === strcasecmp( $components['host'], $local_host );
}
