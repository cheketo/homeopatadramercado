<?
	function clean_string($cadena){ 
	
		$cadena = ereg_replace('�', 'a', $cadena);
		$cadena = ereg_replace('�', 'A', $cadena);
		
		$cadena = ereg_replace('�', 'e', $cadena);
		$cadena = ereg_replace('�', 'E', $cadena);
		
		$cadena = ereg_replace('�', 'i', $cadena);
		$cadena = ereg_replace('�', 'I', $cadena);
		
		$cadena = ereg_replace('�', 'o', $cadena);
		$cadena = ereg_replace('�', 'O', $cadena);
		
		$cadena = ereg_replace('�', 'u', $cadena);
		$cadena = ereg_replace('�', 'U', $cadena);
		
		$cadena = ereg_replace('�', 'n', $cadena);
		$cadena = ereg_replace('�', 'N', $cadena);
		
		$cadena = ereg_replace(' ', '-', $cadena);
		$cadena = ereg_replace('_', '-', $cadena);
		
		$cadena = ereg_replace('[^A-Za-z0-9]', '-', $cadena);
		# La funci�n ereg_replace reemplaza todos lo que no sea n�meros o letras
		$cadena = strtolower($cadena);
		# strtolower transforma todo en min�sculas
		return $cadena;
		
	};
	
?>