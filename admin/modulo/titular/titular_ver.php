<? include ("../../0_mysql.php"); 

	//DESPLEGAR BOTON DE BARRA N°
	$desplegarbarra = 2;
	
	// localizacion de variables get y post:
	$ruta_foto = "../../../imagen/titular/";
	$accion = $_POST['accion'];
	$tipo_titular =  $_POST['tipo_titular'];
	
	if($_GET['idtitular']){
		$filtro_especifico = " AND A.idtitular = '$_GET[idtitular]' ";
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
	
	$estado = $_POST['estado'];
	$ididioma = $_POST['ididioma'];
	$idtitular = $_POST['idtitular'];
	
	//CAMBIAR ESTADO
	if($estado != "" && $ididioma != "" && $idtitular != ""){
		$query = "UPDATE titular SET estado = '$estado'
		WHERE ididioma = '$ididioma' AND idtitular = '$idtitular'";
		mysql_query($query);
		$accion = "buscar";
	}
	
	//BORRAR TITULAR	
	if($accion == "eliminar"){
	
		$query_preliminar = "SELECT * 
		FROM titular
		WHERE idtitular = '$idtitular' AND ididioma = '$ididioma' ";
		$rs_preliminar = mysql_fetch_assoc(mysql_query($query_preliminar));
	
		if ( file_exists($ruta_foto.$rs_preliminar['foto']) ){
			unlink($ruta_foto.$rs_preliminar['foto']);
		};
		
		$query_eliminar = "DELETE FROM titular WHERE idtitular = '$idtitular' AND ididioma = '$ididioma' ";
		mysql_query($query_eliminar);
		
		$accion = "buscar";
	}
	
	//BUSCO POR TIPO
	if($accion == "buscar"){
		
		switch($tipo_titular){
			case 0: //Todos
				$filtro_tipo = "";
				break;
			case 1: //Carpeta
				$filtro_tipo = " AND A.idcarpeta != 0 ";
				break;
			case 2: //Seccion
				$filtro_tipo = " AND A.idseccion != 0 ";
				break;
			case 3: //Producto
				$filtro_tipo = " AND A.idproducto != 0 ";
				break;
			case 4: //General
				$filtro_tipo = " AND A.idcarpeta = 0 AND A.idseccion = 0 AND A.idproducto = 0 ";
				break;
			default:
				$filtro_tipo = " AND 2=1 ";
				break;
		}
		
	}
	
	//FILTRADO
	if($_POST['ordenar'] != ""){
		$_SESSION['ordenar'] = $_POST['ordenar'];
		$filtro_ordenar = $_SESSION['ordenar'];
	}else{
		$filtro_ordenar = $_SESSION['ordenar'];
	}
	
	if($_POST['filtro_idioma'] != ""){ 
		$_SESSION['filtro_idioma'] = $_POST['filtro_idioma'];
		$filtro_idioma = $_SESSION['filtro_idioma'];
	}else{
		$filtro_idioma = $_SESSION['filtro_idioma'];
	}
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
		$filtrado_sede = " AND C.idsede = '$filtro_sede' ";
	}else{
		$obj_value = '';
		//FILTRADO
		if($_POST['filtro_sede'] != ""){ 
			$_SESSION['filtro_sede'] = $_POST['filtro_sede'];
			$filtro_sede = $_SESSION['filtro_sede'];
		}else{
			$filtro_sede = 0;
			$filtro_sede = $_SESSION['filtro_sede'];
		}
	}
	
	if($filtro_idioma != 0){
		$filtro_idioma = " AND A.ididioma = '$filtro_idioma' ";
	}else{
		$filtro_idioma = "";
	}
	
	if($filtro_sede != 0){
		$filtrado_sede = " AND C.idsede = '".$filtro_sede."'";
		$filtrado_sede_inner = " INNER JOIN titular_sede C ON C.idtitular = A.idtitular ";
	}else{
		$filtrado_sede = "";
		$filtrado_sede_inner = "";
	}
	
	if($filtro_ordenar != ""){
		$filtrado_ordenar = $filtro_ordenar." ASC ";
	}else{
		$filtrado_ordenar = " A.idtitular ASC ";
	}
	
	//CANTIDAD TOTAL
	$query_cant = "SELECT A.*
	FROM titular A
	$filtrado_sede_inner
	WHERE A.estado <> 3 $filtro_idioma $filtrado_sede $filtro_tipo $filtro_especifico
	ORDER BY $filtrado_ordenar";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_tips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>

<script language="javascript">
	function ver_titulares(){
		f = document.form_titular;
		f.accion.value = "buscar";
		f.pag.value = 1;
		f.submit();
	}
	
	function cambiar_estado(estado, idtitular, ididioma){
	formulario = document.form_titular;
	
	formulario.estado.value = estado;
	formulario.idtitular.value = idtitular;
	formulario.ididioma.value = ididioma;
	formulario.submit();
	};
	
	function ir_pagina(pag){
		formulario = document.form_titular;
		formulario.pag.value = pag;
		formulario.accion.value = "buscar";
		formulario.submit();
	};
	
	function confirmar_eliminar(idtitular, ididioma){
		if (confirm('¿Está seguro que desea borrar el titular?')){
			var formulario = document.form_titular;
			
			formulario.ididioma.value = ididioma;
			formulario.idtitular.value = idtitular;
			formulario.accion.value = "eliminar";
			formulario.submit();
		}
	};
	
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Titular - Ver</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Buscar por Secci&oacute;n 
                          <input name="accion" type="hidden" id="accion" value="" />
                          <input name="estado" type="hidden" id="estado" />
                          <input name="idtitular" type="hidden" id="idtitular" />
                          <input name="ididioma" type="hidden" id="ididioma" />
                          <span class="detalle_medio">
                          <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td colspan="2" valign="top" class="detalle_medio" style="color:#666666">Seleccione los par&aacute;metros de b&uacute;squeda:</td>
                              </tr>
                              <tr>
                                <td width="90" align="right" valign="middle" class="detalle_medio">Tipo de titular: </td>
                                <td width="578" align="left" valign="middle"><label>
                                  <select name="tipo_titular" class="detalle_medio" id="tipo_titular" style="width:180px;">
                                    <option selected="selected">- Seleccionar Tipo Titular -</option>
                                    <option value="0" <? if($tipo_titular == "0"){ echo "selected"; } ?>>Todos</option>
                                    <option value="1" <? if($tipo_titular == "1"){ echo "selected"; } ?>>Carpeta</option>
                                    <option value="2" <? if($tipo_titular == "2"){ echo "selected"; } ?>>Seccion</option>
                                    <option value="3" <? if($tipo_titular == "3"){ echo "selected"; } ?>>Producto</option>
                                    <option value="4" <? if($tipo_titular == "4"){ echo "selected"; } ?>>Gen&eacute;rico</option>
                                </select>
                                </label></td>
                              </tr>
                              <tr>
                                <td valign="top">&nbsp;</td>
                                <td align="left" valign="middle"><input name="Submit2232" type="button" class="detalle_medio_bold" value="  Ver Titulares &raquo; " onclick="ver_titulares();"></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <? if($accion == "buscar" || $filtro_especifico != ""){ ?>
                    <br />
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="82%" align="right" class="detalle_medio">Filtrar por idioma: </td>
                        <td width="18%" align="right"><select name="filtro_idioma" style="width:160px;" class="detalle_medio" id="filtro_idioma" onchange="document.form_titular.accion.value = 'buscar'; document.form_titular.submit();">
                            <? 
						if($_POST['filtro_idioma'] == '0'){
							$sel_idioma = "selected";
						}else{
							$sel_idioma = "";
						}
				
					?>
                            <option value="0" <?= $sel_idioma ?>>- Seleccionar Idioma</option>
                            <? $query_idioma = "SELECT ididioma, titulo_idioma
						  FROM idioma 
						  WHERE estado = 1
						  ORDER BY titulo_idioma";
								  
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
                        <td align="right" class="detalle_medio">Filtrar por sucursales: </td>
                        <td align="right"><select name="filtro_sede" style="width:160px;" class="detalle_medio" id="filtro_sede" <?= $obj_value ?> onchange="document.form_titular.accion.value = 'buscar'; document.form_titular.submit();">
                            <option value="0">- Seleccione Sucursal</option>
                            <? $query_sede = "SELECT idsede, titulo
						  FROM sede 
						  WHERE estado = 1
						  ORDER BY titulo";
								  
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
                        <td align="right" class="detalle_medio">Ordenar por: </td>
                        <td align="right">
						<select name="ordenar" class="detalle_medio" style="width:160px;" id="ordenar" onchange="document.form_titular.accion.value = 'buscar'; document.form_titular.submit();">
                            <option value="" <? if($filtro_ordenar == ""){ echo "selected";} ?>>- Seleccione Orden </option>
							<option value="A.idtitular" <? if($filtro_ordenar == "A.idtitular"){ echo "selected";} ?>>Titular</option>
							<option value="A.fecha_alta" <? if($filtro_ordenar == "A.fecha_alta"){ echo "selected";} ?>>Fecha Alta</option>
                            <option value="A.fecha_activacion" <? if($filtro_ordenar == "A.fecha_activacion"){ echo "selected";} ?>>Fecha Activación </option>
                            <option value="A.fecha_baja" <? if($filtro_ordenar == "A.fecha_baja"){ echo "selected";} ?>>Fecha Baja</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td align="right" class="detalle_medio">Registros por hoja: </td>
                        <td align="right"><select name="cant" class="detalle_medio" style="width:160px;" onchange="document.form_titular.pag.value = 1; document.form_titular.accion.value = 'buscar'; document.form_titular.submit();">
                          <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?>>5</option>
                          <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                          <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?>>25</option>
                          <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                        </select></td>
                      </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#ffddbc">
                        <td width="17" height="30" align="center" class="detalle_medio_bold">ID</td>
                        <td width="441" height="30" align="left" bgcolor="ffddbc" class="detalle_medio_bold">Titular</td>
                        <td height="30" align="left" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
					  <?
					  $hay_lista = false;
					  $colores = array("#fff0e1","#FFE1C4");
					  $cont_colores = 0;
					  
					  $query_titular = "SELECT A.*
					  FROM titular A
					  $filtrado_sede_inner
					  WHERE A.estado <> 3 $filtro_idioma $filtrado_sede $filtro_tipo $filtro_especifico 
					  ORDER BY $filtrado_ordenar
					  LIMIT $puntero,$cantidad_registros";
					  $result_titular = mysql_query($query_titular);
					  while($rs_lista = mysql_fetch_assoc($result_titular)){
					  	$hay_lista =true;
					  ?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td align="center" valign="top" class="detalle_chico"><span ><a name="<?= $rs_lista['idtitular']; ?>" id="<?= $rs_lista['idtitular']; ?>"></a>
                              <?=$rs_lista['idtitular']?>.
                        </span></td>
                        <td align="left" valign="top" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><? $imagen =& new obj0001('0','../../../imagen/titular/',$rs_lista['foto'],'400','','','','','','','',''); ?></td>
                        <td width="200" align="left" valign="top" bgcolor="<?=$colores[$cont_colores]?>"><a href="titular_editar.php?idtitular=<?= $rs_lista['idtitular'] ?>" target="_parent" class="style10"></a><a href="javascript:confirm_eliminar('<?= $PHP_SELF ?>','<?= $QUERY_STRING ?>','<?= $rs_lista['idtitular'] ?>')" class="style10"></a>
                          <table width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                              <td width="50%" align="left" class="detalle_chico">Propiedades</td>
                              <td width="50%" align="right" class="detalle_chico">Opciones</td>
                            </tr>
                            <tr>
                              <td>
							  <? if($rs_lista['estado'] == 1){ 
							   echo '<a href="javascript:cambiar_estado(2,'.$rs_lista['idtitular'].','.$rs_lista['ididioma'].');"><img src="../../imagen/b_habilitado.png" width="15" height="16" border="0" /></a>';
							  }else{ 
							   echo '<a href="javascript:cambiar_estado(1,'.$rs_lista['idtitular'].','.$rs_lista['ididioma'].');"><img src="../../imagen/b_habilitado_off.png" width="15" height="16" border="0" /></a>';
							  } ?>							  
							  
							  &nbsp;<img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT DISTINCT C.titulo
								  FROM titular A
								  INNER JOIN titular_sede B ON A.idtitular = B.idtitular
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idtitular = '$rs_lista[idtitular]'
								  ORDER BY C.idsede";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" /></td>
                              <td height="25" align="right" valign="middle"><a href="titular_editar.php?idtitular=<?= $rs_lista['idtitular'] ?>&ididioma=<?= $rs_lista['ididioma'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a>&nbsp; <a href="javascript:confirmar_eliminar(<?= $rs_lista['idtitular'] ?>,<?= $rs_lista['ididioma'] ?>);"><img src="../../imagen/trash.png" border="0" /></a></td>
                            </tr>
                          </table>
                             <hr size="1" class="detalle_medio" />
                             <table width="100%" border="0" cellpadding="2" cellspacing="0" >

                               <tr>
                                 <td width="30%" style="font-size:11px;">Idioma:</td>
                                 <td width="70%" align="right" style="color:#006699"><b>
                                   <?
								   $query_idioma = "SELECT titulo_idioma
								   FROM idioma 
								   WHERE ididioma = '$rs_lista[ididioma]'
								   LIMIT 1";
								   $rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
								   echo $rs_idioma['titulo_idioma'];
								   ?>
                                 </b></td>
                               </tr>
                             </table>
                             <hr size="1" class="detalle_medio" />
                             <table width="100%" border="0" cellpadding="2" cellspacing="0">
                            <tr>
                              <td width="30%" align="left" valign="top" class="detalle_medio" style="font-size:11px;"><p>Carpeta:<br />
                              </p>                              </td>
                              <td width="70%" align="right" class="detalle_medio" style="font-size:11px; color:#669966"><?
								 $query_car = "SELECT nombre
								 FROM carpeta_idioma_dato
								 WHERE idcarpeta = '$rs_lista[idcarpeta]' AND ididioma = '1' ";
								 $rs_car = mysql_fetch_assoc(mysql_query($query_car));
								 
								 if($rs_car['nombre']){
								 	echo "<b>".$rs_car['nombre']."</b>";
								 }else{
								 	echo "<b>No esta definido.</b>";
								 }
								 
								 ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" class="detalle_medio" style="font-size:11px;">Secci&oacute;n:<br /></td>
                              <td align="right" class="detalle_medio" style="font-size:11px; color:#669966"><span >
                                <?
								 $query_sec = "SELECT titulo
								 FROM seccion_idioma_dato
								 WHERE idseccion = '$rs_lista[idseccion]' AND ididioma = '1' ";
								 $rs_sec = mysql_fetch_assoc(mysql_query($query_sec));
								 
								 if($rs_sec['titulo']){
								 	echo "<b>".$rs_sec['titulo']."</b>";
								 }else{
								 	echo "<b>No esta definido.</b>";
								 }
								 
								 ?>
                              </span></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" class="detalle_medio" style="font-size:11px;">Producto:</td>
                              <td align="right" class="detalle_medio" style="font-size:11px; color:#669966"><?
								 $query_prod = "SELECT titulo
								 FROM producto_idioma_dato
								 WHERE idproducto = '$rs_lista[idproducto]' AND ididioma = '1' ";
								 $rs_prod = mysql_fetch_assoc(mysql_query($query_prod));
								 
								 if($rs_prod['titulo']){
								 	echo "<b>".$rs_prod['titulo']."</b>";
								 }else{
								 	echo "<b>No esta definido.</b>";
								 }
								 
								 ?></td>
                            </tr>
                          </table>
                          <hr size="1" class="detalle_medio" />
                          <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                            <tr>
                              <td width="60%"><span style="font-size:11px">Fecha Activaci&oacute;n:</span></td>
                              <td width="40%" align="right" class="detalle_medio"><b>
                                <?= str_replace("-","/",$rs_lista['fecha_activacion']) ?>
                              </b></td>
                            </tr>
                            <tr>
                              <td><span style="font-size:11px">Fecha Baja:</span></td>
                              <td align="right" class="detalle_medio"><b>
                                <?= str_replace("-","/",$rs_lista['fecha_baja']) ?>
                              </b></td>
                            </tr>
                          </table></td>
                      </tr>
                      <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                      <tr align="center" valign="middle" >
                        <td colspan="3" bgcolor="#fff0e1" class="detalle_medio_bold" height="40">No se han encontrado titulares.</td>
                      </tr>
<? };
	?>
                      <tr align="center" valign="middle" >
                        <td colspan="3" class="detalle_medio_bold" height="30">
						<? 
						if($cantidad_total > $cantidad_registros && $accion == "buscar"){
							 echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']);
						} 
						?></td>
                      </tr>
                      
                      <?
	////////////////////////////////////////////////
	////Final del Modulo Admin de lista filtrado////
	////////////////////////////////////////////////
?>
                    </table>
					<? } ?>
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