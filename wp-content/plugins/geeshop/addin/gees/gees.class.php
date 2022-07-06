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

require_once( GEESHOP_PLUGIN_DIR . ( 'asset/libs/geeshop.class.php' ) );

if ( ! class_exists( 'GeeShop_gees' ) ) :


	final class GeeShop_gees extends GeeShopClass {  
		
		protected static $_instance = null;
		private $images_lists = array();
		private $Spiler_categories = array();
		private $categories_list = array();
		
		public function __construct() {
			parent::__construct();
			$this->version = '1.0.0';
		}
		
		
	
	public function geeshop_load_css()
	{

	}	
	
	public function Spiler_generator_callback($response=array())
	{

	}
	
	

	/**
	 * Define WC Constants
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function plugin_admin_menu(){


	}
	
	private function  text_to_xml($text){

		return htmlspecialchars(trim($text));
	}
			
	}

	function GShgees() {
		return GeeShop_gees::instance();
	}

endif;
// Global for backwards compatibility.
$GLOBALS['GeeShop_gees'] = GShgees();

if (!is_admin()){
	
//add_action( 'woocommerce_before_single_product_summary', 'gees_print_field_video_youtube', 21 );
 
function gees_print_field_video_youtube(){
	global $post;
	$id = get_post_meta( $post->ID, '_gees_youtube_id', true );
	
	if ( ! empty ( $id ) ) {
	echo '		<div class="gees-accordion" style="display:block;min-width:100%;">
		<div class="gees-mfn-acc2 gees-accordion_wrapper open1st">
			
			<div class="gees-question">
			
					<div class="gees-title gees-video" style="">Obejrzyj film</div>
					
					<div class="gees-answer">
			<div itemprop="description" class="description">
<div class="product-embedded-youtube-player">
                        	<iframe src="http://www.youtube.com/embed/'.$id .'?autohide=2&amp;autoplay=0&amp;modestbranding=1&amp;rel=0&amp;showinfo=0&amp;theme=light" allowfullscreen="allowfullscreen" class="embedded_youtube_video"></iframe>
	</div>
			</div>					
			</div>
		</div>
	</div>
</div>'
		
		;
   }
   
}



//add_action( 'woocommerce_single_product_summary', 'gees_print_field_video_manual', 8 );
 
function gees_print_field_video_manual(){
	global $post;
	$id = get_post_meta( $post->ID, '_gees_manual_id', true );

	if ( ! empty ( $id ) ) {
		
	print '<div class="gees-accordion2">
			<div class="gees-mfn-acc2 gees-accordion_wrapper open1st">
				
				<div class="gees-question">
				
					<div class="gees-title">Materiały do pobrania</div>
					
					<div class="gees-answer">';
	print'				<p style="padding: 20px 0px;">
							<a class="button  button_js" href=" '.$id.'" target="_blank" style=" background-color:#e95d0f !important; color:white !important;"><span class="button_label"><i class="icon-download"></i> POBIERZ INSTRUKCJĘ</span></a>
						</p>
					</div>
				</div>';
	print'	</div>

		</div>';
	

   }
   
}


add_action( 'woocommerce_single_product_summary', 'gees_print_field_ean', 37 );
 
function gees_print_field_ean(){
	global $post;
	$id = get_post_meta( $post->ID, '_gees_ean', true );
	
	if ( ! empty ( $id ) ) {
	echo ' <p class="ean_wrapper">EAN: <span class="ean" itemprop="ean">'.$id .'</span></p>';
   }
   
}

//add_action( 'woocommerce_product_meta_start', 'gees_print_field_manaf', 37 );
 
function gees_print_field_manaf2(){
	global $post;
	$id = get_post_meta( $post->ID, '_gees_manaf', true );
	
	if ( ! empty ( $id ) ) {
//	echo ' <p class="ean_wrapper">Producent: <span class="ean" itemprop="ean">'.$id .'</span></p>';
   }
   
}

add_action( 'woocommerce_single_product_summary', 'gees_print_field_qr', 250 );
 
function gees_print_field_qr(){
	global $post;
	$id = get_page_link( $post->ID );
	
	if ( ! empty ( $id ) ) {
	//	echo do_shortcode('[qrcode content="'.$id.'" size="320" alt="FERM POLSKA" ]');
   }
   
}

	
}
?>