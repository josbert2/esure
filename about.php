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
	
	<!-- Owl stylesheet -->
	


<!-- slitslider 

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
               <li><a href="index">Principal</a></li>
                <li class="active"><a href="about">Nuestra Empresa</a></li>
                <li><a href="agents">Equipo</a></li>         
             
                <li><a href="contact">Contacto</a></li>
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


<div class="container">
	<div class="spacer">
		<div class="row">
				 
			<div class="col-lg-2">
			</div>	
			
			<div class="col-lg-8">
				  <img src="<?php echo($_SESSION["about_picture"]);?>" class="img-responsive thumbnail"  alt="realestate">
			</div>	
			
			<div class="col-lg-2">
			</div>	
			
		</div>
		
	</div>
	<div class="row" >
		<div class="col-lg-12">

			<h3>Nuestra Compania</h3>
			  
			 <?php echo($_SESSION["owerCompany"]); ?>
			  <h3>Nuestros Valores</h3>
			 <?php echo($_SESSION["owerValues"]); ?>
		</div>		
	</div>
	
	
</div>



<?php include'footer.php';?>