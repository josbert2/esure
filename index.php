<?php header('Content-Type: text/html; charset=utf8'); ?>
<?php 


$_SESSION = array();
session_start() ;
$path  		= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$aux  		= explode(".", $path);
$aux2 		= explode("/", $aux[0]);
$index 		= count($aux2);
$empresa 	= $aux2[($index-1)];


if(is_numeric($empresa)){
	$aux  = explode(":", $path);
	//echo($aux[2]."<br>");
	$aux  = explode("/", $aux[2]);
	//echo($aux[0]."<br>");
	$empresa = $aux[0];
	//$empresa = str_replace("/", "", $aux[2]);
};
$r = $_SERVER['DOCUMENT_ROOT'] . '/devs/proyectos/esecure_new/';

//include $_SERVER['DOCUMENT_ROOT']."/includes/functions.php";
include $r."/includes/functions.php";

//include $_SERVER['DOCUMENT_ROOT']."/includes/functions_inmo.php";
include $r."/includes/functions_inmo.php";

//include($_SERVER["DOCUMENT_ROOT"] .'/includes/setDataEnterprise.php');
include($r.'/includes/setDataEnterprise.php');

//include $_SERVER['DOCUMENT_ROOT']."/includes/conexion_mysqli.php";
include $r."/includes/conexion_mysqli.php";
	

$arrayImage = getPropertiesLayout($conn);
$usuario	 = isSet($_GET["usuario"]) ? $_GET["usuario"] : 0;
$clave		 = isSet($_GET["clave"]) ? $_GET["clave"] : 0;


$sql ="SELECT lat, lng, colorSitio FROM enterprise";

$stmt = $conn->prepare($sql);
$stmt->execute();
			
$registros = $stmt->rowCount();
if($registros == 1){
	$row 	   = $stmt->fetch();
	$_SESSION["lat"] = $row["lat"];
	$_SESSION["lng"] = $row["lng"];
}		

$_SESSION["css_name"]		 = getCSSFileName( $row["colorSitio"]);


?>
<html lang="en">
<head>

<title><?php echo($_SESSION["title"])?></title>
<link rel="stylesheet" href="./css/dinamic_css/<?php echo($_SESSION["css_name"])?>.css"/>


	


<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="./js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="./assets/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="./assets/script.js"></script>
	<script type="text/javascript" src="./js/md5.js"></script>	
	<script type="text/javascript" src="./js/_functions.js"></script>	
	<script type="text/javascript" src="./assets/owl-carousel/owl.carousel.js"></script>
	<script type="text/javascript" src="./assets/slitslider/js/modernizr.custom.79639.js"></script>
	<script type="text/javascript" src="./assets/slitslider/js/jquery.ba-cond.min.js"></script>
	<script type="text/javascript" src="./assets/slitslider/js/jquery.slitslider.js"></script>
	
	<link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="./assets/style.css"/>
	<link rel="stylesheet" href="./assets/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="./assets/owl-carousel/owl.theme.css">
	<link rel="stylesheet" type="text/css" href="./assets/slitslider/css/style.css" />
	<link  type="text/css" rel="stylesheet"	media="all" href="./css/ui-lightness/jquery-ui-1.10.3.custom.css"></link>
	<link rel="stylesheet" type="text/css" href="./assets/slitslider/css/custom.css" />
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjnKGGqEz8RBxhQ5LalTkv4GxoETKxwAo&callback=initMap" async defer></script>
	
	<link rel="icon" type="image/png" href="/icon/<?php echo($_SESSION["icon"]);?>"  />
	<link rel="stylesheet" href="./css/index.css" />
<!-- slitslider -->

	<style>
		
	</style>

</head>
<script>


var map ="";
var marker = null;
var arrayPosition = {};
	arrayPosition.lat = ""; 
	arrayPosition.lng = ""; 
var flightPath = "";	

var markers = [];

	
function cargarPuntoEnMapa(id_propiedad, direccion, latitud, longitud,  icon, operacion, importe_operacion){
	 
	var indice =  isNaN(indice) ? 1 : indice;
	 
	var tamanoIcono = 30;
	
	var image = {
		url: icon,
		size: new google.maps.Size(71, 71),
		origin: new google.maps.Point(0, 0),
		anchor: new google.maps.Point(17, 34),
		scaledSize: new google.maps.Size(tamanoIcono, tamanoIcono)
	};

	
	var shape = {
		coords: [1, 1, 1, 20, 18, 20, 18, 1],
		type: 'poly'
	};
	
	var arrayPosicion = {}			
		arrayPosicion.lat = parseFloat(latitud);
		arrayPosicion.lng = parseFloat(longitud);
	
	
	var marker = new google.maps.Marker({
		position: arrayPosicion,
		map: map,
		shape: shape,
		icon:image,
		id_propiedad:id_propiedad,
		direccion:direccion,
		latitud:latitud,
		longitud:longitud,
		operacion:operacion,
		importe_operacion:importe_operacion
		
		
	});
	
	markers.push(marker);
	
	
	
	marker.addListener('click', function() {
		//console.info(marker.latitud + " " +marker.longitud)
		
		 var contentString = '<div id="iw-container">'+
								marker.operacion+
							  '</div>'+
							  '<div id="iw-title" style="margin:5px">'+
									'<p><b>'+marker.direccion+'</b>'+
									'<p><b>'+marker.importe_operacion+'</b>'+
									'<p><b><a target="black" href="/modules_/imno/detalle_publico_propiedad.php?id='+marker.id_propiedad+'">Ver Detalle</a></b>'+
							   '</div>';
		//console.info(contentString	)			  
		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});


		infowindow.open(map, marker);
	});	
	
};

function setMapOnAll(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
};
// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}
		
function mostrarPropiedades(){
	var datos 	  	= {};
	datos.action  	= 23;
	
   jQuery.ajax({
		async: false,
		url: 'http://localhost/devs/proyectos/esecure_new/modules_/imno/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			var data = resp.trim();
			var aux = data.split("::");
			if(aux[0] =="OK"){
				$("#cnt_imagenes_preferenciales").html(aux[1]);
				
				$("#nav-dots").html(aux[2]);
			} else {
				
				alert(data);
				
			}
			
		}
	);
};

function mostrarCamposTipoPropiedad(){};	

function buscarPropiedades(){
	
	var localidad =  $("#localidad").val();
	var id_localidad = 0;
	
	if(localidad !=""){
		var aux = localidad.split("-");
		id_localidad = parseInt(localidad,10);
		
		if(isNaN(id_localidad)){
			alert("Debe ingresar la denominación en forma pausada y elegir una coincidencia, sino borre el campo para continuar");
			return;
		}
	}
	
	var precio_desde = $("#precio_desde").val();
	if(precio_desde !="" && isNaN(precio_desde)){
		alert("El precio DESDE debe ser un valor numérico");
		return;
	}
	
	var precio_hasta = $("#precio_hasta").val();
	if(precio_hasta !="" && isNaN(precio_hasta)){
		alert("El precio HASTA debe ser un valor numérico");
		return;
	}
	
	var superficie = $("#superficie").val();
	if(superficie !="" && isNaN(superficie)){
		alert("La SUPERFICIE debe ser un valor numérico");
		return;
	}
	
	if(precio_hasta > 0 || precio_desde >0){
		var cboTipoOperacion  = parseInt(getComboValue("cboTipoOperacion"),10);
		if(cboTipoOperacion == 0 || cboTipoOperacion == 3 ){
			alert("Debe indicar un tipo de operación Alquiler o Venta.");
			return;
		} 
	}
	
	
	var datos 	  			= {};
	datos.action  			= 38;
	datos.cboTipoPropiedad  = getComboValue("cboTipoPropiedad");
	datos.cboTipoOperacion  = getComboValue("cboTipoOperacion");
	datos.id_localidad  	= id_localidad;
	
	datos.precio_desde  	= (precio_desde == "" ? 0 :precio_desde);
	datos.precio_hasta  	= (precio_hasta == "" ? 0 :precio_hasta);
	datos.superficie  		= (superficie == "" ? 0 :superficie);
	
	datos.cboHabilitaciones  = getComboValue("cboHabilitaciones");
	
	document.getElementById("cntBusqueda").style.display  = "inline";

	
	//$("#cntBusqueda").css("display","none")
	
	
   jQuery.ajax({
		async: false,
		url: 'http://localhost/devs/proyectos/esecure_new/modules_/imno/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			crearCarrusel();
			$("#owl-example").html(resp);
		}
	);
	
};


function compartirFacebook(id_propiedad){
	

	var datos 	  = {};
	datos.action  = 71;
	datos.id_propiedad	  = id_propiedad;
    
   jQuery.ajax({
        async: false,
        url: 'http://localhost/devs/proyectos/esecure_new/modules_/imno/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			var respuesta = resp.trim();
				var date = new Date().getTime();
				var url = '<?php echo($_SESSION["INMO_URL"]);?>'+"/images/toDiskImage/share-fb.php?&a="+date;
				window.open('https://www.facebook.com/sharer/sharer.php?u='+url+'&a='+date+'&quote='+respuesta,'facebook-popup');
	    }
    );	
}

function crearCarrusel(){
	
	$("#owl-example").owlCarousel();
	
	$('.listing-detail span').tooltip('hide');
	$('.carousel').carousel({
		interval: 3000
	}); 
	$('.carousel').carousel('cycle');
};


var objGeo  =  null;
var objAux  = null;

var objAjax = {};

var arrayClientes = null;


function inicializarMapa(){
	map = new google.maps.Map(document.getElementById('cnt_mapa'), {
					center: arrayPosition,
					zoom: 4
				});
	initMap(arrayPosition);		
	//alert(typeof map +" en inicializarMapa" )
	setTimeout(function(){
		
		google.maps.event.addListener(map, 'zoom_changed', function(event) {
			
			clearMarkers(); 
			
		    var z = map.getZoom();
			
			factor_zoom = 1;
			
			if(z <= 15){
				factor_zoom = 1-((16-z)*0.0625);
			}
			
			var cnt = arrayClientes.length;
			var resp = arrayClientes;
			
			for(var a=0; a < cnt;a++){
				
				cargarPuntoEnMapa(  
						   resp[a].id_propiedad, 	
						  resp[a].direccion, 
						  resp[a].latitud, 
						  resp[a].longitud, 
						  resp[a].icon,
						  resp[a].operacion,
						  resp[a].importe_operacion);

			}
		});
		
	},2000);
};


function verMapa(){
	var objFiltroPropiedades = {};
		objFiltroPropiedades.id_propietario	 = 0;
		
	var propietario		= $("#propietario").val();
	
	if(propietario !=""){
		objFiltroPropiedades.id_propietario = parseInt(propietario.split("-")[0],10); 
		
		if(objFiltroPropiedades.id_propietario < 0 || isNaN(objFiltroPropiedades.id_propietario)){
			message("Por favor reingrese el nombre del propietario y seleccionelo de la lista",2);
			return;
		}
	}
	
	objFiltroPropiedades.direccion			= "";
	objFiltroPropiedades.id_barrio			= 0;
	objFiltroPropiedades.id_tipo_operacion	= 0;
	//objFiltroPropiedades.id_estado			= getComboValue("cboEstado");
	objFiltroPropiedades.action				= 12;
	
	consultarPropiedades(objFiltroPropiedades);
	$( "#formReportes" ).dialog("close");
	
};	


function consultarPropiedades(obj){
	
	$("#loader_center").css("display","");
	
	 setTimeout(function(){
	 
		jQuery.ajax({
			async: false,
			url: 'http://localhost/devs/proyectos/esecure_new/modules_/imno/consulta.php',
			type: 'post',
			data: obj
		}).done(
			function (resp) {
				resp = resp.trim();
				
				if(eval(resp)){
					var objData = eval(resp);
					cargarNormal(objData);
				}	
				
				$("#loader_center").css("display","none");
			}		
		);		
	 },1000);
	
};


function cargarNormal(resp){
	//alert("cargarNormal map es "+ typeof map)
	arrayClientes = resp;
	var cnt = resp.length;
	//console.info("cnt "+cnt )
	
	for(var a=0; a < cnt;a++){
		cargarPuntoEnMapa(  
						   resp[a].id_propiedad, 	
						  resp[a].direccion, 
						  resp[a].latitud, 
						  resp[a].longitud, 
						  resp[a].icon,
						  resp[a].operacion,
						  resp[a].importe_operacion);
	}
	
	
	
};


function initMap(arrayData) {
	
	if(arguments.length == 0){return}
	
	myLatLng = arrayData;
	
	map = new google.maps.Map(document.getElementById('cnt_mapa'), {
	  center: myLatLng,
	  zoom: 12
	});
};

function controlTipoAlquiler(){
	
	var id_tipo_operacion = parseInt(getComboValue("cboTipoOperacion"),10);
	if(id_tipo_operacion == 4){
		page = "/alquileres_temporarios.php";
		window.location = page;
	}
	
};	
	
$(document).ready(function () {
	//ostrarPropiedades();
	
	
	
	$("#localidad").autocomplete({
		
		source: function(request, response) {
			
			var localidad = $("#localidad").val();
			if(localidad.length < 3){return}
			
			$.ajax({
				url: "/modules_/imno/consultaAutoComplete.php",
				dataType: "json",
				data: {
					term : request.term,
					action : 1
				},
				success: function(data) {
					response(data);
				}
			});
		},
			min_length: 3,
			delay: 300
		});


	$("#cboTipoPropiedad").addClass("form-control");	
	//posicionarEnId("cboTipoPropiedad",2);
	
	crearCarrusel();

	var usuario = '<?php echo($usuario);?>'	
	var clave   = '<?php echo($clave);?>'	
	
	if(usuario != 0){
		if(isNaN(parseInt(clave,10))){
			clave = calcMD5(clave);
		}
		
		page = "/includes/control_ingreso?redirect=1&username="+usuario+"&password="+clave;
		window.location = page;
	}
	var ventana_alto = $(window).height(); 
	var ventana_ancho = $(window).width(); 

	var lat = parseFloat(<?php echo($_SESSION["lat"])?>);
	var lng = parseFloat(<?php echo($_SESSION["lng"])?>);
	
	setTimeout(function(){ 
	
		arrayPosition.lat = lat; 
		arrayPosition.lng = lng; 
		inicializarMapa();
		
		var objFiltroPropiedades = {};
			objFiltroPropiedades.id_propietario	 	= 0;
			objFiltroPropiedades.direccion			= "";
			objFiltroPropiedades.action				= 12;
			
			objFiltroPropiedades.id_barrio			= 0;
			objFiltroPropiedades.id_tipo_operacion	= 0;
			objFiltroPropiedades.id_estado			= 0;
		 consultarPropiedades(objFiltroPropiedades);
		
	},3000);
	
	buscarPropiedades();
	
	//console.info("ventana_ancho "+ventana_ancho)
	var anchoImagen = 890;
	$(".bg-img").css("width",anchoImagen+"px");
	
	var aux  = ((ventana_ancho -anchoImagen)/2)-200;
		
	$(".bg-img").css("left",aux+"px");
	
	// $("#titlePropiedad").css("left",(aux+120)+"px");
	// $("#divDtitlePropiedad").css("left",(aux+220)+"px");
	$(".owl-item").css("width","336px");
	$(".img-responsive").css("min-width","312px");
	$(".img-responsive").css("height","200px");
	$(".owl-item").css("height","373px");
	$(".img_sc").css("width",ventana_ancho+"px");
	
	//$(".content-wrapper").css("width","400px");
	
	console.info("ventana_ancho  "+ventana_ancho)
	if(ventana_ancho < 1000){
		
		$("h2").css("font-size","20px");

		$(".owl-item").css("width", (ventana_ancho-20)+"px");
		$(".img_sc").css("width",(ventana_ancho)+"px");
		$(".img_sc").css("height","320px");
		$(".sl-slider-wrapper").css("height","320px");
		$(".tituloDestacada").css("margin-left","20px");
		
		// $(".sl-slider-wrapper").css("height","160px");
		$(".img-responsive").css("width", ventana_ancho+"px");
		$(".img-responsive").css("height", (ventana_ancho*0.70)+"px");
	}
});	
	
</script>	
<body style="background: url(../images/bgmain.png) #eee">

    

<!-- Header Starts -->
<div class="navbar-wrapper" style="z-index:9999";>

	<div class="navbar-inverse" role="navigation">
	  <div class="container">
		<div class="navbar-header">


		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" style="color: #333333">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>

		</div>


		<!-- Nav Starts -->
		<div class="navbar-collapse  collapse " >
		 	<div class="d-flex">
			 <ul class="m-r-auto">
		  	<li>
				<a href="index.php">
				<!--Jos-->
				<!--<img src="./icon/<?php echo($_SESSION["icon"]);?>" height="50px" width="50px" alt="Realestate">-->

				
				<img src="https://inmosalerno.esecure.com.ar/icon//logos/_salerno.png" height="50px" width="100px" alt="Realestate">
				</a>
			</li>
		  </ul>
		  <ul class="nav navbar-nav navbar-right d-flex align-items-center">
		   <li class="active"><a href="index" style="z-index:99999999">Principal</a></li>
			<li><a href="about">Nuestra Empresa</a></li>
			<li><a href="agents">Equipo</a></li>         
		 
			<li><a href="contact">Contacto</a></li>
			 <li><a href="login">Ingresar</a></li>
		  </ul>
			 </div>
		</div>
		<!-- #Nav Ends -->

	  </div>
	</div>

</div>
<!-- #Header Starts -->





<!--<div class="container" style="width:100%;padding:0">
<div class="header" style="background-color:#ffffff;width:100%;">
<a href="index.php"></a>


<ul class="pull-right">
</ul>
</div>
</div>-->

<!-- slider -->
<div class="">
   
	<div id="slider" class="sl-slider-wrapper" style="width:100%;height:400px;0border:5px solid red">
		
        <div id="cnt_imagenes_preferenciales" class="sl-slider" >
			<?php echo($arrayImage["cnt_preferenciales"]);	?>
        </div>

        <div id="nav-dots" cclass="nav-dots" style="position:relative;top:-100px;border:2px solid blue;">
			<?php echo($arrayImage["navs_preferenciales"]);	?>
        </div>

      </div>
</div>

<div class="clearfix"></div>
	
<!-- Buscador -->
<div class="container">
<div class="banner-search" sstyle="background-url:none:0border:3px solid blue;width:70%;text-align:center;margin-left:auto;margin-right:auto">
  <div class="container" style="0border:3px solid blue;max-width:850px;text-align:center;margin-left:auto;margin-right:auto"> 
   	<div class="search">
				<div class="search-module form-group" >

						<div class="row">
						
							<div class="col-xs-6 col-sm-3">
							<select style="width:200px" id="cboTipoPropiedad" onchange="mostrarCamposTipoPropiedad()" class="form-control"><option id="0" value="0">Tipo de Propiedad</option><option id="1" value="1">Campo</option><option id="2" value="2">Casa</option><option id="3" value="3">Casa Country </option><option id="4" value="4">Cochera</option><option id="5" value="5">Departamento</option><option id="6" value="6">Duplex</option><option id="7" value="7">Galpon</option><option id="8" value="8">Local</option><option id="9" value="9">Oficina</option><option id="10" value="10">Terreno</option><option id="11" value="11">Terreno Country  </option></select>
							</div>
							<div class="col-xs-6 col-sm-3">
									
										 <select id="cboTipoOperacion" class="form-control select2 select2-hidden-accessible" data-placeholder="Tipo de operación" data-select2-container="big-text" rrequired="" tabindex="-1" aria-hidden="true" style="width:200px" onchange="controlTipoAlquiler()">
											<option value="0">Tipo Operación</option>
											<option value="1">Alquiler Vivienda / Comercio</option>
											<option value="4">Alquiler Temporario</option>
											<option value="2">Venta</option>
											<option value="3" >Ambos</option>
										  </select>
								
							</div>
							<div class="col-xs-6 col-sm-3">
									<input type="text" id="localidad" class="form-control select2 select2-hidden-accessible" placeholder="Ingrese Localidad" data-select2-container="big-text" rrequired="" tabindex="-1" aria-hidden="true" style="width:200px;font-family:Arial;font-size:14px;color:#555555;"/>
							</div>
							<div class="col-xs-6 col-sm-3">
								
									<select id="cboHabilitaciones" class="form-control select2 select2-hidden-accessible" placeholder="Habitaciones" data-select2-container="big-text" tabindex="-1" aria-hidden="true">
										<option value="0" selected>Habitaciones</option>
										<option value="99">Monoambiente</option>
										<option value="1">1 Habitación</option>
										<option value="2">2 Habitaciones</option>
										<option value="3">3 Habitaciones</option>
										<option value="4">4 Habitaciones</option>
										<option value="5">5 Habitaciones</option>
										<option value="6">6 Habitaciones</option>
										<option value="7">7 Habitaciones</option>
										<option value="8">8 Habitaciones</option>
									</select>
									
									
								
								
							</div>
					</div>
					<div class="row">
					
							<div class="col-xs-6 col-sm-3">
									<input type="text" id="precio_desde" class="form-control select2 select2-hidden-accessible" placeholder="Precio Desde" data-select2-container="big-text" rrequired="" tabindex="-1" aria-hidden="true" style="width:200px;font-family:Arial;font-size:14px;color:#555555;"/>
							</div>
							<div class="col-xs-6 col-sm-3">
									<input type="text" id="precio_hasta" class="form-control select2 select2-hidden-accessible" placeholder="Precio Hasta	" data-select2-container="big-text" rrequired="" tabindex="-1" aria-hidden="true" style="width:200px;font-family:Arial;font-size:14px;color:#555555;"/>
							</div>
							<div class="col-xs-6 col-sm-3">
									<input type="text" id="superficie" class="form-control select2 select2-hidden-accessible" placeholder="Superficie Mínima" data-select2-container="big-text" rrequired="" tabindex="-1" aria-hidden="true" style="width:200px;font-family:Arial;font-size:14px;color:#555555;"/>
							</div>
							<div class="col-xs-6 col-sm-3">
								  <button class="btn btn-success"  onclick="buscarPropiedades()">Buscar</button>
							</div>
						</div>
				</div>
			</div>
  </div>
</div>
</div>

<!-- banner -->
<div class="clearfix"></div>

<div class="container">
  <div id="cntBusqueda" style="display:none;" class="properties-listing spacer">
	<div class="h2-style">
		<span>
			PROPIEDADES DESTACADA
		</span>	
	</div>
    <div id="owl-example" class="owl-carousel ">
		<?php echo($arrayImage["cnt_NO_preferenciales"]);	?>
    </div>
  </div>
	<div id="cnt_mapa"  style="pposition:absolute;0left:0px;right:0px;top:30px;height:500px;0border:2px solid red;width:100%;overflow-y:auto;">
	</div>
	</div>
	<div class="clearfix"></div>
	<div class="container">
  <div class="spacer">
    <div class="row">
      <div class="col-lg-12 col-sm-12 recent-view" style="text-align:justify">
        
		<?php echo($_SESSION["overus"]); ?>
      
      </div>
      
    </div>
		</div></div>

	<div class="clearfix"></div>

	
<div class="container">
</div>

<?php include'footer.php';?>