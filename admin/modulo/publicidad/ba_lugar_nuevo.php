<? include ("../../0_mysql.php"); 

	// localizacion de variables get y post:
	$accion = $_POST['accion'];
	$nombre_lugar = $_POST['nombre_lugar'];
	
// Ingreso del lugar:
if($accion == "ingresar"){			
	$query_ingreso = "INSERT INTO ba_lugar (
	  nombre_lugar
	) VALUES (
	  '$nombre_lugar'
	)";
	mysql_query($query_ingreso);
	
		
	// REDIRECCIONAR A EDITAR ANUNCIANTE:
	$query_max = "SELECT MAX(idba_lugar) as idba_lugar FROM ba_lugar";
	$rs_max = mysql_fetch_assoc(mysql_query($query_max));
	echo "<script>window.location.href('ba_lugar_editar.php?idba_lugar=".$rs_max['idba_lugar']."');</script>";
};

?>				

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">
function validar_form_titular(){
	formulario = document.form_titular;

	if(formulario.nombre_lugar.value != ''){
		formulario.accion.value = "ingresar";
		formulario.submit();
	}else{
		alert("Debe ingresar el nombre del lugar.");
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Lugar - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese nuevo lugar <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          </span></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="115" align="right" valign="middle" class="detalle_medio">Nombre del lugar:</td>
                                <td width="541" align="left" valign="middle" class="style2"><label>
                                  <input name="nombre_lugar" type="text" class="detalle_medio" id="nombre_lugar" size="60" />
                                </label></td>
                              </tr>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><input name="Submit222" type="button" class="detalle_medio_bold" onclick="validar_form_titular();" value="  &gt;&gt;  Ingresar     " /></td>
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