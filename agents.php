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
		   <li><a href="index">Principal</a></li>
			<li><a href="about">Nuestra Empresa</a></li>
			<li class="active"><a href="agents">Equipo</a></li>         
		 
			<li><a href="contact">Contacto</a></li>
			 <li><a href="login">Ingresar</a></li>
		  </ul>
		</div>
		<!-- #Nav Ends -->

	  </div>
	</div>

</div>






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
    <span class="pull-right"><a href="#">Home</a> / Agentes</span>
    <h2>Agentes</h2>
</div>
</div>
<!-- banner -->


<div class="container">
<div class="spacer agents">

<div class="row">
  <div class="col-lg-8  col-lg-offset-2 col-sm-12">
      <div class="row">
	  
		<?php if($_SESSION["agente_01_name"] !=""){?>
		
		<div class="col-lg-2 col-sm-2 "><a href="#"><img src="<?php echo($_SESSION["agente_01_picture"]); ?>" class="img-responsive"  alt="agent name"></a></div>
        <div class="col-lg-7 col-sm-7 "><span style="font-weight:bold"><?php echo($_SESSION["agente_01_name"]); ?></span><br><?php echo($_SESSION["agente_01_data"]); ?>
		</p></div>
        <div class="col-lg-3 col-sm-3 "><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:tuinmo@hotmail.com"><?php echo($_SESSION["agente_01_mail"]); ?></a><br>
        <span class="glyphicon glyphicon-earphone"></span><?php echo($_SESSION["agente_01_celu"]); ?>	</div>
		
		<?php } ?>
      </div>
	  
	  <!-- agents -->
	  
	  <div class="row">
	  	<?php if($_SESSION["agente_02_name"] !=""){?>
		
		<div class="col-lg-2 col-sm-2 "><a href="#"><img src="<?php echo($_SESSION["agente_02_picture"]); ?>" class="img-responsive"  alt="agent name"></a></div>
        <div class="col-lg-7 col-sm-7 "><span style="font-weight:bold"><?php echo($_SESSION["agente_02_name"]); ?></span><br><?php echo($_SESSION["agente_02_data"]); ?>
		</p></div>
        <div class="col-lg-3 col-sm-3 "><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:tuinmo@hotmail.com"><?php echo($_SESSION["agente_02_mail"]); ?></a><br>
        <span class="glyphicon glyphicon-earphone"></span><?php echo($_SESSION["agente_02_celu"]); ?>	</div>
     
		<?php } ?>
	 </div>
	  
	  
	   <div class="row">
	   
		<?php if($_SESSION["agente_03_name"] !=""){?>
		<div class="col-lg-2 col-sm-2 "><a href="#"><img src="<?php echo($_SESSION["agente_03_picture"]); ?>" class="img-responsive"  alt="agent name"></a></div>
        <div class="col-lg-7 col-sm-7 "><?php echo($_SESSION["agente_03_name"]); ?><?php echo(": ".$_SESSION["agente_03_data"]); ?>
		</p></div>
        <div class="col-lg-3 col-sm-3 "><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:tuinmo@hotmail.com"><?php echo($_SESSION["agente_03_mail"]); ?></a><br>
        <span class="glyphicon glyphicon-earphone"></span><?php echo($_SESSION["agente_03_celu"]); ?>	</div>
      
		<?php } ?>
	  </div>
      
	  
	  <div class="row">
	  
		<?php if($_SESSION["agente_04_name"] !=""){?>
		<div class="col-lg-2 col-sm-2 "><a href="#"><img src="<?php echo($_SESSION["agente_04_picture"]); ?>" class="img-responsive"  alt="agent name"></a></div>
        <div class="col-lg-7 col-sm-7 "><?php echo($_SESSION["agente_04_name"]); ?><?php echo(": ".$_SESSION["agente_04_data"]); ?>
		</p></div>
        <div class="col-lg-3 col-sm-3 "><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:tuinmo@hotmail.com"><?php echo($_SESSION["agente_04_mail"]); ?></a><br>
        <span class="glyphicon glyphicon-earphone"></span><?php echo($_SESSION["agente_04_celu"]); ?>	</div>
     
		<?php } ?>
	</div>
       <!-- agents -->
     
      <!-- agents -->
      
  </div>
</div>


</div>
</div>

<?php include'footer.php';?>