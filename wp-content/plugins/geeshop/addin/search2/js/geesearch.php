<?php
header('Content-type: text/javascript;');
header('Cache-control: must-revalidate');
$actual = 'http://'.$_SERVER['SERVER_NAME']. $_SERVER['SCRIPT_NAME'];
$actual = str_replace('js/geesearch.php', 'search_now.class.php', $actual); 
?>
jQuery(document).ready(function(){	
	window.urlVar = '<?php print $actual; ?>';
})