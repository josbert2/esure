<?php 
session_start();
include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/constantes.php');

header("Cache-Control: no-cache, must-revalidate"); // Evitar guardado en cache del cliente HTTP/1.1

date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_ALL,"es_ES");
setlocale(LC_TIME, "spanish"); //Fijamos el tiempo local







?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://Entrega_Recibow.w3.org/TR/html4/strict.dtd">
	
	
<html xmlns="" xml:lang="es-es" lang="es-es" dir="ltr" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>




<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">

<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/graphics/loader.js"></script>
<script type="text/javascript" src="/js/graphics/fusioncharts.js"></script>
<script type="text/javascript" src="/js/graphics/fusioncharts.theme.fusion.js"></script>
<script type="text/javascript" src="/js/graphics/jquery-fusioncharts.js"></script>
<script type="text/javascript" src="/js/graphics/d3.v3.min.js"></script>
<script type="text/javascript" src="/js/graphics/viz.v1.0.0.min.js"></script>
  
<style>

</style>
	
<title>Porta - Dashboard Mesa de Ayuda Capital Humano</title> 
<link type="image/x-icon" href="/icon/porta_icon.png" rel="icon" />


<script language="javascript">
// Build the chart

$(document).ready(function () {	
	
});

var data_mining = "";
var myChart     = "";
var objData     = {};

FusionCharts.ready(function() {
	
	  var myChart = new FusionCharts({
		type: "column3d",
		renderAt: "horas_mensuales",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		dataSource: {},
		events: {
			 dataPlotClick: function(ev, props) {
				var index = props.dataIndex;
			    //console.info(props.link)
			    var aux = props.link.split("::");
				
			    $("#titleHorasSupervisor").html("Horas Registradas Por Supervisor - Mes "+aux[1]);
				$("#titleAreas").html("Horas Registradas Por Grupo de Areas - Mes "+aux[1]);
				objData.periodo = aux[2];
				
				$("#titleHorasTipo").html("Detalle por Tipo - Mes "+aux[1]);
				
				
			    var datos = {};
				datos.action   = 8;
				datos.periodo  = objData.periodo;
						
			   jQuery.ajax({
					async: false,
					url: '/subsistemas/dashboard/consulta.php',
					type: 'post',
					data: datos
				}).done(
					function (resp) {
						data_mining = resp.trim();
						myChart_tipo.setJSONData(data_mining);
						
					}
				);	
				
			    var datos = {};
				datos.action = 9;
				datos.index  = index;
						
			   jQuery.ajax({
					async: false,
					url: '/subsistemas/dashboard/consulta.php',
					type: 'post',
					data: datos
				}).done(
					function (resp) {
						data_mining = resp.trim();
						myChartHorasSupervisor.setJSONData(data_mining);
						
					}
				);	
				
				var datos = {};
				datos.action = 11;
				datos.index  = index;
						
			   jQuery.ajax({
					async: false,
					url: '/subsistemas/dashboard/consulta.php',
					type: 'post',
					data: datos
				}).done(
					function (resp) {
						data_mining = resp.trim();
						myChartHorasAreas.setJSONData(data_mining);
						
					}
				);	
			   
			 }
		  },
	  }).render();
	
	 var myChart_tipo = new FusionCharts({
		type: "column3d",
		renderAt: "horas_tipo",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		dataSource: {}
	  }).render();
		
		
	var datos = {};
	datos.action = 7;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			data_mining = resp.trim();
			myChart.setJSONData(data_mining);
		}
	);	
		
	myChartHorasSupervisor = new FusionCharts({
		type: "pie3d",
		renderAt: "horas_supervisor",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		dataSource: {},
		events: {
			 dataPlotClick: function(ev, props) {
				var infoElem = document.getElementById("infolbl");
				var index = props.dataIndex;
				
				var aux = props.link.split("::");
				objData.legajo_supervisor = aux[1];
				
				var datos = {};
				datos.action  = 10;
				datos.periodo = objData.periodo;
				datos.legajo_supervisor = objData.legajo_supervisor;
						
			   jQuery.ajax({
					async: false,
					url: '/subsistemas/dashboard/consulta.php',
					type: 'post',
					data: datos
				}).done(
					function (resp) {
						$("#cntDetalleHorasColaborador").html(resp)
						
					}
				);	
			 }
		  },
	}).render();
	  
	  
	 myChartHorasAreas = new FusionCharts({
		type: "pie3d",
		renderAt: "horas_sectores",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		dataSource: {}
	}).render();
	   
	mycharColaboradores = new FusionCharts({
		type: "msspline",
		renderAt: "cntColaboradores",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		dataSource: {}
  }).render();
	  
	  
  var datos = {};
	datos.action = 12;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			data_mining = resp.trim();
			
			mycharColaboradores.setJSONData(data_mining);
		}
	);	
	
});

	
</script>

</head>


<body style="overfDudas_descuentos:auto;width:100%;height:100%;background-color:#080808c9">
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3><span style="color:#ce6619;font-size:20px;">Evolución de Colaboradores</span></h3>
				<div id="cntColaboradores" style="height:400px;wwidth:500px"></div>
			</div>
			
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3><span style="color:#ce6619;font-size:20px;">Horas Registradas últimos 12 Meses</span></h3>
				<div id="horas_mensuales" style="height:400px;wwidth:500px"></div>
			</div>
			
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3><span id="titleHorasTipo" style="color:#ce6619;font-size:20px;">Detalle por Tipo</span></h3>
				<div id="horas_tipo" style="height:400px;wwidth:500px"></div>
			</div>
			
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<h3><span id="titleHorasSupervisor" style="color:#ce6619;font-size:20px;">Horas Registradas Por Supervisor</span></h3>
				<div id="horas_supervisor" style="height:400px;wwidth:500px"></div>
			</div>
			<div class="col-sm-6">
				<h3><span id="titleAreas" style="color:#ce6619;font-size:20px;">Horas Registradas Por Grupo de Sectores</span></h3>
				<div id="horas_sectores" style="height:400px;wwidth:500px"></div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3><span style="color:#ce6619;font-size:20px;">Detalle por Colaborador</span></h3>
				<div id="cntDetalleHorasColaborador" style="height:400px;wwidth:500px"></div>
			</div>
			
		</div>
		
		
		
		<div class="row">
			<div class="col-sm-6">
				<h3><span style="color:#ce6619;font-size:20px;">Detalle por Colaborador</span></h3>
				<div id="cntDetalleHorasColaborador" style="height:400px;wwidth:500px"></div>
			</div>
			<div class="col-sm-6">
				
			</div>
		</div>
		
	</div>
	
	


	
</body>
</html>