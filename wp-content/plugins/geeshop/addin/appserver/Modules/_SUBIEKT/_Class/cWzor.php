<?php
/*
* Utworzyl: P.P.U.H. GeeSoft Grzegorz Stawski
* email: subarka@poczta.fm
* Wszelkie prawa zastrze¿one!
*/

class Wzor
{
	public $NrBledu = 0;
	public $KomunikatSesja = null;
	public $KomunikatBlad = null;
	public $Komunikat = null;
	static public $UzytkownikID = null;
	static public $UzytkownikNazwa = null;
	protected $UzytkownikIDSessNazwa = 'id_uzytkownika';
	protected $UzytkownikNazwaSessNazwa = 'uzytkownik';
	private $KatalogCache = "cache";
	private $KatalogTemp = "templates";
	private $KatalogTempC = "templates_c";
	protected $UsuwajTagiZPostow = true;
	
	
	public function __construct()
	{
		//$this->URLDomena = 'http://'.$this->Zwroc_Tablica($_SERVER, 'HTTP_HOST'); 
		$this->UzytkownikID = $this->Zwroc_SESSION($this->UzytkownikIDSessNazwa);
		$this->UzytkownikNazwa	= $this->Zwroc_SESSION($this->UzytkownikNazwaSessNazwa);
		if(!empty($_SESSION['KomunikatSesja']))
		{
			$this->Komunikat = $_SESSION['KomunikatSesja'];
			unset($_SESSION['KomunikatSesja']);
		}
	}
	
	
	public function Zwroc_GET($nazwa = '')
	{
		if (isset($_GET[$nazwa]))
		{
			if($this->UsuwajTagiZPostow)
				$_GET[$nazwa] = strip_tags($_GET[$nazwa]);
			return trim(addslashes(stripslashes($_GET[$nazwa])));
		}
		else
		return '';
	}
	
	
	public function Zwroc_POST($nazwa = '')
	{
		if (isset($_POST[$nazwa]))
		{
			if(is_array($_POST[$nazwa]))
			{
				if($this->UsuwajTagiZPostow)
					foreach($_POST[$nazwa] As $k=>$v)
						$_POST[$nazwa][$k] = strip_tags($v);
			return $_POST[$nazwa];
			}
			else
			{
				if($this->UsuwajTagiZPostow)
					$_POST[$nazwa] = strip_tags($_POST[$nazwa]);
				return trim(addslashes(stripslashes($_POST[$nazwa])));
			}
		}
		else return '';
	}
	
	
	public function Zwroc_FILES_Nazwa($nazwa = '')
	{
		if (isset($_FILES[$nazwa]['name']))
			return $_FILES[$nazwa]['name'];
		else
			return '';
	}
	
	
	public function Zwroc_SESSION($nazwa = '')
	{
		if (isset($_SESSION[$nazwa]))
			return $_SESSION[$nazwa];
		else
			return '';
	}

	public function Zwroc_Tablica($tablica=array(),$nazwa = '')
	{
		if (isset($tablica[$nazwa]))
		{
			if($this->UsuwajTagiZPostow)
				$tablica[$nazwa] = strip_tags($tablica[$nazwa]);
			return trim(addslashes(stripslashes($tablica[$nazwa])));
		}
		else
		return '';
	}	
	
	public function Poprawnosc_Email($email)
	{
		$exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
		if(eregi($exp,$email))
			return true;
		else
			return false;
	}
	
	
	public function Refresh($url)
	{
		//print $url;
			if (!headers_sent($_SERVER["PHP_SELF"]))
			{
				//print 'header';
				header('Location:'.$url);
			}
			else
				print '<script language="JavaScript">window.location.replace("'.$url.'");</script>';
			exit;

	}
	
	
	public function Poprawnosc_Float($wartosc)
	{
		try
		{
			$wartosc = floatval($wartosc);
			if(!is_float($wartosc))
			{
				$this->KomunikatBlad = 'Niepoprawna wartoœæ: '.$wartosc;
				throw new Exception;
			}
			return $wartosc;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	
	public function TekstDoInput($txt)
	{
		return str_replace('"', "'", stripslashes($txt));
	}
	
	
	public function UsunUszy($txt)
	{
		return str_replace(array("'", '"'), '', stripslashes($txt));
	}
	
	function Poprawnosc_Haslo($tekst)
	{
		return (ereg ("[:alnum:]", $tekst));
	}
	
	function Poprawnosc_KodPocztowy($tekst)
	{
		return ereg('^[0-9]{2}([-])[0-9]{3}$', $tekst);
	}	
}


function print_t($tablica)
{
	print "<pre>";
	print_r($tablica);
	print "</pre>";
}
?>