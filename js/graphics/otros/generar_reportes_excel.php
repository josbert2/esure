<?php

	
	include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
	include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
	include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');
	include($_SERVER["DOCUMENT_ROOT"] .'/includes/class.php');
	
	function getSorteoDenom($conn, $id_sorteo){
		 $denom ="";
		 
		$sql="SELECT denom
			FROM sorteos
			WHERE id_sorteo = $id_sorteo ";
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$registros = $stmt->rowCount();
			
		if($registros == 1){
			$record = $stmt->fetch();
			$denom = $record["denom"];	
		}	
		return $denom;
	};
	
	$id_sorteo 				= isset($_GET["id_sorteo"]) ? $_GET["id_sorteo"] : 0;

				
	$sql =" SELECT u.legajo, u.denom, p.nombre AS puesto, s.denom AS sector
	FROM usuarios u
		INNER JOIN sectores s
			ON u.id_sector = s.id_sector
		 INNER JOIN puestos p
			ON p.id_puesto = u.id_puesto
		 INNER JOIN sorteos_participantes sp
			ON u.legajo = sp.legajo
				AND sp.id_sorteo = $id_sorteo
	WHERE u.id_estado = 1
	ORDER BY u.denom ";
					
				 
				  
	$filtro = utf8_decode(getSorteoDenom($conn, $id_sorteo));
			
				
				
				
	$retorno ="<table  class=\"textGrid\"  cellpadding=\"0\" cellspacing=\"0\" border=\"1\" width=\"1800px\">".
		  "<tr>".
				"<td colspan=\"4\" bbgcolor=\"#E6E6E6\" style=\"font-weight:bold;font-size:12px;\">Colaboradores Inscriptos</td>".
		 "</tr>".
		 "<tr style=\"font-size:10px\"> ".
				"<td colspan=\"4\" bbgcolor=\"#E6E6E6\"  style=\"font-weight:bold;font-size:12px;\">$filtro</td>".
		 "</tr>".
		 "<tr style=\"font-size:10px\">".					 
				"<td bgcolor=\"#E6E6E6\">Usuario:</td>".
				"<td colspan=\"3\"bgcolor=\"#E6E6E6\">".$_SESSION["denom"]."</td>".
		  "</tr>".
		  "<tr style=\"font-size:10px\">".					 
				"<td bgcolor=\"#000000\" style=\"color:#ffffff\">Legajo</td>".
				"<td bgcolor=\"#000000\" style=\"color:#ffffff\">Apellido y Nombre</td>".
				"<td bgcolor=\"#000000\"  style=\"color:#ffffff\">Puesto</td>".
				"<td bgcolor=\"#000000\"  style=\"color:#ffffff\">Sector</td>".
		  "</tr>";
		  
		  
			$stmt = $conn->prepare($sql);
			$stmt->execute();
						
			
		 		
			while($record_data = $stmt->fetch()){
				$retorno .="<tr  style=\"font-size:11px\">".
							"<td bbgcolor=\"#E6E6E6\" style=\"oborder:1px solid red;\">".$record_data["legajo"]."</td>".
							"<td bbgcolor=\"#E6E6E6\" style=\"oborder:1px solid red;\">".capitalLeter($record_data["denom"])."</td>".
							"<td bbgcolor=\"#E6E6E6\" style=\"oborder:1px solid red;\">".capitalLeter($record_data["puesto"])."</td>".
							"<td bbgcolor=\"#E6E6E6\" style=\"oborder:1px solid red;\">".capitalLeter($record_data["sector"])."</td>".
						  "</tr>";
			}
						  
			$retorno .="</table>";
				
			$fileName = "Participantes_inscriptos_sorteo.xls";
			

	header("content-disposition: attachment;filename=".$fileName);
	echo($retorno);

?>

</body>
</html>



