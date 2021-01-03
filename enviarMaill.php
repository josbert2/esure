<?php
if(session_id()==''){session_start();}

include $_SERVER['DOCUMENT_ROOT']."/includes/PHPMailer/class.phpmailer.php";
	include $_SERVER['DOCUMENT_ROOT']."/includes/PHPMailer/class.smtp.php";
	
//$path = '/home/esecurec/public_html/';	
//include($path.'/includes/functions.php'); 


// Valores enviados desde el formulario
if ( !isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["message"]) || !isset($_POST["fono"]) ) {
    die ("Es necesario completar todos los datos del formulario");
}

$nombre  = $_POST["name"];
$email 	 = $_POST["email"];
$mensaje = $nombre  ."</br>" .$_POST["email"] . "</br>" .$_POST["fono"] ."</br> Escribio ".$_POST["message"];

 function SubirArchivo ($sfArchivo){
        $dir_subida = $_SERVER['DOCUMENT_ROOT']."/includes/PHPMailer/plantilla/";
        $fichero_subido = $dir_subida . basename($_FILES[$sfArchivo]['name']);
        if (move_uploaded_file($_FILES[$sfArchivo]['tmp_name'], $fichero_subido)) {
            return $fichero_subido;
        } else {
            return "";
        }
    }

 set_time_limit(0);
    ignore_user_abort(true);
    /*RECOGER VALORES ENVIADOS DESDE INDEX.PHP*/
    $sDestino = "info@esecure.com.ar";
    $sAsunto = "Mensaje desde contacto.";
    $sMensaje = $mensaje;
   // $sImagen = SubirArchivo('logo.png');
  //  $sAdjunto = SubirArchivo('txtAdjun');

    date_default_timezone_set('Etc/UTC');
    /*CONFIGURACIÓN DE CLASE*/
        $mail = new PHPMailer;
        $mail->isSMTP(); //Indicar que se usará SMTP
        $mail->CharSet = 'UTF-8';//permitir envío de caracteres especiales (tildes y ñ)
    /*CONFIGURACIÓN DE DEBUG (DEPURACIÓN)*/
        $mail->SMTPDebug = 0; //Mensajes de debug; 0 = no mostrar (en producción), 1 = de cliente, 2 = de cliente y servidor
        $mail->Debugoutput = 'html'; //Mostrar mensajes (resultados) de depuración(debug) en html
    /*CONFIGURACIÓN DE PROVEEDOR DE CORREO QUE USARÁ EL EMISOR(GMAIL)*/
		$mail->Host = "mail.esecure.com.ar"; 
        $mail->Port = 587; //Puerto SMTP, 587 para autenticado TLS
        $mail->SMTPSecure = 'tls'; //Sistema de encriptación - ssl (obsoleto) o tls
        $mail->SMTPAuth = true;//Usar autenticación SMTP
        $mail->SMTPOptions = array(
            'ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true)
        );//opciones para "saltarse" comprobación de certificados (hace posible del envío desde localhost)
    //CONFIGURACIÓN DEL EMISOR
		$mail->Username = "info@esecure.com.ar";
		$mail->Password = "antuanCITO135"; 
        $mail->setFrom($_SESSION["EMAIL_FROM"], 'Pagina Web');

    //CONFIGURACIÓN DEL MENSAJE, EL CUERPO DEL MENSAJE SERA UNA PLANTILLA HTML QUE INCLUYE IMAGEN Y CSS
        $mail->Subject = $sAsunto; //asunto del mensaje
        //incrustar imagen para cuerpo de mensaje(no confundir con Adjuntar)
	//	$pathImagen =  $_SERVER['DOCUMENT_ROOT']."/includes/PHPMailer/plantilla/logo.png";
//		$mail->AddEmbeddedImage($pathImagen, 'imagen'); //ruta de archivo de imagen
        //cargar archivo css para cuerpo de mensaje
			
	
            $rcss = $_SERVER['DOCUMENT_ROOT']."/includes/PHPMailer/plantilla/estilo.css";//ruta de archivo css
            $fcss = fopen ($rcss, "r");//abrir archivo css
            $scss = fread ($fcss, filesize ($rcss));//leer contenido de css
            fclose ($fcss);//cerrar archivo css
        //Cargar archivo html   
		
		 
		
            $shtml = file_get_contents($_SERVER['DOCUMENT_ROOT']."/includes/PHPMailer/plantilla/mensaje1.html");
        //reemplazar sección de plantilla html con el css cargado y mensaje creado
            $incss  = str_replace('<style id="estilo"></style>',"<style>$scss</style>",$shtml);
            $cuerpo = str_replace('<p id="mensaje"></p>',$sMensaje,$incss);
        $mail->Body = $cuerpo; //cuerpo del mensaje
        $mail->AltBody = '---';//Mensaje de sólo texto si el receptor no acepta HTML

    //CONFIGURACIÓN DE ARCHIVOS ADJUNTOS 
// $mail->addAttachment($sAdjunto);

    //CONFIGURACIÓN DE RECEPTORES
        $aDestino = explode(",",$sDestino);
        foreach ( $aDestino as $i => $sDest){
            $mail->addAddress(trim($sDest), "Destinatario ".$i+1);
        }
    //ENVIAR MENSAJE
    if (!$mail->send()) {
        echo "Error al enviar: " . $mail->ErrorInfo;
    } else {
        echo "Mensaje enviado correctamente";
        //eliminar archivos temporales de carpeta subidas
       // unlink($sImagen);
        //unlink($sAdjunto);
    }

//echo(sendMailEsecure("Email de Contacto", $mensaje, "alejandroanara@gmail.com", "info@esecure.com.ar", "info@esecure.com.ar", "Esecure SaaS"));

?>