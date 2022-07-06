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

if ( ! class_exists( 'GeeShop_appserver' ) ) :


	final class GeeShop_appserver extends GeeShopClass {  
		
		protected static $_instance = null;
		private $images_lists = array();
		private $Spiler_categories = array();
		private $categories_list = array();
		
		public function __construct() {
			parent::__construct();
			$this->version = '1.0.0';
		}
	
		public function settings_callback($response=array()){
			require_once(GEESHOP_PLUGIN_DIR . ( 'addin/appserver/appserver.settings.class.php') );
		}
		
		public function plugin_admin_menu() {
			parent::plugin_admin_menu();
					add_submenu_page( 'geeshop_options', __( 'ERP - Ustawienia', 'geeshop' ), __( 'ERP - Ustawienia', 'geeshop' ), 'manage_product_terms', 'insert_settings', array( $this, 'settings_callback' ) );
		
		}

		public function geeshop_load_css(){
		}	

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	
		private function  text_to_xml($text){

			return htmlspecialchars(trim($text));
		}
			
	}

	function GShappserver() {
		return GeeShop_appserver::instance();
	}

endif;
// Global for backwards compatibility.
$GLOBALS['GeeShop_zappserver'] = GShappserver();

	function ajax_geeshop_sgt_auth(){
		$data = get_option( 'geeshop_subiekt_general' );
		$token_svd =  isset($data['token']) ? $data['token'] : "";
		//$actual = str_replace('allegro_now.class.php', 'allegro_kategorie_result.xml', $actual); 
		$token = (isset($_POST["parametry"])? $_POST["parametry"] : "");
		$komunikat = "";
		$actual = "";
		if ($token_svd == $token ){
			$actual = GEESHOP_PLUGIN_URI."/addin/appserver/app_server.php";
			
		}else{
			$komunikat = "Token w aplikacji różni się od tokenu wprowadzonym w sklepie internetowym";
		} 
			
		$array = array('link'=>$actual,"komunikat"=> $komunikat );
		
		
		print json_encode($array, true);
	}
	add_action( 'wp_ajax_geeshop_sgt_auth', 'ajax_geeshop_sgt_auth' ) ;
	add_action( 'wp_ajax_nopriv_geeshop_sgt_auth',  'ajax_geeshop_sgt_auth' ) ;

add_action( 'show_user_profile', 'geeshop_profile_fields' );
add_action( 'edit_user_profile', 'geeshop_profile_fields' );

function geeshop_profile_fields( $user ) { ?>
    <h3><?php _e("Dane do Faktury VAT", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="gees_invoice_firm_fullname"><?php _e("Pełna nazwa firmy"); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_fullname" id="gees_invoice_firm_fullname" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_fullname', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj pełną nazwę firmy."); ?></span>
        </td>
    </tr>
    <tr>
        <th><label for="gees_invoice_firm_vat"><?php _e("NIP Podmiotu"); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_vat" id="gees_invoice_firm_vat" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_vat', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj NIP"); ?></span>
        </td>
    </tr>
    <tr>
    <th><label for="gees_invoice_firm_street"><?php _e("Ulica "); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_street" id="gees_invoice_firm_street" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_street', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj ulicę."); ?></span>
        </td>
    </tr>
    <th><label for="gees_invoice_firm_street_nr"><?php _e("Nr budynku "); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_street_nr" id="gees_invoice_firm_street_nr" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_street_nr', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj nr budynku."); ?></span>
        </td>
    </tr>
    <th><label for="gees_invoice_firm_street_nr2"><?php _e("Nr lokalu"); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_street_nr2" id="gees_invoice_firm_street_nr2" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_street_nr2', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj nr lokalu."); ?></span>
        </td>
    </tr>
    <th><label for="gees_invoice_firm_postcode"><?php _e("Kod pocztowy"); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_postcode" id="gees_invoice_firm_postcode" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_postcode', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj kod pocztowy."); ?></span>
        </td>
    </tr>
    <th><label for="gees_invoice_firm_city"><?php _e("Miasto"); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_city" id="gees_invoice_firm_city" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_city', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj miasto."); ?></span>
        </td>
    </tr>
    <th><label for="gees_invoice_firm_phone"><?php _e("Nr telefonu"); ?></label></th>
        <td>
            <input type="text" name="gees_invoice_firm_phone" id="gees_invoice_firm_phone" value="<?php echo esc_attr( get_the_author_meta( 'gees_invoice_firm_phone', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Podaj nr telefonu."); ?></span>
        </td>
    </tr>
    </table>
<?php }	
	
add_action( 'personal_options_update', 'save_geeshop_profile_fields' );
add_action( 'edit_user_profile_update', 'save_geeshop_profile_fields' );

function save_geeshop_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'gees_invoice_firm_fullname', $_POST['gees_invoice_firm_fullname'] );
    update_user_meta( $user_id, 'gees_invoice_firm_vat', $_POST['gees_invoice_firm_vat'] );
    update_user_meta( $user_id, 'gees_invoice_firm_street', $_POST['gees_invoice_firm_street'] );
    update_user_meta( $user_id, 'gees_invoice_firm_street_nr', $_POST['gees_invoice_firm_street_nr'] );
    update_user_meta( $user_id, 'gees_invoice_firm_street_nr2', $_POST['gees_invoice_firm_street_nr2'] );
    update_user_meta( $user_id, 'gees_invoice_firm_postcode', $_POST['gees_invoice_firm_postcode'] );
    update_user_meta( $user_id, 'gees_invoice_firm_city', $_POST['gees_invoice_firm_city'] );
    update_user_meta( $user_id, 'gees_invoice_firm_phone', $_POST['gees_invoice_firm_phone'] );
}	
	