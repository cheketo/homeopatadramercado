<? include ("../../0_mysql.php");
include ("../../../0_include/0_carrito_funciones.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 	
	$accion = $_POST['accion'];
	
	//SI EL USUARIO ADMIN ES NIVEL 1
	if($_SESSION['idcusuario_perfil_log']=='1'){
		$usuario_perfil = 1;
	}else{
		$usuario_perfil = 2;
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

	//VARIABLES
	$accion = $_POST['accion'];
	$dia = $_POST['dia'];
	$mes = $_POST['mes'];
	$anio = $_POST['anio'];
	
	if($dia != "00" && $mes != "00" && $anio != "0000"){
		$fecha_pedido = $anio."-".$mes."-".$dia;
		$filtrado_fecha_pedido = " AND A.fecha_pedido = '$fecha_pedido' ";
	}else{
		$filtrado_fecha_pedido = "";
	}
	
	
	//FILTRADO
	if($_POST['ordenar'] != 0){
		$filtrado_ordenar = " ".$_POST['ordenar']." ASC ";
	}else{
		$filtrado_ordenar = " A.fecha_pedido DESC, A.idca_pedido DESC ";
	}

	//BUSQUEDA
	$estado_pedido = $_POST['estado_pedido'];
	if($estado_pedido){
		$filtrado_pedido = " AND A.estado = '$estado_pedido' ";
	}else{
		$filtrado_pedido = "";
	}


	$estado_pago = $_POST['estado_pago'];
	if($estado_pago){
		$filtrado_pago = " AND B.estado = '$estado_pago' ";
	}else{
		$filtrado_pago = "";
	}
	
	$num_pedido = $_POST['num_pedido'];
	if($num_pedido){
		$filtrado_num_pedido = " AND A.idca_pedido = '$num_pedido' ";
	}else{
		$filtrado_num_pedido = "";
	}

	
	//cantidad total:
	$query_cant = "SELECT A.idca_pedido, A.fecha_pedido, C.nombre, C.apellido, C.mail, A.estado AS estado_pedido, B.estado AS estado_pago
	FROM ca_pedido A
	LEFT JOIN ca_informe_pago B ON A.idca_informe_pago = B.idca_informe_pago
	LEFT JOIN user_web C ON A.iduser_web = C.iduser_web
	WHERE 1=1 $filtrado_pedido $filtrado_pago $filtrado_num_pedido $filtrado_fecha_pedido
	ORDER BY $filtrado_ordenar ";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));
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
		formulario = document.form_carpeta;
		formulario.pag.value = pag;
		formulario.accion.value = "buscar";
		formulario.submit();
	}

		
	function validar_form_lista(){
		formulario = document.form_carpeta;
		formulario.pag.value = "1";
		formulario.accion.value = "buscar";
		
		formulario.submit();
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
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Pedidos del Carrito de compras</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center"><form action="" method="post" name="form_carpeta" id="form_carpeta" enctype="multipart/form-data">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" align="left" bgcolor="#FFDDBC" class="detalle_medio_bold"> &nbsp;Buscar  pedidos
                            <input name="accion" type="hidden" id="accion" />
                            <span class="detalle_medio">
                          <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1">
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td align="left"><span class="detalle_medio" style="color:#666666">Determine los par&aacute;metros de b&uacute;squeda para filtrar los pedidos:</span></td>
                                </tr>
                              </table>
                            <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Estado del pedido: </td>
                                  <td width="543" align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="estado_pedido" style="width:200px;" class="detalle_medio" id="estado_pedido">
                                      <option value="" >- Indiferente</option>
									  <? 
									  
									  $query_estado = "SELECT idca_estado_pedido, titulo
									  FROM ca_estado_pedido";
									  $result_estado = mysql_query($query_estado);
									  while($rs_estado = mysql_fetch_assoc($result_estado)){ 
									  
									  ?>
                                      <option <? if($estado_pedido == $rs_estado['idca_estado_pedido']){ echo "selected"; } ?> value="<?= $rs_estado['idca_estado_pedido'] ?>"><?= $rs_estado['titulo'] ?></option>
									  <? } ?>
                                    </select>
                                  </span></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Estado del pago: </td>
                                  <td align="left" valign="middle" class="style2"><span class="style10">
                                    <select name="estado_pago" style="width:200px;" class="detalle_medio" id="estado_pago" >
                                      <option value="" >- Indiferente</option>
                                      <option value="1" <? if("1" == $estado_pago){ echo "selected"; } ?>>No pagado.</option>
									  <option value="2" <? if("2" == $estado_pago){ echo "selected"; } ?>>Sin confirmar.</option>
									  <option value="3" <? if("3" == $estado_pago){ echo "selected"; } ?>>Pago Confirmado.</option>
									  <option value="4" <? if("4" == $estado_pago){ echo "selected"; } ?>>Informe de pago rechazado. No se pudo confirmar su pago.</option>
                                    </select>
                                  </span></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">N&uacute;m. de Pedido: </td>
                                  <td align="left" valign="middle" class="style2"><label>
                                    <input name="num_pedido" type="text" class="detalle_medio" id="num_pedido" style="width:140px;" value="<?= $num_pedido ?>" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Fecha de pedido:</td>
                                  <td align="left" valign="middle" class="style2"><select name="dia" size="1" class="detalle_medio" id="dia">
                                    <option value='00' ></option>
                                    <?												
						for ($i=1;$i<32;$i++){
							if ($dia == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                  </select>
                                    <select name="mes" size="1" class="detalle_medio" id="mes">
                                      <option value='00' ></option>
                                      <?						
                        for ($i=1;$i<13;$i++){
							if ($mes == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                    </select>
                                    <select name="anio" size="1" class="detalle_medio" id="anio">
                                      <option value='0000' ></option>
                                      <?	
						$anioActual = date("Y");
                        for ($i=$anioActual+1;$i>($anioActual-5);$i--){
							if ($anio == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                                    </select></td>
                                </tr>
                                <tr>
                                  <td width="115" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                  <td align="left" valign="middle"><input name="Submit" type="button" class="detalle_medio_bold" id="Submit" onclick="validar_form_lista();" value="&gt;&gt; Buscar    " /></td>
                                </tr>
                            </table></td>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                        <tr>
                          <td align="right" class="detalle_medio">Ordenar por: </td>
                          <td width="24%" align="left"><select name="ordenar" class="detalle_medio" id="ordenar" style="width:160px;" onchange="javascript:document.form_carpeta.submit();">
                              <option value="0" <? if($_POST['ordenar'] == "0"){ echo "selected";} ?>>- Seleccione Orden </option>
                              <option value="A.fecha_pedido" <? if($_POST['ordenar'] == "A.fecha_pedido"){ echo "selected";} ?>>Fecha</option>
                              <option value="A.idca_pedido" <? if($_POST['ordenar'] == "A.idca_pedido"){ echo "selected";} ?>>N&deg; de Pedido</option>
                              <option value="A.estado" <? if($_POST['ordenar'] == "A.estado"){ echo "selected";} ?>>Estado del pedido</option>
                              <option value="B.estado" <? if($_POST['ordenar'] == "B.estado"){ echo "selected";} ?>>Estado del pago</option>
                            </select></td>
                        </tr>
                        <tr>
                          <td align="right" class="detalle_medio">Registros por hoja: </td>
                          <td align="left">
                            <select name="cant" class="detalle_medio" style="width:160px;" onchange="validar_form_lista();">
                              <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?>>5</option>
                              <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                              <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?>>25</option>
                              <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                            </select>                          </td>
                        </tr>
                    </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#ffddbc">
                          <td width="12%" height="40" align="center" class="detalle_medio_bold">N&ordm; de Pedido </td>
                          <td width="11%" height="40" align="left" bgcolor="ffddbc" class="detalle_medio_bold">Fecha</td>
                          <td width="55%" height="40" align="left" bgcolor="ffddbc" class="detalle_medio_bold">Usuario</td>
                          <td width="15%" height="40" align="center" bgcolor="ffddbc" class="detalle_medio_bold">Propiedades</td>
                          <td width="7%" height="40" bgcolor="ffddbc" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
                        <? 

	$colores = array("#fff0e1","#FFE1C4");
	$cont_colores = 0;
	$hay_lista = false;
	$count_fila = 0;	
	
	$query_lista = "SELECT A.idca_pedido, A.fecha_pedido, C.nombre, C.apellido, C.mail, A.estado AS estado_pedido, B.estado AS estado_pago
	FROM ca_pedido A
	LEFT JOIN ca_informe_pago B ON A.idca_informe_pago = B.idca_informe_pago 
	LEFT JOIN user_web C ON A.iduser_web = C.iduser_web 
	WHERE 1=1 $filtrado_pedido $filtrado_pago $filtrado_num_pedido $filtrado_fecha_pedido 
	ORDER BY $filtrado_ordenar 
	LIMIT $puntero,$cantidad_registros";
	$result_lista = mysql_query($query_lista);
	while ($rs_lista = mysql_fetch_assoc($result_lista)){ 		
		$hay_lista = true;

?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td align="right" class="detalle_chico">
                            <label></label>
                            <a name="<?= $rs_lista['idca_pedido']; ?>" id="<?= $rs_lista['idca_pedido']; ?>"></a>
                            <input name="idca_pedido_row[<?= $count_fila ?>]" type="hidden" id="idca_pedido_row[<?= $count_fila ?>]" value="<?= $rs_lista['idca_pedido']; ?>" />                          
                              <?= $rs_lista['idca_pedido']; ?>.                          </td>
                          <td align="left" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_chico"><? $fecha = split("-",$rs_lista['fecha_pedido']); echo $fecha[2]."/".$fecha[1]."/".substr($fecha[0],-2); ?></td>
                          <td align="left" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['nombre']." ".$rs_lista['apellido']."  (".$rs_lista['mail'].")" ?></td>
                          <td align="center" bgcolor="<?=$colores[$cont_colores]?>">&nbsp;<img src="../../imagen/iconos/icon_pedido.png" width="21" height="20" class="Tips1" title="Estado Pedido  :: <? $rs = estado_pedido($rs_lista['estado_pedido']); echo $rs['titulo']; ?>"/> &nbsp;
							
						  <img src="../../imagen/iconos/icon_pago.png" width="29" height="22" class="Tips1" title="Estado Pago :: <?= estado_pago($rs_lista['estado_pago']); ?>" /></td>
                          <td align="right" bgcolor="<?=$colores[$cont_colores]?>"><a href="pedidos_detalle.php?idca_pedido=<?= $rs_lista['idca_pedido'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a>&nbsp;</td>
                        </tr>
    <?	
		$count_fila++;
		
		if($cont_colores == 0){
			$cont_colores = 1;
		}else{
			$cont_colores = 0;
		};
					
	}
	?>

						<? if($hay_lista == false){  ?>
                        <tr align="center" valign="middle">
                          <td  height="50" colspan="5" bgcolor="#fff0e1" class="detalle_medio_bold">No se han encontrado pedidos.</td>
                        </tr>
                        <? }; ?>
                    </table>
                      <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td height="8"></td>
                      </tr>
                      <tr>
                        <td align="center"><span class="detalle_medio_bold">
                          <input name="count_fila" type="hidden" id="count_fila" value="<?= $count_fila ?>" />
                        <? 
						if($cantidad_total > $cantidad_registros){
							 echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']);
						} 
						?>						
						</span></td>
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