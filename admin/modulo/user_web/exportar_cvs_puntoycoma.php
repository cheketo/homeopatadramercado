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
		
		$query_id = "SELECT mail, nombre, apellido FROM user_web WHERE mail = '$rs_exportar[mail]'";
		$rs_iduser_web = mysql_fetch_assoc(mysql_query($query_id));
		
		echo "\"".$rs_iduser_web['mail']."\";";
		
		if($rs_iduser_web['nombre']){
			echo $rs_iduser_web['nombre']." ";
		}
		
		echo $rs_iduser_web['apellido']."\n";		
	};
	
?>