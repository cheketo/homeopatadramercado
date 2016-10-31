<? include ("../../0_mysql.php"); ?>
<html>
<head>
<link rel="stylesheet" href="../../css/0_fonts.css" tppabs="../../global.css" type="text/css">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><span class="titulo_grande_bold">Listado de Precios: </span>
      <?	
	///////////////////////////////////////////////////
	///// Comienzo Modulo Admin de incorporacion  /////
	///////////////////////////////////////////////////	
    
	// localizacion de variables get y post:
	$query_imprimir = $_POST['query'];

	$iva_titulo = array();
	
	$query_iva = "SELECT * FROM ca_iva WHERE estado = '1' ";
	$result_iva = mysql_query($query_iva);
	
	while($rs_iva = mysql_fetch_assoc($result_iva)){
		$id_iva = $rs_iva['idca_iva'];
		$iva_titulo[$id_iva] = $rs_iva['titulo_iva'] ;
	};
	
?>
      <input type="button" name="Button" value="Imprimir" onClick="window.print();">
      <br>
      <hr size="1" class="detalle_medio">
      <br>
      <table width="100%" border="1" align="center" cellpadding="5" cellspacing="1" bordercolor="#FFFFFF" >
        <tr bgcolor="#ffddbc">
          <td width="4%" height="40" align="center" bgcolor="ffddbc" class="detalle_medio_bold" style="border: 1px solid #FFFFFF; ">ID</td>
          <td width="70%" height="40" bgcolor="ffddbc" class="detalle_medio_bold" style="border: 1px solid #FFFFFF; "> Titulo</td>
          <td height="40" align="left" bgcolor="ffddbc" class="detalle_medio_bold" style="border: 1px solid #FFFFFF; ">Precio</td>
          <td height="40" align="center" class="detalle_medio_bold" style="border: 1px solid #FFFFFF; ">IVA</td>
        </tr>
        <? 

	$colores = array("#fff0e1","#FFE1C4");
	$cont_colores = 0;
	$hay_lista = false;
	
	$result_lista = mysql_query($query_imprimir);
	while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
		$hay_lista = true;
	
	?>
        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
          <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio_bold" style="border: 1px solid #FFFFFF; "><?=$rs_lista['idproducto']?>          </td>
          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio" style="border: 1px solid #FFFFFF; "><?=$rs_lista['titulo']?></td>
          <td width="15%" align="left" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio_bold" style="border: 1px solid #FFFFFF; "><table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr class="detalle_medio_bold">
                <td width="99%"><?= "$ ".$rs_lista['precio']?></td>
              </tr>
          </table></td>
          <td width="11%" align="center" style="border: 1px solid #FFFFFF; "><?= $iva_titulo[$rs_lista['idca_iva']] ?></td>
        </tr>
        <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
        <tr align="center" valign="middle">
          <td  height="50" colspan="6" bgcolor="#fff0e1" class="detalle_medio_bold" style="border: 1px solid #FFFFFF; ">No se han encontrado precios.</td>
        </tr>
        <? };
	?>
        <?
	////////////////////////////////////////////////
	////Final del Modulo Admin de lista filtrado////
	////////////////////////////////////////////////
?>
      </table></td>
  </tr>
</table>
</body>
</html>