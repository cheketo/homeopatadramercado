<? 
	include ("../../0_mysql.php");
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
	}else{
		$obj_value = '';
	}
	
	//VARIABLES
	$accion = $_POST['accion'];
	$iduser_web = $_POST['iduser_web'];

	//FUNCION PAGINAR
	function paginar($actual, $total, $por_pagina, $enlace) {
	  $total_paginas = ceil($total/$por_pagina);
		  $anterior = $actual - 1;
		  $posterior = $actual + 1;
		  $intervalo = 20;
		  
		  if ($actual>1)
			$texto = "<a href=\"javascript:ir_pagina('$anterior');\">&laquo;</a> ";
		  else
			$texto = "<b>&laquo;</b> ";
			
		  $comienzo = $actual-($intervalo/2);
		  if($comienzo < 1) $comienzo = 1;
		  for ($i=$comienzo; $i<$actual; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
		  
		  $texto .= "<b>$actual</b> ";
		  
		  $fin = $comienzo + $intervalo;
		  if($total_paginas <= $fin) $fin = $total_paginas;
		  for ($i=$actual+1; $i<=$fin; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
			
		  
		  if ($actual<$total_paginas)
			$texto .= "<a href=\"javascript:ir_pagina('$posterior');\">&raquo;</a>";
		  else
			$texto .= "<b>&raquo;</b>";
			
		  return $texto;
	}
    
	//CHEQUEO PARA PAGINAR
	$cantidad_registros = $_POST['cant'];
	if(!$cantidad_registros){
		$cantidad_registros = 25;
	}
	
	$pag = $_POST['pag'];
	if(!$pag){
		$pag = 1;
	}
	$puntero = ($pag-1) * $cantidad_registros;
	
	//FILTRADO SEGMENTACION
	$filtro_segmentacion = "";
	$segmentacion = $_POST['segmentacion'];
	$cont_segmentacion = $_POST['cont_segmentacion'];
	$vuelta=0;
	
	if($cont_segmentacion != 0){
	
		$filtro_segmentacion .= " AND (";
		for($i=1; $i<$cont_segmentacion+1; $i++){
			if($segmentacion[$i]){
			
				if($vuelta == 0){
					$segmentacion_actual = $segmentacion[$i];
					$filtro_segmentacion .= " B.iduser_segmentacion = '$segmentacion_actual' ";
					$vuelta=1;
				}else{
					$segmentacion_actual = $segmentacion[$i];
					$filtro_segmentacion .= " OR B.iduser_segmentacion = '$segmentacion_actual' ";
				}
				
			}
		}
		$filtro_segmentacion .= " )";
	
	}
	if($vuelta == 0){
		$filtro_segmentacion = " AND ( B.iduser_segmentacion = '0' ) ";
	}
	
	//FILTRADO SEXO
	$filtro_sexo = "";
	$sexo = $_POST['sexo'];
	$cont_sexo = 3;
	$vuelta=0;
	
	$filtro_sexo .= " AND (";
	for($i=0; $i<$cont_sexo; $i++){
		
		if($sexo[$i]){
			if($vuelta == 0){
				$sexo_actual = $sexo[$i];
				$filtro_sexo .= " A.sexo = '$sexo_actual' ";
				$vuelta=1;
			}else{
				$sexo_actual = $sexo[$i];
				$filtro_sexo .= " OR A.sexo = '$sexo_actual' ";
			}
		}
		
	}
	$filtro_sexo .= " )";
	
	if($vuelta == 0){
		$filtro_sexo = " AND ( A.sexo = '-1' ) ";
	}
	
	
	
	//FILTRADO ESTADO
	$filtro_estado = "";
	$estado = $_POST['estado'];
	$cont_estado = 3;
	$vuelta=0;
	
	$filtro_estado .= " AND (";
	for($i=0; $i<$cont_estado; $i++){
		
		if($estado[$i]){
			if($vuelta == 0){
				$estado_actual = $estado[$i];
				$filtro_estado .= " A.estado = '$estado_actual' ";
				$vuelta=1;
			}else{
				$estado_actual = $estado[$i];
				$filtro_estado .= " OR A.estado = '$estado_actual' ";
			}
		}
		
	}
	$filtro_estado .= " )";
	
	if($vuelta == 0){
		$filtro_estado = " AND ( A.estado = '-1' ) ";
	}
	
	
	//FILTRADO IDIOMA
	$filtro_idioma = "";
	$ididioma = $_POST['ididioma'];
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$vuelta=0;
	
	if($cantidad_idioma != 0){
	
		$filtro_idioma .= " AND (";
		for($i=0; $i<$cantidad_idioma; $i++){
		
			if($ididioma[$i] != "" && $ididioma[$i] != 0){
				if($vuelta == 0){
					$ididioma_actual = $ididioma[$i];
					$filtro_idioma .= " A.ididioma = '$ididioma_actual' ";
					$vuelta=1;
				}else{
					$ididioma_actual = $ididioma[$i];
					$filtro_idioma .= " OR A.ididioma = '$ididioma_actual' ";
				}
			}
			
		}
		$filtro_idioma .= " )";
	
	}
	if($vuelta == 0){
		$filtro_idioma = " AND ( A.ididioma = '0' ) ";
	}

	
	
	//FILTRADO SEDE
	$filtro_sede = "";
	$idsede = $_POST['idsede'];
	$cantidad_sede = $_POST['cantidad_sede'];
	$vuelta=0;
	
	if($cantidad_sede != 0){
	
		$filtro_sede .= " AND (";
		for($i=0; $i<$cantidad_sede; $i++){
		
			if($idsede[$i]){
				if($vuelta == 0){
					$idsede_actual = $idsede[$i];
					$filtro_sede .= " A.idsede = '$idsede_actual' ";
					$vuelta=1;
				}else{
					$idsede_actual = $idsede[$i];
					$filtro_sede .= " OR A.idsede = '$idsede_actual' ";
				}
			}
			
		}
		$filtro_sede .= " )";
	
	}
	if($vuelta == 0){
		$filtro_sede = " AND ( A.idsede = '0' ) ";
	}
	
	//FILTRADO TIPO DE USUARIO
	$filtro_user_perfil = "";
	$iduser_web_perfil = $_POST['iduser_web_perfil'];
	$cantidad_user_perfil = $_POST['cantidad_user_perfil'];
	$vuelta=0;
	
	$filtro_user_perfil .= " AND (";
	for($i=0; $i<$cantidad_user_perfil; $i++){
		
		if($iduser_web_perfil[$i]){
			if($vuelta == 0){
				$iduser_web_perfil_actual = $iduser_web_perfil[$i];
				$filtro_user_perfil .= " A.iduser_web_perfil = '$iduser_web_perfil_actual' ";
				$vuelta=1;
			}else{
				$iduser_web_perfil_actual = $iduser_web_perfil[$i];
				$filtro_user_perfil .= " OR A.iduser_web_perfil = '$iduser_web_perfil_actual' ";
			}
		}
		
	}
	$filtro_user_perfil .= " )";
	
	if($vuelta == 0){
		$filtro_user_perfil = " AND ( A.iduser_web_perfil = '-1' ) ";
	}
	
	//FILTRO PAIS
	$idpais = $_POST['idpais'];
	if($idpais != 0){
		$filtro_pais = " AND A.idpais = ".$idpais." ";
	}else{
		$filtro_pais = "";
	}
	

	//FILTRO PROVINCIA
	$idpais_provincia = $_POST['idpais_provincia'];
	if($idpais_provincia != 0){
		$filtro_provincia = " AND A.idpais_provincia = ".$idpais_provincia." ";
	}else{
		$filtro_provincia = "";
	}
	
	
	//FILTRO ORDENAMIENTO
	$campo_orden = $_POST['campo_orden'];
	$orden = $_POST['orden'];
	
	if(!$campo_orden){
		$campo_orden = " A.iduser_web ";
	}
	
	//LINEAS
	if($_POST['linea_desde']){
		$linea_desde = $_POST['linea_desde'];
	}else{
		$linea_desde = 0;
	}
	
	if($_POST['linea_hasta']){
		$linea_hasta = $_POST['linea_hasta']-$linea_desde;
		$linea_hasta_post = $_POST['linea_hasta'];
	}else{
		$linea_hasta = 35000-$linea_desde;
		$linea_hasta_post = 35000;
	}

	//PAGINACION: Cantidad Total. //A.iduser_web, A.apellido, A.nombre, A.mail
	$query_cant = "SELECT DISTINCT A.mail 
		FROM user_web A 
		INNER JOIN user_web_segmentacion B ON B.iduser_web = A.iduser_web 
		WHERE 1=1 $filtro_segmentacion $filtro_sexo $filtro_estado $filtro_idioma $filtro_sede $filtro_user_perfil $filtro_pais $filtro_provincia 
		ORDER BY $campo_orden $orden 
		LIMIT $linea_desde,$linea_hasta";
		
	$query_exportar = "SELECT DISTINCT A.nombre, A.apellido, A.sexo, A.fecha_nacimiento, A.mail, A.telefono, 
		A.celular, A.fax, A.hash, A.calle, A.calle_numero, A.entre_calles, A.piso, A.depto, A.cp, A.idpais,
		A.idpais_provincia, A.localidad, A.iduser_web_perfil, A.fecha_registro, A.fecha_alta, A.fecha_baja,
		A.idsede, A.ididioma, A.username, A.password, A.emp_denominacion, A.emp_direccion, A.emp_telefono,
		A.emp_fax, A.emp_mail, A.emp_web, A.emp_cargo, A.emp_cuit, A.estado 
		FROM user_web A 
		INNER JOIN user_web_segmentacion B ON B.iduser_web = A.iduser_web 
		WHERE 1=1 $filtro_segmentacion $filtro_sexo $filtro_estado $filtro_idioma $filtro_sede $filtro_user_perfil $filtro_pais $filtro_provincia 
		ORDER BY $campo_orden $orden 
		LIMIT $linea_desde, $linea_hasta";
		
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_tips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>

<script language="javascript">
	
	function ir_pagina(pag){
		formulario = document.form_lista;
		formulario.pag.value = pag;
		formulario.action = "";
		formulario.target = "";
		formulario.submit();
	};
	
	function buscar(){
		var formulario = document.form_lista;
		var aux = 0;
		var flag = true;
		var error = "";
		var result = 0;
		
		/*if(esNumerico(formulario.linea_desde.value) == true && esNumerico(formulario.linea_hasta.value) == true){
		
			if(formulario.linea_desde.value > formulario.linea_hasta.value){
				aux = formulario.linea_desde.value;
				formulario.linea_desde.value = formulario.linea_hasta.value
				formulario.linea_hasta.value = aux;
			}
			
			result = formulario.linea_hasta.value - formulario.linea_desde.value;
			if(result > 35000){
				error = error + "Usted seleccionó un segmento de lineas que supera las 35000 permitidas.\n";
				flag = false;
			}
			
		}else{
			error = error + "Los valores de las lineas no son numericos.\n";
			flag = false;
		}*/
		
		if(flag == true){
			formulario.inicio.value = "1";
			formulario.accion.value = "buscar";
			formulario.pag.value = "1";
			formulario.action = "";
			formulario.target = "";
			formulario.submit();
		}else{
			 alert(error);
		}
			
	};
	
	function getCheckedValue(radioObj) {
		if(!radioObj)
			return "";
		var radioLength = radioObj.length;
		if(radioLength == undefined)
			if(radioObj.checked)
				return radioObj.value;
			else
				return "";
		for(var i = 0; i < radioLength; i++) {
			if(radioObj[i].checked) {
				return radioObj[i].value;
			}
		}
		return "";
	}

	
	function exportar(){
		formulario = document.form_lista;
		
		switch(getCheckedValue(formulario.tipo_exportacion)){
		
			case '1':
			formulario.action = "exportar_xls.php";
			break;
			
			case '2':
			formulario.action = "exportar_txt.php";
			break;
			
			case '3':
			formulario.action = "exportar_cvs_xajax_nc.php?sql_query=" + formulario.query_exportar_cvs.value;
			break;
			
			case '4':
			formulario.action = "exportar_cvs_puntoycoma.php";
			break;
			
			case '5':
			formulario.action = "exportar_cvs_coma.php";
			break;
			
			case '6':
			formulario.action = "exportar_cvs_xajax.php?sql_query=" + formulario.query_exportar_cvs.value;
			break;
						
		}
		formulario.target = "_blank";
		formulario.submit();
	};
	
	function validar_form_lista(){
		formulario = document.form_lista;
		formulario.pag.value = "1";
		formulario.accion.value = "buscar";
		formulario.action = "";
		formulario.target = "";
		formulario.submit();
	};
	
	window.addEvent('domready', function(){
	
		//TIPS	
		//var Tips1 = new Tips($$('.Tips1'));
		var Tips1 = new Tips($$('.Tips1'), {
		initialize:function(){
			this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 200, wait: false}).set(0);
		},
		onShow: function(toolTip) {
			this.fx.start(1);
		},
		onHide: function(toolTip) {
			this.fx.start(0);
		}
		});
	
	});
	
</script>
<style type="text/css">
<!--
.style2 {color: #999999}
.style3 {color: #666666}
-->
</style>
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Web  - Exportar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left"><form action="" method="post" name="form_lista" id="form_lista">
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr >
                        <td height="40" align="left" valign="middle" bgcolor="#d8f6ee" class="titulo_medio_bold">Filtrado de usuarios para exportar: 
                        <input name="inicio" type="hidden" id="inicio" value="<?= $_POST['inicio'] ?>" /></td>
                      </tr>
                      <tr >
                        <td align="left" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="34%" align="left" valign="middle" class="detalle_medio_bold">Tipo de Usuario:                            </td>
                            <td width="34%" align="left" valign="middle" class="detalle_medio_bold">Pais:</td>
                            <td width="32%" rowspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td width="34%" rowspan="3" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                              <?
								  		  $c=0;
										  $query = "SELECT iduser_web_perfil, titulo 
										  FROM user_web_perfil
										  WHERE estado = '1'
										  ORDER BY iduser_web_perfil";
										  $result = mysql_query($query);
										  while ($rs_perfil = mysql_fetch_assoc($result)){
										  
										  	if($_POST['iduser_web_perfil'][$c] == $rs_perfil['iduser_web_perfil']){
												$check = "checked";
											}else{
												$check = "";
											}

							?>
                              <tr>
                                <td width="4%"><input name="iduser_web_perfil[<?= $c ?>]" type="checkbox" id="iduser_web_perfil[<?= $c ?>]" value="<?= $rs_perfil['iduser_web_perfil'] ?>" <?= $check ?> <? if($_POST['inicio'] != 1){ echo "checked"; } ?> /></td>
                                <td width="96%" class="detalle_medio"><?= $rs_perfil['titulo'] ?></td>
                              </tr>
                              <?  $c++; } ?>
                            </table>
                              <input name="cantidad_user_perfil" type="hidden" id="cantidad_user_perfil" value="<?= $c ?>" /></td>
                            <td class="detalle_medio_bold"><label>
                              <select name="idpais" class="detalle_medio" id="idpais" style="width:210px;" onchange="buscar();">
							  	<option value="0" >- Todos los paises -</option>
                                
								<?
									  $query_idproducto = "SELECT *
									  FROM pais
									  WHERE estado <> 3 
									  ORDER BY titulo";
									  $result_idproducto = mysql_query($query_idproducto);
									  while ($rs = mysql_fetch_assoc($result_idproducto))	  
									  {
										if ($idpais == $rs['idpais'])
										{
											$sel = "selected";
										}else{
											$sel = "";
										}
								?>
								<option value="<?= $rs['idpais'] ?>" <?= $sel ?>>
								<?= $rs['titulo'] ?>
								</option>
								<?  } ?>
                              </select>
                            </label></td>
                          </tr>
                          <tr>
                            <td width="34%" class="detalle_medio_bold">Provincia:</td>
                            <td width="32%" rowspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="detalle_medio_bold"><label>
                              <select name="idpais_provincia" class="detalle_medio" id="idpais_provincia" style="width:210px;" onchange="buscar();">
							  <option value="0" >- Todos las provincias -</option>
                                
								<?
									  $query_idproducto = "SELECT *
									  FROM pais_provincia
									  WHERE estado <> 3 AND idpais = '$idpais'
									  ORDER BY titulo";
									  $result_idproducto = mysql_query($query_idproducto);
									  while ($rs = mysql_fetch_assoc($result_idproducto))	  
									  {
										if ($idpais_provincia == $rs['idpais_provincia'])
										{
											$sel = "selected";
										}else{
											$sel = "";
										}
								?>
								<option value="<?= $rs['idpais_provincia'] ?>" <?= $sel ?>>
								<?= $rs['titulo'] ?>
								</option>
								<?  } ?>
                              </select>
                            </label></td>
                          </tr>
                        </table>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="34%" align="left" valign="top" class="detalle_medio_bold"><span class="detalle_medio_bold">Sexo:</span></td>
                            <td width="34%" align="left" valign="top" class="detalle_medio_bold"><span class="detalle_medio_bold">Idioma:</span></td>
                            <td align="left" valign="top" class="detalle_medio_bold">Sucural:</td>
                            </tr>
                          <tr>
                            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td width="4%"><input name="sexo[0]" type="checkbox" id="sexo[0]" value="M" <? if($sexo[0] == "M"){ echo "checked"; } ?> <? if($_POST['inicio'] != 1){ echo "checked"; } ?>></td>
                                  <td width="96%" class="detalle_medio">Masculino</td>
                                </tr>
                                <tr>
                                  <td><input name="sexo[1]" type="checkbox" id="sexo[1]" value="F" <? if($sexo[1] == "F"){ echo "checked"; } ?> <? if($_POST['inicio'] != 1){ echo "checked"; } ?> /></td>
                                  <td width="96%" class="detalle_medio">Femenino</td>
                                </tr>
                                <tr>
                                  <td><input name="sexo[2]" type="checkbox" id="sexo[2]" value="N" <? if($sexo[2] == "N"){ echo "checked"; } ?> <? if($_POST['inicio'] != 1){ echo "checked"; } ?> /></td>
                                  <td class="detalle_medio">No seleccionado </td>
                                </tr>
                                                        </table></td>
                            <td rowspan="3" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <?
								  		  $c=0;
										  $query_ididioma = "SELECT ididioma, titulo_idioma 
										  FROM idioma
										  WHERE estado = '1'
										  ORDER BY titulo_idioma";
										  $result_ididioma = mysql_query($query_ididioma);
										  while ($rs_ididioma = mysql_fetch_assoc($result_ididioma))	  
										  {
										  
										  	if($_POST['ididioma'][$c] == $rs_ididioma['ididioma']){
												$check = "checked";
											}else{
												$check = "";
											}

							?>
                                <tr>
                                  <td width="4%"><input name="ididioma[<?= $c ?>]" type="checkbox" id="ididioma[<?= $c ?>]" value="<?= $rs_ididioma['ididioma'] ?>" <?= $check ?> <? if($_POST['inicio'] != 1){ echo "checked"; } ?> /></td>
                                  <td width="96%" class="detalle_medio"><?= $rs_ididioma['titulo_idioma'] ?></td>
                                </tr>
                                <?  $c++; } ?>
                                                        </table>                              
                              <input name="cantidad_idioma" type="hidden" id="cantidad_idioma" value="<?= $c ?>" /></td>
                            <td rowspan="3" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
									if($_POST['idsede'][$c] == $rs_sede['idsede']){
										$check = "checked";
									}else{
										$check = "";
									}
								
								?>
                                <tr>
                                  <td width="5%"><input name="idsede[<?= $c ?>]" type="checkbox" id="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" 
								  
								   <? 
								  
								  	if($idsede_log){
										if($idsede_log != $rs_sede['idsede']){ 
											echo $obj_value;
										}else{
											echo $check;
											if($_POST['inicio'] != 1){
												echo "checked"; 
											}
										}
									}else{ 
										echo $check; 
										if($_POST['inicio'] != 1){
											echo "checked"; 
										}
									} 
									
									?> <?  ?> /></td>
                                  <td width="95%" class="detalle_medio"><?= $rs_sede['titulo'] ?></td>
                                </tr>
                                <? 
								$c++;
								} 
								?>
                                                        </table>                              <span class="style2">
                              <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" />
                                                            </span></td>
                            </tr>
                          <tr>
                            <td align="left" valign="top" class="detalle_medio_bold"><span class="detalle_medio_bold">Estado:</span></td>
                            </tr>
                          <tr>
                            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td width="4%"><input name="estado[0]" type="checkbox" id="estado[0]" value="1" <? if($estado[0] == "1"){ echo "checked"; } ?> <? if($_POST['inicio'] != 1){ echo "checked"; } ?> /></td>
                                  <td width="96%" class="detalle_medio">Habilitado</td>
                                </tr>
                                <tr>
                                  <td width="4%"><input name="estado[1]" type="checkbox" id="estado[1]" value="2" <? if($estado[1] == "2"){ echo "checked"; } ?> /></td>
                                  <td class="detalle_medio">Deshabilitado</td>
                                </tr>
                                <tr>
                                  <td><input name="estado[2]" type="checkbox" id="estado[2]" value="3" <? if($estado[2] == "3"){ echo "checked"; } ?> <? if($_POST['inicio'] != 1){ echo "checked"; } ?> /></td>
                                  <td width="96%" class="detalle_medio">Sin confirmar </td>
                                </tr>
                                                        </table></td>
                            </tr>
                        </table></td>
                      </tr>
                    </table>
                    <br />
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="40" align="left" valign="middle" bgcolor="#d8f6ee" class="titulo_medio_bold">Seleccione las segmentaciones de usuario que desea exportar: </td>
                        </tr>
                        <tr >
                          <td align="center" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td height="30" align="left" valign="middle" class="detalle_medio_bold">Segmentaciones internas web: </td>
                                </tr>
                              </table>
                                <table width="100%" border="0" cellpadding="3" cellspacing="0" >
                                  <?
							  $cont_segmentacion = 0;
							  $query_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = '2'
							  ORDER BY iduser_segmentacion ASC";
							  $result_segmentacion = mysql_query($query_segmentacion);
							  while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){

								if($_POST['segmentacion'][$cont_segmentacion+1] == $rs_segmentacion['iduser_segmentacion']){
									$mod6_sel_segmentacion = "checked";
								}else{
									$mod6_sel_segmentacion = "";
								}
									
							  	$cont_segmentacion++;
								
								
							 ?>
                                  <tr>
                                    <td width="20" align="center" valign="middle"><input name="segmentacion[<?=$cont_segmentacion?>]" type="checkbox" id="segmentacion[<?=$cont_segmentacion?>]" value="<?=$rs_segmentacion['iduser_segmentacion']?>"  <?=$mod6_sel_segmentacion?> /></td>
                                    <td align="left" valign="middle" class="detalle_medio" ><div style="width:97%; height:14px; overflow:hidden">
                                      <?=$rs_segmentacion['titulo']?>
                                    </div></td>
                                  </tr>
                                  <?
							};
							
							  	$query_cant_tot = "SELECT COUNT(distinct uws.iduser_web) AS cant 
								FROM user_web_segmentacion uws
								INNER JOIN user_web uw ON uw.iduser_web = uws.iduser_web
								WHERE uw.estado = 1 ";
								$rs_cant_tot = mysql_fetch_assoc(mysql_query($query_cant_tot));
							
							?>
                                </table></td>
                              <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td height="30" align="left" valign="middle" class="detalle_medio_bold">Segmentaciones visibles para usuarios web: </td>
                                </tr>
                              </table>
                                <table width="100%" border="0" cellpadding="3" cellspacing="0" >
                                  <?
							  /*$cont_segmentacion = 0;*/
							  $query_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = '1'
							  ORDER BY iduser_segmentacion ASC";
							  $result_segmentacion = mysql_query($query_segmentacion);
							  while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){
							  
							  	if($_POST['segmentacion'][$cont_segmentacion+1] == $rs_segmentacion['iduser_segmentacion']){
									$mod6_sel_segmentacion = "checked";
								}else{
									$mod6_sel_segmentacion = "";
								}

							  	$cont_segmentacion++;
								
								
							 ?>
                                  <tr>
                                    <td width="20" align="center" valign="middle"><input name="segmentacion[<?=$cont_segmentacion?>]" type="checkbox" id="segmentacion[<?=$cont_segmentacion?>]" value="<?=$rs_segmentacion['iduser_segmentacion']?>" <?=$mod6_sel_segmentacion?> /></td>
                                    <td align="left" valign="middle" class="detalle_medio" ><div style="width:97%; height:14px; overflow:hidden">
                                      <?=$rs_segmentacion['titulo']?>
                                    </div></td>
                                  </tr>
                                  <?
							};
							
							  	$query_cant_tot = "SELECT COUNT(distinct uws.iduser_web) AS cant 
								FROM user_web_segmentacion uws
								INNER JOIN user_web uw ON uw.iduser_web = uws.iduser_web
								WHERE uw.estado = 1 ";
								$rs_cant_tot = mysql_fetch_assoc(mysql_query($query_cant_tot));
							
							?>
                                </table></td>
                            </tr>
                          </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td height="30" align="left" valign="middle" class="detalle_medio_bold">Segmentaciones de Origen:</td>
                                  </tr>
                                </table>
                                  <table width="100%" border="0" cellpadding="3" cellspacing="0" >
                                    <?
							  /*$cont_segmentacion = 0;*/
							  $query_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = '3'
							  ORDER BY titulo ASC";
							  $result_segmentacion = mysql_query($query_segmentacion);
							  while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){
							  
							  	if($_POST['segmentacion'][$cont_segmentacion+1] == $rs_segmentacion['iduser_segmentacion']){
									$mod6_sel_segmentacion = "checked";
								}else{
									$mod6_sel_segmentacion = "";
								}

							  	$cont_segmentacion++;
								
								
							 ?>
                                    <tr>
                                      <td width="20" align="center" valign="middle"><input name="segmentacion[<?=$cont_segmentacion?>]" type="checkbox" id="segmentacion[<?=$cont_segmentacion?>]" value="<?=$rs_segmentacion['iduser_segmentacion']?>" <?=$mod6_sel_segmentacion?> /></td>
                                      <td align="left" valign="middle" class="detalle_medio" ><div style="width:97%; height:14px; overflow:hidden">
                                          <?=$rs_segmentacion['titulo']?>
                                      </div></td>
                                    </tr>
                                    <?
							};
							
							  	$query_cant_tot = "SELECT COUNT(distinct uws.iduser_web) AS cant 
								FROM user_web_segmentacion uws
								INNER JOIN user_web uw ON uw.iduser_web = uws.iduser_web
								WHERE uw.estado = 1 ";
								$rs_cant_tot = mysql_fetch_assoc(mysql_query($query_cant_tot));
							
							?>
                                  </table></td>
                                <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td height="30" align="left" valign="middle" class="detalle_medio_bold">Segmentaciones Especiales:</td>
                                  </tr>
                                </table>
                                  <table width="100%" border="0" cellpadding="3" cellspacing="0" >
                                    <?
							  /*$cont_segmentacion = 0;*/
							  $query_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = '4'
							  ORDER BY titulo ASC";
							  $result_segmentacion = mysql_query($query_segmentacion);
							  while ($rs_segmentacion = mysql_fetch_assoc($result_segmentacion)){
							  
							  	if($_POST['segmentacion'][$cont_segmentacion+1] == $rs_segmentacion['iduser_segmentacion']){
									$mod6_sel_segmentacion = "checked";
								}else{
									$mod6_sel_segmentacion = "";
								}

							  	$cont_segmentacion++;
								
								
							 ?>
                                    <tr>
                                      <td width="20" align="center" valign="middle"><input name="segmentacion[<?=$cont_segmentacion?>]" type="checkbox" id="segmentacion[<?=$cont_segmentacion?>]" value="<?=$rs_segmentacion['iduser_segmentacion']?>" <?=$mod6_sel_segmentacion?> /></td>
                                      <td align="left" valign="middle" class="detalle_medio" ><div style="width:97%; height:14px; overflow:hidden">
                                          <?=$rs_segmentacion['titulo']?>
                                      </div></td>
                                    </tr>
                                    <?
							};
							
							  	$query_cant_tot = "SELECT COUNT(distinct uws.iduser_web) AS cant 
								FROM user_web_segmentacion uws
								INNER JOIN user_web uw ON uw.iduser_web = uws.iduser_web
								WHERE uw.estado = 1 ";
								$rs_cant_tot = mysql_fetch_assoc(mysql_query($query_cant_tot));
							
							?>
                                  </table></td>
                              </tr>
                            </table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="8">
                                <tr>
                                  <td height="30" align="left" valign="middle" class="detalle_medio"><span style="font-size: 11px"><a href="javascript:seleccionar_todo();" style="color:#990000">Seleccionar todo</a> &nbsp;/ &nbsp;<a href="javascript:deseleccionar_todo();" style="color:#990000">Deseleccionar todo 
                                    <input name="cont_segmentacion" type="hidden" id="cont_segmentacion" value="<?= $cont_segmentacion ?>" />
                                  </a></span></td>
                                </tr>
                            </table></td>
                        </tr>
                    </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" align="left" valign="middle" bgcolor="#d8f6ee" class="titulo_medio_bold">Ordenar los resultados seg&uacute;n: </td>
                        </tr>
                        <tr>
                          <td align="center" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td height="30" align="left" valign="middle" class="detalle_medio"><select name="campo_orden" class="detalle_medio" id="campo_orden" style="width:200px;">
                                <option value="A.apellido" <? if($campo_orden == "A.apellido"){ echo "selected"; } ?>>Apellido</option>
								<option value="A.nombre" <? if($campo_orden == "A.nombre"){ echo "selected"; } ?>>Nombre</option>
								<option value="A.mail" <? if($campo_orden == "A.mail"){ echo "selected"; } ?>>E-mail</option>
                                <option value="A.iduser_web" <? if($campo_orden == "A.iduser_web"){ echo "selected"; } ?>>ID Usuario</option>
                                <option value="A.fecha_registro" <? if($campo_orden == "A.fecha_registro"){ echo "selected"; } ?>>Fecha de registracion</option>
                                                                                                                        </select>
                                &nbsp;&nbsp;<select name="orden" class="detalle_medio" id="orden">
                                  <option value="ASC" <? if($orden == "ASC"){ echo "selected"; } ?> >Asc.</option>
                                  <option value="DESC" <? if($orden == "DESC"){ echo "selected"; } ?> >Desc.</option>
                                  </select>                              </td>
                            </tr>
                          </table></td>
                        </tr>
                      </table>
                      <br />
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td height="40" align="left" valign="middle" bgcolor="#d8f6ee" class="titulo_medio_bold">Archivo: </td>
                        </tr>
                        <tr>
                          <td align="center" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="16%" height="8" align="left" valign="top" class="detalle_medio">Tipo exportaci&oacute;n:</td>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio"><label><input  name="tipo_exportacion" type="radio" value="1" checked="checked" <? if($_POST['tipo_exportacion'] == 1){ echo 'checked="checked"'; } ?> />
                                    <span class="style3">Excel (*.xls).</span></label>
                                <br />
                                 <label> <input name="tipo_exportacion" type="radio" value="2"  <? if($_POST['tipo_exportacion'] == 2){ echo 'checked="checked"'; } ?> />
                                Texto plano (*.txt).</label> <br />
                                <label> <input name="tipo_exportacion" type="radio" value="3"  <? if($_POST['tipo_exportacion'] == 3){ echo 'checked="checked"'; } ?> />
Exportaci&oacute;n para newslettercorporativo (*.cvs). <br />
                                </label><label> <input name="tipo_exportacion" type="radio" value="4"  <? if($_POST['tipo_exportacion'] == 4){ echo 'checked="checked"'; } ?> />
Exportar -separado por punto y coma- (*.cvs). <br />
                                </label><label> <input name="tipo_exportacion" type="radio" value="5"  <? if($_POST['tipo_exportacion'] == 5){ echo 'checked="checked"'; } ?> />
Exportar -separado por coma- (*.cvs). <br />
<input name="tipo_exportacion" type="radio" value="6"  <? if($_POST['tipo_exportacion'] == 6){ echo 'checked="checked"'; } ?> />
Exportar -xajax- (*.cvs). <br />
                                </label>
								<p>&nbsp;</p></td>
                              </tr>
                              <tr>
                                <td width="16%" height="4" align="left" valign="middle" class="detalle_medio">Lineas:</td>
                                <td width="7%" align="left" valign="middle" class="detalle_medio">Desde:
                                  <label></label></td>
                                <td width="77%" align="left" valign="middle" class="detalle_medio"><input name="linea_desde" type="text" class="detalle_medio" id="linea_desde"  size="6" maxlength="7" <? if($_POST['inicio'] != 1){ echo 'value="0"'; }else{ echo 'value="'.$linea_desde.'"'; } ?>  /></td>
                              </tr>
                              <tr>
                                <td width="16%" height="3" align="left" valign="top" class="detalle_medio">&nbsp;</td>
                                <td width="7%" align="left" valign="middle" class="detalle_medio">Hasta:</td>
                                <td align="left" valign="middle" class="detalle_medio"><input name="linea_hasta" type="text" class="detalle_medio" id="linea_hasta" size="6" maxlength="7" <? if($_POST['inicio'] != 1){ echo 'value="35000"'; }else{ echo 'value="'.$linea_hasta_post.'"'; } ?> /></td>
                              </tr>
                          </table></td>
                        </tr>
                      </table>
<script language="javascript" >
	
	function seleccionar_todo(){
		for(i=1; i<<?=$cont_segmentacion+1?>; i++){
			ncampo = "segmentacion["+i+"]";
			campo = window.form_lista.document.getElementById(ncampo);
			campo.checked = true;
		}
	}
	
	function deseleccionar_todo(){
		for(i=1; i<<?=$cont_segmentacion+1?>; i++){
			ncampo = "segmentacion["+i+"]";
			campo = window.form_lista.document.getElementById(ncampo);
			campo.checked = false;
		}
	}

</script>
                      <table width="100%" border="0" cellpadding="8" cellspacing="0">
                        <tr>
                          <td align="right"><input name="ingresar" type="button" class="detalle_medio_bold" id="ingresar" onclick="javascript:buscar();" value=" &raquo; Ver Listado" /></td>
                        </tr>
                    </table>

                        <input name="cont_segmentacion" type="hidden" value="<?=$cont_segmentacion?>" />
                        <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                        <input name="accion" type="hidden" id="accion" value="<?= $_POST['accion'] ?>" />

						<? if($accion == "buscar"){ ?>
						
					  <p>&nbsp;</p>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="50%" align="left" valign="middle" class="detalle_medio">
                          <input name="exportar2" type="button" class="detalle_medio_bold" id="exportar2" onclick="javascript:exportar()" value=" Exportar &raquo; " />
                          <input name="query_exportar" type="hidden" id="query_exportar" value="<?= $query_exportar ?>" />
						  <input name="query_exportar_cvs" type="hidden" id="query_exportar_cvs" value="<?= $query_cant ?>" /></td>
                          <td width="41%" align="right" valign="middle" class="detalle_medio">Registros por hoja </td>
                          <td width="9%" valign="middle" class="detalle_medio"><select name="cant" class="detalle_medio" onchange="validar_form_lista();">
                              <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?> >25</option>
                              <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                              <option value="100"<? if($cantidad_registros == 100){ echo "selected"; } ?>>100</option>
                          </select></td>
                        </tr>
                        <tr height="5">
                          <td colspan="2" align="right" valign="middle" class="detalle_medio"></td>
                          <td valign="middle" class="detalle_medio"></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#ffddbc">
                          <td width="4%" height="40" align="left" valign="middle" class="detalle_medio_bold">ID</td>
                          <td width="31%" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">E-mail</td>
                          <td width="34%" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Nombre y apellido </td>
                          <td width="15%" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Estado</td>
                          <td width="16%" height="40" align="left" valign="middle" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
        <?
	
		$cont_usuarios = 0;
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		
		$query_lista = "SELECT DISTINCT A.*
		FROM user_web A
		INNER JOIN user_web_segmentacion B ON B.iduser_web = A.iduser_web
		WHERE 1=1 $filtro_segmentacion $filtro_sexo $filtro_estado $filtro_idioma $filtro_sede $filtro_user_perfil $filtro_pais $filtro_provincia
		ORDER BY $campo_orden $orden
		LIMIT $puntero,$cantidad_registros ";
		
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			$hay_lista = true;
			$cont_usuarios++;
		
		?>
						
						<tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="4%" align="left" valign="middle" class="detalle_chico"><?=$rs_lista['iduser_web']?>
                              <input name="iduser_web_row[<?= $cont_usuarios ?>]" type="hidden" id="iduser_web_row[<?= $cont_usuarios ?>]" value="<?= $rs_lista['iduser_web'] ?>" />
                          <a name="ancla_<?=$rs_lista['iduser_web']?>" id="ancla_<?=$rs_lista['iduser_web']?>"></a></td>
                          <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><div style="width:180px; height:14px; overflow:hidden"><?=$rs_lista['mail']?></div></td>
                          <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['apellido'].", ".$rs_lista['nombre']?></td>
                          <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><? if($rs_lista['estado'] == 1){ ?><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" />
                            <? }else{ ?>
                            <img src="../../imagen/b_habilitado_off.png" alt="Habilitado" width="15" height="16" border="0" />
                            <? } ?><? if($rs_lista['estado'] == 3){ ?><img src="../../imagen/b_confirmar.png" alt="A Confirmar" width="15" height="16" border="0" />
<? }else{ ?>
<img src="../../imagen/b_confirmar_off.png" alt="A Confirmar" width="15" height="16" border="0" />
<? } ?><? if($rs_lista['estado'] == 2){ ?><img src="../../imagen/b_deshabilitado.png" alt="Habilitado" width="15" height="16" border="0" />
<? }else{ ?>
<img src="../../imagen/b_deshabilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" />
<? } ?></td>
                          <td align="left" valign="middle"><img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idioma  ::
								  <?
								  $query_idioma = "SELECT titulo_idioma
								  FROM idioma
								  WHERE ididioma = $rs_lista[ididioma]";
								  $rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));

								  echo "&bull; ".$rs_idioma['titulo_idioma'];

								  ?>" /> <img src="../../imagen/iconos/sede.png" width="14" height="20"  class="Tips1" title="Sucursal  ::
								  <?
								  $query_sede = "SELECT titulo
								  FROM sede
								  WHERE idsede = $rs_lista[idsede]";
								  $rs_sede = mysql_fetch_assoc(mysql_query($query_sede));

								  echo "&bull; ".$rs_sede['titulo'];

								  ?>"  /> <img src="../../imagen/iconos/segmento.png" width="21" height="20"  class="Tips1" title="Segmentaciones  ::
								  <?
								  $query_sede = "SELECT B.titulo
								  FROM user_web_segmentacion A
								  INNER JOIN user_segmentacion B ON A.iduser_segmentacion = B.iduser_segmentacion
								  WHERE A.iduser_web = $rs_lista[iduser_web]";
								  $result = mysql_query($query_sede);
								  while($rs_sede = mysql_fetch_assoc($result)){
									  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }

								  ?>"  /></td>
                        </tr>
                        <?
		if($cont_colores == 0){
			$cont_colores = 1;
		}else{
			$cont_colores = 0;
		};
					
	} if($hay_lista == false){ ?>
                        <tr align="center" valign="middle" >
                          <td colspan="5" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado usuarios.</td>
                        </tr>
						 <? };	?>
                        <tr align="center" valign="middle">
                          <td colspan="5" class="titulo_medio_bold"><? echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']."?cant=".$cantidad_registros."&pag=" ); ?></td>
                        </tr>
                        <tr align="center" valign="middle" height="50">
                          <td colspan="5" bgcolor="#FFFFFF" class="detalle_medio">Cantidad total de usuarios:
                            <?= $cantidad_total ?></td>
                        </tr>
                    </table>
					<? } //SI HAY RESULTADOS EN LA BUSQEDA ?>
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