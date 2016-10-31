<?
 	include ("../../0_mysql.php"); 
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = " AND idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
		$filtro_sede = '';
	}
	
	//VARIABLES
	$tipo = $_GET['tipo'];
	$idbarra_menu = $_GET['idbarra_menu'];
	
	$foto_editar = $_FILES['foto_editar']['name'];
	$foto_editar_tmp = $_FILES['foto_editar']['tmp_name'];
	
	$check_ididioma = $_POST['ididioma'];
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$check_idsede = $_POST['idsede'];
	$cantidad_sede = $_POST['cantidad_sede'];
	
	$idseccion = $_POST['idseccion'];
	$idproducto = $_POST['idproducto'];
		
	$url = $_POST['url'];
	$titulo = $_POST['titulo'];
	$target = $_POST['target'];
	$orden = $_POST['orden'];
	$idbarra_padre = $_POST['idbarra_padre'];
	
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
	
	//GUARDAR MODIFICACIONES
	if($accion == "guardar_cambios"){
		
		$tipo = $_POST['tipo']; //Tomo la variable por POST porque es más seguro.
		
		$ididioma_editar = $_POST['ididioma_editar'];
		$titulo_editar = $_POST['titulo_editar'];
		$cantidad_idioma_editar = $_POST['cantidad_idioma_editar'];
		$orden = $_POST['orden'];
		$url = $_POST['url'];
		$target = $_POST['target'];
		$id = $_POST['id'];
		$idbarra_padre = $_POST['idbarra_padre'];
		
		switch($tipo){
			case 1: //CARPETA
			
				//GUARDO CAMBIOS EN BARRA_MENU
				$query_1 = "UPDATE barra_menu
				SET orden = '$orden'
				, idbarra_padre = '$idbarra_padre'
				WHERE idbarra_menu = '$idbarra_menu'
				LIMIT 1 ";
				mysql_query($query_1);
				
				//GUARDO CAMBIOS EN BARRA_MENU_SEDE
				$query_del_sede = "DELETE FROM barra_menu_sede WHERE idbarra_menu = '$idbarra_menu' $filtro_sede ";
				mysql_query($query_del_sede);
				
				for($c=0;$c<$cantidad_sede;$c++){
					if($check_idsede[$c] != ""){
						$query_sede = "INSERT INTO barra_menu_sede(
						  idbarra_menu
						, idsede
						)VALUES(
						  '$idbarra_menu'
						, '$check_idsede[$c]'
						)";
						mysql_query($query_sede);
					}
				}
				
				//GUARDO CAMBIOS EN BARRA_MENU_IDIOMA
				$query_del_idioma = "DELETE FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu'";
				mysql_query($query_del_idioma);
				
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						$query = "SELECT B.nombre, B.ididioma
						FROM carpeta A
						INNER JOIN carpeta_idioma_dato B ON A.idcarpeta = B.idcarpeta
						WHERE B.idcarpeta = '$id' AND B.ididioma = '$check_ididioma[$c]'
						ORDER BY B.nombre ASC
						LIMIT 1";
						$result = mysql_query($query);
						$rs_titulo = mysql_fetch_assoc($result);
						$var_titulo = $rs_titulo['nombre'];
						
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						) VALUES (
						  '$idbarra_menu'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						)";
						mysql_query($query_idioma_ingreso);
						
					}//IF
				}//FOR
				
				break;
			
			case 2: //SECCION
			
				//GUARDO CAMBIOS EN BARRA_MENU
				$query_1 = "UPDATE barra_menu
				SET orden = '$orden'
				, idbarra_padre = '$idbarra_padre'
				WHERE idbarra_menu = '$idbarra_menu'
				LIMIT 1 ";
				mysql_query($query_1);
				
				//GUARDO CAMBIOS EN BARRA_MENU_SEDE
				$query_del_sede = "DELETE FROM barra_menu_sede WHERE idbarra_menu = '$idbarra_menu' $filtro_sede ";
				mysql_query($query_del_sede);
				
				for($c=0;$c<$cantidad_sede;$c++){
					if($check_idsede[$c] != ""){
						$query_sede = "INSERT INTO barra_menu_sede(
						  idbarra_menu
						, idsede
						)VALUES(
						  '$idbarra_menu'
						, '$check_idsede[$c]'
						)";
						mysql_query($query_sede);
					}
				}
				
				//GUARDO CAMBIOS EN BARRA_MENU_IDIOMA
				$query_del_idioma = "DELETE FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu'";
				mysql_query($query_del_idioma);
				
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						$query = "SELECT B.titulo, B.ididioma, B.estado
						FROM seccion A
						INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
						WHERE A.idseccion = '$id' AND B.ididioma = '$check_ididioma[$c]'
						ORDER BY B.titulo ASC
						LIMIT 1";
						$result = mysql_query($query);
						$rs_titulo = mysql_fetch_assoc($result);
						$var_titulo = $rs_titulo['titulo'];
						
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						) VALUES (
						  '$idbarra_menu'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						)";
						mysql_query($query_idioma_ingreso);
						
					}//IF
				}//FOR
				
				break;
			
			case 3: //PRODUCTO
				
				//GUARDO CAMBIOS EN BARRA_MENU
				$query_1 = "UPDATE barra_menu
				SET orden = '$orden'
				, idbarra_padre = '$idbarra_padre'
				WHERE idbarra_menu = '$idbarra_menu'
				LIMIT 1 ";
				mysql_query($query_1);
				
				//GUARDO CAMBIOS EN BARRA_MENU_SEDE
				$query_del_sede = "DELETE FROM barra_menu_sede WHERE idbarra_menu = '$idbarra_menu' $filtro_sede ";
				mysql_query($query_del_sede);
				
				for($c=0;$c<$cantidad_sede;$c++){
					if($check_idsede[$c] != ""){
						$query_sede = "INSERT INTO barra_menu_sede(
						  idbarra_menu
						, idsede
						)VALUES(
						  '$idbarra_menu'
						, '$check_idsede[$c]'
						)";
						mysql_query($query_sede);
					}
				}
				
				//GUARDO CAMBIOS EN BARRA_MENU_IDIOMA
				$query_del_idioma = "DELETE FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu'";
				mysql_query($query_del_idioma);
				
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						$query = "SELECT B.titulo, B.ididioma, B.estado
						FROM producto A
						INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto
						WHERE A.idproducto = '$id' AND B.ididioma = '$check_ididioma[$c]'
						ORDER BY B.titulo ASC
						LIMIT 1";
						$result = mysql_query($query);
						$rs_titulo = mysql_fetch_assoc($result);
						$var_titulo = $rs_titulo['titulo'];
						
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						) VALUES (
						  '$idbarra_menu'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						)";
						mysql_query($query_idioma_ingreso);
						
					}//IF
				}//FOR
				
				break;
			
			case 5: //IMAGEN TITULO
				
				//GUARDO CAMBIOS EN BARRA_MENU
				$query_1 = "UPDATE barra_menu
				SET orden = '$orden'
				, link = '$url'
				, target = '$target'
				, idbarra_padre = '$idbarra_padre'
				WHERE idbarra_menu = '$idbarra_menu'
				LIMIT 1 ";
				mysql_query($query_1);
	
				
				//GUARDO CAMBIOS EN BARRA_MENU_SEDE
				$query_del_sede = "DELETE FROM barra_menu_sede WHERE idbarra_menu = '$idbarra_menu' $filtro_sede ";
				mysql_query($query_del_sede);
				
				for($c=0;$c<$cantidad_sede;$c++){
					if($check_idsede[$c] != ""){
						$query_sede = "INSERT INTO barra_menu_sede(
						  idbarra_menu
						, idsede
						)VALUES(
						  '$idbarra_menu'
						, '$check_idsede[$c]'
						)";
						mysql_query($query_sede);
					}
				}
				
				//GUARDO CAMBIOS EN BARRA_MENU_IDIOMA
				for($i=0;$i<$cantidad_idioma_editar;$i++){
				
					$query_2 = "UPDATE barra_menu_idioma
					SET titulo = '$titulo_editar[$i]'
					WHERE idbarra_menu = '$idbarra_menu' AND ididioma = '$ididioma_editar[$i]'
					LIMIT 1 ";
					mysql_query($query_2);

					if($check_ididioma[$i] != $ididioma_editar[$i]){
					
						//ELIMINAR SI HAY CARGADA UNA FOTO
						$querysel = "SELECT foto FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu' AND ididioma = '$ididioma_editar[$i]' "; 
						$ruta_foto = "../../../imagen/barra/";
						$rowfoto = mysql_fetch_row(mysql_query($querysel));
						
						if ( $rowfoto[0] ){
							if (file_exists($ruta_foto.$rowfoto[0])){
								unlink ($ruta_foto.$rowfoto[0]);
							}
						}
					}
					
					// CAMBIO FOTOS
					if ($foto_editar[$i] != ""){
							
							$ruta_foto = "../../../imagen/barra/";
							$archivo_ext = substr($foto_editar[$i],-4);
							$archivo_nombre = substr($foto_editar[$i],0,strrpos($foto_editar[$i], "."));
							
							$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
							$archivo = strtolower($archivo);
							
							//ELIMINAR SI HAY CARGADA UNA FOTO
							$querysel = "SELECT foto FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu' AND ididioma = '$ididioma_editar[$i]' "; 
							
							$rowfoto = mysql_fetch_row(mysql_query($querysel));
							
							if ( $rowfoto[0] ){
								if (file_exists($ruta_foto.$rowfoto[0])){
									unlink ($ruta_foto.$rowfoto[0]);
								}
							}
							
							
								
							$foto =  $idbarra_menu . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
							$foto_nombre_nueva[$i] = $foto;
							
							if (!copy($foto_editar_tmp[$i], $ruta_foto . $foto)){ //si hay error en la copia de la foto
								$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
								echo "<script>alert('".$error."')</script>"; // se muestra el error.
							}else{
								$imagesize = getimagesize($ruta_foto.$foto);
							
								if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
									$peso = number_format((filesize($ruta_foto.$foto))/1024,2);
									
									if($peso==0){
										$error2 = "La foo fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
										echo "<script>alert('".$error2."')</script>"; // se muestra el error.
									}else{
									
										//ingreso de foto en tabla producto
										$query_upd = "UPDATE barra_menu_idioma SET 
										foto = '$foto' 	
										WHERE idbarra_menu = '$idbarra_menu' AND ididioma = '$ididioma_editar[$i]'
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
					}//IF
				}//FOR
				
				//GUARDAR CAMBIOS EN BARRA_MENU_IDIOMA
				$query_del_idioma = "DELETE FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu'";
				mysql_query($query_del_idioma);
				
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						
						if($titulo_editar[$c] != ""){
							$var_titulo = $titulo_editar[$c];
						}else{
							$var_titulo = $url;
						}
						
						if($foto_editar[$c] != ""){
							$var_foto = $foto_nombre_nueva[$c];
						}else{
							//$query_foto = "SELECT foto FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu' AND ididioma = '$ididioma_editar[$c]'";
							//$rs_foto = mysql_fetch_assoc(mysql_query($query_foto));
							$var_foto = $foto_nombre[$c];
						}
					
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						, foto
						) VALUES (
						  '$idbarra_menu'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						, '$var_foto'
						)"; 
						mysql_query($query_idioma_ingreso);
						
					}
					
				}//FOR
				
				break;
				
			case 4: //LINK
				
				//GUARDO CAMBIOS EN BARRA_MENU
				$query_1 = "UPDATE barra_menu
				SET orden = '$orden'
				, link = '$url'
				, target = '$target'
				, idbarra_padre = '$idbarra_padre'
				WHERE idbarra_menu = '$idbarra_menu'
				LIMIT 1 ";
				mysql_query($query_1);
				
				//GUARDO CAMBIOS EN BARRA_MENU_IDIOMA
				for($i=0;$i<$cantidad_idioma_editar;$i++){
					$query_2 = "UPDATE barra_menu_idioma
					SET titulo = '$titulo_editar[$i]'
					WHERE idbarra_menu = '$idbarra_menu' AND ididioma = '$ididioma_editar[$i]'
					LIMIT 1 ";
					mysql_query($query_2);
				}
				
				//GUARDO CAMBIOS EN BARRA_MENU_SEDE
				$query_del_sede = "DELETE FROM barra_menu_sede WHERE idbarra_menu = '$idbarra_menu' $filtro_sede ";
				mysql_query($query_del_sede);
				
				for($c=0;$c<$cantidad_sede;$c++){
					if($check_idsede[$c] != ""){
						$query_sede = "INSERT INTO barra_menu_sede(
						  idbarra_menu
						, idsede
						)VALUES(
						  '$idbarra_menu'
						, '$check_idsede[$c]'
						)";
						mysql_query($query_sede);
					}
				}
				
				//GUARDAR CAMBIOS EN BARRA_MENU_IDIOMA
				$query_del_idioma = "DELETE FROM barra_menu_idioma WHERE idbarra_menu = '$idbarra_menu'";
				mysql_query($query_del_idioma);
				
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						
						if($titulo_editar[$c] != ""){
							$var_titulo = $titulo_editar[$c];
						}else{
							$var_titulo = $url;
						}
					
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						) VALUES (
						  '$idbarra_menu'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						)";
						mysql_query($query_idioma_ingreso);
						
					}//IF
				}//FOR
				break;
		
		}
	
	}
	
	
		
	//OBTENGO DATOS GENERALES DE LA BARRA MENU
	$query = "SELECT link, target, orden, id, idbarra_padre
	FROM barra_menu
	WHERE idbarra_menu = '$idbarra_menu'
	LIMIT 1";
	$rs_dato = mysql_fetch_assoc(mysql_query($query));
	
	$var_link = $rs_dato['link'];
	$var_target = $rs_dato['target'];
	$var_orden = $rs_dato['orden'];
	$var_id = $rs_dato['id'];
	$var_idbarra_padre = $rs_dato['idbarra_padre'];

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
<script type="text/javascript">

	function cambiar_estado(estado, id){
		formulario = document.form;
		
		formulario.estado.value = estado;
		formulario.idbarra_menu.value = id;
		formulario.submit();
	};
	
	function guardar_cambios(){
		var formulario = document.form;
		var flag = true;
		var checks_sede = 0;
		var checks_idioma = 0;
		
		switch(formulario.tipo.value){
			case '1':
				break;
			case '2':
				break;
			case '3':
				break;
			case '5':
				break;
			case '4':
				for(i=0;i<formulario.cantidad_idioma_editar.value;i++){
			
					actual = document.getElementById("titulo_editar["+i+"]");
					if (actual.value == ""){
						alert("Por favor, introduzca el titulo.");
						flag = false;
						break;
					}
					
				}
				if(formulario.url.value == ""){
					alert("Debe introducir la URL.");
					flag = false;
				}
				break;
		
		}
		
		
		if(esNumerico(formulario.orden.value)==false){
			alert("El Orden debe ser númerico.");
			flag = false;
		}
		
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = document.getElementById("idsede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		for(i=0;i<formulario.cantidad_idioma.value;i++){
			
			check_actual = document.getElementById("ididioma["+i+"]");
			if (check_actual.checked == true){
				checks_idioma = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			alert("Debe seleccionar al menos una sucursal.");
			flag = false;
		}
		
		if(checks_idioma == 0){ 
			alert("Debe seleccionar al menos un idioma.");
			flag = false;
		}
		
		if(flag == true){
			formulario = document.form;
			formulario.accion.value = "guardar_cambios";
			formulario.submit();
		}else{
			alert("No se ha podido crear el boton.");
		}
	}
	
	function mod6_select_idcarpeta(nivel){
		formulario = document.form;
		
		switch(nivel){
			case 1: 
			 formulario.mod6_idcarpeta2.value = "";
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 1");
			 break;
		
			case 2: 
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 2");
			 break;
			 
			case 3:
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 3");
			 break;
		}

		formulario.submit();
	}
	
</script>
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Barra Menu - Editar </td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#cccccc;"></td>
              </tr>
            </table>
              <form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="ffddbc">
                    <td height="40" align="left" bgcolor="#FFE9D2" class="titulo_medio_bold">Editar elemento                    
                      <input name="accion" type="hidden" id="accion" />
                      <input name="tipo" type="hidden" id="tipo" value="<?= $tipo ?>" />
                      <input name="id" type="hidden" id="id" value="<?= $var_id ?>" /></td>
                  </tr>
                  <tr valign="middle" bgcolor="fff0e1" height="50">
                    <td align="left" bgcolor="#fff0e1" ><? if($tipo != ""){
					  		
							switch ($tipo){
								case 1: $nombre_tipo = "Carpeta"; break;
								case 2: $nombre_tipo = "Sección"; break;
								case 3: $nombre_tipo = "Producto"; break;
								case 4: $nombre_tipo = "Link"; break;
								case 5: $nombre_tipo = "Imagen Titulo"; break;
							}
					  
					   ?>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio_bold"><?= $nombre_tipo ?></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo != ""){ ?>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Url:</td>
                          <td><label>
                          <input name="url" type="text" <? if($tipo == 1 || $tipo == 2 || $tipo == 3){ echo "disabled"; } ?> class="detalle_medio" id="url" value="<?= $var_link ?>" size="70" />
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
					  <br />
					  <? if($tipo != ""){
					  		
							$c=0;
							$query_titulo = "SELECT A.titulo, B.titulo_idioma, A.foto, A.ididioma
							FROM barra_menu_idioma A
							INNER JOIN idioma B ON A.ididioma = B.ididioma
							WHERE A.idbarra_menu = '$idbarra_menu'";
							$result_titulo = mysql_query($query_titulo);
							while($rs_titulo = mysql_fetch_assoc($result_titulo)){
								
					  ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio"><strong style="color:#006699"><?= $rs_titulo['titulo_idioma'] ?></strong></td>
                          <td class="detalle_medio"><label>
                          <input name="ididioma_editar[<?= $c ?>]" type="hidden" id="ididioma_editar[<?= $c ?>]" value="<?= $rs_titulo['ididioma'] ?>" />
                          </label></td>
                        </tr>
                        <tr>
                          <td align="right" class="detalle_medio">Titulo:</td>
                          <td><input name="titulo_editar[<?= $c ?>]" type="text" <? if($tipo == 1 || $tipo == 2 || $tipo == 3){ echo "disabled"; } ?> class="detalle_medio" id="titulo_editar[<?= $c ?>]" value="<?= $rs_titulo['titulo'] ?>" size="70" /></td>
                        </tr>
                      </table>
					  <? if($tipo == 5){ ?>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Foto:</td>
                          <td><label>
                            <input name="foto_editar[<?= $c ?>]" type="file" class="detalle_medio" id="foto_editar[<?= $c ?>]" size="55" />
                            <input name="foto_nombre[<?= $c ?>]" type="hidden" id="foto_nombre[<?= $c ?>]" value="<?= $rs_titulo['foto'] ?>" />
                          </label></td>
                        </tr>
						<? if($rs_titulo['foto']){ ?>
                        <tr>
                          <td width="110" align="right" class="detalle_medio">&nbsp;</td>
                          <td><table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFCF9F">

                            <tr>
                              <td><? $foto_seccion =& new obj0001(0,"../../../imagen/barra/",$rs_titulo['foto'],'','','','','','','','wmode=opaque',''); ?></td>
                            </tr>
                          </table></td>
                        </tr>
						<? 	
							}
						?>
                      </table>
                      <? 
					  	
						}
					  ?>
                      <br />
					  <? 	
					  			$c++;
					  		} // FIN WHILE
					  } //FIN IF
					  ?>
					  <input name="cantidad_idioma_editar" type="hidden" id="cantidad_idioma_editar" value="<?= $c ?>" />
					  <? if($tipo == 5 || $tipo == 4){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Target:</td>
                          <td><label>
                            <select name="target" class="detalle_medio" id="target">
                              <option value="_self" <? if($var_target == "_self"){ echo "selected"; } ?>>_self</option>
                              <option value="_blank" <? if($var_target == "_blank"){ echo "selected"; } ?>>_blank</option>
                              <option value="_parent" <? if($var_target == "_parent"){ echo "selected"; } ?>>_parent</option>
                              <option value="_top" <? if($var_target == "_top"){ echo "selected"; } ?>>_top</option>
                            </select>
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo != ""){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Orden:</td>
                          <td><label>
                          <input name="orden" type="text" class="detalle_medio" id="orden" value="<?= $var_orden ?>" size="7" />
                          </label></td>
                        </tr>
						<? if($_GET['ididioma'] != "" && $_GET['idsede'] != ""){ ?>
                        <tr>
                          <td width="110" align="right" class="detalle_medio"> Padre: </td>
                          <td>
                            <select name="idbarra_padre" class="detalle_medio" id="idbarra_padre" style="width:200px;">
								<option value="0" <? if($var_idbarra_padre == 0){ echo "selected"; } ?>>- Raiz -</option>
							<? 
								//NIVEL 1
								$query_barra_1 = "SELECT A.idbarra_menu, B.titulo
								FROM barra_menu A
								INNER JOIN barra_menu_idioma B ON A.idbarra_menu = B.idbarra_menu
								INNER JOIN barra_menu_sede C ON A.idbarra_menu = C.idbarra_menu
								WHERE A.idbarra_padre = '0' AND A.idbarra_menu != '$idbarra_menu' AND A.estado = '1' AND B.ididioma = '$_GET[ididioma]' AND C.idsede = '$_GET[idsede]' ";
								$result_barra_1 = mysql_query($query_barra_1);
								while($rs_barra_1 = mysql_fetch_assoc($result_barra_1)){ 
							?>
                              <option value="<?= $rs_barra_1['idbarra_menu'] ?>" <? if($var_idbarra_padre == $rs_barra_1['idbarra_menu']){ echo "selected"; } ?>><?= $rs_barra_1['titulo'] ?></option>
							<? } ?>
                            </select></td>
                        </tr>
						<? } ?>
                      </table>
					  <br />
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFE9D2">
                        <tr>
                          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="110" align="right" valign="top" class="detalle_medio">Sucursales: </td>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
									
									$query = "SELECT idsede
									FROM barra_menu_sede
									WHERE idbarra_menu = '$idbarra_menu' AND idsede = '$rs_sede[idsede]'";
									if(mysql_num_rows(mysql_query($query)) > 0 ){
										$check = "checked";
									}else{
										$check = "";
									}
								?>
                                  <tr>
                                    <td width="5%"><input type="checkbox" <?= $check ?> id="idsede[<?= $c ?>]" name="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? if($_GET['idsede'] == $rs_sede['idsede']){ echo "checked";} ?> <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?> /></td>
                                    <td width="95%" class="detalle_medio"><?= $rs_sede['titulo'] ?></td>
                                  </tr>
                                  <? 
								$c++;
								} 
								?>
                                </table>
                                  <span class="style2">
                                  <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" />
                                </span></td>
                            </tr>
                          </table></td>
                          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="110" align="right" valign="top" class="detalle_medio">Idiomas: </td>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <?
								  		  $c=0;
										  $query_ididioma = "SELECT ididioma, titulo_idioma 
										  FROM idioma
										  WHERE estado = '1'
										  ORDER BY titulo_idioma";
										  $result_ididioma = mysql_query($query_ididioma);
										  while ($rs_ididioma = mysql_fetch_assoc($result_ididioma)){
										  
										  			$query = "SELECT ididioma
													FROM barra_menu_idioma
													WHERE idbarra_menu = '$idbarra_menu' AND ididioma = '$rs_ididioma[ididioma]'";
													if(mysql_num_rows(mysql_query($query)) > 0 ){
														$check = "checked";
													}else{
														$check = "";
													}

							?>
                                  <tr>
                                    <td width="4%"><input name="ididioma[<?= $c ?>]" <?= $check ?> type="checkbox" id="ididioma[<?= $c ?>]" value="<?= $rs_ididioma['ididioma'] ?>" <? if($_GET['ididioma'] == $rs_ididioma['ididioma']){ echo "checked";} ?> /></td>
                                    <td width="96%" class="detalle_medio"><?= $rs_ididioma['titulo_idioma'] ?></td>
                                  </tr>
                                  <?  $c++; } ?>
                                </table>
                                  <input name="cantidad_idioma" type="hidden" id="cantidad_idioma" value="<?= $c ?>" /></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table>
					  <br />
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">&nbsp;</td>
                          <td><label>
                          <input name="Button" type="button" class="detalle_medio_bold" value="Guardar Cambios &raquo;" onclick="javascript:guardar_cambios()"/>
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
				    </td></tr>
                </table>
              </form></td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>