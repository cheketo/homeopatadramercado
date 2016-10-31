<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	

	//VARIABLES
	$accion = $_POST['accion'];
	$idpais = $_POST['pais'];
	$provincia = $_POST['provincia'];
	$filtro_pais = $_POST['filtro_pais'];
	
	//INGRESAR		
	if( $accion == "ingresar" ){		
			
			$query_ingresar = "INSERT INTO pais_provincia (
			  idpais	
			, titulo
			, posicion
			, estado
			) VALUES (
			  '$idpais'
			, '$provincia'
			, '0'
			, '1'
			)";
			mysql_query($query_ingresar);
		
	}; 
	
	//CAMBIAR ESTADO
	$estado = $_POST['estado'];
	$idpais_provincia = $_POST['idpais_provincia'];

	if($accion == "cambiar_estado"){
		$query = "UPDATE pais_provincia SET estado = '$estado'
		WHERE idpais_provincia = '$idpais_provincia' ";
		mysql_query($query);
	} 

	//BORRAR
	$eliminar = $_POST['eliminar'];
		
	if($eliminar != '' && $eliminar != 0 ){
	
		$query_eliminar = "DELETE FROM pais_provincia WHERE idpais_provincia = '$eliminar'";
		mysql_query($query_eliminar);
	
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
	
	//FILTRO
	if($filtro_pais){
		$filtrado_pais = " AND idpais = ".$filtro_pais." ";
	}
	
	//PAGINACION: Cantidad Total.
	$query_cant = "SELECT *
		FROM pais_provincia 
		WHERE estado <> 3 $filtrado_pais";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">
	
	function ir_pagina(pag){
		formulario = document.form;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function validar_provincia(){
	formulario = document.form;
	
		if(formulario.pais.value == ''){
			alert("Debe seleccionar el país al que pertenece la provincia.");
		}else if(formulario.provincia.value == ''){
			alert("Debe ingresar el nombre de la provincia.");
		}else{
			formulario.accion.value = "ingresar";
			formulario.submit();
		}
	
	};
	
	function cambiar_estado(estado, idpais_provincia){
		formulario = document.form;
	
		formulario.estado.value = estado;
		formulario.idpais_provincia.value = idpais_provincia;
		formulario.accion.value = "cambiar_estado";
		formulario.submit();
	};


	function confirmar_eliminar(id){
		if (confirm('¿Esta seguro que desea eliminar la provincia?')){
			formulario = document.form;
			formulario.eliminar.value = id;
			formulario.submit();
		}
	};
	
	function validar_form_lista(){
		formulario = document.form;
		formulario.pag.value = "1";
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Provincias</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
					<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr>
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingresar nueva provincia:
                            <input name="accion" type="hidden" id="accion" value="" />
                            <input name="idpais_provincia" type="hidden" id="idpais_provincia" />
                            <input name="estado" type="hidden" id="estado" />
                            <input name="eliminar" type="hidden" id="eliminar" /></td>
                          </tr>
                          <tr>
                            <td bgcolor="#FFF0E1">
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Pa&iacute;s: </td>
                                    <td align="left" valign="top"><span class="style2">
                                      <select name="pais" class="detalle_medio" id="pais" style="width:205px;">
                                        <option value="" >- Seleccionar País -</option>
                                        <?
		 
	  $query_idproducto = "SELECT *
	  FROM pais
	  WHERE estado <> 3 
	  ORDER BY titulo";
	  $result_idproducto = mysql_query($query_idproducto);
	  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
	  {
	  	if ($idseccion == $rs_idproducto['idpais'])
		{
			$sel_idproducto = "selected";
		}else{
			$sel_idproducto = "";
		}
?>
                                        <option value="<?= $rs_idproducto['idpais'] ?>"<? echo $sel_idproducto ?>>
                                        <?= $rs_idproducto['titulo'] ?>
                                        </option>
                                        <?  } ?>
                                    </select>
                                    </span></td>
                                  </tr>
                                  <tr>
                                    <td width="15%" align="right" valign="middle" class="detalle_medio">Provincia:</td>
                                    <td width="85%" align="left" valign="top"><label>
                                      <input name="provincia" type="text" class="detalle_medio" id="provincia" style="width:200px;" />
                                    </label></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                    <td align="left" valign="top"><input name="Button" type="button" class="buttons detalle_medio_bold" onclick="validar_provincia();" value="  &raquo; Ingresar  " /></td>
                                  </tr>
                            </table></td>
                          </tr>
                      </table>
                    
                        <br />
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="74%" align="right" class="detalle_medio">                            Filtrar por Pa&iacute;s: </td>
                            <td width="26%" align="right"><select name="filtro_pais" class="detalle_medio" id="filtro_pais" style="width:200px;" onchange="validar_form_lista();">
                              <option value="">- Seleccionar País</option>
                              <?
		 
	  $query_idproducto = "SELECT *
	  FROM pais
	  WHERE estado <> 3 
	  ORDER BY titulo";
	  $result_idproducto = mysql_query($query_idproducto);
	  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
	  {
	  	if ($rs_idproducto['idpais'] == $_POST['filtro_pais'])
		{
			$sel = "selected";
		}else{
			$sel = "";
		}
?>
                              <option value="<?= $rs_idproducto['idpais'] ?>" <?= $sel ?>>
                              <?= $rs_idproducto['titulo'] ?>
                              </option>
                              <?  } ?>
                            </select></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="90%" align="right" class="detalle_medio"><input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                              Registros por hoja: </td>
                            <td width="10%" align="left"><select name="cant" class="detalle_medio" style="width:60px;" onchange="validar_form_lista();">
                                <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?>>5</option>
                                <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                                <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?>>25</option>
                                <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                            </select></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#FFB76F">
                          <td width="4%" height="40" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">ID</td>
                          <td width="13%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Pa&iacute;s</td>
                          <td width="68%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Provincia</td>
                          <td height="40" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">&nbsp;</td>
                          <td height="40" colspan="3" bgcolor="#FFDDBC" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
                        <? 
 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM pais_provincia 
		WHERE estado <> 3 $filtrado_pais 
		LIMIT $puntero,$cantidad_registros";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista))
			{ $hay_lista = true;			  
			  $pais = $rs_lista['idpais'];
	  
?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="4%" align="center" class="detalle_chico"><span ><a name="<?= $rs_lista['idpais_provincia']; ?>" id="<?= $rs_lista['idpais_provincia']; ?>"></a>
                                <?=$rs_lista['idpais_provincia']?>
                          </span></td>
                          <? $query_pais = "SELECT *
		FROM pais
		WHERE estado <> 3 AND idpais = $pais";
		$result_pais = mysql_query($query_pais);
		while ($rs_pais = mysql_fetch_assoc($result_pais))
		{
			?>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_pais['titulo']; }?></td>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['titulo']; ?></td>
                          <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                              <a href="javascript:cambiar_estado(2,<?= $rs_lista['idpais_provincia'] ?>);"><img src="../../imagen/iconos/accept_blue.png" alt="Habilitado" width="16" height="16" border="0" /></a>
                              <? } else { ?>
                              <a href="javascript:cambiar_estado(1,<?= $rs_lista['idpais_provincia'] ?>);"><img src="../../imagen/iconos/accept_blue_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                              <? } ?></td>
                          <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="pa_provincia_editar.php?id=<?= $rs_lista['idpais_provincia'] ?>" target="_parent" class="style10"><img src="../../imagen/iconos/pencil.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                          <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:confirmar_eliminar('<?= $rs_lista['idpais_provincia'] ?>')" class="style10"><img src="../../imagen/iconos/cross.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                        </tr>
                        <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                        <tr align="center" valign="middle" >
                          <td colspan="7" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado provincias.</td>
                        </tr>
						<? } ?>
                        <tr align="center" valign="middle" >
                          <td colspan="7" height="25" class="detalle_medio_bold"><? 
						if($cantidad_total > $cantidad_registros){
							 echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']);
						} 
						?></td>
                        </tr>
                        
                    </table>
					</form>
                  </td>
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