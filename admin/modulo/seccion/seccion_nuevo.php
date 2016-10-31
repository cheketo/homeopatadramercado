<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 	
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
	}else{
		$obj_value = '';
	}
	
	//CARGO PARÁMETROS DE SECCION
	$query_par = "SELECT *
	FROM seccion_parametro
	WHERE idseccion_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
	//VARIABLES:
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];
	$titulo = $_POST['titulo'];
	$accion = $_POST['accion'];
	
	//FILTRADO
	//si biene por get idcarpeta, es para el menu de iconos del home
	if(isset($_GET['idcarpeta'])){	
		$filtro_get_idcarpeta = " AND A.idcarpeta = $_GET[idcarpeta]";
	}else{
		$filtro_get_idcarpeta = " AND A.idcarpeta_padre = 0";
	}
	
	
	
	//CREO LA SECCION:
	if($accion == "mod6_insertar"){
		
		if($mod6_idcarpeta4){
			$mod6_sel_idcarpeta = $mod6_idcarpeta4;
		}else{
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
		}
	
		$fecha_alta = date("Y-m-d",time());
		
		//CARGO VALORES  POR DEFECTO
		$query_comen = "SELECT habilita_comentario_defecto
		FROM carpeta
		WHERE idcarpeta = '$mod6_sel_idcarpeta' ";
		$rs_comen = mysql_fetch_assoc(mysql_query($query_comen));
		
		//ingreso en tabla seccion
		$query_ingreso = "INSERT INTO seccion (
		  fecha_alta
		, mail_moderador
		, habilita_comentario
		) VALUES (
		 '$fecha_alta'
		, '$rs_parametro[mail_moderador_defecto]'
		, '$rs_comen[habilita_comentario_defecto]'
		)";
		mysql_query($query_ingreso);
	
		//peticion de la ultima seccion 
		$query_max = "SELECT MAX(idseccion) as idseccion FROM seccion";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		
		//INGRESO
		$query_ingreso = "INSERT INTO seccion_carpeta (
		  idcarpeta
		, idseccion
		) VALUES (
		  '$mod6_sel_idcarpeta'
		, '$rs_max[idseccion]'
		)";
		mysql_query($query_ingreso);
		
		//INGRESO IDIOMA
		$query_idioma = "SELECT ididioma, reconocimiento_idioma, valor_defecto
		FROM idioma 
		WHERE estado = '1'
		ORDER BY ididioma";
		$result_idioma = mysql_query($query_idioma);
		while($rs_idioma = mysql_fetch_assoc($result_idioma)){
		
			//PLANTILLA
			$query_plantilla = "SELECT plantilla_seccion
			FROM carpeta 
			WHERE idcarpeta = '$mod6_sel_idcarpeta' ";
			$rs_plantilla = mysql_fetch_assoc(mysql_query($query_plantilla));
		
			//TITULO IDIOMA
			if($rs_idioma['ididioma']==1){
				$titulo_seccion = $titulo;
			}else{
				$titulo_seccion = $titulo." (".$rs_idioma['reconocimiento_idioma'].")";
			}
			
			//ingreso en tabla carpeta idioma
			$query_idioma_ingreso = "INSERT INTO seccion_idioma_dato (
			  idseccion
			, ididioma
			, titulo
			, detalle
			, estado
			) VALUES (
			  '$rs_max[idseccion]'
			, '$rs_idioma[ididioma]'
			, '$titulo_seccion'
			, '$rs_plantilla[plantilla_seccion]'
			, '$rs_idioma[valor_defecto]'
			)";
			mysql_query($query_idioma_ingreso);
			
		}
		
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO seccion_sede(
			  idseccion
			, idsede
			)VALUES(
			  '$rs_max[idseccion]'
			, '$sede[$c]'
			)";
			mysql_query($query_insert);
		
		}	
		
		// ABRIR VENTANA EDITAR CATEGORIA:
		//echo "<script>alert('Se ha creado la sección \"".$titulo."\". \\nLa misma se encuentra desactivada por defecto.\\nPuede activarla desde el men&uacute; \"Secciones -> Ver (&aacute;rbol)\" ')</"."script>";
		echo "<script>window.open('seccion_editar.php?idseccion=".$rs_max['idseccion']."','_self');</script>";
	
	};
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

	function mod6_validarRegistro(){
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
			error = error + 'Debe seleccionar la carpeta en donde se creará la nueva seccion.\n';
			flag = false;
		}
		
		if (formulario.titulo.value == ""){
			error = error + 'Debe ingresar el nombre de la nueva seccion.\n';
			flag = false;
		} 
		
		if(flag == true){	
			formulario.accion.value = 'mod6_insertar';
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
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Secci&oacute;n - Nueva</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form" enctype="multipart/form-data">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="35" bgcolor="#FFDDBC" class="titulo_medio_bold">Seleccione un directorio para la secci&oacute;n:<span class="detalle_chico" style="color:#FF0000">
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
			document.getElementById("tr_carpeta4").style.display  = "none";
			document.getElementById("mod6_idcarpeta3").value  = '';
			document.getElementById("mod6_idcarpeta4").value  = '';
			
			carpeta = formulario.mod6_idcarpeta.value;
			oCntrl = formulario.mod6_idcarpeta2;
			break;
			
		case 2:
			//alert("paso 2");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display = "block";
			document.getElementById("tr_carpeta4").style.display  = "none";
			document.getElementById("mod6_idcarpeta4").value  = '';
			
			carpeta = formulario.mod6_idcarpeta2.value;
			oCntrl = formulario.mod6_idcarpeta3;
			break;	
			
		case 3:
			//alert("paso 3");
			document.getElementById("tr_carpeta4").style.display = "block";
			document.getElementById("mod6_idcarpeta4").style.display  = "inline";
			
			carpeta = formulario.mod6_idcarpeta3.value;
			oCntrl = formulario.mod6_idcarpeta4;
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
                                    <select name="mod6_idcarpeta" class="detalle_medio" id="mod6_idcarpeta" style="width:200px;" onchange="cambia(1)">
                                      <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
										<?	
											
											$query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
											FROM carpeta A
											INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
											WHERE A.estado <> 3 AND B.ididioma = '1' $filtro_get_idcarpeta
											ORDER BY B.nombre";
											$result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
											while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta)){
												if ($mod6_idcarpeta == $rs_mod6_idcarpeta['idcarpeta']){
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
                                  <div style="clear:left; height:1px;"></div>
                                <div id="tr_carpeta2" style="display:none">
                                    <div id="colum_carpeta">Carpeta 2&ordm;</div>
                                  <div id="colum_selector">
                                    <select name="mod6_idcarpeta2" class="detalle_medio" id="mod6_idcarpeta2" style="width:200px;" onchange="cambia(2)">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:1px;"></div>
                                <div id="tr_carpeta3" style="display:none">
                                    <div id="colum_carpeta">Carpeta 3&ordm;</div>
                                  <div id="colum_selector">
                                    <select name="mod6_idcarpeta3" class="detalle_medio" id="mod6_idcarpeta3" style="width:200px;" onchange="cambia(3)">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:1px;"></div>
                                <div id="tr_carpeta4" style="display:none">
                                    <div id="colum_carpeta">Carpeta 4&ordm;</div>
                                  <div id="colum_selector">
                                    <select name="mod6_idcarpeta4" class="detalle_medio" id="mod6_idcarpeta4" style="width:200px;" onchange="">
                                    </select>
                                  </div>
                                </div>
                                <div style="clear:left; height:1px;"></div></td>
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
                                      <td width="5%"><input  type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?
									   if(!$idsede_log){ 
									  	echo 'checked="checked"'; 
									  }else{
										  if($idsede_log != $rs_sede['idsede']){ 
											echo $obj_value; 
										  }else{ 
											echo 'checked="checked"'; 
										  } 
									  }
									   ?>  /></td>
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
                          <td height="35" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese el t&iacute;tulo de la nueva secci&oacute;n:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right" class="detalle_medio">T&iacute;tulo:</td>
                                <td align="left"><input name="titulo" type="text" class="detalle_medio text_field_01" id="titulo" value="<?=$titulo?>" size="60" />
                                  &nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit2222" type="button" class="detalle_medio_bold buttons" onclick="mod6_validarRegistro();" value=" &gt;&gt;  Ingresar  " />
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