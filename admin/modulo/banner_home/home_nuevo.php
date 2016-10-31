<? 
	include ("../../0_mysql.php"); 
	include ("../0_includes/0_clean_string.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
	}else{
		$obj_value = '';
	}
	
	//LOCALIZACION DE VARIABLES:
	$accion = $_POST['accion'];
	$banner = $_POST['banner'];
	$ruta_banner = "../../../imagen/banner_home/";
	$titulo = $_POST['titulo'];
	$detalle = $_POST['detalle'];
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$cantidad_sede = $_POST['cantidad_sede'];
	$idsede = $_POST['idsede'];
	$ididioma = $_POST['ididioma'];
		
	//INGRESAR:
	if ($accion == 'ingresar'){
	
		//OBTENGO EL IDTITULAR MAX + 1
		$query_id = "SELECT MAX(idhome) as idhome FROM se_home";
		$rs_id = mysql_fetch_assoc(mysql_query($query_id));
		$idhome_nuevo = $rs_id['idhome'] + 1;
		
		if ($_FILES['banner']['name'] != ''){
			
			for($i=0;$i<$cantidad_idioma;$i++){
			
				//CONSULTA DE IDIOMA
				$query_idioma = "SELECT reconocimiento_idioma
				FROM idioma
				WHERE ididioma = '$ididioma[$i]'";
				$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
				
				$archivo_ext = substr($_FILES['banner']['name'],-4);
				$archivo_nombre = substr($_FILES['banner']['name'],0,strrpos($_FILES['banner']['name'], "."));
						
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
			
				$foto =  $idhome_nuevo .'-'.$rs_idioma['reconocimiento_idioma'].'-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
				
				if (!copy($_FILES['banner']['tmp_name'], $ruta_banner . $foto)){ //si hay error en la copia de la foto
					$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
					echo "<script>alert('".$error."')</script>"; // se muestra el error.
				}else{
					$imagesize = getimagesize($ruta_banner.$foto);
		
					if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
						$peso = number_format((filesize($ruta_banner.$foto))/1024,2);
						
						if($peso==0){
							$error .= "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						}else{
						
							//INGRESO EL BANNER
							$query_ingreso = "INSERT INTO se_home (
							  idhome
							, ididioma
							, titulo
							, detalle
							, archivo
							, estado
							) VALUES (
							  '$idhome_nuevo'
							, '$ididioma[$i]'
							, '$titulo'
							, '$detalle'
							, '$foto'
							, '1'
							)";
							mysql_query($query_ingreso);
								
						};
					
					}else{
						$error .= "El archivo subido no corresponde a un tipo de imagen permitido. ";
						
							//SI NO SALIO BIEN:
							$query_borrar = "DELETE FROM se_home WHERE idhome = '$idhome_nuevo' ";
							mysql_query($query_borrar);
						
						if(!unlink($ruta_banner.$foto)){ //se elimina el archivo subido
							$error .= "El archivo no pudo elminarse. ";
						}else{
							$error .= "El archivo fue elminado. ";
						}
					}
				} 
			}//FIN FOR
			
			//INGRESO A SEDES
			for($c=0;$c<$cantidad_sede;$c++){
				$query_insert = "INSERT INTO se_home_sede(
				  idhome
				, idsede
				)VALUES(
				  '$idhome_nuevo'
				, '$idsede[$c]'
				)";
				mysql_query($query_insert);
			}
			
		}//FIN IF
		
		// REDIRECCIONAR A EDITAR TITULAR:
		$query_max = "SELECT MAX(idhome) as idhome FROM se_home";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		echo "<script>window.location.href('home_ver.php?idhome=".$rs_max['idhome']."');</script>";
		
	};//FIN INGRESAR
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_banner(){
		var formulario = document.form_banner;
		var flag = true;
		var checks_idioma = 0;
		var checks_sede = 0;
		var error= "";
		
		if(formulario.banner.value == ''){
			error = error + "Debe seleccionar el banner que desea ingresar.\n";
			flag = false;
		}
		
		if(formulario.titulo.value == ''){
			error = error + "Debe ingresar el titulo.\n";
			flag = false;
		}
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = window.form_banner.document.getElementById("idsede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		for(i=0;i<formulario.cantidad_idioma.value;i++){

			check_actual = window.form_banner.document.getElementById("ididioma["+i+"]");
			if (check_actual.checked == true){
				checks_idioma = 1;
			}
			
		}

		if(checks_sede == 0){ 
			error = error + "Debe seleccionar al menos una sucursal.\n";
			flag = false;
		}
		
		if(checks_idioma == 0){ 
			error = error + "Debe seleccionar al menos un idioma.\n";
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
	<div id="navigation"><? include("../0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold"> Banner Home - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_banner" id="form_banner">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingresar Banner Home <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="90" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                <td colspan="3" valign="top" class="style2">
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" style="width:98%" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Banner:</td>
                                <td colspan="3" valign="top" class="style2"><input name="banner" type="file" class="detalle_medio" id="banner" style="width:99%" /></td>
                              </tr>
							  <? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio">HTML:</td>
                                <td colspan="3" valign="top" class="style2">
                                  <textarea name="detalle" rows="8" class="detalle_medio" id="detalle" style="width:98%"></textarea>                                </td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td height="10" colspan="4" align="right" valign="middle" class="detalle_medio"></td>
                              </tr>
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio">Sucursales:</td>
                                <td width="229" align="left" valign="middle" class="detalle_medio"><?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								?>
                                    <input name="idsede[<?= $c ?>]" type="checkbox" id="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value; }else{ echo 'checked="checked"'; } ?>  />
                                    <?= $rs_sede['titulo'] ?>
                                    <br />
                                    <? 
								$c++;
								} 
								?>
                                    <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" /></td>
                                <td width="80" align="right" valign="top" class="detalle_medio">Idiomas:</td>
                                <td width="239" align="left" valign="top" class="detalle_medio"><?
								  		  $c=0;
										  $query_ididioma = "SELECT ididioma, titulo_idioma 
										  FROM idioma
										  WHERE estado = '1'
										  ORDER BY titulo_idioma";
										  $result_ididioma = mysql_query($query_ididioma);
										  while ($rs_ididioma = mysql_fetch_assoc($result_ididioma))	  
										  {

										?>
                                    <input name="ididioma[<?= $c ?>]" type="checkbox" id="ididioma[<?= $c ?>]" value="<?= $rs_ididioma['ididioma'] ?>" checked="checked" />
                                    <?= $rs_ididioma['titulo_idioma'] ?>
                                    <br />
                                    <?  $c++; } ?>
                                    <input name="cantidad_idioma" type="hidden" id="cantidad_idioma" value="<?= $c ?>" /></td>
                              </tr>
                              <tr>
                                <td height="10" colspan="4" valign="top" class="style2"></td>
                              </tr>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td colspan="3" align="left" valign="middle" class="style2"><input name="Submit" type="button" class="detalle_medio_bold" id="Submit" onclick="validar_banner();" value=" Insertar &raquo; " /></td>
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