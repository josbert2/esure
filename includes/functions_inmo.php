<?PHP
error_reporting(0);
//header("Content-Type: text/html;  charset=UTF-8", true);
if (session_id() == '') {
	session_start();
}
$urlPath = dirname(__FILE__);
date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_TIME, "spanish");



function getCSSColor($colorSitio)
{
	$retorno = "";

	switch ($colorSitio) {
		case 1: {
				$retorno = "#597db7";
				break;
			};
		case 2: {
				$retorno = "#8f9598";
				break;
			};
		case 3: {
				$retorno = "#dc5c05";
				break;
			};
		case 4: {
				$retorno = "#000000";
				break;
			};
		case 5: {
				$retorno = "#a59d92";
				break;
			};
		case 6: {
				$retorno = "#a41916";
				break;
			};
		case 7: {
				$retorno = "#02b095";
				break;
			};
		case 8: {
				$retorno = "#f3b414";
				break;
			};
	}

	return $retorno;
}
function getCSSFileName($colorSitio)
{
	$retorno = "";

	switch ($colorSitio) {
		case 1: {
				$retorno = "estilos_azul";
				break;
			};
		case 2: {
				$retorno = "estilos_gris";
				break;
			};
		case 3: {
				$retorno = "estilos_naranja";
				break;
			};
		case 4: {
				$retorno = "estilos_negro";
				break;
			};
		case 5: {
				$retorno = "estilos_neutro";
				break;
			};
		case 6: {
				$retorno = "estilos_rojo";
				break;
			};
		case 7: {
				$retorno = "estilos_verde";
				break;
			};
		case 8: {
				$retorno = "estilos_amarillo";
				break;
			};
	}

	return $retorno;
}

function  getImagenesPublicas($conn, $id_propiedad)
{

	$sql = "SELECT * 
		FROM inmo_imagenes 
		WHERE id_propiedad = $id_propiedad
		
		ORDER BY RAND() LIMIT 99";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$registros = $stmt->rowCount();


	if ($registros > 0) {

		$retorno  = "<div id=\"myCarousel\" class=\"carousel slide\" data-ride=\"carousel\" style=\"0border:3px solid red;wwidth:90%;\">";



		$cabecera = "<ol class=\"carousel-indicators\">";
		$imagenes = "<div class=\"carousel-inner\"  style=\";width:100%;text-align:center;margin:auto;\">";

		$cnt = 0;
		while ($datos = $stmt->fetch()) {
			$cabecera .= "<li data-target=\"#myCarousel\" data-slide-to=\"$cnt\"" . ($cnt == 0 ? " class=\"active\"" : "") . "></li>";

			$imagenes .= "<div " . ($cnt == 0 ? " class=\"item active\"" : " class=\"item\"") . ">" .
				"<img src=\" $urlPath/descargar_imagen.php?id_imagen=" . $datos["id"] . "&dataName=$_SESSION[db_name]\" style=\"width:" . ($datos["vertical"] == 1 ? 280 : 500) . "\">" .
				"</div>";


			$cnt++;
		}
		$cabecera .= "</ol>";
		$imagenes .= "</div>";


		$retorno .= $cabecera;
		$retorno .= $imagenes;

		$retorno .= "<a class=\"left carousel-control\" href=\"#myCarousel\" data-slide=\"prev\">
						  <span class=\"glyphicon glyphicon-chevron-left\"></span>
						  <span class=\"sr-only\">Previous</span>
					</a>
					<a class=\"right carousel-control\" href=\"#myCarousel\" data-slide=\"next\">
					  <span class=\"glyphicon glyphicon-chevron-right\"></span>
					  <span class=\"sr-only\">Next</span>
					</a>
				</div>";
	}

	return $retorno;
};


function getPropertiesLayout($conn)
{
	$arrayDestacadas = getPropiedadesDestacadas($conn);

	$arrayRta["cnt_preferenciales"] 	= $arrayDestacadas["cnt_preferenciales"];
	$arrayRta["navs_preferenciales"] 	= $arrayDestacadas["navs_preferenciales"];
	$arrayNoDestacadas = getPropiedadesNoDestacadas($conn);

	$arrayRta["cnt_NO_preferenciales"]  = $arrayNoDestacadas["cnt_NO_preferenciales"];

	return $arrayRta;
};


function getPropiedadesDestacadas($conn)
{
	$arrayRta = array();
	$arrayRta["cnt_preferenciales"] = "";
	$arrayRta["navs_preferenciales"] = "";

	/*
		Mostramos Imagenes destacadas que cumplan con las siguientes condiciones:
		1) La propiedad no tiene que estar eliminada 
		2) Si esta en venta, publicamos siempre salvo que este eliminada
		3) Si esta en alquiler, publicamos solo cuando el estado es disponible
	
	*/

	$sql = " SELECT  0 AS alquilada, ima.id AS id_imagen, loc.denom AS localidad , pro.*
	FROM imno_propiedades pro
		INNER JOIN inmo_imagenes ima
			ON pro.id = ima.id_propiedad
		 INNER JOIN inmo_localidades loc
			ON pro.id_localidad = loc.id
			
	WHERE pro.chk_publicar = 1
		AND ima.destacada = 1 
		AND CASE WHEN (pro.chk_venta  = 1 AND pro.cboEstado !=2) THEN 1 
				 WHEN (pro.chk_venta  = 0 AND pro.cboEstado = 1 )  THEN 1
			END = 1
	
	ORDER BY RAND() LIMIT 100";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$registros = $stmt->rowCount();



	if ($registros > 0) {
		$cnt = 0;
		while ($row = $stmt->fetch()) {

			if ($cnt == 0) {
				$arrayRta["navs_preferenciales"] .= "<span class=\"nav-dot-current\"></span>";
			} else {
				$arrayRta["navs_preferenciales"] .= "<span></span>";
			}
			$moneda 		= ($row["cboMoneda"] == 1 ? "AR$" : "USD");
			$id_imagen 		= $row["id_imagen"];

			$importe_operacion = "ID:" . $row["id"] . " - ";
			$id      = $row["id"];

			$precio_venta = number_format($row["precio_venta"], 0, ',', '.');

			$precio_alquiler = number_format($row["precio_alquiler"], 0, ',', '.');

			if ($row["chk_venta"] == 1 && $row["chk_alquiler"] == 1) {

				if ($row["alquilada"] == 0) {
					$operacion = "Propiedad en Venta  y Alquiler";
					$importe_operacion = "Venta [$moneda " . $precio_venta . "] - Alquiler Desde [ AR$ " . $precio_alquiler . "]";
				} else {
					$operacion = "Propiedad en Venta";
					$importe_operacion = "Venta [$moneda " . $precio_venta . "]";
				}
			} else if ($row["chk_venta"] == 1 && $row["chk_alquiler"] == 0) {
				$operacion = "Propiedad en Venta ";
				$importe_operacion .= "Venta [$moneda " . $precio_venta . "]";
			} else if ($row["chk_venta"] == 0 && $row["chk_alquiler"] == 1) {
				$operacion = "Propiedad en Alquiler ";
				$importe_operacion .= "Alquiler Desde [ AR$ " . $precio_alquiler . "]";
			}
			$cboTipoPropiedad = $row["cboTipoPropiedad"];
			$direccion  	 = $row["calle"] . " " . $row["numero"] . " " . $row["piso"] . " " . $row["depto"] . " - " . $row["localidad"];

			if (($cboTipoPropiedad == 2) ||  ($cboTipoPropiedad == 3) || ($cboTipoPropiedad == 5) || ($cboTipoPropiedad == 6)) {

				$descripcion_01 = $row["dormitorios"] . ($row["dormitorios"] == 1 ? " dormitorio " : " dormitorios ") . $operacion;
			} else {
				$descripcion_01 =  $operacion;
			}

			$comentario		 = $row["comentario_publicacion"];

			$onlick = "window.location.href= './modules_/imno/detalle_publico_propiedad.php?id=$id'";

			$arrayRta["cnt_preferenciales"] .= "<div class=\"sl-slide\" data-orientation=\"horizontal\" data-slice1-rotation=\"-25\" data-slice2-rotation=\"-25\" data-slice1-scale=\"2\" data-slice2-scale=\"2\">" .
				"<div class=\"sl-slide-inner\"  style=\"0border:2px solid orange;\">" .
				//"<img style=\"max-width:900px;\" class=\"bg-img iimg-responsive\" src=\"./modules_/imno/descargar_imagen.php?id_imagen=" . $id_imagen . "&dataName=$_SESSION[db_name]\" alt=\"properties\"/>" .
				//"<img style=\"max-width:900px;\" class=\"bg-img iimg-responsive\" src=\"https://images.pexels.com/photos/6030228/pexels-photo-6030228.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500 alt=\"properties\"/>".
				"<div style=\"background: url(./modules_/imno/descargar_imagen.php?id_imagen=" . $id_imagen . "&dataName=$_SESSION[db_name]);    width: 100%;height: 100%;z-index: 1;position: absolute;background-repeat: no-repeat;background-size: cover;\"></div>".
				"<div class=\"tituloDestacada\" style=\"pposition:relative;0border:2px solid red;margin-left:250px;\" id=\"titlePropiedad\">" .
				"<h2>&nbsp;$descripcion_01</h2>" .
				"<button id=\"divDtitlePropiedad\" type=\"button\" style=\"position:relative;margin-left:3px;width:250px;font-size:12px;\" onclick=\"$onlick\" class=\"ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only\" role=\"button\" aria-disabled=\"false\"><span class=\"ui-button-text\" style=\"background-color:#fff;color:#000;font-weight:bold;\"><span class=\" justify-content-center d-flex id-vivi\">$importe_operacion</span></br><span  class=\"main-color justify-content-center d-flex m-t-5\" style=\"font-size:17px;\">Ver Detalles</span></span>	</button>" .
				"</div>" .
				"</div>" .
				"</div>";

			$cnt++;
		}
	} else { //NO HAY IMAGENES DESTACADAS



		$arrayRta["navs_preferenciales"] .= "<span class=\"nav-dot-current\"></span>";

		$arrayRta["cnt_preferenciales"] .= "<div class=\"sl-slide\" style=\"wwwwidth:600px;0border:2px solid blue;\" data-orientation=\"horizontal\" data-slice1-rotation=\"-25\" data-slice2-rotation=\"-25\" data-slice1-scale=\"2\" data-slice2-scale=\"2\">" .
			"<div class=\"sl-slide-inner\">" .
			"<img sstyle=\"max-width:1368px;\" class=\"img_sc\" src=\"/images/sitio_construccion.jpg\" aqlt=\"properties\"/>" .






			"</div>" .
			"</div>" .
			"</div>";
	}

	return $arrayRta;
};


function getPropiedadesNoDestacadas($conn, $id_localidad = 0, $id_tipo_comercializacion = 0, $id_tipo_propiedad = 0)
{
	$arrayRta = array();
	$arrayRta["cnt_NO_preferenciales"] = "";

	/*
		Mostramos Imagenes destacadas que cumplan con las siguientes condiciones:
		1) La propiedad no tiene que estar eliminada 
		2) Si esta en venta, publicamos siempre salvo que este eliminada
		3) Si esta en alquiler, publicamos solo cuando el estado es disponible
	
	*/

	$sql = " SELECT 0 AS alquilada,  ima.id AS id_imagen, loc.denom AS localidad , pro.*
	FROM imno_propiedades pro
		INNER JOIN inmo_imagenes ima
			ON pro.id = ima.id_propiedad
		 INNER JOIN inmo_localidades loc
			ON pro.id_localidad = loc.id	
		
	WHERE pro.chk_publicar = 1
		AND ima.no_destacada = 1 
		AND CASE WHEN (pro.chk_venta  = 1 AND pro.cboEstado !=2) THEN 1 
				 WHEN (pro.chk_venta  = 0 AND pro.cboEstado = 1 )  THEN 1
			END = 1";

	$sql .= ($id_localidad > 0 ? " AND loc.id = $id_localidad" : "");
	$sql .= ($id_tipo_propiedad > 0 ? " AND pro.cboTipoPropiedad = $id_tipo_propiedad" : "");

	if ($id_tipo_comercializacion == 1) {
		$sql .=  " AND pro.chk_alquiler = 1";
	} else if ($id_tipo_comercializacion == 2) {
		$sql .=  " AND pro.chk_alquiler = 2";
	};


	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$registros = $stmt->rowCount();
	if ($registros > 0) {
		$cnt = 0;


		while ($row = $stmt->fetch()) {

			$id_propiedad 	= $row["id"];
			$moneda 		= ($row["cboMoneda"] == 1 ? "AR$" : "USD");
			$id_imagen 		= $row["id_imagen"];
			if ($row["chk_venta"] == 1 && $row["chk_alquiler"] == 1) {

				if ($row["alquilada"] == 0) {
					$importe_operacion = "Venta [$moneda " . $precio_venta . "] - Alquiler Desde [ AR$ " . $precio_alquiler . "]";
				} else {
					$importe_operacion = "Venta [$moneda " . $precio_venta . "]";
				}
				// Si la propiedad está ALQUILADA solo mostramos la OPCION EN VENTA




			} else if ($row["chk_venta"] == 1 && $row["chk_alquiler"] == 0) {
				$operacion = "Propiedad en Venta ";
				$importe_operacion = "Venta [$moneda " . $row["precio_venta"] . "]";
			} else {
				$operacion = "Propiedad en Aquiler ";
				$importe_operacion = "Alquiler Desde [ AR$ " . $row["precio_alquiler"] . "]";
			}

			$direccion  	= $row["calle"] . " " . $row["numero"] . " " . $row["piso"] . " " . $row["depto"] . " - " . $row["localidad"];
			$descripcion_01 = $row["dormitorios"] . " dormitorios -  $operacion";
			$comentario		 = $row["comentario"];
			$arrayRta["cnt_NO_preferenciales"] .= "<div class=\"owl-item\" style=\"swidth: 240px;\">" .
				"<div class=\"properties\">" .
				"<div class=\"image-holder\">" .
				"<img src=\"$urlPath/descargar_imagen.php?id_imagen=" . $id_imagen . "\" class=\"img-responsive\" alt=\"properties\">" .
				"<div class=\"status sold\">ID: $id_propiedad " . $operacion . "</div>" .
				"</div>" .
				"<h4>" .
				"<a target=\"_blank\" href=\"http://localhost/esure-main/modules_/imno/detalle_publico_propiedad.php?id=$id_propiedad\">" . $direccion . "</a>" .
				"</h4>" .
				"<p class=\"price\">" . $importe_operacion . "</p>" .
				"<a class=\"btn btn-esecure\" target=\"_blank\"  href=\"http://localhost/esure-main/modules_/imno/detalle_publico_propiedad.php?id=$id_propiedad\">Ver Detalles</a>" .
				"</div>" .
				"</div>";

			$cnt++;
		}
	} else {
		$arrayRta["cnt_NO_preferenciales"] .= "<div class=\"owl-item\" style=\"width: 240px;\">" .
			"<div class=\"properties\">" .
			"<div class=\"image-holder\">" .
			"</div>" .
			"</div>" .
			"</div>";
	}

	return $arrayRta;
};


function getFiltroPropiedades($conn, $id_localidad = 0, $id_tipo_comercializacion = 0, $id_tipo_propiedad = 0,  $precio_desde = 0, $precio_hasta = 0, $superficie = 0, $cboHabilitaciones = 0)
{

	$arrayRta = array();

	$arrayRta["cnt_NO_preferenciales"] = "";



	$sql = " SELECT  0 AS alquilada, loc.denom AS localidad , pro.chk_alquiler, pro.chk_venta,
	pro.id, pro.cboMoneda, pro.precio_venta, pro.precio_alquiler, pro.calle, pro.numero, pro.piso,
	pro.depto, pro.dormitorios, pro.comentario, pro.comentario_publicacion
	FROM imno_propiedades pro
		 INNER JOIN inmo_localidades loc
			ON pro.id_localidad = loc.id	
		
	WHERE pro.chk_publicar = 1
		AND (pro.cboEstado = 1 || pro.chk_venta = 1)";

	$sql .= ($id_localidad > 0 ? " AND loc.id = $id_localidad" : "");
	$sql .= ($id_tipo_propiedad > 0 ? " AND pro.cboTipoPropiedad = $id_tipo_propiedad" : "");
	$sql .= ($superficie > 0 ? " AND pro.superficie >= $superficie" : "");
	$sql .= ($cboHabilitaciones > 0 ? " AND pro.dormitorios = $cboHabilitaciones" : "");

	if ($id_tipo_comercializacion == 1) {
		$sql .=  " AND pro.chk_alquiler = 1";
		$sql .= ($precio_desde > 0 ? " AND pro.precio_alquiler >= $precio_desde" : "");
		$sql .= ($precio_hasta > 0 ? " AND pro.precio_alquiler <= $precio_hasta" : "");
	} else if ($id_tipo_comercializacion == 2) {
		$sql .=  " AND pro.chk_venta = 1";
		$sql .= ($precio_desde > 0 ? " AND pro.precio_venta >= $precio_desde" : "");
		$sql .= ($precio_hasta > 0 ? " AND pro.precio_venta <= $precio_hasta" : "");
	};

	//logg("filtro $sql","");	

	$arrayRta = array();
	$arrayRta["cnt_NO_preferenciales"] = "";


	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$registros = $stmt->rowCount();

	if ($registros > 0) {
		$cnt = 0;


		while ($row = $stmt->fetch()) {
			$id_propiedad 	= $row["id"];
			$moneda 		= ($row["cboMoneda"] == 1 ? "AR$" : "USD");
			$id_imagen 		= getImagenPropiedad($conn, $row["id"]);
			if ($id_imagen > 0) {

				$precio_venta = number_format($row["precio_venta"], 0, ',', '.');

				$precio_alquiler = number_format($row["precio_alquiler"], 0, ',', '.');

				if ($row["chk_venta"] == 1 && $row["chk_alquiler"] == 1) {

					if ($row["alquilada"] == 0) {
						$operacion = " Venta y Alquiler";
						$importe_operacion = "Venta [$moneda " . $precio_venta . "] - Alquiler Desde [ AR$ " . $precio_alquiler . "]";
					} else {
						$operacion = " en Venta ";
						$importe_operacion = "Venta [$moneda " . $precio_venta . "]";
					}
					// Si la propiedad está ALQUILADA solo mostramos la OPCION EN VENTA


				} else if ($row["chk_venta"] == 1 && $row["chk_alquiler"] == 0) {
					$operacion = " en Venta ";
					$importe_operacion = "Venta [$moneda " . $precio_venta . "]";
				} else {
					$operacion = " en Aquiler ";
					$importe_operacion = "Alquiler Desde [ AR$ " . $precio_alquiler . "]";
				}

				$direccion  	= $row["calle"] . " " . $row["numero"] . " " . $row["piso"] . " " . $row["depto"] . " - " . $row["localidad"];
				$descripcion_01 = $row["dormitorios"] . " dormitorios -  $operacion";
				$comentario		 = $row["comentario"];
				$urlPublica  	 = "$_SESSION[INMO_URL]$urlPath/modules_/imno/detalle_publico_propiedad.php?id=$row[id]";

				$comentario_publicacion = rtrim(addslashes($row["comentario_publicacion"]));

				$comentario_publicacion = preg_replace("[\n|\r|\n\r]", "", $comentario_publicacion);


				$pagina = '<html>
								<title></title>
								<script type=\"text/javascript\"  charset=\"UTF-8\"></script>
								<head>
									<meta property="og:url" content="https://inmobiliarianocetti.com.ar/share-fb.php" />
									<meta property="og:type"  content="website" />

									<meta property="og:title" content="' . $comentario_publicacion . '" />
									<meta property="og:description" content=""/>';

				$sql = "SELECT * FROM inmo_imagenes WHERE id_propiedad = $row[id] LIMIT 1";
				$stmtIMA = $conn->prepare($sql);
				$stmtIMA->execute();
				$registros = $stmtIMA->rowCount();

				if ($registros > 0) {



					while ($rowIMA = $stmtIMA->fetch()) {
						$pagina  .= '<meta property="og:image" content="http://www.lostiemposcambian.com/blog/posts/share-fb/img/100x100_lostiemposcambian_ejemplo_facebook_foto1.jpg"/>';
					}
				}

				$pagina .= '</head>
								<body>
								</body>
							</html>';

				$archivo = $_SERVER['DOCUMENT_ROOT'] . "/share-fb.php";
				//echo($archivo);

				if (file_exists($archivo)) {


					$file = fopen($archivo, "w+");
					fwrite($file, $pagina);
				}


				$url  = "$_SESSION[INMO_URL]$urlPath/modules_/imno/detalle_publico_propiedad.php?id=$row[id]";
				//$arrayImg2 = array();
				//$sql2 ="SELECT id 
				//FROM inmo_imagenes
				//WHERE id_propiedad = 68 ";

				//$stmt2 = $conn->prepare($sql2);
				//$stmt2->execute();


				//if($registros == 1){
				//	$row 			 = $stmt->fetch();
				//	$id_imagen  = $row["id"];
				//}

				//while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
				//	$arrayImg2 = $row2['id'];
				//echo json_encode($arrayImg);
				//}


				// json_encode($arrayImg2);

				$arrayRta["cnt_NO_preferenciales"] .= "<div class=\"owl-item\" data-id=\". $row[id] .\" style=\"Wwidth:240px;hheight:370px;padding-left:5px;;padding-top:5px;0border:3px solid red;\">
															<div class=\"properties\">" .
					"<div class=\"img-container\">".
					"<div class=\"image-holder slick-img\">";
				foreach ($id_imagen as $array => $id) {
					$arrayRta["cnt_NO_preferenciales"] .= "<div><img data-aqui src=\"./modules_/imno/descargar_imagen.php?id_imagen=" . $id["id"] . "&dataName=esure\" class=\"img-responsive\" style=\"max-height:250px;min-width:331px\" alt=\"properties\"/></div>";
				}

				//"<img src=\"https://images.pexels.com/photos/5539157/pexels-photo-5539157.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260\" class=\"img-responsive\" style=\"max-height:250px;min-width:331px\" alt=\"properties\"/>".
				$arrayRta["cnt_NO_preferenciales"] .= "" .
					"</div>" .
					"<div class=\"status sold\">ID: $id_propiedad " . $operacion . "</div>".
					"</div>".
					"<a target=\"_blank\" href=\"http://localhost/esure-main/modules_/imno/detalle_publico_propiedad.php?id=$id_propiedad\"><span class=\"m-t-10 d-flex text-left\" style=\"font-size:12px; margin-bottom: 10px;margin-left:10px;\">" . $direccion . "<span></a>" .
					"<div class=\"text-left price\" style=\"margin-left:10px;height:50px; 0border:1px solid blue;\">" .
					$importe_operacion .
					"</div>" .
					"<div class=\"d-flex content-padding\" style=\"border-top: 1px solid #eee;\">" .
					"<div class=\"d-flex align-items-center m-r-auto\">" .
					//JOS
					//"<img class=\"m-r-10\"   title=\"Compartir por Wathsapp\" style=\"display:inline;width:40px;hheight:300px; text-align:left\" src=\"/icon/whatsapp.png\" onclick=\"window.open('https://api.whatsapp.com/send?text=".$comentario_publicacion."&nbsp;$url','_blank')\"></img>".
					//"<img   title=\"Compartir por Facebook\" style=\"display:inline;width:25px;hheight:300px; text-align:left\" src=\"/icon/facebook.jpg\" onclick=\"compartirFacebook(".$row["id"].")\"></img>".

					"<a class=\"m-r-15\"  title=\"Compartir por Facebook\" href=\"#\" onclick=\"compartirFacebook(" . $row["id"] . ")\"><svg height='20pt' viewBox='0 0 512 512' width='20pt' xmlns='http://www.w3.org/2000/svg'><path d='m483.738281 0h-455.5c-15.597656.0078125-28.24218725 12.660156-28.238281 28.261719v455.5c.0078125 15.597656 12.660156 28.242187 28.261719 28.238281h455.476562c15.605469.003906 28.257813-12.644531 28.261719-28.25 0-.003906 0-.007812 0-.011719v-455.5c-.007812-15.597656-12.660156-28.24218725-28.261719-28.238281zm0 0' fill='#4267b2'/><path d='m353.5 512v-198h66.75l10-77.5h-76.75v-49.359375c0-22.386719 6.214844-37.640625 38.316406-37.640625h40.683594v-69.128906c-7.078125-.941406-31.363281-3.046875-59.621094-3.046875-59 0-99.378906 36-99.378906 102.140625v57.035156h-66.5v77.5h66.5v198zm0 0' fill='#fff'/></svg></a>" .
					"<a onclick=\"window.open('https://api.whatsapp.com/send?text=" . $comentario_publicacion . "&nbsp;$url','_blank')\"  title=\"Compartir por Wathsapp\" href=\"#\" onclick=\"compartirFacebook(" . $row["id"] . ")\"><?xml version='1.0' encoding='iso-8859-1'?><svg version='1.1' style=\"width:30px;height:30px\" id='Layer_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 512 512' style='enable-background:new 0 0 512 512;' xml:space='preserve'><path style='fill:#EDEDED;' d='M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z'/><path style='fill:#55CD6C;' d='M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z'/><path style='fill:#FEFEFE;' d='M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945'/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a>" .
					"</div>" .
					"<a class=\"m-l-auto btn btn-esecure\" target=\"_blank\"  href=\"./modules_/imno/detalle_publico_propiedad.php?id=$id_propiedad\"><span style=\"font-size:12px;\">Ver Detalles <span></a>" .

					"</div>" .
					"</div>" .
					"</div>";
				// "<img   title=\"Compartir por Facebook\" style=\"display:inline;width:25px;hheight:300px; text-align:left\" src=\"/icon/facebook.jpg\" onclick=\"window.open('https://www.facebook.com/sharer/sharer.php?u=$url&quote=$comentario_publicacion','facebook-popup')\"></img>".



				$cnt++;
			}
		}
	}

	return $arrayRta;
};
function arrayIMG($conn, $id)
{
	$id_imagen = 0;


	$sql = "SELECT id 
	FROM inmo_imagenes
	WHERE id_propiedad = $id ";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$registros = $stmt->rowCount();
	//if($registros == 1){
	//	$row 			 = $stmt->fetch();
	//	$id_imagen  = $row["id"];
	//}

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$arrayImg = $row['id'];
		//echo json_encode($arrayImg);
	}

	return json_encode($arrayImg);
};

function getImagenPropiedad($conn, $id)
{

	$id_imagen = 0;

	$sql = "SELECT id 
		FROM inmo_imagenes
		WHERE id_propiedad = $id";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$registros = $stmt->rowCount();
	if ($registros >= 1) {
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$id_imagen  = $rows;
	} else {
		$id_imagen = [["id" => "jeje"]];
	}


	return $id_imagen;
};
