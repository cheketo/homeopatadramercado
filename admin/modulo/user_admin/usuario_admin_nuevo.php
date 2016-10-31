<? 
	include("../../0_mysql.php"); 
	
	//VARIABLES
	$usuario = $_POST['usuario'];
	$iduser_admin_perfil = $_POST['iduser_admin_perfil'];
	$password = $_POST['password'];
	$idsede = $_POST['idsede'];
	$fecha = date('Y').'-'.date('m').'-'.date('d');
	$accion = $_POST['accion'];
	
	//INGRESAR
	if($accion == "ingresar"){
	
		//se checkea si hay un usuario con el mismo mail
		$query_usuario = "SELECT count(iduser_admin) AS existe FROM user_admin WHERE usuario = '$usuario' ";
		$rs_usuario = mysql_fetch_assoc(mysql_query($query_usuario));
		
		if($rs_usuario['existe'] > 0){
			$error = "Ya existe un usuario registrado con el usuario: '$usuario'.";
		}else{
		
			$password = md5($password);
	
			$query_ingresar = "INSERT INTO user_admin (
			  usuario
			, password
			, idsede
			, iduser_admin_perfil
			) VALUES (
			  '$usuario'
			, '$password'
			, '$idsede'
			, '$iduser_admin_perfil'
			)";
			mysql_query($query_ingresar);
			$error = "El usuario fue ingresado exitosamente.";
				
			$usuario = "";
			$password = "";
		};
	};

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<style type="text/css">
<!--
.carro_tabla {font-size: 11px;
color:#555555;
}
-->
</style>
<script language="javascript">

	function esTelefono(campo){
	   var caracteresValidos = "0123456789-()/ ";
	   var esNumero = true;
	   var caracter;
	
	   for (i = 0; i < campo.length && esNumero == true; i++){
	   
		  caracter = campo.charAt(i); 
		  if (caracteresValidos.indexOf(caracter) == -1){
			 esNumero = false;
		  }
	   }
	   return esNumero;
	};
	
	function validar_registro(){
		var exito=1;
	
		var usuario = document.getElementById("usuario");
		var iduser_admin_perfil = document.getElementById("iduser_admin_perfil");	
		var password = document.getElementById("password");	
		var idsede = document.getElementById("idsede");	
		
	/*
		if (user_segmentacion.value == 0)	{
			alert ('Debe seleccionar una segmentación');
			exito = 0;	}
		*/
		if (usuario.value.length < 4)	{
			alert ('El usuario debe ser de al menos 4 caracteres.');
			exito = 0;	}
	
		if (password.value.length < 4 )	{
			alert("La contraseña debe ser de al menos 4 caracteres.");
			exito = 0;	}
			
		if (iduser_admin_perfil.value == "" )	{
			alert("Debe completar el perfil de usuario.");
			exito = 0;	}
		
		if (idsede.value == "" )	{
			alert("Debe completar la sucursal.");
			exito = 0;	}
	
		if (exito == 1) {
			document.frm.accion.value = "ingresar";
			document.frm.submit(); 
		};
	
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Admin  - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><? if($error){ ?>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="center" valign="middle" class="detalle_medio_bold"><span class="ejemplo_negro_12px" style="color:#FF0000; font-weight:bold">
                            <?=$error?>
                          </span></td>
                        </tr>
                      </table>
                    <? }; ?>
                      <form action="" method="post" name="frm" id="frm">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="35" bgcolor="#FFDDBC" class="detalle_medio_bold">Ingrese los datos del nuevo usuario administrador:
                              <input name="accion" type="hidden" id="accion" value="0" /></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                                <tr>
                                  <td width="87" align="right" class="detalle_medio">Usuario: </td>
                                  <td width="571" align="left"><input name="usuario" type="text" class="detalle_medio" id="usuario" value="" size="27" style="width:180px;" /></td>
                                </tr>
                                <tr>
                                  <td align="right" class="detalle_medio">Perfil:</td>
                                  <td align="left"><select name="iduser_admin_perfil" class="detalle_medio" id="iduser_admin_perfil" style="width:190px;">
                                      <option value="">- Seleccionar tipo de usuario</option>
                                      <?
	  $query_iduser_admin_perfil  = "SELECT * 
	  FROM user_admin_perfil 
	  WHERE iduser_admin_perfil != '1'
	  ORDER BY titulo";
	  $result_iduser_admin_perfil  = mysql_query($query_iduser_admin_perfil);
	  while ($rs_iduser_admin_perfil  = mysql_fetch_assoc($result_iduser_admin_perfil))
	  {
?>
                                      <option value="<?= $rs_iduser_admin_perfil['iduser_admin_perfil'] ?>">
                                      <?= $rs_iduser_admin_perfil['titulo'] ?>
                                      </option>
                                      <?  } ?>
                                  </select></td>
                                </tr>
                                <tr>
                                  <td align="right" class="detalle_medio">Sucursal:</td>
                                  <td align="left"><select name="idsede" class="detalle_medio" id="idsede" style="width:190px;">
                                    <option value="">- Seleccionar Sucursal</option>
									<option value="0">Todas las sucursales</option>
                                    <?
										  $query_sede  = "SELECT * 
										  FROM sede 
										  WHERE estado = '1'
										  ORDER BY orden";
										  $result_sede  = mysql_query($query_sede);
										  while ($rs_sede  = mysql_fetch_assoc($result_sede))
										  {
									?>
                                    <option value="<?= $rs_sede['idsede'] ?>">
                                    <?= $rs_sede['titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select></td>
                                </tr>
                                <tr>
                                  <td width="87" align="right" class="detalle_medio">Contrase&ntilde;a:</td>
                                  <td align="left" class="detalle_chico" style="color:#FF0000"><input name="password" type="text" class="detalle_medio" id="password" value="" size="27" style="width:180px;" /></td>
                                </tr>
                                <tr>
                                  <td align="right" class="detalle_medio_bold">&nbsp;</td>
                                  <td align="left" class="detalle_chico" style="color:#FF0000"><input name="ingresar" type="button" class="detalle_medio_bold" id="ingresar" onclick="validar_registro();" value="  &gt;&gt;  Ingresar     " /></td>
                                </tr>
                            </table></td>
                          </tr>
                        </table>
                        <input name="cont_user_segmentacion" type="hidden" value="<?=$cont_user_segmentacion?>" />
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