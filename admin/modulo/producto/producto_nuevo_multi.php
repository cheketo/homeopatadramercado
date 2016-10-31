<?

	include ("../../0_mysql.php"); 
	include ("../0_includes/0_crear_miniatura.php"); 
	include ("../0_includes/0_clean_string.php"); 
	
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
	}else{
		$obj_value = '';
	}
	
	//FILTRADO
	//si biene por get idcarpeta, es para el menu de iconos del home
	if(isset($_GET['idcarpeta'])){	
		$filtro_get_idcarpeta = " AND A.idcarpeta = $_GET[idcarpeta]";
	}else{
		$filtro_get_idcarpeta = " AND A.idcarpeta_padre = 0";
	}
	
	//CARGO PARÁMETROS DE PRODUCTO
	$query_par = "SELECT *
	FROM producto_parametro
	WHERE idproducto_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));

	$foto_chica_ancho = $rs_parametro['foto_chica'];// ancho maximo de la foto en tamaño chica	
	$foto_mediana_ancho = $rs_parametro['foto_mediana'];// ancho maximo de la foto en tamaño mediana
	$foto_grande_ancho = $rs_parametro['foto_grande'];// ancho maximo de la foto en tamaño grande
	$foto_ruta_chica = "../../../imagen/producto/chica/"; // la ruta donde se va a guardar la foto chica
	$foto_ruta_mediana = "../../../imagen/producto/mediana/"; // la ruta donde se va a guardar la foto mediana
	$foto_ruta_grande = "../../../imagen/producto/grande/"; // la ruta donde se va a guardar la foto grande	
	
	// localizacion de variables get y post:
	$accion = $_POST['accion'];
	$titulo = $_POST['titulo'];
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];
	
	$fecha_hoy = date("Y-m-d");
	
	$cont_producto = $_POST['cont_producto'];	
	$posicion_row = $_POST['posicion_row'];
	$titulo_row = $_POST['titulo_row'];
	$foto_row = $_POST['foto_row'];

	//Sistema de selector de carpeta
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
	
	//INGRESO PRODUCTOS:
	if($accion == "mod6_insertar"){
		
		$prod_nuevo = 0;
		
		for ($i=1; $i< $cont_producto+1 ; $i++){
				
			if($titulo_row[$i]){
			
				$prod_nuevo++;
					
				// INCORPORACION DE FOTO
				if ($_FILES['foto'.$i]['name'] != ''){
			
				
					$archivo_ext = substr($_FILES['foto'.$i]['name'],-4);
					$archivo_nombre = substr($_FILES['foto'.$i]['name'],0,strrpos($_FILES['foto'.$i]['name'], "."));
					
					$archivo = clean_string($archivo_nombre) . $archivo_ext;
					$archivo = strtolower($archivo);
						
					$foto =  rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
					
					if (!copy($_FILES['foto'.$i]['tmp_name'], $foto_ruta_grande.$foto)){ //si hay error en la copia de la foto
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
								}
								
								//ACHICAR IMAGEN AL ANCHO MÁXIMO de la foto mediana:	
								if ($imagesize[0] > $foto_mediana_ancho_sel){						
									$alto_nuevo = ceil($foto_mediana_ancho_sel * $imagesize[1] / $imagesize[0]) ;
									crear_miniatura($foto_ruta_grande.$foto, $foto_mediana_ancho_sel, $alto_nuevo, $foto_ruta_mediana);
								}else{
									crear_miniatura($foto_ruta_grande.$foto, $imagesize[0], $imagesize[1], $foto_ruta_mediana);
								}
														
								//CREAR MINI AL ANCHO MÁXIMO chico:
								if ($imagesize[0] > $foto_chica_ancho_sel){
									$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
									crear_miniatura($foto_ruta_mediana.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica);
								}else{
									crear_miniatura($foto_ruta_mediana.$foto, $imagesize[0], $imagesize[1], $foto_ruta_chica);
								}
			
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
			
				//ingreso en tabla producto
				$query_ingreso = "INSERT INTO producto (
				  fecha_alta
				, estado
				, foto
				) VALUES (
				  '$fecha_hoy'
				, '2'
				, '$foto'
				)";
				$res_ingreso = mysql_query($query_ingreso);
				
				if($res_ingreso == 1){
			
					//peticion del ultimo producto en tabla producto
					$query_max = "SELECT MAX(idproducto) as idproducto FROM producto";
					$rs_max = mysql_fetch_assoc(mysql_query($query_max));
					
					$idproducto_row[$i] = $rs_max['idproducto'];
					$idproducto_row_split .= "idproducto_".$i."=".$rs_max['idproducto']."&";
					
					//COPIO A CARPETA
					$query_ingreso = "INSERT INTO producto_carpeta (
					  idcarpeta
					, idproducto
					, orden
					) VALUES (
					  '$mod6_sel_idcarpeta'
					, '$rs_max[idproducto]'
					, '$posicion_row[$i]'
					)";
					$res_copia_carpeta = mysql_query($query_ingreso);
					
					if($res_copia_carpeta == 1){
					
						//IDIOMA
						$query_idioma = "SELECT ididioma, reconocimiento_idioma, valor_defecto
						FROM idioma 
						WHERE estado = '1'
						ORDER BY ididioma";
						$result_idioma = mysql_query($query_idioma);
						while($rs_idioma = mysql_fetch_assoc($result_idioma)){	
						
							//PLANTILLA E IVA
							$query_plantilla = "SELECT plantilla_producto, idca_iva
							FROM carpeta 
							WHERE idcarpeta = '$mod6_sel_idcarpeta' ";
							$rs_plantilla = mysql_fetch_assoc(mysql_query($query_plantilla));
						
							if($rs_idioma['ididioma']==1){
								$titulo_producto = $titulo_row[$i];
							}else{
								$titulo_producto = $titulo_row[$i]." (".$rs_idioma['reconocimiento_idioma'].")";
							}
							
							//PONER IVA PREDEFINIDO
							$query_upd_iva = "UPDATE producto SET 
							idca_iva = '$rs_plantilla[idca_iva]'
							WHERE idproducto = '$rs_max[idproducto]' ";
							mysql_query($query_upd_iva);
							
							//ingreso en tabla categoria idioma
							$query_idioma_ingreso = "INSERT INTO producto_idioma_dato (
							  idproducto
							, ididioma
							, titulo
							, detalle
							, estado
							) VALUES (
							  '$rs_max[idproducto]'
							, '$rs_idioma[ididioma]'
							, '$titulo_producto'
							, '$rs_plantilla[plantilla_producto]'
							, '$rs_idioma[valor_defecto]'
							)";
							mysql_query($query_idioma_ingreso);
							
						}//FIN IDIOMA
						
						//SEDE
						$cantidad_sede = $_POST['cantidad_sede'];
						$sede = $_POST['sede'];
						
						for($c=0;$c<$cantidad_sede;$c++){
							$query_insert = "INSERT INTO producto_sede(
							  idproducto
							, idsede
							)VALUES(
							  '$rs_max[idproducto]'
							, '$sede[$c]'
							)";
							mysql_query($query_insert);
						
						}//FIN SEDE
						
					}else{//FIN SI SE COPIO A CARPETA
						echo "Error: No se puedieron cargar el/los producto/s en la carpeta seleccionada.";
					}
				}else{//FIN SI SE INGRESO CORRECTAMENTE
					echo "Error: No se puedieron cargar el/los producto/s.";
				}
			}//SI HAY TITULO
		
		}//FOR
		
		$accion = "ingresado";
		
	};//ACCION INSERTAR


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function mod6_validarRegistro(){
	formulario = document.form;
	if (formulario.mod6_idcarpeta.value == "") {
		alert("Debe seleccionar una carpeta.");
	} else {	
		formulario.accion.value = 'mod6_insertar';
		formulario.submit();
	};
};

function mod6_select_idcategoria(idcat){			
	formulario = document.form;
	formulario.submit();
}

<? if($accion == "ingresado"){ echo "document.form.reset();"; } ?>

</script>
</head>
<body>
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto - Nuevo Multiple </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Seleccione una Carpeta a la que pertenecer&aacute;n los  productos:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                            <script language="JavaScript" type="text/javascript">
var i;
function cambia(paso){  

    var formulario = document.form; 
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
                                    <select name="mod6_idcarpeta" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta" onchange="cambia(1)">
                                      <option value="" selected="selected">--- Seleccionar Carpeta</option>
                                      <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND B.ididioma = '1' $filtro_get_idcarpeta
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta)){
	  
	  	if ($carpeta1 == $rs_mod6_idcarpeta['idcarpeta'])
		{
			$mod6_sel_idcarpeta = "selected";
		}else{
			$mod6_sel_idcarpeta = "";
		}
?>
                                      <option  <?= $mod6_sel_idcarpeta ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
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
                                    <select name="mod6_idcarpeta2"  style="width:200px;" class="detalle_medio" id="mod6_idcarpeta2" onchange="cambia(2)">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:1px;"></div>
                                <div id="tr_carpeta3" style="display:none">
                                    <div id="colum_carpeta">Carpeta 3&ordm;</div>
                                  <div id="colum_selector">
                                    <select name="mod6_idcarpeta3"  style="width:200px;" class="detalle_medio" id="mod6_idcarpeta3" onchange="cambia(3)">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:1px;"></div>
                                <div id="tr_carpeta4" style="display:none">
                                    <div id="colum_carpeta">Carpeta 4&ordm;</div>
                                  <div id="colum_selector">
                                    <select name="mod6_idcarpeta4"  style="width:200px;" class="detalle_medio" id="mod6_idcarpeta4" onchange="">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:1px;"></div></td>
                            </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="18%" align="right" valign="top">Para las sucursales: </td>
                                <td width="82%"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                    <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1
								ORDER BY titulo";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
								?>
                                    <tr>
                                      <td width="5%"><input type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? 
									  if(!$idsede_log){ 
									  	echo 'checked="checked"'; 
									  }else{
									  	if($idsede_log != $rs_sede['idsede']){ echo $obj_value; }else{ echo 'checked="checked"'; }
									  } 
									  
									  ?> /></td>
                                      <td width="95%"><?= $rs_sede['titulo'] ?></td>
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
                        </tr>
                    </table>
                      <table width="100%" height="40" border="0" cellpadding="5" cellspacing="0">
                        <tr>
                          <td width="92%" align="right" valign="middle">Cantidad de productos: </td>
                          <td width="8%" align="right" valign="middle">
                            <select name="cantidad_registros" style="width:60px;" class="detalle_medio" onchange="javascript:document.form.submit();">
                              <? 
								 	if($cantidad_registros == '' ){
										$cantidad_registros = 5;
								 	}
								 ?>
                              <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?>>5</option>
                              <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                              <option value="15" <? if($cantidad_registros == 15){ echo "selected"; } ?>>15</option>
                            </select>
                          </td>
                        </tr>
                      </table>
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese el t&iacute;tulo de los nuevos productos:</td>
                          <td align="right" valign="middle" bgcolor="#FFDDBC" class="titulo_medio_bold"><span class="detalle_chico" style="color:#FF0000">
                            <input name="Submit2222" type="button" class="detalle_medio_bold" onclick="mod6_validarRegistro();" value="  Ingresar &raquo; " />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td colspan="2" bgcolor="#FFF0E1"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
                              <? 
	$cont_producto = 0;
	$vuelta_prod_cat = 0;
	for($i2 ; $i2<$cantidad_registros; $i2++){
		$cont_producto++;
		
		if($vuelta_prod_cat==0){
			//$color_fila = 'bgcolor="#FFD9B3"';
			$vuelta_prod_cat = 1;
		}else{
			//$color_fila = 'bgcolor="#FFF5EC"';
			$vuelta_prod_cat = 0;
		};
?>
                              <tr <?= $color_fila?>>
                                
                                <td width="12%" height="10"><span class="detalle_chico">
                                  <?=$cont_producto?>.
                                  <input name="cont_producto_row[<?= $cont_producto ?>]" type="hidden" id="cont_producto_row[<?= $cont_producto ?>]" value="<?= $cont_producto ?>" />
                                  <a name="ancla_<?=$cont_producto?>" id="ancla_<?=$cont_producto?>"></a></span></td>
                                <td width="88%" height="10" colspan="2"></td>
                              </tr>
                              <tr <?= $color_fila?>>
                                <td>Titulo:</td>
                                <td colspan="2"><input name="titulo_row[<?= $cont_producto ?>]" type="text" class="detalle_medio" id="titulo_row[<?= $cont_producto ?>]" size="70" /></td>
                              </tr>
                              <tr <?= $color_fila?>>
                                <td>Foto:</td>
                                <td colspan="2"><input name="foto<?= $cont_producto ?>" type="file" class="detalle_medio" id="foto<?= $cont_producto ?>" size="70" /></td>
                              </tr>
                              <tr <?= $color_fila?>>
                                <td>N&ordm; de Orden:</td>
                                <td colspan="2"><input name="posicion_row[<?= $cont_producto ?>]" type="text" class="detalle_medio" id="posicion_row[<?= $cont_producto ?>]" size="5" /></td>
                              </tr>
                              <tr <?= $color_fila?>>
                                <td colspan="4"><hr width="100%" size="1" />
                                <br /></td>
                              </tr>
                              <?	}; ?>
                            </table>
                              <span class="detalle_medio_bold">
                              <input name="cont_producto" type="hidden" id="cont_producto" value="<?=$cont_producto?>" />
                            </span>
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td><span class="detalle_chico" style="color:#FF0000">
                                    <input name="Submit22222" type="button" class="detalle_medio_bold" onclick="mod6_validarRegistro();" value="  Ingresar &raquo; " />
                                  </span></td>
                                </tr>
                              </table></td>
                        </tr>
                    </table>
                    </form>
                      <br />
					  <? if( $accion == "ingresado" && $prod_nuevo > 0 ){ ?>
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">&nbsp; Productos Cargados:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#eafcf7">
						  
						  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr bgcolor="#FFF0E1">
                                <td width="21" height="30" align="center" valign="middle" bgcolor="#DDF7EF" class="detalle_medio_bold">ID </td>
                                <td height="30" colspan="2" align="left" bgcolor="#DDF7EF" class="detalle_medio_bold">Titulo</td>
                              </tr>
    <? 

	if($prod_nuevo > 0){
		$filtro_producto = " LIMIT $prod_nuevo ";
	}
	
	$vuelta_prod_cat = 0;
	$query_prod_cargado = "SELECT A.idproducto, B.titulo
	FROM producto A
	LEFT JOIN producto_idioma_dato B ON B.idproducto = A.idproducto	
	WHERE B.ididioma = '1'
	ORDER BY A.idproducto DESC
	$filtro_producto";
	$result_prod_cargado = mysql_query($query_prod_cargado);	
	while($rs_prod_cargado = mysql_fetch_assoc($result_prod_cargado)){
		
		if($vuelta_prod_cat==0){
			$color_fila = '';
			$vuelta_prod_cat = 1;
		}else{
			$color_fila = 'bgcolor="#DDF7EF"';
			$vuelta_prod_cat = 0;
		};
		
		
		
?>
                              <tr <?= $color_fila?>>
                                <td align="left" class="detalle_chico"><?= $rs_prod_cargado['idproducto'] ?>.</td>
                                <td width="620" align="left" valign="middle" class="detalle_medio"><?= $rs_prod_cargado['titulo'] ?></td>
                                <td width="17" align="right" valign="middle" class="detalle_medio"><a href="producto_editar.php?idproducto=<?= $rs_prod_cargado['idproducto'] ?>" target="_blank"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a></td>
                              </tr>
                              <?	}; ?>
                          </table>
						  </td>
                        </tr>
                    </table>
					<? } ?></td>
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