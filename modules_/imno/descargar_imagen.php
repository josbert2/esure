<?php header('Content-type:image/jpeg');

session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$id_imagen = $_GET["id_imagen"];
$dbname    = isSet($_GET["dataName"]) ? $_GET["dataName"] : "";

if($dbname == ""){
	echo("No se recibe el nombre del catálogo");
	exit();
}



	
// if($empresa == "1100"){
	// $db_password  = "giulietta";
	// $db_user 	  = "root";

// } else {
	// $db_password  = "Libano2018";
	// $db_user 	  = "esecurec_root";
// }



$OPCIONES = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
	PDO::ATTR_PERSISTENT => true
); 

		
	$db_password  = $_SESSION["db_password"];
	$db_user 	  = $_SESSION["db_user"];	
		
try {
	$connIma = new PDO("mysql:host=".'localhost'.";port=3306;dbname=".$dbname,$db_user,$db_password,$OPCIONES);
	$connIma->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql ="SELECT data FROM inmo_imagenes WHERE id = $id_imagen";

	// echo($sql);
	// exit();
	$stmt_ima = $connIma->prepare($sql);
	$stmt_ima->execute();
		
		
} catch( PDOException $Exception ) {
    echo("Imagenes Exception: ".$Exception->getMessage( ) ."  ". $Exception->getCode( ));
}


$registros = $stmt_ima->rowCount();
$retorno   = "";

if($registros == 1){
	$rowData = $stmt_ima->fetch();	
	$retorno = $rowData["data"]; 	
} else {
	$archivo= $_SERVER["DOCUMENT_ROOT"]. "/images/notphoto.png";
	if (is_file($archivo)){
	   echo(file_get_contents($archivo));
	}
	
}

header("Content-type:image/jpeg");
// header("Content-Transfer-Encoding: binary");
// ob_clean();

// flush();
echo $retorno;?>