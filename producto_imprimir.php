<? 	

	include("0_include/0_mysql.php");

	$idproducto = $_GET['idproducto'];

	$query_prod = "SELECT 
	  A.idproducto
	, A.foto
	, A.idproducto_marca
	, A.precio
	, A.idca_iva
	, B.titulo
	, B.copete
	, B.detalle
	FROM producto A
	LEFT JOIN producto_idioma_dato B ON B.idproducto = A.idproducto
	WHERE A.idproducto = '$idproducto' AND B.ididioma = '$_SESSION[ididioma_session]'";
	$rs_producto = mysql_fetch_assoc(mysql_query($query_prod));
	
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
    <td style="font-family:Georgia, 'Times New Roman', Times, serif; font-size:22px; "><?= $rs_producto['titulo'] ?></td>
  </tr>
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333333; "><div align="justify"><? if($rs_producto['foto']){ ?><img src="imagen/producto/mediana/<?= $rs_producto['foto'] ?>" border="0" align="left"  style=" margin-right:10px;"  /><? } ?><?= $rs_producto['copete'] ?></div></td>
  </tr>
  <? if($rs_producto['detalle']){ ?>
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666; "><div align="justify"><?= html_entity_decode($rs_producto['detalle'], ENT_QUOTES); ?></div></td>
  </tr>
  <? } ?>
</table>
</body>
</html>
