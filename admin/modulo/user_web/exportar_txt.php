<? 
	include ("../../0_mysql.php"); 
	
	// Consultamos los datos desde MySQL
	$query = $_POST['query_exportar'];
	$resEmp = mysql_query($query);
	$numberfields = mysql_num_fields($resEmp);

	for ($i=0; $i<$numberfields ; $i++ ) {
	
		if($i == 0){
	   		$var = mysql_field_name($resEmp, $i);
	   		$row_title .= $var;
	   	}else{
	   		$var = mysql_field_name($resEmp, $i);
	   		$row_title .= ";".$var;
	  	}
	   
	}

	echo $row_title."<br>\n"; 
	
	// Creamos el array con los datos
	while($datatmp = mysql_fetch_array($resEmp)){ 
	
		for($i=0;$i<$numberfields;$i++){
		
			if($i == 0){
				$var = str_replace(chr(13),"",trim($datatmp[$i]));
				$var = str_replace(" ","~",$var);
				$row_dato = $var;
		   	}else{
				$var = str_replace(chr(13),"",trim($datatmp[$i]));
				$var = str_replace(" ","~",$var);
				$row_dato .= ";".$var;
		   	}
		   
		}
		
		echo $row_dato."<br>\n";
	}
	

?>
<script language="javascript">
function guardar(){
	document.execCommand('SaveAs',null,'usuarios_web.txt');
	this.close();
}
guardar();
</script>