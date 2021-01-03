
<?php

include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');


header("Content-type: image/jpeg");  
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');  

$id = $_GET["id"];

$sql ="SELECT data FROM front_end_images WHERE id =$id";
					
$stmt = $conn->prepare($sql);
$stmt->execute();

$registros = $stmt->rowCount();
$retorno = "";

if($registros == 1){
	$rowData = $stmt->fetch();	
	$retorno = $rowData["data"]; 	
}

header("Content-type: image/jpeg");  
header("Content-disposition: download; filename=''"); 
ob_clean();
flush();
echo $retorno;

?>
