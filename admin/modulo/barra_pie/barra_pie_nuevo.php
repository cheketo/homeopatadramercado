<? 	include ("../../0_mysql.php"); ?>
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
	$check_idsede = $_POST['idsede'];
	$cantidad_sede = $_POST['cantidad_sede'];
	
	$url = $_POST['url'];
	$titulo = $_POST['titulo'];
	$target = $_POST['target'];
	$orden = $_POST['orden'];
	
	
	//INGRESAR NUEVO LINK
	if($accion == "ingresar"){
		
		//INGRESO
		$query_ingreso = "INSERT INTO barra_pie (
		  link
		, target
		, orden
		) VALUES (
		  '$url'
		, '$target'
		, '$orden'
		)";
		mysql_query($query_ingreso);
	
		//peticion del ultimo id ingresado
		$query_max = "SELECT MAX(idbarra_pie) AS idbarra_pie FROM barra_pie";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		
		$query_idioma = "SELECT ididioma, reconocimiento_idioma, valor_defecto
		FROM idioma
		WHERE estado = 1
		ORDER BY ididioma";
		$result_idioma = mysql_query($query_idioma);
		while($rs_idioma = mysql_fetch_assoc($result_idioma)){
		
			//TITULO A INGRESAR:
			if($rs_idioma['reconocimiento_idioma'] != 'es'){
				$titulo_ins = $titulo." (".$rs_idioma['reconocimiento_idioma'].")";
			}else{
				$titulo_ins = $titulo;
			}
							
			//ingreso en tabla idioma
			$query_idioma_ingreso = "INSERT INTO barra_pie_idioma (
			  idbarra_pie
			, ididioma
			, titulo
			, estado
			) VALUES (
			  '$rs_max[idbarra_pie]'
			, '$rs_idioma[ididioma]'
			, '$titulo_ins'
			, '$rs_idioma[valor_defecto]'
			)";
			mysql_query($query_idioma_ingreso);
			
		}
		
		//LO ASIGNO PARA LAS SEDE DETERMINADAS
		for($c=0;$c<$cantidad_sede;$c++){
			
			if($check_idsede[$c] != ""){
				$query_sede = "INSERT INTO barra_pie_sede(
				  idbarra_pie
				, idsede
				)VALUES(
				  '$rs_max[idbarra_pie]'
				, '$check_idsede[$c]'
				)";
				mysql_query($query_sede);
			}
		
		}
		
		//REDIRECCIONO A EDITAR
		echo "<script>window.open('barra_pie_editar.php?idbarra_pie=$rs_max[idbarra_pie]','_self');</script>";
	
	}//FIN IF
	
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
<script type="text/javascript">
	
	function agregar_nuevo(){
		var formulario = document.form;
		var flag = true;
		var checks_sede = 0;
		

		if(formulario.titulo.value == ""){
			alert("Debe introducir el titulo.");
			flag = false;
		}
		
		if(formulario.url.value == ""){
			alert("Debe introducir la URL.");
			flag = false;
		}
		
		if(esNumerico(formulario.orden.value)==false){
			alert("El Orden debe ser númerico.");
			flag = false;
		}
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = document.getElementById("idsede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			alert("Debe seleccionar al menos una sucursal.");
			flag = false;
		}
		
		if(flag == true){
			formulario = document.form;
			formulario.accion.value = "ingresar";
			formulario.submit();
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Barra Pie - Nuevo </td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#cccccc;"></td>
              </tr>
            </table>
              <form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="#ffddbc">
                    <td height="40" align="left" bgcolor="#FFE9D2" class="titulo_medio_bold">Nuevo Link                    
                      <input name="accion" type="hidden" id="accion" /></td>
                  </tr>
                  <tr valign="middle" bgcolor="#fff0e1" height="50">
                    <td align="left" ><table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Link:</td>
                            <td><label>
                              <input name="url" type="text" class="detalle_medio" id="url" size="70" />
                              </label></td>
                        </tr>
                      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Titulo:</td>
                            <td><label>
                              <input name="titulo" type="text" class="detalle_medio" id="titulo" size="70" />
                              </label></td>
                        </tr>
                      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
					      <tr>
					        <td width="110" align="right" class="detalle_medio">Target:</td>
                            <td><label>
                              <select name="target" class="detalle_medio" id="target">
                                <option value="_self" selected="selected">_self</option>
                                <option value="_blank">_blank</option>
                                <option value="_parent">_parent</option>
                                <option value="_top">_top</option>
                            </select>
                              </label></td>
                          </tr>
				      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Orden:</td>
                            <td><label>
                              <input name="orden" type="text" class="detalle_medio" id="orden" value="0" size="7" />
                              </label></td>
                        </tr>
                      </table>
					    <br />
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="110" align="right" valign="top" class="detalle_medio">Sucursales: </td>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
								?>
                                  <tr>
                                    <td width="4%" height="24"><input type="checkbox" id="idsede[<?= $c ?>]" name="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? if($_GET['idsede'] == $rs_sede['idsede']){ echo "checked";} ?> <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?> /></td>
                                      <td width="96%" class="detalle_medio"><?= $rs_sede['titulo'] ?></td>
                                  </tr>
                                  <? 
								$c++;
								} 
								?>
                                  </table>
                                    <span class="style2">
                                      <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" />
                                  </span></td>
                            </tr>
                            </table></td>
                        </tr>
                      </table>
					    <br />
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">&nbsp;</td>
                            <td><label>
                              <input name="Button" type="button" class="detalle_medio_bold" value=" Insertar &raquo;" onclick="javascript:agregar_nuevo()"/>
                              </label></td>
                        </tr>
                      </table>
				    </td>
                  </tr>
                </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr align="center" valign="middle" height="50">
                    <td height="20" align="left" valign="top" class="detalle_medio" style="color:#FF6600"><br /></td>
                  </tr>
                </table>
              </form></td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>