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

if ( ! class_exists( 'GeeS_Settings_Page' ) ) :
	
	require_once ("geesoft.settings.class.php");

/**
 * WC_Admin_Settings_General
 */
class GeeS_GeeShop_Settings_General extends GeeS_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->admin_hooks();
		parent::__construct();
		$this->id    = 'general';
		$this->label = __( 'General', 'woocommerce' );
		$this->actions();
		//add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		//add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		//add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}
	private function admin_hooks(){
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'plugin_admin_menu' ),1);
		}
	}

	public function plugin_admin_menu() {
		if ( current_user_can( 'manage_woocommerce' ) ) {
			add_menu_page( 'GeeShop', 'GeeShop', 'manage_options', 'geeshop_options', 'geeshop_options_callback', GEESHOP_PLUGIN_URI.'asset/img/geeshop.png' , 3 );
		}			
	}	

	public function actions() {

		$this->geesop_get_update();
	/*
		if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );

        }
	*/	$current_action = isset( $_POST['action'] ) ? $_POST['action'] : '';
			
        if( ( 'get-shippment'===$current_action ) ){
			$list = array();
			$this->GeeShop_get_shippment( $list );			
		}		
		
			
        if( ( 'save-shippment'===$current_action ) ){
			$this->save_shippment( );			
		}		
        if( ( 'save-orders'===$current_action ) ){
			$this->save_orders( );			
		}		
        if( ( 'save-general'===$current_action ) ){
			$this->save_general( );			
		}		
		
        if( ( 'get-actual'===$current_action ) ){
			$this->get_actual( );			
		}		
        if( ( 'save-user'===$current_action ) ){
			$this->save_user( );			
		}		

        if( ( 'save-product'===$current_action ) ){
			$this->save_product( );			
		}		
        if( ( 'save-attrs'===$current_action ) ){
			$this->save_attrs( );			
		}		
		
        if( ( 'get-user-info'===$current_action ) ){
			$this->GeeShop_get_user( );			
		}	
		
    }
	private function get_actual(){
		//pobierz
		$data = get_option( 'geeshop_general' );
		$token =  isset($data['token']) ? $data['token'] : "";
		$g = get_option('geeshop_app_one');
		$v =  (isset($g->v) ? $g->v : 0);
		
		require_once("geesoft.one.class.php");
		$obj = new GeeS_One_Up;
		if ($obj->get_info($token, $v)){
			$this->message( 6000, 'Aktualizacja przebiegła pomyślnie');
		}else{
			$this->message( 6200, 'Błąd aktualizacji.');			
		}
	}
	
	private function geesop_get_update(){
		$current_action = isset( $_GET['action'] ) ? $_GET['action'] : '';
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';			
        if( ( 'version'===$tab ) ){
			$update = isset( $_GET['update'] ) ? $_GET['update'] : '';
			if( ( 'true'===$update ) ){
				$plugin = isset( $_GET['plugin'] ) ? $_GET['plugin'] : '';
				$ver = isset( $_GET['ver'] ) ? $_GET['ver'] : '';
				$ver_app = isset( $_GET['ver_app'] ) ? $_GET['ver_app'] : '';
				$this->get_update($plugin, $ver, $ver_app);
				$data = get_option( 'geeshop_app_versions' );
				$data = (array)$data;
				$data["$plugin"] = $ver_app;
				update_option('geeshop_app_versions', $data);
				
			}
		}		
	}
	
	public function get_update($addin, $ver_aktual, $ver_app){
		
		$addin_dir = GEESHOP_PLUGIN_DIR.'addin';
		//$addin2 = str_replace('\\','',str_replace($addin_dir, '',  $addin));
		$file = $addin_dir."/$addin/$addin.class.php";
		
		$class_new = 'GeeShop_'.$addin;
		require_once($file);	
		
		//if ( ! class_exists( $class_new ) )
		{
			$class_new = 'GeeShop_'.$addin;
			if( method_exists($class_new , 'get_update')){
				$obj = new $class_new;
				 $result = $obj->get_update($ver_aktual, $ver_app);
			}
		}	
	}
	
	public function get_version($addin){
		$result = '';

		$addin_dir = GEESHOP_PLUGIN_DIR.'addin';
		$addin2 = str_replace('\\','',str_replace($addin_dir, '',  $addin));
		$file = $addin_dir."/$addin/$addin.class.php";
		
		$class_new = 'GeeShop_'.$addin;
		require_once($file);	
	//	if ( ! class_exists( $class_new ) )
	{
			if( method_exists($class_new , 'get_version')){
				$obj = new $class_new;
				$result = $obj->get_version();
			}
		}		
		return $result;
	}

	public function save_product( ){
		$list = isset($_POST['geeshop']['GeeShop']['product']) ? $_POST['geeshop']['GeeShop']['product'] : array();
		if (is_array($list))
			update_option( 'geeshop_GeeShop_product', $list );

	}
	public function save_attrs( ){
		$list = isset($_POST['geeshop']['GeeShop']['attrs']) ? $_POST['geeshop']['GeeShop']['attrs'] : array();
		if (is_array($list))
			update_option( 'geeshop_GeeShop_attrs', $list );

	}

	public function save_general( ){
		$list = isset($_POST['geeshop']['general']) ? ($_POST['geeshop']['general']) : array();
		if (is_array($list))
			update_option( 'geeshop_general', $list );

	}

	public function save_orders( ){
		$list = isset($_POST['geeshop']['GeeShop']['orders']) ? $_POST['geeshop']['GeeShop']['orders'] : array();
		if (is_array($list))
			update_option( 'geeshop_GeeShop_orders', $list );

	}

	public function save_shippment( ){
		$list = isset($_POST['geeshop']['GeeShop']['shippment']) ? $_POST['geeshop']['GeeShop']['shippment'] : array();
		if (is_array($list))
			update_option( 'geeshop_GeeShop_shippment', $list );

	}

	public function save_user( ){
		$list = isset($_POST['geeshop']['GeeShop']['user']) ? $_POST['geeshop']['GeeShop']['user'] : array();
		if (is_array($list))
			update_option( 'geeshop_GeeShop_user', $list );

	}
	
	public function GeeShop_get_shippment( $params ){
		$res = array();
		$result = array();
		if ( is_array($params)  ){
		try {
				
			require_once ("GeeShopApi/geeshop_metods.php");
			$api = new GeeShop_Metods();
			$result =  $api->PobierzMetodyDostawy($params);
			
			$res['error_no'] = $api->error_no;
			$res['error_msg'] = $api->error_msg;
			$res['html'] = $api->result_html;	
		
			if ( empty ( $api->error_msg)){
				$this->PrzypiszMetody($result);
				$api->error_no = 60010;
				$api->error_msg = 'Metody dostawy pobrane!!';
			}
			$this->GeeShop_message( $api->error_no, $api->error_msg );
		
		} catch(Exception $ex){
			
		}
		}
		$res['result'] = $result;
	}
	
	public function GeeShop_get_user(  ){
		$res = array();
		$result = array();
		$params = array();
		if ( is_array($params)  ){
		try {
				
			require_once ("GeeShopApi/geeshop_metods.php");
			$api = new GeeShop_Metods();
			$result =  $api->SklepInfo($params);
			
			$res['error_no'] = $api->error_no;
			$res['error_msg'] = $api->error_msg;
			$res['html'] = $api->result_html;	
		
			if ( empty ( $api->error_msg)){
				$api->error_no = 60010;
				$api->error_msg = 'Test połączenia z GeeShop przebiegł pomyślnie!!';
			}
			$this->GeeShop_message( $api->error_no, $api->error_msg );
		
		} catch(Exception $ex){
			
		}
		}
		$res['result'] = $result;
	}
	
	public function PrzypiszMetody( $params ){
		$res = array();
		$result = array();
		if ( is_array($params->shipmentDataList->item)  ){
		try {
			$list = array();
			if ( isset($params->shipmentDataList->item)  )
				foreach($params->shipmentDataList->item as $item){
					$list["$item->shipmentId"] = array(
														'name'=> $item->shipmentName,
														'type'=> $item->shipmentType,
														'time'=> $item->shipmentTime,
														'fid'=> null, 
														'fid_name'=> null,
													);	
				}	
				
			require_once ("GeeShopApi/geeshop_metods.php");
			$api = new GeeShop_Metods();
			$result =  $api->PobierzPolaFormularza($params);
			
			$res['error_no'] = $api->error_no;
			$res['error_msg'] = $api->error_msg;
			$res['html'] = $api->result_html;	
		
			if ( empty ( $api->error_msg)){
				//$this->PrzypiszMetody($result);
				if ( isset($result->sellFormFields->item)  )
				foreach($result->sellFormFields->item as $pos){
					$metoda_id = intval($pos->sellFormFieldDesc);
					if (! empty( $metoda_id ) ){
						if (empty($list["$metoda_id"]["fid"])){
							$list["$metoda_id"]["fid"] = $pos->sellFormId;
							$list["$metoda_id"]["fid_name"] = $pos->sellFormTitle;
						}
					}
				}
				if (is_array($list))
					update_option( 'geeshop_GeeShop_shippment_def', $list );

			}
			
		} catch(Exception $ex){
			
		}
		}
		$res['result'] = $result;
	}
	
	
	public function form(){
		print '<div class="wrap woocommerce">
		<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br></div>
		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a href="admin.php?page=geeshop_options&tab=general" class="nav-tab '.$this->getActiveTab('general').'">Kontakt GeeShop</a>		
			<a href="admin.php?page=geeshop_options&tab=version" class="nav-tab '.$this->getActiveTab('version').'">Wersje modułów</a>
			<a href="admin.php?page=geeshop_options&tab=aktual" class="nav-tab '.$this->getActiveTab('aktual').'">Aktualizacje</a>
		</h2>

		'.$this->get_tab().'
			<p class="submit">
			</form>
		</div>';
	}
	
	public function general(){
	 return '<h3>Dane kontaktowe</h3><table class="form-table">
				<form method="post" id="mainform" action="" enctype="multipart/form-data">

					<tbody><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">Numer telefonu</label>
										</th>
						<td class="forminp"><div class="select2-container wc-enhanced-select enhanced" id="s2id_autogen1" title="Country" style="min-width:350px;">41 222 70 72</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">email</label>
							</th>
						<td class="forminp forminp-select"> pomoc@geesoft.pl</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">WWW</label>
							</th>
						<td class="forminp forminp-select"> www.geeShop.pl www.dodatki-subiekt.pl</td>
					</tr>
					
					</tbody></table>';
		
		return $form;
	}

	public function version(){
	 return '<h3>Aktualne wersje modułów GeeShop </h3><table class="form-table">
				<form method="post" id="mainform" action="" enctype="multipart/form-data">

					<tr valign="top">
					'.$this->addins_versions_form().'
					</tr>
					
					</tbody></table>';
		
		//return $form;
	}
	public function aktual(){
	 return '<h3>Aktualizacje modułów GeeShop </h3><table class="form-table">
				
					<tbody><tr valign="top">
					'.$this->addins_aktual_form_tok().'
					</tr><tr valign="top">
					 <form method="post" id="mainform" action="" enctype="multipart/form-data">
			
					'.$this->addins_aktual_form().'
					</tr>
						<input type="hidden" name="action" value="get-actual" >
				<input name="save" class="button-primary" type="submit" value="Kliknij, aby pobrać najnowsze aktualizacje">
					<input type="hidden" name="subtab" id="last_tab">
				
					</form>
					</tbody></table>';
		
		//return $form;
	}
	private function addins_aktual_form_tok(){
		$data = get_option( 'geeshop_general' );
		$token =  isset($data['token']) ? $data['token'] : "";
		
		return '<form method="post" id="mainform" action="" enctype="multipart/form-data">
				<table class="form-table">
				
					<tbody>
					<tr valign="top">
						<div class="forminp forminp-select">
							<b><label class="titledesc" for="woocommerce_default_country">Token</label></b>
						</div>	
						<br>
						
						<input type="text" value="'.$token.'" size="40" name="geeshop[general][token]" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-3" id="s2id_autogen3_search" placeholder=""><br>Proszę wprowadzić licencję wygenerowany przez firmę GeeSoft IT Solutions 
					<input type="hidden" name="action" value="save-general" >
					<p class="submit">
					<input name="save" class="button-primary" type="submit" value="Zapisz token">
					<input type="hidden" name="subtab" id="last_tab">
					<input type="hidden" id="_wpnonce" name="_wpnonce" value="c701c8def4"><input type="hidden" name="_wp_http_referer" value="">		</p>
					</td></tr>
					</tbody></table>
					</form>';
	}
	
	public static function expandDirectories($base_dir) {
		$dir_list = array();
		$dir_list = glob($base_dir . "*", GLOB_ONLYDIR ); 		
		return $dir_list;
	}
	
	private function addins_aktual_form(){
		$addin_dir = GEESHOP_PLUGIN_DIR.'addin'.DIRECTORY_SEPARATOR;
		$directories = self::expandDirectories($addin_dir);
		$form = '';
		$data = get_option( 'geeshop_app_versions' );
		foreach ( $directories as $directory ){
			//print_r($directory);
			$addin = str_replace('\\','',str_replace($addin_dir, '',  $directory));
			$file = $directory."/$addin.class.php";
			$file_admin  = $directory."/$addin.admin.class.php";
			if (file_exists($file) ){
				$ver =  isset($data[$addin]) ? $data[$addin] : 'brak';
				$ver_app = $this->get_version($addin);
				$link = ''; 
				if (empty($ver)){
					$link = '';
				}
				$info = 'Aktualna wersja: '.$ver.' <br>Wersja aplikacji: '.$ver_app;
				if (version_compare($ver, $ver_app ,'!='))
					$info.= '<a href="admin.php?page=geeshop_options&tab=version&ver_app='.$ver_app.'&ver='.$ver.'&plugin='.$addin.'&update=true">Zaktualizuj</a>';	
				else{
					
				}
				$form.='				<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">'.$addin.'</label>
							</th>
						<td class="forminp forminp-select"> '.$info.'</td>
					</tr>';
			}
		}
	
		return "";$form;

	} 
	

	private function addins_versions_form(){
		$addin_dir = GEESHOP_PLUGIN_DIR.'addin'.DIRECTORY_SEPARATOR;
		$directories = self::expandDirectories($addin_dir);
		$form = '';
		$data = get_option( 'geeshop_app_versions' );
		foreach ( $directories as $directory ){
			//print_r($directory);
			$addin = str_replace('\\','',str_replace($addin_dir, '',  $directory));
			$file = $directory."/$addin.class.php";
			$file_admin  = $directory."/$addin.admin.class.php";
			if (file_exists($file) ){
				$ver =  isset($data[$addin]) ? $data[$addin] : 'brak';
				$ver_app = $this->get_version($addin);
				$link = ''; 
				if (empty($ver)){
					$link = '';
				}
				$info = 'Aktualna wersja: '.$ver.' <br>Wersja aplikacji: '.$ver_app;
				if (version_compare($ver, $ver_app ,'!='))
					$info.= '<a href="admin.php?page=geeshop_options&tab=version&ver_app='.$ver_app.'&ver='.$ver.'&plugin='.$addin.'&update=true">Zaktualizuj</a>';	
				else{
					
				}
				$form.='				<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">'.$addin.'</label>
							</th>
						<td class="forminp forminp-select"> '.$info.'</td>
					</tr>';
			}
		}
		
		return $form;

	} 
	

	public static function _create_tables(){
		$addins = self::load_addins();
		if ( is_array($addins))
			foreach($addins as $addin){
				if ( ! class_exists( 'GeeShop_'.$addin ) ){
					if( method_exists('GeeShop_'.$addin, 'create_tables')){
						//print 'method_exists';
						$result = 'GeeShop_'.$addin::create_tables();
						//exit;
					}
				}
			}
		//	exit;
	
	}
	public function attrs(){
				 return '<h3>Ustawienia związane z produktami</h3><table class="form-table">
				<form method="post" id="mainform" action="" enctype="multipart/form-data">

					<tbody>
					<input type="hidden" name="action" value="save-attrs" >
					'.$this->get_attrs().'
					</tbody></table>';		
	}
	
	public function get_attrs(){
			require_once('GeeShopApi/GeeShop.fields.class.php');
			$obj = new GeeShop_GeeShopFields();
			$output = $obj->get_attr_print_fields();
		
		return $output;
	}
	
	public function user(){
		$data = get_option( 'geeshop_GeeShop_user' );
		$login =  isset($data['login']) ? $data['login'] : '';
		$pass =  isset($data['pass']) ? $data['pass'] : '';
		$apikey =  isset($data['apikey']) ? $data['apikey'] : '';
		$sandbox = (isset($data['sandbox']) and !empty($data['sandbox'])) ? 'checked="checked"' : '';

		 return '<h3>Dane dostępowe do GeeShop</h3><table class="form-table">
				<form method="post">
				<p class="submit">
						<input  name="btn-action" class="button-primary" type="submit" value="Testuj połączenie z GeeShop">
						<input type="hidden" name="action" value="get-user-info" >
						<input type="hidden" id="_wpnonce" name="_wpnonce" value="c701c8def4"><input type="hidden" name="_wp_http_referer" value="/_wp/wp-admin/admin.php?page=wc-settings&amp;tab=general">		
				</p>
				<p>Aby przetestować dane dostępowe do GeeShop, zapisz ustawienia, następnie kliknij <b>Testuj połączenie z GeeShop</b> </p>
				</form>
				<form method="post" id="mainform" action="" enctype="multipart/form-data">

					<tbody><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">Login</label>
										</th>
						<td class="forminp"><div class="select2-container wc-enhanced-select enhanced" id="s2id_autogen1" title="Country" style="min-width:350px;">
						<input type="text" value="'.$login.'" name="geeshop[GeeShop][user][login]" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-3" id="s2id_autogen3_search" placeholder="Podaj login do serwisu GeeShop"></td>
					</tr><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">Hasło</label>
						<td class="forminp forminp-select">
						<input type="text" value="'.$pass.'" name="geeshop[GeeShop][user][pass]" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-3" id="s2id_autogen3_search" placeholder="Podaj hasło do serwisu GeeShop"></td>
					</tr><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">Api key</label>
													</th>
						<td class="forminp">
						<input type="text" value="'.$apikey.'" name="geeshop[GeeShop][user][apikey]" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-3" id="s2id_autogen3_search" placeholder="Podaj ApiKey do serwisu GeeShop"></td>
						<input type="hidden" name="geeshop[GeeShop][user][apikey1]" id="1">
						</td>
					</tr><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="woocommerce_default_country">Sandbox</label>
													</th>
						<td class="forminp">
						<input name="geeshop[GeeShop][user][sandbox]" id="woocommerce_demo_store" type="checkbox" value="1" '.$sandbox.'> Włącz możliwość testowania WebApi						
					
						</td>
					</tr>
					
					<input type="hidden" name="action" value="save-user" >
					
					</tbody></table>';
		
		return $form;

	}
	
}

endif;

$obj = new GeeS_GeeShop_Settings_General();
$obj->form();
