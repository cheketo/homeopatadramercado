<? 
	include ("../../0_mysql.php"); 
	
	//DESPLEGAR BOTON DE BARRA N°
	$desplegarbarra = "2";

	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
	}else{
		$obj_value = '';
	}
	
	//VARIABLES
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$nombre = $_POST['nombre'];
	$accion = $_POST['accion'];
	$editar = $_POST['editar'];

	//INGRESO:
	if($accion == "mod6_insertar"){
	
		if($mod6_idcarpeta3){
			$mod6_sel_idcarpeta = $mod6_idcarpeta3;
		}else{
			if($mod6_idcarpeta2){
				$mod6_sel_idcarpeta = $mod6_idcarpeta2;
			}else{
				if($mod6_idcarpeta){
					$mod6_sel_idcarpeta = $mod6_idcarpeta;
				}
			}	
		}	
	
		//ingreso en tabla categoria
		$query_ingreso = "INSERT INTO carpeta (
		  idcarpeta_padre
		, estado
		) VALUES (
		  '$mod6_sel_idcarpeta'
		, '1'
		)";
		mysql_query($query_ingreso);
	
		//peticion de ultima categoria en tabla categoria
		$query_max = "SELECT MAX(idcarpeta) AS idcarpeta FROM carpeta";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));	
		
														
		$query_idioma = "SELECT ididioma, reconocimiento_idioma 
		FROM idioma
		WHERE estado = 1
		ORDER BY ididioma";
		$result_idioma = mysql_query($query_idioma);
		while($rs_idioma = mysql_fetch_assoc($result_idioma)){
		
			if($rs_idioma['reconocimiento_idioma'] != "es"){
				$nombre_carpeta = $nombre." (".$rs_idioma['reconocimiento_idioma'].")";
			}else{
				$nombre_carpeta = $nombre;
			}
							
			//ingreso en tabla categoria idioma
			$query_idioma_ingreso = "INSERT INTO carpeta_idioma_dato (
			  idcarpeta
			, ididioma
			, nombre
			) VALUES (
			  '$rs_max[idcarpeta]'
			, '$rs_idioma[ididioma]'
			, '$nombre_carpeta'
			)";
			mysql_query($query_idioma_ingreso);
		}
			
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO carpeta_sede(
			  idcarpeta
			, idsede
			)VALUES(
			  '$rs_max[idcarpeta]'
			, '$sede[$c]'
			)";
			mysql_query($query_insert);
		
		}		
	
		// ABRIR VENTANA EDITAR CATEGORIA:
		if($editar == 1){
			echo "<script>window.open('carpeta_editar.php?idcarpeta=".$rs_max['idcarpeta']."&forma=arbol','_self');</script>";
		}
	
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

	function mod6_validarRegistro(edit){
		var formulario = document.form;
		var checks_sede = 0;
		var error = '';
		var flag = true;
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = document.getElementById("sede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			error = error + 'Debe seleccionar al menos una sucursal.\n';
			flag = false;
		}
		
		if (formulario.mod6_idcarpeta.value == ""){
			error = error + 'Debe seleccionar la carpeta en donde se creará la nueva.\n';
			flag = false;
		}
		
		if (formulario.nombre.value == ""){
			error = error + 'Debe ingresar el nombre de la nueva carpeta.\n';
			flag = false;
		} 
		
		if(flag == true){	
			formulario.accion.value = 'mod6_insertar';
			formulario.editar.value = edit;
			formulario.submit();
		}else{
			alert(error);
		}
	};

	function mod6_select_idcarpeta(idcat)
	{			
		formulario = document.form;
		formulario.submit();
	}
	
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Carpeta - Nueva</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <? if($editar == 2){ ?>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="16" height="30" align="center" valign="middle" bgcolor="#669966" class="detalle_medio_bold_white"><img src="../../imagen/iconos/accept_habilitado.png" width="16" height="16" /></td>
                          <td height="30" align="left" valign="middle" bgcolor="#669966" class="detalle_medio_bold_white">La carpeta fue creada con &eacute;xito.</td>
                        </tr>
                      </table>
                      <br />
                      <? } ?>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="35" bgcolor="#FFDDBC" class="titulo_medio_bold">Seleccione la carpeta en donde desea crear una nueva:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" value="1" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                            <script language="JavaScript" type="text/javascript">
var i;
function cambia(paso){  

    var formulario = document.form; 
	var carpeta;
	var oCntrl;
	
	switch(paso){
		case 1:
			//alert("paso 1");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display  = "none";
			document.getElementById("mod6_idcarpeta3").value  = '';
			
			carpeta = formulario.mod6_idcarpeta.value;
			oCntrl = formulario.mod6_idcarpeta2;
			break;
			
		case 2:
			//alert("paso 2");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display = "block";
			
			carpeta = formulario.mod6_idcarpeta2.value;
			oCntrl = formulario.mod6_idcarpeta3;
			break;	
			
	}   
   
	//alert(carpeta);
	var txtVal = carpeta;
	
	while(oCntrl.length > 0) oCntrl.options[0]=null;  
	i = 0; 
	oCntrl.clear;  
	
	var selOpcion=new Option("--- Seleccionar Carpeta ---", '');  
	eval(oCntrl.options[i++]=selOpcion);  
	 
	<?
	$query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre, A.idcarpeta_padre
	FROM carpeta A
	INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	WHERE A.estado <> 3 AND A.idcarpeta_padre != '0' AND B.ididioma = '1'
	ORDER BY B.nombre";
	$result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
	while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2)){
	?>
	
	if ("<?= $rs_mod6_idcarpeta2['idcarpeta_padre'] ?>" == txtVal){  
		var selOpcion=new Option("<?= $rs_mod6_idcarpeta2['nombre'] ?>", "<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>");  
		eval(oCntrl.options[i++]=selOpcion);  
	}  

	<? } ?> 
}  
   
                          </script>
                            <tr>
                              <td><div id="tr_carpeta">
                                  <div id="colum_carpeta">Carpeta 1&ordm;</div>
                                <div id="colum_selector"><span class="style10">
                                    <select name="mod6_idcarpeta" class="detalle_medio" id="mod6_idcarpeta" onchange="cambia(1)" style="width:200px;">
                                      <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
									  <option value="0">- Carpeta Raiz</option>
                                      <?
	  										//si biene por get idcarpeta, es para el menu de iconos del home
											if(isset($_GET['idcarpeta'])){	
												$filtro_get_idcarpeta = " AND A.idcarpeta = $_GET[idcarpeta]";
											}else{
												$filtro_get_idcarpeta = " AND A.idcarpeta_padre = 0";
											}
											
										  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
										  FROM carpeta A
										  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
										  WHERE A.estado <> 3 AND B.ididioma = '1' $filtro_get_idcarpeta 
										  ORDER BY B.nombre";
										  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
										  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
										  {
											if ($carpeta1 == $rs_mod6_idcarpeta['idcarpeta'])
											{
												$mod6_sel_idcarpeta = "selected";
											}else{
												$mod6_sel_idcarpeta = "";
											}
									?>
                                      <option  <? echo $mod6_sel_idcarpeta ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                                      <?= $rs_mod6_idcarpeta['nombre'] ?>
                                      </option>
                                      <?  } ?>
                                    </select>
                                  </span></div>
                              </div>
                                  <div style="clear:left; height:0px;"></div>
                                <div id="tr_carpeta2" style="display:none">
                                    <div id="colum_carpeta">Carpeta 2&ordm;</div>
                                  <div id="colum_selector">
                                    <select name="mod6_idcarpeta2" class="detalle_medio" id="mod6_idcarpeta2" onchange="cambia(2)" style="width:200px;">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:0px;"></div>
                                <div id="tr_carpeta3" style="display:none">
                                    <div id="colum_carpeta">Carpeta 3&ordm;</div>
                                  <div id="colum_selector">
                                    <select name="mod6_idcarpeta3" class="detalle_medio" id="mod6_idcarpeta3" style="width:200px;">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:0px;"></div>
							  </td>
                            </tr>
                          </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="18%" align="right" valign="top">Para las sucursales: </td>
                                <td width="82%"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                  <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1
								ORDER BY titulo";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
								?>
                                  <tr>
                                    <td width="5%"><input type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? 
									if(!$idsede_log){ 
									  	echo 'checked="checked"'; 
									  }else{
										  if($idsede_log != $rs_sede['idsede']){ 
											echo $obj_value; 
										  }else{ 
											echo 'checked="checked"'; 
										  } 
									  }
									?> /></td>
                                    <td width="95%"><?= $rs_sede['titulo'] ?></td>
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
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="35" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese el nombre de la carpeta nueva:
                          <input type="hidden" name="editar" id="editar" /></td>
                      </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right" class="detalle_medio">Nombre:</td>
                                <td align="left"><input name="nombre" type="text" class="detalle_medio text_field_01" id="nombre" value="<? if($editar != 2){ echo $nombre; } ?>" size="60" />                                  &nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit2222" type="button" class="detalle_medio_bold buttons" onclick="mod6_validarRegistro(1);" value="  &gt;&gt;  Ingresar y Editar" />
                                </span></span></span></span></span></span><span class="detalle_chico" style="color:#FF0000">
                                <input name="Submit" type="button" class="detalle_medio_bold buttons" onclick="mod6_validarRegistro(2);" value="  &gt;&gt;  Ingresar     " />
                                </span></span></td>
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