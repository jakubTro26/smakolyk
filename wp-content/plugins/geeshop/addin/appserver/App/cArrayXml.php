<?php
//error_reporting(0);
/*
 * Created on 2008-02-08
 * File: cArrayXml.php
 * Author: GeeSoft - Grzegorz Stawski
 * email: subarka@poczta.fm
 * All rights reserved
 */
class ArrayXml
	{

	public function __construct()
	{
	}

	public function XmlToArray_GUT($xml_string)
		{
		$tablica = array();
		if (!empty($xml_string))
		{
			$xml=simplexml_load_string($xml_string);
			if (!is_object($xml) || empty($xml))
				return $tablica;
	
			foreach($xml as $key => $type)
				{
				if (is_array($type))
					$tablica[] = (array)$type;
				else
					$tablica[(string)$key] = (string)$type;
				}
		}
	return $tablica;
	}
	
		public function ArrayToXml($tablica)
		{
/*		$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-2\"?>\n";
		$xml = "<?xml version=\"1.0\" encoding=\"win-1250\"?>\n";
*/
			$klucz_glowny = "paczka";
/*			$xml= "<?xml version='1.0' standalone='yes'?>\n";*/
/*	 	  $xml = '<?xml version="1.0" encoding="ISO-8859-2"?>'."\n";*/
	 	  $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
 
			$xml.= "<$klucz_glowny>\n";
					$xml=$this->ArrayToXmlDetail($xml,$tablica, $klucz_glowny);
			$xml .= "</$klucz_glowny>\n";
			//$xml = utf8_encode($xml);
			return $xml;
		}

		private function ArrayToXmlDetailKey($xml, $klucz)
		{
			$len = strlen($klucz)+1;
			$len_xml = strlen($xml);			 
			$klucz_old =  substr($xml, $len_xml-($len),$len);
//			print '$klucz_old:'.$klucz_old ." klucz: ".$klucz."\n<br>";			
			if (trim($klucz_old)==trim($klucz))
				return $xml;			 	
			else
				return $xml.trim($klucz);				 
		}

		private function ArrayToXmlDetail($xml, $tablica, $klucz_glowny)
		{
		//	$xml = '';
		
			if ((!empty($tablica)) && (is_array($tablica)))
			{				
				foreach ($tablica as $klucz => $wiersz)			
				{																
					//print $klucz_glowny;
					$klucz2 = preg_replace( '/\d+/', '', $klucz );
					//if (trim($klucz) != trim($klucz_glowny))
					{ 
						if (!empty($klucz2))					
							$xml=$this->ArrayToXmlDetailKey($xml, "<".$klucz.">");						
						else						
							$xml=$this->ArrayToXmlDetailKey($xml, "<".$klucz_glowny.">");
					}
					if (is_array($wiersz))
						$xml=$this->ArrayToXmlDetail($xml, $wiersz, $klucz);
					else
					{							
//						if (trim($wiersz)!='')
						{							
							//$wiersz2 = htmlspecialchars((string)$wiersz);
							if (is_array($tablica) or is_object($tablica)){
								foreach ($wiersz as $k => $w){
									$wiersz2 = (string)$w;						
									$xml .= ("$wiersz2");							
								}			
							}else
								$wiersz2 = (string)$wiersz;						
					

							//$xml .= ("$wiersz2\n");
							$xml .= ("$wiersz2");							
						}
 						
					}
					//if (trim($klucz) != trim($klucz_glowny))
					{ 					
						if (!empty($klucz2))					
							$xml=$this->ArrayToXmlDetailKey($xml, "</".$klucz.">")."\n";//$xml .= "</".$klucz.">\n";						
						else						
							$xml=$this->ArrayToXmlDetailKey($xml, "</".$klucz_glowny.">")."\n";
					}												
																		
				}
			}	
			return $xml; 		
		}
	 public function XmlToArray($xml_string)
	{	
		$xml_string = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $xml_string);
		$xml_string = str_replace("gees_tag1234", "&", $xml_string);
		//$xml_string = str_replace("nbsp", " ", $xml_string);
		try{
			$xml = simplexml_load_string($xml_string, "SimpleXMLElement");
			
//			$xml = simplexml_load_string($xml_string, "SimpleXMLElement", LIBXML_PARSEHUGE);
		}catch(Exception $ex){
			//$xml = simplexml_load_string($xml_string, "SimpleXMLElement", 131072);
		
		}
		$json = json_encode($xml);
		$json = rtrim($json, "\0");
		$tablica = json_decode(trim($json),true);
		
		//return $tablica;
	//	$array = json_decode(json_encode((array)simplexml_load_string($xml_string)),1);
		return  $tablica;
	}		

	 public function XmlToArray_OK($xml_string)
	{
		
		$xml_string = trim($xml_string);
		$tablica = array();
		if (!empty($xml_string))
		{
			$xml=simplexml_load_string($xml_string);			
			if (!is_object($xml) || empty($xml))
				return $tablica;
			//$xml = isset($xml) ? array($xml) : array();
			  
			if( isset($xml)) { 
				$xml = @json_decode(@json_encode($xml),1);
				//$xml = $xml[0]; 
			}
			
			foreach($xml as $key => $type)
			{				
				if (is_object($type))				
				{
					$type = (array)$type;
					if (count($type)> 1)
						$tablica[(string)$key][] = $this->XMLObjectToArray($key, $type);
	 				else{
						$val = (isset($type[0])) ? $type[0] :'';
						$tablica[(string)$key] = $this->Utf8ToWin($val);
					}
				}
				else{
//					if (!is_array($key))
					$tablica[(string)$key] = $this->Utf8ToWin((string)$type);				
//gs					$tablica[(string)$key] = utf8_decode((string)$type);	
				}			
		  }				
		}
		return $tablica;
	}		

 	private function XMLObjectToArray($key, $dane)
	{
		$tablica = null;
		$wynik = null;
		foreach($dane as $key1 => $type1)
		{
			$type1 = (array)$type1;
			if (is_array((array)$type1))
			{
				if (count($type1)> 2)
					$wynik[(string)$key1] = $this->XMLObjectToArray($key1, $type1);
 				else
					$wynik[(string)$key1] = $this->Utf8ToWin($type1[0]);
			}
			else
				$wynik[(string)$key1] = $this->Utf8ToWin((string)$type1);			
				//gs $wynik[(string)$key1] = utf8_decode((string)$type1);						
		}										
		$tablica = $wynik;
		return $tablica;	
	}


	
	public function ArrayToXml2($tablica)
		{
/*		$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-2\"?>\n";
		$xml = "<?xml version=\"1.0\" encoding=\"win-1250\"?>\n";
*/		$xml = "";		
		$xml.= "<NewDataSet>\n";

		if ((!empty($tablica)) && (is_array($tablica)))
			{
			foreach ($tablica as $klucz => $wiersz)
				{
				if (is_array($wiersz))
					{
					$xml .= "<R>\n";
					foreach ($wiersz as $xml_key => $xml_value)
						{
						if (!(trim($xml_value)=='') && !is_array($xml_value))
							{
							$xml_value = htmlspecialchars($xml_value);
							$xml .= "<".$xml_key.">";
							$xml .= $xml_value;
							$xml .= "</".$xml_key.">\n";
							}
						else
							$xml .= "<" . $xml_key."/>\n";
						}
					$xml .= "</R>\n";
					}
				else
					{
					if (!(trim($wiersz)==''))
							{
							$klucz = (string)$klucz;
							$wiersz = htmlspecialchars((string)$wiersz);
							$xml .= "<".$klucz.">";
							$xml .= $wiersz;
							$xml .= "</".$klucz.">\n";
							}
						else
							$xml .= "<".$klucz."/>\n";
					}
				}
			}

		$xml .= "</NewDataSet>\n";
		return $xml;
		}

	public function ArrayToXml122($tablica)
		{
/*		$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-2\"?>\n";
//		$xml = "<?xml version=\"1.0\" encoding=\"win-1250\"?>\n";
*/		
		$xml= "<NewDataSet>\n";

		if ((!empty($tablica)) && (is_array($tablica)))
		{
			$n = count($tablica);
			$i = 0;
			while ($i <= ($n-1)) 
			{							
				$wiersz = $tablica[$i];			
				if (is_array($wiersz))
				{
					$xml .= "<R>\n";
						$m = count($wiersz);
						$j = 0;
						$klucze = array_keys($wiersz); 					
					  while ($j <= ($m-1)) 					
						{
							$xml_key = $klucze[$j];	
							$xml_value = trim($wiersz[$xml_key]); 
							if (!(trim($xml_value)=='') && !is_array($xml_value))
							{
								$xml_value = htmlspecialchars($xml_value);
								$xml .= "<$xml_key>$xml_value</".$xml_key.">\n";
							}
							else
								$xml .= "<$xml_key/>\n";
							$j++;
						}
					$xml .= "</R>\n";
				}
				else
				{	
					if (empty($klucze))
						$klucze = array_keys($tablica);// slabe ogniowo w petli!! po testach zmienics
					$klucz = $klucze[$i];
					if (trim($wiersz)!='')
					{
						$klucz = (string)$klucz;
						//$wiersz2 = htmlspecialchars((string)$wiersz);
						$wiersz2 = (string)$wiersz;						
						$xml .= "<$klucz>$wiersz2</$klucz>\n";
					}
					else
						$xml .= "<$klucz/>\n";
				}
				$i++;
			}
		}
		$xml .= "</NewDataSet>\n";
		return $xml;
		}
		
		function WinToUtf8($dane)
		{
			return iconv("ISO-8859-2","UTF-8", $dane);
		}

		function Utf8ToWin($dane)
		{
			return iconv("UTF-8", "ISO-8859-2", $dane);
		}
}

/*
$xml = '<?xml version="1.0" encoding="ISO-8859-2"?>
<Product>
<Id>38043</Id>
<Name>Titanic</Name>
<Category>644</Category>
<Price>60.00</Price>
<ProductUrl>http://filmy.ojeju.pl/</ProductUrl>
<Description>Bardzo</Description>
<PicUrl>http://filmy.ojeju.pl/c/9mov/20051019020923.jpg</PicUrl>
<Manufacturer>38043</Manufacturer>
</Product>';


$x = new ArrayXml();
$tab = $x->XmlToArray($xml);

echo '<pre>';
print_r($tab);
echo $xml = $x->ArrayToXml($tab);
*/
		function XmlToArray($XML)
		{
			require_once('cArrayXml.php');
			$Obiekt = new ArrayXml();		
			return $Obiekt->XmlToArray($XML);		
		} 
	
		function ArrayToXml($Parametry)
		{
			require_once('cArrayXml.php');
			$Obiekt = new ArrayXml();		
			return $Obiekt->ArrayToXml($Parametry);		
		} 
		
		function ArrayToXml2($Parametry)
		{
			require_once('cArrayXml.php');
			$Obiekt = new ArrayXml();		
			return $Obiekt->ArrayToXml2($Parametry);		
		} 		
?>