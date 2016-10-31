<? 

	include ("../../0_mysql.php");
	$idpais_provincia = $_GET['id'];

	//ACTUALIZAR	
	$accion = $_POST['accion'];
	
			
	if( $accion == "actualizar" ){
		$idpais = $_POST['pais'];
		$provincia = $_POST['provincia'];
		$posicion = $_POST['posicion'];


		$query_modficacion = "UPDATE pais_provincia SET
		  idpais='$idpais'
		, titulo='$provincia'
		, posicion='$posicion'
		WHERE idpais_provincia = '$idpais_provincia'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		echo "<script>window.location.href=('$PHP_SELF"."?id=$idpais_provincia');</script>";

	}
		
	//datos de la provincia actual
	$query_provincia = "SELECT * FROM pais_provincia WHERE idpais_provincia = '$idpais_provincia' ";
	$rs_provincia = mysql_fetch_assoc(mysql_query($query_provincia));
		
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>


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
                <td height="40" valign="bottom" class="titulo_grande_bold">Provincia - Editar </td>
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
                            <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Editar provincia:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                          </tr>
                          <tr>
                            <td bgcolor="#eafcf7"><script language="JavaScript" type="text/javascript">

function validar_form_preguntas(){
formulario = document.form_titular;

	if(formulario.pais.value == 0){
		alert("Debe elegir el pais al que pertenece");
	}else if(formulario.provincia.value == ''){
		alert("Debe ingresar el nombre de la provincia");
	}else{
		formulario.accion.value = "actualizar";
		formulario.submit();
	}

};
                </script>
                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">ID:</td>
                                    <td align="left" valign="top"><input name="id" type="text" disabled="disabled" class="detalle_medio" id="id" value="<?= $rs_provincia['idpais_provincia'] ?>" size="6" /></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">N&ordm; de Orden :</td>
                                    <td align="left" valign="top"><input name="posicion" type="text" class="detalle_medio" id="posicion" value="<?= $rs_provincia['posicion'] ?>" size="6" /></td>
                                  </tr>
                                  <?
  		$query_titulo = "SELECT p.*
		FROM pais p
		INNER JOIN pais_provincia pc ON p.idpais = pc.idpais
    	WHERE idpais_provincia = '$idpais_provincia' ";
		$rs_titulo = mysql_fetch_assoc(mysql_query($query_titulo)); ?>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Pa&iacute;s Actual: </td>
                                    <td align="left" valign="top"><input name="pais_actual" type="text" disabled="disabled" class="detalle_medio" id="pais_actual" value="<?= $rs_titulo['titulo'] ?>" size="40" /></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Pa&iacute;s al que pertenece: </td>
                                    <td align="left" valign="top"><span class="style2">
                                      <select name="pais" class="detalle_medio" id="pais">
                                        <option value="" >- Seleccionar País</option>
                                        <?
		 
	  $query_idproducto = "SELECT *
	  FROM pais
	  WHERE estado <> 3 
	  ORDER BY titulo";
	  $result_idproducto = mysql_query($query_idproducto);
	  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
	  {
	  	if ($rs_provincia['idpais'] == $rs_idproducto['idpais'])
		{
			$sel_idproducto = "selected";
		}else{
			$sel_idproducto = "";
		}
?>
                                        <option value="<?= $rs_idproducto['idpais'] ?>"<? echo $sel_idproducto ?>>
                                        <?= $rs_idproducto['titulo'] ?>
                                        </option>
                                        <?  } ?>
                                    </select>
                                    </span></td>
                                  </tr>
                                  <?
  		$idprovincia = $_GET['id'];
		$query_provincia = "SELECT * FROM pais_provincia WHERE idpais_provincia = '$idpais_provincia' ";
		$rs_provincia = mysql_fetch_assoc(mysql_query($query_provincia));
		
?>
                                  <tr>
                                    <td width="21%" align="right" valign="middle" class="detalle_medio">Nombre de la provincia: </td>
                                    <td width="79%" align="left" valign="top"><label>
                                      <input name="provincia" type="text" class="detalle_medio" id="provincia" value="<?= $rs_provincia['titulo'] ?>" size="40" />
                                    </label></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                    <td align="left" valign="top"><input name="Button" type="button" class="buttons detalle_medio_bold" onclick="validar_form_preguntas();" value=" Guardar Cambios &raquo; " /></td>
                                  </tr>
                              </table></td>
                          </tr>
                        </table>
                        <br />
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