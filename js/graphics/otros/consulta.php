<?php header('Content-Type: text/html; charset=utf8'); ?>
<?php 


date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_ALL,"es_ES");
setlocale(LC_TIME, "spanish"); //Fijamos el tiempo local

session_start();

include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');
	
$action = isSet($_POST["action"]) ? $_POST["action"] : 0;
$legajo = isSet($_POST["legajo"]) ? $_POST["legajo"] : 0;
$id_estado = isSet($_POST["id_estado"]) ? $_POST["id_estado"] : 0;
$id_tipo = isSet($_POST["id_tipo"]) ? $_POST["id_tipo"] : 0;
$id = isSet($_POST["id"]) ? $_POST["id"] : 0;
$id_sorteo	= isSet($_POST["id_sorteo"]) ? $_POST["id_sorteo"] : 0;
$id_toner	= isSet($_POST["id_toner"]) ? $_POST["id_toner"] : 0;

$FECHA_ACTUAL = date("Y-m-d");
$FECHA_HORA_ACTUAL = date("Y-m-d H:i:s");

$retorno ="";	
switch($action){
	case 1:{//fotos de cumples
	
			$sql="SELECT fei.id, fei.id_estado, so.denom, so.comment, so.date_from, so.date_to, fei.id_sorteo ".
					" FROM front_end_images fei  ".
					"	LEFT JOIN sorteos so ".
					"		ON fei.id_sorteo = so.id_sorteo ".
					" WHERE fei.id_tipo = $id_tipo ";
			
			$sql .= ($id > 0 ? " AND fei.id = $id " :"");		
			
			$sql .=	" ORDER BY fei.id_estado ASC";
	
			logg($sql,"");
	
			$stmt = $conn->prepare($sql);
			$stmt->execute();
							
			$registros = $stmt->rowCount();
			
			
			if($registros > 0){
				$retorno ="<div class=\"resizable container\" style=\"0border:2px solid blue\">".
								"<div class=\"container\">";		
				
			   $cnt = 0;
			   $a = 0;
				while($datos = $stmt->fetch()){
					if($cnt == 0){$retorno .= "<div class=\"row\">";}
					
					$id_estado = $datos["id_estado"];
					$style	   = ($id_estado == 1 ? "" : "border:outset;filter:alpha(opacity=50); opacity:0.5");	
					
					$retorno.="<div class=\"col-sm-6\" style=\"cursor:pointer;width:400px;\">
										<div style=\"$style\" id=\"img_$datos[id]\" onclick=\"activar(".$datos["id"].",".$datos["id_estado"].",".$id_tipo.",".$datos["id_sorteo"].")\" class=\"thumbnail\" href=\"#\" data-image-id=\"\" data-toggle=\"modal\" data-title=\"\"
										   data-image=\"\"  data-target=\"#image-gallery\" >
											<img class=\"img-thumbnail\"
												 src=\"descargarImagenes.php?id=$datos[id]\"
												 
												 style=\"width:350px;mmin-height:250px;max-height:250px\"
												 alt=\"\">";
						if($id_tipo == 3){
							$retorno.=	$datos["denom"] ."<br><span style=\"font-weight:bold\">Vigencia desde el ".getSpanishDate($datos["date_from"])." al ".getSpanishDate($datos["date_to"])."</span>";
							$retorno.=	"<div class=\"boton_1\" onclick=\"descargarInscriptos(".$datos["id_sorteo"].")\" style=\"pposition:absolute;top:0px;\">".getCantidadInscriptos($conn, $datos["id_sorteo"])." Inscriptos</div>";

						}
										
						$retorno.="		</div>
									</div>";
						$cnt ++;	
						$a ++;

						if($cnt == 2){$retorno .= "</div>"; $cnt = 0;}		
						
				}
			}	
		  
			$retorno .= "</div></div>";	
		break;
	};
	case 2:{
		$retorno	 = "OK";		
		$date_to 	 = "";	
		$date_from   = "";
		$comentario  = "";
		$denom 		 = "";
			
		$sql="SELECT fei.id, fei.id_estado, so.denom, so.comment, so.date_from, so.date_to ".
			" FROM front_end_images fei  ".
			"	LEFT JOIN sorteos so ".
			"		ON fei.id_sorteo = so.id_sorteo ".
			" WHERE fei.id = $id ";
			
			$stmt = $conn->prepare($sql);
			$stmt->execute();
							
			$registros = $stmt->rowCount();
			
			if($registros == 1){
				$datos = $stmt->fetch();
				
				$retorno 	.= "::".$datos["denom"];
				$retorno 	.= "::".$datos["comment"];
				$retorno 	.= "::".$datos["date_from"];	
				$retorno 	.= "::".$datos["date_to"];
				
			}	
				
				
		
		break;
	};
	case 3:{//listado de toner consumidos
	
		
			$sql ="SELECT u.legajo, u.denom, t.denom AS toner, tc.id,  t.precio, t.vigencia, t.esperado, tc.contador,
					tc.fecha, u2.denom AS registrado_por
					FROM toner_consumidos tc
						INNER JOIN usuarios u
							ON tc.legajo = u.legajo
						INNER JOIN usuarios u2
							ON tc.registrado_por = u2.legajo
						INNER JOIN toner t
							ON tc.id_toner = t.id
					ORDER BY tc.fecha DESC";
			
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			
			$retorno ="OK::<div style=\"position:absolute;top:0px;left:0px;width:1100px;hheight:600px;0border:1px solid red;ooverflow-y:scroll;\">"; 

			$registros = $stmt->rowCount();
			if($registros > 0){
				$cnt = 0;
				while($row = $stmt->fetch()) {
					
					if($cnt %2 ==0){$color ="#F5D0A9";} else {$color ="#E6E6E6";}	
					
					$image_control = "";
					
					if($row["contador"] == 0){
						$image_control = "<img title=\"\" style=\"display:inline;margin-left:20px;border-radius:50%;;margin-top:5px;\" src=\"/icon/pregunta.png\" width=\"20px\" height=\"20px\" />";
					} else {
						
						if($row["esperado"] < $row["contador"]){
							$image_control = "<img title=\"\" style=\"display:inline;margin-left:20px;border-radius:50%;margin-top:5px;\" src=\"/icon/up.png\" width=\"20px\" height=\"20px\" />";
						} else if($row["esperado"] >= $row["contador"]){
							$image_control = "<img title=\"\" style=\"display:inline;margin-left:20px;border-radius:50%;;margin-top:5px;\" src=\"/icon/down_.png\" width=\"20px\" height=\"20px\" />";
						} 
					}
					
									// "<div  style=\"display:inline-block;width:30px;\"> ".
										// "<input type=\"checkbox\" name=\"chkToner_".$row["id"]."\" id=\"chkToner_".$row["id"]."\"".
											// " value=\"\" onclick=\"clearCheck_toner('".$row["id"]."')\"/> ".
									// "</div>".
					
					
					 $retorno .= "<div  class=\"camposFields\" style=\"background-color:$color;display:inline-block;width:1100px;height:30px;text-align:vertical;0border:1px solid #D2D0DE\">".
									 "<div  style=\"margin-left:5px;display:inline-block;width:200px;\"><a href=\"#\" onclick=\"editarRegistro(".$row["id"].")\">". capitalLeterNew($row["denom"])."</a></div>".
									"<div  style=\"display:inline-block;width:100px;text-align:center;\">". getSpanishDate($row["fecha"])."</div>".									
									"<div  style=\"display:inline-block;width:150px;\">". capitalLeterNew($row["toner"])."</div>".
									 "<div  style=\"display:inline-block;width:70px;;text-align:right;\">". ($row["esperado"])."</div>".
									 "<div  style=\"display:inline-block;width:70px;;text-align:right;\">". ($row["contador"])."</div>".
									 "<div style=\"display:inline-block;width:50px;\">".
										$image_control.
									"</div>".
									 
									"<div  style=\"display:inline-block;width:100px;text-align:right;\">". $row["precio"]."</div>".
									"<div  style=\"display:inline-block;width:100px;text-align:center;\">". getSpanishDate($row["vigencia"])."</div>".
									"<div  style=\"display:inline-block;width:200px;\">".capitalLeterNew($row["registrado_por"])."</div>".
								 "</div>";
					$cnt++;
				}	
			} else {
				$retorno .= "<div class=\"supplier_colections\";></div>";
			}
			 
		
		break;
	};
	case 4:{//buscamos un registro
	
		
		$sql =" SELECT CONCAT(u.denom, ' - ',u.legajo) AS solicitante, t.denom AS toner, tc.id,  t.precio, 
				tc.fecha, t.vigencia, tc.contador, t.esperado, tc.comentario, tc.id_toner
				FROM toner_consumidos tc
					INNER JOIN usuarios u
						ON tc.legajo = u.legajo
					INNER JOIN toner t
						ON tc.id_toner = t.id
				 WHERE tc.id = $id "; 
		
		$retorno = recordRowToJsonOneElement($conn, $sql);
			
		
		
		break;
	};
	case 5:{//devolvemos datos de n toner
	
	
		$sql="SELECT precio, esperado 
			FROM toner
			WHERE id = $id_toner ";
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$registros = $stmt->rowCount();
		$retorno = "OK::";	
		if($registros == 1){
			$record = $stmt->fetch();
			$precio = $record["precio"];	
			$esperado = $record["esperado"];	
			
			$retorno .= "$precio::$esperado";	
		}	
		
		
		
		
		break;
	};
};

echo $retorno;

?>
