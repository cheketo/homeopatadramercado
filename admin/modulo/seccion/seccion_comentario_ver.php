<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 	
	$idseccion = $_GET['idseccion'];
	$accion = $_POST['accion'];
	$estado = $_POST['estado'];
	$idseccion_comentario = $_POST['idseccion_comentario'];
	
	//CAMBIA ESTADO
	if($accion == "cambiar_estado"){
		$query_estado = "UPDATE seccion_comentario SET estado = '$estado'
		WHERE idseccion_comentario = '$idseccion_comentario'
		LIMIT 1";
		$resultado = mysql_query($query_estado);
		if($resultado == false){
			echo "FAILED: ".$query_estado;
		}
	}
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_tips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

	function ir_pagina(pag){
		formulario = document.form_lista;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function validar_form_lista(){
		formulario = document.form_lista;
		formulario.pag.value = "1";
		formulario.submit();
	};
	
	function cambiar_estado(estado, id){
		formulario = document.form_lista;
		
		formulario.estado.value = estado;
		formulario.idseccion_comentario.value = id;
		formulario.accion.value = "cambiar_estado";
		formulario.submit();
		
	};
	function confirm_cambiar_estado(estado, id){
		formulario = document.form_lista;
			if (confirm('¿Esta seguro que desea eliminar esta sección?')){
				formulario.estado.value = estado;
				formulario.idseccion_comentario.value = id;
				formulario.accion.value = "cambiar_estado";
				formulario.submit();
			}
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
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Secci&oacute;n - Comentarios</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
            </table></td>
        </tr>
        <tr>
          <td valign="top"><form id="form_lista" name="form_lista" method="post" action="" enctype="multipart/form-data">
            <? 
		
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
			
			//CHEQUEO PARA PAGINAR
			$cantidad_registros = $_POST['cant'];
			if(!$cantidad_registros){
				$cantidad_registros = 5;
			}
			
			$pag = $_POST['pag'];
			if(!$pag){
				$pag = 1;
			}
			$puntero = ($pag-1) * $cantidad_registros;

			if($_POST['estado'] == 4){
				if( ($puntero-$cantidad_registros) == 0 ){
					$puntero = $puntero - 1;
				}
			}
		
			//PAGINACION: Cantidad Total.
			$query_cant = "SELECT A.idseccion_comentario
			FROM seccion_comentario A
			LEFT JOIN user_web B ON B.iduser_web = A.iduser_web
			WHERE A.estado != 4 AND A.idseccion = '$idseccion'";
			$cantidad_total = mysql_num_rows(mysql_query($query_cant));
		
		
		$query_comentario = "SELECT A.*, B.mail, B.nombre
		FROM seccion_comentario A
		LEFT JOIN user_web B ON B.iduser_web = A.iduser_web
		WHERE A.estado != 4 AND A.idseccion = '$idseccion'
		ORDER BY A.ididioma, A.fecha_alta DESC
		LIMIT $puntero,$cantidad_registros";
		$result_comentario = mysql_query($query_comentario);
		$num_rows_comentario = mysql_num_rows($result_comentario);
		
		if($num_rows_comentario > 0){ 		  
	?>
            <span class="titulo_grande_bold"><span class="detalle_medio_bold">
            <input name="accion" type="hidden" id="accion" />
            </span><span class="detalle_medio_bold">
            <input name="idseccion_comentario" type="hidden" id="idseccion_comentario" />
            <input name="estado" type="hidden" id="estado" />
            <span class="titulo_medio_bold">
            <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
            </span></span></span>
            <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td width="88%" colspan="2" align="center" valign="top" ><table width="640" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td align="right" valign="middle" class="detalle_medio"><label></label>Registros por hoja </td>
                      <td width="8%" align="right" valign="middle" class="detalle_medio"><select name="cant" class="detalle_medio" onchange="validar_form_lista();">
                          <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?> >5</option>
                          <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?> >10</option>
                          <option value="20"<? if($cantidad_registros == 20){ echo "selected"; } ?> >20</option>
                      </select></td>
                    </tr>
                    <tr height="5">
                      <td align="right" valign="middle" class="detalle_medio"></td>
                      <td valign="middle" class="detalle_medio"></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="2" align="center" valign="top" >
				  <? while($rs_comentario = mysql_fetch_assoc($result_comentario)){ ?>
                  <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td height="12" colspan="2" bgcolor="#f7f7f7"><table width="100%" border="0" cellspacing="15" cellpadding="0">
                          <tr>
                            <td width="477" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666666;">Comentado por:
                              <span class="copete_b">
                              <? if($rs_comentario['nombre']){ echo $rs_comentario['nombre']; }else{ echo "Anonimo.";} ?>
                              </span>
							  <? if($rs_comentario['mail']){ echo ' | <a href="mailto:'.$rs_comentario['mail'].'" class="copete_a">'.$rs_comentario['mail'].'</a>'; } ?>
							  <br />
                              Fecha de publicaci&oacute;n:
                            <? $fecha = split("-", $rs_comentario['fecha_alta']); echo $fecha[2]."/".$fecha[1]."/".$fecha[0]; ?></td>
                            <td width="118" align="right" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666666;"><? 
							if ($rs_comentario['estado'] == '1') { 
								//estado 1 activo, 2 inactivo, 3 borrado
                            	echo "<img src=\"../../imagen/b_habilitado.png\" alt=\"Habilitado\" width=\"15\" height=\"16\" border=\"0\" />";
                            } else { 
                            	echo "<a href=\"javascript:cambiar_estado(1,".$rs_comentario['idseccion_comentario'].");\"><img src=\"../../imagen/b_habilitado_off.png\" alt=\"Habilitado\" width=\"15\" height=\"16\" border=\"0\" /></a>";
                            } ?>
                              <? 
							if ($rs_comentario['estado'] == '3') { 
								//estado 1 activo, 2 inactivo, 3 borrado
                            	echo "<img src=\"../../imagen/b_confirmar.png\" alt=\"Sin confirmar\" width=\"16\" height=\"15\" border=\"0\" />";
                            } else { 
                            	echo "<a href=\"javascript:cambiar_estado(3,".$rs_comentario['idseccion_comentario'].");\"><img src=\"../../imagen/b_confirmar_off.png\" alt=\"Sin confirmar\" width=\"16\" height=\"15\" border=\"0\" /></a>";
                            } ?>
                              <? 
							if ($rs_comentario['estado'] == '2') { 
								//estado 1 activo, 2 inactivo, 3 borrado
                            	echo "<img src=\"../../imagen/b_deshabilitado.png\" alt=\"Deshabilitado\" width=\"16\" height=\"15\" border=\"0\" />";
                            } else { 
                            	echo "<a href=\"javascript:cambiar_estado(2,".$rs_comentario['idseccion_comentario'].");\"><img src=\"../../imagen/b_deshabilitado_off.png\" alt=\"Deshabilitado\" width=\"16\" height=\"15\" border=\"0\" /></a>";
                            } ?>                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../../comentario.php?accion=editar&amp;idseccion_comentario=<?= $rs_comentario['idseccion_comentario'] ?>" target="_blank"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a> &nbsp;<a href="javascript:confirm_cambiar_estado(4,<?= $rs_comentario['idseccion_comentario'] ?>);"><img src="../../imagen/trash.png" width="15" height="16" border="0" /></a>&nbsp;&nbsp;</td>
                          </tr>
                          <tr align="left">
                            <td colspan="2" bgcolor="#F7F7F7" style="color:#000000;" class="detalle_medio"><?= html_entity_decode($rs_comentario['comentario'], ENT_QUOTES); ?></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td width="60" height="13" align="right" valign="top"><img src="../../../imagen/varios/tip_coment.gif" width="23" height="23" /></td>
                      <td width="582">&nbsp;</td>
                    </tr>
                  </table>
				  <br />
				  <? }// WHILE SUBCARPETAS  ?>			    </td>
              </tr>
              <tr>
                <td colspan="2" align="center" valign="top" class="detalle_medio_bold" ><span ><? echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']."?cant=".$cantidad_registros."&pag=" ); ?></span></td>
              </tr>
            </table>
            <? 
             } //SI HAY RESULTADOS ?>
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