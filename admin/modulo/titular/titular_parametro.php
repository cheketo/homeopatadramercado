<? 
	include ("../../0_mysql.php"); 
	
	//DESPLEGAR BOTON DE BARRA N°
	$desplegarbarra = 2;

	//VARIABLES
	$ancho_titular = $_POST['ancho_titular'];
	$alto_titular = $_POST['alto_titular'];
	$accion = $_POST['accion'];

	//INGRESO:
	if($accion == "modificar"){
		
		$query = "UPDATE titular_parametro SET
		  ancho = '$ancho_titular'
		, alto = '$alto_titular'
		WHERE idtitular_parametro = 1";
		mysql_query($query);
		
		$accion = "";
	
	};
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM titular_parametro
	WHERE idtitular_parametro = 1";
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
		var flag = true;
		
		if(esNumerico(f.ancho_titular.value) == false || esNumerico(f.alto_titular.value) == false){
			alert("El ancho y alto deben ser valores númericos.");
			flag = false;
		}
		
		if(flag == true){
			f.accion.value = "modificar";
			f.submit();
		}
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Titular - Par&aacute;metros </td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Par&aacute;matros configurables de los titulares:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right" valign="middle" class="detalle_medio">Ancho del titular :</td>
                                <td valign="middle" class="style2"><label>
                                  <input name="ancho_titular" type="text" class="detalle_medio" id="ancho_titular" value="<?= $rs_parametro['ancho'] ?>" size="4" />
                                px.</label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Alto del titular:</td>
                                <td valign="middle" class="style2"><input name="alto_titular" type="text" class="detalle_medio" id="alto_titular" value="<?= $rs_parametro['alto'] ?>" size="4" /> 
                                px. </td>
                              </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="18%" align="right" valign="top">&nbsp;</td>
                                <td width="82%"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22222" type="button" class="detalle_medio_bold" onclick="javascript:parametro();" value="  Guardar Cambios &raquo; " />
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