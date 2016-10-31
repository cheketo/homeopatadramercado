<?

	function ValidaMail($pMail) { 
		$valida=false;
		if (ereg("^[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+.)*[a-zA-Z0-9-]{2,200}.[a-zA-Z]{2,6}$", $pMail )){
			
			$cr=split("@",$pMail); 
			$dominio=$cr[1]; 
			$valida=true;
			/*$validar = @fsockopen($dominio, 80, $errno, $errstr, 5);
			
			if ($validar){
				$valida=true;
				fclose($validar);
			}*/
			
		} 
		return $valida;
	} 
	
	
	function ExisteUsuario($eMail){
	
		$query_max = "SELECT iduser_web
		FROM user_web
		WHERE mail = '$eMail'
		LIMIT 1";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		
		if($rs_max['iduser_web']){
			return $rs_max['iduser_web'];
		}else{
			return 0;
		}
		
	}

?>