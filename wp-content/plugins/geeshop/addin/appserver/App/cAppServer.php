<?php
/*
 * Created on 2008-02-08
 * File: cAppSerwer.php
 * Author: GeeSoft - Grzegorz Stawski
 * email: subarka@poczta.fm
 * All rights reserved
 */
	//error_reporting(0);
	require_once('cParamAppServer.php');
	class AppServer 
 	{ 		
 		private $AppParametry, $AppModul, $AppMetoda, $AppSQLParametry,$AppPartnerID, $AppUzytkownikID = null;
 		private $AppSQLListWlacz = false;
 		private $ClassPath = '_class/';
 		private $ClassPathFile = null;
 		private $TestWlacz = null;
 		private $SQLList = array();
 		protected $Parametry = array();
    private $TagObudowa = "tag_12345"; 
 		public $Komunikat, $NrBledu = null;

   	public function __construct()
	 	{
	 		$this->TestWlacz = $_GET['t'];		 	
	 	}
	 	
	 	public function Execute()
	 	{	 		
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
				$wynik_xml = $this->ArrayToXml($tablica); 
				$wynik_xml = iconv("ISO-8859-2","UTF-8",$wynik_xml);  
		//		print utf8_encode($wynik_xml);
				print $this->XMLObudowa($wynik_xml);
		 	}else
		 	{
		 		
//print "<br>".date("H:i:s");
	//	 		print_t($tablica);	
//print "<br>before ArrayToXml".date("H:i:s");
				$wynik_xml = $this->ArrayToXml($tablica); 
//				$wynik_xml = iconv("ISO-8859-2","UTF-8",$wynik_xml);  
		//		print utf8_encode($wynik_xml);
//print "<br>before XMLObudowa".date("H:i:s");		
				print $this->XMLObudowa($wynik_xml);
//print "<br>KONIEC - ".date("H:i:s");						 			 	
		 	}
		 	
	 	}
	 	
	 	private function XMLObudowa($xml)
	 	{
	 		return "<$this->TagObudowa>".$xml."</$this->TagObudowa>";
	 	} 
		private function ParamTest()
		{
			$this->AppModul = 'TecDoc';
	 		$this->AppMetoda = 'szukajNumeryTecDoc';
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
			 															  
	 		$this->AppPartnerID = 1;
	 		$this->AppUzytkownikID = 1;
			$this->AppParametry  = array();

			$this->AppModul = 'ZamowieniaZewn';
	 		$this->AppMetoda = 'ZamowieniaPozListaZwroc';
			 															  
	 		$this->AppPartnerID = 2;
	 		$this->AppUzytkownikID = 1;	 		
	 		print "ssss";					 					
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
				print $this->NrBledu =  $object->NrBledu;
				print $this->Komunikat = $object->Komunikat;						  
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
	 	{
	 		if (!file_exists($this->ClassPathFile))
			{
 				$this->Komunikat= 'Brak modulu: '.$this->AppModul;
 				$this->NrBledu= 50014;					
				throw new Exception;
			} 			 		
	 	}
	 	
	 	private function ParamRead()
	 	{
	 		
	 	//print_r($_POST);
			$ParametryXML = trim($_POST['parametry']);
 			//$ParametryXML =  htmlspecialchars_decode($ParametryXML, ENT_QUOTES);
//print $this->Komunikat = $ParametryXML;
//throw new Exception();			
			//$ParametryXML = urldecode($ParametryXML);  
			$ParametryXML= str_replace('tag_proc_ent', '%', $ParametryXML);
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
			$this->AppModul = $this->Parametry['modul'];
	 		$this->AppMetoda = $this->Parametry['metoda'];
	 		$this->AppPartnerID = $this->Parametry['partner_id'];
	 		$this->AppUzytkownikID = $this->Parametry['uzytkownik_id'];
	 		$this->AppUzytkownikIP = $this->Parametry['uzytkownik_ip'];	 		
	 		$this->AppSQLListWlacz = $this->Parametry['test_sql'];
	 		
	 		// odczyt parametrow aplikacyjnych AppParametry
	 		$parametry_klas = htmlspecialchars_decode($this->Parametry['parametry_klas'], ENT_NOQUOTES);
	 		$this->Komunikat = $parametry_klas; 
		 	$AppParametry = explode(" ,;, ", $parametry_klas);
		 	$AppParametryNazwy = explode(" ,;, ", $this->Parametry['parametry_klas_nazwy']);
	 		$i = 0;
	 		if (is_array($AppParametryNazwy) and count($AppParametryNazwy)>0)
				foreach ($AppParametryNazwy as $value)
				{
					$this->AppParametry[$value] = $AppParametry[$i]; 
	 				$i++;					
				}
	 		// odczyt parametrow bazodanowych AppSQLParametry
		 	$AppSQLParametry = explode(" ,;, ", htmlspecialchars_decode($this->Parametry['parametry_sql']), ENT_NOQUOTES);
		 	$AppSQLParametryNazwy = explode(" ,;, ", $this->Parametry['parametry_sql_nazwy']);
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
  	//	require_once('cDB_ORACLE.php');
			$Serwis =  new DB();      
			//$Serwis->SQL = 'Select top 10 * from kar';
			//$Serwis->SQL = 'Select * from web_oso';

			$wynik = $Serwis->ExecToArray();
			//$wynik['post'] = $_POST;
			$this->Komunikat = $Serwis->Komunikat;
			$this->NrBledu = $Serwis->NrBledu;					
			return $wynik;	 		
	 	}
	 	
	 	private function SetPartnerService()
	 	{
//	 		require_once('Modules/_DAKOL/Config/config.php');
		switch ($this->AppPartnerID)
				 {
					case 1:
						require_once(ParamAppServer::DIR_CLASS_DAKOL.'Config/config.php');	 		
						$this->ClassPathFile =  ParamAppServer::DIR_CLASS_DAKOL.$this->ClassPath.$this->AppModul.".php";
						break;
					case 2:
//						echo "i equals 2";
						require_once(ParamAppServer::DIR_CLASS_STACHURA.'Config/config.php');	 		
						$this->ClassPathFile =  ParamAppServer::DIR_CLASS_STACHURA.$this->ClassPath.$this->AppModul.".php";
						
						break;
					default:
						require_once(ParamAppServer::DIR_CLASS_DAKOL.'Config/config.php');	 		
						$this->ClassPathFile =  ParamAppServer::DIR_CLASS_DAKOL.$this->ClassPath.$this->AppModul.".php";
			}
			 /*require_once(ParamAppServer::DIR_CLASS_DAKOL.'Config/config.php');	 		
			$this->ClassPathFile =  ParamAppServer::DIR_CLASS_DAKOL.$this->ClassPath.$this->AppModul.".php";*/
	 	} 
	 	
	 	private function XmlToArray($XML)
		{
			require_once('cArrayXml.php');
			$Obiekt = new ArrayXml();		
			return $Obiekt->XmlToArray($XML);		
		} 

		private function ArrayToXml($Parametry)
		{
			require_once('cArrayXml.php');
			$Obiekt = new ArrayXml();		
			return $Obiekt->ArrayToXml($Parametry);		
		}
 }
?>