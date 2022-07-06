<?php
/**
 * Author: GeeSoft IT Solutions
 * Author URI: http://www.geesoft.co/xpert/
 * Requires at least: 4.0
 * Tested up to: 4.1
 *
 * Text Domain: GeeShop
 *
 * @package GeeShop
 * @category Core
 * @author GeeSoft
 */

if ( ! class_exists( 'GeeShop_SearchNow' ) ) :

	final class GeeShop_SearchNow {  
		public $version = '1.0.0';
		protected static $_instance = null;
		private $images_lists = array();
		private $ceneo_categories = array();
		private $categories_list = array();
		
		public function __construct() {
			$this->doSearch();
		}
		
	private function doSearch(){
		$a = '';
		$b = '';
		$find = '';
		if (isset($_POST['dana1']) )
			$a = $_POST['dana1'];
		if (isset($_POST['find']) )
			$find = strtolower($_POST['find']);
		if (isset($_POST['dana2']) )
			$b = $_POST['dana2'];
		
		$type = isset($_POST['type']) ? strtolower($_POST['type']) : '';
		
		$url = 'http://'.$_SERVER['HTTP_HOST'] . str_replace("wp-content/themes/xpert/geesoft/plugins/geeshop/addin/search/search_now.class.php", "", $_SERVER['PHP_SELF']);
	
		$tablica = array();
		
		$actual = $_SERVER['SCRIPT_FILENAME'];
		$actual = str_replace('search_now.class.php', 'geeserach_result.xml', $actual); 

		if (($response_xml_data = file_get_contents($actual))===false){
			echo "Error fetching XML\n";
		} else {
		   libxml_use_internal_errors(true);
		   $data = simplexml_load_string($response_xml_data);
		   if (!$data) {
			   echo "Error loading XML\n";
			   foreach(libxml_get_errors() as $error) {
				   echo "\t", $error->message;
			   }
		   } else {
				$result2 = $data->xpath("//o[contains(desc,'$find')]"); 
				if (empty($type)){
					$result['result'] = $this->get_results_type0($result2) ;
				}else 
					$result['result'] = $this->get_results_type1($result2) ;
				print $result =  json_encode($result); 
			}
		   
		}
		
	}	
	
	private function get_results_type0($result2){
		$result_li ='';
		$i = 0;
		if (is_array( $result2) )
		foreach ( $result2 as $row){		
			   $div_img = '<div class="geeserach-img"><img src="'.$row['img'].'"></img></div>';
			   $div_name = '<div class="geeserach-text">'.$row['name'].'</div>';
			   $div_cart = '<div class="woocommerce woocommerce-content geeserach-cart">
			   <div class="quantity buttons_cart" style="display: table-cell;">
			<input type="button" value="-" class="minus">
			<input type="number" step="1" min="0" name="cart[8fe0093bb30d6f8c31474bd0764e6ac0][qty]" value="1" title="Szt." class="input-text qty text" size="4"><input type="button" value="+" class="plus"></div>
			   
				<div class="gees-add-cart"><a rel="nofollow" href="/projects/b2b/?add-to-cart='.$row['id'].'" data-quantity="1" data-product_id="'.$row['id'].'" data-product_sku="'.$row['sku'].'" class="button single_add_to_cart_button product_type_simple add_to_cart_button ajax_add_to_cart">Zamawiam</a></div>';
			   
			   $div= "<div class='geeserach-li'>$div_img $div_name </div>";
			   $result_li.= '<li><a href="'.$row['url'].'" class="geeserch-pos">'.$div.'</a>'. $div_cart.'</li>';
			   
			   if ( $i++ >5 ){ 
				   $result_li.= '<li><a href="'.$url.'?s='.$find.'&post_type=product"><div class="geeserach-more">More result ('.count($result2).')</div></a></li>';
					break;
			   }
		}
		return $result_li ;
	}
	
	function return_custom_price($price, $user_id ) {    
		$new_price = $price;
		$user_id  = get_current_user_id();
		if (is_user_logged_in()) {
			
			$kth = get_the_author_meta('user_kth_oferta', $user_id);;
			if (!empty($kth)){
				$kth = json_decode($kth);
				$rabat = (float)($kth->rabat * 0.01);	
			}else {
				$rabat = (float)(get_the_author_meta('gees_rabat', $user_id)*0.01);	
			}
			$new_price = $price - ($price * $rabat);
		}
		return $new_price;
	}
	
	private function get_results_type1($result2){
		$result_li ='';
		$i = 0;
		if (is_array( $result2) )
		foreach ( $result2 as $row){		
				$sku = (isset($row['sku']) and !empty($row['sku'])) ? $row['sku'].'' : '';
				$div_img = '<div class="geeserach-img"><img src="'.$row['img'].'"></img></div>';
				$div_name = '<div class="geeserach-text">'.$row['name'].' ';
				$div_name.= '<div class="geeserach-sku">SKU: '.$sku.'</div></div>';
				$div_cart = '<div class="woocommerce woocommerce-content geeserach-cart">
				<div class="quantity buttons_cart" style="display: table-cell;">
			<input type="button" value="-" class="minus">
			<input type="number" step="1" min="0" name="cart[8fe0093bb30d6f8c31474bd0764e6ac0][qty]" value="1" title="Szt." class="input-text qty text" size="4"><input type="button" value="+" class="plus"></div>
			   
				<div class="gees-add-cart"><a rel="nofollow" href="/projects/b2b/?add-to-cart='.$row['id'].'" data-quantity="1" data-product_id="'.$row['id'].'" data-product_sku="'.$row['sku'].'" class="button single_add_to_cart_button product_type_simple add_to_cart_button ajax_add_to_cart">Zamawiam</a></div>';
			   
			   $div= '<div class="geeserach-li"><a href="'.$row['url'].'" class="geeserch-pos">'.$div_img.' '.$div_name.' </a></div>';
			   $result_li.= '<li>'.$div.' '. $div_cart.'</li>';
		}
		return $result_li ;
	}
	private function get_current_url($strip = true) {
		static $filter;
		$filter = function($input) use($strip) {
			$input = str_ireplace(array(
				"\0", '%00', "\x0a", '%0a', "\x1a", '%1a'), '', urldecode($input));
			if ($strip) {
				$input = strip_tags($input);
			}
			$input = htmlentities($input, ENT_QUOTES, 'utf-8'); 
			return trim($input);
		};

		return 'http'. (($_SERVER['SERVER_PORT'] == '443') ? 's' : '')
			.'://'. $_SERVER['SERVER_NAME'] . $filter($_SERVER['REQUEST_URI']);
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	
	}

	function GShSearchNow() {
		return GeeShop_SearchNow::instance();
	}

endif;

$GLOBALS['GeeShop_SearchNow'] = GShSearchNow();