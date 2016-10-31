<? 
	include("0_include/0_mysql.php");
	include("0_include/0_creacion_mail.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

	//VARIABLES
	$accion = $_POST['accion'];
	
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$sexo = $_POST['sexo'];
	
	$dia_fecha_nacimiento = $_POST['dia_fecha_nacimiento'];
	$mes_fecha_nacimiento = $_POST['mes_fecha_nacimiento'];
	$ano_fecha_nacimiento = $_POST['ano_fecha_nacimiento'];
	$fecha_nacimiento = $ano_fecha_nacimiento."-".$mes_fecha_nacimiento."-".$dia_fecha_nacimiento;
	
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
	$ididioma = $_POST['iidioma'];
	$iduser_web_perfil = $_POST['iduser_web_perfil'];
	$idsede = $_POST['idsede'];
	
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
	
	$user_segmentacion = $_POST['segmentacion'];
	$cont_user_segmentacion = $_POST['cont_segmentacion'];
	$fecha = date('Y').'-'.date('m').'-'.date('d');
	
	//DATOS DE PARAMETRIZACION DE LA REGISTRACION
	$query_reg = "SELECT A.reg_mail_moderador, A.reg_aprobacion_moderador , A.reg_confirmacion_usuario, A.reg_mail_desde, A.reg_titulo, A.cont_prod_mail_ideas2
	FROM dato_sitio A
	WHERE A.iddato_sitio = '1' ";
	$rs_reg = mysql_fetch_assoc(mysql_query($query_reg));
	
	$confirmacion_usuario = $rs_reg['reg_confirmacion_usuario'];
	$aprobacion_moderador = $rs_reg['reg_aprobacion_moderador'];
	$mail_moderador = $rs_reg['reg_mail_moderador'];
	$reg_titulo = $rs_reg['reg_titulo'];
	$reg_mail_desde = $rs_reg['reg_mail_desde'];
	
	//SI NO NECESITA NI CONFIRMACION NI APROBACION SE DA DE ALTA AUTOMATICAMENTE
	if($confirmacion_usuario == 2 && $aprobacion_moderador == 2){
		$valor_estado = 1; //HABILITADO
	}else{
		$valor_estado = 3; //SIN CONFIRMACION
	}

	//INGRESAR
	if($accion == "ingresar"){
	
		//se checkea si hay un usuario con el mismo mail
		$query_usuario = "SELECT count(mail) AS existe FROM user_web WHERE mail = '$mail' ";
		$rs_usuario = mysql_fetch_assoc(mysql_query($query_usuario));
		
		if($rs_usuario['existe'] > 0){
			$error = "Ya existe un usuario registrado con el e-mail: '$mail'.";
		}else{
	
			$query_ingresar = "INSERT INTO user_web (
			  nombre
			, apellido
			, sexo
			, fecha_nacimiento
			, mail
			, telefono
			, celular
			, fax
			, calle
			, calle_numero
			, entre_calles
			, cp
			, piso
			, depto
			, idpais
			, idpais_provincia
			, localidad
			, username
			, password
			, ididioma
			, idsede
			, fecha_alta
			, fecha_baja
			, iduser_web_perfil
			, emp_denominacion
			, emp_direccion
			, emp_telefono
			, emp_fax
			, emp_web
			, emp_cargo
			, emp_mail
			, emp_cuit
			, estado
			) VALUES (
			  '$nombre'
			, '$apellido'
			, '$sexo'
			, '$fecha_nacimiento'
			, '$mail'
			, '$telefono'
			, '$celular'
			, '$fax'
			, '$calle'
			, '$calle_numero'
			, '$entre_calles'
			, '$cp'
			, '$piso'
			, '$depto'
			, '$idpais'
			, '$idpais_provincia'
			, '$localidad'
			, '$username'
			, '$password'
			, '$ididioma'
			, '$idsede'
			, '$fecha_alta'
			, '$fecha_baja'
			, '$iduser_web_perfil'
			, '$emp_denominacion'
			, '$emp_direccion'
			, '$emp_telefono'
			, '$emp_fax'
			, '$emp_web'
			, '$emp_cargo'
			, '$emp_mail'
			, '$emp_cuit'
			, '$valor_estado'
			)";
			mysql_query($query_ingresar);
			$error = "El usuario fue ingresado exitosamente.";
			
			$query_max = "SELECT MAX(iduser_web) AS id FROM user_web LIMIT 1";
			$rs_max = mysql_fetch_assoc(mysql_query($query_max));
			$iduser_web_nuevo = $rs_max['id'];
			
			//SE INGRESAN LAS SEGMENTACIONES DEL USUARIO
			if($iduser_web_nuevo != 0){
				for($i=1; $i<$cont_user_segmentacion+1; $i++){
					if($user_segmentacion[$i]){
						$user_segmentacion_actual = $user_segmentacion[$i];
						$query_user_segmentaciones = "INSERT INTO user_web_segmentacion (iduser_web, iduser_segmentacion) VALUES ('$iduser_web_nuevo','$user_segmentacion_actual') ";
						mysql_query($query_user_segmentaciones);
					}
				}
			}
			
			//SE RESETEA EL FORMULARIO
			echo "<script>document.frm.reset();</script>";
		
			//TOMO LOS DATOS DEL USUARIO RECIEN INGRESADO
			$query_iduser_web = "SELECT MAX(iduser_web) AS iduser_web FROM user_web LIMIT 1 ";
			$rs_iduser_web = mysql_fetch_assoc(mysql_query($query_iduser_web));
			
			//CREO EL HASH DEL USUARIO
			$hash = $rs_iduser_web['iduser_web'];
			$hash = md5($hash);
			
			//GUARDO EL HASH
			$query_modificar = "UPDATE user_web
			SET hash='$hash'
			WHERE iduser_web = $rs_iduser_web[iduser_web] ";
			mysql_query($query_modificar);
			
			
			//SI NECESITA CONFRIMACION DE USUARIO
			if($confirmacion_usuario == 1){
			
				//CREACION DEL MAIL:
				$dominio = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/";
				$mensaje = mail_registro_paso01($dominio, $reg_titulo, $nombre, $hash);
				
				//ENVIO DEL MAIL DE CONFIRMACION
				$headers = "Content-type: text/html;\n Content-Type: image/jpg;\n Content-Transfer-Encoding: base64;\n charset=iso-8859-1\n";
				$headers .= "From: ".$reg_mail_desde."\r\n";
				$asunto = $reg_titulo." - Confirmación de registro - ".$mail;
				$destino = $mail;
					
				//SE ENVIA EL MAIL
				if(mail($destino, $asunto, $mensaje, $headers) == true){
					mail($rs_reg['cont_prod_mail_ideas2'], $asunto, $mensaje, $headers);
					echo "<script>document.location.href = '".$_SERVER['PHP_SELF']."?registro=msj01';</script>";
				}else{
					$query_eliminar = "DELETE FROM user_web WHERE iduser_web = '$rs_iduser_web[iduser_web]' LIMIT 1 ";
					mysql_query($query_eliminar);
					$query_segmentaciones = "DELETE FROM user_web_segmentacion WHERE iduser_web = '$rs_iduser_web[iduser_web]' ";
					mysql_query($query_segmentaciones);
					echo "<script>document.location.href= '".$_SERVER['PHP_SELF']."?error=Ha habido un error al registrar. Por favor aguarde unos minutos e intente nuevamente.';</script>";
				}
				
			}else{//SI NO NECESITA CONFRIMACION DE USUARIO
			
				//SI VA A NECESITAR APROBACION
				if($aprobacion_moderador == 1){
					
					//ENVIAR MAIL DE APROBACION AL MODERADOR
					//CREACION DEL MAIL:
					$dominio = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/";
					$mensaje = mail_registro_paso02($dominio, $reg_titulo, $rs_hash_cant['nombre'].' '.$rs_hash_cant['apellido'], $mail, $rs_iduser_web['iduser_web']);
					
					//ENVIO DEL MAIL DE CONFIRMACION
					$headers = "Content-type: text/html;\n Content-Type: image/jpg;\n Content-Transfer-Encoding: base64;\n charset=iso-8859-1\n";
					$headers .= "From: ".$reg_mail_desde."\r\n";
					$asunto = $reg_titulo." - Aprobación de usuario: ".$mail;
						
					//SE ENVIA EL MAIL
					if(mail($mail_moderador, $asunto, $mensaje, $headers) == true){
						mail($rs_reg['cont_prod_mail_ideas2'], $asunto.' [reenvio]', $mensaje, $headers);
						echo "<script>document.location.href= '".$_SERVER['PHP_SELF']."?registro=msj02';</script>";
					}
				
				}else{//SI NO VA A NECESITAR APROBACION YA SE LO DA DE ALTA
				
					//SE DA DE ALTA
					$query_modificar = "UPDATE user_web SET estado = '1' , fecha_alta = '$fecha'
					WHERE iduser_web = '$rs_iduser_web[iduser_web]'
					LIMIT 1";
					mysql_query($query_modificar);
					
					//SE LOGUEA AUTOMATICAMENTE
					$HTTP_SESSION_VARS['iduser_web_session'] = $rs_hash_cant['iduser_web'];
					$HTTP_SESSION_VARS['mail_session'] = $rs_hash_cant['mail'];
					echo "<script>document.location.href= '".$_SERVER['PHP_SELF']."?mensaje=ok';</script>";
				}
				
			}
		};
	}// FIN MODIFICAR LOS DATOS DEL USUARIO

	//SE COMPLETA EL REGISTRO
	if($_GET['hash'] != ''){
	
		$query_hash_cant = "SELECT * FROM user_web WHERE hash = '$hash' ";
		$rs_hash_cant = mysql_fetch_assoc(mysql_query($query_hash_cant));
		$cant_hash = mysql_num_rows(mysql_query($query_hash_cant));
		
		if($cant_hash != 1){
			echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?mensaje=error')</script>";
		}else{
		
			//SI NECESITA APROBACION DE UN MODERADOR
			if($aprobacion_moderador == 1){
			
				//CREACION DEL MAIL:
				$dominio = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/";
				$mensaje = mail_registro_paso02($dominio, $reg_titulo, $rs_hash_cant['nombre'].' '.$rs_hash_cant['apellido'], $rs_hash_cant['mail'], $rs_hash_cant['iduser_web']);
				
				//ENVIO DEL MAIL DE CONFIRMACION
				$headers = "Content-type: text/html;\n Content-Type: image/jpg;\n Content-Transfer-Encoding: base64;\n charset=iso-8859-1\n";
				$headers .= "From: ".$reg_mail_desde."\r\n";
				$asunto = $reg_titulo." - Aprobación de usuario: ".$mail;
					
				//SE ENVIA EL MAIL
				if(mail($mail_moderador, $rs_dato_sitio['emp_prefijo']." - ".$asunto, $mensaje, $headers) == true){
					mail($rs_reg['cont_prod_mail_ideas2'], $rs_dato_sitio['emp_prefijo']." - ".$asunto.' [reenvio]', $mensaje, $headers);
					echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?registro=msj02')</script>";
				}
				
			}else{//SI NO NECESITA APROBACION DE UN MODERADOR
				
				//SE DA DE ALTA
				$query_modificar = "UPDATE user_web SET estado = '1', fecha_alta = '$fecha'
				WHERE iduser_web = '$rs_hash_cant[iduser_web]'
				LIMIT 1";
				mysql_query($query_modificar);
				
				//SE LOGUEA AUTOMATICAMENTE
				$HTTP_SESSION_VARS['iduser_web_session'] = $rs_hash_cant['iduser_web'];
				$HTTP_SESSION_VARS['mail_session'] = $rs_hash_cant['mail'];
				echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."?mensaje=ok')</script>";
			}
			
		}
	}; 
?>

<?

	switch($_GET['form']){
		
		case 1:
			//FORMULARIO CONTACTO
			$datos_personales_simple = true;				/* Nombre, Apellido, E-mail, Telefono */
			$datos_personales_complemento = false;			/* Sexo, Fecha nacimiento */
			$datos_personales_contacto = true;				/* Celular, Fax */
			$datos_personales_direccion_simple = true; 		/* Calle, Numero, Piso, Depto, Pais, Provincia, Localidad */
			$datos_personales_direccion_complemento = false;/* Entre calles, Cod. Postal */
			$datos_ingreso_simple = true;					/* username, password, tipo usuario */
			$datos_ingreso_complemento = false;				/* idioma, sucursal */
			$datos_empresa_simple = false;					/* Denominacion */
			$datos_empresa_complemento = false;				/* direccion, telefono, fax, email, web, cargo, cuit */
			break;
		
		case 2:
			//FORMULARIO NEWSLETTER
			$datos_personales_simple = true;				/* Nombre, Apellido, E-mail, Telefono */
			$datos_personales_complemento = false;			/* Sexo, Fecha nacimiento */
			$datos_personales_contacto = false;				/* Celular, Fax */
			$datos_personales_direccion_simple = false; 	/* Calle, Numero, Piso, Depto, Pais, Provincia, Localidad */
			$datos_personales_direccion_complemento = false;/* Entre calles, Cod. Postal */
			$datos_ingreso_simple = false;					/* username, password, tipo usuario */
			$datos_ingreso_complemento = true;				/* idioma, sucursal */
			$datos_empresa_simple = false;					/* Denominacion */
			$datos_empresa_complemento = false;				/* direccion, telefono, fax, email, web, cargo, cuit */
			break;
		
		default:
			$datos_personales_simple = true;				/* Nombre, Apellido, E-mail, Telefono */
			$datos_personales_complemento = true;			/* Sexo, Fecha nacimiento */
			$datos_personales_contacto = true;				/* Celular, Fax */
			$datos_personales_direccion_simple = true; 		/* Calle, Numero, Piso, Depto, Pais, Provincia, Localidad */
			$datos_personales_direccion_complemento = true;	/* Entre calles, Cod. Postal */
			$datos_ingreso_simple = true;					/* username, password, tipo usuario */
			$datos_ingreso_complemento = true;				/* idioma, sucursal */
			$datos_empresa_simple = true;					/* Denominacion */
			$datos_empresa_complemento = true;				/* direccion, telefono, fax, email, web, cargo, cuit */
			break;
	}



?>
<head>

<link rel="stylesheet" href="skin/index/css/0_body.css" tppabs="css/0_body.css" type="text/css">
<link rel="stylesheet" href="skin/index/css/0_fonts_tiny.css" tppabs="css/0_fonts_tiny.css" type="text/css">
<link rel="stylesheet" href="skin/index/css/0_fonts.css" tppabs="css/0_fonts.css" type="text/css">
<link rel="stylesheet" href="skin/index/css/0_especial.css" tppabs="css/0_especial.css" type="text/css">
<link rel="shortcut icon" href="favicon.ico">

<?
	include_once("0_include/0_head.php"); 
	include_once("0_include/0_scripts.php");
?>

<script language="javascript">
	
	function pasar_cuit(campo){
		var f = document.frm;
		
		switch(campo){
			
			case '1':
				if(f.cuit1.value.length == 2){
					f.cuit2.focus();
				}
				break;
				
			case '2':
				if(f.cuit2.value.length == 8){
					f.cuit3.focus();
				}else if(f.cuit1.value.length < 2){
					f.cuit1.focus();
				}
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
		var error = '';
		
		
		<? if($datos_personales_simple == true){ ?>
		//Nombre (*)
		if (f.nombre.value.length < 4){
			error = error + 'Debe ingresar su nombre.\n';
			flag = false;	
		}
		
		//Apellido (*)
		if (f.apellido.value.length < 4){
			error = error + 'Debe ingresar su apellido.\n'; 
			flag = false;	
		}
		<? } ?>
		
		<? if($datos_ingreso_simple == true){ ?>
		//Username (*)
		if (f.username.value.length < 4)	{
			error = error + 'Debe ingresar su username. (4 dígitos min.)\n';
			flag = false;	
		}
		<? } ?>
		
		<? if($datos_personales_simple == true){ ?>
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
		<? } ?>
		
		<? if($datos_ingreso_simple == true){ ?>	
		//Password (*)
		if(f.password.value.length != 0 ){
			if (f.password.value.length < 4 )	{
				error = error + "La contraseña debe ser de al menos 4 caracteres.\n";
				flag = false;
			}else{
				if(f.password.value != f.confirmar_password.value){
					error = error + "La contraseña no coincide con la contraseña de confirmación.\n";
					flag = false;
				}
			}
		}else{
			error = error + "Debe ingresar la contraseña.\n";
			flag = false;
		}
		<? } ?>
		
		<? if($datos_personales_simple == true){ ?>
		//Telefono (*)
		if (esTelefono(f.telefono.value) == false){
			error = error + 'Verifique que su numero de telefono sea correcto.\n';
			flag = false;
		}
		<? } ?>
		
		<? if($datos_personales_contacto == true){ ?>
		//Telefono (*)
		if (esTelefono(f.celular.value) == false || esTelefono(f.fax.value) == false){
			error = error + 'Verifique que sus numeros de celular o fax sean correctos.\n';
			flag = false;
		}
		<? } ?>
		
		<? if($datos_personales_direccion_simple == true){ ?>
		//Calle Numero
		if(esNumerico(f.calle_numero.value) == false){
			error = error + 'El número de su domicilio debe ser númerico.\n';
			flag = false;
		}
		<? } ?>
		
		<? if($datos_personales_direccion_complemento == true){ ?>
		//Codigo Postal
		if(esNumerico(f.cp.value) == false){
			error = error + 'Verifique que sus numeros de contacto telefonico sean correctos.\n';
			flag = false;
		}
		<? } ?>
		
		<? if($datos_empresa_complemento == true){ ?>
		//Empresa Telefono
		if (esTelefono(f.emp_telefono.value) == false || esTelefono(f.emp_fax.value) == false ){
			error = error + 'Verifique que sus números de contacto empresarial telefonicos sean correctos.\n';
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
		<? } ?>
		
		//FINAL
		if (flag == true) {
			document.frm.accion.value = "ingresar";
			document.frm.submit(); 
		}else{
			alert(error);
		}
	
	};
	
	function copiar_a_username(){
	
		var f = document.frm;
			if(f.username.value == ""){
				f.username.value = f.mail.value;
			}
			
	};
	
</script>

</head>
<body>
<div id="header"><? include_once("0_include/0_top.php"); ?></div>
<div id="cuerpo">
	<div id="barra_izq_imagen"></div>
	
	<div id="barra">
		<? include("0_include/0_barra.php"); ?>
	</div>
  <div id="contenido">
    <? if(!$registro && !$hash && !$mensaje){ ?>
    <form action="" method="post" name="frm" id="frm">
      <table width="98%" border="0" align="center" cellpadding="0" cellspacing="5">
        <tr>
          <td height="60" class="registro_B">Registrarse tiene sus beneficios: acceda a informaci&oacute;n exclusiva, novedades del sitio, lanzamientos de productos, nuevos servicios y ofertas, entre otros&hellip; la mejor forma de estar siempre actualizado.</td>
        </tr>
        <? if($error){ ?>
        <tr>
          <td align="center" class="ejemplo_negro_12px" style="color:#FF0000; font-weight:bold"><?=$error?></td>
        </tr>
        <? }; ?>
      </table>
      <input name="accion" type="hidden" id="accion" value="0" />
      <table width="98%" border="0" align="center" cellpadding="5" cellspacing="0">
        <tr>
          <td align="center" valign="middle" bgcolor="#EBF4FC">
		  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="detalle_medio">
           
		    <? if($datos_personales_simple || $datos_personales_complemento || $datos_personales_contacto || $datos_personales_direccion_simple || $datos_personales_direccion_complemento){ ?>
			<tr>
              <td width="50%" height="25" align="left" valign="bottom" class="registro_A">Datos personales </td>
              <td height="25" colspan="3" align="left" valign="bottom" class="detalle_medio">&nbsp;</td>
            </tr>
			
			<? if($datos_personales_simple == true){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Nombres: (*) </td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Apellidos: (*)</td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><input name="nombre" type="text" class="registro_B" id="nombre" value="<?=$nombre?>" size="27" style="width:90%;" /></td>
              <td colspan="3" align="left"><span class="detalle_chico" style="color:#FF0000">
                <input name="apellido" type="text" class="registro_B" id="apellido" value="<?=$apellido?>" size="27" style="width:90%" />
              </span></td>
            </tr>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">E-mail: (*)</td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Telefono:</td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><label>
              <input name="mail" type="text" class="registro_B" id="mail" style="width:90%" value="<?=$mail?>" size="27" onchange="copiar_a_username();" />
              </label></td>
              <td colspan="3" align="left"><span class="style2"><span class="detalle_chico" style="color:#FF0000">
                <input name="telefono" type="text" class="registro_B" id="telefono" style="width:90%" value="<?=$telefono?>" size="27" />
                </span></span></td>
            </tr>
			<? } ?>
			
			<? if($datos_personales_contacto == true){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Celular:</td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Fax:</td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><input name="celular" type="text" class="registro_B" id="celular" style="width:90%" value="<?=$celular?>" size="27" /></td>
              <td colspan="3" align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                <input name="fax" type="text" class="registro_B" id="fax" style="width:90%" value="<?=$fax?>" size="27" />
              </span></td>
            </tr>
			<? } ?>
			
			<? if($datos_personales_complemento == true){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Sexo:</td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Fecha de Nacimiento:</td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><select name="sexo" class="registro_B" id="sexo" style="width:140px;">
                <option value="N" <? if($sexo == "N"){ echo "selected"; } ?>> - No seleccionado. </option>
                <option value="M" <? if($sexo == "M"){ echo "selected"; } ?>>M</option>
                <option value="F" <? if($sexo == "F"){ echo "selected"; } ?>>F</option>
              </select></td>
              <td colspan="3" align="left" class="detalle_chico" style="color:#FF0000"><span class="style2">
                <select name="dia_fecha_nacimiento" size="1" class="registro_B" id="dia_fecha_nacimiento">
                  <option value='00' ></option>
                  <?												
						for ($i=1;$i<32;$i++){
							if ($dia_fecha_nacimiento == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                </select>
                <select name="mes_fecha_nacimiento" size="1" class="registro_B" id="mes_fecha_nacimiento">
                  <option value='00' ></option>
                  <?						
                        for ($i=1;$i<13;$i++){
							if ($mes_fecha_nacimiento == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                </select>
                <select name="ano_fecha_nacimiento" size="1" class="registro_B" id="ano_fecha_nacimiento">
                  <option value='0000' ></option>
                  <?	
						$anioActual = date("Y");
                        for ($i=$anioActual-8;$i>($anioActual-80);$i--){
							if ($ano_fecha_nacimiento == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                </select>
                </font></span> </td>
            </tr>
			<? } ?>
			
			<? if($datos_personales_direccion_simple == true){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Calle:</td>
              <td width="16%" height="25" align="left" valign="bottom" class="registro_B">N&uacute;mero:</td>
              <td width="16%" align="left" valign="bottom" class="registro_B">Piso</td>
              <td width="18%" align="left" valign="bottom" class="registro_B">Depto</td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><input name="calle" type="text" class="registro_B" id="calle" style="width:90%" value="<?=$calle?>" size="27" /></td>
              <td align="left" class="detalle_chico"><input name="calle_numero" type="text" class="registro_B" id="calle_numero" style="width:70px;" value="<?=$calle_numero?>" size="27" /></td>
              <td align="left" class="detalle_chico"><input name="piso" type="text" class="registro_B" id="piso" style="width:70px" value="<?=$piso?>" size="27" maxlength="2" /></td>
              <td align="left" class="detalle_chico"><span class="detalle_chico" style="color:#FF0000">
                <input name="depto" type="text" class="registro_B" id="depto" style="width:70px" value="<?=$depto?>" size="27" maxlength="4" />
              </span></td>
            </tr>
			<? } ?>
			
			<? if($datos_personales_direccion_complemento == true){ ?>
             <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Entre las calles: </td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Cod. Postal:<a href="http://www.correoargentino.com.ar/consulta_cpa/cons_.php" class="detalle_11px"></a></td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><input name="entre_calles" type="text" class="registro_B" id="entre_calles" style="width:90%" value="<?=$entre_calles?>" size="27" /></td>
              <td colspan="3" align="left" valign="middle" class="detalle_chico"><input name="cp" type="text" class="registro_B" id="cp" style="width:70px" value="<?=$cp?>" size="27" />
                <a href="http://www.correoargentino.com.ar/consulta_cpa/cons_.php" target="_blank" class="detalle_11px">Si no conoce su C.P., averiguelo aqu&iacute;.</a></td>
            </tr>
			<? } ?>
			
			<? if($datos_personales_direccion_simple == true){ ?>
             <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Pa&iacute;s:</td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B" >Provincia:</td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><select name="idpais" class="registro_B" id="idpais" style="width:90%" onchange="document.frm.submit();">
                  <option value="0" selected="selected">- Seleccione su pa&iacute;s </option>
                  <?
									  
									  $query_pais = "SELECT * 
									  FROM pais 
									  WHERE estado = '1' ORDER BY titulo ASC";
									  $result_pais = mysql_query($query_pais);
									  while ($rs_pais = mysql_fetch_assoc($result_pais))
									  {
											  
										if ($idpais == $rs_pais['idpais'])
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
                </select>              </td>
              <td colspan="3" align="left" class="detalle_chico" style="color:#FF0000"><select name="idpais_provincia" class="registro_B" id="select" style="width:90%">
                  <option value="0" selected="selected">- Seleccione su provincia </option>
                  <?
								  $query_provincia = "SELECT * 
								  FROM pais_provincia
								  WHERE estado = '1' AND idpais = '$idpais'
								  ORDER BY titulo ASC";
								  $result_provincia = mysql_query($query_provincia);
								  while ($rs_provincia = mysql_fetch_assoc($result_provincia))
								  {
										  
									if ($idpais_provincia == $rs_provincia['idpais_provincia'])
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
              <td height="25" align="left" valign="bottom" class="registro_B">Localidad:</td>
              <td height="25" colspan="3" align="left" valign="bottom" class="detalle_medio">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" class="detalle_medio"><input name="localidad" type="text" class="registro_B" id="localidad" value="<?=$localidad?>" size="27" style="width:90%" /></td>
              <td colspan="3" align="left" class="detalle_chico" style="color:#FF0000">&nbsp;</td>
            </tr>
			<? } ?>
			
            <tr>
              <td height="20" colspan="4" align="left" valign="middle" class="detalle_medio"><hr size="1" class="detalle_medio" /></td>
            </tr>
			<? } ?>
			
			<? if($datos_ingreso_simple || $datos_ingreso_complemento){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" bgcolor="#AFD3F3" class="registro_A">Datos de ingreso: </td>
              <td height="25" colspan="3" align="left" bgcolor="#AFD3F3" class="detalle_chico" style="color:#FF0000">&nbsp;</td>
            </tr>
			<? if($datos_ingreso_simple == true){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" bgcolor="#AFD3F3" class="registro_B">Username: (*) </td>
              <td height="25" colspan="3" align="left" valign="bottom" bgcolor="#AFD3F3" class="registro_B">Password: (*) </td>
            </tr>
            <tr>
              <td align="left" bgcolor="#AFD3F3" class="detalle_medio"><input name="username" type="text" class="registro_B" id="username" value="<?=$username?>" size="27" style="width:90%" /></td>
              <td colspan="3" align="left" bgcolor="#AFD3F3"><input name="password" type="password" class="registro_B" id="password" value="<?=$password?>" size="27" style="width:90%" /></td>
            </tr>
            <tr>
              <td height="25" align="left" bgcolor="#AFD3F3" class="registro_B">Tipo de usuario: </td>
              <td height="25" colspan="3" align="left" bgcolor="#AFD3F3" class="registro_B">Confimar password: (*) </td>
            </tr>
            <tr>
              <td align="left" bgcolor="#AFD3F3" class="detalle_medio"><label>
                <select name="iduser_web_perfil" class="registro_B" id="iduser_web_perfil" style="width:90%">
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
              <td colspan="3" align="left" bgcolor="#AFD3F3"><input name="confirmar_password" type="password" class="registro_B" id="confirmar_password" value="<?=$password?>" size="27" style="width:90%" /></td>
            </tr>
			<? } ?>

			<? if($datos_ingreso_complemento == true){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" bgcolor="#AFD3F3" class="registro_B">Idioma:</td>
              <td height="25" colspan="3" align="left" valign="bottom" bgcolor="#AFD3F3" class="registro_B">Registrado en la Sucursal:</td>
            </tr>
            <tr>
              <td align="left" valign="middle" bgcolor="#AFD3F3" class="detalle_medio"><label class="detalle_medio">
                <select name="ididioma" class="registro_B" id="ididioma" style="width:90%">
                  <?
									  $query = "SELECT ididioma, titulo_idioma
									  FROM idioma
									  WHERE estado = 1
									  ORDER BY ididioma";
									  $result = mysql_query($query);
									  while($rs_idioma = mysql_fetch_assoc($result)){
									  ?>
                  <option value="<?= $rs_idioma['ididioma'] ?>" <? if($rs_idioma['ididioma'] == $ididioma_session){ echo "selected";} ?>>
                    <?= $rs_idioma['titulo_idioma'] ?>
                    </option>
                  <? } ?>
                </select>
              </label></td>
              <td colspan="3" align="left" valign="middle" bgcolor="#AFD3F3" class="detalle_chico" style="color:#FF0000"><select name="idsede" class="registro_B" id="idsede" style="width:90%">
                  <?
									  $query = "SELECT idsede, titulo
									  FROM sede
									  WHERE estado = 1
									  ORDER BY idsede ASC";
									  $result = mysql_query($query);
									  while($rs_sede = mysql_fetch_assoc($result)){
									  ?>
                  <option value="<?= $rs_sede['idsede'] ?>" <? if($rs_sede['idsede'] == $idsede_session){ echo "selected";} ?>>
                  <?= $rs_sede['titulo'] ?>
                  </option>
                  <? } ?>
              </select></td>
            </tr>
			<? } ?>

            <tr>
              <td height="20" colspan="4" align="left" bgcolor="#AFD3F3" class="detalle_medio"><hr size="1" class="detalle_medio" /></td>
            </tr>
			<? } ?>
			
			<? if($datos_empresa_simple || $datos_empresa_complemento){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_A">Datos de su empresa: </td>
              <td height="25" colspan="3" align="left" class="detalle_chico" style="color:#FF0000">&nbsp;</td>
            </tr>
            <tr>
              <? if($datos_empresa_simple == true){ ?>
			  <td height="25" align="left" valign="bottom" class="registro_B">Denominaci&oacute;n: (Nombre fantasia/Raz&oacute;n social) </td>
			  <? } ?>
			  
			  <? if($datos_empresa_complemento == true){ ?>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Direcci&oacute;n:</td>
			  <? }else{ ?>
			  <td height="25" colspan="3" align="left" valign="bottom" class="registro_B"></td>
			  <? } ?>
            </tr>
			
            <tr>
			  <? if($datos_empresa_simple == true){ ?>
              <td align="left" class="detalle_medio"><input name="emp_denominacion" type="text" class="registro_B" id="emp_denominacion" value="<?=$emp_denominacion?>" size="27" style="width:90%" /></td>
			  <? } ?>
			  <? if($datos_empresa_complemento == true){ ?>
              <td colspan="3" align="left" class="detalle_chico"><input name="emp_direccion" type="text" class="registro_B" id="emp_direccion" value="<?=$emp_direccion?>" size="27" style="width:90%" /></td>
			  <? }else{ ?>
			  <td colspan="3" align="left" class="detalle_chico"></td>
			  <? } ?>
            </tr>
			
			<? if($datos_empresa_complemento == true){ ?>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Telefono:</td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Fax:</td>
            </tr>
            <tr>
              <td align="left" class="ejemplo_11px"><input name="emp_telefono" type="text" class="registro_B" id="emp_telefono" value="<?=$emp_telefono?>" size="27" style="width:90%" /></td>
              <td colspan="3" align="left" class="ejemplo_11px"><input name="emp_fax" type="text" class="registro_B" id="emp_fax" value="<?=$emp_fax?>" size="27" style="width:90%" /></td>
            </tr>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">E-mail:</td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">Web:</td>
            </tr>
            <tr>
              <td align="left" class="ejemplo_11px"><input name="emp_mail" type="text" class="registro_B" id="emp_mail" value="<?=$emp_mail?>" size="27" style="width:90%" /></td>
              <td colspan="3" align="left" class="ejemplo_11px"><input name="emp_web" type="text" class="registro_B" id="emp_web" value="<?=$emp_web?>" size="27" style="width:90%" /></td>
            </tr>
            <tr>
              <td height="25" align="left" valign="bottom" class="registro_B">Cargo en la empresa. </td>
              <td height="25" colspan="3" align="left" valign="bottom" class="registro_B">C.U.I.T.:</td>
            </tr>
            <tr>
              <td height="13" align="left" valign="bottom" class="ejemplo_11px"><input name="emp_cargo" type="text" class="registro_B" id="emp_cargo" value="<?=$emp_cargo?>" size="27" style="width:90%" /></td>
              <td height="13" colspan="3" align="left" valign="bottom" class="registro_B"><label>
                <input name="cuit1" type="text" class="registro_B" id="cuit1" style="width:16px; letter-spacing:1px;" onkeyup="pasar_cuit('1');" value="<?= $cuit1 ?>" maxlength="2">
                -
                <input name="cuit2" type="text" class="registro_B" id="cuit2" style="width:65px; letter-spacing:1px;"   onkeyup="pasar_cuit('2');" value="<?= $cuit2 ?>" maxlength="8" />
                -
                <input name="cuit3" type="text" class="registro_B" id="cuit3" style="width:15px; letter-spacing:1px;"  onkeyup="pasar_cuit('3');" value="<?= $cuit3 ?>" maxlength="1" />
              </label></td>
            </tr>
			<? } ?>
			<? } ?>
          </table></td>
        </tr>
      </table>
      <br />
      <table width="98%" border="0" align="center" cellpadding="8" cellspacing="0" bgcolor="ffe8e8">
        <tr>
          <td align="left" bgcolor="#CDE3F5" class="titulo_b_mediano"><strong>Seleccione sus &aacute;reas de inter&eacute;s </strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#EBF4FC"><table width="90%" border="0" cellpadding="4" cellspacing="0">
            <tr>
              <td align="left" valign="top"><table width="90%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
                <?
					$cont_segmentacion = 0;
					$query_segmentacion = "SELECT *
					FROM user_segmentacion
					WHERE estado = '1' AND tipo = '1'
					ORDER BY iduser_segmentacion ASC";
					$result_segmentacion = mysql_query($query_segmentacion);
					while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){
						
						$cont_segmentacion++;
					
						if ($segmentacion == $rs_segmentacion['idsegmentacion']){
							$mod6_sel_segmentacion = "selected";
						}else{
							$mod6_sel_segmentacion = "";
						}
				 ?>
                <tr>
                  <td width="20" align="center" valign="middle"><input name="segmentacion[<?=$cont_segmentacion?>]" type="checkbox" id="segmentacion[<?=$cont_segmentacion?>]" value="<?=$rs_segmentacion['iduser_segmentacion']?>" checked="checked" /></td>
                    <td align="left" valign="middle" class="registro_B"><?=$rs_segmentacion['titulo']?></td>
                  </tr>
                <? }; ?>
              </table></td>
              </tr>
          </table>
          <table width="90%" border="0" cellspacing="0" cellpadding="8">
              <tr>
                <td align="left" class="detalle_b"><a href="javascript:seleccionar_todo();" >Seleccionar todo</a> &nbsp;/ &nbsp;<a href="javascript:deseleccionar_todo();">Deseleccionar todo </a></td>
              </tr>
            </table>
          <input name="cont_segmentacion" type="hidden" value="<?=$cont_segmentacion?>" /></td>
        </tr>
      </table>
      <div align="center">
		<script language="JavaScript" type="text/javascript">
		
		function seleccionar_todo(){
			for(i=1; i<<?=$cont_segmentacion+1?>; i++){
				ncampo = "segmentacion["+i+"]";
				campo = window.frm.document.getElementById(ncampo);
				campo.checked = true;
			}
		};
		
		function deseleccionar_todo(){
			for(i=1; i<<?=$cont_segmentacion+1?>; i++){
				ncampo = "segmentacion["+i+"]";
				campo = window.frm.document.getElementById(ncampo);
				campo.checked = false;
			}
		};
		
		</script>
        <br />
        <a href="javascript:validar_registro();"><img src="imagen/botones/completar_registro.jpg" name="b_enviar" width="197" height="38" border="0" id="b_enviar" /></a><br />
        <br />
      </div>
    </form>
    <? }; ?>
    <? if($registro){ ?>
    <table width="70%" height="200" border="0" align="center" cellspacing="15">
      <tr>
        <td align="center" class="titulo_b_chico" style="color:#3399CC; font-weight:bold"><p><br />
                <img src="imagen/iconos/mail_registro.jpg" width="101" height="110" /><br />
                <br />
				<?
				
				switch($registro){
					case 'msj01':
						echo "Se ha enviado un e-mail de confirmacion a su cuenta de mail.<br> En el mismo encontrará un link que le permitirá continuar con la registración. Muchas gracias.";
						break;
						
					case 'msj02':
						echo "Sus datos han sido ingresados correctamente. Un moderador del sitio se encargará de aprobar su solicitud de registración.<br><br>Usted será informado vía email cuando se encuentre confirmado. Muchas gracias.";
						break;
						
				}
				
				?>
				</p></td>
      </tr>
    </table>
    <? }; ?>
    <? if($mensaje == "ok"){ ?>
    <table width="70%" height="200" border="0" align="center" cellspacing="15">
      <tr>
        <td align="center" class="titulo_b_chico" style="color:#3399CC; font-weight:bold"><p><br />
                <img src="imagen/iconos/registro_ok.jpg" width="101" height="110" /><br />
                <br />
                <span class="style5">El registro se ha completado con &eacute;xito.</span><br />
                <br />
        </p></td>
      </tr>
    </table>
    <? }; ?>
    <? if($mensaje == "error"){ ?>
    <table width="70%" height="200" border="0" align="center" cellspacing="15">
      <tr>
        <td align="center" class="titulo_b_chico" style="color:#3399CC; font-weight:bold"><p><br />
                <img src="imagen/iconos/registro_error.jpg" width="101" height="110" /><br />
                <br />
                <span class="style4">Ha habido un error. El hash no es v&aacute;lido o no corresponde a un usuario en registro.</span><br />
                <br />
        <span class="style2">Intente registrarse nuevamente o bien comuniquese con nosotros a trav&eacute;s de &quot;contacto&quot; </span></p></td>
      </tr>
    </table>
    <? }; ?>
  </div>
	<div id="barra_der_imagen"></div>
</div>
<div id="footer">
<? include("0_include/0_pie.php"); ?>
</div>
</body>
</html>