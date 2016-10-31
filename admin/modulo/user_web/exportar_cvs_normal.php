<?
	include("../../0_mysql.php");
	
	$fp = fopen('file.csv', 'w+');

	//$query = $_POST['query_exportar_cvs'];
	$query = "SELECT DISTINCT A.mail, A.iduser_web
			FROM user_web A
			INNER JOIN user_web_segmentacion B ON B.iduser_web = A.iduser_web
			WHERE B.iduser_segmentacion = '152' AND ( A.sexo = 'M'  OR A.sexo = 'F'  OR A.sexo = 'N' )  AND ( A.estado = '1'  OR A.estado = '3' )  AND ( A.ididioma = '1'  OR A.ididioma = '2' )  AND ( A.idsede = '1'  OR A.idsede = '2' )  AND ( A.iduser_web_perfil = '1'  OR A.iduser_web_perfil = '2'  OR A.iduser_web_perfil = '3' )  
			ORDER BY A.apellido ASC
			LIMIT 5000";
	$result_exportar =  mysql_query($query);
	$num_exportar = mysql_num_rows(mysql_query($query));
	
	while($rs_exportar = mysql_fetch_assoc($result_exportar)){

		$iduser_segmentacion_exportar = '-';
		
		$query_segmentacion = "SELECT iduser_segmentacion
		FROM user_web_segmentacion 
		WHERE iduser_web = '$rs_exportar[iduser_web]'";
		$result_segmentacion = mysql_query($query_segmentacion);
		while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){
			$iduser_segmentacion_exportar .= $rs_segmentacion['iduser_segmentacion']."-";
		}
	
		$line  = trim($rs_exportar['mail']).";";
		$line .= $iduser_segmentacion_exportar.";";
		$line .= "\n";
		fputs($fp, $line);
		
	}
	
	fclose($fp);
	
	
	// Consultamos los datos desde MySQL
	/*$query = $_POST['query_exportar_cvs'];
	$result_exportar = mysql_query($query);
	$num_lista = mysql_num_rows(mysql_query($query));
	
	while ($rs_exportar = mysql_fetch_assoc($result_exportar)){ 
		
		$iduser_segmentacion_exportar = '-';
		
		$query_id = "SELECT iduser_web FROM user_web WHERE mail = '$rs_exportar[mail]'";
		$rs_iduser_web = mysql_fetch_assoc(mysql_query($query_id));
		
		$query_segmentacion = "SELECT iduser_segmentacion
		FROM user_web_segmentacion 
		WHERE iduser_web = '$rs_iduser_web[iduser_web]'";
		$result_segmentacion = mysql_query($query_segmentacion);
		while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){
			$iduser_segmentacion_exportar .= $rs_segmentacion['iduser_segmentacion']."-";
		}
		
		echo $rs_exportar['mail']." , ".$iduser_segmentacion_exportar."\n";		
	};*/
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>call Script Test | xajax Tests</title>

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
<body>
<a href="file.cvs" target="_blank">Descargar</a>
</body>
</html>