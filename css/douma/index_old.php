

<?php 


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	
	
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/_accordion.js"></script>
<script type="text/javascript" src="/js/_functions.js"></script>
<script type="text/javascript" src="/js/xmlUtil.js"></script>
<script type="text/javascript" src="/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjnKGGqEz8RBxhQ5LalTkv4GxoETKxwAo&callback=initMap" async defer></script>

<link  type="text/css" rel="stylesheet" href="/css/jquery-ui.css"/> 
<link  type="text/css" rel="stylesheet" href="/css/styles.css" >
<link  type="text/css" rel="stylesheet"	media="all" href="/css/ui-lightness/jquery-ui-1.10.3.custom.css"></link>
<link  type="text/css" rel="stylesheet" media="all" href="/css/styles.css"></link>
<link rel="stylesheet" href="/bootstrap-3.3.7-dist/css/bootstrap.min.css"/>
  
<style>








<style>

.carousel-inner > .item > img,
.carousel-inner > .item > a > img {
  width: 70%;
  margin: auto;
}


</style>
<link type="image/x-icon" href="/icon/lebanon.jpg" rel="icon" />	
<title>Owners sell lands at Douma - Batroun </title> 

<script language="javascript">


var periodo ="";
var legajo_supervisor = 0;
var markersArray =[];

var arrayLotes =[{nro_land:1653, price_mtr:'100', size_const:'400 13x13', inversion:'0.9/40%', built:'No',way:'Yes',type_land:'Rustic', build:'No', meters:240,comment:'Little land, good opportunity, good inversion.', price:"24.000", lat:34.206536, lng:35.842001,  image:""},		
					{nro_land:1731, price_mtr:'90',  size_const:'400 13x13',  inversion:'0.9/40%',  built:'Yes',  way:'Yes',type_land:'Rustic and built', build:'Yes', meters:3820,comment:'Family House - Exeptional Location, beautiful view.', price:"1.200.000", lat:34.205564, lng:35.839503, image:"/douma/picturs/1731/image (5).jpg"},
					{nro_land:1847, price_mtr:'150',  size_const:'800 15x15',  inversion:'0.7/30%',  built:'No',  way:'Yes',type_land:'Rustic', build:'Yes', meters:585,comment:'Karen Down, One of three lands of Karen, good location.', price:"87.750", lat:34.200990, lng:35.840572,  image:"/douma/picturs/1847/image (1).jpg"},
					{nro_land:1851, price_mtr:'150',  size_const:'800 15x15',  inversion:'0.7/30%',   built:'No', way:'Yes',type_land:'Rustic', build:'Yes', meters:1133,comment:'Karen Down,  One of three lands of Karen, good location.', price:"169.950", lat:34.200074, lng:35.840575,  image:"/douma/picturs/1847/image (1).jpg"},
					{nro_land:1853, price_mtr:'150',  size_const:'800 15x15',  inversion:'0.7/30%',    built:'No',way:'Yes',type_land:'Rustic', build:'Yes', meters:2301,comment:'Karen Down,  One of three lands of Karen, good location.', price:"345.150", lat:34.200188, lng:35.841381,  image:"/douma/picturs/1847/image (1).jpg"},
					{nro_land:1998, price_mtr:'20',  size_const:'2000 30x30',  inversion:'0.5/5%',  built:'No',  way:'Yes',type_land:'Olivar', build:'Yes', meters:1429,comment:'Olivar, litle land, good oportunity.', price:"28.580", lat:34.210325, lng:35.839305,  image:""},
					{nro_land:3020, price_mtr:'15',  size_const:'1500 20x20',  inversion:'0.1/10%',  built:'No',  way:'Yes',type_land:'Rustic and rocky', build:'Yes', meters:7692,comment:'Ahabe - Olivar, big olives, good price.', price:"115.380", lat:34.201275, lng:35.847509,  image:""},
					{nro_land:3848, price_mtr:'10',  size_const:'3000 35x35',  inversion:'0.5/5%',  built:'No',  way:'No',type_land:'Rustic', build:'Yes', meters:35862,comment:'Sherb, one of five pieces, the best price, good inversion', price:"358.620", lat:34.199036, lng:35.848337,  image:""},
					{nro_land:3849, price_mtr:'10',  size_const:'3000 35x35',  inversion:'0.5/5%',  built:'No',  way:'Yes',type_land:'Rustic', build:'Yes', meters:13094,comment:'Sherb, one of five pieces, the best price, good inversion', price:"130.940", lat:34.198671, lng:35.848585,  image:""},
					{nro_land:3853, price_mtr:'10',  size_const:'3000 35x35',  inversion:'0.5/5%',  built:'No',  way:'Yes',type_land:'Rustic', build:'Yes', meters:12073,comment:'Sherb, one of five pieces, the best price, good inversion', price:"120.730", lat:34.198222, lng:35.848776,  image:""},
					{nro_land:3855, price_mtr:'10',  size_const:'3000 35x35',  inversion:'0.5/5%',  built:'No',  way:'No',type_land:'Rustic', build:'Yes', meters:13922,comment:'Sherb, one of five pieces, the best price, good inversion', price:"139.220", lat:34.198058, lng:35.848883,  image:""},
					{nro_land:3876, price_mtr:'10',  size_const:'3000 35x35',  inversion:'0.5/5%',   built:'No', way:'No',type_land:'Rustic', build:'Yes', meters:20331,comment:'Sherb, one of five pieces, the best price, good inversion', price:"203.310", lat:34.197832, lng:35.848896,  image:""}];				 





$(document).ready(function () {	
	
	var ventana_alto = $(window).height(); 
	var ventana_ancho = $(window).width(); 
	
	$("#cnt_map").css("width",(ventana_ancho -360)+"px");
	$("#cnt_info").css("width",360+"px");	

	setTimeout(function(){ 
		var cnt = arrayLotes.length;
		for(var a=0; a < cnt; a++){
			 cargarPuntoEnMapa(arrayLotes[a].lat, arrayLotes[a].lng, "/icon/esecure_favicon.png",  arrayLotes[a].nro_land);
		}
	}, 2000);
	
	$("#cnt_info").css("height",(ventana_alto-100)+"px");
	
});

function initMap() {
	var objData = {lat:34.202769, lng:35.842335};
	
	map = new google.maps.Map(document.getElementById('cnt_map'), {
	  center: objData,
	  zoom: 15,
	  mapTypeId: google.maps.MapTypeId.SATELLITE
	});
};

function seePicturs(){
	
	var land_number = parseInt($("#land_number").val(),10);
	
	if(isNaN(land_number) || land_number <= 0){
		alert("First, select an icon");
		return;
	}
	
	if(land_number == 1731 || land_number == 1847  || land_number == 1851 || land_number == 1853){
		var ventana_alto = $(window).height(); 
		var ventana_ancho = $(window).width(); 
		
		
		var ventana_alto = (ventana_alto > 676 ? 676 : ventana_alto); 
		var ventana_ancho = (ventana_ancho > 1024 ? 1024 : ventana_ancho); 

		$( "#formConfig" ).dialog({
		  resizable: false,
		  height:ventana_alto,
		  width:ventana_ancho,
		  title:"Pictures",
		  modal: true,
		  buttons: {
			 Cerrar: function(){
			  $( this ).dialog( "close" );
			}
		  }
		});

		jQuery.ajax({
			url:'/douma/consulta.php',
			type:'post',
			async:false,
			data: {action:1, land_number:land_number}
		}).done(
			function(resp){
				$("#formConfig").html(resp);
			}
		);
		
		
	} else {
		alert("This land dont have picturs yet.");
	}
		
	
};

function cargarPuntoEnMapa(lat, lng, icon, land_number){

	var image = {
		//url: icon,
		size: new google.maps.Size(71, 71),
		origin: new google.maps.Point(0, 0),
		anchor: new google.maps.Point(17, 34),
		scaledSize: new google.maps.Size(25, 25)
	};

	
	var shape = {
		coords: [1, 1, 1, 20, 18, 20, 18, 1],
		type: 'poly'
	};
	var myLatLng = {lat,lng};
	
			
	var marker = new google.maps.Marker({
		position: myLatLng,
		map: map,
		shape: shape,
		//icon:image,
		land_number:land_number
	});
	markersArray.push(myLatLng);
	
	 marker.addListener('click', function() {
		
		var cnt = arrayLotes.length;
		for(var a=0; a < cnt; a++){
			//console.info(a +" " +arrayLotes[a].nro_land)
			//{nro_land:1653, meters:240,comment:'', price:24000, lat:34.206536, lng:35.842001},		
			if(arrayLotes[a].nro_land == marker.land_number){
				$("#land_number").val(arrayLotes[a].nro_land);
				$("#size").val(arrayLotes[a].meters);
				$("#price").val(arrayLotes[a].price);
				$("#comment").val(arrayLotes[a].comment);
				$("#lat").val(arrayLotes[a].lat);
				$("#lng").val(arrayLotes[a].lng);	
				$("#build").val(arrayLotes[a].build);

				$("#price_mtr").val(arrayLotes[a].price_mtr);
				$("#size_const").val(arrayLotes[a].size_const);
				$("#inversion").val(arrayLotes[a].inversion);
				$("#built").val(arrayLotes[a].built);
				$("#way").val(arrayLotes[a].way);
				$("#type_land").val(arrayLotes[a].type_land);				
				
				$("#image_01").prop("src", arrayLotes[a].image);
			}
		}
	});
};

</script>

</head>

<body style="overflow:hidden;background-color:#FAFAFA">
	
	<div id="formCabezal" style="position:absolute;top:0px;left:0px;width:100%;height:100px;oborder:2px solid blue;">
			<img src="/icon/ok.png" style="width:20px"></img>We sell 11 land and family house, more information bellow.</br>
			<img src="/icon/ok.png" style="width:20px"></img>We are owners.</br>
		
		Please, select a icon, then click on him and finally enjoy the pictures.<br>
		<img src="/icon/whatsapp.png" style="height:20px"> + 549 -351 5176751 - Eng. Alejandro Nara (All Day) 
		<img src="/icon/mail.png" style="height:20px"> <a href="mailto:alejandroanara@gmail.com">alejandroanara@gmail.com</a>

	</div>
	
	
	<div style="position:absolute;top:20px;left:500px;width:100%;height:100px;oborder:2px solid blue;color:red;font-size:25px;">
		Estimated Location!!!		
	</div>
	
	<div  style="position:absolute;top:0px;right:0px;width:140px;height:100px;oborder:2px solid blue;">
		<!--img src="/icon/lebanon.png" style="width:140px"></img-->
	</div>

	<div id="formConfig" title="" style="display:none;overflow:auto;">	

			
			  
			  
			
	</div>
	
	
	<div id="main"  style="position:absolute;left:0px;top:100px;border:0px solid #6E6E6E;width:100%;height:100%;;0border:2px solid red;">
		
		<div id="cnt_map"  style="position:absolute;left:0px;right:0px;top:0px;0border:2px solid red;width:100%;height:100%;">
		</div>
		<div id="cnt_info"  style="overflow-y:auto;position:absolute;top:0px;0border:5px solid orange;right:0px;height:500px;;">
				<div style="display:inline-block;width:130px;padding-left:10px">Land Number:</div>
				<div style="display:inline;" > 
					<input class="alignRight" disabled type="text" name="land_number" id="land_number"  style="display:inline-block;width:180px;height:25px;oborder:1px solid red;"/>
				</div>
				
				
				<div style="display:inline-block;width:130px;padding-left:10px">Some Way:</div>
				<div style="display:inline;" > 
					<input class="alignRight" disabled type="text" name="way" id="way"  style="display:inline-block;width:180px;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Type Land :</div>
				<div style="display:inline;" > 
					<input class="alignRight" disabled type="text" name="type_land" id="type_land"  style="display:inline-block;width:180px;height:25px;oborder:1px solid red;"/>
				</div>
			
				<div style="display:inline-block;width:130px;padding-left:10px">Size (mt2):</div>
				<div style="display:inline;" > 
					<input  class="alignRight"  disabled type="text" name="size" id="size"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Price US$/mt2:</div>
				<div style="display:inline;" > 
					<input  class="alignRight"  disabled type="text" name="price_mtr" id="price_mtr"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Price Land US$:</div>
				<div style="display:inline;" > 
					<input  class="alignRight" disabled type="text" name="price" id="price"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Built:</div>
				<div style="display:inline;" > 
					<input  class="alignRight" disabled type="text" name="built" id="built"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Inversion:</div>
				<div style="display:inline;" > 
					<input  class="alignRight" disabled type="text" name="inversion" id="inversion"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Size / Const.:</div>
				<div style="display:inline;" > 
					<input  class="alignRight" disabled type="text" name="size_const" id="size_const"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				
				<div style="display:inline-block;width:130px;padding-left:10px">Can Built:</div>
				<div style="display:inline;" > 
					<input  class="alignRight" disabled type="text" name="build" id="build"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Latitude:</div>
				<div style="display:inline;" > 
					<input  class="alignRight" disabled type="text" name="lat" id="lat"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Longitude:</div>
				<div style="display:inline;" > 
					<input  class="alignRight" disabled type="text" name="lng" id="lng"  style="display:inline-block;width:180px;;height:25px;oborder:1px solid red;"/>
				</div>
				
				<div style="display:inline-block;width:130px;padding-left:10px">Comment:</div>
				<div style="display:inline;" > 
					<textarea  disabled id="comment" name="comment"   value="" rows="8" cols="22" ></textarea>
				</div>

				<div style="display:block;width:290px;height:165px;oborder:2px solid blue;" > 
					<img id="image_01" src="" style="width:290px"></img>
				</div>
				<div style="display:inline;" > 
				   <button type="button" class="btn btn-info" onclick="seePicturs()">More Pictures</button>
				</div>
		</div>
	</div>		
</body>
</html>