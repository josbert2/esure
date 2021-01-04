<html lang="en">
<head>
<title><?php echo($_SESSION["title"])?></title>
<link type="image/x-icon" href="/icon/"<?php echo($_SESSION["icon"]);?> rel="icon" />


<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

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
              <ul>
                <li>
                  <a href="index.php">
                    <img src="./icon/<?php echo($_SESSION["icon"]);?>" height="100px" alt="Realestate">
                  </a>
                </li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
               <li class="active"><a href="index">Principal</a></li>
                <li><a href="about">Nuestra Empresa</a></li>
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





<!--<div class="container">


<div class="header">
<a href="index.php">
  <img src="/icon/<?php echo($_SESSION["icon"]);?>" height="100px" alt="Realestate">
</a>
</div>

</div>-->