<? 
	include ("../../0_mysql.php");
	include ("../0_includes/0_crear_miniatura.php"); 
	include ("../0_includes/0_clean_string.php"); 
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = " AND idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
		$filtro_sede = '';
	}
	
	//CARGO PARÁMETROS DE PRODUCTO
	$query_par = "SELECT *
	FROM producto_parametro
	WHERE idproducto_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
	//VARIABLES
	$accion = $_POST['accion'];
	$idproducto = $_GET['idproducto'];
	$idcarpeta = $_GET['idcarpeta'];
	$forma = $_GET['forma'];
	
	$dia_fecha_alta = $_POST['dia_fecha_alta'];
	$mes_fecha_alta = $_POST['mes_fecha_alta'];
	$ano_fecha_alta = $_POST['ano_fecha_alta'];
	$fecha_alta = $ano_fecha_alta."-".$mes_fecha_alta."-".$dia_fecha_alta;
	
	$cod_interno = $_POST['cod_interno'];
	$cod_fabricante = $_POST['cod_fabricante'];
	$idproducto_marca = $_POST['idproducto_marca'];
	$estado = $_POST['estado'];
	$novedad = $_POST['novedad'];
	$compra = $_POST['compra'];
	$oferta = $_POST['oferta'];
	$destacado = $_POST['destacado'];
	$discontinuado = $_POST['discontinuado'];
	$precio = $_POST['precio'];
	$idca_iva = $_POST['idca_iva'];
	
	if(!isset($estado)){
		$estado = 2;
	}
	
	//ACTUALIZO FECHA MODIFICACION
	if($accion != ""){
		$fecha_hoy = date("Y-m-d");
		$query_mod_prod = "UPDATE producto SET fecha_modificacion = '$fecha_hoy' WHERE idproducto = '$idproducto' ";
		mysql_query($query_mod_prod);
	}
	
	//Sistema de selector de carpeta
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];
	
	if($mod6_idcarpeta4){
		$mod6_sel_idcarpeta = $mod6_idcarpeta4;
	}else{
		if($mod6_idcarpeta3){
			$mod6_sel_idcarpeta = $mod6_idcarpeta3;
		}else{
			if($mod6_idcarpeta2){
				$mod6_sel_idcarpeta = $mod6_idcarpeta2;
			}else{
				if($mod6_idcarpeta){
					$mod6_sel_idcarpeta = $mod6_idcarpeta;
				}
			}	
		}	
	}

	
	//Variables de la foto	
	$foto = $_POST['foto'];// es la imagen a ingresar
	$foto_chica_ancho = $rs_parametro['foto_chica'];// ancho maximo de la foto en tamaño chica	
	$foto_mediana_ancho = $rs_parametro['foto_mediana'];// ancho maximo de la foto en tamaño mediana
	$foto_grande_ancho = $rs_parametro['foto_grande'];// ancho maximo de la foto en tamaño grande
	$foto_ruta_chica = "../../../imagen/producto/chica/"; // la ruta donde se va a guardar la foto chica
	$foto_ruta_mediana = "../../../imagen/producto/mediana/"; // la ruta donde se va a guardar la foto mediana
	$foto_ruta_grande = "../../../imagen/producto/grande/"; // la ruta donde se va a guardar la foto grande	
	$ruta_descarga = "../../../descarga/";	
	
	//Variables de la fotos extra	
	$foto_extra_chica_ancho = $rs_parametro['foto_extra_chica'];// ancho maximo de la foto extra en tamaño chica	
	$foto_extra_grande_ancho = $rs_parametro['foto_extra_grande'];// ancho maximo de la foto extra en tamaño grande	
	$foto_extra_ruta_chica = "../../../imagen/producto/extra_chica/"; // la ruta donde se va a guardar la foto extra chica
	$foto_extra_ruta_grande = "../../../imagen/producto/extra_grande/"; // la ruta donde se va a guardar la foto extra grande
	$foto_extra = $_POST['foto_extra'];// es la imagen a ingresar
	$foto_extra_titulo = $_POST['foto_extra_titulo']; // titulo de la foto extra	
	$foto_extra_tipo = $_POST['foto_extra_tipo']; //para determinar la posicion 1 horizontal, 2 vertical, 3 dentro del detalle
	$foto_extra_ididioma = $_POST['foto_extra_ididioma']; // idioma en el que va a aparecer la imagen extra
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$foto_extra_orden = $_POST['foto_extra_orden'];
	
	//Cambia el tipo de foto extra
	$idproducto_foto_sel = $_GET['idproducto_foto_sel'];
	$foto_extra_tipo_sel = $_GET['foto_extra_tipo_sel'];
	
	//Reordena las fotos extras
	$foto_extra_posicion_row = $_POST['foto_extra_posicion_row'];
	$foto_extra_titulo_row = $_POST['foto_extra_titulo_row'];
	$foto_extra_cont = $_POST['foto_extra_cont'];
	$idproducto_foto_row = $_POST['idproducto_foto_row'];

		
	// modificacion del producto:
	if($accion == "modificar"){
			
			// INCORPORACION DE FOTO
			if ($_FILES['foto']['name'] != ''){
		
				$archivo_ext = substr($_FILES['foto']['name'],-4);
				$archivo_nombre = substr($_FILES['foto']['name'],0,strrpos($_FILES['foto']['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
				
					$querysel = "SELECT foto FROM producto WHERE idproducto = '$idproducto' ";
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
					
				$foto =  $idproducto . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
				
				if (!copy($_FILES['foto']['tmp_name'], $foto_ruta_grande.$foto)){ //si hay error en la copia de la foto
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
							if($_POST['foto_grande_ancho_seleccionado']){
								$foto_grande_ancho_sel = $_POST['foto_grande_ancho_seleccionado'];
							}else{
								$foto_grande_ancho_sel = $foto_grande_ancho;
							}
							if($_POST['foto_mediana_ancho_seleccionado']){
								$foto_mediana_ancho_sel = $_POST['foto_mediana_ancho_seleccionado'];
							}else{
								$foto_mediana_ancho_sel = $foto_mediana_ancho;
							}
							if($_POST['foto_chica_ancho_seleccionado']){
								$foto_chica_ancho_sel = $_POST['foto_chica_ancho_seleccionado'];
							}else{
								$foto_chica_ancho_sel = $foto_chica_ancho;
							}
							
							//ACHICAR IMAGEN AL ANCHO MÁXIMO de la foto grande:
							if ($imagesize[0] > $foto_grande_ancho){
								$alto_nuevo = ceil($foto_grande_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_ruta_grande.$foto, $foto_grande_ancho_sel, $alto_nuevo, $foto_ruta_grande);
							};
							
							//ACHICAR IMAGEN AL ANCHO MÁXIMO de la foto mediana:	
							if ($imagesize[0] > $foto_mediana_ancho_sel){						
								$alto_nuevo = ceil($foto_mediana_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_ruta_grande.$foto, $foto_mediana_ancho_sel, $alto_nuevo, $foto_ruta_mediana);
							}else{
								crear_miniatura($foto_ruta_grande.$foto, $imagesize[0], $imagesize[1], $foto_ruta_mediana);
							};
													
							//CREAR MINI AL ANCHO MÁXIMO chico:
							if ($imagesize[0] > $foto_chica_ancho_sel){
								$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_ruta_mediana.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica);
							}else{
								crear_miniatura($foto_ruta_mediana.$foto, $imagesize[0], $imagesize[1], $foto_ruta_chica);
							};
	
					
							//ingreso de foto en tabla producto
							$query_upd = "UPDATE producto SET 
							foto = '$foto' 	
							WHERE idproducto = '$idproducto'
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
					
			//INGRESO DE LOS DATOS EN LA TABLA PRODUCTOS
			$query_modficacion = "UPDATE producto SET 
			  fecha_alta = '$fecha_alta'
			, cod_interno = '$cod_interno'
			, cod_fabricante = '$cod_fabricante'
			, estado = '$estado'
			, oferta = '$oferta'
			, compra = '$compra'
			, novedad = '$novedad'
			, destacado = '$destacado'
			, discontinuado = '$discontinuado'
			, idproducto_marca = '$idproducto_marca'
			, precio = '$precio'
			, idca_iva = '$idca_iva'
			WHERE idproducto = '$idproducto'
			LIMIT 1";
			
			mysql_query($query_modficacion);
			
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";		
	};
	
	//ELIMINAR FOTO PRINCIPAL
	if ($accion == "eliminar_foto"){
				
					$querysel = "SELECT foto FROM producto WHERE idproducto = '$idproducto' ";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
					
					if ( $rowfoto[0] ){
						if (file_exists($ruta_foto.$rowfoto[0])){
							unlink ($ruta_foto.$rowfoto[0]);
						}
						if (file_exists($ruta_foto.$carpeta_mini.$rowfoto[0])){
							unlink ($ruta_foto.$carpeta_mini.$rowfoto[0]);
						}
						if (file_exists($ruta_foto.$carpeta_grande.$rowfoto[0])){
							unlink ($ruta_foto.$carpeta_grande.$rowfoto[0]);
						}
					}
	
					$query_upd = "UPDATE producto SET
					foto = ''
					WHERE idproducto = '$idproducto'
					LIMIT 1";
					mysql_query($query_upd);
					
					echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	}
	
	//CAMBIAR ESTADO IDIOMA	
	$estado_idioma = $_POST['estado_idioma'];
	$ididioma = $_POST['ididioma'];
	
	if($estado_idioma != "" && $ididioma != ""){
		$query_estado = "UPDATE producto_idioma_dato
		SET estado = '$estado_idioma'
		WHERE idproducto = '$idproducto' AND ididioma	= '$ididioma'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	
	}
	
	//CAMBIAR ESTADO DESCARGA	
	if($estado_descarga != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET estado = '$estado_descarga'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	
	}
	
	//CAMBIAR RESTRINGISDO DESCARGA	
	if($estado_restringido != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET restringido = '$estado_restringido'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	
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
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	
	}
	
	//MODIFICAR		
	if($accion == 'copiar_carpeta'){		
		
		$query_adj = "INSERT INTO producto_carpeta
		(idcarpeta, idproducto) 
		VALUES
		('$mod6_sel_idcarpeta', '$idproducto')
		";
		mysql_query($query_adj);	
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
	}
	
	//APLICAR CAMBIOS EN SECCION_CARPETA
	if($accion == 'aplicar_cambios_carpeta'){
		
		$orden_row = $_POST['orden_row'];
		$idcarpeta_row = $_POST['idcarpeta_row'];
		$cont = $_POST['cont_carpeta'];
		
		for($i=0;$i<$cont;$i++){
			
			$query = "UPDATE producto_carpeta
			SET orden = '$orden_row[$i]'
			WHERE idproducto = '$idproducto' AND idcarpeta = '$idcarpeta_row[$i]'";
			mysql_query($query);
			
		}
	
	}
	
	//ELIMINAR COPIA EN CARPETA
	if($accion == "eliminar_copia_carpeta"){
		$idcarpeta_copia = $_POST['idcarpeta_copia'];
		$query_del = "DELETE FROM producto_carpeta
		WHERE idcarpeta = '$idcarpeta_copia' AND idproducto = '$idproducto'";
		mysql_query($query_del);
	}
	
	//ELIMINAR FOTO EXTRA
	if ($accion == "eliminar_foto_extra"){
				
				$eliminar = $_POST['eliminar'];
				$querysel = "SELECT foto FROM producto_foto WHERE idproducto_foto = '$eliminar' ";
				$rowfoto = mysql_fetch_row(mysql_query($querysel));
								
				if ( $rowfoto[0] ){
					if (file_exists($foto_extra_ruta_grande.$rowfoto[0])){
						unlink ($foto_extra_ruta_grande.$rowfoto[0]);
					}
					if (file_exists($foto_extra_ruta_chica.$rowfoto[0])){
						unlink ($foto_extra_ruta_chica.$rowfoto[0]);
					}
				}
				
				$query_del = "DELETE FROM producto_foto WHERE idproducto_foto = '$eliminar' ";
				mysql_query($query_del);
				
				echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
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
			
				$foto =  $idproducto .'-'.$rs_idioma['reconocimiento_idioma'].'-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
				
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
							$alto_nuevo = ceil($foto_extra_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
							crear_miniatura($foto_extra_ruta_grande.$foto, $foto_extra_chica_ancho_sel, $alto_nuevo, $foto_extra_ruta_chica);
							
							
							$query_ins = "INSERT INTO producto_foto 
							(idproducto, foto, titulo,foto_extra_tipo, ididioma, orden) 
							VALUES 
							('$idproducto','$foto', '$foto_extra_titulo', '$foto_extra_tipo', '$foto_extra_ididioma[$c]', '$foto_extra_orden')";
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
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."');</script>";
			
	};
	
	//MODIFICAR SEDE
	if($accion == "modificar_sede"){
		
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		$query_delete = "DELETE FROM producto_sede 
		WHERE idproducto = '$idproducto' $filtro_sede";
		mysql_query($query_delete);
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO producto_sede(
			  idproducto
			, idsede
			)VALUES(
			  '$idproducto'
			, '$sede[$c]'
			)";
			mysql_query($query_insert);

		}
	}

	//Cambia el tipo de foto extra
	if ($idproducto_foto_sel && $foto_extra_tipo_sel){
			$query_mod9_upd = "UPDATE producto_foto 
			SET foto_extra_tipo = '$foto_extra_tipo_sel'
			WHERE idproducto_foto = $idproducto_foto_sel
			";
			mysql_query($query_mod9_upd);
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."')</script>";
	}

	//REORDENA LAS FOTOS EXTRA
	if($accion == "foto_extra_reordenar"){
	
		//ELIMINAR SELECCION DE FOTOS EXTRAS
		$checkbox_row = $_POST['checkbox_row'];
		
		for($i=0;$i<=$foto_extra_cont;$i++){	
		
			if($checkbox_row[$i] != ""){
				
				$querysel = "SELECT foto FROM producto_foto WHERE idproducto_foto = '$checkbox_row[$i]'";
				$rowfoto = mysql_fetch_row(mysql_query($querysel));
								
				if ( $rowfoto[0] ){
					if (file_exists($foto_extra_ruta_grande.$rowfoto[0])){
						unlink ($foto_extra_ruta_grande.$rowfoto[0]);
					}
					if (file_exists($foto_extra_ruta_chica.$rowfoto[0])){
						unlink ($foto_extra_ruta_chica.$rowfoto[0]);
					}
				}
				
				$query_del = "DELETE FROM producto_foto WHERE idproducto_foto = '$checkbox_row[$i]'";
				mysql_query($query_del);
			}
			
		}
	
		$foto_extra_columna = $_POST['foto_extra_columna'];
		
		$queryup = "UPDATE producto
		SET foto_extra_columna ='$foto_extra_columna'
		WHERE idproducto = '$idproducto'";
		mysql_query($queryup);
			
		for ($i=1; $i< $foto_extra_cont+1 ; $i++){
			$queryup = "UPDATE producto_foto
			SET orden ='$foto_extra_orden_row[$i]'
			, titulo = '$foto_extra_titulo_row[$i]'
			WHERE idproducto_foto = $idproducto_foto_row[$i]";
			mysql_query($queryup);
		}
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."')</script>";
	}

	//OBTENER DATOS DEL PRODUCTO:
	$query_producto = "SELECT * 
	FROM producto 
	WHERE idproducto = '$idproducto'";
	$rs_producto = mysql_fetch_assoc(mysql_query($query_producto));
	
	$fecha_alta = split("-",$rs_producto['fecha_alta']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
	
	function cambiar_estado(estado, ididioma){
		formulario = document.form_idioma;
		
		formulario.estado_idioma.value = estado;
		formulario.ididioma.value = ididioma;
		formulario.submit();
	};
	
	function confirm_eliminar(url, variables, id){
	
		if (confirm('¿ Está seguro que desea eliminar el registro ?')){
			if (variables == ''){
				window.location.href=(url+'?eliminar_tipo='+id);
			}else{
				window.location.href=(url+'?'+variables+'&eliminar_tipo='+id);
			};
		}
	
	};

	function copiar_carpeta(){
		formulario = document.form_carpeta;
		if (formulario.mod6_idcarpeta.value == "") {
			alert("Debe seleccionar una carpeta");
		} else {	
			formulario.accion.value = 'copiar_carpeta';
			formulario.submit();
		};
	};

	function eliminar_copia_carpeta(idcarpeta_copia){
		formulario = document.form_carpeta;
		formulario.accion.value = 'eliminar_copia_carpeta';
		formulario.idcarpeta_copia.value = idcarpeta_copia;
		formulario.submit();	
	};
	
	function aplicar_cambios_carpeta(){
		formulario = document.form_carpeta;
		formulario.accion.value = 'aplicar_cambios_carpeta';
		formulario.submit();
	};

	function eliminar_foto(){
		formulario = document.form_datos;
		if (confirm('¿ Está seguro que desea eliminar la foto ?')){
			formulario.accion.value = "eliminar_foto";
			formulario.submit();
		}
	};

	function eliminar_foto_extra(id){
		formulario = document.form_fotos;
		if (confirm('¿ Está seguro que desea eliminar la foto ?')){
			formulario.accion.value = "eliminar_foto_extra";
			formulario.eliminar.value = id;
			formulario.submit();
		}
	};

	function ingresar_foto_extra(){
		formulario = document.form_fotos;
	
		if (formulario.foto_extra.value){
			formulario.accion.value = "ingresar_foto_extra";
			formulario.submit();
		}else{
			alert("Debe seleccionar una foto para ingresar utilizando el botón 'Examinar'.")
		}
	};

	function foto_extra_reordenar(){
		formulario = document.form_fotos;
		if (formulario.foto_extra_columna.value <= 0 || formulario.foto_extra_columna.value >= 6){
			alert("Por favor, indique la cantidad de columnas en que se muestran las fotos extras.");
		}else{
			formulario.accion.value = "foto_extra_reordenar";
			formulario.submit();
		}
	};
	
	function validar_sede(){
		formulario = document.form_sede;
			formulario.accion.value = "modificar_sede";
			formulario.submit();	
	};
	
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
	
	var box1 = {};
	window.addEvent('domready', function(){
		box1 = new MultiBox('desc', {descClassName: 'multiBoxDesc_desc', useOverlay: true});
	});
</script>

<style type="text/css">
 .style3{color:#FF0000}
.style2 {font-family: Arial, Helvetica, sans-serif}
.resize {		margin: 0;
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
</style>
</head>
<body>
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_datos" id="form_datos">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Datos del producto:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr>
                          <td bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="4" cellspacing="0" class="detalle_medio">
                            <tr>
                              <td width="15%" align="right" class="detalle_medio">ID:</td>
                              <td colspan="3" align="left" class="detalle_medio_bold"><input name="id" type="text" disabled="disabled" class="detalle_medio" id="id" value="<?= $rs_producto['idproducto'] ?>" size="3"></td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">Fecha alta:</td>
                              <td colspan="3" align="left" class="detalle_medio_bold"><span class="style2">
                                <select name="dia_fecha_alta" class="detalle_medio" id="dia_fecha_alta">
                                  <option value='00' ></option><?												
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
                                <select name="mes_fecha_alta" class="detalle_medio" id="mes_fecha_alta">
                                  <option value='00' ></option><?						
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
                                <select name="ano_fecha_alta" class="detalle_medio" id="ano_fecha_alta">
                                  <option value='0000' ></option> <?	
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
                              </span><a href="producto_pdf.php?idproducto=<?= $idproducto ?>" target="_blank"></a></td>
                            </tr>
							<? if($rs_parametro['cod_interno'] == 1){ ?>
                            <tr>
                              <td align="right" class="detalle_medio">Cod. Interno:</td>
                              <td colspan="3" align="left" class="detalle_medio_bold"><input name="cod_interno" type="text" class="detalle_medio" id="cod_interno" value="<?= $rs_producto['cod_interno'] ?>" size="10"></td>
                            </tr>
							<? } ?>
							<? if($rs_parametro['cod_fabricante'] == 1){ ?>
                            <tr>
                              <td align="right" class="detalle_medio">Cod. Fabricante:</td>
                              <td colspan="3" align="left" class="detalle_medio_bold"><input name="cod_fabricante" type="text" class="detalle_medio" id="cod_fabricante" value="<?= $rs_producto['cod_fabricante'] ?>" size="10"></td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">Proveedor:</td>
                              <td colspan="3" align="left" class="detalle_medio_bold"><span class="style10">
                                <select name="select" class="detalle_medio" id="select"  style="width:200px;" >
                                  <option value="0">Seleccionar Proveedor</option>
                                  <?
										  $query_idproducto_proveedor = "SELECT idproducto_proveedor, titulo 
										  FROM producto_proveedor
										  WHERE estado = '1'
										  ORDER BY titulo";
										  $result_idproducto_proveedor = mysql_query($query_idproducto_proveedor);
										  
										  while($rs_idproducto_proveedor = mysql_fetch_assoc($result_idproducto_proveedor)){
										  		
												if($rs_idproducto_proveedor['idproducto_proveedor'] == $rs_producto['idproducto_proveedor']){
													$idproducto_proveedor_sel = "selected";
												}else{
													$idproducto_proveedor_sel = "";
												}
										?>
                                  <option <?= $idproducto_proveedor_sel ?> value="<?= $rs_idproducto_proveedor['idproducto_proveedor'] ?>" >
                                  <?= $rs_idproducto_proveedor['titulo'] ?>
                                  </option>
                                  <?  } ?>
                                </select>
                                <label>
                                <input name="Button3" type="button" class="detalle_medio_bold" value="Ver Proveedores &raquo;" onclick="window.open('producto_proveedor_ver.php','_blank');" />
                                </label>
                              </span></td>
                            </tr>
							<? } ?>
							<? if($rs_parametro['marca'] == 1){ ?>
                            <tr>
                              <td align="right" class="detalle_medio">Marca:</td>
                              <td colspan="3" align="left" class="detalle_medio_bold"><span class="style10">
                                <select name="idproducto_marca" class="detalle_medio" id="idproducto_marca" style="width:200px;" >
                                  <option value="0">Seleccionar Marca</option>
                                  <?
										  $query_idproducto_marca = "SELECT idproducto_marca, titulo 
										  FROM producto_marca
										  WHERE estado = '1'
										  ORDER BY titulo";
										  $result_idproducto_marca = mysql_query($query_idproducto_marca);
										  while ($rs_idproducto_marca = mysql_fetch_assoc($result_idproducto_marca)){
										  		if($rs_idproducto_marca['idproducto_marca']==$rs_producto['idproducto_marca']){
													$idproducto_marca_sel = "selected";
												}else{
													$idproducto_marca_sel = "";
												}
										?>
                                  <option <?= $idproducto_marca_sel ?> value="<?= $rs_idproducto_marca['idproducto_marca'] ?>" >
                                  <?= $rs_idproducto_marca['titulo'] ?>
                                  </option>
                                  <?  } ?>
                                </select>
                                <label>
                                <input name="Button" type="button" class="detalle_medio_bold" value="Ver Marcas &raquo;" onclick="window.open('producto_marca_ver.php','_blank');" />
                                </label>
                              </span></td>
                            </tr>
							<? } ?>
							<? if($rs_parametro['precio'] == 1){ ?>
                            <tr>
                              <td align="right" class="detalle_medio">Precio:</td>
                              <td colspan="3" align="left" class="detalle_medio"><input name="precio" type="text" class="detalle_medio" id="precio" value="<?= $rs_producto['precio'] ?>" size="10" /> 
                              $ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IVA: 
                              
                              <label>
                              <select name="idca_iva" id="idca_iva" class="detalle_medio">
							  <? 
							  $query_iva = "SELECT * FROM ca_iva WHERE estado = 1";
							  $result_iva = mysql_query($query_iva);
							  while($rs_iva = mysql_fetch_assoc($result_iva)){
							  ?>
                                <option value="<?= $rs_iva['idca_iva'] ?>" <? if($rs_iva['idca_iva'] == $rs_producto['idca_iva']){ echo "selected"; } ?>><?= $rs_iva['titulo_iva'] ?></option>
							  <? } ?>
                              </select>
                              </label></td>
                            </tr>
							<? } ?>
                            <tr>
                              <td align="right" class="detalle_medio">Propiedades:</td>
                              <td width="4%" align="left" valign="middle"><label>
                                <input name="estado" type="checkbox" id="estado" value="1" <? if($rs_producto['estado']==1){ echo "checked"; } ?>/>
                              </label></td>
                              <td width="81%" colspan="2" align="left" valign="middle"><strong>Habilitado</strong></td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">&nbsp;</td>
                              <td width="4%" align="left" valign="middle"><input name="compra" type="checkbox" id="compra" value="1" <? if($rs_producto['compra']==1){ echo "checked"; } ?>/></td>
                              <td colspan="2" align="left" valign="middle"><strong>Permitir compra de este producto </strong></td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">&nbsp;</td>
                              <td width="4%" align="left" valign="middle"><input name="novedad" type="checkbox" id="novedad" value="1" <? if($rs_producto['novedad']==1){ echo "checked"; } ?>/></td>
                              <td colspan="2" align="left" valign="middle">Novedad</td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">&nbsp;</td>
                              <td align="left" valign="middle"><input name="oferta" type="checkbox" id="oferta" value="1" <? if($rs_producto['oferta']==1){ echo "checked"; } ?> /></td>
                              <td colspan="2" align="left" valign="middle"> Oferta</td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">&nbsp;</td>
                              <td align="left" valign="middle"><input name="destacado" type="checkbox" id="destacado" value="1" <? if($rs_producto['destacado']==1){ echo "checked"; } ?> /></td>
                              <td colspan="2" align="left" valign="middle">Destacado</td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">&nbsp;</td>
                              <td align="left" valign="middle"><input name="discontinuado" type="checkbox" id="discontinuado" value="1" <? if($rs_producto['discontinuado']==1){ echo "checked"; } ?> /></td>
                              <td colspan="2" align="left" valign="middle">Discontinuado</td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Foto:</td>
                              <td colspan="3" align="left"><input name="foto" type="file" class="detalle_medio" id="foto" size="50" /></td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                              <td colspan="3" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                <tr>
                                  <td width="28%" class="detalle_medio">Tama&ntilde;o de foto peque&ntilde;a:</td>
                                  <td width="72%"><span class="detalle_medio_bold">
                                    <input name="foto_chica_ancho_seleccionado" type="text" class="detalle_medio" id="foto_chica_ancho_seleccionado" value="<?= $foto_chica_ancho ?>" size="5" />
                                  </span><span class="detalle_medio"><strong>px</strong> ancho.</span></td>
                                </tr>
                                <tr>
                                  <td class="detalle_medio">Tama&ntilde;o de foto mediana:</td>
                                  <td><span class="detalle_medio_bold">
                                    <input name="foto_mediana_ancho_seleccionado" type="text" class="detalle_medio" id="foto_mediana_ancho_seleccionado" value="<?= $foto_mediana_ancho ?>" size="5" />
                                  </span><span class="detalle_medio">                                  <strong>px</strong> ancho.</span></td>
                                </tr>
                                <tr>
                                  <td class="detalle_medio">Tama&ntilde;o de foto grande:</td>
                                  <td><span class="detalle_medio_bold">
                                    <input name="foto_grande_ancho_seleccionado" type="text" class="detalle_medio" id="foto_grande_ancho_seleccionado" value="<?= $foto_grande_ancho ?>" size="5" />
                                  </span><span class="detalle_medio">                                  <strong>px</strong> ancho.</span></td>
                                </tr>
                              </table>                                </td>
                            </tr>
                            <? if($rs_producto['foto']){ 
									if (file_exists($foto_ruta_chica.$rs_producto['foto'])){
										$foto_chica_ancho_real = getimagesize($foto_ruta_chica.$rs_producto['foto']);
									}
									if (file_exists($foto_ruta_mediana.$rs_producto['foto'])){
										$foto_mediana_ancho_real = getimagesize($foto_ruta_mediana.$rs_producto['foto']);
									}
									if (file_exists($foto_ruta_grande.$rs_producto['foto'])){
										$foto_grande_ancho_real = getimagesize($foto_ruta_grande.$rs_producto['foto']);
									}
							  		
							  	?>
                            <tr>
                              <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                              <td colspan="3" align="left"><table width="100%" border="0" cellspacing="5" cellpadding="0">
                                  <tr>
                                    <td align="left" valign="top"><table border="1" cellpadding="0" cellspacing="0" bordercolor="#BAEFE0">
                                        <tr>
                                          <td bgcolor="#BAEFE0" style="border:1px solid #BAEFE0;"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                              <tr valign="middle" class="detalle_medio">
                                                <td align="right"><a style="color:#C61E00; font-size:10px; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif" href="javascript:eliminar_foto();">Eliminar</a></td>
                                                <td width="10" align="left"><a href="javascript:eliminar_foto();"><img src="../../imagen/b_drop.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                        <tr>
                                          <td style="border:1px solid #BAEFE0;"><? $foto_seccion =& new obj0001(0,$foto_ruta_chica,$rs_producto['foto'],'','','','','','','','wmode=opaque',''); ?></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td align="left" valign="top"><table width="70%" border="0" cellpadding="4" cellspacing="0">
                                      <tr>
                                        <td width="74" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Archivo</td>
                                        <td colspan="3" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$rs_producto['foto']?></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">&nbsp;</td>
                                        <td width="210" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio"><a href="<?= $foto_ruta_chica.$rs_producto['foto']?>" target="_blank">Chica</a> </td>
                                        <td width="210" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio"><a href="<?= $foto_ruta_mediana.$rs_producto['foto']?>" target="_blank">Mediana</a></td>
                                        <td width="210" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio"><a href="<?= $foto_ruta_grande.$rs_producto['foto']?>" target="_blank">Grande</a></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Ancho</td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$foto_chica_ancho_real[0]?>
                                          px</td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$foto_mediana_ancho_real[0]?>
                                          px</td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$foto_grande_ancho_real[0]?>
                                          px</td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Alto</td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$foto_chica_ancho_real[1]?>
                                          px </td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$foto_mediana_ancho_real[1]?>
                                          px</td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$foto_grande_ancho_real[1]?>
                                          px</td>
                                      </tr>
                                    </table></td>
                                  </tr>
                              </table></td>
                            </tr>
                            <? }; ?>
                            <? if($rs_producto['foto_logo']){ 
									if (file_exists($foto_logo_ruta.$rs_producto['foto_logo'])){
										$foto_logo_ancho_real = getimagesize($foto_logo_ruta.$rs_producto['foto_logo']);
									}
							  		
							  	?>
                            <? }; ?>
                            <? if($rs_producto['foto_garantia']){ 
									if (file_exists($foto_garantia_ruta.$rs_producto['foto_garantia'])){
										$foto_garantia_ancho_real = getimagesize($foto_garantia_ruta.$rs_producto['foto_garantia']);
									}
							  		
							  	?>
                            <? }; ?>
                          </table></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr bgcolor="#D8F6EE" class="detalle_medio">
                          <td width="109" align="right" class="detalle_medio_bold">&nbsp;</td>
                          <td width="559" align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                          <?
						  		if($forma=='lista'){
									$boton_forma = "javascript:window.open('producto_ver_lista.php?idcarpeta=".$idcarpeta."','_self');";
								}else{
									$boton_forma = "javascript:window.open('producto_ver.php?idcarpeta=".$idcarpeta."','_self');";
								}
						  
						  ?>
							
							<input name="Submit223" type="button" class="detalle_medio_bold" onclick="<?= $boton_forma ?>" value="&lt;&lt;  Ver (&aacute;rbol) " />
                            </span>
                          <input name="Submit22" type="button" class="detalle_medio_bold" onclick="validar_form();" value="&gt;&gt;  Guardar " /></td>
                        </tr>
                    </table>
                    <span class="titulo_medio_bold"><a name="idioma" id="idioma"></a></span>
                  </form><br />
                      <form action="" method="post" name="form_idioma" id="form_idioma">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFE697" class="titulo_medio_bold">&nbsp; Informaci&oacute;n del producto: <span class="detalle_chico" style="color:#FF0000">
                              <input name="estado_idioma" type="hidden" id="estado_idioma" value="" />
                              <span class="detalle_chico" style="color:#FF0000">
                              <input name="ididioma" type="hidden" id="ididioma" value="" />
                              </span></span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF5D7"><table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
                                <? 					
						$query_idioma = "SELECT A.*, B.titulo_idioma
						FROM producto_idioma_dato A
						LEFT JOIN idioma B ON B.ididioma = A.ididioma
						WHERE A.idproducto = '$idproducto'
						ORDER BY A.ididioma";
						$result_idioma = mysql_query($query_idioma);
						while($rs_idioma = mysql_fetch_assoc($result_idioma)){			
						?>
                                <tr>
                                  <td width="55" bgcolor="#FFECB3" class="detalle_medio_bold">Idioma</a></td>
                                  <td width="523" bgcolor="#FFECB3" class="detalle_medio"><a href="producto_editar_idioma.php?idproducto=<?= $idproducto ?>&amp;ididioma=<?=$rs_idioma['ididioma']?>" target="_blank" class="style10"></a><?=$rs_idioma['titulo_idioma']?></td>
                                  <td width="1" bgcolor="#FFECB3" class="detalle_medio"><a href="producto_pdf.php?idproducto=<?= $idproducto ?>&ididioma=<?=$rs_idioma['ididioma']?>" target="_blank"><img src="../../imagen/iconos/pdf_16px.png" width="16" height="16" border="0" /></a></td>
                                  <td width="16" bgcolor="#FFECB3" class="detalle_medio"><a href="../../../producto_detalle.php?idproducto=<?= $idproducto ?>&ididioma_session=<?= $rs_idioma['ididioma'] ?>&modo=previsualizar" target="_blank"><img src="../../imagen/iconos/search_mini.png" width="16" height="16" border="0" /></a></td>
                                  <td width="17" bgcolor="#FFECB3" class="detalle_medio"><span class="detalle_medio_bold">
                                    <? if ($rs_idioma['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                                    <a href="javascript:cambiar_estado(2,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                                    <? } else { ?>
                                    <a href="javascript:cambiar_estado(1,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                                    <? } ?>
                                  </span></td>
                                  <td width="16" bgcolor="#FFECB3" class="detalle_medio"><a href="producto_editar_idioma.php?idproducto=<?= $idproducto ?>&ididioma=<?=$rs_idioma['ididioma']?>&idcarpeta=<?=$idcarpeta?>&forma=<?=$forma?>" target="_self" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Titulo</td>
                                  <td colspan="5" bgcolor="#FFF5D7" class="detalle_medio"><?=$rs_idioma['titulo']?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Copete</td>
                                  <td colspan="5" bgcolor="#FFF5D7" class="detalle_medio"><?=$rs_idioma['copete']?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Detalle</td>
                                  <td colspan="5" bgcolor="#FFF5D7" class="detalle_medio">
								  <? if($rs_idioma['detalle']){ ?>
                                    <div class="resize" style="height:200px; width:580px;">
                                      <?= str_replace('src="imagen','src="../../../imagen', html_entity_decode($rs_idioma['detalle'], ENT_QUOTES)) ?>
                                    </div>
                                  <? } ?></td>
                                </tr>
                                <tr>
                                  <td align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Keywords</td>
                                  <td colspan="5" bgcolor="#FFF5D7" class="detalle_medio"><?=$rs_idioma['keywords']?></td>
                                </tr>
                                <tr>
                                  <td height="10" colspan="6" bgcolor="#FFF5D7" class="detalle_medio_bold"></td>
                                </tr>
                                <? 					
							};
?>
                            </table></td>
                          </tr>
                        </table>
                      </form><br />
                      <form action="#copy" method="post" enctype="multipart/form-data" name="form_carpeta" id="form_carpeta">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Incorporar nueva Vista del producto en otra carpeta: <a name="copy" id="copy"></a><span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" />
                            <span class="detalle_chico" style="color:#FF0000">
                            <input name="idcarpeta_copia" type="hidden" id="idcarpeta_copia" />
                            </span></span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="19%" height="28" align="right" valign="top" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                    <tr>
                                      <td align="right" class="detalle_medio"><strong>Vistas en carpeta: </strong></td>
                                    </tr>
                                </table></td>
                                <td width="81%"><?
								$cont=0;
								$query_carpeta = "SELECT B.nombre, C.idcarpeta_padre, C.idcarpeta, A.orden
								FROM producto_carpeta A
								INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
								INNER JOIN carpeta C ON C.idcarpeta = B.idcarpeta
								WHERE A.idproducto = '$idproducto' AND B.ididioma = '1'
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
                                        <td width="96%" height="29" class="detalle_medio">N&ordm; de Orden:
                                          <label>
                                            <input name="orden_row[<?= $cont ?>]" type="text" class="detalle_medio" id="orden_row[<?= $cont ?>]" size="4" value="<?= $rs_carpeta['orden'] ?>" />
                                            <input name="idcarpeta_row[<?= $cont ?>]" type="hidden" id="idcarpeta_row[<?= $cont ?>]" value="<?= $rs_carpeta['idcarpeta'] ?>" />
                                          </label></td>
                                        <td width="4%" rowspan="2" align="center" valign="top"><? if($cant_result > 1){ ?>
                                            <a href="javascript:eliminar_copia_carpeta(<?= $rs_carpeta['idcarpeta'] ?>);"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a>
                                            <? } ?></td>
                                      </tr>
                                      <tr>
                                        <td height="15" class="detalle_medio"><?= "<b>Ruta:</b> ".$ruta."<br>"; ?></td>
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
                            </table></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF0E1" class="titulo_medio_bold"><hr size="1" class="detalle_medio" /></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
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
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
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
                            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                                <tr>
                                  <td width="110" align="right" valign="middle">&nbsp;</td>
                                  <td align="left"><span class="style2">
                                    <input name="Submit2" type="button" class="detalle_medio_bold" onclick="javascript:copiar_carpeta()" value=" &gt;&gt;  Incorporar " />
                                  </span></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table>
                      </form><br />
					  <form id="form_sede" name="form_sede" method="post" action="" enctype="multipart/form-data">
					  <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Propiedades del producto: <a name="propiedades" id="propiedades"></a><span class="detalle_chico" style="color:#FF0000">
                              <input name="accion" type="hidden" id="accion" value="" />
                            </span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                
                                <tr>
                                  <td width="100" align="right" valign="top" class="detalle_medio"><strong>Sucursales: </strong></td>
                                  <td width="556" align="left" valign="top" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
									$query_prod_sede = "SELECT A.idsede
									FROM producto_sede A
									WHERE A.idsede = '$rs_sede[idsede]' AND A.idproducto = '$idproducto'";
									$rs_prod_sede = mysql_fetch_assoc(mysql_query($query_prod_sede));
									
									if($rs_prod_sede['idsede'] == $rs_sede['idsede']){
										$check = "checked";
									}else{
										$check = "";
									}								
									
								?>
                                    <tr>
                                      <td width="5%"><input type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?= $check ?>  <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?>></td>
                                      <td width="95%" class="detalle_medio"><?= $rs_sede['titulo'] ?></td>
                                    </tr>
                                    <? 
								$c++;
								} 
								?>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td width="100" align="right" valign="top" class="style2"><input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" /></td>
                                  <td align="left" valign="middle" class="style2"><input name="Submit233" type="button" class="detalle_medio_bold" onclick="javascript:validar_sede()" value="  &gt;&gt;  Modificar   " /></td>
                                </tr>
                            </table></td>
                          </tr>
                        </table>
					  </form><br />
                    <form action="" method="post" enctype="multipart/form-data" name="form_fotos" id="form_fotos">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="35" colspan="2" bgcolor="#FF8282" class="titulo_medio_bold">Fotos extra <a name="foto_extra" id="foto_extra"></a><span class="detalle_chico" style="color:#FF0000">
                              <input name="accion" type="hidden" id="accion" value="1" />
                              <input name="eliminar" type="hidden" id="eliminar" value="0" />
                            </span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td height="35" align="left" bgcolor="#FFBBBB" class="titulo_medio_bold"><span class="detalle_medio_bold">Ingresar  nueva foto extra</span></td>
                            <td align="right" bgcolor="#FFBBBB" class="titulo_medio_bold"><span class="detalle_medio_bold"><span class="detalle_medio_bold_white">
							<? if($_SESSION['idcusuario_perfil_log'] == 1){ ?>
                              <input name="Button2" type="button" class="detalle_medio_bold" onclick="window.open('producto_editar_multi.php?idproducto=<?= $idproducto ?>','_blank');" value=" Carga multiple &raquo; " />
							  <? } ?>
                            </span></span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td colspan="2" align="left" bgcolor="#FFE1E1"><table width="100%" border="0" cellspacing="0" cellpadding="4">
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
                                <td colspan="2" class="detalle_medio"><?
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
                                      <td width="87%" class="detalle_medio">Forma Horizontal. </td>
                                    </tr>
                                    <tr>
                                      <td width="6%"><input name="foto_extra_tipo" type="radio" value="2" /></td>
                                      <td width="7%"><img src="../../imagen/vertical.gif" width="24" height="24" /></td>
                                      <td class="detalle_medio">Forma Vertical. </td>
                                    </tr>
                                    <tr>
                                      <td width="6%"><input name="foto_extra_tipo" type="radio" value="3" /></td>
                                      <td width="7%"><img src="../../imagen/detalle.gif" width="24" height="24" /></td>
                                      <td class="detalle_medio">Dentro del detalle. </td>
                                    </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="118">&nbsp;</td>
                                <td colspan="2"><input name="Submit23" type="button" class="detalle_medio_bold" onclick="javascript:ingresar_foto_extra()" value="  &gt;&gt;  Agregar   " /></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
                        <span class="titulo_medio_bold"><a name="foto_extra_ingresadas" id="foto_extra_ingresadas"></a></span><br />
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td width="51%" height="32" align="left" bgcolor="#FFBBBB" class="detalle_medio_bold">Fotos extras ingresadas </td>
                            <td width="49%" height="32" align="right" bgcolor="#FFBBBB"><span class="detalle_medio_bold">
                              <input name="Button" type="button" class="detalle_medio_bold"  onclick="foto_extra_reordenar();" value="Aplicar cambios &raquo;" />
                            </span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td height="32" colspan="2" align="left" bgcolor="#FFCCCC" class="detalle_medio">Mostrar fotos en 
                              <label>
                              <input name="foto_extra_columna" type="text" class="detalle_medio" id="foto_extra_columna" style="width:20px; height:16px; text-align:center;" value="<?= $rs_producto['foto_extra_columna'] ?>" maxlength="1" />
                              </label>
                            columnas. </td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td colspan="2" align="left" bgcolor="#FFE1E1"><? //FOTO EXTRA
				$foto_extra_cont = 0;
				$query_idioma = "SELECT titulo_idioma, ididioma
				FROM idioma 
				WHERE estado = 1";
				$result_idioma = mysql_query($query_idioma);
				while($rs_idioma = mysql_fetch_assoc($result_idioma)){
				?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                  <tr>
                                    <td height="30" class="detalle_medio_bold">&bull;
                                        <?= $rs_idioma['titulo_idioma'] ?></td>
                                  </tr>
                                </table>
                              <?
				
				$query_foto_cant = "SELECT COUNT(idproducto_foto)
				FROM producto_foto 
				WHERE idproducto = '$idproducto' AND ididioma = '$rs_idioma[ididioma]'";
				$rs_foto_cant = mysql_fetch_row(mysql_query($query_foto_cant));
				if($rs_foto_cant[0]>0){
				
				?>
                                <table width="150" border="0" cellpadding="4" cellspacing="0">
                                  <tr valign="top">
                                    <? 
						
						$query_foto = "SELECT *
						FROM producto_foto 
						WHERE idproducto = '$idproducto' AND ididioma = '$rs_idioma[ididioma]'
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
                                          <td height="30" bgcolor="#FFBBBB" style="border:1px solid #FFBBBB;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                              <tr valign="middle" class="detalle_medio">
                                                <td align="left"><? if($rs_foto['foto_extra_tipo'] == 1){ ?>
                                                    <img src="../../imagen/horizontal.gif" alt="barra horizontal habilitada" width="24" height="24" border="0" />
                                                    <? }else{ ?>
                                                    <a href="<?= $_SERVER['PHP_SELF']."?idproducto=$idproducto&idproducto_foto_sel=".$rs_foto['idproducto_foto']."&foto_extra_tipo_sel=1";?>"><img src="../../imagen/horizontal_gris.gif" alt="barra horizontal deshabilitada" width="24" height="24" border="0" /></a>
                                                    <? } ?>
                                                    <? if($rs_foto['foto_extra_tipo'] == 2){ ?>
                                                    <img src="../../imagen/vertical.gif" alt="barra vertical habilitada" width="24" height="24" border="0" />
                                                    <? }else{ ?>
                                                    <a href="<?= $_SERVER['PHP_SELF']."?idproducto=$idproducto&idproducto_foto_sel=".$rs_foto['idproducto_foto']."&foto_extra_tipo_sel=2";?>"><img src="../../imagen/vertical_gris.gif" alt="barra vertical deshabilitada" width="24" height="24" border="0" /></a>
                                                    <? } ?>
                                                    <? if($rs_foto['foto_extra_tipo'] == 3){ ?>
                                                    <img src="../../imagen/detalle.gif" alt="Insertar en detalle habilitado" width="24" height="24" border="0" />
                                                    <? }else{ ?>
                                                    <a href="<?= $_SERVER['PHP_SELF']."?idproducto=$idproducto&idproducto_foto_sel=".$rs_foto['idproducto_foto']."&foto_extra_tipo_sel=3";?>"> <img src="../../imagen/detalle_gris.gif" alt="Insertar en detalle habilitado" width="24" height="24" border="0" /></a>
                                                    <? } ?>                                                </td>
                                                <td width="5" align="left" bgcolor="#FF9595"><a href="javascript:eliminar_foto_extra(<?=$rs_foto['idproducto_foto']?>);">
                                                  <input name="idproducto_foto_row[<?= $foto_extra_cont ?>]" type="hidden" id="idproducto_foto_row[<?= $foto_extra_cont ?>]" value="<?= $rs_foto['idproducto_foto'] ?>" />
                                                <img src="../../imagen/cruz.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                                                <td width="5" align="left" bgcolor="#FF9595"><input  type="checkbox" name="checkbox_row[<?= $foto_extra_cont ?>]" id="checkbox_row[<?= $foto_extra_cont ?>]"  value="<?=$rs_foto['idproducto_foto']?>" /></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                        <tr>
                                          <td align="center" valign="middle" style="border:1px solid #FFBBBB;"><?
										if($rs_foto['foto'] == ''){
											$foto_xtra =& new obj0001('0','../../../swf/','no_disponible.swf',$ancho_maximo_mini,'','','','','','','wmode=Opaque',''); 
										}else{
											$foto_xtra =& new obj0001('0',$foto_extra_ruta_chica,$rs_foto['foto'],'150','','','',$foto_extra_ruta_grande.$rs_foto['foto'],'_blank','','',''); 
										}; 
										?></td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="detalle_chico" style="border:1px solid #FFBBBB;">Titulo:
                                            <label>
                                              <input name="foto_extra_titulo_row[<?= $foto_extra_cont ?>]" type="text" class="detalle_medio" id="foto_extra_titulo_row[<?= $foto_extra_cont ?>]" value="<?= $rs_foto['titulo']; ?>" size="14" />
                                            </label>                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="right" class="detalle_chico" style="border:1px solid #FFBBBB;">N&ordm; de Orden:
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
                                <table width="100%" border="0" cellspacing="0" cellpadding="8">
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
                    </form><br />
					<form enctype="multipart/form-data" action="" method="post" id="form_descarga" name="form_descarga">
				  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td height="40" colspan="3" bgcolor="#9DBFFF" class="titulo_medio_bold">Descargas<a name="descarga" id="descarga"></a>
                      <input name="estado_descarga" type="hidden" id="estado_descarga" />
                      <input name="iddescarga" type="hidden" id="iddescarga" />
                      <input name="estado_restringido" type="hidden" id="estado_restringido" />
                      <input name="eliminar_iddescarga" type="hidden" id="eliminar_iddescarga" /></td>
                    </tr>
                    <tr>
                      <td width="92%" height="32" bgcolor="#BBD2FF" class="detalle_medio_bold">Nueva descarga para este producto </td>
                      <td width="4%" align="center" valign="middle" bgcolor="#BBD2FF" class="detalle_medio_bold"><a href="producto_editar.php?idproducto=<?= $idproducto ?>&idcarpeta=<?= $idcarpeta ?>&forma=<?= $forma ?>"><img src="../../imagen/iconos/refresh.png" width="20" height="20" border="0" /></a></td>
                      <td width="4%" bgcolor="#BBD2FF" class="detalle_medio_bold">
					  <a href="../descarga/descarga_nueva_individual.php?idproducto=<?= $idproducto ?>" rel="widht:400;height:300" id="d1" class="desc" title="" ><img src="../../imagen/iconos/add_download.png" width="20" height="20" border="0" /></a></td>
                    </tr>
                    <tr>
                      <td colspan="3" bgcolor="#E1EDFF"><table width="100%" border="0" cellspacing="0" cellpadding="3">
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
						WHERE idproducto = '$idproducto' $filtro_carpeta
						ORDER BY idproducto";
						$result_descarga = mysql_query($query_descarga);
						$cant_result = mysql_num_rows($result_descarga);
						while($rs_descarga = mysql_fetch_assoc($result_descarga)){
						
						?>
                        <tr>
                          <td height="30"><img src="../../../imagen/iconos/flecha_cursos.gif" width="5" height="5" /></td>
                          <td height="30" class="detalle_11px"><?= $rs_descarga['titulo'] ?></td>
                          <td height="30" class="detalle_11px"><?= $rs_descarga['archivo'] ?></td>
                          <td height="30" class="detalle_11px"><?= number_format((filesize($ruta_descarga.$rs_descarga['archivo']))/1024,2); ?> kb. </td>
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
						  if($rs_descarga['idproducto'] != 0){ 
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
						  
						  } ?>						  </td>
                        </tr>
						<? } ?>
						
						<? if($cant_result == 0){ ?>
                        <tr>
                          <td height="40" colspan="6" align="center" valign="middle" class="detalle_medio">No hay descargas disponibles. </td>
                        </tr>
						<? } ?>
                      </table></td>
                    </tr>
                  </table>
				  </form>
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
</body>
</html>