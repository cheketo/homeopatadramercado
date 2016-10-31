<?php

class obj0001{

	//Variables

	var $ver = 0;
	var $ruta = 0;
	var $src = 0;
	var $width_max = 0;
	var $height_max = 0;
	var $href = 0;
	var $target = 0;
	var $alt = 0;


	//Variables extras
	var $width_prop = 0;
	var $height_prop = 0;
	var $peso = 0;
	var $extension = 0;
	var $error = 0;
	var $forma = 0;
	var $ancho = 0;
	var $alto = 0;

	
	//Variables pasadas al flash

	var $swf_var = 0;
	var $swf_parametro = 0;

	function obj0001(
	$ver
	,$ruta
	,$src
	,$width_max
	,$height_max
	,$width_prop
	,$height_prop
	,$href
	,$target
	,$swf_var
	,$swf_parametro
	,$alt
	){

	if($src){

		if (file_exists($ruta.$src)){//detecta si existe el archivo
		$peso = number_format((filesize($ruta.$src))/1024,2);//genera las caracteristicas del archivo, resultado(width, height, type, bits)

		if ($peso > 0){//detecta si esta mal subido al ftp, verifica si posee peso 0 (cero)
		$image_var = getimagesize($ruta.$src);//datos del type 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF' //hace un array que posee las caracteristicas de la imagen
		$width_real = $image_var[0];//ancho real
		$height_real = $image_var[1];//alto real
		$width_sel = $width_real;
		$height_sel = $height_real;
		$extension = $image_var[2];//extension del archivo

		if(number_format($width_real/$height_real,2)<1){//verifica si es horizontal o vertical
			$forma =  2;//vertical
		}else{
			$forma =  1;//horizontal
		}

		//TAMAÑO FIJO PROPORCIONAL
			if($width_prop){
				$width_sel = $width_prop;
				$height_sel = ceil( $width_prop * $height_real / $width_real );
				
				if($height_prop){
					if($height_prop < $height_sel){
						$height_sel = $height_prop;
						$width_sel = ceil( $height_prop * $width_real / $height_real );
					}
				}

			}else{ 

				if($height_prop){
					$height_sel = $height_prop;
					$width_sel = ceil( $height_prop * $width_real / $height_real );
				}

			}
			
		//TAMAÑO MAXIMO
			if($width_max && $width_max < $width_sel){ //si se especifico un ancho máximo:
				$height_sel = ceil($height_sel * $width_max / $width_sel);
				$width_sel = $width_max;
			}

			if($height_max && $height_max < $height_sel){ //si se especifico un alto máximo:
				$width_sel = ceil($width_sel * $height_max / $height_sel);
				$height_sel = $height_max;
			}



		switch($extension){ //identifica extension del archivo y reacciona en base a la misma

		case 1:  // GIF
			$extension = ".gif";

			if($href){ //detecta si posee link
			$href_sel = "<a href=\"".$href."\" target=\"".$target."\" >";
			$href_sel_close = "</a>";
			};

			if($alt){
				$alt_sel = $alt;
			}else{
				$alt_sel = "";
			}

			echo $href_sel;
			echo "<img
					src=\"".$ruta.$src."\"
					width=\"".$width_sel."\"
					height=\"".$height_sel."\"
					border=\"0\"
					alt=\"".$alt_sel."\"
					>";
			echo $href_sel_close;
			break;
			
        case 2:  // JPG
			$extension = ".jpg";
			
			if($href){ //detecta si posee link
			$href_sel = "<a href=\"".$href."\" target=\"".$target."\" >";
			$href_sel_close = "</a>";
			};
			
			if($alt){
				$alt_sel = $alt;
			}else{
				$alt_sel = "";
			}
			
			echo $href_sel;
			echo "<img

				src=\"".$ruta.$src."\"
					width=\"".$width_sel."\"
					height=\"".$height_sel."\"

				border=\"0\"
					alt=\"".$alt_sel."\"
					>";
			echo $href_sel_close;
		

	break;
		
        case 3:  // PNG
			$extension = ".png";
			
			if($href){ //detecta si posee link
			$href_sel = "<a href=\"".$href."\" target=\"".$target."\" >";

		$href_sel_close = "</a>";
			};
			
			if($alt){
				$alt_sel = $alt;
			}else{
				$alt_sel = "";
			}

		
			echo $href_sel;
			echo "<img
					src=\"".$ruta.$src."\"
					width=\"".$width_sel."\"
					height=\"".$height_sel."\"
					border=\"0\"
					alt=\"".$alt_sel."\"
					>";
			echo $href_sel_close;
		

	break;
		

	default:
			$extension = strstr($src,".");
			if($extension == '.swf'){ // si es un SWF
			
				//$param_swf = '';
				$var_swf = '';
				
				/*
				if( is_array($swf_parametro) == true){ //si los parametros vienen en array
					for($i=0; $i<count($swf_parametro); $i++){ //por cada parametro recibido
						$arr_param_swf = split("=", $swf_parametro[$i] );
						$param_swf .= "'". $arr_param_swf[0] . "','" . $arr_param_swf[1] . "',";
					};
				}else{
						$arr_param_swf = split("=", $swf_parametro );
						$param_swf .= "'". $arr_param_swf[0] . "','" . $arr_param_swf[1] . "',";

				};
				*/
				
				if( is_array($swf_var) == true){ //si las variables vienen en array
					for($i=0; $i<count($swf_var); $i++){ //por cada variable recibida
						if($i==0){ // si es la primera variable
							$var_swf = $swf_var[$i];
						}else{
							$var_swf .= '&' . $swf_var[$i];
						};
					};
				}else{
					$var_swf = $swf_var;
				};
				?>
				
				<? /*
				<script language="javascript">
				AC_FL_RunContent( 
				'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0',
				'width','<?=$width_sel?>',
				'height','<?=$height_sel?>',
				'src','<?=substr(($ruta.$src),0,-4)?>',
				'quality','high',<?=$param_swf?>
				'FlashVars','<?=$var_swf?>',
				'pluginspage','http://www.macromedia.com/go/getflashplayer',
				'movie','<?=substr(($ruta.$src),0,-4)?>'
				); </script> 
				
				*/
				
				?>
				
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" 
				width="<?=$width_sel?>" 
				height="<?=$height_sel?>">
					<param name="movie" value="<?= $ruta.$src ?>" />
					<param name="quality" value="high" />
					<param name="FlashVars" value="<?=$var_swf?>" />					
					 <param name="wmode" value="<?=$swf_parametro?>" />						
					<embed src="<?= $ruta.$src ?>" quality="high" 
					pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" 
					width="<?=$width_sel?>" height="<?=$height_sel?>" FlashVars="<?=$var_swf?>" wmode="<?=$swf_parametro?>"></embed>
				</object>

				<?
				
			}else{
				$error = "001-3 - El archivo no es una imagen/swf o posee un formato no valido.";
				$error_ver = "<script>alert('".$error."')</script>";
				echo $error_ver;
			};

		break;
				
		}; //fin del switch

		
		}else{//si esta mal subido al ftp o pesa 0
			$error = "001-2 - El archivo ".$src." fue subido incorrectamente, posee un peso de ".$fsize;
			$error_ver = "<script>alert('".$error."')</script>";
			$width_sel = 0;
			$height_sel = 0;
			//echo $error_ver;
		}//fin funcion de deteccion de peso 0 o mal subido al ftp
		}else{//si no existe el archivo
			$error = "001-1 - El archivo ".$src." no existe";
			$error_ver = "<script>alert('".$error."')</script>";
			$width_sel = 0;
			$height_sel = 0;
			//echo $error_ver;
		}//fin funcion que detecta si existe el archivo
			}//si no existe el archivo en la base de datos


		if($ver==1){//es la funcion ver, que permite ver todas las funciones usadas
		echo "<p>///////////////////////////////////////////////<p>";
		echo "<p>Variables del objeto mod1_foto</p>";
		echo "<p>///////////////////////////////////////////////<p>";
		
		echo "IMPORTANTE:<br>";
		echo "Este objeto requiere del archivo '0_run_flash.js' para un correcto funcionamiento.<br>El mismo permite la utilización de SWF sin el marco molesto.<br>";
		
		echo "<p>RT: indica si la variable posee retorno</p>";
		
		echo "<p>D: indica el valor por default</p><br><br>";
		echo "<p>1-[ruta] => ".$ruta." //ruta de archivo</p>";
		echo "<p>1-[src] => ".$src." //Nombre archivo</p>";
		echo "<p>2-[width_max] => ".$width." //Ancho máximo</p>";
		echo "<p>3-[height_max] => ".$height_max." //Alto máximo</p>";
		echo "<p>4-[width_prop] => ".$width_prop." //Ancho proporcional</p>";
		echo "<p>5-[height_prop] => ".$height_prop." //Alto proporcional</p>";
		echo "<p>6-[href] => ".$href." //Ruta del link</p>";
		echo "<p>7-[target] => ".$target." //Target del link: _blank, _parent, _self&nbsp;&nbsp;&nbsp;D: _parent</p>";
		echo "<p>8-[swf_var] => ".$swf_var." //variables para el swf (admite array) EJ: array('var1=valor1','var2=valor2')</p>";
		echo "<p>9-[swf_parametro] => ".$swf_parametro." //parametros del swf, java fondo, fondo transparente, etc (admite array) EJ: array('wmode=Opaque','otro=valor')</p>";
		echo "<p>10-[alt] => ".$alt_sel." //alt es la etiqueta de la imagen, D: nombre del archivo sin su extension</p>";
		echo "<br><br>Variables de retorno unicamente<br>
		<br>[peso] => ".$peso." //es el peso a descargar, RT, tendria que ser optativo el retorno, o bien,no tendria que estar para evitar sobrecarga de procesos
		<br><br>[forma] => ".$forma." //devuelve si es horizontal=1 o vertical=2			
		<br><br>[width] => ".$width_real." //width final con el que aparece el archivo			
		<br><br>[height] => ".$height_real." //height final con el que aparece el archivo	      			
		<br><br>[extension] => ".$extension." //datos del type 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', no posee retorno para no sobrecargar			
		<br><br>[error] => ".$error." //mensaje de error, RT, tendria que ser optativo el retorno <br><br><br><br><p>Para llamar a este objeto hay que incorporar esta linea:</p>";
		echo "<p>  \$nombre_objeto_nuevo =& new obj0001_foto('ver','src','width_max','height','width_prop','height_prop','href','target','swf_var','swf_parametro','alt');</p>";
		echo "<br><br>";
		echo "<p>EJ JPG:<br>  \$nombre_objeto_nuevo =& new obj0001('1','ruta/imagen/','0.jpg','100','100','','','link.html','_blank','','','');</p>";
		echo "<p>EJ SWF:<br>  \$nombre_objeto_nuevo =& new obj0001('1','ruta/','test.swf','100','100','','','','','variable=valor','wmode=opaque','');</p>";
		echo "<br><br>";
		echo "<p>///////////////////////////////////////////////</p><br><br><br><br>";
		}//fin funcion ver   

		//Propiedades devueltas al nuevo objeto
		$this->forma = $forma;
		$this->error = $error;
		$this->ancho = $width_sel;
		$this->alto = $height_sel;
		$this->extension = $extension;
	

	}//fin funcion

};//fin objeto ?>