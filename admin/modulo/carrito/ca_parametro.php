<? 
	include ("../../0_mysql.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	//VARIABLES
	$deposito = $_POST['deposito'];
	$dineromail = $_POST['dineromail'];
	$giropostal = $_POST['giropostal'];
	$pagomiscuentas = $_POST['pagomiscuentas'];
	$transferencia = $_POST['transferencia'];
	$pagofacil = $_POST['pagofacil'];
	$rapipago = $_POST['rapipago'];
	
	$texto_00 = $_POST['texto_00'];
	$texto_01 = $_POST['texto_01'];
	$texto_02 = $_POST['texto_02'];
	$texto_03 = $_POST['texto_03'];
	$texto_04 = $_POST['texto_04'];
	$info_adicional = $_POST['info_adicional'];
	
	$avisos_mail_usar = $_POST['avisos_mail_usar'];
	$aviso_mail = $_POST['aviso_mail'];
	
	$ca_carrito_usar = $_POST['ca_carrito_usar'];
	$accion = $_POST['accion'];

	//INGRESO:
	if($accion == "modificar"){
		
		$query = "UPDATE dato_sitio SET
		ca_carrito_usar = '$ca_carrito_usar' ";
		mysql_query($query);
		
		$query = "UPDATE ca_parametro SET 
		  form_deposito = '$deposito'
		, form_dineromail = '$dineromail'
		, form_giropostal = '$giropostal'
		, form_pagomiscuentas = '$pagomiscuentas'
		, form_transferencia = '$transferencia'
		, form_rapipago = '$rapipago'
		, form_pagofacil = '$pagofacil'
		, texto_00 = '$texto_00'
		, texto_01 = '$texto_01'
		, texto_02 = '$texto_02'
		, texto_03 = '$texto_03'
		, texto_04 = '$texto_04'
		, info_adicional = '$info_adicional'
		, avisos_mail_usar = '$avisos_mail_usar'
		, aviso_mail = '$aviso_mail'
		WHERE idca_parametro = '1' ";
		mysql_query($query);
		
		$accion = "";
	
	};
	
	//CARGO PARÁMETROS DE CARRITO
	$query_par = "SELECT ca_carrito_usar
	FROM dato_sitio
	WHERE iddato_sitio = 1";
	$rs_parametro_sitio = mysql_fetch_assoc(mysql_query($query_par));
	
	$query_parametro = "SELECT *
	FROM ca_parametro
	WHERE idca_parametro = '1' ";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_parametro));
	
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
<!-- TinyMCE -->
<script type="text/javascript" src="../../js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

	var tinyMCEImageList = new Array(
	);

	// O2k7 skin (silver)
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "info_adicional",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "../../../css/0_fonts_tiny.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : tinyMCEImageList,
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});

</script>
<!-- /TinyMCE -->
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Carrito - Par&aacute;metros </td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Par&aacute;matros configurables del carrito:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                          <td align="right" bgcolor="#FFDDBC" class="titulo_medio_bold"><span class="detalle_chico" style="color:#FF0000">
                            <input name="Submit222222" type="button" class="detalle_medio_bold" onclick="javascript:parametro();" value="  &gt;&gt; Guardar     " />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td colspan="2" bgcolor="#FFF0E1"><table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="28%" align="right" valign="middle" class="detalle_medio"><strong>Configuraci&oacute;n general </strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio"></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Utiliza Carrito de compras: </td>
                                <td width="15%" valign="middle" class="detalle_medio"><input name="ca_carrito_usar" type="radio" class="detalle_medio" value="1" <? if($rs_parametro_sitio['ca_carrito_usar'] == 1){ echo "checked"; } ?> />
Si
  <input name="ca_carrito_usar" type="radio" class="detalle_medio" value="2" <? if($rs_parametro_sitio['ca_carrito_usar'] == 2){ echo "checked"; } ?> />
No </td>
                                <td width="57%" align="left" valign="middle" class="detalle_medio_bold"><span style="color:#FF0000">IMPORTANTE</span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Recibir avisos por mail: </td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="avisos_mail_usar" type="radio" class="detalle_medio" value="1" <? if($rs_parametro['avisos_mail_usar'] == 1){ echo "checked"; } ?> />
Si
  <input name="avisos_mail_usar" type="radio" class="detalle_medio" value="2" <? if($rs_parametro['avisos_mail_usar'] == 2){ echo "checked"; } ?> />
No </td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">E-mail para recibir avisos:</td>
                                <td colspan="2" valign="middle" class="detalle_medio"><label>
                                <input name="aviso_mail" type="text" class="detalle_medio" id="aviso_mail" value="<?= $rs_parametro['aviso_mail'] ?>" size="50" />
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio"><strong>Formularios: Informes de pago </strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Dep&oacute;sito bancario: </td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="deposito" type="checkbox" <? if($rs_parametro["form_deposito"] == 1){ echo "checked"; } ?> id="deposito" value="1" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Dinero Mail: </td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="dineromail" type="checkbox" <? if($rs_parametro["form_dineromail"] == 1){ echo "checked"; } ?> id="dineromail" value="1" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Giro Postal: </td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="giropostal" type="checkbox" <? if($rs_parametro["form_giropostal"] == 1){ echo "checked"; } ?> id="giropostal" value="1" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Pago Mis Cuentas: </td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="pagomiscuentas" type="checkbox" <? if($rs_parametro["form_pagomiscuentas"] == 1){ echo "checked"; } ?> id="pagomiscuentas" value="1" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Transferencia bancaria: </td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="transferencia" type="checkbox" <? if($rs_parametro["form_transferencia"] == 1){ echo "checked"; } ?> id="transferencia" value="1" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">RapiPago:</td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="rapipago" type="checkbox" <? if($rs_parametro["form_rapipago"] == 1){ echo "checked"; } ?> id="rapipago" value="1" /></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Pago F&agrave;cil:</td>
                                <td colspan="2" valign="middle" class="detalle_medio"><input name="pagofacil" type="checkbox" <? if($rs_parametro["form_pagofacil"] == 1){ echo "checked"; } ?> id="pagofacil" value="1" /></td>
                              </tr>
                              <tr>
                                <td height="15" colspan="3" align="right" valign="middle" class="detalle_medio"></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio"><strong>Textos informativos y legales </strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Detalle de Carrito <strong>.00</strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio">
                                  <textarea name="texto_00" class="detalle_medio" style="width:99%; height:80px;"><?= $rs_parametro["texto_00"] ?></textarea>                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Datos de   env&iacute;o<strong> .01</strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio"><textarea name="texto_01" class="detalle_medio" style="width:99%; height:80px;"><?= $rs_parametro["texto_01"] ?></textarea></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio"> Datos de facturaci&oacute;n<strong> .02</strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio"><textarea name="texto_02" class="detalle_medio" style="width:99%; height:80px;"><?= $rs_parametro["texto_02"] ?></textarea></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Pagar <strong>.03</strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio"><textarea name="texto_03" class="detalle_medio" style="width:99%; height:80px;"><?= $rs_parametro["texto_03"] ?></textarea></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Terminar la compra<strong> .04</strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio"><textarea name="texto_04" class="detalle_medio" style="width:99%; height:80px;"><?= $rs_parametro["texto_04"] ?></textarea></td>
                              </tr>
                              <tr>
                                <td height="15" colspan="3" align="right" valign="middle" class="detalle_medio"></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio"><strong>Informaci&oacute;n adicional </strong></td>
                                <td colspan="2" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="middle" class="detalle_medio"><span class="detalle_medio_bold">
                                  <textarea name="info_adicional" rows="30" cols="80" id="info_adicional" style="width:100%" ><?= str_replace('src="imagen','src="../../../imagen', html_entity_decode($rs_parametro['info_adicional'], ENT_QUOTES)); ?></textarea>
                                </span></td>
                              </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="29%" align="right" valign="top">&nbsp;</td>
                                <td width="71%" align="right"><span class="detalle_chico" style="color:#FF0000">
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