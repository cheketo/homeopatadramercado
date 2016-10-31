<?php

class obj0007{

	//Variables

	var $ver = '';
	var $ruta = '';
	var $src = '';
	var $ancho = 0;
	var $alto = 0;
	var $efecto = 0;


	//Variables extras
	var $peso = 0;
	var $extension = 0;
	var $error = 0;
	var $forma = 0;
	var $id = 0;
	var $link = '';
	var $swf = '';

	
	//Variables pasadas al flash

	var $swf_var = 0;
	var $swf_parametro = 0;

	function obj0007(
	$ver
	,$ruta
	,$src
	,$link
	,$efecto
	,$swf
	
	){

	if($src){

		if (file_exists($ruta.$src)){//detecta si existe el archivo
		$peso = number_format((filesize($ruta.$src))/1024,2);//genera las caracteristicas del archivo, resultado(width, height, type, bits)

		if ($peso > 0){//detecta si esta mal subido al ftp, verifica si posee peso 0 (cero)
		$image_var = getimagesize($ruta.$src);//datos del type 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF' //hace un array que posee las caracteristicas de la imagen
		$width_img = $image_var[0];//ancho real
		$height_img = $image_var[1];//alto real
		
		$image_var_swf = getimagesize($swf);//datos del type 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF' //hace un array que posee las caracteristicas de la imagen
		$width_sel = $image_var_swf[0];//ancho real
		$height_sel = $image_var_swf[1];//alto real
		/*		
		$width_sel = 20;//ancho real
		$height_sel = 20;//alto real
		*/

		if(number_format($width_sel/$height_sel,2)<1){//verifica si es horizontal o vertical
			$forma =  2;//vertical
		}else{
			$forma =  1;//horizontal
		}

		$extension = strstr($src,".");
		
		if($extension == '.swf' || $extension == '.jpg'|| $extension == '.jpeg' || $extension == '.gif'|| $extension == '.png'|| $extension == '.bmp'){ // si es un SWF
				
			$id = "fotoin".rand(0,9999);
			$id = "fotoin";
			
			$var_swf = "id=".$id."&efecto=".$efecto."&ruta=".$ruta."&src=".$src."&ancho_nuevo=".$width_img."&alto_nuevo=".$height_img."&link=".$link;

			?>
			<script language="javascript">
			/*
			function handleError() {
				return true;
			}
			window.onerror = handleError;
			*/
			AC_FL_RunContent( 
			'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0',
			'width','<?=$width_sel?>',
			'height','<?=$height_img?>',
			'src','<?=substr(($swf),0,-4)?>',
			'quality','high',
			'wmode','opaque',
			'FlashVars','<?=$var_swf?>',
			'pluginspage','http://www.macromedia.com/go/getflashplayer',
			'movie','<?=substr(($swf),0,-4)?>',
			'id','<?=$id?>'
			); </script> <?
			
		}else{
			$error = "001-3 - El archivo no corresponde a un tipo de imagen compatible (jpg, gif, bmp, swf, png).";
			$error_ver = "<script>alert('".$error."')</script>";
			echo $error_ver;
		};

		
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
		echo "<p>Variables del objeto titulo</p>";
		echo "<p>///////////////////////////////////////////////<p>";
		
		echo "IMPORTANTE:<br>";
		echo "Este objeto requiere del archivo '0_run_flash.js' para un correcto funcionamiento.<br>El mismo permite la utilización de SWF sin el marco molesto.<br>";
		
		echo "<p>RT: indica si la variable posee retorno</p>";
		echo "<p>1-[ver] => ".$ver." //abre esta ayuda</p>";
		echo "<p>2-[ruta] => ".$ruta." //ruta de la foto</p>";
		echo "<p>3-[src] => ".$src." //nombre del archivo foto</p>";
		echo "<p>4-[link] => ".$link." //link que se abrira al hacer click</p>";
		echo "<p>5-[efecto] => ".$efecto." //el efecto de rollover. Del 1 al 4 (0 escoje uno al azar). Pueden crearse mas editando el fla del fotoin.</p>";
		echo "<p>6-[swf] => ".$swf." //el nombre del swf que utiliza el objeto (por defecto fotoin.swf, en la carpeta objeto/fotoin)";
		echo "<br><br><br>ejemplo:<br><br>\$fotoin &= new obj0007('1','carpeta_foto/','la_foto.jpg','el_link.php','3','objeto/fotoin/fotoin.swf');";
		
		}; //fin if ver
	
	}//fin funcion

};//fin objeto ?>