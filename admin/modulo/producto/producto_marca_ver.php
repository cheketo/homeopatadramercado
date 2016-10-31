<? include ("../../0_mysql.php"); 
	
	
	$accion = $_POST['accion'];
	$idproducto_marca = $_GET['idproducto_marca'];
	$estado = $_GET['estado'];
	$titulo = $_POST['titulo'];
		
if( $accion == "ingresar_marca" ){
		
	$query_ingresar = "INSERT INTO producto_marca (
	  titulo
	) VALUES (
	  '$titulo'
	)";
	mysql_query($query_ingresar);
	
	echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."')</script>";
	
};
if(isset($_GET['estado'])){
	$query_estado = "UPDATE producto_marca 
	SET estado = '$estado'
	WHERE idproducto_marca = '$idproducto_marca'
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
		alert("Debe ingresar una marca");
	}else{
		formulario.accion.value = "ingresar_marca";
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto Marca - Ver </td>
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
                        <td height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Titulo</td>
                        <td height="40" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">Estado</td>
                        <td height="40" colspan="3" bgcolor="#FFDDBC" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
                      <?  	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM producto_marca 
		WHERE estado <> 3 
		ORDER BY titulo
		";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista))
			{ $hay_lista = true;					  
	  
?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td width="4%" align="center"><?=$rs_lista['idproducto_marca']?></td>
                        <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['titulo']; ?></td>
                        <td width="9%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?estado=2&amp;idproducto_marca=<?= $rs_lista['idproducto_marca'] ?>"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                            <? } else { ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?estado=1&amp;idproducto_marca=<?= $rs_lista['idproducto_marca'] ?>"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                            <? } ?></td>
                        <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="producto_marca_editar.php?idproducto_marca=<?= $rs_lista['idproducto_marca'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                        <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="<?= $_SERVER['PHP_SELF'] ?>?estado=3&amp;idproducto_marca=<?= $rs_lista['idproducto_marca'] ?>" class="style10"><img src="../../imagen/b_drop.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                      </tr>
                      <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                      <tr align="center" valign="middle">
                        <td colspan="6"  height="50" bgcolor="·fff0e1" class="detalle_medio_bold">No se han encontrado marcas.</td>
                      </tr>
                      <? };
	?>
                    </table>
                      <br />
                      <form action="" method="post" name="form_titular" id="form_titular">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingresar nueva marca:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#fff0e1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                                </tr>
                                <tr>
                                  <td width="15%" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                  <td width="85%" align="left" valign="top"><label>
                                    <input name="titulo" type="text" class="detalle_medio" id="titulo" size="60" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                  <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="ingresar_marca();" value=" &gt;&gt; Ingresar " /></td>
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