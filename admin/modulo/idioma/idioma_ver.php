<? include ("../../0_mysql.php"); 

if ($accion == "agregar_idioma"){
	
	//Ve todos los productos que tienen el idioma español
	$query_producto = "SELECT A.idproducto, A.titulo
	FROM producto_idioma_dato A	
	WHERE A.ididioma = '1' ";
	
	$result_producto = mysql_query($query_producto);
	while($rs_producto = mysql_fetch_assoc($result_producto)){				
		
		$query_idioma = "SELECT A.ididioma
		FROM idioma A
		WHERE A.estado = '1'
		ORDER BY A.ididioma";
		$result_idioma = mysql_query($query_idioma);
		while($rs_idioma = mysql_fetch_assoc($result_idioma)){	
								
			//ingreso en tabla producto idioma
			$query_idioma_ingreso = "INSERT INTO producto_idioma_dato (
			  idproducto
			, ididioma
			, titulo
			) VALUES (
			  '$rs_producto[idproducto]'
			, '$rs_idioma[ididioma]'
			, '$rs_producto[titulo]'
			)";
			mysql_query($query_idioma_ingreso);
			
		}//Fin while de los idiomas
	}//Fin while producto
	
	//Ve todos las categorias que tienen el idioma español
	$query_categoria = "SELECT A.idcarpeta, A.nombre
	FROM carpeta_idioma_dato A	
	WHERE A.ididioma = '1' ";
	$result_categoria = mysql_query($query_categoria);
	while($rs_categoria = mysql_fetch_assoc($result_categoria)){			
		
		$query_idioma = "SELECT A.ididioma
		FROM idioma A
		WHERE A.estado = '1'
		ORDER BY A.ididioma";
		$result_idioma = mysql_query($query_idioma);
		while($rs_idioma = mysql_fetch_assoc($result_idioma)){	
								
			//ingreso en tabla producto idioma
			$query_idioma_ingreso = "INSERT INTO carpeta_idioma_dato (
			  idcarpeta
			, ididioma
			, nombre
			) VALUES (
			  '$rs_categoria[idcarpeta]'
			, '$rs_idioma[ididioma]'
			, '$rs_categoria[nombre]'
			)";
			mysql_query($query_idioma_ingreso);
			
		}//Fin while de los idiomas
	}//Fin while categoria
	
	//Ve todos las seccion que tienen el idioma español
	$query_seccion = "SELECT A.idseccion, A.titulo
	FROM seccion_idioma_dato A	
	WHERE A.ididioma = '1'
	";
	$result_seccion = mysql_query($query_seccion);
	while($rs_seccion = mysql_fetch_assoc($result_seccion)){			
		
		$query_idioma = "SELECT A.ididioma
		FROM idioma A
		WHERE A.estado = '1'
		ORDER BY A.ididioma";
		$result_idioma = mysql_query($query_idioma);
		while($rs_idioma = mysql_fetch_assoc($result_idioma)){	
								
			//ingreso en tabla producto idioma
			$query_idioma_ingreso = "INSERT INTO seccion_idioma_dato (
			  idseccion
			, ididioma
			, titulo
			) VALUES (
			  '$rs_seccion[idseccion]'
			, '$rs_idioma[ididioma]'
			, '$rs_seccion[titulo]'
			)";
			mysql_query($query_idioma_ingreso);
			
		}//Fin while de los idiomas
	}//Fin while seccion
	
	echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."');</script>";
};

	if(isset($_GET['estado'])){
		$estado = $_GET['estado'];
		$ididioma = $_GET['ididioma'];
		
		$query_estado = "UPDATE idioma 
		SET estado = '$estado'
		WHERE ididioma = $ididioma
		LIMIT 1 ";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."')</script>";
	};
	
	if(isset($_GET['publicar'])){
		$publicar = $_GET['publicar'];
		$ididioma = $_GET['ididioma'];
		
		$query_estado = "UPDATE idioma 
		SET publicar = '$publicar'
		WHERE ididioma = $ididioma
		LIMIT 1 ";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."')</script>";
	};
	
	if(isset($_GET['defecto'])){
		$publicar = $_GET['defecto'];
		$ididioma = $_GET['ididioma'];
		
		$query_estado = "UPDATE idioma 
		SET valor_defecto = '$defecto'
		WHERE ididioma = $ididioma
		LIMIT 1 ";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."')</script>";
	};
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>


<script language="javascript">
function agregar_idioma(){
	formulario = document.form_idioma;
	formulario.accion.value = "agregar_idioma";
	formulario.submit();
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
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Idioma </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <form action="" method="post" name="form_idioma" id="form_idioma">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#FFB76F">
                          <td width="4%" height="40" align="center" bgcolor="#FFDFBF" class="detalle_medio_bold">ID</td>
                          <td width="32%" height="40" bgcolor="#FFDFBF" class="detalle_medio_bold">Idioma<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                          <td width="28%" height="40" bgcolor="#FFDFBF" class="detalle_medio_bold">Publicar</td>
                          <td width="26%" height="40" bgcolor="#FFDFBF" class="detalle_medio_bold"><label onclick="return (document.getElementById('checkbox_row_7') ? false : true)" for="checkbox_row_7">Valor por defecto</label>
                          &nbsp;</td>
                          <td width="10%" height="40" colspan="2" align="center" bgcolor="#FFDFBF" class="detalle_medio_bold">Estado</td>
                        </tr>
                        <?  	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM idioma
		WHERE estado <> 3 ";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 	  
?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_chico" >
                            <?=$rs_lista['ididioma']?>                          </td>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['titulo_idioma']; ?></td>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><? if ($rs_lista['publicar'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                              <a href="<?= $_SERVER['PHP_SELF'] ?>?publicar=2&amp;ididioma=<?= $rs_lista['ididioma'] ?>"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                              <? } else { ?>
                              <a href="<?= $_SERVER['PHP_SELF'] ?>?publicar=1&amp;ididioma=<?= $rs_lista['ididioma'] ?>"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                              <? } ?></td>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><? if ($rs_lista['valor_defecto'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                            <a href="?defecto=2&amp;ididioma=<?= $rs_lista['ididioma'] ?>"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                            <? } else { ?>
                            <a href="?defecto=1&amp;ididioma=<?= $rs_lista['ididioma'] ?>"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                            <? } ?></td>
                          <td align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                              <a href="?estado=2&amp;ididioma=<?= $rs_lista['ididioma'] ?>"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                              <? } else { ?>
                              <a href="?estado=1&amp;ididioma=<?= $rs_lista['ididioma'] ?>"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                              <? } ?>                          </td>
                        </tr>
                        <?
		if($cont_colores == 0){
			$cont_colores = 1;
		}else{
			$cont_colores = 0;
		};
	}
?>
                    </table></td>
                  </tr>
                </table>
              </form></td>
        </tr>
        <tr>
          <td><span class="detalle_chico" style="color:#FF0000">
            <input name="Submit222" type="button" class="detalle_medio_bold" onclick="agregar_idioma();" value="Agregar Idiomas &raquo;" />
          </span></td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>