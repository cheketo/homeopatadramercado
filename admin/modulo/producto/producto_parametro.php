<? 
	include ("../../0_mysql.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	//VARIABLES
	$cod_interno = $_POST['cod_interno'];
	$cod_fabricante = $_POST['cod_fabricante'];
	$marca = $_POST['marca'];
	$precio = $_POST['precio'];
	
	$foto_chica = $_POST['foto_chica'];
	$foto_mediana = $_POST['foto_mediana'];
	$foto_grande = $_POST['foto_grande'];
	$foto_extra_chica = $_POST['foto_extra_chica'];
	$foto_extra_grande = $_POST['foto_extra_grande'];
	$usar_titulo_web = $_POST['usar_titulo_web'];
	
	$accion = $_POST['accion'];

	//INGRESO:
	if($accion == "modificar"){
		
		$query = "UPDATE producto_parametro SET
		  cod_interno = '$cod_interno'
		, usar_titulo_web = '$usar_titulo_web'
		, cod_fabricante = '$cod_fabricante'
		, marca = '$marca'
		, precio = '$precio'
		, foto_chica = '$foto_chica'
		, foto_mediana = '$foto_mediana'
		, foto_grande = '$foto_grande'
		, foto_extra_chica = '$foto_extra_chica'
		, foto_extra_grande = '$foto_extra_grande'
		WHERE idproducto_parametro = 1";
		mysql_query($query);
		
		$accion = "";
	
	};
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM producto_parametro
	WHERE idproducto_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
<script lnguage="javascript">
function parametro(){
	var f = document.form;
	
	f.accion.value = "modificar";
	f.submit();
}
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto - Par&aacute;metros </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Par&aacute;matros configurables de los productos:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="25%" align="right" valign="middle" class="detalle_medio">Utiliza Titulo URL: </td>
                                <td width="75%" valign="middle" class="style2"><input name="usar_titulo_web" type="radio" class="detalle_medio" value="1" <? if($rs_parametro['usar_titulo_web'] == 1){ echo "checked"; } ?> />
Si
  <input name="usar_titulo_web" type="radio" class="detalle_medio" value="2" <? if($rs_parametro['usar_titulo_web'] == 2){ echo "checked"; } ?> />
No </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Cod. Interno:</td>
                                <td width="75%" valign="middle" class="style2"><span class="style10">
                                  <select name="cod_interno" class="detalle_medio" id="cod_interno">
                                    <option value="1" <? if($rs_parametro['cod_interno']==1){ echo "selected";} ?>>Habilitado</option>
                                    <option value="2" <? if($rs_parametro['cod_interno']==2){ echo "selected";} ?>>Deshabilitado</option>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Cod. Fabricante:</td>
                                <td valign="middle" class="style2"><span class="style10">
                                  <select name="cod_fabricante" class="detalle_medio" id="cod_fabricante">
                                    <option value="1" <? if($rs_parametro['cod_fabricante']==1){ echo "selected";} ?>>Habilitado</option>
                                    <option value="2" <? if($rs_parametro['cod_fabricante']==2){ echo "selected";} ?>>Deshabilitado</option>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Marca:</td>
                                <td valign="middle" class="style2"><span class="style10">
                                  <select name="marca" class="detalle_medio" id="marca">
                                    <option value="1" <? if($rs_parametro['marca']==1){ echo "selected";} ?>>Habilitado</option>
                                    <option value="2" <? if($rs_parametro['marca']==2){ echo "selected";} ?>>Deshabilitado</option>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Precio e IVA: </td>
                                <td valign="middle" class="style2"><span class="style10">
                                  <select name="precio" class="detalle_medio" id="precio">
                                    <option value="1" <? if($rs_parametro['precio']==1){ echo "selected";} ?>>Habilitado</option>
                                    <option value="2" <? if($rs_parametro['precio']==2){ echo "selected";} ?>>Deshabilitado</option>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right" valign="middle" class="detalle_medio" height="10"></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio"><strong>Tama&ntilde;os Predeterminados </strong></td>
                                <td valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto chica: </td>
                                <td valign="middle" class="detalle_medio"><label>
                                  <input name="foto_chica" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_chica'] ?>" />
                                  <strong>px </strong>ancho. </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto mediana: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_mediana" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_mediana'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto grande: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_grande" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_grande'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto extra chica: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_extra_chica" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_extra_chica'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto extra grande: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_extra_grande" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_extra_grande'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="25%" align="right" valign="top">&nbsp;</td>
                                <td width="75%"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22222" type="button" class="detalle_medio_bold" onclick="javascript:parametro();" value="  &gt;&gt; Guardar     " />
                                </span></td>
                              </tr>
                            </table></td>
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