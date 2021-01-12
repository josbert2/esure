<?php

$data = "<a target=\"blank\" href=\"https://api.whatsapp.com/send?phone=".$_SESSION["INMO_TE"]."&text=Hola, estoy en tu sitio y necesito información
 \">".
	"<img src=\"./icon/what.svg\" alt=\"Escribinos y pronto te responderemos\" style=\"display: block!important;".
	"	border-radius: 50%!important;".
	"	text-align: center!important;".
  "	position: fixed!important;".
  " background-color: #fff;".
	"	bottom: 15px!important;".
	"	z-index: 999!important;".
	"	left: 20px!important;".
	"	box-shadow: 2px -1px 6px 0px rgba(0, 0, 0, 0.12)!important;".
	"	font-size: 3px!important;".
  "	max-height: 80px!important;cursor:pointer;".
  
  " padding: 8px;\"/>".
"</a>";

echo($data);
?>

<!--<img style="position:absolute;top:5px;left:10px;z-index:9999;max-width::<?php echo($_SESSION["max_width"])?>px;height:<?php echo($_SESSION["width"])?>px;" src="/icon/<?php echo($_SESSION["icon"]);?>" wwidth="20px" alt="Realestate">
-->

<div class="footer">

<div class="container">



<div class="row">
            <div class="col-lg-3 col-sm-3">
                   <h4>Información</h4>
                   <ul class="row" id="Pie">
                <li class="col-lg-12 col-sm-12 col-xs-3"><a href="about.php" style="font-size: 14px; text-decoration: none; ">Principal</a></li>
                <li class="col-lg-12 col-sm-12 col-xs-3"><a href="agents.php" style="font-size: 14px; text-decoration: none;" >Equipo</a></li>         
                <li class="col-lg-12 col-sm-12 col-xs-3"><a href="contact.php" style="font-size: 14px; text-decoration: none;" >Contacto</a></li>
              </ul>
            </div>
            
            <div class="col-lg-3 col-sm-3">
                    <!--h4>Newsletter</h4>
                    <p>Notificarme sobre las últimas propiedades</p>
                    <form class="form-inline" role="form">
                            <input type="text" placeholder="Enter Your email address" class="form-control">
                                <button class="btn btn-success" type="button">Notificarme!</button></form-->
            </div>
            
            <div class="col-lg-3 col-sm-3">
                    <h4>Seguinos en Nuestras Redes</h4>
                    <a href="<?php echo($_SESSION["facebook"]);?>" style="font-size: 14px; text-decoration: none;"><img src="./images/facebook.png" alt="facebook"></a>
                    <a  href="<?php echo($_SESSION["twitter"]);?>" style="font-size: 14px; text-decoration: none;"><img src="./images/twitter.png" alt="twitter"></a>
                    <a href="<?php echo($_SESSION["linkedin"]);?>" style="font-size: 14px; text-decoration: none;"><img src="./images/linkedin.png" alt="linkedin"></a>
                    <a href="<?php echo($_SESSION["instagram"]);?>" style="font-size: 14px; text-decoration: none;"><img src="./images/instagram.png" alt="instagram"></a>
            </div>

             <div class="col-lg-3 col-sm-3">
                    <h4>Contactenos</h4>
                    <p><b><?PHP echo($_SESSION["INMO_DENOM"]); ?></b><br>
<span class="glyphicon glyphicon-map-marker"></span><?PHP echo($_SESSION["INMO_ADRESS"]); ?><br>
<span class="glyphicon glyphicon-envelope"></span> <?PHP echo($_SESSION["EMAIL_FROM"]); ?>	<br>
<span cclass="glyphicon glyphicon-earphone"></span>

	<?PHP 
		
		?></p>
            
			
			
			</div>
        </div>
		
	
	 
	 
		 
	
		
		
<p class="copyright"></p>


</div></div>




<!-- Modal -->
<div id="loginpop" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="row">
        <div class="col-sm-6 login">
        <h4>Login</h4>
          <form class="" role="form">
        <div class="form-group">
          <label class="sr-only" for="exampleInputEmail2">Email</label>
          <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label class="sr-only" for="exampleInputPassword2">Password</label>
          <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password">
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox"> Recordarme
          </label>
        </div>
        <button type="submit" class="btn btn-success">Ingresar</button>
      </form>          
        </div>
        <!--div class="col-sm-6">
          <h4>New User Sign Up</h4>
          <p>Join today and get updated with all the properties deal happening around.</p>
          <button type="submit" class="btn btn-info"  onclick="window.location.href='register.php'">Join Now</button>
        </div-->

      </div>
    </div>
  </div>
</div>
<!-- /.modal -->


<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
  $('.slick-img').slick();
</script>
</body>
</html>



