<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

	$accion = $_POST['accion'];
	$confirmacion_usuario = $_POST['confirmacion_usuario'];
	$aprobacion_moderador = $_POST['aprobacion_moderador'];
	$mail_moderador = $_POST['mail_moderador'];
	$titulo = $_POST['titulo'];
	$mail_desde = $_POST['mail_desde'];

	//GUARDO LOS DATOS
	if ($accion == "guardar_cambios"){
	
		$query_upd = "UPDATE dato_sitio SET
		  reg_confirmacion_usuario = '$confirmacion_usuario'
		, reg_aprobacion_moderador = '$aprobacion_moderador'
		, reg_mail_moderador = '$mail_moderador'
		, reg_titulo = '$titulo'
		, reg_mail_desde = '$mail_desde'	
		WHERE iddato_sitio = '1'
		LIMIT 1";
		mysql_query($query_upd);
	
	}
		
	$query_dato_sitio = "SELECT A.reg_mail_moderador, A.reg_aprobacion_moderador , A.reg_confirmacion_usuario, A.reg_titulo, A.reg_mail_desde
	FROM dato_sitio A
	WHERE A.iddato_sitio = '1'";
	$rs_dato_sitio = mysql_fetch_assoc(mysql_query($query_dato_sitio));
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

	function guardar_cambios(){
		var formulario = document.form;
		var flag = true;
		var error = '';
		
		if(formulario.aprobacion_moderador.value == 1){
		
			if (formulario.mail_moderador.value == '')	{
				error = error + 'Debe el mail del moderador.\n';
				flag = false;		
			}else{
				
				if (formulario.mail_moderador.value.indexOf("@") == (-1)){
					error = error + 'Al mail del moderador le falta el @.\n';
					flag = false;
				}
				
				if (formulario.mail_moderador.value.indexOf(".") == (-1)){
					error = error + 'Al mail del moderador le falta la extension (ej: .com, .com.es).\n';
					flag = false;
				}
			}
		}
		

		if (formulario.mail_desde.value == '')	{
			error = error + 'Debe el mail de salida.\n';
			flag = false;		
		}else{
			
			if (formulario.mail_desde.value.indexOf("@") == (-1)){
				error = error + 'Al mail de salida le falta el @.\n';
				flag = false;
			}
			
			if (formulario.mail_desde.value.indexOf(".") == (-1)){
				error = error + 'Al mail de salida le falta la extension (ej: .com, .com.es).\n';
				flag = false;
			}
		}
		
		if(flag == true){
			formulario.accion.value = "guardar_cambios";
			formulario.submit();
		}else{
			alert(error);
		}
	};

</script>
<style type="text/css">
<!--
.style1 {
	color: #000000;
	font-weight: bold;
}
-->
</style>
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Configuraci&oacute;n - Sistema de registraci&oacute;n</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Etapas  de registraci&oacute;n:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="12%" rowspan="4" align="right" valign="top" class="detalle_medio"><img src="../../imagen/pasos_registracion.jpg" width="363" height="335" /></td>
                                <td width="642" height="50" align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="81" align="left" valign="top" class="detalle_11px">1. En este paso, le llega un mail al usuario solicitandole que haga click en un link para verificar que su mail sea correcto y este funcionando. <br />
                                    <br />
                                      <select name="confirmacion_usuario" class="detalle_11px" id="confirmacion_usuario" style="width:150px;">
                                        <option value="1" <? if($rs_dato_sitio['reg_confirmacion_usuario'] == 1){ echo 'selected';} ?>>Habilitado</option>
                                        <option value="2" <? if($rs_dato_sitio['reg_confirmacion_usuario'] == 2){ echo 'selected';} ?>>Deshabilitado</option>
                                      </select>
                                 </td>
                              </tr>
                              <tr>
                                <td height="74" align="left" valign="top" class="detalle_11px">2. Aqu&iacute; le llega un mail a un moderador del sitio para que determine la aprobaci&oacute;n o rechazo del usuario que intenta registrarse  <br />
                                  <br />
                                  <select name="aprobacion_moderador" class="detalle_11px" id="aprobacion_moderador" style="width:150px;">
                                    <option value="1" <? if($rs_dato_sitio['reg_aprobacion_moderador'] == 1){ echo 'selected';} ?>>Habilitado</option>
                                    <option value="2" <? if($rs_dato_sitio['reg_aprobacion_moderador'] == 2){ echo 'selected';} ?>>Deshabilitado</option>
                                  </select></td>
                              </tr>
                              <tr>
                                <td align="left" valign="top" class="detalle_11px">3. Por &uacute;ltimo, si el usuario fue dado de alta, le llega un mail de bienvenida. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22" type="button" class="detalle_medio_bold" onclick="guardar_cambios();" value="   Guardar &raquo;  " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Datos  parametrizables:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="111" align="right" valign="top" class="detalle_medio">Mail del Moderador: </td>
                                <td width="547" align="left"><input name="mail_moderador" type="text" class="detalle_medio" id="mail_moderador"  value="<?= $rs_dato_sitio['reg_mail_moderador']; ?>" style="width:98%" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Titulo:</td>
                                <td align="left"><input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?= $rs_dato_sitio['reg_titulo'] ?>" style="width:98%" />
                                    <div class="div_comentario"><span class="detalle_11px"><span class="style1">Titulo</span><br />
                                      Es el nombre que va a llevar el asunto de todos los mails de registraci&oacute;n. <br />
                                  </span></div></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Desde:</td>
                                <td align="left"><input name="mail_desde" type="text" class="detalle_medio" id="mail_desde" value="<?= $rs_dato_sitio['reg_mail_desde'] ?>" style="width:98%" />
                                    <div class="div_comentario"><span class="detalle_11px"><span class="style1">Desde</span><br />
                                      Es el mail que va a figurar como remitente (quien envia) del mail de registraci&oacute;n. <br />
                                  </span></div></td>
                              </tr>
                              
                              
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit222" type="button" class="detalle_medio_bold" onclick="guardar_cambios();" value="   Guardar &raquo;  " />
                                </span></td>
                              </tr>
                          </table>
                            </td>
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