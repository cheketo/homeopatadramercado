<?
	include ("../../0_mysql.php"); 
	header("Content-Description: File Transfer"); 
	header("Content-Type: application/force-download"); 
	header("Content-Disposition: attachment; filename=usuarios_web.csv"); 
	
	
	// Consultamos los datos desde MySQL
	$query = $_POST['query_exportar_cvs'];
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
	};
	
?>