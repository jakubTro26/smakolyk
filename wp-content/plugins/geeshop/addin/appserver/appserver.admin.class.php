<?php
/**

 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'GeeShop_appserver' ) ) :


	function GShappserver_admin() {
		return GeeShop_appserver::instance();
	}

endif;
