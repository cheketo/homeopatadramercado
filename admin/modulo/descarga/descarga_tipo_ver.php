<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	
<?

	$accion = $_POST['accion'];
	$titulo = $_POST['titulo'];
	$estado = $_POST['estado'];
	$iddescarga_tipo = $_POST['iddescarga_tipo'];
	$eliminar = $_POST['eliminar'];
	
	//INGRESAR NUEVO
	if($accion == "ingresar"){
		
		$query_ingresar = "INSERT INTO descarga_tipo (
		  titulo
		, estado
		) VALUES (
		  '$titulo'
		, '1'
		)";
		mysql_query($query_ingresar);
	
	};
 

	//BORRAR	
	if($eliminar != '' && $eliminar != 0){
	
		$query_eliminar = "DELETE FROM descarga_tipo 
		WHERE iddescarga_tipo = '$eliminar'";
		mysql_query($query_eliminar);
	
	};
	
	//CAMBIAR ESTADO
	if($estado != ""){
		
		$query_estado = "UPDATE descarga_tipo SET 
		estado = '$estado'
		WHERE iddescarga_tipo = '$iddescarga_tipo'
		LIMIT 1 ";
		mysql_query($query_estado);
		
	};

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>


<script language="javascript">

	function validar_form(){
		formulario = document.form_titular;
		if(formulario.titulo.value == ''){
			alert("Debe ingresar un tipo de descarga");
		}else{
			formulario.accion.value = "ingresar";
			formulario.submit();
		}
	};

	function cambiar_estado(estado, id){
		formulario = document.form_titular;
		
		formulario.estado.value = estado;
		formulario.iddescarga_tipo.value = id;
		formulario.submit();
	};


function confirmar_eliminar(id){
	if (confirm('¿Esta seguro que desea eliminar el registro?')){
		formulario = document.form_titular;
		formulario.eliminar.value = id;
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
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Descarga tipo - Ver </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#FFB76F">
                        <td width="4%" height="40" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">ID</td>
                        <td width="84%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Tipo
                        <input name="estado" type="hidden" id="estado" />
                        <input name="iddescarga_tipo" type="hidden" id="iddescarga_tipo" />
                        <input name="eliminar" type="hidden" id="eliminar" /></td>
                        <td height="40" colspan="4" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
                      <?

		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM descarga_tipo
		WHERE estado <> 3 ";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 	  
?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td width="4%" align="center" class="detalle_chico"><?= $rs_lista['iddescarga_tipo'] ?>.</td>
                        <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['titulo']; ?></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>">
						<? 
						
						   if ($rs_lista['estado'] == '1') { 
                            	echo '<a href="javascript:cambiar_estado(2,'.$rs_lista['iddescarga_tipo'].');"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>';
                           } else { 
                           		echo '<a href="javascript:cambiar_estado(1,'.$rs_lista['iddescarga_tipo'].');"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>';  
						   } 
						   
						 ?>
						 </td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="descarga_tipo_editar.php?iddescarga_tipo=<?= $rs_lista['iddescarga_tipo'] ?>" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:confirmar_eliminar(<?= $rs_lista['iddescarga_tipo'] ?>);" class="style10"><img src="../../imagen/b_drop.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                      </tr>
                      <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                      <? }; ?>
                    </table>
                      <br />
                      <br />
                    
              <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese nuevo tipo de descarga:
                            <input name="accion" type="hidden" id="accion" /></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                                </tr>
                                <tr>
                                  <td width="18%" align="right" valign="top" class="detalle_medio">Tipo de descarga: </td>
                                  <td width="82%" align="left" valign="top"><label>
                                    <input name="titulo" type="text" class="detalle_medio" id="titulo" size="60" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                  <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form();" value="   Ingresar   " /></td>
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