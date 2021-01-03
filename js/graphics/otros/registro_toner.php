<?php 
include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');

include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/control_sesion.php');
 
include($_SERVER["DOCUMENT_ROOT"] .'/subsistemas/minidest/registros_xml_to_database.php');

header("Cache-Control: no-cache, must-revalidate"); // Evitar guardado en cache del cliente HTTP/1.1

date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_ALL,"es_ES");
setlocale(LC_TIME, "spanish"); //Fijamos el tiempo local

$date 			= date("Y-m-d");
$arrayAux 		= explode("-",$date);
$day 			= $arrayAux[2];
	$day 		= ($day < 10 ? $day : $day);	
$month 			= $arrayAux[1];
	$month 		= ($month < 10 ? $month : $month);
$year			= $arrayAux[0];

$spanish_date  = $day."/".$month."/".$year;

$id_interfaz   = $_GET["id"];
$id_stack      = $_GET["id_stack"];
?>
	
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<meta name="description" content="" />	
<meta charset="utf-8">
	
<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/xmlUtil.js"></script>
<script type="text/javascript" src="/js/_accordion.js"></script>
<script type="text/javascript" src="/js/_functions.js"></script>

<link  type="text/css" rel="stylesheet"	media="all" href="/css/ui-lightness/jquery-ui-1.10.3.custom.css"></link>
<link  type="text/css" rel="stylesheet" media="all" href="/css/styles.css"></link>

<link type="image/x-icon" href="/icon/porta_icon.png" rel="icon" />

<title>Porta - Registro de Toner</title> 

<style>
	html, body {
	height: 100%;
	margin: 0;
	padding: 0;
	}
	#map {
	height: 100%;
	}

	input[type="text"] {
	width: 100px;
	height: 25px;
	background-color: #FFFFFF;
	font-size:12px
	}

	ul.ui-autocomplete {
	z-index: 1100;
	}

	::-webkit-scrollbar {
	display: none;
	}

</style>

<script language="javascript" type="text/javascript">



var objData = {};
	objData.registro_seleccionado = 0;
	
function actualizarDatosCombo(){
	var id_toner = getComboValue("cboToner");
	console.info("id_toner "+id_toner)
	
	 jQuery.ajax({
		async: false,
		url: '/subsistemas/otros/consulta.php',
		type: 'post',
		data: {action:5, id_toner}
	}).done(
		function (resp) {
			var respuesta = resp.trim();
			var aux = respuesta.split("::");
			
			if(aux[0] =="OK"){
				$("#precio").val(aux[1]);	
				$("#esperado").val(aux[2]);
				
			} else {
				 setMessage(respuesta);
				
			}
		}
	);	
};
	
function limpiarCampos(){
	$("#solicitante").val("");
	$("#contador").val("");
	$("#comentario").val("");	
};

function verificarAnterGrabar(){
	
	var action = 0;
	var respuesta = {};
		respuesta.status = true;
		respuesta.message = "";
		respuesta.focus = "";
		respuesta.active_tab = "";
		
		
	var spanish_date = '<?php echo($spanish_date); ?>';
	
	//--Chequeamos campos comunes
	
	respuesta.fecha = $("#fecha").val();
	if(!isDate(respuesta.fecha)){
		respuesta.status 	 = false;
		respuesta.message 	 = "Por favor ingrese una fecha válida";
		respuesta.focus 	 = "fecha";
		respuesta.active_tab = 0; 
	} 
	
	var solicitante = $("#solicitante").val();
	var aux = solicitante.split("-");
	
	respuesta.legajo = parseInt(aux[1],10);
	if(isNaN(respuesta.legajo) || respuesta.legajo <= 0){
		respuesta.status 	 = false;
		respuesta.message 	 = "Por favor ingrese el nombre del colaborador";
		respuesta.focus 	 = "solicitante";
		respuesta.active_tab = 0; 
	}
	
	respuesta.contador = parseInt($("#contador").val(),10);
	if(isNaN(respuesta.contador) || respuesta.contador < 0){
		respuesta.status 	 = false;
		respuesta.message 	 = "Por favor ingrese la cantidad de copias impresas";
		respuesta.focus 	 = "contador";
		respuesta.active_tab = 0; 
	}
	
	
	respuesta.comentario = $("#comentario").val();
	
	//--FIN Chequeamos campos comunes


	
	return respuesta;
};		
	
	
function saveData() {
	
	var ojbRespuesta = verificarAnterGrabar();
	
	
	if(!ojbRespuesta.status){
		setMessage(ojbRespuesta.message);
		$("#"+ojbRespuesta.focus).focus();
		return;
	}
	ojbRespuesta.action    = 3;
	ojbRespuesta.id 	   = objData.registro_seleccionado;
	ojbRespuesta.id_toner  = getComboValue("cboToner");
	ojbRespuesta.esperado  = $("#esperado").val();
	ojbRespuesta.precio    = $("#precio").val();
	
    jQuery.ajax({
		async: false,
		url: '/subsistemas/otros/saveData.php',
		type: 'post',
		data: ojbRespuesta
	}).done(
		function (resp) {
			var respuesta = resp.trim();
		
			if(respuesta =="OK"){
				setMessage("Registro Grabado Correctamente.");
				$("#form_activity").dialog( "close" );
				limpiarCampos();
				loadData();
			} else {
				setMessage(respuesta);
			}
		}
	);
};
	 
function agregar(){
	
	limpiarCampos();
	
	objData.registro_seleccionado = 0;
	
	$("#form_activity").dialog({
	  resizable: false,
	  width:530,
	  height:370,
	  title:"Edición de Registro",
	  modal: true,
	  buttons: {
		Grabar: function(){
		  saveData();
		},
		Cerrar: function(){
		  $( this ).dialog( "close" );
		}
	  }
	});		
	
	actualizarDatosCombo();	
};

function editarRegistro (id){
	
	objData.registro_seleccionado = id;
	
	$("#form_activity").dialog({
	  resizable: false,
	  width:530,
	  height:370,
	  title:"Edición de Registro",
	  modal: true,
	  buttons: {
		Grabar: function(){
		  saveData();
		},
		Cerrar: function(){
		  $( this ).dialog( "close" );
		}
	  }
	});	
	
	buscarRegistro();
};



function buscarRegistro(){
	
	jQuery.ajax({
		async: false,
		url: '/subsistemas/otros/consulta.php',
		type: 'post',
		data: {action:4, id:objData.registro_seleccionado}
	}).done(
		function (resp) {
			var respuesta = resp.trim();
		  		
				
			if(eval(respuesta)){
				var arrayData = eval(respuesta);	
				
				$("#solicitante").val(arrayData[0].solicitante);
				$("#esperado").val(arrayData[0].esperado);
				$("#precio").val(arrayData[0].precio);
				$("#contador").val(arrayData[0].contador);
				$("#comentario").val(arrayData[0].comentario);
				$("#fecha").val(getSpanishDate(arrayData[0].fecha));
				
				posicionarEnId("cboToner", arrayData[0].id_toner);
				
			};	
		})
};	


function cancelar(){
	
	if(objData.action != 12){
		setMessage("Debe seleccionar una acción.");
		return;
	}
	
	if(objData.registro_seleccionado > 0){
		
		var mensaje ="¿Esta seguro que desea cancelar los cambios en progreso?";
		
		if(!confirm(mensaje)){
			return false;
		}
		
		clearCheck_reclamo(objData.registro_seleccionado);
		objData.action = 0;
		disabledEnabled("formData",true);		
	}
};

function init(page){
	var ventana_ancho = $(window).width();
	var ventana_alto = $(window).height()-100;
		
   $("body").bind('mousewheel DOMMouseScroll', function() {
	  return false
	});
	
	
	$("#td_01").css("width",160+"px");
	$("#td_02").css("width","0px");
	$("#td_03").css("width","0px");
	$("#td_04").css("width",(ventana_ancho - 320)+"px");
	$("#td_05").css("width",160+"px");
	
	
	
	var capa ='<div id="capa" style="position:absolute;left:0px;top:0px;'+
		' width:100%;'+
		' height:100%;'+
		' background-color:blue;'+
		' z-index:5000;'+
		' filter:alpha(opacity=30);'+
		' -moz-opacity:.70;'+
		' opacity:.70">'+
		' <img src="/images/chrome.png" id="imgCapa" style="z-index:7000"/>'+
	' </div>';
	
	 var navegador = new String(navigator.userAgent);
	 
	 if(navegador.match(/Firefox/) == null && navegador.match(/Chrome/)  == null){
		
		$("#cabecera").html(capa);
		
		$("#capa").css("width",ventana_ancho+"px");
		$("#capa").css("height",ventana_alto+100+"px");
		$("#imgCapa").css("width",ventana_ancho+"px");
		$("#imgCapa").css("height",ventana_alto+100+"px");
	 }
};

function loadData(){
	
	var datos = {};
	datos.action	  = 3;
	/*datos.id_unineg	  = getComboValue("cboUnineg");
	
	datos.date_from   = $("#date_from").val();
	datos.date_to     = $("#date_to").val();;
	datos.alto_grilla = objData.alto_grilla;
	datos.id_estado   = ($("#chk_verRegistros").is(":checked") ? "1,2" : "1,2,3");
	*/
	
   jQuery.ajax({
        async: false,
        url: '/subsistemas/otros/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			var respuesta = resp.trim();
			var aux = respuesta.split("::");
			$("#cnt_registros").html(aux[1]);			
			
	    }
    );
	
	//verConsumos();
};

$(document).ready(function () {
	init();
    
	id_interfaz ='<?php echo($id_interfaz);?>';
	var id_stack 	=parseInt('<?php echo($id_stack);?>',10);
	
	removeClassName(id_interfaz);   
    
	$("#tabs_datos").tabs({collapsible: false });
	
    var ventana_alto = $(window).height();
	
	var altoSinCabecera = ventana_alto -80;
	var altoTablasPersonas = (altoSinCabecera - 50)/2;
	
	ventana_ancho = $(window).width();
	ventana_alto = $(window).height() - 100;
	
	
	
		
	$("#center").css("left", "10px");
	$("#center").css("width", (ventana_ancho - 610) + "px");

	
	$("#left").css("height", ventana_alto + "px");
	$("#center").css("height", ventana_alto + "px");

	$("#body").css("height", ventana_alto + "px");
	
	var altoGrillaDatos = (ventana_alto - 75);
	var topGrillaDatos = parseInt($("#cntReclamos").css("top"),10);
	
	//--Altura disponible para tabdatos
	ventana_alto = ventana_alto -25;//filtro pagina
	
	
	$("#tabs_datos").css("width","1100px");
	
	$("#tabs_datos").css("height", ventana_alto+"px");
	
	
	
	objData.alto_grilla =(ventana_alto-100) ;
	$("#cnt_registros").css("height", objData.alto_grilla +"px");
	$("#fecha").datepicker({dateFormat: "mm/yy"});

	

	$("body").bind('mousewheel DOMMouseScroll', function (){return false});
	
	loadData();
	
	$( "#solicitante" ).autocomplete({
		source:"consultaAutoComplete.php?action=4",
	})
	
	//editarTarea();
	//setMessage("ojbRespuesta.message");
});

</script>

</head>
<body style="overflow:hidden;background-color:#F8ECE0">
	
	<?php include($_SERVER["DOCUMENT_ROOT"] .'/cabecera.php'); ?>
	
	<form name="formGenerico" id="formGenerico" action="" method="post"   onsubmit="return false">	</form>		
	
	
	<div id="form_activity" style="display:none;width: auto; min-height: 0px; max-height: none; height: 307px;" class="ui-dialog-content ui-widget-content">
		<table cellpadding="0" cellspacing="2" border="0" style=";width:100%;">
			<tbody>
				<tr>
					<td colspan="2" style="font-weight:bold;width:150px;border-bottom: 1px solid black;" class="textos">Información del Registro</td>
				</tr>
				<tr>
					<td class="textos">Solicitante</td>
					<td><input type="text" class="auto" name="solicitante" id="solicitante" style="width:290px;display:inline;text-align:left"></td>
				</tr>
				<tr>
					<td class="textos">Toner</td>
					<td>
						<?php echo(getComboFromTable("toner",$conn,"cboToner", false, false, false, true, false, false, false, 290)); ?>
					</td>
				</tr>
				<tr>
					<td class="textos">Fecha</td>
					<td><input type="text"  name="fecha" id="fecha"  style="width:100px;display:inline;text-align:right;font-size:12px;"></td>
				</tr>
				<tr>
					<td class="textos">Precio Unitario</td>
					<td><input type="number" disabled="" name="precio" id="precio"  style="width:100px;display:inline;text-align:right"></td>
				</tr>
				<tr>
					<td class="textos">Valor Esperado</td>
					<td><input type="number" disabled="" name="esperado" id="esperado"  style="width:100px;display:inline;text-align:right"></td>
				</tr>
				<tr>
					<td class="textos">Contador</td>
					<td><input type="number" step="100" name="contador" id="contador"  style="width:100px;display:inline;;text-align:right"></td>
				</tr>
				<tr>
					<td class="textos" colspan="2">
						<textarea style="font-size:13px;width:500px" id="comentario" name="comentario" placeholder="Ingrese un Comentario" maxlength="600" value="" rows="5" cols="40"></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div id="form_document" title="" style="display:none;;">	
	</div>
	
	<div id="form_periode" title="" style="display:none;;">	
		<table cellpadding="0" cellspacing="0" border="0" style=";wwidth:660px;hheight:50px;">
			
			<tr>
				<td sstyle="width:150px;" class="labelMessageNew" >Seleccione periodo</td>
				<td sstyle="width:500px;" class="labelMessageNew" >
					<input type="text" name="date_periode" id="date_periode" style="width:70px;display:inline;" ></input>
				</td>
			</tr>	
			
		</table>
	</div>
	
	
	<div id="main"  style="position:absolute;left:0px;top:105px;border:1px solid #6E6E6E;width:100%;height:100%;">
		<div id="left"  style="position:absolute;left:0px;top:0px;0border:1px solid #6E6E6E;width:250px;height:100%">
			<?PHP include($_SERVER["DOCUMENT_ROOT"] .'/accordion.php'); ?>
		</div>
		
		<div id="center"  style="position:absolute;left:250px;right:250px;top:0px;0border:3px solid #6E6E6E;width:100%;height:100%">
			
			
			<div class="textos" style="position:absolute;left:0px;top:0px;width:1215px;height:128px;0border:3px solid #F5D0A9;overflow-x: hidden;overflow-y: auto">
				
			
			</div>
			
			
				<form name="formData" id="formData" action="" method="post"   onsubmit="return false">	
					<div id="tabs_datos" class="textTabs" style="position:absolute;left:0px;top:0px;display:inline-block;width:350px;0border:2px solid orange;overflow-y:visible;">
						<ul>
							<li><a href="#tabs-00" class="textTabs" oonclick="selectTabIndex(0)">Registros</a></li>
						</ul>
						
						<div id="tabs-00" style="0border:1px solid blue;width:1130px;">
						
							<div id="cntCabecera" class="textos" style="position:absolute;left:0px;top:35px;width:inherit;height:30px;0border:1px solid blue;text-align:top;overflow-x:hidden;">
												<a href="#"  onclick="agregar()"><img src="/icon/plus.png" title="Agregar" style="width:20px;cursor:pointer;"/>Agregar Toner</a>

							</div>
							
							
							<div  class="textos" style="position:absolute;left:5px;top:60px;width:1100px;height:20px;oborder:1px solid blue;">
								<div class="cntCabecera headerTable" style="width:1100;background-color:#e6e6e6;;border:1px solid blue:">
									
									<input class="headerTable" style="width:220px;background-color:#e6e6e6;" value="Solicitado Por" onblur="restablecerCabezal(1)" onfocus="limpiarCabezal(1)" onkeypress="buscarCabezal(event, this, 1)"></input>		
									<input class="headerTable" style="width:70px;background-color:#e6e6e6;" value="Fecha" onblur="restablecerCabezal(2)" onfocus="limpiarCabezal(2)" onkeypress="buscarCabezal(event, this, 2)"></input>		
									<input class="headerTable" style="width:170px;background-color:#e6e6e6;" value="Toner" onblur="restablecerCabezal(2)" onfocus="limpiarCabezal(2)" onkeypress="buscarCabezal(event, this, 2)"></input>		
									<input class="headerTable" style="width:70px;background-color:#e6e6e6;" value="Esperado" onblur="restablecerCabezal(2)" onfocus="limpiarCabezal(2)" onkeypress="buscarCabezal(event, this, 2)"></input>		
									<input class="headerTable" style="width:70px;background-color:#e6e6e6;" value="Contador" onblur="restablecerCabezal(2)" onfocus="limpiarCabezal(2)" onkeypress="buscarCabezal(event, this, 2)"></input>		
									<input class="headerTable" style="width:60px;background-color:#e6e6e6;" value="Control" onblur="restablecerCabezal(2)" onfocus="limpiarCabezal(2)" onkeypress="buscarCabezal(event, this, 2)"></input>		
									<input class="headerTable" style="width:75px;background-color:#e6e6e6;" value="Precio" onblur="restablecerCabezal(3)" onfocus="limpiarCabezal(3)" onkeypress="buscarCabezal(event, this, 3)"></input>		
									<input class="headerTable" style="width:80px;background-color:#e6e6e6;" value="Vigencia" onblur="restablecerCabezal(3)" onfocus="limpiarCabezal(3)" onkeypress="buscarCabezal(event, this, 3)"></input>		
									<input class="headerTable" style="width:200px;background-color:#e6e6e6;" value="Registrado Por" onblur="restablecerCabezal(1)" onfocus="limpiarCabezal(1)" onkeypress="buscarCabezal(event, this, 1)"></input>		
									
								</div>	
							</div>
							
							<div id="cnt_registros"  class="textos" style="position:absolute;left:0px;top:85px;width:1100px;height:130px;0border:2px solid blue;overflow-x:hidden;overflow-y:auto"></div>
							
						</div>
						
				</form>		
			</div>
			
   			
			
		</div>
		
	</div>	
	
</body>
</html>