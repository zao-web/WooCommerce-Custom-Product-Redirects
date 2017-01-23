<?php
namespace QPractice\Woo_Custom_Product_Redirects;

class General {

	/**
	 * General
	 *
	 * @var General
	 */
	protected static $single_instance = null;

	/**
	 * Product_Editor
	 *
	 * @var Product_Editor
	 */
	protected $product_editor = null;

	/**
	 * Woo_Settings
	 *
	 * @var Woo_Settings
	 */
	protected $woo_settings = null;

	/**
	 * Handle_Redirects
	 *
	 * @var Handle_Redirects
	 */
	protected $handle_redirects = null;

	/**
	 * Creates or returns an instance of this class.
	 * @since  0.1.0
	 * @return General A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Initiate our sub-objects.
	 *
	 * @since 0.1.0
	 */
	protected function __construct() {
		if ( is_admin() ) {
			$this->product_editor = new Admin\Product_Editor;
			$this->woo_settings = new Admin\Woo_Settings;
		}

		$this->handle_redirects = new Handle_Redirects;
	}

	/**
	 * Run the init method on our sub-objects (on the init hook).
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function init() {
		if ( is_admin() ) {
			$this->product_editor->init();
			$this->woo_settings->init();
		}

		$this->handle_redirects->init();
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
