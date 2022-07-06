<?php
/**
 * Plugin Name: GeeShop for WooCommerce
 * Plugin URI: http://www.dodatki-subiekt.pl/
 * Description: An e-commerce toolkit that helps you sell anything. Beautifully.
 * Version: 1.0.0
 * Author: GeeSoft IT Solutions
 * Author URI: http://www.dodatki-subiekt.pl/
 * Requires at least: 1.0
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


if ( ! class_exists( 'GeeShop' ) ) :


	final class GeeShop {  
		
		private $addins = array();
		protected static $_instance = null;
		
		public function __construct() {
			
			$this->define_constants();
			$this->init_hooks();
			$this->include_modules();
		//	load_plugin_textdomain( 'geeshop_plugin', false, GEESHOP_PLUGIN_DIR.'/languages' );
		}
		
		
		
	private function init_hooks() {
	// Add menus
	
		add_action( 'wp_enqueue_scripts', array( $this, 'geeshop_load_css' ));
		
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		$this->admin_hooks();
	}

	private function include_modules(){
		$this->load_addins();

		if ( is_admin() ) {
			require_once(GEESHOP_PLUGIN_DIR . ( 'admin/attribute/geeshop-attribute-fields.php') );
		}else {
			require_once(GEESHOP_PLUGIN_DIR . ( 'theme/geeshop-product-fields.php') );
		}
	}
	private function admin_hooks(){
		
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'geeshop_admin_menu' ),9 );
		} 

	}
		public function load_plugin_textdomain() {

			$domain = 'geeshop';
			$locale = apply_filters('plugin_locale', get_locale(), $domain );
			$link = trailingslashit( GEESHOP_PLUGIN_DIR ) . 'languages/'.$domain . '_plugin-' . $locale . '.mo';
			load_textdomain( 'geeshop_plugin', $link);//$domain, trailingslashit( GEESHOP_PLUGIN_DIR ).'/plugins/geeshop/'. $domain . '/' . $domain . '-' . $locale . '.mo' );
			dirname(dirname(plugin_basename( __FILE__ ))) . '/languages/';
			load_plugin_textdomain( $domain, FALSE, dirname(dirname(plugin_basename( __FILE__ ))) . '/languages/' );
			//load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}
	/**
	 * Define WC Constants
	 */
	 private function plugin_exist(){
		 include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		$list = get_plugins();print_r($list);
		if (is_array($list))
			foreach ($list as $key =>$plgn){
			//	print $key;
				//print_r($plgn);
			}
	}
	
	private function define_constants() {
	//	$this->plugin_exist();
		$list = get_option( 'active_plugins' );
		//if(is_admin()) print_r($list);
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		 $plugins = get_plugins();
		//  in_array( 'geeshop/geeshop.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) or
		//if(  is_plugin_inactive('geeshop/geeshop.php') or is_plugin_active('geeshop/geeshop.php') ){
		//if(  in_array( 'geeshop/geeshop.php', $plugins ) ){
		if (isset($plugins["geeshop/geeshop.php"])){
			define('GEESHOP_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
			define('GEESHOP_PLUGIN_URI', plugin_dir_url( __FILE__ ));
		}
		else {
			define('GEESHOP_PLUGIN_DIR',  get_template_directory().'/geesoft/plugins/geeshop/');
			define('GEESHOP_PLUGIN_URI', get_template_directory_uri().'/geesoft/plugins/geeshop/');			
		}
		//print GEESHOP_PLUGIN_DIR;
		define('GEESHOP_VERSION', '1.0');
	}
	
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function geeshop_load_css(){
		//wp_enqueue_style('geeshop-style', GEESHOP_PLUGIN_DIR . '\asset\css\geeshop.css',false,'1.0','all');	
		wp_register_style( 'geeshop-css', GEESHOP_PLUGIN_URI. 'asset/css/geeshop.css', 'geeshop-css', GEESHOP_VERSION );
		wp_enqueue_style( 'geeshop-css');
		//wp_enqueue_style( 'geeshop-css' , GEESHOP_PLUGIN_DIR . '/asset/css/geeshop.css');
	}

	public function geeshop_admin_menu() {
		add_menu_page( 'Aktualizacje', 'GeeShop', 'manage_product_terms', 'geeshop_options', array($this,'geeshop_options_callback'), GEESHOP_PLUGIN_URI.'asset/img/geeshop.png' , 3 );	
		
	}
				
	public static function load_addins(){
		$addin_dir = GEESHOP_PLUGIN_DIR.'addin';
		$directories = self::expandDirectories($addin_dir);
		$addins = array();
		foreach ( $directories as $directory ){
			$addin = str_replace('\\','',str_replace($addin_dir, '',  $directory));
			$addin = $directory;
			$file = "$addin_dir/".$directory."/$addin.class.php";
			$file_admin  = "$addin_dir/".$directory."/$addin.admin.class.php";
			if (file_exists($file) ){
				require_once($file);	
				$addins[] = $addin;				
			}
			if (file_exists($file_admin) ){
				require_once($file_admin);					
			}
		}		
		return $addins;
	}

	public static function create_tables($networkwide = false){
		global $wpdb;
		
		if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
		
			$old_blog = $wpdb->blogid;
			
            // Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
			
            foreach($blogids as $blog_id){
				switch_to_blog($blog_id);
				self::_create_tables();
            }
			
            switch_to_blog($old_blog); //go back to correct blog
			
		}else{  //no multisite, do normal installation
		
			self::_create_tables();
			
		}
		
	}

	public static function _create_tables(){
		$addins = self::load_addins();
		if ( is_array($addins))
			foreach($addins as $addin){
				if ( ! class_exists( 'GeeShop_'.$addin ) ){
					if( method_exists('GeeShop_'.$addin, 'create_tables')){
						//$result = 'GeeShop_'.$addin::create_tables();
						//exit;
					}
				}
			}
		//	exit;
	
	}		
	
	public static function expandDirectories($base_dir) {
      $directories = array();
		if (is_dir($base_dir)) {
			if ($dh = @opendir($base_dir)) {
				while (($file = readdir($dh)) !== false) {
					if($file == '.' || $file == '..') continue;
						$dir = $file;;//"".DIRECTORY_SEPARATOR;//$base_dir.DIRECTORY_SEPARATOR.$file;
					if (is_dir("$base_dir/$dir"))						
					$directories []= "$dir";
					//echo "filename: ".$file."<br />";
				}
				closedir($dh);
				
			}
		}
		if(is_admin()){
		
		}
		/*
	  $dirs = array();//scandir($base_dir);
	  if (is_array($dirs)){
		  foreach($dirs as $file) {
				if($file == '.' || $file == '..') continue;
				$dir = $base_dir.DIRECTORY_SEPARATOR.$file;
				if(is_dir($dir)) {
					$directories []= $dir;
				//    $directories = array_merge($directories, $this->expandDirectories($dir));
				}
		  }
	  }
	  */
      return $directories;
	}
	public function geeshop_options_callback(){
		require_once(GEESHOP_PLUGIN_DIR . ( 'admin/geeshop.settings.class.php') );
		$app = new GeeS_GeeShop_Settings_General();
		
	}

	}

	function GSh() {
		return GeeShop::instance();
	}
endif;

if (!function_exists('print_tt')){
	function print_tt( $array ){
		print '<pre>';print_r( $array );print '</pre>';
	}
}
	register_activation_hook( __FILE__, array('GeeShop', 'create_tables' ));
	$GLOBALS['GeeShop'] = GSh();

