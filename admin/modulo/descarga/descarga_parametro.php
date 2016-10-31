<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	//VARIABLES
	$prefijo = $_POST['prefijo'];
	$usa_prefijo = $_POST['usa_prefijo'];
	$usa_random = $_POST['usa_random'];
	$ver_desc_siempre = $_POST['ver_desc_siempre'];
	$ver_desc_user = $_POST['ver_desc_user'];
	$valor_restringido = $_POST['valor_restringido'];
	
	$accion = $_POST['accion'];

	//INGRESO:
	if($accion == "modificar"){
		
		$query = "UPDATE descarga_parametro SET
		  prefijo = '$prefijo'
		, usa_prefijo = '$usa_prefijo'
		, usa_random = '$usa_random'
		, ver_desc_siempre = '$ver_desc_siempre'
		, ver_desc_user = '$ver_desc_user'
		, valor_restringido = '$valor_restringido'
		WHERE iddescarga_parametro = 1";
		mysql_query($query);
		
		$accion = "";
	
	};
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM descarga_parametro
	WHERE iddescarga_parametro = 1";
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
  <div id="navigation">
    <? include("../../modulo/0_barra/0_barra.php"); ?>
  </div>
  <div id="content">
    <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="40" valign="bottom" class="titulo_grande_bold">Descarga - Par&aacute;metros </td>
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
                        <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Par&aacute;matros configurables de las descargas:<span class="detalle_chico" style="color:#FF0000">
                          <input name="accion" type="hidden" id="accion" value="1" />
                        </span></td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td bgcolor="#FFF0E1"><table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                            <tr>
                              <td width="126" align="right" valign="middle" class="detalle_medio">Prefijo:</td>
                              <td width="532" valign="middle" class="style2"><input name="prefijo" type="text" class="detalle_medio" id="prefijo" style="width:250px;" maxlength="30" value="<?= $rs_parametro['prefijo'] ?>" /></td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&iquest;Usa Prefijo?</td>
                              <td valign="middle" class="style2"><input name="usa_prefijo" type="radio" value="1" <? if($rs_parametro['usa_prefijo'] == 1){ echo "checked"; } ?> />
                                Si
                                <input name="usa_prefijo" type="radio" value="2" <? if($rs_parametro['usa_prefijo'] == 2){ echo "checked"; } ?> />
                                No</td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&iquest;Usa n&uacute;m. aleatorio?</td>
                              <td valign="middle" class="style2"><input name="usa_random" type="radio" value="1" <? if($rs_parametro['usa_random'] == 1){ echo "checked"; } ?> />
                                Si
                                <input name="usa_random" type="radio" value="2" <? if($rs_parametro['usa_random'] == 2){ echo "checked"; } ?> />
                                No</td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="detalle_medio">Cuadro de descarga: </td>
                              <td valign="middle" class="style2"><p class="style10">&iquest;Muestra cuadro de descargas cuando no hay descargas? </p>
                                <p class="style10">
                                        <input name="ver_desc_siempre" type="radio" value="1" <? if($rs_parametro['ver_desc_siempre'] == 1){ echo "checked"; } ?> />
                                  Si
                                  <input name="ver_desc_siempre" type="radio" value="2" <? if($rs_parametro['ver_desc_siempre'] == 2){ echo "checked"; } ?> />
                                  No </p></td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                              <td valign="middle" class="style2"><p class="style10">&iquest;Muestra cuadro de descargas cuando el usuario no esta logueado? </p>
                                <p class="style10">
                                        <input name="ver_desc_user" type="radio" value="1" <? if($rs_parametro['ver_desc_user'] == 1){ echo "checked"; } ?> />
                                  Si
                                  <input name="ver_desc_user" type="radio" value="2" <? if($rs_parametro['ver_desc_user'] == 2){ echo "checked"; } ?> />
                                  No <br />  
                                  <br />
                                </p></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="detalle_medio">Valor Predeterminado: </td>
                              <td valign="middle" class="style2"><p class="style10">Por defecto, todas las descargas que se creen ser&aacute;n...
</p>
                                <p class="style10">                                  
                                  <input name="valor_restringido" type="radio" value="1" <? if($rs_parametro['valor_restringido'] == 1){ echo "checked"; } ?> />
                                  Restringidas
                                  <input name="valor_restringido" type="radio" value="2" <? if($rs_parametro['valor_restringido'] == 2){ echo "checked"; } ?>  />
                              No restringidas </p></td>
                            </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="20%" align="right" valign="top">&nbsp;</td>
                                <td width="80%"><span class="detalle_chico" style="color:#FF0000">
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