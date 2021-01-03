<?php header('Content-Type: text/html; charset=utf8'); ?>
<?php 

session_start() ;
include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions_inmo.php');

?>

<html lang="en">
<head>
<title><?php echo($_SESSION["title"])?></title>
	<link rel="icon" type="image/png" href="/icon/<?php echo($_SESSION["icon"]);?>"  />


<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="/css/dinamic_css/<?php echo($_SESSION["css_name"])?>.css"/>

 	<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="/assets/style.css"/>
	
	

	<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="/assets/script.js"></script>
	<script type="text/javascript" src="/js/md5.js"></script>	
	<script type="text/javascript" src="/js/_functions.js"></script>	
	<script type="text/javascript" src="/assets/owl-carousel/owl.carousel.js"></script>
	
	<!-- Owl stylesheet -->
	
	<link rel="stylesheet" href="/assets/owl-carousel/owl.carousel.css">
	<link rel="stylesheet" href="/assets/owl-carousel/owl.theme.css">
	<!-- Owl stylesheet -->


<!-- slitslider -->
    <link rel="stylesheet" type="text/css" href="/assets/slitslider/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/slitslider/css/custom.css" />
    <script type="text/javascript" src="/assets/slitslider/js/modernizr.custom.79639.js"></script>
    <script type="text/javascript" src="/assets/slitslider/js/jquery.ba-cond.min.js"></script>
    <script type="text/javascript" src="/assets/slitslider/js/jquery.slitslider.js"></script>
	<link  type="text/css" rel="stylesheet"	media="all" href="/css/ui-lightness/jquery-ui-1.10.3.custom.css"></link>

<!-- slitslider -->

</head>

<body>


<!-- Header Starts -->
<div class="navbar-wrapper">

        <div class="navbar-inverse" role="navigation">
          <div class="container">
            <div class="navbar-header">


              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>

            </div>


            <!-- Nav Starts -->
            <div class="navbar-collapse  collapse">
              <ul class="nav navbar-nav navbar-right">
               <li ><a href="index">Principal</a></li>
                <li><a href="about">Nuestra Empresa</a></li>
                <li><a href="agents">Equipo</a></li>         
             
                <li><a href="contact">Contacto</a></li>
				 <li class="active"><a href="login">Ingresar</a></li>
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

<script>
		
		
		
$(document).ready(function () {
//$("#email").val("info@esecure.com.ar");

});	
function ingresar(){
	
	$("#conectado").val(($("#chk_conectado").is(":checked") ? 1 : 0));
	
	if($("#chk_conectado").is(":checked")){
		document.cookie = "username="+$("#email").val();
		document.cookie = "password="+$("#password").val();
	} else {
		document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
		document.cookie = "password=; expires=Thu, 01 Jan 1970 00:00:00 UTC";	
		
	}
	var clave = $("#password").val();
               
	if(isNaN(parseInt(clave,10))){
		clave = calcMD5(clave);
	}

	$("#password").val(clave) ;
	
	//$("#frm_ingreso").submit();
	 jQuery.ajax({
		async:false,  
		url:'/includes/control_ingreso.php',
		type:'post',
		data: {username:$("#email").val(),password:$("#password").val()}
		
	}).done(
		function(resp){
			resp  = resp.trim();
			var aux = resp.split("::");
			if(aux[0]=="OK"){
				window.location = aux[1];
			} else {
				alert(resp);
			}
		}
	);
			
};


function enviarMail(){
	var name = $("#name").val();
	var email = $("#email_").val();
	var message = $("#message").val();
	
	if(!validarEmail(email) || email ==""){
		alert("La dirección de email ingresada parece no ser válida.");
		$("#email_").focus();
		return;
	}
	
	if(name ==""){
		alert("Ingrese su nombre por favor.");
		$("#name").focus();
		return;
	}
	
	if(message ==""){
		alert("Ingrese un comentario por favor.");
		$("#message").focus();
		return;
	}
	
	jQuery.ajax({
		async:false,  
		url:'/contacto.php',
		type:'post',
		data: {name:name,email:email, message:message}
		
	}).done(
		function(resp){
			resp  = resp.trim();
			
			if(resp =="OK"){
				window.location = "/index.php";
				alert("Email enviado correctamente");
			} else {
				alert(resp);
			}
		}
	);
};

function verFormularioDemo(){
	
	
	  document.location.href = "#formFree";

	//$("#formFree").css("display","inline");

};


function leerTecla(event){
	var chCode = ('charCode' in event) ? event.charCode : event.keyCode;
	if(chCode == 13){
		ingresar();
	}
}

function enviarMail_free(){
	
	var name_free = $("#name_free").val();
	var email_free = $("#email_free").val();
	var empresa_free = $("#empresa_free").val();
	var cuit_empresa = $("#cuit_empresa").val();
	
	
	if(!validarEmail(email_free) || email_free ==""){
		alert("La dirección de email ingresada parece no ser válida.");
		$("#email_free").focus();
		return;
	}
	
	if(name_free ==""){
		alert("Ingrese su nombre por favor.");
		$("#name_free").focus();
		return;
	}
	
	if(empresa_free ==""){
		alert("Ingrese el nombre de su empresa.");
		$("#empresa_free").focus();
		return;
	}
	
	if(cuit_empresa ==""){
		alert("Ingrese el CUIT de su empresa.");
		$("#cuit_empresa").focus();
		return;
	}
	
	
	jQuery.ajax({
		async:false,  
		url:'/contacto_free.php',
		type:'post',
		data: {name_free:name_free,email_free:email_free, empresa_free:empresa_free,cuit_empresa:cuit_empresa}
		
	}).done(
		function(resp){
			resp  = resp.trim();
			
			if(resp =="OK"){
				window.location = "/index.php";
				alert("Email enviado correctamente");
			} else {
				alert(resp);
			}
		}
	);
};

</script>
<!-- banner -->
<div class="inside-banner">
  <div class="container"> 
    <h2>Ingresar</h2>
</div>
</div>
<!-- banner -->


<div class="container">
<div class="spacer">
<div class="row contact">
  <div class="col-lg-6 col-sm-6 ">


				<input type="text" class="form-control" placeholder="Ingrese su mail *" id="email" required="" data-validation-required-message="Please enter your name.">
				<input type="password"  class="form-control" onkeypress="leerTecla(event)" placeholder="Ingrese contraseña *" id="password" required="" data-validation-required-message="Please enter your email address.">

				<button type="submit" onclick="ingresar()" class="btn btn-esecure" name="Submit">Ingresar</button>
          


                
        </div>
	<div class="col-lg-6 col-sm-6 ">
		
  </div>
</div>
</div>
</div>

<?php include'footer.php';?>