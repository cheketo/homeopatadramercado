<? include ("../../0_mysql.php"); ?>				

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?
	//TOMO VARIABLE POR GET
	$accion = $_POST['accion'];
	$idba_anunciante = $_POST['id'];
	$estado = $_POST['estado'];		

			 
	//CAMBIAR ESTADO
	if($accion == "cambiar_estado"){
			
		$query_update = "UPDATE ba_anunciante
		SET estado = '$estado'
		WHERE idba_anunciante = '$idba_anunciante'
		LIMIT 1";
		mysql_query($query_update);
		
	}

		
	//BORRAR ANUNCIANTE
	if($accion == "eliminar"){
	
		//verifico que el anunciante no tenga banners cargados	
		$query_preliminar = "SELECT * 
		FROM ba_banner
		WHERE idba_anunciante = '$idba_anunciante' ";
		$rs_preliminar = mysql_num_rows(mysql_query($query_preliminar));

		if($rs_preliminar == 0){
			$query_eliminar = "DELETE FROM ba_anunciante WHERE idba_anunciante = '$idba_anunciante'";
			mysql_query($query_eliminar);
		}else{
			echo "<script>alert('No se puede eliminar el anunciante porque actualmente tiene banners cargados.');</script>";
		}
	
	}


?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

	function cambiar_estado(estado, id){
	formulario = document.form_titular;
	
	formulario.estado.value = estado;
	formulario.id.value = id;
	formulario.accion.value = "cambiar_estado";
	formulario.submit();
	};
	
	function confirmar_eliminar(id){
	formulario = document.form_titular;
		if (confirm('¿Esta seguro que desea eliminar el anunciante?')){
			formulario.accion.value = "eliminar";
			formulario.id.value = id;
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Anunciante - Ver </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">&nbsp; Ver anunciantes <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="" />
                            <input name="estado" type="hidden" id="estado" value="" />
                            <input name="id" type="hidden" id="id" value="" />
                          </span></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr bgcolor="#999999">
                                <td width="17" height="40" align="center" bgcolor="#FFE7CE" class="detalle_medio_bold">ID</td>
                                <td width="572" height="40" align="left" bgcolor="#FFE7CE" class="detalle_medio_bold">Anunciante</td>
                                <td height="40" colspan="3" align="center" valign="middle" bgcolor="#FFE7CE" class="detalle_medio">&nbsp;</td>
                              </tr>

							<?
								//Realiza consulta, para mostrar los datos enunciados en la tabla.
								$query_mod1_lista = "SELECT * 
								FROM ba_anunciante 
								ORDER BY idba_anunciante";
								
								$result_mod1_lista = mysql_query($query_mod1_lista);
								while ($rs_mod1_lista = mysql_fetch_assoc($result_mod1_lista)){
								
								$hay_lista = true;
							?>
								
                              <tr valign="top">
                                <td height="12" align="center" bgcolor="#FFF0E1" class="detalle_medio"><?= $rs_mod1_lista['idba_anunciante'] ?>
                                    <span class="detalle_medio_bold"><a name="<?= $rs_mod1_lista['idba_anunciante'] ?>" id="<?= $rs_mod1_lista['idba_anunciante'] ?>"></a></span></td>
                                <td height="12" bgcolor="#FFF0E1" class="detalle_medio"><?= $rs_mod1_lista['nombre'] ?></td>
                                <td width="17" align="center" bgcolor="#FFF0E1" class="detalle_medio"><?  
						  if ($rs_mod1_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                                    <a href="javascript:cambiar_estado(2,<?= $rs_mod1_lista['idba_anunciante'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                                    <? } else { ?>
                                    <a href="javascript:cambiar_estado(1,<?= $rs_mod1_lista['idba_anunciante'] ?>);"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                                    <? } ?></td>
                                <td width="16" align="right" bgcolor="#FFF0E1" class="detalle_medio"><a href="ba_anunciante_editar.php?idba_anunciante=<?= $rs_mod1_lista['idba_anunciante'] ?>" class="style10"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a></td>
                                <td width="16" align="right" bgcolor="#FFF0E1" class="detalle_medio"><a href="javascript:confirmar_eliminar(<?= $rs_mod1_lista['idba_anunciante'] ?>);" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a></td>
                              </tr>
                              <? }  ?>
                              <?  if($hay_lista == false){ ?>
                              <tr valign="top">
                                <td colspan="16"  height="40" align="center" valign="middle" bgcolor="#FFDDBC" class="detalle_medio_bold">No se encontraron anunciantes. </td>
                              </tr>
							  <? } ?>
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