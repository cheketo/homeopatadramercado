<?

	require("../../js/xajax/xajax_core/xajax.inc.php");
	include("../../0_mysql.php");
	include("funciones_importar.php");
	
	//$mail_lista = file('listado_newsletter.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	//echo $mail_lista[0]."<br>";
	//print_r (split(";",$mail_lista[0]));

	function callScript($pos){
	
		//INICIO AJAX RESPONSE
		$response = new xajaxResponse();
		
		//VARIABLES
		$i = $pos;
		$cada = 1000;
		$log = "";
		$ingresado = 0;
		$ingresado_previo = 0;
		$no_valido = 0;
		$fecha_actual = date("Y-m-d");
		$iduser_segmentacion = split("-",$_GET['segmentacion']);
		
		//VARIABLES PROVENIENTES DEL ARCHIVO
		$mail_lista = file('listado_newsletter.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$cant_mail = count($mail_lista);
		
		if($pos == 0){
			$log = "IMPORTANT: Keep this web browser window open during the import. If you close it, the import will be terminated.<br>About to import $cant_mail lines ...<br><br>";
		}
		
		//REVISAR TANDA DE MAILS DEL ARRAY
		while($i < $pos+$cada && $i < $cant_mail){
			
			//Busco si es un mail valido.
			$valido = ValidaMail(trim($mail_lista[$i]));
			$mail_lista[$i] = trim($mail_lista[$i]);
			
			if($valido == true){//Si es un mail valido
			
				//Busco si ya existe ese mail.
				$existe = ExisteUsuario($mail_lista[$i]);
				
				if($existe == 0){ //Si no existe creo el usuario y agrego segmentacion.
					
					$query_ingresar = "INSERT INTO user_web (
					  fecha_alta
					, mail
					, estado
					) VALUES (
					  '$fecha_actual'
					, '$mail_lista[$i]'
					, '1'
					)";
					$res = mysql_query($query_ingresar);
					
					if($res){
					
						$query_max = "SELECT iduser_web
						FROM user_web
						WHERE mail = '$mail_lista[$i]'
						LIMIT 1";
						$rs_max = mysql_fetch_assoc(mysql_query($query_max));
						
						for($c=0;$c<count($iduser_segmentacion);$c++){
						
							$query_ingresar_segmentacion = "INSERT INTO user_web_segmentacion (
							  iduser_web
							, iduser_segmentacion
							) VALUES (
							  '$rs_max[iduser_web]'
							, '$iduser_segmentacion[$c]'
							)";
							mysql_query($query_ingresar_segmentacion);
							
						}
						
					}
					
					$ingresado++;
					
				}else{ //Si existe agrego nueva segmentacion
					
					for($c=0;$c<count($iduser_segmentacion);$c++){
					
						$query_seg = "SELECT *
						FROM user_web_segmentacion 
						WHERE iduser_web = '$existe' AND iduser_segmentacion = '$iduser_segmentacion[$c]'";
						$cant_seg = mysql_num_rows(mysql_query($query_seg));
						
						if($cant_seg == 0){
							$query_ingresar_segmentacion = "INSERT INTO user_web_segmentacion (
							  iduser_web
							, iduser_segmentacion
							) VALUES (
							  '$existe'
							, '$iduser_segmentacion[$c]'
							)";
							mysql_query($query_ingresar_segmentacion);
						}
						
					}
					
					$ingresado_previo++;
				
				}
				
			}else{ //Sino es un mail valido: ERROR = Mail no valido.

				$no_valido++;
			
			}
			$i++;
		
		}
		
		if($i < $cant_mail){
			$hacer = true;
		}else{
			$hacer = false;
		}
		
		
		$log .= "<b>Importing from line ".$pos." to ".$i.".</b><br>
		<i>total successful imports: $ingresado<br>
		total duplicates: $ingresado_previo<br>
		total malformed email addresses: $no_valido<br></i>";
			
		$response->call("myJSFunction", $pos, $cada, array("log" => $log, "cant_mail" => $cant_mail, "hacer" => $hacer));
		return $response;
	}

	$xajax = new xajax();
	//$xajax->setFlag("debug", true);
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
	
		xajax.$('myDiv').innerHTML += myArrayArg["log"] + "\n<br>";
		if(myArrayArg.hacer == true){
			xajax_callScript(firstArg+numberArg);
		}else{
			document.frm.btn_importar.disabled = 'disabled';
			xajax.$('result').innerHTML = "Se han importado correctamente.";
		}
		
	}

</script>
<style type="text/css">
.div_import {
	height:300px;
	width:95%;
	overflow:auto; 
	background-color:#FBFBFB;
	padding: 15px;
	border:1px #666666 solid;
}

.text_import {
	font-family:Arial, Helvetica, sans-serif; 
	font-size:12px; 
	color:#666666;
}
</style>
</head>
<body>

<h2>
<form action="" name="frm" method="post">
  <input name="btn_importar" id="btn_importar" type="button" class="text_import"  value="Comenzar &raquo;" onclick="xajax_callScript(0)" />
</form>
</h2>
<div id="myDiv" class="div_import text_import" ><i>Haga click en el boton "Comenzar" para iniciar la importación.</i><br><br></div><br />
<div id="result" style="height:30px; width:100%; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666; " ></div>
</body>
</html>
