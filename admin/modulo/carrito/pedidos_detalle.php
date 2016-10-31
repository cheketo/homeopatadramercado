<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	include ("../0_includes/0_crear_miniatura.php"); 
	include ("../0_includes/0_clean_string.php"); 

	// localizacion de variables get y post:
	$idca_pedido = $_GET['idca_pedido'];
	$accion = $_POST['accion'];
	
	$estado_pedido = $_POST['estado_pedido'];
	$comentario_pedido = str_replace(chr(13), "<br>", addslashes($_POST['comentario_pedido']));	
	$orden_de_compra = $_POST['orden_de_compra'];
	$estado_pago = $_POST['estado_pago'];


	// modificacion del pedido:
	if($accion == "modificar"){	
	
		$fecha = date("Y-m-d");
		
		if($estado_pedido == 5){
			$fecha_finalizado = date("Y-m-d");
		}else{
			$fecha_finalizado = "0000-00-00";
		}
						
		$query_upd_pedido = "UPDATE ca_pedido SET
		  estado = '$estado_pedido'
		, orden_de_compra = '$orden_de_compra'
		, comentario_pedido = '$comentario_pedido'
		, fecha_ultima_modificacion = '$fecha'
		, fecha_finalizado = '$fecha_finalizado'
		WHERE idca_pedido = '$idca_pedido' ";
		mysql_query($query_upd_pedido);
		
		$query_pedido = "SELECT idca_informe_pago 
		FROM ca_pedido
		WHERE idca_pedido = '$idca_pedido' ";
		$rs_pedido = mysql_fetch_assoc(mysql_query($query_pedido));
		
		$query_upd_informe = "UPDATE ca_informe_pago SET
		estado = '$estado_pago'
		WHERE idca_informe_pago = '$rs_pedido[idca_informe_pago]' ";
		mysql_query($query_upd_informe);
		
	};
	

	//obtener datos de la seccion actual:
	$query_pedido = "SELECT * 
	FROM ca_pedido
	WHERE idca_pedido = '$idca_pedido' ";
	$rs_pedido = mysql_fetch_assoc(mysql_query($query_pedido));
	

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

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
		formulario.accion.value = "modificar";
		formulario.submit();
		
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
	
	window.addEvent('domready', function(){
		var mySlide1 = new Fx.Slide('layer_datos'); mySlide1.hide();
		$('btn_datos').addEvent('click', function(e){
			e = new Event(e);
			mySlide1.toggle();
			e.stop();
		});
	});
	
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
		elements : "orden_de_compra",
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Detalle del Pedido - N&ordm; <font color="#003399">
                  <?= $_GET['idca_pedido']; ?></font>:</td>
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
                          <td height="35" bgcolor="#d8f6ee" class="titulo_medio_bold">Datos del pedido:
                            <input name="accion" type="hidden" id="accion" value="1" /></td>
                          <td align="right" bgcolor="#d8f6ee" class="titulo_medio_bold"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_medio_bold">
                            <input name="Submit2222" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.location.href = 'pedidos_ver_lista.php';" value="   &raquo;  Volver    " />
                            <input name="Submit222" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="   &raquo;  Guardar    " />
                          </span></span></td>
                        </tr>
                        <tr >
                          <td colspan="2" bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="150" align="left" class="detalle_medio">Fecha del pedido: </td>
                                <td width="508" align="left"><strong>
                                <?= $rs_pedido['fecha_pedido'] ?>
                                </strong></td>
                              </tr>
							  <? if($rs_pedido['fecha_ultima_modificacion'] != '0000-00-00'){ ?>
                              <tr>
                                <td width="150" align="left" class="detalle_medio">Fecha última modificación:</td>
                                <td align="left"><strong>
                                <?= $rs_pedido['fecha_ultima_modificacion'] ?>
                                </strong></td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td align="left" class="detalle_medio">Estado del pedido:</td>
                                <td width="508" align="left"><span class="style10">
                                  <select name="estado_pedido" style="width:200px;" class="detalle_medio" id="estado_pedido">
                                    <? 
									  
									  $query_estado = "SELECT idca_estado_pedido, titulo
									  FROM ca_estado_pedido";
									  $result_estado = mysql_query($query_estado);
									  while($rs_estado = mysql_fetch_assoc($result_estado)){ 
									  
									  ?>
                                    <option <? if($rs_pedido['estado'] == $rs_estado['idca_estado_pedido']){ echo "selected"; } ?> value="<?= $rs_estado['idca_estado_pedido'] ?>">
                                    <?= $rs_estado['titulo'] ?>
                                    </option>
                                    <? } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio">Orden de compra: </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio_bold"><textarea name="orden_de_compra" rows="30" cols="80" id="orden_de_compra" style="width:100%" ><?= str_replace('src="imagen','src="../../../imagen', html_entity_decode($rs_pedido['orden_de_compra'], ENT_QUOTES)); ?></textarea></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="30%" height="25" align="left" valign="middle" bgcolor="#E3E8E7" class="detalle_11px">&nbsp;&nbsp; M&aacute;s informaci&oacute;n</td>
                                    <td width="40%" align="center" valign="middle" bgcolor="#E3E8E7"><a href="#" id="btn_datos"><img src="../../imagen/desplegar.png" width="30" height="16" border="0" /></a></td>
                                    <td width="30%" align="center" valign="middle" bgcolor="#E3E8E7">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="3" bgcolor="#EDF0EF"><table id="layer_datos" width="100%" border="0" cellspacing="0" cellpadding="4">
                                      <tr>
                                        <td align="center" valign="middle"><?= $rs_pedido['datos_envio'] ?></td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="middle"><?= str_replace("imagen/","../../../imagen/",$rs_pedido['forma_pago']) ?></td>
                                      </tr>
                                      <tr>
                                        <td align="center" valign="middle"><?= $rs_pedido['datos_facturacion'] ?></td>
                                      </tr>
                                    </table></td>
                                  </tr>
                                </table>                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top"><br />
Comentario: </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top"><textarea name="comentario_pedido" cols="74" rows="6" wrap="virtual" class="detalle_medio text_areas_01" id="comentario_pedido" style="width:96%"><?=  str_replace("<br>", chr(13), $rs_pedido['comentario_pedido']); ?></textarea>
                                  <div class="div_comentario" style="width:97%"><span class="detalle_11px"><span class="style1">Comentario:</span><br />
                                Lo que usted escriba en &eacute;ste campo lo podr&aacute; ver el cliente cuando ingrese a ver el detalle de su pedido. Podr&aacute; informar aqu&iacute; en el caso de que surja alg&uacute;n inconveniete con el pedido, los gastos de env&iacute;o o lo que desee. </span></div></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
					  <br />
				    <?
						
						$query_inf = "SELECT * 
						FROM ca_informe_pago
						WHERE idca_informe_pago = '$rs_pedido[idca_informe_pago]' ";
						$result_inf = mysql_query($query_inf);
						
						$num_informe = mysql_num_rows($result_inf);
						$rs_informe = mysql_fetch_assoc($result_inf);
						
						if($num_informe > 0){
						
					?>
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr >
                        <td height="35" bgcolor="#d8f6ee" class="titulo_medio_bold">Informe pedido:</td>
						
                        <td align="right" bgcolor="#d8f6ee" class="titulo_medio_bold"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_medio_bold">
                          <input name="Submit22223" type="button" class="detalle_medio_bold buttons" onclick="javascript:document.location.href = 'pedidos_ver_lista.php';" value="   &raquo;  Volver    " />
                          <input name="Submit2223" type="button" class="detalle_medio_bold buttons" onclick="validar_form();" value="   &raquo;  Guardar    " />
                        </span></span></td>
                      </tr>
                      <tr >
                        <td colspan="2" bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                            <tr>
                              <td width="110" align="left" class="detalle_medio">Fecha: </td>
                              <td width="548" align="left"><strong>
                                <?= $rs_informe['fecha'] ?>
                              </strong></td>
                            </tr>
                            <tr>
                              <td align="left" class="detalle_medio">Estado del pago:</td>
                              <td width="548" align="left"><span class="style10">
                                <select name="estado_pago" style="width:200px;" class="detalle_medio" id="estado_pago">
                                  <option <? if($rs_informe['estado'] == 1){ echo "selected"; } ?> value="1">No pagado.</option>
								  <option <? if($rs_informe['estado'] == 2){ echo "selected"; } ?> value="2">Sin confirmar.</option>
								  <option <? if($rs_informe['estado'] == 3){ echo "selected"; } ?> value="3">Pago Confirmado.</option>
								  <option <? if($rs_informe['estado'] == 4){ echo "selected"; } ?> value="4">Informe de pago rechazado. No se pudo confirmar su pago.</option>
                                </select>
                                &nbsp;&nbsp; <a href="javascript:enviarInformePago();"><img src="../../imagen/email.gif" width="20" height="20" border="0" /></a></span></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" class="detalle_medio">Informe de pago:</td>
                              <td align="left"><?= $rs_informe['informe']; ?></td>
                            </tr>


                        </table></td>
                      </tr>
                    </table>
					<?
						}
					?>
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