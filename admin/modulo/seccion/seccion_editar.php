<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	include ("../0_includes/0_crear_miniatura.php"); 
	include ("../0_includes/0_clean_string.php"); 
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede_a = " AND idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
		$filtro_sede_a = '';
	}
	
	//CARGO PARÁMETROS DE SECCION
	$query_par = "SELECT *
	FROM seccion_parametro
	WHERE idseccion_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));

	//VARIABLES
	$idseccion = $_GET['idseccion'];
	$idcarpeta = $_GET['idcarpeta'];
	$forma = $_GET['forma'];
	$accion = $_POST['accion'];
	
	//CAMBIAR ESTADO
	$estado_idioma = $_POST['estado_idioma'];
	$ididioma = $_POST['ididioma'];
	
	$dia_fecha_alta = $_POST['dia_fecha_alta'];
	$mes_fecha_alta = $_POST['mes_fecha_alta'];
	$ano_fecha_alta = $_POST['ano_fecha_alta'];
	
	$dia_fecha_baja = $_POST['dia_fecha_baja'];
	$mes_fecha_baja = $_POST['mes_fecha_baja'];
	$ano_fecha_baja = $_POST['ano_fecha_baja'];
	
	$fecha_alta = $ano_fecha_alta."-".$mes_fecha_alta."-".$dia_fecha_alta;
	$fecha_baja = $ano_fecha_baja."-".$mes_fecha_baja."-".$dia_fecha_baja;
	
	$precio = $_POST['precio'];
	$esnuevo = $_POST['esnuevo'];
	$destacado = $_POST['destacado'];

	
	//Variables de la foto	
	$foto = $_POST['foto'];// es la imagen a ingresar
	$foto_chica_ancho = $rs_parametro['foto_chica'];// ancho maximo de la foto en tamaño chica	
	$foto_mediana_ancho = $rs_parametro['foto_mediana'];// ancho maximo de la foto en tamaño mediana
	$foto_grande_ancho = $rs_parametro['foto_grande'];// ancho maximo de la foto en tamaño grande
	$foto_ruta_chica = "../../../imagen/seccion/chica/"; // la ruta donde se va a guardar la foto chica
	$foto_ruta_mediana = "../../../imagen/seccion/mediana/"; // la ruta donde se va a guardar la foto mediana
	$foto_ruta_grande = "../../../imagen/seccion/grande/"; // la ruta donde se va a guardar la foto grande
	$ruta_descarga = "../../../descarga/";	
	
	//Variables del selector de carpeta
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];

	//Variables de la fotos extra	
	$foto_extra_chica_ancho = $rs_parametro['foto_extra_chica'];// ancho maximo de la foto extra en tamaño chica	
	$foto_extra_grande_ancho = $rs_parametro['foto_extra_grande'];// ancho maximo de la foto extra en tamaño grande	
	$foto_extra_ruta_chica = "../../../imagen/seccion/extra_chica/"; // la ruta donde se va a guardar la foto extra chica
	$foto_extra_ruta_grande = "../../../imagen/seccion/extra_grande/"; // la ruta donde se va a guardar la foto extra grande
	$foto_extra = $_POST['foto_extra'];// es la imagen a ingresar
	$foto_extra_titulo = $_POST['foto_extra_titulo']; // titulo de la foto extra
	$foto_extra_tipo = $_POST['foto_extra_tipo']; //para determinar la posicion 1 horizontal, 2 vertical, 3 dentro del detalle
	$foto_extra_ididioma = $_POST['foto_extra_ididioma']; // idioma en el que va a aparecer la imagen extra
	//$foto_extra_posicion = $_POST['foto_extra_posicion']; // posicion de la foto extra que se esta cargando
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$foto_extra_orden = $_POST['foto_extra_orden'];
	
	
	//Cambia el tipo de foto extra
	$idseccion_foto_sel = $_GET['idseccion_foto_sel'];
	$foto_extra_tipo_sel = $_GET['foto_extra_tipo_sel'];
	
	//Reordena las fotos extras
	$foto_extra_orden_row = $_POST['foto_extra_orden_row'];
	$foto_extra_cont = $_POST['foto_extra_cont'];
	$idseccion_foto_row = $_POST['idseccion_foto_row'];
	$foto_extra_titulo_row = $_POST['foto_extra_titulo_row'];

	//Variables de la descargas
	$descarga_ruta = "../../../descarga/seccion/"; // la ruta donde se va a guardar la descarga
	$descarga_tipo = $_POST['descarga_tipo'];// el tipo de descarga que es -  catalogo, manual etc
	$descarga_titulo = $_POST['descarga_titulo']; // titulo de la descarga
	$descarga_archivo = $_POST['descarga_archivo']; // archivo que se selecciona para crear la descarga
	$descarga_archivo_txt = $_POST['descarga_archivo_txt']; // archivo que se selecciona para crear la descarga, para luego subirlo con ftp
	$eliminar = $_POST['eliminar'];//elimina la descarga

	//Variables de las palabras claves
	$keywords_titulo = $_POST['keywords_titulo'];
	$keywords_link = $_POST['keywords_link'];
	$keywords_tamano = $_POST['keywords_tamano'];
	$keywords_ididioma = $_POST['keywords_ididioma'];
	
	//Variables de comentarios
	$habilita_comentario = $_POST['habilita_comentario'];
	$mail_moderador = $_POST['mail_moderador'];
	
	//Variables de rating
	$usa_rating = $_POST['usa_rating'];
	
	//ACTUALIZO FECHA MODIFICACION
	if($accion != ""){
		$fecha_hoy = date("Y-m-d");
		$query_mod_seccion = "UPDATE seccion SET fecha_modificacion = '$fecha_hoy' WHERE idseccion = '$idseccion' ";
		mysql_query($query_mod_seccion);
	}
	
	//ACTUALIZO RATING
	if($accion == "rating"){
		
		$query_upd = "UPDATE seccion SET
		usa_rating = '$usa_rating'
		WHERE idseccion = '$idseccion' 
		LIMIT 1";
		mysql_query($query_upd);
	
	}
	
	//FORMATEO RATING
	if($accion == "formatear_rating"){
		
		$query_upd = "UPDATE seccion SET
		  puntaje_total = '0'
		, votos_total = '0'
		WHERE idseccion = '$idseccion' 
		LIMIT 1";
		mysql_query($query_upd);
		
		$query_del = "DELETE FROM seccion_voto 
		WHERE idseccion = '$idseccion' 
		LIMIT 1";
		mysql_query($query_del);
		
	}
	
	//MODIFICAR COMENTARIOS
	if($accion == "modificar_comentario"){
	
		$query_upd = "UPDATE seccion SET 
		  habilita_comentario = '$habilita_comentario' 
		, mail_moderador = '$mail_moderador'
		WHERE idseccion = '$idseccion'
		LIMIT 1";
		mysql_query($query_upd);
	}
	
	//MODIFICAR SECCION:
	if($accion == "modificar"){
			
			// INCORPORACION DE FOTO
			if ($_FILES['foto']['name'] != ''){
		
				$archivo_ext = substr($_FILES['foto']['name'],-4);
				$archivo_nombre = substr($_FILES['foto']['name'],0,strrpos($_FILES['foto']['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
				
					$querysel = "SELECT foto FROM seccion WHERE idseccion = '$idseccion' ";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
					
					if ( $rowfoto[0] ){
						if (file_exists($foto_ruta_chica.$rowfoto[0])){
							unlink ($foto_ruta_chica.$rowfoto[0]);
						}
						if (file_exists($foto_ruta_mediana.$rowfoto[0])){
							unlink ($foto_ruta_mediana.$rowfoto[0]);
						}
						if (file_exists($foto_ruta_grande.$rowfoto[0])){
							unlink ($foto_ruta_grande.$rowfoto[0]);
						}
					}
					
				$foto =  $idseccion . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
				
				if (!copy($_FILES['foto']['tmp_name'], $foto_ruta_grande . $foto)){ //si hay error en la copia de la foto
					$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
					echo "<script>alert('".$error."')</script>"; // se muestra el error.
				}else{
					$imagesize = getimagesize($foto_ruta_grande.$foto);
				
					if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
						$peso = number_format((filesize($foto_ruta_grande.$foto))/1024,2);
						
						if($peso==0){
							$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
							echo "<script>alert('".$error2."')</script>"; // se muestra el error.
						}else{
						
							//SI TODO SALIO BIEN:
							if($_POST['foto_grande_ancho']){
								$foto_grande_ancho_sel = $_POST['foto_grande_ancho'];
							}else{
								$foto_grande_ancho_sel = $foto_grande_ancho;
							}
							if($_POST['foto_mediana_ancho']){
								$foto_mediana_ancho_sel = $_POST['foto_mediana_ancho'];
							}else{
								$foto_mediana_ancho_sel = $foto_mediana_ancho;
							}
							if($_POST['foto_chica_ancho']){
								$foto_chica_ancho_sel = $_POST['foto_chica_ancho'];
							}else{
								$foto_chica_ancho_sel = $foto_chica_ancho;
							}
							
							//SI EL ARCHIVO NO ES UN SWF
							if( strtolower($archivo_ext) != ".swf" ){
							
								//ACHICAR IMAGEN AL ANCHO MÁXIMO de la foto grande:
								if ($imagesize[0] > $foto_grande_ancho_sel){
									$alto_nuevo = ceil($foto_grande_ancho_sel * $imagesize[1] / $imagesize[0]) ;
									crear_miniatura($foto_ruta_grande.$foto, $foto_grande_ancho_sel, $alto_nuevo, $foto_ruta_grande);
								};
							
							}
							
							//SI EL ARCHIVO NO ES UN SWF
							if( strtolower($archivo_ext) != ".swf" ){
							
								//ACHICAR IMAGEN AL ANCHO MÁXIMO de la foto mediana:		
								if ($imagesize[0] > $foto_mediana_ancho_sel){				
									$alto_nuevo = ceil($foto_mediana_ancho_sel * $imagesize[1] / $imagesize[0]) ;
									crear_miniatura($foto_ruta_grande.$foto, $foto_mediana_ancho_sel, $alto_nuevo, $foto_ruta_mediana);
								}else{
									crear_miniatura($foto_ruta_grande.$foto, $imagesize[0], $imagesize[1], $foto_ruta_mediana);
								};
							
							}else{
							
								//SI EL ARCHIVO ES UN SWF
								copy($_FILES['foto']['tmp_name'], $foto_ruta_mediana.$foto);
								
							};
							
							
							//SI EL ARCHIVO NO ES UN SWF
							if( strtolower($archivo_ext) != ".swf" ){	
								
								//CREAR MINI AL ANCHO MÁXIMO chico:
								if ($imagesize[0] > $foto_chica_ancho_sel){
									$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
									crear_miniatura($foto_ruta_mediana.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica);
								}else{
									crear_miniatura($foto_ruta_mediana.$foto, $imagesize[0], $imagesize[1], $foto_ruta_chica);
								};
								
							}else{
							
								//SI EL ARCHIVO ES UN SWF
								copy($_FILES['foto']['tmp_name'], $foto_ruta_chica.$foto);
								
							};
					
							//ingreso de foto en tabla producto
							$query_upd = "UPDATE seccion SET 
							foto = '$foto' 	
							WHERE idseccion = '$idseccion'
							LIMIT 1";
							mysql_query($query_upd);
		
						};			
					
					}else{
					
						$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
						echo "<script>alert('".$error3."')</script>"; // se muestra el error.
						
						if(!unlink($ruta_foto.$foto)){ //se elimina el archivo subido
							$error4 = "El archivo no pudo elminarse. ";
							echo "<script>alert('".$error4."')</script>"; // se muestra el error.
						}else{
							$error5 = "El archivo fue elminado. ";
							echo "<script>alert('".$error5."')</script>"; // se muestra el error.
						};
					
					};
			
				};
			} // FIN INCORPORACION DE FOTO
		
			// INCORPORACION DE BANNER
			if ($_FILES['foto_banner']['name'] != ''){
		
				$archivo_ext = substr($_FILES['foto_banner']['name'],-4);
				$archivo_nombre = substr($_FILES['foto_banner']['name'],0,strrpos($_FILES['foto_banner']['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
				
					$querysel = "SELECT banner FROM seccion WHERE idseccion = '$idseccion' ";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
					
					if ( $rowfoto[0] ){
						if (file_exists($banner_ruta.$rowfoto[0])){
							unlink ($banner_ruta.$rowfoto[0]);
						}
					}
	
					
				$foto =  $idseccion . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
				
				if (!copy($_FILES['foto_banner']['tmp_name'], $banner_ruta . $foto)){ //si hay error en la copia de la foto
					$error = "Hubo un error al subir el banner. "; // se crea la variable error con un mensaje de error.
					echo "<script>alert('".$error."')</script>"; // se muestra el error.
				}else{
					$imagesize = getimagesize($banner_ruta.$foto);
				
					if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
						$peso = number_format((filesize($banner_ruta.$foto))/1024,2);
						
						if($peso==0){
							$error2 = "El banner fue subido incorrectamente. "; //se crea la variable error con un mensaje de error.
							echo "<script>alert('".$error2."')</script>"; // se muestra el error.
						}else{
						
							//SI TODO SALIO BIEN:
							
							//ACHICAR IMAGEN AL ANCHO MÁXIMO:
							if ($imagesize[0] > $banner_ancho){
								$alto_nuevo = ceil($banner_ancho * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($banner_ruta.$foto, $banner_ancho, $alto_nuevo, $banner_ruta);
							};
					
							//ingreso de foto en tabla producto
							$query_upd = "UPDATE seccion SET 
							banner = '$foto' 	
							WHERE idseccion = '$idseccion'
							LIMIT 1";
							mysql_query($query_upd);
		
						};			
					
					}else{
					
						$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
						echo "<script>alert('".$error3."')</script>"; // se muestra el error.
						
						if(!unlink($ruta_foto.$foto)){ //se elimina el archivo subido
							$error4 = "El archivo no pudo elminarse. ";
							echo "<script>alert('".$error4."')</script>"; // se muestra el error.
						}else{
							$error5 = "El archivo fue elminado. ";
							echo "<script>alert('".$error5."')</script>"; // se muestra el error.
						};
					
					};
			
				};
			} // FIN INCORPORACION DE BANNER
			

			// INGRESO DE DATOS
			$query_modficacion = "UPDATE seccion SET 
			  fecha_alta='$fecha_alta'
			, fecha_baja='$fecha_baja'
			, precio = '$precio'
			, esnuevo = '$esnuevo'
			, destacado = '$destacado'
			WHERE idseccion = '$idseccion'
			LIMIT 1";
			
			mysql_query($query_modficacion);
			
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";		
	};
	
	//CAMBIAR ESTADO IDIOMA	
	if($estado_idioma != "" && $ididioma != ""){
		$query_estado = "UPDATE seccion_idioma_dato
		SET estado = '$estado_idioma'
		WHERE idseccion	= '$idseccion' AND ididioma	= '$ididioma'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	
	}
	
	$estado_descarga = $_POST['estado_descarga'];
	$estado_restringido = $_POST['estado_restringido'];
	$eliminar_iddescarga = $_POST['eliminar_iddescarga'];
	$iddescarga = $_POST['iddescarga'];
	
	//CAMBIAR ESTADO DESCARGA	
	if($estado_descarga != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET estado = '$estado_descarga'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."#descarga');</script>";
	
	}
	
	//CAMBIAR RESTRINGISDO DESCARGA	
	if($estado_restringido != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET restringido = '$estado_restringido'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."#descarga');</script>";
	
	}
	
	//ELIMINAR DESCARGA	
	if($eliminar_iddescarga != ""){
		$query_estado = "SELECT archivo
		FROM descarga
		WHERE iddescarga = '$eliminar_iddescarga'
		LIMIT 1";
		$rs_del = mysql_fetch_assoc(mysql_query($query_estado));
		
		if (file_exists($ruta_descarga.$rs_del['archivo'])){
			unlink ($ruta_descarga.$rs_del['archivo']);
		}
		
		$query = "DELETE FROM descarga WHERE iddescarga = '$eliminar_iddescarga' LIMIT 1 ";
		mysql_query($query);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."#descarga');</script>";
	
	}
	
	//MODIFICAR SEDE
	if($accion == "modificar_sede"){
		
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		$query_delete = "DELETE FROM seccion_sede 
		WHERE idseccion = '$idseccion' $filtro_sede_a";
		mysql_query($query_delete);
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO seccion_sede(
			  idseccion
			, idsede
			)VALUES(
			  '$idseccion'
			, '$sede[$c]'
			)";
			mysql_query($query_insert);
			//echo $sede[$c];
		}
	}
	
	//ELIMINAR COPIA EN CARPETA
	if($accion == "eliminar_copia_carpeta"){
		$idcarpeta_copia = $_POST['idcarpeta_copia'];
		$query_del = "DELETE FROM seccion_carpeta
		WHERE idcarpeta = '$idcarpeta_copia' AND idseccion = '$idseccion'";
		mysql_query($query_del);
	}
	
	//ELIMINAR FOTO
	if ($accion == "eliminar_foto"){
			
				$querysel = "SELECT foto FROM seccion WHERE idseccion = '$idseccion' ";
				$rowfoto = mysql_fetch_row(mysql_query($querysel));
				
				if ( $rowfoto[0] ){
					if (file_exists($foto_ruta_chica.$rowfoto[0])){
						unlink ($foto_ruta_chica.$rowfoto[0]);
					}
					if (file_exists($foto_ruta_mediana.$rowfoto[0])){
						unlink ($foto_ruta_mediana.$rowfoto[0]);
					}
					if (file_exists($foto_ruta_grande.$rowfoto[0])){
						unlink ($foto_ruta_grande.$rowfoto[0]);
					}
				}

				$query_upd = "UPDATE seccion SET
				foto = ''
				WHERE idseccion = '$idseccion'
				LIMIT 1";
				mysql_query($query_upd);
				
				echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	}

	//ELIMINAR FOTO EXTRA
	if ($accion == "eliminar_foto_extra"){
					
					$eliminar = $_POST['eliminar'];
					$querysel = "SELECT foto FROM seccion_foto WHERE idseccion_foto = '$eliminar'";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
									
					if ( $rowfoto[0] ){
						if (file_exists($foto_extra_ruta_grande.$rowfoto[0])){
							unlink ($foto_extra_ruta_grande.$rowfoto[0]);
						}
						if (file_exists($foto_extra_ruta_chica.$rowfoto[0])){
							unlink ($foto_extra_ruta_chica.$rowfoto[0]);
						}
					}
					
					$query_del = "DELETE FROM seccion_foto WHERE idseccion_foto = '$eliminar'";
					mysql_query($query_del);
					
					echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."#foto_extra');</script>";
	}

	//INGRESAR FOTO EXTRA
	if($accion == "ingresar_foto_extra"){
	
		// INCORPORACION DE FOTO		
		if ($_FILES['foto_extra']['name'] != ''){
		
			for($c=0;$c<$cantidad_idioma;$c++){
			
				//CONSULTA DE IDIOMA
				$query_idioma = "SELECT reconocimiento_idioma
				FROM idioma
				WHERE ididioma = '$foto_extra_ididioma[$c]'";
				$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
			
				$archivo_ext = substr($_FILES['foto_extra']['name'],-4);
				$archivo_nombre = substr($_FILES['foto_extra']['name'],0,strrpos($_FILES['foto_extra']['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
			
				$foto =  $idseccion .'-'.$rs_idioma['reconocimiento_idioma'].'-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
				
				if (!copy($_FILES['foto_extra']['tmp_name'], $foto_extra_ruta_grande . $foto)){ //si hay error en la copia de la foto
					$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
					echo "<script>alert('".$error."')</script>"; // se muestra el error.
				}else{
					$imagesize = getimagesize($foto_extra_ruta_grande.$foto);
				
					if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
						$peso = number_format((filesize($foto_extra_ruta_grande.$foto))/1024,2);
		
						if($peso==0){
							$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
							echo "<script>alert('".$error2."')</script>"; // se muestra el error.
						}else{
						
							//SI TODO SALIO BIEN:
							if($_POST['foto_extra_grande_ancho']){
								$foto_extra_grande_ancho_sel = $_POST['foto_extra_grande_ancho'];
							}else{
								$foto_extra_grande_ancho_sel = $foto_extra_grande_ancho;
							}
							if($_POST['foto_extra_chica_ancho']){
								$foto_extra_chica_ancho_sel = $_POST['foto_extra_chica_ancho'];
							}else{
								$foto_extra_chica_ancho_sel = $foto_extra_chica_ancho;
							}
		
							//ACHICAR IMAGEN AL ANCHO MÁXIMO:
							if ($imagesize[0] > $foto_extra_grande_ancho_sel){
								$alto_nuevo = ceil($foto_extra_grande_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_extra_ruta_grande.$foto, $foto_extra_grande_ancho_sel, $alto_nuevo, $foto_extra_ruta_grande);
							};
							
							
							//CREAR LA IMAGEN CHICA DE FOTO EXTRA Y LA GUARDA:
							if ($imagesize[0] > $foto_extra_chica_ancho_sel){
								$alto_nuevo = ceil($foto_extra_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_extra_ruta_grande.$foto, $foto_extra_chica_ancho_sel, $alto_nuevo, $foto_extra_ruta_chica);
							}else{
								crear_miniatura($foto_extra_ruta_grande.$foto, $imagesize[0], $imagesize[1], $foto_extra_ruta_chica);
							};	
							
							
							$query_ins = "INSERT INTO seccion_foto 
							(idseccion, foto, titulo,foto_extra_tipo, ididioma, orden) 
							VALUES 
							('$idseccion','$foto', '$foto_extra_titulo', '$foto_extra_tipo', '$foto_extra_ididioma[$c]', '$foto_extra_orden')";
							mysql_query($query_ins);
								
		
						};	
					
					}else{
					
						$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
						echo "<script>alert('".$error3."')</script>"; // se muestra el error.
						
						if(!unlink($ruta_foto_extra.$foto)){ //se elimina el archivo subido
							$error4 = "El archivo no pudo elminarse. ";
							echo "<script>alert('".$error4."')</script>"; // se muestra el error.
						}else{
							$error5 = "El archivo fue elminado. ";
							echo "<script>alert('".$error5."')</script>"; // se muestra el error.
						};
					
					};
			
				};
			}//IDIOMAS	
		} // FIN INCORPORACION DE FOTO
				
			$accion = "";
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
			
	};

	//CAMBIA TIPO DE FOTO EXTRA
	if ($idseccion_foto_sel && $foto_extra_tipo_sel){
			$query_mod9_upd = "UPDATE seccion_foto 
			SET foto_extra_tipo = '$foto_extra_tipo_sel'
			WHERE idseccion_foto = $idseccion_foto_sel
			";
			mysql_query($query_mod9_upd);
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."#foto_extra')</script>";
	}

	//REORDENA LAS FOTOS EXTRA
	if($accion == "foto_extra_reordenar"){	
	
		
		//ELIMINAR SELECCION DE FOTOS EXTRAS
		$checkbox_row = $_POST['checkbox_row'];
		
		for($i=0;$i<=$foto_extra_cont;$i++){	
		
			if($checkbox_row[$i] != ""){
				
				$querysel = "SELECT foto FROM seccion_foto WHERE idseccion_foto = '$checkbox_row[$i]'";
				$rowfoto = mysql_fetch_row(mysql_query($querysel));
								
				if ( $rowfoto[0] ){
					if (file_exists($foto_extra_ruta_grande.$rowfoto[0])){
						unlink ($foto_extra_ruta_grande.$rowfoto[0]);
					}
					if (file_exists($foto_extra_ruta_chica.$rowfoto[0])){
						unlink ($foto_extra_ruta_chica.$rowfoto[0]);
					}
				}
				
				$query_del = "DELETE FROM seccion_foto WHERE idseccion_foto = '$checkbox_row[$i]'";
				mysql_query($query_del);
			}
			
		}
	
		//ACTUALIZA VALOR DE COLUMNAS
		$foto_extra_columna = $_POST['foto_extra_columna'];
		
		$queryup = "UPDATE seccion
		SET foto_extra_columna ='$foto_extra_columna'
		WHERE idseccion = '$idseccion'";
		mysql_query($queryup);
			
		for ($i=0; $i< $foto_extra_cont+1 ; $i++){
			$queryup = "UPDATE seccion_foto
			SET orden = '$foto_extra_orden_row[$i]'
			, titulo = '$foto_extra_titulo_row[$i]'
			WHERE idseccion_foto = '$idseccion_foto_row[$i]'";
			mysql_query($queryup);
		}
		
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."#foto_extra')</script>";
	}
	
	//APLICAR CAMBIOS EN SECCION_CARPETA
	if($accion == 'aplicar_cambios_carpeta'){
		
		$orden_row = $_POST['orden_row'];
		$idcarpeta_row = $_POST['idcarpeta_row'];
		$cont = $_POST['cont_carpeta'];
		
		for($i=0;$i<$cont;$i++){
			
			$query = "UPDATE seccion_carpeta
			SET orden = '$orden_row[$i]'
			WHERE idseccion = '$idseccion' AND idcarpeta = '$idcarpeta_row[$i]'";
			mysql_query($query);
			
		}
	
	}
	
	//MODIFICAR		
	if($accion == 'copiar_carpeta'){
		
		if($mod6_idcarpeta4){
			$mod6_idcarpeta_sel = $mod6_idcarpeta4;
		}else{
			if($mod6_idcarpeta3){
				$mod6_idcarpeta_sel = $mod6_idcarpeta3;
			}else{
				if($mod6_idcarpeta2){
					$mod6_idcarpeta_sel = $mod6_idcarpeta2;
				}else{
					if($mod6_idcarpeta){
						$mod6_idcarpeta_sel = $mod6_idcarpeta;
					}
				}	
			}	
		}		
		
		$query_adj = "INSERT INTO seccion_carpeta
		(idcarpeta, idseccion) 
		VALUES
		('$mod6_idcarpeta_sel', '$idseccion')
		";
		mysql_query($query_adj);	
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."#copy');</script>";
	}

	//CONSULTA DATOS DEL PRODUCTO ACTUAL:
	$query_seccion = "SELECT A.*, B.nombre AS nombre_carpeta, C.idcarpeta
	FROM seccion A
	LEFT JOIN seccion_carpeta C ON C.idseccion = A.idseccion
	LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = C.idcarpeta
	WHERE A.idseccion = '$idseccion' AND B.ididioma = '1'";
	$rs_seccion = mysql_fetch_assoc(mysql_query($query_seccion));
	
	$fecha_alta = split("-",$rs_seccion['fecha_alta']);
	$fecha_baja = split("-",$rs_seccion['fecha_baja']);
	
	$accion= "";
?>		

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>

<script type="text/javascript" src="../../js/overlay.js"></script>
<script type="text/javascript" src="../../js/multibox.js"></script>
<link href="../../css/0_multibox.css" rel="stylesheet" type="text/css" />

<script language="javascript">

	function validar_form(){
		formulario = document.form_datos;
		formulario.accion.value = "modificar";
		formulario.submit();	
	};

	function confirm_eliminar(url, variables, id){
	
		if (confirm('¿Está seguro que desea eliminar el registro?')){
			if (variables == ''){
				window.location.href=(url+'?eliminar_tipo='+id);
			}else{
				window.location.href=(url+'?'+variables+'&eliminar_tipo='+id);
			};
		}
	
	}

	function eliminar_foto(){
		formulario = document.form_datos;
		if (confirm('¿ Está seguro que desea eliminar la foto ?')){
			formulario.accion.value = "eliminar_foto";
			formulario.submit();
		}
	}

	function eliminar_foto_extra(id){
		formulario = document.form_fotos;
		if (confirm('¿Está seguro que desea eliminar la foto?')){
			formulario.accion.value = "eliminar_foto_extra";
			formulario.eliminar.value = id;
			formulario.submit();
		}
	}

	function ingresar_foto_extra(){
		formulario = document.form_fotos;
	
		if (formulario.foto_extra.value){
			formulario.accion.value = "ingresar_foto_extra";
			formulario.submit();
		}else{
			alert("Debe seleccionar una foto para ingresar utilizando el botón 'Examinar'.")
		}
	}
	
	function foto_extra_reordenar(){
		formulario = document.form_fotos;
		
		if (formulario.foto_extra_columna.value <= 0 || formulario.foto_extra_columna.value >= 6){
			alert("Por favor, indique la cantidad de columnas en que se muestran las fotos extras.");
		}else{
			formulario.accion.value = "foto_extra_reordenar";
			formulario.submit();
		}
	
	}

	function copiar_carpeta(){
		formulario = document.form_carpeta;
		if (formulario.mod6_idcarpeta.value == "") {
			alert("Debe seleccionar una carpeta");
		} else {	
			formulario.accion.value = 'copiar_carpeta';
			formulario.submit();
		};
	};
	
	function aplicar_cambios_carpeta(){
		formulario = document.form_carpeta;
			formulario.accion.value = 'aplicar_cambios_carpeta';
			formulario.submit();
	}
	
	function mod6_select_idcarpeta(idcat)
	{			
		formulario = document.form_carpeta;
		formulario.submit();
	}
	
	function cambiar_estado(estado, ididioma){
		formulario = document.form_idioma;
		
		formulario.estado_idioma.value = estado;
		formulario.ididioma.value = ididioma;
		formulario.submit();
	}
	
	function cambiar_estado_descarga(estado, iddescarga){
		formulario = document.form_descarga;
		
		formulario.estado_descarga.value = estado;
		formulario.iddescarga.value = iddescarga;
		formulario.submit();
	}
	
	function cambiar_restringido_descarga(estado, iddescarga){
		formulario = document.form_descarga;
		
		formulario.estado_restringido.value = estado;
		formulario.iddescarga.value = iddescarga;
		formulario.submit();
	}
	
	function eliminar_descarga(eliminar_iddescarga){
		formulario = document.form_descarga;
		
		formulario.eliminar_iddescarga.value = eliminar_iddescarga;
		formulario.submit();
	}
	
	function validar_sede(){
		var formulario = document.form_sede;
		var checks_sede = 0;
		var error = '';
		var flag = true;
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = window.form_sede.document.getElementById("sede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			error = error + 'Debe seleccionar al menos una sucursal.\n';
			flag = false;
		}
		
		if(flag == true){	
			formulario.accion.value = "modificar_sede";
			formulario.submit();
		}else{
			alert(error);
		}
	};
	
	function eliminar_copia_carpeta(idcarpeta_copia){
		formulario = document.form_carpeta;
			formulario.accion.value = "eliminar_copia_carpeta";
			formulario.idcarpeta_copia.value = idcarpeta_copia;
			formulario.submit();	
	};
	
	<? if($rs_parametro['usar_comentarios'] == 1){ ?>
	function validar_comentario(){
		var f = document.form_comentario;
		var flag = true;
		var error = '';
		
		if (f.mail_moderador.value == '')	{
			error = error + 'Debe ingresar su e-mail.\n';
			flag = false;		
		}else{
			
			if (f.mail_moderador.value.indexOf("@") == (-1)){
				error = error + 'A su e-mail le falta el @.\n';
				flag = false;
			}
			
			if (f.mail_moderador.value.indexOf(".") == (-1)){
				error = error + 'A su e-mail le falta la extension (ej: .com, .com.es).\n';
				flag = false;
			}
		}
		
		if(flag == true){
			f.accion.value = "modificar_comentario";
			f.submit();
		}else{
			alert(error);
		}
	};
	<? } ?>
	<? if($rs_parametro['usa_rating'] == 1){ ?>
	function validar_rating(){
		var f = document.form_rating;
		var flag = true;
		var error = '';
		
		if(flag == true){
			f.accion.value = "rating";
			f.submit();
		}else{
			alert(error);
		}
	
	};
	
	function formatear_rating(){
		var f = document.form_rating;
		
		if (confirm('¿Está seguro que desea formatear el rating de esta seccion?')){
			f.accion.value = "formatear_rating";
			f.submit();
		}
		
	};
	<? } ?>
	

	
	
window.addEvent('domready', function(){

	//SLIDE
	var mySlide1 = new Fx.Slide('layer_fotos_extras'); mySlide1.hide();
	var mySlide2 = new Fx.Slide('layer_descarga'); mySlide2.hide();
	<? if($rs_parametro['usar_comentarios'] == 1){ ?>var mySlide3 = new Fx.Slide('layer_comentario'); mySlide3.hide();<? } ?>
	<? if($rs_parametro['usa_rating'] == 1){ ?>var mySlide4 = new Fx.Slide('layer_star'); mySlide4.hide();<? } ?>
	var mySlide5 = new Fx.Slide('layer_fotos_extras_nueva'); 
	var mySlide6 = new Fx.Slide('layer_vista'); 
	var mySlide7 = new Fx.Slide('layer_sucursal'); 
	
	
	
	$('btn_fotos_extras').addEvent('click', function(e){
		e = new Event(e);
		mySlide1.toggle();
		e.stop();
	});
	
	$('btn_descarga').addEvent('click', function(e){
		e = new Event(e);
		mySlide2.toggle();
		e.stop();
	});
	
	<? if($rs_parametro['usar_comentarios'] == 1){ ?>$('btn_comentario').addEvent('click', function(e){
		e = new Event(e);
		mySlide3.toggle();
		e.stop();
	});<? } ?>
	
	<? if($rs_parametro['usa_rating'] == 1){ ?>$('btn_star').addEvent('click', function(e){
		e = new Event(e);
		mySlide4.toggle();
		e.stop();
	});<? } ?>
	
	$('btn_fotos_extras_nueva').addEvent('click', function(e){
		e = new Event(e);
		mySlide5.toggle();
		e.stop();
	});
	
	$('btn_vista').addEvent('click', function(e){
		e = new Event(e);
		mySlide6.toggle();
		e.stop();
	});
	
	$('btn_sucursal').addEvent('click', function(e){
		e = new Event(e);
		mySlide7.toggle();
		e.stop();
	});
	
	
});
	
</script>
<style type="text/css">
	.resize {
		margin: 0;
		padding: 10px;
		border-style:dashed;
		border-bottom: 1px  ;
		border-right: 1px  ;
		border-top: 1px  ;
		border-left: 1px  ;
		overflow: auto;
		background-color:#FFFFFF;
		border:#FF9900;
	}
.Estilo1 {
	color: #FF0000;
	font-weight: bold;
}
</style>
</head>
<body>
<div id="header">
  <? include("../../0_top.php"); ?>
</div>
<div id="wrapper">
  <div id="marco_izq"></div>
  <div id="navigation">
    <? include("../../modulo/0_barra/0_barra.php"); ?>
  </div>
  <div id="content">
    <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="40" valign="bottom" class="titulo_grande_bold">Secci&oacute;n - Editar</td>
          </tr>
          <tr>
            <td height="20" valign="top" class="titulo_grande_bold">&nbsp;</td>
          </tr>
        </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
              <tr>
                <td><form action="" method="post" enctype="multipart/form-data" name="form_datos" id="form_datos" style="margin:0px;">
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr>
                        <td height="35" bgcolor="#d8f6ee" class="titulo_medio_bold">Datos de la secci&oacute;n:<span class="detalle_chico" style="color:#FF0000">
                          <input name="accion" type="hidden" id="accion" value="1" />
                        </span></td>
                      </tr>
                      <tr>
                        <td bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                            <tr>
                              <td width="101" align="right" class="detalle_medio">ID:</td>
                              <td colspan="2" align="left" class="detalle_medio_bold"><input name="id" type="text" disabled="disabled" class="detalle_medio" id="id" value="<?= $rs_seccion['idseccion'] ?>" size="4" /></td>
                            </tr>
                            <tr>
                              <td width="101" align="right" valign="middle" class="detalle_medio">Fecha Alta: </td>
                              <td colspan="2" align="left" class="detalle_medio_bold"><span class="style2">
                                <select name="dia_fecha_alta" size="1" class="detalle_medio" id="dia_fecha_alta">
                                  <option value='00' ></option>
                                 <?												
						for ($i=1;$i<32;$i++){
							if ($fecha_alta[2] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                </select>
                                <select name="mes_fecha_alta" size="1" class="detalle_medio" id="mes_fecha_alta">
                                  <option value='00' ></option>
                                 <?						
                        for ($i=1;$i<13;$i++){
							if ($fecha_alta[1] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                </select>
                                <select name="ano_fecha_alta" size="1" class="detalle_medio" id="ano_fecha_alta">
                                  <option value='0000' ></option>
                                <?	
						$anioActual = date("Y");
                        for ($i=$anioActual+1;$i>($anioActual-5);$i--){
							if ($fecha_alta[0] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                </select>
                              </span></td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Fecha Baja: </td>
                              <td colspan="2" align="left" class="detalle_medio_bold"><span class="style2">
                                <select name="dia_fecha_baja" size="1" class="detalle_medio" id="dia_fecha_baja">
                                  <option value='00' ></option>
                                  <?												
						for ($i=1;$i<32;$i++){
							if ($fecha_baja[2] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                </select>
                                <select name="mes_fecha_baja" size="1" class="detalle_medio" id="mes_fecha_baja">
                                  <option value='00' ></option>
                                  <?						
                        for ($i=1;$i<13;$i++){
							if ($fecha_baja[1] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                </select>
                                <select name="ano_fecha_baja" size="1" class="detalle_medio" id="ano_fecha_baja">
                                  <option value='0000' ></option>
                                  <?	
						$anioActual = date("Y");
                        for ($i=$anioActual+1;$i>($anioActual-5);$i--){
							if ($fecha_baja[0] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                </select>
                              </span></td>
                            </tr>
                            <? if(".swf" == strtolower(substr($rs_seccion['foto'],-4)) ){ ?>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Precio Hotel:</td>
                              <td colspan="2" align="left" class="detalle_medio"><label>
                                <input name="precio" type="text"  class="text_field_01 detalle_medio" value="<?= $rs_seccion['precio'] ?>" id="precio" style="width:60px; text-align:center;" maxlength="4"/>
                              </label>
                              U$S</td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&iquest;Es Nuevo?:</td>
                              <td colspan="2" align="left" class="detalle_medio">
                              
                              <label><input type="radio" name="esnuevo" id="esnuevo_SI" value="true" <? if($rs_seccion['esnuevo'] == "true"){ echo "checked"; } ?> />Si</label>
                              <label><input type="radio" name="esnuevo" id="esnuevo_NO" value="false" <? if($rs_seccion['esnuevo'] == "false"){ echo "checked"; } ?> />No</label>
                              
                              </td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Destacado:</td>
                              <td colspan="2" align="left" class="detalle_medio">
                              
                              <label><input type="radio" name="destacado" id="destacado_SI" value="1" <? if($rs_seccion['destacado'] == 1){ echo "checked"; } ?> />Si</label>
                              <label><input type="radio" name="destacado" id="destacado_NO" value="2" <? if($rs_seccion['destacado'] == 2){ echo "checked"; } ?> />No</label>
                                
                              </td>
                            </tr>
                            <? } ?>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Foto:</td>
                              <td colspan="2" align="left"><input name="foto" type="file" class="detalle_medio" id="foto" size="65" /></td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                              <td width="153" align="left" class="detalle_medio">Tama&ntilde;o de foto peque&ntilde;a:</td>
                              <td width="494" align="left" class="detalle_medio"><span class="detalle_medio_bold">
                                <input name="foto_chica_ancho" type="text" class="detalle_medio" id="foto_chica_ancho" value="<?= $foto_chica_ancho ?>" size="5" />
                              </span><strong>px</strong> ancho. </td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                              <td align="left">Tama&ntilde;o de foto mediana:</td>
                              <td align="left"><span class="detalle_medio_bold">
                                <input name="foto_mediana_ancho" type="text" class="detalle_medio" id="foto_mediana_ancho" value="<?= $foto_mediana_ancho ?>" size="5" />
                              </span><strong>px</strong> ancho. </td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                              <td align="left">Tama&ntilde;o de foto grande:</td>
                              <td align="left"><span class="detalle_medio_bold">
                                <input name="foto_grande_ancho" type="text" class="detalle_medio" id="foto_grande_ancho" value="<?= $foto_grande_ancho ?>" size="5" />
                              </span><strong>px</strong> ancho. </td>
                            </tr>
                            <? if($rs_seccion['foto']){ 
									if (file_exists($foto_ruta_chica.$rs_seccion['foto'])){
										$foto_chica_ancho_real = getimagesize($foto_ruta_chica.$rs_seccion['foto']);
									}
									if (file_exists($foto_ruta_mediana.$rs_seccion['foto'])){
										$foto_mediana_ancho_real = getimagesize($foto_ruta_mediana.$rs_seccion['foto']);
									}
									if (file_exists($foto_ruta_grande.$rs_seccion['foto'])){
										$foto_grande_ancho_real = getimagesize($foto_ruta_grande.$rs_seccion['foto']);
									}
							  ?>
                            <tr>
                              <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                              <td colspan="2" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td width="17%" align="left" valign="top"><table width="100" border="1" cellpadding="0" cellspacing="0" bordercolor="#BAEFE0">
                                        <tr>
                                          <td bgcolor="#BAEFE0"  style="border:1px solid #BAEFE0;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                              <tr valign="middle" class="detalle_medio">
                                                <td height="23" align="right"><a style="color:#C61E00; font-size:10px; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif" href="javascript:eliminar_foto();">Quitar</a></td>
                                                <td width="10" align="left"><a href="javascript:eliminar_foto();"><img src="../../imagen/iconos/cross.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                        <tr>
                                          <td style="border:1px solid #BAEFE0;"><? $foto_seccion =& new obj0001(0,$foto_ruta_chica,$rs_seccion['foto'],'','','','','','','precio='.$rs_seccion['precio'].'&esNuevo='.$rs_seccion['esnuevo'],'wmode=opaque',''); ?></td>
                                        </tr>
                                    </table></td>
                                    <td width="83%" align="left" valign="top"><table width="100%" border="0" cellpadding="4" cellspacing="0">
                                      <tr >
                                        <td colspan="4" align="left" valign="middle" class="detalle_medio">Nombre del Archivo: <br />
                                        <strong><?=$rs_seccion['foto']?></strong>
                                        <br />
                                        <br />
                                        </td>
                                      </tr>

                                      <tr>
                                        <td width="6%" align="right" valign="middle" class="detalle_medio"><img src="../../imagen/image.png" width="16" height="16" /></td>
                                        <td width="24%" align="left" valign="middle" class="detalle_medio"><a href="<?= $foto_ruta_chica.$rs_seccion['foto']?>" target="_blank">Pequeña</a></td>
                                        <td width="37%" align="left" valign="middle" class="detalle_medio">
                                        
                                        	<span class="Estilo1"><?=$foto_chica_ancho_real[0]?> x <?=$foto_chica_ancho_real[1]?></span>  px
                                        
                                        </td>
                                        <td width="33%" align="left" valign="middle" class="detalle_medio"><span class="Estilo1"><?= number_format((filesize($foto_ruta_chica.$rs_seccion['foto']))/1024,2) ?></span> kb</td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="detalle_medio"><img src="../../imagen/image.png" width="16" height="16" /></td>
                                        <td align="left" valign="middle" class="detalle_medio"><a href="<?= $foto_ruta_mediana.$rs_seccion['foto']?>" target="_blank">Mediana</a></td>
                                        <td align="left" valign="middle" class="detalle_medio">
                                        
                                        	<span class="Estilo1"><?=$foto_mediana_ancho_real[0]?> x <?=$foto_mediana_ancho_real[1]?></span> px
                                        
                                        </td>
                                        <td align="left" valign="middle" class="detalle_medio"><span class="Estilo1">
                                          <?= number_format((filesize($foto_ruta_mediana.$rs_seccion['foto']))/1024,2) ?></span> kb</td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" class="detalle_medio"><img src="../../imagen/image.png" width="16" height="16" /></td>
                                        <td align="left" valign="middle" class="detalle_medio"><a href="<?= $foto_ruta_grande.$rs_seccion['foto']?>" target="_blank">Grande</a></td>
                                        <td align="left" valign="middle" class="detalle_medio">
                                        
                                        	<span class="Estilo1"><?=$foto_grande_ancho_real[0]?> x <?=$foto_grande_ancho_real[1]?></span> px
                                        
                                        </td>
                                        <td align="left" valign="middle" class="detalle_medio"><span class="Estilo1">
                                          <?= number_format((filesize($foto_ruta_grande.$rs_seccion['foto']))/1024,2) ?></span> kb</td>
                                      </tr>
                                    </table></td>
                                  </tr>
                              </table></td>
                            </tr>
                            <? }; ?>
                        </table></td>
                      </tr>
                    </table>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr bgcolor="#D8F6EE" class="detalle_medio">
                        <td width="111" align="right" class="detalle_medio_bold">&nbsp;</td>
                        <td width="657" align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                          <?
						  		if($forma=='lista'){
									$boton_forma = "javascript:window.open('seccion_ver_lista.php?idcarpeta=".$idcarpeta."','_self');";
								}else{
									$boton_forma = "javascript:window.open('seccion_ver.php?idcarpeta=".$idcarpeta."','_self');";
								}
						  
						  ?>
                    <input name="Submit223" type="button" class="detalle_medio_bold buttons" onclick="<?= $boton_forma ?>" value="  &laquo; Volver  " />
                          </span><span class="detalle_chico" style="color:#FF0000">
                          <input name="Submit22" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="   Guardar &raquo;  " />
                          </span></td>
                    </tr>
                  </table>                  
                </form><br />
				  <form action="" method="post" name="form_idioma" id="form_idioma" style="margin:0px;">
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFE697" class="titulo_medio_bold">&nbsp; Informaci&oacute;n de la secci&oacute;n: <a name="idioma" id="idioma"></a><span class="detalle_chico" style="color:#FF0000">
                            <input name="estado_idioma" type="hidden" id="estado_idioma" value="" />
                            <span class="detalle_chico" style="color:#FF0000">
                            <input name="ididioma" type="hidden" id="ididioma" value="" />
                          </span></span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td align="left" bgcolor="#FFF5D7"><table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
                              <? 					
						$p = 0;
						$query_idioma = "SELECT A.*, B.titulo_idioma
						FROM seccion_idioma_dato A
						LEFT JOIN idioma B ON B.ididioma = A.ididioma
						WHERE A.idseccion = '$idseccion'
						ORDER BY A.ididioma";
						$result_idioma = mysql_query($query_idioma);
						while($rs_idioma = mysql_fetch_assoc($result_idioma)){			
?>
                              <tr>
                                <td width="63" bgcolor="#FFECB3" class="detalle_medio_bold">Idioma</td>
                                <td width="524" bgcolor="#FFECB3" class="detalle_medio_bold"><a href="seccion_editar_idioma.php?idseccion=<?= $idseccion ?>&amp;ididioma=<?=$rs_idioma['ididioma']?>" target="_blank" class="style10"></a>
                                    <?=$rs_idioma['titulo_idioma']?></td>
                                <td width="16" align="center" valign="middle" bgcolor="#FFECB3" class="detalle_medio_bold"><a href="../../../seccion_detalle.php?idseccion=<?= $idseccion ?>&ididioma=<?= $rs_idioma['ididioma'] ?>&idcarpeta=<?= $idcarpeta ?>&modo=previsualizar" target="_blank"><img src="../../imagen/iconos/search_mini.png" width="16" height="16" border="0" /></a></td>
                                <td width="18" bgcolor="#FFECB3" class="detalle_medio_bold"><? if ($rs_idioma['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                                    <a href="javascript:cambiar_estado(2,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                                    <? } else { ?>
                                    <a href="javascript:cambiar_estado(1,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                                    <? } ?></td>
                                <td width="17" bgcolor="#FFECB3" class="detalle_medio_bold"><a href="seccion_editar_idioma.php?idseccion=<?= $idseccion ?>&ididioma=<?=$rs_idioma['ididioma']?>&idcarpeta=<?=$idcarpeta?>&forma=<?=$forma?>" target="_self" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Titulo:</td>
                                <td colspan="4" bgcolor="#FFF5D7" class="detalle_medio"><?=$rs_idioma['titulo']?></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Copete:</td>
                                <td colspan="4" bgcolor="#FFF5D7" class="detalle_medio"><?= stripslashes($rs_idioma['copete']) ?></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Detalle:</td>
                                <td colspan="4" align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio"><? if($rs_idioma['detalle']){
								   $p++; 
								   ?>
                                  <div class="resize" style="height:200px; width:96%; text-align:left">
                                    <?= str_replace('src="imagen','src="../../../imagen', html_entity_decode($rs_idioma['detalle'], ENT_QUOTES)) ?>
                                  </div>
                                <? } ?></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Banner:</td>
                                <td colspan="4" bgcolor="#FFF5D7" class="detalle_medio"><?=$rs_idioma['banner']?></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Keywords:</td>
                                <td colspan="4" bgcolor="#FFF5D7" class="detalle_medio"><?=$rs_idioma['keywords']?></td>
                              </tr>
                              <tr>
                                <td height="15" colspan="5" bgcolor="#FFF5D7" class="detalle_medio_bold"></td>
                              </tr>
                              <? 					
							};
?>
                          </table></td>
                        </tr>
                      </table>
                  </form><br />
                  <form id="form_sede" name="form_sede" method="post" action="" enctype="multipart/form-data" style="margin:0px;">
                      <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="37" bgcolor="#FFDDBC" class="titulo_medio_bold">Propiedades de la secci&oacute;n: <a name="propiedades" id="propiedades"></a><span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td align="left" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5" id="layer_sucursal">
                              <tr>
                                <td width="17%" align="right" valign="top"><span class="detalle_medio_bold">Sucursales:</span></td>
                                <td width="83%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <?
								
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
									$query_seccion_sede = "SELECT A.idsede
									FROM seccion_sede A
									WHERE A.idsede = '$rs_sede[idsede]' AND A.idseccion = '$idseccion'";
									$rs_seccion_sede = mysql_fetch_assoc(mysql_query($query_seccion_sede));
									
									if($rs_seccion_sede['idsede'] == $rs_sede['idsede']){
										$check = "checked";
									}else{
										$check = "";
									}
										
								?>
                                  <tr>
                                    <td width="5%"><input type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?= $check ?> <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?> /></td>
                                    <td width="95%" class="detalle_medio"><?= $rs_sede['titulo'] ?>
                                    </td>
                                  </tr>
                                  <? 
								$c++;
								} 
								?>
                                </table></td>
                              </tr>
                              <tr>
                                <td align="right"><span class="style2">
                                  <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" />
                                </span></td>
                                <td><span class="style2">
                                  <input name="Submit233" type="button" class="detalle_medio_bold" onclick="javascript:validar_sede()" value="  &gt;&gt;  Modificar   " />
                                </span></td>
                              </tr>
                            </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="center" valign="middle" bgcolor="#FFFAF0"><a href="#" id="btn_sucursal"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                  </form><br />
                  <form action="" method="post" name="form_carpeta" id="form_carpeta" style="margin:0px;">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Incorporar nueva Vista de la secci&oacute;n en otra carpeta: <a name="copy" id="copy"></a><span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="" />
                            <input name="idcarpeta_copia" type="hidden" id="idcarpeta_copia" value="" />
                          </span></td>
                          <td bgcolor="#FFDDBC" class="titulo_medio_bold">&nbsp;</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td colspan="2" align="left" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="layer_vista">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td width="19%" height="28" align="right" valign="top" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td align="right"><strong>Vistas en carpeta: </strong></td>
                                      </tr>
                                  </table></td>
                                  <td width="81%"><?
								$cont=0;
								$query_carpeta = "SELECT B.nombre, C.idcarpeta_padre, C.idcarpeta, A.orden
								FROM seccion_carpeta A
								INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
								INNER JOIN carpeta C ON C.idcarpeta = B.idcarpeta
								WHERE A.idseccion = '$idseccion' AND B.ididioma = '1'
								ORDER BY A.orden ASC";
								$result_carpeta = mysql_query($query_carpeta);
								$cant_result = mysql_num_rows($result_carpeta);
								while($rs_carpeta = mysql_fetch_assoc($result_carpeta)){ 
								
								$ruta = $rs_carpeta['nombre'];
								
										//AVERIGUO RUTA COMPLETA - NIVEL 1
										$query_1 = "SELECT B.nombre, A.idcarpeta_padre
										FROM carpeta A
										INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
										WHERE B.ididioma = 1 AND A.idcarpeta = '$rs_carpeta[idcarpeta_padre]'
										LIMIT 1";
										$result_query_1 = mysql_query($query_1);
										while($rs_query_1 = mysql_fetch_assoc($result_query_1)){ 
											
											$ruta = $rs_query_1['nombre']." &raquo; ".$ruta;
											
											//NIVEL 2
											$query_2 = "SELECT B.nombre, A.idcarpeta_padre
											FROM carpeta A
											INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
											WHERE B.ididioma = 1 AND A.idcarpeta = '$rs_query_1[idcarpeta_padre]'
											LIMIT 1";
											$result_query_2 = mysql_query($query_2);
											while($rs_query_2 = mysql_fetch_assoc($result_query_2)){ 
											
												$ruta = $rs_query_2['nombre']." &raquo; ".$ruta;
												
												//NIVEL 3
												$query_3 = "SELECT B.nombre, A.idcarpeta_padre
												FROM carpeta A
												INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
												WHERE B.ididioma = 1 AND A.idcarpeta = '$rs_query_2[idcarpeta_padre]'
												LIMIT 1";
												$result_query_3 = mysql_query($query_3);
												while($rs_query_3 = mysql_fetch_assoc($result_query_3)){ 
												
													$ruta = $rs_query_3['nombre']." &raquo; ".$ruta;
												
												}
											
											}
											
											
										}//FIN AVERIGUO RUTA COMPLETA
										
								?>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="96%" height="29">N&ordm; de Orden:
                                            <label>
                                              <input name="orden_row[<?= $cont ?>]" type="text" class="detalle_medio" id="orden_row[<?= $cont ?>]" size="4" value="<?= $rs_carpeta['orden'] ?>" />
                                              <input name="idcarpeta_row[<?= $cont ?>]" type="hidden" id="idcarpeta_row[<?= $cont ?>]" value="<?= $rs_carpeta['idcarpeta'] ?>" />
                                            </label></td>
                                          <td width="4%" rowspan="2" align="center" valign="top"><? if($cant_result > 1){ ?>
                                              <a href="javascript:eliminar_copia_carpeta(<?= $rs_carpeta['idcarpeta'] ?>);"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a>
                                              <? } ?></td>
                                        </tr>
                                        <tr>
                                          <td height="15"><?= "<b>Ruta:</b> ".$ruta."<br>"; ?></td>
                                        </tr>
                                        <tr>
                                          <td height="15" colspan="2"><hr width="100%" size="1" class="detalle_medio_bold_white" /></td>
                                        </tr>
                                      </table>
                                    <? $cont++; } ?>
                                      <input name="cont_carpeta" type="hidden" id="cont_carpeta" value="<?= $cont ?>" />
                                      <br />
                                      <span class="style2">
                                      <input name="Submit24" type="button" class="detalle_medio_bold" onclick="javascript:aplicar_cambios_carpeta();" value=" &gt;&gt;  Aplicar cambios " />
                                    </span></td>
                                </tr>
                              </table>
                                <hr size="1" class="detalle_medio" />
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td width="19%" align="left"><strong>Llevar a la carpeta: </strong></td>
                                    <td width="81%">&nbsp;</td>
                                  </tr>
                                </table>
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                                  <script language="JavaScript" type="text/javascript">
var i;
function cambia(paso){  

    var formulario = document.form_carpeta; 
	var carpeta;
	var oCntrl;
	
	switch(paso){
		case 1:
			//alert("paso 1");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display  = "none";
			document.getElementById("tr_carpeta4").style.display  = "none";
			document.getElementById("mod6_idcarpeta3").value  = '';
			document.getElementById("mod6_idcarpeta4").value  = '';
			
			carpeta = formulario.mod6_idcarpeta.value;
			oCntrl = formulario.mod6_idcarpeta2;
			break;
			
		case 2:
			//alert("paso 2");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display = "block";
			document.getElementById("tr_carpeta4").style.display  = "none";
			document.getElementById("mod6_idcarpeta4").value  = '';
			
			carpeta = formulario.mod6_idcarpeta2.value;
			oCntrl = formulario.mod6_idcarpeta3;
			break;	
			
		case 3:
			//alert("paso 3");
			document.getElementById("tr_carpeta4").style.display = "block";
			document.getElementById("mod6_idcarpeta4").style.display  = "inline";
			
			carpeta = formulario.mod6_idcarpeta3.value;
			oCntrl = formulario.mod6_idcarpeta4;
			break;	
	}   
   
	//alert(carpeta);
	var txtVal = carpeta;
	
	while(oCntrl.length > 0) oCntrl.options[0]=null;  
	i = 0; 
	oCntrl.clear;  
	
	var selOpcion=new Option("--- Seleccionar Carpeta ---", '');  
	eval(oCntrl.options[i++]=selOpcion);  
	 
	<?
	$query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre, A.idcarpeta_padre
	FROM carpeta A
	INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	WHERE A.estado <> 3 AND A.idcarpeta_padre != '0' AND B.ididioma = '1'
	ORDER BY B.nombre";
	$result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
	while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2)){
	?>
	
	if ("<?= $rs_mod6_idcarpeta2['idcarpeta_padre'] ?>" == txtVal){  
		var selOpcion=new Option("<?= $rs_mod6_idcarpeta2['nombre'] ?>", "<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>");  
		eval(oCntrl.options[i++]=selOpcion);  
	}  

	<? } ?> 
}  
   
                                </script>
                                  <tr>
                                    <td><div id="tr_carpeta">
                                        <div id="colum_carpeta">Carpeta 1&ordm;</div>
                                      <div id="colum_selector"><span class="style10">
                                          <select name="mod6_idcarpeta" class="detalle_medio" id="mod6_idcarpeta" onchange="cambia(1)">
                                            <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
                                            <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
	  {
	  	if ($carpeta1 == $rs_mod6_idcarpeta['idcarpeta'])
		{
			$mod6_sel_idcarpeta = "selected";
		}else{
			$mod6_sel_idcarpeta = "";
		}
?>
                                            <option  <? echo $mod6_sel_idcarpeta ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                                            <?= $rs_mod6_idcarpeta['nombre'] ?>
                                            </option>
                                            <?  } ?>
                                          </select>
                                        </span></div>
                                    </div>
                                        <div style="clear:left; height:1px;"></div>
                                      <div id="tr_carpeta2" style="display:none">
                                          <div id="colum_carpeta">Carpeta 2&ordm;</div>
                                        <div id="colum_selector">
                                            <select name="mod6_idcarpeta2" class="detalle_medio" id="mod6_idcarpeta2" onchange="cambia(2)">
                                            </select>
                                          </div>
                                      </div>
                                      <div style="clear:left; height:1px;"></div>
                                      <div id="tr_carpeta3" style="display:none">
                                          <div id="colum_carpeta">Carpeta 3&ordm;</div>
                                        <div id="colum_selector">
                                            <select name="mod6_idcarpeta3" class="detalle_medio" id="mod6_idcarpeta3" onchange="cambia(3)">
                                            </select>
                                          </div>
                                      </div>
                                      <div style="clear:left; height:1px;"></div>
                                      <div id="tr_carpeta4" style="display:none">
                                          <div id="colum_carpeta">Carpeta 4&ordm;</div>
                                        <div id="colum_selector">
                                            <select name="mod6_idcarpeta4" class="detalle_medio" id="mod6_idcarpeta4" onchange="">
                                            </select>
                                          </div>
                                      </div>
                                      <div style="clear:left; height:1px;"></div></td>
                                  </tr>
                                </table>
                                <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                  <tr>
                                    <td width="119" align="right" valign="top" class="style2">&nbsp;</td>
                                    <td width="539" align="left" valign="middle" class="style2"><input name="Submit2" type="button" class="detalle_medio_bold" onclick="javascript:copiar_carpeta();" value="  &gt;&gt;  Incorporar   " /></td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="center" valign="middle" bgcolor="#FFFAF0"><a href="#" id="btn_vista"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                  </form><br />
                  <form action="#fotos" method="post" enctype="multipart/form-data" name="form_fotos" id="form_fotos" style="margin:0px;">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="35" colspan="2" bgcolor="#FF8282" class="titulo_medio_bold">Fotos extras: <a name="foto_extra" id="foto_extra"></a><span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="" />
                            <input name="eliminar" type="hidden" id="eliminar" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td height="32" align="left" bgcolor="#FFBBBB" class="detalle_medio_bold">Ingresar  nueva foto extra</td>
                          <td align="right" bgcolor="#FFBBBB" class="detalle_medio_bold"><span class="detalle_medio_bold_white">
                            <? if($_SESSION['idcusuario_perfil_log'] == 1){ ?>
                            <input name="Button2" type="button" class="detalle_medio_bold" onclick="window.open('seccion_foto_extra_multi.php?idseccion=<?= $idseccion ?>','_blank');" value=" Carga multiple &raquo; " />
                            <? } ?>
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td colspan="2" align="left" bgcolor="#FFE1E1"><table width="100%" border="0" cellspacing="0" cellpadding="4" id="layer_fotos_extras_nueva">
                              <tr>
                                <td width="118" align="right" class="detalle_medio">Titulo:</td>
                                <td colspan="2"><input name="foto_extra_titulo" type="text" class="detalle_medio" id="foto_extra_titulo" size="58" /></td>
                              </tr>
                              <tr>
                                <td width="118" align="right" class="detalle_medio">N&deg; de Orden: </td>
                                <td colspan="2"><span class="detalle_medio_bold">
                                  <input name="foto_extra_orden" type="text" class="detalle_medio" id="foto_extra_orden" size="4" />
                                </span></td>
                              </tr>
                              <tr>
                                <td width="118" align="right" valign="top" class="detalle_medio">Idiomas:</td>
                                <td colspan="2"><?
								  		  $c=0;
										  $query_ididioma = "SELECT ididioma, titulo_idioma 
										  FROM idioma
										  WHERE estado = '1'
										  ORDER BY titulo_idioma";
										  $result_ididioma = mysql_query($query_ididioma);
										  while ($rs_ididioma = mysql_fetch_assoc($result_ididioma))	  
										  {

										?>
                                    <input name="foto_extra_ididioma[<?= $c ?>]" type="checkbox" id="foto_extra_ididioma[<?= $c ?>]" value="<?= $rs_ididioma['ididioma'] ?>" checked="checked" />
                                    <?= $rs_ididioma['titulo_idioma'] ?>
                                    <br />
                                    <?  $c++; } ?>
                                    <input name="cantidad_idioma" type="hidden" id="cantidad_idioma" value="<?= $c ?>" /></td>
                              </tr>
                              <tr>
                                <td align="right" class="detalle_medio">Foto:</td>
                                <td colspan="2"><input name="foto_extra" type="file" class="detalle_medio" id="foto_extra" size="42" /></td>
                              </tr>
                              <tr>
                                <td width="118" align="right" class="detalle_medio">&nbsp;</td>
                                <td colspan="2" align="left" class="detalle_medio"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                                    <tr>
                                      <td width="25%" align="left">Tama&ntilde;o foto peque&ntilde;a: </td>
                                      <td width="75%"><input name="foto_extra_chica_ancho" type="text" class="detalle_medio" id="foto_extra_chica_ancho" value="<?= $foto_extra_chica_ancho ?>" size="4" />
                                          <strong> px</strong> ancho. </td>
                                    </tr>
                                    <tr>
                                      <td align="left">Tama&ntilde;o foto grande: </td>
                                      <td><input name="foto_extra_grande_ancho" type="text" class="detalle_medio" id="foto_extra_grande_ancho" value="<?= $foto_extra_grande_ancho ?>" size="4" />
                                          <strong>px</strong> ancho.</td>
                                    </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="118" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td width="129" align="left" valign="top" class="detalle_medio">&nbsp; Ubicaci&oacute;n de la foto: </td>
                                <td align="left" valign="top" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="6%"><input name="foto_extra_tipo" type="radio" value="1" checked="checked" /></td>
                                      <td width="7%"><img src="../../imagen/horizontal.gif" width="24" height="24" /></td>
                                      <td width="87%">Forma Horizontal. </td>
                                    </tr>
                                    <tr>
                                      <td width="6%"><input name="foto_extra_tipo" type="radio" value="2" /></td>
                                      <td width="7%"><img src="../../imagen/vertical.gif" width="24" height="24" /></td>
                                      <td>Forma Vertical. </td>
                                    </tr>
                                    <tr>
                                      <td width="6%"><input name="foto_extra_tipo" type="radio" value="3" /></td>
                                      <td width="7%"><img src="../../imagen/detalle.gif" width="24" height="24" /></td>
                                      <td>Dentro del detalle. </td>
                                    </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="118">&nbsp;</td>
                                <td colspan="2"><input name="Submit23" type="button" class="detalle_medio_bold" onclick="javascript:ingresar_foto_extra()" value="  &gt;&gt;  Agregar   " /></td>
                              </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="center" valign="middle" bgcolor="#FFECEC"><a href="#" id="btn_fotos_extras_nueva"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td width="51%" height="32" align="left" bgcolor="#FFBBBB" class="detalle_medio_bold">Fotos extras ingresadas </td>
                          <td height="32" align="right" bgcolor="#FFBBBB"><span class="detalle_medio_bold">
                            <input name="Button" type="button" class="detalle_medio_bold"  onclick="foto_extra_reordenar();" value="  &gt;&gt;  Aplicar cambios  " />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td height="32" colspan="2" align="left" bgcolor="#FFCCCC" class="detalle_medio">Mostrar fotos en
                            <label>
                            <input name="foto_extra_columna" type="text" class="detalle_medio" id="foto_extra_columna" style="width:20px; height:16px; text-align:center;" value="<?= $rs_seccion['foto_extra_columna'] ?>" maxlength="1" />
                            </label>
columnas. </td>
                        </tr>
                        <tr bgcolor="#999999"  >
                          <td colspan="2" align="left" bgcolor="#FFE1E1">
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="layer_fotos_extras">
                            <tr>
                              <td><? //FOTO EXTRA
				$foto_extra_cont = 0;
				$query_idioma = "SELECT titulo_idioma, ididioma
				FROM idioma 
				WHERE estado = 1";
				$result_idioma = mysql_query($query_idioma);
				while($rs_idioma = mysql_fetch_assoc($result_idioma)){
				?>
                                <table  width="100%" border="0" cellspacing="0" cellpadding="4">
                                  <tr>
                                    <td height="30" class="detalle_medio_bold">&bull;
                                        <?= $rs_idioma['titulo_idioma'] ?></td>
                                  </tr>
                                </table>
                                <?
				
				$query_foto_cant = "SELECT COUNT(idseccion_foto)
				FROM seccion_foto 
				WHERE idseccion = '$idseccion' AND ididioma = '$rs_idioma[ididioma]'";
				$rs_foto_cant = mysql_fetch_row(mysql_query($query_foto_cant));
				if($rs_foto_cant[0]>0){
				
				?>
                                <table width="100" border="0" cellpadding="4" cellspacing="0">
                                  <tr valign="top">
                                    <? 
						
						$query_foto = "SELECT *
						FROM seccion_foto 
						WHERE idseccion = '$idseccion' AND ididioma = '$rs_idioma[ididioma]'
						ORDER BY orden";
						$result_foto = mysql_query($query_foto);
						$vuelta_foto = 1;
						while($rs_foto = mysql_fetch_assoc($result_foto)){
								$hay_fotos = true;
								$foto_extra_cont++;
								//echo "<script>alert('".$foto_extra_cont."');</script>";
							
							 ?>
                                    <td align="center" valign="top" class="ejemplo_12px"><table width="150" border="1" cellpadding="0" cellspacing="0" bordercolor="#FFBBBB">
                                        <tr>
                                          <td height="30" bgcolor="#FFBBBB"  style="border:1px solid #FFBBBB;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                              <tr valign="middle" class="detalle_medio">
                                                <td width="96" align="left"><? if($rs_foto['foto_extra_tipo'] == 1){ ?>
                                                    <img src="../../imagen/horizontal.gif" alt="barra horizontal habilitada" width="24" height="24" border="0" />
                                                    <? }else{ ?>
                                                    <a href="<?= $_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."&idseccion_foto_sel=".$rs_foto['idseccion_foto']."&foto_extra_tipo_sel=1";?>"><img src="../../imagen/horizontal_gris.gif" alt="barra horizontal deshabilitada" width="24" height="24" border="0" /></a>
                                                    <? } ?>
                                                    <? if($rs_foto['foto_extra_tipo'] == 2){ ?>
                                                    <img src="../../imagen/vertical.gif" alt="barra vertical habilitada" width="24" height="24" border="0" />
                                                    <? }else{ ?>
                                                    <a href="<?= $_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."&idseccion_foto_sel=".$rs_foto['idseccion_foto']."&foto_extra_tipo_sel=2";?>"><img src="../../imagen/vertical_gris.gif" alt="barra vertical deshabilitada" width="24" height="24" border="0" /></a>
                                                    <? } ?>
                                                    <? if($rs_foto['foto_extra_tipo'] == 3){ ?>
                                                    <img src="../../imagen/detalle.gif" alt="Insertar en detalle habilitado" width="24" height="24" border="0" />
                                                    <? }else{ ?>
                                                    <a href="<?= $_SERVER['PHP_SELF']."?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."&idseccion_foto_sel=".$rs_foto['idseccion_foto']."&foto_extra_tipo_sel=3";?>"> <img src="../../imagen/detalle_gris.gif" alt="Insertar en detalle habilitado" width="24" height="24" border="0" /></a>
                                                    <? } ?>                                                </td>
                                                <td width="17" align="left" bgcolor="#FF9595"><a href="javascript:eliminar_foto_extra(<?=$rs_foto['idseccion_foto']?>);">
                                                  <input name="idseccion_foto_row[<?= $foto_extra_cont ?>]" type="hidden" id="idseccion_foto_row[<?= $foto_extra_cont ?>]" value="<?= $rs_foto['idseccion_foto'] ?>" />
                                                  <img src="../../imagen/cruz.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                                                <td width="21" align="left" bgcolor="#FF9595"><input  type="checkbox" name="checkbox_row[<?= $foto_extra_cont ?>]" id="checkbox_row[<?= $foto_extra_cont ?>]"  value="<?=$rs_foto['idseccion_foto']?>" /></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                        <tr>
                                          <td align="center" valign="middle"  style="border:1px solid #FFBBBB;"><?
										if($rs_foto['foto'] == ''){
											$foto_xtra =& new obj0001('0','../../../swf/','no_disponible.swf',$ancho_maximo_mini,'','','','','','','wmode=Opaque',''); 
										}else{
											$foto_xtra =& new obj0001('0',$foto_extra_ruta_chica,$rs_foto['foto'],'150','','','',$foto_extra_ruta_grande.$rs_foto['foto'],'_blank','','',''); 
										}; 
										?></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="detalle_chico"  style="border:1px solid #FFBBBB;">Titulo:
                                            <label>
                                              <input name="foto_extra_titulo_row[<?= $foto_extra_cont ?>]" type="text" class="detalle_medio" id="foto_extra_titulo_row[<?= $foto_extra_cont ?>]" value="<?= $rs_foto['titulo']; ?>" size="14" />
                                              </label>                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="detalle_chico"  style="border:1px solid #FFBBBB;">N&ordm; de Orden:
                                            <input name="foto_extra_orden_row[<?= $foto_extra_cont ?>]" type="text" class="detalle_medio" id="foto_extra_orden_row[<?= $foto_extra_cont ?>]" value="<?= $rs_foto['orden'] ?>" size="7" /></td>
                                        </tr>
                                    </table></td>
                                    <?	
								if($vuelta_foto == 4){ 
									echo "</tr><tr>";
									$vuelta_foto = 1;
								}else{
									$vuelta_foto++;
								};			
							 
							 }; //FIN WHILE 
									 
							?>
                                  </tr>
                                </table>
                                <? }else{ ?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="8" id="layer_fotos_extras">
                                  <tr>
                                    <td height="40" align="center" valign="middle" class="detalle_medio">No se han cargado fotos extra.</td>
                                  </tr>
                                </table>
                                <? }; // FIN IF CANT
							  }//FIN IDIOMAS
						// FIN PRODUCTO FOTO ?>
                                <span class="detalle_medio_bold">
                                <input name="foto_extra_cont" type="hidden" id="foto_extra_cont" value="<?=$foto_extra_cont?>" />
                                </span></td>
                            </tr>
                          </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="center" valign="middle" bgcolor="#FFECEC"><a href="#" id="btn_fotos_extras"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table>
                  </form><br />
				  <form enctype="multipart/form-data" action="" method="post" id="form_descarga" name="form_descarga" style="margin:0px;">
				  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td height="40" colspan="3" bgcolor="#9DBFFF" class="titulo_medio_bold">Descargas<a name="descarga" id="descarga"></a>
                      <input name="estado_descarga" type="hidden" id="estado_descarga" />
                      <input name="iddescarga" type="hidden" id="iddescarga" />
                      <input name="estado_restringido" type="hidden" id="estado_restringido" />
                      <input name="eliminar_iddescarga" type="hidden" id="eliminar_iddescarga" /></td>
                    </tr>
                    <tr>
                      <td width="92%" height="32" bgcolor="#BBD2FF" class="detalle_medio_bold">Nueva descarga para esta seccion </td>
                      <td width="4%" align="center" valign="middle" bgcolor="#BBD2FF" class="detalle_medio_bold"><a href="seccion_editar.php?idseccion=<?= $idseccion ?>&idcarpeta=<?= $idcarpeta ?>&forma=<?= $forma ?>"><img src="../../imagen/iconos/refresh.png" width="20" height="20" border="0" /></a></td>
                      <td width="4%" bgcolor="#BBD2FF" class="detalle_medio_bold">
					  <a href="../descarga/descarga_nueva_individual.php?idseccion=<?= $idseccion ?>" rel="widht:400;height:300" id="d1" class="desc" title="" ><img src="../../imagen/iconos/add_download.png" width="20" height="20" border="0" /></a></td>
                    </tr>
                    <tr>
                      <td colspan="3" bgcolor="#E1EDFF"><table width="100%" border="0" cellspacing="0" cellpadding="3" id="layer_descarga">
                        <tr>
                          <td width="2%" height="25" class="detalle_medio_bold">&nbsp;</td>
                          <td width="31%" class="detalle_medio_bold">Titulo</td>
                          <td width="26%" height="25" class="detalle_medio_bold">Archivo</td>
                          <td width="12%" height="25" class="detalle_medio_bold">Tama&ntilde;o</td>
                          <td width="16%" height="25" class="detalle_medio_bold">Tipo</td>
                          <td width="13%" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
						<?
						
						if($idcarpeta){
							$filtro_carpeta = " OR idcarpeta = '$idcarpeta' ";
						}else{
							$filtro_carpeta = "";
						}
						
						$query_descarga = "SELECT *
						FROM descarga
						WHERE idseccion = '$idseccion' $filtro_carpeta
						ORDER BY idseccion";
						$result_descarga = mysql_query($query_descarga);
						$cant_result = mysql_num_rows($result_descarga);
						while($rs_descarga = mysql_fetch_assoc($result_descarga)){
						
						?>
                        <tr>
                          <td height="30"><img src="../../../imagen/iconos/flecha_cursos.gif" width="5" height="5" /></td>
                          <td height="30" class="detalle_11px"><?= $rs_descarga['titulo'] ?></td>
                          <td height="30" class="detalle_11px"><?= $rs_descarga['archivo'] ?></td>
                          <td height="30" class="detalle_11px">
						  <? 
						  		if(file_exists($ruta_descarga.$rs_descarga['archivo'])){ 
									echo  number_format((filesize($ruta_descarga.$rs_descarga['archivo']))/1024,2);									
								}; 
						  ?> kb.
						  </td>
                          <td height="30" class="detalle_11px">
						  <?
						   
						  $query = "SELECT B.titulo AS titulo_tipo
						  FROM descarga A
						  INNER JOIN descarga_tipo B ON A.idtipo_descarga = B.iddescarga_tipo
						  WHERE B.iddescarga_tipo = '$rs_descarga[idtipo_descarga]' "; 
						  $rs_descarga_tipo = mysql_fetch_assoc(mysql_query($query));
						  echo $rs_descarga_tipo['titulo_tipo'];
						  
						  ?>
						  </td>
                          <td height="30" align="left">
						  <? 
						  if($rs_descarga['idseccion'] != 0){ 
						  	 if($rs_descarga['estado'] == 1){ 
						  		echo '<a href="javascript:cambiar_estado_descarga(2,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/b_habilitado.png" width="15" height="16" border="0" /></a>';
						     }else{ 
						  		echo '<a href="javascript:cambiar_estado_descarga(1,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/b_habilitado_off.png" width="15" height="16" border="0" /></a>';
						     } 
						   
						  ?>&nbsp; <?
						  
						  	if($rs_descarga['restringido'] == 1){
						  		echo '<a href="javascript:cambiar_restringido_descarga(2,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/s_rights.png" width="16" height="16" border="0" /></a>';
						  	}else{
						  		echo '<a href="javascript:cambiar_restringido_descarga(1,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/s_rights_b.png" width="14" height="14" border="0" /></a>';
							}
						  
						  ?>&nbsp;<a href="../descarga/descarga_editar.php?iddescarga=<?= $rs_descarga['iddescarga'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a>						  <? 
						  		
						   		echo '<a href="javascript:eliminar_descarga('.$rs_descarga['iddescarga'].');"><img src="../../imagen/trash.png" width="15" height="16" border="0" /></a>';
						  }else{ //FIN SI TIENE SECCION
						  		
								echo '<a href="../carpeta/carpeta_editar.php?idcarpeta='.$idcarpeta.'"  target="_blank" ><img src="../../imagen/iconos/mini_folder.png" width="25" height="25" border="0" /></a>';
						  
						  } ?></td>
                        </tr>
						<? } ?>
						
						<? if($cant_result == 0){ ?>
                        <tr>
                          <td height="40" colspan="6" align="center" valign="middle" class="detalle_medio">No hay descargas disponibles. </td>
                        </tr>
						<? } ?>
                      </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" bgcolor="#ECF3FF"><a href="#" id="btn_descarga"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
				  </form><br />
				  
				<? if($rs_parametro['usar_comentarios'] == 1){ ?>
				 <form action="" method="post" name="form_comentario" id="form_comentario" style="margin:0px;">
				   <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                     <tr bgcolor="#999999">
                       <td width="79%" height="37" bgcolor="#A8C6A8" class="titulo_medio_bold">Comentarios: <a name="propiedades" id="propiedades"></a><span class="detalle_chico" style="color:#FF0000">
                         <input name="accion" type="hidden" id="accion" value="" />
                       </span></td>
                       <td width="5%" bgcolor="#A8C6A8" class="titulo_medio_bold"><img src="../../imagen/iconos/comments_20px.png" width="20" height="20" /></td>
                       <td width="16%" align="left" valign="middle" bgcolor="#A8C6A8" class="detalle_medio_bold" ><a href="seccion_comentario_ver.php?idseccion=<?= $idseccion ?>" target="_self"><font color="#4A714A">Ver comentarios</font></a></td>
                     </tr>
                     <tr >
                       <td colspan="3" align="left" bgcolor="#CEDFCE"><table width="100%" border="0" cellspacing="0" cellpadding="5" id="layer_comentario">
                           <tr>
                             <td width="20%" align="right">Mail Moderador: </td>
                             <td width="80%"><input name="mail_moderador" type="text" class="detalle_medio" id="mail_moderador" style="width:250px;" value="<?= $rs_seccion['mail_moderador'] ?>" /></td>
                           </tr>
                           <tr>
                             <td align="right">Habilitar comentarios: </td>
                             <td><input name="habilita_comentario" type="radio" value="1" <? if($rs_seccion['habilita_comentario'] == 1){ echo "checked";} ?> />
Si
  <input name="habilita_comentario" type="radio" value="2" <? if($rs_seccion['habilita_comentario'] == 2){ echo "checked";} ?> />
No</td>
                           </tr>
                           <tr>
                             <td>&nbsp;</td>
                             <td><span class="style2">
                               <input name="Submit2332" type="button" class="detalle_medio_bold" onclick="javascript:validar_comentario()" value="  &gt;&gt;  Modificar   " />
                             </span></td>
                           </tr>
                         </table>
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td align="center" valign="middle" bgcolor="#DBE8DB"><a href="#" id="btn_comentario"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                           </tr>
                         </table></td>
                     </tr>
                   </table>
				  </form>
                  <br />
				  <? } ?>
				
				<? if($rs_parametro['usa_rating'] == 1){ ?>
				<form action="" method="post" name="form_rating" id="form_rating" enctype="multipart/form-data" style="margin:0px;">
				  <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                    <tr bgcolor="#999999">
                      <td height="37" bgcolor="#FFA8A8" class="titulo_medio_bold">Star Rating: <a name="propiedades" id="propiedades"></a><span class="detalle_chico" style="color:#FF0000">
                      <input name="accion" type="hidden" id="accion" value="" />
                                            </span></td>
                    </tr>
                    <tr >
                      <td align="left" bgcolor="#FFD9D9"><table width="100%" border="0" cellspacing="0" cellpadding="5" id="layer_star">
                        <tr>
                          <td width="20%" align="right" valign="middle">Habilitar rating: </td>
                          <td width="80%"><input name="usa_rating" type="radio" value="1" <? if($rs_seccion['usa_rating'] == 1){ echo "checked";} ?> />
Si
  <input name="usa_rating" type="radio" value="2" <? if($rs_seccion['usa_rating'] == 2){ echo "checked";} ?> />
No</td>
                        </tr>
                        <tr>
                          <td align="right" valign="middle">&nbsp;</td>
                          <td><a href="javascript:formatear_rating();"><font color="#993300">Reiniciar rating de esta seccion (vuelve los valores a cero). </font></a></td>
                        </tr>
                        <tr>
                          <td align="right" valign="middle">&nbsp;</td>
                          <td><?
							if($rs_seccion['puntaje_total'] > 0){
								$promedio = round($rs_seccion['puntaje_total'] / $rs_seccion['votos_total'],2);
							}else{
								$promedio = 0;
							}
							echo "Promedio de valoraci&oacute;n: <b>".$promedio."</b> (de ".$rs_seccion['votos_total']." votos)."; 
							
							?></td>
                        </tr>
                        <tr>
                          <td align="right" valign="middle">&nbsp;</td>
                          <td><span class="style2">
                            <input name="Submit23322" type="button" class="detalle_medio_bold" onclick="javascript:validar_rating();" value="  &gt;&gt;  Modificar   " />
                          </span></td>
                        </tr>
                      </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center" valign="middle" bgcolor="#FFECEC"><a href="#" id="btn_star"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
				</form>
				<? } ?>
				</td>
              </tr>
          </table></td>
      </tr>
    </table>
  </div>
  <div id="marco_der"></div>
</div>
<div id="footer">
</div>
<script language="javascript">
	var box1 = {};
	window.addEvent('domready', function(){
		box1 = new MultiBox('desc', {descClassName: 'multiBoxDesc_desc', useOverlay: true});
	});
</script>
</body>
</html>