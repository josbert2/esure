<?php header('Content-Type: text/html; charset=utf8');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/conexion_mysqli.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/functions_inmo.php');
include($_SERVER["DOCUMENT_ROOT"] .'/includes/class.php');

getdata($conn, "{mail.esecure.com.ar/pop3/ssl/novalidate-cert}", $_SESSION["email_acount"], "antuanCITO135");

function getNextMail($conn){
	
	$ultimo_mail_leido = 0;
	$sql="SELECT COALESCE(ultimo_mail_leido +1,2) AS ultimo_mail_leido
		FROM enterprise";
		
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$registros = $stmt->rowCount();
	if($registros == 1){
		$row = $stmt->fetch();
		$ultimo_mail_leido = $row["ultimo_mail_leido"];
	}	
	
	return $ultimo_mail_leido;
}

function getdata($conn, $host, $login, $password, $savedirpath = "") {
	$FECHA_HORA_ACTUAL   = date("Y-m-d H:i:s");

	 
	 $mbox = imap_open($host, $login, $password) or die("can't connect: " . imap_last_error());
     $message = array();
     $message["attachment"]["type"][0] = "text";
     $message["attachment"]["type"][1] = "multipart";
     $message["attachment"]["type"][2] = "message";
     $message["attachment"]["type"][3] = "application";
     $message["attachment"]["type"][4] = "audio";
     $message["attachment"]["type"][5] = "image";
     $message["attachment"]["type"][6] = "video";
     $message["attachment"]["type"][7] = "other";
     $buzon_destino = "cfdi";
     //echo imap_createmailbox($mbox, imap_utf7_encode("{$buzon_destino}"));
     //echo imap_num_msg($mbox);
 
	$limite = imap_num_msg($mbox);
	
	$sql_insert_mensaje = "";
	$id_nex_mail = getNextMail($conn);
	
	for ($jk = $id_nex_mail ; $jk <= $limite; $jk++) {
		$structure = imap_fetchstructure($mbox, $jk);

		$result = imap_fetch_overview($mbox, $jk);

		$mail 		  = imap_fetchbody($mbox,$jk,1);
		$destinatario = $result[0]->from;
		$mensaje 	  = imap_fetchbody($mbox,$jk,1);

		$sql_insert_mensaje .=" INSERT INTO inmo_contactos_comunicaciones ".
										"(id_contacto, mensaje, fechor) VALUES ".
										"(0, '$mensaje', '$FECHA_HORA_ACTUAL');";
		
			

	}
	
	if($sql_insert_mensaje !=""){
		$conn->exec($sql_insert_mensaje);	
		
		$update = "UPDATE enterprise SET ultimo_mail_leido = $limite WHERE 1=1";
		$conn->exec($update);	
	}
	
	imap_close($mbox);
}
?>