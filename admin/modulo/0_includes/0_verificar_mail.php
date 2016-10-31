<?
	function verificar_mail($email){
		$valida=false;
		if (ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$", $email )){
			$cr=split("@",$email); 
			$dominio=$cr[1]; 
			$validar = @fsockopen($dominio, 80, $errno, $errstr, 5);
			if ($validar){
				$valida=true;
				fclose($validar);
			}else{
				echo "<script>alert('".$errstr."');</script>";
			}
		}
		return $valida;
	}
?>