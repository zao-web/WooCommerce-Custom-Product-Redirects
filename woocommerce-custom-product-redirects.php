<?php
/**
 * Plugin Name: WooCommerce Custom Product Redirects
 * Plugin URI:  https://www.qpractice.com
 * Description: Configure checkout redirects for your products. Can be configured globally and individually per product with a priority.
 * Version:     0.1.1
 * Author:      Justin Sternberg
 * Author URI:  http://zao.is
 * Text Domain: wooproduct_redirects
 * Domain Path: /languages
 * License:     GPL-2.0+
 */

/**
 * Copyright (c) 2016 Justin Sternberg (email : jt@zao.is)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using yo wp-make:plugin
 * Copyright (c) 2016 10up, LLC
 * https://github.com/10up/generator-wp-make
 */

// Useful global constants
define( 'WOOPRODUCT_REDIRECTS_VERSION', '0.1.1' );
define( 'WOOPRODUCT_REDIRECTS_URL',     plugin_dir_url( __FILE__ ) );
define( 'WOOPRODUCT_REDIRECTS_PATH',    dirname( __FILE__ ) . '/' );
define( 'WOOPRODUCT_REDIRECTS_INC',     WOOPRODUCT_REDIRECTS_PATH . 'includes/' );

// Include files
require_once WOOPRODUCT_REDIRECTS_INC . 'functions/core.php';
require_once WOOPRODUCT_REDIRECTS_INC . 'functions/functions.php';

// Bootstrap
QPractice\Woo_Custom_Product_Redirects\setup();
