<? 	
	//INCLUDES
	include ("../../0_mysql.php"); 
	
	//VARIABLES		
	$accion = $_POST['accion'];
	$idcliente = $_GET['idcliente'];
	$idservicio = $_POST['idservicio'];
	$subtitulo = $_POST['subtitulo'];
	
	//FUNCION PAGINAR
	function paginar($actual, $total, $por_pagina) {
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
	
	//CLIENTE
	$query_cliente = "SELECT empresa
		FROM cli_datos
		WHERE idcliente = '$idcliente'
		ORDER BY empresa ASC";
	$rs_cliente = mysql_fetch_assoc(mysql_query($query_cliente));
	

	//PAGINACION: Cantidad Total.
	$query_cant = "SELECT *
		FROM cli_cliente_servicio
		WHERE idcliente = '$idcliente' ";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	

	//INGRESAR
	if( $accion == "ingresar" ){
	
		$query_ingresar = "INSERT INTO cli_cliente_servicio (
		  idservicio, idcliente, subtitulo
		) VALUES (
		  '$idservicio', '$idcliente', '$subtitulo'
		)";
		mysql_query($query_ingresar);
	
	};
			   
	//BORRAR
	$eliminar = $_POST['eliminar'];
	
	if($eliminar){	
		$query_eliminar = "DELETE 
		FROM cli_cliente_servicio
		WHERE idcliente_servicio = '$eliminar'";
		mysql_query($query_eliminar);
		
	};
	
	//CAMBIAR ESTADO
	$idcliente_servicio = $_POST['idcliente_servicio'];
	$estado = $_POST['estado'];
	
	if($estado != "" && $idcliente != ""){
		$query_estado = "UPDATE cli_cliente_servicio
		SET estado = '$estado'
		WHERE idcliente_servicio = '$idcliente_servicio'
		LIMIT 1";
		mysql_query($query_estado);
	
	}
	
?>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_empresa(){
	formulario = document.form_titular;
	
		if(formulario.subtitulo.value == ''){
			alert("Debe un subtitulo para identificar este servicio.");
		}else{
			formulario.accion.value = "ingresar";
			formulario.submit();
		}

	};
	
	function cambiar_estado(estado, id){
		formulario = document.form_titular;
		
		formulario.estado.value = estado;
		formulario.idcliente_servicio.value = id;
		formulario.submit();
	};
	
	function confirmar_eliminar(id){
	formulario = document.form_titular;
		if (confirm('¿Esta seguro que desea eliminar la Empresa?')){
			formulario.eliminar.value = id;
			formulario.submit();
		}
	};
	
	function ir_pagina(pag){
		formulario = document.form_titular;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function validar_form_lista(){
		formulario = document.form_titular;
		formulario.pag.value = "1";
		formulario.submit();
	};
	
	function buscar(){
		formulario = document.form_titular;
		/*if(formulario.busqueda.value != ""){*/
			formulario.pag.value = "1";
			formulario.submit();
		/*}else{
			alert("Introduzca el texto que desee buscar.");
		}*/
	};

</script>

<link href="../../../skin/index/css/0_especial.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #003399}
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
                <td width="48%" height="40" valign="bottom" class="titulo_grande_bold"> <img src="../../imagen/iconos/computer_process.png" width="32" height="32" /> Servicios de: <span class="Estilo1"><?= $rs_cliente['empresa'] ?></span></td>
                <td width="27%" align="right" valign="middle" class="detalle_medio">Editar Cliente</td>
                <td width="6%" align="center" valign="middle" class="detalle_medio"><a href="cliente_editar.php?idcliente=<?= $idcliente ?>"><img src="../../imagen/iconos/business_user_edit24px.png" width="24" height="24" border="0" /></a></td>
                <td width="13%" align="right" valign="middle" class="detalle_medio">Ver Clientes</td>
                <td width="6%" align="center" valign="middle" class="detalle_medio"><a href="cliente_ver.php"><img src="../../imagen/iconos/business_user_search24px.png" width="24" height="24" border="0" /></a></td>
            </tr>
              <tr>
                <td height="20" colspan="5" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
				   <form action="" method="post" name="form_titular" id="form_titular">
				     <span class="detalle_medio">
				     <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
				     </span>
				     <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td width="50%" align="left" valign="middle" class="detalle_medio"><label></label></td>
                      <td width="41%" align="right" valign="middle" class="detalle_medio">Registros por hoja </td>
                      <td width="9%" valign="middle" class="detalle_medio"><select name="cant" class="detalle_medio" onchange="validar_form_lista();">
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
      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#FFB76F">
                        <td width="4%" height="40" align="center" bgcolor="#FFD3A8" class="detalle_medio_bold">ID</td>
                        <td width="80%" height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Servicios y productos
                          <input name="estado" type="hidden" id="estado" />
                        <input name="idcliente_servicio" type="hidden" id="idcliente_servicio" />
                        <input name="eliminar" type="hidden" id="eliminar" /></td>
                        <td height="40" colspan="5" align="center" bgcolor="#FFD3A8" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
                      <? 
 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM cli_cliente_servicio
		WHERE idcliente = '$idcliente' 
		LIMIT $puntero,$cantidad_registros";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			
			$hay_lista = true;			  
			  
	  
?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td width="4%" align="left" valign="top" class="detalle_chico"><a name="<?= $rs_lista['idcliente_servicio']; ?>" id="<?= $rs_lista['idcliente_servicio']; ?>"></a>
                              <?=$rs_lista['idcliente_servicio']?>.                        </td>
                        <td valign="top" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
						<?
						
							$query_titulo_servicio = "SELECT titulo
							FROM cli_servicios
							WHERE idservicio = '$rs_lista[idservicio]' ";
							$rs_titulo_servicio = mysql_fetch_assoc(mysql_query($query_titulo_servicio));
							echo $rs_titulo_servicio['titulo'];
						
						?><br />
						<span class="detalle_11px"><?= $rs_lista['subtitulo']; ?></span></td>
                      <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                            <a href="javascript:cambiar_estado(2,<?= $rs_lista['idcliente_servicio'] ?>);"><img src="../../imagen/iconos/accept_blue.png" alt="Habilitado" width="16" height="16" border="0" /></a>
                            <? } else { ?>
                            <a href="javascript:cambiar_estado(1,<?= $rs_lista['idcliente_servicio'] ?>);"><img src="../../imagen/iconos/accept_blue_off.png" alt="Deshabilitado" width="16" height="16" border="0" /></a>
                            <? } ?></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="cliente_servicios_editar.php?idcliente_servicio=<?= $rs_lista['idcliente_servicio'] ?>&idcliente=<?= $idcliente ?>" target="_parent" class="style10"><img src="../../imagen/iconos/pencil.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>">
						
						<a href="javascript:confirmar_eliminar('<?= $rs_lista['idcliente_servicio'] ?>')" class="style10"><img src="../../imagen/iconos/cross.png" alt="Eliminar" width="16" height="16" border="0" /></a>												</td>
                    </tr>
                      <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                      <tr align="center" valign="middle">
                        <td colspan="7" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado servicios o productos de &eacute;ste cliente.</td>
                      </tr>
                      <? }; ?>
                      <tr align="center" valign="middle">
                        <td colspan="7" class="detalle_medio_bold"><span class="titulo_medio_bold"><? echo paginar($pag, $cantidad_total, $cantidad_registros); ?></span></td>
                      </tr>
                    </table>
                     
                        <br />
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFD3A8" class="detalle_medio_bold" >Agregar nuevo servicio o producto para &eacute;ste cliente:
                              <input name="accion" type="hidden" id="accion" value="0" />
                            <a name="nuevo" id="nuevo"></a></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td colspan="2" align="right" valign="top"></td>
                                </tr>
                                <tr>
                                  <td width="15%" align="right" valign="middle" class="detalle_medio">Servicio:</td>
                                <td width="85%" align="left" valign="middle"><label>
                                  <select name="idservicio" class="detalle_medio" id="idservicio" style="width:255px;">
                                  <?
								  	$query_servicios = "SELECT * FROM cli_servicios
									WHERE estado = 1
									ORDER BY titulo ASC";
									$result_servicios = mysql_query($query_servicios);
									while($rs_servicios = mysql_fetch_assoc($result_servicios)){
								  ?>
                                    <option value="<?= $rs_servicios['idservicio'] ?>"><?= $rs_servicios['titulo'] ?></option>
                                  <? } ?>  
                                  </select>
                                </label></td>
                                </tr>
                                <tr>
                                  <td width="15%" align="right" valign="middle" class="detalle_medio">Subtitulo:</td>
                                  <td align="left" valign="middle"><label>
                                    <input name="subtitulo" type="text" class="detalle_medio" id="subtitulo" style="width:250px;"/>
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" >&nbsp;</td>
                                  <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold buttons" onclick="validar_empresa();" value=" Ingresar &raquo; " lass="detalle_medio_bold buttons" /></td>
                                </tr>
                            </table></td>
                          </tr>
                     </table>
                     <br />
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