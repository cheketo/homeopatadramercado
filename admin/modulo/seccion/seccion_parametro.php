<? 
	include ("../../0_mysql.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	//VARIABLES
	$usar_titulo_web = $_POST['usar_titulo_web'];
	$foto_chica = $_POST['foto_chica'];
	$foto_mediana = $_POST['foto_mediana'];
	$foto_grande = $_POST['foto_grande'];
	$foto_extra_chica = $_POST['foto_extra_chica'];
	$foto_extra_grande = $_POST['foto_extra_grande'];
	$usar_comentarios = $_POST['usar_comentarios'];
	$restringe_lectura = $_POST['restringe_lectura'];
	$restringe_escritura = $_POST['restringe_escritura'];
	$mail_moderador_defecto = $_POST['mail_moderador_defecto'];
	$estado_comentario_defecto = $_POST['estado_comentario_defecto'];
	$usa_rating = $_POST['usa_rating'];
	$rating_restringido = $_POST['rating_restringido'];
	
	$accion = $_POST['accion'];

	//INGRESO:
	if($accion == "modificar"){
		
		$query = "UPDATE seccion_parametro SET
		  foto_chica = '$foto_chica'
		, usar_titulo_web = '$usar_titulo_web'  
		, foto_mediana = '$foto_mediana'
		, foto_grande = '$foto_grande'
		, foto_extra_chica = '$foto_extra_chica'
		, foto_extra_grande = '$foto_extra_grande'
		, usa_rating = '$usa_rating'
		, rating_restringido = '$rating_restringido'
		, usar_comentarios = '$usar_comentarios'
		, restringe_lectura = '$restringe_lectura'
		, restringe_escritura = '$restringe_escritura'
		, mail_moderador_defecto = '$mail_moderador_defecto'
		, estado_comentario_defecto = '$estado_comentario_defecto'
		WHERE idseccion_parametro = 1";
		mysql_query($query);
		
		$accion = "";
	
	};
	
	//CARGO PARÁMETROS DE SECCION
	$query_par = "SELECT *
	FROM seccion_parametro
	WHERE idseccion_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
?>

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
                <td height="40" valign="bottom" class="titulo_grande_bold">Secci&oacute;n - Par&aacute;metros </td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Par&aacute;matros configurables de las secciones:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="29%" align="right" valign="middle" class="detalle_medio"><strong>Configuraci&oacute;n general </strong></td>
                                <td width="71%" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Utiliza Titulo URL: </td>
                                <td width="71%" valign="middle" class="detalle_medio"><input name="usar_titulo_web" type="radio" class="detalle_medio" value="1" <? if($rs_parametro['usar_titulo_web'] == 1){ echo "checked"; } ?> />
Si
  <input name="usar_titulo_web" type="radio" class="detalle_medio" value="2" <? if($rs_parametro['usar_titulo_web'] == 2){ echo "checked"; } ?> />
No </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Utiliza Star Rating: </td>
                                <td valign="middle" class="detalle_medio"><input name="usa_rating" type="radio" class="detalle_medio" value="1" <? if($rs_parametro['usa_rating'] == 1){ echo "checked"; } ?> />
Si
  <input name="usa_rating" type="radio" class="detalle_medio" value="2" <? if($rs_parametro['usa_rating'] == 2){ echo "checked"; } ?> />
No </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Restringir rating: </td>
                                <td valign="middle" class="detalle_medio"><input name="rating_restringido" type="radio" class="detalle_medio" value="1" <? if($rs_parametro['rating_restringido'] == 1){ echo "checked"; } ?> />
Si
  <input name="rating_restringido" type="radio" class="detalle_medio" value="2" <? if($rs_parametro['rating_restringido'] == 2){ echo "checked"; } ?> />
No </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio"><strong>Tama&ntilde;os Predeterminados </strong></td>
                                <td width="71%" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto chica: </td>
                                <td valign="middle" class="detalle_medio"><label>
                                  <input name="foto_chica" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_chica'] ?>" />
                                  <strong>px </strong>ancho. </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto mediana: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_mediana" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_mediana'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto grande: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_grande" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_grande'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto extra chica: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_extra_chica" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_extra_chica'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Foto extra grande: </td>
                                <td valign="middle" class="detalle_medio">
								<input name="foto_extra_grande" type="text" class="detalle_medio" style="width:60px;" value="<?= $rs_parametro['foto_extra_grande'] ?>" />
                                <strong>px </strong>ancho. </td>
                              </tr>
                              <tr>
                                <td height="10" colspan="2" align="right" valign="middle" class="detalle_medio"></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio"><strong> Par&aacute;metros de Comentarios</strong></td>
                                <td valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&iquest;Habilitar el uso de comentarios? </td>
                                <td valign="middle" class="detalle_medio"><select name="usar_comentarios" class="detalle_medio" id="usar_comentarios" style="width:150px;">
                                  <option value="1" <? if($rs_parametro['usar_comentarios'] == 1){ echo "selected"; } ?> >Habilitado</option>
                                  <option value="2" <? if($rs_parametro['usar_comentarios'] == 2){ echo "selected"; } ?> >Deshabilitado</option>
                                </select></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Restringe lectura mensajes: </td>
                                <td valign="middle" class="detalle_medio"><label>
                                <input name="restringe_lectura" type="radio" class="detalle_medio" value="1" <? if($rs_parametro['restringe_lectura'] == 1){ echo "checked"; } ?> />
                                Si
                                
                                <input name="restringe_lectura" type="radio" class="detalle_medio" value="2" <? if($rs_parametro['restringe_lectura'] == 2){ echo "checked"; } ?> /> 
                                No
</label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Restringe escritura mensajes: </td>
                                <td valign="middle" class="detalle_medio"><label>
                                  <input name="restringe_escritura" type="radio" class="detalle_medio" value="1" <? if($rs_parametro['restringe_escritura'] == 1){ echo "checked"; } ?> />
                                  Si
                                
                                  <input name="restringe_escritura" type="radio" class="detalle_medio" value="2" <? if($rs_parametro['restringe_escritura'] == 2){ echo "checked"; } ?> /> 
                                  No
</label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Mail por defecto de moderador: </td>
                                <td valign="middle" class="detalle_medio"><label>
                                  <input name="mail_moderador_defecto" type="text" class="detalle_medio" id="mail_moderador_defecto" style="width:300px;" value="<?= $rs_parametro['mail_moderador_defecto'] ?>" />
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Estado predeter. del mensajes: </td>
                                <td valign="middle" class="detalle_medio"><label>
                                  <select name="estado_comentario_defecto" class="detalle_medio" style="width:150px;">
                                    <option value="1" <? if($rs_parametro['estado_comentario_defecto'] == 1){ echo "selected"; } ?> >Habilitado</option>
                                    <option value="2" <? if($rs_parametro['estado_comentario_defecto'] == 2){ echo "selected"; } ?> >Deshabilitado</option>
                                    <option value="3" <? if($rs_parametro['estado_comentario_defecto'] == 3){ echo "selected"; } ?> >Sin Confirmar</option>
                                  </select>
                                </label></td>
                              </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="29%" align="right" valign="top">&nbsp;</td>
                                <td width="71%"><span class="detalle_chico" style="color:#FF0000">
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