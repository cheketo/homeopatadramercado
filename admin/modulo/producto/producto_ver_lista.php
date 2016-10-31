<? include ("../../0_mysql.php");
	
	//VARIABLES
	$idproducto = $_POST['idproducto'];
	$ruta_foto = "../../../imagen/producto/mediana/";
	$accion = $_POST['accion'];
	
	//SI EL USUARIO ADMIN ES NIVEL 1
	if($_SESSION['idcusuario_perfil_log']=='1'){
		$usuario_perfil = 1;
	}else{
		$usuario_perfil = 2;
	}
	
	//FUNCION PAGINAR
	function paginar($actual, $total, $por_pagina, $enlace) {
	  $total_paginas = ceil($total/$por_pagina);
		  $anterior = $actual - 1;
		  $posterior = $actual + 1;
		  if ($actual>1)
			$texto = "<a href=\"javascript:ir_pagina('$anterior');\">&laquo;</a> ";
		  else
			$texto = "<b>&laquo;</b> ";
		  for ($i=1; $i<$actual; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
		  $texto .= "<b>$actual</b> ";
		  for ($i=$actual+1; $i<=$total_paginas; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
		  if ($actual<$total_paginas)
			$texto .= "<a href=\"javascript:ir_pagina('$posterior');\">&raquo;</a>";
		  else
			$texto .= "<b>&raquo;</b>";
		  return $texto;
	}
	
	//PAGINACION
	$cantidad_registros = $_POST['cant'];
	if(!$cantidad_registros){
		$cantidad_registros = 25;
	}
	
	$pag = $_POST['pag'];
	if(!$pag){
		$pag = 1;
	}
	$puntero = ($pag-1) * $cantidad_registros;
	
	if(isset($_GET['idcarpeta'])){		
		$sel_idcarpeta_bis = " AND B.idcarpeta =".$_GET['idcarpeta']; 
		$idcarpeta = $_GET['idcarpeta'];		
	}else{
		//VARIABLES DE CARPETAS
		$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
		$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
		$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
		$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];
	
		if($mod6_idcarpeta4){	
			$sel_idcarpeta_bis = "AND B.idcarpeta =".$mod6_idcarpeta4;
			$idcarpeta = $mod6_idcarpeta4;
		}else{	
			if($mod6_idcarpeta3){
				$sel_idcarpeta_bis = "AND B.idcarpeta =".$mod6_idcarpeta3;
				$idcarpeta = $mod6_idcarpeta3;
			}else{
				if($mod6_idcarpeta2){
					$sel_idcarpeta_bis = "AND B.idcarpeta =".$mod6_idcarpeta2;
					$idcarpeta = $mod6_idcarpeta2;
				}else{
					if($mod6_idcarpeta == '-1'){			
						$sel_idcarpeta_bis = "AND 1=1 ";
					}else{
						if($mod6_idcarpeta){
							$sel_idcarpeta_bis = "AND B.idcarpeta =".$mod6_idcarpeta;
							$idcarpeta = $mod6_idcarpeta;
						}
					}
				}
			}
		}	
	}//fin del get[idcarpeta]
	
	
	//CAMBIA ESTADO
	$estado = $_POST['estado'];
	
	if($estado != "" && $idproducto != ""){
		$query_estado = "UPDATE producto SET estado = '$estado'
		WHERE idproducto = '$idproducto'
		LIMIT 1";
		mysql_query($query_estado);
	}
	
	//CAMBIO DEL CAMPO RESTRINGIDO
	$restringido = $_POST['restringido'];
	
	if($restringido != "" && $idproducto != ""){
		$query = "UPDATE producto
		SET restringido = $restringido
		WHERE idproducto = '$idproducto'	
		LIMIT 1";
		mysql_query($query);
	}
	
	//CAMBIO SEGURO
	$seguro = $_POST['seguro'];
	
	if($seguro != "" && $idproducto != ""){
		$query = "UPDATE producto
		SET seguro = $seguro
		WHERE idproducto = '$idproducto'	
		LIMIT 1";
		mysql_query($query);
	}
	
	//APLICAR CAMBIOS
	if($accion == "aplicar_cambios"){		
		
		$cont_producto = $_POST['cont_producto'];
		$idproducto_row = $_POST['idproducto_row'];
		$orden_row = $_POST['orden_row'];
		$checkbox_row = $_POST['checkbox_row'];	
				
		//ORDENAMIENTO DEL ARRAY
		//METODO DE BURBUJEO
		/*for($i=0;$i<$count_fila;$i++){			
			
			for($j=0;$j<$count_fila-1;$j++){
				
				if($orden_row[$j] > $orden_row[$j+1]){
				
					$aux = $orden_row[$j]; 
					$orden_row[$j]=$orden_row[$j+1]; 
					$orden_row[$j+1] = $aux; 
					
					$aux = $idseccion_row[$j]; 
					$idseccion_row[$j]=$idseccion_row[$j+1]; 
					$idseccion_row[$j+1] = $aux; 
					
				}
				
			}
			
		}*/
		
		for($i=0;$i<$cont_producto;$i++){	
				
			$query_upd = "UPDATE producto_carpeta
			SET orden = '$orden_row[$i]'
			WHERE idproducto = '$idproducto_row[$i]' AND idcarpeta = '$idcarpeta'
			LIMIT 1";
			mysql_query($query_upd);
					
		}
		
		
		//ELIMINAR SELECCION
		for($i=0;$i<$cont_producto;$i++){	
		
			if($checkbox_row[$i] != ""){
				$query_upd = "UPDATE producto
				SET estado = '3'
				WHERE idproducto = '$idproducto_row[$i]'
				LIMIT 1";
				mysql_query($query_upd);
			}
			
		}
		
		
		//Reordena	
		/*$count_tp_reordenar = 0;	
		$query_tp_reordenar = "SELECT A.idseccion, B.idcarpeta
		FROM seccion A	
		INNER JOIN seccion_carpeta B ON B.idseccion = A.idseccion	
		INNER JOIN seccion_sede C ON C.idseccion = A.idseccion	
		WHERE A.estado = '1' AND B.idcarpeta = '$idcarpeta' AND C.idsede = '$filtrado_sede' 
		ORDER BY B.orden
		";
		$result_tp_reordenar = mysql_query($query_tp_reordenar);
		while ($rs_tp_reordenar = mysql_fetch_assoc($result_tp_reordenar)){
			echo $count_tp_reordenar ++;
			echo "<br>";
			$queryup_tp = "UPDATE seccion_carpeta
			SET orden = '$count_tp_reordenar'
			WHERE idseccion = '$rs_tp_reordenar[idseccion]' AND idcarpeta = '$rs_tp_reordenar[idcarpeta]'
			LIMIT 1";
			mysql_query($queryup_tp);				
		}	*/
			
	}
	
	//FILTRADO
	if($_POST['ordenar'] != ""){
		$_SESSION['ordenar_producto'] = $_POST['ordenar'];
		$filtro_ordenar = $_SESSION['ordenar_producto'];
	}else{
		$filtro_ordenar = $_SESSION['ordenar_producto'];
	}
	
	if($_POST['filtro_idioma'] != ""){ 
		$_SESSION['filtro_idioma'] = $_POST['filtro_idioma'];
		$filtro_idioma = $_SESSION['filtro_idioma'];
	}else{
		$filtro_idioma = $_SESSION['filtro_idioma'];
	}
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
	}else{
		$obj_value = '';
		if($_POST['filtro_sede'] != ""){ 
			$_SESSION['filtro_sede'] = $_POST['filtro_sede'];
			$filtro_sede = $_SESSION['filtro_sede'];
		}else{
			$filtro_sede = $_SESSION['filtro_sede'];
		}
	}
	
	if($filtro_idioma != 0){
		$filtro_idioma = $filtro_idioma;
		$filtrado_idioma = " AND C.estado = '1' ";
	}else{
		$filtro_idioma = 1;
		$filtrado_idioma = "";
	}
	
	if($filtro_sede != 0){
		$filtrado_sede = " AND D.idsede = '".$filtro_sede."'";
		$filtrado_sede_inner = " INNER JOIN producto_sede D ON D.idproducto = A.idproducto ";
	}else{
		$filtrado_sede = "";
		$filtrado_sede_inner = "";
	}
	
	if($filtro_ordenar != 0){
		$filtrado_ordenar = $filtro_ordenar." ASC ";
	}else{
		$filtrado_ordenar = " B.orden ASC, C.titulo ";
	}
	
	//CANTIDAD TOTAL:
	$query_cant = "SELECT DISTINCT A.idproducto, C.titulo, A.fecha_alta, A.foto, A.estado
	FROM producto A
	LEFT JOIN producto_carpeta B ON A.idproducto = B.idproducto
	LEFT JOIN producto_idioma_dato C ON C.idproducto = A.idproducto
	$filtrado_sede_inner
	WHERE A.estado <> 3 AND C.ididioma = '$filtro_idioma' $filtrado_idioma $sel_idcarpeta_bis $filtrado_sede";
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto - Ver (Listado) </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_carpeta" id="form_carpeta">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Buscar por Carpeta<span class="detalle_medio_bold">
                              <input name="accion" type="hidden" id="accion" />
                              <input name="idproducto" type="hidden" id="idproducto" />
                              <input name="estado" type="hidden" id="estado" />
                              <input name="restringido" type="hidden" id="restringido" />
                              <input name="seguro" type="hidden" id="seguro" />
                              <span class="detalle_medio">
                              <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                              <span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                              <input name="lista_fil" type="hidden" id="lista_fil" value="1" />
                              </span><span class="detalle_chico" style="color:#FF0000">
                              <input name="lista_orden" type="hidden" id="lista_orden" value="1" />
                              </span></span></span></span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td bgcolor="#FFF0E1">
<script language="JavaScript" type="text/javascript">

	function ir_pagina(pag){
		formulario = document.form_carpeta;
		formulario.pag.value = pag;
		formulario.accion.value = "buscar";
		formulario.submit();
	}

	function mod6_select_idcarpeta(nivel){
		formulario = document.form_carpeta;
		
		switch(nivel){
			case 1: 
			 formulario.mod6_idcarpeta2.value = "";
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 1");
			 break;
		
			case 2: 
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 2");
			 break;
			 
			case 3:
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 3");
			 break;
		}
			
		formulario.submit();
	}
		
	function validar_form_lista(){
		formulario = document.form_carpeta;
		formulario.pag.value = "1";
		formulario.accion.value = "buscar";
		
		formulario.submit();
	};
	function cambiar_estado(estado, idproducto){
		formulario = document.form_carpeta;
		
		formulario.estado.value = estado;
		formulario.idproducto.value = idproducto;
		formulario.accion.value = "buscar";
		formulario.submit();
	};
	function confirm_cambiar_estado(estado, idproducto){
		formulario = document.form_carpeta;
			if (confirm('¿Esta seguro que desea eliminar esta sección?')){
				formulario.estado.value = estado;
				formulario.idproducto.value = idproducto;
				formulario.accion.value = "buscar";
				formulario.submit();
			}
	};
	
	function cambiar_restringido(restringido, idproducto){
	formulario = document.form_carpeta;
	
	formulario.restringido.value = restringido;
	formulario.idproducto.value = idproducto;
	formulario.accion.value = "buscar";
	formulario.submit();
	};
	
	function cambiar_seguro(seguro, idproducto){
	formulario = document.form_carpeta;
	
	formulario.seguro.value = seguro;
	formulario.idproducto.value = idproducto;
	formulario.accion.value = "buscar";
	formulario.submit();
	};
	
	window.addEvent('domready', function(){
		//TIPS	
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
	
	

function lista_orden(filtro,orden){
	formulario = document.form_lista;
	formulario.lista_fil.value = filtro;
	formulario.lista_orden.value = orden;
	formulario.submit();
};

function lista_reordenar(filtro,orden){
	formulario = document.form_lista;
	formulario.lista_fil.value = filtro;
	formulario.lista_orden.value = orden;
	formulario.accion.value = "lista_reordenar";
	formulario.submit();
};
function submitear(){
	formulario = document.form_lista;
	formulario.lista_orden.value = '<?=$lista_orden?>';
	formulario.lista_fil.value = '<?=$lista_fil?>';
	formulario.submit();
};

function aplicar_cambios(){
	formulario = document.form_carpeta;
	formulario.action = "";
	formulario.target = "";
	formulario.accion.value = "aplicar_cambios";
	formulario.submit();
};

</script>
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td><span class="detalle_medio" style="color:#666666">Seleccione la carpeta a la que pertenece el producto:</span></td>
                                  </tr>
                                </table>
                                <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                  <tr>
                                    <td align="right" valign="middle" class="detalle_medio">Carpeta 1&ordm; </td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta" class="detalle_medio" style="width:200px;" id="mod6_idcarpeta" onchange="mod6_select_idcarpeta('1')">
                                        <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
	  {
	  	if ($mod6_idcarpeta == $rs_mod6_idcarpeta['idcarpeta'])
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
                                    </span></td>
                                  </tr>
                                  <tr>
                                    <? if($mod6_idcarpeta){ ?>
                                    <td align="right" valign="middle" class="detalle_medio">Carpeta 2&ordm;</td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta2" class="detalle_medio" id="mod6_idcarpeta2" style="width:200px;"  onchange="mod6_select_idcarpeta('2');">
                                        <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta'  AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
	  while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2))	  
	  {
	  	if ($mod6_idcarpeta2 == $rs_mod6_idcarpeta2['idcarpeta'])
		{
			$mod6_sel_idcarpeta2 = "selected";
		}else{
			$mod6_sel_idcarpeta2 = "";
		}
?>
                                        <option  <? echo $mod6_sel_idcarpeta2 ?> value="<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>">
                                        <?= $rs_mod6_idcarpeta2['nombre'] ?>
                                        </option>
                                        <?  } ?>
                                      </select>
                                    </span></td>
                                    <?  } ?>
                                  </tr>
                                  <tr>
                                    <? if($mod6_idcarpeta2 && $mod6_idcarpeta){ ?>
                                    <td align="right" valign="middle" class="detalle_medio">Carpeta 3&ordm; </td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta3" class="detalle_medio" id="mod6_idcarpeta3"  style="width:200px;"  onchange="mod6_select_idcarpeta('3')">
                                        <option value="">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta3 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta2' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta3 = mysql_query($query_mod6_idcarpeta3);
	  while ($rs_mod6_idcarpeta3 = mysql_fetch_assoc($result_mod6_idcarpeta3))	  
	  {
	  	if ($mod6_idcarpeta3 == $rs_mod6_idcarpeta3['idcarpeta'])
		{
			$mod6_sel_idcarpeta3 = "selected";
		}else{
			$mod6_sel_idcarpeta3 = "";
		}
?>
                                        <option  <? echo $mod6_sel_idcarpeta3 ?> value="<?= $rs_mod6_idcarpeta3['idcarpeta'] ?>">
                                        <?= $rs_mod6_idcarpeta3['nombre'] ?>
                                        </option>
                                        <?  } ?>
                                      </select>
                                    </span></td>
                                    <?  }   ?>
                                  </tr>
                                  <tr>
                                    <? if($mod6_idcarpeta3 && $mod6_idcarpeta2  && $mod6_idcarpeta){ ?>
                                    <td align="right" valign="middle" class="detalle_medio">Carpeta 4&ordm; </td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta4" class="detalle_medio"  style="width:200px;"  id="mod6_idcarpeta4">
                                        <option value="">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta4 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta3' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta4 = mysql_query($query_mod6_idcarpeta4);
	  while ($rs_mod6_idcarpeta4 = mysql_fetch_assoc($result_mod6_idcarpeta4))	  
	  {
	  	if ($mod6_idcarpeta4 == $rs_mod6_idcarpeta4['idcarpeta'])
		{
			$mod6_sel_idcarpeta4 = "selected";
		}else{
			$mod6_sel_idcarpeta4 = "";
		}
?>
                                        <option  <? echo $mod6_sel_idcarpeta4 ?> value="<?= $rs_mod6_idcarpeta4['idcarpeta'] ?>">
                                        <?= $rs_mod6_idcarpeta4['nombre'] ?>
                                        </option>
                                        <?  } ?>
                                      </select>
                                    </span></td>
                                    <?  }   ?>
                                  </tr>
                                  
                                  <tr>
                                    <td width="90" valign="top" class="detalle_medio_bold">&nbsp;</td>
                                    <td align="left" valign="middle"><input name="Submit222" type="button" class="detalle_medio_bold" value="&gt;&gt; Buscar " onclick="validar_form_lista();" /></td>
                                  </tr>
                            </table></td>
                          </tr>
                    </table>
                        <br />
                        <? if($sel_idcarpeta_bis){ ?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td align="right" class="detalle_medio">Filtrar por idioma: </td>
                            <td width="23%" align="left"><select name="filtro_idioma" style="width:160px;" class="detalle_medio" id="filtro_idioma" onchange="javascript:document.form_carpeta.submit();">
                                <? 
		if($_POST['filtro_idioma'] == '0'){
			$sel_idioma = "selected";
		}else{
			$sel_idioma = "";
		}
		
		//echo $_SESSION['filtro_idioma']."-".$sel_idioma
	?>
                                <option value="0" <?= $sel_idioma ?>>- Seleccionar Idioma</option>
                                <? 
	$query_idioma = "SELECT ididioma, titulo_idioma
	  FROM idioma 
	  WHERE estado = 1
	  ORDER BY titulo_idioma";
			  
	  $result_idioma = mysql_query($query_idioma);
	  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
		
			if($_POST['filtro_idioma'] == $rs_idioma['ididioma']){
				$sel_idioma = "selected";
			}else{
				$sel_idioma = "";
			}
	  
	  ?>
                                <option value="<?= $rs_idioma['ididioma'] ?>" <?= $sel_idioma ?>>
                                <?= $rs_idioma['titulo_idioma'] ?>
                                </option>
                                <? } ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td align="right" class="detalle_medio">Filtrar por sucursales:</td>
                            <td align="left"><select name="filtro_sede" class="detalle_medio" style="width:160px;" <?= $obj_value ?> id="filtro_sede" onchange="javascript:document.form_carpeta.submit();">
                                <option value="0">- Seleccione Sucursal</option>
                                <? $query_sede = "SELECT idsede, titulo
						  FROM sede 
						  WHERE estado = 1";
								  
						  $result_sede = mysql_query($query_sede);
						  while($rs_sede = mysql_fetch_assoc($result_sede)){
						  	
								if($filtro_sede == $rs_sede['idsede']){
									$sel_sede = "selected";
								}else{
									$sel_sede = "";
								}
						  
						  ?>
                                <option value="<?= $rs_sede['idsede'] ?>" <?= $sel_sede ?>>
                                <?= $rs_sede['titulo'] ?>
                                </option>
                                <? } ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td align="right" class="detalle_medio">Ordenar por: </td>
                            <td align="left"><select name="ordenar" class="detalle_medio" id="ordenar" style="width:160px;" onchange="javascript:document.form_carpeta.submit();">
                                <option value="0" <? if($filtro_ordenar == "0"){ echo "selected";} ?>>- Seleccione Orden </option>
                                <option value="C.titulo" <? if($filtro_ordenar == "C.titulo"){ echo "selected";} ?>>Titulo</option>
                                <option value="B.orden" <? if($filtro_ordenar == "B.orden"){ echo "selected";} ?>>N&deg; de Orden</option>
                                <option value="A.fecha_alta" <? if($filtro_ordenar == "A.fecha_alta"){ echo "selected";} ?>>Fecha de Alta</option>
                            </select></td>
                          </tr>
                          <tr>
                            <td align="right" class="detalle_medio">Registros por hoja: </td>
                            <td align="left"><select name="cant" class="detalle_medio" id="cant" style="width:160px;" onchange="validar_form_lista();">
                                <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?>>5</option>
                                <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?>>10</option>
                                <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?>>25</option>
                                <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                              </select></td>
                          </tr>
                          <tr>
                            <td align="left" class="detalle_medio"><input name="Button" type="button" class="detalle_medio_bold" value=" Aplicar cambios " onclick="javascript:aplicar_cambios();" /></td>
                            <td align="left">&nbsp;</td>
                          </tr>
                        </table>
                        <br />
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="ffddbc">
                            <td height="40" align="center" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold"><img src="../../imagen/cruz.png" width="16" height="16" /></td>
                            <td height="40" align="center" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">ID</td>
                            <td width="52" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Fecha</td>
                            <td width="35" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Orden</td>
                            <td width="323" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Titulo</td>
                            <td width="137" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Propiedades</td>
                            <td width="38" height="40" align="center" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                          </tr>
                          <? 
	
	$cont_producto = 0;
	$colores = array("#fff0e1","#FFE1C4");
	$cont_colores = 0;
	$hay_lista = false;
	
	/*$query_lista = "SELECT DISTINCT p.idproducto, C.orden, pi.titulo, p.foto, p.estado, p.restringido, p.seguro, p.fecha_alta
	FROM producto p
	INNER JOIN producto_carpeta C ON p.idproducto = C.idproducto
	LEFT JOIN producto_idioma_dato pi ON pi.idproducto = p.idproducto
	WHERE p.estado <> 3 AND pi.ididioma = 1 $sel_idcarpeta_bis 
	ORDER BY C.orden, pi.titulo
	LIMIT $puntero,$cantidad_registros";*/
	
	$query_lista = "SELECT DISTINCT A.idproducto, B.orden, C.titulo, A.fecha_alta, A.foto, A.estado, A.restringido, A.seguro
	FROM producto A
	LEFT JOIN producto_carpeta B ON A.idproducto = B.idproducto
	LEFT JOIN producto_idioma_dato C ON C.idproducto = A.idproducto
	$filtrado_sede_inner
	WHERE A.estado <> 3 AND C.ididioma = '$filtro_idioma' $filtrado_idioma $sel_idcarpeta_bis $filtrado_sede
	ORDER BY B.orden
	LIMIT $puntero,$cantidad_registros";
	
	$result_lista = mysql_query($query_lista);
	while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
		$hay_lista = true;
		
		
		if($accion == "lista_reordenar"){
			$query = "UPDATE producto SET orden=$cont_producto WHERE idproducto=$rs_lista[idproducto] LIMIT 1";
			mysql_query($query);
		};
?>
                          <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                            <td width="16" align="center" valign="middle" class="detalle_chico"><? if($rs_lista['seguro'] == 1){  ?>
                              <input name="checkbox_row[<?= $cont_producto ?>]" type="checkbox" id="checkbox_row[<?= $cont_producto ?>]" value="3" />
                              <? } ?></td>
                            <td width="17" align="center" valign="middle" class="detalle_chico"><?= "[".$rs_lista['idproducto']."]";?>
                              <input name="idproducto_row[<?= $cont_producto ?>]" type="hidden" id="idproducto_row[<?= $cont_producto ?>]" value="<?= $rs_lista['idproducto']; ?>" />
                            <a name="ancla_<?=$rs_lista['idproducto']?>" id="ancla_<?=$rs_lista['idproducto']?>"></a></td>
                            <td width="52" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_chico"><? $fecha = split("-",$rs_lista['fecha_alta']); echo $fecha[2]."/".$fecha[1]."/".substr($fecha[0],-2); ?></td>
                            <td width="35" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_chico"><input name="orden_row[<?= $count_fila ?>]" type="text" class="detalle_medio" id="orden_row[<?= $cont_producto ?>]" style="width:30px; height:16px; text-align:center" value="<?= $rs_lista['orden'] ?>" size="6" maxlength="3" /></td>
                            <td valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><span class="detalle_chico"><?=$rs_lista['orden'].". "?> </span><?=$rs_lista['titulo']?></td>
                            <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>"><? 
							if ($rs_lista['estado'] == '1') { 
								//estado 1 activo, 2 inactivo, 3 borrado
                            	echo "<a href=\"javascript:cambiar_estado(2,".$rs_lista['idproducto'].");\"><img src=\"../../imagen/b_habilitado.png\" alt=\"Habilitado\" width=\"15\" height=\"16\" border=\"0\" /></a>";
                            } else { 
                            	echo "<a href=\"javascript:cambiar_estado(1,".$rs_lista['idproducto'].");\"><img src=\"../../imagen/b_deshabilitado.png\" alt=\"Deshabilitado\" width=\"16\" height=\"15\" border=\"0\" /></a>";
                            } ?>
                              <? 
								   if($rs_lista['seguro'] == 1 && $usuario_perfil == 1){
								   		if($usuario_perfil == 1){
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(2,".$rs_lista['idproducto'].");\"><img src=\"../../imagen/trash_hab.png\"  alt=\"No Seguro (Permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}
								   }else{
								   		if($usuario_perfil == 1){
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(1,".$rs_lista['idproducto'].");\"><img src=\"../../imagen/trash_deshab.png\"  alt=\"Seguro (No se permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}
								   }
								  ?>
                              <? 
								   if($rs_lista['restringido'] == 1){ 
									  echo "<a href=\"javascript:cambiar_restringido(2,".$rs_lista['idproducto'].");\"><img src=\"../../imagen/s_rights.png\"  alt=\"Exclusivo solo para usuarios.\"  width=\"16\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_restringido(1,".$rs_lista['idproducto'].");\"><img src=\"../../imagen/s_rights_b.png\"  alt=\"Seccion de acesso público.\"  width=\"14\" height=\"14\" border=\"0\" /></a>";
								   }
								  ?>
                              <img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idiomas  ::
								  <?
								  $query_idioma = "SELECT B.titulo_idioma
								  FROM producto_idioma_dato A
								  INNER JOIN idioma B ON A.ididioma = B.ididioma
								  WHERE A.idproducto = $rs_lista[idproducto] AND A.estado = 1";
								  
								  $result_idioma = mysql_query($query_idioma);
								  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
								  	echo "&bull; ".$rs_idioma['titulo_idioma']."<br>";
								  }
								  ?>"/> <img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT C.titulo
								  FROM producto A
								  INNER JOIN producto_sede B ON A.idproducto = B.idproducto
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idproducto = '$rs_lista[idproducto]'
								  ORDER BY C.idsede";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" />
                      <?
						if ($rs_lista['foto']) {
							echo "&nbsp;<img src=\"../../imagen/eye_on.png\" alt=\"Tiene foto principal\" border=\"0\">";
						}else {
							echo "&nbsp;<img src=\"../../imagen/eye_off.png\" alt=\"No tiene foto principal\" border=\"0\">";
						};
					  ?>					  	  </td>
                            <td align="right" valign="middle" bgcolor="<?=$colores[$cont_colores]?>"><a href="producto_editar.php?idproducto=<?= $rs_lista['idproducto'] ?>&idcarpeta=<?= $idcarpeta ?>&forma=lista" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a>                              <? 
								   if($rs_lista['seguro'] == 1){ 
									  echo "&nbsp;<a href=\"javascript:confirm_cambiar_estado(3,".$rs_lista['idproducto'].");\"><img src=\"../../imagen/trash.png\" alt=\"Eliminar\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                          </tr>
                          <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
	$cont_producto++;
					
	} if($hay_lista == false){ ?>
                          <tr align="center" valign="middle" >
                            <td colspan="9" bgcolor="#fff0e1" class="detalle_medio_bold" height="50">No se han encontrado productos.</td>
                          </tr>
                          <? };	?>

                          <tr align="center" valign="middle" >
                            <td colspan="7" bgcolor="#FFFFFF" class="detalle_medio_bold" height="25">
                              <input name="cont_producto" type="hidden" id="cont_producto" value="<?=$cont_producto?>" />
                                                     <? 
						if($cantidad_total > $cantidad_registros){
							 echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']);
						} 
						?></td>
                          </tr>
                          <?
	////////////////////////////////////////////////
	////Final del Modulo Admin de lista filtrado////
	////////////////////////////////////////////////
?>
                    </table>
                      </form>
                    <? }; ?>
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