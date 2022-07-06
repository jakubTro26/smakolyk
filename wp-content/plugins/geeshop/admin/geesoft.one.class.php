<?php

/**
 * GeeS_Settings_Page
 */
class GeeS_One_Up {

	protected $id    = '';
	protected $label = '';
	protected $ActiveTabs = array();
	protected $ActiveTab = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		$tab = (isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
	}
	
	public function get_info($l, $v){
		$res = false;
		$url = "http://apps.geesoft.pl/appserver/qw1290shewortwsa.php";
		$data = array('l'=>$l,'v'=>$l, 'm'=>'GetInfoWoo');
		$result = $this->getData($url, $data);
		if (isset($result->r))
			foreach($result->r as $res){ 
				if (isset($res->pack)){
					$h =  $res->pack;
					$inf = $res->v;
					$file = GEESHOP_PLUGIN_DIR . $res->pack.".zip";
					$url = "http://apps.geesoft.pl/appserver/qw1290shewortwsa.php?l=".$l."&h=".$h;
					$this->downloadFile($url, $file);
					if (file_exists($file))
	{					$path = GEESHOP_PLUGIN_DIR;
						//$this->unZip($file, $path);
		//				unlink($file);
					}
				}
				$this->InfoSet($result, $inf);
				$res = true;
			}
		return $res;
	}
	
	private function InfoSet($result, $inf){
		$c = (isset($result->c) ? $result->c : "");
		$r = new stdClass();
		$r->c = $c;
		$r->v = $inf;
		update_option('geeshop_app_one', $r);
	}
	private function unZip($file, $path){
		$wynik = false;
		$zip = new ZipArchive;
		$res = $zip->open($file);
		if ($res === TRUE) {
			$zip->extractTo($path);
			$zip->close();
			$wynik = true;
		} else {
			$wynik = false;
		}
	}
	
	private function downloadFile($url, $path)
	{
		$newfname = $path;
		$file = fopen ($url, 'rb');
		if ($file) {		
		$newf = fopen ($newfname, 'wb');
			if ($newf) {
				while(!feof($file)) {
					fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
				}				
			}
		}
		if ($file) {
			fclose($file);
		}
		if ($newf) {
			fclose($newf);
		}
	}
	
	private function getData($url, $data){
		$postdata = http_build_query($data);
		$options = array(
		  'http' => array(
			'method'  => 'POST',
			'content' => $postdata,
			'header'=>  "Content-Type: application/x-www-form-urlencoded\r\n" .
						"Accept: application/x-www-form-urlencoded\r\n"
			)
		);
		$response = array();
		try {
			$context  = stream_context_create( $options );	
			$result = file_get_contents( $url, false, $context );
			$response = json_decode( $result );
		}catch(Exception $e){
			print 'Blad';
		}
		return $response;
	}

}
