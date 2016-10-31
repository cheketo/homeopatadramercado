<? 	
	//INCLUDES
	include ("../../0_mysql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$ididioma = $_GET['ididioma'];
	$idencuesta = $_GET['idencuesta'];
	
	$opcion = $_POST['opcion'];
	$idopcion = $_POST['idopcion'];
	$cant_opciones = $_POST['cant_opciones'];
	
	//GUARDAR CAMBIOS
	if($accion == "guardar_encuesta" && $idencuesta && $idopcion){
		for($i=1;$i<=$cant_opciones;$i++){
		
			$query_upd = "UPDATE encuesta_opcion SET
			  opcion = '$opcion[$i]' 
			 WHERE idopcion = '$idopcion[$i]' AND idencuesta = '$idencuesta' ";
			mysql_query($query_upd);
		}
	
	}
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_opciones(){
	var formulario = document.form;
	var flag = true;
	var error = "";
		
		for(i=1;i<=formulario.cant_opciones.value;i++){
			
			txt_actual = document.getElementById("opcion["+i+"]");
			if (txt_actual.value == ""){
				error = error + "Debe completar la opcion "+ i +".\n";
				flag = false;
			}
			
		}
		
		if(flag == true){
			formulario.accion.value = "guardar_encuesta";
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Encuesta - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Editar Encuesta:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="14%" align="right" valign="top" class="detalle_medio">Encuesta:</td>
                                <td width="86%" align="left" valign="top" class="detalle_medio_bold"><?
								$query_encuesta = "SELECT pregunta
								FROM encuesta
								WHERE idencuesta = '$idencuesta' AND ididioma = '$ididioma' ";
								$rs_encuesta = mysql_fetch_assoc(mysql_query($query_encuesta));
								echo $rs_encuesta['pregunta'];
								?></td>
                              </tr>
                              <tr>
                                <td height="10" colspan="2" align="right" valign="top" class="detalle_medio"></td>
                              </tr>
							  <?
							  $c=0;
							  $query_opcion = "SELECT *
							  FROM encuesta_opcion
							  WHERE idencuesta = '$idencuesta' ";
							  $result_opcion = mysql_query($query_opcion);
							  
							  while($rs_opcion = mysql_fetch_assoc($result_opcion)){
							 	
								 $c++;
							  ?>
                              <tr>
                                <td width="14%" align="right" valign="middle" class="detalle_medio">Opcion <?= $c ?>:</td>
                                <td align="left" valign="top" class="detalle_medio"><input name="opcion[<?= $c ?>]" type="text" class="detalle_medio" id="opcion[<?= $c ?>]" value="<?= $rs_opcion['opcion'] ?>" size="40" />
                                <input name="idopcion[<?= $c ?>]" type="hidden" id="idopcion[<?= $c ?>]" value="<?= $rs_opcion['idopcion'] ?>" /></td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_opciones();" value=" Guardar &raquo; " />
                                <input name="cant_opciones" type="hidden" id="cant_opciones" value="<?= $c ?>" /></td>
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