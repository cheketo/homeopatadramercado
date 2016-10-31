<? 
	include ("../../0_mysql.php"); 

	//VARIABLES
	$idadmin_menu = $_GET['idadmin_menu'];
	$titulo = $_POST['titulo'];
	$link = $_POST['link'];
	$orden = $_POST['orden'];
	$accion = $_POST['accion'];
	
	
	$mod6_idcategoria_admin = $_POST['mod6_idcategoria_admin'];
	$mod6_idcategoria_admin2 = $_POST['mod6_idcategoria_admin2'];
	$mod6_idcategoria_admin3 = $_POST['mod6_idcategoria_admin3'];


	//ACTUALIZAR:
	if($accion == "update"){		
		
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
			$padre_fil = ", idadmin_menu_padre = '$mod6_sel_idcategoria_admin'";
		}
		
		$query_update = "UPDATE admin_menu
		SET titulo = '$titulo'
		, link = '$link'		
		, orden = '$orden'
		$padre_fil
		WHERE idadmin_menu	= '$idadmin_menu'
		LIMIT 1";
		mysql_query($query_update);
		
		
		// ABRIR VENTANA EDITAR CATEGORIA:		
		echo "<script>window.location.href('admin_menu_ver.php#cat".$idadmin_menu."');</script>";

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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Menu Admin - Editar </td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Categoria Admin:<span class="detalle_chico" style="color:#FF0000">
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
                                <td width="90" align="right" valign="top" class="detalle_medio">Link</td>
                                <td valign="top" class="style2"><input name="link" type="text" class="detalle_medio" id="link" value="<?=$rs_idadmin_menu['link']?>" size="60" /></td>
                              </tr>
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio">N&deg; Orden</td>
                                <td align="left" valign="middle" class="style2"><input name="orden" type="text" class="detalle_medio" id="orden" value="<?=$rs_idadmin_menu['orden']?>" size="6" /></td>
                              </tr>
                            </table>
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td width="90" align="right" valign="middle" class="detalle_medio">Padre actual </td>
                                  <td valign="middle" class="titulo_medio_bold"><input name="padre" type="text" disabled="disabled" class="detalle_medio" id="padre" value="<?=$rs_idadmin_menu['titulo_padre']?>" size="30" /></td>
                                </tr>
                                <tr>
                                  <td width="90" align="right" valign="middle" class="detalle_medio">Categoria 1&ordm; </td>
                                  <td valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcategoria_admin" class="detalle_medio" id="mod6_idcategoria_admin" onchange="mod6_select_idcategoria_admin(this)">
                                      <option value="">--- Seleccionar Categoria ---</option>
                                      <? if( $mod6_idcategoria_admin == '0' ){ 
									  		$sel_sin_padre = "selected";
											unset($mod6_idcategoria_admin2);
											unset($mod6_idcategoria_admin3);
										 } else { 
										 	$sel_sin_padre = ""; 
									  }; ?>
                                      <option <?=$sel_sin_padre?> value="0">--- Categoria sin padre ---</option>
                                      <?
	  $query_mod6_idcategoria = "SELECT * 
	  FROM admin_menu 
	  WHERE estado <> 3 AND idadmin_menu_padre = '0' AND icono = 2
	  ORDER BY orden, titulo";
	  $result_mod6_idcategoria = mysql_query($query_mod6_idcategoria);
	  while ($rs_mod6_idcategoria = mysql_fetch_assoc($result_mod6_idcategoria))	  
	  {
	  	if ($mod6_idcategoria_admin == $rs_mod6_idcategoria['idadmin_menu']){
			$mod6_sel_idcategoria = "selected";
		}else{
			$mod6_sel_idcategoria = "";
		}
?>
                                      <option  <? echo $mod6_sel_idcategoria ?> value="<?= $rs_mod6_idcategoria['idadmin_menu'] ?>">
                                      <?= $rs_mod6_idcategoria['titulo'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                </tr>
                                <tr>
                                  <? if($mod6_idcategoria_admin){ ?>
                                  <td width="90" align="right" valign="middle" class="detalle_medio">Categoria2&ordm; </td>
                                  <td valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcategoria_admin2" class="detalle_medio" id="mod6_idcategoria_admin2" onchange="mod6_select_idcategoria_admin(this)">
                                      <option value="">--- Seleccionar Categoria ---</option>
                                      <?
	  $query_mod6_idcategoria2 = "SELECT * 
	  FROM admin_menu 
	  WHERE estado <> 3 AND idadmin_menu_padre = '$mod6_idcategoria_admin'  AND icono = 2
	  ORDER BY orden, titulo";
	  $result_mod6_idcategoria2 = mysql_query($query_mod6_idcategoria2);
	  while ($rs_mod6_idcategoria2 = mysql_fetch_assoc($result_mod6_idcategoria2))	  
	  {
	  	if ($mod6_idcategoria_admin2 == $rs_mod6_idcategoria2['idadmin_menu'])
		{
			$mod6_sel_idcategoria2 = "selected";
		}else{
			$mod6_sel_idcategoria2 = "";
		}
?>
                                      <option  <? echo $mod6_sel_idcategoria2 ?> value="<?= $rs_mod6_idcategoria2['idadmin_menu'] ?>">
                                      <?= $rs_mod6_idcategoria2['titulo'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                  <?  } ?>
                                </tr>
                                <tr>
                                  <? if($mod6_idcategoria_admin2){ ?>
                                  <td width="90" align="right" valign="middle" class="detalle_medio">Categoria 3&ordm; </td>
                                  <td valign="middle" class="style2"><span class="style10">
                                    <select name="mod6_idcategoria_admin3" class="detalle_medio" id="mod6_idcategoria_admin3" >
                                      <option value="">--- Seleccionar Categoria ---</option>
                                      <?
	  $query_mod6_idcategoria3 = "SELECT * 
	  FROM admin_menu 
	  WHERE estado <> 3 AND idadmin_menu_padre = '$mod6_idcategoria_admin2' AND icono = 2
	  ORDER BY orden, titulo";
	  $result_mod6_idcategoria3 = mysql_query($query_mod6_idcategoria3);
	  while ($rs_mod6_idcategoria3 = mysql_fetch_assoc($result_mod6_idcategoria3))	  
	  {
	  	if ($mod6_idcategoria_admin3 == $rs_mod6_idcategoria3['idadmin_menu'])
		{
			$mod6_sel_idcategoria3 = "selected";
		}else{
			$mod6_sel_idcategoria3 = "";
		}
?>
                                      <option  <? echo $mod6_sel_idcategoria3 ?> value="<?= $rs_mod6_idcategoria3['idadmin_menu'] ?>">
                                      <?= $rs_mod6_idcategoria3['titulo'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                  <?  }   ?>
                                </tr>
                                <? if($rs_idcategoria_admin['foto']){ 
									if (file_exists($foto_ruta.$rs_idcategoria_admin['foto'])){
										$foto_ancho_real = getimagesize($foto_ruta.$rs_idcategoria_admin['foto']);
									}
							  ?>
                                <? } ?>
                                <tr>
                                  <td align="right" valign="top" class="style2">&nbsp;</td>
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