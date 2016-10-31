<? 
	include ("../../0_mysql.php"); 
	include ("../0_includes/0_crear_miniatura.php");

	//VARIABLES
	$idadmin_menu = $_GET['idadmin_menu'];
	$titulo = $_POST['titulo'];
	$orden = $_POST['orden'];
	$accion = $_POST['accion'];
	
	
	$mod6_idcategoria_admin = $_POST['mod6_idcategoria_admin'];
	$mod6_idcategoria_admin2 = $_POST['mod6_idcategoria_admin2'];
	$mod6_idcategoria_admin3 = $_POST['mod6_idcategoria_admin3'];
	
	//Variables de la foto
	$foto = $_POST['foto']; // es la imagen a ingresar
	$foto_ruta = "../../imagen/admin_panel/"; // la ruta donde se va a guardar la foto chica

	//ACTUALIZAR:
	if($accion == "update"){		
		// INCORPORACION DE FOTO
		if ($_FILES['foto']['name'] != ''){
	
			$archivo_ext = substr($_FILES['foto']['name'],-4);
			$archivo_nombre = substr($_FILES['foto']['name'],0,strrpos($_FILES['foto']['name'], "."));
			
			$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
			
				$querysel = "SELECT foto FROM admin_menu WHERE idadmin_menu = '$idadmin_menu' ";
				$rowfoto = mysql_fetch_row(mysql_query($querysel));
				
				if ( $rowfoto[0] ){
					if (file_exists($foto_ruta.$rowfoto[0])){
						unlink ($foto_ruta.$rowfoto[0]);
					}
				}
				
			$foto =  $idadmin_menu . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
			
			if (!copy($_FILES['foto']['tmp_name'], $foto_ruta . $foto)){ //si hay error en la copia de la foto
				$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
				echo "<script>alert('".$error."')</script>"; // se muestra el error.
			}else{
				$imagesize = getimagesize($foto_ruta.$foto);
			
				if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
					$peso = number_format((filesize($foto_ruta.$foto))/1024,2);
					
					if($peso==0){
						$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
						echo "<script>alert('".$error2."')</script>"; // se muestra el error.
					}else{						
				
						//ingreso de foto en tabla producto
						$query_upd = "UPDATE admin_menu SET 
						foto = '$foto' 	
						WHERE idadmin_menu = '$idadmin_menu'
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
			
			
		if($mod6_idcategoria_admin3){
			$mod6_sel_idcategoria_admin = $mod6_idcategoria_admin3;
		}else{
			if($mod6_idcategoria_admin2){
				$mod6_sel_idcategoria_admin = $mod6_idcategoria_admin2;
			}else{
				if($mod6_idcategoria_admin){
					$mod6_sel_idcategoria_admin = $mod6_idcategoria_admin;
				}
			}	
		}	
		if($mod6_sel_idcategoria_admin){
			$padre_fil = ", idcategoria_admin_padre = '$mod6_sel_idcategoria_admin'";
		}
		
		$query_update = "UPDATE admin_menu
		SET titulo = '$titulo'		
		, orden = '$orden'
		$padre_fil
		WHERE idadmin_menu	= $idadmin_menu LIMIT 1";
		mysql_query($query_update);
		
		/*
		// ABRIR VENTANA EDITAR CATEGORIA:		
		echo "<script>window.location.href('".$_SERVER['PHP_SELF']."?idadmin_menu=".$idadmin_menu."');</script>";
		*/

	};

	//CONSULTA:	
	$query_idadmin_menu = "SELECT A.*, B.titulo AS titulo_padre
	FROM admin_menu A 
	LEFT JOIN admin_menu B ON B.idadmin_menu = A.idadmin_menu_padre
	WHERE A.idadmin_menu = '$idadmin_menu' ";
	$result_idadmin_menu = mysql_query($query_idadmin_menu);
	$rs_idadmin_menu = mysql_fetch_assoc($result_idadmin_menu);
	  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">
	
	function mod6_select_idcategoria_admin(idcat){			
	formulario = document.form;
		formulario.submit();
	}
	
	function mod6_validarRegistro(){
	formulario = document.form;
		if (formulario.titulo.value == "") {
			alert("Debe tener un titulo.");
		} else {	
			formulario.accion.value = 'update';
			formulario.submit();
		};
	};

	function eliminar_foto(){
		formulario = document.form;
		if (confirm('¿ Está seguro que desea eliminar la foto ?')){
			formulario.accion.value = "eliminar_foto";
			formulario.submit();
		}
	}
	
</script>

</head>
<body>
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold"> Menu Iconos Admin  - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form" enctype="multipart/form-data">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Editar Icono Panel:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio">T&iacute;tulo</td>
                                <td valign="top" class="style2"><input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?=$rs_idadmin_menu['titulo']?>" size="60" /></td>
                              </tr>
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio">N&ordm; de Orden</td>
                                <td align="left" valign="middle" class="style2"><input name="orden" type="text" class="detalle_medio" id="orden" value="<?=$rs_idadmin_menu['orden']?>" size="5" /></td>
                              </tr>
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio">Foto</td>
                                <td align="left" valign="middle" class="style2"><input name="foto" type="file" class="detalle_medio" id="foto" size="50" /></td>
                              </tr>
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio_bold_black">&nbsp;</td>
                                <td align="left" valign="middle" class="style2">Tama&ntilde;o de foto 48 <strong>px</strong> ancho.</td>
                              </tr>
				<? if($rs_idadmin_menu['foto']){  
									if (file_exists($foto_ruta.$rs_idadmin_menu['foto'])){
										$foto_chica_ancho_real = getimagesize($foto_ruta.$rs_idadmin_menu['foto']);
									}
				
				?>
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio_bold_black">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td width="50%" align="left" valign="top"><table border="3" cellpadding="4" cellspacing="0" bordercolor="#FFDDBC">
                                        <tr>
                                          <td align="right" bgcolor="#FFDDBC"><a href="javascript:eliminar_foto();"><img src="../../imagen/cruz.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                                        </tr>
                                        <tr>
                                          <td bgcolor="#FFDDBC"><? $foto_seccion =& new obj0001(0,$foto_ruta,$rs_idadmin_menu['foto'],'','','','','','','','wmode=opaque',''); ?></td>
                                        </tr>
                                    </table></td>
                                    <td width="50%" align="center" valign="top"><table width="100%" border="0" align="left" cellpadding="5" cellspacing="0">
                                        <tr>
                                          <td width="74" align="right" valign="middle" bgcolor="#FFDDBC" class="detalle_medio">Archivo:</td>
                                          <td align="left" valign="middle" bgcolor="#FFDDBC" class="detalle_medio_bold style3"><?=$rs_idadmin_menu['foto']?></td>
                                        </tr>

                                        <tr>
                                          <td align="right" valign="middle" bgcolor="#FFDDBC" class="detalle_medio">Ancho:</td>
                                          <td align="left" valign="middle" bgcolor="#FFDDBC" class="detalle_medio_bold style3"><?=$foto_chica_ancho_real[0]?>
                                            px</td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" bgcolor="#FFDDBC" class="detalle_medio">Alto:</td>
                                          <td align="left" valign="middle" bgcolor="#FFDDBC" class="detalle_medio_bold style3"><?=$foto_chica_ancho_real[1]?>
                                            px </td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                </table></td>
                              </tr>
					<? } //fin si hay foto ?>
                            </table>
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                   <tr>
                                  <td width="90" align="right" valign="top" class="style2">&nbsp;</td>
                                  <td align="left" valign="middle" class="style2"><span class="detalle_chico" style="color:#FF0000">
                                    <input name="Submit22222" type="button" class="detalle_medio_bold" onClick="mod6_validarRegistro();" value="  &gt;&gt;  Modificar      " />
                                  </span></td>
                                </tr>
                                <?
	///////////////////////////////////////////////////
	/////////Fin Modulo Admin de incorporacion  ///////
	///////////////////////////////////////////////////	
?>
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