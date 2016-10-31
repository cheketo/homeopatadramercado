<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?	
	///////////////////////////////////////////////////
	///// Comienzo Modulo Admin de incorporacion  /////
	///////////////////////////////////////////////////	

	//INGRESAR	
	
	
		$accion = $_POST['accion'];
		$idpais_zona = $_POST['idpais_zona'];
		$idpais = $_POST['idpais'];
		$idpais_provincia = $_POST['idpais_provincia'];
		$zona = $_POST['zona'];
		$posicion = $_POST['posicion'];
			
		if( $accion == "ingresar" ){
			
			
			$query_ingresar = "INSERT INTO pais_zona (
			  idpais	
			, idpais_provincia
			, titulo
			, posicion
			, estado
			) VALUES (
			  '$idpais'
			, '$idpais_provincia'
			, '$zona'
			, '0'
			, '1'
			)";
			mysql_query($query_ingresar);
			
			echo "<script>window.location.href=('pa_zona_ver.php')</script>";
		
		};
			echo $seccion_titulo;    

	//BORRAR	
		if( $eliminar != '' && $eliminar != 0 ){
		
			$query_eliminar = "DELETE FROM pais_zona WHERE idpais_zona = '$eliminar'";
			mysql_query($query_eliminar);
			
			echo "<script>window.location.href=('pa_zona_ver.php')</script>";
		
		};
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function validar_form_preguntas(){
formulario = document.form_titular;

	if(formulario.idpais.value == ''){
		alert("Debe selecciona un pa&iacute;s");
	}else if(formulario.idpais_provincia.value == ''){
		alert("Debe ingresar el nombre de una provincia");
	}else if(formulario.zona.value == ''){
		alert("Debe ingresar el nombre de una zona");
	}else{
		formulario.accion.value = "ingresar";
		formulario.submit();
	}

};


function confirm_eliminar(url, variables, id){
	if (confirm('&iquest; Esta seguro que desea eliminar el registro ?')){
		if (variables == ''){
			window.location.href=(url+'?eliminar='+id);
		}else{
			window.location.href=(url+'?'+variables+'&eliminar='+id);
		};
	}
}

</script>
                      
</head>
<body>
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Zonas</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
					<form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingresar nueva zona:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td bgcolor="#fff0e1">
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Pa&iacute;s: </td>
                                  <td align="left" valign="top"><span class="style2">
                                    <select name="idpais" class="detalle_medio" id="idpais" style="width:200px;" onchange="javascript:document.form_titular.submit();">
                                      <option value="" >- Seleccionar Pa&iacute;s</option>
                                      <?
		 
	  $query_idproducto = "SELECT *
	  FROM pais
	  WHERE estado <> 3 
	  ORDER BY titulo";
	  $result_idproducto = mysql_query($query_idproducto);
	  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
	  {
	  	if ($idpais == $rs_idproducto['idpais'])
		{
			$sel_idproducto = "selected";
		}else{
			$sel_idproducto = "";
		}
?>
                                      <option value="<?= $rs_idproducto['idpais'] ?>" <? echo $sel_idproducto ?>>
                                      <?= $rs_idproducto['titulo'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></td>
                                </tr>
                                <tr>
                                  <td width="15%" align="right" valign="middle" class="detalle_medio">Provincia:</td>
                                  <td width="85%" align="left" valign="top"><label><span class="style2">
                                    <select name="idpais_provincia" class="detalle_medio" style="width:200px;" id="idpais_provincia">
                                      <option value="" >- Seleccionar Provincia</option>
                                      <?
		 
	  $query_idproducto = "SELECT *
	  FROM pais_provincia
	  WHERE estado <> 3 AND idpais = $idpais
	  ORDER BY titulo";
	  $result_idproducto = mysql_query($query_idproducto);
	  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
	  {
	  	if ($idpais_provincia == $rs_idproducto['idpais_provincia'])
		{
			$sel_idproducto = "selected";
		}else{
			$sel_idproducto = "";
		}
?>
                                      <option value="<?= $rs_idproducto['idpais_provincia'] ?>" <? echo $sel_idproducto ?>>
                                      <?= $rs_idproducto['titulo'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle" class="detalle_medio">Zona:</td>
                                  <td align="left" valign="top"><label>
                                    <input name="zona" type="text" class="detalle_medio" style="width:195px;" id="zona" size="35" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                  <td align="left" valign="top"><input name="Button" type="button" class="buttons detalle_medio_bold" onclick="validar_form_preguntas();" value="   Ingresar   " /></td>
                                </tr>
                            </table></td>
                        </tr>
                      </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#FFB76F">
                          <td width="4%" height="40" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">ID</td>
                          <td width="15%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Pa&iacute;s</td>
                          <td width="18%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Provincia</td>
                          <td width="50%" height="40" bgcolor="#FFDDBC" class="detalle_medio_bold">Zona</td>
                          <td height="40" colspan="4" align="center" bgcolor="#FFDDBC" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
                        <? 
	
	if(isset($_GET['estado'])){
		$estado = $_GET['estado'];
		$idpreguntas_frecuentes = $_GET['id'];
		$url_ant = substr($REQUEST_URI, 0, strrpos($REQUEST_URI, "&"));
		$url_ant = substr($url_ant, 0, strrpos($url_ant, "&"))."#".$idpreguntas_frecuentes;
		
		$query_estado = "UPDATE pais_zona SET estado = '$estado'
		WHERE idpais_zona = $idpais_zona
		LIMIT 1 ";
		mysql_query($query_estado);
		
		echo "<script>window.location.href=('$url_ant')</script>";
	};
				
/*$colores = array("#fff0e1","#FFE1C4");
$cont_colores = 0;
$hay_lista = false;
$query_lista = "SELECT *
FROM pais_zona
WHERE estado <> 3";
$result_lista = mysql_query($query_lista);
while ($rs_lista = mysql_fetch_assoc($result_lista))
	{ $hay_lista = true;*/
?>
                        <? 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM pais_zona
		WHERE estado <> 3 ";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista))
			{ $hay_lista = true;
			  $id_pais = $rs_lista['idpais']; 
			  $id_provincia = $rs_lista['idpais_provincia']; 
	  
?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="4%" align="center" class="detalle_chico"><span > <a name="<?= $rs_lista['idpais_zona']; ?>" id="<?= $rs_lista['idpais_zona']; ?>"></a>
                                <?=$rs_lista['idpais_zona']?>
                          </span></td>
                          <?
  						$query_pais = "SELECT *
						FROM pais
    					WHERE idpais = '$id_pais'";
						$rs_pais = mysql_fetch_assoc(mysql_query($query_pais)); ?>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_pais['titulo']; ?></td>
                          <?
				  		$query_provincia = "SELECT *
						FROM pais_provincia
				    	WHERE idpais_provincia = '$id_provincia'";
						$rs_provincia = mysql_fetch_assoc(mysql_query($query_provincia)); ?>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_provincia['titulo']; ?></td>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_lista['titulo']; ?></td>
                          <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                              <a href="?estado=2&amp;idpais_zona=<?= $rs_lista['idpais_zona'] ?>"><img src="../../imagen/iconos/accept_blue.png" alt="Habilitado" width="16" height="16" border="0" /></a>
                              <? } else { ?>
                              <a href="?estado=1&amp;idpais_zona=<?= $rs_lista['idpais_zona'] ?>"><img src="../../imagen/iconos/accept_blue_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                              <? } ?>                          </td>
                          <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="pa_zona_editar.php?id=<?= $rs_lista['idpais_zona'] ?>" target="_parent" class="style10"><img src="../../imagen/iconos/pencil.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                          <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:confirm_eliminar('<?= $PHP_SELF ?>','<?= $QUERY_STRING ?>','<?= $rs_lista['idpais_zona'] ?>')" class="style10"><img src="../../imagen/iconos/cross.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                        </tr>
                        <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                        <tr align="center" valign="middle" >
                          <td colspan="8" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado provincias.</td>
                        </tr>
                        <? };
	?>
                        <? // };
	?>
                        <?
	////////////////////////////////////////////////
	////Final del Modulo Admin de lista filtrado////
	////////////////////////////////////////////////
?>
                      </table>
                      
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