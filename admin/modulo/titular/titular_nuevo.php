<? 
	include ("../../0_mysql.php"); 
	
	//DESPLEGAR BOTON DE BARRA N°
	$desplegarbarra = 2;
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
	}else{
		$obj_value = '';
	}

	// localizacion de variables get y post:	
	$accion = $_POST['accion'];
	$ididioma = $_POST['ididioma'];
	
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$cantidad_sede = $_POST['cantidad_sede'];
	$idsede = $_POST['idsede'];
	$ididioma = $_POST['ididioma'];

	// Ingreso del titular:
	if($accion == "ingresar"){
		
		//OBTENGO EL IDTITULAR MAX + 1
		$query_id = "SELECT MAX(idtitular) as idtitular FROM titular";
		$rs_id = mysql_fetch_assoc(mysql_query($query_id));
		$idtitular_nuevo = $rs_id['idtitular'] + 1;
		
		// INCORPORACION DE FOTO		
		if ($_FILES['titular']['name'] != ''){
		
			for($i=0;$i<$cantidad_idioma;$i++){
				
				if($ididioma[$i]){
					$ruta_foto = "../../../imagen/titular/";
					
					//CONSULTA DE IDIOMA
					$query_idioma = "SELECT reconocimiento_idioma
					FROM idioma
					WHERE ididioma = '$ididioma[$i]'";
					$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
				
					$archivo_ext = substr($_FILES['titular']['name'],-4);
					$archivo_nombre = substr($_FILES['titular']['name'],0,strrpos($_FILES['titular']['name'], "."));
					
					$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
					$archivo = strtolower($archivo);
				
					$foto =  $idtitular_nuevo .'-'.$rs_idioma['reconocimiento_idioma'].'-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
					
					if (!copy($_FILES['titular']['tmp_name'], $ruta_foto . $foto)){ //si hay error en la copia de la foto
						$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error."')</script>"; // se muestra el error.
					}else{
						$imagesize = getimagesize($ruta_foto.$foto);
					
						if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
							$peso = number_format((filesize($ruta_foto.$foto))/1024,2);
			
							if($peso==0){
								$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
								echo "<script>alert('".$error2."')</script>"; // se muestra el error.
							}else{
							
								$query_ingreso = "INSERT INTO titular (
								  idtitular
								, ididioma
								, foto
								, estado
								) VALUES (
								  '$idtitular_nuevo'
								, '$ididioma[$i]'
								, '$foto'
								, '1'
								)";
								mysql_query($query_ingreso);
								
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
				}//FIN SI ESTA CHECKEADO EL IDIOMA
			}//FIN FOR
		}//FIN FOTO
		
		//INGRESO A SEDES
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO titular_sede(
			  idtitular
			, idsede
			)VALUES(
			  '$idtitular_nuevo'
			, '$idsede[$c]'
			)";
			mysql_query($query_insert);
		
		}
		
		// REDIRECCIONAR A EDITAR TITULAR:
		$query_max = "SELECT MAX(idtitular) as idtitular FROM titular";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		echo "<script>window.location.href('titular_ver.php?idtitular=".$rs_max['idtitular']."');</script>";
		
	};


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_titular(){
		var formulario = document.form;
		var flag = true;
		var checks_idioma = 0;
		var checks_sede = 0;
		
		if(formulario.titular.value == ''){
			alert("Debe seleccionar el titular que desea ingresar.");
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
			formulario.accion.value = "ingresar";
			formulario.submit();
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Titular - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Ingresar nuevo  Titular <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1">
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td width="90" align="right" valign="middle" class="detalle_medio">Titular:</td>
                                  <td colspan="3" valign="top" class="style2"><label>
                                    <input name="titular" type="file" class="detalle_medio" id="titular" size="60" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td height="10" colspan="4" align="right" valign="middle" class="detalle_medio"></td>
                                </tr>
                                <tr>
                                  <td width="90" align="right" valign="top" class="detalle_medio">Sucursales:</td>
                                  <td width="229" align="left" valign="middle" class="detalle_medio">
                                    <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								?>
                                    <input name="idsede[<?= $c ?>]" type="checkbox" id="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?
									   if(!$idsede_log){ 
									  	echo 'checked="checked"'; 
									  }else{
										  if($idsede_log != $rs_sede['idsede']){ 
											echo $obj_value; 
										  }else{ 
											echo 'checked="checked"'; 
										  } 
									  }
									   ?> />
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
                                  <td colspan="3" align="left" valign="middle" class="style2"><input name="Submit" type="button" class="detalle_medio_bold" id="Submit" onclick="validar_titular();" value=" Insertar Titular &raquo; " /></td>
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