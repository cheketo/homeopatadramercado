<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
	}else{
		$obj_value = '';
	}
	
	//VARIABLES
	$accion = $_POST['accion'];
	$idba_anunciante = $_POST['idba_anunciante'];
	$idba_lugar= $_POST['idba_lugar'];

	$cantidad_idioma = $_POST['cantidad_idioma'];
	$cantidad_sede = $_POST['cantidad_sede'];
	$idsede = $_POST['idsede'];
	$ididioma = $_POST['ididioma'];

	//INGRESO DEL BANNER
	if($accion == "ingresar"){	
	
		//OBTENGO EL IDTITULAR MAX + 1
		$query_id = "SELECT MAX(idba_banner) as idba_banner FROM ba_banner";
		$rs_id = mysql_fetch_assoc(mysql_query($query_id));
		$idba_banner_nuevo = $rs_id['idba_banner'] + 1;
		
		//IDIOMAS
		for($i=0;$i<$cantidad_idioma;$i++){
		
			$fecha_hoy = date("Y-m-d");
			
			if($ididioma[$i] != 0){
				$query_ingreso = "INSERT INTO ba_banner (
				  idba_banner
				, ididioma
				, idba_anunciante
				, idba_lugar 
				, fecha_alta
				) VALUES (
				  '$idba_banner_nuevo'
				, '$ididioma[$i]'
				, '$idba_anunciante'
				, '$idba_lugar'
				, '$fecha_hoy'
				)";
				mysql_query($query_ingreso);
			}
			
		}//FIN FOR
		
		//INGRESO A SEDES
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO ba_banner_sede(
			  idba_banner
			, idsede
			)VALUES(
			  '$idba_banner_nuevo'
			, '$idsede[$c]'
			)";
			mysql_query($query_insert);
		}
			
		//REDIRECCIONAR A EDITAR BANNER:
		echo "<script>window.location.href('ba_banner_ver.php?idba_banner=".$idba_banner_nuevo."');</script>";
		
	}; //FIN INGRESO

?>				

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_banner(){
	
		var formulario = document.form;
		var flag = true;
		var checks_idioma = 0;
		var checks_sede = 0;
		var error = "";
		
		if(formulario.idba_anunciante.value == ''){
			error = error + "Debe seleccionar el anunciante del banner que desea ingresar.\n";
			flag = false;
		}
		
		if(formulario.idba_lugar.value == ''){
			error = error + "Debe seleccionar el lugar del banner que desea ingresar.\n";
			flag = false;
		}
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = document.getElementById("idsede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		for(i=0;i<formulario.cantidad_idioma.value;i++){

			check_actual = document.getElementById("ididioma["+i+"]");
			if (check_actual.checked == true){
				checks_idioma = 1;
			}
			
		}

		if(checks_sede == 0){ 
			error = error + "Debe seleccionar al menos una sucursal.\n";
			flag = false;
		}
		
		if(checks_idioma == 0){ 
			error = error + "Debe seleccionar al menos un idioma.\n";
			flag = false;
		}
			
		if(flag == true){
			formulario.accion.value = "ingresar";
			formulario.submit();
		}else{
			alert(error);
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Banner - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese nuevo banner <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="-1" />
                          </span></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td colspan="4" valign="top" class="detalle_medio" style="color:#666666">Seleccione al anunciante del banner y su ubicaci&oacute;n:</td>
                              </tr>
                              <tr>
                                <td width="78" align="right" valign="middle" class="detalle_medio">Anunciante:</td>
                                <td colspan="3" align="left" valign="middle" class="style2"><select name="idba_anunciante" class="detalle_medio" id="idba_anunciante" style="width:200px;">
                                    <option value="" >-- Seleccionar Anunciante</option>
                                    <?
										  $query_idproducto = "SELECT * 
										  FROM ba_anunciante
										  WHERE estado = 1 
										  ORDER BY nombre";
										  $result_idproducto = mysql_query($query_idproducto);
										  while ($rs_idproducto = mysql_fetch_assoc($result_idproducto)){
									
									?>
                                    <option value="<?= $rs_idproducto['idba_anunciante'] ?>"><?= $rs_idproducto['nombre'] ?></option>
                                    <?  } ?>
                                </select></td>
                              </tr>
                              <tr>
                                <td width="78" align="right" valign="middle" class="detalle_medio">Ubicaci&oacute;n:</td>
                                <td colspan="3" align="left" valign="middle" class="style2"><select name="idba_lugar" class="detalle_medio" id="idba_lugar"  style="width:200px;">
                                    <option value="" >-- Seleccionar Ubicación</option>
                                    <?
										  $query_lugar = "SELECT * 
										  FROM ba_lugar
										  WHERE estado_lugar = 1 
										  ORDER BY nombre_lugar";
										  $result_lugar = mysql_query($query_lugar);
										  while ($rs_lugar = mysql_fetch_assoc($result_lugar)){
									
									?>
                                    <option value="<?= $rs_lugar['idba_lugar'] ?>"><?= $rs_lugar['nombre_lugar'] ?></option>
                                    <?  } ?>
                                </select></td>
                              </tr>
                              <tr>
                                <td width="78" align="right" valign="top" class="detalle_medio">Sucursales:</td>
                                <td width="241" align="left" valign="top" class="style2"><span class="detalle_medio">
                                <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								?>
                                  <input name="idsede[<?= $c ?>]" type="checkbox" id="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value; }else{ echo 'checked'; } ?> />
                                  <?= $rs_sede['titulo'] ?>
                                  <br />
                                <? 
								$c++;
								} 
								?>
                                  <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" />
                                </span></td>
                                <td width="67" align="right" valign="top" class="detalle_medio">Idiomas:</td>
                                <td width="252" align="left" valign="top" class="style2"><span class="detalle_medio">
                                  <?
								  		  $c=0;
										  $query_ididioma = "SELECT ididioma, titulo_idioma 
										  FROM idioma
										  WHERE estado = '1'
										  ORDER BY titulo_idioma";
										  $result_ididioma = mysql_query($query_ididioma);
										  while ($rs_ididioma = mysql_fetch_assoc($result_ididioma)){

								  ?>
                                  <input name="ididioma[<?= $c ?>]" type="checkbox" id="ididioma[<?= $c ?>]" value="<?= $rs_ididioma['ididioma'] ?>" checked="checked" />
                                  <?= $rs_ididioma['titulo_idioma'] ?>
                                  <br />
                                  <?  $c++; } ?>
                                  <input name="cantidad_idioma" type="hidden" id="cantidad_idioma" value="<?= $c ?>" />
                                </span></td>
                              </tr>
                              <tr>
                                <td valign="top" class="style2">&nbsp;</td>
                                <td colspan="3" align="left" valign="middle" class="style2"><input name="Submit222" type="button" class="detalle_medio_bold" onclick="validar_banner();" value=" &raquo;  Ingresar " /></td>
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