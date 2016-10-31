<?

	include ("../../0_mysql.php"); 
	header("Content-Description: File Transfer"); 
	header("Content-Type: application/force-download"); 
	header("Content-Disposition: attachment; filename=usuarios_web.xls"); 
	
	
	// Consultamos los datos desde MySQL
	$query = $_POST['query_exportar'];
	$resEmp = mysql_query($query);
	$numberfields = mysql_num_fields($resEmp);

	$shtml='<table border="1" bordercolor="#666666">'; 
	$shtml .= "<tr>";
	
	for ($i=0; $i<$numberfields ; $i++ ) {
		$shtml=$shtml."<td>".mysql_field_name($resEmp, $i)."</td>";
	}
	
	$shtml .= "</tr>"; 
	
	
	// Creamos el array con los datos
	while($datatmp = mysql_fetch_array($resEmp)){ 
	
		$shtml .= "<tr>";
	
		for($i=0;$i<$numberfields;$i++){
			$shtml .= "<td>".trim($datatmp[$i])."</td>";
		}
		
		$shtml .= "</tr>"; 
		
	}
	
	$shtml .= "</table>"; 
	
	echo $shtml;

	

?>