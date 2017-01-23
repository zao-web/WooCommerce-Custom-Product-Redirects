<?php
namespace QPractice\Woo_Custom_Product_Redirects\Admin;
use QPractice\Woo_Custom_Product_Redirects as Functions;

class Woo_Settings {

	/**
	 * Bootstraps the class and hooks required actions & filters.
	 */
	public function init() {
		add_filter( 'woocommerce_payment_gateways_settings', array( $this, 'add_redirect_setting' ) );
	}

	/**
	 * Output the redirect settings in the Woo Checkout settings.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $settings The modified settings array.
	 */
	public function add_redirect_setting( $settings ) {

		$new_settings = array(
			array(
				'title' => __( 'Checkout Redirect', 'wooproduct_redirects' ),
				'type'  => 'title',
				'id'    => 'checkout_redirect_options',
			),
			array(
				'title'    => __( 'Default Redirect', 'wooproduct_redirects' ),
				'desc'     => __( 'By default, a user is redirected to the order review page when they complete a purchase. This allows overriding that behavior', 'wooproduct_redirects' ),
				'id'       => 'wooproduct_redirects_default',
				'type'     => 'text',
			),
			array(
				'type' => 'sectionend',
				'id' => 'checkout_page_options',
			),
		);

		$settings = array_merge( $new_settings, $settings );

		return $settings;
	}

	/**
	 * Magic getter for our object.
	 * @param string $property
	 * @return mixed
	 */
	public function __get( $property ) {
		return $this->{$property};
	}
}
