<?

	require("../../js/xajax/xajax_core/xajax.inc.php");
	include("../../0_mysql.php");
	include("funciones_importar.php");

	function callScript($pos){
	
		//INICIO AJAX RESPONSE
		$response = new xajaxResponse();
		
		//VARIABLES
		$i = $pos;
		$cada = 1000;
		$log = "";
		$ingresado = 0;
		$no_ingresado = 0;
		$duplicado = 0;
		$fecha_actual = date("Y-m-d");
		$iduser_segmentacion = split("-",$_GET['segmentacion']);
		
		//VARIABLES PROVENIENTES DEL ARCHIVO
		$mail_lista = file('listado_newsletter.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$cant_mail = count($mail_lista);
		
		if($pos == 1){
			$log = "IMPORTANT: Keep this web browser window open during the import. If you close it, the import will be terminated.<br>About to import ".($cant_mail-1)." lines ...<br><br>";
			$pos++;
		}
		
		//REVISAR TANDA DE MAILS DEL ARRAY
		while($i < $pos+$cada && $i < $cant_mail){
					
			//VECTOR CON LOS NOMBRES DE LOS CAMPOS
			$vec_campo = str_replace(";",", ",trim($mail_lista[0]));
			
			//BUSCO QUE NUMERO DE CAMPO ES EL CAMPO MAIL
			$num_mail = 0;
			$array_field = split(", ", $vec_campo);
			for($c=0;$c<count($array_field);$c++){
				if($array_field[$c] == "mail"){
					$num_mail = $c;
					break;
				}
			}
			
			//VECTOR CON LOS DATOS DE LA FILA ACTUAL
			$vec_datos = str_replace(";","', '",str_replace("~"," ",trim($mail_lista[$i])));
			$array_datos = split("', '", $vec_datos);
			
			//Busco si es un mail valido.
			$valido = ValidaMail(trim($array_datos[$num_mail]));
			
			if($valido == true){
			
				//ME FIJO SI EXISTE UN MAIL IGUAL
				$query_existe = "SELECT iduser_web
				FROM user_web
				WHERE mail = '$array_datos[$num_mail]'
				LIMIT 1";
				$cant_existe = mysql_num_rows(mysql_query($query_existe));
				$rs_existe = mysql_fetch_assoc(mysql_query($query_existe));
				
				
				
				if($cant_existe == 0){ // SI NO EXISTE
				
					$query_ingresar = "INSERT INTO user_web ( ".$vec_campo." ) VALUES ( '".$vec_datos."' )";
					
					$res = mysql_query($query_ingresar);
					
					if($res == true){
					
						$query_max = "SELECT LAST_INSERT_ID() AS id";
						$rs_max = mysql_fetch_array(mysql_query($query_max));
						//$log .= "LAST ID: $rs_max[id]<br>";
						
						for($c=0;$c<count($iduser_segmentacion);$c++){
							
							$query_seg = "SELECT *
							FROM user_web_segmentacion 
							WHERE iduser_web = '$rs_max[id]' AND iduser_segmentacion = '$iduser_segmentacion[$c]'";
							$cant_seg = mysql_num_rows(mysql_query($query_seg));
		
							if($cant_seg == 0){
								$query_ingresar_segmentacion = "INSERT INTO user_web_segmentacion (
								  iduser_web
								, iduser_segmentacion
								) VALUES (
								  '$rs_max[id]'
								, '$iduser_segmentacion[$c]'
								)";
								mysql_query($query_ingresar_segmentacion);
							}
							
							
						}
						
						$ingresado++;
						
					}
					
				}else{
				
					for($c=0;$c<count($iduser_segmentacion);$c++){
						
						$query_seg = "SELECT *
						FROM user_web_segmentacion 
						WHERE iduser_web = '$rs_existe[iduser_web]' AND iduser_segmentacion = '$iduser_segmentacion[$c]'";
						$cant_seg = mysql_num_rows(mysql_query($query_seg));
						
						if($cant_seg == 0){
							$query_ingresar_segmentacion = "INSERT INTO user_web_segmentacion (
							  iduser_web
							, iduser_segmentacion
							) VALUES (
							  '$rs_existe[iduser_web]'
							, '$iduser_segmentacion[$c]'
							)";
							mysql_query($query_ingresar_segmentacion);
						}
						
					}
					
					$duplicado++;
				
				}//FIN EXISTE
				
			}else{
				$no_ingresado++;
			}
			
			$i++;
		}
		
		if($i < $cant_mail){
			$hacer = true;
		}else{
			$hacer = false;
		}
		
		
		$log .= "<b>Importing from line ".($pos-1)." to ".($i-1).".</b><br>
		<i>total successful imports: $ingresado<br>
		total duplicate imports: $duplicado<br>
		total not successful imports: $no_ingresado<br></i>";
			
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
  <input name="btn_importar" id="btn_importar" type="button" class="text_import"  value="Comenzar &raquo;" onclick="xajax_callScript(1)" />
</form>
</h2>
<div id="myDiv" class="div_import text_import" ><i>Haga click en el boton "Comenzar" para iniciar la importación.</i><br><br></div><br />
<div id="result" style="height:30px; width:100%; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666; " ></div>
</body>
</html>
