<?php 
include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions_minidest.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');
//include($_SERVER["DOCUMENT_ROOT"] .'/includes/control_sesion.php');
 
include($_SERVER["DOCUMENT_ROOT"] .'/subsistemas/minidest/registros_xml_to_database.php');

header("Cache-Control: no-cache, must-revalidate"); // Evitar guardado en cache del cliente HTTP/1.1

date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_ALL,"es_ES");
setlocale(LC_TIME, "spanish"); //Fijamos el tiempo local

?>
	
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Expires" content="0">
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta name="description" content="" />	
<meta charset="utf-8">
	
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<link  type="text/css" rel="stylesheet"	media="all" href="/css/ui-lightness/jquery-ui-1.10.3.custom.css"></link>
<link  type="text/css" rel="stylesheet" media="all" href="/css/styles.css"></link>

<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="/js/xmlUtil.js"></script>
<script type="text/javascript" src="/js/_accordion.js"></script>
<script type="text/javascript" src="/js/_functions.js"></script>


<link type="image/x-icon" href="/icon/porta_icon.png" rel="icon" />


<title>Porta - Motor de Comunicaciones</title> 

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
		background-color: #E1D8B0;
		font-size:12px
	}
	
	ul.ui-autocomplete {
    z-index: 1100;}
	
</style>



<script language="javascript" type="text/javascript">



var objData = {};
	objData.id_tipo = 1;

	
function selectTabIndex(id){
	objData.id_tipo		= id;		 
	objData.tabSelected = id;
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
		if(datos !="OK"){
			alert(datos)
			objData.action = 0;
			objData.id_selected = 0 ;
		}else {
			//$("#cargados").append(msg); //Mostrara los archivos cargados en el div con el id "Cargados"
			$("#form_document").dialog( "close" );
			message("Archivo grabado correctamente", 1);
			
			loadData();
			//$("#comment").prop("disabled",true);		
		}
		
    });
};

function activar(id, id_estado){
	
	var mensaje = "Si continua "+(id_estado == 1 ? " deshabilitará " :" habilitará" ) +" la imagen\nRealmente desea continuar?";
	
	if(!confirm(mensaje)){
		return;
	}
	
	
	// $("#img_"+id).css("border","outset");
	// $("#img_"+id).css("filter","alpha(opacity=50)");
	// $("#img_"+id).css("opacity","0.5");
	
	//border:2px dotted black;filter:alpha(opacity=50); opacity:0.5
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
	

  
$(document).ready(function () {
	init();
    
	id_interfaz ='<?php echo($id_interfaz);?>';
	var id_stack 	=parseInt('<?php echo($id_stack);?>',10);
	
	removeClassName(id_interfaz);   
    
	$("#tabs_datos").tabs({collapsible: false });
	
	var acordionIndex  = $( "#accordion" );
	
	acordionIndex.accordion({
       heightStyle: "content"
     });
	
	acordionIndex.accordion("destroy");
    acordionIndex.accordion({ active:id_stack });
	
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
	
	$("#cntPestanas").css("width",(ventana_ancho-100)+"px");
	$("#tabs_datos").css("width",(ventana_ancho-200)+"px");
	$("#tabs_datos").css("height", ventana_alto+"px");
	$("#cntPestanas").css("height", ventana_alto+"px");
	
	objData.alto_grilla =(ventana_alto-100) ;
	$("#cnt_registros_00").css("height", objData.alto_grilla +"px");
	$("#cnt_registros_01").css("height", objData.alto_grilla +"px");
	$("#cnt_registros_02").css("height", objData.alto_grilla +"px");
	
	$("#tabs_datos").css("width",(ventana_ancho-0)+"px");

	$("body").bind('mousewheel DOMMouseScroll', function (){return false});

	loadData();
	

	
});

</script>

</head>
<body style="overflow:hidden;background-color:#F8ECE0">
	
	<?php include($_SERVER["DOCUMENT_ROOT"] .'/cabecera.php'); ?>
	
	
	<form name="formGenerico" id="formGenerico" action="" method="post"   onsubmit="return false">	</form>		
	
	
	<div id="form_activity" title="Registro de Actividad" style="display:none;;">	
	</div>
	
	
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
					<div id="tabs_datos" class="textTabs" style="position:absolute;left:0px;top:0px;display:inline-block;width:350px;0border:2px solid orange;overflow-y:visible;">
						<ul>
							<li><a href="#tabs-01" class="textTabs" onclick="selectTabIndex(1)">Portada</a></li>
							<li><a href="#tabs-02" class="textTabs" onclick="selectTabIndex(2)">Beneficios</a></li>
							<li><a href="#tabs-03" class="textTabs" onclick="selectTabIndex(3)">Sorteos</a></li>
							
							
						</ul>
						
						<div id="tabs-01" style="0border:1px solid blue;width:1030px;">
							<a href="#" onclick="addDocument()"><img src="/icon/mas.png" title="Agregar Nueva Imagen" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Agregar Imagen</span>&nbsp;</a>
							<div id="cnt_registros_01"  class="textos" style="position:absolute;left:0px;top:65px;width:930px;height:130px;0border:2px solid blue;overflow-x:hidden;overflow-y:auto"></div>
						</div>
						
						<div id="tabs-02" style="0border:1px solid blue;width:1030px;">
							<a href="#" onclick="addDocument()"><img src="/icon/mas.png" title="Agregar Nueva Imagen" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Agregar Imagen</span>&nbsp;</a>
							<div id="cnt_registros_02"  class="textos" style="position:absolute;left:0px;top:65px;width:930px;height:130px;0border:2px solid blue;overflow-x:hidden;overflow-y:auto"></div>
						</div>
						
						<div id="tabs-03" style="0border:1px solid blue;width:1030px;">
							<a href="#" onclick="addDocument()"><img src="/icon/mas.png" title="Agregar Nueva Imagen" style="width:20px;cursor:pointer;"><span style="vertical-align:top;;cursor:pointer">&nbsp;&nbsp;Agregar Imagen</span>&nbsp;</a>
							<div id="cnt_registros_03"  class="textos" style="position:absolute;left:0px;top:65px;width:930px;height:130px;0border:2px solid blue;overflow-x:hidden;overflow-y:auto"></div>
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