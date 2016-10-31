<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	include ("../0_includes/0_crear_miniatura.php");
	include ("../0_includes/0_clean_string.php");

	// localizacion de variables get y post:
	$accion = $_POST['accion'];	
	$eliminar = $_POST['eliminar'];	
	$cont_producto = $_POST['cont_producto'];	
	$foto_ancho = $_POST['foto_ancho'];
	$foto_extra_titulo_row = $_POST['foto_extra_titulo_row'];	
	$idne_newsletter = $_GET['idne_newsletter'];	
	
	//Variables de la fotos extra	
	$foto_newsletter_ruta = "../../../imagen/newsletter/"; // la ruta donde se va a guardar la foto extra chica

	//FUNCION PAGINAR
	function paginar($actual, $total, $por_pagina, $enlace) {
	  $total_paginas = ceil($total/$por_pagina);
		  $anterior = $actual - 1;
		  $posterior = $actual + 1;
		  $intervalo = 20;
		  
		  if ($actual>1)
			$texto = "<a href=\"javascript:ir_pagina('$anterior');\">&laquo;</a> ";
		  else
			$texto = "<b>&laquo;</b> ";
			
		  $comienzo = $actual-($intervalo/2);
		  if($comienzo < 1) $comienzo = 1;
		  for ($i=$comienzo; $i<$actual; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
		  
		  $texto .= "<b>$actual</b> ";
		  
		  $fin = $comienzo + $intervalo;
		  if($total_paginas <= $fin) $fin = $total_paginas;
		  for ($i=$actual+1; $i<=$fin; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
			
		  
		  if ($actual<$total_paginas)
			$texto .= "<a href=\"javascript:ir_pagina('$posterior');\">&raquo;</a>";
		  else
			$texto .= "<b>&raquo;</b>";
			
		  return $texto;
	}
    
	//CHEQUEO PARA PAGINAR
	$cantidad_registros = $_POST['cant'];
	if(!$cantidad_registros){
		$cantidad_registros = 20;
	}
	
	$pag = $_POST['pag'];
	if(!$pag){
		$pag = 1;
	}
	$puntero = ($pag-1) * $cantidad_registros;

	//PAGINACION: Cantidad Total.
	$query_cant = "SELECT *
						FROM ne_foto 
						WHERE estado = '1'  AND idne_newsletter = 0
						ORDER BY idne_foto";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));

	//ELIMINAR FOTO
	if($accion == "eliminar_foto"){	
	
		$query_sel = "SELECT foto FROM ne_foto WHERE idne_foto = '$eliminar'  AND idne_newsletter = 0";
		$rs_foto = mysql_fetch_assoc(mysql_query($query_sel));
		
		if(file_exists($foto_newsletter_ruta.$rs_foto['foto'])){
			unlink($foto_newsletter_ruta.$rs_foto['foto']);
		}
	
		$query_del = "DELETE FROM ne_foto WHERE idne_foto = '$eliminar'  AND idne_newsletter = 0";
		mysql_query($query_del);
	
	}
	
	//VINCULAR FOTO
	if($accion == "vincular"){	
	
		$id_vincular = $_POST['id_vincular'];
		
		$query_sel = "SELECT nombre, foto, estado FROM ne_foto WHERE idne_foto = '$id_vincular' AND idne_newsletter = 0";
		$rs_foto_vincular = mysql_fetch_assoc(mysql_query($query_sel));
		
		$nombre_foto_nueva = $idne_newsletter."-".rand(0,999)."-".$rs_foto_vincular['foto'];
		
		if (!copy($foto_newsletter_ruta.$rs_foto_vincular['foto'], $foto_newsletter_ruta.$nombre_foto_nueva)){ //si hay error en la copia de la foto
			echo "<script>alert('Hubo un error al vincular la foto.')</script>"; // se muestra el error.
		}else{
			$query_ins = "INSERT INTO ne_foto (idne_newsletter, nombre, foto, estado) VALUES ('$idne_newsletter', '$rs_foto_vincular[nombre]','$nombre_foto_nueva','$rs_foto_vincular[estado]')";
			mysql_query($query_ins);
			$se_vinculo = 1;
		}
		
	}
	
	
	// Ingreso de producto:
	if($accion == "mod6_insertar"){	
	
		for ($i=1; $i<=$cont_producto ; $i++){
		
			// INCORPORACION DE FOTO		
			if ($_FILES['foto_extra'.$i]['name'] != ''){
				
				$archivo_ext = substr($_FILES['foto_extra'.$i]['name'],-4);
				$archivo_nombre = substr($_FILES['foto_extra'.$i]['name'],0,strrpos($_FILES['foto_extra'.$i]['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
			
				$foto = 'gral' .'-'. rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto			
				
				if (!copy($_FILES['foto_extra'.$i]['tmp_name'], $foto_newsletter_ruta . $foto)){ //si hay error en la copia de la foto
					$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
					echo "<script>alert('".$error."')</script>"; // se muestra el error.
				}else{
					$imagesize = getimagesize($foto_newsletter_ruta.$foto);
				
					if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
						$peso = number_format((filesize($foto_newsletter_ruta.$foto))/1024,2);
	
						if($peso==0){
							$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
							echo "<script>alert('".$error2."')</script>"; // se muestra el error.
						}else{
						
							//SI TODO SALIO BIEN:
							if($_POST['foto_ancho']){
								$foto_extra_grande_ancho_sel = $_POST['foto_ancho'];
							}else{
								$foto_extra_grande_ancho_sel = $foto_ancho;
							}
							
		
							//ACHICAR IMAGEN AL ANCHO MÁXIMO:
							if ($imagesize[0] > $foto_extra_grande_ancho_sel){
								$alto_nuevo = ceil($foto_extra_grande_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_newsletter_ruta.$foto, $foto_extra_grande_ancho_sel, $alto_nuevo, $foto_newsletter_ruta);
							};
							
					
							//ingreso de foto en tabla producto_foto
							$query_ins = "INSERT INTO ne_foto 
							(foto, nombre, estado) 
							VALUES 
							('$foto', '$foto_extra_titulo_row[$i]','1')";
							mysql_query($query_ins);
		
						};	
					
					}else{
					
						$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
						echo "<script>alert('".$error3."')</script>"; // se muestra el error.
						
						if(!unlink($foto_newsletter_ruta.$foto)){ //se elimina el archivo subido
							$error4 = "El archivo no pudo elminarse. ";
							echo "<script>alert('".$error4."')</script>"; // se muestra el error.
						}else{
							$error5 = "El archivo fue elminado. ";
							echo "<script>alert('".$error5."')</script>"; // se muestra el error.
						};
					
					};
			
				};
				
			} // FIN INCORPORACION DE FOTO
			
		}// FIN CONTAR FOTOS EXTRAS		
		
	};		

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

	function eliminar_foto(id){
		formulario = document.frm;
		formulario.eliminar.value = id;
		formulario.accion.value = 'eliminar_foto';
		formulario.submit();
	
	};
	
	function vincular(id){
		formulario = document.frm;
		formulario.id_vincular.value = id;
		formulario.accion.value = 'vincular';
		formulario.submit();
	
	};
	
	function ir_pagina(pag){
		formulario = document.frm;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function validar_form_lista(){
		formulario = document.frm;
		formulario.pag.value = "1";
		formulario.submit();
	};
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Newsletter - Galer&iacute;a de Im&aacute;genes General </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="detalle_medio"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="frm" id="frm">
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#999999">
                        <td height="35" bgcolor="#91B591" class="titulo_medio_bold">Galer&iacute;a de Fotos:
                        <input name="eliminar" type="hidden" id="eliminar" />
                        <input name="id_vincular" type="hidden" id="id_vincular" />
                        <span class="detalle_chico" style="color:#FF0000">
                        <input name="accion" type="hidden" id="accion" value="1" />
                        </span></td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td align="center" bgcolor="#B5E6CD"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="50%" align="left" valign="middle" class="detalle_medio"><label><span><? echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']."?cant=".$cantidad_registros."&pag=" ); ?></span></label></td>
                            <td width="41%" align="right" valign="middle" class="detalle_medio"><input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                            Registros por hoja </td>
                            <td width="9%" valign="middle" class="detalle_medio"><select name="cant" class="detalle_medio" onchange="validar_form_lista();">
                                <option value="20" <? if($cantidad_registros == 20){ echo "selected"; } ?> >20</option>
                                <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                                <option value="100"<? if($cantidad_registros == 100){ echo "selected"; } ?> >100</option>
                            </select></td>
                          </tr>
                          <tr height="5">
                            <td colspan="2" align="right" valign="middle" class="detalle_medio"></td>
                            <td valign="middle" class="detalle_medio"></td>
                          </tr>
                        </table>
						  <? if($se_vinculo == 1){ ?>
						  <div align="center">
							<table width="150" border="1" cellpadding="4" cellspacing="0" bordercolor="#7CA77C" bgcolor="#91B591">
                            <tr>
                              <td align="center" class="detalle_medio_bold_white">Se v&iacute;nculo la imagen correctamente. </td>
                            </tr>
                          </table>
					        </div>
						  <? } ?>
						  
                          <?
				
				if($cantidad_total>0){
				
				?>
                          <table width="150" border="0" cellpadding="4" cellspacing="0" valign="bottom">
                              <tr valign="bottom">
                                <? 
						
						$query_foto = "SELECT *
						FROM ne_foto 
						WHERE estado = '1' AND idne_newsletter = 0
						ORDER BY idne_foto
						LIMIT $puntero,$cantidad_registros";
						$result_foto = mysql_query($query_foto);
						$vuelta_foto = 1;
						while($rs_foto = mysql_fetch_assoc($result_foto)){
								$hay_fotos = true;
								$foto_extra_cont++;
							
							 ?>
                                <td align="center" valign="top" class="ejemplo_12px" ><table width="150" border="1" cellpadding="0" cellspacing="0" bordercolor="#A4C1A4">
                                    <tr>
                                      <td height="30" align="center" bgcolor="#A4C1A4"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                                          <tr valign="middle" class="detalle_medio">
                                            <td width="84%" align="left"><? if($idne_newsletter != ""){ ?>
                                            <a href="javascript:vincular(<?=$rs_foto['idne_foto']?>);"><img src="../../imagen/vincular.png" alt="Copiar a la Galeria Particular" width="16" height="16" border="0" /></a>                                              <? } ?></td>
                                            <td width="16%" align="right"><? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?><a href="javascript:eliminar_foto(<?=$rs_foto['idne_foto']?>);"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a><? } ?></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="middle" bgcolor="#A4C1A4"><?
											$foto_xtra =& new obj0001('0',$foto_newsletter_ruta,$rs_foto['foto'],'150','','','',$foto_newsletter_ruta.$rs_foto['foto'],'_blank','','',''); 
										?></td>
                                    </tr>
                                    <tr>
                                      <td height="28" align="left" bgcolor="#A4C1A4" class="detalle_medio" >&nbsp;
                                          <?= $rs_foto['nombre']; ?></td>
                                    </tr>
                                </table></td>
                                <?	
								if($vuelta_foto == 4){ 
									echo '</tr><tr valign="bottom">';
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
                                <td height="40" align="center" valign="middle" class="detalle_medio">No se han cargado fotos.</td>
                              </tr>
                            </table>
                        <? }; // FIN IF CANT
							  
						// FIN PRODUCTO FOTO ?></td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td align="left" valign="middle" bgcolor="#B5E6CD" class="detalle_medio" height="0"><span><? echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']."?cant=".$cantidad_registros."&pag=" ); ?></span></td>
                      </tr>
                    </table>
					<? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#999999">
                        <td height="40" bgcolor="#91B591" class="titulo_medio_bold">Propiedades de las im&aacute;genes:</td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td bgcolor="#B5E6CD"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tr>
                              <td width="19%" align="right" valign="middle" class="detalle_medio">Dimensiones:</td>
                              <td align="left" valign="middle" class="detalle_medio"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="detalle_medio">
                                <tr>
                                  <td width="15%"> Tama&ntilde;o foto:</td>
                                  <td width="85%" class="detalle_medio"><span>
                                    <input name="foto_ancho" type="text" class="detalle_medio" id="foto_ancho" value="150" size="5" />
                                  px ancho. </span></td>
                                </tr>

                                                            </table></td>
                            </tr>
                            
                            <tr >
                              <td align="right" valign="top"  class="detalle_medio">&nbsp;</td>
                              <td align="left" valign="top"  class="detalle_medio">Cantidad de fotos extra a cargar en forma simult&aacute;nea: 
                                <select name="cantidad_fotos" class="detalle_medio" id="cantidad_fotos" onchange="javascript:document.frm.submit();">
                                  <? 
								 	if($cantidad_fotos == '' ){
										$cantidad_fotos = 5;
								 	}
								 ?>
                                  <option value="5" <? if($cantidad_fotos == 5){ echo "selected"; } ?>>5</option>
                                  <option value="10" <? if($cantidad_fotos == 10){ echo "selected"; } ?>>10</option>
                                  <option value="15" <? if($cantidad_fotos == 15){ echo "selected"; } ?>>15</option>
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
	for($i2=0 ; $i2<$cantidad_fotos; $i2++){
	
		$cont_producto++;
		
		if($vuelta_prod_cat==0){
			$color_fila = 'bgcolor="#A4C1A4"';
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
                              <td height="35" align="right"  class="detalle_medio_bold">Foto 
                              <?= $cont_producto ?></td>
                              <td height="35" align="left" valign="middle"  class="detalle_medio_bold"><img src="../../imagen/iconos/image_add_mini.png" width="18" height="18" /></td>
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
                        <input name="cont_producto" type="hidden" id="cont_producto" value="<?=$cont_producto?>" /></td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td height="40" align="left" valign="middle" bgcolor="#B5E6CD"><span class="detalle_chico" style="color:#FF0000">
                          <input name="Submit22222" type="button" class="detalle_medio_bold" onclick="mod6_validarRegistro();" value=" &gt;&gt;  Ingresar  " />
                        </span></td>
                      </tr>
                    </table>
					<? } ?>
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