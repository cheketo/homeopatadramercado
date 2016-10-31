<?
	
	require("../../js/xajax/xajax_core/xajax.inc.php");
	include("../../0_mysql.php");

	
	function callScript($pos){
	
		//INICIO AJAX RESPONSE
		$response = new xajaxResponse();
		
		if($pos == 0){
			unlink('usuarios_web.csv');
			$log .= "Por favor no cierre esta ventana, se van a exportar los usuarios. <br><br>";
		}
		
		//VARIABLES
		$i = $pos;
		$cada = 500;
		$log = "";
		$ingresado = 0;
		$fin = $pos+$cada;
		$c = 0;
		
		//VARIABLES PROVENIENTES DEL ARCHIVO
		$result_exportar = mysql_query($_GET['sql_query']);

		
		while($rs_exportar = mysql_fetch_assoc($result_exportar)) { 
			$datos[$c] = $rs_exportar['mail'];
			$c++;
		}

		
		//ABRO ARCHIVO
		$handle = fopen('usuarios_web.csv', 'a');
		
		//REVISAR TANDA DE MAILS DEL ARRAY
		while($i < $fin && $i < $c){
			
			$iduser_segmentacion_exportar = '-';

			$query_id = "SELECT iduser_web FROM user_web WHERE mail = '$datos[$i]'";
			$rs_iduser_web = mysql_fetch_assoc(mysql_query($query_id));
			
			$query_segmentacion = "SELECT iduser_segmentacion
			FROM user_web_segmentacion 
			WHERE iduser_web = '$rs_iduser_web[iduser_web]'";
			$result_segmentacion = mysql_query($query_segmentacion);
			
			while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){
				$iduser_segmentacion_exportar .= $rs_segmentacion['iduser_segmentacion']."-";
			}
			
			fputs($handle, $datos[$i].";".$iduser_segmentacion_exportar."\n");	
			
			$ingresado++;
			$i++;
				
		}
		
		//CIERRO ARCHIVO
		fclose($handle);
		
		
		if($i < $c){
			$hacer = true;
		}else{
			$hacer = false;
		}
		
		
		$log .= "<b>Exporting from line $pos to $i</b><br>
		<i>total successful export: $ingresado<br><br></i>";
			
		$response->call("myJSFunction", $pos, $cada, array("log" => $log, "cant_mail" => $cant_mail, "hacer" => $hacer));
		return $response;
	}

	$xajax = new xajax();
	// $xajax->setFlag("debug", true);
	$xajax->registerFunction("callScript");
	$xajax->processRequest();
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>call Script Test | xajax Tests</title>

<?php $xajax->printJavascript("../../js/xajax/") ?>
<script type="text/javascript">

	function myJSFunction(firstArg, numberArg, myArrayArg){
	
		xajax.$('myDiv').innerHTML = myArrayArg["log"] + xajax.$('myDiv').innerHTML + "\n<br>";
		if(myArrayArg.hacer == true){
			xajax_callScript(firstArg+numberArg);
		}else{
			xajax.$('result').innerHTML = "Se han importado correctamente. <br /><a href=\"usuarios_web.csv\" target=\"_blank\">Descargar.</a>";
		} 
		
	}

</script>
<style type="text/css">
.div_export {
	height:300px;
	width:95%;
	overflow:auto; 
	background-color:#FBFBFB;
	padding: 15px;
	border:1px #666666 solid;
}

.text_export {
	font-family:Arial, Helvetica, sans-serif; 
	font-size:12px; 
	color:#666666;
}
</style>
</head>
<body onload="javascript:xajax_callScript(0);">
<div id="myDiv" class="div_export text_export" style="width:96%" ></div>
<br />
<div class="text_export" id="result" style="height:30px; width:100%; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666;" ></div>
</body>
</html>