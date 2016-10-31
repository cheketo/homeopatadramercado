<? 
	include ("../../0_mysql.php"); 

	//VARIABLES
	$estado_breve_descripcion = $_POST['estado_breve_descripcion'];
	$estado_contenido = $_POST['estado_contenido'];
	$pag_seccion = $_POST['pag_seccion'];
	$pag_producto = $_POST['pag_producto'];
	$accion = $_POST['accion'];

	//INGRESO:
	if($accion == "modificar"){
		
		$query = "UPDATE carpeta_parametro SET
		  estado_breve_descripcion = '$estado_breve_descripcion'
		, estado_contenido = '$estado_contenido'
		, pag_seccion = '$pag_seccion'
		, pag_producto = '$pag_producto'
		WHERE idcarpeta_parametro = 1";
		mysql_query($query);
		
		$accion = "";
	
	};
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM carpeta_parametro
	WHERE idcarpeta_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Carpeta - Par&aacute;metros </td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Par&aacute;matros configurables de la carpeta:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="112" align="right" valign="middle" class="detalle_medio">Breve descripci&oacute;n:</td>
                                <td colspan="2" valign="middle" class="style2"><span class="style10">
                                  <select name="estado_breve_descripcion" class="detalle_medio" id="estado_breve_descripcion">
                                    <option value="1" <? if($rs_parametro['estado_breve_descripcion']==1){ echo "selected";} ?>>Habilitado</option>
                                    <option value="2" <? if($rs_parametro['estado_breve_descripcion']==2){ echo "selected";} ?>>Deshabilitado</option>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Contenido:</td>
                                <td colspan="2" valign="middle" class="style2"><span class="style10">
                                  <select name="estado_contenido" class="detalle_medio" id="estado_contenido">
                                    <option value="1" <? if($rs_parametro['estado_contenido']==1){ echo "selected";} ?>>Habilitado</option>
                                    <option value="2" <? if($rs_parametro['estado_contenido']==2){ echo "selected";} ?>>Deshabilitado</option>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Cant. de secciones: </td>
                                <td width="30" valign="middle" class="style2"><input name="pag_seccion" type="text" class="detalle_medio" id="pag_seccion" style="width:30px; text-align:center;" value="<?= $rs_parametro['pag_seccion'] ?>" /></td>
                                <td width="506" valign="middle" class="style2">que se muestran por p&aacute;g.</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Cant. de productos: </td>
                                <td width="30" valign="middle" class="style2"><input name="pag_producto" type="text" class="detalle_medio" id="pag_producto" style="width:30px; text-align:center;" value="<?= $rs_parametro['pag_producto'] ?>" /></td>
                                <td valign="middle" class="style2">que se muestran por p&aacute;g.</td>
                              </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="18%" align="right" valign="top">&nbsp;</td>
                                <td width="82%"><span class="detalle_chico" style="color:#FF0000">
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