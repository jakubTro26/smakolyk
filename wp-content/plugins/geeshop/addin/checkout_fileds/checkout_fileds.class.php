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


if ( ! class_exists( 'GeeShop_Checkout_Fileds' ) ) :


	final class GeeShop_Checkout_Fileds extends GeeShopClass {  
		
		protected static $_instance = null;
		
		public function __construct() {
			$this->version = '1.0.0';
			parent::__construct();
			//$this->init_hooks();
		}

		public function plugin_admin_menu(){
				
		}
		
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		public function init_hooks() {
			add_action( 'wp_enqueue_scripts', array( $this, 'geeshop_load_css' ));
		}
		public function geeshop_load_css() {
			wp_enqueue_style( 'checkout_fileds', GEESHOP_PLUGIN_URI. 'addin/checkout_fileds/asset/css/checkout_fileds2.css');			
			wp_enqueue_script( 'checkout_fileds', GEESHOP_PLUGIN_URI. 'addin/checkout_fileds/asset/js/checkout_fileds.js', false, time(), true );
		}
			
	}

	function GShCheckoutFileds() {
		
		return GeeShop_Checkout_Fileds::instance();
	}

endif;
// Global for backwards compatibility.
$GLOBALS['GeeShop_Checkout_Fileds'] = GShCheckoutFileds();
	
	/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_after_order_notes', 'gees_custom_checkout_field' );

function gees_custom_checkout_field( $checkout ) {
	
	$user_id = get_current_user_id();
	$data = get_option( 'geeshop_subiekt_form_tax' );
	$css_form_hide = (isset($data['form_hide']) and !empty($data['form_hide'])) ? ' geeshop-erp-hidenew' : '';
	$css_form_readonly = (isset($data['form_readonly']) and !empty($data['form_readonly'])) ? ' geeshop-erp-readonly' : '';
	$css_form_show = (isset($data['form_show']) and !empty($data['form_show'])) ? ' geeshop-erp-readonly geeshop-erp-show' : '';
	$css_form_show_default = (isset($data['form_show']) and !empty($data['form_show'])) ? 1 : 0;
	$gees_custom_checkout_field_hide = (isset($data['form_show']) and !empty($data['form_show'])) ? ' gees_custom_checkout_field_show' : 'gees_custom_checkout_field_hide';

	
	
	$css = $css_form_readonly;
    echo '<div id="gees_custom_checkout_field" class="'.$css_form_hide .'"><h2>' . __('Dane do Faktury','geeshop_plugin') . '</h2>';
	 woocommerce_form_field( 'gees_invoice_add', array(
        'type'          => 'checkbox',
        'class'         => array('gees-field-class form-row-wide'.$css_form_show ),
        'label'         => __('Wystaw Fakturę VAT','geeshop_plugin'),
		'value'  		=> $css_form_show_value, 
		'default' 		=> $css_form_show_default
        ), $checkout->get_value( 'gees_invoice_add' ));
	
	echo '<div id="'.$gees_custom_checkout_field_hide.'">';
	$value = $checkout->get_value( 'gees_invoice_firm_fullname' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_fullname', true);

	woocommerce_form_field( 'gees_invoice_firm_fullname', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-fullname form-row-wide'.$css),
        'label'         => __('Pełna nazwa firmy','geeshop_plugin'),
        'placeholder'   => __('Podaj pełną nazwe firmy','geeshop_plugin'),
		'required'  	=> true, 
        ), $value);

	$value = $checkout->get_value( 'gees_invoice_firm_name' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_fullname', true);
		
	woocommerce_form_field( 'gees_invoice_firm_name', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-name form-row-wide'.$css),
        'label'         => __('Nazwa firmy','geeshop_plugin'),
        'placeholder'   => __('Podaj nazwe firmy','geeshop_plugin'),
		'required'  	=> true, 
        ), $value);
	
	$value = $checkout->get_value( 'gees_invoice_firm_vat' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_vat', true);
    
	woocommerce_form_field( 'gees_invoice_firm_vat', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-vat form-row-wide'.$css),
        'label'         => __('NIP','geeshop_plugin'),
        'placeholder'   => __('Podaj NIP firmy','geeshop_plugin','geeshop_plugin'),
		'required'  	=> true, 
        ), $value);
	$value = $checkout->get_value( 'gees_invoice_firm_street' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_street', true);
		
	woocommerce_form_field( 'gees_invoice_firm_street', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-street form-row-wide'.$css),
        'label'         => __('Ulica:','geeshop_plugin'),
        'placeholder'   => __('Podaj ulicę','geeshop_plugin'),
		'required'  	=> true, 
        ), $value);

	$value = $checkout->get_value( 'gees_invoice_firm_street_nr' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_street_nr', true);
	
	woocommerce_form_field( 'gees_invoice_firm_street_nr', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-str-nr form-row-wide'.$css),
        'label'         => __('Numer:','geeshop_plugin'),
        'placeholder'   => __('Podaj numer','geeshop_plugin'),
		'required'  	=> true, 
        ), $value);

	$value = $checkout->get_value( 'gees_invoice_firm_street_nr2' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_street_nr2', true);
		
	woocommerce_form_field( 'gees_invoice_firm_street_nr2', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-str-nr2 form-row-wide'.$css),
        'label'         => __('Lokal:','geeshop_plugin'),
        'placeholder'   => __('Podaj lokal','geeshop_plugin'),
        ), $value);
	
	$value = $checkout->get_value( 'gees_invoice_firm_postcode' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_postcode', true);
						
	woocommerce_form_field( 'gees_invoice_firm_postcode', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-potcode form-row-wide'.$css),
        'label'         => __('Kod pocztowy','geeshop_plugin'),
        'placeholder'   => __('Podaj Kod pocztowy','geeshop_plugin'),
		'required'  	=> true, 
        ), $value);
		
	$value = $checkout->get_value( 'gees_invoice_firm_city' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_city', true);
		
	woocommerce_form_field( 'gees_invoice_firm_city', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-city form-row-wide'.$css),
        'label'         => __('Miasto','geeshop_plugin'),
        'placeholder'   => __('Podaj Miasto','geeshop_plugin'),
		'required'  	=> true, 
        ), $value );
		
	$value = $checkout->get_value( 'gees_invoice_firm_phone' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_phone', true);
		
	woocommerce_form_field( 'gees_invoice_firm_phone', array(
        'type'          => 'text',
        'class'         => array('gees-field-class form-row-wide'.$css),
        'label'         => __('Telefon kontaktowy','geeshop_plugin'),
        'placeholder'   => __('Podaj telefon kontaktowy','geeshop_plugin'),
		'required'  	=> true, 
        ), $value);

		$value = $checkout->get_value( 'gees_invoice_firm_fullname' );
	if ( empty ( $value ))
		$value = get_user_meta( $user_id, 'gees_invoice_firm_fullname', true);
		
	woocommerce_form_field( 'gees_invoice_firm_id', array(
        'type'          => 'text',
        'class'         => array('gees-field-class form-row-wide gees-hidden-field'.$css),
        'label'         => __('ID klienta','geeshop_plugin'),
        'placeholder'   => __('Podaj ID klienta','geeshop_plugin'),
        ), $checkout->get_value( 'gees_invoice_firm_id' ));
		
	woocommerce_form_field( 'gees_invoice_firm_id_ext', array(
        'type'          => 'text',
        'class'         => array('gees-field-class form-row-wide gees-hidden-field'.$css),
        'label'         => __('ID klienta w systemie ERP','geeshop_plugin'),
        'placeholder'   => __('Podaj ID klienta  w systemie ERP','geeshop_plugin'),
        ), $checkout->get_value( 'gees_invoice_firm_id_ext' ));
	echo '	</div>';
	
    echo '</div>';

}
	
/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'gees_custom_checkout_field_process');

function gees_custom_checkout_field_process() {
    if ( isset( $_POST['gees_invoice_add'] ) and !empty( $_POST['gees_invoice_add'] ) )
	{
		
		if ( ! $_POST['gees_invoice_firm_fullname'] )
			wc_add_notice( __( 'Podaj pełną nazwę firmy.' ,'geeshop_plugin'), 'error' );
		if ( ! $_POST['gees_invoice_firm_vat'] )
			wc_add_notice( __( 'Podaj numer NIP ' ,'geeshop_plugin'), 'error' );
		if ( ! $_POST['gees_invoice_firm_street'] )
			wc_add_notice( __( 'Podaj adres (ulicę)' ,'geeshop_plugin'), 'error' );
		if ( ! $_POST['gees_invoice_firm_street_nr'] )
			wc_add_notice( __( 'Podaj adres (numer) ' ,'geeshop_plugin'), 'error' );
		if ( ! $_POST['gees_invoice_firm_postcode'] )
			wc_add_notice( __( 'Podaj kod pocztowy' ,'geeshop_plugin'), 'error' );
		if ( ! $_POST['gees_invoice_firm_city'] )
			wc_add_notice( __( 'Podaj Miasto' ,'geeshop_plugin'), 'error' );

	
	}
}	

	/**
	 * Update the order meta with field value
	 */
	add_action( 'woocommerce_checkout_update_order_meta', 'gees_custom_checkout_field_update_order_meta' );

	function gees_custom_checkout_field_update_order_meta( $order_id ) {

		$user_id = get_current_user_id();
		
    update_user_meta( $user_id, 'gees_invoice_firm_fullname', sanitize_text_field($_POST['gees_invoice_firm_fullname']) );
    update_user_meta( $user_id, 'gees_invoice_firm_vat', sanitize_text_field($_POST['gees_invoice_firm_vat']) );

		//if ( ! empty( $_POST['gees_invoice_firm_fullname'] ) ) 
		{
			update_post_meta( $order_id, 'gees_invoice_firm_fullname', sanitize_text_field( $_POST['gees_invoice_firm_fullname'] ) );
		}		
		//if ( ! empty( $_POST['gees_invoice_firm_name'] ) ) 
		{
			update_post_meta( $order_id, 'gees_invoice_firm_name', sanitize_text_field( $_POST['gees_invoice_firm_name'] ) );
		}	
		//if ( ! empty( $_POST['gees_invoice_firm_vat'] ) ) 
		{
			update_post_meta( $order_id, 'gees_invoice_firm_vat', sanitize_text_field( $_POST['gees_invoice_firm_vat'] ) );
		}	
		//if ( ! empty( $_POST['gees_invoice_firm_street'] ) ) 
		{
			update_post_meta( $order_id, 'gees_invoice_firm_street', sanitize_text_field( $_POST['gees_invoice_firm_street'] ) );
			update_user_meta( $user_id, 'gees_invoice_firm_street', sanitize_text_field($_POST['gees_invoice_firm_street']) );
		}	
		if ( ! empty( $_POST['gees_invoice_firm_street_nr'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_firm_street_nr', sanitize_text_field( $_POST['gees_invoice_firm_street_nr'] ) );
			update_user_meta( $user_id, 'gees_invoice_firm_street_nr', sanitize_text_field($_POST['gees_invoice_firm_street_nr']) );
		}	
		if ( ! empty( $_POST['gees_invoice_firm_street_nr2'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_firm_street_nr2', sanitize_text_field( $_POST['gees_invoice_firm_street_nr2'] ) );
			update_user_meta( $user_id, 'gees_invoice_firm_street_nr2', sanitize_text_field($_POST['gees_invoice_firm_street_nr2']) );
		}	
		if ( ! empty( $_POST['gees_invoice_firm_postcode'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_firm_postcode', sanitize_text_field( $_POST['gees_invoice_firm_postcode'] ) );
			update_user_meta( $user_id, 'gees_invoice_firm_postcode', sanitize_text_field($_POST['gees_invoice_firm_postcode']) );
		}	
		if ( ! empty( $_POST['gees_invoice_firm_city'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_firm_city', sanitize_text_field( $_POST['gees_invoice_firm_city'] ) );
			update_user_meta( $user_id, 'gees_invoice_firm_city', sanitize_text_field($_POST['gees_invoice_firm_city']) );
		}	
		if ( ! empty( $_POST['gees_invoice_firm_phone'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_firm_phone', sanitize_text_field( $_POST['gees_invoice_firm_phone'] ) );
			update_user_meta( $user_id, 'gees_invoice_firm_phone', sanitize_text_field($_POST['gees_invoice_firm_phone']) );
		}
		if ( ! empty( $_POST['gees_invoice_add'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_add', sanitize_text_field( $_POST['gees_invoice_add'] ) );
		}
		if ( ! empty( $_POST['gees_delivery_tracking'] ) ) {
			update_post_meta( $order_id, 'gees_delivery_tracking', sanitize_text_field( $_POST['gees_delivery_tracking'] ) );
		}
//		}
//		if ( ! empty( $_POST['gees_delivery_tracking'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_firm_id', sanitize_text_field( $_POST['gees_invoice_firm_id'] ? $_POST['gees_invoice_firm_id'] : '') ) ;
			update_post_meta( $order_id, 'gees_invoice_firm_id_ext', sanitize_text_field( ($_POST['gees_invoice_firm_id_ext'] ? $_POST['gees_invoice_firm_id_ext'] : '') ) );
	//	}
//		if ( ! empty( $_POST['gees_invoice_nr'] ) ) {
	//		update_post_meta( $order_id, 'gees_invoice_nr', sanitize_text_field( $_POST['gees_invoice_nr'] ) );
		//}
//		if ( ! empty( $_POST['gees_delivery_nr'] ) ) {
	//		update_post_meta( $order_id, 'gees_delivery_nr', sanitize_text_field( $_POST['gees_delivery_nr'] ) );
		//}
		// z hostorii przekierowan
		geeshop_add_order_meta_appid( $order_id );
	}

	function geeshop_add_order_meta_appid( $order_id ) {
		
		if ( !empty( $order_id )){				
			$order = new WC_Order($order_id);
			// z hostorii przekierowan
				$appid = isset($_SESSION['appid']) ? $_SESSION['appid'] : (isset($_GET['appid']) ? $_GET['appid'] : '');					
				if (!empty ($appid)){
					$value = '';
					switch($appid){
						case 'hsy1263912hej83552': 
							$value = 'Ceneo.pl';
						break;
						case 'akjshduwhdiqbcn': 
							$value = 'Skąpiec.pl';
						break;
						case '129879djhgkjhg1': 
							$value = 'Nokaut.pl';
						break;
						default:
							$value = 'www';
						break;
					}
					if (!empty($value)){
						$order->add_order_note(__('Zakup z serwisu: ','geeshop_plugin').$value);						
						update_post_meta( $order->id, 'order_service', $value );
					}
				}
		}
	}
	//add_action( 'save_post', 'gees_custom_checkout_field_update' );
	
	function gees_custom_checkout_field_update() {
		global $post;
		$order_id = $post->ID;
		if ( ! empty( $_POST['gees_invoice_copy'] ) ) {
			update_post_meta( $order_id, 'gees_invoice_copy', sanitize_text_field( $_POST['gees_invoice_copy'] ) );
		}
		if ( ! empty( $_POST['gees_delivery_nr'] ) ) {
			update_post_meta( $order_id, 'gees_delivery_nr', sanitize_text_field( $_POST['gees_delivery_nr'] ) );
		}
		if ( ! empty( $_POST['gees_delivery_tracking'] ) ) {
			update_post_meta( $order_id, 'gees_delivery_tracking', sanitize_text_field( $_POST['gees_delivery_tracking'] ) );
		}
		if ( ! empty( $_POST['gees_delivery_doc'] ) ) {
			update_post_meta( $order_id, 'gees_delivery_doc', sanitize_text_field( $_POST['gees_delivery_doc'] ) );
		}
		
	}
	add_action( 'woocommerce_admin_order_data_after_shipping_address', 'gees_delivery_checkout_field_display_admin_order_meta', 10, 1 );

	function get_addresses_10ka($value){
		$res = get_option( 'geeshop_10ka_addresses');
		$adresy = "<option value=''><b>-- ".__('Wybierz','geeshop_plugin'). "--</b></option>"; 
		if ( is_array( $res ) ){
			foreach($res as $key=> $item){
				if (isset($item->type) and $item->type == 'success'){
					foreach($item->addresses as $k=>$pos){
						$adresy.='<option value="'.$k.'"'.((($k == $value)) ? 'selected="selected"' : '').'><b>'.$key.' - '.$pos.' </b></option>';
					
					}
				}
			}
		}
		return $adresy;
	}
	
	function gees_10ka_options(){
		$data = get_option( 'geeshop_10ka_general' );
		$address_value =  isset($data['address']) ? intval($data['address']) : '';				
		$address = get_addresses_10ka($address_value);
		return '<table><tbody><tr valign="top">
						<th scope="row" class="titledesc" style="text-align: left;">
							<label for="woocommerce_default_country">'.__('Wysyłka','geeshop_plugin').'</label>
										</th></tr>
					<tr valign="top">
						<td class="forminp"><div class="select2-container wc-enhanced-select enhanced" id="s2id_autogen1" title="Country" style="min-width:350px;">
					<select name="geeshop[10ka][general][delivery]">
						<option value="pocztex" '.((isset($data['delivery']) and ($data['delivery'] == 'pocztex')) ? 'selected="selected"' : '').'>Pocztex 24h</option>
						<option value="kurier48" '.((isset($data['delivery']) and ($data['delivery'] == 'kurier48')) ? 'selected="selected"' : '').'>Kurier 48</option>
						<option value="paletowa" '.((isset($data['delivery']) and ($data['delivery'] == 'paletowa')) ? 'selected="selected"' : '').'>Paletowa</option>
		
				</select>
			</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc" style="text-align: left;">
						
							<label for="woocommerce_default_country">'.__('Adres wysyłki','geeshop_plugin').'</label>
							
							</th>
							</tr>
					<tr valign="top">
						<td class="forminp forminp-select">
						<select name="geeshop[10ka][general][address]">
							'.$address.'
							</select>
					</tr>
					
					</tbody></table>';
					
	}
	
	function gees_delivery_checkout_field_display_admin_order_meta($order){
		$form = get_post_meta( $order->id, 'gees_delivery_nr', true );
		echo '<div class="gees-delivery">';
		echo '<p>';
		echo '<h4>List przewozowy:</h4>';
		if (empty($form)){
			//echo '<form name="Form_delivery"  action="" method="post">';						
			echo '<input type="hidden" name="gees-10ka-actions" value="">';
			echo '<input type="hidden" name="gees-order-id" value="'.$order->id.'">';
		//	echo '<input type="hidden" id="delivery_nr_run" name="delivery_nr_run" class="wc-product-search" style="width: 100%;" data-placeholder="Szukaj produktu&hellip;" data-multiple="true" />';
//			echo '<a href="javascript:document.Form1.submit();">Generuj</a>';
			//echo '<a href="#" onclick="document.forms[\'Form1\'].submit();return false;"><img src="whatever.jpg" /></a>';
		//	echo '<input class="button button-primary 10ka-button-generuj" type="submit" class="submit" name="generuj" Value="Generuj"/>';
			echo '<span class="button button-primary 10ka-button-generuj-click" >Generuj list przewozowy</span>';
			//echo '</form>';
			$options = gees_10ka_options();
			echo '<div class="a10ka-button-generuj-div" style="display:none; text-align: left; min-width: 300px; position: absolute; ">'.$options.'<br><span class="button button-primary 10ka-button-generuj">Generuj</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="button button-primary 10ka-button-close">Zamknij okno</span></div>';
		}else{
			//echo '<p><strong>Firmy:</strong> ' . get_post_meta( $order->id, 'gees_invoice_firm_fullname', true ) ;
//			echo '<form name="Form_delivery"  action="" method="post">';
	//		echo '<input type="hidden" id="delivery_nr_run" name="delivery_nr_run" class="wc-product-search" style="width: 100%;" data-placeholder="Szukaj produktu&hellip;" data-multiple="true" />';
//			echo '<a href="javascript:document.Form1.submit();">Generuj</a>';
			//echo '<a href="#" onclick="document.forms[\'Form1\'].submit();return false;"><img src="whatever.jpg" /></a>';
			echo '<input type="hidden" name="gees-10ka-actions" value="">';
			echo '<input type="hidden" name="gees-order-id" value="'.$order->id.'">';
			echo '<span class="button button-primary 10ka-button-pobierz" >Pobierz list przewozowy z 10ka.pl</span>';
			//echo '<input type="submit" class="button button-primary" name="Pobierz" Value="Pobierz"/>';
						
		}
	//	echo '<h4>Nr przesyłki:</h4>';
		woocommerce_form_field( 'gees_delivery_nr', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-fullname form-row-wide'),
        'label'         => __('Nr przesyłki','geeshop_plugin'),
        'placeholder'   => __('Podaj nr przesyłki','geeshop_plugin'),
        ), get_post_meta( $order->id, 'gees_delivery_nr', true ));
		$doc = get_post_meta( $order->id, 'gees_delivery_doc', true );
		woocommerce_form_field( 'gees_delivery_doc', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-fullname form-row-wide'),
        'label'         => __('Link do listu przewozowego','geeshop_plugin'),
        'placeholder'   => __('Podaj link do listu przewozowego','geeshop_plugin'),
        ), $doc);
		
		
		if (!empty($doc))
			echo '<a href="'.$doc.'" target="_new">Wyświetl list przewozowy do wydruku</a>';
		
		echo '</p>';
		//echo '</form>';
		echo '</div>';
		echo '<div class="gees-delivery-tracking">';
		//echo '<form name="Form_delivery"  action="" method="post">';
		echo '<p>';		
		echo '<h4>Śledzenie przesyłki:</h4>';
	
		$tracking = get_post_meta( $order->id, 'gees_delivery_tracking', true );
		woocommerce_form_field( 'gees_delivery_tracking', array(
        'type'          => 'text',
        'class'         => array('gees-field-class-fullname form-row-wide'),
        'label'         => __('Link do śledzenia przesyłki','geeshop_plugin'),
        'placeholder'   => __('Podaj link do śledzenia przesyłki','geeshop_plugin'),
        ), $tracking);
		if (!empty($tracking))
			echo '<a href="'.$tracking.'" target="_new">Śledź przesyłkę</a>';
		
		//echo '<input type="hidden" id="delivery_nr_run" name="gees-mode" />';
		
	//	echo '<input type="submit" class="button button-primary" name="ZapiszNumer" Value="Zapisz dane wysyłki"/>';
		echo '</p>';
	//	echo '</form>';
		echo '</div>';
		
	}

	
	add_action( 'woocommerce_admin_order_data_after_shipping_address', 'gees_custom_checkout_field_display_admin_order_meta', 10, 1 );

	function gees_custom_checkout_field_display_admin_order_meta($order){
		$form = get_post_meta( $order->id, 'gees_invoice_add', true );
		if (!empty($form)){
			echo '<h4>'.__('Dane do faktury VAT:','geeshop_plugin').'</h4>';
			echo '<p><strong>'.__('Firma:','geeshop_plugin').'</strong> ' . get_post_meta( $order->id, 'gees_invoice_firm_fullname', true ) ;
			echo ' ' . get_post_meta( $order->id, 'gees_invoice_firm_name', true );
			echo '<br><strong>'.__('NIP:','geeshop_plugin').'</strong> ' . get_post_meta( $order->id, 'gees_invoice_firm_vat', true ) . '';
			echo '<br><strong>'.__('Adres:','geeshop_plugin').'</strong> ' . get_post_meta( $order->id, 'gees_invoice_firm_street', true ) . '';
			echo ' ' . get_post_meta( $order->id, 'gees_invoice_firm_street_nr', true ) ;
			echo ' ' . get_post_meta( $order->id, 'gees_invoice_firm_street_nr2', true ) ;
			echo '<br>' . get_post_meta( $order->id, 'gees_invoice_firm_postcode', true );
			echo ' ' . get_post_meta( $order->id, 'gees_invoice_firm_city', true ) ;
			echo '<br> '.__('Telefon:','geeshop_plugin') . get_post_meta( $order->id, 'gees_invoice_firm_phone', true );
			$id = get_post_meta( $order->id, 'gees_invoice_firm_id', true );
			echo !empty($id) ? '<br> '.__('ID Klienta: ','geeshop_plugin') . $id:'';
			$id = get_post_meta( $order->id, 'gees_invoice_firm_id_ext', true );
			echo !empty($id)? '<br> '.__('ID Klienta w systemie ERP: ','geeshop_plugin') . $id : '';
			//echo '<br> Dokument sprzedaży: ' . get_post_meta( $order->id, 'gees_invoice_firm_phone', true );
			
			echo '</p>';
		}
		echo '<h4>'.__('Dokument sprzedaży:','geeshop_plugin').'</h4>';
		echo '<p>';
		//echo '<br> Dokument sprzedaży: ' . get_post_meta( $order->id, 'gees_invoice_copy', true );
		
		$doc =  get_post_meta( $order->id, 'gees_invoice_copy', true );	
		woocommerce_form_field( 'gees_invoice_copy', array(
			'type'          => 'text',
			'class'         => array('gees-field-class-fullname form-row-wide'),
			'label'         => __('Link do dokumentu sprzedaży','geeshop_plugin'),
			'placeholder'   => __('Podaj link do dokumentu sprzedaży','geeshop_plugin'),
			), $doc);		
					
		if (!empty($doc))
			echo '<a href="'.$doc.'" target="_new">',__('Pobierz dokument sprzedaży','geeshop_plugin').'</a>';
		echo '</p>';
		
	}
	

	function gees_display_order_data_in_admin3( $order_id ){
    print ' <div class="order_data_column">';
	$form = get_post_meta( $order_id, 'gees_invoice_add', true );
		//if (!empty($form))
		{
			echo '<h4>'.__('Dane do faktury VAT','geeshop_plugin').':</h4>';
			echo '<p><strong>'.__('Firma','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_fullname', true ) ;
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_name', true );
			echo '<br><strong>'.__('NIP','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_vat', true ) . '';
			echo '<br><strong>'.__('Adres','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_street', true ) . '';
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_street_nr', true ) ;
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_street_nr2', true ) ;
			echo '<br>' . get_post_meta( $order_id, 'gees_invoice_firm_postcode', true );
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_city', true ) ;
			$tel = get_post_meta( $order_id, 'gees_invoice_firm_phone', true );
			echo (!empty($tel) ? '<br> '.__('Telefon','geeshop_plugin').': '. $tel:'');
			
			$id = get_post_meta( $order_id, 'gees_invoice_firm_id', true );
			echo !empty($id) ? '<br> '.__('ID Klienta','geeshop_plugin').': ' . $id:'';
			$id = get_post_meta( $order_id, 'gees_invoice_firm_id_ext', true );
			echo !empty($id)? '<br> '.__('ID Klienta w systemie ERP','geeshop_plugin').': ' . $id : '';
			//echo '<br> Dokument sprzedaży: ' . get_post_meta( $order->id, 'gees_invoice_firm_phone', true );
			
			echo '</p>';
		}
		
    print '</div>';
}

add_filter( 'woocommerce_email_order_meta_keys', 'gees2_some_order_meta_keys' );
 
	function gees2_some_order_meta_keys() {
		//				echo '<br /><h2>TEST gees2_some_order_meta_keys</h2>';

		$link = get_post_meta( get_the_ID(), 'gees_delivery_tracking', true);
		if (!empty($link)) {
			echo '<br /><h2>Śledzenie przesyłki</h2> <a href="' . $link . '"> Kliknij aby śledzić przesyłkę</a><br /><br />';
			echo 'Jeżeli nie możesz uruchomić śledzenia przesyłki, uruchom przeglądarkę i wklej ten link:<br /> ' . $link . '<br />';
		}
		$link_fv = get_post_meta( get_the_ID(), 'gees_invoice_copy', true);
		if (!empty($link_fv)) {
			echo '<br /><h2>Dokument sprzedaży - <b>eFaktura</b></h2> <a href="' . $link_fv . '"> Kliknij tutaj, aby pobrać dokument sprzedaży (eFakturę)</a><br /><br />';
			echo 'Jeżeli nie udało się pobrać efaktury, uruchom przeglądarkę i wklej ten link:<br /> <b>' . $link_fv . '</b><br /><br />';
		}	

	}



	/**
	 * Add the field to order emails
	 **/
	add_filter('woocommerce_email_order_meta_keys', 'gees_woocommerce_email_order_meta_keys');

	
	
	
	
	function gees_woocommerce_email_order_meta_keys( $keys ) {
			//	echo 'Tracking przesyłki:  gees_woocommerce_email_order_meta_keys <br />';

	
		$keys[] = __('Dane do faktury VAT','geeshop_plugin'); 
		$keys['<strong>'.__('Firma','geeshop_plugin').':</strong> '] = 'gees_invoice_firm_fullname';
		$keys['<strong>'.__('Pelna nazwa','geeshop_plugin').' </strong> '] = 'gees_invoice_firm_name';
		$keys['<strong>'.__('NIP','geeshop_plugin').'</strong> '] = 'gees_invoice_firm_vat';
		$keys['<strong>'.__('Adres','geeshop_plugin').'</strong> '] = 'gees_invoice_firm_street';
		$keys['<strong>'.__('Nr budynku','geeshop_plugin').'</strong> '] = 'gees_invoice_firm_street_nr';
		$keys['<strong>'.__('Nr lokalu','geeshop_plugin').'</strong> '] = 'gees_invoice_firm_street_nr2';
		$keys['<strong>'.__('Kod pocztowy','geeshop_plugin').'</strong> '] = 'gees_invoice_firm_postcode';
		$keys['<strong>'.__('Miejscowość','geeshop_plugin').'</strong> '] = 'gees_invoice_firm_city';
		$keys['<strong>'.__('Telefon','geeshop_plugin').'</strong> '] = 'gees_invoice_firm_phone';
		
  
		return $keys;
	}
	
	//add_filter('woocommerce_email_heading_new_order', 'my_email_heading_customisation_function', 1, 2);

	/*
	// Change new order email recipient for registered customers
function gees_order_fields_email_recipient( $recipient, $order ) {
    global $woocommerce;
    if ( check_user_role( 'customer' ) ) {
        $recipient = "accounts@yourdomain.com";
    } else {
        $recipient = "newbusiness@yourdomain.com";
    }
    return $recipient;
}
add_filter('woocommerce_order_status_completed_notification', 'gees_order_fields_email_recipient', 1, 2);
	
	*/
	if ( is_admin() ) {
	
	}else {
			//require_once(GEESHOP_PLUGIN_DIR . ( 'theme/geeshop-product-fields.php') );
		}
		add_action( 'woocommerce_thankyou', 'email_custom_tracking' );

function email_custom_tracking( $order_id ) {
//						echo '<br /><h2>TEST email_custom_tracking</h2>';

//// Lets grab the order
//$order = wc_get_order( $order_id );
//print " order id :".$order_id;
//print " TOTAL:".$order->get_order_total();
			$link = get_post_meta( $order_id, 'gees_delivery_tracking', true);
			if (!empty($link)) {
				echo '<br /><h2>Śledzenie przesyłki</h2> <a href="' . $link . '"> Kliknij aby śledzić przesyłkę</a><br /><br />';
				echo 'Jeżeli nie możesz uruchomić śledzenia przesyłki, uruchom przeglądarkę i wklej ten link:<br /> ' . $link . '<br />';
			}
			$link_fv = get_post_meta( $order_id, 'gees_invoice_copy', true);
			if (!empty($link_fv)) {
				echo '<br /><h2>Dokument sprzedaży - <b>eFaktura</b></h2> <a href="' . $link_fv . '"> Kliknij tutaj, aby pobrać dokument sprzedaży (eFakturę)</a><br /><br />';
				echo 'Jeżeli nie udało się pobrać efaktury, uruchom przeglądarkę i wklej ten link:<br /> <b>' . $link_fv . '</b><br /><br />';
			}	

}


//add_action('woocommerce_order_status_completed_notification','email_custom_tracking_invoice');
function email_custom_tracking_invoice( $order_id ) {
//	$email = new WC_Email_Customer_Invoice();
  //  $email->trigger($order_id);

	//					echo '<br /><h2>TEST email_custom_tracking_invoice</h2>';

//// Lets grab the order
//$order = wc_get_order( $order_id );
//print " order id :".$order_id;
//print " TOTAL:".$order->get_order_total();
/*			$link = get_post_meta( $order_id, 'gees_delivery_tracking', true);
			if (!empty($link)) {
				echo '<br /><h2>Śledzenie przesyłki</h2> <a href="' . $link . '"> Kliknij aby śledzić przesyłkę</a><br /><br />';
				echo 'Jeżeli nie możesz uruchomić śledzenia przesyłki, uruchom przeglądarkę i wklej ten link:<br /> ' . $link . '<br />';
			}
			$link_fv = get_post_meta( $order_id, 'gees_invoice_copy', true);
			if (!empty($link_fv)) {
				echo '<br /><h2>Dokument sprzedaży - <b>eFaktura</b></h2> <a href="' . $link_fv . '"> Kliknij tutaj, aby pobrać dokument sprzedaży (eFakturę)</a><br /><br />';
				echo 'Jeżeli nie udało się pobrać efaktury, uruchom przeglądarkę i wklej ten link:<br /> <b>' . $link_fv . '</b><br /><br />';
			}	
*/
}

//dzialajaca funckjonalnosc
add_action( 'woocommerce_email_before_order_table', 'gees_email_custom_tracking', 0,2 );
 
function gees_email_custom_tracking( $order, $is_admin) {
	$order_id = $order->id;
	//			echo '<br /><h2>TEST gees_email_custom_tracking</h2>';
	

	$link = get_post_meta( $order_id, 'gees_delivery_tracking', true);
			if (!empty($link)) {
				echo '<br /><h2>Śledzenie przesyłki</h2> <a href="' . $link . '"> Kliknij aby śledzić przesyłkę</a><br /><br />';
				echo 'Jeżeli nie możesz uruchomić śledzenia przesyłki, uruchom przeglądarkę i wklej ten link:<br /> ' . $link . '<br />';
			}
			$link_fv = get_post_meta( $order_id, 'gees_invoice_copy', true);
			if (!empty($link_fv)) {
				echo '<br /><h2>Dokument sprzedaży - <b>eFaktura</b></h2> <a href="' . $link_fv . '"> Kliknij tutaj, aby pobrać dokument sprzedaży (eFakturę)</a><br /><br />';
				echo 'Jeżeli nie udało się pobrać eFaktury, uruchom przeglądarkę i wklej ten link:<br /> <b>' . $link_fv . '</b><br /><br />';
			}	
//	$email = new WC_Email_Customer_Invoice();
  //  $email->trigger($order_id);
}



	// display the extra data on order recieved page and my-account order review
	function gees_display_order_data( $order_id ){  
		$form = get_post_meta( $order_id, 'gees_invoice_add', true );
		//if (!empty($form))
		{
			echo '<br><div class="col-1">';
			echo '<h3>'.__('Dane do faktury VAT','geeshop_plugin').'</h3>';
			echo '<p><strong>'.__('Firma','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_fullname', true ) ;
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_name', true );
			echo '<br><strong>'.__('NIP','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_vat', true ) . '';
			echo '<br><strong>'.__('Adres','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_street', true ) . '';
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_street_nr', true ) ;
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_street_nr2', true ) ;
			echo '<br>' . get_post_meta( $order_id, 'gees_invoice_firm_postcode', true );
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_city', true ) ;
			echo '<br> Tel: ' . get_post_meta( $order_id, 'gees_invoice_firm_phone', true );
			$id = get_post_meta( $order_id, 'gees_invoice_firm_id', true );
			echo !empty($id) ? '<br><strong>'.__('ID Klienta','geeshop_plugin').':</strong> ' . $id:'';
			$id = get_post_meta( $order_id, 'gees_invoice_firm_id_ext', true );
			echo !empty($id)? '<br><strong>'.__('ID Klienta w systemie ERP','geeshop_plugin').':</strong> ' . $id : '';
			//echo '<br> Dokument sprzedaży: ' . get_post_meta( $order_id, 'gees_invoice_firm_phone', true );
			$link = get_post_meta( $order_id, 'gees_delivery_tracking', true);
			if (!empty($link)) {
				echo '<br /><h2>'.__('Śledzenie przesyłki','geeshop_plugin').'</h2> <a href="' . $link . '"> '.__('Kliknij aby śledzić przesyłkę','geeshop_plugin').'</a><br /><br />';
				echo __('Jeżeli nie możesz uruchomić śledzenia przesyłki, uruchom przeglądarkę i wklej ten link','geeshop_plugin').':<br /> ' . $link . '<br />';
			}
			echo '</p>';
			echo '</div>';
		}
}
add_action( 'woocommerce_thankyou', 'gees_display_order_data', 20 );
add_action( 'woocommerce_view_order', 'gees_display_order_data', 20 );


// display the extra data in the order admin panel
function gees_display_order_data_in_admin( $order_id ){
    print ' <div class="order_data_column">';
	$form = get_post_meta( $order_id, 'gees_invoice_add', true );
		//if (!empty($form))
		{
			echo '<h4>'.__('Dane do faktury VAT','geeshop_plugin').':</h4>';
			echo '<p><strong>'.__('Firma','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_fullname', true ) ;
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_name', true );
			echo '<br><strong>'.__('NIP','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_vat', true ) . '';
			echo '<br><strong>'.__('Adres','geeshop_plugin').':</strong> ' . get_post_meta( $order_id, 'gees_invoice_firm_street', true ) . '';
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_street_nr', true ) ;
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_street_nr2', true ) ;
			echo '<br>' . get_post_meta( $order_id, 'gees_invoice_firm_postcode', true );
			echo ' ' . get_post_meta( $order_id, 'gees_invoice_firm_city', true ) ;
			echo '<br> Tel: ' . get_post_meta( $order_id, 'gees_invoice_firm_phone', true );
			$id = get_post_meta( $order_id, 'gees_invoice_firm_id', true );
			echo !empty($id) ? '<br> '.__('ID Klienta','geeshop_plugin').': ' . $id:'';
			$id = get_post_meta( $order_id, 'gees_invoice_firm_id_ext', true );
			echo !empty($id)? '<br> '.__('ID Klienta w systemie ERP','geeshop_plugin').': ' . $id : '';
			$link = get_post_meta( $order_id, 'gees_delivery_tracking', true);
			if (!empty($link)) {
				echo '<br /><h2>'.__('Śledzenie przesyłki','geeshop_plugin').'</h2> <a href="' . $link . '"> '.__('Kliknij aby śledzić przesyłkę','geeshop_plugin').'</a><br /><br />';
				echo __('Jeżeli nie możesz uruchomić śledzenia przesyłki, uruchom przeglądarkę i wklej ten link','geeshop_plugin').':<br /> ' . $link . '<br />';
			}

			
			echo '</p>';
		}
		
    print '</div>';
}
//add_action( 'woocommerce_admin_order_data_after_order_details', 'gees_display_order_data_in_admin' );	
		