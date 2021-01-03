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
$index = isSet($_POST["index"]) ? $_POST["index"] : 0;
$periodo = isSet($_POST["periodo"]) ? $_POST["periodo"] : "";
$legajo_supervisor = isSet($_POST["legajo_supervisor"]) ? $_POST["legajo_supervisor"] : 0;

$FECHA_ACTUAL = date("Y-m-d");
$FECHA_HORA_ACTUAL = date("Y-m-d H:i:s");

function getNivelSatisfaccion($conn){
	$sql="SELECT  COUNT(*) AS cantidad, SUM(calificacion)* 2 AS calificacion
		 FROM mesa_ayuda_registros 
		 WHERE id_estado = 10";
			
	$stmt_ = $conn->prepare($sql);
	$stmt_->execute();
					
	$registros = $stmt_->rowCount();

	$datos_ = $stmt_->fetch();
	$cantidad = $datos_["cantidad"];
	$calificacion = $datos_["calificacion"];
	
	return (($calificacion/$cantidad)*10);	
};
 
$retorno ="";	

function getColaboradoresActivos($conn, $id_grupo_sector, $desde, $hasta){
	
	$cnt = 0;
	
	if($id_grupo_sector == 0){
		
		$sql="SELECT COUNT(*) AS colaboraores 
				FROM usuarios
				WHERE fecha_alta < '$desde' AND (fecha_baja IS NULL OR fecha_baja >'$hasta')";
	} else {
		$sql="SELECT COUNT(*) AS colaboraores 
				FROM usuarios u
					INNER JOIN sectores s
						ON u.id_sector = s.id_sector
					INNER JOIN sectores_grupos sg
						ON s.id_grupo_sector = sg.id
				WHERE u.fecha_alta < '$desde' AND (u.fecha_baja IS NULL OR u.fecha_baja >'$hasta')
					AND sg.id = $id_grupo_sector";
		
	}
		
logg($sql,"");
		
	$stmt_ = $conn->prepare($sql);
	$stmt_->execute();
					
	$registros = $stmt_->rowCount();

	$datos_		= $stmt_->fetch();
	$cnt 		= $datos_["colaboraores"];
	
	return $cnt;
};

switch($action){
	case 0:{//retornamos total de casos
		
		$sql="SELECT analista_responsable, id_estado FROM mesa_ayuda_registros ";
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
						
		$registros = $stmt->rowCount();
		
		$retorno 			="OK::";
		$total_casos		= 0;
		$casos_pendientes	= 0;
		$total_no_asignados = 0;
		$total_resueltos    = 0;
		
		while($datos = $stmt->fetch()){
			if(($datos["analista_responsable"] == 0) && ($datos["id_estado"] != 10)){
				$total_no_asignados ++;
			} 
			
			if($datos["id_estado"] == 10){
				$total_resueltos ++;
			} 
			
			if($datos["id_estado"] != 10){
				$casos_pendientes ++;
			} 
			
			$total_casos ++;
		};
		
		$retorno .= $total_casos."::".$casos_pendientes."::".$total_no_asignados."::".$total_resueltos;
		
		break;
	};
	case 1:{//graficos de casos pendientes por analista
				
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"caption"=> "",
											"subcaption"=> "",
											"showvalues"=> 1,
											"showpercentintooltip"=>0,
											"numberprefix"=>" Casos ",
											"enablemultislicing"=>1,
											"theme"=> "fusion"),
							"data"=>array());	

		$sql ="SELECT * FROM usuarios WHERE analista_cp = 1 AND id_estado = 1";

		$stmt = $conn->prepare($sql);
		$stmt->execute();
						
		$registros = $stmt->rowCount();

		while($datos = $stmt->fetch()){
			$legajo = $datos["legajo"];
			$denom  = $datos["denom"];
			
			$sql="SELECT COUNT(*) AS cantidad 
				 FROM mesa_ayuda_registros 
				 WHERE analista_responsable = $legajo 
					AND id_estado != 10";
			
			$stmt_ = $conn->prepare($sql);
			$stmt_->execute();
							
			$registros = $stmt_->rowCount();

			$datos_ = $stmt_->fetch();
			$cantidad = $datos_["cantidad"];
			
			if($cantidad > 0){
				array_push($arrayData["data"],array("label"=>$denom,"value"=>$cantidad));
			}
		};
		
		break;
	};
	case 2:{//nivel de satisfacción, casos cerrados
		
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"caption"=> "",
											"labelFontColor"=> "#ffffff",
											"subcaption"=> "",
											"lowerlimit"=> "0",
											"upperlimit"=> "100",
											"showvalue"=> 1,
											"numbersuffix"=>"%",
											"theme"=> "fusion"),
							"colorrange"=>array("color"=>array(
												array("minvalue"=>0,"maxvalue"=>50,"code"=>"#F2726F"),
												array("minvalue"=>50,"maxvalue"=>75,"code"=>"#FFC533"),
												array("minvalue"=>75,"maxvalue"=>100,"code"=>"#62B58F"))),
							 "dials"=>array("dial"=>array(array("value"=>getNivelSatisfaccion($conn),"tooltext"=>"l"))),
							 "trendpoints"=>array("point"=>array("startvalue"=>"80",
																"displayvalue"=>"Target",
																"thickness"=>"2",
																"color"=>"#FFFFFF",
																"usemarker"=>"1",
																"markerbordercolor"=>"#FFFFFF",
																"markertooltext"=> "80%")));	
																
			

		break;
	};
	case 3:{//casos resueltos por analistas

		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"caption"=> "",
											"subcaption"=> "",
											"showvalues"=> 1,
											"showpercentintooltip"=>0,
											"numberprefix"=>" Casos ",
											"enablemultislicing"=>1,
											"theme"=> "fusion"),
							"data"=>array());	

		
		$sql ="SELECT * FROM usuarios WHERE analista_cp = 1 AND id_estado = 1";

		$stmt = $conn->prepare($sql);
		$stmt->execute();
						
		$registros = $stmt->rowCount();

		while($datos = $stmt->fetch()){
			$legajo = $datos["legajo"];
			$denom  = $datos["denom"];
			
			$sql="SELECT COUNT(*) AS cantidad 
				 FROM mesa_ayuda_registros 
				 WHERE analista_responsable = $legajo 
					AND id_estado = 10";
			
			$stmt_ = $conn->prepare($sql);
			$stmt_->execute();
							
			$registros = $stmt_->rowCount();

			$datos_ = $stmt_->fetch();
			$cantidad = $datos_["cantidad"];
			
			if($cantidad > 0){
				array_push($arrayData["data"],array("label"=>$denom,"value"=>$cantidad));
			}
		};
		

		
		break;
	};
	case 4:{//tiempo de resolución medio por analista
		
		
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"labelFontColor"=> "#ffffff",
											"caption"=> "",
											"yaxisname"=> "",
											"showvalues"=>0,
											"numberprefix"=>"Dìas ",
											"theme"=> "fusion"),
							"data"=>array());	
		
		$sql ="SELECT * FROM usuarios WHERE analista_cp = 1 AND id_estado = 1";

		$stmt = $conn->prepare($sql);
		$stmt->execute();
						
		$registros = $stmt->rowCount();

		while($datos = $stmt->fetch()){
			$legajo = $datos["legajo"];
			$denom  = $datos["denom"];
			
			$sql="SELECT fechor, editado
				 FROM mesa_ayuda_registros 
				 WHERE analista_responsable = $legajo 
					AND id_estado = 10";
			
			$stmt_ = $conn->prepare($sql);
			$stmt_->execute();
							
			$registros = $stmt_->rowCount();

			if($registros > 0){
				
				$cnt = 0;
				$desde = "";
				$hasta = "";
				$subtotal = 0;
				while($datos_ = $stmt_->fetch()){
					$desde = strtotime($datos_["fechor"]);
					$hasta = strtotime($datos_["editado"]);
					
					$subtotal +=($hasta-$desde);
					
					$cnt = 1;
				}	
				
				if($cnt > 0){
					$dias = ($subtotal/$cnt)/86400;
					
					array_push($arrayData["data"],array("label"=>$denom,"value"=>$dias));
				}
			}	
		}

		break;
	};
	case 5:{//casos registrados por tipo ultimo año movil
		
		$FECHA_ACTUAL = date("Y-m-d");
		$arrayMonth = array();

		for($a=12; $a>=1; $a--){
			//echo($a."<br>");
			$new_date  = date("d-m-Y",strtotime($FECHA_ACTUAL."- $a months"));
			$aux 	   = explode("-",$new_date);
			$desde = $aux[2]."/".$aux[1]."/1";
			
			$hasta = $aux[2]."/".$aux[1]."/".getUltimoDiaMes($aux[2],$aux[1]);
			
			array_push($arrayMonth, array("monthName"=>getAbreMonth($aux[1]),"desde"=>$desde,"hasta"=>$hasta));
		}

		$cnt = count($arrayMonth);
		

		$arrayData = array();
		for($a=0;$a < $cnt;$a++){
			
			$sql =" SELECT ma.abreviatura, COUNT(mar.id) AS cantidad ".
				  "	FROM mesa_ayuda_menu ma ".
				  "	LEFT JOIN mesa_ayuda_registros mar ".
				  "			ON ma.id = mar.id_opcion_principal ".
				  "			 AND mar.fechor BETWEEN '".$arrayMonth[$a]["desde"]."' AND '".$arrayMonth[$a]["hasta"]."'".       
				  "	 GROUP BY ma.id";
			
		
			$stmt = $conn->prepare($sql);
			$stmt->execute();
							
			$registros = $stmt->rowCount();
			$arrayItem = array();
			$arraySubitem = array();
			
			$data = "";
			
			while($datos = $stmt->fetch()){
				
				array_push($arraySubitem, $datos["cantidad"]);
			}
		
			
			
			array_push($arrayData, array("State"=>$arrayMonth[$a]["monthName"],
									"freq"=>(array("Descuentos"=>(int) $arraySubitem[0],
														 "Haberes"=>(int) $arraySubitem[1],
														  "ART"=>(int)  $arraySubitem[2],
														  "EntregaRec"=> (int) $arraySubitem[3],
														  "Licencias"=>(int) $arraySubitem[4], 
														  "Sugerencias"=>(int) $arraySubitem[5], 
														  "Ganancias"=> (int) $arraySubitem[6],
														  "Otras"=> (int) $arraySubitem[7],
														  "Adelanto"=>(int) $arraySubitem[8]))));
			
		}
		
		break;
	};
	case 6:{//tiempo de resolución medio del area


		$sql="SELECT fechor, editado
			 FROM mesa_ayuda_registros 
			 WHERE id_estado = 10";
		
		$stmt_ = $conn->prepare($sql);
		$stmt_->execute();
							
		$registros = $stmt_->rowCount();

		if($registros > 0){
			
			$cnt = 0;
			$desde = "";
			$hasta = "";
			$subtotal = 0;
			while($datos_ = $stmt_->fetch()){
				$desde = strtotime($datos_["fechor"]);
				$hasta = strtotime($datos_["editado"]);
				
				$subtotal +=($hasta-$desde);
				
				$cnt ++;
			}	
			
			if($cnt > 0){
				$dias = (int) (($subtotal/$cnt)/86400);
			}
		}	
	
		$arrayData = array("chart"=>array("bgColor"=> "#DDDDDD",
											"caption"=> "Tiempo Medio de Respuesta",
											"subcaption"=> "",
											"numbersuffix"=>"Días",
											"gaugefillmix"=>"{dark-20},{light+70},{dark-10}",
											"theme"=> "fusion"),
							"colorrange"=>array("color"=>array(
												array("minvalue"=>0,"maxvalue"=>5,"label"=>"","code"=>"#62B58F"),
												array("minvalue"=>5,"maxvalue"=>7.5,"label"=>"","code"=>"#FFC533"),
												array("minvalue"=>7.5,"maxvalue"=>"+10","label"=>"","code"=>"#F2726F"))),
							 "pointers"=>array("pointer"=>array(array("value"=>$dias))));
		
		break;
	};
	case 7:{//novedades registradas
	
		
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"caption"=> "",
											"subcaption"=> "",
											"yaxisname"=>"Unidades",
											"decimals"=> 1,
											"theme"=> "fusion"),
							"data"=>array());	

		$arrayMonth = array();

		for($a=12; $a>=1; $a--){
			//echo($a."<br>");
			$new_date  = date("d-m-Y",strtotime($FECHA_ACTUAL."- $a months"));
			$aux 	   = explode("-",$new_date);
			$desde = $aux[2]."/".$aux[1]."/1";
			
			$hasta = $aux[2]."/".$aux[1]."/".getUltimoDiaMes($aux[2],$aux[1]);
			
			array_push($arrayMonth, array("monthName"=>getAbreMonth($aux[1]),"desde"=>$desde,"hasta"=>$hasta));
		}

		$cnt = count($arrayMonth);
		
		for($a=0; $a < $cnt; $a++){
			
			$sql="SELECT SUM(r.cantidad) AS cantidad
				FROM (SELECT id_tipo, cantidad
						FROM horas
						WHERE id_tipo <= 14 AND periode = '".$arrayMonth[$a]["desde"]."'
						UNION ALL
						SELECT id_tipo, cantidad_aprobada AS cantidad 
						FROM horas_pendientes
						WHERE id_tipo <= 14 AND periode = '".$arrayMonth[$a]["desde"]."') AS r";
			//logg($sql,"");			
			$stmt_ = $conn->prepare($sql);
			$stmt_->execute();
			
			$datos_ = $stmt_->fetch();
			$cantidad = $datos_["cantidad"];
			
			array_push($arrayData["data"],array("label"=>$arrayMonth[$a]["monthName"],
												"value"=>$cantidad,
												"link"=>"void::".$arrayMonth[$a]["monthName"]."::".$arrayMonth[$a]["desde"]));
						
			
		}
		
		
		break;
	};
	case 8:{// novedades registradas por tipo
		
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"caption"=> "",
											"subcaption"=> "",
											"yaxisname"=>"Unidades",
											"decimals"=> 1,
											"theme"=> "fusion"),
							"data"=>array());	

		// $arrayMonth = array();
		// $cnt = 0;
		// for($a=12; $a>=1; $a--){
			// //echo($a."<br>");
			// $new_date  = date("d-m-Y",strtotime($FECHA_ACTUAL."- $a months"));
			// $aux 	   = explode("-",$new_date);
			// $desde = $aux[2]."/".$aux[1]."/1";
			
			// $hasta = $aux[2]."/".$aux[1]."/".getUltimoDiaMes($aux[2],$aux[1]);
			
			// if($cnt == $index){
				// array_push($arrayMonth, array("monthName"=>getAbreMonth($aux[1]),"desde"=>$desde,"hasta"=>$hasta));
			// }
			// $cnt ++;
		// }
		
		
		$sql="SELECT te.denom, SUM(r.cantidad) AS cantidad
				FROM (SELECT id_tipo, cantidad
						FROM horas
						WHERE id_tipo <= 14 AND periode = '".$periodo."'
						UNION ALL
						SELECT id_tipo, cantidad_aprobada AS cantidad 
						FROM horas_pendientes
						WHERE id_tipo <= 14 AND periode = '".$periodo."') AS r
					  INNER JOIN tipo_evento te
						ON r.id_tipo = te.id_tipo
				GROUP BY r.id_tipo";
		//logg($sql,"");
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$data = "";
			
		while($datos = $stmt->fetch()){
	
			array_push($arrayData["data"],array("label"=>capitalLeter($datos["denom"]),
												"value"=>$datos["cantidad"]));

		}
		
		break;
	};
	case 9:{//graficos de casos pendientes por analista
				
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"caption"=> "",
											"subcaption"=> "",
											"showvalues"=> 1,
											"showpercentintooltip"=>0,
											"numberprefix"=>" Unidades ",
											"enablemultislicing"=>1,
											"theme"=> "fusion"),
							"data"=>array());	
		
		$arrayMonth = array();
		$cnt = 0;
		for($a=12; $a>=1; $a--){
			//echo($a."<br>");
			$new_date  = date("d-m-Y",strtotime($FECHA_ACTUAL."- $a months"));
			$aux 	   = explode("-",$new_date);
			$desde = $aux[2]."/".$aux[1]."/1";
			
			$hasta = $aux[2]."/".$aux[1]."/".getUltimoDiaMes($aux[2],$aux[1]);
			
			if($cnt == $index){
				array_push($arrayMonth, array("monthName"=>getAbreMonth($aux[1]),"desde"=>$desde,"hasta"=>$hasta));
			}
			$cnt ++;
		}
		
		
		$sql="SELECT t.denom, t.legajo, SUM(t.cantidad) AS cantidad
				FROM (SELECT u.denom, hp.cantidad_aprobada AS cantidad, u.legajo
						FROM horas_pendientes hp
							INNER JOIN usuarios u
								ON hp.legajo_supervisor = u.legajo
						WHERE hp.id_tipo <= 14 AND  hp.periode = '".$arrayMonth[0]["desde"]."'
						UNION ALL
						SELECT u.denom, h.cantidad , u.legajo
						FROM horas h
							INNER JOIN usuarios u
								ON h.legajo_supervisor = u.legajo
						WHERE h.id_tipo <= 14 AND   h.periode = '".$arrayMonth[0]["desde"]."') t
				GROUP BY t.denom ";
			
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$data = "";
			
		while($datos = $stmt->fetch()){
	
			array_push($arrayData["data"],array("label"=>capitalLeter($datos["denom"]),
												"value"=>$datos["cantidad"], 
												"link"=>"void::".$datos["legajo"]));

		}
		
		break;
	};
	case 10:{//detalle de horas por supervisor
			
		$sql="SELECT t.legajo, t.denom, SUM(t.cantidad) AS cantidad
				FROM (
					SELECT hp.legajo, u.denom, hp.cantidad_aprobada AS cantidad
					FROM horas_pendientes hp
						INNER JOIN usuarios u
							ON hp.legajo = u.legajo
					WHERE hp.periode = '$periodo' AND hp.legajo_supervisor = $legajo_supervisor AND hp.id_tipo <=14
					UNION ALL
					SELECT h.legajo, u.denom, h.cantidad 
					FROM horas h
						INNER JOIN usuarios u
							ON h.legajo= u.legajo AND h.id_tipo <=14
					WHERE h.periode = '$periodo' AND h.legajo_supervisor= $legajo_supervisor) t

				GROUP BY t.legajo
				ORDER BY SUM(t.cantidad) DESC";	
				
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$data = "";
			
		$retorno ="<div style=\"position:absolute;top:0px;left:0px;width:350px;hheight:600px;0border:1px solid red;ooverflow-y:scroll;\">"; 
		$cnt = 0;
		
		$retorno .= "<div  class=\"camposFields\" style=\"background-color:$color;display:inline-block;width:350px;hheight:150px;text-align:vertical;0border:1px solid #D2D0DE\">".
						"<div  style=\"display:inline-block;width:250px;\">Colaborador</div>".
						"<div  style=\"display:inline-block;width:100px;\">Cantidad</div>".
					"</div>";
			$cnt++;
		
		while($datos = $stmt->fetch()){
				
			if($cnt %2 ==0){$color ="#F5D0A9";} else {$color ="#E6E6E6";}	
				
			$retorno .= "<div  class=\"camposFields\" style=\"background-color:$color;display:inline-block;width:350px;hheight:150px;text-align:vertical;0border:1px solid #D2D0DE\">".
							"<div  style=\"display:inline-block;width:250px;\">". capitalLeterNew($datos["denom"])."</div>".
							 "<div  style=\"display:inline-block;width:100px;\">". ($datos["cantidad"])."</div>".
						"</div>";
			$cnt++;
		}
		
		$retorno .= "</div>";
					
		break;
	};
	case 11:{//detalle de horas por grupo de areas
			
				
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"caption"=> "",
											"subcaption"=> "",
											"showvalues"=> 1,
											"showpercentintooltip"=>0,
											"numberprefix"=>" Unidades ",
											"enablemultislicing"=>1,
											"theme"=> "fusion"),
							"data"=>array());	
		
		$arrayMonth = array();
		$cnt = 0;
		for($a=12; $a>=1; $a--){
			//echo($a."<br>");
			$new_date  = date("d-m-Y",strtotime($FECHA_ACTUAL."- $a months"));
			$aux 	   = explode("-",$new_date);
			$desde = $aux[2]."/".$aux[1]."/1";
			
			$hasta = $aux[2]."/".$aux[1]."/".getUltimoDiaMes($aux[2],$aux[1]);
			
			if($cnt == $index){
				array_push($arrayMonth, array("monthName"=>getAbreMonth($aux[1]),"desde"=>$desde,"hasta"=>$hasta));
			}
			$cnt ++;
		}
		
		
		$sql="SELECT t.id, t.denom, SUM(t.cantidad) AS cantidad
				FROM (
					SELECT sg.id, sg.denom, hp.cantidad_aprobada AS cantidad
					FROM horas_pendientes hp
						INNER JOIN usuarios u
							ON hp.legajo = u.legajo
						INNER JOIN sectores s
							ON u.id_sector = s.id_sector
						INNER JOIN sectores_grupos sg
							ON s.id_grupo_sector = sg.id
					WHERE hp.periode = '".$arrayMonth[0]["desde"]."' AND hp.id_tipo <=14
					UNION ALL
					SELECT sg.id, sg.denom, h.cantidad 
					FROM horas h
						INNER JOIN usuarios u
							ON h.legajo= u.legajo AND h.id_tipo <=14
						 INNER JOIN sectores s
							ON u.id_sector = s.id_sector   
					   INNER JOIN sectores_grupos sg
							ON s.id_grupo_sector = sg.id 
					WHERE h.periode = '".$arrayMonth[0]["desde"]."' ) t

				GROUP BY t.id
				ORDER BY SUM(t.cantidad) DESC ";
			
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$data = "";
			
		while($datos = $stmt->fetch()){
	
			array_push($arrayData["data"],array("label"=>capitalLeter($datos["denom"]),
												"value"=>$datos["cantidad"], 
												"link"=>"void::".$datos["legajo"]));

		}
					
		break;
	};
	case 12:{//evolucion de colaboradores
	
		
		$arrayData = array("chart"=>array("bgColor"=> "#202121",
											"valueFontColor"=> "#ffffff",
											"caption"=> "",
											"yaxisname"=> "",
											 "subcaption"=> "",
											"numdivlines"=> "3",
											"showvalues"=> "1",
											"legenditemfontsize"=>"15",
											"legenditemfontbold"=> "1",
											"plottooltext"=> "",
											"xAxisNameAlpha"=>"2",
											"theme"=> "fusion"),
							"categories"=>array(),
							"dataset"=>array());	
		
		
		
		
		$FECHA_ACTUAL = date("Y-m-d");
		$arrayMonth = array();

		for($a=12; $a>=1; $a--){
			//echo($a."<br>");
			$new_date  = date("d-m-Y",strtotime($FECHA_ACTUAL."- $a months"));
			$aux 	   = explode("-",$new_date);
			$desde = $aux[2]."/".$aux[1]."/1";
			
			$hasta = $aux[2]."/".$aux[1]."/".getUltimoDiaMes($aux[2],$aux[1]);
			
			array_push($arrayMonth, array("monthName"=>getAbreMonth($aux[1]),"desde"=>$desde,"hasta"=>$hasta));
		}

		$cnt = count($arrayMonth);
		
		// AGREGAMOS CATEGORIAS
		
		
		
		$arrayCategory = array("category"=>array());
		//$arrayMeses = array();
		
		for($a=0; $a< 12 ; $a++){
			array_push($arrayCategory["category"] ,array("label"=>$arrayMonth[$a]["monthName"]));
		}
		
		
		array_push($arrayData["categories"] ,$arrayCategory);
		
		$sql= "SELECT 0 AS id, 'TODOS' AS denom
				UNION ALL
				SELECT id, denom 
				FROM sectores_grupos
				";
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$arrayColor = array("#FF5733","#E333FF","#FF4933","#F0FF33","#287319","#1D34D9");
		
		$index = 0;			
		while($datos = $stmt->fetch()){
			
			$arraySerieName = array("seriesname"=>$datos["denom"],
									"color"=>$arrayColor[$index],
									
									"data"=>array());
			
			 for($a=0; $a< 12 ; $a++){
				 array_push($arraySerieName["data"] ,array("value"=>getColaboradoresActivos($conn, $datos["id"], $arrayMonth[$a]["desde"], $arrayMonth[$a]["hasta"])));
					
				

		
				 
				 
			 }	
			array_push($arrayData["dataset"], $arraySerieName);
			$index ++;
		}	
		break;
	};
}

	
$retorno = (($action > 0 && $action != 10)   ? json_encode($arrayData) : $retorno);
	
echo $retorno;
?>
