<? 
	include ("../../0_mysql.php");

	//VARIABLES
	$accion = $_POST['accion'];
	$nombre = $_POST['nombre'];
	$ididioma = $_POST['ididioma'];
	$fecha_creacion = date("Y-m-d");
	$fecha_ultima_modificacion = date("Y-m-d");
	
	//INGRESAR		
	if( $accion == "ingresar" ){		
			
		$query_ingresar = "INSERT INTO ne_newsletter (
		  nombre_identificacion	
		, ididioma
		, fecha_creacion
		, fecha_ultima_modificacion
		) VALUES (
		  '$nombre'
		, '$ididioma'
		, '$fecha_creacion'
		, '$fecha_ultima_modificacion'
		)";
		mysql_query($query_ingresar);
		
		echo "<script>document.location.href='ne_newsletter_ver.php';</script>";
		
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">
	
	
	function validar(){
	
		var formulario = document.form;
		var error = '';
		var flag = true;
	
		if(formulario.nombre.value == ''){
			error = "Debe ingresar el nombre identificatorio del newsletter.\n";
			flag=false;
		}
		
		if(formulario.ididioma.value == ''){
			error = error + "Debe seleccionar el idioma.\n";
			flag=false;
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Newsletter - Nuevo </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
					<form action="" method="post" name="form" id="form" enctype="multipart/form-data">
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr>
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Crear nuevo newsletter:
                              <input name="accion" type="hidden" id="accion" value="" /></td>
                          </tr>
                          <tr>
                            <td bgcolor="#FFF0E1">
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Nombre: </td>
                                    <td align="left" valign="top"><input name="nombre" type="text" class="detalle_medio" id="nombre"  style="width:98%" /></td>
                                  </tr>
                                  <tr>
                                    <td width="15%" align="right" valign="middle" class="detalle_medio">Idioma:</td>
                                    <td width="85%" align="left" valign="top"><label><span class="style2">
                                    <select name="ididioma" class="detalle_medio" id="ididioma" style="width:180px;">
                                        <option value="" >- Seleccionar Idioma</option>
                                        <?
		 
	  $query_idioma = "SELECT ididioma, titulo_idioma
	  FROM idioma
	  WHERE estado = 1 AND publicar = 1 
	  ORDER BY titulo_idioma";
	  $result_idioma = mysql_query($query_idioma);
	  while($rs_idioma = mysql_fetch_assoc($result_idioma)){

?>
                                        <option value="<?= $rs_idioma['ididioma'] ?>" >
                                        <?= $rs_idioma['titulo_idioma'] ?>
                                        </option>
                                        <?  } ?>
                                      </select>
                                      </span></label></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                    <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar();" value="  Ingresar &raquo; " /></td>
                                  </tr>
                            </table></td>
                         </tr>
                      </table>
                    
                        <br />
					</form>
                  </td>
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