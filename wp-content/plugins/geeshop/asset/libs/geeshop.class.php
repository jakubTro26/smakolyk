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
	//require_once( plugin_dir_path( __FILE__ ) . '/geeslider_config.php');


if ( ! class_exists( 'GeeShopClass' ) ) :


	class GeeShopClass {  
		public $version = '1.0.0';
		protected static $_instance = null;
		public function __construct() {
			$this->define_constants();
			$this->init_hooks_class();
			$this->include_modules();
		}
		
		
		
	protected function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'geeshop_load_css' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'geeshop_load_css_admin' ));
	}

	private function init_hooks_class() {
	// Add menus
		$this->init_hooks();
		$this->admin_hooks_all();
		add_action( 'wp_enqueue_scripts', array( $this, 'geeshop_load_css' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'geeshop_load_css_admin' ));
	}
	protected function include_modules_admin(){
	}
	
	protected function include_modules_site(){
	}
	
	public function get_version(){
		return $this->version;	
	}
	
	public function get_update( $ver_aktual, $ver_app){
		return $ver_aktual;	
	}
	
	protected function load_addins(){
		
	}
	
	public function get_update2(){
		$addins = self::load_addins();
		if ( is_array($addins))
			foreach($addins as $addin){
				if ( ! class_exists( 'GeeShop_'.$addin ) ){
					if( method_exists('GeeShop_'.$addin, 'update')){
					//	print 'method_exists';
//						$result = 'GeeShop_'.$addin::update();
						//exit;
					}
				}
			}
		//	exit;
	
	}
	
	protected function include_modules(){
		$this->load_addins();

		if ( is_admin() ) {
		}else {
		}
	}
	protected function plugin_admin_menu(){
		
	}
	protected function admin_hooks(){
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'plugin_admin_menu' ),9 );

		} 
		else {
		}

	}
	private function admin_hooks_all(){
		$this->admin_hooks();
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'plugin_admin_menu' ),9 );

		} 
		else {
		}

	}
	
	/**
	 * Define WC Constants
	 */
	protected function define_constants() {
	
	}
		
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	public function geeshop_load_css_admin(){


		wp_register_style( 'geeshop-css', GEESHOP_PLUGIN_URI. 'asset/css/geeshop.css', 'geeshop-css', GEESHOP_VERSION );
		wp_enqueue_style( 'geeshop-css');

	}
	/* 
		Add menu items
	 */
	public function geeshop_admin_menu() {
		if ( current_user_can( 'manage_woocommerce2' ) ) {
	//		$menu[] = array( '', 'read', 'separator-woocommerce', '', 'wp-menu-separator woocommerce' );
		}
	}
	
	

	public function getPOST($key = '')
	{	global $_POST;
		if (isset($_POST[$key]))
		{		
			global $_POST;
			if(is_array($_POST[$key])){			
			return $_POST[$key];
			}
			else{
				return trim(addslashes(stripslashes($_POST[$key])));
			}
		}
		else return '';
	}
	
	public function getGET($key = '')
	{
		global $_GET;
		if (isset($_GET[$key]))
		{
			if(is_array($_GET[$key]))
			{
			
			return $_GET[$key];
			}
			else
			{
				return trim(addslashes(stripslashes($_GET[$key])));
			}
		}
		else return '';
	}

	public function getMode()
	{
		$this->mode = $this->getPOST('mode');
		if (empty($this->mode))
			$this->mode = $this->getGET('mode');
			else
			if (empty($this->mode))
				$this->mode = 'list';
	}
	

	}
endif;