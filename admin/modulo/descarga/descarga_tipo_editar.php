<? include ("../../0_mysql.php");

	$iddescarga_tipo = $_GET['iddescarga_tipo'];
	$accion = $_POST['accion'];
	$titulo = $_POST['titulo'];		
				
	if( $accion == "actualizar" ){
		
		$query_modficacion = "UPDATE descarga_tipo SET
		  titulo = '$titulo'
		WHERE iddescarga_tipo = '$iddescarga_tipo'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		echo "<script>window.location.href('descarga_tipo_ver.php');</script>";
	};

	$query_zona = "SELECT * 
	FROM descarga_tipo 
	WHERE iddescarga_tipo = '$iddescarga_tipo' ";
	$rs_zona = mysql_fetch_assoc(mysql_query($query_zona));
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>


<script language="javascript">

function validar_form_preguntas(){
formulario = document.form_titular;

	if(formulario.titulo.value == 0){
		alert("Debe ingresar un tipo de descarga");
	}else{
		formulario.accion.value = "actualizar";
		formulario.submit();
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Descarga tipo - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Editar descarga tipo:
                          <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <?
  		$query_pais = "SELECT *
		FROM pais
    	WHERE idpais = '$id_pais_actual'";
		$rs_pais = mysql_fetch_assoc(mysql_query($query_pais)); ?>
                              <?

  		$query_provincia = "SELECT *
		FROM pais_provincia
    	WHERE idpais_provincia = '$id_provincia_actual'";
		$rs_provincia = mysql_fetch_assoc(mysql_query($query_provincia)); 
		
?>
                              <?

		$query_provincia = "SELECT * FROM pais_provincia WHERE idpais_provincia = '$idpais_provincia' ";
		$rs_provincia = mysql_fetch_assoc(mysql_query($query_provincia));
		
?>
                              <tr>
                                <td width="19%" align="right" valign="top" class="detalle_medio">Tipo de descarga: </td>
                                <td width="81%" align="left" valign="top"><label>
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?= $rs_zona['titulo'] ?>" size="60">
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form_preguntas();" value="  Modificar »  " /></td>
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