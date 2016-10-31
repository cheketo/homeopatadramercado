<? include ("../../0_mysql.php"); 

// localizacion de variables get y post:
$url_manual_nuevo = $_GET['url_manual_nuevo'];
$accion = $_POST['accion'];
$url_manual = $_POST['url_manual'];
$titulo = $_POST['titulo'];
$detalle = $_POST['detalle'];

if($_GET['idma_manual']){
	$idma_manual = $_GET['idma_manual'];
}else{
	$idma_manual = $_POST['idma_manual'];
}

// Ingreso del titular:
if($accion == "modificar"){

	//ingreso de datos en tabla producto
	$query_modficacion = "UPDATE ma_manual SET 
	  url_manual='$url_manual'
	, titulo='$titulo'
	, detalle='$detalle'
	WHERE idma_manual = '$idma_manual'
	LIMIT 1";	
	mysql_query($query_modficacion);
	
	echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idma_manual=$idma_manual');</script>";

};
	if($url_manual_nuevo){
		$url_fil = "WHERE A.url_manual = '$url_manual_nuevo'";
	}else{
		$url_fil = "WHERE A.idma_manual = '$idma_manual'";
	}
	
	$query_manual = "SELECT A.*
	FROM ma_manual  A
	$url_fil";	
	$result_manual = mysql_query($query_manual);
	$rs_manual = mysql_fetch_assoc($result_manual);
	
	$idma_manual = $rs_manual['idma_manual'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function mod6_validarRegistro(){
	formulario = document.form;
	if (formulario.url_manual.value == ""){
		alert("Debe ingresar la URL a la cual pertenece la ayuda del manual.");	
	} else if (formulario.titulo.value == ""){
		alert("Debe ingresar el título.");
	} else {	
		formulario.accion.value = 'modificar';
		formulario.submit();
	};
};
</script>

<!-- TinyMCE editor-->
<script language="javascript" type="text/javascript" src="../../js/tiny_mce/tiny_mce_gzip.js"></script>
<!-- Compresor -->
<script type="text/javascript">
tinyMCE_GZ.init({
	plugins : 'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,'+ 
        'searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
	themes : 'simple,advanced',
	languages : 'en',
	disk_cache : true,
	debug : false
});
</script>
<!-- Inicio y config del editor -->
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	plugins : "table,advhr,advimage,advlink,iespell,preview,zoom,flash,searchreplace,print,contextmenu",
	theme_advanced_buttons1_add_before : "separator",
	theme_advanced_buttons1_add : "fontselect,fontsizeselect",
	theme_advanced_buttons2_add : "separator,preview,zoom,separator,forecolor,backcolor",
	theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
	theme_advanced_buttons3_add_before : "tablecontrols,separator",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",

	content_css : "../../js/tiny_mce/0_global.css",
	editor_selector : "con",
	editor_deselector : "sin"
});
</script>
<!-- TinyMCE editor END -->

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
                <td height="40" valign="bottom" class="titulo_grande_bold">Manual - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFDDBC" class="titulo_medio_bold">Datos del Manual :<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                            <span class="detalle_chico" style="color:#FF0000">
                            <input name="idma_manual" type="hidden" id="idma_manual" value="<?= $rs_manual['idma_manual'] ?>" />
                          </span></span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="left" valign="top" class="detalle_medio_bold">URL</td>
                                <td align="left"><input name="url_manual" type="text" id="url_manual" size="70" value="<?= $rs_manual['url_manual'] ?>" /></td>
                              </tr>
                              <tr>
                                <td width="110" align="left" valign="top" class="detalle_medio_bold">T&iacute;tulo</td>
                                <td align="left"><input name="titulo" type="text" id="titulo" size="70" value="<?= $rs_manual['titulo'] ?>" /></td>
                              </tr>
                              <tr>
                                <td width="110" align="left" valign="top" class="detalle_medio_bold">Detalle</td>
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio_bold"><span class="detalle_medio_bold">
                                  <textarea name="detalle" rows="20" id="detalle" style="width:70%" class="con"><?= $rs_manual['detalle'] ?>
                                  </textarea>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22222" type="button" class="" onclick="mod6_validarRegistro();" value="  &gt;&gt; Modificar     " />
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