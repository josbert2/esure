<?php 

session_start();

include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
	
	$action = $_GET["action"];
	$fecha_dia = date("Y-m-d");
	$sql = "";
	
	switch($action){
		case 1:{
			
			$sql ="SELECT CONCAT(CONCAT(u.denom ,' - ' ,u.legajo),' - ', COALESCE(u.eventual,0)) AS denom  ".
			" FROM usuarios u ".
			" WHERE denom LIKE '%".$_GET["term"]."%'".
			" AND id_estado IN(1,2,3,8) ".
			// " AND u.legajo not in (SELECT mue.legajo ".
			// "					FROM menu_usuario_eventual mue ".
			// "						INNER JOIN menu_usuario mu ".
			// "							ON mue.fecha_dia = mu.fecha_dia ".
			// "								AND mue.legajo = mu.legajo ".
			// "								AND mu.id_comida > 0 ".
			// "					WHERE mue.fecha_dia ='".$_SESSION["fechaMenu"]."') ".
			" ORDER BY u.denom ";
			
			break;
		};
		case 2:{
			$sql ="SELECT CONCAT(TRIM(RTRIM(u.denom)) ,' - ' ,u.legajo, ' - Puesto: ',TRIM(RTRIM(p.nombre)), ' - Sector: ', TRIM(RTRIM(s.denom))) AS denom ".
			" FROM usuarios u ".
			"	INNER JOIN sectores s ".
			"		ON u.id_sector = s.id_sector ".
			"	INNER JOIN puestos p ".
			"		ON u.id_puesto =  p.id_puesto ".
			" WHERE u.denom LIKE '%".$_GET["term"]."%'".
			" ORDER BY denom ";
			//"  AND u.id_estado = 1 ".
		
			break;
		};
		case 3:{
			$sql ="SELECT CONCAT(TRIM(RTRIM(denom)) ,' - ' ,id_sector) AS denom ".
			" FROM sectores ".
			" WHERE denom LIKE '%".$_GET["term"]."%'".
			" ORDER BY denom ";
		
			break;
		};
		case 4:{
			$sql ="SELECT CONCAT(TRIM(RTRIM(denom)) ,' - ' ,legajo) AS denom ".
			" FROM usuarios ".
			" WHERE denom LIKE '%".$_GET["term"]."%'".
			" ORDER BY denom ";
		
			break;
		};
		case 5:{
			
			$sql ="SELECT CONCAT(TRIM(RTRIM(u.denom)) ,' - ' ,u.legajo) AS denom ".
				"	FROM usuarios u  ".
				"		INNER JOIN puestos p  ".
				"			ON u.id_puesto = p.id_puesto  ".
				"		INNER JOIN sectores s  ".
				"			ON u.id_sector = s.id_sector  ".
				"		LEFT JOIN dependientes d ".
				"			ON u.legajo = d.legajo ".
				"	WHERE d.legajo_depende = $_SESSION[legajo] ".
				" 		AND  u.denom LIKE '%".$_GET["term"]."%'";
			
			break;
		};
		case 6:{//Registro de Novedades
			$sql ="SELECT CONCAT(TRIM(RTRIM(u.denom)) ,' - ' ,u.legajo, ' - ',p.posicion , ' - ',p.id_puesto,' - Categoría: ',TRIM(RTRIM(p.nombre)), ' - Sector: ', TRIM(RTRIM(s.denom))) AS denom ".
			" FROM usuarios u ".
			"	INNER JOIN sectores s ".
			"		ON u.id_sector = s.id_sector ".
			"	INNER JOIN puestos p ".
			"		ON u.id_puesto =  p.id_puesto ".
			" WHERE u.denom LIKE '%".$_GET["term"]."%'".
			"  AND u.id_estado = 1 ".
			" ORDER BY denom ";
		
			break;
		};
	};
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$registros = $stmt->rowCount();
	
	if($registros > 0){
		$retorno ="[";
		
		while($record = $stmt->fetch()){$retorno .="{\"label\":\"".capitalLeterNew($record["denom"])."\"},";	}
		$retorno .="]";
		$retorno = str_replace("},]", "}]", $retorno);
	}
	
	echo $retorno;

?>