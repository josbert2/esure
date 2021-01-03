<?PHP if(session_id()==''){session_start();}

	include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
	include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
	include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions_inmo.php');

	$nombre_contacto 	= isSet($_GET["nombre_contacto"]) ? $_GET["nombre_contacto"] : ""; 
	$email_contacto 	= isSet($_GET["email_contacto"]) ? $_GET["email_contacto"] : ""; 
	$telefono_contacto 	= isSet($_GET["telefono_contacto"]) ? $_GET["telefono_contacto"] : ""; 
				
				
?>

<html lang="en">
<head>
<title><?php echo($_SESSION["title"])?></title>
<link rel="icon" type="image/png" href="/icon/<?php echo($_SESSION["icon"]);?>"  />
<link rel="stylesheet" href="estilos/estilos.css"/>

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
<script>


$(document).ready(function () {
	/*
	$nombre_contacto 	= isSet($_GET["nombre_contacto"]) ? $_GET["nombre_contacto"] : ""; 
	$email_contacto 	= isSet($_GET["email_contacto"]) ? $_GET["email_contacto"] : ""; 
	$telefono_contacto 	= i
	*/
	
	$("#name").val('<?php echo($nombre_contacto);?>');
	$("#email_").val('<?php echo($email_contacto);?>');
	
	$("#fono").val('<?php echo($telefono_contacto);?>');
	
	if('<?php echo($nombre_contacto);?>' != ""){
		$("#mensaje").focus();
	}
	
});	

function enviarMail(){
	var name = $("#name").val();
	var email = $("#email_").val();
	var message = $("#mensaje").val();
	var fono = $("#fono").val();
	
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
		$("#mensaje").focus();
		return;
	}
	
	if(fono ==""){
		alert("Ingrese un teléfono de contacto.");
		$("#fono").focus();
		return;
	}
	
	jQuery.ajax({
		async:false,  
		url:'/modules_/imno/saveData.php',
		type:'post',
		data: {action:33,name:name,email:email, fono:fono, message:message}
		
	}).done(
		function(resp){
			resp  = resp.trim();
			
			if(resp =="OK"){
				window.location = "/contact.php";
				alert("Gracias por comunicarse, en breve nos contactaremos.");
				$("#name").val("");
				$("#email_").val("");
				$("#mensaje").val("");
				$("#fono").val("");
			} else {
				alert(resp);
			}
		}
	);
};

</script>

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
             
                <li class="active"><a href="contact">Contacto</a></li>
				 <li><a href="login">Ingresar</a></li>
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




<!-- banner -->
<div class="inside-banner">
  <div class="container"> 
    <span class="pull-right"><a href="#">Home</a> / Contactarnos</span>
    <h2>Contactarnos</h2>
</div>
</div>
<!-- banner -->


<div class="container">
<div class="spacer">
<div class="row contact">
  <div class="col-lg-6 col-sm-6 ">


		<input type="text" id="name" class="form-control" placeholder="Nombre Completo">
		<input type="text" id="email_" class="form-control" placeholder="Correo Electrónico">
		<input type="text" id="fono" class="form-control" placeholder="Número de Teléfono">
		<textarea rows="6" id="mensaje" maxlength="1000" class="form-control" placeholder="Mensaje"></textarea>
      <button type="submit" onclick="enviarMail()" class="btn btn-esecure" name="Submit">Enviar Mensaje</button>
          


                
        </div>
  <div class="col-lg-6 col-sm-6 ">
  <div class="well">
  <iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
  src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Córdoba&amp;aq=0&amp;oq=pulch&amp;sll=-31.4381219,-64.1586833&amp;sspn=-31.4381219,-64.1586833&amp;ie=UTF8&amp;hq=&amp;hnear=Córdoba+Argetina&amp;ll=-31.4381219,-64.1586833&amp;spn=0.001347,0.002642&amp;t=m&amp;z=14&amp;output=embed"></iframe></div>
  </div>
</div>
</div>
</div>

<?php include'footer.php';?>