<? 
	include("0_include/0_mysql.php"); 
	include("0_include/0_creacion_mail.php"); 
	
	$accion = $_POST['accion'];
	$enviado = $_GET['enviado'];
	$idcarpeta = $_GET['idcarpeta'];
	$idseccion = $_GET['idseccion'];
	$idproducto = $_GET['idproducto'];
	
	if($idseccion && $idcarpeta){
		$link_consulta = "http://www.travel-54.com.ar/seccion_detalle.php?idseccion=".$idseccion."&idcarpeta=".$idcarpeta;
	}
	
	$nombre = addslashes($_POST['nombre']);
	$telefono = addslashes($_POST['telefono']);	
	$email = addslashes($_POST['email']);
	$idpais = $_POST['idpais'];
	$num_personas = $_POST['num_personas'];
	
	$dia_checkin = $_POST['dia_checkin'];
	$mes_checkin = $_POST['mes_checkin'];
	$ano_checkin = $_POST['ano_checkin'];
	$checkin = $dia_checkin."/".$mes_checkin."/".$ano_checkin;
	
	$dia_checkout = $_POST['dia_checkout'];
	$mes_checkout = $_POST['mes_checkout'];
	$ano_checkout = $_POST['ano_checkout'];
	$checkout = $dia_checkout."/".$mes_checkout."/".$ano_checkout;
	
	$mensaje = str_replace(chr(13), "<br>", addslashes($_POST['mensaje']));
	
	//OBTENGO EL NOMBRE DEL PAIS
	$query_pais = "SELECT titulo 
				  FROM pais 
				  WHERE idpais = '$idpais' ";
	$rs_pais = mysql_fetch_assoc(mysql_query($query_pais));
	$nombrePais = $rs_pais['titulo'];
				  
				  
	//ARMO LA URL DESDE DONDO SE ENVIA EL MAIL
	$url_split = split("\|",$_GET['url']);
	for($i=0;$i<count($url_split);$i++){
		$url_b .= $url_split[$i]."&";
	}

	$query_idsede = "SELECT idpais
	FROM sede
	WHERE idsede = '$_SESSION[idsede_session]'";
	$rs_idsede = mysql_fetch_assoc(mysql_query($query_idsede));
	
	$idpais_predeterminado = $rs_idsede['idpais'];//Argentina:54, España:34, Colombia:57 , Uruguay:598
	
	//INGRESAR
	if($accion == "enviar"){
	
		//se checkea si hay un usuario con el mismo mail
		$query_usuario = "SELECT iduser_web FROM user_web WHERE mail = '$email' ";
		$rs_usuario = mysql_fetch_assoc(mysql_query($query_usuario));
		$num_rows_usuario = mysql_num_rows(mysql_query($query_usuario));
		
		if($num_rows_usuario == 0){		
			
			$fecha_alta = date("Y-m-d");
			
			$query_ingresar = "INSERT INTO user_web (
			  nombre
			, mail
			, telefono
			, idpais
			, ididioma
			, idsede
			, fecha_alta
			, iduser_web_perfil
			, estado
			) VALUES (
			  '$nombre'
			, '$email'
			, '$telefono'
			, '$idpais'
			, '$_SESSION[ididioma_session]'
			, '$_SESSION[idsede_session]'
			, '$fecha_alta'
			, '1'
			, '2'
			)";
			mysql_query($query_ingresar);
						
			/*$query_max = "SELECT MAX(iduser_web) AS id FROM user_web LIMIT 1";
			$rs_max = mysql_fetch_assoc(mysql_query($query_max));
			$iduser_web_nuevo = $rs_max['id'];			

			$query_user_segmentaciones = "INSERT INTO user_web_segmentacion (iduser_web, iduser_segmentacion) VALUES ('$iduser_web_nuevo','$user_segmentacion_actual') ";
			mysql_query($query_user_segmentaciones);*/
		
		}else{
			/*$query_user_segmentaciones = "INSERT INTO user_web_segmentacion (iduser_web, iduser_segmentacion) VALUES ('$rs_usuario[iduser_web]','$user_segmentacion_actual') ";
			mysql_query($query_user_segmentaciones);*/
		}				
		
		//URL DE DONDE SE ENVIA EL MAIL
		if(!$link_consulta){
			$url = split("url=",$_SERVER['REQUEST_URI']); 
			$link_consulta = "http://".str_replace("|","&",$url[1]);
		}
		
		//ENVIO DEL MAIL 
		//los datos de rs_dato_sitio estan estan en 0_mysql.php
		require_once("fzo.mail.php");
		$mail = new SMTP("localhost","","");
		
		//DATOS DE ENVIO
		$destinatario = trim($rs_dato_sitio['cont_mail_destino']);
		$de = trim($rs_dato_sitio['cont_mail_desde']);
		$asunto = $rs_dato_sitio['cont_mail_asunto'];
		
		//estos datos se usaran como cabecera del email.
		$cabeceras="MIME-Version: 1.0\r\n";
		$cabeceras.= "Content-type: text/html; charset=iso-8859-1\r\n";
		$cabeceras.="From: ".$de."\r\n";
		$cabeceras.="Reply-To: ".$_POST['email']."\r\n";
		$cabeceras.="Subject: ".$asunto."\r\n";
		
		$cuerpoMail = "
						<strong>Nombre y Apellido:</strong><br> 
						$nombre<br><br>
						
						<strong>Teléfono:</strong><br> 
						$telefono<br><br>
						
						<strong>E-Mail:</strong><br>
						$email<br><br>
						
						<strong>Pais:</strong><br> 
						$nombrePais<br><br>
						
						<strong>Cantidad de Personas:</strong><br> 
						$num_personas<br><br>	
												
						<strong>Check In:</strong><br> 
						$checkin<br><br>
						
						<strong>Check Out:</strong><br> 
						$checkout<br><br>
													
						<strong>Mensaje:</strong><br>
						$mensaje<br><br>
						
						________________________________________<br>
						Link desde donde se realizo la consulta: $link_consulta
						";
	
		$mensajeMail = mail_personalizado("http://".$_SERVER['SERVER_NAME']."/","Consulta para Travel54",$cuerpoMail);
			 
		$error = $mail->smtp_send($de, $destinatario, $cabeceras, $mensajeMail);  
			if($error == "0"){
				$error = $mail->smtp_send($de, $rs_dato_sitio['cont_prod_mail_ideas2'], $cabeceras, $mensajeMail);
				echo "<script>document.location.href= '".$_SERVER['PHP_SELF']."?enviado=ok';</script>";
			 }else{
				echo "<script>document.location.href= '".$_SERVER['PHP_SELF']."?enviado=no';</script>";
			 } 
		
	}// fin accion enviar
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="skin/index/css/0_fonts_tiny.css" tppabs="css/0_fonts_tiny.css" type="text/css">
<link rel="stylesheet" href="skin/index/css/0_fonts.css" tppabs="css/0_fonts.css" type="text/css">
<link rel="stylesheet" href="skin/index/css/0_especial.css" tppabs="css/0_fonts.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->
</style>

<script language="JavaScript" type="text/javascript">

function validarContacto(){
	datos_ok = true;
	var mensaje_error;
	
	if (document.form_contacto.nombre.value == "" || document.form_contacto.nombre.value.length < 3) {
		mensaje_error = "<?= $alert_01 ?>\n";
		datos_ok = false;
	} 
	
	if (document.form_contacto.email.value == "" || document.form_contacto.email.value.length < 3) {
		mensaje_error = mensaje_error + "<?= $alert_02 ?>\n";
		datos_ok = false;
	} else {
		if (document.form_contacto.email.value.indexOf("@") == (-1)) {
			mensaje_error = mensaje_error + "<?= $alert_03 ?>\n";
			datos_ok = false;
		} 
		if (document.form_contacto.email.value.indexOf(".") == (-1)) {
			mensaje_error = mensaje_error + "<?= $alert_04 ?>\n";
			datos_ok = false;
		} 
	}
	
	if (document.form_contacto.idpais.value == 0) {
		mensaje_error = mensaje_error + "<?= $alert_05 ?>\n";
		datos_ok = false;
	} 
	
	if (document.form_contacto.num_personas.value == "") {
		mensaje_error = mensaje_error + "<?= $alert_06 ?>\n";
		datos_ok = false;
	}
	
	if (document.form_contacto.dia_checkin.value == "00" || document.form_contacto.dia_checkout.value == "00" || document.form_contacto.mes_checkin.value == "00" || document.form_contacto.mes_checkout.value == "00" || document.form_contacto.ano_checkin.value == "0000" || document.form_contacto.ano_checkout.value == "0000") {
		mensaje_error = mensaje_error + "<?= $alert_07 ?>\n";
		datos_ok = false;
	}
	
	if (document.form_contacto.mensaje.value == "" || document.form_contacto.mensaje.value.length < 3) {
		mensaje_error = mensaje_error + "<?= $alert_08 ?>\n";
		datos_ok = false;
	} 	
	
	if(datos_ok == true){
		document.form_contacto.accion.value = "enviar";
		document.form_contacto.submit(); 
	}else{
		alert(mensaje_error);
	};
};

</script>
</head>
<body>
<form  method="post" name="form_contacto" class="detalle_negro" id="form_contacto" style="margin:0px;">
  <table width="600" height="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2" valign="top" class="detalle_a"><input name="accion" type="hidden" id="accion" value="1" />
          <table width="100%" border="0" cellspacing="0" cellpadding="8">
            <? if($enviado == 'ok' && $error == ''){?>
            <tr>
              <td width="25%" align="center" valign="top"><p><img src="imagen/iconos/registro_ok.jpg" width="101" height="110" /></p>
              </td>
              <td width="75%" align="center" valign="middle"><p class="Arial_12px_grisoscuro"><?= $msj_Enviado ?></p>
              </td>
            </tr>
            <? }?>
			<? if($enviado != 'ok' && $error == ''){?>
            <tr>
              <td align="center" valign="top"><p><img src="imagen/iconos/comments__.png" width="90" height="90" /></p></td>
              <td align="left" valign="middle" class="Arial_12px_grisoscuro"><span class="tituloCarpeta">
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="405" height="24">
                  <param name="movie" value="skin/index/swf/titulo.swf" />
                  <param name="quality" value="high" />
                  <param name="FlashVars" value="titulo=<?= $lbl_FormContactoReserva ?>" />
                  <param name="wmode" value="transparent" />
                  <embed src="skin/index/swf/titulo.swf" FlashVars="titulo=<?= $lbl_FormContactoReserva ?>" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="405" height="24"></embed>
                </object>
              </span><br />
                <br />
              <?= $msg_Reserva ?></td>
            </tr>
            <? } ?>
            <? if($error != ''){?>
            <tr>
              <td align="center" valign="top"><img src="imagen/iconos/registro_error.jpg" width="101" height="110" /></td>
              <td align="center" valign="middle" class="Arial_12px_grisoscuro"><?= $error ?></td>
            </tr>
            <? } ?>
        </table></td>
    </tr>
    <? if($enviado != 'ok'){?>
    <tr>
      <td width="50%" valign="top" >
	  <?
	  	//$url = split("url=",$_SERVER['REQUEST_URI']);
        //echo $link_consulta = "http://".str_replace("|","&",$url[1]); 
	  ?>
  <table width="100%" border="0" cellpadding="0" cellspacing="4" >
    <tr >
      <td colspan="2" align="left" class="Arial_11px_grisoscuro"><?= $lbl_NomApe ?> (*)</td>
          </tr>
    <tr>
      <td height="20" colspan="2" align="left" class="ejemplo_12px"><input name="nombre" type="text" id="nombre" value="<?= $nombre ?>" style="border:1px solid #CCCCCC; padding:1px; width:90%;" class="Arial_11px_grisoscuro" /></td>
          </tr>
    <tr >
      <td colspan="2" align="left" class="Arial_11px_grisoscuro"><?= $lbl_Tel ?> (*)</td>
          </tr>
    <tr>
      <td height="20" colspan="2" align="left" class="ejemplo_12px"><input name="telefono" type="text"  id="telefono" value="<?= $telefono ?>" style="border:1px solid #CCCCCC; padding:1px; width:90%;" class="Arial_11px_grisoscuro" /></td>
          </tr>
    <tr >
      <td colspan="2" align="left" class="Arial_11px_grisoscuro" >E-Mail (*)</td>
          </tr>
    <tr>
      <td height="10" colspan="2" align="left" class="ejemplo_12px"><input name="email" type="text"  id="email" value="<?= $email ?>" style="border:1px solid #CCCCCC; padding:1px; width:90%;" class="Arial_11px_grisoscuro" /></td>
          </tr>
    <tr>
      <td colspan="2" align="left" class="Arial_11px_grisoscuro"><?= $lbl_Nacionalidad ?> (*)</td>
          </tr>
    <tr>
      <td colspan="2" align="left" class="detalle_a"><select name="idpais" id="idpais" style="border:1px solid #CCCCCC; padding:1px; width:92%;" class="Arial_11px_grisoscuro" >
        <option value="0" >- <?= $lbl_Seleccionar ?></option>
        	<?
				  $query_pais = "SELECT * 
				  FROM pais 
				  WHERE estado = 1 ORDER BY titulo ASC";
				  $result_pais = mysql_query($query_pais);
				  while($rs_pais = mysql_fetch_assoc($result_pais)){						  
					
			?>
        <option <?= $idpais_sel ?> value="<?= $rs_pais['idpais'] ?>"><?= $rs_pais['titulo'] ?></option>
        	<?  } ?>
        </select></td>
          </tr>

    <tr>
      <td colspan="2" align="left" class="Arial_11px_grisoscuro"><?= $lbl_NumPeople ?>: (*)</td>
          </tr>
    <tr>
      <td colspan="2" align="left" class="detalle_a"><span class="ejemplo_12px">
        <input name="num_personas" type="text"  id="num_personas" style="border:1px solid #CCCCCC; padding:1px; width:90%;" class="Arial_11px_grisoscuro" />
      </span></td>
          </tr>
   
    <tr>
      <td height="-1" align="left" class="Arial_11px_grisoscuro">Check in:  (*)</td>
          <td align="left" class="Arial_11px_grisoscuro">Check out: (*)</td>
    </tr>
    <tr>
      <td width="50%" align="left" class="ejemplo_12px"><span class="style2">
        <select name="dia_checkin" size="1" class="Arial_11px_grisoscuro" id="dia_checkin">
          <option value='00' ></option>
          <?												
						for ($i=1;$i<32;$i++){
							if ($fecha_alta[2] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
        </select><select name="mes_checkin" size="1" class="Arial_11px_grisoscuro" id="mes_checkin">
          <option value='00' ></option>
          <?						
                        for ($i=1;$i<13;$i++){
							if ($fecha_alta[1] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
        </select><select name="ano_checkin" size="1" class="Arial_11px_grisoscuro" id="ano_checkin">
          <option value='0000' ></option>
          <?	
						$anioActual = date("Y");
                        for ($i=$anioActual+2;$i>($anioActual-1);$i--){
							if (date("Y") == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
        </select>
      </span></td>
      <td align="left" class="ejemplo_12px"><span class="style2">
        <select name="dia_checkout" size="1" class="Arial_11px_grisoscuro" id="dia_checkout">
          <option value='00' ></option>
          <?												
						for ($i=1;$i<32;$i++){
							if ($fecha_alta[2] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
        </select><select name="mes_checkout" size="1" class="Arial_11px_grisoscuro" id="mes_checkout">
          <option value='00' ></option>
          <?						
                        for ($i=1;$i<13;$i++){
							if ($fecha_alta[1] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
        </select><select name="ano_checkout" size="1" class="Arial_11px_grisoscuro" id="ano_checkout">
          <option value='0000' ></option>
          <?	
						$anioActual = date("Y");
                        for ($i=$anioActual+2;$i>($anioActual-1);$i--){
							if (date("Y") == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
        </select>
      </span></td>
    </tr>
  </table>
  
  	</td>
      <td width="50%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="4" >
          <tr class="detalle_negro">
            <td align="left" class="Arial_11px_grisoscuro"><?= $lbl_Mensaje ?></td>
          </tr>
          <tr>
            <td height="10" align="left" class="ejemplo_12px"><textarea name="mensaje" style="border:1px solid #CCCCCC; padding:1px; width:300px; height:200px;" class="Arial_11px_grisoscuro" id="mensaje"><?= $mensaje ?></textarea></td>
          </tr>
          <tr>
            <td align="left" class="ejemplo_12px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="21%" align="left"><input name="Submit222" type="button" class="Arial_11px_grisoscuro"  onclick="javascript:validarContacto()" value="  <?= $btn_enviar2 ?>  "/></td>
                  <td width="79%" align="left"><input name="Submit22" type="reset" class="Arial_11px_grisoscuro" value="  <?= $btn_borrar ?>  " /></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
    <? } ?>
  </table>
</form>
<? include("0_include/0_googleanalytics.php"); ?>
</body>
</html>