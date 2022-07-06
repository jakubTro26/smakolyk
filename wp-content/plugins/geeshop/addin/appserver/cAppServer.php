<?php
/*
 * Created on 2016-02-07
 * File: cConfig.php
 * Author: GeeSoft - Grzegorz Stawski
 * email: biuro@geesoft.pl
 * All rights reserved
 */
	//error_reporting(0);
	require_once('cParamAppServer.php');
	require_once('App/cWzorClass.php');
 
	class AppServer 
 	{ 		
 		private $AppParametry, $AppModul, $AppMetoda, $AppSQLParametry,$AppPartnerID, $AppUzytkownikID = null;
 		private $AppSQLListWlacz = false;
 		private $ClassPath = '_Class/';
 		private $ClassPathFile = null;
 		private $TestWlacz = null;
 		private $SQLList = array();
 		protected $Parametry = array();
		private $TagObudowa = "tag_12345"; 
 		public $Komunikat, $NrBledu = null;

   	public function __construct()
	 	{
	 		$this->TestWlacz = isset($_GET['t']) ? $_GET['t'] : '';		 	
	 	}
	 	
	 	public function Execute()
	 	{	 
			$wynik = '';		
	 		try
	 		{
				if (empty($this->TestWlacz))
		 		{
			 		// odczyt i sprawdzenie parametrow		 			
					$this->ParamRead();
			 		// przypisanie parametrow do zmiennych klasy
					$this->ParamWrite();
		 		}else
		 		{
					//testowanie
					$this->ParamTest();
		 		}
		 		// ustawienie partnera i pliku configuracyjnego
				$this->SetPartnerService();
					
				// sprawdzeie czy istnieje klasa
				$this->CheckFileMethod();
  		  
				// ustawienie Modulu i Metody, Parametrow i wykoaniie 
				$wynik = $this->ExecModule();
	 		}
	 		catch(Exception $e)
	 		{	}
	 		
			$tablica['r'] = $wynik;
			$tablica['komunikat'] = $this->Komunikat;
			$tablica['nr_bledu'] = $this->NrBledu;
			if (empty($this->TestWlacz))
		 	{
				if (!empty($this->AppSQLListWlacz))
					$tablica['test_SQL'] = $this->SQLList;
				$wynik_xml = json_encode($tablica);				
				$res= $this->XMLObudowa($wynik_xml);
		 	}else
		 	{
				$wynik_xml = json_encode($tablica);
				$res= $this->XMLObudowa($wynik_xml);
		 	}
			header('content-type: application/json; charset=utf-8');
		 	print $wynik_xml;
	 	}
	 	
		private function ArrayToXML2( $data, &$xml_data ) {
		foreach( $data as $key => $value ) {
			if (is_object($value))
				$value = (array)$value;
			if( is_array($value) ) {
				if( is_numeric($key) ){
					$key = 'item'.$key; //dealing with <0/>..<n/> issues
				}
				$subnode = $xml_data->addChild($key);
				$this->ArrayToXML($value, $subnode);
			} else {

				$xml_data->addChild("$key",htmlspecialchars("$value"));
			}
		}
	}
		
	 	private function XMLObudowa($xml)
	 	{
			$result = $xml;//"<$this->TagObudowa>".$xml."</$this->TagObudowa>";
			return $result;
	 	} 
		private function ParamTest()
		{
			$this->AppModul = 'APPS';
	 		$this->AppMetoda = 'GetInfo';
//	 		$this->AppMetoda = 'pokazZawartoscKoszyka';
			 		$this->AppParametry = array('zapytanie'=>" and d.nazwa like'%FILTR%' and d.nazwa like'%KABINOWY%' and d.nazwa like'%BMW%' and d.nazwa like'%94%' "
			 		,
			 															  'ilosc_wynikow'=>1,
			 															  'logo'=>'299008',
			 															  'ktora_strona'=>1);
			$this->AppParametry = array(
	 															  'typ'=>'2',
 																  'typ_nr'=>'4629',
 																  'asort'=>'',
 																  'nr' => "MAN HU710X"
 																  );
 																  
			$this->AppParametry = array( 	
															'nr' => "MANHU710X"
															
 														);			 		 																  			 															  
	 		$this->AppPartnerID = 1;
	 		$this->AppUzytkownikID = 2;
	 		$this->AppUzytkownikIP = 'TEST';	 		
			//print "<br>after ParamTest".date("H:i:s");
			$this->AppModul = 'TecDoc';
	 		$this->AppMetoda = 'wyswietlDostawcow';
//	 		$this->AppMetoda = 'pokazZawartoscKoszyka';
			 		$this->AppParametry = array('typ_nr'=>7866,
			 															  'typ'=>2,
			 															  'id'=>100259);
			 															  
			$this->AppModul = 'TecDoc';
	 		$this->AppMetoda = 'wyswietlDostawcow';
	 		$this->AppMetoda = 'wyswietlProducentowOsobowych';
			 		$this->AppParametry = array('typ_nr'=>7866,
			 															  'typ'=>2,
			 															  'id'=>100259);
			$this->AppParametry  = array();
			$this->AppModul = 'Test';
	 		$this->AppMetoda = 'TestTest';
			 															  
	 		$this->AppPartnerID = 5;
	 		$this->AppUzytkownikID = 1;
			$this->AppModul = 'SUBIEKT';
			$this->AppMetoda = 'ProduktyLista';
	 		$this->AppMetoda = 'ZamowieniaLista';	
			$this->AppMetoda = 'ProduktyLista';			
 		
		} 	 	
		
	 	private function ExecModule()
	 	{
	 		// ustawienie Moduly i Metody
	 		try
	 		{
		 		require_once($this->ClassPathFile); 
		 		$object = new $this->AppModul();
				$object->AppMetoda = $this->AppMetoda;				
		 		// przypisanie parametrow do metody
	//	 		$object->SetParam =  $this->AppParametry;
				$object->AppUzytkownikID = $this->AppUzytkownikID;
				$object->AppUzytkownikIP = $this->AppUzytkownikIP;				
		 		$object->SetParam($this->AppParametry);	 		
		 		$object->SetSQLParam($this->AppSQLParametry);		 		
		 		// wykonanie na bazie danej modody
			  $wynik = $object->ExecuteMethod();
			  $this->SQLList = $object->GetSQLList();
//			  print $this->Komunikat;
//			 	print_r($wynik); 			 	
				$this->NrBledu =  $object->NrBledu;
				$this->Komunikat = $object->Komunikat;						  
		 		return $wynik;
	 		}
	 		catch(Exception $e)
	 		{
	 			if (empty($this->Komunikat))
		 		{
					$this->NrBledu =  $object->NrBledu;
					$this->Komunikat = $object->Komunikat;
		 		}		  
	 		}
	 	}
	 	
	 	private function CheckFileMethod()
	 	{	if (!file_exists($this->ClassPathFile))
			{
 				$this->Komunikat= 'Brak modulu: '.$this->AppModul;
 				$this->NrBledu= 50014;					
				throw new Exception;
			} 			 		
	 	}
	 	
	 	private function ParamRead()
	 	{
	 		
			$ParametryXML = (isset($_POST['parametry'])) ? trim($_POST['parametry']): '';
			$ParametryXML = trim(stripslashes(htmlspecialchars_decode($ParametryXML, ENT_NOQUOTES)));

			$this->Parametry = $this->XmlToArray($ParametryXML);
 			if (!is_array($this->Parametry))
 			{
 				$this->Komunikat= 'Bledna tablica parametrow'.htmlspecialchars($this->ArrayToXML($this->Parametry));
 				$this->NrBledu= 50004;
 				throw new Exception;
 			}	 		 	
 			if (count($this->Parametry)==0)
 			{
 				$this->Komunikat= 'Brak parametrów';
 				$this->NrBledu= 50005;
 				throw new Exception;	 				
 			}
			 			
	 	}	

	 	private function ParamWrite()
	 	{
			$this->AppModul = isset($this->Parametry['modul']) ? $this->Parametry['modul']:'';
	 		$this->AppMetoda = isset($this->Parametry['metoda']) ? $this->Parametry['metoda']:'';
	 		$this->AppPartnerID = isset($this->Parametry['partner_id']) ? $this->Parametry['partner_id']:'';
	 		$this->AppUzytkownikID = isset($this->Parametry['uzytkownik_id']) ? $this->Parametry['uzytkownik_id']:'';
	 		$this->AppUzytkownikIP = isset($this->Parametry['uzytkownik_ip']) ? $this->Parametry['uzytkownik_ip']:'';	 		
	 		$this->AppSQLListWlacz = isset($this->Parametry['test_sql']) ? $this->Parametry['test_sql']:'';
	 		$this->AppParametry = isset($this->Parametry['parametry']) ? $this->Parametry['parametry']: array();
			$parametry_sql = isset($this->Parametry['parametry_sql']) ? $this->Parametry['parametry_sql'] :'';
		 	$AppSQLParametry = explode(" ,;, ", htmlspecialchars_decode($parametry_sql), ENT_NOQUOTES);
			$parametry_sql_nazwy = isset($this->Parametry['parametry_sql_nazwy']) ? $this->Parametry['parametry_sql_nazwy'] :'';
		 	$AppSQLParametryNazwy = explode(" ,;, ", $parametry_sql_nazwy);
	 		$i = 0;
	 		if (is_array($AppSQLParametryNazwy) and count($AppSQLParametryNazwy)>0)
				foreach ($AppSQLParametryNazwy as $value)
				{
					$this->AppSQLParametry[$value] = $AppSQLParametry[$i]; 
	 				$i++;					
				}
					 			 			 	
 			if (empty($this->AppModul))
 			{
 				$this->Komunikat= 'Brak parametru MODUL';
 				$this->NrBledu= 50009;
 				throw new Exception;
 			}

 			if (empty($this->AppMetoda))
 			{
 				$this->Komunikat= 'Brak parametru METODA';
 				$this->NrBledu= 50010;
 				throw new Exception;
 			}

 			if (empty($this->AppPartnerID))
 			{
 				$this->Komunikat= 'Brak parametru PartnerID';
 				$this->NrBledu= 50011;
 				throw new Exception;
 			} 			
	 	}	
	 	
	 	private function ParamRead2()
	 	{
	 			$this->Parametry = $_POST; 	
	 			if (!is_array($this->Parametry))
	 			{
	 				$this->Komunikat= 'Bledna tablica parametrow';
	 				$this->NrBledu= 50004;
	 				throw new Exception;
	 			}
	 			if (count($this->Parametry)==0)
	 			{
	 				$this->Komunikat= 'Brak parametrow';
	 				$this->NrBledu= 50005;
	 				throw new Exception;	 				
	 			}	 			
	 	}	
	 		 	
	 	private function DBExec()
	 	{
	 		require_once('cDB_MSSQL.php');  
			$Serwis =  new DB();      

			$wynik = $Serwis->ExecToArray();
			$this->Komunikat = $Serwis->Komunikat;
			$this->NrBledu = $Serwis->NrBledu;					
			return $wynik;	 		
	 	}
	 	
	 	private function SetPartnerService()
	 	{
		switch ($this->AppPartnerID)
				 {
					case 4:
						require_once(getcwd().ParamAppServer::DIR_CLASS_SUBIEKT.'Config/config.php');	 		
						$this->ClassPathFile =  getcwd().ParamAppServer::DIR_CLASS_SUBIEKT.$this->ClassPath.$this->AppModul.".php";
						
						break;
					case 5:
						require_once(getcwd().ParamAppServer::DIR_CLASS_SUBIEKT.'Config/config.php');	 		
						$this->ClassPathFile =  getcwd().ParamAppServer::DIR_CLASS_SUBIEKT.$this->ClassPath.$this->AppModul.".php";
						
						break;						
					default:
			}
	 	} 
	 	
	 	private function XmlToArray($XML)
		{
			require_once('App/cArrayXml.php');
			$Obiekt = new ArrayXml();		
			return $Obiekt->XmlToArray($XML);		
		} 

		private function ArrayToXml($Parametry)
		{
			require_once('App/cArrayXml.php');
			$Obiekt = new ArrayXml();		
			return $Obiekt->ArrayToXml($Parametry);		
		}
 }
?>