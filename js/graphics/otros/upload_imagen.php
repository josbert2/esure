<?php
	
include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/class.php');

$regional_config = new regional_config();
$regional_config->setTimezone("America/Argentina/Cordoba");
$regional_config->setLocalZone("spanish");

$query_ = new Query();
$date_  = new makeDate();			
	 

$id 	 	= isset($_POST["id"]) ? $_POST["id"] : 0;
$id_tipo 	= isset($_POST["id_tipo"]) ? $_POST["id_tipo"] : 0;
$id_sorteo  = isset($_POST["id_sorteo"]) ? $_POST["id_sorteo"] : 0;

$date_from 	= isset($_POST["date_from"]) ? getEnglishDate($_POST["date_from"]) : "";
$date_to	= isset($_POST["date_to"]) ? getEnglishDate($_POST["date_to"]) : "";
$titulo 	= isset($_POST["titulo"]) ? $_POST["titulo"] : "";


	 
	try{	
		//$name_file= $_POST["id_cateo"]
	
			// Make sure the file was sent without errors
			if($_FILES['archivos0']['error'] == 0) {
				// Connect to the database
			   
   
			   $dbLink = new mysqli('127.0.0.1', $query_->userName , $query_->passWord , $query_->dataBase );
			   
			   
			   if (!$dbLink->set_charset("utf8")) {
					printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
					exit();
				}

						   
				if(mysqli_connect_errno()) {
					die("MySQL connection failed: ". mysqli_connect_error());
				}
	
				$name = $dbLink->real_escape_string($_FILES['archivos0']['name']);
				$name = str_replace(",","",$name);
				
	
				$mime = $dbLink->real_escape_string($_FILES['archivos0']['type']);
				$data = $dbLink->real_escape_string(file_get_contents($_FILES['archivos0']['tmp_name']));
				$size = intval($_FILES['archivos0']['size']);
				
				if($id_tipo == 3){
					
					$sql="INSERT INTO sorteos (denom, date_from, date_to,  id_estado, creado_por, fecha_alta) VALUES ".
					"('$titulo', '$date_from', '$date_to', 1, $_SESSION[legajo],'".$date_->getActualDate()."')";
			
					$result = $dbLink->query($sql);
									
									
					 $result = $dbLink->query("SELECT LAST_INSERT_ID() AS id");
					 $row = mysqli_fetch_assoc($result);
					 $id_sorteo = $row["id"];
									
				}
				
								
				
				$sql ="INSERT INTO front_end_images (data, name, ".
											  " mime, kb_size, fechor, ".
											  " legajo, id_estado, id_tipo, id_sorteo) ".
					" VALUES ('".$data."','".$name."','".
							 $mime."',".$size.",'".$date_->getActualDate()."',".
							 $_SESSION["legajo"].",1, $id_tipo, $id_sorteo);";
			
				$result = $dbLink->query($sql);
			
			 $result = $dbLink->query("SELECT LAST_INSERT_ID() AS id");
			 $row = mysqli_fetch_assoc($result);
			 $id_imagen = $row["id"];	
			
			
				
				if (!$result) {
				   echo("Errormessage:". $dbLink->error);
				} else {
					echo("OK::$id_imagen");
				}
			
			} else {
				echo ('An error accured while the file was being uploaded. '
				   . 'Error code: '. intval($_FILES['archivos0']['error']));
			}
	
	} catch (Exception $e) {
		logg( 'Upload: ' . $e->getMessage(),"");
	}

?>
 