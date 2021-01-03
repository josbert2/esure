<?php 
include $_SERVER['DOCUMENT_ROOT']."/includes/control_sesion.php";
include $_SERVER['DOCUMENT_ROOT']."/includes/functions.php";
include $_SERVER['DOCUMENT_ROOT']."/includes/conexion_mysqli.php";
include $_SERVER['DOCUMENT_ROOT']."/includes/constantes.php";

header("Cache-Control: no-cache, must-revalidate"); // Evitar guardado en cache del cliente HTTP/1.1

date_default_timezone_set("America/Argentina/Cordoba");
setlocale(LC_ALL,"es_ES");
setlocale(LC_TIME, "spanish"); //Fijamos el tiempo local

$date = date("Y-m-d");
$arrayAux = explode("-",$date);
$day = $arrayAux[2];
	$day = ($day < 10 ? $day : $day);	
$month = $arrayAux[1];
	$month = ($month < 10 ? $month : $month);
$year = $arrayAux[0];

$spanish_date = $day."/".$month."/".$year;

$pestana = isSet($_GET["pestana"]) ? $_GET["pestana"] : 0;
$action = isSet($_GET["action"]) ? $_GET["action"] : 0;

?>
	
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es" dir="ltr" >

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<meta name="description" content="" />	
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">

<script type="text/javascript" src="/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/_functions.js"></script>


<link  type="text/css" rel="stylesheet"	media="all" href="/css/ui-lightness/jquery-ui-1.10.3.custom.css">
<link  type="text/css" rel="stylesheet" media="all" href="/css/styles.css">
<link  type="text/css" rel="stylesheet" media="all" href="/css/site.css">

<link type="image/x-icon" href="/icon/esecure_favicon.png" rel="icon" />

<title>Esecure - Consultas Generales</title> 

    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
	  
	  input[type="text"] {
		width: 100px;
		height: 25px;
		background-color: #FFFFFF;
		font-size:12px
	}
	
	ul.ui-autocomplete {
    z-index: 1100;
}
    </style>
<script language="javascript" type="text/javascript">


var objData = {};
	objData.id_contrato		 = 0; 
	objData.id_contratoCobranza = 0;
	objData.id_contratoLiquidacion = 0;
	objData.id_propiedad_seleccionada = 0;
	objData.id_pestana_contrato = 0;
	
var spanish_date = null;

function limpiarCampos(){
	clearForm("form_data");
	// $("#comentario").val("");
	// posicionarEnId("cboReclamo",0);
	// posicionarEnId("cboMedioIngreso",0);
	// posicionarEnId("cboUnineg",0);
	// posicionarEnId("cboDesvio",0);
	// posicionarEnId("cboRetirarMuestra",0);
	// posicionarEnId("cboDevolucion",0);
	
};

function ver_form_filter_contrato(){
	$("#formFilterContrato" ).css("display","block");
		
	$("#formFilterContrato" ).dialog({
	  resizable: false,
	  width:480,
	  height:210,
	  title:"Filtro de Contratos",
	  modal: true,
	  buttons: {
		Consultar: function(){
		  consultarContratos();
		},
		 Cerrar: function(){
		  $( this ).dialog( "close" );
		}
	  }
	});		
}

function mostrarCamposTipoPropiedad(){};

function verFormPropiedad(){
	$( "#formReportes" ).css("display","block");
	
	$( "#formReportes" ).dialog({
      resizable: false,
	  width:530,
      height:340,
	  title:"Buscador de Propiedades",
      modal: true,
	  buttons: {
			"Consultar": function(){
				consultarPropiedades();
			},
			Cerrar: function(){
				$( this ).dialog( "close" );
			}
      }
    });	
};

function verPropiedad(){
	var page = "/modules_/imno/propiedades.php?id_propiedad="+objData.id_propiedad_seleccionada;
	window.open(page, '_blank');				
};

function actulizarDepenProvincias(){
	var obj = {};
	obj.action = 31;
	obj.id_provincia = getComboValue("cboProvincia");
	
	jQuery.ajax({
		async: false,
		url: '/modules_/imno/consulta.php',
		type: 'post',
		data: obj
	}).done(
		function (resp) {
			resp = resp.trim();
			var aux = resp.split("::");
			if(aux[0] =="OK"){
				$("#cntLocalidades").html(aux[1]);
			} else {
				alert(resp);
			}
		}		
	);		
	
	actulizarDepenLocalidades();
};

function actulizarDepenLocalidades(){
	
	var obj = {};
	obj.action = 32;
	obj.id_localidad = getComboValue("cboLocalidad");
	
	jQuery.ajax({
		async: false,
		url: '/modules_/imno/consulta.php',
		type: 'post',
		data: obj
	}).done(
		function (resp) {
			resp = resp.trim();
			var aux = resp.split("::");
			if(aux[0] =="OK"){
				$("#cntBarrios").html(aux[1]);
			} else {
				alert(resp);
			}
		}		
	);		
};
function actulizarDepenPropiedades(){
	
};

function consultarPropiedades(){
	var propietario= $("#propietario").val();
	var id_propietario = 0;
	
	if(propietario !=""){
		id_propietario = parseInt(propietario.split("-")[0],10); 
		
		if(id_propietario < 0 || isNaN(id_propietario)){
			alert("Por favor reingrese el nombre del propietario y seleccionelo de la lista");
			return;
		}
	}
	
	var precio_desde = ($("#precio_desde").val() != "" ? parseFloat( $("#precio_desde").val()) : 0);
	var precio_hasta = ($("#precio_hasta").val() != "" ? parseFloat( $("#precio_hasta").val()) : 0);

	if(isNaN(precio_desde)){
		alert("El precio desde debe ser un valor numérico");
		return;
	}
	
	if(isNaN(precio_hasta)){
		alert("El precio hasta debe ser un valor numérico");
		return;
	}
			
	
	if(precio_desde > 0 || precio_hasta > 0){
		if(parseInt(getComboValue("cboTipoOperacion"),10) == 0){
			alert("Seleccione un tipo de operación.");	
			return;
		}
		
		if(parseInt(getComboValue("cboMoneda"),10) == 0){
			alert("Seleccione una moneda.");	
			return;
		}
	}
			
	
	var datos = {};
	datos.action 				= 37;
	datos.id_estado 			= getComboValue("cboEstado");
	datos.id_propietario 		= id_propietario;
	datos.calle		 			= $("#calle").val();
	datos.id_tipo_propiedad		= getComboValue("cboTipoPropiedad");
	datos.dormitorios			= $("#dormitorios").val();
	datos.id_provincia			= getComboValue("cboProvincia");
	datos.id_localidad			= getComboValue("cboLocalidad");
	datos.id_barrio				= getComboValue("cboBarrio");
	datos.id_tipo_operacion		= getComboValue("cboTipoOperacion");
	datos.id_moneda				= getComboValue("cboMoneda");
	datos.precio_desde			= precio_desde;
	datos.precio_hasta			= precio_hasta;
	
	
   jQuery.ajax({
        async: false,
        url: '/modules_/imno/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			$("#cntContenedor_04").html(resp);
			$("#formReportes").dialog("close");
	    }
    );
};

function ver_form_filter() {
	$("#formFilter" ).css("display","block");
		
	$("#formFilter" ).dialog({
	  resizable: false,
	  width:480,
	  height:220,
	  title:"Filtro de Cobranzas",
	  modal: true,
	  buttons: {
		Consultar: function(){
		  consultarRegistros();
		},
		 Cerrar: function(){
		  $( this ).dialog( "close" );
		}
	  }
	});		
};	

function ver_form_filter_LIQUI() {
	$("#formFilterLiqui" ).css("display","block");
		
	$("#formFilterLiqui" ).dialog({
	  resizable: false,
	  width:480,
	  height:180,
	  title:"Filtro de Liquidaciones",
	  modal: true,
	  buttons: {
		Consultar: function(){
		  consultarLiquidaciones();
		},
		 Cerrar: function(){
		  $( this ).dialog( "close" );
		}
	  }
	});		
};	


function consultarLiquidaciones(){
	var arrayData = {};
	arrayData.denom		  =  $("#denom_02").val();
	arrayData.fecha_desde =  $("#fecha_desde_02").val();
	arrayData.fecha_hasta =  $("#fecha_hasta_02").val();
	arrayData.id_contrato =  $("#id_contrato_02").val() != "" ? parseInt($("#id_contrato_02").val() ,10) : 0;
	
	if(arrayData.fecha_desde!="" && !isDate(arrayData.fecha_desde)){
		alert("Debe seleccionar la fecha DESDE válida..");
		$("#fecha_desde_02").focus();
		return;
	}
	
	if(arrayData.fecha_hasta!="" && !isDate(arrayData.fecha_hasta)){
		alert("Debe seleccionar la fecha HASTA válida..");
		$("#fecha_hasta_02").focus();
		return;
	}
	
	if(isNaN(arrayData.id_contrato)){
		alert("Debe seleccionar un número de Contrato válido..");
		$("#id_contrato_02").focus();
		return;
		
	}
	
	loadDataLiuidaciones(arrayData);
	$( "#formFilterLiqui" ).dialog( "close" );
};	

let setPestanaContrato = (id)=>{
	objData.id_pestana_contrato = id;
	
};

function consultarContratosProximosVtos(){
	
	var datos = {};
	datos.action 	   		  = 39	;
	
	
   jQuery.ajax({
        async: false,
        url: '/modules_/imno/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			$("#cntContenedor_03").html(resp);
	    }
    );
};

function consultarContratos(){
	
	
	var datos = {};
	datos.action 	   		  = (objData.id_pestana_contrato == 0 ? 36 : 65);
	datos.denom_locador		  = $("#denom_locador").val();
	datos.denom_locatario 	  = $("#denom_locatario").val();
	datos.desde_contrato 	  = $("#desde_contrato").val();
	datos.hasta_contrato 	  = $("#hasta_contrato").val();
	
	datos.id_contrato 		 =  $("#id_contrato_").val() != "" ? parseInt($("#id_contrato_").val() ,10) : 0;
	
	if(datos.desde_contrato!="" && !isDate(datos.desde_contrato)){
		alert("Debe seleccionar la fecha DESDE válida..");
		$("#desde_contrato").focus();
		return;
	}
	
	if(datos.hasta_contrato!="" && !isDate(datos.hasta_contrato)){
		alert("Debe seleccionar la fecha HASTA válida..");
		$("#hasta_contrato").focus();
		return;
	}
	
	if(isNaN(datos.id_contrato)){
		alert("Debe seleccionar un número de Contrato válido..");
		$("#id_contrato_").focus();
		return;
		
	}
	
   jQuery.ajax({
        async: false,
        url: '/modules_/imno/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			if(objData.id_pestana_contrato == 0 ){
				$("#cntContenedor_03").html(resp);
			} else {
				$("#cntContenedor_03A").html(resp);
			}
			$("#formFilterContrato").dialog("close");
	    }
    );
	
};	
	
function consultarRegistros(){



	var arrayData = {};
	arrayData.denom		  =  $("#denom_").val();
	arrayData.fecha_desde =  $("#fecha_desde").val();
	arrayData.fecha_hasta =  $("#fecha_hasta").val();
	arrayData.id_contrato =  $("#id_contrato_01").val() != "" ? parseInt($("#id_contrato_01").val() ,10) : 0;
	
	if(arrayData.fecha_desde!="" && !isDate(arrayData.fecha_desde)){
		alert("Debe seleccionar la fecha DESDE válida..");
		$("#fecha_desde").focus();
		return;
	}
	
	if(arrayData.fecha_hasta!="" && !isDate(arrayData.fecha_hasta)){
		alert("Debe seleccionar la fecha HASTA válida..");
		$("#fecha_hasta").focus();
		return;
	}
	
	if(isNaN(arrayData.id_contrato)){
		alert("Debe seleccionar un número de Contrato válido..");
		$("#id_contrato_01").focus();
		return;
		
	}
	
	loadData(arrayData);
	$( "#formFilter" ).dialog( "close" );
};	
	
function cobranzas(){
	if(objData.id_contratoCobranza == 0){
		alert("Por favor seleccione un contrato");
		return;
	}
	window.location="/modules_/imno/registro_pagos?id_contrato="+objData.id_contratoCobranza;
	
};	

function liquidaciones(){
	if(objData.id_contratoLiquidacion == 0){
		alert("Por favor seleccione una liquidacion");
		return;
	}
	window.location="/modules_/imno/registro_liqudaciones.php?id_liquidacion="+objData.id_contratoLiquidacion;
	
};	

function clearCheck_propiedad(idSelected){
	objData.id_propiedad_seleccionada = idSelected;
	
	myFormDiv = document.getElementsByTagName("input");
	
	for(var a=0; a < myFormDiv.length; a++){
		if(myFormDiv[a].getAttribute("id")){
			var id= myFormDiv[a].getAttribute("id");
			var expresion ="^chkPropiedad_";
			
			re = new RegExp(expresion);
			
			if(id.match(re)){
				var idAux = id.split("_")[1]; 
				if(idAux == idSelected){

					$("#chkPropiedad_"+idAux).prop("checked", true);  
				} else {

					$("#chkPropiedad_"+idAux).prop("checked", false);
				}
			}
		}
	}
};

function clearCheck_cuota (idSelected){
	var aux =  idSelected.split("-");
	
	objData.id_contratoCobranza	= aux[0];
	objData.id_cuota	= aux[1];	
	
	
	myFormDiv = document.getElementsByTagName("input");
	
	for(var a=0; a < myFormDiv.length; a++){
		if(myFormDiv[a].getAttribute("id")){
			var id= myFormDiv[a].getAttribute("id");
			var expresion ="^chkCuota_";
			re = new RegExp(expresion);
			if(id.match(re)){
				var idAux = id.split("_")[1]; 
				if(idAux == idSelected){

					$("#chkCuota_"+idAux).prop("checked", true);  
				} else {

					$("#chkCuota_"+idAux).prop("checked", false);
				}
			}
		}
	}
	
};	

function clearCheck_contrato(idSelected){
	objData.id_contrato	= idSelected;
	myFormDiv = document.getElementsByTagName("input");
	
	for(var a=0; a < myFormDiv.length; a++){
		if(myFormDiv[a].getAttribute("id")){
			var id= myFormDiv[a].getAttribute("id");
			var expresion ="^chkContrato_";
			re = new RegExp(expresion);
			if(id.match(re)){
				var idAux = id.split("_")[1]; 
				if(idAux == idSelected){

					$("#chkContrato_"+idAux).prop("checked", true);  
				} else {

					$("#chkContrato_"+idAux).prop("checked", false);
				}
			}
		}
	}	
	
}

function clearCheck_liquidacion (idSelected){
	var aux =  idSelected.split("-");
	
	objData.id_contratoLiquidacion	= aux[0];
	objData.id_cuota	= aux[1];	
	
	
	myFormDiv = document.getElementsByTagName("input");
	
	for(var a=0; a < myFormDiv.length; a++){
		if(myFormDiv[a].getAttribute("id")){
			var id= myFormDiv[a].getAttribute("id");
			var expresion ="^chkCuota_";
			re = new RegExp(expresion);
			if(id.match(re)){
				var idAux = id.split("_")[1]; 
				if(idAux == idSelected){

					$("#chkCuotaLiqui_"+idAux).prop("checked", true);  
				} else {

					$("#chkCuotaLiqui_"+idAux).prop("checked", false);
				}
			}
		}
	}	
};	
	
function verContrato(){
	
	if(objData.id_contrato == 0){
		alert("Debe seleccionar el contrato que desee editar");
		return;
	}
	
	//bloquearCamposContrato(true);
	
	//objData.action = 11;
	//$("#comentario").val("");
	
	$("#formAddContratos" ).css("display","block");
		
	$("#formAddContratos" ).dialog({
	  resizable: false,
	  width:700,
	  height:600,
	  title:"Consulta de Contratos",
	  modal: true,
	  buttons: {
		 Cerrar: function(){
		  $( this ).dialog( "close" );
		}
	  }
	});		
	
	$("#denom").focus();
	
	buscarDatos(7, objData.id_contrato);
	verDetalleContrato();
	buscarDatosCalculoContrato();
	consultarEscalasContrato();
	
};

function verDetalleContrato(){
	var datos = {};
	datos.action 		= 5;
	datos.id_contrato   = objData.id_contrato;
   jQuery.ajax({
        async: false,
        url: '/modules_/imno/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			$("#cntContrato").html(resp);
	    }
    );
};

function consultarEscalasContrato(){
	var objDatos		  	 = {};
	objDatos.action 	  	 = 43;
	objDatos.id_contrato 	 = objData.id_contrato;
	
	jQuery.ajax({
		async: false,
		url: '/modules_/imno/consulta.php',
		type: 'post',
		data: objDatos
	}).done(
		function (resp) {
		for(var a=0; a< 100; a++){

				$( "#trCuota_"+a ).remove();

				$( "#mes_"+a+"d").remove();

				$( "#mes_"+a+"d" ).remove();

				$( "#mes_"+a+"d" ).remove();

				$( "#importe_"+a).remove();

				

				

				

			}
			$('#cntDetalleCuotas').after(resp);
		}	
	);
	setTimeout(function(){
		calcularGastos(false);
	},1000);
};

function validarDatosContrato(validarLocataio){
	var objDatos = {};
		objDatos.retorno = true;

	if(objData.id_propietario == 0){
		alert("Debe seleccionar un propietario.");
		objDatos.retorno = false;
		return  objDatos;
	}

	if(objData.propiedad_seleccionada == 0){
		alert("Debe seleccionar una propiedad.");
		objDatos.retorno = false;
		return  objDatos;
	}

	objDatos.meses = parseInt($("#meses").val(),10);

	if(isNaN(objDatos.meses) || objDatos.meses < 1 ){
		alert("Debe indicar la cantidad de meses del contrato");
		$("#meses").focus();
		objDatos.retorno = false;
		return  objDatos;
	}

	if(validarLocataio){
		objDatos.nombre_locatario = $("#nombre_locatario").val();
		if(objDatos.nombre_locatario == ""){
			alert("Debe ingresar el nombre del locatario");
			$("#nombre_locatario").focus();
			objDatos.retorno = false;
			return  objDatos;
		}
		objDatos.dni_locatario = $("#dni_locatario").val();

		if(objDatos.celular_locatario == ""){
			alert("Debe ingresar el DNI del locatario");
			$("#dni_locatario").focus();
			objDatos.retorno = false;
			return  objDatos;
		}

		objDatos.celular_locatario = $("#celular_locatario").val();

		if(objDatos.celular_locatario == ""){
			alert("Debe ingresar el celular del locatario");
			$("#celular_locatario").focus();
			objDatos.retorno = false;
			return  objDatos;
		}
	}	

	objDatos.date_from_contrato = $("#date_from_contrato").val();

	if(!isDate(objDatos.date_from_contrato)){
		alert("Debe seleccionar la fecha de inicio del contrato..");
		$("#date_from_contrato").focus();
		objDatos.retorno = false;
		return  objDatos;
	}
	
	objDatos.date_to_contrato = $("#date_to_contrato").val();
	objDatos.meses_deposito = parseInt($("#meses_deposito").val(),10);

	if(isNaN(objDatos.meses_deposito) || objDatos.meses_deposito < 0 ){
		alert("Debe indicar la cantidad de meses de depósito, valor esperado [0-xx]");
		$("#meses_deposito").focus();
		objDatos.retorno = false;
		return  objDatos;
	}

	objDatos.comision = parseFloat($("#comision").val());

	if(isNaN(objDatos.comision) || objDatos.comision < 0 || objDatos.comision > 10){
		alert("Debe indicar el porcentaje de comsion, valor esperado [0-10 %]");
		$("#comision").focus();
		objDatos.retorno = false;
		return  objDatos;
	}

	if(objDatos.comision == 0 && objData.id_contrato ==0){
		var mensaje = "Usted no ha registrado comisión, está seguro que desea continuar?";
		if(!confirm(mensaje)){
			$("#comision").focus();
			objDatos.retorno = false;
		};
	}

	objDatos.mora_diaria = parseFloat($("#mora_diaria").val());

	if(isNaN(objDatos.mora_diaria) || objDatos.mora_diaria < 0 || objDatos.mora_diaria > 5){
		alert("Debe indicar el porcentaje de mora diaria, valor esperado [0-5 %]");
		$("#mora_diaria").focus();
		objDatos.retorno = false;
		return  objDatos;
	}
	objDatos.dia_vto = parseInt($("#dia_vto").val(),10);

	if(isNaN(objDatos.dia_vto) || objDatos.dia_vto < 0 || objDatos.dia_vto > 30){
		alert("Debe indicar el día de vencimiento de cada cuota, valor esperado [0-30]");
		$("#dia_vto").focus();
		objDatos.retorno = false;
		return  objDatos;
	}

	objDatos.dia_cobro = parseInt($("#dia_cobro").val(),10);

	if(isNaN(objDatos.dia_cobro) || objDatos.dia_cobro < 0 || objDatos.dia_cobro > 30){
		alert("Debe indicar el día de cobro de cada cuota, valor esperado [0-30]");
		$("#dia_cobro").focus();
		objDatos.retorno = false;
		return  objDatos;
	}

	objDatos.importe_1 = parseFloat($("#importe_1").val());

	if(isNaN(objDatos.importe_1) || objDatos.importe_1 < 0){
		alert("Debe indicar el importe de la primer cuota de alquiler");
		$("#importe_1").focus();
		objDatos.retorno = false;
		return  objDatos;
	}

	objDatos.mes_1d = parseInt($("#mes_1d").val(),10);
	objDatos.mes_1h = parseInt($("#mes_1h").val(),10);

	if(isNaN(objDatos.mes_1h) || objDatos.mes_1h < 2){
		alert("Debe indicar hasta que mes se debe cobrar la primer escala del alquiler");
		$("#mes_1h").focus();
		objDatos.retorno = false;
		return  objDatos;
	}

	if(objDatos.meses  > objDatos.mes_1h ){
		var ultima_cuota_hasta = 0;

		for(var a =2; a <= 8 ;a++){
			var importe 	= parseFloat($("#importe_"+a).val());
			var cuota_desde = parseInt($("#mes_"+a+"d").val(),10);
			var cuota_hasta = parseInt($("#mes_"+a+"h").val(),10);
		
			if(typeof $("#importe_"+a).val() =="undefined"){
				continue;
			}
		
			if($("#importe_"+a).val() !="" || $("#mes_"+a+"d").val() !="" || $("#mes_"+a+"h").val() !=""){
				eval("objDatos.importe_"+a+" = "+ parseFloat($("#importe_"+a).val()));
				eval("objDatos.mes_"+a+"d = "+ parseInt($("#mes_"+a+"d").val(),10));
				eval("objDatos.mes_"+a+"h = "+ parseInt($("#mes_"+a+"h").val(),10));

				ultima_cuota_hasta = cuota_hasta;
				var importe_anterior 		= parseFloat($("#importe_"+(a-1)).val());
				var cuota_desde_anterior    = parseInt($("#mes_"+(a-1)+"d").val(),10);
				var cuota_hasta_anterior    = parseInt($("#mes_"+(a-1)+"h").val(),10);

				if(isNaN(importe) || (importe < importe_anterior)){
					alert("Por favor controle los importes de cada escala ["+getNombreEscala(a)+"]");
					objDatos.retorno = false;
					return  objDatos;
				}

				if(isNaN(cuota_desde) || (cuota_desde < cuota_desde_anterior)){
					alert("Por favor controle la escala de cuotas");
					objDatos.retorno = false;
					return  objDatos;
				}

				if(isNaN(cuota_hasta) || (cuota_hasta < cuota_hasta_anterior) || (cuota_desde > cuota_hasta)){
					alert("Por favor controle la escala de cuotas");
					objDatos.retorno = false;
					return  objDatos;
				}
			}

		}

		if(ultima_cuota_hasta != objDatos.meses){
			alert("Por favor verifique que el total de cuotas coincida con la cantidad de meses.");
			objDatos.retorno = false;
			return  objDatos;
		}

	} else {
		objDatos.mes_2h 	= 0; 
		objDatos.mes_2d 	= 0; 
		objDatos.importe_2 	= 0;
		objDatos.mes_3h 	= 0; 
		objDatos.mes_3d  	= 0;
		objDatos.importe_3 	= 0;
		objDatos.mes_4h  	= 0;
		objDatos.mes_4d  	= 0;
		objDatos.importe_4 	= 0;
	}
	return objDatos;
};

function  calcularGastos(validarLocataio = true){

	var objDatos = validarDatosContrato(validarLocataio);
	if(!objDatos.retorno){ return;}

	var mes_1d = isNaN(parseInt($("#mes_1d").val(),10)) ? 0 : parseInt($("#mes_1d").val(),10);
	var mes_1h = isNaN(parseInt($("#mes_1h").val(),10)) ? 0 : parseInt($("#mes_1h").val(),10);
	var importe_1 = isNaN(parseFloat($("#importe_1").val())) ? 0 : parseFloat($("#importe_1").val());	
	
	var mes_2d = isNaN(parseInt($("#mes_2d").val(),10)) ? 0 : parseInt($("#mes_2d").val(),10);
	var mes_2h = isNaN(parseInt($("#mes_2h").val(),10)) ? 0 : parseInt($("#mes_2h").val(),10);
	var importe_2 = isNaN(parseFloat($("#importe_2").val())) ? 0 : parseFloat($("#importe_2").val());

	var mes_3d = isNaN(parseInt($("#mes_3d").val(),10)) ? 0 : parseInt($("#mes_3d").val(),10);
	var mes_3h = isNaN(parseInt($("#mes_3h").val(),10)) ? 0 : parseInt($("#mes_3h").val(),10);
	var importe_3 = isNaN(parseFloat($("#importe_3").val())) ? 0 : parseFloat($("#importe_3").val());

	var mes_4d = isNaN(parseInt($("#mes_4d").val(),10)) ? 0 : parseInt($("#mes_4d").val(),10);
	var mes_4h = isNaN(parseInt($("#mes_4h").val(),10)) ? 0 : parseInt($("#mes_4h").val(),10);
	var importe_4 = isNaN(parseFloat($("#importe_4").val())) ? 0 : parseFloat($("#importe_4").val());

	var mes_5d = isNaN(parseInt($("#mes_5d").val(),10)) ? 0 : parseInt($("#mes_5d").val(),10);
	var mes_5h = isNaN(parseInt($("#mes_5h").val(),10)) ? 0 : parseInt($("#mes_5h").val(),10);
	var importe_5 = isNaN(parseFloat($("#importe_5").val())) ? 0 : parseFloat($("#importe_5").val());

	var mes_6d = isNaN(parseInt($("#mes_6d").val(),10)) ? 0 : parseInt($("#mes_6d").val(),10);
	var mes_6h = isNaN(parseInt($("#mes_6h").val(),10)) ? 0 : parseInt($("#mes_6h").val(),10);
	var importe_6 = isNaN(parseFloat($("#importe_6").val())) ? 0 : parseFloat($("#importe_6").val());

	var mes_7d = isNaN(parseInt($("#mes_7d").val(),10)) ? 0 : parseInt($("#mes_7d").val(),10);
	var mes_7h = isNaN(parseInt($("#mes_7h").val(),10)) ? 0 : parseInt($("#mes_7h").val(),10);
	var importe_7 = isNaN(parseFloat($("#importe_7").val())) ? 0 : parseFloat($("#importe_7").val());

	var mes_8d = isNaN(parseInt($("#mes_8d").val(),10)) ? 0 : parseInt($("#mes_8d").val(),10);
	var mes_8h = isNaN(parseInt($("#mes_8h").val(),10)) ? 0 : parseInt($("#mes_8h").val(),10);
	var importe_8 = isNaN(parseFloat($("#importe_8").val())) ? 0 : parseFloat($("#importe_8").val());

	var total_contrato = ((mes_1h - (mes_1d -1)) * importe_1) +
							((mes_2h - (mes_2d -1)) * importe_2) +
							((mes_3h - (mes_3d -1)) * importe_3) +
							((mes_4h - (mes_4d -1)) * importe_4) +
							((mes_5h - (mes_5d -1)) * importe_5) +
							((mes_6h - (mes_6d -1)) * importe_6) +
							((mes_7h - (mes_7d -1)) * importe_7) +
							((mes_8h - (mes_8d -1)) * importe_8);

	var deposito 		= (objDatos.meses_deposito * objDatos.importe_1);

	$("#total_contrato").val(total_contrato);

	var totalAlquiler 	= total_contrato;
 	var comision 		= (objDatos.comision/100 * totalAlquiler);
	var total_gastos 	= (deposito + comision);
	var valor_total 	= (total_gastos);
	
	$("#deposito").val(deposito.toFixed(2));
	$("#comision_pesos").val(comision.toFixed(2));
	$("#total").val(total_gastos.toFixed(2));

	var valor_cuota = deposito / parseInt(getComboValue("cboCntCuotas"));

	$("#valor_cuota").val(valor_cuota.toFixed(2));	

	var valor_cuota_comision = comision / parseInt(getComboValue("cboCntCuotasComision"));
	$("#valor_cuota_comision").val(valor_cuota_comision.toFixed(2));	

};

function buscarDatosCalculoContrato(){
	var objDatos		  = {};
	objDatos.action 	  = 33;
	objDatos.id_propiedad = objData.propiedad_seleccionada;
	objDatos.id_contrato  = objData.id_contrato;

	jQuery.ajax({
		async: false,
		url: '/modules_/imno/consulta.php',
		type: 'post',
		data: objDatos
	}).done(
		function (resp) {
			var respuesta = resp.trim();

			if(eval(respuesta)){
				var arrayData = eval(respuesta);	
				$("#importe_inicial").val(parseFloat(arrayData[0]["precio_alquiler"]).toFixed(2));
				$("#comision_adm").val(parseFloat(arrayData[0]["comision_alquiler"]).toFixed(2));
				$("#ajuste_").val(parseFloat(arrayData[0]["ajuste"]).toFixed(2));
				posicionarEnId("cboAjuste_",arrayData[0]["cboAjuste"]);
				posicionarEnId("cboCalculo_",arrayData[0]["cboCalculo"]);
			}
		}
	);
};

function buscarDatos(action, id){
	$("#comentario_contrato").html("");
	jQuery.ajax({
		async: false,
		url: '/modules_/imno/consulta.php',
		type: 'post',
		data: {action:action, id: id}
	}).done(
		function (resp) {

			var respuesta = resp.trim();

			if(eval(respuesta)){
				var arrayData = eval(respuesta);	

				for(k in arrayData[0]){

					var expresion ="^cbo";
					reCombo = new RegExp(expresion);

					var expresion ="^chk";
					reChk = new RegExp(expresion);

					var expresion ="^opt";
					reOpt = new RegExp(expresion);

					var expresion ="^fecha";
					reFechas = new RegExp(expresion);

					var expresion ="^date";
					reFechas_2 = new RegExp(expresion);

					if(k.match(reCombo)){
						if(k !="cboEstado"){
							posicionarEnId(k,arrayData[0][k]);
						}
					} else if(k.match(reChk)){
						$("#"+k).prop("checked", (parseInt(arrayData[0][k],10) == 1 ? true : false));
					}  else if(k.match(reFechas) || k.match(reFechas_2)){
						$("#"+k).val(getSpanishDate(arrayData[0][k]));
					}  else if(k.match(reOpt)){	

					} else {
						if(!isNaN(arrayData[0][k]) && arrayData[0][k] !=""){//numero
							var numero = arrayData[0][k];

							if(	numero % 1 != 0){//es decimal
								numero = parseFloat(numero).toFixed(2);
							} else {
								numero = parseFloat(numero).toFixed(0);
							}
							arrayData[0][k] = numero;
						}
						if(action == 7 && k == "calculos_editados" && arrayData[0][k] == 1){
							$("#comentario_contrato").html("Los importes de las cuotas de cada segmento han sido editadas manualmente.");	
						}
						$("#"+k).val(arrayData[0][k]);
					}
				}
			}
		}
	);

};	

function setTabActivePrincipal(id){
	
	switch(id){
		case 0:{
			
			break;
		}
		case 1:{
			loadDataLiuidaciones();
			break;
		}

	}	
};


function loadDataLiuidaciones(){
	
	var datos = {};
	datos.action 		= 30;
	
	datos.id_estado 	= ($("#chk_activos").is(":checked") ? 1 : 0);
	//--El valor del campo estado se puede sobre escribir con el valor del parametro
	if(arguments.length > 0){
		datos.filtro 	  = 1;
		datos.denom 	  = arguments[0].denom;
		datos.fecha_desde = arguments[0].fecha_desde;
		datos.fecha_hasta = arguments[0].fecha_hasta;
		datos.id_contrato = arguments[0].id_contrato;
		datos.cuotas_canceladas = $("#chk_canceladas_02").is(":checked") ? 1 : 0;

	}
    
   jQuery.ajax({
        async: false,
        url: '/modules_/imno/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
			var data = resp.trim();
			var aux = data.split("::");
			if(aux[0] == "OK"){
				
				$("#cntContenedor_02").html(aux[1]);
				
				var total  = aux[2];
				var mensaje = "Total ARS "+(separadorMiles(parseFloat(total)));
				$("#total_liquidacion").html(mensaje);
			}
	    }
    );
};

function init(page){
	var ventana_ancho = $(window).width();
	var ventana_alto = $(window).height()-100;
		
   $("body").bind('mousewheel DOMMouseScroll', function() {
	  return false
	});
	
	
	$("#td_01").css("width",160+"px");
	$("#td_02").css("width","0px");
	$("#td_03").css("width","0px");
	$("#td_04").css("width",(ventana_ancho - 320)+"px");
	$("#td_05").css("width",160+"px");
	
	
	
	var capa ='<div id="capa" style="position:absolute;left:0px;top:0px;'+
		' width:100%;'+
		' height:100%;'+
		' background-color:blue;'+
		' z-index:5000;'+
		' filter:alpha(opacity=30);'+
		' -moz-opacity:.70;'+
		' opacity:.70">'+
		' <img src="/images/chrome.png" id="imgCapa" style="z-index:7000"/>'+
	' </div>';
	
	 var navegador = new String(navigator.userAgent);
	 
	 if(navegador.match(/Firefox/) == null && navegador.match(/Chrome/)  == null){
		
		$("#cabecera").html(capa);
		
		$("#capa").css("width",ventana_ancho+"px");
		$("#capa").css("height",ventana_alto+100+"px");
		$("#imgCapa").css("width",ventana_ancho+"px");
		$("#imgCapa").css("height",ventana_alto+100+"px");
	 }
};

function loadData(){
	
	var datos = {};
	datos.action 		= 29;
	
	datos.id_estado 	= ($("#chk_activos").is(":checked") ? 1 : 0);
	//--El valor del campo estado se puede sobre escribir con el valor del parametro
	if(arguments.length > 0){
		datos.filtro 	  = 1;
		datos.denom 	  = arguments[0].denom;
		datos.fecha_desde = arguments[0].fecha_desde;
		datos.fecha_hasta = arguments[0].fecha_hasta;
		datos.id_contrato = arguments[0].id_contrato;
		datos.cuotas_canceladas = $("#chk_canceladas").is(":checked") ? 1 : 0;
	}
	
	
    
   jQuery.ajax({
        async: false,
        url: '/modules_/imno/consulta.php',
		type: 'post',
        data: datos
    }).done(
        function (resp) {
				
			var data = resp.trim();
			var aux = data.split("::");
			if(aux[0] == "OK"){
				
				$("#cntContenedor").html(aux[1]);
				
				var total  = aux[2];
				var mensaje = "Total ARS "+(separadorMiles(parseFloat(total)));
				$("#total_conbranza").html(mensaje);
			}	
				
			
	    }
    );
};

function descargarContrato(id){
	var form = document.formGenerico;
	var url = "/modules_/imno/downloadFile.php?action=2&id="+id;
	
	form.action = url;
	form.submit();
};

function impresion_contrato(id){
	
	var datos = {};
	datos.action 	   		  = id;
	datos.denom_locador		  = $("#denom_locador").val();
	datos.denom_locatario 	  = $("#denom_locatario").val();
	datos.desde_contrato 	  = $("#desde_contrato").val();
	datos.hasta_contrato 	  = $("#hasta_contrato").val();
	
	datos.id_contrato 		 =  $("#id_contrato_").val() != "" ? parseInt($("#id_contrato_").val() ,10) : 0;
	
	if(datos.desde_contrato!="" && !isDate(datos.desde_contrato)){
		alert("Debe seleccionar la fecha DESDE válida..");
		$("#desde_contrato").focus();
		return;
	}
	
	if(datos.hasta_contrato!="" && !isDate(datos.hasta_contrato)){
		alert("Debe seleccionar la fecha HASTA válida..");
		$("#hasta_contrato").focus();
		return;
	}
	
	if(isNaN(datos.id_contrato)){
		alert("Debe seleccionar un número de Contrato válido..");
		$("#id_contrato_").focus();
		return;
		
	}
	
	var form = document.formGenerico;
	var url = "/modules_/imno/reportes/generar_reportes_contratos.php?"+
				"action="+datos.action+"&"+
				"denom_locador="+datos.denom_locador+"&"+
				"denom_locatario="+datos.denom_locatario+"&"+
				"desde_contrato="+datos.desde_contrato+"&"+
				"hasta_contrato="+datos.hasta_contrato+"&"+
				"id_contrato="+datos.id_contrato;
	
	form.action = url;
	form.submit();
}

function consultarlistado(){
	
	var obj = {};
	obj.action 		  = (parseInt(getComboValue("cboListado"),10) == 1 ? 72 : 73);
	
	jQuery.ajax({
		async: false,
		url: '/modules_/imno/consulta.php',
		type: 'post',
		data: obj
	}).done(
		function (resp) {
			resp = resp.trim();
			var aux = resp.split("::");
			if(aux[0] =="OK"){
				$("#cntContenedor_05").html(aux[1]);
			} else {
				setMessage(resp,3);
			}
		}		
	);	
	
};

$(document).ready(function () {
	init();
    

	
	//removeClassName(id_interfaz);   
    $("#tabs_ent").tabs({collapsible: false });
	$("#tabsPro_ent").tabs({collapsible: false });
	$("#tabs_con").tabs({collapsible: false });

	
	
    var ventana_alto = $(window).height();
	
	var altoSinCabecera = ventana_alto -80;
	var altoTablasPersonas = (altoSinCabecera - 50)/2;
	
	ventana_ancho = $(window).width();
	ventana_alto = $(window).height() - 100;

	spanish_date = '<?php echo($spanish_date); ?>';
	$("#fecha_desde").datepicker({dateFormat: "dd/mm/yy"});
	$("#fecha_hasta").datepicker({dateFormat: "dd/mm/yy"});
	
	$("#desde_contrato").datepicker({dateFormat: "dd/mm/yy"});
	$("#hasta_contrato").datepicker({dateFormat: "dd/mm/yy"});
	
	$("#fecha_desde_02").datepicker({dateFormat: "dd/mm/yy"});
	$("#fecha_hasta_02").datepicker({dateFormat: "dd/mm/yy"});
	
	$("#center").css("left", "10px");
	$("#center").css("width", (ventana_ancho - 610) + "px");
	
	$("#left").css("height", ventana_alto + "px");
	$("#center").css("height", ventana_alto + "px");
	$("#body").css("height", ventana_alto + "px");
	
	var altoGrillaDatos = (ventana_alto - 0);
	$("#tabs_ent").css("height", altoGrillaDatos  + "px");
	$("#tabs_det").css("height", (altoGrillaDatos-50)  + "px");

	
	$("#cntContenedor").css("height", (altoGrillaDatos-80)  + "px");
	$("#cntContenedor_02").css("height", (altoGrillaDatos-78)  + "px");
	$("#cntContenedor_03").css("height", (altoGrillaDatos-120)  + "px");
	$("#cntContenedor_03A").css("height", (altoGrillaDatos-120)  + "px");
	
	$("#cntContenedor_04").css("height", (altoGrillaDatos-78)  + "px");
	
	$("#cntContenedor_04").css("width", (ventana_ancho - 30) + "px");
	
	$("#tabsPro_ent").css("height", (altoGrillaDatos-10)  + "px");
	$("#tabs_con").css("height", (520)  + "px");
	$("#cntContrato").css("height","350px");
	
	$("#tabs_ent").css("width", (ventana_ancho-20)  + "px");
	$("#tabs_det").css("width", (ventana_ancho-30)  + "px");
	
	
	
	$('body').bind('mousewheel DOMMouseScroll', function (){return false});

	$("#tabs").tabs({
		collapsible: false
	});
	
	$("#tabs_det").tabs({
		collapsible: false
	});
	
	
	
	$("#tabsPro").tabs({
		collapsible: false
	});
	
	$("#tabsVen_ent").tabs({
		collapsible: false
	});
	
	var pestana = parseInt(<?php echo($pestana); ?>,10);
	if(pestana > 0){
		$( "#tabs_ent" ).tabs({ active: pestana });
		consultarContratosProximosVtos();
		
	}

	loadData();
	
});

</script>

</head>
<body style="overflow:hidden;background-color:#F8ECE0">
	
	<?PHP include $_SERVER['DOCUMENT_ROOT']."/modules_/layout/header.php"; ?>
	
	
	
	<input  type="hidden" id="cboAjuste">
	<input  type="hidden" id="cboCalculo" >
	
	<form name="formGenerico" id="formGenerico" action="" method="post"   onsubmit="return false">	</form>		
	
	<?PHP include $_SERVER['DOCUMENT_ROOT']."/modules_/imno/propiedades_datos_contrato.php"; ?>
	<div id="formFilter" title="Filtro de Cobranzas" style="display:none;;">	
		
		<table class="textTabs" cellpadding="2" cellspacing="0" border="0" width="460px">
			
			<tr height="30px">	
				<td>Apellido y Nombre
				</td>
				<td colspan="3">
					<input style="display:inline;width:300px;height:25px" type="text" id="denom_" name="denom_" value=""class="auto" >
				</td>
			</tr>
			<tr height="30px">	
				<td width="130px">Desde</td>
				<td  width="80px">
					<input type="text"  id="fecha_desde" maxlength="10" value=""  autocomplete="off" style="text-align:left;width:80px" />
				</td>
				<td  width="50px">Hasta</td>
				<td  width="200px">
					<input type="text"  id="fecha_hasta" maxlength="10" value=""  autocomplete="off" style="text-align:left;width:80px" />
				</td>
			</tr>	
			<tr height="30px">	
				<td width="110px">ID Contrato</td>
				<td colspan="3"  width="80px">
					<input type="text"  id="id_contrato_01" maxlength="10" value=""  autocomplete="off" style="text-align:right;width:80px" />
				</td>
				
			</tr>	
			<tr height="30px">	
				<td width="110px">Ver cuotas canceladas</td>
				<td colspan="3"  width="80px">
					<input type="checkbox" name="chk_canceladas" id="chk_canceladas"/>
				</td>
				
			</tr>	
		</table>	
	</div>
	
	<div id="formFilterLiqui" title="Filtro de Cobranzas" style="display:none;;">	
		
		<table class="textTabs" cellpadding="2" cellspacing="0" border="0" width="460px">
			
			<tr height="30px">	
				<td>Apellido y Nombre
				</td>
				<td colspan="3">
					<input style="display:inline;width:300px;height:25px" type="text" id="denom_02" name="denom_02" value=""class="auto" >
				</td>
			</tr>
				<tr height="30px">	
				<td width="130px">Desde</td>
				<td  width="80px">
					<input type="text"  id="fecha_desde_02" maxlength="10" value=""  autocomplete="off" style="text-align:left;width:80px" />
				</td>
				<td  width="50px">Hasta</td>
				<td  width="200px">
					<input type="text"  id="fecha_hasta_02" maxlength="10" value=""  autocomplete="off" style="text-align:left;width:80px" />
				</td>
			</tr>	
				<tr height="30px">	
				<td width="110px">ID Contrato</td>
				<td colspan="3"  width="80px">
					<input type="text"  id="id_contrato_02" maxlength="10" value=""  autocomplete="off" style="text-align:right;width:80px" />
				</td>
				
			</tr>	
			<tr height="30px">	
				<td width="110px">Ver cuotas canceladas</td>
				<td colspan="3"  width="80px">
					<input type="checkbox" name="chk_canceladas_02" id="chk_canceladas_02"/>
				</td>
				
			</tr>	
		</table>	
	</div>
	
	<div id="formFilterContrato" title="Filtro de Contratos" style="display:none;;">	
		
		<table class="textTabs" cellpadding="0" cellspacing="0" border="0" width="460px">
			
			<tr height="25px">	
				<td>Nombre Locador
				</td>
				<td colspan="3">
					<input style="display:inline;width:300px;height:20px" type="text" id="denom_locador" name="denom_locador" value=""class="auto" >
				</td>
			</tr>
			<tr height="25px">	
				<td>Nombre Locatario
				</td>
				<td colspan="3">
					<input style="display:inline;width:300px;height:20px" type="text" id="denom_locatario" name="denom_locatario" value=""class="auto" >
				</td>
			</tr>
			<tr>
				<td width="130px">Desde</td>
				<td  width="80px">
					<input type="text"  id="desde_contrato" maxlength="10" value=""  autocomplete="off" style="text-align:left;width:80px" />
				</td>
				<td  width="50px">Hasta</td>
				<td  width="200px">
					<input type="text"  id="hasta_contrato" maxlength="10" value=""  autocomplete="off" style="text-align:left;width:80px" />
				</td>
			</tr>	
			<tr>
				<td width="110px">ID Contrato</td>
				<td colspan="3"  width="80px">
					<input type="text"  id="id_contrato_" maxlength="10" value=""  autocomplete="off" style="text-align:right;width:80px" />
				</td>
				
			</tr>	
		</table>	
	</div>
	
	<div id="formReportes" title="Referencia de Iconos" style="display:none;;overflow:hidden;">	
		<div id="form_cabecera" style="position:absolute;width:100%;height:155px;">
		
			<table class="textTabs" cellpadding="0" cellspacing="0" border="0" width="500px">
				<tr height="25px">	
					<td width="200px">Propietario
					</td>
					<td  width="300px">
						<input type="text" name="propietario" id="propietario"  style="width:200px;height:15px;display:inline;"/> 
					</td>
				</tr>
				<tr height="25px">	
					<td>Dirección Inmueble [calle]
					</td>
					<td>
						<input type="text" name="calle" id="calle"  style="width:200px;height:15px;display:inline;"/> 
					</td>
				</tr>
			
			
			
				<tr height="25px">	
					<td>Tipo de Propiedad
					</td>
					<td>
						<?php  echo(getComboFromTable("inmo_tipo_propiedades",$conn,"cboTipoPropiedad", false, true,false, false,200));?>
						
					</td>
				</tr>
				
				<tr height="25px">	
					<td>Cantidad Dormitorios
					</td>
					<td>
						<input type="text" name="dormitorios" id="dormitorios"  style="width:30px;height:15px;display:inline;text-align:right;"/> 
					</td>
				</tr>
			
				<tr height="25px">	
					<td>Provincia
					</td>
					<td>
						<?php  echo(getComboFromTable("imno_provincias",$conn,"cboProvincia", false, true,false, false,200));?>
						
					</td>
				</tr>
			
				<tr height="25px">	
					<td>Localidad
					</td>
					<td>
						<div id="cntLocalidades">						
							<?php  echo(getComboFromTable("inmo_localidades",$conn,"cboLocalidad", false, true,false, false,200));?>
						</div>
						
					</td>
				</tr>
			
				<tr height="25px">	
					<td>Barrio
					</td>
					<td>
						<div id="cntBarrios">	
							<select style="width:200px" id="cboBarrio" selected="5">
								<option id="0" value="0">Seleccione</option>
							</select>
						</div>	
					</td>
				</tr>
				<tr height="25px">	
					<td>Tipo Operación
					</td>
					<td>
						<select style="width:200px" id="cboTipoOperacion" selected="5">
							<option id="1" value="1">Alquiler</option>
							<option id="2" value="2">Venta</option>
							<option id="0"  value="0" selected>Seleccione</option>
						</select>
					</td>
				</tr>
				<tr height="25px">	
					<td>Moneda
					</td>
					<td>
						<select style="width:200px" id="cboMoneda" selected="5">
							<option id="1" value="1">Pesos</option>
							<option id="2" value="2">Dolar</option>
							<option id="0"  value="0" selected>Seleccione</option>
						</select>
					</td>
				</tr>
				
				<tr height="25px">	
					<td>Rango de Precios
					</td>
					<td>
						<input type="text" name="precio_desde" id="precio_desde"  style="width:70px;height:15px;display:inline;text-align:right;"/> &nbsp;&nbsp;
						<input type="text" name="precio_hasta" id="precio_hasta"  style="width:70px;height:15px;display:inline;text-align:right;"/> 
					</td>
				</tr>
				<tr height="25px">	
					<td>Estado
					</td>
					<td>
						<select style="width:200px" id="cboEstado" selected="5">
							<option id="0" >Todos</option>
							<option id="1" selected>Libre</option>
							<option id="2">Ocupada</option>
						</select>
					</td>
				</tr>
						
			</table>
		</div>
		
	</div>
	
	
	<div id="main"  style="position:absolute;left:0px;top:70px;0border:10px solid #6E6E6E;width:100%;height:100%;">
		<div id="left"  style="position:absolute;left:0px;top:0px;0border:1px solid #6E6E6E;width:250px;height:100%">
		</div>
		
		<div id="center"  style="position:absolute;left:250px;right:250px;top:0px;0border:3px solid #6E6E6E;width:100%;height:100%">
			
			
			
				<div id="tabs_ent" cclass="textTabs" style="position:absolute;left:0px;top:0px;width:890px;height:350px;0border:3px solid blue;overflow-y:visible;">
					<ul>
						<li><a href="#tabs-0_ent" class="textTabs" onclick="setTabActivePrincipal(0)">Cobranzas</a></li>
						<li><a href="#tabs-1_ent" class="textTabs" onclick="setTabActivePrincipal(1)">Liquidaciones</a></li>
						<li><a href="#tabs-3_ent" class="textTabs" onclick="setTabActivePrincipal(3)">Contratos</a></li>
						<li><a href="#tabs-4_ent" class="textTabs" onclick="setTabActivePrincipal(4)">Propiedades</a></li>
					</ul>
					
					<div id="tabs-0_ent" style="height:200px">
						
						<div  class="textos" style="position:absolute;left:0px;top:35px;width:720px;height:20px;0border:1px solid blue;">
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="ver_form_filter()"><img src="/icon/find_red.png"  title="Filtrar "  width="16px" />&nbsp;Filtrar </div>
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="cobranzas()"><img src="/icon/pagos.png"  title="Ir A cobranzas "  width="16px" />&nbsp;Cobrar </div>
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="impresion_cobranzas()"><img src="/icon/excel.jpg"  title="Exportar "  width="16px" />&nbsp;Exportar a Excel </div>

						</div>
						
						<div id="total_conbranza" class="textos" style="position:absolute;left:945px;top:35px;width:920px;height:20px;0border:1px solid blue;font-weight:bold;font-size:14px;"><span style="font-size:14px">Total ARS</span> 	<span style="font-size:18px"></span></div>

						
						<div  class="textos" style="position:absolute;left:10px;top:70px;width:1110px;height:20px;0border:2px solid blue;">
							<div class="cntCabecera headerTable" style="width:1110px;background-color:#e6e6e6;">
								<div class="headerTable" style="width:20px;;">#</div>		
								<input class="headerTable" 	style="width:200px;background-color:#e6e6e6;" value="Locatario" onkeypress="return false;"></input>	
								<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Contrato" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:50px;background-color:#e6e6e6;" value="Cuota" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:80px;background-color:#e6e6e6;" value="Vencimiento" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Importe" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Intereses" onkeypress="return false;"></input>		
								<input class="headerTable"  style="width:90px;background-color:#e6e6e6;" value="Total" onkeypress="return false;"></input>		
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="F.Pago" onkeypress="return false;"></input>		
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="Valor Cuota" onkeypress="return false;"></input>	
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="Intereses P." onkeypress="return false;"></input>	
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="Total P." onkeypress="return false;"></input>		
								<input class="headerTable"  style="width:100px;background-color:#e6e6e6;" value="Descargar" onkeypress="return false;"></input>									
							</div>	
						</div>
						<div id="cntContenedor"  class="textos" style="position:absolute;left:10px;top:90px;width:1110px;height:150px;0border:3px solid red;overflow-x:hidden;overflow-y:visible"></div>
					
					</div>
					
					<div id="tabs-1_ent" style="height:200px">
						
						<div  class="textos" style="position:absolute;left:0px;top:35px;width:1220px;height:20px;0border:1px solid blue;">
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="ver_form_filter_LIQUI()"><img src="/icon/find_red.png"  title="Filtrar "  width="16px" />&nbsp;Filtrar </div>
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="liquidaciones()"><img src="/icon/pagos.png"  title="Ir A cobranzas "  width="16px" />&nbsp;Liquidar </div>
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="impresion_liquidaciones()"><img src="/icon/excel.jpg"  title="Exportar "  width="16px" />&nbsp;Exportar a Excel </div>

						</div>
						
						<div id="total_liquidacion" class="textos" style="position:absolute;left:1070px;top:35px;width:920px;height:20px;0border:1px solid blue;font-weight:bold;font-size:14px;"><span style="font-size:14px">Total ARS</span> 	<span style="font-size:18px"></span></div>
						
						
						<div  class="textos" style="position:absolute;left:10px;top:70px;width:1220px;height:20px;0border:2px solid blue;">
							<div class="cntCabecera headerTable" style="width:1220px;background-color:#e6e6e6;">
								
								
								<div class="headerTable" style="width:20px;">#</div>		
								<input class="headerTable" 	style="width:200px;background-color:#e6e6e6;" value="Locador" onkeypress="return false;"></input>	
								<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Contrato" onkeypress="return false;"></input>		
								
								<input class="headerTable" 	style="width:50px;background-color:#e6e6e6;" value="Cuota" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:80px;background-color:#e6e6e6;" value="Vencimiento" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Valor Cuota" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Intereses" onkeypress="return false;"></input>		
								<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Otros" onkeypress="return false;"></input>		
								<input class="headerTable"  style="width:90px;background-color:#e6e6e6;" value="Total" onkeypress="return false;"></input>		
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="F.Pago" onkeypress="return false;"></input>		
								
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="Sub Total Liq." onkeypress="return false;"></input>	
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="Otros" onkeypress="return false;"></input>	
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="Comisión" onkeypress="return false;"></input>
								<input class="headerTable"  style="width:70px;background-color:#e6e6e6;" value="Liquidar" onkeypress="return false;"></input>
								<input class="headerTable"  style="width:90px;background-color:#e6e6e6;" value="F.Liquidación" onkeypress="return false;"></input>								
							
							
								
							
							</div>	
						</div>
						<div id="cntContenedor_02"  class="textos" style="position:absolute;left:10px;top:90px;width:1250px;height:150px;0border:3px solid red;overflow-x:hidden;overflow-y:visible"></div>
					
						
					</div>
					
					
					
					<!-- CONTRATOS-->
					<div id="tabs-3_ent" style="height:200px">
						
						
						<div id="tabs_det" cclass="textTabs" style="position:absolute;left:0px;top:40px;width:890px;height:350px;0border:3px solid blue;overflow-y:visible;">
							<ul>
								<li><a href="#tabs-0_det" class="textTabs" onclick="setPestanaContrato(0)">Listado de Contratos</a></li>
								<li><a href="#tabs-1_det" class="textTabs" onclick="setPestanaContrato(1)">Detalle de Prox. Vtos.</a></li>
							</ul>
							<div id="tabs-0_det" style="height:200px">
							
								<div  class="textos" style="position:absolute;left:0px;top:35px;width:720px;height:20px;0border:1px solid blue;">
									<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="ver_form_filter_contrato(1)"><img src="/icon/find_red.png"  title="Filtrar "  width="16px" />&nbsp;Filtrar </div>
									<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="verContrato()"><img src="/icon/detail.png"  title="Filtrar "  width="16px" />&nbsp;Ver Contrato </div>
									<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="impresion_contrato(1)"><img src="/icon/excel.jpg"  title="Exportar "  width="16px" />&nbsp;Exportar a Excel </div>
								</div>
								
								<div  class="textos" style="position:absolute;left:0px;top:55px;width:1060px;height:20px;oborder:1px solid blue;">
									<div class="cntCabecera headerTable" style="width:1060px;background-color:#e6e6e6;">
										<div class="headerTable" style="width:30px;;">#</div>		
										<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Id" onkeypress="return false;"></input>		
										<input class="headerTable" 	style="width:200px;background-color:#e6e6e6;" value="Locador" onkeypress="return false;"></input>	
										<input class="headerTable" 	style="width:200px;background-color:#e6e6e6;" value="Locatario" onkeypress="return false;"></input>	
										<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Desde" onkeypress="return false;"></input>		
										<input class="headerTable" 	style="width:70px;background-color:#e6e6e6;" value="Hasta" onkeypress="return false;"></input>		
										<input class="headerTable" 	style="width:100px;background-color:#e6e6e6;" value="Deuda" onkeypress="return false;"></input>		
										<input class="headerTable" 	style="width:300px;background-color:#e6e6e6;" value="Documento" onkeypress="return false;"></input>
									</div>	
								</div>
								<div id="cntContenedor_03"  class="textos" style="position:absolute;left:0px;top:80px;width:1060px;height:200px;0border:3px solid red;overflow-x:auto;overflow-y: auto"></div>
					
							
							</div>
							<div id="tabs-1_det" style="height:200px">
								
								<div  class="textos" style="position:absolute;left:0px;top:35px;width:720px;height:20px;0border:1px solid blue;">
									<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="ver_form_filter_contrato(2)"><img src="/icon/find_red.png"  title="Filtrar "  width="16px" />&nbsp;Filtrar </div>
									<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="impresion_contrato(2)"><img src="/icon/excel.jpg"  title="Exportar "  width="16px" />&nbsp;Exportar a Excel </div>
								</div>
								
								<div id="cntContenedor_03A"  class="textos" style="position:absolute;left:0px;top:60px;width:1060px;height:200px;0border:3px solid red;overflow-x:auto;overflow-y: auto"></div>
					
							
							</div>
						</div>	
						
						
					</div>
					
					<!-- PROPIEDADES-->
					<div id="tabs-4_ent" style="height:200px">
						
						<div  class="textos" style="position:absolute;left:0px;top:35px;width:720px;height:20px;0border:1px solid blue;">
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="verFormPropiedad()"><img src="/icon/find_red.png"  title="Filtrar "  width="16px" />&nbsp;Filtrar </div>
							<div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="verPropiedad()"><img src="/icon/detail.png"  title="Filtrar "  width="16px" />&nbsp;Ver Propiedad </div>
							<!--div style="padding-left:10px;cursor:pointer;oborder:1px solid #6E6E6E;width:50px;display:inline"  onclick="descargarExcel()"><img src="/icon/excel.jpg"  title="Exportar "  width="16px" />&nbsp;Exportar a Excel </div-->

						</div>
						
						
						<div id="cntContenedor_04"  class="textos" style="position:absolute;left:0px;top:70px;width:700px;height:200px;0border:3px solid red;;overflow-x:auto;overflow-y: auto"></div>
					
					
					</div>
					
					
					
				</div>
			
   			
			
		</div>
		
	</div>	
	
</body>
</html>
