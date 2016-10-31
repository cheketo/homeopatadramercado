<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	include ("../0_includes/0_crear_miniatura.php");
	include ("../0_includes/0_clean_string.php");

	// localizacion de variables get y post:
	$idseccion = $_GET['idseccion'];
	$accion = $_POST['accion'];	
	$cont_producto = $_POST['cont_producto'];	
	$foto_extra_tipo = $_POST['foto_extra_tipo'];	
	$foto_extra_ididioma = $_POST['foto_extra_ididioma'];	
	$foto_extra_chica_ancho = $_POST['foto_extra_chica_ancho'];
	$foto_extra_grande_ancho = $_POST['foto_extra_grande_ancho'];
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$cantidad_registros = $_POST['cantidad_registros'];
		
	$foto_extra_orden_row = $_POST['foto_extra_orden_row'];
	$foto_extra_titulo_row = $_POST['foto_extra_titulo_row'];	
	
	//CARGO PARÁMETROS DE SECCION
	$query_par = "SELECT *
	FROM seccion_parametro
	WHERE idseccion_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
	//Variables de la fotos extra	
	$foto_extra_chica_ancho = $rs_parametro['foto_extra_chica'];// ancho maximo de la foto extra en tamaño chica	
	$foto_extra_grande_ancho = $rs_parametro['foto_extra_grande'];// ancho maximo de la foto extra en tamaño grande	
	$foto_extra_ruta_chica = "../../../imagen/seccion/extra_chica/"; // la ruta donde se va a guardar la foto extra chica
	$foto_extra_ruta_grande = "../../../imagen/seccion/extra_grande/"; // la ruta donde se va a guardar la foto extra grande

	
	
// Ingreso de producto:
if($accion == "mod6_insertar"){	

	for ($i=1; $i< $cont_producto+1 ; $i++){
	
		// INCORPORACION DE FOTO		
		if ($_FILES['foto_extra'.$i]['name'] != ''){
			
			for($c=0;$c<$cantidad_idioma;$c++){
				
				//CONSULTA DE IDIOMA
				$query_idioma = "SELECT reconocimiento_idioma
				FROM idioma
				WHERE ididioma = '$foto_extra_ididioma[$c]'";
				$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
				
				$archivo_ext = substr($_FILES['foto_extra'.$i]['name'],-4);
				$archivo_nombre = substr($_FILES['foto_extra'.$i]['name'],0,strrpos($_FILES['foto_extra'.$i]['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
			
				$foto =  $idseccion .'-'.$rs_idioma['reconocimiento_idioma'].'-'. rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto			
				
				if (!copy($_FILES['foto_extra'.$i]['tmp_name'], $foto_extra_ruta_grande . $foto)){ //si hay error en la copia de la foto
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
							};
					
							//ingreso de foto en tabla producto_foto
							$query_ins = "INSERT INTO seccion_foto 
							(idseccion, foto, titulo, foto_extra_tipo, ididioma, orden) 
							VALUES 
							('$idseccion','$foto', '$foto_extra_titulo_row[$i]', '$foto_extra_tipo', '$foto_extra_ididioma[$c]', '$foto_extra_orden_row[$i]')";
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
				
			} // FIN FOR IDIOMA
			
		} // FIN INCORPORACION DE FOTO
		
	}// FIN CONTAR FOTOS EXTRAS		
		
	echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."');</script>";
	
};		

	$query_producto = "SELECT titulo 
	FROM seccion_idioma_dato
	WHERE idseccion = '$idseccion' AND ididioma = 1";
	$rs_producto = mysql_fetch_assoc(mysql_query($query_producto));
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function mod6_validarRegistro(){
	formulario = document.frm;

	formulario.accion.value = 'mod6_insertar';
	formulario.submit();

};

function mod6_select_idcategoria(idcat){			
	formulario = document.frm;
	formulario.submit();
}
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Secci&oacute;n - Carga Multiple de Fotos Extra </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="detalle_medio"><hr size="1" noshade="noshade" style="color:#CCCCCC;" />
                &nbsp; <?= $idseccion.". ".$rs_producto['titulo'] ?><br /><br />
</td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="frm" id="frm">
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#999999">
                        <td height="40" bgcolor="#FF8282" class="titulo_medio_bold">Propiedades de las fotos extras :<span class="detalle_chico" style="color:#FF0000">
                          <input name="accion" type="hidden" id="accion" value="1" />
                        </span></td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td bgcolor="#FFE1E1"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tr>
                              <td width="19%" height="32" align="right" valign="middle" class="detalle_medio">Dimensiones:</td>
                              <td rowspan="2" align="left" valign="middle" class="detalle_medio"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="detalle_medio">
                                <tr>
                                  <td width="25%"> Tama&ntilde;o foto peque&ntilde;a:</td>
                                  <td width="75%" class="detalle_medio"><span>
                                    <input name="foto_extra_chica_ancho" type="text" class="detalle_medio" id="foto_extra_chica_ancho" value="<?= $foto_extra_chica_ancho ?>" size="4" />
                                  px ancho. </span></td>
                                </tr>
                                <tr>
                                  <td> Tama&ntilde;o foto grande: </td>
                                  <td class="detalle_medio"><span>
                                    <input name="foto_extra_grande_ancho" type="text" class="detalle_medio" id="foto_extra_grande_ancho" value="<?= $foto_extra_grande_ancho ?>" size="4" />
                                  px ancho. </span></td>
                                </tr>

                                                            </table></td>
                            </tr>
                            <tr >
                              <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                            </tr>
                            
                            <tr>
                              <td align="right" valign="top"  class="detalle_medio">Idiomas:</td>
                              <td align="left" valign="middle"  class="detalle_medio"><span>
                                <?
								  		  $c=0;
										  $query_ididioma = "SELECT ididioma, titulo_idioma 
										  FROM idioma
										  WHERE estado = '1'
										  ORDER BY titulo_idioma";
										  $result_ididioma = mysql_query($query_ididioma);
										  while ($rs_ididioma = mysql_fetch_assoc($result_ididioma))	  
										  {

										?><input name="foto_extra_ididioma[<?= $c ?>]" type="checkbox" id="foto_extra_ididioma[<?= $c ?>]" value="<?= $rs_ididioma['ididioma'] ?>" checked="checked" />
                                <?= $rs_ididioma['titulo_idioma'] ?>
                                <br />
                                <?  $c++; } ?>
                                <input name="cantidad_idioma" type="hidden" id="cantidad_idioma" value="<?= $c ?>" />
                              </span></td>
                            </tr>
                            <tr >
                              <td height="25" align="right" valign="middle"  class="detalle_medio">&nbsp; Ubicaci&oacute;n de la foto: </td>
                              <td rowspan="2" align="left" valign="top"><span >
                                </span>
                                <table width="100%" border="0" cellpadding="2" cellspacing="0" class="detalle_medio">
                                  <tr>
                                    <td width="4%"><input name="foto_extra_tipo" type="radio" value="1" checked="checked" /></td>
                                    <td width="5%"><img src="../../imagen/horizontal.gif" width="24" height="24" /></td>
                                    <td width="91%">Forma Horizontal. </td>
                                  </tr>
                                  <tr>
                                    <td width="4%"><input name="foto_extra_tipo" type="radio" value="2" /></td>
                                    <td width="5%"><img src="../../imagen/vertical.gif" width="24" height="24" /></td>
                                    <td>Forma Vertical. </td>
                                  </tr>
                                  <tr>
                                    <td width="4%"><input name="foto_extra_tipo" type="radio" value="3" /></td>
                                    <td width="5%"><img src="../../imagen/detalle.gif" width="24" height="24" /></td>
                                    <td>Dentro del detalle. </td>
                                  </tr>
                                </table>                              </td>
                            </tr>
                            <tr >
                              <td height="65" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                            </tr>
                            <tr >
                              <td align="right" valign="top"  class="detalle_medio">&nbsp;</td>
                              <td align="left" valign="top"  class="detalle_medio">Cantidad de fotos extra a cargar en forma simult&aacute;nea: 
                                <select name="cantidad_registros" class="detalle_medio" onchange="javascript:document.frm.submit();">
                                  <? 
								 	if($cantidad_registros == '' ){
										$cantidad_registros = 5;
								 	}
								 ?>
                                  <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?>>5</option>
                                  <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                                  <option value="15" <? if($cantidad_registros == 15){ echo "selected"; } ?>>15</option>
                                </select></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top"  class="detalle_medio_bold"><span class="detalle_chico" style="color:#FF0000"> </span></td>
                              <td align="left" valign="middle"  class="detalle_medio_bold"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                                <input name="Submit2222" type="button" class="detalle_medio_bold" onclick="mod6_validarRegistro();" value=" &gt;&gt;  Ingresar  " />
                              </span><br />
                                <br />

                              </span></td>
                            </tr>
                            <? 
	$cont_producto = 0;
	$vuelta_prod_cat = 0;
	for($i2 ; $i2<$cantidad_registros; $i2++){
		$cont_producto++;
		
		if($vuelta_prod_cat==0){
			$color_fila = 'bgcolor="#FFBBBB"';
			$vuelta_prod_cat = 1;
		}else{
			$color_fila = '';
			$vuelta_prod_cat = 0;
		};
?>
                            <tr <?= $color_fila?>>
                              <td height="6" colspan="2" ></td>
                            </tr>
                            <tr <?= $color_fila?>>
                              <td height="35" align="right"  class="detalle_medio_bold">Foto Extra 
                              <?= $cont_producto ?></td>
                              <td height="35" align="left" valign="middle"  class="detalle_medio_bold"><img src="../../imagen/iconos/image_add_mini.png" width="18" height="18" /></td>
                            </tr>
                            <tr <?= $color_fila?>>
                              <td align="right" valign="middle" class="detalle_medio"><span class="detalle_medio">Num. de Orden: </span></td>
                              <td>
                                <input name="foto_extra_orden_row[<?= $cont_producto ?>]" type="text" class="detalle_medio" id="foto_extra_orden_row[<?= $cont_producto ?>]" value="<? if($foto_extra_posicion_row[$cont_producto] != ""){ echo $foto_extra_posicion_row[$cont_producto]; }else{ echo $cont_producto; } ?>" size="10" />                              </td>
                            </tr>
                            <tr <?= $color_fila?>>
                              <td align="right" valign="middle" class="detalle_medio"><span class="detalle_medio">Titulo:</span></td>
                              <td><input name="foto_extra_titulo_row[<?= $cont_producto ?>]" type="text" class="detalle_medio" id="foto_extra_titulo_row[<?= $cont_producto ?>]" value="<?= $foto_extra_titulo_row[$cont_producto]  ?>" size="70" />
                                
                                <input name="cont_producto_row[<?= $cont_producto ?>]" type="hidden" id="cont_producto_row[<?= $cont_producto ?>]" value="<?= $cont_producto ?>" />
                                <a name="ancla_<?=$cont_producto?>" id="ancla_<?=$cont_producto?>"></a></td>
                            </tr>
                            <tr <?= $color_fila?>>
                              <td height="33" align="right" valign="middle" class="detalle_medio">Foto:</td>
                              <td><input name="foto_extra<?= $cont_producto ?>" type="file" class="detalle_medio" id="foto_extra<?= $cont_producto ?>" size="55" /></td>
                            </tr>
                            <?	}; ?>
                          </table>
                            <input name="cont_producto" type="hidden" id="cont_producto" value="<?=$cont_producto?>" />                        </td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td height="40" align="left" valign="middle" bgcolor="#FFE1E1"><span class="detalle_chico" style="color:#FF0000">
                          <input name="Submit22222" type="button" class="detalle_medio_bold" onclick="mod6_validarRegistro();" value=" &gt;&gt;  Ingresar  " />
                        </span></td>
                      </tr>
                    </table>
                    </form><br />
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="35" bgcolor="#FF8282" class="titulo_medio_bold">Fotos extras  de la secci&oacute;n:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFE1E1"><? //FOTO EXTRA
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
				
				$query_foto_cant = "SELECT COUNT(idseccion_foto)
				FROM seccion_foto 
				WHERE idseccion = '$idseccion' AND ididioma = '$rs_idioma[ididioma]'";
				$rs_foto_cant = mysql_fetch_row(mysql_query($query_foto_cant));
				if($rs_foto_cant[0]>0){
				
				?>
                            <table width="150" border="0" cellpadding="4" cellspacing="0">
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
                                      <td height="30" bgcolor="#FFBBBB"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr valign="middle" class="detalle_medio">
                                            <td align="left"><? if($rs_foto['foto_extra_tipo'] == 1){ ?>
                                                <img src="../../imagen/horizontal.gif" alt="barra horizontal habilitada" width="24" height="24" border="0" />
                                                <? }else{ ?>
                                                <img src="../../imagen/horizontal_gris.gif" alt="barra horizontal deshabilitada" width="24" height="24" border="0" />
                                                <? } ?>
                                                <? if($rs_foto['foto_extra_tipo'] == 2){ ?>
                                                <img src="../../imagen/vertical.gif" alt="barra vertical habilitada" width="24" height="24" border="0" />
                                                <? }else{ ?>
                                                <img src="../../imagen/vertical_gris.gif" alt="barra vertical deshabilitada" width="24" height="24" border="0" />
                                                <? } ?>
                                                <? if($rs_foto['foto_extra_tipo'] == 3){ ?>
                                                <img src="../../imagen/detalle.gif" alt="Insertar en detalle habilitado" width="24" height="24" border="0" />
                                                <? }else{ ?>
                                                <img src="../../imagen/detalle_gris.gif" alt="Insertar en detalle habilitado" width="24" height="24" border="0" />
                                                <? } ?>
                                            </td>
                                            <td align="right">&nbsp;</td>
                                            <td width="10" align="left"><a href="javascript:eliminar_foto_extra(<?=$rs_foto['idproducto_foto']?>);">
                                              <input name="idproducto_foto_row[<?= $foto_extra_cont ?>]" type="hidden" id="idproducto_foto_row[<?= $foto_extra_cont ?>]" value="<?= $rs_foto['idproducto_foto'] ?>" />
                                            </a></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="middle" bgcolor="#FFBBBB"><?
										if($rs_foto['foto'] == ''){
											$foto_xtra =& new obj0001('0','../../../swf/','no_disponible.swf',$ancho_maximo_mini,'','','','','','','wmode=Opaque',''); 
										}else{
											$foto_xtra =& new obj0001('0',$foto_extra_ruta_chica,$rs_foto['foto'],'150','','','',$foto_extra_ruta_grande.$rs_foto['foto'],'_blank','','',''); 
										}; 
										?></td>
                                    </tr>
                                    <tr>
                                      <td height="28" align="left" bgcolor="#FFBBBB" class="detalle_medio" >&nbsp; <?= $rs_foto['titulo']; ?></td>
                                    </tr>
                                    <tr>
                                      <td height="28" align="left" bgcolor="#FFBBBB" class="detalle_chico" >&nbsp; N&ordm; de Orden: <?= $rs_foto['orden'] ?></td>
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
						// FIN PRODUCTO FOTO ?></td>
                        </tr>
                    </table></td>
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