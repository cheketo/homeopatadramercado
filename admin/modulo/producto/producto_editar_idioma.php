<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
include ("../0_includes/0_clean_string.php"); 

	// localizacion de variables get y post:
	$idproducto = $_GET['idproducto'];
	$idcarpeta = $_GET['idcarpeta'];
	$forma = $_GET['forma'];
	$ididioma = $_GET['ididioma'];
	$titulo = htmlentities($_POST['titulo'], ENT_QUOTES);
	$copete = str_replace(chr(13), "<br>", addslashes($_POST['copete']));
	$keywords = $_POST['keywords'];
	$detalle = htmlentities(str_replace(' src="../../../imagen', ' src="imagen',  stripslashes($_POST['detalle'])), ENT_QUOTES);
	$accion = $_POST['accion'];
	$titulo_web = clean_string($_POST['titulo_web']);
	
	//ACTUALIZO FECHA MODIFICACION
	if($accion != ""){
		$fecha_hoy = date("Y-m-d");
		$query_mod_prod = "UPDATE producto SET fecha_modificacion = '$fecha_hoy' WHERE idproducto = '$idproducto' ";
		mysql_query($query_mod_prod);
	}

		
// modificacion del producto:
if($accion == "modificar"){				
	//ingreso de datos en tabla producto
	$query_modficacion = "UPDATE producto_idioma_dato SET 
	  titulo='$titulo'
	, titulo_web='$titulo_web'
	, copete='$copete'
	, keywords='$keywords'	
	, detalle='$detalle'
	WHERE idproducto = '$idproducto' AND ididioma = '$ididioma'
	LIMIT 1";
	
	mysql_query($query_modficacion);
	
	//ACTUALIZO DATOS EN LA BARRA MENU
	$query_id = "SELECT idbarra_menu 
	FROM barra_menu
	WHERE id = '$idproducto' AND tipo = 3";
	$rs_id = mysql_fetch_assoc(mysql_query($query_id)); //OBTENGO ID
	
	$query_modficacion = "UPDATE barra_menu_idioma SET 
	titulo='$titulo'
	WHERE idbarra_menu = '$rs_id[idbarra_menu]' AND ididioma = '$ididioma'
	LIMIT 1";	
	mysql_query($query_modficacion);
	/*
	echo "<script>window.opener.location.reload();</script>";
	echo "<script>window.close()</script>";	
	*/
	
	/*echo "<script>window.open('producto_editar.php?idproducto=".$idproducto."&idcarpeta=".$idcarpeta."&forma=".$forma."','_self');</script>";		*/
};
	
	
	//NOMBRE Y ABREVIATURA DE IDIOMA
	$query_idioma = "SELECT titulo_idioma, reconocimiento_idioma
	FROM idioma
	WHERE ididioma = '$ididioma'";
	$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
	
	//obtener datos del producto actual:
	$query_seccion = "SELECT * 
	FROM producto_idioma_dato
	WHERE idproducto = '$idproducto' AND ididioma = '$ididioma'";
	$rs_seccion = mysql_fetch_assoc(mysql_query($query_seccion));
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM producto_parametro
	WHERE idproducto_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));


?>		

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">
	function validar_form(){
		formulario = document.form_datos;
	
		if (formulario.titulo.value == "") {
			alert("Debe ingresar el titulo.");
			formulario.titulo.focus();
		} else {
			formulario.accion.value = "modificar";
			formulario.submit();
		};
	};

	function copiar_campo(){
	
		var f = document.form_datos;
		if(f.titulo_web.value == ""){
			f.titulo_web.value = f.titulo.value;
		}
		if(f.titulo_web.value == f.titulo.value && f.titulo_web.value != ""){
			f.titulo_web.value = f.titulo.value;
		}

	};

</script>


<!-- TinyMCE -->
<script type="text/javascript" src="../../js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

	var tinyMCEImageList = new Array(
	<?
		$cont=0;
		$query = "SELECT foto, titulo
		FROM producto_foto
		WHERE idproducto = '$idproducto' AND ididioma = '$ididioma' AND foto_extra_tipo = 3";
		$result = mysql_query($query);
		while($rs_foto = mysql_fetch_assoc($result)){
			
			$cont++;
			if($cont == 1){
				echo ' ["'.$rs_foto['titulo'].' (Chica)", "../../../imagen/producto/extra_chica/'.$rs_foto['foto'].'"]';
				echo ',["'.$rs_foto['titulo'].' (Grande)", "../../../imagen/producto/extra_grande/'.$rs_foto['foto'].'"]';
			}else{
				echo ',["'.$rs_foto['titulo'].' (Chica)", "../../../imagen/producto/extra_chica/'.$rs_foto['foto'].'"]';
				echo ',["'.$rs_foto['titulo'].' (Grande)", "../../../imagen/producto/extra_grande/'.$rs_foto['foto'].'"]';
			}
		
		}
	?>
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


<style type="text/css">
<!--
.style1 {color: #000000;
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto - Modificar en Idioma: <font color="#003399">
                <?= $rs_idioma['titulo_idioma']; ?>
                </font></td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_datos" id="form_datos">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td width="53%" height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Datos del producto:
                          <input name="accion" type="hidden" id="accion" value="1" /></td>
                          <td width="47%" align="right" valign="middle" bgcolor="#d8f6ee" class="titulo_medio_bold"><span class="detalle_medio_bold">
                            <input name="Submit2222" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.location.href = 'producto_editar.php?idproducto=<?= $idproducto ?>&amp;idcarpeta=<?= $idcarpeta ?>&amp;forma=<?= $forma ?>';" value="   &raquo;  Volver    " />
                            <input name="Submit222" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="  &raquo;  Guardar    " />
                          </span></td>
                        </tr>
                        <tr>
                          <td colspan="2" bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="63" align="left" valign="middle" class="detalle_medio">Titulo:</td>
                                <td colspan="2" align="left"><input name="titulo" type="text" class="detalle_medio text_field_01" id="titulo" value="<?= $rs_seccion['titulo'] ?>" style="width:99%"   onkeyup="javascript:copiar_campo();"  /></td>
                              </tr>
							  <? if($rs_parametro['usar_titulo_web'] == 1){ ?>
                              <tr>
                                <td width="63" align="left" valign="middle" class="detalle_medio">Titulo Web:</td>
                                <td width="566" rowspan="2" align="left" valign="middle"><input name="titulo_web" type="text" class="detalle_medio text_field_01" id="titulo_web" value="<?= $rs_seccion['titulo_web'] ?>" style="width:99%" />
                                  <div class="div_comentario" style="width:98%"><span class="detalle_11px"><span class="style1">T&iacute;tulo Web:</span><br />
                                    El t&iacute;tulo web (o t&iacute;tulo url) es el nombre de la p&aacute;gina de este producto (es decir, el nombre archivo). Se permite editar este nombre ya que si el mismo contiene palabras claves, ser&aacute; mejor posicionado por los buscadores. Para crear el t&iacute;tulo web usted lo debe editar, y luego guardar para que el sistema lo adapte al formato permitido. </span></div></td>
                                <td width="19" height="33" align="right" valign="middle"><a href="javascript:copiar_campo();"><img src="../../imagen/copiar_titulo_url.jpg" alt="Copiar a: Titulo URL" width="19" height="19" border="0" /></a></td>
                              </tr>
                              <tr>
                                <td width="63" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td align="right" valign="middle">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="63" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td colspan="2" align="left"><?
									$query_sedes = "SELECT idsede
									FROM producto_sede
									WHERE idproducto = '$idproducto' AND idsede != 0
									LIMIT 1"; 
									$rs_sede = mysql_fetch_assoc(mysql_query($query_sedes));
									
									echo '<a href="'.$dominio.clean_string($rs_seccion['titulo_web']).'.'.$rs_seccion['ididioma'].'.'.$rs_sede['idsede'].'.p.'.$idcarpeta.'.'.$rs_seccion['idproducto'].'.htm'.'" target="_blank">';
									echo $dominio.clean_string($rs_seccion['titulo_web']).".".$rs_seccion['ididioma'].".".$rs_sede['idsede'].".p.".$idcarpeta.".".$rs_seccion['idproducto'].".htm"; 
									echo '</a>';
								?></td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio">Copete:</td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio"><textarea name="copete" rows="6" class="detalle_medio text_areas_01" id="copete" style="width:97%"><?= str_replace("<br>",chr(13),$rs_seccion['copete']); ?></textarea></td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio">Palabras Claves: </td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio"><textarea name="keywords" rows="4" class="detalle_medio text_areas_01"  style="width:97%" id="keywords"><?= $rs_seccion['keywords'] ?></textarea>
                                  <div class="div_comentario" style="width:98%"><span class="detalle_11px"><span class="style1">Keywords:</span><br />
                                    Son las palabres claves genericas, se usa para cuando no 
                                    
                                se especifican palabras claves especificas para un producto o seccion. Tiene que estar separado por comas, ej: auto, lancha, compra, etc. Es importante que no superen los 500 caracteres, o 20 palabras.</span></div></td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio">Detalle:</td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio_bold"><textarea name="detalle" rows="30" id="detalle" style="width:100%"><?= $rs_seccion['detalle'] ?></textarea></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr bgcolor="#D8F6EE" class="detalle_medio">
                          <td align="right" bgcolor="#D8F6EE" class="detalle_medio_bold"><input name="Submit22222" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.location.href = 'producto_editar.php?idproducto=<?= $idproducto ?>&amp;idcarpeta=<?= $idcarpeta ?>&amp;forma=<?= $forma ?>';" value="   &raquo;  Volver    " />
                          <input name="Submit22" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="  &raquo;  Guardar    " /></td>
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