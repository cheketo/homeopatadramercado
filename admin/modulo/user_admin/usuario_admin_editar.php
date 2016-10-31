<? 
	//INCLUDES
	include ("../../0_mysql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	//VARIABLES
	$accion = $_POST['accion'];
	$iduser_admin = $_GET['iduser_admin'];
	
	$usuario = $_POST['usuario'];
	$idsede = $_POST['idsede'];
	$iduser_admin_perfil = $_POST['iduser_admin_perfil'];
	$password = $_POST['password'];
	
	if($password){
		$password = md5($password);
		$filtro_pass = " , password = '$password' ";
	}else{
		$filtro_pass = "";
	}
		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
		
		$query_modficacion = "UPDATE user_admin SET
		  usuario = '$usuario'
		, idsede = '$idsede'
		, iduser_admin_perfil = '$iduser_admin_perfil'
		$filtro_pass
		WHERE iduser_admin = '$iduser_admin'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		echo "<script>document.location.href= 'usuario_admin_ver.php';</script>";
		
	
	};
		
	//CONSULTA
	$query = "SELECT * 
	FROM user_admin 
	WHERE iduser_admin = '$iduser_admin' ";
	$rs_lista = mysql_fetch_assoc(mysql_query($query));
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_form(){
	formulario = document.form_titular;
	
		if(formulario.usuario.value == ''){
			alert("Debe ingresar el usuario");
		}else if(formulario.password.value.length < 4 || formulario.password_confirm.value.length < 4){
			alert("La contraseña debe tener al menos 4 caracteres.");
		}else if(formulario.password.value != '' && formulario.password_confirm.value != '' && formulario.password.value != formulario.password_confirm.value){
			alert("No coincide la password ingresada con la de confirmacion.");			
		}else{
			formulario.accion.value = "actualizar";
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Admin - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Editar Usuario Admin:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="19%" align="right" valign="middle" class="detalle_medio">Usuario:</td>
                                <td width="81%" align="left" valign="top"><label>
                                  <input name="usuario" type="text" class="detalle_medio" id="usuario" value="<?= $rs_lista['usuario'] ?>" style="width:300px" />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="19%" align="right" valign="middle" class="detalle_medio">Nueva Password:</td>
                                <td align="left" valign="top" class="detalle_11px"><input name="password" type="password" class="detalle_medio" id="password" style="width:200px" /> 
                                * Complete solo si desea cambiar la contrase&ntilde;a </td>
                              </tr>
                              <tr>
                                <td width="19%" align="right" valign="middle" class="detalle_medio">Confirmar Password: </td>
                                <td align="left" valign="top" class="detalle_11px"><input name="password_confirm" type="password" class="detalle_medio" id="password_confirm" style="width:200px" />
                                * Complete solo si desea cambiar la contrase&ntilde;a </td>
                              </tr>
                              <tr>
                                <td width="19%" align="right" valign="middle" class="detalle_medio">Sucursal:</td>
                                <td align="left" valign="top"><select name="idsede" class="detalle_medio" id="idsede" style="width:305px;">
                                  <option value="0"  <? if($rs_lista['idsede'] == 0){ echo "selected"; } ?>>Todas las sucursales</option>
                                  <?
										  $query_sede  = "SELECT * 
										  FROM sede 
										  WHERE estado = '1'
										  ORDER BY orden";
										  $result_sede  = mysql_query($query_sede);
										  while ($rs_sede  = mysql_fetch_assoc($result_sede))
										  {
									?>
                                  <option value="<?= $rs_sede['idsede'] ?>" <? if($rs_lista['idsede'] == $rs_sede['idsede']){ echo "selected"; } ?> >
                                  <?= $rs_sede['titulo'] ?>
                                  </option>
                                  <?  } ?>
                                </select></td>
                              </tr>
                              <tr>
                                <td width="19%" align="right" valign="middle" class="detalle_medio">Perfil:</td>
                                <td align="left" valign="top"><select name="iduser_admin_perfil" class="detalle_medio" id="iduser_admin_perfil" style="width:305px;">
                                  <?
	  $query_iduser_admin_perfil  = "SELECT * 
	  FROM user_admin_perfil 
	  WHERE iduser_admin_perfil != '1'
	  ORDER BY titulo";
	  $result_iduser_admin_perfil  = mysql_query($query_iduser_admin_perfil);
	  while ($rs_iduser_admin_perfil  = mysql_fetch_assoc($result_iduser_admin_perfil))
	  {
?>
                                  <option value="<?= $rs_iduser_admin_perfil['iduser_admin_perfil'] ?>" <? if($rs_lista['iduser_admin_perfil'] == $rs_iduser_admin_perfil['iduser_admin_perfil']){ echo "selected"; } ?> >
                                  <?= $rs_iduser_admin_perfil['titulo'] ?>
                                  </option>
                                  <?  } ?>
                                </select></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form();" value=" Modificar &raquo; " /></td>
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