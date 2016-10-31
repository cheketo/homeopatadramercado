<? 	
	//INCLUDES
	include ("../../0_mysql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$iduser_segmentacion = $_GET['iduser_segmentacion'];
	$titulo = $_POST['titulo'];
	$tipo = $_POST['tipo'];
		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE user_segmentacion SET
		  titulo='$titulo'
		, tipo = '$tipo'
		WHERE iduser_segmentacion = '$iduser_segmentacion'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		echo "<script>window.location.href=('usuario_web_segmentacion.php');</script>";
		
	
	};
		
	//CONSULTA DE PROVINCIA
	$query = "SELECT * 
	FROM user_segmentacion 
	WHERE iduser_segmentacion = '$iduser_segmentacion' ";
	$rs_provincia = mysql_fetch_assoc(mysql_query($query));
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_form_preguntas(){
	formulario = document.form_titular;
	
		 if(formulario.titulo.value == ''){
			alert("Debe ingresar el nombre");
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Web Segmentaci&oacute;n - Editar </td>
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
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Editar Segmentaci&oacute;n:
<input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="14%" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                <td width="86%" align="left" valign="top"><label>
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?= $rs_provincia['titulo'] ?>" size="60" />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="14%" align="right" valign="top" class="detalle_medio">Tipo:</td>
                                <td align="left" valign="top" class="detalle_medio">
                                  <? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                                  <label><input name="tipo" type="radio" value="2" <? if($rs_provincia['tipo'] == 2){ echo "checked"; } ?> />
Interna web. </label>
<? } ?>
<br />
                                  <label><input name="tipo" type="radio" value="1" <? if($rs_provincia['tipo'] == 1){ echo "checked"; } ?> /></label>
                                  Visible para usuarios web. <br />
                                 <label> <input name="tipo" type="radio" value="3" <? if($rs_provincia['tipo'] == 3){ echo "checked"; } ?> /></label>
                                  De origen. <br />
                               <label> <input name="tipo" type="radio" value="4" <? if($rs_provincia['tipo'] == 4){ echo "checked"; } ?> />Especiales. </label>
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form_preguntas();" value=" Modificar &raquo; " /></td>
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