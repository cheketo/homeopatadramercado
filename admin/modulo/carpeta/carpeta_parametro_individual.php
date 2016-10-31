<? 
	include ("../../0_mysql.php"); 

	//VARIABLES
	$idcarpeta = $_GET['idcarpeta'];
	$plantilla_seccion = $_POST['plantilla_seccion'];
	$plantilla_producto = $_POST['plantilla_producto'];
	$habilita_comentario_defecto = $_POST['habilita_comentario_defecto'];
	$idca_iva = $_POST['idca_iva'];
	
	$accion = $_POST['accion'];

	//INGRESO:
	if($accion == "modificar"){
		
		$query = "UPDATE carpeta SET
		  plantilla_seccion = '$plantilla_seccion'
		, plantilla_producto = '$plantilla_producto'
		, habilita_comentario_defecto = '$habilita_comentario_defecto'
		, idca_iva = '$idca_iva'
		WHERE idcarpeta = '$idcarpeta' ";
		mysql_query($query);
		
		$accion = "";
	
	};
	
	//CARGO DATOS DE CARPETA
	$query_car = "SELECT nombre
	FROM carpeta_idioma_dato
	WHERE idcarpeta = '$idcarpeta' ";
	$rs_carpeta = mysql_fetch_assoc(mysql_query($query_car));
	
	//CARGO PLANTILLAS
	$query_par = "SELECT plantilla_seccion, plantilla_producto, habilita_comentario_defecto, idca_iva
	FROM carpeta
	WHERE idcarpeta = '$idcarpeta' ";
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
		mode : "textareas",
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Carpeta: <font color="#003399"><?= $rs_carpeta['nombre'] ?></font> <br />
                  <span class="detalle_medio">Par&aacute;metros </span></td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Par&aacute;matros individuales:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio_bold">Plantilla de secci&oacute;n: </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio">
                                  <textarea name="plantilla_seccion" rows="18" id="plantilla_seccion" style="width:100%"><?= $rs_parametro['plantilla_seccion'] ?></textarea>                                </td>
                              </tr>
                              <tr>
                                <td width="60%" align="left" valign="middle" class="detalle_medio">Habilita los comentarios por defecto en las secciones de esta carpeta: </td>
                                <td width="40%" align="left" valign="middle" class="detalle_medio"><label>
                                  <input name="habilita_comentario_defecto" type="radio" value="1" <? if($rs_parametro['habilita_comentario_defecto'] == 1){ echo "checked";} ?> />
                                  Si
                                  <input name="habilita_comentario_defecto" type="radio" value="2" <? if($rs_parametro['habilita_comentario_defecto'] == 2){ echo "checked";} ?> />
                                No</label></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right" valign="middle" class="detalle_medio">
                                  <input name="Submit222222" type="button" class="detalle_medio_bold buttons" onclick="javascript:parametro();" value="  &gt;&gt; Guardar     " />                                </td>
                              </tr>
                          </table>
                            <table width="100%" height="50" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio_bold">Plantilla de producto: </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio"><span class="detalle_medio_bold">
                                  <textarea name="plantilla_producto" rows="18" id="plantilla_producto" style="width:100%"><?= $rs_parametro['plantilla_producto'] ?></textarea>
                                </span></td>
                              </tr>
                              <tr>
                                <td width="60%" align="left" valign="middle" class="detalle_medio">Posicion del IVA por defecto para todos los productos de esta carpeta: </td>
                                <td width="40%" align="left" valign="middle" class="detalle_medio"><select name="idca_iva" id="idca_iva" class="detalle_medio">
                                  <option value="0" <? if(0 == $rs_producto['idca_iva']){ echo "selected"; } ?>></option>
                                  <? 
							  $query_iva = "SELECT * FROM ca_iva WHERE estado = 1";
							  $result_iva = mysql_query($query_iva);
							  while($rs_iva = mysql_fetch_assoc($result_iva)){
							  ?>
                                  <option value="<?= $rs_iva['idca_iva'] ?>" <? if($rs_iva['idca_iva'] == $rs_parametro['idca_iva']){ echo "selected"; } ?>>
                                    <?= $rs_iva['titulo_iva'] ?>
                                  </option>
                                  <? } ?>
                                </select></td>
                              </tr>
                            </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td align="right" valign="top"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22222" type="button" class="detalle_medio_bold buttons" onclick="javascript:parametro();" value="  &gt;&gt; Guardar     " />
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