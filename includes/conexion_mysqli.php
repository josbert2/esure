<?php 
if(session_id()==''){session_start();};

$OPCIONES = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	PDO::ATTR_PERSISTENT => true
); 
//	$conn = new PDO("mysql:host=".'localhost'.";port=3306;dbname=".$_SESSION["db_name"], $_SESSION["db_user"], $_SESSION["db_password"], $OPCIONES)
try {
	$conn = new PDO("mysql:host=".'localhost'.";port=3306;dbname=encure", 'root', '', $OPCIONES);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch( PDOException $Exception ) {
    echo("CM Exception: ".$Exception->getMessage( ) ."  ". $Exception->getCode( ));
}
?>