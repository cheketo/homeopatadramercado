<?

	function mail_registro_paso01($dominio, $titulo, $nombre, $hash){
	
		$mensaje = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>'.$titulo.'</title>
			<style type="text/css">
			<!--
			body,td,th {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #666666;
			}
			body {
				background-color: #FFFFFF;
				margin-left: 0px;
				margin-top: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
			}
			-->
			</style></head>
			<body>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="center" valign="middle"><table width="620" border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td height="6"></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/top.jpg" width="619" height="106"></td>
				  </tr>
				  <tr>
					<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_izq.jpg"><img src="'.$dominio.'imagen/mail/sombra_izq.jpg" width="10" height="100%"></td>
						<td width="600" align="center" valign="top">';
						
						$mensaje .= '<table width="100%" border="0" cellpadding="20" cellspacing="0">
							<tr>
							  <td align="center" valign="middle" style="font-size:14px">
							  
							  	<p>'.$nombre.'<br><br>
								<strong>Muchas gracias por registrarse.</strong><br><br>
								Para completar el registro,<br><br>
								<a href="'.$dominio.'registro.php?hash='.$hash.'">haga click aqu&iacute;.</a><br><br>
								Si no puede acceder haciendo click, pegue esta direccion en su navegador:<br><br>
								<a href="'.$dominio.'registro.php?hash='.$hash.'">'.$dominio.'registro.php?hash='.$hash.'</a><br>
								</p>
							  
							  
								</td>
							</tr>
						  </table>';
						
						$mensaje.='<br></td>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_der.jpg"><img src="'.$dominio.'imagen/mail/sombra_der.jpg" width="10" height="100%"></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/pie.jpg" width="619" height="55"></td>
				  </tr>
				</table></td>
			  </tr>
			</table>
			</body>
			</html>';
		
		return $mensaje;
			
	}
	
	
	function mail_registro_paso02($dominio,$titulo,$nombre,$mail,$iduser){
	
	$mensaje = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>'.$titulo.'</title>
			<style type="text/css">
			<!--
			body,td,th {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #666666;
			}
			body {
				background-color: #FFFFFF;
				margin-left: 0px;
				margin-top: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
			}
			.style1 {
				font-size: 12px;
				font-family: Arial, Helvetica, sans-serif;
			}
			.style2 {font-size: 12px}
			.style3 {
				color: #669966;
				font-weight: bold;
			}
			.style4 {
				color: #FF0000;
				font-weight: bold;
			}
			a:link {
				text-decoration: none;
			}
			a:visited {
				text-decoration: none;
			}
			a:hover {
				text-decoration: underline;
			}
			a:active {
				text-decoration: none;
			}
			.style5 {
				font-size: 10px;
				font-family: Arial, Helvetica, sans-serif;
			}
			-->
			</style></head>
			<body>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="center" valign="middle"><table width="620" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="6"></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/top.jpg" width="619" height="106"></td>
				  </tr>
				  <tr>
					<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_izq.jpg"><img src="'.$dominio.'imagen/mail/sombra_izq.jpg" width="10" height="220"></td>
						<td width="600" align="center" valign="top">
						
						<table width="100%" border="0" cellpadding="20" cellspacing="0">
							<tr>
							  <td align="left" valign="middle" style="font-size:14px">
							  
							  	<p><span class="style2">El siguiente usuario ha completado el formulario de registro:</span><br />
							  	  <br />
						  	    <span class="style1"><strong>Nombre:</strong> '.$nombre.' <br />
						  	    <strong>E-mail:</strong> </span><span class="style1"> '.$mail.' </span><br />
						  	    <span class="style5"><br />
						  	    *Para ver m&aacute;s informaci&oacute;n de este usuario, <a href="'.$dominio.'ficha_usuario.php?mail='.$mail.'" target="_blank">ingrese aqu&iacute;.</a></span> <br>
							  	  <br />
							  	  <br>
							  	  <span class="style1"><strong>Por favor determine el estado de este usuario:&nbsp;&nbsp;  </strong>                                   
						  	      <a href="'.$dominio.'ficha_usuario.php?iduser_web='.$iduser.'&estado=1" target="_blank"><span class="style3">Aceptar</span></a>&nbsp; - 
					  	        &nbsp;<a href="'.$dominio.'ficha_usuario.php?iduser_web='.$iduser.'&estado=2" target="_blank"><span class="style4">Rechazar</span></a> </span></p>
							  	<p><span class="style5">Atenci&oacute;n: Si desea eliminar el usuario, deber&aacute; hacerlo desde el panel de administraci&oacute;n.</span> <br>
						  	    </p></td>
							</tr>
						  </table>
						<br></td>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_der.jpg"><img src="'.$dominio.'imagen/mail/sombra_der.jpg" width="10" height="220"></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/pie.jpg" width="619" height="55"></td>
				  </tr>
				</table></td>
			  </tr>
			</table>
			</body>
			</html>';
			
		return $mensaje;
	
	}
	
	function mail_registro_msj($dominio,$titulo,$username,$password){
	
		$mensaje = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>'.$titulo.'</title>
			<style type="text/css">
			<!--
			body,td,th {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #666666;
			}
			body {
				background-color: #FFFFFF;
				margin-left: 0px;
				margin-top: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
			}
			.style1 {
				font-size: 12px;
				font-family: Arial, Helvetica, sans-serif;
			}
			.style3 {
	color: #669966;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
			}
			a:link {
				text-decoration: none;
			}
			a:visited {
				text-decoration: none;
			}
			a:hover {
				text-decoration: underline;
			}
			a:active {
				text-decoration: none;
			}
			-->
			</style></head>
			<body>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="center" valign="middle"><table width="620" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="6"></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/top.jpg" width="619" height="106"></td>
				  </tr>
				  <tr>
					<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_izq.jpg"><img src="'.$dominio.'imagen/mail/sombra_izq.jpg" width="10" height="220"></td>
						<td width="600" align="center" valign="top">
						
						<table width="100%" border="0" cellpadding="20" cellspacing="0">
							<tr>
							  <td align="left" valign="middle" style="font-size:16px">
							  
							  	<p><span class="style3">&iexcl;Bienvenido/a!</span><span class="style1"><br /> 
			  	                <br />
			  	                Usted ha sido registrado en <strong>'.$titulo.'</strong>. <br />
					  	        <br />
					  	        <br />
					  	        Sus datos de ingreso son:<br />
					  	        <br />
					  	        <strong>Username:</strong> '.$username.' <br />
					  	        <strong>Pass:</strong> '.$password.'</span><br>
					  	      </p>
						  	  </td>
							</tr>
						  </table>
						<br></td>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_der.jpg"><img src="'.$dominio.'imagen/mail/sombra_der.jpg" width="10" height="220"></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/pie.jpg" width="619" height="55"></td>
				  </tr>
				</table></td>
			  </tr>
			</table>
			</body>
			</html>';
	
	}
	
	function mail_personalizado($dominio, $titulo, $cuerpo_msj){
	
		$mensaje = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>'.$titulo.'</title>
			<style type="text/css">
			<!--
			body,td,th {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				color: #666666;
			}
			body {
				background-color: #FFFFFF;
				margin-left: 0px;
				margin-top: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
			}
			.style1 {
				font-size: 12px;
				font-family: Arial, Helvetica, sans-serif;
			}
			.style3 {
	color: #669966;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
			}
			a:link {
				text-decoration: none;
			}
			a:visited {
				text-decoration: none;
			}
			a:hover {
				text-decoration: underline;
			}
			a:active {
				text-decoration: none;
			}
			-->
			</style></head>
			<body>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="center" valign="middle"><table width="620" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td height="6"></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/top.jpg" width="619" height="106"></td>
				  </tr>
				  <tr>
					<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_izq.jpg"><img src="'.$dominio.'imagen/mail/sombra_izq.jpg" width="10" height="220"></td>
						<td width="600" align="center" valign="top">
						
						<table width="100%" border="0" cellpadding="20" cellspacing="0">
							<tr>
							  <td align="left" valign="middle" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#666666;">
							  '.$cuerpo_msj.'
						  	  </td>
							</tr>
						  </table>
						<br></td>
						<td width="10" background="'.$dominio.'imagen/mail/sombra_der.jpg"><img src="'.$dominio.'imagen/mail/sombra_der.jpg" width="10" height="220"></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>
					<td><img src="'.$dominio.'imagen/mail/pie.jpg" width="619" height="55"></td>
				  </tr>
				</table></td>
			  </tr>
			</table>
			</body>
			</html>';
		
		return $mensaje;
	
	}

?>