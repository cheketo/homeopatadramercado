<?

	//INCLUDES
	include("0_include/0_mysql.php");
	include("0_include/0_creacion_mail.php");

	//VARIABLES
	$accion 	= $_POST['accion'];
	$enviado 	= $_GET['enviado'];

	$nombre 	= $_POST['nombre'];
	$telefono 	= $_POST['telefono'];
	$email 		= $_POST['email'];
	$mensaje 	= $_POST['mensaje'];

	if($accion == "enviar"){


		//INGRESO EL USUARIO A LA TABLA DE USERWEB SI NO ESTA YA REGISTRADO
		$query_check = "SELECT mail FROM user_web WHERE mail = '$email'";
		if(mysql_num_rows(mysql_query($query_check))==0){

				$query_insert_user = "INSERT INTO user_web (
					nombre
				  ,	mail
				) VALUES (
					  '$nombre'
					, '$email'
				)";
				$rs_insert_user = mysql_query($query_insert_user);
		}


		//DATOS DE ENVIO
		$destinatario 	= trim($rs_dato_sitio['cont_mail_destino']);
		$de 			= trim($rs_dato_sitio['cont_mail_desde']);
		$asunto 		= $rs_dato_sitio['cont_mail_asunto'];

		//estos datos se usaran como cabecera del email.
		$cabeceras = 'MIME-Version: 1.0'."\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		$cabeceras .= 'Reply-To: '.$email.' '."\r\n";
		$cabeceras .= 'From: '.$de.' <'.$email.'> '."\r\n";//es la cuenta de mail de donde sale

		$contenido = "
						<strong>Nombre y Apellido:</strong><br>
						$nombre<br><br>

						<strong>Tel�fono:</strong><br>
						$telefono<br><br>

						<strong>E-Mail:</strong><br>
						$email<br><br>

						<strong>Mensaje:</strong><br>
						$mensaje<br><br>
						";


		$contenido = str_replace("�","&aacute;", $contenido);
		$contenido = str_replace("�","&Aacute;", $contenido);
		$contenido = str_replace("�","&eacute;", $contenido);
		$contenido = str_replace("�","&Eacute;", $contenido);
		$contenido = str_replace("�","&iacute;", $contenido);
		$contenido = str_replace("�","&Iacute;", $contenido);
		$contenido = str_replace("�","&oacute;", $contenido);
		$contenido = str_replace("�","&Oacute;", $contenido);
		$contenido = str_replace("�","&uacute;", $contenido);
		$contenido = str_replace("�","&Uacute;", $contenido);


		$mensaje = mail_personalizado($dominioSite, "Contacto desde la Web", $contenido);
		$enviado = mail($destinatario, $asunto, $mensaje, $cabeceras);

		if($enviado == true){

			mail('adm@didstudio.com.ar', $asunto, $mensaje, $cabeceras);
			echo "<script>document.location.href= '".$_SERVER['PHP_SELF']."?enviado=ok';</script>";
		 }else{
			echo "<script>document.location.href= '".$_SERVER['PHP_SELF']."?enviado=no';</script>";
		 }
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include_once("0_include/0_head.php"); ?>
<script language="JavaScript" type="text/javascript">

	function validarEmail(valor)
		{
			document.getElementById("alertEmailNoSeleccionado").style.display = "none";
			document.getElementById("alertValidarEmailNoSeleccionado").style.display = "none";

			if (document.getElementById("email").value == "")
			{
			   document.getElementById("alertEmailNoSeleccionado").style.display = "block";
			}else{
				if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(valor)){
					return true;

				} else {
					document.getElementById("alertValidarEmailNoSeleccionado").style.display = "block";
					return false;
				}
			}
		};


	function validarNombre(valor){
		document.getElementById("alertValidarNombreNoSeleccionado").style.display = "none";
		document.getElementById("alertNombreNoSeleccionado").style.display = "none";

		if (valor == ""){
			document.getElementById("alertNombreNoSeleccionado").style.display = "block";
			return false;
		}else{
			if (valor.length < 3){
				document.getElementById("alertValidarNombreNoSeleccionado").style.display = "block";
				return false;
			}else{
				return true;
			}
		}

	};


	function validarMensaje(valor){
		document.getElementById("alertMensajeNoSeleccionado").style.display = "none";

		if (valor == ""){
			document.getElementById("alertMensajeNoSeleccionado").style.display = "block";
			return false;
		}else{
			return true;

		}

	};


function validarContacto(){
	datos_ok = true;


	if (validarNombre(document.form_contacto.nombre.value)==false) {
		datos_ok = false;
	}

	if (validarMensaje(document.form_contacto.mensaje.value)==false) {
		datos_ok = false;
	}

	if (validarEmail(document.form_contacto.email.value)==false) {
		datos_ok = false;
	}

	if(datos_ok == true){
		document.form_contacto.accion.value = "enviar";
		document.form_contacto.submit();
	}

};

</script>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 16px;
	font-weight: bold;
}
.Estilo2 {color: #006699}
-->
</style>
</head>

<body>
<div id="header"><? include_once("0_include/0_top.php"); ?></div>
<div id="cuerpo">
  <div id="contenido" align="center">


    <? if($enviado == "ok"){ ?>
    <br />
    <table width="732" border="0" cellspacing="0" cellpadding="7">
      <tr>
        <td align="center" bgcolor="#66CC66" class="Arial_11px_blanco">Gracias por comunicarse conmigo, a la brevedad me contactar&eacute; con usted.</td>
      </tr>
    </table>
    <br />
    <? } ?>
    <? if($enviado == "no"){ ?>
    <br />
   <div class=" divAlerts">Debido a problemas t&eacute;cnicos, no se ha enviado su mensaje. Por favor intente m&aacute;s tarde.</div>
    <br />
    <? } ?>

    <form id="form_contacto" name="form_contacto" method="post" action="">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="86" colspan="3" align="left" valign="bottom"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="56">
            <param name="movie" value="skin/index/swf/titulo_chico.swf" />
            <param name="quality" value="high" />
            <param name="FlashVars" value="titulo=Contacto" />
            <param name="wmode" value="transparent" />
        	<embed src="skin/index/swf/titulo.swf" FlashVars="titulo=Contacto" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="700" height="56"></embed>
          </object></td>
      </tr>
      <tr>
        <td colspan="3" valign="top">&nbsp;</td>
      </tr>
    </table>

    <table width="732" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="50%" align="center" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="200" align="left" class="Arial_11px_grisoscuro">Nombre: *</td>
                    <td width="26" rowspan="6"><input type="hidden" name="accion" id="accion" value="" /></td>
                    <td width="200" align="left" class="Arial_11px_grisoscuro">Mensaje: *</td>
                  </tr>
                  <tr>
                    <td width="200">
                      <input name="nombre" type="text" class="Arial_11px_grisoscuro" id="nombre" style="border:1px solid #CCCCCC; padding:1px; width:240px" tabindex="1" value="<?= $_POST['nombre'] ?>" onchange="validarNombre(this.value);" />
                      <div style="display:none;" id="alertNombreNoSeleccionado" class="divAlerts">Ingrese un nombre.</div>
                      <div style="display:none;" id="alertValidarNombreNoSeleccionado" class="divAlerts">Ingrese un nombre v&aacute;lido.</div>
                    </td>
                    <td width="200" rowspan="5" align="left" valign="top"><textarea tabindex="4" name="mensaje" id="mensaje" style="border:1px solid #CCCCCC; padding:1px; width:450px; height:96px;" class="Arial_11px_grisoscuro" onchange="validarMensaje(this.value);"><?= $_POST['mensaje'] ?></textarea>
                    <div style="display:none;" id="alertMensajeNoSeleccionado" class="divAlerts">Ingrese un mensaje.</div></td>
                  </tr>
                  <tr align="left" class="Arial_11px_grisoscuro">
                    <td width="200">Tel&eacute;fono: </td>
                  </tr>
                  <tr>
                    <td width="200"><input name="telefono" type="text" class="Arial_11px_grisoscuro" id="telefono" style="border:1px solid #CCCCCC; padding:1px; width:240px" tabindex="2" value="<?= $_POST['telefono'] ?>" /></td>
                  </tr>
                  <tr align="left" class="Arial_11px_grisoscuro">
                    <td width="200">E-mail: *</td>
                  </tr>
                  <tr>
                    <td width="200"><input name="email" type="text" class="Arial_11px_grisoscuro" id="email" style="border:1px solid #CCCCCC; padding:1px; width:240px" tabindex="3" value="<?= $_POST['email'] ?>" onchange="validarEmail(this.value);" />
                    <div style="display:none;" id="alertEmailNoSeleccionado" class="divAlerts">Ingrese un email.</div>
                    <div style="display:none;" id="alertValidarEmailNoSeleccionado" class="divAlerts">Ingrese un email v&aacute;lido.</div></td>
                  </tr>
                  <tr>
                    <td height="30">&nbsp;</td>
                    <td height="30">&nbsp;</td>
                    <td height="30" align="left" valign="middle"><a href="javascript:validarContacto()"><span class="readMore" style="background-color:#069fc8; margin-top:10px;"><?= $btn_enviar ?></span></a></td>
                  </tr>
              </table></td>
              </tr>
          </table></td>
      </tr>
    </table>
    </form>


    <div id="titulo-consultorios" style="width:100%;text-align:left; margin-top:20px; margin-bottom:15px; float:left;">
	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="56">
        <param name="movie" value="skin/index/swf/titulo_chico.swf" />
        <param name="quality" value="high" />
        <param name="FlashVars" value="titulo=Consultorios" />
        <param name="wmode" value="transparent" />
        <embed src="skin/index/swf/titulo.swf" FlashVars="titulo=Consultorios" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="700" height="56"></embed>
      </object>
    </div>
    <div style=" width:187px; float:left; padding-top:50px;"><img src="imagen/varios/consultorios.jpg" width="187" height="122" /></div>
    <div style=" width:582px; float:left; border-left:1px solid #c7c7c7; text-align:left;">

      <div id="titular-caballito" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#069dc5; margin-bottom:15px; padding-left:15px;  "><span style="font-size:12px;">en</span> Caballito /</div>

      	<div style="width:30%; float:left; padding-left:15px;">
        	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px;  color:#069dc5;">Direcci&oacute;n:</div>
            <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#5e5e5e;">
            	<p>Rosario 441. Piso 5, Depto "B". Ciudad Aut. de Buenos Aires. <br />Tel&eacute;fono: 4902-2947.</p>
           	</div>
      </div>
        <div style="width:30%; float:left; padding-left:15px;">
        	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px;  color:#069dc5;">Turnos en el:</div>
            <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#5e5e5e;">
            	4631-1833 / 4902-2947 de Lunes a Viernes ma&ntilde;ana y tarde.
			</div>
        </div>
        <div style="width:30%; float:left; padding-left:15px;">
        	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px;  color:#069dc5;">D&iacute;as de atenci&oacute;n:</div>
            <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#5e5e5e;">
            	&raquo; Martes de 16 a 19.30hs.<br />
              &raquo; Jueves de 9 a 13.30hs.
			</div>
        </div>


      <div style="clear:both; height:40px;"></div>

      <div id="titular-palermo" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#069dc5; margin-bottom:15px; padding-left:15px;  "><span style="font-size:12px;">en</span> Palermo /</div>

      	<div style="width:30%; float:left; padding-left:15px;">
        	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px;  color:#069dc5;">Direcci&oacute;n:</div>
            <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#5e5e5e;">
            	Av. Santa Fe 3778. Piso 12, Depto 1204. Ciudad Aut. de Buenos Aires.<br />Tel&eacute;fono: 4831-9240.
			</div>
        </div>
        <div style="width:30%; float:left; padding-left:15px;">
        	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px;  color:#069dc5;">Turnos en el:</div>
            <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#5e5e5e;">
            	4631-1833 / 4831-9240 de Lunes a Viernes de 15 a 19 hs. ma&ntilde;ana y tarde.
			</div>
        </div>
        <div style="width:30%; float:left; padding-left:15px;">
        	<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px;  color:#069dc5;">D&iacute;as de atenci&oacute;n:</div>
            <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#5e5e5e;">
            	&raquo; Mi&eacute;rcoles de 14 a 17.30hs.
			</div>
        </div>

    </div>


    </div>
  <div id="footer" style="margin-top:10px;">
	<? include("0_include/0_pie.php"); ?>
	</div>
</div>
<? include("0_include/0_googleanalytics.php"); ?>
</body>
</html>
