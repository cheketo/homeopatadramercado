<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	include ("../0_includes/0_crear_miniatura.php"); 
	include ("../0_includes/0_clean_string.php"); 

	// localizacion de variables get y post:
	$idseccion = $_GET['idseccion'];
	$ididioma = $_GET['ididioma'];
	$idcarpeta = $_GET['idcarpeta'];
	$forma = $_GET['forma'];
	
	
	$titulo = htmlentities($_POST['titulo'], ENT_QUOTES);
	$copete = str_replace(chr(13), "<br>", addslashes($_POST['copete']));
	$banner = $_POST['banner'];
	$keywords = $_POST['keywords'];
	$detalle = htmlentities(str_replace(' src="../../../imagen', ' src="imagen',  stripslashes($_POST['detalle'])), ENT_QUOTES);
	$foto_banner = $_POST['foto_banner'];
	$accion = $_POST['accion'];
	$titulo_web = clean_string($_POST['titulo_web']);
	
	//ACTUALIZO FECHA MODIFICACION
	if($accion != ""){
		$fecha_hoy = date("Y-m-d");
		$query_mod_seccion = "UPDATE seccion SET fecha_modificacion = '$fecha_hoy' WHERE idseccion = '$idseccion' ";
		mysql_query($query_mod_seccion);
	}
	
	//Variables del banner
	$banner_ruta = "../../../imagen/seccion/banner/"; // la ruta donde se va a guardar el banner
	$banner_ancho = 500;// ancho maximo del banner
	
	//NOMBRE Y ABREVIATURA DE IDIOMA
	$query_idioma = "SELECT titulo_idioma, reconocimiento_idioma
	FROM idioma
	WHERE ididioma = '$ididioma'";
	$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));

	// modificacion del producto:
	if($accion == "modificar"){	
	
		// INCORPORACION DE BANNER
		if ($_FILES['foto_banner']['name'] != ''){
		
				$archivo_ext = substr($_FILES['foto_banner']['name'],-4);
				$archivo_nombre = substr($_FILES['foto_banner']['name'],0,strrpos($_FILES['foto_banner']['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
				
					$querysel = "SELECT banner FROM seccion_idioma_dato WHERE idseccion = '$idseccion' AND ididioma = '$ididioma'";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
					
					if ( $rowfoto[0] ){
						if (file_exists($banner_ruta.$rowfoto[0])){
							unlink ($banner_ruta.$rowfoto[0]);
						}
					}
	
					
				$foto =  $idseccion.'-'.$rs_idioma['reconocimiento_idioma'].'-'. rand(0,999).'-'.$archivo; //se captura el nombre del archivo de la foto
				
				if (!copy($_FILES['foto_banner']['tmp_name'], $banner_ruta . $foto)){ //si hay error en la copia de la foto
					$error = "Hubo un error al subir el banner. "; // se crea la variable error con un mensaje de error.
					echo "<script>alert('".$error."')</script>"; // se muestra el error.
				}else{
					$imagesize = getimagesize($banner_ruta.$foto);
				
					if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
						$peso = number_format((filesize($banner_ruta.$foto))/1024,2);
						
						if($peso==0){
							$error2 = "El banner fue subido incorrectamente. "; //se crea la variable error con un mensaje de error.
							echo "<script>alert('".$error2."')</script>"; // se muestra el error.
						}else{
						
							//SI TODO SALIO BIEN:
							
							//ACHICAR IMAGEN AL ANCHO MÁXIMO:
							if ($imagesize[0] > $banner_ancho){
								$alto_nuevo = ceil($banner_ancho * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($banner_ruta.$foto, $banner_ancho, $alto_nuevo, $banner_ruta);
							};
					
							//ingreso de foto en tabla producto
							$query_upd = "UPDATE seccion_idioma_dato SET 
							banner = '$foto' 	
							WHERE idseccion = '$idseccion' AND ididioma = '$ididioma'
							LIMIT 1";
							mysql_query($query_upd);
		
						};			
					
					}else{
					
						$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
						echo "<script>alert('".$error3."')</script>"; // se muestra el error.
						
						if(!unlink($ruta_foto.$foto)){ //se elimina el archivo subido
							$error4 = "El archivo no pudo elminarse. ";
							echo "<script>alert('".$error4."')</script>"; // se muestra el error.
						}else{
							$error5 = "El archivo fue elminado. ";
							echo "<script>alert('".$error5."')</script>"; // se muestra el error.
						};
					
					};
			
				};
			} // FIN INCORPORACION DE BANNER
						
		//ingreso de datos en tabla secion
		$query_modficacion = "UPDATE seccion_idioma_dato SET 
		  titulo='$titulo'
		, titulo_web = '$titulo_web'
		, copete='$copete'
		, detalle='$detalle'
		, keywords='$keywords'				
		WHERE idseccion = '$idseccion' AND ididioma = '$ididioma'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		//ACTUALIZO DATOS EN LA BARRA MENU
		$query_id = "SELECT idbarra_menu 
		FROM barra_menu
		WHERE id = '$idseccion' AND tipo = '2' ";
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
		/*echo "<script>window.open('seccion_editar.php?idseccion=".$idseccion."&idcarpeta=".$idcarpeta."&forma=".$forma."','_self');</script>";	*/
	};
	
	//ELIMINAR BANNER
	if ($accion == "eliminar_banner"){
				
					$querysel = "SELECT banner FROM seccion_idioma_dato WHERE idseccion = '$idseccion' AND ididioma = '$ididioma'";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
					
					if ( $rowfoto[0] ){
						if (file_exists($banner_ruta.$rowfoto[0])){
							unlink ($banner_ruta.$rowfoto[0]);
						}
					}
	
					$query_upd = "UPDATE seccion_idioma_dato SET
					banner = ''
					WHERE idseccion = '$idseccion' AND ididioma = '$ididioma'
					LIMIT 1";
					mysql_query($query_upd);
					
					echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idseccion=".$idseccion."&ididioma=".$ididioma."');</script>";
	}

	//obtener datos de la seccion actual:
	$query_seccion = "SELECT * 
	FROM seccion_idioma_dato
	WHERE idseccion = '$idseccion' AND ididioma = '$ididioma'";
	$rs_seccion = mysql_fetch_assoc(mysql_query($query_seccion));
	
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

<script language="javascript">

	function eliminar_banner(){
		formulario = document.form_datos;
		if (confirm('¿Está seguro que desea eliminar el banner?')){
			formulario.accion.value = "eliminar_banner";
			formulario.submit();
		}
	}
	
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
		FROM seccion_foto
		WHERE idseccion = '$idseccion' AND ididioma = '$ididioma' AND foto_extra_tipo = 3";
		$result = mysql_query($query);
		while($rs_foto = mysql_fetch_assoc($result)){
			
			$cont++;
			if($cont == 1){
				echo ' ["'.$rs_foto['titulo'].' (Chica)", "../../../imagen/seccion/extra_chica/'.$rs_foto['foto'].'"]';
				echo ',["'.$rs_foto['titulo'].' (Grande)", "../../../imagen/seccion/extra_grande/'.$rs_foto['foto'].'"]';
			}else{
				echo ',["'.$rs_foto['titulo'].' (Chica)", "../../../imagen/seccion/extra_chica/'.$rs_foto['foto'].'"]';
				echo ',["'.$rs_foto['titulo'].' (Grande)", "../../../imagen/seccion/extra_grande/'.$rs_foto['foto'].'"]';
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

<style type="text/css">
<!--
.style1 {	color: #000000;
	font-weight: bold;
}
-->
</style>
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Secci&oacute;n - Modificar en Idioma: <font color="#003399">
                  <?= $rs_idioma['titulo_idioma']; ?>
                </font></td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_datos" id="form_datos" >
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="35" bgcolor="#d8f6ee" class="titulo_medio_bold">Datos de la secci&oacute;n:
                          <input name="accion" type="hidden" id="accion" value="1" /></td>
                          <td align="right" bgcolor="#d8f6ee" class="titulo_medio_bold"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_medio_bold">
                            <input name="Submit2222" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.location.href = 'seccion_editar.php?idseccion=<?= $idseccion ?>&idcarpeta=<?= $idcarpeta ?>&forma=<?= $forma ?>';" value="   &raquo;  Volver    " />
                            <input name="Submit222" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="   &raquo;  Guardar    " />
                          </span></span></td>
                        </tr>
                        <tr >
                          <td colspan="2" bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="70" align="left" class="detalle_medio">Titulo:</td>
                                <td colspan="2" align="left"><input name="titulo" type="text" class="detalle_medio text_field_01" id="titulo" value="<?= $rs_seccion['titulo'] ?>" style="width:98%"  onchange="javascript:copiar_campo();" ></td>
                              </tr>
							  <? if($rs_parametro['usar_titulo_web'] == 1){ ?>
                              <tr>
                                <td width="70" height="33" align="left" valign="top" class="detalle_medio">Titulo Web:</td>
                                <td width="559" rowspan="2" align="left"><input name="titulo_web" type="text" class="detalle_medio text_field_01" id="titulo_web" value="<?= $rs_seccion['titulo_web'] ?>" style="width:98%" />
                                  <div class="div_comentario" style="width:97%"><span class="detalle_11px"><span class="style1">T&iacute;tulo Web:</span><br />
                                El t&iacute;tulo web (o t&iacute;tulo url) es el nombre de la p&aacute;gina de esta secci&oacute;n (es decir, el nombre archivo). Se permite editar este nombre ya que si el mismo contiene palabras claves, ser&aacute; mejor posicionado por los buscadores. Para crear el t&iacute;tulo web usted lo debe editar, y luego  guardarlo para que el sistema lo adapte al formato permitido. </span></div></td>
                                <td width="19" align="right" valign="top"><a href="javascript:copiar_campo();"><img src="../../imagen/copiar_titulo_url.jpg" alt="Copiar a: Titulo URL" width="19" height="19" border="0" /></a></td>
                              </tr>
                              <tr>
                                <td width="70" align="left" class="detalle_medio">&nbsp;</td>
                                <td align="right">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="left" class="detalle_medio">&nbsp;</td>
                                <td colspan="2" align="left">
								Link:
							    <?
									$query_sedes = "SELECT idsede
									FROM seccion_sede
									WHERE idseccion = '$idseccion' AND idsede != 0
									LIMIT 1"; 
									$rs_sede = mysql_fetch_assoc(mysql_query($query_sedes));
									
									echo '<a href="'.$dominio.clean_string($rs_seccion['titulo']).'_'.$rs_seccion['ididioma'].'_'.$rs_sede['idsede'].'_s_'.$idcarpeta.'_'.$rs_seccion['idseccion'].'.html'.'" target="_blank">';
									echo $dominio.clean_string($rs_seccion['titulo_web'])."_".$rs_seccion['ididioma']."_".$rs_sede['idsede']."_s_".$idcarpeta."_".$rs_seccion['idseccion'].".html"; 
									echo '</a>';
								?>								</td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td width="70" align="left" class="detalle_medio">Banner:</td>
                                <td colspan="2" align="left">
                                  <input name="foto_banner" type="file" class="detalle_medio  text_field_01" id="foto_banner" style="width:98%" size="99%" />                                </td>
                              </tr>
							  <? if($rs_seccion['banner'] != ""){ ?>
                              <tr>
                                <td width="70" align="right" class="detalle_medio">&nbsp;</td>
                                <td colspan="2" align="left"><table width="100" border="1" cellpadding="0" cellspacing="0" bordercolor="#BAEFE0">
                                  <tr>
                                    <td bgcolor="#BAEFE0" style="border:solid #BAEFE0 0px;"><table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                        <tr valign="middle" class="detalle_medio">
                                          <td height="23" align="right"><a style="color:#C61E00; font-size:10px; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif" href="javascript:eliminar_banner();">Eliminar</a></td>
                                          <td width="10" align="left"><a href="javascript:eliminar_banner();"><img src="../../imagen/cruz.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td style="border:solid #BAEFE0 1px;"><? $foto_seccion =& new obj0001(0,$banner_ruta,$rs_seccion['banner'],'','','','','','','','wmode=opaque',''); ?></td>
                                  </tr>
                                </table></td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio">Copete:</td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio"><textarea name="copete" cols="74" rows="6" wrap="virtual" class="detalle_medio text_areas_01" id="copete" style="width:97%"><?=  str_replace("<br>", chr(13), stripslashes($rs_seccion['copete'])); ?></textarea></td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio">Palabras claves: 
                                <label></label></td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio"><textarea name="keywords" rows="4" id="keywords" class="detalle_medio text_areas_01" style="width:97%"><?= $rs_seccion['keywords'] ?></textarea>
                                  <div class="div_comentario" style="width:98%"><span class="detalle_11px"><span class="style1">Keywords:</span><br />
Son las palabres claves genericas, se usa para cuando no 
                                  
                                se especifican palabras claves especificas para un producto o seccion. Tiene que estar separado por comas, ej: auto, lancha, compra, etc. Es importante que no superen los 500 caracteres, o 20 palabras.</span></div></td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio">Detalle</td>
                              </tr>
                              <tr>
                                <td colspan="3" align="left" valign="top" class="detalle_medio_bold"><textarea name="detalle" rows="30" cols="80" id="detalle" style="width:100%" ><?= str_replace('src="imagen','src="../../../imagen', html_entity_decode($rs_seccion['detalle'], ENT_QUOTES)); ?></textarea></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr bgcolor="#D8F6EE" class="detalle_medio">
                          <td align="right" bgcolor="#D8F6EE" class="detalle_medio_bold"><input name="Submit22222" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.location.href = 'seccion_editar.php?idseccion=<?= $idseccion ?>&amp;idcarpeta=<?= $idcarpeta ?>&amp;forma=<?= $forma ?>';" value="   &raquo;  Volver    " />
                          <input name="Submit22" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="   &raquo;  Guardar    " /></td>
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