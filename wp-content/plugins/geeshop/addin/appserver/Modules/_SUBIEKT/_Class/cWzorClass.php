<?php
/*
 * Created on 2008-01-22
 * File: cWzorDB
 * Author: GeeSoft - Grzegorz Stawski
 * email: subarka@poczta.fm
 * All rights reserved
 */
 
 abstract class WzorClass 
 {
 	
  public $SQL = null;
  private  $SQLList = array();
	public $NrBledu, $Komunikat = null;
	public $AppMetoda = null;
	public $Parametry = array();	
	public $KlientIP, $KlientID = null;
	//public $AppPartnerID = null;
 	public $AppUzytkownikID = null;
	public $AppUzytkownikIP = ''; 			 
	public $SQLParametry, $SQLParametryWynik = array();	
 	public function __construct() {} 	 	 
	public function __destruct() 	{}
	abstract protected function DBExecToArray();
	
	public function SetSQLParam($SQLParametry)
	{			
		$this->SQLParametry = $SQLParametry;		
	}

	public function SetParam($Parametry)
	{			
		$this->Parametry = $Parametry;		
	}
	public function SetSQLList($SQL)
	{
		if (!empty($SQL))		//trim(stripslashes(htmlspecialchars_decode($ParametryXML, ENT_NOQUOTES)));
			$this->SQLList[] = trim(htmlspecialchars($SQL, ENT_NOQUOTES));
			
	}	

	public function GetSQLList()
	{
		return $this->SQLList;		
	}	
	
	public function test2()
	{
		return '';	
	}					
	
	public function ExecuteMethod()
	{			
		try
		{				
			
			if (!method_exists($this,$this->AppMetoda))
			{
  			$this->Komunikat = "Brak metody: ".$this->AppMetoda;
  			$this->NrBledu = 50012;
  			throw new Exception(); 				
			} 
			return call_user_method ($this->AppMetoda, $this, "\t");

		}
		catch(Exception $e)
		{
		}
	}
 }
/* $ob = new WzorClass();
	$ob->AppMetoda = 'test'; 
 	print $ob->ExecuteMethod();
 	print "$ob->NrBledu $ob->Komunikat";*/
?>