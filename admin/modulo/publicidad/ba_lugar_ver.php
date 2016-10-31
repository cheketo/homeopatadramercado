<? include ("../../0_mysql.php"); ?>				
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

	//Tomo variable por get
	$accion = $_POST['accion'];	
	$idba_lugar = $_POST['id'];	
	$estado = $_POST['estado'];		
			 
	//Modifica el estado si se lo require
	if($accion == "cambiar_estado"){
			
		$query_update = "UPDATE ba_lugar
		SET estado_lugar = '$estado'
		WHERE idba_lugar = '$idba_lugar'
		LIMIT 1";
		mysql_query($query_update);
		
	}

		
	//BORRAR LUGAR
	if($accion == "eliminar"){
	
		//verifico que el lugar no este siendo usado por banners.
		$query_preliminar = "SELECT * 
		FROM ba_banner
		WHERE idba_lugar = '$idba_lugar' ";
		$rs_preliminar = mysql_num_rows(mysql_query($query_preliminar));
		
		if($rs_preliminar == 0){
			$query_eliminar = "DELETE FROM ba_lugar WHERE idba_lugar = '$idba_lugar'";
			mysql_query($query_eliminar);
		}else{
			echo "<script>alert('No se puede eliminar el lugar porque actualmente tiene banners cargados en ese lugar.');</script>";
		}
	
	} 

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function confirmar_eliminar(id){
	formulario = document.form_titular;
		if (confirm('¿Esta seguro que desea eliminar el lugar?')){
			formulario.accion.value = "eliminar";
			formulario.id.value = id;
			formulario.submit();
		}
	};


	function cambiar_estado(estado, id){
	formulario = document.form_titular;
	
	formulario.estado.value = estado;
	formulario.id.value = id;
	formulario.accion.value = "cambiar_estado";
	formulario.submit();
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Lugar - Ver </td>
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
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">&nbsp;&nbsp;Ver lugares <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          </span><span class="style2">
                          <input name="estado" type="hidden" id="estado" value="-1" />
                          </span><span class="style2">
                          <input name="id" type="hidden" id="id" value="" />
                          </span></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tr>
                              <td width="32" height="35" align="center" bgcolor="#FFE7CE" class="detalle_medio_bold">ID</td>
                              <td width="242" height="35" bgcolor="#FFE7CE" class="detalle_medio_bold">Nombre del Lugar </td>
                              <td width="66" height="35" bgcolor="#FFE7CE" class="detalle_medio_bold">Alto</td>
                              <td width="71" height="35" bgcolor="#FFE7CE" class="detalle_medio_bold">Ancho</td>
                              <td width="70" height="35" bgcolor="#FFE7CE" class="detalle_medio_bold">Peso Max. </td>
                              <td width="71" height="35" align="center" valign="middle" bgcolor="#FFE7CE" class="detalle_medio_bold">Estado </td>
                              <td height="35" bgcolor="#FFE7CE" class="detalle_medio_bold">&nbsp;</td>
                              <td height="35" bgcolor="#FFE7CE" class="detalle_medio_bold">&nbsp;</td>
                            </tr>

                            <?
							
	//Realiza consulta, para mostrar los datos enunciados en la tabla.
	$query_mod1_lista = "SELECT * 
	FROM ba_lugar 
	ORDER BY nombre_lugar";
	
	$result_mod1_lista = mysql_query($query_mod1_lista);
	while ($rs_mod1_lista = mysql_fetch_assoc($result_mod1_lista))
	{
	
	$hay_lista = true;
?>
                            <tr valign="top">
                              <td height="32" align="center" valign="middle" bgcolor="#FFF0E1" class="detalle_chico"><?= $rs_mod1_lista['idba_lugar'] ?>.
                                  <span class="detalle_medio_bold"><a name="<?= $rs_mod1_lista['idba_lugar'] ?>" id="<?= $rs_mod1_lista['idba_lugar'] ?>"></a></span></td>
                              <td height="32" valign="middle" bgcolor="#FFF0E1" class="detalle_medio"><?= $rs_mod1_lista['nombre_lugar'] ?></td>
                              <td height="32" valign="middle" bgcolor="#FFF0E1" class="detalle_medio"><?= $rs_mod1_lista['alto'] ?>
                                px.</td>
                              <td height="32" valign="middle" bgcolor="#FFF0E1" class="detalle_medio"><?= $rs_mod1_lista['ancho'] ?>
                                px.</td>
                              <td height="32" valign="middle" bgcolor="#FFF0E1" class="detalle_medio"><?= $rs_mod1_lista['peso_maximo'] ?>
                                kb.</td>
                              <td height="32" align="center" valign="middle" bgcolor="#FFF0E1" class="detalle_medio"><?  
						  if ($rs_mod1_lista['estado_lugar'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                                  <a href="javascript:cambiar_estado(2,<?= $rs_mod1_lista['idba_lugar'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                                  <? } else { ?>
                                  <a href="javascript:cambiar_estado(1,<?= $rs_mod1_lista['idba_lugar'] ?>);"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                                  <? } ?></td>
                              <td width="16" height="32" align="right" valign="middle" bgcolor="#FFF0E1" class="detalle_medio"><a href="ba_lugar_editar.php?idba_lugar=<?= $rs_mod1_lista['idba_lugar'] ?>" class="style10"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a></td>
                              <td width="19" height="32" align="right" valign="middle" bgcolor="#FFF0E1" class="detalle_medio"><a href="javascript:confirmar_eliminar(<?= $rs_mod1_lista['idba_lugar'] ?>);" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a></td>
                            </tr>
                            <? }  ?>
                              <?  if($hay_lista == false){ ?>
                            <tr valign="middle">
                              <td height="40" colspan="19" align="center" valign="middle" bgcolor="#FFDDBC" class="detalle_medio_bold">No se encontraron lugares. </td>
                            </tr>
							<? } ?>
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