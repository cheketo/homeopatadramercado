<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<? 

	$accion = $_POST['accion'];
	$in_titulo = $_POST['in_titulo'];
	$in_tipoinmueble  = $_POST['in_tipoinmueble'];
	
	//INGRESO INMUEBLE NUEVO:
	if($accion == "nuevo_inmueble"){	
			
		$query_ingreso = "INSERT INTO in_inmueble (
						  in_titulo,
						  in_tipoinmueble
						  ) VALUES (
						  '$in_titulo',
						  '$in_tipoinmueble'
						  )";
						  mysql_query($query_ingreso);
		
			
		// REDIRECCIONAR A EDITAR INMUEBLE:
		$query_max = "SELECT MAX(in_idinmueble) as in_idinmueble FROM in_inmueble";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		echo "<script>window.location.href('in_inmueble_editar.php?in_idinmueble=".$rs_max['in_idinmueble']."');</script>";
	};

?>				



<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>


<script language="javascript">

	function validar_form_titular(){
	formulario = document.form_inmueble;
	var c = true;
	
	if(formulario.in_titulo.value == ''){
		alert("Debe ingresar el titulo.");
		c = false;
	}
	
	if(formulario.in_tipoinmueble.value == ''){
		alert("Debe seleccionar el tipo de inmueble.");
		c = false;
	}
	
	if(c == true){
		formulario.accion.value = "nuevo_inmueble";
		formulario.submit();
	}
	
};
</script>
</head>
<body>
<div id="header">
  <? include("../../0_top.php"); ?>
</div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Inmueble - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_inmueble" id="form_inmueble">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Nuevo inmueble: <span class="style2">
                          <input name="accion" type="hidden" id="accion" value="-1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="19%" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                <td align="left" valign="middle" class="style2"><label>
                                  <input name="in_titulo" type="text" class="detalle_medio" id="in_titulo" size="70" />
                                </label></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><span class="detalle_medio" style="color:#666666">* Debe ingresar el titulo del nuevo inmueble que va a publicar.</span></td>
                              </tr>
                              <tr>
                                <td align="right" valign="middle" class="detalle_medio">Tipo de  Inmueble: </td>
                                <td align="left" valign="middle" class="style2"><select name="in_tipoinmueble" class="detalle_medio" id="in_tipoinmueble" >
                                  <option value="" >--- Seleccionar Tipo de Inmueble ---</option>
                                  <?
		 
										  $query = "SELECT *
										  FROM in_tipo_inmueble
										  WHERE in_tipo_inmueble_estado <> 2
										  ORDER BY in_tipo_inmueble_titulo";
										  $result = mysql_query($query);
										  while ($rs_tipoinmueble = mysql_fetch_assoc($result)){
									
								  ?>
                                  <option value="<?= $rs_tipoinmueble['in_idtipo_inmueble'] ?>" >
                                  <?= $rs_tipoinmueble['in_tipo_inmueble_titulo'] ?>
                                  </option>
                                  <?  } ?>
                                </select></td>
                              </tr>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><input name="Submit222" type="button" class="detalle_medio_bold" onclick="validar_form_titular();" value="  &gt;&gt;  Ingresar     " /></td>
                              </tr>
                          </table></td>
                        </tr>
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