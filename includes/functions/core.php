<?php
namespace QPractice\Woo_Custom_Product_Redirects;

/**
 * Will look for Some_Class\Name in /includes/classes/some-class/class.name.php
 *
 * @since  3.0.0
 *
 * @return void
 */
function autoload( $class_name ) {

	// project-specific namespace prefix
	$prefix = __NAMESPACE__. '\\';

	// does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( 0 !== strncmp( $prefix, $class_name, $len ) ) {
	    // no, move to the next registered autoloader
	    return;
	}

	// base directory for the namespace prefix
	$base_dir = WOOPRODUCT_REDIRECTS_INC . 'classes/';

	// get the relative class name
	$relative_class = substr( $class_name, $len );

	/*
	 * replace the namespace prefix with the base directory, replace namespace
	 * separators with directory separators in the relative class name, replace
	 * underscores with dashes, and append with .php
	 */
	$path = strtolower( str_replace( array( '\\', '_' ), array( '/', '-' ), $relative_class ) );
	$file = $base_dir . $path . '.php';

	// if the file exists, require it
	if ( file_exists( $file ) ) {
		require $file;
	}
}

/**
 * Default setup routine
 *
 * @uses add_action()
 * @uses do_action()
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	spl_autoload_register( $n( 'autoload' ), false );

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );

	do_action( 'wooproduct_redirects_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @uses apply_filters()
 * @uses get_locale()
 * @uses load_textdomain()
 * @uses load_plugin_textdomain()
 * @uses plugin_basename()
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wooproduct_redirects' );
	load_textdomain( 'wooproduct_redirects', WP_LANG_DIR . '/wooproduct_redirects/wooproduct_redirects-' . $locale . '.mo' );
	load_plugin_textdomain( 'wooproduct_redirects', false, plugin_basename( WOOPRODUCT_REDIRECTS_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @uses do_action()
 *
 * @return void
 */
function init() {
	$general = General::get_instance();

	add_action( 'wooproduct_redirects_init', array( $general, 'init' ) );

	do_action( 'wooproduct_redirects_init' );
}
