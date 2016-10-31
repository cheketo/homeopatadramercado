<? include ("../../0_mysql.php"); 

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
		$cantidad_registros = 25;
	}
	
	$pag = $_POST['pag'];
	if(!$pag){
		$pag = 1;
	}
	$puntero = ($pag-1) * $cantidad_registros;

	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<?

	//Tomo variables por POST
	$accion = $_POST['accion']; 
	$eliminar = $_POST['eliminar'];
	
	$in_estado_row = $_POST['in_estado_row'];
	$in_idinmueble_row = $_POST['in_idinmueble_row']; 
	$cont_pro_pago = $_POST['cont_pro_pago'];
	
	$accion_buscar = $_POST['accion_buscar']; //indica si hay qe realizar una busqueda
	
	//VARIABLES QUE VIENEN DE LA BUSQUEDA:
	$in_idinmueble = $_POST['in_idinmueble'];
	$in_tipoinmueble = $_POST['in_tipoinmueble'];
	$in_operacion = $_POST['in_operacion'];
	$in_provincia = $_POST['in_provincia'];


	//APLICAR CAMBIOS:
	if($accion == "aplicar_cambios"){
	 
		for ($i=1; $i<$cont_pro_pago ; $i++){
			
			$queryup = "UPDATE in_inmueble
			SET in_estado = '$in_estado_row[$i]'
			WHERE in_idinmueble = '$in_idinmueble_row[$i]'";
			mysql_query($queryup);
			
		}
	};

	//BORRA INMUEBLE:
	if( $eliminar != '' && $eliminar != 0 ){
		
		//Eliminar foto principal
		$query = "SELECT in_foto FROM in_inmueble WHERE in_idinmueble = '$eliminar'";
		$rs_foto = mysql_fetch_assoc(mysql_query($query));
		
		if ($rs_foto['in_foto']){
			if (file_exists("../../../imagen/inmueble/thumbnails/".$rs_foto['in_foto'])){
				unlink ("../../../imagen/inmueble/thumbnails/".$rs_foto['in_foto']);
			}
		}
		
		//Eliminar fotografias
		$query = "SELECT in_foto FROM in_foto WHERE in_idinmueble = '$eliminar'";
		$result = mysql_query($query);
		
		while($rs_foto = mysql_fetch_assoc($result)){
		
			if ($rs_foto['in_foto']){
				if (file_exists("../../../imagen/inmueble/foto/chica/".$rs_foto['in_foto'])){
					unlink ("../../../imagen/inmueble/foto/chica/".$rs_foto['in_foto']);
				}
				if (file_exists("../../../imagen/inmueble/foto/grande/".$rs_foto['in_foto'])){
					unlink ("../../../imagen/inmueble/foto/grande/".$rs_foto['in_foto']);
				}
			}	
			
		}
		$query = "DELETE FROM in_foto WHERE in_idinmueble = '$eliminar'";
		mysql_query($query);
		
		//Eliminar planos
		$query = "SELECT in_plano FROM in_plano WHERE in_idinmueble = '$eliminar'";
		$result = mysql_query($query);
		
		while($rs_foto = mysql_fetch_assoc($result)){
		
			if ($rs_foto['in_plano']){
				if (file_exists("../../../imagen/inmueble/plano/chica/".$rs_foto['in_plano'])){
					unlink ("../../../imagen/inmueble/plano/chica/".$rs_foto['in_plano']);
				}
				if (file_exists("../../../imagen/inmueble/plano/grande/".$rs_foto['in_plano'])){
					unlink ("../../../imagen/inmueble/plano/grande/".$rs_foto['in_plano']);
				}
			}	
			
		}
		$query = "DELETE FROM in_plano WHERE in_idinmueble = '$eliminar'";
		mysql_query($query);
		
		//Eliminar mapas
		$query = "SELECT in_mapa FROM in_mapa WHERE in_idinmueble = '$eliminar'";
		$result = mysql_query($query);
		
		while($rs_foto = mysql_fetch_assoc($result)){
		
			if ($rs_foto['in_mapa']){
				if (file_exists("../../../imagen/inmueble/mapa/chica/".$rs_foto['in_mapa'])){
					unlink ("../../../imagen/inmueble/mapa/chica/".$rs_foto['in_mapa']);
				}
				if (file_exists("../../../imagen/inmueble/mapa/grande/".$rs_foto['in_mapa'])){
					unlink ("../../../imagen/inmueble/mapa/grande/".$rs_foto['in_mapa']);
				}
			}	
			
		}
		$query = "DELETE FROM in_mapa WHERE in_idinmueble = '$eliminar'";
		mysql_query($query);
		
		//Eliminar detalle de ambientes
		$query = "DELETE FROM in_detalle WHERE in_idinmueble = '$eliminar'";
		mysql_query($query);
		
		//Eliminar inmueble
		$query_eliminar = "DELETE FROM in_inmueble WHERE in_idinmueble = '$eliminar'";
		mysql_query($query_eliminar); 	
		
	} 
	
?>


<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="Javascript">
<!--

function validar_busqueda(){
	formulario = document.form_ver;
	c = true;
	
	if(esNumerico(formulario.in_idinmueble.value) == false){
		c = false;
		alert("El campo ID Inmueble debe ser numerico.");
	}
	
	if(c == true){
	formulario.accion_buscar.value = "buscar";
	formulario.submit();
	}
};


function aplicar_cambios(id){
	formulario = document.form_ver;
	formulario.accion.value = "aplicar_cambios";
	formulario.submit();
};

function validar_form_lista(){
		formulario = document.form_ver;
		formulario.pag.value = "1";
		formulario.submit();
};

function ir_pagina(pag){
		formulario = document.form_ver;
		formulario.pag.value = pag;
		formulario.submit();
};

function lista_orden(filtro,orden){
	formulario = document.form_ver;
	formulario.lista_fil.value = filtro;
	formulario.lista_orden.value = orden;
	formulario.action = "";
	formulario.submit();
};
	
function confirm_eliminar(id){
	if (confirm('¿Esta seguro que desea eliminar el inmueble?')){
			formulario = document.form_ver;
			formulario.eliminar.value = id;
			formulario.submit();
	}
}

//-->
</script>



<style type="text/css">
<!--
.style2 {font-family: Arial, Helvetica, sans-serif}
.style3 {color: #666666}
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
	  <form name="form_ver" id="form_ver" method="post" action="">
              <table width="100%" border="0" cellspacing="0" cellpadding="12" class="fondo_tabla_principal">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="40" valign="bottom" class="titulo_grande_bold"><span class="titulo_grade_bold">Inmueble - Buscar </span></td>
                          </tr>
                          <tr>
                            <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#CCCCCC;"></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#FFFFFF">
                      <tr>
                            <td width="100%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold"><p>Buscar inmueble seg&uacute;n:
                                <input name="accion_buscar" type="hidden" id="accion_buscar" value="<?= $accion_buscar ?>">
                                    <input name="accion" type="hidden" id="accion">
                                    <input name="id_pagos" type="hidden" id="id_pagos" value="-1">
                                    <input name="eliminar" type="hidden" id="eliminar">
                            </p></td>
                        </tr>
                          <tr>
                            <td bgcolor="#FFF0E1" class="detalle_medio_bold"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="19%" align="right" class="detalle_medio">ID Inmueble: </td>
                                <td><label>
                                  <input name="in_idinmueble" type="text" class="detalle_medio" id="in_idinmueble" value="<?= $in_idinmueble ?>" size="4">
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" class="detalle_medio">Operaci&oacute;n:</td>
                                <td><span class="detalle_medio">
                                  <select name="in_operacion" class="detalle_medio" id="in_operacion" style="width:200px;">
									<option value="">- Ver todos</option>
                                    <option value="1" <? if($in_operacion == "1"){ echo "selected=\"selected\"";} ?>>Alquiler</option>
                                    <option value="2" <? if($in_operacion == "2"){ echo "selected=\"selected\"";} ?>>Venta</option>
                                </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" class="detalle_medio"><span class="detalle_foto">Tipo de inmueble: </span></td>
                                <td><span class="style2">
                                  <select name="in_tipoinmueble" class="detalle_medio" id="in_tipoinmueble" style="width:200px;" >
                                    <option value="" >- Seleccionar Tipo de Inmueble</option>
                                    <?
		 
	  $query = "SELECT *
	  FROM in_tipo_inmueble
	  WHERE in_tipo_inmueble_estado <> 2
	  ORDER BY in_tipo_inmueble_titulo";
	  $result = mysql_query($query);
	  while ($rs_tipoinmueble = mysql_fetch_assoc($result))
	  {
	  		if($in_tipoinmueble == $rs_tipoinmueble['in_idtipo_inmueble']){ $sel = "selected=\"selected\"";} else { $sel = ""; }
?>
                                    <option value="<?= $rs_tipoinmueble['in_idtipo_inmueble'] ?>" <?= $sel ?>>
                                    <?= $rs_tipoinmueble['in_tipo_inmueble_titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" class="detalle_medio"><span class="detalle_foto">Barrio:</span></td>
                                <td><span class="style2">
                                  <select name="in_provincia" class="detalle_medio" id="in_provincia" style="width:200px;">
                                    <option value="" >- Seleccionar Barrio</option>
                                    <?
		 
	  $query_provincia = "SELECT *
	  FROM pais_provincia
	  WHERE estado <> 3 AND idpais = 54
	  ORDER BY titulo";
	  $result_provincia = mysql_query($query_provincia);
	  while ($rs_provincia = mysql_fetch_assoc($result_provincia))	  
	  {
			if($in_provincia == $rs_provincia['idpais_provincia']){ $sel = "selected=\"selected\"";} else { $sel = ""; }
?>
                                    <option value="<?= $rs_provincia['idpais_provincia'] ?>" <?= $sel ?>>
                                    <?= $rs_provincia['titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td class="detalle_medio">&nbsp;</td>
                                <td class="detalle_medio"><input name="btn_buscar" type="button" class="buttons detalle_medio_bold" id="btn_buscar"  onClick="javascript:validar_busqueda();" value="     Buscar &raquo;   "></td>
                              </tr>
                            </table></td>
                        </tr>
                        </table>
                        <br />
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="50%" align="left" valign="middle" class="detalle_medio"><label>
                            <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                            </label></td>
                            <td width="41%" align="right" valign="middle" class="detalle_medio">Registros por hoja </td>
                            <td width="9%" valign="middle" class="detalle_medio"><select name="cant" class="detalle_medio" onchange="validar_form_lista();">
                                <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?> >5</option>
                                <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?> >25</option>
                                <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                                <option value="100"<? if($cantidad_registros == 100){ echo "selected"; } ?> >100</option>
                            </select></td>
                          </tr>
                          <tr height="5">
                            <td colspan="2" align="right" valign="middle" class="detalle_medio"></td>
                            <td valign="middle" class="detalle_medio"></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
<tr bgcolor="#999999">
                            <td height="40" colspan="7" align="center" valign="middle" bgcolor="#FFDDBC" class="titulo_medio_bold"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left" class="detalle_medio_bold_black">Listado de inmuebles </td>
                                  <td align="right"><span class="detalle_medio_bold">
                                    <span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                                    <input name="lista_fil" type="hidden" id="lista_fil" value="1">
                                    </span><span class="detalle_chico" style="color:#FF0000">
                                    <input name="lista_orden" type="hidden" id="lista_orden" value="1">
                                    </span></span>
                                    <input name="Button" type="button" class="buttons detalle_medio_bold"  onClick="aplicar_cambios();" value=" Aplicar Cambios ">
                                  </span></td>
                                </tr>
                            </table></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td height="40" align="center" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold">ID</td>
                            <td height="40" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold"><p>
                              Titulo
                            </p></td>
                            <td height="40" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold">Valor</td>
                            <td width="75" height="40" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold">Operaci&oacute;n</td>
                            <td width="126" height="40" align="center" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold">Estado</td>
                            <td height="40" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold">&nbsp;</td>
                            <td width="23" height="40" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
                          <?

	//REALIZA BUSQUEDA:
	if($accion_buscar == "buscar"){
		
		//Filtrado
		$filtro = "";
		
		//VARIABLE DE FILTRACION
		if($in_idinmueble != ""){
			$filtro .= " AND in_idinmueble = '$in_idinmueble'";
		}
		if($in_tipoinmueble != ""){
			$filtro .= " AND in_tipoinmueble = '$in_tipoinmueble'";
		}
		if($in_operacion != ""){
			$filtro .= " AND in_operacion = '$in_operacion'";
		}
		if($in_provincia != ""){
			$filtro .= " AND in_provincia = '$in_provincia'";
		}
		
		//Ordenamiento
		if(($lista_fil<>1)&&($lista_fil)){
			$lista_order_by_fil = $lista_fil;
			$lista_order_by_fil .= " ".$lista_orden;
		}else{
			$lista_order_by_fil = "in_idinmueble";
		}
		
		//PAGINACION: Cantidad Total.
		$query_cant = "SELECT in_idinmueble
			FROM in_inmueble
			WHERE 1 = 1 $filtro
			";
		$cantidad_total = mysql_num_rows(mysql_query($query_cant));
		
		//Consulta
		$cont_pro_pago = 1;
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$query = "SELECT in_idinmueble, in_titulo, in_operacion, in_valor, in_moneda, in_estado
		FROM in_inmueble
		WHERE 1 = 1 $filtro
		ORDER BY $lista_order_by_fil
		LIMIT $puntero,$cantidad_registros";
		$result = mysql_query($query);
		
		while ($rs_inmueble = mysql_fetch_assoc($result)){
			$hay_lista = true;
	
?>
                          <tr valign="top" bgcolor="<?= $colores[$cont_colores] ?>" id="list1">
                            <td width="17" height="12" align="right"  class="detalle_chico"><?= $rs_inmueble['in_idinmueble'] ?>.
                                <span class="detalle_medio_bold">
                                <input name="in_idinmueble_row[<?=$cont_pro_pago?>]" type="hidden" id="in_idinmueble_row[<?=$cont_pro_pago?>]" value="<?= $rs_inmueble['in_idinmueble']?>">
                            </span></td>
                            <td width="227" align="left"  class="detalle_medio"><?= $rs_inmueble['in_titulo'] ?></td>
                            <td width="133" align="left"  class="detalle_medio">
							
							<? if($rs_inmueble['in_valor'] != ''){
									echo $rs_inmueble['in_valor'] . " " . $rs_inmueble['in_moneda'];
							   } else {
							   		echo "-";
							   }							?>
							<span class="detalle_inmueble_azul_bold">
							<?
							  if($rs_inmueble['in_operacion'] == "Alquiler" && $rs_inmueble['in_alquiler_periodo'] != ""){
							  		echo "por ".$rs_inmueble['in_alquiler_periodo'];
							  }
							  ?>
							</span></td>
                            <td align="left"  class="detalle_medio"><label>
                            <?
							
							if($rs_inmueble['in_operacion'] == 2){
								echo "En Venta";
							}else{
								echo "En Alquiler";
							} ?>
                            </label></td>
                            <td align="center"  class="detalle_medio">
							<select name="in_estado_row[<?=$cont_pro_pago?>]" class="detalle_medio" id="in_estado_row[<?=$cont_pro_pago?>]">
                              <option value="0" <? if($rs_inmueble['in_estado'] == "0"){ echo "selected";} ?>>Disponible</option>
                              <option value="1" <? if($rs_inmueble['in_estado'] == "1"){ echo "selected";} ?>>Reservado</option>
                              <option value="2" <? if($rs_inmueble['in_estado'] == "2"){ echo "selected";} ?>>Alquilado</option>
                              <option value="3" <? if($rs_inmueble['in_estado'] == "3"){ echo "selected";} ?>>Vendido</option>
                              <option value="4" <? if($rs_inmueble['in_estado'] == "4"){ echo "selected";} ?>>Suspendido</option>
                            </select></td>
                            <td width="17" align="right"  class="detalle_medio_bold"><a href="in_inmueble_editar.php?in_idinmueble=<?= $rs_inmueble['in_idinmueble'] ?>" class="style10"><img src="../../imagen/iconos/pencil.png" width="16" height="16" border="0" /></a></td>
                            <td align="center"  class="detalle_medio_bold"><a href="javascript:confirm_eliminar('<?= $rs_inmueble['in_idinmueble'] ?>')" class="style10"><img src="../../imagen/iconos/cross.png" width="16" height="16" border="0" /></a></td>
                        </tr>
                          <? 
						$cont_pro_pago++; 
						
						if($cont_colores == 0){
							$cont_colores = 1;
						}else{
							$cont_colores = 0;
						};
					} 
				 }  ?>
                          <tr valign="top" bgcolor="#CCCCCC">
                            <?  if($hay_lista == false){ ?>
                            <td height="50" colspan="7" align="center" valign="middle" bgcolor="#FFF0E1" class="detalle_medio_bold">No se encontraron resultados. </td>
                            <? } ?>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="40" align="center" valign="middle"><span class="titulo_medio_bold"><? echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']."?cant=".$cantidad_registros."&pag=" ); ?></span></td>
                          </tr>
                        </table>
                        <span class="detalle_medio_bold">
                          <input name="cont_pro_pago" type="hidden" id="cont_pro_pago" value="<?=$cont_pro_pago?>">
                          </span></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
      </form>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>