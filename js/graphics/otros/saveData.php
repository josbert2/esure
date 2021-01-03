<?php header('Content-Type: text/html; charset=utf8'); ?>
<?php 

session_start();

include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/class.php');

date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_TIME, "spanish"); //Fijamos el tiempo local
		
	
	$fecha_actual = date("Y-m-d H:i:s");
	
	$action 	= isSet($_POST["action"]) ? $_POST["action"] : 0;
	$id_estado  = isSet($_POST["id_estado"]) ? $_POST["id_estado"] : 0;
	$id 		= isSet($_POST["id"]) ? $_POST["id"] : 0;
	$id_sorteo	= isSet($_POST["id_sorteo"]) ? $_POST["id_sorteo"] : 0;
	$titulo 	= isSet($_POST["titulo"]) ? $_POST["titulo"] : "";
	$comentario = isSet($_POST["comentario"]) ? $_POST["comentario"] : "";
	$date_from = isSet($_POST["date_from"]) ? getSpanishDate($_POST["date_from"]) : ""; 
	$date_to = isSet($_POST["date_to"]) ? getSpanishDate($_POST["date_to"]) : ""; 

	function getSector($conn, $legajo){
		
		$id_sector = 0;
	 
		$sql="SELECT id_sector
			FROM usuarios
			WHERE legajo = $legajo ";
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$registros = $stmt->rowCount();
			
		if($registros == 1){
			$record = $stmt->fetch();
			$id_sector = $record["id_sector"];	
		}	
		
		return $id_sector;
	};
	
	
	$FECHA_ACTUAL = date("Y-m-d");

	$FECHA_HORA_ACTUAL = date("Y-m-d H:i:s");
	$retorno  ="";
	
	switch($action){
		case 1:{//update estado de imagen
				
			try{	
				
				$sql_update = "UPDATE front_end_images SET id_estado = $id_estado WHERE id =$id";
				$conn->exec($sql_update);	
				$retorno = "OK";
			
			} catch (Exception $e) {
				$retorno = 'savedata: ' . $e->getMessage();
			
				logg( 'savedata: ' . $e->getMessage(),"");
			}
			
			break;
		};
		case 2:{//
			
				$sql_update = "UPDATE sorteos SET 
									denom		= '$titulo',
									comment		= '$comentario',
									date_from	= '$date_from',
									date_to		= '$date_to',
									editado_por = $_SESSION[legajo],
									fecha_edit  = '$FECHA_HORA_ACTUAL'
							   WHERE id_sorteo = $id_sorteo";		
				$conn->exec($sql_update);	
				$retorno = "OK";
			
			break;
		};		
		case 3:{//actualizamos registro de TONER
			
				$id_toner  = isSet($_POST["id_estado"]) ? $_POST["id_toner"] : 0;
				$legajo  = isSet($_POST["legajo"]) ? $_POST["legajo"] : 0;
				$contador  = isSet($_POST["contador"]) ? $_POST["contador"] : 0;
				$esperado  = isSet($_POST["esperado"]) ? $_POST["esperado"] : 0;
				$precio  = isSet($_POST["precio"]) ? $_POST["precio"] : 0;
				$fecha = isSet($_POST["fecha"]) ? getSpanishDate($_POST["fecha"]) : ""; 

				$id_sector_actual = getSector($conn, $legajo);
			
			
				if($id == 0){
					$sql_insert_update = "INSERT INTO toner_consumidos (id_toner, fecha, legajo, id_sector_actual, 
																		contador, esperado, precio, comentario, registrado_por, 
																		fechor ) VALUES 
																		($id_toner, '$fecha', $legajo, $id_sector_actual, 
																		$contador, $esperado, $precio, '$comentario', $_SESSION[legajo], '$FECHA_HORA_ACTUAL')";
					
				} else {
					
					$sql_insert_update = "UPDATE toner_consumidos SET 
													id_toner			= $id_toner,
													fecha				= '$fecha',
													legajo				= $legajo,
													id_sector_actual	= $id_sector_actual,
													contador 			= $contador,
													esperado  			= $esperado,
													comentario  		= '$comentario',
													editado_por 	 	= $_SESSION[legajo],
													fechor_edit			= '$FECHA_HORA_ACTUAL'
											   WHERE id = $id";		
				}
			
				$conn->exec($sql_insert_update);	
				$retorno = "OK";
			
			break;
		};
	}
	

echo $retorno;

?>
