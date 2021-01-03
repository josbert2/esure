<?PHP
	// $dato = "\'30-63101883-8\'";
	// $dato = str_replace("\\", "", $dato);
	
	// echo($dato);
	// exit();
	
	$validacion = isset($_GET["validacion"]) ? $_GET["validacion"] : 0;
	$cerrar = isset($_GET["cerrar"]) ? $_GET["cerrar"] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">


<link rel="stylesheet" href="css/site_reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/site_layout.css" type="text/css" media="all">
<link rel="stylesheet" href="css/site_style.css" type="text/css" media="all">


	<link  type="text/css" rel="stylesheet"	media="all" href="/css/ui-lightness/jquery-ui-1.10.3.custom.css">



<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/_functions.js"></script>
<script type="text/javascript" src="/js/md5.js"></script>


<script type="text/javascript" src="js/site_cufon-yui.js"></script>
<script type="text/javascript" src="js/site_cufon-replace.js"></script>
<script type="text/javascript" src="js/site_Didact_Gothic_400.font.js"></script>
<script type="text/javascript" src="js/site_Shanti_400.font.js"></script>
<script type="text/javascript" src="js/site_roundabout.js"></script>
<script type="text/javascript" src="js/site_roundabout_shapes.js"></script>
<script type="text/javascript" src="js/site_jquery.easing.1.2.js"></script>
<script type="text/javascript" src="js/site_script.js"></script>




<link type="image/x-icon" href="/icon/esecure_favicon.png" rel="icon" />
<title>Control de Documentación de Contratistas</title> 


<script type="text/javascript" >



function obtenerCookie(clave) {
    var name = clave + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

$(document).ready(function() {
	
	
	
	$('#myRoundabout').roundabout({
		 shape: 'square',
		 minScale: 0.89, // tiny!
		 maxScale: 1, // tiny!
		 easing:'easeOutExpo',
		 clickToFocus:'true',
		 focusBearing:'0',
		 duration:800,
		 reflect:true
	});
	
	Cufon.now()
	
	var validacion = parseInt('<?php echo($validacion); ?>',10);
	
	if(validacion == -1){
	
		var texto ="<span class=\"fondoNegro\">Error de Ingreso</span></br>"+
		"El nombre de usuario o la clave es inválida.</br>"+
		"<span class=\"fondoNegro\">Por favor verifique los datos ingresados y reintente nuevamente.</span></br>"+
		"Si usted copio y pego la contraseña, intente borrar el últmo caracter y reintentar.</br>"+
		"Si el problema persiste intente cambiar su contraseña desde la opción habilitada o comuniquese con el administrador del sistema.";

	
		$("#form-ayuda").html(texto);
		
		$("#form-ayuda").css("display", "");

		 $("#form-ayuda").dialog({
			resizable: false,
			title: "Ingreso Inválido",
			height: 400,
			width: 720,
			modal: true
		});
	
	}
	
	var cerrar = '<?php echo($cerrar) ?>';
	
	
	var password = obtenerCookie("password") ;	
	var username = obtenerCookie("username") ;	
	
	if(username !="" && password !=""){
		$("#chk_conectado").prop("checked" ,true);
		
		$("#email").val(username);
		$("#password").val(password);
		
		if(cerrar == ""){
			ingresar();
		}
	}
	
	

	
});

function setearConectado(){
	if(!$("#chk_conectado").is(":checked")){
		document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
		document.cookie = "password=; expires=Thu, 01 Jan 1970 00:00:00 UTC";	
	}
};

function ingresar(){
	$("#conectado").val(($("#chk_conectado").is(":checked") ? 1 : 0));
	
	if($("#chk_conectado").is(":checked")){
		document.cookie = "username="+$("#email").val();
		document.cookie = "password="+$("#password").val();
	} else {
		document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
		document.cookie = "password=; expires=Thu, 01 Jan 1970 00:00:00 UTC";	
		
	}
			
	$("#password").val(calcMD5($("#password").val())) ;
	$("#frm_ingreso").submit();
};

function passwordRecover(){
	$( "#form-forget-password" ).css("display","");
	
	$( "#form-forget-password" ).dialog({
      resizable: false,
      height:200,
	  width:350,
      modal: true,
      buttons: {
        "Restaurar Clave": function() {
          
			if($("#find-email").val() == ""){
				alert("Debe ingresar la dirección de email a la cual le enviaremos una nueva contraseña.");
				$("#find-email").focus();
				return;
			}
			if($("#cuit").val() == ""){
				alert("Debe ingresar su CUIT/CUIL.");
				$("#cuit").focus();
				return;
			}
		   var password = getPasswordAleatorio();
		   var password_md5 = calcMD5(password);
		   
		   
		  jQuery.ajax({
				async:false,  
				url:'/modules_/general/saveData.php',
				type:'post',
				data: {email_adress:$("#find-email").val(),cuit:$("#cuit").val(),password:password,password_md5:password_md5 ,tipoabm:7}
				
			}).done(
				function(resp){
					resp  =resp.trim();
					if(resp=="OK"){
						alert("Hemos enviando una nueva clave a su dirección de correo.");
					} else {
						alert(resp);
					}
				}
			);
		  $( this ).dialog( "close" );
        },
        Cancel: function() {
		
          $( this ).dialog( "close" );
        }
      }
    });
};



</script>


</head>
<body id="page1">
	
	<div id="form-ayuda" title="Ayuda del Sistema" style="display:none;height:300px;"></div>
	
	<div  style="padding:10px;">
		<img src="/icon/esecure_300x100.png" height="60px">
	</div>
	<div class="body1">
	<div class="main">
<!-- header -->
		<header>
			
			<?PHP 	include $_SERVER['DOCUMENT_ROOT']."/modules_/general/site_general_header.php";?>
	
			<div class="wrapper">
				<nav>
					<?PHP 	include $_SERVER['DOCUMENT_ROOT']."/modules_/general/site_marquesina.php";?>
				</nav>
				
				<?PHP 	include $_SERVER['DOCUMENT_ROOT']."/modules_/general/site_redesSoiales.php";?>	
			</div>
			<div 	class="wrapper" style="oborder:3px solid blue">
				<h1><a href="index" did="logo"></a></h1>
				
				<nav>
					<ul id="menu">
						<li id="menu_active"class="nav1" ><a href="index"><span><span>Inicio</span></span></a></li>
						<li><a href="institucional"><span><span>Institucional</span></a></li>
						<li class="nav3"><a href="servicios"><span><span>Servicios</span></span></a></li>
						<li  class="nav4"><a href="clientes"><span><span>Clientes</span></span></a></li>
						<li class="nav5"><a href="contacto"><span><span>Contactos</span></span></a></li>
					</ul>
				</nav>
			</div>
			<div class="wrapper" style="0border:3px solid red;;">
				<div class="text">
					<span class="tittle">El mejor<span>Software del mercado</span></span>
					<!--p>Gestioná a tus empresas tercerizadas<span>como nunca antes lo hiciste</span><span>sistema totalmente amigable con interfaces muy simples de usar.</span><span></span></p-->
						<p>Controlá quien ingresa a tu empresa mitigando el riesgo de Responsabilidad Solidaria.</p>
						<p>Asegurá el mantenimiento y los vencimientos de tu flota vehicular.</p>
					<!--a href="#" class="button1">More Info</a-->
				</div>
				<div id="gallery">
					<ul id="myRoundabout">
						<li><img src="images/status.jpg" alt=""></li>
						<li><img src="images/personas.jpg" alt=""></li>
						<li><img src="images/vehicles.jpg" alt=""></li>
					</ul>
				</div>
			</div>
		</header>
		
	</div>
</div>

<!-- footer -->
<?PHP 	include $_SERVER['DOCUMENT_ROOT']."/modules_/general/site_footer.php";?>	



	<div id="form-forget-password" title="Recuperaci&oacute;n de Clave de Usuario" style="display:none;">
		<p>
			<div > 
				<div > 
					<div style="display:inline-block;width:140px;padding-left:10px" > Cuit/Cuit<span style="color:black"> *</span>
					</div>
					
					<div style="display:inline-block;width:70px" > 
						<input type="text" name="cuit" id="cuit" value=""  style="width:170px"/>
					</div>
				</div>
				<div style="display:inline-block;width:140px;padding-left:10px" > Dirección de Correo<span style="color:black"> *</span>
				</div>
				
				<div style="display:inline-block;width:70px" > 
					<input type="text" name="find-email" id="find-email" value=""  style="width:170px"/>
				</div>
			</div>
		</p>
	</div>



</body>
</html>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-64253253-1', 'auto');
  ga('send', 'pageview');

</script>