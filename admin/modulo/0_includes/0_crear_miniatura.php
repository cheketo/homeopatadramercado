<?	// FUNCION CREAR MINIATURA
	
	function crear_miniatura($imagen, $ancho_max, $alto_max, $subcarpeta){
	
		$temp = explode('/', $imagen);
		$archivo = $temp[count($temp)-1];
		$temp = explode('.', $archivo);
		$extension = $temp[1];
		$carpeta = substr($imagen,0,-(strlen($archivo)));
			
		switch ($extension){
		
			case 'gif':
				$original = imagecreatefromgif($imagen);
				break;
			case 'jpg':	
			case 'jpeg':
				$original = imagecreatefromjpeg($imagen);
				break;
			case 'png':
				$original = imagecreatefrompng($imagen);
				break;
			default:
				return false;
		};
		
		$ancho_original = imagesx($original);
		$alto_original = imagesy($original);
		
		$ancho_miniatura = $ancho_max;
		$alto_miniatura = $alto_max;
	
		$thumb = imagecreatetruecolor($ancho_miniatura,$alto_miniatura);
		imagecopyresampled($thumb,$original,0,0,0,0,$ancho_miniatura,$alto_miniatura,$ancho_original,$alto_original);
			
		$destino = $carpeta;
		
		if (!file_exists($destino)){
			mkdir($destino,0777);
		};
		
		if($subcarpeta){
			$destino = $subcarpeta;
			if (!file_exists($destino)){
				mkdir($destino,0777);
			};
		};
		
		$destino .= "/".$archivo;
		
		switch ($extension){
	
			case 'gif':
				imagegif($thumb, $destino, 90);
				break;
			
			case 'jpg':
			case 'jpeg':
				imagejpeg($thumb, $destino, 90);
				break;
				
			case 'png':
				imagepng($thumb, $destino, 90);
				break;
		};
	};
	// FIN DE LA FUNCION CREAR MINIATURA
	?>