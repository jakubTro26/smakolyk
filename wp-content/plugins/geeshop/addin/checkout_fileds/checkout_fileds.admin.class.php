<?php
/**
 * Plugin Name: GeeShop for WooCommerce
 * Plugin URI: http://www.geesoft.eu/GeeShop/
 * Description: An e-commerce toolkit that helps you sell anything. Beautifully.
 * Version: 1.0.0
 * Author: GeeSoft IT Solutions
 * Author URI: http://www.geesoft.eu/GeeShop/
 * Requires at least: 4.0
 * Tested up to: 4.1
 *
 * Text Domain: GeeShop
 *
 * @package GeeShop
 * @category Core
 * @author GeeSoft
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'GeeShop_CheckoutFileds' ) ) :


	function GSh_CheckoutFileds() {
		return GeeShop_CheckoutFileds::instance();
	}

endif;