<? 
	include ("../../0_mysql.php"); 

	// localizacion de variables get y post:
	$idcarpeta = $_GET['idcarpeta'];
	$forma = $_GET['forma'];	
	$ididioma = $_GET['ididioma'];
	$nombre = $_POST['nombre'];
	$breve_descripcion = str_replace(chr(13), "<br>", addslashes($_POST['breve_descripcion']));
	$detalle = htmlentities(stripslashes($_POST['detalle']), ENT_QUOTES);
	$accion = $_POST['accion'];	
	
	//ACTUALIZO FECHA MODIFICACION
	if($accion != ""){
		$fecha_hoy = date("Y-m-d");
		$query_mod_carpeta = "UPDATE carpeta SET fecha_modificacion = '$fecha_hoy' WHERE idcarpeta = '$idcarpeta' ";
		mysql_query($query_mod_carpeta);
	}
		
	// modificacion de la carpeta:
	if($accion == "modificar"){
	
		$query_modficacion = "UPDATE carpeta_idioma_dato SET 
		  nombre='$nombre'
		, breve_descripcion='$breve_descripcion'
		, contenido='$detalle'
		WHERE idcarpeta = '$idcarpeta' AND ididioma = '$ididioma'
		LIMIT 1";	
		mysql_query($query_modficacion);
		
		//ACTUALIZO DATOS EN LA BARRA MENU
		$query_id = "SELECT idbarra_menu 
		FROM barra_menu
		WHERE id = '$idcarpeta' AND tipo = '1' ";
		$rs_id = mysql_fetch_assoc(mysql_query($query_id)); //OBTENGO ID
		
		$query_modficacion = "UPDATE barra_menu_idioma SET 
		titulo='$nombre'
		WHERE idbarra_menu = '$rs_id[idbarra_menu]' AND ididioma = '$ididioma'
		LIMIT 1";	
		mysql_query($query_modficacion);
		
		
		/*
		echo "<script>window.opener.location.reload();</script>";
		echo "<script>window.close()</script>";
		*/
		
		echo "<script>window.open('carpeta_editar.php?idcarpeta=".$idcarpeta."&forma=".$forma."','_self');</script>";	
				
	};
	
	//NOMBRE IDIOMA
	$query_idioma = "SELECT titulo_idioma
	FROM idioma
	WHERE ididioma = '$ididioma'";
	$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM carpeta_parametro
	WHERE idcarpeta_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
	//obtener datos de la categoria actual:
	$query_carpeta = "SELECT nombre, breve_descripcion, contenido
	FROM carpeta_idioma_dato
	WHERE idcarpeta = '$idcarpeta' AND ididioma = '$ididioma'";
	$rs_carpeta = mysql_fetch_assoc(mysql_query($query_carpeta));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_form(){
		formulario = document.form_datos;
	
		if (formulario.nombre.value == "") {
			alert("Debe ingresar un nombre para la carpeta.");
			formulario.nombre.focus();
		} else {
			formulario.accion.value = "modificar";
			formulario.submit();
		};
	};
	
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
		elements : "detalle",
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
		content_css : "../../../skin/index/css/0_fonts_tiny.css",

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
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Carpeta - Modificar en Idioma: <font color="#003399"><?= $rs_idioma['titulo_idioma']; ?></font></td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_datos" id="form_datos">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Datos de la carpeta:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="107" align="left" class="detalle_medio">Nombre:</td>
                                <td width="551" align="left"><input name="nombre" type="text" class="detalle_medio text_field_01" id="nombre" value="<?= $rs_carpeta['nombre'] ?>" style="width:98%" size="50"></td>
                              </tr>
							  <? if($rs_parametro['estado_breve_descripcion'] == 1){ ?>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio">Breve descripc&oacute;n: </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio"><textarea name="breve_descripcion" rows="6" class="detalle_medio text_areas_01" id="breve_descripcion" style="width:97%"><?= stripslashes($rs_carpeta['breve_descripcion']) ?></textarea></td>
                              </tr>
							  <? } ?>
							  <? if($rs_parametro['estado_contenido'] == 1){ ?>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio">Detalle:</td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio_bold"><span class="detalle_medio_bold">
                                  <textarea name="detalle" rows="15" id="detalle" style="width:100%" ><?= html_entity_decode($rs_carpeta['contenido'], ENT_QUOTES);  ?></textarea>
                                </span></td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td height="10" colspan="2" align="right" valign="top" class="detalle_medio_bold"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                              <? if($rs_categoria['foto']){ 
							  		$medidas_foto = getimagesize($ruta_foto.$rs_categoria['foto']);
							  ?>
                              <? }; ?>
                          </table></td>
                        </tr>
                    </table>
                  </form>
                      <script language="JavaScript" type="text/javascript">
	function validar_barra(){
		formulario = document.form_barra;
		if(formulario.link_tipo.value == ''){
			alert("Debe seleccionar un tipo link");
		}else{
			formulario.accion.value = "modificar_barra";
			formulario.submit();		
		};
	};

	function validar_link_tipo(){
		formulario = document.form_barra;
		switch ( formulario.link_tipo.value ){
			case "1":
				window.form_barra.document.getElementById("link_especifico").style.visibility = "hidden";
				break;
			
			case "2":
				window.form_barra.document.getElementById("link_especifico").style.visibility = "hidden";
				break;
			
			case "3":
				window.form_barra.document.getElementById("link_especifico").style.visibility = "visible";
				break;
			
			default:
				window.form_barra.document.getElementById("link_especifico").style.visibility = "hidden";
				break;
		}
	};

function eliminar_foto_barra(){
	formulario = document.form_barra;
	if (confirm('&iquest; Esta seguro que desea eliminar la foto ?')){
		formulario.accion.value = "eliminar_foto_barra";
		formulario.submit();
	}

}
                </script></td>
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