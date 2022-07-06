<?php
/*
 * Created on 2008-01-22
 * File: cWzorDB
 * Author: GeeSoft - Grzegorz Stawski
 * email: subarka@poczta.fm
 * All rights reserved
 */
 
 abstract class WzorDB 
 {
 	protected $DB_Result;
 	protected $DB_Host,$DB_Base, $DB_Pass,$DB_User, $DB_Port = null;
 	protected $DB_Handle = null;
 	
 	public $SQL = null;
	public $NrBledu, $Komunikat = null;
	public $SQLPrint = null;
	public $SQLParametry = array();
			
 	public function __construct() {
	} 	 	 
	public function __destruct() 
	{
		$this->ConnectClose();					
	}
	
	abstract function DBConnect();
	abstract function DBConnectClose();
	abstract public function DBExec();		 
	abstract protected function DBExecToArray();
	abstract protected function DBExecToValue();	
	abstract protected function DBExecError();
	abstract protected function DBExecErrorSave();	 
//	abstract public function DBExecToValue();
	
	public function Exec()
	{			
		try
		{		
			if(!$this->DB_Handle)
					$this->Connect();
			if(!$this->DB_Handle)
				throw new Exception;

			$this->DB_Result = $this->DBExec();// or $this->DBError();;
			if(isset($this->SQLPrint))
				print "<br>".$this->SQL . "<br><hr>";
			return $this->DB_Result;
		}
		catch(Exception $e)
		{			
			$this->ExecError();
		}
	}
	protected function ExecError()
	{
		$this->DBExecError();	   	
	} 
	
	public function ExecToArray()
	{			
		try
		{
		  $this->Exec();
			$return_array = array();
			$return_array = $this->DBExecToArray();
			$this->ConnectClose();			
			return $return_array; 		
		}
		catch(Exception $e)
		{
			$this->ExecError();
		}
	}
		 
	public function ExecToValue()
	{			
		try
		{
		  $this->Exec();
			$return_value = null;
			$return_value = $this->DBExecToValue();
			$this->ConnectClose();			
			return $return_value; 		
		}
		catch(Exception $e)
		{
			$this->ExecError();
		}
	}
			
	private function Connect()
	{
		try
		{
			$this->DBConnect();		 
		}
		catch(Exception $e)
		{
			$this->NrBledu = 50000;
			$this->Komunikat = 'Problem z polaczeniem do bazy danych';
			throw $e;
		}		
	 }
	 
	 private function ConnectClose()
	 {
	 	try
	 	{
	 		$this->DBConnectClose();
	 	}
	 	catch(Exception $e)
	 	{	 		
	 	}
	 }	  	  
 }
 
 function print_t($wynik)
 {  	
 	print "<pre>";
 	print_r($wynik);
 	print "</pre>";
 }

 
?>