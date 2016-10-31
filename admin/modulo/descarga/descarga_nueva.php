<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? 
	
	include ("../0_includes/0_clean_string.php"); 

	// localizacion de variables get y post:	
	$accion = $_POST['accion'];
	$msj = "";

	$ruta_descarga = "../../../descarga/";
	
	$titulo = $_POST['titulo'];
	$archivo_ftp = $_POST['archivo_ftp'];	
	$archivo = $_POST['archivo'];
	$idtipo_descarga = $_POST['idtipo_descarga'];
	
	$tipo_mostrar = $_POST['tipo_mostrar'];
	$idproducto = $_POST['idproducto'];
	$idseccion = $_POST['idseccion'];

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
	
	//SETEAR LA DESCARGA SEGUN DONDE SE MUESTRA
	switch($tipo_mostrar){
	
		case '1':
			$idcarpeta_des = $mod6_sel_idcarpeta;
			$seccion_des = 0;
			$producto_des = 0;
			break;
			
		case '2':
			$idcarpeta_des = 0;
			$seccion_des = $idseccion;
			$producto_des = 0;
			break;
			
		case '3':
			$idcarpeta_des = 0;
			$seccion_des = 0;
			$producto_des = $idproducto;
			break;
	}
	
	//TOMO PARAMETROS DE LA DESCARGA
	$query_par = "SELECT *
	FROM descarga_parametro
	WHERE iddescarga_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
	if($rs_parametro['usa_prefijo'] == 1){
		$prefijo = $rs_parametro['prefijo'].'-';
	}else{
		$prefijo = "";
	}
	
	//INGRESO LA DESCARGA
	if($accion == "ingresar"){
		
		// INCORPORACION DE FOTO		
		if ($_FILES['archivo']['name'] != ''){
		
			$archivo_ext = substr($_FILES['archivo']['name'],-4);
			$archivo_nombre = substr($_FILES['archivo']['name'],0,strrpos($_FILES['archivo']['name'], "."));
			
			$archivo = clean_string($archivo_nombre);
			$archivo = strtolower($archivo);
		
			$archivo = $prefijo.$archivo;
			if($rs_parametro['usa_random'] == 1){
				$archivo .= '('.rand(0,999).')';
			}
			$archivo =  $archivo.$archivo_ext;
			
			if (!copy($_FILES['archivo']['tmp_name'], $ruta_descarga.$archivo)){ //si hay error en la copia de la foto
				echo "<script>alert('Hubo un error al subir el archivo. ')</script>"; // se muestra el error.
			}else{

				if($_FILES['archivo']['size'] > 0){
				
					$query_ingreso = "INSERT INTO descarga (
					  titulo
					, idtipo_descarga
					, archivo
					, idcarpeta
					, idseccion
					, idproducto
					, restringido
					, estado
					) VALUES (
					  '$titulo'
					, '$idtipo_descarga'
					, '$archivo'
					, '$idcarpeta_des'
					, '$seccion_des'
					, '$producto_des'
					, '$rs_parametro[valor_restringido]'
					, '1'
					)";
					mysql_query($query_ingreso);
					$msj="Se ha ingresado correctamente su descarga";
					
				}else{
					
					echo "<script>alert('El archivo fue subido incorrectamente.')</script>"; // se muestra el error.
					
					if(!unlink($ruta_descarga.$archivo)){ //se elimina el archivo subido
						echo "<script>alert('El archivo no pudo elminarse. ')</script>";
					}else{
						echo "<script>alert('El archivo fue elminado. ')</script>"; 
					}
					
				}
		
			}
		}else{
		
			if($archivo_ftp){
			
				$query_ingreso = "INSERT INTO descarga (
				  titulo
				, idtipo_descarga
				, archivo
				, idcarpeta
				, idseccion
				, idproducto
				, restringido
				, estado
				) VALUES (
				  '$titulo'
				, '$idtipo_descarga'
				, '$archivo_ftp'
				, '$idcarpeta_des'
				, '$seccion_des'
				, '$producto_des'
				, '$rs_parametro[valor_restringido]'
				, '1'
				)";
				mysql_query($query_ingreso);
				$msj="Se ha ingresado correctamente su descarga";
			}
		
		}//FIN FOTO
	};


?>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_descarga(){
		var formulario = document.form;
		var flag = true;
		var error = "";
		
		if(formulario.titulo.value == ''){
			error = "Debe seleccionar el titulo.\n";
			flag = false;
		}
		
		if(formulario.archivo.value == '' && formulario.archivo_ftp.value == ''){
			error = error + "Debe seleccionar el archivo.\n";
			flag = false;
		}
		
		if(formulario.tipo_mostrar[0].checked == false && formulario.tipo_mostrar[1].checked  == false && formulario.tipo_mostrar[2].checked  == false){
			error = error + "Debe seleccionar donde se aplicara la descarga.\n";
			flag = false;
		}
		
		if(formulario.tipo_mostrar[0].checked == true && formulario.mod6_idcarpeta.value == ""){
			error = error + "Debe seleccionar la carpeta.\n";
			flag = false;
		}
		
		if(formulario.tipo_mostrar[1].checked == true && formulario.idseccion.value == ""){
			error = error + "Debe seleccionar la seccion.\n";
			flag = false;
		}
		
		if(formulario.tipo_mostrar[2].checked == true && formulario.idproducto.value == ""){
			error = error + "Debe seleccionar la producto.\n";
			flag = false;
		}
			
		if(flag == true){
			formulario.accion.value = "ingresar";
			formulario.submit();
		}else{
			alert(error);
		}
	
	};
	
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Descarga - Nueva</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                      <? if($msj){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td height="40" bgcolor="#66CC99" class="detalle_medio_bold"><?= $msj ?></td>
                        </tr>
                    </table>
					  <? } ?>
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold" >Ingresar nueva  Descarga <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1">
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td width="77" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                  <td colspan="2" valign="top" class="style2"><label></label>
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?= $titulo ?>" size="60" maxlength="60" /></td>
                                </tr>
                                <tr>
                                  <td width="77" align="right" valign="middle" class="detalle_medio">Tipo:</td>
                                  <td colspan="2" valign="top" class="style2"><select name="idtipo_descarga" class="detalle_medio" id="idtipo_descarga" style="width:200px;">
                                    <option value="0">- Seleccionar Tipo</option>
                                    <?
									$query = "SELECT * 
									FROM descarga_tipo
									WHERE estado = 1";
									$result = mysql_query($query);
									while($rs_query = mysql_fetch_assoc($result)){
									?>
									<option value="<?= $rs_query['iddescarga_tipo'] ?>" <? if($rs_query['iddescarga_tipo'] == $idtipo_descarga){ echo "selected"; } ?>><?= $rs_query['titulo'] ?></option>
									<? } ?>
                                                                    </select></td>
                                </tr>
                                <tr>
                                  <td width="77" align="right" valign="middle" class="detalle_medio">Archivo:</td>
                                  <td colspan="2" valign="top" class="style2"><input name="archivo" type="file" class="detalle_medio" id="archivo" size="44" /></td>
                                </tr>
                                <tr>
                                  <td width="77" align="right" valign="middle" class="detalle_medio">Archivo FTP: </td>
                                  <td colspan="2" valign="top" class="style2"><input name="archivo_ftp" type="text" class="detalle_medio" id="archivo_ftp" value="<?= $archivo_ftp ?>" size="60" maxlength="60" /></td>
                                </tr>
                                <tr>
                                  <td width="77" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td colspan="2" valign="top" class="style2">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Carpeta 1&ordm; </td>
                                  <td width="20" align="center" valign="middle" class="style2"><input name="tipo_mostrar" type="radio" value="1" <? if($tipo_mostrar == 1){ echo "checked"; } ?> /></td>
                                  <td width="551" align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta" style="width:230px;" class="detalle_medio" id="mod6_idcarpeta" onfocus="document.form.tipo_mostrar[0].checked = 'checked';"  onchange="document.form.submit();">
                                      <option value="" selected="selected">- Seleccionar Carpeta</option>
                                      <?
										  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
										  FROM carpeta A
										  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
										  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND B.ididioma = '1'
										  ORDER BY B.nombre";
										  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
										  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
										  {
											if ($mod6_idcarpeta == $rs_mod6_idcarpeta['idcarpeta'])
											{
												$sel = "selected";
											}else{
												$sel = "";
											}
									?>
                                      <option  <? echo $sel ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                                      <?= $rs_mod6_idcarpeta['nombre'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                </tr>
                                <tr>
                                  <? if($mod6_idcarpeta){ ?>
                                  <td align="right" valign="middle" class="detalle_medio">Carpeta 2&ordm;</td>
                                  <td align="center" valign="middle" class="style2">&nbsp;</td>
                                  <td align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta2" style="width:230px;" class="detalle_medio" id="mod6_idcarpeta2" onfocus="document.form.tipo_mostrar[0].checked = 'checked';"   onchange="document.form.submit();">
                                      <option value="" selected="selected">- Seleccionar Carpeta</option>
                                      <?
	  $query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
	  while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2))	  
	  {
	  	if ($mod6_idcarpeta2 == $rs_mod6_idcarpeta2['idcarpeta'])
		{
			$sel2 = "selected";
		}else{
			$sel2 = "";
		}
?>
                                      <option  <? echo $sel2 ?> value="<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>">
                                      <?= $rs_mod6_idcarpeta2['nombre'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                  <?  } ?>
                                </tr>
                                <tr>
                                  <? if($mod6_idcarpeta2 && $mod6_idcarpeta){ ?>
                                  <td align="right" valign="middle" class="detalle_medio">Carpeta 3&ordm; </td>
                                  <td align="center" valign="middle" class="style2">&nbsp;</td>
                                  <td align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta3" style="width:230px;" class="detalle_medio" id="mod6_idcarpeta3" onfocus="document.form.tipo_mostrar[0].checked = 'checked';"   onchange="document.form.submit();">
                                      <option value="">- Seleccionar Carpeta</option>
                                      <?
	  $query_mod6_idcarpeta3 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta2' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta3 = mysql_query($query_mod6_idcarpeta3);
	  while ($rs_mod6_idcarpeta3 = mysql_fetch_assoc($result_mod6_idcarpeta3))	  
	  {
	  	if ($mod6_idcarpeta3 == $rs_mod6_idcarpeta3['idcarpeta'])
		{
			$sel3 = "selected";
		}else{
			$sel3 = "";
		}
?>
                                      <option  <? echo $sel3 ?> value="<?= $rs_mod6_idcarpeta3['idcarpeta'] ?>">
                                      <?= $rs_mod6_idcarpeta3['nombre'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                  <?  }   ?>
                                </tr>
                                <tr>
                                  <? if($mod6_idcarpeta3 && $mod6_idcarpeta2  && $mod6_idcarpeta){ ?>
                                  <td align="right" valign="middle" class="detalle_medio">Carpeta 4&ordm; </td>
                                  <td align="center" valign="middle" class="style2">&nbsp;</td>
                                  <td align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta4" style="width:230px;" class="detalle_medio" onfocus="document.form.tipo_mostrar[0].checked = 'checked';"   id="mod6_idcarpeta4">
                                      <option value="" selected="selected">- Seleccionar Carpeta</option>
                                      <?
	  $query_mod6_idcarpeta4 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta3' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta4 = mysql_query($query_mod6_idcarpeta4);
	  while ($rs_mod6_idcarpeta4 = mysql_fetch_assoc($result_mod6_idcarpeta4))	  
	  {
	  	if ($mod6_idcarpeta4 == $rs_mod6_idcarpeta4['idcarpeta'])
		{
			$sel4 = "selected";
		}else{
			$sel4 = "";
		}
?>
                                      <option  <? echo $sel4 ?> value="<?= $rs_mod6_idcarpeta4['idcarpeta'] ?>">
                                      <?= $rs_mod6_idcarpeta4['nombre'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                  <?  }   ?>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td colspan="2" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Secci&oacute;n:</td>
                                  <td align="center" valign="middle" class="detalle_medio"><span class="style2">
                                    <input name="tipo_mostrar" type="radio" value="2" <? if($tipo_mostrar == 2){ echo "checked"; } ?> />
                                  </span></td>
                                  <td align="left" valign="middle" class="detalle_medio"><span class="titulo_medio_bold">
                                    <select name="idseccion" style="width:350px; background:#FF6600; color:#FFFFFF; border:0;" onfocus="document.form.tipo_mostrar[1].checked = 'checked';" class="detalle_medio" id="idseccion">
                                      <option value="">- Seleccionar Seccion</option>
                                      <?
							$query_seccion = "SELECT DISTINCT A.idseccion, B.titulo 
							FROM seccion A
							INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
							INNER JOIN seccion_carpeta C ON A.idseccion = C.idseccion
							INNER JOIN seccion_sede D ON A.idseccion = D.idseccion
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1";
							$result_seccion = mysql_query($query_seccion);
							while($rs_seccion = mysql_fetch_assoc($result_seccion)){
							?>
                                      <option value="<?= $rs_seccion['idseccion'] ?>">
                                      <?= $rs_seccion['titulo'] ?>
                                      </option>
                                      <?
							}
							?>
                                    </select>
                                  </span></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Producto:</td>
                                  <td align="center" valign="middle" class="detalle_medio"><span class="style2">
                                    <input name="tipo_mostrar" type="radio" value="3" <? if($tipo_mostrar == 3){ echo "checked"; } ?> />
                                  </span></td>
                                  <td align="left" valign="middle" class="detalle_medio"><span class="titulo_medio_bold">
                                    <select name="idproducto" style="width:350px; background:#669966; color:#FFFFFF;  border:0;" onfocus="document.form.tipo_mostrar[2].checked = 'checked';"  class="detalle_medio" id="idproducto">
                                      <option value="">- Seleccionar Productos</option>
                                      <?
							$query_producto = "SELECT DISTINCT A.idproducto, B.titulo 
							FROM producto A
							INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto
							INNER JOIN producto_carpeta C ON A.idproducto = C.idproducto
							INNER JOIN producto_sede D ON A.idproducto = D.idproducto
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1";
							$result_producto = mysql_query($query_producto);
							while($rs_producto = mysql_fetch_assoc($result_producto)){
							?>
                                      <option value="<?= $rs_producto['idproducto'] ?>">
                                      <?= $rs_producto['titulo'] ?>
                                      </option>
                                      <?
							}
							?>
                                    </select>
                                  </span></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td colspan="2" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td valign="top" class="style2">&nbsp;</td>
                                  <td colspan="2" align="left" valign="middle" class="style2"><input name="Submit" type="button" class="detalle_medio_bold" id="Submit" onclick="validar_descarga();" value=" Cargar &raquo; " /></td>
                                </tr>
                            </table></td>
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