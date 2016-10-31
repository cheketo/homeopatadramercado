<? 	
	//INCLUDES
	include ("../../0_mysql.php");
	
	//FUNCION FORMATEAR FECHA
	function formatearFecha($fecha, $separador){
		
		$fecha_array = split("-",$fecha);
		return $fecha_array[2].$separador.$fecha_array[1].$separador.$fecha_array[0];
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$idservicio = $_GET['idservicio'];
	$titulo = $_POST['titulo'];

		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE cli_servicios SET
		  titulo='$titulo'
		WHERE idservicio = '$idservicio'
		LIMIT 1";
		mysql_query($query_modficacion);
	
	};
		
	//CONSULTA DE PROVINCIA
	$query = "SELECT * 
	FROM cli_servicios 
	WHERE idservicio = '$idservicio' ";
	$rs_servicio = mysql_fetch_assoc(mysql_query($query));
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_form_preguntas(){
	var formulario = document.form_titular;
	var flag = true;
	var error = "";
	
		if(formulario.titulo.value == ''){
			error = error + "Debe ingresar el titulo del producto o servicio.\n" ;
			flag = false;
		}
		
		if(flag == true){
			formulario.accion.value = "actualizar";
			formulario.submit();
		}else{
			alert(error);
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
                <td width="45%" height="40" valign="bottom" class="titulo_grande_bold"><img src="../../imagen/iconos/computer_process.png" width="32" height="32" /> Servicios - Editar </td>
                <td width="32%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                <td width="6%" align="center" valign="middle" class="detalle_medio">&nbsp;</td>
                <td width="11%" align="right" valign="middle" class="detalle_medio">Ver Servicios</td>
                <td width="6%" align="center" valign="middle" class="titulo_grande_bold"><a href="servicios_ver.php" target="_self"><img src="../../imagen/iconos/computer_search.png" width="24" height="24" border="0" /></a></td>
            </tr>
              <tr>
                <td height="20" colspan="5" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Servicio:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">N&deg; ID:</td>
                          <td width="83%" align="left" valign="top"><label>
                                  <input name="id" type="text" class="detalle_medio" id="id" disabled="disabled" value="<?= $rs_servicio['idservicio'] ?>" style="width:40px; text-align:center;" />
                                </label></td>
                            </tr>
                              <tr>
                                <td width="17%" align="right" valign="middle" class="detalle_medio">T&iacute;tulo:</td>
                                <td align="left" valign="top"><input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?= $rs_servicio['titulo']; ?>" style="width:300px;" /></td>
                              </tr>
                              <tr>
                                <td width="17%" align="right" valign="top" class="detalle_medio">&nbsp;</td>
                                <td align="left" valign="top" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold buttons" onclick="validar_form_preguntas();" value=" Modificar &raquo; " /></td>
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