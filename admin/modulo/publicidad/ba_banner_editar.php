<? 
	include ("../../0_mysql.php"); 
	include ("../0_includes/0_clean_string.php");
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
		$filtrado_sede = " AND idsede = '$filtro_sede' ";
	}else{
		$obj_value = '';
	}

	$accion = $_POST['accion'];
	$idba_banner = $_GET['idba_banner'];
	$ididioma = $_GET['ididioma'];
	$idba_anunciante = $_POST['idba_anunciante'];
	$idba_lugar = $_POST['idba_lugar'];
	$link = $_POST['link'];
	$target = $_POST['target'];
	
	$dia_fecha_activacion = $_POST['dia_fecha_activacion'];
	$mes_fecha_activacion = $_POST['mes_fecha_activacion'];
	$ano_fecha_activacion = $_POST['ano_fecha_activacion'];
	
	$dia_fecha_baja = $_POST['dia_fecha_baja'];
	$mes_fecha_baja = $_POST['mes_fecha_baja'];
	$ano_fecha_baja = $_POST['ano_fecha_baja'];

	$ruta_foto = "../../../imagen/banner/"; 	
	
	//ingresar archivo:
	if($accion == 'update'){
		
		if ($_FILES['archivo']['name'] != ''){		
			
			$archivo_ext = substr($_FILES['archivo']['name'],-4);
			$archivo_nombre = substr($_FILES['archivo']['name'],0,strrpos($_FILES['archivo']['name'], "."));
					
			$archivo = clean_string($archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
	
				$querysel = "SELECT archivo FROM ba_banner WHERE idba_banner = '$idba_banner' AND ididioma = $ididioma ";
				$rowfoto = mysql_fetch_row(mysql_query($querysel));
			
				if ($rowfoto[0] != '' && file_exists($ruta_foto.$rowfoto[0])){
					unlink ($ruta_foto.$rowfoto[0]);
				};
		
				$foto =  $idba_banner.'-'.rand(0,999).'-'.$archivo; //se captura el nombre del archivo de la foto
	
			
			if (!copy($_FILES['archivo']['tmp_name'], $ruta_foto.$foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir el banner. "; //se crea la variable error con un mensaje de error.
				echo $foto;
			}else{
				$imagesize = getimagesize($ruta_foto.$foto);
	
				if($imagesize[2]>0 && $imagesize[2]<=16 ){ //si es una imagen
					$peso = number_format((filesize($ruta_foto.$foto))/1024,2);
					$error_banner = 0;
					
					//Realizo comprobaciones de peso, ancho y alto
					
					if($peso==0) { //si el archivo esta corrupto
					$error_banner = 1;
					$error .= "El banner se subio incorrectamente. ";//se crea la variable error con un mensaje de error.
					}
					
					if($error_banner != 1){
					
						//SI TODO SALIO BIEN:
						$query_ingresar = "UPDATE ba_banner 
						SET archivo = '$foto'
						WHERE idba_banner= $idba_banner AND ididioma = $ididioma LIMIT 1";
						mysql_query($query_ingresar);
							
					}else{
						
						if(!unlink($ruta_foto.$foto)){ //se elimina el archivo subido
							$error .= "El archivo no pudo elminarse. ";
						}else{
							$error .= "El archivo fue elminado. ";
						}
					
					}
					

				
				}else{
					
					$error .= "El archivo subido no corresponde a un tipo de imagen permitido, o sus medidas no son las requeridas. ";
					
						//SI NO SALIO BIEN:
						$query_borrar = "DELETE FROM ba_banner WHERE idba_banner = '$idba_banner' AND ididioma = $ididioma ";
						mysql_query($query_borrar);
					
					if(!unlink($ruta_foto.$foto)){ //se elimina el archivo subido
						$error .= "El archivo no pudo elminarse. ";
					}else{
						$error .= "El archivo fue elminado. ";
					};
					
				};
			};
		};
		
		//ACTUALIZA LOS OTROS CAMPOS:
		$fecha_activacion = $ano_fecha_activacion."-".$mes_fecha_activacion."-".$dia_fecha_activacion;
		$fecha_baja = $ano_fecha_baja."-".$mes_fecha_baja."-".$dia_fecha_baja;
			
		//actualiza link, clicks y vistas.
		$query_update_link = "UPDATE ba_banner
		SET link = '$link'
		, fecha_baja = '$fecha_baja'
		, fecha_alta = '$fecha_activacion'
		, idba_anunciante = '$idba_anunciante'
		, idba_lugar = '$idba_lugar'
		, target = '$target'
		WHERE idba_banner = '$idba_banner' AND ididioma = '$ididioma'
		LIMIT 1";
		mysql_query($query_update_link);

	};//fin accion update

	//MODIFICAR SEDE
	if($accion == "modificar_sede"){
		
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		$query_delete = "DELETE FROM ba_banner_sede 
		WHERE idba_banner = '$idba_banner' $filtrado_sede";
		mysql_query($query_delete);
		
		for($c=0;$c<$cantidad_sede;$c++){
			
			if($sede[$c] != 0){
				$query_insert = "INSERT INTO ba_banner_sede(
				  idba_banner
				, idsede
				)VALUES(
				  '$idba_banner'
				, '$sede[$c]'
				)";
				mysql_query($query_insert);
			}
		}
	}
	
	
	//Consulta
	$query_link="SELECT *
	FROM ba_banner
	WHERE idba_banner = '$idba_banner' AND ididioma = $ididioma
	LIMIT 1";
	$result_link = mysql_query($query_link);
 	$rs_link = mysql_fetch_assoc($result_link);	 
	
	$fecha_activacion = split("-",$rs_link['fecha_alta']);
	$fecha_baja = split("-",$rs_link['fecha_baja']);
	
	//Busco las medidas: ALto, Ancho y Peso Max.
	$query_medidas = "SELECT A.*, B.* 
	FROM ba_banner A
	INNER JOIN ba_lugar B ON B.idba_lugar = A.idba_lugar
	WHERE A.idba_banner = $idba_banner";	
	$result_medidas = mysql_query($query_medidas);
	$rs_medidas = mysql_fetch_assoc($result_medidas);
	
	//Toma las variables necesarias de la consulta
	$alto = $rs_medidas['alto'];
	$ancho = $rs_medidas['ancho'];
	$peso_maximo = $rs_medidas['peso_maximo'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

	function validar_banner(){
		formulario = document.form_titular;
		formulario.accion.value = "update";
		formulario.submit();
	
	};
	
	function validar_sede(){
		var formulario = document.form_titular;
		var flag = true;
		var checks_sede = 0;
		var error = "";
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = window.form_titular.document.getElementById("sede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			error = error + "Debe seleccionar al menos una sucursal.\n";
			flag = false;
		}
		
		if(flag == true){
			formulario.accion.value = "modificar_sede";
			formulario.submit();	
		}else{
			
		}
	};

</script>

<style type="text/css">
<!--
.style2 {font-weight: bold}
-->
</style>
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Banner - Editar</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Editar datos del  Banner <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          </span></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <? if($error){ ?>
                              <tr>
                                <td colspan="2" valign="top" class="detalle_medio" style="color:#FF0000"><?=$error?></td>
                              </tr>
                              <? }; ?>
                              <tr>
                                <td width="18%" align="left" valign="middle" class="detalle_medio">Fecha de Alta: </td>
                                <td width="82%" valign="top" class="detalle_medio"><strong>
                                <?= $rs_medidas['fecha_alta'] ?>
                                </strong></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Fecha de Activaci&oacute;n: </td>
                                <td width="82%" valign="top" class="detalle_medio"><span >
                                  <select name="dia_fecha_activacion" size="1" class="detalle_medio" id="dia_fecha_activacion">
                                    <option value='00' ></option>
                                    <?												
										for ($i=1;$i<32;$i++){
											if ($fecha_activacion[2] == $i){								     
												$sel_fecha_ano = "selected";
											}else{
												$sel_fecha_ano  = "";
											}
												print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
										}
									?>
                                  </select>
                                  <select name="mes_fecha_activacion" size="1" class="detalle_medio" id="mes_fecha_activacion">
                                    <option value='00' ></option>
                                    <?						
										for ($i=1;$i<13;$i++){
											if ($fecha_activacion[1] == $i){								     
												$sel_fecha_ano = "selected";
											}else{
												$sel_fecha_ano  = "";
											}
												print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
										}
									?>
                                  </select>
                                  <select name="ano_fecha_activacion" size="1" class="detalle_medio" id="ano_fecha_activacion">
                                    <option value='0000' ></option>
                                    <?	
										$anioActual = date("Y");
										for ($i=$anioActual+1;$i>($anioActual-5);$i--){
											if ($fecha_activacion[0] == $i){								     
												$sel_fecha_ano = "selected";
											}else{
												$sel_fecha_ano  = "";
											}	
											
												print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
										}
									?>
                                  </select>
Si no posee fecha de activaci&oacute;n, se mostrara en forma instantanea. </span></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Fecha de Baja: </td>
                                <td valign="top" class="detalle_medio"><span >
                                  <select name="dia_fecha_baja" size="1" class="detalle_medio" id="dia_fecha_baja">
                                    <option value='00' ></option>
                                    
                                          
                                          
                                        ;
										
                                        
                                          
                                          
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
                                    
                                          
                                          
                                        ;
                                        
                                        
                                          
                                          
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
                                    
                                          
                                          
                                        ;
                                        
                                        
                                          
                                          
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
                                  </font>Si no posee fecha de baja, este quedara activo en forma indefinida. </span></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Anunciante</td>
                                <td valign="top" class="detalle_medio"><span >
                                  <select name="idba_anunciante" class="detalle_medio" id="idba_anunciante" style="width:200px;">
                                    <?
										  $query = "SELECT * 
										  FROM ba_anunciante
										  WHERE estado = 1
										  ORDER BY nombre";
										  $result = mysql_query($query);
										  while ($rs_banner = mysql_fetch_assoc($result)){
										  
												if ($rs_medidas['idba_anunciante'] == $rs_banner['idba_anunciante']){
													$sel = "selected";
												}else{
													$sel = "";
												}
									?>
                                    <option  <?= $sel ?> value="<?= $rs_banner['idba_anunciante'] ?>">
                                    <?= $rs_banner['nombre'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Lugar:</td>
                                <td valign="top" class="detalle_medio">
								<select name="idba_lugar" class="detalle_medio" id="idba_lugar" style="width:200px;">
                                  <?
										  $query = "SELECT * 
										  FROM ba_lugar
										  WHERE estado_lugar = 1
										  ORDER BY nombre_lugar";
										  $result = mysql_query($query);
										  while ($rs_banner = mysql_fetch_assoc($result)){
										  
												if ($rs_medidas['idba_lugar'] == $rs_banner['idba_lugar']){
													$sel = "selected";
												}else{
													$sel = "";
												}
									?>
                                  <option  <?= $sel ?> value="<?= $rs_banner['idba_lugar'] ?>">
                                  <?= $rs_banner['nombre_lugar'] ?>
                                  </option>
                                  <?  } ?>
                                </select></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Link:</td>
                                <td valign="top" class="detalle_chico"><label>
                                  <input name="link" type="text" class="detalle_medio" id="link" value="<?= $rs_link['link'] ?>"   style="width:350px;"/>
                                  (Solo para imagenes.)</label></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Target:</td>
                                <td valign="top" class="detalle_chico"><label>
                                  <select name="target" class="detalle_medio" id="target">
                                    <option value="_self" <? if($rs_link['target']=="_self"){ echo "selected"; } ?>>_self</option>
                                    <option value="_blank" <? if($rs_link['target']=="_blank"){ echo "selected"; } ?>>_blank</option>
                                  </select>
                                </label></td>
                              </tr>
                              <tr>
                                <td valign="middle" class="detalle_medio">Banner:</td>
                                <td valign="top" class="detalle_medio"><span class="detalle_medio_bold"><span class="style2">
                                  <input name="archivo" type="file" class="detalle_medio" id="archivo" style="width:400px;" />
                                </span></span></td>
                              </tr>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td valign="top" class="detalle_medio"><span class="detalle_medio"><strong>Importante:</strong></span> El banner debe ser de<strong> 
                                <?=$ancho?>
                                x 
                                <?=$alto?>
                                </strong>
                                pixeles (ancho x alto) y un peso maximo de<strong> 
                                <?=$peso_maximo?>
kb.</strong></td>
                              </tr>
                              <? if($rs_link['archivo'] != ''){ ?>
                              <tr>
                                <td valign="top">&nbsp;</td>
                                <td valign="top" class="detalle_medio"><span class="detalle_medio_bold" style="color:#FF0000;"><span class="style2">                                </span></span>
                                  <table border="0" cellpadding="5" cellspacing="0"<?
								  
								  $archivo_tamaño = getimagesize($ruta_foto.$rs_link['archivo']);
								  
								  	if($archivo_tamaño[0] == $ancho &&  $archivo_tamaño[1] == $alto){
								  		echo ' bgcolor="#669966" ';
										$error_banner = "";
									}else{
										echo ' bgcolor="#D20000" ';
										$error_banner = "Atención: el tamaño de la imagen no es el adecuado.";
									}
								  ?> >
                                    <tr>
                                      <td><span class="style2">
                                        <? 									  
										  if($rs_link['archivo'] != ''){
											$imagen =& new obj0001('0','../../../imagen/banner/',$rs_link['archivo'],'500','','','','../../../imagen/banner/'.$rs_link['archivo'],'_blank','','wmode=opaque',''); 
										  }; 
										?>
                                      </span></td>
                                    </tr>
                                    <tr>
                                      <td class="detalle_medio_bold_white">Archivo: 
                                        <?=$rs_link['archivo']?>                                      </td>
                                    </tr>
                                  </table>                                
                                <br />
								<?
									if($error_banner){
										echo $error_banner;
									}
								?>								</td>
                              </tr>
                              <? }; ?>
							  <?
							  	
								$fecha_actual = date("Y-m-d");
								$query = "SELECT archivo
								FROM ba_banner  A
								WHERE A.estado = 1 AND A.ididioma = '$ididioma' 
								AND A.archivo != ''
								AND A.fecha_alta <= '$fecha_actual' 
								AND (A.fecha_baja >= '$fecha_actual' OR A.fecha_baja = '0000-00-00')";
								$rs_banner = mysql_fetch_assoc(mysql_query($query));
								
								if($rs_banner['archivo']){
									if(file_exists($ruta_foto.$rs_banner['archivo']) == true){
										$se_muestra = true;
									}else{
										$se_muestra = false;
									}
								}else{
									$se_muestra = false;
								}
								
							  if($se_muestra == false){
							  ?>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td valign="top" class="style2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td width="6%" bgcolor="#FF0000"><img src="../../../imagen/varios/alert20px.png" width="24" height="20" /></td>
                                    <td width="94%" bgcolor="#FF0000" class="detalle_medio_bold_white"> <p>Atenci&oacute;n: El banner no se visualizar&aacute;. Sus posible causas pueden ser: <br />
                                     &bull; No se encuentra dentro del per&iacute;odo de la fecha de  activaci&oacute;n y de baja.<br />
                                     &bull; Todavia no tiene un banner cargado.
</p>                                    </td>
                                  </tr>
                                </table></td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td valign="top" class="style2"><input name="Submit22" type="button" class="detalle_medio_bold" onclick="validar_banner();" value=" &raquo; Guardar cambios  " /></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="37" bgcolor="#FFDDBC" class="titulo_medio_bold">Propiedades del banner: <a name="propiedades" id="propiedades"></a></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td align="left" bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="111" align="right" valign="top" class="detalle_medio_bold">Sucursales: </td>
                                <td width="545" align="left" valign="top" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <?
								
									$c=0;
									$query_sede = "SELECT idsede, titulo
									FROM sede
									WHERE estado = 1";
									$result_sede = mysql_query($query_sede);
									while($rs_sede = mysql_fetch_assoc($result_sede)){
									
										$query_sel = "SELECT idba_banner
										FROM ba_banner_sede
										WHERE idba_banner = '$idba_banner' AND idsede = '$rs_sede[idsede]' ";
										if(mysql_num_rows(mysql_query($query_sel))>0){
											$sel="checked";
										}else{
											$sel="";
										}
									
								?>
                                    <tr>
                                      <td width="5%"><input type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?= $sel ?> <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?> /></td>
                                      <td width="95%"><?= $rs_sede['titulo'] ?></td>
                                    </tr>
                                    <? 
								$c++;
								} 
								?>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="111" align="right" valign="top" class="style2"><input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" /></td>
                                <td align="left" valign="middle" class="style2"><input name="Submit233" type="button" class="detalle_medio_bold" onclick="javascript:validar_sede()" value="  &raquo;  Modificar   " /></td>
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