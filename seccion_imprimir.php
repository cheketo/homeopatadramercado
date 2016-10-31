<? 	

	include("0_include/0_mysql.php");

	$idseccion = $_GET['idseccion'];

	$query_seccion = "SELECT 
	  A.idseccion
	, A.foto
	, B.titulo
	, B.copete
	, B.detalle
	FROM seccion A
	LEFT JOIN seccion_idioma_dato B ON B.idseccion = A.idseccion
	WHERE A.idseccion = '$idseccion' AND B.ididioma = '$_SESSION[ididioma_session]' ";
	$rs_seccion = mysql_fetch_assoc(mysql_query($query_seccion));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Version Imprimible.</title>
</head>

<body>
<table width="600" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:22px; "><span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666; ">
      <input type="button" name="Imprimir" value=" Imprimir " onclick="self.print()" />
    </span></td>
  </tr>
  <tr>
    <td style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:22px; "><?= $rs_seccion['titulo'] ?></td>
  </tr>
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333333; "><div align="justify"><? if($rs_seccion['foto']){ ?><img src="imagen/seccion/mediana/<?= $rs_seccion['foto'] ?>" border="0" align="left" style=" margin-right:10px;" /><? } ?><?= $rs_seccion['copete'] ?></div></td>
  </tr>
  <? if($rs_seccion['detalle']){ ?>
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666; "><div align="justify"><?= html_entity_decode($rs_seccion['detalle'], ENT_QUOTES); ?></div></td>
  </tr>
  <? } ?>
</table>
</body>
</html>
