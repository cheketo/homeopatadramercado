<? 
	include ("../../0_mysql.php");
	include ("../0_includes/0_newsletter_funciones.php");
	
	//VARIABLES
	$idne_newsletter = $_GET['idne_newsletter'];
	$accion = $_POST['accion'];
	$tipo = $_POST['tipo'];
	$idseccion = $_POST['idseccion'];
	$idproducto = $_POST['idproducto'];
	$contenido = $_POST['contenido'];
	$borrador = $_POST['borrador'];
	$nombre = $_POST['nombre'];
	
	$check_detalle = $_POST['check_detalle'];
	$check_foto = $_POST['check_foto'];
	$check_vinculo = $_POST['check_vinculo'];
	
	//Sistema de selector de carpeta
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];
	
	if($mod6_idcarpeta4){
		$mod6_sel_idcarpeta = $mod6_idcarpeta4;
	}else{
		if($mod6_idcarpeta3){
			$mod6_sel_idcarpeta = $mod6_idcarpeta3;
		}else{
			if($mod6_idcarpeta2){
				$mod6_sel_idcarpeta = $mod6_idcarpeta2;
			}else{
				if($mod6_idcarpeta){
					$mod6_sel_idcarpeta = $mod6_idcarpeta;
				}
			}	
		}	
	}
	
	if($mod6_sel_idcarpeta){
		$fecha = date("Y-m-d");
		$query_upd = "UPDATE ne_newsletter SET borrador = '$borrador'
		, contenido = '$contenido'
		, nombre_identificacion = '$nombre'
		, fecha_ultima_modificacion = '$fecha'
		WHERE idne_newsletter = '$idne_newsletter' ";
		mysql_query($query_upd);
	}
	
	//CONSULTA NEWSLETTER
	$query_newsletter = "SELECT * FROM ne_newsletter WHERE idne_newsletter = '$idne_newsletter'";
	$rs_newsletter = mysql_fetch_assoc(mysql_query($query_newsletter));
	
	//GUARDAR
	if($accion == "guardar"){
		
		$fecha = date("Y-m-d");
		$query_upd = "UPDATE ne_newsletter SET borrador = '$borrador'
		, contenido = '$contenido'
		, nombre_identificacion = '$nombre'
		, fecha_ultima_modificacion = '$fecha'
		WHERE idne_newsletter = '$idne_newsletter' ";
		mysql_query($query_upd);
		
	}
	
	//GENERAR CONTENIDO
	if($accion == "generar_contenido"){
	
		switch($tipo){
			
			case 1: //SECCION
				$query_seccion = "SELECT B.titulo, B.copete, A.foto, B.detalle, A.idseccion AS id
				FROM seccion A
				INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
				WHERE A.idseccion = '$idseccion' AND B.ididioma = '$rs_newsletter[ididioma]' ";
				$rs_contenido = mysql_fetch_assoc(mysql_query($query_seccion));
				$ruta_foto = $dominio_name."imagen/seccion/mediana/";
				$ruta_link = $dominio_name."seccion_detalle.php?idseccion=";
				break;
			
			case 2: //PRODUCTO
				$query_producto = "SELECT B.titulo, B.copete, A.foto, B.detalle, A.idproducto AS id
				FROM producto A
				INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto
				WHERE A.idproducto = '$idproducto' AND B.ididioma = '$rs_newsletter[ididioma]' ";
				$rs_contenido = mysql_fetch_assoc(mysql_query($query_producto));
				$ruta_foto = $dominio_name."imagen/producto/mediana/";
				$ruta_link = $dominio_name."producto_detalle.php?idproducto=";
				break;
				
		}
		
		if($check_foto == 1 && $rs_contenido['foto'] != ""){
			$ins_foto = '<table width="0" border="0" cellspacing="3" cellpadding="0" align="left">
			<td><img src="'.$ruta_foto.$rs_contenido['foto'].'" border="0" ></td>
			</tr>
			</table>';
		}else{
			$ins_foto = '';
		}
		
		$ins_borrador = $_POST['borrador'].'<table width="100%" border="0" cellspacing="0" cellpadding="4" >
					<tr height="30"  >
						<td style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000;" >'.$rs_contenido['titulo'].'</td>
					</tr>
					<tr>
						<td style=" font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;" >'.$ins_foto.$rs_contenido['copete'].'</td>
					</tr>';
		
		if($check_detalle == 1){
			$ins_borrador .= '<tr>
			<td style=" font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;" >'.$rs_contenido['detalle'].'</td>
			</tr>';
		}
					
		if($check_vinculo == 1){
			$ins_borrador .= '<tr>
			<td style=" font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#999999;" ><a href="'.$ruta_link.$rs_contenido['id'].'">ver más.</a></td>
			</tr>';
		}
					
				'</table>';
		
		$fecha = date("Y-m-d");
		$query_upd = "UPDATE ne_newsletter SET borrador = '$ins_borrador'
		, contenido = '$contenido'
		, fecha_ultima_modificacion = '$fecha'
		WHERE idne_newsletter = '$idne_newsletter' ";
		mysql_query($query_upd);
	
	}
	
	
	//CONSULTA NEWSLETTER
	$query_newsletter = "SELECT * FROM ne_newsletter WHERE idne_newsletter = '$idne_newsletter'";
	$rs_newsletter = mysql_fetch_assoc(mysql_query($query_newsletter));
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>
<!-- TinyMCE -->
<script type="text/javascript" src="../../js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

	var tinyMCEImageList = new Array(
	<?
		$cont=0;
		$query = "SELECT *
		FROM ne_foto
		WHERE estado = 1 AND idne_newsletter = '$idne_newsletter' 
		ORDER BY idne_newsletter DESC";
		$result = mysql_query($query);
		while($rs_foto = mysql_fetch_assoc($result)){
			
			$cont++;
			if($cont == 1){
				echo ' ["'.$rs_foto['nombre'].'", "../../../imagen/newsletter/'.$rs_foto['foto'].'"]';
			}else{
				echo ',["'.$rs_foto['nombre'].'", "../../../imagen/newsletter/'.$rs_foto['foto'].'"]';
			}
		
		}
	?>
	);
	
	// Titulo, URL, Description
	var tinyMCETemplateList = new Array(
	 ["Titulo Encabezado", "template/titulo_encabezado.htm", "Titulo grande para separar o agrupar contenidos."]
	,["Cuadro Formato 01", "template/cuadro_formato01.htm", "Cuadro con titulo, copete y vinculo para editar."]
	,["Cuadro Formato 02", "template/cuadro_formato02.htm", "Cuadro con titulo, copete, foto y vinculo para editar."]
	);

	// O2k7 skin (silver)
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "borrador",
		theme : "advanced",
		//skin : "o2k7",
		//skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,advhr,|,print,|,ltr,rtl",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "../../../css/0_fonts_tiny.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : tinyMCETemplateList,
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : tinyMCEImageList,
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
	
	
	// Titulo, URL, Description
	var tinyMCETemplateList2 = new Array(
	 ["Formato A", "template/newsletter_formatoA.htm", "2 Columnas: 65%+35%"]
	,["Formato B", "template/newsletter_formatoB.htm", "2 Columnas: 35%+65%"]
	,["Formato C", "template/newsletter_formatoC.htm", "2 Columnas: 50%+50%"]
	,["Formato D", "template/newsletter_formatoD.htm", "1 Columna"]
	);

	// O2k7 skin (silver)
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "contenido",
		theme : "advanced",
		skin : "o2k7",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,insertdate,inserttime,preview",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "../../../css/0_fonts_tiny.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : tinyMCETemplateList2,
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

<script language="javascript">
	function ir_pagina(pag){
		formulario = document.form;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function generar_contenido(){
		formulario = document.form;
		
		if(formulario.tipo.value == 1){
			
			if(formulario.idseccion.value == ''){
				alert("Por favor, seleccione la sección");
			}else{
				formulario.accion.value = "generar_contenido";
				document.getElementById('load_image').style.display = 'block';
				formulario.submit();
			}
			
		}
		
		if(formulario.tipo.value == 2){
			
			if(formulario.idproducto.value == ''){
				alert("Por favor, seleccione el producto");
			}else{
				formulario.accion.value = "generar_contenido";
				document.getElementById('load_image').style.display = 'block';
				formulario.submit();
			}
			
		}
		
		
	};
	
	function guardar(){
		formulario = document.form;
		formulario.accion.value = "guardar";
		formulario.submit();
	}
	
	function validar_form_lista(){
		formulario = document.form;
		formulario.pag.value = "1";
		formulario.submit();
	};
	
	function cambiar_estado(estado, id){
		formulario = document.form;
		
		formulario.estado.value = estado;
		formulario.idpais.value = id;
		formulario.submit();
	};
	
	function confirmar_eliminar(id){
		formulario = document.form;
		if (confirm('¿Esta seguro que desea eliminar este país?')){
			formulario.eliminar.value = id;
			formulario.submit();
		}
	};
	
	function str_replace(s1,s2,texto){
		return texto.split(s1).join(s2);
	}
	
	function mod6_select_idcarpeta(nivel){
		formulario = document.form;
		
		switch(nivel){
			case 1: 
			 formulario.mod6_idcarpeta2.value = "";
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 1");
			 break;
		
			case 2: 
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 2");
			 break;
			 
			case 3:
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 3");
			 break;
		}

		document.getElementById("load_image").style.display = "block";
		formulario.submit();
	}
	
	
	window.addEvent('domready', function(){

		//SLIDE
		var mySlide1 = new Fx.Slide('layer_ayuda'); mySlide1.hide();
		
		$('btn_ayuda').addEvent('click', function(e){
			e = new Event(e);
			mySlide1.toggle();
			e.stop();
		});
		
	});
</script>


<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:645px;
	top:619px;
	width:143px;
	height:19px;
	z-index:1;
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Newsletter - Editar  </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <form action="" method="post" name="form" id="form" enctype="multipart/form-data">
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#999999">
                        <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Datos Generales:
                        <input name="accion" type="hidden" id="accion" /></td>
                      </tr>
                      <tr bgcolor="#999999">
                        <td bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Fecha Creaci&oacute;n:</td>
                              <td width="562" align="left"><input name="fecha_creacion" style="width:100px;" disabled="disabled"  type="text" class="detalle_medio" id="fecha_creacion" value="<?= $rs_newsletter['fecha_creacion'] ?>" size="50" /></td>
                            </tr>

                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Nombre: </td>
                              <td align="left"><input name="nombre" style="width:99%"  type="text" class="detalle_medio" id="nombre" value="<?= $rs_newsletter['nombre_identificacion'] ?>" size="50" /></td>
                            </tr>

                            <tr>
                              <td width="96" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                              <td align="right"><span class="detalle_medio_bold">
                                <input name="Submit2222" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.location.href = 'ne_newsletter_ver.php';" value="   &raquo;  Volver    " />
                                <input name="Submit222" type="button" class="detalle_medio_bold buttons" onclick="guardar();" value="   &raquo;  Guardar    " />
                              </span></td>
                            </tr>
                        </table></td>
                      </tr>
                </table>
                <br />
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr>
                    <td height="40" bgcolor="#FF8282"><span class="titulo_medio_bold">Galer&iacute;as de fotos</span></td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFE1E1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="5%" rowspan="2" align="left" valign="top" class="detalle_11px"><a href="ne_newsletter_foto_particular.php?idne_newsletter=<?= $idne_newsletter ?>" target="_blank"><img src="../../imagen/iconos/image_next.png" width="24" height="24" border="0" /></a></td>
                        <td width="47%" height="25" align="left" valign="middle" class="detalle_11px"><a href="ne_newsletter_foto_particular.php?idne_newsletter=<?= $idne_newsletter ?>" target="_blank"><span class="detalle_11px" style=" text-decoration:none;"> Abrir <strong>Galeria de Imagenes</strong> de &eacute;ste newsletter.</span></a></td>
                        <td width="5%" rowspan="2" align="left" valign="top" class="detalle_11px"><a href="ne_newsletter_foto_ver.php?idne_newsletter=<?= $idne_newsletter ?>" target="_blank"><img src="../../imagen/iconos/image_next.png" width="24" height="24" border="0" /></a></td>
                        <td width="43%" height="25" align="left" valign="middle" class="detalle_11px"><a href="ne_newsletter_foto_ver.php?idne_newsletter=<?= $idne_newsletter ?>" target="_blank"><span class="detalle_11px" style=" text-decoration:none;">Abrir<strong> Galeria General</strong> con  im&aacute;genes varias. </span></a></td>
                      </tr>
                      <tr>
					  <?
					  
					  $query_gen = "SELECT COUNT(idne_foto) AS cant FROM ne_foto WHERE idne_newsletter = 0";
					  $rs_gen = mysql_fetch_assoc(mysql_query($query_gen));
					  
					  $query_act = "SELECT COUNT(idne_foto) AS cant FROM ne_foto WHERE idne_newsletter = $idne_newsletter";
					  $rs_act = mysql_fetch_assoc(mysql_query($query_act));
					  
					  ?>
                        <td height="25" align="left" valign="middle" class="detalle_11px">&raquo; Cantidad: <strong><?= $rs_act['cant'] ?></strong> fotos. </td>
                        <td width="43%" height="25" align="left" valign="middle" class="detalle_11px">&raquo; Cantidad: <strong><?= $rs_gen['cant'] ?></strong> fotos. </td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                <br />
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="#999999">
                    <td height="40" bgcolor="#FFECB3" class="titulo_medio_bold">Contenido - Generar cuadro: </td>
                  </tr>
                  <tr bgcolor="#999999">
                    <td align="left" bgcolor="#FFF5D7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                      <tr>
                        <td width="96" align="right" valign="middle" class="detalle_medio">Tipo de info:</td>
                        <td align="left"><label>
                          <select name="tipo" class="detalle_medio" id="tipo" style="width:200px;" onchange="document.getElementById('load_image').style.display = 'block';document.form.submit();">
                            <option value="0">- Selecciona Tipo</option>
                            <option value="1" <? if($tipo == 1){ echo "selected"; } ?>>Seccion</option>
                            <option value="2" <? if($tipo == 2){ echo "selected"; } ?>>Producto</option>
                          </select>
                        </label></td>
                        <td align="right"><img id="load_image" src="../../imagen/24-1.gif" width="24" height="24" style="display:none" /></td>
                      </tr>
					  
					  <? if($tipo != 0){ ?>
					  <? if($tipo == 1 || $tipo == 2){ ?>
                      <tr>
                        <td colspan="3" height="10" align="right" valign="middle" class="detalle_medio"><hr size="1" /></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle" class="detalle_medio"><strong>Carpeta: </strong></td>
                        <td colspan="2" align="left" valign="middle" class="style2"><span class="style10">
                          <select name="mod6_idcarpeta" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta" onchange="mod6_select_idcarpeta('1')">
                            <option value="" selected="selected">- Seleccionar Carpeta</option>
                            <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
	  {
	  	if ($mod6_idcarpeta == $rs_mod6_idcarpeta['idcarpeta'])
		{
			$sel = "selected";
		}else{
			$sel = "";
		}
?>
                            <option  <? echo $sel ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                            <?= $rs_mod6_idcarpeta['nombre'] ?>
                            </option>
                            <?  } ?>
                          </select>
                          <? if($mod6_idcarpeta3 == "" && $mod6_idcarpeta2  == "" && $mod6_idcarpeta == ""){ ?>
&nbsp;<img src="../../imagen/etiqueta_carpeta.png" width="160" height="20" />
<? } ?>
</span></td>
                      </tr>
					  <?
						
						  $query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre 
						  FROM carpeta A
						  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
						  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta'  AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
						  ORDER BY B.nombre";
						  $result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
						  $cant_carpeta2 = mysql_num_rows($result_mod6_idcarpeta2);
						  
						  if($cant_carpeta2 > 0){
						  
						  if($mod6_idcarpeta){
						  
						?>
                      <tr>
                        <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                        <td colspan="2" align="left" valign="middle" class="style2"><span class="style10">
						
                          <select name="mod6_idcarpeta2" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta2" onchange="mod6_select_idcarpeta('2');">
                            <option value="" selected="selected">- Seleccionar Carpeta</option>
                            <?
	  
								  while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2))	  
								  {
									if ($mod6_idcarpeta2 == $rs_mod6_idcarpeta2['idcarpeta'])
									{
										$sel2 = "selected";
									}else{
										$sel2 = "";
									}
							?>
                            <option  <? echo $sel2 ?> value="<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>">
                            <?= $rs_mod6_idcarpeta2['nombre'] ?>
                            </option>
                            <?  } ?>
                          </select>
                        
                        <? if($mod6_idcarpeta3 == "" && $mod6_idcarpeta2  == "" && $mod6_idcarpeta =! ""){ ?>
&nbsp;<img src="../../imagen/etiqueta_carpeta.png" width="160" height="20" />
<? } ?>
</span></td>
                      </tr>
					 	 <?  } ?>
					  <? }// FIN SI HAY CARPETAS ?>
					  <?
						
						  $query_mod6_idcarpeta3 = "SELECT A.idcarpeta, B.nombre 
						  FROM carpeta A
						  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
						  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta2' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
						  ORDER BY B.nombre";
						  $result_mod6_idcarpeta3 = mysql_query($query_mod6_idcarpeta3);
	 					  $cant_carpeta3 = mysql_num_rows($result_mod6_idcarpeta3);
						  
						  if($cant_carpeta3 > 0){
						  	if($mod6_idcarpeta2 && $mod6_idcarpeta){
					  ?>
                      <tr>
                        <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                        <td colspan="2" align="left" valign="middle" class="style2"><span class="style10">
						
                          <select name="mod6_idcarpeta3" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta3" onchange="mod6_select_idcarpeta('3')">
                            <option value="">- Seleccionar Carpeta</option>
                            <?
	  
								  while ($rs_mod6_idcarpeta3 = mysql_fetch_assoc($result_mod6_idcarpeta3)){
								  
									if ($mod6_idcarpeta3 == $rs_mod6_idcarpeta3['idcarpeta']){
										$sel3 = "selected";
									}else{
										$sel3 = "";
									}
							?>
                            <option  <? echo $sel3 ?> value="<?= $rs_mod6_idcarpeta3['idcarpeta'] ?>">
                            <?= $rs_mod6_idcarpeta3['nombre'] ?>
                            </option>
                            <?  } ?>
                          </select>
                          <? if($mod6_idcarpeta3 == "" && $mod6_idcarpeta2  && $mod6_idcarpeta){ ?>
&nbsp;<img src="../../imagen/etiqueta_carpeta.png" width="160" height="20" />
<? } ?>
                        </span></td>
                      </tr>
						  <?  }   ?>
                      <? }// FIN SI HAY CARPETAS ?>
                        <? 
						$query_mod6_idcarpeta4 = "SELECT A.idcarpeta, B.nombre 
						FROM carpeta A
						INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
						WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta3' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
						ORDER BY B.nombre";
						$result_mod6_idcarpeta4 = mysql_query($query_mod6_idcarpeta4);
						$cant_carpeta4 = mysql_num_rows($result_mod6_idcarpeta4);
					  
						if($cant_carpeta4 > 0){
						
						if($mod6_idcarpeta3 && $mod6_idcarpeta2  && $mod6_idcarpeta){ ?>
					  <tr>
                        <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                        <td colspan="2" align="left" valign="middle" class="style2"><span class="style10">
                          <select name="mod6_idcarpeta4" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta4">
                            <option value="" selected="selected">- Seleccionar Carpeta</option>
                            <?
	 
								  while ($rs_mod6_idcarpeta4 = mysql_fetch_assoc($result_mod6_idcarpeta4))	  
								  {
									if ($mod6_idcarpeta4 == $rs_mod6_idcarpeta4['idcarpeta'])
									{
										$sel4 = "selected";
									}else{
										$sel4 = "";
									}
							?>
                            <option  <? echo $sel4 ?> value="<?= $rs_mod6_idcarpeta4['idcarpeta'] ?>">
                            <?= $rs_mod6_idcarpeta4['nombre'] ?>
                            </option>
                            <?  } ?>
                          </select>
						  <? if($mod6_idcarpeta3 && $mod6_idcarpeta2  && $mod6_idcarpeta){ ?>
                           &nbsp;<img src="../../imagen/etiqueta_carpeta.png" width="160" height="20" />
                           <? } ?></span></td>
                      </tr>
					  	<? } ?>
					  <? }// FIN SI HAY CARPETAS ?>
					  <? } ?>
					  <? if($tipo == 1){ ?>
                      <tr>
                        <td align="right" valign="middle" bgcolor="#FFEEB9" class="detalle_medio"><strong>&raquo; Secci&oacute;n: </strong></td>
                        <td width="350" align="left" bgcolor="#FFEEB9"><select name="idseccion" style="width:350px; background:#FF6600; color:#FFFFFF; border:0;" class="detalle_medio" id="idseccion">
                          <?
							$query_seccion = "SELECT DISTINCT A.idseccion, B.titulo 
							FROM seccion A
							INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
							INNER JOIN seccion_carpeta C ON A.idseccion = C.idseccion
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1";
							$result_seccion = mysql_query($query_seccion);
							$cant_seccion = mysql_num_rows($result_seccion);
							while($rs_seccion = mysql_fetch_assoc($result_seccion)){
							?>
                          <option value="<?= $rs_seccion['idseccion'] ?>">
                          <?= $rs_seccion['titulo'] ?>
                          </option>
                          <?
							}
						  ?>
						  <? if($cant_seccion == 0){ ?>
						  <option value="">- Ésta carpeta no contiene Secciones. -</option>
						  <? } ?>
                        </select></td>
                        <td width="202" align="left" valign="middle" bgcolor="#FFEEB9"> <? if($cant_seccion > 0){ ?><img src="../../imagen/etiqueta_seccion.png" width="152" height="20" /> <? }else{ ?> <img src="../../imagen/etiqueta_atencion.jpg" width="100" height="20" />                          <? } ?></td>
                      </tr>
					  <? } ?>
					  <? if($tipo == 2){ ?>
                      <tr>
                        <td align="right" valign="middle" bgcolor="#FFEEB9" class="detalle_medio"><strong>&raquo; Producto:</strong></td>
                        <td width="350" align="left" bgcolor="#FFEEB9"><select name="idproducto" style="width:350px; background:#669966; color:#FFFFFF; border:0;" class="detalle_medio" id="idproducto">
                          <?
							$query_producto = "SELECT DISTINCT A.idproducto, B.titulo 
							FROM producto A
							INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto
							INNER JOIN producto_carpeta C ON A.idproducto = C.idproducto
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1";
							$result_producto = mysql_query($query_producto);
							$cant_producto = mysql_num_rows($result_producto);
							while($rs_producto = mysql_fetch_assoc($result_producto)){
							?>
                          <option value="<?= $rs_producto['idproducto'] ?>">
                          <?= $rs_producto['titulo'] ?>
                          </option>
                          <?
							}
							?>
						  <? if($cant_producto == 0){ ?>
						  <option value="">- Ésta carpeta no contiene Productos. -</option>
						  <? } ?>
                        </select></td>
                        <td width="202" align="left" bgcolor="#FFEEB9"><? if($cant_producto > 0){ ?><img src="../../imagen/etiqueta_producto.png" width="152" height="20" />
                          <? }else{ ?>
                          <img src="../../imagen/etiqueta_atencion.jpg" width="100" height="20" />
                          <? } ?></td>
                      </tr>
					  <? } ?>
                      <tr>
                        <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                        <td colspan="2" align="left" bgcolor="#FFF2CC"><label>
                          </label>
                          Propiedades:
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="4%"><input name="checkbox" type="checkbox" value="checkbox" checked="checked"  disabled="disabled"  /></td>
                              <td width="30%" class="detalle_medio">Titulo</td>
                              <td width="4%" class="detalle_medio"><input name="check_detalle" type="checkbox" id="check_detalle" value="1" <? if($check_detalle == 1){ echo "checked"; } ?> /></td>
                              <td width="62%" class="detalle_medio">Detalle</td>
                            </tr>
                            <tr>
                              <td><input name="checkbox2" type="checkbox" value="checkbox" checked="checked" disabled="disabled" /></td>
                              <td class="detalle_medio">Copete</td>
                              <td class="detalle_medio"><input name="check_foto" type="checkbox" id="check_foto" value="1"  <? if($check_foto == 1){ echo "checked"; } ?> /></td>
                              <td class="detalle_medio">Foto</td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td class="detalle_medio">&nbsp;</td>
                              <td class="detalle_medio"><input name="check_vinculo" type="checkbox" id="check_vinculo" value="1" <? if($check_vinculo == 1){ echo "checked"; } ?> /></td>
                              <td class="detalle_medio">V&iacute;nculo</td>
                            </tr>
                          </table>                         </td>
                      </tr>
                      <tr>
                        <td width="96" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                        <td colspan="2" align="left"><span class="detalle_medio_bold">
                          <input name="Submit2223" type="button" class="detalle_medio_bold buttons" onclick=" generar_contenido();" value="&raquo;  Generar contenido" />
                          <input name="Submit22222" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.getElementById('load_image').style.display = 'block'; document.form.tipo.value = 0; document.form.submit();" value="   &raquo;  Reset    " />
                        </span></td>
                      </tr>
					  <? } ?>
                    </table></td>
                  </tr>
                </table>
                <br />
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">

                  <tr bgcolor="#999999">
                    <td bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                        <tr>
                          <td align="left" valign="middle" class="detalle_medio"><span class="detalle_medio_bold">
                            <span class="detalle_medio_bold">Borrador:</span><br />
                            <textarea name="borrador" rows="20" cols="80" id="borrador" style="width:100%" ><?= $rs_newsletter['borrador']; ?></textarea>
                          </span></td>
                        </tr>


                    </table></td>
                  </tr>
                </table>
                <br />
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="#999999">
                    <td height="20" bgcolor="#BBD2FF" class="titulo_medio_bold">Newsletter:</td>
                    <td align="right" valign="middle" bgcolor="#BBD2FF" class="titulo_medio_bold"><span class="detalle_medio_bold">
                      <input name="Submit22242" type="button" class="detalle_medio_bold buttons" onclick="guardar();" value="   &raquo;  Guardar    " />
                    </span></td>
                  </tr>
                  <tr bgcolor="#999999">
                    <td height="20" colspan="2" bgcolor="#D2E4FF"><table width="100%" id="layer_ayuda" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="3%" align="left" valign="middle"><img src="../../imagen/iconos/tinymce/nuevo.png" width="11" height="14" /></td>
                            <td width="32%" align="left" valign="middle" class="detalle_11px">Nuevo</td>
                            <td width="3%" align="left" valign="middle"><img src="../../imagen/iconos/tinymce/guidelines.png" width="16" height="13" /></td>
                            <td width="31%" align="left" valign="middle" class="detalle_11px">Ocultar guias de tabla</td>
                            <td width="3%" align="left" valign="middle"><img src="../../imagen/iconos/tinymce/deshacer.png" width="12" height="13" /></td>
                            <td width="28%" align="left" valign="middle" class="detalle_11px">Deshacer</td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/pegar.png" width="16" height="14" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Pegar</td>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/eliminarfila.png" width="16" height="13" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Eliminar fila </td>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/rehacer.png" width="12" height="13" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Rehacer</td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/pegarword.png" width="16" height="14" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Pegar desde Word </td>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/eliminarcolumna.png" width="13" height="14" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Eliminar columna </td>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/link.png" width="15" height="7" /></td>
                            <td align="left" valign="middle" class="detalle_11px">V&iacute;nculo</td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/cortar.png" width="11" height="14" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Cortar</td>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/vistaprevia.png" width="16" height="16" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Vista previa </td>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/plantilla.png" width="14" height="14" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Plantillas de newsletter </td>
                          </tr>
                          <tr>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/imagen.jpg" width="15" height="15" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Insertar/Editar imagen </td>
                            <td align="left" valign="middle"><img src="../../imagen/iconos/tinymce/barrahorizontal.png" width="16" height="5" /></td>
                            <td align="left" valign="middle" class="detalle_11px">Barra horizontal </td>
                            <td align="left" valign="middle">&nbsp;</td>
                            <td align="left" valign="middle" class="detalle_11px">&nbsp;</td>
                          </tr>
                        </table><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" valign="middle" bgcolor="#D2E4FF"><a href="#" id="btn_ayuda"><img src="../../imagen/desplegar.png" alt="Desplegar Ayuda &raquo;" width="30" height="16" border="0" /></a></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr bgcolor="#999999">
                    <td colspan="2" bgcolor="#E1EDFF"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                        <tr>
                          <td align="left" valign="middle" class="detalle_medio"><span class="detalle_medio_bold">
                            <textarea name="contenido" rows="45" cols="80" id="contenido" style="width:100%" ><?= $rs_newsletter['contenido']; ?></textarea>
                          </span></td>
                        </tr>
                        <tr>
                          <td align="right" valign="middle" class="detalle_medio"><span class="detalle_medio_bold">
                            <input name="Submit2224" type="button" class="detalle_medio_bold buttons" onclick="guardar();" value="   &raquo;  Guardar    " />
                          </span></td>
                        </tr>
                    </table></td>
                  </tr>
                </table>
                <br />
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="#999999">
                    <td height="40" bgcolor="#85D6AD" class="titulo_medio_bold">C&oacute;digo newsletter </td>
                    <td align="right" valign="middle" bgcolor="#85D6AD" class="titulo_medio_bold"><span class="detalle_medio_bold">
                      <input name="Submit222422" type="button" class="detalle_medio_bold buttons" onclick="guardar();" value="&raquo;  Guardar y Generar c&oacute;digo" />
                    </span></td>
                  </tr>
                  <tr bgcolor="#999999">
                    <td colspan="2" bgcolor="#66FFCC"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                        <tr>
                          <td align="left" valign="middle" class="detalle_medio">
                            <textarea name="codigo" class="detalle_medio" id="codigo" style="width:98%; height:170px; border:1px solid #FFFFFF; padding:8px; SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;
	SCROLLBAR-SHADOW-COLOR: #e7e7e7;
	SCROLLBAR-DARKSHADOW-COLOR: #999999;
	SCROLLBAR-BASE-COLOR: #e7e7e7;
	SCROLLBAR-3DLIGHT-COLOR: #e7e7e7;
	SCROLLBAR-ARROW-COLOR: #999999;"><?= convertLatin1ToHtml(str_replace("../../../",$dominio_name,$rs_newsletter['contenido'])); ?></textarea>
                          </td>
                        </tr>

                    </table></td>
                  </tr>
                </table>
              </form></td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>