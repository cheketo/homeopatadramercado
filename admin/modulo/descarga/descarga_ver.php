<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

	// localizacion de variables get y post:
	$ruta_descarga = "../../../descarga/";	
	$accion = $_POST['accion'];
	$estado = $_POST['estado'];
		
	//FILTRO BUSQUEDA
	$filtro_busqueda = "";
	
	if($_POST['busqueda'] != '' ){
		$filtro_busqueda .= " AND titulo LIKE '%$_POST[busqueda]%' OR archivo LIKE '%$_POST[busqueda]%' ";
	}else{
	
		if($_POST['carpeta'] == '' ){
			$filtro_busqueda .= " AND idcarpeta = '' ";
		}
		
		if($_POST['seccion'] == '' ){
			$filtro_busqueda .= " AND idseccion = '' ";
		}
		
		if($_POST['producto'] == '' ){
			$filtro_busqueda .= " AND idproducto = '' ";
		}
		
		if($_POST['carpeta'] == 1 ){
			$filtro_busqueda .= " OR idcarpeta != '0' ";
		}
		
		if($_POST['seccion'] == 1 ){
			$filtro_busqueda .= " OR idseccion != '0' ";
		}
		
		if($_POST['producto'] == 1 ){
			$filtro_busqueda .= " OR idproducto != '0' ";
		}
	
	}
	
	$estado_descarga = $_POST['estado_descarga'];
	$estado_restringido = $_POST['estado_restringido'];
	$eliminar_iddescarga = $_POST['eliminar_iddescarga'];
	$iddescarga = $_POST['iddescarga'];
	
	//CAMBIAR ESTADO DESCARGA	
	if($estado_descarga != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET estado = '$estado_descarga'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
	
	}
	
	//CAMBIAR RESTRINGISDO DESCARGA	
	if($estado_restringido != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET restringido = '$estado_restringido'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
	
	}
	
	//ELIMINAR DESCARGA	
	if($eliminar_iddescarga != ""){
		$query_estado = "SELECT archivo
		FROM descarga
		WHERE iddescarga = '$eliminar_iddescarga'
		LIMIT 1";
		$rs_del = mysql_fetch_assoc(mysql_query($query_estado));
		
		if (file_exists($ruta_descarga.$rs_del['archivo'])){
			unlink ($ruta_descarga.$rs_del['archivo']);
		}
		
		$query = "DELETE FROM descarga WHERE iddescarga = '$eliminar_iddescarga' LIMIT 1 ";
		mysql_query($query);
	
	}
	
	//FUNCION PAGINAR
	function paginar($actual, $total, $por_pagina, $enlace) {
	  $total_paginas = ceil($total/$por_pagina);
		  $anterior = $actual - 1;
		  $posterior = $actual + 1;
		  if ($actual>1)
			$texto = "<a href=\"javascript:ir_pagina('$anterior');\">&laquo;</a> ";
		  else
			$texto = "<b>&laquo;</b> ";
		  for ($i=1; $i<$actual; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
		  $texto .= "<b>$actual</b> ";
		  for ($i=$actual+1; $i<=$total_paginas; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
		  if ($actual<$total_paginas)
			$texto .= "<a href=\"javascript:ir_pagina('$posterior');\">&raquo;</a>";
		  else
			$texto .= "<b>&raquo;</b>";
		  return $texto;
	}
	
	//PAGINACION
	$cantidad_registros = $_POST['cant'];
	if(!$cantidad_registros){
		$cantidad_registros = 25;
	}
	
	$pag = $_POST['pag'];
	if(!$pag){
		$pag = 1;
	}
	$puntero = ($pag-1) * $cantidad_registros;
	
	//FILTRADO
	if($_POST['ordenar'] != ""){
		$_SESSION['ordenar'] = $_POST['ordenar'];
		$filtro_ordenar = $_SESSION['ordenar'];
	}else{
		$filtro_ordenar = $_SESSION['ordenar'];
	}
	
	if($filtro_ordenar != ""){
		$filtrado_ordenar = $filtro_ordenar." DESC ";
	}else{
		$filtrado_ordenar = " idcarpeta DESC ";
	}
	
	//CANTIDAD TOTAL
	$query_cant = "SELECT *
	FROM descarga
	WHERE 1=1 $filtro_busqueda
	ORDER BY $filtrado_ordenar";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_tips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>

<script language="javascript">

	function ver_titulares(){
		f = document.form;
		f.accion.value = "buscar";
		f.pag.value = 1;
		f.submit();
	}
	
	function cambiar_estado(estado, idtitular, ididioma){
	formulario = document.form;
	
	formulario.estado.value = estado;
	formulario.idtitular.value = idtitular;
	formulario.ididioma.value = ididioma;
	formulario.submit();
	};
	
	function ir_pagina(pag){
		formulario = document.form;
		formulario.pag.value = pag;
		formulario.accion.value = "buscar";
		formulario.submit();
	};
	
	function confirmar_eliminar(idtitular, ididioma){
		if (confirm('¿Está seguro que desea borrar el titular?')){
			var formulario = document.form;
			
			formulario.ididioma.value = ididioma;
			formulario.idtitular.value = idtitular;
			formulario.accion.value = "eliminar";
			formulario.submit();
		}
	};
	
	function cambiar_estado_descarga(estado, iddescarga){
		formulario = document.form;
		
		formulario.estado_descarga.value = estado;
		formulario.iddescarga.value = iddescarga;
		formulario.submit();
	}
	
	function cambiar_restringido_descarga(estado, iddescarga){
		formulario = document.form;
		
		formulario.estado_restringido.value = estado;
		formulario.iddescarga.value = iddescarga;
		formulario.submit();
	}
	
	function eliminar_descarga(eliminar_iddescarga){
		formulario = document.form;
		
		formulario.eliminar_iddescarga.value = eliminar_iddescarga;
		formulario.submit();
	}
	
	window.addEvent('domready', function(){
		//TIPS	
		var Tips1 = new Tips($$('.Tips1'), {
			initialize:function(){
				this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 200, wait: false}).set(0);
			},
			onShow: function(toolTip) {
				this.fx.start(1);
			},
			onHide: function(toolTip) {
				this.fx.start(0);
			}
		});
	});
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Descargas - Ver</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Buscar descargas en:
                            <input name="accion" type="hidden" id="accion" value="" />
                            <span class="detalle_medio">
                          <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                          <input name="estado_descarga" type="hidden" id="estado_descarga" />
                          <input name="iddescarga" type="hidden" id="iddescarga" />
                          <input name="estado_restringido" type="hidden" id="estado_restringido" />
                          <input name="eliminar_iddescarga" type="hidden" id="eliminar_iddescarga" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="3" cellspacing="0">
                              <tr>
                                <td width="59" align="left" valign="middle" class="detalle_medio">Carpeta: </td>
                                <td width="607" align="left" valign="middle"><label>
                                  <input name="carpeta" type="checkbox" id="carpeta" value="1" onclick="javascript:document.form.submit();" <? if($_POST['carpeta'] == 1){ echo "checked"; } ?> />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="59" align="left" valign="middle" class="detalle_medio">Secci&oacute;n:</td>
                                <td align="left" valign="middle"><input name="seccion" type="checkbox" id="seccion" value="1" onclick="javascript:document.form.submit();" <? if($_POST['seccion'] == 1){ echo "checked"; } ?> /></td>
                              </tr>
                              <tr>
                                <td width="59" align="left" valign="middle" class="detalle_medio">Producto:</td>
                                <td align="left" valign="middle"><input name="producto" type="checkbox" id="producto" value="1" onclick="javascript:document.form.submit();" <? if($_POST['producto'] == 1){ echo "checked"; } ?> /></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="82%" colspan="2" align="right" class="detalle_medio">Ordenar por: </td>
                        <td width="18%" align="right">
						<select name="ordenar" class="detalle_medio" style="width:160px;" id="ordenar" onchange="document.form.accion.value = 'buscar'; document.form.submit();">
                            <option value="" <? if($filtro_ordenar == ""){ echo "selected";} ?>>- Seleccione Orden </option>
							<option value="idcarpeta" <? if($filtro_ordenar == "idcarpeta"){ echo "selected";} ?>>Carpeta</option>
							<option value="idseccion" <? if($filtro_ordenar == "idseccion"){ echo "selected";} ?>>Seccion</option>
                            <option value="idproducto" <? if($filtro_ordenar == "idproducto"){ echo "selected";} ?>>Producto</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td align="left" class="detalle_medio">Buscar:
                          <input name="busqueda" type="text" class="detalle_medio" id="busqueda" style="width:180px;" value="<?= $busqueda ?>" />
                          <a href="javascript:document.form.submit();"> <img src="../../imagen/iconos/search_mini.png" width="16" height="16" border="0" /></a></td>
                        <td align="right" class="detalle_medio">Registros por hoja:</td>
                        <td align="right"><select name="cant" class="detalle_medio" style="width:160px;" onchange="document.form.pag.value = 1; document.form.accion.value = 'buscar'; document.form.submit();">
                          <option value="5"  <? if($cantidad_registros == 5) { echo "selected"; } ?>>5 </option>
                          <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                          <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?>>25</option>
                          <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?>>50</option>
                        </select></td>
                      </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="ffddbc">
                        <td width="24" height="35" align="center" bgcolor="#ffddbc" class="detalle_medio_bold">ID</td>
                        <td height="35" colspan="2" align="left" bgcolor="#ffddbc" class="detalle_medio_bold">Titulo</td>
                        <td width="188" height="35" align="left" bgcolor="#ffddbc" class="detalle_medio_bold">Archivo</td>
                        <td width="69" height="35" align="left" bgcolor="#ffddbc" class="detalle_medio_bold">Tama&ntilde;o</td>
                        <td width="76" height="35" align="left" bgcolor="#ffddbc" class="detalle_medio_bold">Tipo</td>
                        <td width="88" height="35" align="left" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
					  <?
					  $hay_lista = false;
					  $colores = array("#fff0e1","#FFE1C4");
					  $cont_colores = 0;
					  
					  $query_descarga = "SELECT *
						FROM descarga
						WHERE 1=1 $filtro_busqueda
						ORDER BY $filtrado_ordenar
					  	LIMIT $puntero,$cantidad_registros";
					  $result_descarga = mysql_query($query_descarga);
					  while($rs_lista = mysql_fetch_assoc($result_descarga)){
					  	$hay_lista =true;
					  ?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td height="28" align="center" valign="middle" class="detalle_chico"><span ><?=$rs_lista['iddescarga']?>.
                        </span></td>
                        <td width="17" height="28" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_11px">
							<? 
							if($rs_lista['idcarpeta'] != '0' ){
								echo '<a href="../carpeta/carpeta_editar.php?idcarpeta='.$rs_lista['idcarpeta'].'" target="_blank" ><img src="../../imagen/iconos/folder_xp20.png" width="20" height="20" border="0" /></a>';
							} 
							
							if($rs_lista['idproducto'] != '0' ){
								echo '<a href="../producto/producto_editar.php?idproducto='.$rs_lista['idproducto'].'" target="_blank" ><img src="../../imagen/iconos/producto20px.png" width="20" height="20" border="0" /></a>';
							}
							
							if($rs_lista['idseccion'] != '0' ){
								echo '<a href="../seccion/seccion_editar.php?idseccion='.$rs_lista['idseccion'].'" target="_blank" ><img src="../../imagen/iconos/seccion20px.png" width="20" height="20" border="0" /></a>';
							}
							?>						</td>
                        <td width="156" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_11px"><?=$rs_lista['titulo']?></td>
                        <td height="28" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_11px"><?=$rs_lista['archivo']?></td>
                        <td height="28" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_11px"><? 
						
							if(file_exists($ruta_descarga.$rs_lista['archivo'])){ 
								$peso_kb = filesize($ruta_descarga.$rs_lista['archivo'])/1024;

								if($peso_kb < 1024){
									echo number_format($peso_kb, 2)." kb.";
								}else{
									echo number_format($peso_kb/1024, 2)." mb.";
								}
							}
						
						?></td>
                        <td height="28" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_11px">
                          <?
						   
						  $query = "SELECT B.titulo AS titulo_tipo
						  FROM descarga A
						  INNER JOIN descarga_tipo B ON A.idtipo_descarga = B.iddescarga_tipo
						  WHERE B.iddescarga_tipo = '$rs_lista[idtipo_descarga]' "; 
						  $rs_descarga_tipo = mysql_fetch_assoc(mysql_query($query));
						  echo $rs_descarga_tipo['titulo_tipo'];
						  
						  ?>                        </td>
                        <td height="28" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>">
							<? 
							
						  	 if($rs_lista['estado'] == 1){ 
						  		echo '<a href="javascript:cambiar_estado_descarga(2,'.$rs_lista['iddescarga'].');"><img src="../../imagen/b_habilitado.png" width="15" height="16" border="0" /></a>';
						     }else{ 
						  		echo '<a href="javascript:cambiar_estado_descarga(1,'.$rs_lista['iddescarga'].');"><img src="../../imagen/b_habilitado_off.png" width="15" height="16" border="0" /></a>';
						     } 
						   
						  	?>
                          &nbsp;
                          <?
						  
						  	if($rs_lista['restringido'] == 1){
						  		echo '<a href="javascript:cambiar_restringido_descarga(2,'.$rs_lista['iddescarga'].');"><img src="../../imagen/s_rights.png" width="16" height="16" border="0" /></a>';
						  	}else{
						  		echo '<a href="javascript:cambiar_restringido_descarga(1,'.$rs_lista['iddescarga'].');"><img src="../../imagen/s_rights_b.png" width="14" height="14" border="0" /></a>';
							}
						  
						  ?>
                          <a href="descarga_editar.php?iddescarga=<?= $rs_lista['iddescarga'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a>&nbsp;
                        <? 
						   	echo '<a href="javascript:eliminar_descarga('.$rs_lista['iddescarga'].');"><img src="../../imagen/trash.png" width="15" height="16" border="0" /></a>'
						  ?>						  </td>
                      </tr>
                      <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                      <tr align="center" valign="middle" >
                        <td colspan="7" bgcolor="#fff0e1" class="detalle_medio" height="40">No se han encontrado descargas.</td>
                      </tr>
<? };
	?>
                      <tr align="center" valign="middle" >
                        <td colspan="7" class="detalle_medio_bold" height="30">
						<? 
						if($cantidad_total > $cantidad_registros ){
							 echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']);
						} 
						?></td>
                      </tr>
                    </table>
					</form></td>
                </tr>
              </table>
          </td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>