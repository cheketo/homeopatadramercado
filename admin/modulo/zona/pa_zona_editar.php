<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	$idpais_zona = $_GET['id'];
	$accion = $_POST['accion'];
	
	$idpais = $_POST['idpais'];
	$idpais_provincia = $_POST['idpais_provincia'];
	$zona = $_POST['zona'];
	$posicion = $_POST['posicion'];
	
	//ACTUALIZAR		
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE pais_zona SET
		  idpais = '$idpais'
		, idpais_provincia = '$idpais_provincia'
		, titulo = '$zona'
		, posicion = '$posicion'
		WHERE idpais_zona = '$idpais_zona'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		/*echo "<script>window.location.href=('$PHP_SELF"."?id=$idpais_zona');</script>";*/
	
	};
	
	//DATOS DE LA ZONA
	$query_zona = "SELECT * FROM pais_zona WHERE idpais_zona = '$idpais_zona' ";
	$rs_zona = mysql_fetch_assoc(mysql_query($query_zona));
	
	
	if($idpais == ""){
		$idpais = $rs_zona['idpais'];
	}
	
	if($idpais_provincia == ""){
	$idpais_provincia = $rs_zona['idpais_provincia'];
	}
	
	if($zona == ""){
	$zona = $rs_zona['titulo'];
	}
	
 ?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function validar_form_preguntas(){
formulario = document.form_titular;

	if(formulario.idpais.value == 0){
		alert("Debe elegir el pais al que pertenece");
	}else if(formulario.idpais_provincia.value == 0){
		alert("Debe elegir la provincia al que pertenece");
	}else if(formulario.zona.value == ''){
		alert("Debe ingresar el nombre de la zona");
	}else{
		formulario.accion.value = "actualizar";
		formulario.submit();
	}

};

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
                <td width="81%" height="40" valign="bottom" class="titulo_grande_bold">Zona - Editar </td>
                <td width="7%" align="center" valign="middle" class="titulo_grande_bold"><a href="pa_zona_ver.php"><img src="../../imagen/iconos/globe_search.png" width="24" height="24" border="0" /></a></td>
                <td width="12%" valign="middle" class="detalle_medio"><a href="pa_zona_ver.php"><span class="detalle_medio" style="text-decoration:none">Ver Zonas</span></a></td>
              </tr>
              <tr>
                <td height="20" colspan="3" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                      <form action="" method="post" name="form_titular" id="form_titular">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr >
                            <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Editar zona:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                          </tr>
                          <tr >
                            <td bgcolor="#eafcf7">
							
							
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td width="22%" align="right" valign="middle" class="detalle_medio">ID:</td>
                                    <td width="78%" align="left" valign="top"><input name="id" type="text" disabled="disabled" style="text-align:center" class="detalle_medio" id="id" value="<?= $rs_zona['idpais_zona'] ?>" size="2" /></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">N&ordm; de Orden: </td>
                                    <td align="left" valign="top"><input name="posicion" type="text" class="detalle_medio" style="text-align:center" id="posicion" value="<?= $rs_zona['posicion'] ?>" size="2" /></td>
                                  </tr>

                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Pais al que pertenece:</td>
                                    <td align="left" valign="top"><select name="idpais" class="detalle_medio" id="idpais" style="width:200px;"  onchange="document.form_titular.submit();">
                                        <option value="" >--- Seleccionar País</option>
<?
		 
	  $query_idproducto = "SELECT *
	  FROM pais
	  WHERE estado <> 3 
	  ORDER BY titulo";
	  $result_idproducto = mysql_query($query_idproducto);
	  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto))	  
	  {
	  	if ($idpais == $rs_idproducto['idpais']){
			$sel_idproducto = "selected";
		}else{
			$sel_idproducto = "";
		}
		
?>
                                        <option value="<?= $rs_idproducto['idpais'] ?>" <?= $sel_idproducto ?>>
                                        <?= $rs_idproducto['titulo'] ?>
                                        </option>
                                        <?  } ?>
                                      </select>                                    </td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Nombre de la provincia:</td>
                                    <td align="left" valign="top"><select name="idpais_provincia" class="detalle_medio" style="width:200px;" id="idpais_provincia">
                                        <option value="" >--- Seleccionar Provincia</option>
                                        <?
		 
	  $query_idproducto = "SELECT *
	  FROM pais_provincia
	  WHERE estado <> 3 AND idpais = $idpais
	  ORDER BY titulo"; //$_POST[idpais]
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
                                        <option value="<?= $rs_idproducto['idpais_provincia'] ?>" <?= $sel_idproducto ?>>
                                        <?= $rs_idproducto['titulo'] ?>
                                        </option>
                                        <?  } ?>
                                      </select>                                    </td>
                                  </tr>
                                  <?

		$query_provincia = "SELECT * FROM pais_provincia WHERE idpais_provincia = '$idpais_provincia' ";
		$rs_provincia = mysql_fetch_assoc(mysql_query($query_provincia));
		
?>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Zona:</td>
                                    <td align="left" valign="top"><label>
                                      <input name="zona" type="text"  class="detalle_medio"  style="width:195px;" id="zona" value="<?= $zona ?>" size="40" />
                                    </label></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                    <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form_preguntas();" value=" Guardar Cambios &raquo; " /></td>
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