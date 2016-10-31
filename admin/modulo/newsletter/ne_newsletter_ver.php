<? 
	include ("../../0_mysql.php");

	//CAMBIAR DE ESTADO
	if($_POST['estado'] && $_POST['idne_newsletter']){
		$estado = $_POST['estado'];
		$idne_newsletter = $_POST['idne_newsletter'];
		
		$query_estado = "UPDATE ne_newsletter SET estado = '$estado'
		WHERE idne_newsletter = '$idne_newsletter'
		LIMIT 1";
		mysql_query($query_estado);

	};
	
	//BORRAR	
	if($_POST['eliminar']){
		
		$eliminar = $_POST['eliminar'];
		$query_eliminar = "DELETE FROM ne_newsletter WHERE idne_newsletter = '$eliminar'";
		mysql_query($query_eliminar);
		
	};
	
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
	
	//PAGINACION: Cantidad Total.
	$query_cant = "SELECT idne_newsletter FROM ne_newsletter";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">
	function ir_pagina(pag){
		formulario = document.form;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function validar_form_lista(){
		formulario = document.form;
		formulario.pag.value = "1";
		formulario.submit();
	};
	
	function cambiar_estado(estado, id){
		formulario = document.form;
		
		formulario.estado.value = estado;
		formulario.idpais.value = id;
		formulario.submit();
	};
	
	function confirmar_eliminar(id){
		formulario = document.form;
			if (confirm('¿Esta seguro que desea eliminar este país?')){
				formulario.eliminar.value = id;
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
          <td>
		  <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Newsletter - Ver  </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td align="right" valign="bottom" class="detalle_medio"><input name="eliminar" type="hidden" id="eliminar" />
                              <input name="estado" type="hidden" id="estado" />
                              <input name="idne_newsletter" type="hidden" id="idne_newsletter" />
                              <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
Registros por hoja: </td>
                            <td width="10%" align="left" valign="bottom"><select name="cant" class="detalle_medio" style="width:60px;" onchange="validar_form_lista();">
                                <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?>>5</option>
                                <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                                <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?>>25</option>
                                <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                            </select></td>
                          </tr>
                      </table>
                        <br />
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#FFB76F">
                            <td width="4%" height="40" align="center" bgcolor="#FFD2A6" class="detalle_medio_bold">ID</td>
                            <td width="46%" height="40" bgcolor="#FFD2A6" class="detalle_medio_bold">Nombre</td>
                            <td width="21%" bgcolor="#FFD2A6" class="detalle_medio_bold">Fecha Creaci&oacute;n </td>
                            <td width="21%" bgcolor="#FFD2A6" class="detalle_medio_bold">&Uacute;ltima modificaci&oacute;n </td>
                            <td width="4%" height="40" bgcolor="#FFD2A6" class="detalle_medio_bold">&nbsp;</td>
                            <td width="4%" height="40" colspan="2" bgcolor="#FFD2A6" class="detalle_medio_bold">&nbsp;</td>
                          </tr>
                          <? 

									$colores = array("#fff0e1","#FFE1C4");
									$cont_colores = 0;
									$hay_lista = false;
									$query_lista = "SELECT *
									FROM ne_newsletter
									ORDER BY idne_newsletter ASC
									LIMIT $puntero,$cantidad_registros";
									$result_lista = mysql_query($query_lista);
									while ($rs_lista = mysql_fetch_assoc($result_lista)){
										
										 $hay_lista = true;
								  
						  ?>
                          <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                            <td width="4%" align="right" class="detalle_chico"><span ><?=$rs_lista['idne_newsletter']?>.
                            </span></td>
                            <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['nombre_identificacion']; ?></td>
                            <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['fecha_creacion']; ?></td>
                            <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['fecha_ultima_modificacion']; ?></td>
                            <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><a href="ne_newsletter_editar.php?idne_newsletter=<?= $rs_lista['idne_newsletter']; ?>"><img src="../../imagen/iconos/pencil.png" width="16" height="16" border="0" /></a></td>
                            <td align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:confirmar_eliminar('<?= $rs_lista['idne_newsletter'] ?>')" class="style10"><img src="../../imagen/iconos/cross.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                          </tr>
                          <?
			if($cont_colores == 0){
				$cont_colores = 1;
			}else{
				$cont_colores = 0;
			};
					
	} ?>
						<? if($hay_lista == false){ ?>
                          <tr align="center" valign="middle" >
                            <td colspan="7" bgcolor="#fff0e1" height="50" class="detalle_medio_bold">No se han encontrado newsletter.</td>
                          </tr>
						 <? } ?>
                          <tr align="center" valign="middle" >
                            <td colspan="7" height="25" class="detalle_medio_bold"><? 
						if($cantidad_total > $cantidad_registros){
							 echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']);
						} 
						?></td>
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