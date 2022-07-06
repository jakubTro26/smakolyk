<?php
/*
 * Created on 2016-02-07
 * File: cConfig.php
 * Author: GeeSoft - Grzegorz Stawski
 * email: biuro@geesoft.pl
 * All rights reserved
 */
// require_once('../../../App/cWzorClass.php'); 
// require_once('cWzorClass.php');
 //require_once('cParamMsSQL.php'); 
 class AppClass extends WzorClass  
 {
	private $Serwis = null;		
	
 	public function __construct() 
 	{
 		parent::__construct();
		//header( 'Content-type: text/css;' );
		//header( 'Cache-control: must-revalidate' );
	
		//if ( !defined('ABSPATH') ) {
			//define('ABSPATH', dirname(__FILE__) . '/');
		//}
		
 		if (!defined('ABSPATH')) {
 		  

			$url 	= dirname( __FILE__ );
 		    if (file_exists($url.'/cConfig.php')){
				require_once( 'cConfig.php' ); 
				$strpos = strpos( $url, GEES_WP_CONTENT );
			}
			else				
				$strpos = strpos( $url, 'wp-content' );
 		    $base 	= substr( $url, 0, $strpos );
		} else {
 		    $base = ABSPATH;
 		}
		require_once( $base .'wp-load.php' ); 
 	} 	 	 
 	
	public function __destruct() 	
	{	
		parent::__destruct();
	}
	
	protected function DBExecToArray()
 	{ 	 	
	/*	$this->SetSQLList($this->SQL); 			
		if (empty($this->Serwis))
		{
		//	require_once('cDB_MSSQL.php'); 		
			//$this->Serwis =  new DB();
		}      		
		$this->Serwis->SQLParametry = $this->SQLParametry;
		$this->DBExecSaveToDB();
		$this->Serwis->SQL = $this->SQL;
		$wynik = $this->Serwis->ExecToArray();
		$this->SQLParametryWynik = $this->Serwis->SQLParametryWynik;		
		$this->Komunikat = $this->Serwis->Komunikat;
		$this->NrBledu = $this->Serwis->NrBledu;					
		return $wynik;	 
*/		
 	}	

	protected function DBExecToValue()
 	{
		/*
		$this->SetSQLList($this->SQL);
		if (empty($this->Serwis))
		{
			//require_once('cDB_MSSQL.php'); 		
			//$this->Serwis =  new DB();
		}		      		
		$this->Serwis->SQLParametry = $this->SQLParametry;
		$this->DBExecSaveToDB();
		$this->Serwis->SQL = $this->SQL;
		$wynik = $this->Serwis->ExecToValue();
		$this->SQLParametryWynik = $this->Serwis->SQLParametryWynik;
		$this->Komunikat = $this->Serwis->Komunikat;
		$this->NrBledu = $this->Serwis->NrBledu;					
		return $wynik;	 	
*/		
 	}
 	
 	private function DBExecSaveToDB()
 	{
	/*	if (empty($this->Serwis))
		{
			require_once('cDB_MSSQL.php'); 		
			$this->Serwis =  new DB();
		}
		$SQLParametry = array(
														'komunikat'=>'',
														'result' => ''
													);
//print_t($SQLParametry);
		$SQLParametryOLD = $this->Serwis->SQLParametry;													      		
		$this->Serwis->SQLParametry = $SQLParametry;
	//$SQL = "begin :result := adnet.web_logi_zapisz(:web_oso_id, :zapytanie, :ip, :komunikat); end;";
		if (empty($this->AppUzytkownikID))
			$this->AppUzytkownikID = 0;
		if (empty($this->AppUzytkownikIP))
			$this->AppUzytkownikIP = 'none';
		$SQL = "begin :result := adnet.web_logi_zapisz(".$this->AppUzytkownikID.", '".htmlspecialchars("$this->SQL", ENT_QUOTES)."', '".$this->AppUzytkownikIP."', :komunikat); end;";
		$this->Serwis->SQL = $SQL;
		$wynik = $this->Serwis->ExecToArray();
		$this->SQLParametryWynik = $this->Serwis->SQLParametryWynik;
		$this->Serwis->SQLParametry = $SQLParametryOLD; 
		$this->Serwis->SQL = null;
		$this->Serwis->Komunikat = null;
		$this->Serwis->NrBledu = null; 		
		$this->Komunikat = $this->Serwis->Komunikat;
		$this->NrBledu = $this->Serwis->NrBledu;	 	*/
 	}
 	 	 	
 }
?>