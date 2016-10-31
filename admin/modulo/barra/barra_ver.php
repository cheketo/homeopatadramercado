<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 	
	
	//VARIABLES
	$filtro_idioma = $_POST['filtro_idioma'];
	$filtro_sede = $_POST['filtro_sede'];
	$accion = $_POST['accion'];
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
	}else{
		$obj_value = '';
	}
	
	//REORDENAR
	if($accion == "reordenar"){
	
		$orden_row = $_POST['orden_row'];
		$idbarra_menu_row = $_POST['idbarra_menu_row'];
		$cantidad = $_POST['cantidad'];
		
		for($i=0;$i<$cantidad;$i++){
			
			$query_upd = "UPDATE barra_menu
			SET orden = '$orden_row[$i]'
			WHERE idbarra_menu = '$idbarra_menu_row[$i]'";
			mysql_query($query_upd);
			
		}
	}
	
	//CAMBIO DE ESTADO
	$estado = $_POST['estado'];
	$idbarra_menu = $_POST['idbarra_menu'];
	
	if($estado != "" && $idbarra_menu != ""){
		$query = "UPDATE barra_menu
		SET estado = '$estado'
		WHERE idbarra_menu = '$idbarra_menu'	
		LIMIT 1";
		mysql_query($query);
	}
	
	//CAMBIO DEL CAMPO RESTRINGIDO
	$restringido = $_POST['restringido'];
	$idbarra_menu = $_POST['idbarra_menu'];
	
	if($restringido != "" && $idbarra_menu != ""){
		$query = "UPDATE barra_menu
		SET restringido = '$restringido'
		WHERE idbarra_menu = '$idbarra_menu'	
		LIMIT 1";
		mysql_query($query);
	}
	
	//ELIMINAR
	$eliminar = $_POST['eliminar'];
	
	if($eliminar != ""){
	
		/* SI HAY UNA SEDE DETERMINADA, DEBE COMPROBAR QUE NO PUEDE ELIMINAR EL BOTON
		 A MENOS QUE LA UNICA SEDE HABILITADA SEA LA UTILIZADA POR EL USUARIO.      */
		if($idsede_log){
			
			$query_sede = "SELECT * 
			FROM barra_menu A
			INNER JOIN barra_menu_sede B ON A.idbarra_menu = B.idbarra_menu
			WHERE A.idbarra_menu = '$eliminar' AND B.idsede != '$idsede_log' ";
			$opt_delete = mysql_num_rows(mysql_query($query_sede));
			
		} 
		   
		if($opt_delete == 0){
		
			//SI TIENE FOTOS LAS ELIMINA
			$ruta_foto = "../../../imagen/barra/";
			$query_foto = "SELECT foto FROM barra_menu_idioma
			WHERE idbarra_menu = '$eliminar'";
			$result_foto = mysql_query($query_foto);
			
			while($rs_foto = mysql_fetch_assoc($result_foto)){
				if ($rs_foto['foto'] != ""){
					if (file_exists($ruta_foto.$rs_foto['foto'])){
						unlink ($ruta_foto.$rs_foto['foto']);
					}
				}
			}
			
			$query = "DELETE FROM barra_menu
			WHERE idbarra_menu = '$eliminar'";
			mysql_query($query);
			
			$query = "DELETE FROM barra_menu_idioma
			WHERE idbarra_menu = '$eliminar'";
			mysql_query($query);
			
			$query = "DELETE FROM barra_menu_sede
			WHERE idbarra_menu = '$eliminar'";
			mysql_query($query);
			
		}else{
			echo "<script>alert('No puede eliminar este boton porque es utilizado por otras sucursales.');</script>";
		}
	}
	
?>

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
	
	function ver_barra(){
		if(document.form.filtro_idioma.value != 0 && document.form.filtro_sede.value != 0){
			document.form.submit();
		}else{
			alert("Seleccione el idioma y sucursal de la barra que desea ver.");
		}
	}
	
	function cambiar_restringido(restringido, idbarra_menu){
		formulario = document.form;
		
		formulario.restringido.value = restringido;
		formulario.idbarra_menu.value = idbarra_menu;
		formulario.submit();
	};
	
	function confirmar_eliminar_elemento(idbarra_menu){
	formulario = document.form;
		if (confirm('¿Esta seguro que desea eliminar el registro?\n Si elimina este elemento se eliminará en sus respectivos idiomas y sede también.\n Si lo que desea hacer es quitarlo, ingrese a "Editar" y configurelo segun idioma y sede.')){
			formulario.eliminar.value = idbarra_menu;
			formulario.submit();
		}
	};
	
	function reordenar(){
		formulario = document.form;
		formulario.accion.value = "reordenar";
		formulario.submit();
	};
	
</script>
<style type="text/css">
<!--
.style1 {font-size: 9px}
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Barra Menu</td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#cccccc;"></td>
              </tr>
            </table>
              <form action="" method="post" name="form" id="form">
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="ffddbc">
                    <td height="40" align="left" bgcolor="#FFE9D2" class="detalle_medio_bold">Seleccione el idioma y la sede
                    <input name="idbarra_menu" type="hidden" id="idbarra_menu" />
                    <input name="estado" type="hidden" id="estado" />
                    <input name="accion" type="hidden" id="accion" value="" /></td>
                  </tr>
                  <tr valign="middle" bgcolor="fff0e1" height="50">
                    <td align="left" bgcolor="#fff0e1" ><table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="12%" class="detalle_medio">Idioma:</td>
                        <td width="88%" class="detalle_medio"><select name="filtro_idioma" class="detalle_medio" style="width:200px;" id="filtro_idioma" onchange="javascript:document.form_lista.submit();">
                          <? 
		if($_POST['filtro_idioma'] == '0'){
			$sel_idioma = "selected";
		}else{
			$sel_idioma = "";
		}
		
		//echo $_SESSION['filtro_idioma']."-".$sel_idioma
	?>
                          <option value="0" <?= $sel_idioma ?>>- Seleccionar Idioma</option>
                          <? $query_idioma = "SELECT ididioma, titulo_idioma
						  FROM idioma 
						  WHERE estado = 1";
								  
						  $result_idioma = mysql_query($query_idioma);
						  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
						  	
								if($_POST['filtro_idioma'] == $rs_idioma['ididioma']){
									$sel_idioma = "selected";
								}else{
									$sel_idioma = "";
								}
						  
						  ?>
                          <option value="<?= $rs_idioma['ididioma'] ?>" <?= $sel_idioma ?>>
                            <?= $rs_idioma['titulo_idioma'] ?>
                          </option>
                          <? } ?>
                        </select></td>
                      </tr>
                      <tr>
                        <td class="detalle_medio">Sucursal:</td>
						<td width="88%" class="detalle_medio"><select name="filtro_sede" style="width:200px;" class="detalle_medio" id="filtro_sede" <?= $obj_value ?> onchange="javascript:document.form_lista.submit();">
                          <option value="0">- Seleccione Sucursal</option>
                          <? $query_sede = "SELECT idsede, titulo
						  FROM sede 
						  WHERE estado = 1";
								  
						  $result_sede = mysql_query($query_sede);
						  while($rs_sede = mysql_fetch_assoc($result_sede)){
						  	
								if($filtro_sede == $rs_sede['idsede']){
									$sel_sede = "selected";
								}else{
									$sel_sede = "";
								}
						  
						  ?>
                          <option value="<?= $rs_sede['idsede'] ?>" <?= $sel_sede ?>>
                            <?= $rs_sede['titulo'] ?>
                          </option>
                          <? } ?>
                        </select></td>
                      </tr>
                      <tr>
                        <td class="detalle_medio">&nbsp;</td>
                        <td class="detalle_medio"><label>
                          <input name="Button" type="button" class="detalle_medio_bold" value="Ver barra &raquo;" onclick="ver_barra()" />
                        </label></td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr align="center" valign="middle" height="50">
                    <td height="20" align="left" valign="top" class="detalle_medio" style="color:#FF6600"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                    </table>                      <br />
                      <? if($filtro_idioma && $filtro_sede){ ?>
                      <br />
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td height="40" bgcolor="#FFE9D2"><span class="detalle_medio_bold">Barra menu 
                                <input name="eliminar" type="hidden" id="eliminar" value=""/>
                                <input name="restringido" type="hidden" id="restringido" value=""/>
                              </span></td>
                              <td align="right" valign="middle" bgcolor="#FFE9D2"><? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                                <input name="Button2" type="button" class="detalle_medio_bold" value="Nuevo Boton &raquo;" onclick="window.open('barra_nuevo.php?ididioma=<?= $filtro_idioma ?>&amp;idsede=<?= $filtro_sede ?>','_self');" /> 
							  <? } ?>
                              &nbsp;  <input name="Button22" type="button" class="detalle_medio_bold" value="Reordenar botones &raquo;" onclick="javascript:reordenar();" /></td>
                            </tr>
                            <tr>
                              <td colspan="2" bgcolor="#FFF0E1">
							  <?
								$c=0;
								$query_barra = "SELECT A.link, A.idbarra_menu, A.tipo, A.orden, A.estado as estado_barra , B.titulo, A.restringido
								FROM barra_menu A
								INNER JOIN barra_menu_idioma B ON A.idbarra_menu = B.idbarra_menu
								INNER JOIN barra_menu_sede C ON A.idbarra_menu = C.idbarra_menu
								WHERE A.idbarra_padre = '0' AND A.estado != '3' AND B.ididioma = '$filtro_idioma' AND C.idsede = '$filtro_sede'
								ORDER BY A.orden ASC";
								$result_barra = mysql_query($query_barra);
								while($rs_barra = mysql_fetch_assoc($result_barra)){
								?>
							  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                
								<tr>
                                  <td width="4%" align="center" valign="top">
								  <?
								  switch($rs_barra['tipo']){
								  	case 1:
										echo '<img src="../../imagen/iconos/mini_folder.png" width="25" height="25" />';
										break;
									case 2:
										echo '<img src="../../imagen/iconos/seccion.png" width="25" height="25" />';
										break;
									case 3:
										echo '<img src="../../imagen/iconos/mini_producto.png" width="25" height="25" />';
										break;
									case 4:
										echo '<img src="../../imagen/iconos/ie_icon.png" width="25" height="25" />';
										break;
									case 5:
										echo '<img src="../../imagen/iconos/image_add_mini.png" width="18" height="18" />';
										break;
								  }
								  ?>
								  <input name="idbarra_menu_row[<?= $c ?>]" type="hidden" id="idbarra_menu_row[<?= $c ?>]" value="<?= $rs_barra['idbarra_menu'] ?>" /></td>
                                  <td class="detalle_medio" ><span class="detalle_chico">
                                    <input name="orden_row[<?= $c ?>]" type="text" class="detalle_chico" id="orden_row[<?= $c ?>]" style="width:18px; height:10px;" value="<?= $rs_barra['orden'] ?>" />
                                    . </span><span style="color:#000000"><?= $rs_barra['titulo'] ?></span>
                                  <br />
                                  <span class="detalle_chico" style="color:#666666">
                                  <?= $rs_barra['link'] ?>
                                  </span></td>
                                  <td width="21%" align="right" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="72%" align="left" class="detalle_chico style1">Propiedades</td>
                                      <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                                    </tr>
                                    <tr>
                                      <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td align="left"><? 
								   if($rs_barra['estado_barra'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_barra['idbarra_menu'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_barra['idbarra_menu'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?> &nbsp;<? 
								   if($rs_barra['restringido'] == 1){ 
									  echo "<a href=\"javascript:cambiar_restringido(2,".$rs_barra['idbarra_menu'].");\"><img src=\"../../imagen/s_rights.png\" width=\"16\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_restringido(1,".$rs_barra['idbarra_menu'].");\"><img src=\"../../imagen/s_rights_b.png\" width=\"14\" height=\"14\" border=\"0\" /></a>";
								   }
								  ?></td>
                                          </tr>
                                      </table></td>
                                      <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td align="right"><a href="barra_editar.php?tipo=<?= $rs_barra['tipo'] ?>&idbarra_menu=<?= $rs_barra['idbarra_menu'] ?>&ididioma=<?= $filtro_idioma ?>&idsede=<?= $filtro_sede ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a> &nbsp;<a href="javascript:confirmar_eliminar_elemento(<?= $rs_barra['idbarra_menu'] ?>);"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                  </table>                                    </td>
								</tr>
								<tr>
								  <td height="5" colspan="3"><hr width="100%" size="1" class="detalle_medio" style="color:#999999; height:1px;"/></td>
							    </tr>
                              </table>
							  <?
								
								$query_barra_2 = "SELECT A.link, A.idbarra_menu, A.tipo, A.orden, A.estado as estado_barra , B.titulo, A.restringido
								FROM barra_menu A
								INNER JOIN barra_menu_idioma B ON A.idbarra_menu = B.idbarra_menu
								INNER JOIN barra_menu_sede C ON A.idbarra_menu = C.idbarra_menu
								WHERE A.idbarra_padre = '$rs_barra[idbarra_menu]' AND A.estado != '3' AND B.ididioma = '$filtro_idioma' AND C.idsede = '$filtro_sede'
								ORDER BY A.orden ASC";
								$result_barra_2 = mysql_query($query_barra_2);
								while($rs_barra_2 = mysql_fetch_assoc($result_barra_2)){
								?>
							  <br />
							  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td width="7%" align="center" valign="top">&nbsp;</td>
                                  <td width="4%" align="center" valign="top"><?
								  switch($rs_barra_2['tipo']){
								  	case 1:
										echo '<img src="../../imagen/iconos/mini_folder.png" width="25" height="25" />';
										break;
									case 2:
										echo '<img src="../../imagen/iconos/seccion.png" width="25" height="25" />';
										break;
									case 3:
										echo '<img src="../../imagen/iconos/mini_producto.png" width="25" height="25" />';
										break;
									case 4:
										echo '<img src="../../imagen/iconos/ie_icon.png" width="25" height="25" />';
										break;
									case 5:
										echo '<img src="../../imagen/iconos/image_add_mini.png" width="18" height="18" />';
										break;
								  }
								  ?>
                                    <input name="idbarra_menu_row[<?= $c ?>]2" type="hidden" id="idbarra_menu_row[<?= $c ?>]2" value="<?= $rs_barra_2['idbarra_menu'] ?>" /></td>
                                  <td width="68%" class="detalle_medio" ><span class="detalle_chico">
                                    <input name="orden_row[<?= $c ?>]2" type="text" class="detalle_chico" id="orden_row[<?= $c ?>]2" style="width:18px; height:10px;" value="<?= $rs_barra_2['orden'] ?>" />
                                    . </span><span style="color:#000000">
                                      <?= $rs_barra_2['titulo'] ?>
                                      </span> <br />
                                    <span class="detalle_chico" style="color:#666666">
                                      <?= $rs_barra_2['link'] ?>
                                    </span></td>
                                  <td width="21%" align="right" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td width="72%" align="left" class="detalle_chico style1">Propiedades</td>
                                        <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                                      </tr>
                                      <tr>
                                        <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr>
                                              <td align="left"><? 
								   if($rs_barra_2['estado_barra'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_barra_2['idbarra_menu'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_barra_2['idbarra_menu'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?>
                                   &nbsp;
                                   <? 
								   if($rs_barra_2['restringido'] == 1){ 
									  echo "<a href=\"javascript:cambiar_restringido(2,".$rs_barra_2['idbarra_menu'].");\"><img src=\"../../imagen/s_rights.png\" width=\"16\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_restringido(1,".$rs_barra_2['idbarra_menu'].");\"><img src=\"../../imagen/s_rights_b.png\" width=\"14\" height=\"14\" border=\"0\" /></a>";
								   }
								  ?></td>
                                            </tr>
                                        </table></td>
                                        <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr>
                                              <td align="right"><a href="barra_editar.php?tipo=<?= $rs_barra_2['tipo'] ?>&idbarra_menu=<?= $rs_barra_2['idbarra_menu'] ?>&ididioma=<?= $filtro_idioma ?>&idsede=<?= $filtro_sede ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a> &nbsp;<a href="javascript:confirmar_eliminar_elemento(<?= $rs_barra_2['idbarra_menu'] ?>);"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a></td>
                                            </tr>
                                        </table></td>
                                      </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td height="5" colspan="4"><hr width="100%" size="1" class="detalle_medio" style="color:#999999; height:1px;"/></td>
                                </tr>
                              </table>
							  <? } ?>
							<? $c++; } ?></td>
                            </tr>
                      </table>
                    <? } ?>
                    <input name="cantidad" type="hidden" id="cantidad" value="<?= $c ?>" /></td>
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