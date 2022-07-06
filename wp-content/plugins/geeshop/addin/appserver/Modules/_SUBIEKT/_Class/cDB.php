<?php
require_once('inc/top.php');
/*
Utworzyl: P.P.U.H. GeeSoft Grzegorz Stawski
email: subarka@poczta.fm
Wszelkie prawa zastrze¿one!
*/
require_once('cParamAppServer.php');
require_once('cWzor.php');

class DB extends Wzor
{
	protected $server = null;
	protected $user = null;
	protected $password = null;
	protected $base = null;
	private $SQLWyniki = null;
	private $database_handle;
	private $KomunikatBladAnalizaWlacz = true;
	public $SQL;
	public $SQLPrint;
	
	
	public function __construct()
	{
		$this->server = ParamAppServer::DB_HOST;
		$this->user = ParamAppServer::DB_USER;
		$this->password = ParamAppServer::DB_PASS;
		$this->base = ParamAppServer::DB_BASE;
		parent::__construct();
		
	//ini_set('display_errors','0');
	//error_reporting(0);
	//$this->KomunikatBladAnalizaWlacz = false;
	//echo 'display_errors = ' . ini_get('display_errors') . "\n";
	//echo 'error_reporting = ' . ini_get('error_reporting') . "\n";
	}
	
	
	protected function SQLPolacz()
	{
		try
		{
			$ConnectionString = "host='".$this->server."' dbname='".$this->base."' user='".$this->user."' password='".$this->password."' port='".Parametry::DB_PORT."' options='-c client_encoding=WIN1250'";
			$this->database_handle = pg_connect($ConnectionString);
			if(!$this->database_handle)
				throw new Exception;
		}
		catch(Exception $e)
		{
			$this->NrBledu = 50000;
			$this->KomunikatBlad = 'Problem z po³aczeniem do bazy danych';//.$this->SQL;
			throw $e;
		}
	}
	
	
	protected function KomunikatAnalizuj()
	{
		$komunikat = $this->KomunikatBlad;
		if (empty($komunikat))
			$komunikat = $this->Komunikat;		
		//print $ile = strpos($komunikat, 'NrBledu');
		if(!empty($ile))
		{
			
			$tmp = substr($this->KomunikatBlad,'NrBledu');
			$ile = strpos($this->KomunikatBlad, 'NrBledu:');
			$this->NrBledu = substr($this->KomunikatBlad, 0, strpos($this->KomunikatBlad,'-', 0)+1);
			$ile = strpos($this->NrBledu, 'NrBledu:')+8;
			$this->NrBledu = (int)substr($this->NrBledu, $ile, strpos($this->KomunikatBlad,'-', 0)-1);
			$this->KomunikatBlad = substr($this->KomunikatBlad, strpos($this->KomunikatBlad,'-', 0)+1, -1);
			print 'Analiza bledu';
		}	
	}	
	
	private function KomunikatBladAnalizuj()
	{
		if($this->KomunikatBladAnalizaWlacz)
		{
			$this->KomunikatAnalizuj();
		}
		else
		{
			$this->KomunikatBladZglos();
			$ile = strpos($this->KomunikatBlad, 'ERROR:');
			$this->KomunikatBlad = "Przepraszamy, wyst±pi³ b³±d.";
		}
	}
	
	
	public function SQLBlad()
	{
		try
		{
			$this->KomunikatBlad = pg_last_error();
			if(!empty($this->KomunikatBlad))
			{
				$this->NrBledu = 60289;
				
				throw new Exception($this->KomunikatBlad.'<br>'.$this->SQL);
			}
			else
				$this->NrBledu = 0;
			return $this->NrBledu;
		}
		catch(Exception $e)
		{
			//print $this->KomunikatBlad;
			$this->KomunikatBladAnalizuj();
			throw $e;
		}
	}
	
	
	public function SQLWykonaj()
	{
		try
		{
			//print $this->SQL."<br>";
			if(!$this->database_handle)
				$this->SQLPolacz();
			if(!$this->database_handle)
				throw new Exception;
			$this->SQLWyniki = pg_query($this->database_handle,$this->SQL) or $this->SQLBlad();
			if(isset($this->SQLPrint))
				print "<br>".$this->SQL . "<br><hr>";
			return $this->SQLWyniki;
		}
		catch(Exception $e)
		{
				//print "<br>".$this->SQL . "<br><hr>";
			if(empty($this->NrBledu))
				$this->KomunikatBlad.= 'B³¹d wykonania zapytania:<br>'.$this->SQL;
		}
	}
	
	
	public function SQLZwrocWyniki()
	{
		if($this->NrBladu = 0)
		if(pg_fetch_array($this->SQLWyniki))
		return pg_fetch_array($this->SQLWyniki);
	}
	
	
	public function SQLZwrocWynikiTablica()
	{
		$this->SQLWykonaj();
		$tablica = array();
		
		//if(empty($this->NrBledu) and empty($this->KomunikatBlad) and (is_resource($this->SQLWyniki)))
		if(is_resource($this->SQLWyniki))
		{
			while($wiersz = pg_fetch_assoc($this->SQLWyniki))
				$tablica[]= $wiersz;
		}
		return $tablica;
	}
	
	
	public function SQLZwrocWynikiWartosc()
	{
		try
		{
			$this->SQLWykonaj();
			$wartosc = null;
			if($this->SQLWyniki)
			{
				$wiersz = pg_fetch_array($this->SQLWyniki);
				if(is_array($wiersz) && array_key_exists("0", $wiersz) && isset($wiersz[0]))
					$wartosc = $wiersz[0];
			}
			return $wartosc;
		}
		catch(Exception $e)
		{
			return null;
		}
	}
	
	
	public function SQLIloscRekordow()
	{
		return pg_num_rows($this->SQLWyniki);
	}
	
	
	protected function SQLObslugaLIMIT($start, $stop)
	{
		return ' LIMIT '.$stop.' OFFSET '.$start;
	}
	
	
	final protected function ParametrOdczytaj($SklepID, $klucz)
	{
		if(empty($SklepID))
			$SklepID = 'null';
		$this->SQL = "SELECT * FROM www.ADMIN_PARAMETR_ODCZYTAJ($SklepID,'$klucz');";
		return $this->SQLZwrocWynikiWartosc();
	}
	
	
	private function TablicaTresc($tablica)
	{
		$br = "\r";
		$tab = "\t";
		$tresc = "Array".$br."(".$br;
		foreach($tablica as $klucz => $wartosc)
		{
			if(is_array($wartosc))
				$tresc.= $tab."[$klucz] => ".$this->TablicaTresc($wartosc);
			else
				$tresc.= $tab."[$klucz] => $wartosc$br";
		}
		$tresc.= "$br)$br";
		return $tresc;
	}
	
	protected function BazaWartosc($Wartosc, $Typ)
	{
		if ($Wartosc=='')
			$Wartosc = 'NULL';
			
		switch($Typ)
		{
			case "bool":
				if ($Wartosc == 'NULL')
					$Wartosc = "false";
				else
					$Wartosc = "true";				
			break;			
			case "str":
				if ($Wartosc != 'NULL')
					$Wartosc = "'".$Wartosc."'";
			break;
		}
		return $Wartosc; 
	}
		
	private function KomunikatBladZglos()
	{
		require_once('cEmail.php');
		$temat = "NrBledu: ".$this->NrBledu;
		$tresc = "NrBledu: ".$this->NrBledu."<br><br>Tresc:".$this->KomunikatBlad."<br><br>SQL: ".$this->SQL;
		$tresc.= "<br>Wywolano: ".$_SERVER['PHP_SELF'];
		$tresc.= "<br>_POST: <br>";
		$tresc.= "<pre>";
		$tresc.= $this->TablicaTresc($_POST);
		$tresc.= "</pre>";
		$tresc.= "<br><br>_GET:<br> ";
		$tresc.= "<pre>";
		$tresc.= $this->TablicaTresc($_GET);
		$tresc.= "</pre>";
		$tresc.= "<br><br>_SESSION:<br>";
		$tresc.= "<pre>";
		$tresc.= $this->TablicaTresc($_SESSION);
		$tresc.= "</pre>";
	}
}
?>