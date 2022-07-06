<?php
/**
 * WooCommerce General Settings
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'GeeS_10ka_Settings_General' ) ) :
	
	require_once (GEESHOP_PLUGIN_DIR."/asset/libs/geesoft.settings.class.php");


class GeeS_Subiekt_Settings_General extends GeeS_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->id    = 'general';
		$this->label = __( 'General', 'woocommerce' );

		 $this->actions();

	}
	
	
	public function actions() {
		$current_action = isset( $_POST['action'] ) ? $_POST['action'] : '';

		
		
        if( ( 'save-general'===$current_action ) ){
			$this->save_general( );			
		}else
        if( ( 'save-form-tax'===$current_action ) ){
			$this->save_form_tax( );			
		}		
			

    }
	

	public function save_general( ){
		$list = isset($_POST['geeshop']['subiekt']['general']) ? $_POST['geeshop']['subiekt']['general'] : array();
		if (is_array($list))
			update_option( 'geeshop_subiekt_general', $list );

	}

	public function save_form_tax( ){
		$list = isset($_POST['geeshop']['erp']) ? $_POST['geeshop']['erp'] : array();
//		if (is_array($list))
			update_option( 'geeshop_subiekt_form_tax', $list );

	}
	
	public function form(){
		print '<div class="wrap woocommerce">
		<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br></div>
		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a href="admin.php?page=insert_settings&tab=general" class="nav-tab '.$this->getActiveTab('general').'">Główne</a>
			<a href="admin.php?page=insert_settings&tab=form_tax" class="nav-tab '.$this->getActiveTab('form_tax').'">Formularz VAT</a>
		</h2>

	'.$this->get_tab().'
			<p class="submit">
					<input name="save" class="button-primary" type="submit" value="Zapisz ustawienia">
					<input type="hidden" name="subtab" id="last_tab">
					<input type="hidden" id="_wpnonce" name="_wpnonce" value="c701c8def4"><input type="hidden" name="_wp_http_referer" value="/_wp/wp-admin/admin.php?page=wc-settings&amp;tab=general">		</p>
			</form>
		</div>';
		//	<a href="admin.php?page=10ka-settings&tab=pay" class="nav-tab ">Metody płatności</a>		
		//	<a href="admin.php?page=10ka-settings&tab=product" class="nav-tab ">Produkty</a>
	}
	
	public function general(){
		$data = get_option( 'geeshop_subiekt_general' );
		$token =  isset($data['token']) ? $data['token'] : "";
		
		return '<h3>Główne ustawienia</h3><table class="form-table">
				<form method="post" id="mainform" action="" enctype="multipart/form-data">

					<tbody>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">Token</label>
							</th>
						<td class="forminp forminp-select">
						<input type="text" value="'.$token.'" size="40" name="geeshop[subiekt][general][token]" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-3" id="s2id_autogen3_search" placeholder=""><br>
						Proszę wprowadzić token wygenerowany w aplikacji integracyjnej (Serwisy)</td>
					</tr>
					<input type="hidden" name="action" value="save-general" >
					
					</tbody></table>';
		
		return $form;
	}
	
	public function form_tax(){
		$data = get_option( 'geeshop_subiekt_form_tax' );
		$form_hide = (isset($data['form_hide']) and !empty($data['form_hide'])) ? 'checked="checked"' : '';
		$form_readonly = (isset($data['form_readonly']) and !empty($data['form_readonly'])) ? 'checked="checked"' : '';
		$form_show = (isset($data['form_show']) and !empty($data['form_show'])) ? 'checked="checked"' : '';
		return '<h3>Ustawienia formularza VAT</h3><table class="form-table">
				<form method="post" id="mainform" action="" enctype="multipart/form-data">

					<tbody>
					<tr valign="top">
						<th scope="row" class="titledesc" style="text-align:center;">
						<input name="geeshop[erp][form_hide]" id="woocommerce_demo_store" type="checkbox" value="1" '.$form_hide.'>
							</th>
						<td class="forminp forminp-select">
							 Zaznacz, jeżeli chcesz schować formularz VAT					
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc" style="text-align:center;">
						<input name="geeshop[erp][form_readonly]" id="woocommerce_demo_store" type="checkbox" value="1" '.$form_readonly.'>
							</th>
						<td class="forminp forminp-select">
							 Zaznacz, jeżeli chcesz zablokować edycję danych w formularzu VAT przez użytkownika					
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc" style="text-align:center;">
						<input name="geeshop[erp][form_show]" id="woocommerce_demo_store" type="checkbox" value="1" '.$form_show.'>
							</th>
						<td class="forminp forminp-select">
							 Zaznacz, jeżeli chcesz aby formularz był widoczny i wymagany 					
						</td>
					</tr>
					<input type="hidden" name="action" value="save-form-tax" >
					
					</tbody></table>';
		
		return $form;
	}
	
	
}

endif;

$obj = new GeeS_Subiekt_Settings_General();
$obj->form();
