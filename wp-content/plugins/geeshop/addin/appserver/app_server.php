<?php
/**
 * Plugin Name: GeeShop for WooCommerce
 * Plugin URI: http://www.GeeShop.pl
 * Description: Plugin obslugi firmy kurierskiej  mPartner 10ka.pl
 * Version: 1.0.0
 * Author: GeeSoft IT Solutions
 * Author URI: http://www.GeeShop.pl
 * Requires at least: 1.0
 * Tested up to: 1.0
 *
 * Text Domain: GeeShop
 *
 * @package GeeShop
 * @category Core
 * @author GeeSoft
 */
//	error_reporting(0);
	require_once('config/config.php');   
	require_once('cAppServer.php');
	$Serwis =  new AppServer();      
	$Serwis->Execute();  
?>