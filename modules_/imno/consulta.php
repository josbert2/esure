<?php header('Content-Type: text/html; charset=utf8'); ?>
<?php 


session_start();
//$_SERVER["DOCUMENT_ROOT"]
//include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
$rr = "localhost/devs/proyectos/esecure_new";


require '../../includes/conexion_mysqli.php';
require '../../includes/functions.php';
require '../../includes/functions_inmo.php';
require '../../includes/constantes.php';
require '../../includes/class.php';





date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_TIME, "spanish"); //Fijamos el tiempo local

$uuid_ = new uuid_();	
	
$action 			= isSet($_POST["action"]) ? $_POST["action"] : 0;
$id 				= isSet($_POST["id"]) ? $_POST["id"] : 0;


$id_propiedad		 = isSet($_POST["id_propiedad"]) ? $_POST["id_propiedad"] : 0;
$id_contrato		 = isSet($_POST["id_contrato"]) ? $_POST["id_contrato"] : 0;
$id_provincia		 = isSet($_POST["id_provincia"]) ? $_POST["id_provincia"] : 0;
$id_localidad		 = isSet($_POST["id_localidad"]) ? $_POST["id_localidad"] : 0;
$id_propietario		 = isSet($_POST["id_propietario"]) ? $_POST["id_propietario"] : 0;
$id_tipo_propiedad	= isSet($_POST["id_tipo_propiedad"]) ? $_POST["id_tipo_propiedad"] : 0;
$id_tipo_operacion	= isSet($_POST["id_tipo_operacion"]) ? $_POST["id_tipo_operacion"] : 0;

$id_moneda			= isSet($_POST["id_moneda"]) ? $_POST["id_moneda"] : 0;
$precio_desde		= isSet($_POST["precio_desde"]) ? $_POST["precio_desde"] : 0;
$precio_hasta		= isSet($_POST["precio_hasta"]) ? $_POST["precio_hasta"] : 0;

$dormitorios		= isSet($_POST["dormitorios"]) ? $_POST["dormitorios"] : 0;
$id_barrio			= isSet($_POST["id_barrio"]) ? $_POST["id_barrio"] : 0;

$calle				 = isSet($_POST["calle"]) ? $_POST["calle"] : "";

$id_tipo_ingreso 	= isSet($_POST["id_tipo_ingreso"]) ? $_POST["id_tipo_ingreso"] : 0;
$editable 			= isSet($_POST["editable"]) ? $_POST["editable"] : 0;
$principal 			= isSet($_POST["principal"]) ? $_POST["principal"] : 0;
$verTodasCuotas 	= isSet($_POST["verTodasCuotas"]) ? $_POST["verTodasCuotas"] : 0;

$id_estado 			= isSet($_POST["id_estado"]) ? $_POST["id_estado"] : 0;
$id_cuota 			= isSet($_POST["id_cuota"]) ? $_POST["id_cuota"] : 0;
$eliminar_imagen 	= isSet($_POST["eliminar_imagen"]) ? $_POST["eliminar_imagen"] : 0;
$denom 				= isSet($_POST["denom"]) ? $_POST["denom"] : "";
$id_tipo 			= isSet($_POST["id_tipo"]) ? $_POST["id_tipo"] : "";

 
$date_from 			= isSet($_POST["date_from"]) ? getEnglishDate($_POST["date_from"]) : ""; 
$date_to 			= isSet($_POST["date_to"]) ? getEnglishDate($_POST["date_to"]) : ""; 

$cboTipoPropiedad 	= isSet($_POST["cboTipoPropiedad"]) ? $_POST["cboTipoPropiedad"] : 0;
$cboTipoOperacion 	= isSet($_POST["cboTipoOperacion"]) ? $_POST["cboTipoOperacion"] : 0;
$id_localidad 		= isSet($_POST["id_localidad"]) ? $_POST["id_localidad"] : 0;
$id_concepto 		= isSet($_POST["id_concepto"]) ? $_POST["id_concepto"] : 0;

$id_gasto_adicional = isSet($_POST["id_gasto_adicional"]) ? $_POST["id_gasto_adicional"] : 0;
 
$person_id = isSet($_POST["person_id"]) ? $_POST["person_id"] :"";

$FECHA_ACTUAL   = date("Y-m-d");

   
   $retorno ="";	
   switch($action){
	
		case 38:{//buscamos propiedades NO DESTACADAS
	
			$precio_desde		= isSet($_POST["precio_desde"]) ? $_POST["precio_desde"] : 0;
			$precio_hasta		= isSet($_POST["precio_hasta"]) ? $_POST["precio_hasta"] : 0;
			$superficie		 	= isSet($_POST["superficie"]) ? $_POST["superficie"] : 0;
			$cboHabilitaciones	= isSet($_POST["cboHabilitaciones"]) ? $_POST["cboHabilitaciones"] : 0;

			$arraData = getFiltroPropiedades($conn, $id_localidad, $cboTipoOperacion, $cboTipoPropiedad, $precio_desde, $precio_hasta, $superficie, $cboHabilitaciones);
			
			$retorno = $arraData["cnt_NO_preferenciales"]; 
			
			break;
		};
		
	}
echo $retorno;

?>