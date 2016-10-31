<? include ("../../0_mysql.php"); 

// localizacion de variables get y post:
$url_manual_nuevo = $_GET['url_manual_nuevo'];
$titulo = $_POST['titulo'];
$accion = $_POST['accion'];

// Ingreso del titular:
if($accion == "insertar"){

	//ingreso en tabla manual
	$query_ingreso = "INSERT INTO ma_manual (
	  url_manual
	, titulo
	, estado
	) VALUES (
	  '$url_manual_nuevo'
	, '$titulo'
	, '1'
	)";
	mysql_query($query_ingreso);

	//peticion del ultimo producto en tabla producto
	$query_max = "SELECT MAX(idma_manual) as idma_manual FROM ma_manual";
	$rs_max = mysql_fetch_assoc(mysql_query($query_max));
			

	// ABRIR VENTANA EDITAR CATEGORIA:
	echo "<script>window.open('ma_manual_editar.php?idma_manual=".$rs_max['idma_manual']."','_self');</script>";

};

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
	if (formulario.titulo.value == ""){
		alert("Debe ingresar el título de la nueva sección.");
	} else {	
		formulario.accion.value = 'insertar';
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Manual - Nuevo</td>
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
                          <td bgcolor="#FFDDBC" class="titulo_medio_bold">Seleccione una categor&iacute;a a la que pertenecer&aacute; la secci&oacute;n:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right" class="detalle_medio_bold">URL</td>
                                <td align="left"><?= $url_manual_nuevo ?></td>
                              </tr>
                              <tr>
                                <td width="110" align="right" class="detalle_medio_bold">T&iacute;tulo</td>
                                <td align="left"><input name="titulo" type="text" id="titulo" size="27" value="<?=$titulo?>" /></td>
                              </tr>
                              <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22222" type="button" class="" onclick="mod6_validarRegistro();" value="  &gt;&gt;  Ingresar     " />
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