<?php

class obj0006{

	//Variables

	var $ver = '';
	var $ruta = '';
	var $src = '';
	var $texto = '';


	//Variables extras
	var $peso = 0;
	var $extension = 0;
	var $error = 0;
	var $forma = 0;
	var $ancho = 0;
	var $alto = 0;
	var $id = 0;

	
	//Variables pasadas al flash

	var $swf_var = 0;
	var $swf_parametro = 0;

	function obj0006(
	$ver
	,$ruta
	,$src
	,$texto
	,$swf_var
	,$swf_parametro
	){

	if($src){

		if (file_exists($ruta.$src)){//detecta si existe el archivo
		$peso = number_format((filesize($ruta.$src))/1024,2);//genera las caracteristicas del archivo, resultado(width, height, type, bits)

		if ($peso > 0){//detecta si esta mal subido al ftp, verifica si posee peso 0 (cero)
		$image_var = getimagesize($ruta.$src);//datos del type 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF' //hace un array que posee las caracteristicas de la imagen
		$width_sel = $image_var[0];//ancho real
		$height_sel = $image_var[1];//alto real
		//$extension = $image_var[2];//extension del archivo

		if(number_format($width_sel/$height_sel,2)<1){//verifica si es horizontal o vertical
			$forma =  2;//vertical
		}else{
			$forma =  1;//horizontal
		}


		$extension = strstr($src,".");
		if($extension == '.swf'){ // si es un SWF
		
			$param_swf = '';
			$var_swf = '';
			
			if( is_array($swf_parametro) == true){ //si los parametros vienen en array
				for($i=0; $i<count($swf_parametro); $i++){ //por cada parametro recibido
					$arr_param_swf = split("=", $swf_parametro[$i] );
					$param_swf .= "'". $arr_param_swf[0] . "','" . $arr_param_swf[1] . "',";
				};
			}else{
					$arr_param_swf = split("=", $swf_parametro );
					$param_swf .= "'". $arr_param_swf[0] . "','" . $arr_param_swf[1] . "',";

		};
			
			$id = "titulo".rand(0,9999);
						
			if( is_array($swf_var) == true){ //si las variables vienen en array
				
				$var_swf = "titulo=".$texto."&id=".$id;	
								
				for($i=0; $i<count($swf_var); $i++){ //por cada variable recibida
					$var_swf .= '&' . $swf_var[$i];
				};
				
			}else{
				$var_swf = "titulo=".$texto."&id=".$id."&".$swf_var;
			};
				
			?>
			<script language="javascript">
			function agrandar_<?=$id?>(alto){
				document.getElementById("<?=$id?>").height = alto;
			}
			/*
			function handleError() {
				return true;
			}
			window.onerror = handleError;
			*/
			
			AC_FL_RunContent( 
			'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0',
			'width','<?=$width_sel?>',
			'height','<?=$height_sel?>',
			'src','<?=substr(($ruta.$src),0,-4)?>',
			'quality','high',<?=$param_swf?>
			'FlashVars','<?=$var_swf?>',
			'pluginspage','http://www.macromedia.com/go/getflashplayer',
			'movie','<?=substr(($ruta.$src),0,-4)?>',
			'id','<?=$id?>'
			); </script> <?
			
		}else{
			$error = "001-3 - El archivo debe ser de extensión SWF (flash).";
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
		
		echo "<p>D: indica el valor por default</p><br><br>";
		echo "<p>1-[ruta] => ".$ruta." //ruta de archivo</p>";
		echo "<p>1-[src] => ".$src." //Nombre archivo</p>";
		echo "<p>8-[swf_var] => ".$swf_var." //variables para el swf (admite array) EJ: array('var1=valor1','var2=valor2')</p>";
		echo "<p>9-[swf_parametro] => ".$swf_parametro." //parametros del swf, java fondo, fondo transparente, etc (admite array) EJ: array('wmode=Opaque','otro=valor')</p>";
		echo "<br><br>Variables de retorno unicamente<br>
		<br>[peso] => ".$peso." //es el peso a descargar, RT, tendria que ser optativo el retorno, o bien,no tendria que estar para evitar sobrecarga de procesos
		<br><br>[forma] => ".$forma." //devuelve si es horizontal=1 o vertical=2			
		<br><br>[error] => ".$error." //mensaje de error, RT, tendria que ser optativo el retorno <br><br><br><br><p>Para llamar a este objeto hay que incorporar esta linea:</p>";
		echo "<p>  \$nombre_objeto_nuevo =& new obj0006_foto('ver','src','width_max','height','width_prop','height_prop','href','target','swf_var','swf_parametro','alt');</p>";
		echo "<br><br>";
		echo "<p>EJ:<br>  \$nombre_objeto_nuevo =& new obj0006('1','ruta/','test.swf','texto a mostrar en el titulo','variable=valor','wmode=opaque','');</p>";
		echo "<br><br>";
		echo "<p>///////////////////////////////////////////////</p><br><br><br><br>";
		}//fin funcion ver   

		//Propiedades devueltas al nuevo objeto
		$this->forma = $forma;
		$this->error = $error;
	

	}//fin funcion

};//fin objeto ?>