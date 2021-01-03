
function sendEmail(){
	
	if($("#contacto_nombre").val()==""){
		alert("Por favor ingrese su nombre.");
		$("#contacto_nombre").focus();
		return;
	}
	
	if($("#contacto_mensaje").val()==""){
		alert("Por favor ingrese un mensaje.");
		$("#contacto_mensaje").focus();
		return;
	}
	
	if(!validarEmail($("#contacto_email").val())){
		alert("La dirección ingresada ["+$("#contacto_email").val()+"] no es válida.");
		$("#contacto_email").focus();
		return;
	}
	var datos = "contacto_nombre=" + $("#contacto_nombre").val()+"&"+
				"contacto_telefono=" + $("#contacto_telefono").val()+"&"+
				"contacto_email=" + $("#contacto_email").val()+"&"+
				"contacto_mensaje=" + $("#contacto_mensaje").val();
	
	$("#ContactForm").submit();
	clearSendEmail();
};

function clearSendEmail(){
	$("#contacto_nombre").val("");
	$("#contacto_telefono").val("");
	$("#contacto_email").val("");
	$("#contacto_mensaje").val("");
};	

