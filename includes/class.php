<?php
	
	class regional_config{
		function setTimezone($str){
			date_default_timezone_set($str);//"America/Argentina/Cordoba"
		}
		
		function setLocalZone($str){
			 setlocale(LC_TIME, $str); // setlocale(LC_TIME, "spanish"); 
			setlocale(LC_ALL,"es_ES");
		}

	};
	
	class Matrix{
		var $retorno ="";
		var $boolMark ="";
		
		function createTable(){
			$this->retorno ="<table style=\"width:400px;\"  cellspading=\"0\" cellspacing=\"0\" border=\"1\">".
								"<tr>".
									"<td width=\"50px\">#</td>".
									"<td width=\"100px\">Legajo</td>".
									"<td width=\"250px\">Apellido y Nombre</td>".
								"</tr>";
		}
	};
	
	class Query{
		var $oConection;
		var $stmt;
		var $registros ="";
		public $userName ="";
		public $passWord ="";
		public $dataBase ="";
		
		function __construct(){
			$OPCIONES = array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
					); 
			
			$conn = new PDO("mysql:host=localhost;port=3306;dbname=".$_SESSION["db_name"], $_SESSION["db_user"], $_SESSION["db_password"], $OPCIONES);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$this->oConection = $conn;
			$this->userName = $_SESSION["db_user"];
			$this->passWord = $_SESSION["db_password"];
			$this->dataBase = $_SESSION["db_name"];
		}
		
		function setConection($oConection){
			$this->oConection = $oConection;
		}
	   
		function getRecords($sql , $stmt_new = ""){
			try{
			
				$this->stmt = $this->oConection->prepare($sql);
				$this->stmt->execute();
				$registros = $this->stmt->rowCount();
				$this->stmt = "";	
				return $registros;
				
			
			} catch (Exception $e) {
				logg("getRecords ".$sql. " " .$e->getMessage,""); 
			}
		}
		
		
		public function getRecordSet($sql){
			
			$this->stmt = $this->oConection->prepare($sql);
			$this->stmt->execute();	
			$stmt = $this->stmt;	
			return $stmt;
		}
		
		function executeSql($sql){
			$this->oConection->exec($sql);
		}
	};
	
	class makeDate{
		var $fechaHoraActual = "";
		var $fechaActual = "";
		var $fecha ="";
		var $periodeArray ="";
		
		
		function __construct(){
			
			// date_default_timezone_set("America/Argentina/Cordoba");
			// setlocale(LC_TIME, "spanish");
			// setlocale(LC_ALL,"es_ES");
			
		}
		
		
		
		function getSpanishDate($str){
			//try{
				
				if($str !=""){
					$aux = explode(" ",$str);
					$fecha = explode("-",$aux[0]);
					//$hora = $aux[1];
					$this->fechaActual = $fecha[2]."/".$fecha[1]."/".$fecha[0];
				
				} else {
					$this->fechaActual = "";
				}
				return $this->fechaActual;
			// }catch($ex){
				// logg("getSpanishDate..".$str ." " .$ex->getMessage(),"");
			// }
		}
		
		function getSpanishDateFromDate($str){
			$fecha = explode("-",$str);
			$this->fechaActual = $fecha[2]."/".$fecha[1]."/".$fecha[0];
			return $this->fechaActual;
		}
		
		function getEnglishDate($str){
			if($str == ""){return $str;};
			$fecha = explode("/",$str);
			
			$this->fechaActual = $fecha[2]."-".$fecha[1]."-".$fecha[0];
			return $this->fechaActual;
		}
		
		function getActualDate(){
			$this->fechaActual = date("Y-m-d");
			return $this->fechaActual;
		}
		
		function getActualTime(){
			$this->fechaActual = date("Y-m-d H:i:s");
			return $this->fechaActual;
		}
		
		public function getPeriodeDate(){
			
			$aux = explode("-",$this->getActualDate());
			$actual_day = $aux[2];
			$actual_month = $aux[1];
			if($actual_day <=24){
				$actual_month --;
			}
			
			$actual_year = $aux[0]; 
			
			
			$date_from = $actual_year."/".$actual_month ."/25 00:00:00";
			
			$next_month = ($actual_month == 12 ? 1 : ($actual_month +1));
			$nex_year = ($actual_month == 12 ? ($actual_year +1) : $actual_year );
			
			$date_to = $nex_year."/".($next_month < 10 ? ("0".$next_month) : $next_month)."/24 23:59:59";
			
			$this->periodeArray = array();
				
			$this->periodeArray[0] = array("date_from"=>$date_from, 
								"date_to"=>$date_to);	
			return $this->periodeArray[0];
		}
		
		public function getAnualPeriodeDate(){
			
			$aux = explode("-",$this->getActualDate());
			$actual_month = $aux[1];//($aux[1] >= 10 ? $aux[1] : ("0".$aux[1] ));
			$actual_year = $aux[0]; 
			
			$date_from = $actual_year."/01/01 00:00:00";
			$date_to = $actual_year."/12/31 23:59:59";
			
			$this->periodeArray = array();
				
			$this->periodeArray[0] = array("date_from"=>$date_from, 
								"date_to"=>$date_to);	
			
			return $this->periodeArray[0];
		}
		
		public function getDayPeriodeDate(){
			
			$aux = explode("-",$this->getActualDate());
			$actual_day = $aux[2];
			$actual_month = $aux[1]; 
			$actual_year = $aux[0]; 
			
			$date_from = $actual_year."/".$actual_month."/".$actual_day. " 00:00:00";
			$date_to = $actual_year."/".$actual_month."/".$actual_day. " 23:59:59";
			
			$this->periodeArray = array();
				
			$this->periodeArray[0] = array("date_from"=>$date_from, 
								"date_to"=>$date_to);	
			
			return $this->periodeArray[0];
		}
	};
	
	class uuid_{
	
		function uuid($serverID=1){
			$t=explode(" ",microtime());
			return sprintf( '%04x-%08s-%08s-%04s-%04x%04x',
			$serverID,
			clientIPToHex(),
			substr("00000000".dechex($t[1]),-8),   // get 8HEX of unixtime
			substr("0000".dechex(round($t[0]*65536)),-4), // get 4HEX of microtime
			mt_rand(0,0xffff), mt_rand(0,0xffff));
		}
	
		function clientIPToHex($ip="") {
			$hex="";
			if($ip=="") $ip=getEnv("REMOTE_ADDR");
			$part=explode('.', $ip);
			for ($i=0; $i<=count($part)-1; $i++) {
				$hex.=substr("0".dechex($part[$i]),-2);
			}
			return $hex;
		}
	};
	
	
?>	