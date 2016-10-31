<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	
<? 
	
	include ("../0_includes/0_crear_miniatura.php");
	include ("../0_includes/0_clean_string.php");
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = " AND idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
		$filtro_sede = '';
	}

	//VARIABLES
	$idcarpeta = $_GET['idcarpeta'];
	$forma = $_GET['forma'];	
	$accion = $_POST['accion'];
	$campo_orden = $_POST['campo_orden'];
	$ordenamiento = $_POST['ordenamiento'];
	$orden = $_POST['orden'];
	
	//CAMBIAR ESTADO
	$estado_idioma = $_POST['estado_idioma'];
	$ididioma = $_POST['ididioma'];
	
	//SESSION USER
	if($_SESSION[idcusuario_perfil_log]=='1'){
		$usuario_perfil = 1;
	}else{
		$usuario_perfil = 2;
	}
	
	//MOVER CARPETA
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];

	//Variables de la foto
	$foto = $_POST['foto']; // es la imagen a ingresar
	$foto_chica_ancho = 170; // ancho maximo de la foto en tamaño chica
	$foto_mediana_ancho = 220; // ancho maximo de la foto en tamaño mediana
	$foto_ruta_chica = "../../../imagen/carpeta/chica/"; // la ruta donde se va a guardar la foto chica
	$foto_ruta_mediana = "../../../imagen/carpeta/mediana/"; // la ruta donde se va a guardar la foto mediana
	$ruta_descarga = "../../../descarga/";
	
	$estado_descarga = $_POST['estado_descarga'];
	$estado_restringido = $_POST['estado_restringido'];
	$eliminar_iddescarga = $_POST['eliminar_iddescarga'];
	$iddescarga = $_POST['iddescarga'];
	
	//ACTUALIZO FECHA MODIFICACION
	if($accion != ""){
		$fecha_hoy = date("Y-m-d");
		$query_mod_carpeta = "UPDATE carpeta SET fecha_modificacion = '$fecha_hoy' WHERE idcarpeta = '$idcarpeta' ";
		mysql_query($query_mod_carpeta);
	}
	
	//CAMBIAR ESTADO DESCARGA	
	if($estado_descarga != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET estado = '$estado_descarga'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idcarpeta=".$idcarpeta."#descarga');</script>";
	}
	
	//CAMBIAR RESTRINGISDO DESCARGA	
	if($estado_restringido != "" && $iddescarga != ""){
		$query_estado = "UPDATE descarga
		SET restringido = '$estado_restringido'
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idcarpeta=".$idcarpeta."#descarga');</script>";
	
	}
	
	//ELIMINAR DESCARGA	
	if($eliminar_iddescarga != ""){
		$query_estado = "SELECT archivo
		FROM descarga
		WHERE iddescarga = '$eliminar_iddescarga'
		LIMIT 1";
		$rs_del = mysql_fetch_assoc(mysql_query($query_estado));
		
		if (file_exists($ruta_descarga.$rs_del['archivo'])){
			unlink ($ruta_descarga.$rs_del['archivo']);
		}
		
		$query = "DELETE FROM descarga WHERE iddescarga = '$eliminar_iddescarga' LIMIT 1 ";
		mysql_query($query);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idcarpeta=".$idcarpeta."#descarga');</script>";
	
	}

	//MODIFICACION DE DATOS DE LA CARPETA:
	if($accion == "modificar"){
			
			// INCORPORACION DE FOTO
			if ($_FILES['foto']['name'] != ''){
		
				$archivo_ext = substr($_FILES['foto']['name'],-4);
				$archivo_nombre = substr($_FILES['foto']['name'],0,strrpos($_FILES['foto']['name'], "."));
				
				$archivo = clean_string($archivo_nombre) . $archivo_ext;
				$archivo = strtolower($archivo);
				
					$querysel = "SELECT foto FROM carpeta WHERE idcarpeta = '$idcarpeta' ";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
					
					if ( $rowfoto[0] ){
						if (file_exists($foto_ruta_chica.$rowfoto[0])){
							unlink ($foto_ruta_chica.$rowfoto[0]);
						}
						if (file_exists($foto_ruta_mediana.$rowfoto[0])){
							unlink ($foto_ruta_mediana.$rowfoto[0]);
						}
					}
					
				$foto =  $idcarpeta . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
				
				if (!copy($_FILES['foto']['tmp_name'], $foto_ruta_mediana . $foto)){ //si hay error en la copia de la foto
					$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
					echo "<script>alert('".$error."')</script>"; // se muestra el error.
				}else{
					$imagesize = getimagesize($foto_ruta_mediana.$foto);
				
					if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
						$peso = number_format((filesize($foto_ruta_mediana.$foto))/1024,2);
						
						if($peso==0){
							$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
							echo "<script>alert('".$error2."')</script>"; // se muestra el error.
						}else{
						
							//SI TODO SALIO BIEN:
							if($_POST['foto_mediana_ancho']){
								$foto_mediana_ancho_sel = $_POST['foto_mediana_ancho'];
							}else{
								$foto_mediana_ancho_sel = $foto_mediana_ancho;
							}
							if($_POST['foto_chica_ancho']){
								$foto_chica_ancho_sel = $_POST['foto_chica_ancho'];
							}else{
								$foto_chica_ancho_sel = $foto_chica_ancho;
							}		
	
							
							//ACHICAR IMAGEN AL ANCHO MÁXIMO de la foto mediana:	
							if ($imagesize[0] > $foto_mediana_ancho_sel){					
								$alto_nuevo = ceil($foto_mediana_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_ruta_mediana.$foto, $foto_mediana_ancho_sel, $alto_nuevo, $foto_ruta_mediana);
							}
													
							//CREAR MINI AL ANCHO MÁXIMO chico:
							if ($imagesize[0] > $foto_chica_ancho_sel){	
								$alto_nuevo = ceil($foto_chica_ancho_sel * $imagesize[1] / $imagesize[0]) ;
								crear_miniatura($foto_ruta_mediana.$foto, $foto_chica_ancho_sel, $alto_nuevo, $foto_ruta_chica);
							}else{
								crear_miniatura($foto_ruta_mediana.$foto, $imagesize[0], $imagesize[1], $foto_ruta_chica);
							};
					
							//ingreso de foto en tabla producto
							$query_upd = "UPDATE carpeta SET 
							foto = '$foto' 	
							WHERE idcarpeta = '$idcarpeta'
							LIMIT 1";
							mysql_query($query_upd);
		
						};			
					
					}else{
					
						$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
						echo "<script>alert('".$error3."')</script>"; // se muestra el error.
						
						if(!unlink("../../".$ruta_foto.$foto)){ //se elimina el archivo subido
							$error4 = "El archivo no pudo elminarse. ";
							echo "<script>alert('".$error4."')</script>"; // se muestra el error.
						}else{
							$error5 = "El archivo fue elminado. ";
							echo "<script>alert('".$error5."')</script>"; // se muestra el error.
						};
					
					};
			
				};
			} // FIN INCORPORACION DE FOTO		
	
					
			//ingreso de datos en tabla producto
			$query_modficacion = "UPDATE carpeta SET 
			  orden = '$orden'
			, campo_orden = '$campo_orden'
			, ordenamiento = '$ordenamiento'
			WHERE idcarpeta = '$idcarpeta'
			LIMIT 1";
			
			mysql_query($query_modficacion);
			
	};

	//ELIMINAR FOTO PRINCIPAL
	if ($accion == "eliminar_foto"){
				
					$querysel = "SELECT foto FROM carpeta WHERE idcarpeta = '$idcarpeta' ";
					$rowfoto = mysql_fetch_row(mysql_query($querysel));
					
					if ( $rowfoto[0] ){
						if (file_exists($foto_ruta_chica.$rowfoto[0])){
							unlink ($foto_ruta_chica.$rowfoto[0]);
						}
						if (file_exists($foto_ruta_mediana.$rowfoto[0])){
							unlink ($foto_ruta_mediana.$rowfoto[0]);
						}
					}
	
					$query_upd = "UPDATE carpeta SET 
					foto = '' 	
					WHERE idcarpeta = '$idcarpeta'
					LIMIT 1";
					mysql_query($query_upd);
					
	
	}
	
	//CAMBIAR ESTADO IDIOMA
	if($estado_idioma != "" && $ididioma != ""){
		
		$query = "UPDATE carpeta_idioma_dato
		SET estado = '$estado_idioma'
		WHERE ididioma = '$ididioma' AND idcarpeta = '$idcarpeta'	
		LIMIT 1";
		mysql_query($query);
		
		echo "<script>window.location.href=('$PHP_SELF"."?idcarpeta=$idcarpeta#$ididioma');</script>";
	
	}
	
	//MODIFICAR SEDE
	if($accion == "modificar_sede"){
		
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		$query_delete = "DELETE FROM carpeta_sede 
		WHERE idcarpeta = '$idcarpeta' $filtro_sede";
		mysql_query($query_delete);
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO carpeta_sede(
			  idcarpeta
			, idsede
			)VALUES(
			  '$idcarpeta'
			, '$sede[$c]'
			)";
			mysql_query($query_insert);
			//echo $sede[$c];
		}
	}
	
	//MOVER CARPETA
	if($accion == "mod6_insertar"){
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
	
		$query_adj = "UPDATE carpeta 
		SET idcarpeta_padre = '$mod6_sel_idcarpeta'			
		WHERE idcarpeta = '$idcarpeta'
		LIMIT 1";
		mysql_query($query_adj);	
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?idcarpeta=$idcarpeta');</script>";
	}
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM carpeta_parametro
	WHERE idcarpeta_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
	//CONSULTA DATOS DE LA CARPETA ACTUAL:
	$query_carpeta = "SELECT * 
	FROM carpeta 
	WHERE idcarpeta = '$idcarpeta'";
	$rs_carpeta = mysql_fetch_assoc(mysql_query($query_carpeta));
	
	$accion = "";
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>

<script type="text/javascript" src="../../js/overlay.js"></script>
<script type="text/javascript" src="../../js/multibox.js"></script>
<link href="../../css/0_multibox.css" rel="stylesheet" type="text/css" />

<script language="javascript">

	function validar_form(){
		formulario = document.form_datos;
			formulario.accion.value = "modificar";
			formulario.submit();	
	};
	
	function validar_sede(){
		var formulario = document.form_sede;
		var checks_sede = 0;
		var error = '';
		var flag = true;
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = window.form_sede.document.getElementById("sede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			error = error + 'Debe seleccionar al menos una sucursal.\n';
			flag = false;
		}
		
		if(flag == true){	
			formulario.accion.value = "modificar_sede";
			formulario.submit();
		}else{
			alert(error);
		}
				
	};


	function confirm_eliminar(url, variables, id){
	
		if (confirm('¿ Esta seguro que desea eliminar el registro ?')){
			if (variables == ''){
				window.location.href=(url+'?eliminar_tipo='+id);
			}else{
				window.location.href=(url+'?'+variables+'&eliminar_tipo='+id);
			};
		}
	
	}

	function eliminar_foto(){
		formulario = document.form_datos;
		if (confirm('¿ Esta seguro que desea eliminar la foto ?')){
			formulario.accion.value = "eliminar_foto";
			formulario.submit();
		}
	
	}

	function eliminar_descarga(id){
		formulario = document.form_descargas;
		if (confirm('¿ Está seguro que desea eliminar la descarga ?')){
			formulario.accion.value = "eliminar_descarga";
			formulario.eliminar.value = id;
			formulario.submit();
		}
	}

	function ingresar_descarga(){
		formulario = document.form_descargas;
		
		if ((formulario.descarga_archivo.value == '')&&(formulario.descarga_archivo_txt.value == '')){
			alert("Debe seleccionar un archivo para ingresar utilizando el botón 'Examinar'.")
		}else{
			formulario.accion.value = "ingresar_descarga";
			formulario.submit();
		}
	}

	function cambiar_estado(estado, ididioma){
	formulario = document.form_idioma;
	
	formulario.estado_idioma.value = estado;
	formulario.ididioma.value = ididioma;
	formulario.submit();
	}
	
	function cambiar_estado_descarga(estado, iddescarga){
		formulario = document.form_descarga;
		
		formulario.estado_descarga.value = estado;
		formulario.iddescarga.value = iddescarga;
		formulario.submit();
	}
	
	function cambiar_restringido_descarga(estado, iddescarga){
		formulario = document.form_descarga;
		
		formulario.estado_restringido.value = estado;
		formulario.iddescarga.value = iddescarga;
		formulario.submit();
	}
	
	function eliminar_descarga(eliminar_iddescarga){
		formulario = document.form_descarga;
		
		formulario.eliminar_iddescarga.value = eliminar_iddescarga;
		formulario.submit();
	}
	
	var box1 = {};
	window.addEvent('domready', function(){
		box1 = new MultiBox('desc', {descClassName: 'multiBoxDesc_desc', useOverlay: true});
	});

</script>
<style type="text/css">
<!--
.style1 {font-size: 10px}
.style2 {font-weight: bold}
.style3 {font-weight: bold}
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Carpeta - Editar</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_datos" id="form_datos">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td width="69%" height="35" bgcolor="#d8f6ee" class="titulo_medio_bold"> Datos de la carpeta:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                          <td width="26%" align="right" valign="middle" bgcolor="#d8f6ee" class="detalle_medio">
						   <? if($usuario_perfil == 1 ){ ?>
						  Parametrizaci&oacute;n Individual: 
						  <? } ?></td>
                          <td width="5%" align="right" valign="middle" bgcolor="#d8f6ee" class="titulo_medio_bold">
						  <? if($usuario_perfil == 1 ){ ?>
						  <a href="carpeta_parametro_individual.php?idcarpeta=<?= $idcarpeta ?>" target="_blank"><img src="../../imagen/iconos/page_process_24px.png" width="24" height="24" border="0" /></a>						  <? } ?>						  </td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td colspan="3" bgcolor="#eafcf7"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                            <tr>
                              <td width="102" align="right" class="detalle_medio">ID:</td>
                              <td width="556" align="left" class="detalle_medio_bold"><input name="id" type="text" disabled="disabled" class="detalle_medio" id="id" value="<?= $rs_carpeta['idcarpeta'] ?>" size="3" /></td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">N&ordm; de Orden:</td>
                              <td align="left" class="detalle_medio_bold"><input name="orden" type="text" class="detalle_medio" id="orden" value="<?= $rs_carpeta['orden'] ?>" size="3" /></td>
                            </tr>
                            <tr>
                              <td align="right" class="detalle_medio">&nbsp;</td>
                              <td align="left" class="detalle_medio style1"><u>Atenci&oacute;n:</u> Las carpetas se ordenar&aacute;n de menor a mayor seg&uacute;n el n&uacute;mero de orden. </td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" class="detalle_medio">Foto:</td>
                              <td align="left"><input name="foto" type="file" class="detalle_medio" id="foto" size="40" /></td>
                            </tr>

                            <tr>
                              <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                              <td align="left" class="detalle_medio">Tama&ntilde;o de foto peque&ntilde;a: <span class="detalle_medio_bold">
                                <input name="foto_chica_ancho" type="text" class="detalle_medio" id="foto_chica_ancho" value="<?= $foto_chica_ancho ?>" size="5" />
                              </span>                              <strong>px</strong> ancho.</td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                              <td align="left" class="detalle_medio">Tama&ntilde;o de foto mediana: <span class="detalle_medio_bold">
                                <input name="foto_mediana_ancho" type="text" class="detalle_medio" id="foto_mediana_ancho" value="<?= $foto_mediana_ancho ?>" size="5" />
                              </span>                              <strong>px</strong> ancho.</td>
                            </tr>
                            <? if($rs_carpeta['foto']){ 
									if (file_exists($foto_ruta_chica.$rs_carpeta['foto'])){
										$foto_chica_ancho_real = getimagesize($foto_ruta_chica.$rs_carpeta['foto']);
									}
									if (file_exists($foto_ruta_mediana.$rs_carpeta['foto'])){
										$foto_mediana_ancho_real = getimagesize($foto_ruta_mediana.$rs_carpeta['foto']);
									}
							  ?>

                            <tr>
                              <td align="right" valign="top" class="detalle_medio">&nbsp;</td>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td align="left" valign="top"><table width="100" border="1" cellpadding="0" cellspacing="0" bordercolor="#BAEFE0">
                                        <tr>
                                          <td bgcolor="#BAEFE0"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                              <tr valign="middle" class="detalle_medio">
                                                <td align="right"><a style="color:#C61E00; font-size:10px; font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif" href="javascript:eliminar_foto();">Eliminar</a></td>
                                                <td width="10" align="left"><a href="javascript:eliminar_foto();"><img src="../../imagen/b_drop.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                        <tr>
                                          <td><? $foto_seccion =& new obj0001(0,$foto_ruta_mediana,$rs_carpeta['foto'],'','','','','','','','wmode=opaque',''); ?></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td align="left" valign="top"><table width="370" border="0" cellpadding="5" cellspacing="0">
                                      <tr>
                                        <td width="74" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio"><strong>Archivo</strong></td>
                                        <td colspan="2" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold" ><?=$rs_carpeta['foto']?></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio">&nbsp;</td>
                                        <td width="210" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio" ><a href="<?= $foto_ruta_chica.$rs_carpeta['foto']?>" target="_blank">Chica</a> </td>
                                        <td width="210" align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio" ><a href="<?= $foto_ruta_mediana.$rs_carpeta['foto']?>" target="_blank">Mediana</a></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio"><strong>Ancho</strong></td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold"><?=$foto_chica_ancho_real[0]?>
                                          px</td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold"><?=$foto_mediana_ancho_real[0]?>
                                          px</td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio"><strong>Alto</strong></td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold"><?=$foto_chica_ancho_real[1]?>
                                          px </td>
                                        <td align="right" valign="middle" bgcolor="#D8F6EE" class="detalle_medio_bold"><?=$foto_mediana_ancho_real[1]?>
                                          px</td>
                                      </tr>
                                    </table></td>
                                  </tr>
                              </table></td>
                            </tr>
							<? }; //fin foto?>
                            <tr>
                              <td align="right" valign="top" class="detalle_medio">Ordenar: </td>
                              <td align="left"><label>
                                <select name="campo_orden" class="detalle_medio" id="campo_orden" style="width:200px;">
                                  <option value="fecha_alta" <? if($rs_carpeta['campo_orden'] == "fecha_alta"){ echo "selected"; } ?>>Fecha de Alta</option>
                                  <option value="titulo" <? if($rs_carpeta['campo_orden'] == "titulo"){ echo "selected"; } ?>>Alfabetico</option>
                                  <option value="orden" <? if($rs_carpeta['campo_orden'] == "orden"){ echo "selected"; } ?>>N&ordm; de Orden</option>
                              </select>
                                <select name="ordenamiento" class="detalle_medio" id="ordenamiento">
                                  <option value="ASC" <? if($rs_carpeta['ordenamiento'] == "ASC"){ echo "selected"; } ?>>Asc.</option>
								  <option value="DESC" <? if($rs_carpeta['ordenamiento'] == "DESC"){ echo "selected"; } ?>>Desc.</option>
                                </select>
                              </label></td>
                            </tr>
                            
                          </table></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr bgcolor="#D8F6EE" class="detalle_medio">
                          <td width="108" align="right" class="detalle_medio_bold">&nbsp;</td>
                          <td width="560" align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                            <input name="Submit223" type="button" class="detalle_medio_bold" onclick="window.open('carpeta_ver.php','_self');" value="   &lt;&lt;  Ver &aacute;rbol     " />
                            </span>
                          <input name="Submit22" type="button" class="detalle_medio_bold" onclick="validar_form();" value="  &gt;&gt;  Guardar    " /></td>
                        </tr>
                    </table>
                  </form><br />

                      <form action="#carpeta" method="post" name="form_categoria" id="form_categoria">
                        <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="37" bgcolor="#FFDDBC" class="titulo_medio_bold">Cambiar de lugar   la carpeta: <a name="carpeta" id="carpeta"></a><span class="detalle_chico" style="color:#FF0000">
                              <input name="accion" type="hidden" id="accion" value="1" />
                            </span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td align="left" bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <?

$query_mod6_lista2 = "SELECT B.nombre, A.idcarpeta_padre
FROM carpeta A
INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
WHERE A.idcarpeta = $rs_carpeta[idcarpeta_padre] AND B.ididioma = 1
ORDER BY B.nombre";
$rs_mod6_lista2 = mysql_fetch_assoc(mysql_query($query_mod6_lista2));
		
?>

<script language="JavaScript" type="text/javascript">

function mod6_validarRegistro(){
	formulario = document.form_categoria;
	if (formulario.mod6_idcarpeta.value == "") {
		alert("Debe seleccionar una categoria");
	} else {	
		formulario.accion.value = 'mod6_insertar';
		formulario.submit();
	};
};

function mod6_select_idcarpeta(idcat)
{			
	formulario = document.form_categoria;
	formulario.submit();
}
                        </script>
                                
                                <tr class="detalle_medio_bold">
                                  <td width="110" align="right" valign="middle" class="detalle_medio_bold">Mover desde: </td>
                                  <td width="546" valign="middle" class="titulo_medio_bold">&nbsp;</td>
                                </tr>
                                <tr class="detalle_medio">
                                  <td align="right" valign="middle" class="detalle_medio">Carpeta actual: </td>
                                  <td width="546" valign="middle" class="detalle_medio"><?
								  		if($rs_mod6_lista2['nombre'] == ""){
											$ruta = "<b>[Carpeta Raíz]</b>";
										}else{
											$ruta = $rs_mod6_lista2['nombre'];
										}
										
										//AVERIGUO RUTA COMPLETA - NIVEL 1
										$query_1 = "SELECT B.nombre, A.idcarpeta_padre
										FROM carpeta A
										INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
										WHERE B.ididioma = 1 AND A.idcarpeta = '$rs_mod6_lista2[idcarpeta_padre]'
										LIMIT 1";
										$result_query_1 = mysql_query($query_1);
										while($rs_query_1 = mysql_fetch_assoc($result_query_1)){ 
											
											$ruta = $rs_query_1['nombre']." » ".$ruta;
											
											//NIVEL 2
											$query_2 = "SELECT B.nombre, A.idcarpeta_padre
											FROM carpeta A
											INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
											WHERE B.ididioma = 1 AND A.idcarpeta = '$rs_query_1[idcarpeta_padre]'
											LIMIT 1";
											$result_query_2 = mysql_query($query_2);
											while($rs_query_2 = mysql_fetch_assoc($result_query_2)){ 
											
												$ruta = $rs_query_2['nombre']." » ".$ruta;
												
												//NIVEL 3
												$query_3 = "SELECT B.nombre, A.idcarpeta_padre
												FROM carpeta A
												INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
												WHERE B.ididioma = 1 AND A.idcarpeta = '$rs_query_2[idcarpeta_padre]'
												LIMIT 1";
												$result_query_3 = mysql_query($query_3);
												while($rs_query_3 = mysql_fetch_assoc($result_query_3)){ 
												
													$ruta = $rs_query_3['nombre']." » ".$ruta;
												
												}
											
											}
											
											
										}//FIN AVERIGUO RUTA COMPLETA
										
										//IMPRIMO RUTA
										echo $ruta;
								  ?></td>
                                </tr>

                                <tr>
                                  <td width="110" align="right" valign="middle" class="detalle_medio_bold">Mover a: </td>
                                  <td valign="middle" class="style2">&nbsp;</td>
                                </tr>
                            </table>
                              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                                <script language="JavaScript" type="text/javascript">
var i;
function cambia(paso){  

    var formulario = document.form_categoria; 
	var carpeta;
	var oCntrl;
	
	switch(paso){
		case 1:
			//alert("paso 1");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display  = "none";
			document.getElementById("mod6_idcarpeta3").value  = '';
			
			carpeta = formulario.mod6_idcarpeta.value;
			oCntrl = formulario.mod6_idcarpeta2;
			break;
			
		case 2:
			//alert("paso 2");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display = "block";
			
			carpeta = formulario.mod6_idcarpeta2.value;
			oCntrl = formulario.mod6_idcarpeta3;
			break;	
			
	}   
   
	//alert(carpeta);
	var txtVal = carpeta;
	
	while(oCntrl.length > 0) oCntrl.options[0]=null;  
	i = 0; 
	oCntrl.clear;  
	
	var selOpcion=new Option("--- Seleccionar Carpeta ---", '');  
	eval(oCntrl.options[i++]=selOpcion);  
	 
	<?
	$query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre, A.idcarpeta_padre
	FROM carpeta A
	INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	WHERE A.estado <> 3 AND A.idcarpeta_padre != '0' AND B.ididioma = '1'
	ORDER BY B.nombre";
	$result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
	while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2)){
	?>
	
	if ("<?= $rs_mod6_idcarpeta2['idcarpeta_padre'] ?>" == txtVal){  
		var selOpcion=new Option("<?= $rs_mod6_idcarpeta2['nombre'] ?>", "<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>");  
		eval(oCntrl.options[i++]=selOpcion);  
	}  

	<? } ?> 
}  
   
                              </script>
                                <tr>
                                  <td><div id="tr_carpeta">
                                      <div id="colum_carpeta">Carpeta 1&ordm;</div>
                                    <div id="colum_selector"><span class="style10">
                                        <select name="mod6_idcarpeta" class="detalle_medio" id="mod6_idcarpeta" onchange="cambia(1)">
                                          <option value="0" selected="selected">--- Seleccionar Carpeta ---</option>
                                          <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
	  {
	  	if ($carpeta1 == $rs_mod6_idcarpeta['idcarpeta'])
		{
			$mod6_sel_idcarpeta = "selected";
		}else{
			$mod6_sel_idcarpeta = "";
		}
?>
                                          <option  <? echo $mod6_sel_idcarpeta ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                                          <?= $rs_mod6_idcarpeta['nombre'] ?>
                                          </option>
                                          <?  } ?>
                                        </select>
                                      </span></div>
                                  </div>
                                      <div style="clear:left; height:1px;"></div>
                                    <div id="tr_carpeta2" style="display:none">
                                        <div id="colum_carpeta">Carpeta 2&ordm;</div>
                                      <div id="colum_selector">
                                          <select name="mod6_idcarpeta2" class="detalle_medio" id="mod6_idcarpeta2" onchange="cambia(2)">
                                          </select>
                                        </div>
                                    </div>
                                    <div style="clear:left; height:1px;"></div>
                                    <div id="tr_carpeta3" style="display:none">
                                        <div id="colum_carpeta">Carpeta 3&ordm;</div>
                                      <div id="colum_selector">
                                          <select name="mod6_idcarpeta3" class="detalle_medio" id="mod6_idcarpeta3">
                                          </select>
                                        </div>
                                    </div>
                                    <div style="clear:left; height:1px;"></div></td>
                                </tr>
                              </table>
                              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                                <tr>
                                  <td width="111" class="detalle_medio">&nbsp;</td>
                                  <td width="545"><span class="style2">
                                    <input name="Submit2" type="button" class="detalle_medio_bold" onclick="javascript:mod6_validarRegistro()" value="  &gt;&gt;  Modificar   " />
                                  </span></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table>
					</form><br />

                        <form id="form_idioma" name="form_idioma" method="post" action="" enctype="multipart/form-data">
                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr bgcolor="#999999">
                              <td height="40" bgcolor="#FFE697" class="titulo_medio_bold">&nbsp; Informaci&oacute;n de la carpeta: <a name="idioma" id="idioma"></a><span class="detalle_chico" style="color:#FF0000">
                                <input name="estado_idioma" type="hidden" id="estado_idioma" value="" />
                                <span class="detalle_chico" style="color:#FF0000">
                                <input name="ididioma" type="hidden" id="ididioma" value="" />
                              </span></span></td>
                            </tr>
                            <tr>
                              <td align="left"><table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
                                  <? 					
						$query_idioma = "SELECT A.*, B.titulo_idioma, A.estado as estado_idioma
						FROM carpeta_idioma_dato A
						LEFT JOIN idioma B ON B.ididioma = A.ididioma
						WHERE A.idcarpeta = '$idcarpeta' AND B.estado = '1'
						ORDER BY A.ididioma";
						$result_idioma = mysql_query($query_idioma);
						while($rs_idioma = mysql_fetch_assoc($result_idioma)){			
						?>
                                  <tr>
                                    <td bgcolor="#FFECB3" class="detalle_medio_bold">Idioma: <a name="<?= $ididioma ?>" id="<?= $ididioma ?>"></a><a href="categoria_editar_idioma.php?idcategoria=<?= $idcategoria ?>&amp;ididioma=<?=$rs_idioma['ididioma']?>" target="_blank" class="style10"></a></td>
                                    <td width="504" bgcolor="#FFECB3" class="detalle_medio_bold"><?=$rs_idioma['titulo_idioma']?></td>
                                    <td width="20" align="center" valign="middle" bgcolor="#FFECB3" class="detalle_medio_bold">
										<? if($rs_idioma['estado_idioma'] == 1){ ?>
                                        <a href="javascript:cambiar_estado(2,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado.png" width="15" height="16" border="0" /></a>
                                        <? }else{?>
                                        <a href="javascript:cambiar_estado(1,<?= $rs_idioma['ididioma'] ?>);"><img src="../../imagen/b_habilitado_off.png" width="15" height="16" border="0" /></a>
                                        <? } ?>                                    </td>
                                    <td width="19" align="center" valign="middle" bgcolor="#FFECB3" class="detalle_medio_bold"><a href="carpeta_editar_idioma.php?idcarpeta=<?= $idcarpeta ?>&amp;ididioma=<?=$rs_idioma['ididioma']?>&forma=<?= $forma ?>" target="_self" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                                  </tr>
                                  <tr>
                                    <td width="105" align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Nombre:</td>
                                    <td colspan="3" bgcolor="#FFF5D7" class="detalle_medio"><?=$rs_idioma['nombre']?></td>
                                  </tr>
                                  <? if($rs_parametro['estado_breve_descripcion'] == 1){ ?>
                                  <tr>
                                    <td align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Breve descripci&oacute;n:</td>
                                    <td colspan="3" bgcolor="#FFF5D7" class="detalle_medio"><?= stripslashes($rs_idioma['breve_descripcion']) ?></td>
                                  </tr>
                                  <? } ?>
                                  <? if($rs_parametro['estado_contenido'] == 1){ ?>
                                  <tr>
                                    <td align="left" valign="top" bgcolor="#FFF5D7" class="detalle_medio">Contenido:</td>
                                    <td colspan="3" bgcolor="#FFF5D7" class="detalle_medio"><?= html_entity_decode($rs_idioma['contenido'], ENT_QUOTES); ?></td>
                                  </tr>
                                  <? } ?>
                                  <tr>
                                    <td height="12" colspan="4" bgcolor="#FFF5D7" class="detalle_medio"></td>
                                  </tr>
                                  <? 					
							};
?>
                              </table></td>
                            </tr>
                          </table>
                        </form><br />

                    <form id="form_sede" name="form_sede" method="post" action="" enctype="multipart/form-data">
                      <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="37" bgcolor="#FFDDBC" class="titulo_medio_bold">Propiedades de la carpeta: <a name="propiedades" id="propiedades"></a><span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td align="left" bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <?

$query_mod6_lista2 = "SELECT B.nombre
FROM carpeta A
INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
WHERE A.idcarpeta = $rs_carpeta[idcarpeta_padre] AND B.ididioma = 1
ORDER BY B.nombre";
$rs_mod6_lista2 = mysql_fetch_assoc(mysql_query($query_mod6_lista2));
		
?>
                              <script language="JavaScript" type="text/javascript">

function mod6_validarRegistro(){
	formulario = document.form_categoria;
	if (formulario.mod6_idcarpeta.value == "") {
		alert("Debe seleccionar una categoria");
	} else {	
		formulario.accion.value = 'mod6_insertar';
		formulario.submit();
	};
};

function mod6_select_idcarpeta(idcat)
{			
	formulario = document.form_categoria;
	formulario.submit();
}
                        </script>
                              <tr>
                                <td width="100" align="right" valign="top" class="detalle_medio_bold">Sucursales: </td>
                                <td width="556" align="left" valign="top" class="detalle_medio">
								<table width="100%" border="0" cellspacing="0" cellpadding="2">
								<?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
								$query_sel = "SELECT idsede
								FROM carpeta_sede
								WHERE idcarpeta = $idcarpeta AND idsede = $rs_sede[idsede]";
								if(mysql_num_rows(mysql_query($query_sel))>0){
									$sel="checked";
								}else{
									$sel="";
								}
								?>
                                  <tr>
                                    <td width="5%"><input type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?= $sel ?> <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?> /></td>
                                    <td width="95%" class="detalle_medio"><?= $rs_sede['titulo'] ?></td>
                                  </tr>
								<? 
								$c++;
								} 
								?>
                                </table>                                  </td>
                              </tr>
                              

                              <tr>
                                <td width="100" align="right" valign="top" class="style2"><input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" /></td>
                                <td align="left" valign="middle" class="style2"><input name="Submit23" type="button" class="detalle_medio_bold" onclick="javascript:validar_sede()" value="  &gt;&gt;  Modificar   " /></td>
                              </tr>
                          </table></td>
                        </tr>
                      </table>
                    </form><br />
					<form enctype="multipart/form-data" action="" method="post" id="form_descarga" name="form_descarga">
				  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                      <td height="40" colspan="3" bgcolor="#9DBFFF" class="titulo_medio_bold">Descargas<a name="descarga" id="descarga"></a>
                      <input name="estado_descarga" type="hidden" id="estado_descarga" />
                      <input name="iddescarga" type="hidden" id="iddescarga" />
                      <input name="estado_restringido" type="hidden" id="estado_restringido" />
                      <input name="eliminar_iddescarga" type="hidden" id="eliminar_iddescarga" /></td>
                    </tr>
                    <tr>
                      <td width="92%" height="32" bgcolor="#BBD2FF" class="detalle_medio_bold">Nueva descarga para todos los productos y secciones de esta carpeta </td>
                      <td width="4%" align="center" valign="middle" bgcolor="#BBD2FF" class="detalle_medio_bold"><a href="carpeta_editar.php?idcarpeta=<?= $idcarpeta ?>"><img src="../../imagen/iconos/refresh.png" width="20" height="20" border="0" /></a></td>
                      <td width="4%" bgcolor="#BBD2FF" class="detalle_medio_bold">
					  <a href="../descarga/descarga_nueva_individual.php?idcarpeta=<?= $idcarpeta ?>" rel="widht:400;height:300" id="d1" class="desc" title="" ><img src="../../imagen/iconos/add_download.png" width="20" height="20" border="0" /></a></td>
                    </tr>
                    <tr>
                      <td colspan="3" bgcolor="#E1EDFF"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                        <tr>
                          <td width="2%" height="25" class="detalle_medio_bold">&nbsp;</td>
                          <td width="31%" class="detalle_medio_bold">Titulo</td>
                          <td width="26%" height="25" class="detalle_medio_bold">Archivo</td>
                          <td width="12%" height="25" class="detalle_medio_bold">Tama&ntilde;o</td>
                          <td width="15%" height="25" class="detalle_medio_bold">Tipo</td>
                          <td width="14%" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
						<?
						
						$query_descarga = "SELECT *
						FROM descarga
						WHERE idcarpeta = '$idcarpeta'
						ORDER BY idseccion";
						$result_descarga = mysql_query($query_descarga);
						$cant_result = mysql_num_rows($result_descarga);
						while($rs_descarga = mysql_fetch_assoc($result_descarga)){
						
						?>
                        <tr>
                          <td height="30"><img src="../../../imagen/iconos/flecha_cursos.gif" width="5" height="5" /></td>
                          <td height="30" class="detalle_11px"><?= $rs_descarga['titulo'] ?></td>
                          <td height="30" class="detalle_11px"><?= $rs_descarga['archivo'] ?></td>
                          <td height="30" class="detalle_11px"><? 
						
							if(file_exists($ruta_descarga.$rs_descarga['archivo'])){ 
								$peso_kb = filesize($ruta_descarga.$rs_descarga['archivo'])/1024;

								if($peso_kb < 1024){
									echo number_format($peso_kb, 2)." kb.";
								}else{
									echo number_format($peso_kb/1024, 2)." mb.";
								}
							}
						
						?></td>
                          <td height="30" class="detalle_11px">
						  <?
						   
						  $query = "SELECT B.titulo AS titulo_tipo
						  FROM descarga A
						  INNER JOIN descarga_tipo B ON A.idtipo_descarga = B.iddescarga_tipo
						  WHERE B.iddescarga_tipo = '$rs_descarga[idtipo_descarga]' "; 
						  $rs_descarga_tipo = mysql_fetch_assoc(mysql_query($query));
						  echo $rs_descarga_tipo['titulo_tipo'];
						  
						  ?>
						  </td>
                          <td height="30" align="left">
						  <? 
						  
						  	 if($rs_descarga['estado'] == 1){ 
						  		echo '<a href="javascript:cambiar_estado_descarga(2,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/b_habilitado.png" width="15" height="16" border="0" /></a>';
						     }else{ 
						  		echo '<a href="javascript:cambiar_estado_descarga(1,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/b_habilitado_off.png" width="15" height="16" border="0" /></a>';
						     } 
						   
						  ?>&nbsp; <?
						  
						  	if($rs_descarga['restringido'] == 1){
						  		echo '<a href="javascript:cambiar_restringido_descarga(2,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/s_rights.png" width="16" height="16" border="0" /></a>';
						  	}else{
						  		echo '<a href="javascript:cambiar_restringido_descarga(1,'.$rs_descarga['iddescarga'].');"><img src="../../imagen/s_rights_b.png" width="14" height="14" border="0" /></a>';
							}
						  
						  ?>&nbsp;<a href="../descarga/descarga_editar.php?iddescarga=<?= $rs_descarga['iddescarga'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a>						  <? 
						  		
						  	echo '<a href="javascript:eliminar_descarga('.$rs_descarga['iddescarga'].');"><img src="../../imagen/trash.png" width="15" height="16" border="0" /></a>';
						  ?>						  </td>
                        </tr>
						<? } ?>
						
						<? if($cant_result == 0){ ?>
                        <tr>
                          <td height="40" colspan="6" align="center" valign="middle" class="detalle_medio">No hay descargas disponibles. </td>
                        </tr>
						<? } ?>
                      </table></td>
                    </tr>
                  </table>
				  </form>
                  </td>
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