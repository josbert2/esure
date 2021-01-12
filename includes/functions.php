<?PHP 
//header("Content-Type: text/html;  charset=UTF-8", true);
if(session_id()==''){session_start();}

$REMOTE_ADDR = $_SERVER["REMOTE_ADDR"];

if($REMOTE_ADDR == ""){

	$path = '/home/esecurec/public_html/';	
	define('PATH',$path);

	require_once(PATH .'/includes/sendMail.php');

	
} else {
	//include $_SERVER['DOCUMENT_ROOT']."/includes/sendMail.php";	
	//include "./includes/sendMail.php";	
	
}



date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_TIME, "spanish"); 


?>