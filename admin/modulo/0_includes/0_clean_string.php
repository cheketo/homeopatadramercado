<?
	function clean_string($cadena){ 
	
		$cadena = ereg_replace('á', 'a', $cadena);
		$cadena = ereg_replace('Á', 'A', $cadena);
		
		$cadena = ereg_replace('é', 'e', $cadena);
		$cadena = ereg_replace('É', 'E', $cadena);
		
		$cadena = ereg_replace('í', 'i', $cadena);
		$cadena = ereg_replace('Í', 'I', $cadena);
		
		$cadena = ereg_replace('ó', 'o', $cadena);
		$cadena = ereg_replace('Ó', 'O', $cadena);
		
		$cadena = ereg_replace('ú', 'u', $cadena);
		$cadena = ereg_replace('Ú', 'U', $cadena);
		
		$cadena = ereg_replace('ñ', 'n', $cadena);
		$cadena = ereg_replace('Ñ', 'N', $cadena);
		
		$cadena = ereg_replace(' ', '-', $cadena);
		$cadena = ereg_replace('_', '-', $cadena);
		
		$cadena = ereg_replace('[^A-Za-z0-9]', '-', $cadena);
		# La función ereg_replace reemplaza todos lo que no sea números o letras
		$cadena = strtolower($cadena);
		# strtolower transforma todo en minúsculas
		return $cadena;
		
	};
	
?>