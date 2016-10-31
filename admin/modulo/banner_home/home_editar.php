<?
	include ("../../0_mysql.php");
	include ("../0_includes/0_clean_string.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = " AND idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
		$filtro_sede = '';
	}
	
	//LOCALIZACION DE VARIABLES:
	$accion = $_POST['accion'];
	$idhome = $_GET['idhome'];
	$ididioma = $_GET['ididioma'];
	
	$titulo = $_POST['titulo'];
	$banner = $_POST['banner'];
	$detalle = $_POST['detalle'];
	$orden = $_POST['orden'];
	$ruta_banner = "../../../imagen/banner_home/";

	//IDIOMA	
	$query_idioma = "SELECT titulo_idioma, reconocimiento_idioma
	FROM idioma 
	WHERE ididioma = '$ididioma'
	LIMIT 1";
	$rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
		
	//ACTUALIZAR
	if ($accion == "editar"){
		
		if($_FILES['banner']['name'] != ''){		
			
			$archivo_ext = substr($_FILES['banner']['name'],-4);
			$archivo_nombre = substr($_FILES['banner']['name'],0,strrpos($_FILES['banner']['name'], "."));
			$archivo = clean_string($archivo_nombre) . $archivo_ext;
			
			//ELIMINAR EL ANTERIOR
			$querysel = "SELECT archivo FROM se_home WHERE idhome = '$idhome' AND ididioma = '$ididioma' ";
			$rowfoto = mysql_fetch_row(mysql_query($querysel));
			if ($rowfoto[0] != '' && file_exists($ruta_banner.$rowfoto[0])){
				unlink ($ruta_banner.$rowfoto[0]);
			}; 
		
			$foto =  $idhome . '-' . $rs_idioma['reconocimiento_idioma'] . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
	
			
			if (!copy($_FILES['banner']['tmp_name'], $ruta_banner.$foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. "; //se crea la variable error con un mensaje de error.
				echo $foto;
			}else{
				$imagesize = getimagesize($ruta_banner.$foto);
	
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($ruta_banner.$foto))/1024,2);
					
					if($peso==0){
						$error .= "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
					}else{
					
						//SI TODO SALIO BIEN:
						$query_ingresar = "UPDATE se_home 
						SET archivo = '$foto'
						WHERE idhome = '$idhome' AND ididioma = '$ididioma'
						LIMIT 1";
						mysql_query($query_ingresar);
							
					};
				
				}else{
					$error .= "El archivo subido no corresponde a un tipo de imagen permitido. ";
					
						//SI NO SALIO BIEN:
						$query_borrar = "DELETE FROM se_home WHERE idhome = '$idhome' AND ididioma = '$ididioma' ";
						mysql_query($query_borrar);
					
					if(!unlink($ruta_banner.$foto)){ //se elimina el archivo subido
						$error .= "El archivo no pudo elminarse. ";
					}else{
						$error .= "El archivo fue elminado. ";
					};
				};
			};
		};
		
		//MODIFICAR EL RESTO DE LOS CAMPOS
		$query_upd = "UPDATE se_home 
		SET titulo = '$titulo'
		, detalle = '$detalle'
		, orden = '$orden'
		WHERE idhome = '$idhome' AND ididioma = '$ididioma'
		LIMIT 1";
		mysql_query($query_upd);
		
	};
	
	
	//MODIFICAR SEDE
	if($accion == "modificar_sede"){
		
		$cantidad_sede = $_POST['cantidad_sede'];
		$idsede = $_POST['idsede'];
		
		$query_delete = "DELETE FROM se_home_sede 
		WHERE idhome = '$idhome' $filtro_sede";
		mysql_query($query_delete);
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query = "INSERT INTO se_home_sede(
			  idhome
			, idsede
			)VALUES(
			  '$idhome'
			, '$idsede[$c]'
			)";
			mysql_query($query);
		}
	}
	
	//CONSULTA:	
	$query = "SELECT * 
	FROM se_home 
	WHERE idhome = '$idhome' AND ididioma = '$ididioma' ";
	$result = mysql_query($query);
	$rs_banner = mysql_fetch_assoc($result);
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_banner(){
		var formulario = document.form_banner;
		var flag = true;
		var checks_idioma = 0;
		var checks_sede = 0;
		var error= "";
		
		if(formulario.titulo.value == ''){
			error = error + "Debe ingresar el titulo.\n";
			flag = false;
		}
		
		if(esNumerico(formulario.orden.value) == false){
			error = error + "El número de orden debe ser númerico.\n";
			flag = false;
		}
			
		if(flag == true){
			formulario.accion.value = "editar";
			formulario.submit();
		}else{
			alert(error);
		}
	
	};
	
	function validar_sede(){
		formulario = document.form_banner;
		formulario.accion.value = "modificar_sede";
		formulario.submit();	
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
	<div id="navigation"><? include("../0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold"> Banner Home - Editar Idioma:<span style="color:#006699">
                <?= $rs_idioma['titulo_idioma'] ?></span>. </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_banner" id="form_banner">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Editar datos del Banner Home <span class="style2">
                            <input name="accion" type="hidden" id="accion" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="12%" align="right" valign="middle" class="detalle_medio" >Titulo:</td>
                                <td width="88%" valign="top" >
                                  <input id="titulo" name="titulo" type="text" class="detalle_medio" style="width:99%" value="<?= $rs_banner['titulo'] ?>" />                                </td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="middle" class="detalle_medio" >N&ordm; de Orden:</td>
                                <td valign="top" ><span class="detalle_medio">
                                  <input name="orden" type="text" class="detalle_medio" id="orden" style="width:9%" value="<?= $rs_banner['orden'] ?>" />
                                </span></td>
                              </tr>
                              <tr>
                                <td width="12%" align="right" valign="middle" class="detalle_medio" >Banner:</td>
                                <td valign="top" class="detalle_medio">
                                  <input name="banner" type="file" class="detalle_medio" id="banner" style="width:99%" />                                </td>
                              </tr>
							  <?  if($rs_banner['archivo'] != ''){ ?>
                              <tr>
                                <td width="12%" align="right" valign="middle" class="detalle_medio" >&nbsp;</td>
                                <td valign="top" class="detalle_medio"><table border="3" cellpadding="2" cellspacing="0" bordercolor="#669966" bgcolor="#669966">
                                  <tr>
                                    <td><? 
									  	$imagen =& new obj0001('0',$ruta_banner,$rs_banner['archivo'],'','','355','',$ruta_banner.$rs_banner['archivo'],'_blank','','wmode=opaque',''); 
									  ?></td>
                                  </tr>
                                  <tr>
                                    <td style="color:#FFFFFF">Archivo: 
                                   <b> <?=$rs_banner['archivo']?></b>                                    </td>
                                  </tr>
								<? }; ?>
                                </table>                                </td>
                              </tr>
							  <? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                              <tr>
                                <td width="12%" align="left" valign="top" class="detalle_medio" >HTML:</td>
                                <td valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top" class="detalle_medio" ><textarea name="detalle" rows="15" id="detalle" style="width:98%" class="con"><?= $rs_banner['detalle'] ?></textarea></td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td align="left" valign="middle" class="style2">&nbsp;</td>
                                <td height="35" align="left" valign="middle" class="style2"><input name="Submit22" type="button" class="detalle_medio_bold" onclick="validar_banner();" value="  &gt;&gt;  Actualizar   " /></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="37" bgcolor="#FFDDBC" class="titulo_medio_bold">Propiedades del banner: <a name="propiedades" id="propiedades"></a></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td align="left" bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="71" align="right" valign="top" class="detalle_medio_bold">Sucursales: </td>
                                <td width="585" align="left" valign="top" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
									$query_sel = "SELECT idhome
									FROM se_home_sede
									WHERE idhome = '$idhome' AND idsede = '$rs_sede[idsede]' ";
									if(mysql_num_rows(mysql_query($query_sel))>0){
										$sel="checked";
									}else{
										$sel="";
									}
								?>
                                    <tr>
                                      <td width="5%"><input type="checkbox" id="idsede[<?= $c ?>]" name="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?= $sel ?>  <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?>  /></td>
                                      <td width="95%"><?= $rs_sede['titulo'] ?></td>
                                    </tr>
                                    <? 
								$c++;
								} 
								?>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="71" align="right" valign="top" class="style2"><input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" /></td>
                                <td align="left" valign="middle" class="style2"><input name="Submit233" type="button" class="detalle_medio_bold" onclick="javascript:validar_sede()" value="  &gt;&gt;  Modificar   " /></td>
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