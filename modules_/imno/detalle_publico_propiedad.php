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


$r =  '../../';

include $r . "includes/functions.php";
include $r ."includes/functions_inmo.php";
include $r .'includes/setDataEnterprise.php';
include $r ."includes/conexion_mysqli.php";

$id_propiedad = isSet($_GET["id"]) ? $_GET["id"] : 0;

?>
<html lang="en">
<head>
<title><?php echo($_SESSION["title"])?></title>


<?php 
$rrPath =  '../..'; 

?>


<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<script type="text/javascript" src="<?php echo $rrPath; ?>/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $rrPath; ?>/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $rrPath; ?>/assets/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo $rrPath; ?>/assets/script.js"></script>
	<script type="text/javascript" src="<?php echo $rrPath; ?>/js/md5.js"></script>	
	<script type="text/javascript" src="<?php echo $rrPath; ?>/js/_functions.js"></script>	
	<script type="text/javascript" src="<?php echo $rrPath; ?>/assets/owl-carousel/owl.carousel.js"></script>
	<script type="text/javascript" src="<?php echo $rrPath; ?>/assets/slitslider/js/modernizr.custom.79639.js"></script>
	<script type="text/javascript" src="<?php echo $rrPath; ?>/assets/slitslider/js/jquery.ba-cond.min.js"></script>
	<script type="text/javascript" src="<?php echo $rrPath; ?>/assets/slitslider/js/jquery.slitslider.js"></script>
	
	<link rel="stylesheet" href="<?php echo $rrPath; ?>/assets/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo $rrPath; ?>/assets/style.css"/>
	<link rel="stylesheet" href="<?php echo $rrPath; ?>/assets/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="<?php echo $rrPath; ?>/assets/owl-carousel/owl.theme.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $rrPath; ?>/assets/slitslider/css/style.css" />
	<link  type="text/css" rel="stylesheet"	media="all" href="<?php echo $rrPath; ?>/css/ui-lightness/jquery-ui-1.10.3.custom.css"></link>
	<link rel="stylesheet" type="text/css" href="<?php echo $rrPath; ?>/assets/slitslider/css/custom.css" />
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjnKGGqEz8RBxhQ5LalTkv4GxoETKxwAo&callback=initMap" async defer></script>
	
	
	<link rel="icon" type="image/png" href="/icon/<?php echo($_SESSION["icon"]);?>"  />
    <style>
		:root {
			--main-color: <?php echo($_SESSION["css_color"]); ?>
		}
      
	</style>
	<link rel="stylesheet" href="<?php echo $rrPath; ?>/css/index.css" />
	<link rel="stylesheet" href="<?php echo $rrPath; ?>/css/dinamic_css/<?php echo($_SESSION["css_name"])?>.css"/>
<!-- slitslider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css">

</head>
<script>

var objData = {};
	objData.id_propiedad = 0;
var arrayPosition = {};
	arrayPosition.lat = ""; 
	arrayPosition.lng = ""; 	
var map ="";
var marker = null;
var markers = [];
	
function initMap(arrayData) {

	if(arguments.length == 0){return}
	myLatLng = arrayData;

	map = new google.maps.Map(document.getElementById('cnt_mapa'), {
	  center: myLatLng,
	  zoom: 12
	});

};

// function consultarImagenes(){
	// var datos 					= {};
	// datos.action 				= 19;
	// datos.id_propiedad 			= objData.id_propiedad 
	
   // jQuery.ajax({
        // async: false,
        // url: '/modules_/imno/consulta.php',
		// type: 'post',
        // data: datos
    // }).done(
        // function (resp) {
			// var data = resp.trim();
			// var aux = data.split("::");
			// if(aux[1] =="OK"){
				
				// // $(".flexslider").remove();
				// // $("#cnt_imagenes").html(aux[2]);
				// // $('.flexslider').flexslider();
				
			// } else {
				// alert(resp);
			// }
			
	    // }
    // );	
// };	
	
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


function inicializarMapa(){
	map = new google.maps.Map(document.getElementById('cnt_mapa'), {
					center: arrayPosition,
					zoom: 4
				});
	initMap(arrayPosition);		
	
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
						  resp[a].importe_operacion,
						  resp[a].comentario,
						  resp[a].comentario_barrio,
						  resp[a].comentario_normativa);
						  
		$("#comentario").html(resp[a].comentario);		
		$("#comentario_barrio").html(resp[a].comentario_barrio);	
		$("#comentario_publicacion").html(resp[a].comentario_publicacion);	

		$("#comentario_normativa").html(resp[a].comentario_normativa);		

		$("#direccion").html(resp[a].direccion);	
		$("#operacion").html(resp[a].operacion);	
		$("#importe_operacion").html(resp[a].importe_operacion);	
		$("#caracteristicas").html(resp[a].caracteristicas);
		
	}
};
function crearCarousel(){
	$(".owl-example-2").slick({

// normal options...
infinite: false,
slidesToShow: 1,
prevArrow: $('.prev'),
nextArrow: $('.next'),
// the magic
responsive: [{

	breakpoint: 1024,
	settings: {
	  slidesToShow: 1,
	  infinite: true
	}

  }, {

	breakpoint: 600,
	settings: {
	  slidesToShow: 1,
	  dots: true
	}

  }, {

	breakpoint: 300,
	settings: "unslick" // destroys slick

  }]
});
}


function cargarPuntoEnMapa(id_propiedad, direccion, latitud, longitud,  icon, operacion, importe_operacion, comentario, comentario_barrio, comentario_normativa){
	 
	var indice =  isNaN(indice) ? 1 : indice;
	 
	var tamanoIcono = 20;
	
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
	//initMap(arrayPosicion);
	
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
	
};


function consultarPropiedades(obj){
	
	$("#loader_center").css("display","");
	
	 setTimeout(function(){
	 
		jQuery.ajax({
			async: false,
			url: '/modules_/imno/consulta.php',
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

$(document).ready(function () {
	//init();
    
	objData.id_propiedad = <?php echo($id_propiedad); ?>;
    var ventana_alto = $(window).height();
	
	var altoSinCabecera = ventana_alto -80;
	var altoTablasPersonas = (altoSinCabecera - 50)/2;
	
	ventana_ancho = $(window).width();
	ventana_alto = $(window).height() - 100;

	
		
	$("#center").css("left", "10px");
	$("#center").css("width", (ventana_ancho) + "px");
	$("#cnt_mapa").css("width", (ventana_ancho-200) + "px");
	

	$("#right").css("left", (ventana_ancho - 360) + "px");
	$("#right").css("width", "360px");

	$("#left").css("height", ventana_alto + "px");
	$("#center").css("height", ventana_alto + "px");
	$("#right").css("height", ventana_alto + "px");
	$("#body").css("height", ventana_alto + "px");
	
	var altoGrillaDatos = (ventana_alto - 55);
	
	//consultarImagenes();

	
	setTimeout(function(){ 
		arrayPosition.lat = parseFloat(<?php echo($_SESSION["lat"])?>);
		arrayPosition.lng = parseFloat(<?php echo($_SESSION["lng"])?>);
		
		inicializarMapa();
			
		var objFiltroPropiedades = {};
				objFiltroPropiedades.id_propietario	 	= 0;
				objFiltroPropiedades.direccion			= "";
				objFiltroPropiedades.action				= 12;
				objFiltroPropiedades.id_propiedad		= objData.id_propiedad ;
				objFiltroPropiedades.id_tipo_operacion		= 3;
				
			crearCarousel();
			 consultarPropiedades(objFiltroPropiedades);
	},1000);	
		
	document.body.onwheel = function() {};
	
	if(ventana_ancho < 1000){
		$("#myCarousel").css("width","380px");
		$("#cnt_mapa").css("width","380px");
		
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
		<div class="navbar-collapse  collapse" >
		  <ul class="nav navbar-nav navbar-right">
		   <li class="active"><a href="/index" style="z-index:99999999">Principal</a></li>
			<li><a href="/about">Nuestra Empresa</a></li>
			<li><a href="/agents">Equipo</a></li>         
		 
			<li><a href="/contact">Contacto</a></li>
			 <li><a href="/login">Ingresar</a></li>
		  </ul>
		</div>
		<!-- #Nav Ends -->

	  </div>
	</div>

</div>
<!-- #Header Starts -->





<div class="container" style="width:100%;padding:0">
<div class="header" style="background-color:#ffffff;width:100%;">
<a href="index.php"></a>


<ul class="pull-right">
</ul>
</div>
</div>



<div class="container">
  <div class="col-md-4" style=";webkit-box-shadow: 3px 3px 7px #BBBBBB; box-shadow: 3px 3px 7px #BBBBBB;font-size:15px;padding: 10px 10px;">
							<!--span style="font-weight:bold">Comentario General</span><div id="comentario"></div>
							<hr>
							<span style="font-weight:bold">Comentario del Barrio</span><div id="comentario_barrio"></div>
							<hr-->
							<span style="font-weight:bold">Comentario Publicación</span><div id="comentario_publicacion"></div>
							<hr>
							<!--h5 style="padding-bottom:5px;" class="">
							<span style="font-weight:bold">Normativa</span>	<div id="comentario_normativa"></div>
							<hr-->
							<h5 style="padding-bottom:5px;" class="">
							<span style="font-weight:bold">Dirección</span>	<div id="direccion"></div>	
							</h5>
							<h5 style="padding-top:7px;">
							<span style="font-weight:bold">Operación</span>	<div id="operacion"></div>		
							</h5>
							<h5 style="padding-top:7px;">
							<span style="font-weight:bold">Importe</span>	<div id="importe_operacion"></div>		
							</h5>
							<hr>
							<h5 style="padding-top:7px;">
							<span style="font-weight:bold">Características</span>	<div id="caracteristicas" style="0border:3px solid blue;padding-left:10px;"></div>		
							</h5>
							<hr>
							
						</div>
						
						<div id="cnt_imagenes" class="col-md-8" style="0border:5px solid blue;;height:500px;">
								<?php echo(getImagenesPublicas($conn, $id_propiedad)); ?>
				
						</div>
</div>	
<!-- Buscador -->
	
<!-- banner -->
<div class="clearfix"></div>


				

<div class="container">
  
	<div id="cnt_mapa"  style="pposition:absolute;0left:0px;right:0px;top:30px;height:500px;0border:5px solid red;width:60%;overflow-y:auto;">
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

<?php include'../../footer.php';?>