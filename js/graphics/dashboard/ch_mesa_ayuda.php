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
	
	
<html xmlns="http://Entrega_Recibow.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >

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


#container_casos_mes {
  height: 400px; 
  min-width: 310px; 
  max-width: 800px;
  margin: 0 auto;
} 



body{
    width:1060px;
    margin:50px auto;
}
path {  stroke: #fff; }
path:hover {  opacity:0.9; }

.axis {  font: 10px sans-serif; }
.legend tr{    border-bottom:1px solid grey;background-color:#ffffff }
.legend tr:first-child{    border-top:1px solid grey; }

.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}

.x.axis path {  display: none; }
.legend{
    margin-bottom:76px;
    display:inline-block;
    border-collapse: collapse;
    border-spacing: 0px;
}
.legend td{
    padding:4px 5px;
    vertical-align:bottom;
}
.legendFreq, .legendPerc{
    align:right;
    width:50px;
}

</style>
	
<title>Porta - Dashboard Mesa de Ayuda Capital Humano</title> 
<link type="image/x-icon" href="/icon/porta_icon.png" rel="icon" />


<script language="javascript">
// Build the chart

$(document).ready(function () {	
	
});

var data_mining ="";
FusionCharts.ready(function() {
	
	var datos = {};
	datos.action = 0;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			data_mining = resp.trim();
			var aux = data_mining.split("::");
			if(aux[0]=="OK"){
				$("#cntTotalCasos").html(aux[1]);
				$("#cntCasosPendientes").html(aux[2]);
				$("#cntCasosNoAsignados").html(aux[3]);
				$("#cntCasosResueltos").html(aux[4]);
				
				//	$retorno .= $total_casos."::".$casos_pendientes."::".$total_no_asignados."::".$total_resueltos;
			}
			
		}
	);	
	
	//=== CASOS PENDIENTES POR ANALISTAS
	
	var myChart = new FusionCharts({
		type: "pie3d",
		renderAt: "casos_pendientes_analistas",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		dataSource: {},
		events: {
			 dataPlotClick: function(ev, props) {
				var infoElem = document.getElementById("infolbl");
				var index = props.dataIndex;
			   console.info(index)
			 }
		  },
	}).render();
	  
  
	var datos = {};
	datos.action = 1;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			data_mining = resp.trim();
			myChart.setJSONData((data_mining));
		}
	);	

	//== FIN CASOS PENDIENTES POR ANALISTAS
	
	//== INICIO NIVEL SATISFACCIÓN
	var myChart2 = new FusionCharts({
		type: "angulargauge",
		renderAt: "nivelSatisfaccion",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		backgroundColor: 'transparent',
		dataSource :{}
	}).render();
  
	var datos = {};
	datos.action = 2;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			data_mining = resp.trim();
			myChart2.setJSONData((data_mining));
		}
	);	
  
  //== FIN NIVEL SATISFACCIÓN

  //== INICIO CASOS RESUELTOS POR ANALISTAS
  
  
	myChart_3 = new FusionCharts({
		type: "pie3d",
		renderAt: "casosResueltos",
		width: "100%",
		height: "100%",
		dataFormat: "json",
		dataSource: {},
		events: {
			 dataPlotClick: function(ev, props) {
				var infoElem = document.getElementById("infolbl");
				var index = props.dataIndex;
			   console.info(index)
			 }
		  },
	  
		
  }).render();
  
  var datos = {};
	datos.action = 3;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			data_mining = resp.trim();
			myChart_3.setJSONData((data_mining));
		}
	);	
	//== FIN CASOS RESUELTOS POR ANALISTAS
	
  //== INICIO TIEMPO DE RESPUESTA POR ANALISTAS
  
  var myChart_4 = new FusionCharts({
    type: "bar3d",
    renderAt: "tiempoRespuesta",
    width: "100%",
    height: "100%",
    dataFormat: "json",
    dataSource:{}
  }).render();

  var datos = {};
	datos.action = 4;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			data_mining = resp.trim();
			myChart_4.setJSONData((data_mining));
		}
	);	
  
  var datos = {};
	datos.action = 5;
			
   jQuery.ajax({
		async: false,
		url: '/subsistemas/dashboard/consulta.php',
		type: 'post',
		data: datos
	}).done(
		function (resp) {
			dashboard('#dashboard',eval(resp));
		}
	);	
});

function dashboard(id, fData){
    var barColor = 'steelblue';
    function segColor(c){ return {Descuentos:"#807dba", 
									Haberes:"#e08214",
									ART:"#41ab5d", 
									EntregaRec:"#cc0000",  
									Licencias:"#B709DA",
									Sugerencias:"#050503",
									Ganancias:"#09DACA",
									Otras:"#DA6B09",
									Adelanto:"#FFFFFF"
									}[c]; }
	

    // compute total for each state.
    fData.forEach(function(d){d.total= d.freq.Descuentos +
									  d.freq.Haberes +
									  d.freq.ART + 
									  d.freq.EntregaRec +
									  d.freq.Licencias +
									  d.freq.Sugerencias +
									  d.freq.Ganancias +
									  d.freq.Otras +
									  d.freq.Adelanto });
    
    // function to handle histogram.
    function histoGram(fD){
        var hG={},    hGDim = {t: 60, r: 0, b: 30, l: 0};
        hGDim.w = 500 - hGDim.l - hGDim.r, 
        hGDim.h = 300 - hGDim.t - hGDim.b;
            
        //create svg for histogram.
        var hGsvg = d3.select(id).append("svg")
            .attr("width", hGDim.w + hGDim.l + hGDim.r)
            .attr("height", hGDim.h + hGDim.t + hGDim.b).append("g")
            .attr("transform", "translate(" + hGDim.l + "," + hGDim.t + ")");

        // create function for x-axis mapping.
        var x = d3.scale.ordinal().rangeRoundBands([0, hGDim.w], 0.1)
                .domain(fD.map(function(d) { return d[0]; }));

        // Add x-axis to the histogram svg.
        hGsvg.append("g").attr("class", "x axis")
            .attr("transform", "translate(0," + hGDim.h + ")")
            .call(d3.svg.axis().scale(x).orient("bottom"));

        // Create function for y-axis map.
        var y = d3.scale.linear().range([hGDim.h, 0])
                .domain([0, d3.max(fD, function(d) { return d[1]; })]);

        // Create bars for histogram to contain rectangles and freq labels.
        var bars = hGsvg.selectAll(".bar").data(fD).enter()
                .append("g").attr("class", "bar");
        
        //create the rectangles.
        bars.append("rect")
            .attr("x", function(d) { return x(d[0]); })
            .attr("y", function(d) { return y(d[1]); })
            .attr("width", x.rangeBand())
            .attr("height", function(d) { return hGDim.h - y(d[1]); })
            .attr('fill',barColor)
            .on("mouseover",mouseover)// mouseover is defined beDudas_descuentos.
            .on("mouseout",mouseout);// mouseout is defined beDudas_descuentos.
            
        //Create the frequency labels above the rectangles.
        bars.append("text").text(function(d){ return d3.format(",")(d[1])})
            .attr("x", function(d) { return x(d[0])-20+x.rangeBand()/2; })
            .attr("y", function(d) { return y(d[1])-5; })
            .attr("text-anchor", "Conceptos_recibosdle");
        
        function mouseover(d){  // utility function to be called on mouseover.
            // filter for selected state.
            var st = fData.filter(function(s){ return s.State == d[0];})[0],
                nD = d3.keys(st.freq).map(function(s){ return {type:s, freq:st.freq[s]};});
               
            // call update functions of pie-chart and legend.    
            pC.update(nD);
            leg.update(nD);
        }
        
        function mouseout(d){    // utility function to be called on mouseout.
            // reset the pie-chart and legend.    
            pC.update(tF);
            leg.update(tF);
        }
        
        // create function to update the bars. This will be used by pie-chart.
        hG.update = function(nD, color){
            // update the domain of the y-axis map to reflect change in frequencies.
            y.domain([0, d3.max(nD, function(d) { return d[1]; })]);
            
            // Attach the new data to the bars.
            var bars = hGsvg.selectAll(".bar").data(nD);
            
            // transition the height and color of rectangles.
            bars.select("rect").transition().duration(500)
                .attr("y", function(d) {return y(d[1]); })
                .attr("height", function(d) { return hGDim.h - y(d[1]); })
                .attr("fill", color);

            // transition the frequency labels location and change value.
            bars.select("text").transition().duration(500)
                .text(function(d){ return d3.format(",")(d[1])})
                .attr("y", function(d) {return y(d[1])-5; });            
        }        
        return hG;
    }
    
    // function to handle pieChart.
    function pieChart(pD){
        var pC ={},    pieDim ={w:250, h: 250};
        pieDim.r = Math.min(pieDim.w, pieDim.h) / 2;
                
        // create svg for pie chart.
        var piesvg = d3.select(id).append("svg")
            .attr("width", pieDim.w).attr("height", pieDim.h).append("g")
            .attr("transform", "translate("+pieDim.w/2+","+pieDim.h/2+")");
        
        // create function to draw the arcs of the pie slices.
        var arc = d3.svg.arc().outerRadius(pieDim.r - 10).innerRadius(0);

        // create a function to compute the pie slice angles.
        var pie = d3.layout.pie().sort(null).value(function(d) { return d.freq; });

        // Draw the pie slices.
        piesvg.selectAll("path").data(pie(pD)).enter().append("path").attr("d", arc)
            .each(function(d) { this._current = d; })
            .style("fill", function(d) { return segColor(d.data.type); })
            .on("mouseover",mouseover).on("mouseout",mouseout);

        // create function to update pie-chart. This will be used by histogram.
        pC.update = function(nD){
            piesvg.selectAll("path").data(pie(nD)).transition().duration(500)
                .attrTween("d", arcTween);
        }        
        // Utility function to be called on mouseover a pie slice.
        function mouseover(d){
            // call the update function of histogram with new data.
            hG.update(fData.map(function(v){ 
                return [v.State,v.freq[d.data.type]];}),segColor(d.data.type));
        }
        //Utility function to be called on mouseout a pie slice.
        function mouseout(d){
            // call the update function of histogram with all data.
            hG.update(fData.map(function(v){
                return [v.State,v.total];}), barColor);
        }
        // Animating the pie-slice requiring a custom function which specifies
        // how the intermediate paths should be drawn.
        function arcTween(a) {
            var i = d3.interpolate(this._current, a);
            this._current = i(0);
            return function(t) { return arc(i(t));    };
        }    
        return pC;
    }
    
    // function to handle legend.
    function legend(lD){
        var leg = {};
            
        // create table for legend.
        var legend = d3.select(id).append("table").attr('class','legend');
        
        // create one row per segment.
        var tr = legend.append("tbody").selectAll("tr").data(lD).enter().append("tr");
            
        // create the first column for each segment.
        tr.append("td").append("svg").attr("width", '16').attr("height", '16').append("rect")
            .attr("width", '16').attr("height", '16')
			.attr("fill",function(d){ return segColor(d.type); });
            
        // create the second column for each segment.
        tr.append("td").text(function(d){ return d.type;});

        // create the third column for each segment.
        tr.append("td").attr("class",'legendFreq')
            .text(function(d){ return d3.format(",")(d.freq);});

        // create the fourth column for each segment.
        tr.append("td").attr("class",'legendPerc')
            .text(function(d){ return getLegend(d,lD);});

        // Utility function to be used to update the legend.
        leg.update = function(nD){
            // update the data attached to the row elements.
            var l = legend.select("tbody").selectAll("tr").data(nD);

            // update the frequencies.
            l.select(".legendFreq").text(function(d){ return d3.format(",")(d.freq);});

            // update the percentage column.
            l.select(".legendPerc").text(function(d){ return getLegend(d,nD);});        
        }
        
        function getLegend(d,aD){ // Utility function to compute percentage.
            return d3.format("%")(d.freq/d3.sum(aD.map(function(v){ return v.freq; })));
        }

        return leg;
    }
    
	/*
	 {Descuentos:"#807dba", 
									Haberes:"#e08214",
									ART:"#41ab5d", 
									EntregaRec:"#cc0000",  
									Licencias:"#B709DA",
									Sugerencias:"#050503",
									Ganancias:"#09DACA",
									Otras:"#DA6B09",
									Adelanto:"#FFFFFF"
	*/
    // calculate total frequency by segment for all state.
    var tF = ['Descuentos','Haberes','ART', 'EntregaRec','Licencias','Sugerencias','Ganancias','Otras', 'Adelanto'].map(function(d){ 
        return {type:d, freq: d3.sum(fData.map(function(t){ return t.freq[d];}))}; 
    });    
    
    // calculate total frequency by state for all segment.
    var sF = fData.map(function(d){return [d.State,d.total];});

    var hG = histoGram(sF), // create the histogram.
        pC = pieChart(tF), // create the pie-chart.
        leg= legend(tF);  // create the legend.
}
  
	
</script>

</head>


<body style="overfDudas_descuentos:auto;width:100%;height:100%;background-color:#080808c9">

	<div style="position:absolute;top:10px;left:10px;color:#db0c0c;font-size:60px;"><div id="cntTotalCasos"></div> <span style="position:absolute;left:70px;top:50px;color:white;font-size:12px;">Total Casos</span></div>
	<div style="position:absolute;top:10px;left:200px;color:#db0c0c;font-size:60px;"><div id="cntCasosPendientes"></div> <span style="position:absolute;left:70px;top:50px;color:white;font-size:12px;">Casos Pendientes</span></div>
	<div style="position:absolute;top:10px;left:410px;color:#db0c0c;font-size:60px;"><div id="cntCasosNoAsignados"></div>  <span style="position:absolute;left:80px;top:50px;color:white;font-size:12px;">Casos no Asignados</span></div>
	<div style="position:absolute;top:10px;left:610px;color:#db0c0c;font-size:60px;"><div id="cntCasosResueltos"></div>  <span style="position:absolute;left:80px;top:50px;color:white;font-size:12px;">Casos Resueltos</span></div>

	
	</br>
	<!--div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3><span style="color:white;font-size:20px;">Dashboard Mesa de Ayuda Capital Humano</span></h3>
				
			</div>
		</div>
	</div-->
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<h3><span style="color:#ce6619;font-size:20px;">Casos Pendientes por Analista</span></h3>
				<div id="casos_pendientes_analistas" style="height:400px;wwidth:500px"></div>
			</div>
			<div class="col-sm-6">
				<h3><span style="color:#ce6619;font-size:20px;">Nivel de Satisfacción</span></h3>
				<div id="nivelSatisfaccion" style="width:500px;height:200px"></div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
			<h3><span style="color:#ce6619;font-size:20px;">Valores Históricos - Últimos doce Meses</span></h3>
				
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<h3><span style="color:#ce6619;font-size:20px;">Casos Resueltos </span></h3>
				<div id="casosResueltos" style="height:400px;wwidth:500px"></div>
			</div>
			<div class="col-sm-6">
				<h3><span style="color:#ce6619;font-size:20px;">Tiempo Medio de Respuesta [Casos cerrados en días] </span></h3>
				<div id="tiempoRespuesta" style="height:400px;wwidth:500px"></div>
			</div>
		</div>
	</div>

  	<div class="container">
		<div class="row">
			<div class="col-sm-12">
			<h3><span style="color:#ce6619;font-size:20px;">Eventos Registrados por Tipo - Ultimos doce Meses</span></h3>
			<div id='dashboard'  style="hheight:400px;width:100%"></div>
		</div>
	</div>


	
</body>
</html>