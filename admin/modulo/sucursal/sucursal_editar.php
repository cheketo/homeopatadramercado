<? 	
	//INCLUDES
	include ("../../0_mysql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$idsede = $_GET['idsede'];
	
	$titulo = $_POST['titulo'];
	$direccion = $_POST['direccion'];
	$telefono = $_POST['telefono'];
	$mail = $_POST['mail'];
	$idpais = $_POST['idpais'];
	$orden = $_POST['orden'];
		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE sede SET
		  titulo='$titulo'
		, direccion='$direccion'
		, telefono='$telefono'
		, mail='$mail'
		, idpais='$idpais'
		, orden='$orden'
		WHERE idsede = '$idsede'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		echo "<script>window.location.href=('sucursal_ver.php');</script>";
		
	
	};
		
	//CONSULTA DE PROVINCIA
	$query = "SELECT * 
	FROM sede 
	WHERE idsede = '$idsede' ";
	$rs_provincia = mysql_fetch_assoc(mysql_query($query));
		
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
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Sucursal - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Editar Sucural:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="14%" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                <td width="86%" align="left" valign="top"><label>
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?= $rs_provincia['titulo'] ?>" size="60" />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="14%" align="right" valign="top" class="detalle_medio">Direcci&oacute;n:</td>
                                <td align="left" valign="top" class="detalle_medio">
                                  <input name="direccion" type="text" class="detalle_medio" id="direccion" value="<?= $rs_provincia['direccion'] ?>" size="60" />
                                                                </td>
                              </tr>
                              <tr>
                                <td width="14%" align="right" valign="top" class="detalle_medio">Telefono:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="telefono" type="text" class="detalle_medio" id="telefono" value="<?= $rs_provincia['telefono'] ?>" size="40" /></td>
                              </tr>
                              <tr>
                                <td width="14%" align="right" valign="top" class="detalle_medio">E-mail:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="mail" type="text" class="detalle_medio" id="mail" value="<?= $rs_provincia['mail'] ?>" size="60" /></td>
                              </tr>
                              <tr>
                                <td width="14%" align="right" valign="top" class="detalle_medio">Pa&iacute;s:</td>
                                <td align="left" valign="top" class="detalle_medio"><select name="idpais" class="detalle_medio" id="idpais" style="width:200px;" >
                                  <option value="">- Seleccionar Pa&iacute;s</option>
                                  <?
		 
	  $query_idproducto = "SELECT *
	  FROM pais
	  WHERE estado <> 3 
	  ORDER BY titulo";
	  $result_idproducto = mysql_query($query_idproducto);
	  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
	  {
	  	if ($rs_idproducto['idpais'] == $rs_provincia['idpais'])
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
                              <tr>
                                <td width="14%" align="right" valign="top" class="detalle_medio">N&deg; de Orden: </td>
                                <td align="left" valign="top" class="detalle_medio"><input name="orden" type="text" class="detalle_medio" id="orden" value="<?= $rs_provincia['orden'] ?>" size="7" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form_preguntas();" value=" Modificar &raquo; " /></td>
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