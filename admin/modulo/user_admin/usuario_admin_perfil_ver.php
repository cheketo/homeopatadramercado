<? 
	//INCLUDES
	include ("../../0_mysql.php"); 

	//VARIABLES		
	$accion = $_POST['accion'];
	$titulo = $_POST['titulo'];	
	$iduser_admin_perfil_borrar = $_GET['iduser_admin_perfil_borrar'];
	$iduser_admin_perfil = $_GET['iduser_admin_perfil'];
	$estado = $_GET['estado'];
	
	//INGRESAR
	if( $accion == "ingresar" ){
	
		$query_ingresar = "INSERT INTO user_admin_perfil (
		  titulo
		) VALUES (
		  '$titulo'
		)";
		mysql_query($query_ingresar);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."')</script>";
	
	};
			   
	//BORRAR	
	if($iduser_admin_perfil_borrar){	
		$query_eliminar = "DELETE 
		FROM user_admin_perfil 
		WHERE iduser_admin_perfil = '$iduser_admin_perfil_borrar'";
		mysql_query($query_eliminar);
		
		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."')</script>";	
	};
	
	//CAMBIAR ESTADO
	if($estado && $iduser_admin_perfil){
		
		$query_estado = "UPDATE user_admin_perfil 
		SET estado = '$estado'
		WHERE $iduser_admin_perfil = $iduser_admin_perfil
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

<SCRIPT LANGUAGE="JavaScript" SRC="js/htmleditor.js"></SCRIPT>
<script language="javascript">

	function validar_form_preguntas(){
	formulario = document.form_titular;
	
		if(formulario.titulo.value == ''){
			alert("Debe tener un titulo el perfil");
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Admin Perfil - Ver </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#FFB76F">
                        <td width="4%" height="40" align="center" bgcolor="#FFD3A8" class="detalle_medio_bold">ID</td>
                        <td width="77%" height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Titulo</td>
                        <td height="40" colspan="4" align="center" bgcolor="#FFD3A8" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
                      <? 
 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM user_admin_perfil 
		WHERE estado <> 3 ";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista))
			{ $hay_lista = true;			  
			  $pais = $rs_lista['idpais'];
	  
?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td width="4%" align="center"><span class="detalle_medio_bold"><a name="<?= $rs_lista['iduser_admin_perfil']; ?>" id="<?= $rs_lista['iduser_admin_perfil']; ?>"></a>
                              <?=$rs_lista['iduser_admin_perfil']?>
                        </span></td>
                        <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['titulo']; ?></td>
                        <td width="8%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?estado=2&amp;iduser_admin_perfil=<?= $rs_lista['iduser_admin_perfil'] ?>"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                            <? } else { ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?estado=1&amp;iduser_admin_perfil=<?= $rs_lista['iduser_admin_perfil'] ?>"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                            <? } ?></td>
                        <td width="6%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="usuario_admin_perfil_editar.php?iduser_admin_perfil=<?= $rs_lista['iduser_admin_perfil'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                        <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>">
						<? if($rs_lista['iduser_admin_perfil'] != 1){ ?>
						<a href="javascript:confirm_eliminar('<?= $_SERVER['PHP_SELF'] ?>?iduser_admin_perfil_borrar=<?= $rs_lista['iduser_admin_perfil'] ?>')" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a>
						<? } ?>
						</td>
                      </tr>
                      <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                      <tr align="center" valign="middle">
                        <td colspan="6" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado perfiles.</td>
                      </tr>
                      <? };
	?>
                    </table>
                      <form action="" method="post" name="form_titular" id="form_titular">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Ingresar nuevo Perfil:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                                </tr>
                                <tr>
                                  <td width="15%" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                  <td width="85%" align="left" valign="top"><label>
                                    <input name="titulo" type="text" id="titulo" size="35" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                  <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form_preguntas();" value=" Ingresar &raquo; " /></td>
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