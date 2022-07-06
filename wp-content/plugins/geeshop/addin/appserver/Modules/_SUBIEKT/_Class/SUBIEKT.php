<?php
/*
 * Created on 2008-01-22
 * File: cWzorDB
 * Author: GeeSoft - Grzegorz Stawski
 * email: subarka@poczta.fm
 * All rights reserved
 */
 require_once('cAppClass.php');
 class SUBIEKT extends AppClass 
 {
	private $auth_mess = false;
	private $mess = "";
	private $wynik = "";
 	public function __construct() {
 		parent::__construct();
 	} 	 	 

	public function Autoryzacja(){
		$res = 1;
		try
		{
			$token_in = (isset($this->Parametry["token"])) ? $this->Parametry["token"] : "";
			$data = get_option( 'geeshop_subiekt_general' );
			$token =  isset($data['token']) ? $data['token'] : "";
			$wynik = "";
			$plugins = get_plugins();
			//print_r($plugins);
			//print_r($this->Parametry);
			
			if (!isset($plugins["geeshop/geeshop.php"])){
				$this->auth_mess = true;
				$this->Komunikat = " Plugin GeeShop nie jest aktywny!";
				$wynik = array('error'=>$this->Komunikat);		 
				$res = 0;
				$this->NrBledu = 69931;
			}
			
			if ($token_in != $token)
			{
				$res = 0;
				$this->auth_mess = true;
				$this->Komunikat = " Błędny token autoryzacji!";
				$this->wynik = array('error'=>$this->Komunikat);		 
				$this->NrBledu = 69930;
			}
			
		}
		catch (Exception $ex)
		{
			//print_r($ex);
			
		}
		return $res;			
	}
	
	public function KuierNewOrder(){
		$wynik = "";
	 	$file = strtolower(GEESHOP_PLUGIN_DIR."addin/10ka/10ka.admin.class.php2");
		if (file_exists($file)){
			require_once($file);
			$params = $this->Parametry["kurier"];
			$id = (isset($params['gees-order-id']) ? $params['gees-order-id'] : 0);
			$obj = new GeeShop_10kaAdmin();			
			$obj->NewOrder( $id, $params );
		}else {
			$komunikat = "Brak modułu obsługi Kuriera 10ka.pl";
			$this->Komunikat = $komunikat;
			$this->NrBledu = 69900;
		}
		return $out = array('wynik'=>$wynik);
	}
	
	public function FV_Zapisz()
	{  	
		global  $wpdb;
		//$this->Autoryzacja();
		//if ($this->auth_mess)
			//return $this->wynik;
		$list = $_FILES;
		if (isset($_FILES['image']) and isset($this->Parametry['order_id'])){
			$file_tmp =$_FILES['image']['tmp_name'];
			$uid = uniqid(); 
			$list['file_tmp']=  $file_tmp;
			$file = $uid.'_fv.pdf';
			$uid.='_fv.pdf';
			$file = $uid;
			$dir = GEESHOP_PLUGIN_DIR . "addin/gees/download/docs/in/".$file;
			$url = GEESHOP_PLUGIN_URI . "addin/gees/download/docs/in/".$file;
			$current = $file_tmp;
			
			$list['dir']=  $dir;
			if (move_uploaded_file($current, $dir)){
				$id = $this->Parametry['order_id'];
				update_post_meta( $id, 'gees_invoice_copy', sanitize_text_field( $url ) );
				$list['url']= $url ;
				$list['id']= $id;
			/*	$url 	= dirname( __FILE__ );
				$strpos = strpos( $url, 'wp-content' );
				$base 	= substr( $url, 0, $strpos );
				require_once( $base .'wp-load.php' ); 				
				require_once( $base .'wp-content/plugins/woocommerce/includes/emails/class-wc-email.php' ); 
				require_once( $base .'wp-content/plugins/woocommerce/includes/emails/class-wc-email-customer-invoice.php' ); 
				  
					$email = new WC_Email_Customer_Invoice();
					$email->trigger($id);*/
			}
		}

		return $list;
	}

	public function PF_Zapisz()
	{  	
		global  $wpdb;
		//$this->Autoryzacja();
		//if ($this->auth_mess)
			//return $this->wynik;
		$list = $_FILES;
		if (isset($_FILES['image']) and isset($this->Parametry['order_id'])){
			$file_tmp =$_FILES['image']['tmp_name'];
			$uid = uniqid(); 
			$list['file_tmp']=  $file_tmp;
			$file = $uid.'_pf.pdf';
			$uid.='_pf.pdf';
			$file = $uid;
			$dir = GEESHOP_PLUGIN_DIR . "addin/gees/download/docs/inp/".$file;
			$url = GEESHOP_PLUGIN_URI . "addin/gees/download/docs/inp/".$file;
			$current = $file_tmp;
			
			$list['dir']=  $dir;
			if (move_uploaded_file($current, $dir)){
				$id = $this->Parametry['order_id'];
				update_post_meta( $id, 'gees_invoice_pf_copy', sanitize_text_field( $url ) );
				$list['url']= $url ;
				$list['id']= $id;
			}
		}

		return $list;
	}

	
	public function Imgs_Zapisz()
	{  	
		$this->Autoryzacja();
		if ($this->auth_mess)
			return $this->wynik;

		require_once(GEESHOP_PLUGIN_DIR . ( '/addin/gees/import/gees.product.class.php') );
		$import = new GeesProductClass();
		$product = $this->Parametry;
		$result = $import->AddImages( $product );

		return $result;		
/*
	$list = $_FILES;

		if (isset($_FILES['image']) and isset($this->Parametry['order_id'])){
			$file_tmp =$_FILES['image']['tmp_name'];
			$uid = uniqid(); 
			$list['file_tmp']=  $file_tmp;
			$file = $uid.'_fv.pdf';
			$uid.='_fv.pdf';
			$file = $uid;
			$dir = GEESHOP_PLUGIN_DIR . "addin/gees/download/imgs/in/".$file;
			$url = GEESHOP_PLUGIN_URI . "addin/gees/download/imgs/in/".$file;
			$current = $file_tmp;
			
			$list['dir']=  $dir;
			if (move_uploaded_file($current, $dir)){
				$id = $this->Parametry['order_id'];
				update_post_meta( $id, 'gees_invoice_copy', sanitize_text_field( $url ) );
				$list['url']= $url ;
				$list['id']= $id;

			}
		}

		return $list;*/
	}
	
	
	public function Kurier_Zapisz()
	{  	
		$list = $_FILES;
		if (isset($_FILES['image']) and isset($this->Parametry['order_id'])){
			$file_tmp = $_FILES['image']['tmp_name'];
			$uid = uniqid(); 
			$list['file_tmp']=  $file_tmp;
			$file = $uid.'_del.pdf';
			$uid.='_del.pdf';
			$file = $uid;
			$dir = GEESHOP_PLUGIN_DIR . "addin/gees/download/docs/del/".$file;
			$list['dir']=  $dir;
			$url = GEESHOP_PLUGIN_URI . "addin/gees/download/docs/del/".$file;
			$current = $file_tmp;
		}
		$tra = isset($this->Parametry['tra']) ? $this->Parametry['tra'] :"";
		$nr = isset($this->Parametry['nr']) ? $this->Parametry['nr'] :"";
		$pro = isset($this->Parametry['pro']) ? $this->Parametry['pro'] :"";
		$opr = isset($this->Parametry['opr']) ? $this->Parametry['opr'] :"";
		$id = $this->Parametry['order_id'];
		
		if (move_uploaded_file($current, $dir)){
			update_post_meta( $id, 'gees_delivery_doc', sanitize_text_field( $url ) );

			$list['url']= $url ;
			$list['id']= $id;

		}
		if (!empty($nr))
			update_post_meta( $id, 'gees_delivery_nr', sanitize_text_field( $nr ) );
		if (!empty($tra))
			update_post_meta( $id, 'gees_delivery_tracking', sanitize_text_field( $tra ) );
		if (!empty($pro))
			update_post_meta( $id, 'gees_delivery_protocol', sanitize_text_field( $pro ) );
		if (!empty($opr))
			update_post_meta( $id, 'gees_delivery_operator', sanitize_text_field($opr) );

		return $list;
	}
	
	public function ZamowieniaLista()
	{  	
		global  $wpdb;
		//error_reporting(0);
		$orders_out = array();
		$this->Autoryzacja(); if ($this->auth_mess) return $this->wynik;
	
		//register_awaiting_shipment_order_status();
		$order_id = (isset($this->Parametry["ident"])) ? $this->Parametry["ident"] : "0";
		$page_limit = (isset($this->Parametry["page_limit"])) ? intval($this->Parametry["page_limit"]) : 0;
		$page = (isset($this->Parametry["page"])) ? $this->Parametry["page"] : "0";
		 $limit = (!empty($page_limit) ? "  ORDER BY p.ID ASC LIMIT ".$page.",".$page_limit."": "");

		//$order_id  = 6994;
		//$page_limit = 1;
		if (empty($order_id))
			$order_id = 0;
		$prds_list = array();
		if (empty($page_limit))
			$prds_list = $this->ProduktyLista();
		//print_r($prds_list);
		$query = "";
		$query.= " SELECT p.ID FROM {$wpdb->posts} p ";
		$query.= " WHERE p.post_type='shop_order'  and post_status not in ('trash') ";
		$query.= " and (p.id > cast( '$order_id' as SIGNED INTEGER) or (p.id > cast( '$order_id' as SIGNED INTEGER) and post_status in ('wc-processing', 'wc-pending','wc-on-hold'))) ";
		//print $query.= $limit;
		//$query.= " order by p.ID desc ";
		
	//	print_r($prds_list);
		$orders = $wpdb->get_results(  $query  );
		//print_r($orders);
		foreach( $orders as $item ) {  
			$order_id = $item->ID;		
			$order_org = new WC_Order($order_id);
		//	tax_or_vat
			$metody_dostawy = $order_org->get_shipping_methods();
			$dostawa_id = "";
			if (is_array($metody_dostawy))
				if (count($metody_dostawy) > 0) {
					$id = key($metody_dostawy);
					if (isset($metody_dostawy[$id]["method_id"]))
						$dostawa_id = $metody_dostawy[$id]["method_id"];
				}
			
			if (strpos($dostawa_id,':')>0)
			{					
				$dostawa_id = substr($dostawa_id, 0, strpos($dostawa_id,':') );	
			}
			//print " Zamowienie: ";
			//print_r(json_decode(json_encode($order_org->get_taxes()), true));
			//print_r($metody_dostawy);
			$order["order_id"] = $order_org->id;
			$order["customer_id"] = $order_org->user_id;
			$order["sgt_id"] = '';
			$order["status_id"] =  $order_org->post_status;
			$order["status"] =  wc_get_order_status_name( $order_org->post_status);
			$order["data_dod"] = $order_org->order_date;
			$order["data_mod"] = $order_org->modified_date;
			$order["uwagi"] = $order_org->customer_message;
			$order["wartosc"] = $order_org->order_total;			
			$order["waluta"] = array("waluta"=>"PLN");	
			try{			
				if (method_exists($order_org, 'get_currency'))
					$order["waluta"] = $order_org->get_currency();
			}catch(Exception $ex){};
			$order["metoda_platnosci_id"] = $order_org->payment_method;
			$order["metoda_platnosci"] = $order_org->payment_method_title;
			$order["metoda_platnosci_lista"] = $order_org->payment_method;						
			$order["metoda_dostawy"] = $order_org->get_shipping_method();
			$order["metoda_dostawy_id"] = $dostawa_id;
			$order["metoda_dostawy_lista"] = $metody_dostawy;
			$order["koszt_dostawy"] = $order_org->order_shipping + $order_org->order_shipping_tax;
			$order["koszt_dostawy_vat"] = $order_org->order_shipping_tax;
			$order["vat_lista"] = json_decode(json_encode($order_org->get_taxes()), true);
			//print '<pre>';print_r($order_org);print '</pre>';
			if (method_exists($order_org, "is_paid") )
				$order["czy_zaplacone"] = $order_org->is_paid( );
			else 
				$order["czy_zaplacone"] = true;

			$ordder["www"] = $link = (isset( $product->ID ) ? get_page_link($product->ID) : ""); 
			$prods = $order_org->get_items();
			$produkty = array();
			//print_t($prods);
			$this->Parametry["ids"] = "";
			$prd_tmp = array();
			
			//print_r($order);
			if (is_array($prods)){
				
				$ids = array();
				foreach($prods as $k=>$pos){
					$prd_tmp[$k]["prd_id"] = (isset($pos["variation_id"]) and !empty($pos["variation_id"]))?$pos["variation_id"]:(isset($pos["product_id"])?$pos["product_id"]: '');							
					$prd_tmp[$k]["var_product_id"] = (isset($pos["variation_id"]) and !empty($pos["variation_id"]))?(isset($pos["product_id"])?$pos["product_id"]: ''):'';							
				
				}
				if (!empty ( $prd_tmp ) ){
					//print_r( $prd_tmp );
					foreach( $prd_tmp as $p ){
						$prd_id = $p['prd_id'];
						$var_product_id = $p['var_product_id'];
						$ids[$prd_id] = $prd_id;
						$ids[$var_product_id] = $var_product_id;
					}
				}
				
					//print_r( $ids );
				if (!empty ( $ids ) ){
					$ids_str = "0";
					foreach( $ids as $k=>$p ){
						if ( !empty ( $p ) )
							$ids_str = $ids_str.",".$p;
					}					
					//print $this->Parametry["ids"] =  $ids_str;
					$prds_list = $this->ProduktyLista();	
					//print_r( $prds_list );
				}
//				$this->Parametry["ids"] =  $ids;

				foreach($prods as $pos){
					
			//print_t($pos);
					$prd["product_id"] = get_post_meta( $pos["product_id"], '_gees_external_prd_id', true );								
					//$prd["prd_id"] = isset($pos["product_id"])?$pos["product_id"]: '';							
					$prd["prd_id"] = (isset($pos["variation_id"]) and !empty($pos["variation_id"]))?$pos["variation_id"]:(isset($pos["product_id"])?$pos["product_id"]: '');							
					//$prd["nazwa"] = isset($pos["name"])?$pos["name"]: '';
					$p = isset($prds_list[$prd["prd_id"]]) ? ($prds_list[$prd["prd_id"]]) : "";
					$prd["nazwa"] = isset($p->title)? ($p->title): (isset($pos["name"])?$pos["name"]: '');

					$prd["shop_product_id"] = $prd["prd_id"];
					$prd["ilosc"] = isset($pos["qty"])?$pos["qty"]: '';
					$prd["wartosc"] = isset($pos["line_total"])?$pos["line_total"]: '';
					$prd["wartosc_vat"] = isset($pos["line_tax"])?$pos["line_tax"]: '';
					$cena_vat = isset($pos["qty"])? ((isset($pos["line_tax"])?$pos["line_tax"]:0)/$pos["qty"]): 0;
					$cena_netto = isset($pos["qty"])? ((isset($pos["line_total"])?$pos["line_total"]:0)/$pos["qty"]): 0;
					$cena_brutto = $cena_netto + $cena_vat;
					$cena = $cena_brutto;
					$prd["cena"] = $cena;
					$prd["cena_vat"] = $cena_vat;
					$prd["cena_netto"] = $cena_netto;
					$prd["cena_brutto"] = $cena_brutto;
					$prd["content"] = isset($prds_list[$prd["prd_id"]])? ($prds_list[$prd["prd_id"]]): array();
					$prd["tax_id"] = isset($pos["tax_class"])?$pos["tax_class"]: '';
					$prd["line_tax_data"] = isset($pos["line_tax_data"])?$pos["line_tax_data"]: '';
					$prd["unit_id"] = '0';  
//					$prd["var_product_id"] = (isset($pos["variation_id"]) and !empty($pos["variation_id"]))?$pos["variation_id"]:"";	
					$prd["var_product_id"] = (isset($pos["variation_id"]) and !empty($pos["variation_id"]))?(isset($pos["product_id"])?$pos["product_id"]: ''):'';							
					$produkty[] = $prd;
				//	print_t($prd);
				
				}
			}
			//print_r($orders);
				//		print '<pre>';print_r($order_org);print '</pre>';

		//	$order->shipment = $order->get_items();
			$order["produkty"] = $produkty;
			$order["dostawa"] = $order_org->get_address( 'shiping' );
			if (empty($order["dostawa"]))
				$order["dostawa"] = $order_org->get_address( 'shipping' );
		
			//$order["dostawa"]["Dodatki_machine_id"] = get_post_meta( $order_id, '_parcel_machine_id', true ),
			//$order["dostawa"]["Dodatki_parcel"] = get_post_meta( $order_id, '_easypack_parcels', true ),
			if (empty($order["dostawa"]))
				$order["dostawa"] = $order_org->get_address( 'billing' );
			$order["platnosc"] = $order_org->get_address( 'billing' );
			//$order->faktura = $order->get_address( 'billing' );
			$faktura = array(
				'czy_faktura' =>	get_post_meta( $order_id, 'gees_invoice_add', true ),
				'fullname' =>	get_post_meta( $order_id, 'gees_invoice_firm_fullname', true ),
				'firm_name' =>	get_post_meta( $order_id, 'gees_invoice_firm_name', true ),
				'vat' =>	get_post_meta( $order_id, 'gees_invoice_firm_vat', true ),
				'street' =>	get_post_meta( $order_id, 'gees_invoice_firm_street', true ),
				'street_nr' =>	get_post_meta( $order_id, 'gees_invoice_firm_street_nr', true ),
				'street_nr2' =>	get_post_meta( $order_id, 'gees_invoice_firm_street_nr2', true ),
				'postcode' =>	get_post_meta( $order_id, 'gees_invoice_firm_postcode', true ),
				'city' =>	get_post_meta( $order_id, 'gees_invoice_firm_city', true ),
				'phone' =>	get_post_meta( $order_id, 'gees_invoice_firm_phone', true )

			);
			$order["faktura"] = $faktura;
			$order_org = null;
			$orders_out[]=$order;
			$order = null;
		}
//		print_t($orders_out);
		return $orders_out;
 	}	
	public function ProduktAktualizuj()
	{
		$this->Autoryzacja();
		if ($this->auth_mess) return $this->wynik;
		
		require_once(GEESHOP_PLUGIN_DIR . ( '/addin/gees/import/gees.product.class.php') );
		
		$import = new GeesProductClass();
		$product = $this->Parametry;//isset($this->Parametry['parametry']) ? $this->Parametry['parametry']:'';
	
		$new_post_id = $import->importProduct( $product );

		$content = "";
		if (! empty ( $new_post_id ) ){
			$this->Parametry["id"] =  $new_post_id;
			$content = $this->ProduktyLista();
		}
		return array( 'product_id' => $new_post_id , 'content' => $content );		
	}
	
	public function CennikiAktualizuj()
	{
		$this->Autoryzacja();
		if ($this->auth_mess)
			return $this->wynik;
		
		$staus = 0;
		//$lista = (isset( $this->Parametry["lista"]['prd'])) ? $this->Parametry["lista"]: array();
		$lista = (isset( $this->Parametry['lista']['prd'])) ? $this->Parametry['lista']['prd']: array();
		$res =array();
		
		foreach($lista as $key=>$item){
			$prd_id = $item['prd_id'];
			$price = $item['price'];
			$price_promo = isset($item['price_promo'])?$item['price_promo']:"";
			$typ = $item['typ_id'];
//			$date_from = getdate()	;							
	//		$date_from = date( 'Y-m-d', $date_from );
		//	$date_from = strtotime( $date_from );
		//	$date_from = date_i18n( 'Y-m-d H:i:s', getdate());/*current_time( 'timestamp' )*/ )
			if  (!empty($prd_id)){
				//if (isset($prd))
				{					
				
					if (!empty($price)){
						$price = str_replace(',', '.',$price);
						if (empty($typ)){
							update_post_meta( $prd_id, '_regular_price', $price );
							update_post_meta( $prd_id, '_price', $price );
							if (!empty($price_promo)){
								update_post_meta( $prd_id, '_price', $price_promo );
								update_post_meta( $prd_id, '_sale_price', $price_promo );						
								update_post_meta( $prd_id, '_sale_price_dates_from', strtotime( date('Y-m-d', time() - (1 * 24 * 60 * 60) ) ) ); 															
							}
							else
							{
								update_post_meta( $prd_id, '_sale_price', '' );								
								update_post_meta( $prd_id, '_sale_price_dates_from', '');																
							}
						}
						else{
							update_post_meta( $prd_id, '_gees_allegro_cena', $price );
						}
						$res = $typ;
					}
				}
			}
		}
		$staus =  $res;
		
		return array('staus'=>$staus);		
	}
	
	public function StanyMagAktualizuj()
	{
		$this->Autoryzacja();
		if ($this->auth_mess)
			return $this->wynik;
		
		$staus = 0;
		//$lista = (isset( $this->Parametry["lista"]['prd'])) ? $this->Parametry["lista"]: array();
		$lista = (isset( $this->Parametry["lista"])) ? $this->Parametry["lista"]: array();
		$res =array();
		if (isset($lista["prd"])) {
			$lista = $lista["prd"] ;
		}

		foreach( $lista as $key=> $item ){
			$prd_id = $item['prd_id'];
			$stan = $item['qty'];
			$prd = null;
			if  (!empty($prd_id)){
				try{
					
					try{


						if (!isset($prd)){
							$prd = new WC_Product($prd_id);
						}
					}catch(Exception $ex){
						//print_r($ex);
					}

					try{
						if (!isset($prd)){
							$prd = new wc_product_variation($prd_id);						
						}
					}catch(Exception $ex){
						//print_r($ex);
					}
					
				//print_r($prd);
				if (isset($prd))
				{
					
					//print_r($prd);
					//if (!empty($stan))
					{
						//print get_post_meta( $prd_id, '_manage_stock', true );
						
						//print $prd->manage_stock ;
						update_post_meta( $prd_id, '_manage_stock', 'yes' );

						$prd->manage_stock = 'yes';
						$prd->set_stock($stan);
						$prd->set_manage_stock( true );
						$prd->set_stock_quantity( $stan );
						$prd->save();
						//update_post_meta( $prd_id, '_stock', $stan );
						
						$res["$prd_id"] = array("$stan",$prd->get_stock_quantity());
			//			$timeoffset = date( 'c', time() );
		//				$d= date("Y-m-d\TH:i:s\Z",time()-$timeoffset);
	///					$prd->set_date_modified($d);
					}
//					print_r($prd);
					// obsluga stanu ZERO
					//set_stock_status( $status )
					//$status = ( 'outofstock' === $status ) ? 'outofstock' : 'instock';
				}
				}catch(Exception $ex){
					//print_r($ex);
				}
			}
		}
//		print_r($res);
		$staus =  $res;
		
		return array('staus'=>$staus);		
	}
	private function XmlEleClear($input){
		if (is_array($input)) $input = "";
		$input = str_replace('AmpersandGSS', '&amp;',$input);
		return $input;
	}
	
	public function KontrahenciAktualizuj()
	{
		$this->Autoryzacja();
		if ($this->auth_mess)
			return $this->wynik;
		
		$staus = 0;
		//$lista = (isset( $this->Parametry["lista"]['prd'])) ? $this->Parametry["lista"]: array();
		$lista = (isset( $this->Parametry["lista"]["myTable"])) ? $this->Parametry["lista"]["myTable"]: array();
	//	print_t($lista);
		//$lista =base64_decode($lista);
		$res =array();
		global $wpdb;
		$table_name = $wpdb->prefix . 'gs_b2b_kth';
		foreach($lista as $item){
			
				
				try {
					
					$sql_exist = " select kth_id_zew from $table_name where kth_id_zew = '".$item['kth_id_zew']."'";
					$exists = $wpdb->get_var( $sql_exist );;
					if (empty($exists)){
						$sql = "INSERT INTO $table_name
								(kth_id_zew,nazwa1,nazwa2,nazwa_skrot,nip,regon,miasto,
									ulica,ulica_nr,ulica_lok,telefon1,telefon2,email,
									rabat_typ,rabat,data_dod,kod_pocztowy)
								VALUES
								( '".$this->XmlEleClear($item['kth_id_zew'])."', '".$this->XmlEleClear($item['nazwa1'])."', '".$this->XmlEleClear($item['nazwa2'])."', '".$this->XmlEleClear($item['nazwa_skrot'])."', '".$this->XmlEleClear($item['nip'])."', '".$this->XmlEleClear($item['regon'])."', '".$this->XmlEleClear($item['miasto'])."',
								'".$this->XmlEleClear($item['ulica'])."','".$this->XmlEleClear($item['ulica_nr'])."','".$this->XmlEleClear($item['ulica_lok'])."','".$this->XmlEleClear($item['telefon1'])."','".$this->XmlEleClear($item['telefon2'])."','".$this->XmlEleClear($item['email'])."',
								0,0,now(), '".$this->XmlEleClear($item['kod_pocztowy'])."'); ";
						$execut= $wpdb->query( $sql );	
					}else{
						$sql = "UPDATE  $table_name
								SET kth_id_zew = '".$this->XmlEleClear($item['kth_id_zew'])."', nazwa1 = '".$this->XmlEleClear($item['nazwa1'])."', nazwa2= '".$this->XmlEleClear($item['nazwa2'])."',
									nazwa_skrot='".$this->XmlEleClear($item['nazwa_skrot'])."', nip = '".$this->XmlEleClear($item['nip'])."', regon  ='".$this->XmlEleClear($item['regon'])."', miasto ='".$this->XmlEleClear($item['miasto'])."',
									ulica = '".$this->XmlEleClear($item['ulica'])."',ulica_nr= '".$this->XmlEleClear($item['ulica_nr'])."',ulica_lok = '".$this->XmlEleClear($item['ulica_lok'])."',
									telefon1 = '".$this->XmlEleClear($item['telefon1'])."', telefon2='".$this->XmlEleClear($item['telefon2'])."',email='".$this->XmlEleClear($item['email'])."',
									rabat_typ = '".$item['rabat_typ']."', rabat = '".$item['rabat']."', kod_pocztowy = '".$this->XmlEleClear($item['kod_pocztowy'])."'
								WHERE kth_id_zew = '".$item['kth_id_zew']."' ";
						$execut= $wpdb->query( $sql );	
					}
				//	print_t($item);
				//	return $wpdb->insert_id;
				}catch(Exception $ex){
					//obsluzyc
				}
				
			}		

		$staus = $sql;
		
		return array('staus'=>$staus);			
	}
	
	public function KontrahenciAdresyAktualizuj()
	{
		$this->Autoryzacja();
		if ($this->auth_mess)
			return $this->wynik;
		
		$staus = 0;
		//$lista = (isset( $this->Parametry["lista"]['prd'])) ? $this->Parametry["lista"]: array();
		$lista = (isset( $this->Parametry["lista"]["myTable"])) ? $this->Parametry["lista"]["myTable"]: array();
	//	print_t($lista);
		//$lista =base64_decode($lista);
		$res =array();
		global $wpdb;
		$table_name = $wpdb->prefix . 'gs_b2b_kth_adrs';
		$table_name_k = $wpdb->prefix . 'gs_b2b_kth';
		foreach($lista as $item){
			
				
			try {
				// czy istnieje kontrahent, jezeli tak, dodajemy z jego ID
				$sql_exist = " select kth_id  from $table_name_k where kth_id_zew = '".$item['kth_id_zew']."'";
				$kth_exists = $wpdb->get_var( $sql_exist );;
				if (!empty($kth_exists)){
					// Czy istnieje adres ,ejzeli tak update else id
					$sql_exist = " select addrs_id 
					from $table_name a inner join $table_name_k k on k.kth_id = a.kth_id
					where addrs_id_zew = '".$item['kth_id_zew']."' and k.kth_id_zew = '".$item['kth_id_zew']."' ";
					$exists = $wpdb->get_var( $sql_exist );
					
					if (empty($exists)){
						$sql = "INSERT INTO $table_name
								(kth_id,addrs_id_zew, nazwa,imie,nazwisko,firma,miasto,
									ulica,ulica_nr,ulica_lok,data_dod,kod_pocztowy)
								VALUES
								( ".$this->XmlEleClear($kth_exists).", '".$this->XmlEleClear($item['addrs_id_zew'])."', '".$this->XmlEleClear($item['nazwa'])."', '".$this->XmlEleClear($item['imie'])."', '".$this->XmlEleClear($item['nazwisko'])."', '".$this->XmlEleClear($item['firma'])."', '".$this->XmlEleClear($item['miasto'])."',
								'".$this->XmlEleClear($item['ulica'])."','".$this->XmlEleClear($item['ulica_nr'])."','".$this->XmlEleClear($item['ulica_lok'])."',
								now(), '".$this->XmlEleClear($item['kod_pocztowy'])."'); ";
						$execut= $wpdb->query( $sql );	
					}else{
						$sql = "UPDATE  $table_name
								SET nazwa = '".$this->XmlEleClear($item['nazwa'])."', imie= '".$this->XmlEleClear($item['imie'])."',
									nazwisko ='".$this->XmlEleClear($item['nazwisko'])."', firma = '".$this->XmlEleClear($item['firma'])."', miasto  ='".$this->XmlEleClear($item['miasto'])."',
									ulica = '".$this->XmlEleClear($item['ulica'])."',ulica_nr= '".$this->XmlEleClear($item['ulica_nr'])."',ulica_lok = '".$this->XmlEleClear($item['ulica_lok'])."',
									data_ed = now(), kod_pocztowy = '".$this->XmlEleClear($item['kod_pocztowy'])."'
								WHERE addrs_id = ".$exists." ";
						$execut= $wpdb->query( $sql );	
					}
				}
				//	print_t($item);
				//	return $wpdb->insert_id;
			}catch(Exception $ex){
				//obsluzyc
			}
				
					
		}
		$staus = $kth_exists;
		
		return array('staus'=>$staus);			
	}
	
	private function getMetodyPlatnosci(){
		global $woocommerce;
		$metody = array();
		$met = $woocommerce->payment_gateways->payment_gateways();
		foreach($met as $item){
			$metody[$item->id]= $item->title;
		}
		return $metody;
	
	}
	public function ZamowieniaStatusZmien(){
		$this->Autoryzacja();
		if ($this->auth_mess)
			return $this->wynik;
		$order_id = (isset($this->Parametry["nr_zam"])) ? $this->Parametry["nr_zam"] : "";
		$status = (isset($this->Parametry["status"])) ? $this->Parametry["status"] : "";
		$osoba = (isset($this->Parametry["osoba"])) ? $this->Parametry["osoba"] : "";
		if (!(empty($order_id)) and !empty($status)){
			$order = new WC_Order($order_id);
			$order->update_status( $status, 'Subiekt: '.$osoba.'.');													
			$wynik = array('status'=>1);
		}
		else 
			$wynik = array('status'=>0);
		return $wynik ;
	}
	private function getMetodyDostawy(){
		global $woocommerce;
		$metody = array();
		$met = $woocommerce->shipping->load_shipping_methods();
		foreach($met as $item){
			$metody[$item->id]= $item->title;
		}
		//print_t($metody);
		//print_t($met);
		return $metody;
	
	}
	private function PodatkiLista(){
		global $wpdb;
		$list_out =array();
		$list = $wpdb->get_results(  "SELECT tax_rates.*
			FROM {$wpdb->prefix}woocommerce_tax_rates as tax_rates
			 ");
			if (is_array($list)){
				foreach($list as $pos){
					$value = "(".$pos->tax_rate_country.") ".$pos->tax_rate_name." - ".$pos->tax_rate;
					$list_out[$pos->tax_rate_class] = $value;
				}
			}
		return $list_out;
	
	}
	private function ZamPodatkiLista(){
		global $wpdb;
		$list_out =array();
		$list = $wpdb->get_results(  "SELECT tax_rates.*
			FROM {$wpdb->prefix}woocommerce_tax_rates as tax_rates
			 ");
			if (is_array($list)){
				foreach($list as $pos){
					$value = "(".$pos->tax_rate_country.") ".$pos->tax_rate_name." - ".$pos->tax_rate;
					$list_out[$pos->tax_rate_class] = $value;
				}
			}
		return $list_out;
	
	}


	private function JednostkiLista(){
		//global $wpdb;
		$list_out =array();
		//$data = get_option( 'geeshop_erp_units' );
		//$token =  isset($data['token']) ? $data['token'] : "";

	/*	$list = $wpdb->get_results(  "SELECT tax_rates.*
			FROM {$wpdb->prefix}woocommerce_tax_rates as tax_rates where 1=0 ");
			if (is_array($list)){
				foreach($list as $pos){
					$value = "(".$pos->tax_rate_country.") ".$pos->tax_rate_name." - ".$pos->tax_rate;
					$list_out[$pos->tax_rate_class] = $value;
				}
			}
			$list_out = array();
		*/	$list_out["0"] = "Sztuka" ;
			$list_out["1"] = "Komplet" ;
			$list_out["2"] = "Para" ;
		return $list_out;
	
	}

	private function KlasyWysylkoweLista()
	{
		
		global $woocommerce;
		$metody = array();
		$met = $woocommerce->shipping->get_shipping_classes();
		foreach($met as $item){
			$metody[$item->term_id]= $item->name;
		}
		return $metody;
	}
	
	public function SlownikiLista()
	{	
		//$this->Autoryzacja(); if ($this->auth_mess) return $this->wynik;	

//		require_once(GEESHOP_PLUGIN_DIR . ( 'addin/allegro/allegro.orders.class.php') );
	//	register_awaiting_shipment_order_status();
		$result["zamowienia_status"] = wc_get_order_statuses();
		$result["kategorie"] = $this->SlownikiGetKategorie();
		$result["kategoria_obslug"] = array('kategoria_obslug'=>'Rodzaj obsługi mapowania kategorii ERP - WWW.');
		$result["kategoria_domysl"] = array('kategoria_domysl'=>'Kategoria domyślna dla nowego towaru w sklepie WWW.');
		$result["klienci_grupa"] = array('grupa'=>'Grupa Kontrahentow zapisywanych po imporcie');
		$result["klienci"] = array('kth_jednorazowy'=>'Obsługa klienta jednorazowego?', 'kth_domyslny'=>'Wybierz klienta domyślnego');
		$result["zamowienia"] = array('kategoria'=>'Kategoria zamówień zapisywanych po imporcie');
		$result["metody_dostawy"] = $this->getMetodyDostawy();
		$result["metody_platnosci"] = $this->getMetodyPlatnosci();	
//		$result["termin_platnosci"] = array('termin_platnosci'=>'Termin płatności w dniach dla płatnośi odroczonych');
		$result["dostawa_towar"] = array('product'=>'Towar / Usługa pakowania lub dostawy');
		$result["dostawa_do_fs"] = array('dostawa_do_fs'=>'Dodaj produkt dostawy do dokumentu sprzedażowego');
		$result["dostawca"] = array('dostawca_config'=>'Przypisana konfiguracja domyślnego dostawcy', 'dostawca_id'=>'Identyfikator dostawcy');
		$result["email"] = array('email_dostawcy'=>'Email do BOK', 'email_magazyn'=>'Email do obsługi magazynu?', 'email_temat_zam'=>'Temat email z zamówieniami?', 'email_temat_dost'=>'Temat email z danymi dostawy?', 'email_temat_ds'=>'Temat email z dokumentem sprzedaży', 'email_temat_pf'=>'Temat email z pro formą');
		$result["email_klient"] = array('email_serwer'=>'Serwer email', 'email_user'=>'Email nadawcy', 'email_pass'=>'Hasło do konta email', 'email_port'=>'Port konta email', 'email_typ'=>'Typ obsługi email ( zewnętrzny / wewnętrzny)', 'email_usluga'=>'usługa pop');
		$result["magazyn_zewn"] = array('magazyn_zewn'=>'Czy włączyć obsługę magazynów zewnętrznych(dostawców)?');
		$result["magazyn"] = array('magazyn_sprzedazy'=>'Magazyn obsługujący sprzedaż internetową', 'magazyn_synchro'=>'Magazyn obsługujący stany magazynowe', 'magazyn_rezerw'=>'Czy uwzględnić rezerwację produktu?', 'magazyn_roznic'=>'Czy uwzględnić synchronizację różnicową stan. mag.?', 'magazyn_komplet'=>'Czy wykluczyć komplety z aktualizacji stn. mag.?', 'magazyn_uslugi'=>'Czy wykluczyć usługi z aktualizacji stn. mag.?');
		$result["skutek_mag_ds"] = array('skutek_mag_ds'=>'Skutek magazynowy dla dokumentu sprzedażowego');
		$result["wzorzec_wydr_fs"] = array('wzorzec_wydr_fs'=>'Wzorzec wydruku dokumentu dla Faktury sprzed.', 'wzorzec_wydr_pa'=>'Wzorzec wydruku dla PARAGON', 'wzorzec_wydr_pai'=>'Wzorzec wydruku dla PARAGON Imiennego', 'wzorzec_wydr_pf'=>'Wzorzec wydruku dla PRO FORMY');
		$result["cennik_internet"] = array('cennik_internet'=>'Cennik dedykowany sprzedaży internetowej','cennik_promocja'=>'Cennik promocyjny dla sprzedaży internetowej', 'cennik_inte_dynam'=>'Cennik wyliczany dynamicznie na podstawie cen zakupu ( dystrybutorów )');
		$result["cennik_allegro"] = array('cennik_allegro'=>'Cennik dedykowany sprzedaży dla Allegro','cennik_alle_dynam'=>'Cennik wyliczany dynamicznie na podstawie cen zakupu ( dystrybutorów )');
		$result["cena_internet"] = array('cena_internet'=>'Cena Netto/Brutto produktu w sklepie internetowym');
		$result["allegro"] = array('allegro_rel'=>'Czy pobrać powiązanie produktów z aukcjami?');
		$result["stawki_vat"] = $this->PodatkiLista();
		$result["jezyk"] = array('jezyk_algorytm'=>'Algorytm tłumaczenia');
		$result["synchronizacja"] = array('zamowienie_status'=>'Zapisuj zamowienia w ERP uwzględniając status','towary_sklep'=>'Parametr związany z produktami do sprzedaży internetowej','zamowienie_ostatnie'=>'Podaj nr zamówienia WWW, od którego ma być realizowany import','zamowienie_ost_erp'=>'Podaj nr zamówienia WWW, od którego będą zapisywane ZK w ERP','towary_nazwa'=>'Czy aktualizować nazwę produktu w Sklepie','towary_symbol'=>'Czy aktualizować symbol produktu w Sklepie','towary_ean'=>'Czy aktualizować EAN produktu w Sklepie','towary_opis'=>'Czy aktualizować opis ptoduktu w Sklepie',  'towary_krotki'=>'Czy aktualizować krótki opis ptoduktu w Sklepie',  'towary_widoczny'=>'Czy nowy towar ma być zapisany jako Szkic?',  'towary_kategoria'=>'Czy aktualizować kategorię towaru w Sklepie',  'klienci_nowi'=>'Każdy klient www jako nowy klient w systemie',  'allegro_opisy'=>'Aktualizacja nazw i opisów dla aukcji Allegro.pl',  'ceneo_opisy'=>'Aktualizacja nazw dla aukcji Ceneo.pl',  'fotografie'=>'Dodawanie zdjęć produktu (nowy produkt)',  'erp_opis'=>'"Pełna charakterystyka pozycji" jako opis długi  w Sklepie (GT)', 'allegro_exclude'=>'Obsługa Allegro ( wykluczanie produktów )', 'erp_nazwa_uwagi'=>'Uwagi jako nazwa produktu w Sklepie (GT)',  'erp_opis_krotki'=>'Opis prduktu (ERP) jako opis krótki w Sklepie','zamowienie_iter'=>'Max. ilość zamówień pobieranych ze sklepu WWW', 'page_limit'=>'Max. ilość pobranych pozycji ze sklepu WWW',  'sku_map'=>'Automatycznie łącz produkty WWW-ERP',  'data_zam'=>'Czy data zamówienia ERP = data zamówienia w WWW?', 'fotografie_import'=> 'Czy importować zdjęcia produktu z WWW?', 'zamykanie_list'=> 'Zamykanie zamówienia - wyślij info o przesyłce?', 'zamykanie_ds'=> 'Zamykanie zamówienia - wyślij dokument sprzedaży?', 'klienci_symbol'=> 'Nowy symbol klienta jako skróty');       

		//		$result["statusowanie"] = array('obsluga_status'=>'Czy obsługiwać automatyczne statusy ERP->WWW?','stat_przyjete'=>'1. Zamówienie przyjęte do realizacji','stat_realiacja'=>'2. Zamówienie realizowane','stat_pakowane'=>'3. Zamówienie pakowane','stat_zrealizowane'=>'4. Zamówienie zrealizowane (Paragon / FV)','stat_wyslane'=>'5. Zamówienie wysłane ( wygenerowany list przewozowy)');       
		$result["statusowanie"] = array('obsluga_status'=>'Czy obsługiwać automatyczne statusy ERP->WWW?','0'=>'1. Zaimportowane','10'=>'2. Zamówienie przyjęte do realizacji','30'=>'3. Zamówienie realizowane','40'=>'4. Zamówienie pakowane','50'=>'5. Zamówienie zrealizowane (Paragon / FV)','60'=>'6. Zamówienie wysłane ( wygenerowany list przewozowy)','100'=>'7. Anulowane');       
		$result["eksport"] = array('katalog'=>'Podaj katalog, w którym będą zapisane pliki','nr_sklepu'=>'Unikalny numer sklepu');       
		$result["waluty"] = array('waluta_www'=>'Podaj walutę sklepu WWW','waluta_erp'=>'Podaj walutę w systemie ERP','waluta_erp_auto'=>'Automatyczna waluta w systemie ERP','waluta_obsluga'=>'Przeliczanie','waluta_klient'=>'Podaj walutę domyślną dla klienta');       
		$result["producent_obslug"] = array('producent_obslug'=>'Rodzaj obsługi mapowania Producentów ERP - WWW.');       
		$result["producenci"] = array('producenci'=>'');
		$result["klasy_wysylkowe"] = $this->KlasyWysylkoweLista();
		$result["jednostki"] = array('szt'=>'Sztuka','kpl'=>'Komplet','para'=>'Para');//$this->JednostkiLista();
		return $result;		
	}
	
	private function SlownikiGetKategorie(){
		$list = array();
	  $taxonomy     = 'product_cat';
	  $orderby      = 'name';  
	  $show_count   = 0;      // 1 for yes, 0 for no
	  $pad_counts   = 0;      // 1 for yes, 0 for no
	  $hierarchical = 1;      // 1 for yes, 0 for no  
	  $title        = '';  
	  $empty        = 0;

	  $args = array(
			 'taxonomy'     => $taxonomy,
			 'orderby'      => $orderby,
			 'show_count'   => $show_count,
			 'pad_counts'   => $pad_counts,
			 'hierarchical' => $hierarchical,
			 'title_li'     => $title,
			 'hide_empty'   => $empty
	  );
	 $all_categories = get_categories( $args );
	  foreach ($all_categories as $cat) {
		//if($cat->category_parent == 0) 
		{
			$category_id = $cat->term_id;       
		 //   echo $cat->slug .' - '. $cat->name .'<br>'; 
			$list[$cat->slug] =  $cat->name;
			$args2 = array(
					'taxonomy'     => $taxonomy,
					'child_of'     => 0,
					'parent'       => $category_id,
					'orderby'      => $orderby,
					'show_count'   => $show_count,
					'pad_counts'   => $pad_counts,
					'hierarchical' => $hierarchical,
					'title_li'     => $title,
					'hide_empty'   => $empty
			);
			$sub_cats = get_categories( $args2 );
			if($sub_cats) {
				foreach($sub_cats as $sub_category) {
				//    echo  $sub_category->name ;
					$list[$sub_category->slug] =    $cat->name .' -> '.$sub_category->name;
				}   
			}
		}       
	}
		return $list;
	}

	public function SlownikiGetKategorieDrzewo(){
		$list = array();
	  $taxonomy     = 'product_cat';
	  $orderby      = 'name';  
	  $show_count   = 0;      // 1 for yes, 0 for no
	  $pad_counts   = 0;      // 1 for yes, 0 for no
	  $hierarchical = 1;      // 1 for yes, 0 for no  
	  $title        = '';  
	  $empty        = 0;

	  $args = array(
			 'taxonomy'     => $taxonomy,
			 'orderby'      => $orderby,
			 'show_count'   => $show_count,
			 'pad_counts'   => $pad_counts,
			 'hierarchical' => $hierarchical,
			 'title_li'     => $title,
			 'hide_empty'   => $empty
	  );
	 $all_categories = get_categories( $args );
	  foreach ($all_categories as $cat) {
		//if($cat->category_parent == 0) 
		{
			$category_id = $cat->term_id;       
		 //   echo $cat->slug .' - '. $cat->name .'<br>'; 
			$list[$cat->slug] =  $cat->name;
			$args2 = array(
					'taxonomy'     => $taxonomy,
					'child_of'     => 0,
					'parent'       => $category_id,
					'orderby'      => $orderby,
					'show_count'   => $show_count,
					'pad_counts'   => $pad_counts,
					'hierarchical' => $hierarchical,
					'title_li'     => $title,
					'hide_empty'   => $empty
			);
			$sub_cats = get_categories( $args2 );
			if($sub_cats) {
				foreach($sub_cats as $sub_category) {
				//    echo  $sub_category->name ;
					$list[$sub_category->slug] =    $cat->name .' -> '.$sub_category->name;
				}   
			}
		}       
	}
		return $list;
	}


	public function SlownikiGetKategorieDrzewo2(){
		$list = array();
	  $taxonomy     = 'product_cat';
	  $orderby      = 'name';  
	  $show_count   = 0;      // 1 for yes, 0 for no
	  $pad_counts   = 0;      // 1 for yes, 0 for no
	  $hierarchical = 1;      // 1 for yes, 0 for no  
	  $title        = '';  
	  $empty        = 0;

	  $args = array(
			 'taxonomy'     => $taxonomy,
			 'orderby'      => $orderby,
			 'show_count'   => $show_count,
			 'pad_counts'   => $pad_counts,
			 'hierarchical' => $hierarchical,
			 'title_li'     => $title,
			 'hide_empty'   => $empty
	  );
	 $all_categories = get_categories( $args );
	  
	  //print_t($all_categories);
	  foreach ($all_categories as $cat) {
		//if($cat->category_parent == 0) 
		{
			$category_id = $cat->term_id;       
			//$list[$cat->slug] =  $cat->name;
			$args2 = array(
					'taxonomy'     => $taxonomy,
					'child_of'     => 0,
					'parent'       => $category_id,
					'orderby'      => $orderby,
					'show_count'   => $show_count,
					'pad_counts'   => $pad_counts,
					'hierarchical' => $hierarchical,
					'title_li'     => $title,
					'hide_empty'   => $empty
			);
			$sub_cats = get_categories( $args2 );
			if($sub_cats) {
				foreach($sub_cats as $sub_category) {
//					$list[$sub_category->slug] =    $cat->name .' -> '.$sub_category->name;
					$list[$sub_category->cat_ID] = array("id"=>$sub_category->cat_ID, "parent_id"=>$sub_category->category_parent, "slug"=>$sub_category->slug, "name"=>$sub_category->name, "subname"=>$cat->name .' -> '.$sub_category->name );

				}   
			}else{

			}
				$list[$cat->cat_ID] = array("id"=>$cat->cat_ID, "parent_id"=>$cat->category_parent, "slug"=>$cat->slug, "name"=>$cat->name, "subname"=>$cat->name );
		}       
	}
		return $list;
	}
	
	
	public function ProduktyLista()
	{  	
		global $wpdb;
		$this->Autoryzacja(); if ($this->auth_mess ==true) return $this->wynik;
		

		$id = (isset($this->Parametry["id"])) ? $this->Parametry["id"] : "";
		$ids = (isset($this->Parametry["ids"])) ? $this->Parametry["ids"] : "";
		$page = (isset($this->Parametry["page"])) ? $this->Parametry["page"] : "0";
		$page_limit = (isset($this->Parametry["page_limit"])) ? $this->Parametry["page_limit"] : "";
		$limit = (!empty($page_limit) ? "  ORDER BY post.ID ASC LIMIT ".$page.",".$page_limit."": "");

		//return array();SET SQL_BIG_SELECTS=1
		//$test = $wpdb->get_results("SET SQL_BIG_SELECTS=1");
		$test = $wpdb->get_results("DELETE o FROM $wpdb->posts o  LEFT OUTER JOIN $wpdb->posts r ON o.post_parent = r.ID WHERE r.id IS null AND o.post_type = 'product_variation'");
		$products = $wpdb->get_results(  "
		SELECT post.ID ID, post.post_content as content,  post.post_title as title, post.post_excerpt as excerpt, post.post_status as status, post.post_name as name, 
			case when _price.meta_value ='' then _regular_price.meta_value end as _price, _regular_price.meta_value  as _regular_price, 
			_gees_allegro_cena.meta_value as _gees_allegro_cena,_sale_price.meta_value as _sale_price_value, _sku.meta_value as _sku
			, _thumbnail_id.meta_value as _thumbnail_id, _allegro_id.meta_value as _allegro_id, _allegro_check.meta_value  _allegro_check, 
			_stock_status.meta_value as  _stock_status,  _gees_allegro_templeta.meta_value as  _gees_allegro_templeta, 
			_gees_allegro_title.meta_value as  _gees_allegro_title,  _gees_allegro_title2.meta_value as  _gees_allegro_title2,
			_gees_allegro_desc.meta_value as  _gees_allegro_desc,  _gees_external_prd_id.meta_value as  erp_prd_id, _tax_class.meta_value as  tax_id,		   
			_gees_manaf.meta_value as  _gees_manaf , _allegro_check.meta_value as  _gees_allegro_exclude , _gees_ean.meta_value as  ean, '0' as unit_id
			,post.post_parent  as  var_product_id,'' variant_options, post.post_type as prd_type, '' title_org /*--_org.post_title as title_org */
			,_gees_kod_prod.meta_value as  kod_prod 
			,_gees_ceneo_name.meta_value as  _gees_ceneo_name
		 FROM {$wpdb->posts} AS post
/*		LEFT JOIN {$wpdb->posts} AS _org ON _org.ID = post.post_parent  and _org.post_type IN ( 'product', 'product_variation' ) */
		 left JOIN {$wpdb->postmeta} AS _price ON post.ID = _price.post_id   AND _price.meta_key = '_price'
		LEFT JOIN {$wpdb->postmeta} AS _regular_price ON post.ID = _regular_price.post_id and _regular_price.meta_key = '_regular_price'
		LEFT JOIN {$wpdb->postmeta} AS _sale_price ON post.ID = _sale_price.post_id AND _sale_price.meta_key = '_sale_price'
		LEFT JOIN {$wpdb->postmeta} as _sku ON _sku.post_id = post.ID AND _sku.meta_key = '_sku'	
		LEFT JOIN {$wpdb->postmeta} as _gees_ean ON _gees_ean.post_id = post.ID AND _gees_ean.meta_key = '_gees_ean'	
		LEFT JOIN {$wpdb->postmeta} as _gees_kod_prod ON _gees_kod_prod.post_id = post.ID AND _gees_kod_prod.meta_key = '_gees_kod_prod'			
		LEFT JOIN {$wpdb->postmeta} as _allegro_check ON _allegro_check.post_id = post.ID AND _allegro_check.meta_key = '_gees_allegro_exclude'		
		LEFT JOIN {$wpdb->postmeta} as _allegro_id ON _allegro_id.post_id = post.ID AND _allegro_id.meta_key = '_gees_allegro_id'		
		LEFT JOIN {$wpdb->postmeta} as _thumbnail_id ON post.ID = _thumbnail_id.post_id AND _thumbnail_id.meta_key = '_thumbnail_id' 
		LEFT JOIN {$wpdb->postmeta} as _stock_status ON _stock_status.post_id = post.ID AND _stock_status.meta_key = '_stock_status'		
		LEFT JOIN {$wpdb->postmeta} as _gees_allegro_templeta ON _gees_allegro_templeta.post_id = post.ID AND _gees_allegro_templeta.meta_key = '_gees_allegro_templeta'		
		LEFT JOIN {$wpdb->postmeta} as _gees_allegro_title ON _gees_allegro_title.post_id = post.ID AND _gees_allegro_title.meta_key = '_gees_allegro_title'		
		LEFT JOIN {$wpdb->postmeta} as _gees_allegro_cena ON _gees_allegro_cena.post_id = post.ID AND _gees_allegro_cena.meta_key = '_gees_allegro_cena'		
		LEFT JOIN {$wpdb->postmeta} as _gees_allegro_title2 ON _gees_allegro_title2.post_id = post.ID AND _gees_allegro_title2.meta_key = '_gees_allegro_title2'		
		LEFT JOIN {$wpdb->postmeta} as _gees_allegro_desc ON _gees_allegro_desc.post_id = post.ID AND _gees_allegro_desc.meta_key = '_gees_allegro_desc'		
		LEFT JOIN {$wpdb->postmeta} as _gees_ceneo_name ON _gees_ceneo_name.post_id = post.ID AND _gees_ceneo_name.meta_key = '_gees_ceneo_name'		
		LEFT JOIN {$wpdb->postmeta} as _gees_manaf ON _gees_manaf.post_id = post.ID AND _gees_manaf.meta_key = '_gees_manaf'		
		LEFT JOIN {$wpdb->postmeta} as _gees_external_prd_id ON _gees_external_prd_id.post_id = post.ID AND _gees_external_prd_id.meta_key = '_gees_external_prd_id'		
		LEFT JOIN {$wpdb->postmeta} as _tax_class ON _tax_class.post_id = post.ID AND _tax_class.meta_key = '_tax_class'		
					
		WHERE post.post_type IN ( 'product', 'product_variation' )
			/*AND post.post_status = 'publish' 
			AND _price.meta_key = '_sale_price'*/
			/*and (_allegro_check.meta_value is null or _allegro_check.meta_value = 0)
			and _stock_status.meta_value = 'instock' */
			".(!empty($id)?" and post.ID =$id ":"")." 
			".(!empty($ids)?" and post.ID IN ( $ids ) ":"")." 
	/*		limit 1000
		order by post.post_type */
		".$limit."
		" );
		//print_r($wpdb);
		$product_out = array();
		foreach ($products as $key=>$product){
			try{
				$st = 0;
				//$prd["nazwa"] = isset($pos["name"])?$pos["name"]: '';
				$product_cat_id = '';

				$terms = get_the_terms( $product->ID, 'product_cat' );
				if (is_array($terms)){
					foreach ($terms as $term) {
						$product_cat_id = $term->slug;
						break;
					}
				}
				$name = "";
				if ($product->prd_type =='product_variation'){
					
					$prd = new WC_Product_Variation($product->ID);
					//$name = $prd->parent->get_title();
					if (1==1 /*$prd->variation_is_active() and $prd->parent_is_visible()*/)
					{
						
						if (method_exists($prd, "get_formatted_name") ){
							
							try{
								$name = $prd->get_formatted_name();
							}catch(Exception $ex){}
						}				
						//if (method_exists($prd, "get_formatted_variation_attributes") )
						{
							
							try
							{ 
/*								if (function_exists($prd, "wc_get_formatted_variation"))
									$name.=  $prd->wc_get_formatted_variation(true);		
								else
									if (method_exists($prd, "get_formatted_variation_attributes"))
										$name.=  $prd->get_formatted_variation_attributes(true);
									else $name.= '  - Wariant';*/
							}catch(Exception $ex){}
							
						}
					}/*else
						$name = "";*/ 
					$org_id = $product->var_product_id;
					$p = ( isset($product_out[$org_id]) ? $product_out[$org_id] : null );
					if ( $p != null )
						$product->title_org = ( method_exists( $product_out[$org_id], "title") ? $product_out[$org_id]->title : $prd->get_parent_data["get_title"]/*$prd->parent->get_title()*/ );
					else{
						$product->title_org = "";
						if (empty ($product->title_org))
						{
							try
							{
								if (method_exists($prd, "get_title") )
								{
									$product->title_org = $prd->get_title(); 
								}
							}catch(Exception $ex){}
						}
					}
					$product->variant_options = $name;
					$product->title = $product->title_org.' : '.$name;
	//				print_t($prd);
					try{
						$st = $prd->get_stock_quantity();
					} catch(Exception $ex){}
		
					$product->s = $st;				
				}
				else
				{
					$prd = new WC_product($product->ID);
					$product->var_product_id = '';
					try{
						$st = $prd->get_stock_quantity();
					} catch(Exception $ex){}
		
					$product->s = $st;
				}
				
				$product_out[$product->ID] = $product;
				$product_out[$product->ID]->kategoria = $product_cat_id;
				
				$image_id = $prd->get_image_id();
				
				if (!empty($image_id)){
					$thumb = wp_get_attachment_url( $image_id);
					$product_out[$product->ID]->img =  $thumb;
				}else 
					$product_out[$product->ID]->img ="";
				$product_out[$product->ID]->WWW = get_page_link($product->ID);
				$prd = null;
				unset($product);
			}
			catch(Exception $ex){
				//print_r($ex);
			}
//			$product = null;
		}

		return $product_out;
 	}	

	public function AllegroActual(){
		$wynik = "";
		try{
			$file = strtolower(GEESHOP_PLUGIN_URI."/addin/allegro/allegro_automat.php");
			//if (file_exists($file)){
				$wynik = file_get_contents($file);			
				print "wynik ".$wynik ;
			//}
		}
		catch(Exception $ex)
		{
			
		}
		return $out = array( 'wynik' => $file );
	}
	public function AllegroActualInv(){
		$wynik = "";
		try{
			$file = strtolower(GEESHOP_PLUGIN_URI."/addin/allegro/allegro_automat_inv.php");
			$wynik = file_get_contents($file);			
			//print "wynik ".$wynik ;
		}
		catch(Exception $ex)
		{
			
		}
		return $out = array( 'wynik' => $file );
	}

	public function GetAllegroMapping(){
		$wynik = "";
		try{
			global $wpdb;
			$table_name = $wpdb->prefix . 'gs_allegro_list';
			
				$products = $wpdb->get_results(  "SELECT product_id, auction_id FROM $table_name order by auction_id desc" );
			
			$product_out = array();
			foreach ($products as $key=>$product){
				//$product_cat_id = $term->slug;
			}
		}
		catch(Exception $ex)
		{
			
		}
		return $out = array( 'wynik' => $products );
	}
	
 }
 	 
?>