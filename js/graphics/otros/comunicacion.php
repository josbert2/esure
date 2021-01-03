<?php 

session_start();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');

$ip_acceso =  $_SERVER["REMOTE_ADDR"]; 

// $sql="SELECT id_sorteo 
// FROM front_end_images 
// WHERE id_tipo = 3 AND id_sorteo IN(SELECT id_sorteo 
// FROM sorteos
// WHERE  curdate() > date_to )";

// $stmt = $conn->prepare($sql);
// $stmt->execute();
				
// $registros = $stmt->rowCount();
// $sql_update = "";

// if($registros > 0){
	// while($datos = $stmt->fetch()){
		// $sql_update .="UPDATE front_end_images SET id_estado = 2 WHERE id_sorteo = $datos[id_sorteo];";
	// }
// }	
// if($sql_update != ""){
	// $conn->exec($sql_update);
// }




?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Intranet PORTA HNOS S.A.</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
<link  type="text/css" rel="stylesheet"	media="all" href="/css/ui-lightness/jquery-ui-1.10.3.custom.css"></link>
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<link  type="text/css" rel="stylesheet" media="all" href="/css/styles.css"></link>


<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="/js/xmlUtil.js"></script>
<script type="text/javascript" src="/js/_accordion.js"></script>
<script type="text/javascript" src="/js/_functions.js"></script>


  
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
		height: 20px;
		background-color: #ffffff;
		font-size:12px
	}
	
	ul.ui-autocomplete {
    z-index: 1100;
	}
	::-webkit-scrollbar {
		display: none;
	}
	.ui-datepicker {font-size:200%; }

	.ui-tabs .ui-tabs-nav li a {font-size:10pt !important;}

	.ui-dialog .ui-dialog-titlebar, .ui-dialog .ui-dialog-buttonpane { font-size: 1.8em; }

	.boton_1{
		text-decoration: none;
		padding: 3px;
		padding-left: 10px;
		padding-right: 10px;
		font-family: helvetica;
		font-weight: bold;
		font-size: 25px;
		color: white;
		background-color: #2d37db;
		border-radius: 5px;
		
	  }
	  .boton_1:hover{
		opacity: 0.6;
		text-decoration: none;
	  }

</style>

	
	<link type="image/x-icon" href="/icon/porta_icon.png" rel="icon" />

	
<script language="javascript" type="text/javascript">



var objData = {};
	objData.id_tipo = 3;
	objData.id 		= 0;
	objData.id_sorteo = 0;
	
function selectTabIndex(id){
	objData.id_tipo		= id;		 
	objData.tabSelected = id;
	
	if(id == 3){
		$("#tableData").css("display","none");	
		$("#btn_addSorteo").css("display","");
		$("#btn_saveChanges").css("display","none");
		$("#btn_addImagen").css("display","");
		objData.id 		= 0;
		objData.id_sorteo = 0;
		$("#cnt_registros_03").css("height", (objData.alto_grilla-50) +"px");
	}
	loadData();
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
	datos.action	  = 1;
	datos.id_tipo	  = objData.id_tipo;
	datos.id		  = (objData.id > 0 ? objData.id : 0);
	
	
   jQuery.ajax({
        async: false,
        url: '/subsistemas/otros/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			var respuesta = resp.trim();
			//var aux = respuesta.split("::");
			$("#cnt_registros_0"+objData.id_tipo).html(respuesta);			
			
			
	    }
    );
	
	//verConsumos();
};

function seleccionado() {
	
	var archivos = document.getElementById("archivos_"); //Damos el valor del input tipo file
	
	
    var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo
    //El objeto FormData nos permite crear un formulario pasandole clave/valor para poder enviarlo, 
    //este tipo de objeto ya tiene la propiedad multipart/form-data para poder subir archivos
    var data_ = new FormData();
	
	for (i = 0; i < archivo.length; i++) {
		 data_.append('archivos' + i, archivo[i]);
	}
	
	 data_.append('id_tipo', objData.id_tipo);
	 
	if(objData.id_tipo == 3){
	
		var date_from 	= $("#date_from").val();
		if(!isDate(date_from)){
			alert("Por favor ingrese la fecha DESDE");
			$("#date_from").focus()
			return;
		}
		var date_to 	= $("#date_to").val();
		
		if(!isDate(date_to)){
			alert("Por favor ingrese la fecha HASTA");
			$("#date_to").focus()
			return;
		}
		
		var titulo 		= $("#titulo").val();
		
		if(titulo == ""){
			alert("Por favor ingrese un TITULO");
			$("#titulo").focus()
			return;
		}
		
		// var comentario 	= $("#comentario").val();
		// if(comentario == ""){
			// alert("Por favor ingrese un COMENTARIO");
			// $("#comentario").focus()
			// return;
		// }
		
		
	
		
		data_.append('date_from', date_from);
		data_.append('date_to', date_to);
		data_.append('titulo', titulo);
		//data_.append('comentario', comentario);
		   
	
	}
	 
	
    $.ajax({
        url: '/subsistemas/otros/upload_imagen.php', //Url a donde la enviaremos
        type: 'POST', //Metodo que usaremos
        contentType: false, //Debe estar en false para que pase el objeto sin procesar
        //data:data,cuit:1, //Le pasamos el objeto que creamos con los archivos
        data: data_,

        processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
        cache: false //Para que el formulario no guarde cache
    }).done(function (msg) {
		
        var datos = msg.trim();
		var aux = datos.split("::");
		if(aux[0] !="OK"){
			alert(datos)
			objData.action = 0;
			objData.id_selected = 0 ;
		}else {
			//$("#cargados").append(msg); //Mostrara los archivos cargados en el div con el id "Cargados"
			$("#form_document").dialog( "close" );
			message("Archivo grabado correctamente", 1);
			objData.id = aux[1];
			
			loadData();
			$("#tableData").css("display","none");	
			$("#btn_addSorteo").css("display","");
			$("#btn_saveChanges").css("display","none");
			$("#btn_addImagen").css("display","");
			objData.id 		= 0;
			objData.id_sorteo = 0;
			$("#cnt_registros_03").css("height", (objData.alto_grilla-50) +"px");

		}
		
    });
};

function activar(id, id_estado, id_tipo, id_sorteo){
	
	if(id_tipo < 3){
		
		var mensaje = "Si continua "+(id_estado == 1 ? " deshabilitará " :" habilitará" ) +" la imagen\nRealmente desea continuar?";
		
		if(!confirm(mensaje)){
			return;
		}
		
		var objDatos = {};
		objDatos.action       = 1;
		objDatos.id_estado	  = (id_estado == 1 ? 2 : 1);
		objDatos.id	  		  = id;
		
		jQuery.ajax({
			async: false,
			url: '/subsistemas/otros/saveData.php',
			type: 'post',
			data: objDatos
		}).done(
			function (resp) {
				var respuesta = resp.trim();
				if(respuesta !="OK"){
					alert(respuesta);
				}
				loadData();
			}
		);	
	} else {//sorteo
	
	
		objData.id_sorteo = id_sorteo;
		
		$("#tableData").css("display","");	
		
		var objDatos = {};
		objDatos.action       = 2;
		objDatos.id	  		  = id;
		
		jQuery.ajax({
			async: false,
			url: '/subsistemas/otros/consulta.php',
			type: 'post',
			data: objDatos
		}).done(
			function (resp) {
				var respuesta = resp.trim();
				var aux = respuesta.split("::");
				if(aux[0] =="OK"){
					$("#titulo").val(aux[1]);
					//$("#comentario").val(aux[2]);
					$("#date_from").val(getSpanishDate(aux[3]));
					$("#date_to").val(getSpanishDate(aux[4]));
					
					objData.id 	= id;	
					loadData();
					
					$("#btn_addSorteo").css("display","none");
					$("#btn_saveChanges").css("display","");
					$("#btn_addImagen").css("display","none");
					
					
				}
			}
		);	
		
	}
	
};

function descargarInscriptos(id_sorteo){

	var form = document.formGenerico;
	var url = "/subsistemas/otros/generar_reportes_excel.php?id_sorteo="+id_sorteo;
	
	form.action = url;
	form.submit();
};

function saveChanges(){
	
	$("#btn_addSorteo").css("display","");
	$("#btn_saveChanges").css("display","none");
	$("#btn_addImagen").css("display","");
	
	
	var date_from 	= $("#date_from").val();
	if(!isDate(date_from)){
		alert("Por favor ingrese la fecha DESDE");
		$("#date_from").focus()
		return;
	}
	
	var date_to 	= $("#date_to").val();
	if(!isDate(date_to)){
		alert("Por favor ingrese la fecha HASTA");
		$("#date_to").focus()
		return;
	}
	
	var titulo 		= $("#titulo").val();
	if(titulo == ""){
		alert("Por favor ingrese un TITULO");
		$("#titulo").focus()
		return;
	}
	
	// var comentario 	= $("#comentario").val();
	// if(comentario == ""){
		// alert("Por favor ingrese un COMENTARIO");
		// $("#comentario").focus()
		// return;
	// }
	
		
		
	var objDatos = {};
	objDatos.action       = 2;
	objDatos.id_sorteo	  = objData.id_sorteo;
	objDatos.date_from	  = date_from;
	objDatos.date_to	  = date_to;
	objDatos.titulo	 	  = titulo;
	//objDatos.comentario	  = comentario;
	
	
	jQuery.ajax({
		async: false,
		url: '/subsistemas/otros/saveData.php',
		type: 'post',
		data: objDatos
	}).done(
		function (resp) {
			var respuesta = resp.trim();
			if(respuesta !="OK"){
				alert(respuesta);
			}
			
			loadData();
			$("#tableData").css("display","none");	
			$("#btn_addSorteo").css("display","");
			$("#btn_saveChanges").css("display","none");
			$("#btn_addImagen").css("display","");
			objData.id 		= 0;
			objData.id_sorteo = 0;
			$("#cnt_registros_03").css("height", (objData.alto_grilla-50) +"px");
		}
	);	
	
};

function addDocument(){
	$("#form_document").dialog({
	  resizable: false,
	  width:350,
	  height:180,
	  title:"Adjuntar Imagen",
	   closeOnEscape: false,
	  open: function(event, ui) {
	 		$(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
     },
	  modal: true,
	  buttons: {
		Cerrar: function(){
		  $( this ).dialog( "close" );
		}
	  }
	});		
};
	
function addSorteo(){
	objData.id 	= 0;
	$("#tableData").css("display","");
	$("#cnt_registros_03").html("");

	$("#titulo").val("");
	//$("#comentario").val("");
	$("#date_from").val("");
	$("#date_to").val("");
						
	$("#date_from").focus();		
	
	$("#cnt_registros_03").css("height", (objData.alto_grilla-300) +"px");

};
 
$(document).ready(function () {
	init();
    
	
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
	
	$("#cntPestanas").css("width","1010px");
	$("#tabs_datos").css("width","950px");
	$("#tabs_datos").css("height", ventana_alto+"px");
	$("#cntPestanas").css("height", ventana_alto+"px");
	
	objData.alto_grilla =(ventana_alto-100) ;
	$("#cnt_registros_01").css("height", objData.alto_grilla +"px");
	$("#cnt_registros_02").css("height", objData.alto_grilla +"px");
	$("#cnt_registros_03").css("height", (objData.alto_grilla-50) +"px");
	

	$("body").bind('mousewheel DOMMouseScroll', function (){return false});

	loadData();
	$("#date_from").datepicker({dateFormat: "dd/mm/yy"});
	$("#date_to").datepicker({dateFormat: "dd/mm/yy"});

	$("#tabs_datos").tabs({active: 2 });
	
});

</script>

</head>
<body style="overflow:hidden;background-color:#F8ECE0;overflow:hidden;">
	
	<?php include($_SERVER["DOCUMENT_ROOT"] .'/cabecera.php'); ?>
	
	
	<form name="formGenerico" id="formGenerico" action="" method="post"   onsubmit="return false"></form>		
	
	
	<div id="form_activity" title="Registro de Actividad" style="display:none;;"></div>
	
	
	<div id="form_document" title="" style="display:none;overflow-x:hidden;">	
		
		<table cellpadding="0" cellspacing="0" border="0" width="360px" class="textos">
			<tr height="40px">
				<td width="100px">Documento</td>
				<td width="250px"><input id="archivos_" style="display:inline;width:220px" type="file" name="archivos_" mmultiple="multiple" onchange="seleccionado(this)"></td>
			</tr>
		</table>	
		
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
				
				</br>
									
			</div>
			
			<div id="cntPestanas"  class="textos" style="position:absolute;left:0px;top:0px;width:750px;height:200px;0border:3px solid red;overflow:hidden;">
				<form name="formData" id="formData" action="" method="post"   onsubmit="return false">	
					<div id="tabs_datos" class="textTabs my-tabs" style="position:absolute;left:0px;top:0px;display:inline-block;width:350px;0border:2px solid orange;overflow-y:visible;">
						<ul>
							<li><a href="#tabs-01" class="textTabs" onclick="selectTabIndex(1)">Portada</a></li>
							<li><a href="#tabs-02" class="textTabs" onclick="selectTabIndex(2)">Beneficios</a></li>
							<li><a href="#tabs-03" class="textTabs" onclick="selectTabIndex(3)">Sorteos</a></li>
							
							
						</ul>
						
						<div id="tabs-01" style="0border:1px solid blue;width:1030px;">
							<a href="#" onclick="addDocument()"><img src="/icon/mas.png" title="Agregar Nueva Imagen" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Agregar Imagen</span>&nbsp;</a>
							<div id="cnt_registros_01"  class="textos" style="position:absolute;left:0px;top:65px;width:930px;height:130px;0border:2px solid blue;overflow-x:hidden;overflow-y:auto;"></div>
						</div>
						
						<div id="tabs-02" style="0border:1px solid blue;width:1030px;">
							<a href="#" onclick="addDocument()"><img src="/icon/mas.png" title="Agregar Nueva Imagen" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Agregar Imagen</span>&nbsp;</a>
							<div id="cnt_registros_02"  class="textos" style="position:absolute;left:0px;top:65px;width:930px;height:130px;0border:2px solid blue;overflow-x:hidden;overflow-y:auto"></div>
						</div>
						
						<div id="tabs-03" style="0border:1px solid blue;width:1030px;">
							<a  id="btn_addSorteo" href="#" style="position:absolute;left:16px;top:48px" onclick="addSorteo()"><img src="/icon/plus.png" title="Agregar Sorteo" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Agregar Sorteo</span>&nbsp;</a>
							<a  id="btn_saveChanges" href="#" style="position:absolute;left:16px;top:48px;display:none;" onclick="saveChanges()"><img src="/icon/changes.png" title="Agregar Sorteo" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Grabar Cambios</span>&nbsp;</a>
							
							</br></br>
							<table id="tableData" style="display:none;" cellspacing="0" cellpadding="1" border="0" width="910px">
								<tr height="25px">
									<td style="width:20px" class="textos" >Desde&nbsp;</td>
									<td style="width:80px" class="textos">
										<input type="text" name="date_from" id="date_from" value=""  style="width:80px"/>
									</td>
									<td style="width:20px" class="textos" >Hasta&nbsp;</td>
									<td style="width:80px"class="textos">
										<input type="text" name="date_to" id="date_to" value=""  style="width:80px"/>
									</td>
									<td style="width:700px"class="textos">
									</td>
								</tr>
								<tr height="25px">		
									<td colspan="5">
										<textarea  style="font-size:13px;width:910px" id="titulo" name="titulo"   placeholder="Ingrese un Tìtulo" maxlength="600" value="" rows="2" cols="90" ></textarea>
									</td>
								</tr>
								
								<tr>		
									<td colspan="5">
										<a id="btn_addImagen" href="#" style="position:absolute;left:10px;top:150px" onclick="addDocument()"><img src="/icon/mas.png" title="Agregar Nueva Imagen" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Agregar Imagen</span>&nbsp;</a>
									</td>
								</tr>
							</table>
		
							<br><br><br><br>
							<div id="cnt_registros_03"  class="textos" style="sposition:absolute;left:10px;top:380px;width:930px;height:130px;0border:2px solid blue;overflow-x:hidden;overflow-y:auto"></div>
						</div>
						
					
					</div>
				</form>		
			</div>
			
   			
			
		</div>
		<div id="right"  style="position:absolute;right:0px;top:0px;0border:5px solid #6E6E6E;width:0%;height:0%">

		</div>	
	</div>	
	
</body>
</html>
