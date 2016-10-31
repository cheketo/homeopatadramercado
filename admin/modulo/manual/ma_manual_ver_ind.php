<? include ("../../0_mysql.php"); 

// localizacion de variables get y post:
$url_manual = $_GET['url_manual'];
$accion = $_POST['accion'];

	$query_manual = "SELECT A.*
	FROM ma_manual  A
	WHERE A.url_manual = '$url_manual'";	
	$result_manual = mysql_query($query_manual);
	$rs_manual = mysql_fetch_assoc($result_manual);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function mod6_validarRegistro(){
	formulario = document.form;
	if (formulario.url_manual.value == ""){
		alert("Debe ingresar la URL a la cual pertenece la ayuda del manual.");	
	} else if (formulario.titulo.value == ""){
		alert("Debe ingresar el título.");
	} else {	
		formulario.accion.value = 'modificar';
		formulario.submit();
	};
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Manual - Ver </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFDDBC" class="titulo_medio_bold">Datos del Manual :<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right" valign="top" class="detalle_medio_bold">URL</td>
                                <td align="left"><?= $rs_manual['url_manual'] ?></td>
                              </tr>
                              <tr>
                                <td width="110" align="right" valign="top" class="detalle_medio_bold">T&iacute;tulo</td>
                                <td align="left"><?= $rs_manual['titulo'] ?></td>
                              </tr>
                              <tr>
                                <td width="110" align="right" valign="top" class="detalle_medio_bold">Detalle</td>
                                <td align="left"><?= $rs_manual['detalle'] ?></td>
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