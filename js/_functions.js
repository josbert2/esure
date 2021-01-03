// This is just a sample script. Paste your real code (javascript or HTML) here.
// Unpacker warning: be careful when using myobfuscate.com for your projects:
// scripts obfuscated by the free online version may call back home.
//

var objFunction = {};
objFunction.responderNotificacion     = false;
objFunction.email_contacto 			  = "";
objFunction.nombre_contacto 		  = "";
objFunction.telefono_contacto 		  = "";
objFunction.propiedades_seleccionadas = "";
objFunction.comunicacion_id			  = 0;
var buscar = 1;

setTimeout(function(){
	
	$("#txtSearch").val("");	
},1000);

function separadorMiles(n, sep, decimals) { 
    sep = sep || "."; // Default to period as decimal separator 
    decimals = decimals || 2; // Default to 2 decimals 

    return n.toLocaleString().split(sep)[0] 
     + sep 
     + n.toFixed(decimals).split(sep)[1]; 
} 

function verContratosProximosVto(){
	window.location="/modules_/imno/consultas_generales?pestana=2&action=1";	
};

function marcarRespondido(id){
	var mensaje ="Desea finalizar esta comunicación?";
	
	if(!confirm(mensaje)){
		return;
	}

	var objData = {};
	 objData.action  = 43;
	 objData.id = id;
	 
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var aux = resp.trim().split("::");
			if(aux[0] =="OK"){				
			 	verAdvertencias();
			} else {
				alert(resp)
			}
	    }
    );
	
}
/*
	type vale 1 para email, 2 para denom
*/

function actualizarDatos(id,type){
	
	if(type == 1){
		var campo = "email_"+id;
	} else if(type == 2){
		var campo = "denom_"+id;
	} else if(type == 3){
		var campo = "celular_"+id;		
	}	
	
	var valor = $("#"+campo).val();
	
	if(valor !=""){
		if(type ==1 && !validarEmail(valor)){
			alert("El valor registrado no tiene forma de email");
			return;
		}
		
		var objData = {};
		objData.action 			 = 6;
		objData.texto			 = valor;
		//objData.id_tipo			 = id_tipo;
		objData.id				 = id;
		objData.type			 = type;
		 
		jQuery.ajax({
			url: '/modules_/imno/saveDataCRM.php',
			type: 'post',
			data: objData
		}).done(
			function (resp) {
				var aux = resp.trim().split("::");
				if(aux[0] !="OK"){				
					alert(resp)
				}
			}
		);	
	}
};

function actualizarTamanoHoja(){
	var objData = {};
		objData.action  	 = 50;
		objData.tamanoPapel  = getComboValue("cboHoja");
		
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   if(resp != "OK"){
			   setMessage(resp);
		   } else {
			   setMessage("Datos grabados correctamente");
		   }
			
	    }
    );
	
};

function actualizarColorSitio(){
	var objData = {};
		objData.action  	 = 51;
		objData.colorSitio  = getComboValue("cboColorSitio");
		
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   if(resp != "OK"){
			   setMessage(resp);
		   } else {
			   setMessage("Datos grabados correctamente");
		   }
			
	    }
    );
	
};

function verCambiosVersion(){
	$("#formNotasVersion").css("display","inline");
	$("#formNotasVersion").css("zIndex",9999999);
	

	var objData = {};
	 objData.action  = 61;
	 
    jQuery.ajax({
        url: '/modules_/imno/consulta.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
		   $("#data_notasVersion").html(resp);
	    }
    );
}

function cerrar_NotasVersion(){
	$("#formNotasVersion").css("display","none");		
};

function configurar(){
	$("#formConfig").css("display","inline");
	$("#formConfig").css("zIndex",9999999);
	
	$("#tabs_empresa").tabs({
		collapsible: false
	});

	var objData = {};
	 objData.action  = 60;
	 
    jQuery.ajax({
        url: '/modules_/imno/consulta.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
			var aux =  resp.split("::");
		   if(aux[0] == "OK"){
			   $("#chk_comparte").prop("checked",(parseInt(aux[1],10) == 1 ? true :false));
			   $("#comision_comparte").val(parseFloat(aux[2]).toFixed(2));
			   $("#chkLogo").prop("checked",(parseInt(aux[3],10) == 1 ? true :false));
			   $("#comentario_recibo_").val(aux[4]);
			   $("#comentario_liquidacion_").val(aux[5]);
			   $("#chkCobroCuentaOrden").prop("checked",(parseInt(aux[6],10) == 1 ? true :false));
			   $("#chkShowPropData").prop("checked",(parseInt(aux[7],10) == 1 ? true :false));
			   posicionarEnId("cboHoja",aux[8]);
			   posicionarEnId("cboColorSitio",aux[9]);
			  
			  $("#chkOtrosVtos").prop("checked",(parseInt(aux[10],10) == 1 ? true :false));
			  
			   habilitarOtrosVtos(false);
			   
			   if(parseInt(aux[10],10) == 1){
				   
				   
				$("#segVtoSiro").val(aux[11]);
				$("#intSegVto").val(aux[12]);
				$("#terVtoSiro").val(aux[13]);
				$("#intTerVto").val(aux[14]);
				$("#porcAdicional").val(aux[15]);
			   }
		   } else {
			   
			   alert(resp);
		   }
			
	    }
    );
	
	var objData = {};
	 objData.action  = 62;
	 
    jQuery.ajax({
        url: '/modules_/imno/consulta.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			
		   $("#cntPropiedadesCompartidas").html(resp);
		  
	    }
    );
	
};

function saveComentarioRecibo(){
	var objData = {};
		objData.action  	 = 44;
		objData.comentario_recibo 	 = $("#comentario_recibo_").val();
		
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   if(resp != "OK"){
			   setMessage(resp);
		   } else {
			   setMessage("Datos grabados correctamente");
		   }
			
	    }
    );
	
};

function saveComentarioLiquidacion(){
	var objData = {};
		objData.action  	 = 45;
		objData.comentario_liquidacion 	 = $("#comentario_liquidacion_").val();
		
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   if(resp != "OK"){
			   setMessage(resp);
		   } else {
			   setMessage("Datos grabados correctamente");
		   }
			
	    }
    );
	
};

function habilitarOtrosVtos(mostrarMensaje = true){
	if($("#chkOtrosVtos").is(":checked")){
		$("#porcAdicional").prop("disabled", false);
		$("#segVtoSiro").prop("disabled", false);
		$("#intSegVto").prop("disabled", false);
		$("#terVtoSiro").prop("disabled", false);
		$("#intTerVto").prop("disabled", false);
		
		$("#segVtoSiro").focus();
		
	} else {
		
		if(mostrarMensaje ){
			var mensaje ="Se quitarán los valores asignados a los Vtos de SIRO.¿Está seguro?";
			
			if(!confirm(mensaje)){
				return;
			}
			
		}
		$("#porcAdicional").val("");
		$("#segVtoSiro").val("");
		$("#intSegVto").val("");
		$("#terVtoSiro").val("");
		$("#intTerVto").val("");
	
		$("#porcAdicional").prop("disabled", true);
		$("#segVtoSiro").prop("disabled", true);
		$("#intSegVto").prop("disabled", true);
		$("#terVtoSiro").prop("disabled", true);
		$("#intTerVto").prop("disabled", true);
	}
	
};

function saveMasConfiguracion(){
	var objData = {};
		
		objData.chkOtrosVtos = ($("#chkOtrosVtos").is(":checked") ? 1 : 0);
		if($("#chkOtrosVtos").is(":checked")){
			
			
			
			objData.porcAdicional = parseFloat($("#porcAdicional").val());
			objData.segVtoSiro = parseInt($("#segVtoSiro").val(),10);
			objData.intSegVto  = parseFloat($("#intSegVto").val());	
			objData.terVtoSiro = parseInt($("#terVtoSiro").val(),10);
			objData.intTerVto  = parseFloat($("#intTerVto").val());	
			
			if(isNaN(objData.porcAdicional) || (objData.porcAdicional >10)){
				setMessage("El recargo por Cargos Bancarios no puede superar el 10 %");
				return;
			}
			
			if(isNaN(objData.segVtoSiro) || (objData.segVtoSiro <=10)){
				setMessage("El 2do vencimiento debe ser mayor al día 10");
				return;
			}
			
			if(isNaN(objData.terVtoSiro) || (objData.terVtoSiro < objData.segVtoSiro)){
				setMessage("El 3do vencimiento debe ser mayor o igual al segundo vencimiento");
				return;
			}
			
			if(isNaN(objData.intSegVto) || (objData.intSegVto < 0)){
				setMessage("Los Punitorios del segundo vencimiento deben ser mayores o iguales a 0");
				return;
			}
			if(isNaN(objData.intTerVto) || (objData.intTerVto < 0) || (objData.intTerVto < objData.intSegVto)){
				setMessage("Los Punitorios del tercer vencimiento deben ser mayores o iguales a 0 y no menores a los segundo vencimiento");
				return;
			}
			
			if(objData.terVtoSiro > 31){
				setMessage("El 3er vencimiento no puede ser mayor al día 31");
				return;
			}
			
			if(objData.segVtoSiro > 31){
				setMessage("El 2do vencimiento no puede ser mayor al día 31");
				return;
			}
			
		}
		
		objData.action  	 				  = 46;
		objData.imprime_logo 				  = ($("#chkLogo").is(":checked") ? 1 : 0);
		objData.imprime_leyenda_cuenta_orden  = ($("#chkCobroCuentaOrden").is(":checked") ? 1 : 0);
		objData.mostrarDatosPropietarios	  = ($("#chkShowPropData").is(":checked") ? 1 : 0);
		
		
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   if(resp != "OK"){
			   setMessage(resp);
		   } else {
			   setMessage("Datos grabados correctamente");
		   }
			
	    }
    );
	
};

function cerrar_formConfig(){
	$("#formConfig").css("display","none");	
};

function clearCheck_propiedad_compartida(id_propiedad){
	var compartir = ($("#chkPropiedad_"+id_propiedad).is(":checked") ? 1 : 0);
	
	var objData = {};
		objData.action  	 = 35;
		objData.compartir 	 = compartir;
		objData.id			 = id_propiedad;
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   if(resp != "OK"){
			   alert(resp);
		   }
			
	    }
    );
	
}

function saveCompartir(){
	
	//var comparte = ($("#chk_comparte").is(":checked") ? 1 : 0);
	var comision_comparte   = parseFloat($("#comision_comparte").val());
	
	if(comision_comparte != "" && isNaN(comision_comparte)){
		alert("Debe ingresar un valor númerico menor a 100, el valor que ingrese será la comisión que usted ofrecerá a sus colegas");
		return;
	}
	
	
	var objData = {};
		objData.action             = 34;
		objData.comparte 		   = (comision_comparte > 0 ? 1 : 0);
		objData.comision_comparte  = comision_comparte;
    jQuery.ajax({
        url: '/modules_/imno/saveData.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   if(resp == "OK"){
			   cerrar_formConfig();			   
		   } else {
			   
			   alert(resp);
		   }
			
	    }
    );
	
};

function verNotificaciones(id){
	$("#formNotificaciones").css("display","inline");
	$("#formNotificaciones").css("zIndex",9999999);
	
	var objData = {};
	 objData.action  = 10;
	 objData.id = id;
    jQuery.ajax({
        url: '/modules_/imno/consultaCRM.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var resp = resp.trim();
		   $("#data_formNotificaciones").html(resp);
			
	    }
    );
};

function responderNotificacion(nombre, email, fono, id){
	
	if(email ==""){
		alert("El contacto no posee una dirección de correo válida");
		return;
	}
	objFunction.responderNotificacion = true;
	objFunction.email_contacto 		= email;
	objFunction.nombre_contacto 	= nombre;
	objFunction.telefono_contacto	= fono;
	objFunction.comunicacion_id		= id;
	
	$("#formRN_title").html(id == 0 ? "Enviar Notificación":" Responder Consulta");

	
	
	$("#formRN").css("display","inline");
	$("#formRN").css("zIndex",(id== 0? 999999:10));
	
	var objData = {};
	 objData.action  = 58;
    jQuery.ajax({
        url: '/modules_/imno/consulta.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var aux = resp.trim().split("::");
			if(aux[0] =="OK"){				
			   $("#data_formRN").html(aux[1]);
				$("#denom_contacto").val("Responder a "+nombre);
				$("#mensaje_respuesta").focus();
				$("#mensaje_respuesta").val(id == 0 ? "Estimado/a ":"Estimado/a muchas gracias por comunicarse, ");
				
			} else {
				alert(resp)
			}
	    }
    );
};
	
function enviarNotificacion(){
	
	$("#loader_center").css("display","");
	
	setTimeout(function(){
		var objData = {};
		objData.action 			 = (objFunction.comunicacion_id > 0 ? 5 : 7);
		objData.nombre_contacto  = objFunction.nombre_contacto;
		objData.email_contacto   = objFunction.email_contacto;
		objData.telefono_contacto= objFunction.telefono_contacto;
		objData.comunicacion_id  = objFunction.comunicacion_id;
		
		objData.mensaje			 = $("#mensaje_respuesta").val();
		objData.recordar		 = ($("#chk_seguimiento").is(":checked") ? 1 : 0);
		objData.compartir_propiedades = $("#propiedades_compartidas").val();
		 
		if( objData.mensaje == ""){
			alert("Debe ingresar un mensaje.");
			return;
		} 
		 
		jQuery.ajax({
			url: '/modules_/imno/saveDataCRM.php',
			type: 'post',
			data: objData
		}).done(
			function (resp) {
				var aux = resp.trim().split("::");
				if(aux[0] =="OK"){				
					
					$("#formRN").css("display","none");
					verAdvertencias();
					$("#mensaje_respuesta").val("Estimado/a muchas gracias por comunicarse, ");
					objFunction.responderNotificacion     = false;
					objFunction.email_contacto 			  = "";
					objFunction.nombre_contacto 		  = "";
					objFunction.telefono_contacto 		  = "";
					objFunction.propiedades_seleccionadas = "";
					objFunction.comunicacion_id			  = 0;
				} else {
					alert(resp)
				}
			$("#loader_center").css("display","none");
		});
		
	},1000);	 
};

function verAdvertencias(){
	$("#formCampana").css("display","inline");
	
	$("#formCampana").css("zIndex","10");
	var objData = {};
	 objData.action  = 57;
    jQuery.ajax({
        url: '/modules_/imno/consulta.php',
        type: 'post',
        data: objData
    }).done(
        function (resp) {
			var aux = resp.trim().split("::");
			if(aux[0] =="OK"){				
			   $("#cntData").html(aux[1]);
			} else {
				alert(resp)
			}
	    }
    );
};

function cerrar_formNotificaciones(){
	$("#formNotificaciones").css("display","none");
}

function cerrar_formRN(){
	$("#formRN").css("display","none");
}

function cerrarVtnCampana(){
	$("#formCampana").css("display","none");
}
	
function verContratosProximosVto(){

	window.location="/modules_/imno/consultas_generales?pestana=2&action=1";	

};


function buscarPor(id){
	//id = 1 => personas
	//id = 2 => propiedades
	$("#txtSearch").val("");
	
	if(id == 1){
		buscar = 1;
		$("#contacto_visible").css("display","inline");
		$("#contacto_oculto").css("display","none");
		
		$("#propiedad_visible").css("display","none");
		$("#propiedad_oculta").css("display","inline");
		
		$("#propiedad_red_visible").css("display","none");
		$("#propiedad_red_oculta").css("display","inline");
		
		
	} else if(id==2){
		buscar = 2;
		$("#contacto_visible").css("display","none");
		$("#contacto_oculto").css("display","inline");
		
		$("#propiedad_visible").css("display","inline");
		$("#propiedad_oculta").css("display","none");
		
		$("#propiedad_red_visible").css("display","none");
		$("#propiedad_red_oculta").css("display","inline");
	} else if(id==3){
		buscar = 3;
		$("#contacto_visible").css("display","none");
		$("#contacto_oculto").css("display","inline");
		
		$("#propiedad_visible").css("display","none");
		$("#propiedad_oculta").css("display","inline");
		
		$("#propiedad_red_visible").css("display","inline");
		$("#propiedad_red_oculta").css("display","none");
	}	
	
};	
	
function buscarRegistros(event){

	setTimeout(function(){
		
		var chCode = ('charCode' in event) ? event.charCode : event.keyCode;
		
		if($("#txtSearch").val().length <=2){return} 
		
		$("#formResultado" ).css("display","block");
		
		if(objFunction.responderNotificacion == 1){
			$("#formResultado" ).dialog({
			  resizable: false,
			  width:820,
			  height:500,
			  title:"Resultado de Busqueda",
			  modal: false,
			  buttons: {
				Seleccionar: function(){
				  
					objFunction.propiedades_seleccionadas = getSelectedCheck("chkPropiedad_", "");
					if(objFunction.propiedades_seleccionadas == ""){
						alert("Por favor seleccione que propiedad desea compartir");
						return;
					}
					$("#propiedades_compartidas").val("Propiedades Compartidas "+objFunction.propiedades_seleccionadas);
					 $( this ).dialog( "close" );
				},
				Cerrar: function(){
				  $( this ).dialog( "close" );
				}
			  }
			});	
		} else {
			$("#formResultado" ).dialog({
			  resizable: false,
			  width:820,
			  height:500,
			  title:"Resultado de Busqueda",
			  modal: false,
			  buttons: {
				 Cerrar: function(){
				  $( this ).dialog( "close" );
				}
			  }
			});				
		}		
		
		$(".ui-dialog-titlebar").hide()     
	
		$("#txtSearch").focus();
		
		var datos = {};
		datos.action  			 = (buscar == 1 ?  55 : (buscar ==2 ? 56 : 59));
		datos.texto  			 = $("#txtSearch").val();
		datos.mostrarSelector    = (objFunction.responderNotificacion ? 1:0);

	   jQuery.ajax({
			async: false,
			url: '/modules_/imno/consulta.php',
			type: 'post',
			data: datos
		}).done(
			function (resp) {
				$("#formResultado").html(resp);
			}
		);
		
	},1000);
	
	
}	

var ventana_ancho = null;
var ventana_alto = null;

function addDaysToStrFecha(strData, months){
	var auxDate = strData.split("/");
	var new_date = new Date(auxDate[2], (auxDate[1]-1), auxDate[0]); // January 1, 2000
	
	new_date.setMonth(new_date.getMonth() + months);
	new_date.setDate(new_date.getDate() -1);       
	var retorno =  new_date.getDate() + "/"+ (new_date.getMonth() + 1) +"/"+new_date.getFullYear();
	return retorno;
};
	
	
function getSelectedCheck(name, exluir_legajo){
	myFormDiv = document.getElementsByTagName("input");
	var id_str = "";
	
	for(var a=0; a < myFormDiv.length; a++){
	
		if(myFormDiv[a].getAttribute("id")){
		var id= myFormDiv[a].getAttribute("id");
			var expresion ="^"+name;
			re = new RegExp(expresion);
			if(id.match(re)){
				if($("#"+id).is(":checked")){
					var aux = id.split("_");
					id_str += (exluir_legajo != aux[1] ? (aux[1]+",") :"");
				}
			}
		}
	}	
	
	if(id_str !=""){
		id_str = id_str.substr(0, id_str.length-1);
	}
	return id_str;
};

function validarHora(hora){
	var retorno = false;

	var patron=/^(0[1-9]|1\d|2[0-3]):([0-5]\d)$/;
	if (patron.test(hora)){
		retorno = true;
	}

	return retorno;
};

function cerrarSesion() {
   var datos = "logout=" + $("#logout").val();
    $("#frm_logout").attr("action", "/includes/validar_ingreso?" + datos);
    $("#frm_logout").submit();
};

function disabledEnabled(nameForm, falseTrue, arrayExecpiones) {
    
	var myFormPersons = document.getElementById(nameForm);
	
	if(typeof myFormPersons =="undefined" || myFormPersons == null){return false;}
	
    var myFormInputs = myFormPersons.getElementsByTagName("input");
    var continuar = true;

    for (var a = 0; a < myFormInputs.length; a++) {
        if (myFormInputs[a].getAttribute("id")) {
            var id = myFormInputs[a].getAttribute("id");

            if (arrayExecpiones) {
                var count = arrayExecpiones.length;
                for (var b = 0; b < count; b++) {
                    if (id == arrayExecpiones[b]) {
                        continuar = false;
                    }
                }
            }
            if (continuar) {
                $("#" + myFormInputs[a].getAttribute("id")).prop('disabled', falseTrue);
            }
            continuar = true;
        }
    }
    var myFormSelect = myFormPersons.getElementsByTagName("select");


    continuar = true;

    for (var a = 0; a < myFormSelect.length; a++) {
        if (myFormSelect[a].getAttribute("id")) {

            if (arrayExecpiones) {
                var count = arrayExecpiones.length;
                for (var b = 0; b < count; b++) {
                    if (id == arrayExecpiones[b]) {
                        continuar = false;
                    }
                }
            }
            if (continuar) {
                $("#" + myFormSelect[a].getAttribute("id")).prop('disabled', falseTrue);
            }
            continuar = true;
        }
    }

    var myFormButton = myFormPersons.getElementsByTagName("button");

    for (var a = 0; a < myFormButton.length; a++) {
	    $("#" + myFormButton[a].getAttribute("id")).prop('disabled', falseTrue);
    }
};



function clearForm(nameForm, arrayExecpiones) {
    
	var myFormPersons = document.getElementById(nameForm);
	
	if(typeof myFormPersons =="undefined" || myFormPersons == null){return false;}
	
	var myFormInputs = myFormPersons.getElementsByTagName("input");
    var continuar = true;

    for (var a = 0; a < myFormInputs.length; a++) {
        if (myFormInputs[a].getAttribute("id")) {
            var id = myFormInputs[a].getAttribute("id");

            if (arrayExecpiones) {
                var count = arrayExecpiones.length;
                for (var b = 0; b < count; b++) {
                    if (id == arrayExecpiones[b]) {
                        continuar = false;
                    }
                }
            }
            if (continuar) {
				
                $("#" + myFormInputs[a].getAttribute("id")).val("");
				$("#" + myFormInputs[a].getAttribute("id")).prop("checked", false);
            }
            continuar = true;
        }
    }
  
	
	var myFormInputs = myFormPersons.getElementsByTagName("textarea");

    for (var a = 0; a < myFormInputs.length; a++) {
        if (myFormInputs[a].getAttribute("id")) {
            var id = myFormInputs[a].getAttribute("id");

			$("#" + myFormInputs[a].getAttribute("id")).val("");
        }
    }
  
  
};


/** getFormField
 * @param nameForm nombre del formulario contenedor.
 * @return object .
 * @throws No dispara ninguna excepcion.
 * @observaciones esta función recibe un nombre de formulario y retorna un objeto par atributo valor
 * de todos aquellos campos que poseen identificador.
 */
function getFormField(nameForm) {
    try {
        var objData = {};
        myFormPersons = document.getElementById(nameForm);
        myFormInputs = myFormPersons.getElementsByTagName("input");
        for (var a = 0; a < myFormInputs.length; a++) {
            if (myFormInputs[a].getAttribute("id")) {
                var id = myFormInputs[a].getAttribute("id");
                var valor = $("#" + myFormInputs[a].getAttribute("id")).val();
                objData[id] = valor;
            }
        }


        myFormSelect = myFormPersons.getElementsByTagName("select");

        for (var a = 0; a < myFormSelect.length; a++) {
            if (myFormSelect[a].getAttribute("id")) {

                var id = myFormSelect[a].getAttribute("id");
                var index = document.getElementById(id).selectedIndex;
                var valor = document.getElementById(id).options[index].value;
                objData[id] = valor;
            }
        }

        objData["tipoabm"] = persons.tipoAbm;

        return objData;

    } catch (ex) {
        message("getFormField..." + ex.message, 3);
    }
};

/**
	Retorna un campo de la tabla perons
	fieldFind....por que campo buscamos por ej.cuit
	fieldData....el dato por el cual buscamos, ej número cuit
	fieldReturn..el campo que esperamos se devuelva por ej. name

*/
function checkDate(date_from, date_to) {
    var retorno = false;

    if (isDate(date_from) && isDate(date_to)) {
        date_from_split = date_from.split("/");
        date_to_split = date_to.split("/");

        date_from = date_from_split[1] + "/" + date_from_split[0] + "/" + date_from_split[2];
        date_to = date_to_split[1] + "/" + date_to_split[0] + "/" + date_to_split[2];

        date_from = new Date(date_from).getTime();
        date_to = new Date(date_to).getTime();
        //--Hasta el 18 del 10 la condicion era  date_to > date_from
		if (date_to >= date_from) {
            retorno = true;
        }
    }
    return retorno;
};


function isDate(d) {
    var retorno = false;
    var validformat = /^\d{2}\/\d{2}\/\d{4}$/;
    if (validformat.test(d)) {
        retorno = true;
    }
    var day = d.split("/")[0]
    var mth = d.split("/")[1]
    var yr = d.split("/")[2]
    var bday = new Date(yr, mth - 1, day)
    if ((bday.getMonth() + 1 != mth) || (bday.getDate() != day) || (bday.getFullYear() != yr)) {
        retorno = false;
    }
    return retorno;
};

function validarEmail(email) {
    var retorno = false;
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (expr.test(email)) {
        //alert("Error: La dirección de correo " + email + " es incorrecta.");
        retorno = true;
    }
    return retorno;
};

function validarCuit(inputValor) {
    var retorno = false;
    var aux = inputValor.split("-");
    inputValor = aux[0] + aux[1] + aux[2];

    inputString = inputValor.toString()

    if (inputString.length == 11) {
        var Caracters_1_2 = inputString.charAt(0) + inputString.charAt(1)
        if (Caracters_1_2 == "20" || Caracters_1_2 == "23" || Caracters_1_2 == "24" || Caracters_1_2 == "27" || Caracters_1_2 == "30" || Caracters_1_2 == "33" || Caracters_1_2 == "34") {
            var Count = inputString.charAt(0) * 5 + inputString.charAt(1) * 4 + inputString.charAt(2) * 3 + inputString.charAt(3) * 2 + inputString.charAt(4) * 7 + inputString.charAt(5) * 6 + inputString.charAt(6) * 5 + inputString.charAt(7) * 4 + inputString.charAt(8) * 3 + inputString.charAt(9) * 2 + inputString.charAt(10) * 1
            Division = Count / 11;
            if (Division == Math.floor(Division)) {
                retorno = true;
            }
        }
    }
    return retorno;
};


function getSpanishDate(date) {
    //date ---> 2013-10-17 to 17-10-2013
	var retorno ="";
	
	if(date !="0000-00-00" && typeof date !="undefined"){
		retorno =  (date == "" ? "" : (date.substring(8, 10) + "/" + date.substring(5, 7) + "/" +date.substring(0, 4)));
	}
    
	return retorno;
};

function getEnglishDate(date) {
    //date ---> 17-10-2013 to 2013-10-17 
	return date == "" ? "" : (date.substring(6, 10) + "/" + date.substring(3, 6) + "/" +date.substring(0, 2));
     
};


function posicionarEnId(combo, val) {
    try{
		for (var indice = 0; indice < document.getElementById(combo).length; indice++) {
			if (document.getElementById(combo).options[indice].value == val)
				document.getElementById(combo).selectedIndex = indice;
		}
	}catch(ex){
		alert(ex.message + " " +combo +" " + val )
	}
};

function getComboValue(comboId) {
    var index = document.getElementById(comboId).selectedIndex;
    return document.getElementById(comboId).options[index].value;
};

function optionMenuContext(o) {
    for (k in o) {
        if (typeof o[k] == "function") {
            continue;
        }
    }
};

function setMessage(str){
	
	$("#cntMessage").html(str);
	$("#cntMessage").css("display","");
	
	setTimeout(function(){
		$("#cntMessage").html("");
		$("#cntMessage").css("display","none");

		
	},2000);
}

function message(msn, tipo) {
    var imagen = "";
    if (tipo == 1) {
        imagen = "ok.jpg";
    } else if (tipo == 2) {
        imagen = "advertencia.png";
    } else {
        imagen = "error.png";
    }
    var data = '<div style="heigth:16px; width:100%;font-size:12px;font-weight:bold;" ><img src="/icon/' + imagen + '" style="heigth:16px; width:16px;font-size:12px;font-weight:bold;padding-right:5px;padding-left:10px;">' + msn + '</div>';
    $("#msn").html(data);
	$("#msn").css("display", "");
	
    setTimeout(function () {
        $("#msn").html("");
		$("#msn").css("display", "none");
		
    }, 5000);
};

function getKeyPassword() {
    return "1882";
};


function cuenta_letras(objCampo, limite) { 
	if(objCampo.value.length > limite){
		objCampo.value = objCampo.value.substring(0, limite);
	}
	$("#remLen").val((limite-objCampo.value.length));
};

function verNotaVersion(){
	
	var texto ="<span class=\"fondoNegro\">Interfaz Perfil de Sistemas</span></br>"+
	"Se corrige cuadro de Seleccion de Rol.</br>"+
	"<span class=\"fondoNegro\">Interfaz Estado de Documentacion</span></br>"+
	"Se corrige largo de nombres de Empresas y Colaboradores para evitar solapamiento en la pantalla.</br>"+
	"<span class=\"fondoNegro\">Interfaz Registros Horarios</span></br>"+
	"Se corrige consulta ya que para contratistas sin empleados no se devolvían registros.</br>"+
	"<span class=\"fondoNegro\">Documentacion Mensual</span></br>"+
	"Se corrige la pantalla, para pantallas de baja resolución no se visualizaban a los colaboradores, al buscar a un contratistas se repetía la información en ambas grillas y no se limpiaba la grilla de colaboradores.</br>"+
	"<span class=\"fondoNegro\">Ingreso al Sistema</span></br>"+
	"Al ingresar con acceso solo a reportes el sistema mostraba acceso a otra interfaz.";
	
	$("#form-ayuda").html(texto);
	
	$("#form-ayuda").css("display", "");

	 $("#form-ayuda").dialog({
        resizable: false,
		title: "Notas de  Versión ",
        height: 400,
        width: 720,
        modal: true
    });
};


function proximaLiberacion(){
	
	var texto ="<span class=\"fondoNegro\">Seguimiento Satelital</span></br>"+
	"A la gestión de documentación y matenimiento vehicular le agregamos SEGUIMIENTO SATELITAL.</br>"+
	"Con esta nueva funcionalidad integra la gestión y seguimiento de la flota vehicular en una sola aplicación.</br>"+
	"<span class=\"fondoNegro\">Relojes de Marcación Biométricos</span></br>"+
	"Con esta nueva liberación, automatiza el ingreso de tus colaboraores, propios o terceros a tu empresa.</br>"+
	"Con tarjeta Contact-less o con registro Biométrico controla el ingreso a tus instalaciones";
	
	$("#form-ayuda").html(texto);
	
	$("#form-ayuda").css("display", "");

	 $("#form-ayuda").dialog({
        resizable: false,
		title: "Proximamente....",
        height: 400,
        width: 720,
        modal: true
    });
};


function verAyuda(){

    $("#form-ayuda").css("display", "");

	 $("#form-ayuda").dialog({
        resizable: false,
        height: 400,
        width: 720,
        modal: true
    });
};

function changePassword() {


    if ($("#password_new").val() != $("#password_new_repeat").val()) {
        message("La nueva clave y la clave de repetición no coinciden, por favor verifique.", 2);
        return;
    }

    $("#form-change-password").dialog({
        resizable: false,
        height: 200,
        width: 320,
        modal: true,
        buttons: {
            "Grabar Datos": function () {
				var password_old = $("#password_old").val();
				
				if(isNaN(parseInt(password_old,10))){
					password_old = calcMD5(password_old);
				}
				
				var password_new = $("#password_new").val();
				
				if(isNaN(parseInt(password_new,10))){
					password_new = calcMD5(password_new);
				}
				

                jQuery.ajax({
                    async: false,
                    url: '/modules_/general/saveData',
                    type: 'post',
                    data: {
                        password_old: password_old,
                        password_new: password_new,
                        tipoabm: 6
                    }

                }).done(
                    function (resp) {
                        resp = resp.trim();
                        if (resp == "OK") {
                            setMessage("Clave cambiada con éxito", 1);
                        } else {
                            setMessage(resp, 3);
                        }
                    }
                );

                $(this).dialog("close");
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        }
    });
};


function seePassword() {
    if ($("#chk_password").is(":checked")) {
        $("#password_old").attr("type", "text");
        $("#password_new").attr("type", "text");
        $("#password_new_repeat").attr("type", "text");


    } else {
        $("#password_old").attr("type", "password");
        $("#password_new").attr("type", "password");
        $("#password_new_repeat").attr("type", "password");
    }
};