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
	$idposible_cliente = $_GET['idposible_cliente'];
	
	$empresa = $_POST['empresa'];
	$website = $_POST['website'];
	
	$idpais_provincia = $_POST['idpais_provincia'];
	$localidad = $_POST['localidad'];
	
	$mail = $_POST['mail'];
	$nombre_contacto = $_POST['nombre_contacto'];
	$telefono_contacto = $_POST['telefono_contacto'];
	$celular_contacto = $_POST['celular_contacto'];
	
	$requerimientos = $_POST['requerimientos'];
	$observaciones = $_POST['observaciones'];
		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE cli_posibles_clientes SET
		  empresa='$empresa'
		, website='$website'
		, idpais_provincia='$idpais_provincia'
		, localidad='$localidad'
		, mail='$mail'
		, nombre_contacto='$nombre_contacto'
		, telefono_contacto='$telefono_contacto'
		, celular_contacto='$celular_contacto'
		, requerimientos='$requerimientos'
		, observaciones='$observaciones'

		WHERE idposible_cliente = '$idposible_cliente'
		LIMIT 1";
		mysql_query($query_modficacion);
	
	};
		
	//CONSULTA DE PROVINCIA
	$query = "SELECT * 
	FROM cli_posibles_clientes 
	WHERE idposible_cliente = '$idposible_cliente' ";
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
	
		if(formulario.empresa.value == ''){
			error = error + "Debe ingresar el nombre de la Empresa.\n" ;
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
                <td width="45%" height="40" valign="bottom" class="titulo_grande_bold"><img src="../../imagen/iconos/business_user_edit.png" width="32" height="32" /> Posible Cliente - Editar Datos</td>
                <td align="right" valign="middle" class="detalle_medio">Ver Posibles Clientes</td>
                <td width="6%" align="center" valign="middle" class="titulo_grande_bold"><a href="cliente_ver.php" target="_self"><img src="../../imagen/iconos/business_user_search24px.png" width="24" height="24" border="0" /></a></td>
            </tr>
              <tr>
                <td height="20" colspan="3" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Informaci&oacute;n  del posible cliente:
<input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">N&deg; Posible Cliente:</td>
                          <td width="83%" align="left" valign="top"><label>
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" disabled="disabled" value="<?= $rs_cliente['idposible_cliente'] ?>" style="width:40px; text-align:center;" />
                                </label></td>
                            </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio_bold">Datos de la empresa:</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Empresa/Nombre:</td>
                                <td align="left" valign="top" class="detalle_medio"><p>
                                  <input name="empresa" type="text" class="detalle_medio" id="empresa" value="<?= $rs_cliente['empresa'] ?>" style="width:200px;" />
                                </p>                                </td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Website:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="website" type="text" class="detalle_medio" id="website" value="<?= $rs_cliente['website'] ?>" style="width:200px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Provincia:</td>
                                <td align="left" valign="top" class="detalle_medio"><span class="style2">
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
                                    <option value="<?= $rs_idproducto['idpais_provincia'] ?>" <?= $sel_idproducto ?>>
                                    <?= $rs_idproducto['titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Barrio/Localidad:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="localidad" type="text" class="detalle_medio" id="localidad" value="<?= $rs_cliente['localidad'] ?>" style="width:200px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio_bold">Datos del responsable de la empresa:</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Nombre:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="nombre_contacto" type="text" class="detalle_medio" id="nombre_contacto" value="<?= $rs_cliente['nombre_contacto'] ?>" style="width:200px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Telefono:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="telefono_contacto" type="text" class="detalle_medio" id="telefono_contacto" value="<?= $rs_cliente['telefono_contacto'] ?>" style="width:150px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Celular:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="celular_contacto" type="text" class="detalle_medio" id="celular_contacto" value="<?= $rs_cliente['celular_contacto'] ?>" style="width:150px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">E-mail:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="mail" type="text" class="detalle_medio" id="mail" value="<?= $rs_cliente['mail'] ?>" style="width:200px;" /> 
                                   &nbsp; <a href="mailto:<?= $rs_cliente['mail'] ?>"><img src="../../imagen/iconos/email_go.png" width="16" height="16" border="0" /></a></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio_bold">Presupuesto:</td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Requerimientos:</td>
                                <td align="left" valign="top" class="detalle_medio"><textarea name="requerimientos" class="detalle_medio" id="requerimientos" style="width:400px; height:100px;"><?= $rs_cliente['requerimientos'] ?></textarea></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">Observaciones:</td>
                                <td align="left" valign="top" class="detalle_medio"><label>
                                  <textarea name="observaciones" class="detalle_medio" id="observaciones" style="width:400px; height:100px;"><?= $rs_cliente['observaciones'] ?></textarea>
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