<?php
namespace QPractice\Woo_Custom_Product_Redirects\Admin;
use QPractice\Woo_Custom_Product_Redirects as Functions;

class Product_Editor {

	public function init() {
		add_action( 'woocommerce_product_options_advanced', array( $this, 'render_redirect_options' ) );
		add_action( 'woocommerce_process_product_meta',     array( $this, 'save_redirect_options' ), 10, 2 );
	}

	/**
	 * Adds settings to the "Advanced" panel in the Product Data postbox in the product interface.
	 */
	public function render_redirect_options() {
		global $post;

		woocommerce_wp_text_input( array(
			'id'                => 'product_redirect',
			'label'             => __( 'Checkout Redirect', 'wooproduct_redirects' ),
			'desc_tip'          => 'true',
			'description'       => __( 'By default, a user is redirected to the order review page when they complete a purchase. This allows overriding that behavior. If the "Default Redirect" is set in the Woo Settings, that will be the fallback if no value is set here.', 'wooproduct_redirects' ),
			'value'             => esc_attr( $post->product_redirect ),
		) );

		woocommerce_wp_text_input( array(
			'id'                => 'product_redirect_priority',
			'label'             => __( 'Redirect Priority', 'wooproduct_redirects' ),
			'desc_tip'          => 'true',
			'description'       => __( 'Determines which redirect takes precedence when multiple products in the checkout have registered redirects.', 'wooproduct_redirects' ),
			'value'             => intval( $post->product_redirect_priority ),
			'type'              => 'number',
			'custom_attributes' => array(
				'step' => '1',
			),
		) );
	}

	/**
	 * Saves the redirect data inputed into the product boxes, as post meta data.
	 *
	 * @param int      $post_id the post (product) identifier
	 * @param stdClass $post    the post (product)
	 */
	public function save_redirect_options( $post_id, $post ) {
		if ( empty( $post_id ) ) {
			return;
		}

		$redirect = ! empty( $_REQUEST['product_redirect'] )
			? esc_url_raw( $_REQUEST['product_redirect'] )
			: '';

		update_post_meta( $post_id, 'product_redirect', $redirect );

		$priority = ! empty( $_REQUEST['product_redirect_priority'] )
			? intval( $_REQUEST['product_redirect_priority'] )
			: '';

		update_post_meta( $post_id, 'product_redirect_priority', $priority );
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
