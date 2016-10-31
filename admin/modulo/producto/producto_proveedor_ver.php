<? include ("../../0_mysql.php"); 
	
	$idproducto_proveedor = $_GET['idproducto_proveedor'];
	$estado = $_GET['estado'];
	
	$accion = $_POST['accion'];
	$titulo = $_POST['titulo'];
	$comentario = $_POST['comentario'];
	
		
	if( $accion == "ingresar" ){
			
		$query_ingresar = "INSERT INTO producto_proveedor (
		  titulo
		, comentario
		) VALUES (
		  '$titulo'
		, '$comentario'
		)";
		mysql_query($query_ingresar);
		
	};
	
	if(isset($_GET['estado'])){
		$query_estado = "UPDATE producto_proveedor 
		SET estado = '$estado'
		WHERE idproducto_proveedor = '$idproducto_proveedor'
		LIMIT 1 ";
		mysql_query($query_estado);
	}	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

function ingresar_marca(){
	formulario = document.form_titular;

	if(formulario.titulo.value == ''){
		alert("Debe ingresar el nombre del proveedor.");
	}else{
		formulario.accion.value = "ingresar";
		formulario.submit();
	}

};


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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto Proveedor - Ver </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#FFB76F">
                        <td width="4%" height="40" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">ID</td>
                        <td width="84%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Titulo</td>
                        <td height="40" colspan="4" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
                      <?  	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM producto_proveedor 
		WHERE estado <> 3 
		ORDER BY titulo
		";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			
			$hay_lista = true;					  
	  
?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td width="4%" align="center"><?=$rs_lista['idproducto_proveedor']?></td>
                        <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['titulo']; ?></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?estado=2&amp;idproducto_proveedor=<?= $rs_lista['idproducto_proveedor'] ?>"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                            <? } else { ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?estado=1&amp;idproducto_proveedor=<?= $rs_lista['idproducto_proveedor'] ?>"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                            <? } ?></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="producto_proveedor_editar.php?idproducto_proveedor=<?= $rs_lista['idproducto_proveedor'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="<?= $_SERVER['PHP_SELF'] ?>?estado=3&amp;idproducto_proveedor=<?= $rs_lista['idproducto_proveedor'] ?>" class="style10"><img src="../../imagen/b_drop.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                      </tr>
<?

	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ 
	
?>
                      <tr align="center" valign="middle">
                        <td colspan="6"  height="50" bgcolor="·fff0e1" class="detalle_medio_bold">No se han encontrado proveedores.</td>
                      </tr>
                      <? };
	?>
                    </table>
                      <br />
                      <form action="" method="post" name="form_titular" id="form_titular">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingresar nuevo proveedor:
                              <input name="accion" type="hidden" id="accion" value="0" /></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#fff0e1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                                </tr>
                                <tr>
                                  <td width="21%" align="right" valign="middle" class="detalle_medio">Nombre del Proveedor:</td>
                                  <td width="79%" align="left" valign="top"><label><input name="titulo" type="text" class="detalle_medio" id="titulo" style="width:98%" /></label></td>
                                </tr>
                                <tr>
                                  <td width="21%" align="right" valign="top" class="detalle_medio">Comentarios:</td>
                                  <td align="left" valign="top">
                                  <textarea name="comentario" rows="6" class="detalle_medio" id="comentario"  style="width:98%" ></textarea></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                  <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="ingresar_marca();" value="   Ingresar &raquo;  " /></td>
                                </tr>
                            </table></td>
                          </tr>
                        </table>
                        <br />
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