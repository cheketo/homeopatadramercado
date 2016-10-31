<? 
	include ("../../0_mysql.php"); 

	//VARIABLES
	$titulo = $_POST['titulo'];
	$accion = $_POST['accion'];

	//INGRESO
	if($accion == "insertar"){			
	
		//ingreso en tabla categoria admin
		$query_ingreso = "INSERT INTO admin_menu (
		  titulo
		, icono
		) VALUES (
		  '$titulo'
		, '1'
		)";
		mysql_query($query_ingreso);		
		
		//peticion de ultima categoria en tabla categoria
		$query_max = "SELECT MAX(idadmin_menu) AS idadmin_menu FROM admin_menu";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		
		//ingreso en tabla categoria admin perfil
		$query_ingreso = "INSERT INTO admin_menu_perfil (
		  idadmin_menu
		, iduser_admin_perfil 
		) VALUES (
		  '$rs_max[idadmin_menu]'
		, '1'
		)";
		mysql_query($query_ingreso);		
		
		// ABRIR VENTANA EDITAR CATEGORIA:		
		echo "<script>window.location.href('admin_panel_editar.php?idadmin_menu=".$rs_max['idadmin_menu']."');</script>";

	};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

	function mod6_validarRegistro(){
		formulario = document.form;
		if (formulario.titulo.value == "") {
			alert("Debe seleccionar una categoria padre.");
		} else {	
			formulario.accion.value = 'insertar';
			formulario.submit();
		};
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Menu Iconos Admin    - Nueva</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="50" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese el t&iacute;tulo del nuevo Icono Panel:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="90" align="right" valign="top" class="detalle_medio">T&iacute;tulo</td>
                                <td valign="top" class="style2"><input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?=$titulo?>" size="60" /></td>
                              </tr>
                              <tr>
                                <td width="90" valign="top" class="style2">&nbsp;</td>
                                <td align="left" valign="middle" class="style2"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit22222" type="button" class="detalle_medio_bold" onclick="mod6_validarRegistro();" value="  &gt;&gt;  Ingresar     " />
                                </span></td>
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