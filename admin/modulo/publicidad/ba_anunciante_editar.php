<? include ("../../0_mysql.php"); 

	// localizacion de variables get y post:
	$accion = $_POST['accion'];
	$nombre = $_POST['nombre'];
	$idba_anunciante = $_GET['idba_anunciante'];

	// Actualiza el nombre
	if($accion == "update"){	
			
		$query_ingreso = "UPDATE ba_anunciante 
		SET nombre = '$nombre'
		WHERE idba_anunciante = $idba_anunciante";
		mysql_query($query_ingreso);
		
	}

	//CONSULTA
	 $query_nombre = "SELECT * FROM ba_anunciante
	 WHERE idba_anunciante = $idba_anunciante
	 LIMIT 1";
	 $result_nombre = mysql_query($query_nombre);
	 $rs_nombre = mysql_fetch_assoc($result_nombre);

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

	if(formulario.nombre.value != ''){
		formulario.accion.value = "update";
		formulario.submit();
	}else{
		alert("Debe ingresar el nombre del anunciante.");
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Anunciante  - Editar </td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Edite anunciante <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          </span></td>
                        </tr>
                        <tr >
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td colspan="2" valign="top" class="detalle_medio" style="color:#666666">Edite el nombre del anunciante:</td>
                              </tr>
                              <tr>
                                <td width="90" valign="middle" class="detalle_medio">Anunciante:</td>
                                <td align="left" valign="middle" class="style2"><label>
                                  <input name="nombre" type="text" class="detalle_medio" id="nombre" value="<?= $rs_nombre['nombre']; ?>" size="60" />
                                </label></td>
                              </tr>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><input name="Submit222" type="button" class="detalle_medio_bold" onclick="validar_form_titular();" value=" &raquo; Guardar cambios " /></td>
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