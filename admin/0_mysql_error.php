<?
	$error = $_GET['error'];
	
	switch($error){
		case 1://no conecta usuario
			$error_color = "#FF9900";
			break;
		case 2://no conecta a base de datos
			$error_color = "#FF0000";
			break;
	}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.style2 {font-size: 14px}
.style3 {font-size: 18px; font-family: Arial, Helvetica, sans-serif;}
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
        <td><img src="../imagen/mail/top.jpg" width="619" height="106"></td>
      </tr>
      <tr>
        <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="10" background="../imagen/mail/sombra_izq.jpg">&nbsp;</td>
            <td width="600" align="center" valign="top" bgcolor="#FFFFFF"><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="570" align="center" valign="bottom" class="style3"><p>&nbsp;</p>
                    <p>En este momento estamos implementando mejoras y realizando tareas de mantenimiento en el sitio.
                      
                      Le pedimos disculpas por los inconvenientes  y le rogamos nos visite nuevamente mas tarde.
                      
                      Gracias
                      &nbsp;<br>
                    </p></td>
              </tr>
              <tr>
                <td align="center" valign="bottom" class="style3">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="bottom" class="style3"><a href="index.php">Intentar nuevamente </a></td>
              </tr>
              <tr>
                <td align="center" valign="bottom" class="style3"><table width="100%" border="0" cellpadding="0" cellspacing="10">
                    <tr>
                      <td bgcolor="<?= $error_color;?>">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="10" background="../imagen/mail/sombra_der.jpg">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><img src="../imagen/mail/pie.jpg" width="619" height="55"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>