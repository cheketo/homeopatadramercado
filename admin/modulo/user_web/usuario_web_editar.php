<? 
	include ("../../0_mysql.php"); 

	//VARIABLES
	$accion = $_POST['accion'];
	$iduser_web = $_GET['iduser_web'];
	
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$sexo = $_POST['sexo'];
	
	$dia_fecha_nacimiento = $_POST['dia_fecha_nacimiento'];
	$mes_fecha_nacimiento = $_POST['mes_fecha_nacimiento'];
	$ano_fecha_nacimiento = $_POST['ano_fecha_nacimiento'];
	$fecha_nacimiento = $ano_fecha_nacimiento."-".$mes_fecha_nacimiento."-".$dia_fecha_nacimiento;
	
	$dia_fecha_alta = $_POST['dia_fecha_alta'];
	$mes_fecha_alta = $_POST['mes_fecha_alta'];
	$ano_fecha_alta = $_POST['ano_fecha_alta'];
	$fecha_alta = $ano_fecha_alta."-".$mes_fecha_alta."-".$dia_fecha_alta;
	
	$dia_fecha_baja = $_POST['dia_fecha_baja'];
	$mes_fecha_baja = $_POST['mes_fecha_baja'];
	$ano_fecha_baja = $_POST['ano_fecha_baja'];
	$fecha_baja = $ano_fecha_baja."-".$mes_fecha_baja."-".$dia_fecha_baja;
		
	$mail = $_POST['mail'];
	$telefono = $_POST['telefono'];
	$celular = $_POST['celular'];
	$fax = $_POST['fax'];
	
	$calle = $_POST['calle'];
	$calle_numero = $_POST['calle_numero'];
	$entre_calles = $_POST['entre_calles'];
	$cp = $_POST['cp'];
	$piso = $_POST['piso'];
	$depto = $_POST['depto'];
	$idpais = $_POST['idpais'];
	$idpais_provincia = $_POST['idpais_provincia'];
	$localidad = $_POST['localidad'];
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$ididioma = $_POST['ididioma'];
	$idsede = $_POST['idsede'];
	$iduser_web_perfil = $_POST['iduser_web_perfil'];
	
	$emp_denominacion = $_POST['emp_denominacion'];
	$emp_direccion = $_POST['emp_direccion'];
	$emp_telefono = $_POST['emp_telefono'];
	$emp_fax = $_POST['emp_fax'];
	$emp_web = $_POST['emp_web'];
	$emp_cargo = $_POST['emp_cargo'];
	$emp_mail = $_POST['emp_mail'];
	$cuit1 = $_POST['cuit1'];
	$cuit2 = $_POST['cuit2'];
	$cuit3 = $_POST['cuit3'];
	
	if($cuit1 != "" && $cuit2 != "" && $cuit3 != ""){
		$emp_cuit = $cuit1."-".$cuit2."-".$cuit3;
	}else{
		$emp_cuit = "";
	}
	
	
	$user_segmentacion = $_POST['user_segmentacion'];
	$cont_user_segmentacion = $_POST['cont_user_segmentacion'];
	
	//ACTUALIZAR
	if($accion == "update"){
		
		$query_modficacion = "UPDATE user_web SET 
		  nombre = '$nombre'
		, apellido = '$apellido'
		, sexo = '$sexo'
		, fecha_nacimiento = '$fecha_nacimiento'
		, mail = '$mail'
		, telefono = '$telefono'
		, celular = '$celular'
		, fax = '$fax'
		, calle = '$calle'
		, calle_numero = '$calle_numero'
		, entre_calles = '$entre_calles'
		, cp = '$cp'
		, piso = '$piso'
		, depto = '$depto'
		, idpais = '$idpais'
		, idpais_provincia = '$idpais_provincia'
		, fecha_alta = '$fecha_alta'
		, fecha_baja = '$fecha_baja'
		, localidad = '$localidad'
		, username = '$username'
		, password = '$password'
		, ididioma = '$ididioma'
		, idsede = '$idsede'
		, iduser_web_perfil = '$iduser_web_perfil'
		, emp_denominacion = '$emp_denominacion'
		, emp_direccion = '$emp_direccion'
		, emp_telefono = '$emp_telefono'
		, emp_fax = '$emp_fax'
		, emp_web = '$emp_web'
		, emp_cargo = '$emp_cargo'
		, emp_mail = '$emp_mail'				
		, emp_cuit = '$emp_cuit'
		
		WHERE iduser_web = '$iduser_web'
		LIMIT 1";
		
		mysql_query($query_modficacion);
		
		$query_del = "DELETE FROM user_web_segmentacion WHERE iduser_web = '$iduser_web'";
		mysql_query($query_del);
		
		for($i=1; $i<$cont_user_segmentacion+1; $i++){
			if($user_segmentacion[$i]){
				$user_segmentacion_actual = $user_segmentacion[$i];
				$query_user_segmentaciones = "INSERT INTO user_web_segmentacion (iduser_web, iduser_segmentacion) VALUES ('$iduser_web','$user_segmentacion_actual') ";
				mysql_query($query_user_segmentaciones);
			}
		}

	
	};

	//CONSULTA DATOS DEL USUARIO WEB ACTUAL:
	$query_userweb = "SELECT * 
	FROM user_web 
	WHERE iduser_web = '$iduser_web' ";
	$rs_userweb = mysql_fetch_assoc(mysql_query($query_userweb));
	
	$cuit = split("-",$rs_userweb['emp_cuit']);
	$fecha_nacimiento = split("-",$rs_userweb['fecha_nacimiento']);
	
	$fecha_alta = split("-",$rs_userweb['fecha_alta']);
	$dia_fecha_alta = $fecha_alta[2];
	$mes_fecha_alta = $fecha_alta[1];
	$ano_fecha_alta = $fecha_alta[0];
	
	$fecha_baja = split("-",$rs_userweb['fecha_baja']);
	$dia_fecha_baja = $fecha_baja[2];
	$mes_fecha_baja = $fecha_baja[1];
	$ano_fecha_baja = $fecha_baja[0];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function pasar_cuit(campo){
		var f = document.frm;
		
		switch(campo){
			
			case '1':
				if(f.cuit1.value.length == 2){
					f.cuit2.focus();
				}
				//alert("1) longitud: " + f.cuit1.value.length);
				break;
				
			case '2':
				if(f.cuit2.value.length == 8){
					f.cuit3.focus();
				}else if(f.cuit1.value.length < 2){
					f.cuit1.focus();
				}
				//alert("2) longitud: " + f.cuit2.value.length);
				break;
				
			case '3':
				if(f.cuit2.value.length < 8){
					f.cuit2.focus();
				}else if(f.cuit1.value.length < 2){
					f.cuit1.focus();
				}
				break;
				
		}
		
	}
	
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
		var f = document.frm;
		var flag = true;
		var error = "";
		
		//Nombre (*)
		if (f.nombre.value.length > 0 && f.nombre.value.length < 4)	{
			error = error + 'Debe ingresar su nombre.\n';
			flag = false;	
		}
		
		//Apellido (*)
		if (f.apellido.value.length > 0 && f.apellido.value.length < 4)	{
			error = error + 'Debe ingresar su apellido.\n'; 
			flag = false;	
		}
		
		//Username (*)
		if (f.username.value.length > 0 && f.username.value.length < 4)	{
			error = error + 'Debe ingresar su username.\n';
			flag = false;	
		}
		
		//Mail (*)
		if (f.mail.value == '')	{
			error = error + 'Debe ingresar su e-mail.\n';
			flag = false;		
		}else{
			
			if (f.mail.value.indexOf("@") == (-1)){
				error = error + 'A su e-mail le falta el @.\n';
				flag = false;
			}
			
			if (f.mail.value.indexOf(".") == (-1)){
				error = error + 'A su e-mail le falta la extension (ej: .com, .com.es).\n';
				flag = false;
			}
		}
		
		//Password (*)
		if(f.password.value.length > 0){
			if (f.password.value.length < 4 )	{
				error = error + "La contraseña debe ser de al menos 4 caracteres.\n";
				flag = false;
			}else{
				if(f.password.value != f.confirmar_password.value){
					error = error + "La contraseña no coincide con la contraseña de confirmación.\n";
					flag = false;
				}
			}
		}
		
		//Telefono (*)
		if (esTelefono(f.telefono.value) == false || esTelefono(f.celular.value) == false || esTelefono(f.fax.value) == false ){
			error = error + "Verifique que sus numeros de contacto telefonico sean correctos.\n";
			flag = false;
		}
		
		//Calle Numero
		if(esNumerico(f.calle_numero.value) == false){
			error = error + "El número de su domicilio debe ser númerico.\n";
			flag = false;
		}
		
		//Codigo Postal
		if(f.cp.value == ""){
			error = error + "Ingrese su código postal.\n";
			flag = false;
		}
		
		//Empresa Telefono
		if (esTelefono(f.emp_telefono.value) == false || esTelefono(f.emp_fax.value) == false ){
			error = error + "Verifique que sus números de contacto empresarial telefonicos sean correctos.\n";
			flag = false;
		}
		
		//Mail Empresa
		if (f.emp_mail.value != ''){
			
			if (f.emp_mail.value.indexOf("@") == (-1)){
				error = error + 'A su e-mail empresarial le falta el @.\n';
				flag = false;
			}
			
			if (f.emp_mail.value.indexOf(".") == (-1)){
				error = error + 'A su e-mail empresarial le falta la extension (ej: .com, .com.es).\n';
				flag = false;
			}
		}
		
		//CUIT
		if(f.cuit1.value != "" || f.cuit2.value != "" || f.cuit3.value != ""){
			if (esNumerico(f.cuit1.value) != 2 || esNumerico(f.cuit2.value) != 8 || esNumerico(f.cuit3.value) != 1){
				error = error + "Complete correctamente su número de CUIT.\n";
				flag = false;
			}
		}
		
		//FINAL
		if (flag == true) {
			document.frm.accion.value = "update";
			document.frm.submit(); 
		}else{
			alert(error);
		};
	};

	
</script>
</head>
<body>
<div id="header">
  <? include("../../0_top.php"); ?>
</div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Web  - Editar </td>
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
    <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Datos del nuevo usuario web:
      <input name="accion" type="hidden" id="accion" value="0" /></td>
  </tr>
  <tr bgcolor="#999999">
    <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="detalle_medio">
      <tr>
        <td width="50%" height="25" align="left" valign="bottom" class="detalle_medio_bold">Datos personales </td>
        <td width="50%" height="25" align="left" valign="bottom" class="detalle_medio">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Nombres: (*) </td>
        <td width="50%" height="25" align="left" valign="bottom" class="detalle_medio">Apellidos: (*)</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="nombre" type="text" class="detalle_medio" id="nombre" value="<?= $rs_userweb['nombre'] ?>" size="27" style="width:90%" /></td>
        <td align="left"><span class="detalle_chico" style="color:#FF0000">
          <input name="apellido" type="text" class="detalle_medio" id="apellido" value="<?= $rs_userweb['apellido'] ?>" size="27" style="width:90%" />
        </span></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Sexo:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Fecha de Nacimiento:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><label>
          <select name="sexo" class="detalle_medio" id="sexo" style="width:140px;">
            <option value="N" <? if($rs_userweb['sexo'] == "N"){ echo "selected"; } ?>> - No seleccionado. </option>
            <option value="M" <? if($rs_userweb['sexo'] == "M"){ echo "selected"; } ?>>M</option>
            <option value="F" <? if($rs_userweb['sexo'] == "F"){ echo "selected"; } ?>>F</option>
          </select>
        </label></td>
        <td align="left"><span class="style2">
          <select name="dia_fecha_nacimiento" size="1" class="detalle_medio" id="dia_fecha_nacimiento">
            <option value='00' ></option>
            <?												
						for ($i=1;$i<32;$i++){
							if ($fecha_nacimiento[2] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          <select name="mes_fecha_nacimiento" size="1" class="detalle_medio" id="mes_fecha_nacimiento">
            <option value='00' ></option>
            <?						
                        for ($i=1;$i<13;$i++){
							if ($fecha_nacimiento[1] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          <select name="ano_fecha_nacimiento" size="1" class="detalle_medio" id="ano_fecha_nacimiento">
            <option value='0000' ></option>
            <?	
						$anioActual = date("Y");
                        for ($i=$anioActual-8;$i>($anioActual-80);$i--){
							if ($fecha_nacimiento[0] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          </font></span></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">E-mail: (*)</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Telefono:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="mail" type="text" class="detalle_medio" id="mail" style="width:90%" value="<?= $rs_userweb['mail'] ?>" size="27"  /></td>
        <td align="left" class="detalle_chico" style="color:#FF0000"><input name="telefono" type="text" class="detalle_medio" id="telefono" style="width:90%" value="<?= $rs_userweb['telefono'] ?>" size="27" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Celular:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Fax:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="celular" type="text" class="detalle_medio" id="celular" style="width:90%" value="<?= $rs_userweb['celular'] ?>" size="27" /></td>
        <td align="left" class="detalle_chico" style="color:#FF0000"><input name="fax" type="text" class="detalle_medio" id="fax" style="width:90%" value="<?= $rs_userweb['fax'] ?>" size="27" />        </td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Calle:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">N&uacute;mero:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="calle" type="text" class="detalle_medio" id="calle" style="width:90%" value="<?= $rs_userweb['calle'] ?>" size="27" /></td>
        <td align="left" class="detalle_chico"><input name="calle_numero" type="text" class="detalle_medio" id="calle_numero" style="width:90%" value="<?= $rs_userweb['calle_numero'] ?>" size="27" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Entre las calles: </td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Cod. Postal:<a href="http://www.correoargentino.com.ar/consulta_cpa/cons_.php" class="detalle_11px"></a></td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="entre_calles" type="text" class="detalle_medio" id="entre_calles" style="width:90%" value="<?= $rs_userweb['entre_calles'] ?>" size="27" /></td>
        <td align="left" valign="middle" class="detalle_chico"><input name="cp" type="text" class="detalle_medio" id="cp" style="width:100px" value="<?= $rs_userweb['cp'] ?>" size="27" />
            <a href="http://www.correoargentino.com.ar/consulta_cpa/cons_.php" target="_blank" class="detalle_11px">Si no conoce su C.P., averiguelo aqu&iacute;.</a></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Piso:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Depto:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><span class="detalle_chico">
          <input name="piso" type="text" class="detalle_medio" id="piso" style="width:100px" value="<?= $rs_userweb['piso'] ?>" size="27" maxlength="2" />
        </span></td>
        <td align="left" class="detalle_chico" style="color:#FF0000"><input name="depto" type="text" class="detalle_medio" id="depto" style="width:100px" value="<?= $rs_userweb['depto'] ?>" size="27" maxlength="4" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Pa&iacute;s:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio" >Provincia:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><select name="idpais" class="detalle_medio" id="idpais" style="width:90%" onchange="validar_registro();">
          <option value="0" selected="selected">- Seleccione su pa&iacute;s </option>
          <?
									  
									  $query_pais = "SELECT * 
									  FROM pais 
									  WHERE estado = '1' ORDER BY titulo ASC";
									  $result_pais = mysql_query($query_pais);
									  while ($rs_pais = mysql_fetch_assoc($result_pais)){
											  
										if ($rs_userweb['idpais'] == $rs_pais['idpais'])
										{
											$pais_sel = "selected";
										}else{
											$pais_sel = "";
										}
	
									?>
          <option <?=$pais_sel?> value="<?= $rs_pais['idpais'] ?>">
            <?= $rs_pais['titulo'] ?>
            </option>
          <?  } ?>
        </select>        </td>
        <td align="left" class="detalle_chico" style="color:#FF0000"><select name="idpais_provincia" class="detalle_medio" id="select" style="width:90%">
          <option value="0" selected="selected">- Seleccione su provincia </option>
          <?
								  $query_provincia = "SELECT * 
								  FROM pais_provincia
								  WHERE estado = '1' AND idpais = '$rs_userweb[idpais]'
								  ORDER BY titulo ASC";
								  $result_provincia = mysql_query($query_provincia);
								  while ($rs_provincia = mysql_fetch_assoc($result_provincia))
								  {
										  
									if ($rs_userweb['idpais_provincia'] == $rs_provincia['idpais_provincia'])
									{
										$pais_sel = "selected";
									}else{
										$pais_sel = "";
									}
	
									?>
          <option <?=$pais_sel?> value="<?= $rs_provincia['idpais_provincia'] ?>">
            <?= $rs_provincia['titulo'] ?>
            </option>
          <?  } ?>
        </select></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Localidad:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="localidad" type="text" class="detalle_medio" id="localidad" value="<?=$rs_userweb['localidad'] ?>" size="27" style="width:90%" /></td>
        <td align="left" class="detalle_chico" style="color:#FF0000">&nbsp;</td>
      </tr>
      <tr>
        <td height="20" colspan="2" align="left" valign="middle" class="detalle_medio"><hr size="1" class="detalle_medio" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" bgcolor="#FFE9D2" class="detalle_medio_bold">Datos de ingreso: </td>
        <td height="25" align="left" bgcolor="#FFE9D2" class="detalle_chico" style="color:#FF0000">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" bgcolor="#FFE9D2" class="detalle_medio">Username: (*) </td>
        <td height="25" align="left" valign="bottom" bgcolor="#FFE9D2">Password: (*) </td>
      </tr>
      <tr>
        <td align="left" bgcolor="#FFE9D2" class="detalle_medio"><input name="username" type="text" class="detalle_medio" id="username" value="<?= $rs_userweb['username'] ?>" size="27" style="width:90%" /></td>
        <td align="left" bgcolor="#FFE9D2"><input name="password" type="password" class="detalle_medio" id="password" value="<?= $rs_userweb['password'] ?>" size="27" style="width:90%" /></td>
      </tr>
      <tr>
        <td align="left" bgcolor="#FFE9D2" class="detalle_medio">Tipo de usuario: </td>
        <td align="left" bgcolor="#FFE9D2">Confimar password: (*) </td>
      </tr>
      <tr>
        <td align="left" bgcolor="#FFE9D2" class="detalle_medio"><label>
        <select name="iduser_web_perfil" class="detalle_medio" id="iduser_web_perfil" style="width:90%">
          <?
									  $query = "SELECT iduser_web_perfil, titulo
									  FROM user_web_perfil
									  WHERE estado = 1
									  ORDER BY iduser_web_perfil";
									  $result = mysql_query($query);
									  while($rs_perfil_web = mysql_fetch_assoc($result)){
									  ?>
          <option value="<?= $rs_perfil_web['iduser_web_perfil'] ?>" <? if($rs_perfil_web['iduser_web_perfil'] == $iduser_web_perfil){ echo "selected";} ?>>
            <?= $rs_perfil_web['titulo'] ?>
            </option>
          <? } ?>
        </select>
        </label></td>
        <td align="left" bgcolor="#FFE9D2"><input name="confirmar_password" type="password" class="detalle_medio" id="confirmar_password" value="<?= $rs_userweb['password'] ?>" size="27" style="width:90%" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" bgcolor="#FFE9D2" class="detalle_medio">Idioma:</td>
        <td height="25" align="left" valign="bottom" bgcolor="#FFE9D2">Registrado en la Sucursal:</td>
      </tr>
      <tr>
        <td align="left" valign="middle" bgcolor="#FFE9D2" class="detalle_medio"><label class="detalle_medio">
          <select name="ididioma" class="detalle_medio" id="ididioma" style="width:90%">
           
            <?
									  $query = "SELECT ididioma, titulo_idioma
									  FROM idioma
									  WHERE estado = 1";
									  $result = mysql_query($query);
									  while($rs_idioma = mysql_fetch_assoc($result)){
									  ?>
            <option value="<?= $rs_idioma['ididioma'] ?>" <? if($rs_idioma['ididioma'] == $rs_userweb['ididioma'] ){ echo "selected";} ?>>
              <?= $rs_idioma['titulo_idioma'] ?>
              </option>
            <? } ?>
          </select>
        </label></td>
        <td align="left" valign="middle" bgcolor="#FFE9D2" class="detalle_chico" style="color:#FF0000"><select name="idsede" class="detalle_medio" id="idsede" style="width:90%">
          
          <?
									  $query = "SELECT idsede, titulo
									  FROM sede
									  WHERE estado = 1
									  ORDER BY titulo ASC";
									  $result = mysql_query($query);
									  while($rs_sede = mysql_fetch_assoc($result)){
									  ?>
          <option value="<?= $rs_sede['idsede'] ?>" <? if($rs_sede['idsede'] == $rs_userweb['idsede']){ echo "selected";} ?>>
            <?= $rs_sede['titulo'] ?>
            </option>
          <? } ?>
        </select></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" bgcolor="#FFE9D2" class="detalle_medio">Fecha Alta: </td>
        <td height="25" align="left" valign="bottom" bgcolor="#FFE9D2" class="detalle_medio">Fecha Baja: </td>
      </tr>
      <tr>
        <td align="left" valign="middle" bgcolor="#FFE9D2" class="detalle_medio"><span class="style2">
          <select name="dia_fecha_alta" size="1" class="detalle_medio" id="dia_fecha_alta">
            <option value='00' ></option>
            <?
						
						//$diaActual = date("d");
																
						for ($i=1;$i<32;$i++){
						
							if ($dia_fecha_alta == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          <select name="mes_fecha_alta" size="1" class="detalle_medio" id="mes_fecha_alta">
            <option value='00' ></option>
            <?	
						//$mesActual = date("m");					
                        for ($i=1;$i<13;$i++){
							if ($mes_fecha_alta == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          <select name="ano_fecha_alta" size="1" class="detalle_medio" id="ano_fecha_alta">
            <option value='0000' ></option>
            <?	
						//$anioActual = date("Y");
						
                        for ($i=($anioActual+3);$i>($anioActual-3);$i--){
							if ($ano_fecha_alta == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          </font></span></td>
        <td align="left" valign="middle" bgcolor="#FFE9D2" class="detalle_chico" style="color:#FF0000"><span class="style2">
          <select name="dia_fecha_baja" size="1" class="detalle_medio" id="dia_fecha_baja">
            <option value='00' ></option>
            <?
																
						for ($i=1;$i<32;$i++){
							if ($dia_fecha_baja == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
						
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          <select name="mes_fecha_baja" size="1" class="detalle_medio" id="mes_fecha_baja">
            <option value='00' ></option>
            <?	
						//$mesActual = date("m");					
                        for ($i=1;$i<13;$i++){
						
							if ($mes_fecha_baja == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          <select name="ano_fecha_baja" size="1" class="detalle_medio" id="ano_fecha_baja">
            <option value='0000' ></option>
            <?	
						$anioActual = date("Y");
						
                        for ($i=($anioActual+5);$i>($anioActual-1);$i--){	
							if ($ano_fecha_baja == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
          </select>
          </font></span></td>
      </tr>
      
      <tr>
        <td height="20" colspan="2" align="left" bgcolor="#FFE9D2" class="detalle_medio">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio_bold">Datos de su empresa: </td>
        <td height="25" align="left" class="detalle_chico" style="color:#FF0000">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Denominaci&oacute;n: (Nombre fantasia/Raz&oacute;n social) </td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Direcci&oacute;n:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="emp_denominacion" type="text" class="detalle_medio" id="emp_denominacion" value="<?= $rs_userweb['emp_denominacion'] ?>" size="27" style="width:90%" /></td>
        <td align="left" class="detalle_chico"><input name="emp_direccion" type="text" class="detalle_medio" id="emp_direccion" value="<?= $rs_userweb['emp_direccion'] ?>" size="27" style="width:90%" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Telefono:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Fax:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="emp_telefono" type="text" class="detalle_medio" id="emp_telefono" value="<?= $rs_userweb['emp_telefono'] ?>" size="27" style="width:90%" /></td>
        <td align="left" class="detalle_chico"><input name="emp_fax" type="text" class="detalle_medio" id="emp_fax" value="<?= $rs_userweb['emp_fax'] ?>" size="27" style="width:90%" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">E-mail:</td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Web:</td>
      </tr>
      <tr>
        <td align="left" class="detalle_medio"><input name="emp_mail" type="text" class="detalle_medio" id="emp_mail" value="<?= $rs_userweb['emp_mail'] ?>" size="27" style="width:90%" /></td>
        <td align="left" class="detalle_chico"><input name="emp_web" type="text" class="detalle_medio" id="emp_web" value="<?= $rs_userweb['emp_web'] ?>" size="27" style="width:90%" /></td>
      </tr>
      <tr>
        <td height="25" align="left" valign="bottom" class="detalle_medio">Cargo en la empresa. </td>
        <td height="25" align="left" valign="bottom" class="detalle_medio">C.U.I.T.:</td>
      </tr>
      <tr>
        <td height="13" align="left" valign="bottom" class="detalle_medio"><input name="emp_cargo" type="text" class="detalle_medio" id="emp_cargo" value="<?= $rs_userweb['emp_cargo'] ?>" size="27" style="width:90%" /></td>
        <td height="13" align="left" valign="bottom" class="detalle_medio"><label>
          <input name="cuit1" type="text" class="detalle_medio" id="cuit1" style="width:16px; letter-spacing:1px;" onkeyup="pasar_cuit('1');" value="<?= $cuit[0] ?>" maxlength="2">
          -
          <input name="cuit2" type="text" class="detalle_medio" id="cuit2" style="width:65px; letter-spacing:1px;"   onkeyup="pasar_cuit('2');" value="<?= $cuit[1] ?>" maxlength="8" />
          -
          <input name="cuit3" type="text" class="detalle_medio" id="cuit3" style="width:15px; letter-spacing:1px;"  onkeyup="pasar_cuit('3');" value="<?= $cuit[2] ?>" maxlength="1" />
        </label></td>
      </tr>
    </table>
      <hr size="1" class="detalle_medio" /></td>
  </tr>
</table>
<br />
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr bgcolor="#999999">
    <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Segmentaciones</td>
  </tr>
  <tr bgcolor="#999999">
    <td align="center" bgcolor="#FFF0E1"><table width="90%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td height="40" align="left" valign="bottom" class="detalle_medio_bold">Segmentaciones internas web: </td>
            </tr>
          </table>
            <table width="100%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
              <?
							  $cont_user_segmentacion = 0;
							  $query_user_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = '2'
							  ORDER BY titulo";
							  $result_user_segmentacion = mysql_query($query_user_segmentacion);
							  while ($rs_user_segmentacion = mysql_fetch_assoc($result_user_segmentacion)){
							  		
									$cont_user_segmentacion++;
								
								
									$query = "SELECT *
									FROM user_web_segmentacion
									WHERE iduser_segmentacion = '$rs_user_segmentacion[iduser_segmentacion]' AND iduser_web = '$iduser_web'";
									if(mysql_num_rows(mysql_query($query)) > 0 ){
										$mod6_sel_user_segmentacion = "checked";
									}else{
										$mod6_sel_user_segmentacion = "";
									}

							 ?>
              <tr>
                <td width="20" align="center" valign="middle"><input name="user_segmentacion[<?=$cont_user_segmentacion?>]" <?= $mod6_sel_user_segmentacion ?> type="checkbox" id="user_segmentacion[<?=$cont_user_segmentacion?>]" value="<?=$rs_user_segmentacion['iduser_segmentacion']?>"/></td>
                <td align="left" valign="middle" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                    <?=$rs_user_segmentacion['titulo']?>
                </div></td>
              </tr>
              <?
								};
							 ?>
          </table></td>
        <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td height="40" align="left" valign="bottom" class="detalle_medio_bold">Segmentaciones visibles para usuarios web: </td>
            </tr>
          </table>
            <table width="100%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
              <?
							  
							  $query_user_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = 1
							  ORDER BY titulo";
							  $result_user_segmentacion = mysql_query($query_user_segmentacion);
							  while ($rs_user_segmentacion = mysql_fetch_assoc($result_user_segmentacion))	  
							  {
							  	$cont_user_segmentacion++;
								
									$query = "SELECT *
									FROM user_web_segmentacion
									WHERE iduser_segmentacion = '$rs_user_segmentacion[iduser_segmentacion]' AND iduser_web = '$iduser_web'";
									if(mysql_num_rows(mysql_query($query)) > 0 ){
										$mod6_sel_user_segmentacion = "checked";
									}else{
										$mod6_sel_user_segmentacion = "";
									}
							 ?>
              <tr>
                <td width="20" align="center" valign="middle"><input name="user_segmentacion[<?=$cont_user_segmentacion?>]" type="checkbox" id="user_segmentacion[<?=$cont_user_segmentacion?>]" value="<?=$rs_user_segmentacion['iduser_segmentacion']?>" <?= $mod6_sel_user_segmentacion ?> /></td>
                <td align="left" valign="middle" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                    <?=$rs_user_segmentacion['titulo']?>
                </div></td>
              </tr>
              <?
								};
							 ?>
          </table></td>
      </tr>
    </table>
      <table width="90%" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td height="40" align="left" valign="bottom" class="detalle_medio_bold">Segmentaciones de Origen:</td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
                <?
							  /*$cont_user_segmentacion = 0;*/
							  $query_user_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = 3
							  ORDER BY titulo";
							  $result_user_segmentacion = mysql_query($query_user_segmentacion);
							  while ($rs_user_segmentacion = mysql_fetch_assoc($result_user_segmentacion))	  
							  {
							  	$cont_user_segmentacion++;
								
								
									$query = "SELECT *
									FROM user_web_segmentacion
									WHERE iduser_segmentacion = '$rs_user_segmentacion[iduser_segmentacion]' AND iduser_web = '$iduser_web'";
									if(mysql_num_rows(mysql_query($query)) > 0 ){
										$mod6_sel_user_segmentacion = "checked";
									}else{
										$mod6_sel_user_segmentacion = "";
									}

							 ?>
                <tr>
                  <td width="20" align="center" valign="middle"><input name="user_segmentacion[<?=$cont_user_segmentacion?>]" <?= $mod6_sel_user_segmentacion ?> type="checkbox" id="user_segmentacion[<?=$cont_user_segmentacion?>]" value="<?=$rs_user_segmentacion['iduser_segmentacion']?>"/></td>
                  <td align="left" valign="middle" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                      <?=$rs_user_segmentacion['titulo']?>
                  </div></td>
                </tr>
                <?
								};
							 ?>
            </table></td>
          <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td height="40" align="left" valign="bottom" class="detalle_medio_bold">Segmentaciones Especiales:</td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
                <?
							  
							  $query_user_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = 4
							  ORDER BY titulo";
							  $result_user_segmentacion = mysql_query($query_user_segmentacion);
							  while ($rs_user_segmentacion = mysql_fetch_assoc($result_user_segmentacion))	  
							  {
							  	$cont_user_segmentacion++;
								
									$query = "SELECT *
									FROM user_web_segmentacion
									WHERE iduser_segmentacion = '$rs_user_segmentacion[iduser_segmentacion]' AND iduser_web = '$iduser_web'";
									if(mysql_num_rows(mysql_query($query)) > 0 ){
										$mod6_sel_user_segmentacion = "checked";
									}else{
										$mod6_sel_user_segmentacion = "";
									}
							 ?>
                <tr>
                  <td width="20" align="center" valign="middle"><input name="user_segmentacion[<?=$cont_user_segmentacion?>]" type="checkbox" id="user_segmentacion[<?=$cont_user_segmentacion?>]" value="<?=$rs_user_segmentacion['iduser_segmentacion']?>" <?= $mod6_sel_user_segmentacion ?> /></td>
                  <td align="left" valign="middle" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                      <?=$rs_user_segmentacion['titulo']?>
                  </div></td>
                </tr>
                <?
								};
							 ?>
            </table></td>
        </tr>
      </table>
      <table width="90%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td align="left"><span style="font-size: 11px"><a href="javascript:seleccionar_todo();" style="color:#990000">Seleccionar todo</a> &nbsp;/ &nbsp;<a href="javascript:deseleccionar_todo();" style="color:#990000">Deseleccionar todo </a></span></td>
          </tr>
        </table><script language="JavaScript" type="text/javascript">
function seleccionar_todo(){
	for(i=1; i<<?=$cont_user_segmentacion+1?>; i++){
		ncampo = "user_segmentacion["+i+"]";
		campo = document.getElementById(ncampo);
		campo.checked = true;
	}
}
function deseleccionar_todo(){
	for(i=1; i<<?=$cont_user_segmentacion+1?>; i++){
		ncampo = "user_segmentacion["+i+"]";
		campo = document.getElementById(ncampo);
		campo.checked = false;
	}
}
                </script></td>
  </tr>
</table>
<br />
                        <table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFF0E1">
                          <tr>
                            <td width="10">&nbsp;</td>
                            <td width="646"><input name="ingresar" type="button" class="detalle_medio_bold" id="ingresar" onclick="validar_registro();" value="  &gt;&gt;  Modificar   " />
                            </td>
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