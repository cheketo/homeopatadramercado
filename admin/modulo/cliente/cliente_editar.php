<? 	
	//INCLUDES
	include ("../../0_mysql.php");
	
	//FUNCION FORMATEAR FECHA
	function formatearFecha($fecha, $separador){
		
		$fecha_array = split("-",$fecha);
		return $fecha_array[2].$separador.$fecha_array[1].$separador.$fecha_array[0];
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$idcliente = $_GET['idcliente'];
	
	$empresa = $_POST['empresa'];
	$telefono = $_POST['telefono'];
	$mail = $_POST['mail'];
	$empresa = $_POST['empresa'];
	$calle = $_POST['calle'];
	$numero = $_POST['numero'];
	$piso = $_POST['piso'];
	$depto = $_POST['depto'];
	$idpais_provincia = $_POST['idpais_provincia'];
	$localidad = $_POST['localidad'];
	$mail = $_POST['mail'];
	$nombre_contacto = $_POST['nombre_contacto'];
	$mail_contacto = $_POST['mail_contacto'];
	$telefono_contacto = $_POST['telefono_contacto'];
	$celular_contacto = $_POST['celular_contacto'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$observaciones = $_POST['observaciones'];
		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE cli_datos SET
		  empresa='$empresa'
		, calle='$calle'
		, numero='$numero'
		, piso='$piso'
		, depto='$depto'
		, idpais_provincia='$idpais_provincia'
		, localidad='$localidad'
		, mail='$mail'
		, telefono='$telefono'
		, nombre_contacto='$nombre_contacto'
		, mail_contacto='$mail_contacto'
		, telefono_contacto='$telefono_contacto'
		, celular_contacto='$celular_contacto'
		, username='$username'
		, password='$password'
		, observaciones='$observaciones'

		WHERE idcliente = '$idcliente'
		LIMIT 1";
		mysql_query($query_modficacion);
	
	};
		
	//CONSULTA DE PROVINCIA
	$query = "SELECT * 
	FROM cli_datos 
	WHERE idcliente = '$idcliente' ";
	$rs_cliente = mysql_fetch_assoc(mysql_query($query));
		
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
	
		if (formulario.mail.value != '')	{
			
			if (formulario.mail.value.indexOf("@") == (-1)){
				error = error + 'A su e-mail le falta el @.\n';
				flag = false;
			}
			
			if (formulario.mail.value.indexOf(".") == (-1)){
				error = error + 'A su e-mail le falta la extension (ej: .com, .com.es).\n';
				flag = false;
			}
			
		}
		
		if(formulario.titulo.value == ''){
			error = error + "Debe ingresar el titulo de la Sucursal.\n" ;
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
                <td width="45%" height="40" valign="bottom" class="titulo_grande_bold"><img src="../../imagen/iconos/business_user_edit.png" width="32" height="32" /> Cliente - Editar </td>
                <td width="32%" align="right" valign="middle" class="detalle_medio">Ver Servicios</td>
                <td width="6%" align="center" valign="middle" class="detalle_medio"><a href="cliente_servicios_ver.php?idcliente=<?= $idcliente ?>"><img src="../../imagen/iconos/computer_process24px.png" width="24" height="24" border="0" /></a></td>
                <td width="11%" align="right" valign="middle" class="detalle_medio">Ver Clientes</td>
                <td width="6%" align="center" valign="middle" class="titulo_grande_bold"><a href="cliente_ver.php" target="_self"><img src="../../imagen/iconos/business_user_search24px.png" width="24" height="24" border="0" /></a></td>
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
                          <td height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Datos del cliente:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">N&deg; Cliente:</td>
                          <td colspan="5" align="left" valign="top"><label>
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" disabled="disabled" value="<?= $rs_cliente['idcliente'] ?>" style="width:40px; text-align:center;" />
                                </label></td>
                            </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">Fecha de alta:</td>
                                <td colspan="5" align="left" valign="top"><input name="titulo2" type="text" class="detalle_medio" id="titulo2" disabled="disabled" value="<?= formatearFecha($rs_cliente['fecha_alta'],"/"); ?>" style="width:100px; text-align:center;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio_bold">Datos de la empresa:</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Empresa/Nombre:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><p>
                                  <input name="empresa" type="text" class="detalle_medio" id="empresa" value="<?= $rs_cliente['empresa'] ?>" style="width:200px;" />
                                </p>                                </td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Telefono:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="telefono" type="text" class="detalle_medio" id="telefono" value="<?= $rs_cliente['telefono'] ?>" style="width:150px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">E-mail:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="mail" type="text" class="detalle_medio" id="mail" value="<?= $rs_cliente['mail'] ?>" style="width:200px;" />
&nbsp; <a href="mailto:<?= $rs_cliente['mail'] ?>"><img src="../../imagen/iconos/email_go.png" width="16" height="16" border="0" /></a></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Provincia:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><span class="style2">
                                  <select name="idpais_provincia" class="detalle_medio" style="width:205px;" id="idpais_provincia">
                                    <option value="" >- Seleccionar Provincia</option>
                                    <?
		 
										  $query_idproducto = "SELECT *
										  FROM pais_provincia
										  WHERE estado <> 3 AND idpais = '54'
										  ORDER BY titulo";
										  $result_idproducto = mysql_query($query_idproducto);
										  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
										  {
											if ($rs_cliente['idpais_provincia'] == $rs_idproducto['idpais_provincia'])
											{
												$sel_idproducto = "selected";
											}else{
												$sel_idproducto = "";
											}
									?>
                                    <option value="<?= $rs_idproducto['idpais_provincia'] ?>" <? echo $sel_idproducto ?>>
                                    <?= $rs_idproducto['titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Barrio/Localidad:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="localidad" type="text" class="detalle_medio" id="localidad" value="<?= $rs_cliente['localidad'] ?>" style="width:200px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Calle:</td>
                                <td width="32%" colspan="3" align="left" valign="top" class="detalle_medio"><input name="calle" type="text" class="detalle_medio" id="calle" value="<?= $rs_cliente['calle'] ?>" style="width:200px;" /></td>
                                <td width="9%" align="left" valign="top" class="detalle_medio">&nbsp;</td>
                                <td width="42%" align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">N&uacute;mero:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="numero" style="width:40px;" type="text" class="detalle_medio" id="numero" value="<?= $rs_cliente['numero'] ?>" /></td>
                                <td align="right" valign="top" class="detalle_medio">Piso:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="piso" style="width:40px;" type="text" class="detalle_medio" id="piso" value="<?= $rs_cliente['piso'] ?>" /></td>
                                <td align="left" valign="top" class="detalle_medio">Depto:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="depto" style="width:40px;" type="text" class="detalle_medio" id="depto" value="<?= $rs_cliente['depto'] ?>" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio_bold">Datos del responsable de la empresa:</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Responsable:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="nombre_contacto" type="text" class="detalle_medio" id="nombre_contacto" value="<?= $rs_cliente['nombre_contacto'] ?>" style="width:200px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Telefono:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="telefono_contacto" type="text" class="detalle_medio" id="telefono_contacto" value="<?= $rs_cliente['telefono_contacto'] ?>" style="width:150px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Celular:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="celular_contacto" type="text" class="detalle_medio" id="celular_contacto" value="<?= $rs_cliente['celular_contacto'] ?>" style="width:150px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">E-mail:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="mail_contacto" type="text" class="detalle_medio" id="mail_contacto" value="<?= $rs_cliente['mail_contacto'] ?>" style="width:200px;" /> 
                                   &nbsp; <a href="mailto:<?= $rs_cliente['mail_contacto'] ?>"><img src="../../imagen/iconos/email_go.png" width="16" height="16" border="0" /></a></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio_bold">Datos de ingreso:</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Username:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="username" type="text" class="detalle_medio" id="username" value="<?= $rs_cliente['username'] ?>" style="width:200px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Password:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><input name="password" type="text" class="detalle_medio" id="password" value="<?= $rs_cliente['password'] ?>" style="width:200px;" /> <label></label></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Observaciones:</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio"><label>
                                  <textarea name="observaciones" class="detalle_medio" id="observaciones" style="width:400px; height:100px;"><?= $rs_cliente['observaciones'] ?></textarea>
                                </label></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td colspan="5" align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td colspan="5" align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold buttons" onclick="validar_form_preguntas();" value=" Modificar &raquo; " /></td>
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