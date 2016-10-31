<? 	include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	
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
	$accion = $_POST['accion'];
	$idsede = $_POST['idsede'];
	$cantidad_sede = $_POST['cantidad_sede'];
	
	$url = $_POST['url'];
	$titulo = $_POST['titulo'];
	$target = $_POST['target'];
	$orden = $_POST['orden'];
	$ididioma_row = $_POST['ididioma_row'];
	
	$idbarra_pie = $_GET['idbarra_pie'];
	$cantidad_idioma_editar = $_POST['cantidad_idioma_editar'];
	
	//GUARDAR CAMBIOS
	if($accion == "guardar_cambios"){
		
		//INGRESO
		$query_upd = "UPDATE barra_pie SET
		  link = '$url'
		, target = '$target'
		, orden = '$orden' 
		WHERE idbarra_pie = '$idbarra_pie'
		LIMIT 1";
		mysql_query($query_upd);
	
	}//FIN
	
	//CAMBIO DE ESTADO
	$estado_idioma = $_POST['estado_idioma'];
	$ididioma = $_POST['ididioma'];
	
	if($estado_idioma != "" && $ididioma != ""){
		$query = "UPDATE barra_pie_idioma
		SET estado = '$estado_idioma'
		WHERE idbarra_pie = '$idbarra_pie'	AND ididioma = '$ididioma'
		LIMIT 1";
		mysql_query($query);
	}
	
	//MODIFICAR IDIOMA
	if($accion == "modificar_idioma"){
		
		for($i=0;$i<$cantidad_idioma_editar;$i++){
			$query = "UPDATE barra_pie_idioma 
			SET titulo = '$titulo[$i]'
			WHERE  idbarra_pie = '$idbarra_pie'	AND ididioma = '$ididioma_row[$i]'
			LIMIT 1";
			mysql_query($query);
		}
		
	}
	
	//MODIFICAR SEDE			
	if($accion == "modificar_sucursales"){
	
		//GUARDO CAMBIOS EN BARRA_MENU_SEDE
		$query_del_sede = "DELETE FROM barra_pie_sede WHERE idbarra_pie = '$idbarra_pie' $filtro_sede";
		mysql_query($query_del_sede);
		
		for($c=0;$c<$cantidad_sede;$c++){
			if($idsede[$c] != ""){
				$query_sede = "INSERT INTO barra_pie_sede(
				  idbarra_pie
				, idsede
				)VALUES(
				  '$idbarra_pie'
				, '$idsede[$c]'
				)";
				mysql_query($query_sede);
			}
		}
		
	}
	
	//CONSULTA
	$query_pie = "SELECT A.*
	FROM barra_pie A
	WHERE A.idbarra_pie = '$idbarra_pie'";
	$rs_pie = mysql_fetch_assoc(mysql_query($query_pie));
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
<script type="text/javascript">
	
	
	function cambiar_estado(estado, ididioma){
		formulario = document.form;
		formulario.estado_idioma.value = estado;
		formulario.ididioma.value = ididioma;
		formulario.submit();
	};
	
	function guardar_cambios(){
		var formulario = document.form;
		var flag = true;
		
		if(formulario.url.value == ""){
			alert("Debe introducir la URL.");
			flag = false;
		}
		
		if(esNumerico(formulario.orden.value)==false){
			alert("El Orden debe ser númerico.");
			flag = false;
		}
			
		if(flag == true){
			formulario.accion.value = "guardar_cambios";
			formulario.submit();
		}
	
	};
	
	function modificar_idioma(){
		var formulario = document.form;
		var flag = true;
		
		for(i=0;i<formulario.cantidad_idioma_editar.value;i++){
			
			actual = document.getElementById("titulo["+i+"]");
			if (actual.value == ""){
				alert("Por favor, introduzca el titulo.");
				flag = false;
				break;
			}
					
		}
		
		if(flag == true){
			formulario.accion.value = "modificar_idioma";
			formulario.submit();
		}
	};
	
	function modificar_sucursales(){
		var formulario = document.form;
		var flag = true;
		var checks_sede = 0;
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = document.getElementById("idsede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			alert("Debe seleccionar al menos una sucursal.");
			flag = false;
		}
		
		if(flag == true){
			formulario.accion.value = "modificar_sucursales";
			formulario.submit();
		}
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Barra Pie - Editar </td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#cccccc;"></td>
              </tr>
            </table>
              <form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="#ffddbc">
                    <td height="40" align="left" bgcolor="#FFE9D2" class="titulo_medio_bold">Editar Link                    
                      <input name="accion" type="hidden" id="accion" /></td>
                  </tr>
                  <tr valign="middle" bgcolor="#fff0e1" height="50">
                    <td align="left" bgcolor="#fff0e1" ><table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="105" align="right" class="detalle_medio">Link:</td>
                            <td width="553"><label>
                              <input name="url" type="text" class="detalle_medio" id="url" size="70" value="<?= $rs_pie['link'] ?>" />
                          </label></td>
                        </tr>
                      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
					      <tr>
					        <td width="105" align="right" class="detalle_medio">Target:</td>
                            <td width="553"><label>
                            <select name="target" class="detalle_medio" id="target">
                              <option value="_self" <? if($rs_pie['target'] == "_self"){ echo "selected"; } ?>>_self</option>
                              <option value="_blank" <? if($rs_pie['target'] == "_blank"){ echo "selected"; } ?>>_blank</option>
                              <option value="_parent" <? if($rs_pie['target'] == "_parent"){ echo "selected"; } ?>>_parent</option>
                              <option value="_top" <? if($rs_pie['target'] == "_top"){ echo "selected"; } ?>>_top</option>
                            </select>
                            </label></td>
                          </tr>
				      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="105" align="right" class="detalle_medio">Orden:</td>
                            <td width="553"><label>
                              <input name="orden" type="text" class="detalle_medio" id="orden" value="<?= $rs_pie['orden'] ?>" size="7" />
                          </label></td>
                        </tr>
                      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="105" align="right" class="detalle_medio">&nbsp;</td>
                            <td width="553"><label>
                            <input name="Button" type="button" class="detalle_medio_bold" value=" Guardar Cambios &raquo;" onclick="javascript:guardar_cambios()"/>
                          </label></td>
                        </tr>
                      </table>
				    </td>
                  </tr>
                </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr align="center" valign="middle" height="50">
                    <td height="20" align="left" valign="top" class="detalle_medio" style="color:#FF6600"><br />
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td width="50%" height="40" bgcolor="#FFE697" class="titulo_medio_bold">&nbsp; Informaci&oacute;n del link: <a name="idioma" id="idioma"></a><span class="detalle_chico" style="color:#FF0000">
                              <input name="estado_idioma" type="hidden" id="estado_idioma" value="" />
                              <span class="detalle_chico" style="color:#FF0000">
                              <input name="ididioma" type="hidden" id="ididioma" value="" />
                            </span></span></td>
                            <td width="50%" align="right" valign="middle" bgcolor="#FFE697" class="titulo_medio_bold"><input name="Button2" type="button" class="detalle_medio_bold" value=" Guardar Cambios &raquo;" onclick="javascript:modificar_idioma()"/> &nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" align="left"><table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
                        <?
						$c=0; 					
						$query_idioma = "SELECT A.*, B.titulo_idioma, A.estado as estado_idioma
						FROM barra_pie_idioma A
						LEFT JOIN idioma B ON B.ididioma = A.ididioma
						WHERE A.idbarra_pie = '$idbarra_pie' AND B.estado = '1'
						ORDER BY A.ididioma";
						$result_idioma = mysql_query($query_idioma);
						while($rs_idioma = mysql_fetch_assoc($result_idioma)){			
						?>
                                <tr>
                                  <td bgcolor="#FFECB3" class="detalle_medio_bold">Idioma: <a name="<?= $ididioma ?>" id="<?= $ididioma ?>"></a><a href="categoria_editar_idioma.php?idcategoria=<?= $idcategoria ?>&amp;ididioma=<?=$rs_idioma['ididioma']?>" target="_blank" class="style10"></a></td>
                                  <td width="536" bgcolor="#FFECB3" class="detalle_medio_bold"><?=$rs_idioma['titulo_idioma']?></td>
                                  <td width="17" align="center" valign="middle" bgcolor="#FFECB3" class="detalle_medio_bold"><? if($rs_idioma['estado_idioma'] == 1){ ?>
                                      <a href="javascript:cambiar_estado(2,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado.png" width="15" height="16" border="0" /></a>
                                      <? }else{?>
                                      <a href="javascript:cambiar_estado(1,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado_off.png" width="15" height="16" border="0" /></a>
                                      <? } ?></td>
                                </tr>
                                <tr>
                                  <td width="105" align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Titulo:
                                  <input name="ididioma_row[<?= $c ?>]" type="hidden" id="ididioma_row[<?= $c ?>]" value="<?=$rs_idioma['ididioma']?>" /></td>
                                  <td colspan="2" bgcolor="#FFF5D7" class="detalle_medio"><label>
                                    <input name="titulo[<?= $c ?>]" type="text" class="detalle_medio" id="titulo[<?= $c ?>]" value="<?=$rs_idioma['titulo']?>" size="70" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td height="12" colspan="3" bgcolor="#FFF5D7" class="detalle_medio"></td>
                                </tr>
                                <? 	$c++;				
							};?>
                            </table></td>
                          </tr>
                      </table>
                        <input name="cantidad_idioma_editar" type="hidden" id="cantidad_idioma_editar" value="<?= $c ?>" />
<br />
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td height="40" bgcolor="#FFE9D2" class="titulo_medio_bold">Sucursales</td>
                        </tr>
                        <tr>
                          <td bgcolor="#fff0e1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="110" align="right" valign="top" class="detalle_medio">Sucursales: </td>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
										$query_pie_sede = "SELECT A.idsede
										FROM barra_pie_sede A
										WHERE A.idsede = '$rs_sede[idsede]' AND A.idbarra_pie = '$idbarra_pie'";
										$rs_pie_sede = mysql_fetch_assoc(mysql_query($query_pie_sede));
										
										if($rs_pie_sede['idsede'] == $rs_sede['idsede']){
											$check = "checked";
										}else{
											$check = "";
										}
								
								?>
                                  <tr>
                                    <td width="4%" height="24"><input type="checkbox" id="idsede[<?= $c ?>]" name="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?= $check ?> <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?> /></td>
                                    <td width="96%" class="detalle_medio"><?= $rs_sede['titulo'] ?></td>
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
                            <tr>
                              <td width="110" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                              <td><input name="Button3" type="button" class="detalle_medio_bold" value=" Guardar Cambios &raquo;" onclick="javascript:modificar_sucursales()"/></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                  </tr>
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