<?php
/**
 * Plugin Name: GeeShop for WooCommerce
 * Plugin URI: http://themes.geesoft.cp/xpert/
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

if ( ! class_exists( 'GeeShop_Search' ) ) :


	final class GeeShop_Search {  
		public $version = '1.0.0';
		protected static $_instance = null;
		private $images_lists = array();
		private $ceneo_categories = array();
		private $categories_list = array();
		
		public function __construct() {
			$this->define_constants();
			$this->include_modules();
			$this->init_hooks();
		}
		
		
		
	private function init_hooks() {
		$this->admin_hooks();
	
	}
	private function include_modules(){

	if ( is_admin() ) {
		add_action( 'save_post', array( $this , 'data_results_clear') );

		}else {
		add_action( 'wp_enqueue_scripts', array( $this, 'geeshop_load_css' ));
		add_action( 'wp_loaded', array( $this , 'data_results') );
		$this->GetForm();

		}
	}
	private function admin_hooks(){
	}
	
	private function GetForm(){
	}
	/**
	 * Define WC Constants
	 */
	private function define_constants() {
		add_shortcode("gees_search_menu", array($this, "shortcode_search_menu"));
		add_shortcode("gees_search_menu_more", array($this, "shortcode_search_menu_more"));
		add_shortcode("gees_search_content", array($this, "shortcode_search_content1"));
	}
		
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function geeshop_load_css(){
		wp_enqueue_script( 'geesearchphp', GEESHOP_PLUGIN_URI. 'addin/search/js/geesearch.php', false, time(), true );	
		wp_enqueue_script( 'geesearch', GEESHOP_PLUGIN_URI. 'addin/search/js/geesearch.js', false, time(), true );	
		wp_enqueue_style('geesearch', GEESHOP_PLUGIN_URI . 'addin/search/css/geesearch.css',false,'1.0','all');	
	}
	/* 
		Add menu items
	 */
	 public function shortcode_search_menu_more(){
		return '<div id="gees-search-menu" class="gees-search-menu">
			<div class="gees-search-more">
			<form id="search-prd" role="search" method="get" class="woocommerce-product-search" action="'.home_url().'/search/">
				<label class="screen-reader-text" for="s">Szukaj:</label>
				<span class="kth-search-clear"><i class="fa fa-times-circle"></i></span>
				<input type="search" class="search-field" placeholder="Szukaj produktu…" value="" name="s" title="Szukaj:" id="s-prd" autocomplete="off">					
				<input type="submit" value="Szukaj"  style="display:none;">
				<span class="kth-search-button" style="font-size: 25px; padding:10px;vertical-align: middle;cursor: pointer;"><i class="fa fa-search"></i>	</span>
			<div class="gees-search-advanced"><a href="'.home_url().'/search/">Wyszukiwanie zaawansowane</a></div>
				
			</form>
			</div>
		</div>';
	}

	public function shortcode_search_menu(){
		return '<div id="gees-search-menu" class="gees-search-menu">
		<form id="search-prd" role="search" method="get" class="woocommerce-product-search" action="'.home_url().'/search/">
			<label class="screen-reader-text" for="s">Szukaj:</label>
			<span class="kth-search-clear"><i class="fa fa-times-circle"></i></span>
			<input type="search" class="search-field" placeholder="Szukaj produktu…" value="" name="s" title="Szukaj:" id="s-prd" autocomplete="off">
			<input type="submit" value="Szukaj"  style="display:none;">
			<span class="kth-search-button" style="font-size: 25px; padding:10px;vertical-align: middle;cursor: pointer;"><i class="fa fa-search"></i>	</span>
		</form>
		</div>';
	}
	public function shortcode_search_content1(){
		return '<div id="gees-search-content" class="gees-search-content">
		<div class="">
		<form id="search-content" role="search" method="get" class="woocommerce-product-search">
			<label class="screen-reader-text" for="s">Szukaj:</label>
			<span class="kth-search-clear"><i class="fa fa-times-circle"></i></span>
			<input type="search" class="search-field-content" placeholder="Szukaj produktu…" value="" name="s" title="Szukaj:" id="s-prd" autocomplete="off">
			<input type="submit" value="Szukaj"  style="display:none;">
			<span class="kth-search-button" style="font-size: 25px; padding:10px;vertical-align: middle;cursor: pointer;"><i class="fa fa-search"></i>	</span>
			<input type="hidden" name="post_type" value="product">
		</form>	
		</div>
		<div class="gees-search-result"><ul id="geeshop-content" style="min-height:200px"></ul></div>		
		</div>'
		;
	 }
	
	public function ceneo_generator_form_callback($response=array())
	{
		$i = 0;
		$code = '';
		$style = '';
		$desc = '';
		$desc2 = '';
		$code_option = '<form method="post" action="admin.php?page=ceneo_generator&mode=generuj"><div class = "gees-options-height-top-wrapper" style = "min-width:800px;"> <table> ';
		$code_option.= '<tr><td>&nbsp;</td><td><p>&nbsp;</p></td></tr>';
		$name_input  = GEES_THEME_TPL.'[layout_general_template]';
		$code_option.= '<tr><td width = "45%">&nbsp;&nbsp; Choose Visualization:</br><div style = "font-size:10px;display:block;">'.$desc.'</div></td>';
		$code_option.= '<td><input type = "hidden" name = "change_tpl" value = "1">';	
		$code_option.= '<div style = "font-size:10px;display:inline;">'.$desc2.'</div></td></tr>';

		$code_option.= '<tr><td><p class = "submit"><input type = "submit" class = "button-primary" value = "Generuj plik XML" /></p></td><td><p>&nbsp;</p></td></tr>';
		$code_option.= '</table></div>';
		$code_option.= '</div></form>'; 
		$code = $code_option;
		
		print  $code.'</div>';
	}
	
	public function ceneo_importer_execute(){
	}
	
	private function ceneo_get_category( $list, $key, $pre, $show)
	{
	}
	
	public function ceneo_importer_form_callback($response=array())
	{
	}
	public function data_results(){
		$filename = GEESHOP_PLUGIN_DIR . ( 'addin/search/geeserach_result.xml' );
		if (! file_exists($filename)) {  
			try{
				$this->data_generator_make_file();
			}catch(Exception $ex){}
		}
	}
	public function data_results_clear(){
		$filename = GEESHOP_PLUGIN_DIR . ( 'addin/search/geeserach_result.xml' );
		try{
			if ( file_exists( $filename )) {  
				unlink( $filename );
			}
		}catch(Exception $ex){}
		
		
	}
	
	public function data_generator_make_file(){
		global $wpdb;
		
		$products = $wpdb->get_results(  "
		SELECT * , _price.meta_value as _price_value, _sale_price.meta_value as _sale_price_value, _sku.meta_value as _sku
		 , _thumbnail_id.meta_value as _thumbnail_id
		 FROM {$wpdb->posts} AS post
		LEFT JOIN {$wpdb->postmeta} AS _price ON post.ID = _price.post_id
		LEFT JOIN {$wpdb->postmeta} AS _sale_price ON post.ID = _sale_price.post_id
		LEFT JOIN {$wpdb->postmeta} as _sku ON _sku.post_id = post.ID AND _sku.meta_key = '_sku'		
		LEFT JOIN {$wpdb->postmeta} _thumbnail_id ON post.ID = _thumbnail_id.post_id AND _thumbnail_id.meta_key = '_thumbnail_id' 
		WHERE post.post_type IN ( 'product', 'product_variation' )
			AND post.post_status = 'publish'
			AND _price.meta_key = '_sale_price'
			AND _sale_price.meta_key = '_price'
		/*	AND CAST( _price.meta_value AS DECIMAL ) >= 0
			AND CAST( _price.meta_value AS CHAR ) != ''
			AND CAST( _price.meta_value AS DECIMAL ) = CAST( _sale_price.meta_value AS DECIMAL ) */
		" );
		try
		{
			$file_name =GEESHOP_PLUGIN_DIR . ( 'addin/search/geeserach_result.xml' );
			$output_xml = '<?xml version="1.0" encoding="utf-8"?>'."\n";
			$output_xml .= '<offers xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1">'."\n";
			$fp = fopen($file_name, 'w');
			fwrite($fp,  $output_xml );

			if ( $products ) {
				$this->get_images();
				$this->get_categories();
				foreach ( $products as $product ) { 
				
					$price = $product->_price_value;
					if ($product->_sale_price_value)
						$price = $product->_sale_price_value;
					$sku = $product->_sku;
					$img = $this->get_product_imgs( $product->_thumbnail_id , $product->ID);
					$output_xml = '<o id="'.$product->ID.'" name="'.$this->text_to_xml($product->post_title).'" url="'.get_page_link($product->ID).'" sku="'.$sku.'" img="'.$img.'">'."\n";
					$categories = $this->get_categories();
					$product_attributes =  $this->get_product_attributes($product->ID);		
					
					$output_xml .= '<desc><![CDATA['.strtolower( $sku.' '.$this->text_to_xml($product->post_title).' '.$this->text_to_xml( $product->post_excerpt ).' '.$categories.' '.$product_attributes ).']]></desc>'."\n";		
					$output_xml .= '</o>'."\n";
				//	$fp = fopen($file_name, 'w+');
					fwrite($fp,  $output_xml );

				}
			}
			$output_xml = '</offers>';
			//$fp = fopen($file_name, 'w+');
			fwrite($fp,  $output_xml );
			fclose($fp);
		}catch(Exception $ex){
			
		}
	}	
		private function get_product_imgs( $id,  $post_id ){
			$output_xml = '';
			if (!empty( $id )){
				if (key_exists( $id, $this->images_lists ) ){
					$img = $this->images_lists[$id];
					$thumb = wp_get_attachment_image_src( $id);
					$img_small = '';
					if ( is_array($thumb) )
						if (isset ( $thumb[0] ) ){
							$img_small = $thumb[0];
							
						}
					$output_xml .= $img_small;//'<img src="'.$img_small.'"</img>'."\n";					
				}
			}
			return 	$output_xml;
		}			

		public function get_images(){
			$media_query = new WP_Query(
				array(
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'posts_per_page' => -1,
				)
			);
			$list = array();
			foreach ($media_query->posts as $post) {
				$list[$post->ID] = wp_get_attachment_url($post->ID);
			}
			$this->images_lists = $list;
			//print_t( $list );
		}	
		
		public function get_categories(){
			$ceneo_categories =  get_option( 'GeeShop_CENEO_CATEGORIES' );
			  $taxonomy     = 'product_cat';
			  $orderby      = 'name';  
			  $show_count   = 0;      // 1 for yes, 0 for no
			  $pad_counts   = 0;      // 1 for yes, 0 for no
			  $title        = '';  
			  $empty        = 0;
			$args = array(
			  'taxonomy'     => $taxonomy,
			  'orderby'      => $orderby,
			  'title_li'     => $title,
			  'hide_empty'   => $empty
			);
			$the_query = get_categories( $args );
			$list = array();
			$this->categories_list = $list;
			$cat = '';
		}
			
		public function get_product_category($prd_id){
			$terms = get_the_terms( $prd_id, 'product_cat' );
			$nterms = get_the_terms( $prd_id, 'product_tag'  );
			$output_xml = '';
			if (is_array($terms))
			foreach ($terms  as $term  ) {
				$product_cat_id = $term->term_id;
				$product_cat_name = $term->name;
				$cat = '';
				if (isset($this->categories_list["$product_cat_id"]['ceneo']['name']))
					$cat = $this->text_to_xml($this->categories_list["$product_cat_id"]['ceneo']['name']);
					$cat_tmp  = '<cat><![CDATA['.$cat.']]></cat>'."\n";	
					if ( strlen ($cat_tmp) > strlen ($output_xml) )
						$output_xml .= $cat_tmp ;	
			}
				
			return $output_xml;
		}			
		
		private function  text_to_xml($text){

			return htmlspecialchars(trim($text));
		}
			
		public function get_product_attributes($prd_id){
			
			$output_xml = '';
			
			$args = array(
				'post_type' => 'product',
				'meta_query' => array(
					array(
						'key' => 'pa_on',
						'value' => 'yes',
						'compare' => '='
					)
				)
			);
				
			$output_xml = ''."\n";
			$prd = wc_get_product($prd_id);
		//print_r($prd);
			//$values = $prd->get_sku();
			$values = get_post_meta( $prd_id, '_gees_ean', true );
			$attributes  = (method_exists($prd,'get_attributes') ? $prd->get_attributes():array());
			$iter = 2;
			foreach ( $attributes as $key=>$attribute ) {
				{ 

					if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $key ) ) ) {
							
							  $name = wc_attribute_label( $key , $prd); 
							 
							 if ( $attribute['is_taxonomy'] ) {
							$values = wc_get_product_terms( $prd_id, $key, array( 'fields' => 'names' ) );
							 $values = apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
						} else {

						
							$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
							 $values =  apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

						}
						
						 
					}
					
					if ( !empty( $attribute['is_visible'] ) and $iter <10){
						$values =  $this->get_term_value($prd_id, $key);
						$name = $key;
						if ( taxonomy_exists( $key ) ){
							$name = wc_attribute_label( $name, $prd );
						}
							
						if ( ! empty($values) && !empty($key) ){
							$output_xml.= $this->text_to_xml($values).' '."\n";;
							$iter++;
						}
					}
				}
			}

			return $output_xml;
		}		
		
		private function get_term_value($prd_id, $key){
			$terms_values = get_the_terms( $prd_id, $key);
			$values ='';
			if (is_array($terms_values))
				foreach ( $terms_values as $pos ) {
					if ( isset( $pos->name ) and !empty($pos->name)){
						if (! empty($values))
							$values.= ', ';
						$values.= $this->text_to_xml( $pos->name );
					}
				}
			return trim($values);
		}
		
	}

	function GShSearch() {
		return GeeShop_Search::instance();
	}

endif;
$GLOBALS['GeeShop_Search'] = GShSearch();

