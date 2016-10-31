<?	 include ("../../0_mysql.php"); 

	//DESPLEGAR BOTON DE BARRA N°
	$desplegarbarra = 2;
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = " AND idsede = '$idsede_log' ";
		$filtro_sede_sp = " AND D.idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
		$filtro_sede = '';
	}
	
	//medidas adecuadas del titular:
	$query_parametro = "SELECT ancho, alto FROM titular_parametro WHERE idtitular_parametro = '1' ";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_parametro));
	
	$ancho_adecuado = $rs_parametro['ancho'];
	$alto_adecuado = $rs_parametro['alto'];
	$ruta_foto = "../../../imagen/titular/"; 
	
	//localizacion de variables get y post:
	$archivo = $_POST['archivo'];
	$accion = $_POST['accion'];
	$idtitular = $_GET['idtitular'];
	$ididioma = $_GET['ididioma'];
	
	$dia_fecha_activacion = $_POST['dia_fecha_activacion'];
	$mes_fecha_activacion = $_POST['mes_fecha_activacion'];
	$ano_fecha_activacion = $_POST['ano_fecha_activacion'];
	
	$dia_fecha_baja = $_POST['dia_fecha_baja'];
	$mes_fecha_baja = $_POST['mes_fecha_baja'];
	$ano_fecha_baja = $_POST['ano_fecha_baja'];
	
	$idproducto = $_POST['idproducto'];
	$idseccion = $_POST['idseccion'];
	$urlespecifica = $_POST['urlespecifica'];

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
	
	//ingresar archivo:
	if($accion == 'update'){
		
		if ($_FILES['archivo']['name'] != ''){		
			
			$archivo_ext = substr($_FILES['archivo']['name'],-4);
			$archivo_nombre = substr($_FILES['archivo']['name'],0,strrpos($_FILES['archivo']['name'], "."));
					
			$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
	
				$querysel = "SELECT foto FROM titular WHERE idtitular = '$idtitular' AND ididioma = '$ididioma' ";
				$rowfoto = mysql_fetch_row(mysql_query($querysel));
			
				if ($rowfoto[0] != '' && file_exists($ruta_foto.$rowfoto[0])){
					unlink ($ruta_foto.$rowfoto[0]);
				};
		
			$foto =  $idtitular . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
	
			
			if (!copy($_FILES['archivo']['tmp_name'], $ruta_foto . $foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. "; //se crea la variable error con un mensaje de error.
				echo $foto;
			}else{
				$imagesize = getimagesize($ruta_foto.$foto);
	
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($ruta_foto.$foto))/1024,2);
					
					if($peso==0){
						$error .= "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
					}else{
					
						//SI TODO SALIO BIEN:
						$query_ingresar = "UPDATE titular 
						SET foto = '$foto'
						WHERE idtitular	= '$idtitular' AND ididioma = '$ididioma' LIMIT 1";
						mysql_query($query_ingresar);
							
					};
				
				}else{
					$error .= "El archivo subido no corresponde a un tipo de imagen permitido. ";
					
						//SI NO SALIO BIEN:
						$query_borrar = "DELETE FROM titular WHERE idtitular = '$idtitular' AND ididioma = '$ididioma' ";
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
		
		$query_update = "UPDATE titular 
		SET fecha_activacion = '$fecha_activacion'	
		, fecha_baja = '$fecha_baja'	
		, ididioma = '$ididioma'
		WHERE idtitular	= $idtitular";
		mysql_query($query_update);

	};//fin accion update	

	//ACTUALIZO CARPETA	
	if($accion == 'cambiar_carpeta'){
		
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
		
		//CARPETA PADRE
		$query_padre = "SELECT idcarpeta_padre
		FROM carpeta
		WHERE idcarpeta = '$mod6_sel_idcarpeta'";
		$rs_padre = mysql_fetch_assoc(mysql_query($query_padre));
			
		$query_adj = "UPDATE titular 
		SET idcarpeta = '$mod6_sel_idcarpeta'	
		, idcarpeta_padre = '$rs_padre[idcarpeta_padre]'		
		WHERE idtitular = '$idtitular'";
		mysql_query($query_adj);	
		
		/*echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idtitular=$idtitular');</script>";*/
	}
	
	//ACTUALIZO PRODUCTO
	if($accion == 'cambiar_prod'){	
		
		$query_adj = "UPDATE titular 
		SET idproducto = '$idproducto'	
		WHERE idtitular = '$idtitular'";
		mysql_query($query_adj);	
		
		/*echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idtitular=$idtitular');</script>";*/
	}
	
	//ACTUALIZO SECCION
	if($accion == 'cambiar_sec'){	
		
		$query_adj = "UPDATE titular 
		SET idseccion = '$idseccion'	
		WHERE idtitular = '$idtitular'";
		mysql_query($query_adj);	
		
		/*echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idtitular=$idtitular');</script>";*/
	}
	
	//ACTUALIZO URL ESPECIFICA
	if($accion == 'cambiar_url'){	
		
		$query_adj = "UPDATE titular 
		SET urlespecifica = '$urlespecifica'	
		WHERE idtitular = '$idtitular'";
		mysql_query($query_adj);	
		
		/*echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idtitular=$idtitular');</script>";*/
	}
	
	//MODIFICAR SEDE
	if($accion == "modificar_sede"){
		
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		$query_delete = "DELETE FROM titular_sede 
		WHERE idtitular = '$idtitular' $filtro_sede";
		mysql_query($query_delete);
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO titular_sede(
			  idtitular
			, idsede
			)VALUES(
			  '$idtitular'
			, '$sede[$c]'
			)";
			mysql_query($query_insert);
			//echo $sede[$c];
		}
	}
	
	//VACIAR
	if($accion == "vaciar_producto"){
		$query = "UPDATE titular SET idproducto = '0' WHERE idtitular = '$idtitular' ";
		mysql_query($query);
	}
	if($accion == "vaciar_seccion"){
		$query = "UPDATE titular SET idseccion = '0' WHERE idtitular = '$idtitular' ";
		mysql_query($query);
	}
	if($accion == "vaciar_carpeta"){
		$query = "UPDATE titular SET idcarpeta = '0', idcarpeta_padre = '0' WHERE idtitular = '$idtitular' ";
		mysql_query($query);
	}
	
	//comsulta:	
	$query_archivo = "SELECT * 
	FROM titular 
	WHERE idtitular = '$idtitular' AND ididioma = '$ididioma' ";
	$result_archivo = mysql_query($query_archivo);
	$rs_titular = mysql_fetch_assoc($result_archivo);
	
	$fecha_activacion = split("-",$rs_titular['fecha_activacion']);
	$fecha_baja = split("-",$rs_titular['fecha_baja']);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">
	
	function validar_form_titular(){
	formulario = document.form_titular;
		formulario.accion.value = "update";
		formulario.submit();
	};

	function validar_carpeta(){
		formulario = document.form_titular;
		if (formulario.mod6_idcarpeta.value == ""){
			alert("Debe seleccionar una carpeta.");
		} else {	
			formulario.accion.value = 'cambiar_carpeta';
			formulario.submit();
		}
	};

	function validar_producto(){
		formulario = document.form_titular;
		if (formulario.idproducto.value == ""){
			alert("Debe seleccionar un producto.");
		} else {	
			formulario.accion.value = 'cambiar_prod';
			formulario.submit();
		}
		
	};
	
	function validar_seccion(){
		formulario = document.form_titular;
		if (formulario.idseccion.value == ""){
			alert("Debe seleccionar un seccion.");
		} else {	
			formulario.accion.value = 'cambiar_sec';
			formulario.submit();
		}
		
	};
	
	function validar_url(){
		formulario = document.form_titular;
		if (formulario.urlespecifica.value == ""){
			alert("Debe escribir la URL.");
		} else {	
			formulario.accion.value = 'cambiar_url';
			formulario.submit();
		}
		
	};
	
	function validar_sede(){
		formulario = document.form_titular;
		formulario.accion.value = "modificar_sede";
		formulario.submit();	
	};
	
	function vaciar_seccion(){
		formulario = document.form_titular;
		formulario.accion.value = 'vaciar_seccion';
		formulario.submit();	
	};
	
	function vaciar_producto(){
		formulario = document.form_titular;
		formulario.accion.value = 'vaciar_producto';
		formulario.submit();	
	};
	
	function vaciar_carpeta(){
		formulario = document.form_titular;
		formulario.accion.value = 'vaciar_carpeta';
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Titular - Editar Idioma:<span style="color:#006699">
				<?
								   $query_idioma = "SELECT titulo_idioma
								   FROM idioma 
								   WHERE ididioma = '$rs_titular[ididioma]'
								   LIMIT 1";
								   $rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
								   echo $rs_idioma['titulo_idioma'];
								   ?></span>.
                  </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Editar Titular <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <? if($error){ ?>
                              <tr>
                                <td colspan="2" valign="top" class="detalle_medio_bold" style="color:#FF0000"><?=$error?></td>
                              </tr>
                              <? }; ?>
                              <tr>
                                <td width="20%" align="right" valign="middle" class="detalle_medio">Fecha de Activaci&oacute;n: </td>
                                <td width="80%" valign="top" class="detalle_medio"><span class="style2">
                                  <select name="dia_fecha_activacion" size="1" class="detalle_medio" id="dia_fecha_activacion">
                                    <option value='00' ></option>
                                    
                                          ;
										
                                          
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
                                    
                                          ;
                                        
                                          
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
                                    
                                          ;
                                        
                                          
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
                                </font>Si no posee fecha de activaci&oacute;n, se mostrara en forma instantanea. </span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Fecha de Baja: </td>
                                <td valign="top" class="detalle_medio"><span class="style2">
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
                                <td height="10" colspan="2" valign="top" class="detalle_medio"></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Titular:</td>
                                <td valign="top" class="style2"><input name="archivo" type="file" class="detalle_medio" id="archivo" size="60" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td valign="top" class="detalle_medio"><strong>Importante:</strong> La imagen debe ser de <span class="detalle_medio">
                                <?=$ancho_adecuado?>
                                </span> x <span class="detalle_medio">
                                <?=$alto_adecuado?>
                                </span> pixeles (ancho x alto) </td>
                              </tr>
                              <? if($rs_titular['foto'] != ''){ ?>
                              <tr>
                                <td valign="top">&nbsp;</td>
                                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="55" class="detalle_medio_bold">Archivo:</td>
                                    <td class="detalle_medio_bold" style="color:#FF0000;"><?=$rs_titular['foto']?></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <? }; ?>
							  <?
							  //VERIFICO EL ANCHO Y ALTO
							  $titular_optimo = true;  
							  if($rs_titular['foto']){
								  $titularsize = getimagesize($ruta_foto.$rs_titular['foto']);
								  if($titularsize[0] != $ancho_adecuado || $titularsize[1] != $alto_adecuado){
									$titular_optimo = false;
								  }
							  }
							  ?>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td valign="top" class="style2"><span class="detalle_medio">
                                  </span>
								  <? if($titular_optimo == false){ ?>
                                  <table width="185" border="4" cellpadding="0" cellspacing="0" bordercolor="#FF0000" style="border: #FF0000 solid 4px;">
                                    <tr>
                                      <td bgcolor="#FF0000"  style="border: #FF0000 solid 0px;"><span class="detalle_medio">
									  <? } ?>
                                        <? 
									  if($rs_titular['foto'] != ''){
									  	$imagen =& new obj0001('0',$ruta_foto,$rs_titular['foto'],'510','','','','','','','wmode=opaque',''); 
									  }; 
									  ?>
									   <? if($titular_optimo == false){ ?>
                                      </span></td>
                                    </tr>
                                    <tr>
                                      <td bgcolor="#FF0000" class="detalle_medio_bold_white"  style="border: #FF0000 solid 0px;">Importante: El ancho u alto de la foto no son los recomendados. </td>
                                    </tr>
                                  </table>
								  <? } ?>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="style2">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><input name="Submit" type="button" class="detalle_medio_bold" id="Submit" onclick="javascript:validar_form_titular();" value=" Guardar Cambios &raquo; " /></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                        <br />
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Actualmente se muestra en:</td>
                          </tr>
                          <tr>
                            <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right">Carpeta:</td>
                                <td width="521">
                                <?		
										$query_mod6_lista2 = "SELECT B.nombre, A.idcarpeta_padre
										FROM carpeta A
										INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
										WHERE A.idcarpeta = '$rs_titular[idcarpeta]' AND B.ididioma = '1'
										ORDER BY B.nombre";
										$rs_mod6_lista2 = mysql_fetch_assoc(mysql_query($query_mod6_lista2));
										
								  		if($rs_mod6_lista2['nombre'] == ""){
											$ruta = "<b>No esta definida.</b>";
										}else{
											$ruta = "<b>".$rs_mod6_lista2['nombre']."</b>";
										}
										
										//AVERIGUO RUTA COMPLETA - NIVEL 1
										$query_1 = "SELECT B.nombre, A.idcarpeta_padre
										FROM carpeta A
										INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
										WHERE B.ididioma = 1 AND A.idcarpeta = '$rs_titular[idcarpeta]'
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
										
										//IMPRIMO RUTA
										echo $ruta;
								  ?></td>
                                <td width="17" align="center">
								<? 
								if($rs_titular['idcarpeta']!= "0"){ 
									echo '<a href="javascript:vaciar_carpeta();"><img src="../../imagen/trash.png" width="15" height="16" border="0" title="Vaciar" /></a>';
								}
								?></td>
                              </tr>
                              <tr>
                                <td align="right">Producto:</td>
                                <td>
							    <?
								 $query_prod = "SELECT titulo
								 FROM producto_idioma_dato
								 WHERE idproducto = '$rs_titular[idproducto]' AND ididioma = '$ididioma' ";
								 $rs_prod = mysql_fetch_assoc(mysql_query($query_prod));
								 
								 if($rs_prod['titulo']){
								 	echo "<b>".$rs_prod['titulo']."</b>";
								 }else{
								 	echo "<b>No esta definido.</b>";
								 }
								 
								 ?></td>
                                <td align="center">
								<? 
								if($rs_titular['idproducto'] != "0"){  
									echo '<a href="javascript:vaciar_producto();"><img src="../../imagen/trash.png" width="15" height="16" border="0"  title="Vaciar" /></a>';
								} 
								?></td>
                              </tr>
                              <tr>
                                <td align="right">Secci&oacute;n:</td>
                                <td>
                                <?
								 $query_sec = "SELECT titulo
								 FROM seccion_idioma_dato
								 WHERE idseccion = '$rs_titular[idseccion]' AND ididioma = '$ididioma' ";
								 $rs_sec = mysql_fetch_assoc(mysql_query($query_sec));
								 
								 if($rs_sec['titulo']){
								 	echo "<b>".$rs_sec['titulo']."</b>";
								 }else{
								 	echo "<b>No esta definido.</b>";
								 }
								 
								 ?></td>
                                <td align="center"><? 
								if($rs_titular['idseccion'] != "0"){  
									echo '<a href="javascript:vaciar_seccion();"><img src="../../imagen/trash.png" width="15" height="16" border="0"  title="Vaciar" /></a>';
								} 
								?></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
                        <br />
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Asignar el Titular en la web: <a name="categoria" id="categoria"></a></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right">&nbsp;</td>
                                <td><strong>Carpeta </strong></td>
                              </tr>
                            </table>
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td width="110" align="right" valign="middle" class="detalle_medio">Carpeta 1&ordm; </td>
                                  <td width="548" align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta" onchange="document.form_titular.submit();">
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
                                  <td align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta2" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta2" onchange="document.form_titular.submit();">
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
                                  <td align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta3" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta3" onchange="document.form_titular.submit();">
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
                                  <td align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcarpeta4" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta4">
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
                              </table>
                              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                                <tr>
                                  <td width="110">&nbsp;</td>
                                  <td><input name="Submit2" type="button" class="detalle_medio_bold" value=" Cambiar &raquo; " onclick="validar_carpeta();"/></td>
                                </tr>
                              </table>
                              <br />
                              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right">&nbsp;</td>
                                <td><strong>Producto y Secci&oacute;n </strong></td>
                              </tr>
                            </table>
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr class="detalle_medio">
                                  <td width="110" align="right" valign="middle" class="detalle_medio">Producto:</td>
                                  <td width="548" valign="middle" class="titulo_medio_bold"><select name="idproducto" style="width:450px; background:#669966; color:#FFFFFF; border:1 #663300;" class="detalle_medio" id="idproducto">
                                    <option value="">- Seleccionar Productos</option>
                                    <?
							$query_producto = "SELECT DISTNCT A.idproducto, B.titulo 
							FROM producto A
							INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto
							INNER JOIN producto_carpeta C ON A.idproducto = C.idproducto
							INNER JOIN producto_sede D ON A.idproducto = D.idproducto
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1 $filtro_sede_sp";
							$result_producto = mysql_query($query_producto);
							while($rs_producto = mysql_fetch_assoc($result_producto)){
							?>
                                    <option value="<?= $rs_producto['idproducto'] ?>">
                                    <?= $rs_producto['titulo'] ?>
                                    </option>
                                    <?
							}
							?>
                                  </select></td>
                                </tr>
                                <tr class="detalle_medio">
                                  <td width="110" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td valign="middle" class="titulo_medio_bold"><input name="Submit32" type="button" class="detalle_medio_bold" value=" Cambiar &raquo; " onclick="validar_producto();" /></td>
                                </tr>
                                <tr class="detalle_medio">
                                  <td align="right" valign="middle" class="detalle_medio">Secci&oacute;n:</td>
                                  <td width="548" valign="middle" class="titulo_medio_bold"><select name="idseccion" style="width:450px; background:#FF6600; color:#FFFFFF; border:1 #FF6600;" class="detalle_medio" id="idseccion">
                                    <option value="">- Seleccionar Seccion</option>
                                    <?
							$query_seccion = "SELECT DISTINCT A.idseccion, B.titulo 
							FROM seccion A
							INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
							INNER JOIN seccion_carpeta C ON A.idseccion = C.idseccion
							INNER JOIN seccion_sede D ON A.idseccion = D.idseccion
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1 $filtro_sede_sp";
							$result_seccion = mysql_query($query_seccion);
							while($rs_seccion = mysql_fetch_assoc($result_seccion)){
							?>
                                    <option value="<?= $rs_seccion['idseccion'] ?>">
                                    <?= $rs_seccion['titulo'] ?>
                                    </option>
                                    <?
							}
							?>
                                  </select></td>
                                </tr>
                                <tr class="detalle_medio">
                                  <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td valign="middle" class="titulo_medio_bold"><label>
                                    <input name="Submit3" type="button" class="detalle_medio_bold" value=" Cambiar &raquo; " onclick="validar_seccion();" />
                                  </label></td>
                                </tr>
                                <? if( $rs_mod6_lista2['titulo'] ){ ?>
                                <? } ?>
                            </table>
                            <br />
                            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right">&nbsp;</td>
                                <td><strong>URL Especifica</strong></td>
                              </tr>
                            </table>
                            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right">URL Especifica:</td>
                                <td><label>
                                  <input name="urlespecifica" type="text" class="detalle_medio" id="urlespecifica" style="width:200px;" value="<?= $rs_titular['urlespecifica'] ?>" />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="110" align="right">&nbsp;</td>
                                <td><input name="Submit4" type="button" class="detalle_medio_bold" value=" Cambiar &raquo; " onclick="validar_url();" /></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
                        <br />
                        <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="37" bgcolor="#FFDDBC" class="titulo_medio_bold">Propiedades del titular: <a name="propiedades" id="propiedades"></a></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td width="100" align="right" valign="top" class="detalle_medio_bold">Sucursales: </td>
                                  <td width="556" align="left" valign="top" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
									$query_sel = "SELECT idtitular
									FROM titular_sede
									WHERE idtitular = '$idtitular' AND idsede = '$rs_sede[idsede]' ";
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
                                  <td width="100" align="right" valign="top" class="style2"><input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" /></td>
                                  <td align="left" valign="middle" class="style2"><input name="Submit233" type="button" class="detalle_medio_bold" onclick="javascript:validar_sede()" value="  &gt;&gt;  Modificar   " /></td>
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