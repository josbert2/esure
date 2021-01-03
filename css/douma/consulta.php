<?php 
		
		$action = isset($_POST["action"]) ? $_POST["action"] : 0;
		$land_number = isset($_POST["land_number"]) ? $_POST["land_number"] : 0;
		
		$retorno ="<div id=\"myCarousel\" class=\"carousel slide\" data-ride=\"carousel\">";
		// echo ("action ----".$action);
		
		// exit();
		switch ($action){
			case 1:{// Retornamos Datos de los Contratistas. //v2.01
				
				if($land_number == 1731){//casa
					
						$retorno .="<ol class=\"carousel-indicators\">".
									"<li data-target=\"#myCarousel\" data-slide-to=\"0\" class=\"active\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"1\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"2\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"3\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"4\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"5\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"6\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"7\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"8\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"9\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"10\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"11\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"12\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"13\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"14\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"15\"></li>".
								"</ol>".
								  "<div class=\"carousel-inner\" role=\"listbox\">".
									"<div class=\"item active\">".
										"<img src=\"/douma/picturs/1731/image (1).jpg\" alt=\"Chania\">".
									"</div>".
									"<div class=\"item\">".
									 " <img src=\"/douma/picturs/1731/image (2).jpg\" alt=\"Chania\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (3).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (4).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (5).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (6).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (7).jpg\" alt=\"Flower\">".
								"	</div>".
									"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (8).jpg\" alt=\"Flower\">".
								"	</div>".
									"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (9).jpg\" alt=\"Flower\">".
								"	</div>".
									"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (10).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (11).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (12).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (13).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (14).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (15).jpg\" alt=\"Flower\">".
								"	</div>".
								"	<div class=\"item\">".
								"	  <img src=\"/douma/picturs/1731/image (16).jpg\" alt=\"Flower\">".
								"	</div>".
								"  </div>".
								"  <a class=\"left carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"prev\">".
								"	<span class=\"glyphicon glyphicon-chevron-left\" aria-hidden=\"true\"></span>".
								"	<span class=\"sr-only\">Previous</span>".
								"  </a>".
								"  <a class=\"right carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"next\">".
								"	<span class=\"glyphicon glyphicon-chevron-right\" aria-hidden=\"true\"></span>".
								"	<span class=\"sr-only\">Next</span>".
								" </a>";
					
				} else if($land_number == 1847 || $land_number == 1851  || $land_number == 1853 ){
					$retorno .="<ol class=\"carousel-indicators\">".
									"<li data-target=\"#myCarousel\" data-slide-to=\"0\" class=\"active\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"1\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"2\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"3\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"4\"></li>".
									"<li data-target=\"#myCarousel\" data-slide-to=\"5\"></li>".
								"</ol>".
								  "<div class=\"carousel-inner\" role=\"listbox\">".
										"<div class=\"item active\">".
											"<img src=\"/douma/picturs/1847/image (1).jpg\" alt=\"Chania\">".
										"</div>".
										"<div class=\"item\">".
										 " <img src=\"/douma/picturs/1847/image (2).jpg\" alt=\"Chania\">".
									"	</div>".
									"	<div class=\"item\">".
									"	  <img src=\"/douma/picturs/1847/image (3).jpg\" alt=\"Flower\">".
									"	</div>".
										"	<div class=\"item\">".
									"	  <img src=\"/douma/picturs/1847/image (4).jpg\" alt=\"Flower\">".
									"	</div>".
										"	<div class=\"item\">".
									"	  <img src=\"/douma/picturs/1847/image (5).jpg\" alt=\"Flower\">".
									"	</div>".
										"	<div class=\"item\">".
									"	  <img src=\"/douma/picturs/1847/image (6).jpg\" alt=\"Flower\">".
									"	</div>".
								"  </div>".
								"  <a class=\"left carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"prev\">".
								"	<span class=\"glyphicon glyphicon-chevron-left\" aria-hidden=\"true\"></span>".
								"	<span class=\"sr-only\">Previous</span>".
								"  </a>".
								"  <a class=\"right carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"next\">".
								"	<span class=\"glyphicon glyphicon-chevron-right\" aria-hidden=\"true\"></span>".
								"	<span class=\"sr-only\">Next</span>".
								" </a>";
					
				}
				break;
			}
		};	
		$retorno .= "</div>";
		
		echo ($retorno);
?>