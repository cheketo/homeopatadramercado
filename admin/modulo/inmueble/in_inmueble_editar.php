<? require_once ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<? 

	$in_idinmueble = $_GET['in_idinmueble'];
	$accion = $_POST['accion'];
	
	$accion_detalle = $_POST['accion_detalle'];
	$eliminar_detalle = $_POST['eliminar_detalle'];
	
	$accion_foto = $_POST['accion_foto'];
	$eliminar_foto = $_POST['eliminar_foto'];
	$accion_plano = $_POST['accion_plano'];
	$eliminar_plano = $_POST['eliminar_plano'];
	$accion_mapa = $_POST['accion_mapa'];
	$eliminar_mapa = $_POST['eliminar_mapa'];
	
	$in_titulo = $_POST['in_titulo'];
	$in_titulo_en = $_POST['in_titulo_en'];
	$in_operacion = $_POST['in_operacion'];
	$in_valor = $_POST['in_valor'];
	$in_moneda = $_POST['in_moneda'];
	
	$in_calle = $_POST['in_calle'];
	$in_numero = $_POST['in_numero'];
	$in_piso = $_POST['in_piso'];
	$in_depto = $_POST['in_depto'];
	$in_entre = $_POST['in_entre'];
	$in_provincia = $_POST['in_provincia'];
	$in_barrio = $_POST['in_barrio'];
	
	$in_supcubierta = $_POST['in_supcubierta'];
	$in_supsemicubierta = $_POST['in_supsemicubierta'];
	$in_supdescubierta = $_POST['in_supdescubierta'];
	$in_suptotalpropia = $_POST['in_suptotalpropia'];
	$in_suptotal = $_POST['in_suptotal'];
	
	$in_orientacion = $_POST['in_orientacion'];
	$in_disposicion = $_POST['in_disposicion'];
	$in_tipoedificio = $_POST['in_tipoedificio'];
	
	$in_descripcion = str_replace(chr(13),"<br>",$_POST['in_descripcion']);
	$in_descripcion_en = str_replace(chr(13),"<br>",$_POST['in_descripcion_en']);
	
	$in_observaciones_internas = str_replace(chr(13),"<br>",$_POST['in_observaciones_internas']);
	$in_observaciones = str_replace(chr(13),"<br>",$_POST['in_observaciones']);
	$in_observaciones_en = str_replace(chr(13),"<br>",$_POST['in_observaciones_en']);
	
	$in_horario_visita = $_POST['in_horario_visita'];
	
	$in_foto = $_POST['in_foto'];
	
	$dia_fecha_pub = $_POST['dia_fecha_pub'];
	$mes_fecha_pub = $_POST['mes_fecha_pub'];
	$ano_fecha_pub = $_POST['ano_fecha_pub'];
	$in_fecha_publicacion = $ano_fecha_pub."-".$mes_fecha_pub."-".$dia_fecha_pub;
	
	$dia_fecha_ult_mod = $_POST['dia_fecha_ult_mod'];
	$mes_fecha_ult_mod = $_POST['mes_fecha_ult_mod'];
	$ano_fecha_ult_mod = $_POST['ano_fecha_ult_mod'];
	$in_fecha_ult_actualizacion = $ano_fecha_ult_mod."-".$mes_fecha_ult_mod."-".$dia_fecha_ult_mod;
	
	$in_estado = $_POST['in_estado'];
	$in_mostrar_home = $_POST['in_mostrar_home'];
	$in_mostrar_publicacion = $_POST['in_mostrar_publicacion'];
	
	$in_ambiente = $_POST['in_ambiente'];
	$in_cochera = $_POST['in_cochera'];
	$in_baul = $_POST['in_baul'];
	$in_antiguedad = $_POST['in_antiguedad'];
	$in_telefono = $_POST['in_telefono'];
	$in_pisos = $_POST['in_pisos'];
	$in_deptospiso = $_POST['in_deptospiso'];
	$in_ascensores = $_POST['in_ascensores'];
	$in_aireacondicionado = $_POST['in_aireacondicionado'];
	$in_plantas = $_POST['in_plantas'];
	$in_aptoprofesional = $_POST['in_aptoprofesional'];
	$in_luminosidad = $_POST['in_luminosidad'];
	$in_agua_caliente = $_POST['in_agua_caliente'];
	$in_calefaccion = $_POST['in_calefaccion'];
	$in_expensas = $_POST['in_expensas'];
	$in_aptorubros = $_POST['in_aptorubros'];
	$in_medidafrente = $_POST['in_medidafrente'];
	$in_medidafondo = $_POST['in_medidafondo'];
	$in_posicion = $_POST['in_posicion'];
	$in_zonificacion = $_POST['in_zonificacion'];
	$in_fot = $_POST['in_fot'];
	$in_aptopara = $_POST['in_aptopara'];
	
	$in_ambiente_idioma = $_POST['in_ambiente_idioma'];
	$in_ambiente_tipo = $_POST['in_ambiente_tipo'];
	$in_ambiente_medida = $_POST['in_ambiente_medida'];
	$in_ambiente_placard = $_POST['in_ambiente_placard'];
	$in_ambiente_terminacion = $_POST['in_ambiente_terminacion'];
	
	$in_img_foto = $_POST['in_img_foto'];
	$in_nombre_foto = $_POST['in_nombre_foto'];
	$in_plano = $_POST['in_plano'];
	$in_nombre_plano = $_POST['in_nombre_plano'];
	$in_mapa = $_POST['in_mapa'];
	$in_nombre_mapa = $_POST['in_nombre_mapa'];
	
	$fecha_actual = date('Y').'-'.date('m').'-'.date('d');
	
	$foto_ruta_chica_foto = "../../../imagen/inmueble/foto/chica/";
	$foto_ruta_grande_foto = "../../../imagen/inmueble/foto/grande/";
	$foto_ruta_chica_plano = "../../../imagen/inmueble/plano/chica/";
	$foto_ruta_grande_plano = "../../../imagen/inmueble/plano/grande/";
	$foto_ruta_chica_mapa = "../../../imagen/inmueble/mapa/chica/";
	$foto_ruta_grande_mapa = "../../../imagen/inmueble/mapa/grande/";
	
	//FUNCION CLEAN_STRING
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
	
	//ACTUALIZA:
	if ($accion == 'modificar_datos'){
	
		// INCORPORACION DE FOTO
		if ($_FILES['in_foto']['name'] != ''){
		
			$foto_ruta_chica = "../../../imagen/inmueble/thumbnails/";
			$archivo_ext = substr($_FILES['in_foto']['name'],-4);
			$archivo_nombre = substr($_FILES['in_foto']['name'],0,strrpos($_FILES['in_foto']['name'], "."));
			
			$archivo = clean_string($archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
			
			$querysel = "SELECT in_foto FROM in_inmueble WHERE in_idinmueble = '$in_idinmueble' ";
			$rowfoto = mysql_fetch_row(mysql_query($querysel));
			
			if ( $rowfoto[0] ){
				if (file_exists($foto_ruta_chica.$rowfoto[0])){
					unlink ($foto_ruta_chica.$rowfoto[0]);
				}
			}
				
			$foto =  $in_idinmueble . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
			
			if (!copy($_FILES['in_foto']['tmp_name'], $foto_ruta_chica . $foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
				echo "<script>alert('".$error."')</script>"; 
			}else{
				$imagesize = getimagesize($foto_ruta_chica.$foto);
				
				if( ($imagesize[2]>0 && $imagesize[2]<=5) || ($imagesize[2]>6 && $imagesize[2]<=16) ){ //si es una imagen
					$peso = number_format((filesize($foto_ruta_chica.$foto))/1024,2);
					
					if($peso==0){
						$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error2."')</script>"; // se muestra el error.
					}else{
					
						$foto_chica_ancho_sel = 150;
						$foto_chica_alto_sel = 100;
						
						//ACHICAR IMAGEN PROPORCIONALMENTE:
						if ($imagesize[0] > $foto_chica_ancho_sel){
							$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
							if($alto_nuevo > $foto_chica_alto_sel){
								$ancho_nuevo = ceil($foto_chica_alto_sel * $foto_chica_ancho_sel / $alto_nuevo);
								crear_miniatura($foto_ruta_chica.$foto, $ancho_nuevo, $foto_chica_alto_sel, $foto_ruta_chica);
							}else{
								crear_miniatura($foto_ruta_chica.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica);
							}
						};
				
						//ingreso de foto en tabla 
						$query = "UPDATE in_inmueble SET 
								  in_foto = '$foto' 
								  WHERE in_idinmueble = '$in_idinmueble'
								  LIMIT 1";
								  mysql_query($query);
	
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
			
			}
		} // FIN INCORPORACION DE FOTO
		
		$query = "UPDATE in_inmueble
				  SET 
				     in_titulo = '$in_titulo' 
					, in_titulo_en = '$in_titulo_en'
					, in_operacion = '$in_operacion'
					, in_valor = '$in_valor'
					, in_moneda = '$in_moneda'
					
					, in_calle = '$in_calle'
					, in_numero = '$in_numero'
					, in_piso = '$in_piso'
					, in_depto = '$in_depto'
					, in_entre = '$in_entre'
					, in_provincia = '$in_provincia'
					, in_barrio = '$in_barrio'
					
					, in_supcubierta = '$in_supcubierta'
					, in_supsemicubierta = '$in_supsemicubierta'
					, in_supdescubierta = '$in_supdescubierta'
					, in_suptotalpropia = '$in_suptotalpropia'
					, in_suptotal = '$in_suptotal'
					
					, in_orientacion = '$in_orientacion'
					, in_disposicion = '$in_disposicion'
					, in_tipoedificio = '$in_tipoedificio'
					
					, in_descripcion = '$in_descripcion'
					, in_descripcion_en = '$in_descripcion_en'
					
					, in_observaciones_internas = '$in_observaciones_internas'
					, in_observaciones = '$in_observaciones'
					, in_observaciones_en = '$in_observaciones_en'
					
					, in_horario_visita = '$in_horario_visita'
					
					, in_fecha_publicacion = '$in_fecha_publicacion'
					
					, in_estado = '$in_estado'
					, in_mostrar_home = '$in_mostrar_home'
					, in_mostrar_publicacion = '$in_mostrar_publicacion'
					
					, in_ambiente = '$in_ambiente'
					, in_cochera = '$in_cochera'
					, in_baul = '$in_baul'
					, in_antiguedad = '$in_antiguedad'
					, in_telefono = '$in_telefono'
					, in_pisos = '$in_pisos'
					, in_deptospiso = '$in_deptospiso'
					, in_ascensores = '$in_ascensores'
					, in_aireacondicionado = '$in_aireacondicionado'
					, in_plantas = '$in_plantas'
					, in_aptoprofesional = '$in_aptoprofesional'
					, in_luminosidad = '$in_luminosidad'
					, in_agua_caliente = '$in_agua_caliente'
					, in_calefaccion = '$in_calefaccion'
					, in_expensas = '$in_expensas'
					, in_aptorubros = '$in_aptorubros'
					, in_medidafrente = '$in_medidafrente'
					, in_medidafondo = '$in_medidafondo'
					, in_posicion = '$in_posicion'
					, in_zonificacion = '$in_zonificacion'
					, in_fot = '$in_fot'
					, in_aptopara = '$in_aptopara'
					
		    	  WHERE in_idinmueble = '$in_idinmueble'
	    		  LIMIT 1";
				  mysql_query($query);
				 
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble');</script>";
			$accion = "";
	};
	
	//ELIMINAR FOTO PRINCIPAL;
	if ($accion == 'eliminar_foto_principal'){
		
		$foto_ruta_chica = "../imagen/inmueble/thumbnails/";
		$query = "SELECT in_foto FROM in_inmueble WHERE in_idinmueble = '$in_idinmueble' ";
		$rs_foto = mysql_fetch_row(mysql_query($query));
			
		if ( $rs_foto[0] ){
			if (file_exists($foto_ruta_chica.$rs_foto[0])){
				unlink ($foto_ruta_chica.$rs_foto[0]);
			}
		}
		
		$query = "UPDATE in_inmueble SET 
				  in_foto = '' 
				  WHERE in_idinmueble = '$in_idinmueble'
				  LIMIT 1";
				  mysql_query($query);
	$accion = "";
	};
	
	//ELIMINAR FOTO;
	if ($accion_foto == 'eliminar_foto'){
		
		$query = "SELECT in_foto 
				  FROM in_foto
				  WHERE in_idfoto = '$eliminar_foto' ";
		$rs_foto = mysql_fetch_row(mysql_query($query));
			
		if ( $rs_foto[0] ){
			if (file_exists($foto_ruta_chica_foto.$rs_foto[0])){
				unlink ($foto_ruta_chica_foto.$rs_foto[0]);
			}
			if (file_exists($foto_ruta_grande_foto.$rs_foto[0])){
				unlink ($foto_ruta_grande_foto.$rs_foto[0]);
			}
		}
		
		$query = "DELETE FROM in_foto 
				  WHERE in_idfoto = '$eliminar_foto'";
				  mysql_query($query);
				  
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#04');</script>";
	$accion_foto = "";
	};
	
	//ELIMINAR PLANO;
	if ($accion_plano == 'eliminar_plano'){
		
		$query = "SELECT in_plano 
				  FROM in_plano
				  WHERE in_idplano = '$eliminar_plano' ";
		$rs_foto = mysql_fetch_row(mysql_query($query));
			
		if ( $rs_foto[0] ){
			if (file_exists($foto_ruta_chica_plano.$rs_foto[0])){
				unlink ($foto_ruta_chica_plano.$rs_foto[0]);
			}
			if (file_exists($foto_ruta_grande_plano.$rs_foto[0])){
				unlink ($foto_ruta_grande_plano.$rs_foto[0]);
			}
		}
		
		$query = "DELETE FROM in_plano 
				  WHERE in_idplano = '$eliminar_plano'";
				  mysql_query($query);
				  
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#05');</script>";
	};
	
	//ELIMINAR MAPA;
	if ($accion_mapa == 'eliminar_mapa'){
		
		$query = "SELECT in_mapa 
				  FROM in_mapa
				  WHERE in_idmapa = '$eliminar_mapa' ";
		$rs_foto = mysql_fetch_row(mysql_query($query));
			
		if ( $rs_foto[0] ){
			if (file_exists($foto_ruta_chica_mapa.$rs_foto[0])){
				unlink ($foto_ruta_chica_mapa.$rs_foto[0]);
			}
			if (file_exists($foto_ruta_grande_mapa.$rs_foto[0])){
				unlink ($foto_ruta_grande_mapa.$rs_foto[0]);
			}
		}
		
		$query = "DELETE FROM in_mapa 
				  WHERE in_idmapa = '$eliminar_mapa'";
				  mysql_query($query);
				  
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#06');</script>";
		$accion_mapa = "";
	};
	
	//ELIMINAR DETALLE:
	if ($accion_detalle == 'eliminar_detalle'){
		
		$query = "DELETE FROM in_detalle
				  WHERE in_iddetalle = '$eliminar_detalle'";
				  mysql_query($query);
				  echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#03');</script>";
		$accion_detalle = "";
	}
	
	//INGRESA NUEVO DETALLE:
	if ($accion_detalle == 'insertar_detalle'){
	
		$query = "INSERT INTO in_detalle (
				  in_idinmueble,
				  in_ambiente_idioma,
				  in_ambiente_tipo,
				  in_ambiente_placard,
				  in_ambiente_medida,
				  in_ambiente_terminacion
				  ) VALUES (
				  '$in_idinmueble',
				  '$in_ambiente_idioma',
				  '$in_ambiente_tipo',
				  '$in_ambiente_placard',
				  '$in_ambiente_medida',
				  '$in_ambiente_terminacion'
				  )";
				  mysql_query($query);
				  echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#03');</script>";
		$accion_detalle = "";
	};
	
	//INGRESAR FOTOS:
	if ($accion_foto == 'nueva_foto'){
		
		// INCORPORACION DE FOTO
		if ($_FILES['in_foto2']['name'] != ''){
		
			$archivo_ext = substr($_FILES['in_foto2']['name'],-4);
			$archivo_nombre = substr($_FILES['in_foto2']['name'],0,strrpos($_FILES['in_foto2']['name'], "."));
			
			$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
				
			$foto =  $in_idinmueble . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
			
			if (!copy($_FILES['in_foto2']['tmp_name'], $foto_ruta_grande_foto.$foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. Puede deberse a que la imagen sea demasiado pesada."; // se crea la variable error con un mensaje de error.
				echo "<script>alert('".$error."')</script>"; 
			}else{
				$imagesize = getimagesize($foto_ruta_grande_foto.$foto);
			
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($foto_ruta_grande_foto.$foto))/1024,2);
					
					if($peso==0){
						$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error2."')</script>"; // se muestra el error.
					}else{
					
						$foto_chica_ancho_sel = 150;
						$foto_chica_alto_sel = 100;
						
						$foto_grande_ancho = 770;
						//ACHICAR IMAGEN AL ANCHO MÁXIMO:
						if ($imagesize[0] > $foto_grande_ancho){
							$alto_nuevo = ceil($foto_grande_ancho * $imagesize[1] / $imagesize[0]) ;
							crear_miniatura($foto_ruta_grande_foto.$foto, $foto_grande_ancho, $alto_nuevo, $foto_ruta_grande_foto);
						}else{
							crear_miniatura($foto_ruta_grande_foto.$foto, $imagesize[0], $imagesize[1], $foto_ruta_grande_foto);
						}
						
						//ACHICAR IMAGEN PROPORCIONALMENTE:
						if ($imagesize[0] > $foto_chica_ancho_sel){
							$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
							if($alto_nuevo > $foto_chica_alto_sel){
								$ancho_nuevo = ceil($foto_chica_alto_sel * $foto_chica_ancho_sel / $alto_nuevo);
								crear_miniatura($foto_ruta_grande_foto.$foto, $ancho_nuevo, $foto_chica_alto_sel, $foto_ruta_chica_foto);
							}else{
								crear_miniatura($foto_ruta_grande_foto.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica_foto);
							}
						};
						
						//ingreso de foto en tabla 
						$query = "INSERT INTO in_foto (
								  in_idinmueble,
								  in_foto,
								  in_nombre_foto
								  ) VALUES (
								  '$in_idinmueble',
								  '$foto',
								  '$in_nombre_foto'
								  )";
								  mysql_query($query);
								  echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#04');</script>";
	
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
			
			}
		} // FIN INCORPORACION DE FOTO
	$accion_foto = "";
	}
	
	//INGRESAR PLANO:
	if ($accion_plano == 'nuevo_plano'){
		
		// INCORPORACION DE FOTO
		if ($_FILES['in_plano']['name'] != ''){
		
			$archivo_ext = substr($_FILES['in_plano']['name'],-4);
			$archivo_nombre = substr($_FILES['in_plano']['name'],0,strrpos($_FILES['in_plano']['name'], "."));
			
			$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
				
			$foto =  $in_idinmueble . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
			
			if (!copy($_FILES['in_plano']['tmp_name'], $foto_ruta_grande_plano.$foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. Puede deberse a que la imagen sea demasiado pesada."; // se crea la variable error con un mensaje de error.
				echo "<script>alert('".$error."')</script>"; 
			}else{
				$imagesize = getimagesize($foto_ruta_grande_plano.$foto);
			
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($foto_ruta_grande_plano.$foto))/1024,2);
					
					if($peso==0){
						$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error2."')</script>"; // se muestra el error.
					}else{
					
						$foto_chica_ancho_sel = 150;
						$foto_chica_alto_sel = 100;
						
						$foto_grande_ancho = 770;
						//ACHICAR IMAGEN AL ANCHO MÁXIMO:
						if ($imagesize[0] > $foto_grande_ancho){
							$alto_nuevo = ceil($foto_grande_ancho * $imagesize[1] / $imagesize[0]) ;
							crear_miniatura($foto_ruta_grande_plano.$foto, $foto_grande_ancho, $alto_nuevo, $foto_ruta_grande_plano);
						}else{
							crear_miniatura($foto_ruta_grande_plano.$foto, $imagesize[0], $imagesize[1], $foto_ruta_grande_plano);
						}
						
						//ACHICAR IMAGEN PROPORCIONALMENTE:
						if ($imagesize[0] > $foto_chica_ancho_sel){
							$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
							if($alto_nuevo > $foto_chica_alto_sel){
								$ancho_nuevo = ceil($foto_chica_alto_sel * $foto_chica_ancho_sel / $alto_nuevo);
								crear_miniatura($foto_ruta_grande_plano.$foto, $ancho_nuevo, $foto_chica_alto_sel, $foto_ruta_chica_plano);
							}else{
								crear_miniatura($foto_ruta_grande_plano.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica_plano);
							}
						};
						
						//ingreso de foto en tabla 
						$query = "INSERT INTO in_plano (
								  in_idinmueble,
								  in_plano,
								  in_nombre_plano
								  ) VALUES (
								  '$in_idinmueble',
								  '$foto',
								  '$in_nombre_plano'
								  )";
								  mysql_query($query);
								  echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#05');</script>";
	
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
			
			}
		} // FIN INCORPORACION DE FOTO
	$accion_plano = "";
	}
	
	//INGRESAR MAPA:
	if ($accion_mapa == 'nuevo_mapa'){
		
		// INCORPORACION DE FOTO
		if ($_FILES['in_mapa']['name'] != ''){
		
			$archivo_ext = substr($_FILES['in_mapa']['name'],-4);
			$archivo_nombre = substr($_FILES['in_mapa']['name'],0,strrpos($_FILES['in_mapa']['name'], "."));
			
			$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
				
			$foto =  $in_idinmueble . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
			
			if (!copy($_FILES['in_mapa']['tmp_name'], $foto_ruta_grande_mapa.$foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. Puede deberse a que la imagen sea demasiado pesada."; // se crea la variable error con un mensaje de error.
				echo "<script>alert('".$error."')</script>"; 
			}else{
				$imagesize = getimagesize($foto_ruta_grande_mapa.$foto);
			
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($foto_ruta_grande_mapa.$foto))/1024,2);
					
					if($peso==0){
						$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error2."')</script>"; // se muestra el error.
					}else{
					
						$foto_chica_ancho_sel = 150;
						$foto_chica_alto_sel = 100;
						
						$foto_grande_ancho = 770;
						//ACHICAR IMAGEN AL ANCHO MÁXIMO:
						if ($imagesize[0] > $foto_grande_ancho){
							$alto_nuevo = ceil($foto_grande_ancho * $imagesize[1] / $imagesize[0]) ;
							crear_miniatura($foto_ruta_grande_mapa.$foto, $foto_grande_ancho, $alto_nuevo, $foto_ruta_grande_mapa);
						}else{
							crear_miniatura($foto_ruta_grande_mapa.$foto, $imagesize[0], $imagesize[1], $foto_ruta_grande_mapa);
						}
						
						//ACHICAR IMAGEN PROPORCIONALMENTE:
						if ($imagesize[0] > $foto_chica_ancho_sel){
							$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
							if($alto_nuevo > $foto_chica_alto_sel){
								$ancho_nuevo = ceil($foto_chica_alto_sel * $foto_chica_ancho_sel / $alto_nuevo);
								crear_miniatura($foto_ruta_grande_mapa.$foto, $ancho_nuevo, $foto_chica_alto_sel, $foto_ruta_chica_mapa);
							}else{
								crear_miniatura($foto_ruta_grande_mapa.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica_mapa);
							}
						};
						
						//ingreso de foto en tabla 
						$query = "INSERT INTO in_mapa (
								  in_idinmueble,
								  in_mapa,
								  in_nombre_mapa
								  ) VALUES (
								  '$in_idinmueble',
								  '$foto',
								  '$in_nombre_mapa'
								  )";
								  mysql_query($query);
								  echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?in_idinmueble=$in_idinmueble#06');</script>";
	
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
			
			}
		} // FIN INCORPORACION DE FOTO
	$accion_mapa = "";
	}
	
	//FUNCION CREAR MINIATURA
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
		
	//CONSULTA:	
	$query = "SELECT *
			  FROM in_inmueble
			  WHERE in_idinmueble = $in_idinmueble";
			  $result = mysql_query($query);
		      $rs_inmueble = mysql_fetch_assoc($result); 

	//SEPARO LOS DIAS-MESES-AÑOS DE LAS FECHAS
	$fecha_publicacion = split("-",$rs_inmueble['in_fecha_publicacion']);
	$fecha_ult_modificacion = split("-",$rs_inmueble['in_fecha_ult_actualizacion']);
	
?>
<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">
	
	function eliminar_foto_principal(){
	formulario = document.form_inmueble;
	formulario.accion.value = "eliminar_foto_principal";
	formulario.submit();
	};
	
	function eliminar_foto(id){
	formulario = document.form_foto;
	formulario.eliminar_foto.value = id;
	formulario.accion_foto.value = "eliminar_foto";
	formulario.submit();
	};
	
	function eliminar_plano(id){
	formulario = document.form_plano;
	formulario.eliminar_plano.value = id;
	formulario.accion_plano.value = "eliminar_plano";
	formulario.submit();
	};
	
	function eliminar_mapa(id){
	formulario = document.form_mapa;
	formulario.eliminar_mapa.value = id;
	formulario.accion_mapa.value = "eliminar_mapa";
	formulario.submit();
	};
	
	function eliminar_detalle(id){
	formulario = document.form_detalle;
	formulario.eliminar_detalle.value = id;
	formulario.accion_detalle.value = "eliminar_detalle";
	formulario.submit();
	};
	
	function validar_form_mapa(){
	formulario = document.form_mapa;
	var c = true;
	
		if(formulario.in_mapa.value == ""){
			alert("Seleccionar el mapa.");
			c=false;
		}
		if(c==true){
			formulario.accion_mapa.value = "nuevo_mapa";
			formulario.submit();
		}
	};

	function validar_form_plano(){
	formulario = document.form_plano;
	var c = true;
	
		if(formulario.in_plano.value == ""){
			alert("Seleccionar el plano.");
			c=false;
		}
		if(c==true){
			formulario.accion_plano.value = "nuevo_plano";
			formulario.submit();
		}
	};
	
	function validar_form_foto(){
	formulario = document.form_foto;
	var c = true;
	
		if(formulario.in_foto2.value == ""){
			alert("Seleccionar una foto.");
			c=false;
		}
		if(c==true){
			formulario.accion_foto.value = "nueva_foto";
			formulario.submit();
		}
	};
	
	function validar_form_detalle(){
	formulario = document.form_detalle;
		var c = true;
		
		if(formulario.in_ambiente_tipo.value == ""){
			alert("Debe completar el tipo del ambiente.");
			c=false;
		}
		if(formulario.in_ambiente_medida.value == ""){
			alert("Debe ingresar las medidas.");
			c=false;
		}
		if(c==true){
			formulario.accion_detalle.value = "insertar_detalle";
			formulario.submit();
		}

	};
	
	function validar_form_inmueble(){
		formulario = document.form_inmueble;
		var c = true;
		
		if(formulario.in_titulo.value == "" || formulario.in_titulo_en.value == "" ){
			alert("Debe redactar el titulo en ambos idiomas.");
			c=false;
		}
		if(esNumerico(formulario.in_valor.value) == false){
			alert("El valor del inmueble debe ser numerico.");
			c=false;
		}

		if(c==true){
			formulario.accion.value = "modificar_datos";
			formulario.submit();
		}
	
	};

</script>



<script type="text/javascript">

window.addEvent('domready', function(){

	var mySlide = new Fx.Slide('fotos'); mySlide.hide();
	var mySlide2 = new Fx.Slide('planos'); mySlide2.hide();
	var mySlide3 = new Fx.Slide('mapas'); mySlide3.hide();
	 
	$('slidein').addEvent('click', function(e){
		e = new Event(e);
		mySlide.slideIn();
		e.stop();
	});
	 
	$('slideout').addEvent('click', function(e){
		e = new Event(e);
		mySlide.slideOut();
		e.stop();
	});
	
	$('slidein2').addEvent('click', function(e){
		e = new Event(e);
		mySlide2.slideIn();
		e.stop();
	});
	 
	$('slideout2').addEvent('click', function(e){
		e = new Event(e);
		mySlide2.slideOut();
		e.stop();
	});
	 
	$('slidein3').addEvent('click', function(e){
		e = new Event(e);
		mySlide3.slideIn();
		e.stop();
	});
	 
	$('slideout3').addEvent('click', function(e){
		e = new Event(e);
		mySlide3.slideOut();
		e.stop();
	});
	
	 
});

</script>
</head>
<body onload="">
<div id="header">
  <? include("../../0_top.php"); ?>
</div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
                <td width="45%" height="40" valign="bottom" class="titulo_grande_bold"> Inmueble - Editar</td>
                <td width="12%" align="right" valign="middle" class="detalle_medio"><a href="in_inmueble_ver.php"></a><a href="in_inmueble_nuevo.php"></a> <a href="in_inmueble_nuevo.php"><img src="../../imagen/iconos/home_search.png" width="24" height="24" border="0" /></a></td>
                <td width="16%" align="left" valign="middle" class="detalle_medio">&nbsp;<a href="in_inmueble_ver.php"><span class="detalle_medio" style="text-decoration:none"> Ver Inmuebles</span></a></td>
                <td width="10%" align="right" valign="middle" class="detalle_medio"><a href="in_inmueble_nuevo.php"><img src="../../imagen/iconos/home.png" width="24" height="24" border="0" /></a></td>
                <td width="17%" align="left" valign="middle" class="detalle_medio">&nbsp;<a href="in_inmueble_nuevo.php"><span class="detalle_medio" style="text-decoration:none"> Nuevo Inmueble</span></a></td>
              </tr>
              <tr>
                <td height="15" colspan="5" valign="middle" class="detalle_medio">&nbsp;</td>
            </tr>
              <tr>
                <td height="30" valign="middle" bgcolor="#FFF2E8" class="detalle_medio">&nbsp;
                  <?
				$query_prev = "SELECT in_idinmueble
				FROM in_inmueble
				WHERE in_idinmueble < $in_idinmueble
				ORDER BY in_idinmueble DESC
				LIMIT 1";
				$rs_prev = mysql_fetch_assoc(mysql_query($query_prev));
				
				if($rs_prev['in_idinmueble']){
					echo '<a href="in_inmueble_editar.php?in_idinmueble='.$rs_prev['in_idinmueble'].'">&laquo; anterior</a>';
				}else{
					echo '&laquo; anterior';
				}
				?></td>
                <td height="30" colspan="3" align="left" valign="middle" bgcolor="#FFF2E8" class="detalle_medio">&nbsp;</td>
                <td height="30" align="right" valign="middle" bgcolor="#FFF2E8" class="detalle_medio"><?
				$query_next = "SELECT in_idinmueble
				FROM in_inmueble
				WHERE in_idinmueble > $in_idinmueble
				ORDER BY in_idinmueble ASC
				LIMIT 1";
				$rs_next = mysql_fetch_assoc(mysql_query($query_next));
				
				if($rs_next['in_idinmueble']){
					echo '<a href="in_inmueble_editar.php?in_idinmueble='.$rs_next['in_idinmueble'].'"><span class="detalle_medio" style="text-decoration:none">siguiente &raquo;</span></a>';
				}else{
					echo 'siguiente &raquo;';
				}
				?>
                &nbsp; </td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_inmueble" id="form_inmueble">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFE2C6" class="titulo_medio_bold">Datos del inmueble: <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF2E8"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="145" align="right" valign="middle" class="detalle_medio">ID Inmueble: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_idinmueble" type="text" disabled="disabled" class="detalle_medio" style="text-align:center" id="in_idinmueble" value="<?= $rs_inmueble['in_idinmueble'] ?>" size="2" />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="145" align="right" valign="middle" class="detalle_medio">Tipo:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><strong>
                                <?
                          
						  $query_tipo = "SELECT in_tipo_inmueble_titulo 
						  FROM in_tipo_inmueble
						  WHERE in_idtipo_inmueble = '$rs_inmueble[in_tipoinmueble]' ";
						  $rs_tipo = mysql_fetch_assoc(mysql_query($query_tipo));
						  echo $rs_tipo['in_tipo_inmueble_titulo'];
						  
						  ?>
                                </strong></td>
                              </tr>
                              <tr>
                                <td width="145" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_titulo" type="text" class="detalle_medio" id="in_titulo" value="<?= $rs_inmueble['in_titulo'] ?>" size="80"  />
                                  <img src="../../imagen/flag_spain.jpg" width="18" height="12" /></label></td>
                              </tr>
                              <tr>
                                <td width="145" align="right" valign="middle" class="detalle_medio">Title:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_titulo_en" type="text" class="detalle_medio" id="in_titulo_en" value="<?= $rs_inmueble['in_titulo_en'] ?>" size="80"  />
                                <img src="../../imagen/flag_england.jpg" width="17" height="12" /></td>
                              </tr>
                              <tr>
                                <td width="145" align="right" valign="middle" class="detalle_medio">Operaci&oacute;n:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><select name="in_operacion" class="detalle_medio" id="in_operacion">
                                    
                                    <? if($rs_inmueble['in_tipoinmueble'] != 5 && $rs_inmueble['in_tipoinmueble'] != 7){ ?>
                                    <option value="1" <? if($rs_inmueble['in_operacion'] == "1"){ echo "selected";} ?>>Alquiler</option>
                                    <? } ?>
                                    
                                    <option value="2" <? if($rs_inmueble['in_operacion'] == "2"){ echo "selected";} ?>>Venta</option>
                                </select></td>
                              </tr>
                              <tr>
                                <td width="145" align="right" valign="middle" class="detalle_medio">Valor: </td>
                                <td width="57" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_valor" type="text" class="detalle_medio" id="in_valor" value="<? if($rs_inmueble['in_valor']>0){ echo $rs_inmueble['in_valor'];} ?>" size="7" />
                                </label></td>
                                <td width="47" align="right" valign="middle" class="detalle_medio">Moneda:</td>
                                <td colspan="3" align="left" valign="middle" class="detalle_medio"><label>
                                  <select name="in_moneda" class="detalle_medio" id="in_moneda">
                                    <option value="ARG" <? if($rs_inmueble['in_moneda'] == "ARG"){ echo "selected";} ?>> Peso </option>
                                    <option value="USD" <? if($rs_inmueble['in_moneda'] == "USD"){ echo "selected";} ?>> Dolar </option>
                                    <option value="EUR" <? if($rs_inmueble['in_moneda'] == "EUR"){ echo "selected";} ?>> Euro </option>
                                  </select>
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><label></label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio_bold">Ubicaci&oacute;n</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Calle:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_calle" type="text" class="detalle_medio" id="in_calle" size="50" value="<?= $rs_inmueble['in_calle'] ?>" />
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">N&uacute;mero:</td>
                                <td align="left" valign="middle" class="detalle_medio"><input name="in_numero" type="text" class="detalle_medio" id="in_numero" value="<?= $rs_inmueble['in_numero'] ?>" size="7" /></td>
                                <td align="right" valign="middle" class="detalle_medio">Piso:</td>
                                <td width="75" align="left" valign="middle" class="detalle_medio"><input name="in_piso" type="text" class="detalle_medio" id="in_piso" value="<?= $rs_inmueble['in_piso'] ?>" size="3" /></td>
                                <td width="57" align="right" valign="middle" class="detalle_medio">Depto:</td>
                                <td width="235" align="left" valign="middle" class="detalle_medio"><input name="in_depto" type="text" class="detalle_medio" id="in_depto" value="<?= $rs_inmueble['in_depto'] ?>" size="3" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Entre:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_entre" type="text" class="detalle_medio" id="in_entre" size="50" value="<?= $rs_inmueble['in_entre'] ?>" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Barrio:</td>
                              <td colspan="5" align="left" valign="middle" class="detalle_medio"><span class="style2">
                                  <select name="in_provincia" class="detalle_medio" id="in_provincia">
                                    <option value="" >--- Seleccionar Barrio ---</option>
                                    <?
		 
	  $query_provincia = "SELECT *
	  FROM pais_provincia
	  WHERE estado <> 3 AND idpais = 54
	  ORDER BY titulo";
	  $result_provincia = mysql_query($query_provincia);
	  while ($rs_provincia = mysql_fetch_assoc($result_provincia))	  
	  {
	  
	  	if ($rs_inmueble['in_provincia'] == $rs_provincia['idpais_provincia'])
		{
			$sel_prov = "selected";
		}else{
			$sel_prov = "";
		}
?>
                                    <option value="<?= $rs_provincia['idpais_provincia'] ?>" <? echo $sel_prov ?>>
                                    <?= $rs_provincia['titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio_bold">Superficie</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Superficie Cubierta: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_supcubierta" type="text" class="detalle_medio" id="in_supcubierta" value="<?= $rs_inmueble['in_supcubierta'] ?>" size="7" />
m2</td>
                              </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Superficie Semi-Cubierta: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_supsemicubierta" type="text" class="detalle_medio" id="in_supsemicubierta" value="<?= $rs_inmueble['in_supsemicubierta'] ?>" size="7" />
m2</td>
                              </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1  || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Superficie Descubierta: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_supdescubierta" type="text" class="detalle_medio" id="in_supdescubierta" value="<?= $rs_inmueble['in_supdescubierta'] ?>" size="7" />
m2</td>
                              </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Superficie Total Propia: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_suptotalpropia" type="text" class="detalle_medio" id="in_suptotalpropia" value="<?= $rs_inmueble['in_suptotalpropia'] ?>" size="7" />
m2</td>
                              </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 5){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Superficie Total: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_suptotal" type="text" class="detalle_medio" id="in_suptotal" value="<?= $rs_inmueble['in_suptotal'] ?>" size="7" />
m2</td>
                              </tr>
                              <? } ?>
                              
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Orientaci&oacute;n:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_orientacion" type="text" class="detalle_medio" id="in_orientacion" value="<?= $rs_inmueble['in_orientacion'] ?>" size="7" maxlength="2" /></td>
                              </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 3){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Disposici&oacute;n:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><select name="in_disposicion" class="detalle_medio" id="in_disposicion">
                                  <option value="">Seleccionar Disposicion</option>
                                  <option value="1" <? if($rs_inmueble['in_disposicion'] == 1){ echo "selected"; } ?> >Frente</option>
                                  <option value="2" <? if($rs_inmueble['in_disposicion'] == 2){ echo "selected"; } ?> >Contra Frente</option>
                                  <option value="3" <? if($rs_inmueble['in_disposicion'] == 3){ echo "selected"; } ?> >Lateral</option>
                                  <option value="4" <? if($rs_inmueble['in_disposicion'] == 4){ echo "selected"; } ?> >Interno</option>
                                 </select></td>
                              </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 3){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Tipo Edificio:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">
                                <select name="in_tipoedificio" class="detalle_medio" id="in_tipoedificio">
                                  <option value="">Seleccionar Tipo de Edificio</option>
                                  <option value="1" <? if($rs_inmueble['in_tipoedificio'] == 1){ echo "selected"; } ?> >Entre Medianera</option>
                                  <option value="2" <? if($rs_inmueble['in_tipoedificio'] == 2){ echo "selected"; } ?> >Torre</option>
                                  </select></td>
                              </tr>
                              <? } ?>
                              
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="15" align="right" valign="top" class="detalle_medio">Descripci&oacute;n: </td>
                          <td colspan="5" rowspan="2" align="left" valign="middle" class="detalle_medio"><label>
                          <textarea name="in_descripcion" cols="80" rows="8" class="detalle_medio" id="in_descripcion" style="background-image:url(../../imagen/spainflag.jpg); background-repeat:no-repeat; background-position:left bottom; background-color:#FFFFFF; "><?= str_replace("<br>",chr(13),$rs_inmueble['in_descripcion']); ?></textarea>
                          </label></td>
                              </tr>
                              <tr>
                                <td height="15" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="25" align="right" valign="top" class="detalle_medio">Description:</td>
                                <td colspan="5" rowspan="2" align="left" valign="top" class="detalle_medio"><textarea name="in_descripcion_en" cols="80" rows="8" class="detalle_medio" id="in_descripcion_en" style="background-image:url(../../imagen/englandflag.jpg); background-repeat:no-repeat; background-position:left bottom; background-color:#FFFFFF;"><?= str_replace("<br>",chr(13),$rs_inmueble['in_descripcion_en']); ?></textarea></td>
                              </tr>
                              <tr>
                                <td height="45" align="right" valign="top" class="detalle_medio"></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">Haga una completa descripci&oacute;n del inmueble publicado. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Observaciones Internas:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><textarea name="in_observaciones_internas" cols="80" rows="8" class="detalle_medio" id="in_observaciones_internas"><?= str_replace("<br>",chr(13),$rs_inmueble['in_observaciones_internas']); ?></textarea></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Observaciones:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><textarea name="in_observaciones" cols="80" rows="8" class="detalle_medio" id="in_observaciones" style="background-image:url(../../imagen/spainflag.jpg); background-repeat:no-repeat; background-position:left bottom; background-color:#FFFFFF"><?= str_replace("<br>",chr(13),$rs_inmueble['in_observaciones']); ?></textarea></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Observations:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><textarea name="in_observaciones_en" cols="80" rows="8" class="detalle_medio" id="in_observaciones_en" style="background-image:url(../../imagen/englandflag.jpg); background-repeat:no-repeat; background-position:left bottom; background-color:#FFFFFF;"><?= str_replace("<br>",chr(13),$rs_inmueble['in_observaciones_en']); ?>
                                </textarea></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">Haga sus observaciones acerca del inmueble publicado. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto principal: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><span class="style2">
                                  <input name="in_foto" type="file" class="detalle_medio" id="in_foto" size="40" />
                                </span></td>
                              </tr>
                              <? if($rs_inmueble['in_foto'] != ''){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><u>Archivo:</u>
                                    <?= $rs_inmueble['in_foto'] ?></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><table width="10" border="0" cellpadding="2" cellspacing="0" class="Tabla_estilo01">
                                    <tr>
                                      <td width="150" height="20" align="left" valign="middle" bgcolor="#FFE7D5" class="detalle_medio">&nbsp;</td>
                                      <td width="25" height="25" align="center" valign="middle" bgcolor="#FFE7D5"><img src="../../imagen/iconos/cross.png" width="16" height="16" onclick="eliminar_foto_principal();" /></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" bgcolor="#FFF9F4"><img src="../../../imagen/inmueble/thumbnails/<?= $rs_inmueble['in_foto'] ?>" /></td>
                                    </tr>
                                </table></td>
                              </tr>
                              <? } ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Fecha Publicaci&oacute;n: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><span class="style2">
                                  <select name="dia_fecha_pub" size="1" class="detalle_medio" id="dia_fecha_pub">
                                    <option value='00' ></option>
                                    
                                        
                                        ;
										
                                        
                                        
                                    <?	

						if($fecha_publicacion[2] == "00"){
							$fecha_publicacion[2] = date("j");
						}
																
						for ($i=1;$i<32;$i++){
							if ($fecha_publicacion[2] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                  </select>
                                  <select name="mes_fecha_pub" size="1" class="detalle_medio" id="mes_fecha_pub">
                                    <option value='00' ></option>
                                    
                                        
                                        ;
                                        
                                        
                                        
                                    <?	
						if($fecha_publicacion[1] == "00"){
							$fecha_publicacion[1] = date("n");
						}					
                        for ($i=1;$i<13;$i++){
							if ($fecha_publicacion[1] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                  </select>
                                  <select name="ano_fecha_pub" size="1" class="detalle_medio" id="ano_fecha_pub">
                                    <option value='0000' ></option>
                                    
                                        
                                        ;
                                        
                                        
                                        
                                    <?
						if($fecha_publicacion[0] == "0000"){
							$fecha_publicacion[0] = date("Y");
						}
						$anioActual = date("Y");
                        for ($i=$anioActual+1;$i>($anioActual-5);$i--){
							if ($fecha_publicacion[0] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                  </select>
                                  </font></span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Horario de Visita: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_horario_visita" type="text" class="detalle_medio" id="in_horario_visita" value="<?= $rs_inmueble['in_horario_visita'] ?>" size="80" />
                                </label></td>
                            </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Estado:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><label>
                                  <select name="in_estado" class="detalle_medio" id="in_estado">
                                    <option value="0" <? if($rs_inmueble['in_estado'] == "0"){ echo "selected";} ?>>Disponible</option>
                                    <option value="1" <? if($rs_inmueble['in_estado'] == "1"){ echo "selected";} ?>>Reservado</option>
                                    <option value="2" <? if($rs_inmueble['in_estado'] == "2"){ echo "selected";} ?>>Alquilado</option>
                                    <option value="3" <? if($rs_inmueble['in_estado'] == "3"){ echo "selected";} ?>>Vendido</option>
                                    <option value="4" <? if($rs_inmueble['in_estado'] == "4"){ echo "selected";} ?>>Suspendido</option>
                                  </select>
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><? if($rs_inmueble['in_estado'] < "4"){ ?>
                                    Esta visible.
                                 <? }else{ ?>
                                    <u>Atenci&oacute;n:</u> Recuerde cambiar el estado para que se muestre en el sitio.
                                  <? } ?></td>
                            </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Mostrar en el home: </td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_mostrar_home" type="radio" class="detalle_medio" value="1" <? if($rs_inmueble['in_mostrar_home'] == "1"){ echo "checked";} ?> />
                                    <label> Si</label>
                                    <input name="in_mostrar_home" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_mostrar_home'] == "2"){ echo "checked";} ?> />
                                  No</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Mostrar en Publicaciones:</td>
                                <td colspan="5" align="left" valign="middle" class="detalle_medio"><input name="in_mostrar_publicacion" type="radio" class="detalle_medio" value="1" <? if($rs_inmueble['in_mostrar_publicacion'] == "1"){ echo "checked";} ?> />
                                  <label> Si</label>
                                  <input name="in_mostrar_publicacion" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_mostrar_publicacion'] == "2"){ echo "checked";} ?> />
No</td>
                              </tr>
                              <tr>
                                <td valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td colspan="5" valign="top" class="detalle_medio"><span class="style2">
                                  <input name="Submit22" type="button" class="buttons detalle_medio_bold" onclick="validar_form_inmueble();" value="  &gt;&gt;  Guardar   " />
                                  &nbsp;&nbsp;</span></td>
                              </tr>
                          </table></td>
                        </tr>
                      </table>
                    <p>&nbsp;</p>
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFE2C6" class="titulo_medio_bold">Caracteristicas del inmueble:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF2E8"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                      <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Cochera:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_cochera" type="radio" class="detalle_medio" value="1" <? if($rs_inmueble['in_cochera'] == "1"){ echo "checked";} ?>  />
                                  <label> Si</label>
                                  <input name="in_cochera" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_cochera'] == "2"){ echo "checked";} ?>  />
No</td>
                              </tr>
                               <? } ?>
                               <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2){ ?>
                      <tr>
                        <td align="right" valign="middle" class="detalle_medio">Clasif. de Ambientes:</td>
                        <td width="77%" align="left" valign="middle" class="detalle_medio"><label>
                          <select name="in_ambiente" class="detalle_medio" id="in_ambiente">
                            <option value="">Seleccionar</option>
                            <option value="1" <? if($rs_inmueble['in_ambiente'] == 1){ echo "selected"; } ?>>1 Ambiente</option>
                            <option value="2" <? if($rs_inmueble['in_ambiente'] == 2){ echo "selected"; } ?>>2 Ambientes</option>
                            <option value="3" <? if($rs_inmueble['in_ambiente'] == 3){ echo "selected"; } ?>>3 Ambientes</option>
                            <option value="4" <? if($rs_inmueble['in_ambiente'] == 4){ echo "selected"; } ?>>3 Ambientes con dependencia</option>
                            <option value="5" <? if($rs_inmueble['in_ambiente'] == 5){ echo "selected"; } ?>>4 Ambientes</option>
                            <option value="6" <? if($rs_inmueble['in_ambiente'] == 6){ echo "selected"; } ?>>4 Ambientes con dependencia</option>
                            <option value="7" <? if($rs_inmueble['in_ambiente'] == 7){ echo "selected"; } ?>>5 Ambientes o m&aacute;s</option>
                          </select>
                        </label></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                        <td align="left" valign="middle" class="detalle_medio"><span style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666666;"><u>Clasif. de Ambientes:</u> Este campo no se visualiza en la ficha del inmueble, <em>es solo para clasificaci&oacute;n</em>. <u><br />
                          Atenci&oacute;n:</u> Si no selecciona la cantidad de ambientes, el inmueble no se podr&aacute; visualizar en el sitio.<br />
                          <br />
                        </span></td>
                      </tr>
                             <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 ||  $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Ba&uacute;l: </td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_baul" type="radio" class="detalle_medio" value="1" <? if($rs_inmueble['in_baul'] == "1"){ echo "checked";} ?>  />
                                  <label> Si</label>
                                  <input name="in_baul" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_baul'] == "2"){ echo "checked";} ?>  />
No</td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Antig&uuml;edad: </td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_antiguedad" type="text" class="detalle_medio" id="in_antiguedad" value="<? if($rs_inmueble['in_antiguedad']){ echo $rs_inmueble['in_antiguedad'];} ?>" size="7" />
a&ntilde;os</td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Telefono:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><label>
                                <input name="in_telefono" type="radio" class="detalle_medio" value="1" <? if($rs_inmueble['in_telefono'] == "1"){ echo "checked";} ?>  />
Si
<input name="in_telefono" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_telefono'] == "2"){ echo "checked";} ?>  />
No</label></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Cant. de Pisos:</td>
                              <td width="77%" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_pisos" type="text" class="detalle_medio" id="in_pisos" value="<? if($rs_inmueble['in_pisos']){ echo $rs_inmueble['in_pisos'];} ?>" size="7" />
                                </label></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Deptos por piso:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_deptospiso" type="text" class="detalle_medio" id="in_deptospiso" value="<? if($rs_inmueble['in_deptospiso']){ echo $rs_inmueble['in_deptospiso'];} ?>" size="7" /> </td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Cantidad Ascensores:</td>
                              <td width="77%" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_ascensores" type="text" class="detalle_medio" id="in_ascensores" value="<? if($rs_inmueble['in_ascensores']){ echo $rs_inmueble['in_ascensores'];} ?>" size="7" />
                                </label></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Aire Acondicionado: </td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_aireacondicionado" type="radio" class="detalle_medio" value="1" <? if($rs_inmueble['in_aireacondicionado'] == "1"){ echo "checked";} ?>  />
                                  <label> Si</label>
                                  <input name="in_aireacondicionado" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_aireacondicionado'] == "2"){ echo "checked";} ?>  />
No</td>
                            </tr>
                              <? } ?>
                              
							  <? if($rs_inmueble['in_tipoinmueble'] == 2 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Plantas: </td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_plantas" type="text" class="detalle_medio" id="in_plantas" value="<?= $rs_inmueble['in_plantas'] ?>" size="7" /></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 3 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Apto profesional:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_aptoprofesional" type="radio" class="detalle_medio" value="1" <? if($rs_inmueble['in_aptoprofesional'] == "1"){ echo "checked";} ?>  />
                                  <label> Si</label>
                                  <input name="in_aptoprofesional" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_aptoprofesional'] == "2"){ echo "checked";} ?>  />
No</td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Luminosidad:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_luminosidad" type="text" class="detalle_medio" id="in_luminosidad" value="<?= $rs_inmueble['in_luminosidad'] ?>" size="60" /></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Agua caliente: </td>
                              <td width="77%" align="left" valign="middle" class="detalle_medio"><label class="detalle_medio">
                                  <select name="in_agua_caliente" class="detalle_medio" id="in_agua_caliente">
                                    <option value="">Seleccionar</option>
                                    <option value="Individual"  <? if($rs_inmueble['in_agua_caliente'] == "Individual"){ echo "selected";} ?> >Individual</option>
                                    <option value="Central"  <? if($rs_inmueble['in_agua_caliente'] == "Central"){ echo "selected";} ?> >Central</option>
                                  </select>
                                </label></td>
                            </tr>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Calefacci&oacute;n:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><select name="in_calefaccion" class="detalle_medio" id="in_calefaccion">
                                  <option value="">Seleccionar</option>
                                  <option value="Individual"  <? if($rs_inmueble['in_calefaccion'] == "Individual"){ echo "selected";} ?> >Individual</option>
                                  <option value="Central"  <? if($rs_inmueble['in_calefaccion'] == "Central"){ echo "selected";} ?> >Central</option>
                                                                </select></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Expensas:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_expensas" type="text" class="detalle_medio" id="in_expensas" value="<?= $rs_inmueble['in_expensas'] ?>" size="7" /> 
                                  $</td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 4 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Apto Rubros: </td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_aptorubros" type="text" class="detalle_medio" id="in_aptorubros" value="<?= $rs_inmueble['in_aptorubros'] ?>" size="60" /></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 5 || $rs_inmueble['in_tipoinmueble'] == 4 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Medida Frente:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_medidafrente" type="text" class="detalle_medio" id="in_medidafrente" value="<?= $rs_inmueble['in_medidafrente'] ?>" size="7" /> 
                                  m</td>
                            </tr>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Medida Fondo: </td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_medidafondo" type="text" class="detalle_medio" id="in_medidafondo" value="<?= $rs_inmueble['in_medidafondo'] ?>" size="7" /> 
                                  m</td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 4 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Posici&oacute;n:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><select name="in_posicion" class="detalle_medio" id="in_posicion">
                                  <option>Seleccionar</option>
                                  <option value="1" <? if($rs_inmueble['in_posicion'] == "1"){ echo "selected";} ?> >Subsuelo</option>
                                  <option value="2" <? if($rs_inmueble['in_posicion'] == "2"){ echo "selected";} ?> >Entre Piso</option>
                                  <option value="3" <? if($rs_inmueble['in_posicion'] == "3"){ echo "selected";} ?> >Planta Baja</option>
                                                                </select></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 5 || $rs_inmueble['in_tipoinmueble'] == 4 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Zonificaci&oacute;n:</td>
                              <td width="77%" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_zonificacion" type="text" class="detalle_medio" id="in_zonificacion" value="<?= $rs_inmueble['in_zonificacion'] ?>" size="7" />
                                </label></td>
                            </tr>
                              <? } ?>
                              
                              <? if($rs_inmueble['in_tipoinmueble'] == 5 ){ ?>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">FOT:</td>
                              <td width="77%" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="in_fot" type="text" class="detalle_medio" id="in_fot" value="<?= $rs_inmueble['in_fot'] ?>" size="7" />
                                </label></td>
                            </tr>
                              <tr>
                                <td width="23%" align="right" valign="middle" class="detalle_medio">Apto para:</td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="in_aptopara" type="text" class="detalle_medio" id="in_aptopara" value="<?= $rs_inmueble['in_aptopara'] ?>" size="7" /> 
                                m2</td>
                            </tr>
                              <? } ?>
                              <? if($rs_inmueble['in_tipoinmueble'] != 7 ){ ?>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td valign="top" class="detalle_medio"><span class="style2">
                                  <input name="Submit223" type="button" class="buttons detalle_medio_bold" onclick="validar_form_inmueble();" value="  &gt;&gt;  Guardar   " />
                                  &nbsp;&nbsp;</span></td>
                              </tr>
                              <? } ?>
                          </table></td>
                        </tr>
                      </table>
                  </form>
                      <p></p>
                    <? if($rs_inmueble['in_tipoinmueble'] == 1 || $rs_inmueble['in_tipoinmueble'] == 2 || $rs_inmueble['in_tipoinmueble'] == 3 || $rs_inmueble['in_tipoinmueble'] == 4 || $rs_inmueble['in_tipoinmueble'] == 6 ){ ?>
                    <form name="form_detalle" id="form_detalle" method="post" action="">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFE2C6" class="titulo_medio_bold"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr>
                                  <td>Detalle de ambientes: <span class="style2">
                                  <input name="accion_detalle" type="hidden" id="accion_detalle" value="-1" />
                                  <input name="eliminar_detalle" type="hidden" id="eliminar_detalle" value="" />
                                  <a name="03" id="03"></a></span></td>
                                  <td align="right" valign="middle">&nbsp;</td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#FFF2E8"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td width="22%" align="right" valign="middle" class="detalle_medio">Idioma / Language:</td>
    <td width="78%" align="left" valign="middle" class="detalle_medio"><label>
      <input name="in_ambiente_idioma" type="radio" class="detalle_medio" value="1" checked="checked" />
      <img src="../../imagen/flag_spain.jpg" width="18" height="12" />
<input name="in_ambiente_idioma" type="radio" class="detalle_medio" value="2" />
<img src="../../imagen/flag_england.jpg" width="17" height="12" />    </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Tipo: </td>
                                  <td align="left" valign="middle" class="detalle_medio"><input name="in_ambiente_tipo" type="text" class="detalle_medio" id="in_ambiente_tipo" size="50" /></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Medidas Ambiente:</td>
                                  <td align="left" valign="middle" class="detalle_medio"><input name="in_ambiente_medida" type="text" class="detalle_medio" id="in_ambiente_medida" size="7" /> 
                                  m</td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Placard:</td>
                                  <td align="left" valign="middle" class="detalle_medio"><input name="in_ambiente_placard" type="radio" class="detalle_medio" value="1" checked="checked" <? if($rs_inmueble['in_aptoprofesional'] == "1"){ echo "checked";} ?>  />
                                    <label> Si</label>
                                    <input name="in_ambiente_placard" type="radio" class="detalle_medio" value="2" <? if($rs_inmueble['in_aptoprofesional'] == "2"){ echo "checked";} ?>  />
No</td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio">Terminaci&oacute;n Ambiente:</td>
                                  <td align="left" valign="middle" class="detalle_medio"><label>
                                    <textarea name="in_ambiente_terminacion" cols="52" rows="4" class="detalle_medio" id="in_ambiente_terminacion"></textarea>
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td align="left" valign="middle" class="detalle_medio"><span class="style2">
                                    <input name="Submit224" type="button" class="buttons detalle_medio_bold" onclick="validar_form_detalle();" value="  &gt;&gt;  Insertar   " />
                                  </span></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio"></td>
                                  <td align="left" valign="middle" class="detalle_medio"><hr width="100%" size="1" /></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio"></td>
                                  <td align="left" valign="top" class="detalle_medio"><? 
										$query="SELECT COUNT(*) 
										 		FROM in_detalle
												WHERE in_idinmueble = '$in_idinmueble'";
												$result = mysql_fetch_assoc(mysql_query($query));
										
										if($result > 0){
										?>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="7">
                                      
                                      <tr>
                                        <td width="6%" align="left" valign="top" bgcolor="<?= $color ?>" class="detalle_medio">&nbsp;</td>
                                        <td width="14%" align="left" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><span class="detalle_medio_bold">Tipo</span></td>
                                        <td width="19%" align="center" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><span class="detalle_medio_bold">Medidas Ambiente</span></td>
                                        <td width="14%" align="center" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><span class="detalle_medio_bold">Placard</span></td>
                                        <td width="42%" align="left" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><span class="detalle_medio_bold">Terminaci&oacute;n Ambiente</span></td>
                                        <td width="5%" align="center" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><span class="detalle_medio_bold">&nbsp;</span></td>
                                      </tr>
                                      <?
										 $c=1;
										 //CONSULTA:
										 $query="SELECT * 
										 		 FROM in_detalle
												 WHERE in_idinmueble = '$in_idinmueble'";
												 $result = mysql_query($query);
												 while($rs_detalle = mysql_fetch_assoc($result)){
												 	
												if($c==1){
													$color="#FFE9D2";
													$c=2;
												}else{
													$color="#FFEEDD";
													$c=1;
												}
												 
										 ?>
                                      <tr>
                                        <td align="left" valign="top" bgcolor="<?= $color ?>" class="detalle_medio">
                                        <? if($rs_detalle['in_ambiente_idioma'] == 1){ ?>
                                        <img src="../../imagen/flag_spain.jpg" width="18" height="12" />
                                        <? }else{ ?>
                                        <img src="../../imagen/flag_england.jpg" width="17" height="12" />
                                        <? } ?>
                                        </td>
                                        <td align="left" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><?= $rs_detalle['in_ambiente_tipo'] ?></td>
                                        <td width="19%" align="center" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><?= $rs_detalle['in_ambiente_medida'] ?></td>
                                        <td width="14%" align="center" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><? if($rs_detalle['in_ambiente_placard'] == 1){ echo "Sí"; }else{ echo "No."; } ?></td>
                                        <td width="42%" align="left" valign="top" bgcolor="<?= $color ?>" class="detalle_medio"><?= $rs_detalle['in_ambiente_terminacion'] ?></td>
                                        <td width="5%" align="center" valign="top" bgcolor="<?= $color ?>" class="detalle_medio" ><a href="javascript:eliminar_detalle(<?= $rs_detalle['in_iddetalle'] ?>);"><img src="../../imagen/iconos/cross.png" alt="Quitar" width="16" height="16" border="0" /></a></td>
                                      </tr>
                                      <? } ?>
                                    </table>
                                  <? } ?></td>
                                </tr>
                                <tr>
                                  <td valign="top" class="detalle_medio_bold">&nbsp;</td>
                                  <td valign="top" class="detalle_medio">&nbsp;</td>
                                </tr>
                            </table></td>
                          </tr>
                        </table>
                    </form>
                    <p></p>
                    <? } ?>
                    <form action="" method="post" enctype="multipart/form-data" name="form_foto" id="form_foto">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFBBBB" class="titulo_medio_bold"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                  <tr>
                                    <td>Im&aacute;genes: Adherir fotos <a name="04" id="04"></a>
                                      <input name="accion_foto" type="hidden" id="accion_foto" value="" />
                                      <input name="eliminar_foto" type="hidden" id="eliminar_foto" value="" /></td>
                                    <td align="right" valign="middle"><a id="slideout"  href="#"><img src="../../imagen/linkup.gif" width="9" height="9" border="0" /></a>&nbsp;<a id="slidein" href="#"><img src="../../imagen/linkdown.gif" width="9" height="9" border="0" /></a>&nbsp;</td>
                                  </tr>
                                </table>
                            </td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFE1E1">
							<div id="fotos">
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
                                <tr>
                                  <td width="23%" align="right" valign="middle" class="detalle_medio">Foto:</td>
                                  <td ><label>
                                    <input name="in_foto2" type="file" class="detalle_medio" id="in_foto2" size="47" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Nombre:</td>
                                  <td class="detalle_medio"><input name="in_nombre_foto" type="text" class="detalle_medio" id="in_nombre_foto" size="63" /></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td><input name="Submit23" type="button" class="buttons detalle_medio_bold" onclick="javascript:validar_form_foto();" value="  &gt;&gt;  Agregar   " /></td>
                                </tr>
                                <tr>
                                  <td width="19%">&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="30" align="right" valign="top" class="detalle_medio">Fotos ingresadas: </td>
                                  <td rowspan="2"><? //IN_FOTO 
															$query_foto_cant = "SELECT COUNT(in_idfoto)
															FROM in_foto
															WHERE in_idinmueble = '$in_idinmueble'";
															$rs_foto_cant = mysql_fetch_row(mysql_query($query_foto_cant));
															if($rs_foto_cant[0]>0){
															?>
                                      <table width="170" border="0" cellpadding="0" cellspacing="0" class="Tabla_rightspace">
                                        <tr valign="top">
                                          <? 
						$foto_extra_cont = '0';
						$query_foto = "SELECT *
						FROM in_foto
						WHERE in_idinmueble = '$in_idinmueble'
						ORDER BY in_idfoto ASC";
						$result_foto = mysql_query($query_foto);
						$vuelta_foto = 1;
						while($rs_foto = mysql_fetch_assoc($result_foto)){
								$hay_fotos = true;
								$foto_extra_cont++;
							
							 ?>
                                          <td width="170" align="left" valign="top" class="ejemplo_12px">
										  <table width="150" border="0" cellpadding="3" cellspacing="0" bordercolor="#FFBBBB" class="Tabla_estilo01" style="margin-right:15px;margin-bottom:15px; border:solid #FFBBBB 1px;;">
                                          <tr>
                                                <td align="right" valign="middle" bgcolor="#FFBBBB"><a href="javascript:eliminar_foto(<?= $rs_foto['in_idfoto'] ?>);"><img src="../../imagen/iconos/cross.png" width="16" height="16" border="0" align="right" /></a></td>
                                            </tr>
                                              <tr>
                                                <td height="100" align="center" valign="bottom" bgcolor="#FFBBBB"><table width="10" border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#FFE1E1">
                                                    <tr>
                                                      <td><?
										if($rs_foto['in_foto'] == ''){
											$foto_xtra =& new obj0001('0','../swf/','no_disponible.swf',$ancho_maximo_mini,'','','','','','','wmode=Opaque',''); 
										}else{
											$foto_xtra =& new obj0001('0',$foto_ruta_chica_foto,$rs_foto['in_foto'],'150','','','',$foto_ruta_grande_foto.$rs_foto['in_foto'],'_blank','','',''); 
										}; 
										?></td>
                                                    </tr>
                                                  </table></td>
                                              </tr>
                                              <? if($rs_foto['in_nombre_foto'] != ''){ ?>
                                              <tr>
                                                <td width="150" align="left" bgcolor="#FFBBBB" class="detalle_medio" ><?= $rs_foto['in_nombre_foto'] ?></td>
                                              </tr>
                                              <? } ?>
                                          </table></td>
                                          <?	
								if($vuelta_foto == 3){ 
									echo "</tr><tr>";
									$vuelta_foto = 1;
								}else{
									$vuelta_foto++;
								};
							 
							 }; //FIN WHILE 
											 
							?>
                                        </tr>
                                    </table>
                                    <?  }else{ ?>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="8">
                                        <tr>
                                          <td align="center" valign="middle" class="detalle_medio_bold">No se han cargado fotos.</td>
                                        </tr>
                                      </table>
                                    <? }; // FIN IF CANT
						// FIN PRODUCTO FOTO ?></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                </tr>
                            </table>
							</div>
							</td>
                          </tr>
                        </table>
                    </form>
                    <p></p>
                    <form action="" method="post" name="form_plano" id="form_plano" enctype="multipart/form-data" >
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#DADADA" class="titulo_medio_bold"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                  <tr>
                                    <td width="71%">Im&aacute;genes: Adherir planos <a name="05" id="05"></a>
                                      <input name="accion_plano" type="hidden" id="accion_plano" value="" />
                                      <input name="eliminar_plano" type="hidden" id="eliminar_plano" value="" /></td>
                                    <td width="29%" align="right" valign="middle"><a id="slideout2" href="#"><img src="../../imagen/linkup.gif" width="9" height="9" border="0" /></a>&nbsp;<a id="slidein2" href="#"><img src="../../imagen/linkdown.gif" width="9" height="9" border="0" /></a>&nbsp;</td>
                                  </tr>
                                </table></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#EAEAEA">
							<div id="planos"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                <tr>
                                  <td width="23%" align="right" valign="middle" class="detalle_medio">Plano:</td>
                                  <td ><label>
                                    <input name="in_plano" type="file" class="detalle_medio" id="in_plano" size="47" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Nombre de la vista:</td>
                                  <td class="detalle_medio"><input name="in_nombre_plano" type="text" class="detalle_medio" id="in_nombre_plano" size="63" /></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td><input name="Submit232" type="button" class="buttons detalle_medio_bold" onclick="javascript:validar_form_plano();" value="  &gt;&gt;  Agregar   " /></td>
                                </tr>
                                <tr>
                                  <td width="19%">&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="30" align="right" valign="top" class="detalle_medio">Planos ingresados: </td>
                                  <td rowspan="2"><? //IN_PLANO
															$query_foto_cant = "SELECT COUNT(in_idplano)
															FROM in_plano
															WHERE in_idinmueble = '$in_idinmueble'";
															$rs_foto_cant = mysql_fetch_row(mysql_query($query_foto_cant));
															if($rs_foto_cant[0]>0){
															?>
                                      <table width="150" border="0" cellpadding="0" cellspacing="0" class="Tabla_rightspace">
                                        <tr valign="top">
                                          <? 
						$foto_extra_cont = '0';
						$query_foto = "SELECT *
						FROM in_plano
						WHERE in_idinmueble = '$in_idinmueble'
						ORDER BY in_idplano ASC";
						$result_foto = mysql_query($query_foto);
						$vuelta_foto = 1;
						while($rs_foto = mysql_fetch_assoc($result_foto)){
								$hay_fotos = true;
								$foto_extra_cont++;
							
							 ?>
                                          <td align="left" valign="top" class="ejemplo_12px"><table width="150" border="1" cellpadding="2" cellspacing="0" bordercolor="#DADADA" class="Tabla_estilo01" style="margin-right:15px;margin-bottom:15px;">
                                              <tr>
                                                <td align="right" valign="middle" bgcolor="#DADADA"><a href="javascript:eliminar_plano(<?= $rs_foto['in_idplano'] ?>);"><img src="../../imagen/iconos/cross.png" width="16" height="16" border="0" align="right" /></a></td>
                                            </tr>
                                              <tr>
                                                <td height="100" align="center" valign="bottom" bgcolor="#DADADA"><table width="10" border="1" cellpadding="1" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
                                                  <tr>
                                                    <td><?
										if($rs_foto['in_plano'] == ''){
											$foto_xtra =& new obj0001('0','../swf/','no_disponible.swf',$ancho_maximo_mini,'','','','','','','wmode=Opaque',''); 
										}else{
											$foto_xtra =& new obj0001('0',$foto_ruta_chica_plano,$rs_foto['in_plano'],'150','','','',$foto_ruta_grande_plano.$rs_foto['in_plano'],'_blank','','',''); 
										}; 
										?></td>
                                                  </tr>
                                                </table>
                                                </td>
                                              </tr>
                                              <? if($rs_foto['in_nombre_plano'] != ''){ ?>
                                              <tr>
                                                <td width="150" align="left" bgcolor="#DADADA" class="detalle_medio" ><?= $rs_foto['in_nombre_plano'] ?></td>
                                              </tr>
                                              <? } ?>
                                          </table></td>
                                          <?	
								if($vuelta_foto == 3){ 
									echo "</tr><tr>";
									$vuelta_foto = 1;
								}else{
									$vuelta_foto++;
								};
							 
							 }; //FIN WHILE 
											 
							?>
                                        </tr>
                                      </table>
                                    <?  }else{ ?>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="8">
                                        <tr>
                                          <td align="center" valign="middle" class="detalle_medio_bold">No se han cargado planos.</td>
                                        </tr>
                                      </table>
                                    <? }; // FIN IF CANT
						// FIN PRODUCTO FOTO ?></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                </tr>
                            </table></div></td>
                          </tr>
                        </table>
                    </form>
                    <p></p>
                    <form action="" method="post" name="form_mapa" id="form_mapa" enctype="multipart/form-data" >
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#00FFFF" class="titulo_medio_bold"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                  <tr>
                                    <td>Im&aacute;genes: Adherir mapas <a name="06" id="06"></a>
                                      <input name="accion_mapa" type="hidden" id="accion_mapa" value="" />
                                      <input name="eliminar_mapa" type="hidden" id="eliminar_mapa" value="" /></td>
                                    <td align="right" valign="middle"><a id="slideout3"  href="#"><img src="../../imagen/linkup.gif" width="9" height="9" border="0" /></a>&nbsp;<a id="slidein3" href="#"><img src="../../imagen/linkdown.gif" width="9" height="9" border="0" /></a>&nbsp;</td>
                                  </tr>
                                </table></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#A8FFFF">
							<div id="mapas">
							<table width="100%" border="0" cellspacing="0" cellpadding="4">
                                <tr>
                                  <td width="23%" align="right" valign="middle" class="detalle_medio">Mapa:</td>
                                  <td ><label>
                                    <input name="in_mapa" type="file" class="detalle_medio" id="in_mapa" size="47" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                  <td class="detalle_medio"><input name="in_nombre_mapa" type="text" class="detalle_medio" id="in_nombre_mapa" size="63" /></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td><input name="Submit2322" type="button" class="buttons detalle_medio_bold" onclick="javascript:validar_form_mapa();" value="  &gt;&gt;  Agregar   " /></td>
                                </tr>
                                <tr>
                                  <td width="19%">&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="30" align="right" valign="top" class="detalle_medio">Mapas ingresados: </td>
                                  <td rowspan="2"><? //IN_MAPA
															$query_foto_cant = "SELECT COUNT(in_idmapa)
															FROM in_mapa
															WHERE in_idinmueble = '$in_idinmueble'";
															$rs_foto_cant = mysql_fetch_row(mysql_query($query_foto_cant));
															if($rs_foto_cant[0]>0){
															?>
                                      <table width="150" border="0" cellpadding="0" cellspacing="0" class="Tabla_rightspace">
                                        <tr valign="top">
                                          <? 
						$foto_extra_cont = '0';
						$query_foto = "SELECT *
						FROM in_mapa
						WHERE in_idinmueble = '$in_idinmueble'
						ORDER BY in_idmapa ASC";
						$result_foto = mysql_query($query_foto);
						$vuelta_foto = 1;
						while($rs_foto = mysql_fetch_assoc($result_foto)){
								$hay_fotos = true;
								$foto_extra_cont++;
							
							 ?>
                                          <td align="left" valign="top" class="ejemplo_12px"><table width="150" border="1" cellpadding="2" cellspacing="0" bordercolor="#00FFFF" class="Tabla_estilo01" style="margin-right:15px;margin-bottom:15px;">
                                              <tr>
                                                <td align="right" valign="middle" bgcolor="#00FFFF"><a href="javascript:eliminar_mapa(<?= $rs_foto['in_idmapa'] ?>);"><img src="../../imagen/iconos/cross.png" width="16" height="16" border="0" align="right" /></a></td>
                                            </tr>
                                              <tr>
                                                <td height="100" align="center" valign="bottom" bgcolor="#00FFFF"><table width="10" border="1" cellpadding="1" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#A8FFFF">
                                                  <tr>
                                                    <td>
													<?
										if($rs_foto['in_mapa'] == ''){
											$foto_xtra =& new obj0001('0','../swf/','no_disponible.swf',$ancho_maximo_mini,'','','','','','','wmode=Opaque',''); 
										}else{
											$foto_xtra =& new obj0001('0',$foto_ruta_chica_mapa,$rs_foto['in_mapa'],'150','','','',$foto_ruta_grande_mapa.$rs_foto['in_mapa'],'_blank','','',''); 
										}; 
										?></td>
                                                  </tr>
                                                </table>
                                                </td>
                                              </tr>
                                              <? if($rs_foto['in_nombre_mapa'] != ''){ ?>
                                              <tr>
                                                <td width="150" align="left" bgcolor="#00FFFF" class="detalle_medio" ><?= $rs_foto['in_nombre_mapa'] ?></td>
                                              </tr>
                                              <? } ?>
                                          </table></td>
                                          <?	
								if($vuelta_foto == 3){ 
									echo "</tr><tr>";
									$vuelta_foto = 1;
								}else{
									$vuelta_foto++;
								};
							 
							 }; //FIN WHILE 
											 
							?>
                                        </tr>
                                      </table>
                                    <?  }else{ ?>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="8">
                                        <tr>
                                          <td align="center" valign="middle" class="detalle_medio_bold">No se han cargado mapas.</td>
                                        </tr>
                                      </table>
                                    <? }; // FIN IF CANT
						// FIN PRODUCTO FOTO ?></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                </tr>
                            </table>
							</div></td>
                          </tr>
                        </table>
                    </form></td>
                </tr>
            </table></td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>

</body>
</html>