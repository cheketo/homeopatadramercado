<? 
	include("0_include/0_mysql.php");
	include("0_include/0_creacion_mail.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?

	//VARIABLES
	$accion = $_POST['accion'];
	
	if($_POST['mail']){
		$mail = $_POST['mail'];
	}else if($_GET['mail']){
		$mail = $_GET['mail'];
	}
	
	//BAJA
	if($accion == "baja" || $mail != ""){
		
		$fecha = date("Y-m-d");
		
		$query = "UPDATE user_web SET estado = 4,
		fecha_baja = '$fecha'
		WHERE mail = '$mail'";
		$result = mysql_query($query);
		$cant = mysql_affected_rows();
		
		if($cant > 0){
			$msj = "Usted se ha dado de baja.";
		}else{
			$msj = "No se ha podido dar de baja. Intente nuevamente más tarde.";
		}
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
	

	
	function validar_baja(){
		var f = document.frm;
		var flag = true;
		var error = '';
			
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
		
		//FINAL
		if (flag == true) {
			document.frm.accion.value = "baja";
			document.frm.submit(); 
		}else{
			alert(error);
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
    <form action="" method="post" name="frm" id="frm">
      <br />
      <table width="68%" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td align="left" class="titulo_a_chico"><input name="accion" type="hidden" id="accion" /></td>
          <td align="left"><span class="titulo_a_chico">&iquest;Desea darse de baja? </span></td>
        </tr>
        <tr>
          <td align="left" class="titulo_a_chico">&nbsp;</td>
          <td align="left"><span class="detalle_a">Ingrese su direcci&oacute;n de e-mail y su contrase&ntilde;a para darse de baja. </span></td>
        </tr>
        <tr>
          <td width="23%" align="left" class="titulo_b_chico">E-mail:</td>
          <td width="77%" align="left" valign="middle" class="ejemplo_negro_12px"><input name="mail" type="text" class="registro_B" id="mail" size="45" style="width:98%" />
          </td>
        </tr>
        <tr>
          <td align="left" class="ejemplo_negro_12px">&nbsp;</td>
          <td align="left" valign="middle" class="ejemplo_negro_12px"><a href="javascript:validar_baja();">
            <? if($msj){ ?>
</a>
            <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#00FFFF">
              <tr>
                <td class="registro_B"><?= $msj ?></td>
              </tr>
            </table>
            <? } ?>
            <a href="javascript:validar_baja();"><br />
          <img src="imagen/botones/enviar.jpg" name="b_enviar" width="107" height="29" border="0" id="b_enviar" /></a></td>
        </tr>
      </table> 
      <br />
    </form>
  </div>
	<div id="barra_der_imagen"></div>
</div>
<div id="footer">
<? include("0_include/0_pie.php"); ?>
</div>
</body>
</html>