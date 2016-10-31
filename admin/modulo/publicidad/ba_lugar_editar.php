<? include ("../../0_mysql.php"); ?>				

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	// localizacion de variables get y post:
	$accion = $_POST['accion'];
	$nombre_lugar = $_POST['nombre_lugar'];
	$alto = $_POST['alto'];
	$ancho = $_POST['ancho'];
	$peso_maximo = $_POST['peso_maximo'];
	
	$idba_lugar = $_GET['idba_lugar'];

	// Actualiza el nombre
	if($accion == "update"){	
			
		$query_ingreso = "UPDATE ba_lugar 
		SET nombre_lugar = '$nombre_lugar'
		, alto = '$alto'
		, ancho = '$ancho'
		, peso_maximo = '$peso_maximo'
		WHERE idba_lugar = '$idba_lugar' ";
		mysql_query($query_ingreso);
		
		echo "<script>window.location.href('ba_lugar_ver.php');</script>";
	}
	
	//CONSULTA
	$query_datos_lugar = "SELECT * 
	FROM ba_lugar
	WHERE idba_lugar = '$idba_lugar'
	LIMIT 1";
	$result_datos_lugar = mysql_query($query_datos_lugar);
	$rs_datos_lugar = mysql_fetch_assoc($result_datos_lugar);

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">
function validar_form_titular(){
	formulario = document.form_titular;

	if(formulario.nombre_lugar.value != '' && formulario.alto.value > 0 && formulario.ancho.value > 0 && formulario.peso_maximo.value > 0){
		formulario.accion.value = "update";
		formulario.submit();
	}else{
		alert("Debe completar todos los campos, siendo alto, ancho y peso mayor a cero.");
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Lugar - Editar</td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Editar lugar <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          </span></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="90" valign="middle" class="detalle_medio">Nombre lugar </td>
                                <td align="left" valign="middle" class="style2"><label>
                                  <input name="nombre_lugar" type="text" class="detalle_medio" id="nombre_lugar" value="<?= $rs_datos_lugar['nombre_lugar'] ?>" size="60" />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="90" valign="middle" class="detalle_medio">Alto</td>
                                <td align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="alto" type="text" class="detalle_medio" id="alto" value="<?= $rs_datos_lugar['alto'] ?>" size="6" />
                                  px</label></td>
                              </tr>
                              <tr>
                                <td width="90" valign="middle" class="detalle_medio">Ancho</td>
                                <td align="left" valign="middle" class="detalle_medio"><input name="ancho" type="text" class="detalle_medio" id="ancho" value="<?= $rs_datos_lugar['ancho'] ?>" size="6" />
                                  px </td>
                              </tr>
                              <tr>
                                <td width="90" valign="middle" class="detalle_medio">Peso Max. </td>
                                <td align="left" valign="middle" class="detalle_medio"><input name="peso_maximo" type="text" class="detalle_medio" id="peso_maximo" value="<?= $rs_datos_lugar['peso_maximo'] ?>" size="6" />
                                  kb</td>
                              </tr>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><input name="Submit222" type="button" class="detalle_medio_bold" onclick="validar_form_titular();" value=" &raquo; Guardar cambios " /></td>
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