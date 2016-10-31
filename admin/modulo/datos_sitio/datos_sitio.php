<? include ("../../0_mysql.php"); 

	// localizacion de variables get y post:
	$accion = $_POST['accion'];
	$mail_top =  "top.jpg"; //se captura el nombre del archivo de la foto
	$mail_pie =  "pie.jpg"; //se captura el nombre del archivo de la foto
	$ruta_mail = "../../../imagen/mail/";
	
	$pie = $_POST['pie'];
	$meta_tag_title = $_POST['meta_tag_title'];
	$meta_tag_keywords = $_POST['meta_tag_keywords'];
	$meta_tag_description = $_POST['meta_tag_description'];
	$meta_tag_revisit_after = $_POST['meta_tag_revisit_after'];
	
	$emp_nombre = $_POST['emp_nombre'];
	$emp_prefijo = $_POST['emp_prefijo'];
	$emp_datos_generales = $_POST['emp_datos_generales'];
	$emp_telefonos = $_POST['emp_telefonos'];
	$emp_direccion = $_POST['emp_direccion'];
	
	$cont_prod_mail_destino = $_POST['cont_prod_mail_destino'];
	$cont_prod_mail_desde = $_POST['cont_prod_mail_desde'];
	$cont_prod_mail_asunto = $_POST['cont_prod_mail_asunto'];
	$codigo_contacto = $_POST['codigo_contacto'];
	
	$cont_mail_destino = $_POST['cont_mail_destino'];
	$cont_mail_desde = $_POST['cont_mail_desde'];
	$cont_mail_asunto = $_POST['cont_mail_asunto'];
	
	$env_amigo_asunto = $_POST['env_amigo_asunto'];
	
	$copyright = $_POST['copyright'];
	$admin_titulo = $_POST['admin_titulo'];
	
	$keywords = $_POST['keywords'];
	$mantenimiento = $_POST['mantenimiento'];
	
	if($_POST['ididioma_datositio']){
		$ididioma_datositio = $_POST['ididioma_datositio'];
		$_SESSION['ididioma_datositio'] = $ididioma_datositio;
	}else{
		$ididioma_datositio = 1;
	}
	
if ($accion == "update_dato_sitio"){

	$query_upd = "UPDATE dato_sitio SET
	  pie = '$pie'
	, meta_tag_title = '$meta_tag_title'
	, meta_tag_keywords = '$meta_tag_keywords'	
	, meta_tag_description = '$meta_tag_description'
	, meta_tag_revisit_after = '$meta_tag_revisit_after'	

	, cont_prod_mail_destino = '$cont_prod_mail_destino'
	, cont_prod_mail_desde = '$cont_prod_mail_desde'
	, cont_prod_mail_asunto = '$cont_prod_mail_asunto'
	, codigo_contacto = '$codigo_contacto'	
	
	, cont_mail_destino = '$cont_mail_destino'
	, cont_mail_desde = '$cont_mail_desde'
	, cont_mail_asunto = '$cont_mail_asunto'
	
	, env_amigo_asunto = '$env_amigo_asunto'
	
	, copyright = '$copyright'
	, admin_titulo = '$admin_titulo'	
	, keywords = '$keywords'	
	, mantenimiento = '$mantenimiento'
	
	WHERE ididioma = '$ididioma_datositio'
	LIMIT 1";
	mysql_query($query_upd);
	
	$query_upd2 = "UPDATE dato_sitio SET
	  emp_nombre = '$emp_nombre'
	, emp_prefijo = '$emp_prefijo'
	, emp_datos_generales = '$emp_datos_generales'	
	, emp_telefonos = '$emp_telefonos'	
	, emp_direccion = '$emp_direccion'
	WHERE ididioma = '$ididioma_datositio'
	LIMIT 1";
	mysql_query($query_upd2);

}

if($accion == "cambiar_idioma"){
	$ididioma_datositio = $_POST['ididioma_datositio'];
}

if ($accion == "update_imagenes_mail"){

		//INCORPORACION DE TOP
		if ($_FILES['mail_top']['name'] != ''){
			
			$archivo_ext = substr($_FILES['mail_top']['name'],-4);
			$archivo_nombre = substr($_FILES['mail_top']['name'],0,strrpos($_FILES['mail_top']['name'], "."));
			
			$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
			
					if (file_exists($ruta_mail.$mail_top)){
						unlink ($ruta_mail.$mail_top);
					}
			
			if (!copy($_FILES['mail_top']['tmp_name'], $ruta_mail . $mail_top)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
				echo "<script>alert('".$error."')</script>"; // se muestra el error.
			}else{
				$imagesize = getimagesize($ruta_mail.$mail_top);
			
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($ruta_mail.$mail_top))/1024,2);
					
					if($peso==0){
						$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error2."')</script>"; // se muestra el error.
					}else{
					
						//SI TODO SALIO BIEN:

					};
				
				}else{
				
					$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
					echo "<script>alert('".$error3."')</script>"; // se muestra el error.
					
					if(!unlink($ruta_mail.$mail_top)){ //se elimina el archivo subido
						$error4 = "El archivo no pudo elminarse. ";
						echo "<script>alert('".$error4."')</script>"; // se muestra el error.
					}else{
						$error5 = "El archivo fue elminado. ";
						echo "<script>alert('".$error5."')</script>"; // se muestra el error.
					};
				};
			};
		} // FIN INCORPORACION DE TOP
		
		
		//INCORPORACION DE PIE
		if ($_FILES['mail_pie']['name'] != ''){
			
			$archivo_ext = substr($_FILES['mail_pie']['name'],-4);
			$archivo_nombre = substr($_FILES['mail_pie']['name'],0,strrpos($_FILES['mail_pie']['name'], "."));
			
			$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
			
					if (file_exists($ruta_mail.$mail_pie)){
						unlink ($ruta_mail.$mail_pie);
					}
			
			if (!copy($_FILES['mail_pie']['tmp_name'], $ruta_mail . $mail_pie)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
				echo "<script>alert('".$error."')</script>"; // se muestra el error.
			}else{
				$imagesize = getimagesize($ruta_mail.$mail_pie);
			
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($ruta_mail.$mail_pie))/1024,2);
					
					if($peso==0){
						$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error2."')</script>"; // se muestra el error.
					}else{
					
						//SI TODO SALIO BIEN:

					};
				
				}else{
				
					$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
					echo "<script>alert('".$error3."')</script>"; // se muestra el error.
					
					if(!unlink($ruta_mail.$mail_pie)){ //se elimina el archivo subido
						$error4 = "El archivo no pudo elminarse. ";
						echo "<script>alert('".$error4."')</script>"; // se muestra el error.
					}else{
						$error5 = "El archivo fue elminado. ";
						echo "<script>alert('".$error5."')</script>"; // se muestra el error.
					};
				};
			};
		} // FIN INCORPORACION DE PIE

	echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."');</script>";
}
			
	$query_dato_sitio = "SELECT A.* 
	FROM dato_sitio A
	WHERE A.ididioma = '$ididioma_datositio' ";
	$rs_dato_sitio = mysql_fetch_assoc(mysql_query($query_dato_sitio));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function update_dato_sitio(){
	formulario = document.form;
	if (formulario.meta_tag_title.value == "") {
		alert("Debe completar el titulo del sitio.");		
	} else if (formulario.meta_tag_keywords.value == ""){
		alert("Debe completar las palabras claves del sitio.");
	} else if (formulario.meta_tag_description.value == ""){
		alert("Debe completar la descripcion del sitio.");
		
	} else if (formulario.cont_prod_mail_destino.value == ""){
		alert("Debe completar el mail de destino para el formulario de consulta de productgo.");
	} else if (formulario.cont_prod_mail_asunto.value == ""){
		alert("Debe completar el asunto para el formulario de consulta de productgo.");
		
	} else if (formulario.cont_mail_destino.value == ""){
		alert("Debe completar el mail de destino para el formulario de consulta.");
	} else if (formulario.cont_mail_asunto.value == ""){
		alert("Debe completar el asunto para el formulario de consulta.");
		
	} else if (formulario.copyright.value == ""){
		alert("Debe completar el copyright del sitio.");
	} else if (formulario.admin_titulo.value == ""){
		alert("Debe completar el titulo del admin.");
		
	} else {	
		formulario.accion.value = 'update_dato_sitio';
		formulario.submit();
	};
	
};

function update_imagenes_mail(){
	formulario = document.form;
	if(formulario.mail_top.value != '' || formulario.mail_pie.value != ''){
		formulario.accion.value = 'update_imagenes_mail';
		formulario.submit();	
	}else{
		alert("Debe seleccionar al menos una imagen para cambiar.");
	}
};

function cambiar_idioma(){
	formulario = document.form;
	formulario.accion.value = 'cambiar_idioma';
	formulario.submit();	
}

</script>
<style type="text/css">
<!--
.style1 {
	color: #000000;
	font-weight: bold;
}
.style3 {font-size: 10px}
.style4 {font-size: 11px; letter-spacing:0px; font-family: Arial, Helvetica, sans-serif;}
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Datos de Sitio - Editar</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                          <td width="69%" height="40" align="right" valign="middle" class="detalle_medio">Cambiar idioma: </td>
                          <td width="31%" align="right" valign="middle"><select name="ididioma_datositio" class="detalle_medio" onchange="cambiar_idioma();" style="width:200px">
                              <? 
								$query_idioma = "SELECT ididioma, titulo_idioma
								FROM idioma
								WHERE estado = 1";
								$result_idioma = mysql_query($query_idioma);
								
								while($rs_idioma = mysql_fetch_assoc($result_idioma)){
							  ?>
                            <option value="<?= $rs_idioma['ididioma'] ?>" <? if($rs_idioma['ididioma'] == $ididioma_datositio){ echo "selected"; } ?>>
                              <?= $rs_idioma['titulo_idioma'] ?>
                            </option>
                            <? } ?>
                          </select></td>
                        </tr>
                      </table>
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Datos Cliente:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Empresa:</td>
                                <td width="642" align="left"><input name="emp_nombre" style="width:98%"  type="text" class="detalle_medio" id="emp_nombre" value="<?= $rs_dato_sitio['emp_nombre'] ?>" size="50" />
                                    <div class="div_comentario"><span class="detalle_11px"><span class="style1">Nombre de su empresa.</span></span></div></td>
                              </tr>
							  <? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Prefijo: </td>
                                <td align="left"><input name="emp_prefijo" style="width:98%"  type="text" class="detalle_medio" id="emp_prefijo" value="<?= $rs_dato_sitio['emp_prefijo'] ?>" size="50" />
                                <div class="div_comentario">
                                  <span class="style1"><span class="style4">Prefijo para el asunto de los mails: </span><br />
                                </span><span class="detalle_11px">Es el prefijo que ir&aacute; en el asunto de todos los mails que se envien desde esta p&aacute;gina. </span>                                </div></td>
                              </tr>
                              <tr>
							  <? } ?>
                                <td align="right" valign="top" class="detalle_medio">Datos Gral:</td>
                                <td align="left" class="detalle_11px"><textarea name="emp_datos_generales" rows="7" wrap="virtual" class="detalle_medio" id="emp_datos_generales" style="width:98%"><?= $rs_dato_sitio['emp_datos_generales'] ?></textarea>
                        <div class="div_comentario"><span class="style1">Datos Generales.</span><br />
                        Ingrese aqu&iacute; todos los datos que desee. Nombre de empresa, direcci&oacute;n, telefono, fax, etc. Esta informaci&oacute;n podr&aacute; ser utilizada si usted lo solicita como firma al pie de los mails que se envien desde la p&aacute;gina. </div></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Telefonos:</td>
                                <td align="left" class="detalle_11px"><input name="emp_telefonos" style="width:98%"  type="text" class="detalle_medio" id="emp_telefonos" value="<?= $rs_dato_sitio['emp_telefonos'] ?>" size="50" />
                                  <div class="div_comentario"><span class="style1">Telefonos de su empresa.</span><br />
                                Ingrese aqu&iacute; todos los telefonos de su empresa. Se utilizar&aacute; esta informaci&oacute;n en donde se requira mostrar &uacute;nicamente  estos datos. </div></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Direcci&oacute;n:</td>
                                <td align="left" class="detalle_11px"><input name="emp_direccion" style="width:98%"  type="text" class="detalle_medio" id="emp_direccion" value="<?= $rs_dato_sitio['emp_direccion'] ?>" size="50" />
                                  <div class="div_comentario"><span class="style1">Direcci&oacute;n.</span><br />
                                Ingrese aqu&iacute; la direcci&oacute;n de su empresa. Se utilizar&aacute; esta informaci&oacute;n en donde se requira mostrar &uacute;nicamente  estos datos. </div></td>
                              </tr>

                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit226" type="button" class="detalle_medio_bold" onclick="update_dato_sitio();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                      </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Meta Tags:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Titulo:</td>
                                <td width="642" align="left">
                                  <input name="meta_tag_title" style="width:98%"  type="text" class="detalle_medio" id="meta_tag_title" value="<?= $rs_dato_sitio['meta_tag_title'] ?>" size="50" />
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Titulo:</span><br />
Debe ser corto, no mas de 2 o 3 palabras, dado que el sistema adiciona automaticamente la categoria que se esta navegando y el titulo de producto que se esta consultando. </span></div></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Pie:</td>
                                <td align="left" class="detalle_11px"><textarea name="pie" rows="7" wrap="virtual" class="detalle_medio" id="pie" style="width:98%"><?= $rs_dato_sitio['pie'] ?></textarea>
                                <br />
                                <br /></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">Keywords:</td>
                                <td align="left"><textarea name="meta_tag_keywords" rows="7" wrap="virtual" class="detalle_medio" id="meta_tag_keywords" style="width:98%"><?= $rs_dato_sitio['meta_tag_keywords'] ?></textarea>
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Keywords:</span><br />
Son las palabres claves genericas, se usa para cuando no 
                                  
                                se especifican palabras claves especificas para un producto o seccion. Tiene que estar separado por comas, ej: auto, lancha, compra, etc. Es importante que no superen los 500 caracteres, o 20 palabras.</span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">Descripci&oacute;n del sitio</td>
                                <td align="left"><textarea name="meta_tag_description" rows="7" class="detalle_medio" id="meta_tag_description" style="width:98%"><?= $rs_dato_sitio['meta_tag_description'] ?></textarea>
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Descripci&oacute;n del sitio :</span><br />
Muestra la descripcion generica, se usa siempre y cuando no se especifique uno en particular. En los productos y las secciones se asigna directamente el copete. Evitar poner gran cantidad de caracteres y no superar los 200 caracteres.</span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22" type="button" class="detalle_medio_bold" onclick="update_dato_sitio();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                      <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Formulario de Contacto:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Destino</td>
                                <td width="642" align="left"><input name="cont_mail_destino" type="text" class="detalle_medio" id="cont_mail_destino" value="<?= $rs_dato_sitio['cont_mail_destino'] ?>" style="width:98%" />
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1"><strong>Destino</strong></span><br />
Es el mail a donde va a llegar las consultas realizadas por el formulario de contacto. <br />
Si deseo que el mail le llegue a mas de una cuenta de mail, tendre que separar por coma cada una de las cuentas. 
                                  Ej: info@misitio.com, consultas@misitio.com <br />
                                  <br />
                                </span></div></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Desde</td>
                                <td align="left">
                                  <input name="cont_mail_desde" type="text" class="detalle_medio" style="width:98%"  id="cont_mail_desde" value="<?= $rs_dato_sitio['cont_mail_desde'] ?>">
                                  <div class="div_comentario"><span class="detalle_11px"><strong class="style1">Desde</strong><br />
Es el mail que va a figurar como remitente (quien envia) del mail de contacto. <br />
                                  </span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">Asunto</td>
                                <td align="left">
                                  <input name="cont_mail_asunto"  type="text" class="detalle_medio" id="cont_mail_asunto" style="width:98%"  value="<?= $rs_dato_sitio['cont_mail_asunto'] ?>">
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Asunto</span><br />
Es el titulo del mail con el que va a llegar la consulta del formulario de contacto. <br />
                                  </span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit223" type="button" class="detalle_medio_bold" onclick="update_dato_sitio();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Formulario de Consulta de Producto:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Destino:</td>
                                <td width="642" align="left"><input name="cont_prod_mail_destino" type="text" class="detalle_medio" id="cont_prod_mail_destino" value="<?= $rs_dato_sitio['cont_prod_mail_destino'] ?>" style="width:98%" >
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Destino</span><br />
Es el mail a donde va a llegar las consultas realizadas por el formulario de contacto. <br />
Si deseo que el mail le llegue a mas de una cuenta de mail, tendre que separar por coma cada una de las cuentas. <br />
Ej: info@misitio.com, consultas@misitio.com <br />
                                  </span></div></td>
                              </tr>
                              
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Desde:</td>
                                <td align="left">
                                  <input name="cont_prod_mail_desde" type="text" class="detalle_medio" id="cont_prod_mail_desde" value="<?= $rs_dato_sitio['cont_prod_mail_desde'] ?>" style="width:98%" />
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Desde</span><br />
Es el mail que va a figurar como remitente (quien envia) del mail de contacto. <br />
<br />
                                  </span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">Asunto:</td>
                                <td align="left">
                                  <input name="cont_prod_mail_asunto" type="text" class="detalle_medio" id="cont_prod_mail_asunto" value="<?= $rs_dato_sitio['cont_prod_mail_asunto'] ?>" style="width:98%" >
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Asunto</span><br />
Es el titulo del mail con el que va a llegar la consulta del formulario de contacto de producto. </span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio"><label onclick="return (document.getElementById('checkbox_row_5') ? false : true)" for="checkbox_row_5">Cod. Contacto:</label></td>
                                <td align="left">
                                  <input name="codigo_contacto" type="text" class="detalle_medio" id="codigo_contacto" value="<?= $rs_dato_sitio['codigo_contacto'] ?>" style="width:98%" >
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Codigo Contacto</span> <br />
Es el prefijo que aparece adelante del numero de contacto al momento de hacer la consulta.</span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit222" type="button" class="detalle_medio_bold" onclick="update_dato_sitio();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Formulario Enviar a un amigo:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">Asunto:</td>
                                <td width="642" align="left" class="detalle_11px">
                                  <input name="env_amigo_asunto" type="text" class="detalle_medio" id="env_amigo_asunto" value="<?= $rs_dato_sitio['env_amigo_asunto'] ?>" style="width:98%" >
                                  <div class="div_comentario"><span class="style1">Asunto</span><br />
                                    Es el titulo del mail con el que va a llegar la consulta del formulario de contacto de producto. </div>                                </td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit224" type="button" class="detalle_medio_bold" onclick="update_dato_sitio();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Datos Varios   :</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td align="right" valign="top" class="detalle_medio"><label onclick="return (document.getElementById('checkbox_row_7') ? false : true)" for="checkbox_row_7">Copyright</label>                                  :</td>
                                <td width="642" align="left" class="detalle_11px"><input name="copyright" type="text" class="detalle_medio" id="copyright" value="<?= $rs_dato_sitio['copyright'] ?>" style="width:98%" >
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">Copyright</span><br />
Aparece en el pie del sitio y el el formulario de envio a un amigo.
<br />
                                </span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio">Titulo del Admin:</td>
                                <td align="left">
                                  <input name="admin_titulo" type="text" class="detalle_medio" id="admin_titulo" style="width:98%"  value="<?= $rs_dato_sitio['admin_titulo'] ?>">
                                  <div class="div_comentario"><span class="detalle_11px"><span class="style1">
                                    <label onclick="return (document.getElementById('checkbox_row_6') ? false : true)" for="checkbox_row_6">Titulo del Admin</label>
                                  </span> <br />
Es el titulo que aparece en la parte superior del admin.
  <br />
                                  </span></div></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit225" type="button" class="detalle_medio_bold" onclick="update_dato_sitio();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Imagenes Mail:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Top:</td>
                                <td width="623" align="left"><input name="mail_top" type="file" class="detalle_medio" id="mail_top" style="width:98%" />
                                    <? $medidas_foto_top = getimagesize("../../../imagen/mail/top.jpg"); ?></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio">Pie:</td>
                                <td align="left"><input name="mail_pie" type="file" class="detalle_medio" id="mail_pie" style="width:98%"  />
                                    <? $medidas_foto_pie = getimagesize("../../../imagen/mail/pie.jpg"); ?></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="center" valign="top" >&nbsp;</td>
                                <td align="center" valign="top" ><table width="1" border="1" cellpadding="0" cellspacing="0" bordercolor="#BAEFE0">
                                  <tr>
                                    <td bgcolor="#BAEFE0"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        <tr valign="middle" class="detalle_medio">
                                          <td align="center"><table width="280" border="0" cellpadding="5" cellspacing="0">
                                              <tr>
                                                <td width="40" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Top:</td>
                                                <td width="47" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Ancho</td>
                                                <td width="57" align="left" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$medidas_foto_top[0]?>
                                                  px</td>
                                                <td width="58" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Alto</td>
                                                <td width="63" align="left" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$medidas_foto_top[1]?>
                                                  px </td>
                                              </tr>
                                            </table>
                                              <table width="280" border="0" cellpadding="5" cellspacing="0">
                                                <tr>
                                                  <td width="35" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Pie:</td>
                                                  <td width="45" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Ancho</td>
                                                  <td width="47" align="left" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$medidas_foto_pie[0]?>
                                                    px</td>
                                                  <td width="49" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">Alto</td>
                                                  <td width="54" align="left" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold style3"><?=$medidas_foto_pie[1]?>
                                                    px </td>
                                                </tr>
                                            </table></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td align="center" valign="middle"><table width="619" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                          <td colspan="3"><? $foto_producto =& new obj0001(0,"../../../imagen/mail/","top.jpg",'','','','','','','','wmode=opaque',''); ?></td>
                                        </tr>
                                        <tr>
                                          <td width="10" background="../../../imagen/mail/sombra_izq.jpg"><img src="../../../imagen/mail/sombra_izq.jpg" width="10" height="1" /></td>
                                          <td width="599" height="300" align="center" valign="middle" bgcolor="#FFFFFF"><br />
                                            <table bordercolor="#cde3f5" cellspacing="0" cellpadding="5" width="95%" border="1">
                                            <tbody>
                                              <tr bgcolor="#2480c9">
                                                <td bgcolor="#cde3f5"><table cellspacing="0" cellpadding="0" width="100%" border="0">
                                                    <tbody>
                                                      <tr bgcolor="#2480c9">
                                                        <td align="left" bgcolor="#cde3f5" class="detalle_medio_bold">Datos del contacto: </td>
                                                        <td align="right" bgcolor="#cde3f5" class="detalle_medio_bold">N&ordm; de Contacto: </td>
                                                      </tr>
                                                    </tbody>
                                                </table></td>
                                              </tr>
                                              <tr>
                                                <td valign="center" align="left" bgcolor="#ebf4fc"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                                  <tr>
                                                    <td height="20" align="left" valign="bottom"><strong>Nombre y Apellido </strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left"><span class="detalle_11px">Juan Manuel </span></td>
                                                  </tr>
                                                  <tr align="left" valign="bottom">
                                                    <td height="30" align="left"><strong>Provincia:</strong></td>
                                                  </tr>
                                                  <tr class="detalle_11px">
                                                    <td align="left">Buenos Aires</td>
                                                  </tr>
                                                  <tr align="left" valign="bottom">
                                                    <td height="30" align="left"><strong>Ciudad:</strong></td>
                                                  </tr>
                                                  <tr class="detalle_11px">
                                                    <td align="left">Chacabuco</td>
                                                  </tr>
                                                  <tr align="left" valign="bottom">
                                                    <td height="30" align="left"><strong>Telefono:</strong></td>
                                                  </tr>
                                                  <tr class="detalle_11px">
                                                    <td align="left">02362-15-574433</td>
                                                  </tr>
                                                  <tr>
                                                    <td height="30" align="left" valign="bottom"><strong>E-mail:</strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left" class="detalle_11px">email@hotmail.com</td>
                                                  </tr>
                                                  <tr>
                                                    <td height="30" align="left" valign="bottom" ><strong>Mensaje</strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left" class="detalle_11px">Me interesan sus productos y   herramientas. Saludos y gracias.</td>
                                                  </tr>
                                                </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                              <br /></td>
                                          <td width="10" background="../../../imagen/mail/sombra_der.jpg"><img src="../../../imagen/mail/sombra_der.jpg" width="10" height="1" /></td>
                                        </tr>
                                        <tr>
                                          <td colspan="3"><? $foto_producto =& new obj0001(0,"../../../imagen/mail/","pie.jpg",'','','','','','','','wmode=opaque',''); ?></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                              </tr>
                              <tr>
                                <td width="35" align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit2252" type="button" class="detalle_medio_bold" onclick="update_imagenes_mail();" value="  &gt;&gt;  Guardar    " />
                                </span></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="d8f6ee" class="titulo_medio_bold">Palabras Clave:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio_bold"><input name="mantenimiento" type="radio" value="1" <? if($rs_dato_sitio['mantenimiento'] == "1"){ echo "checked='checked'"; } ?>></td>
                                <td width="642" align="left" class="detalle_medio">Activadas</td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio_bold"><input name="mantenimiento" type="radio" value="2" <? if($rs_dato_sitio['mantenimiento'] == "2"){ echo "checked='checked'"; } ?> /></td>
                                <td align="left" class="detalle_medio">Desactivadas</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left"><span class="detalle_11px">
                                  Permite que se vean o no las palabras clave al pie del sitio. 
                                </span>
                                </td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit2242" type="button" class="detalle_medio_bold" onclick="update_dato_sitio();" value="  &gt;&gt;  Guardar    " />
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