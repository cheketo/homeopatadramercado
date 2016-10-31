<? 	
	//INCLUDES
	include ("../../0_mysql.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$idcliente_servicio = $_GET['idcliente_servicio'];
	$idcliente = $_GET['idcliente'];
	
	$subtitulo = $_POST['subtitulo'];
	$detalle = $_POST['detalle'];
	$costo = $_POST['costo'];
	$frecuencia_pago = $_POST['frecuencia_pago'];

		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE cli_cliente_servicio SET
		  subtitulo='$subtitulo'
		, detalle = '$detalle'
		, costo = '$costo'
		, frecuencia_pago = '$frecuencia_pago'
		WHERE idcliente_servicio = '$idcliente_servicio'
		LIMIT 1";
		mysql_query($query_modficacion);
	
	};
	
	//CLIENTE
	$query_cliente = "SELECT empresa
		FROM cli_datos
		WHERE idcliente = '$idcliente'
		ORDER BY empresa ASC";
	$rs_cliente = mysql_fetch_assoc(mysql_query($query_cliente));
		
	//CONSULTA DE PROVINCIA
	$query = "SELECT * 
	FROM cli_cliente_servicio
	WHERE idcliente_servicio = '$idcliente_servicio' ";
	$rs_servicio = mysql_fetch_assoc(mysql_query($query));
	
	//CONSULTA DE PROVINCIA
	$query_titulo = "SELECT titulo 
	FROM cli_servicios
	WHERE idservicio = '$rs_servicio[idservicio]' ";
	$rs_titulo_servicio = mysql_fetch_assoc(mysql_query($query_titulo));
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_form_preguntas(){
	var formulario = document.form_titular;
	var flag = true;
	var error = "";
	
		if(formulario.subtitulo.value == ''){
			error = error + "Debe ingresar el titulo del producto o servicio.\n" ;
			flag = false;
		}
		
		if(flag == true){
			formulario.accion.value = "actualizar";
			formulario.submit();
		}else{
			alert(error);
		}
	
	};
	
</script>

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
                <td width="45%" height="40" valign="bottom" class="titulo_grande_bold"><img src="../../imagen/iconos/computer_process.png" width="32" height="32" /> Editar Servicio de: <span class="Estilo1">
                  <?= $rs_cliente['empresa'] ?>
                </span></td>
                <td width="32%" align="right" valign="middle" class="detalle_medio">Editar Cliente</td>
                <td width="6%" align="center" valign="middle" class="detalle_medio"><a href="cliente_editar.php?idcliente=<?= $idcliente ?>"><img src="../../imagen/iconos/business_user_edit24px.png" width="24" height="24" border="0" /></a></td>
                <td width="11%" align="right" valign="middle" class="detalle_medio">Ver Servicios</td>
                <td width="6%" align="center" valign="middle" class="titulo_grande_bold"><a href="cliente_servicios_ver.php?idcliente=<?= $idcliente ?>" target="_self"><img src="../../imagen/iconos/computer_process24px.png" width="24" height="24" border="0" /></a></td>
            </tr>
              <tr>
                <td height="20" colspan="5" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Servicio:
                            <?= $rs_titulo_servicio['titulo'] ?>
                          <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td width="83%" align="left" valign="top">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio_bold"><?= $rs_titulo_servicio['titulo'] ?></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">Subtitulo:</td>
                                <td align="left" valign="top"><input name="subtitulo" type="text" class="detalle_medio" id="subtitulo" value="<?= $rs_servicio['subtitulo']; ?>" style="width:400px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Detalle:</td>
                                <td align="left" valign="top" class="detalle_medio"><label>
                                  <textarea name="detalle" cols="45" rows="5" class="detalle_medio" id="detalle" style="width:400px; height:100px;"><?= $rs_servicio['detalle']; ?></textarea>
                                </label></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio_bold">Presupuesto</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Costo:</td>
                                <td align="left" valign="top" class="detalle_medio"><label>
                                  <input name="costo" type="text" class="detalle_medio" id="costo" value="<?= $rs_servicio['costo']; ?>" style="width:80px; text-align:center"/>
                                $</label></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Frecuencia Pago:</td>
                                <td align="left" valign="top" class="detalle_medio"><label>
                                  <select name="frecuencia_pago" class="detalle_medio" id="frecuencia_pago" style="width:85px;">
                                    <option value="1" <? if($rs_servicio['frecuencia_pago'] == 1){ echo "selected"; } ?> >Unico</option>
                                    <option value="2" <? if($rs_servicio['frecuencia_pago'] == 2){ echo "selected"; } ?> >Mensual</option>
                                    <option value="3" <? if($rs_servicio['frecuencia_pago'] == 3){ echo "selected"; } ?> >Bimestral</option>
                                    <option value="4" <? if($rs_servicio['frecuencia_pago'] == 4){ echo "selected"; } ?> >Trimestral</option>
                                    <option value="5" <? if($rs_servicio['frecuencia_pago'] == 5){ echo "selected"; } ?> >Anual</option>
                                                                    </select>
                                </label></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold buttons" onclick="validar_form_preguntas();" value=" Modificar &raquo; " /></td>
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