<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include ("../../0_mysql.php"); 

	//VARIABLES
	$idproducto = $_GET['idproducto'];
	$estado = $_POST['estado'];
	$accion = $_POST['accion'];
	
	//SI EL USUARIO ADMIN ES NIVEL 1
	if($_SESSION['idcusuario_perfil_log']=='1'){
		$usuario_perfil = 1;
	}else{
		$usuario_perfil = 2;
	}

	//VARIABLES DE CARPETAS
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];

	if($mod6_idcarpeta4){	
		$sel_idcarpeta_bis = " AND C.idcarpeta =".$mod6_idcarpeta4;
	}else{	
		if($mod6_idcarpeta3){
			$sel_idcarpeta_bis = " AND C.idcarpeta =".$mod6_idcarpeta3;
		}else{
			if($mod6_idcarpeta2){
				$sel_idcarpeta_bis = " AND C.idcarpeta =".$mod6_idcarpeta2;
			}else{
				if($mod6_idcarpeta == ''){			
					$sel_idcarpeta_bis = "";
				}else{
					if($mod6_idcarpeta){
						$sel_idcarpeta_bis = " AND C.idcarpeta =".$mod6_idcarpeta;
					}
				}
			}
		}
	}
	
	//FUNCION PAGINAR POR POST
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

	//VECTORES PARA MODFICAR DATOS
	$idproducto_row = $_POST['idproducto_row'];
	$marca_row = $_POST['marca_row'];
	$precio_row = $_POST['precio_row'];
	$iva_row = $_POST['iva_row'];
	$cantidad_registros = $_POST['cantidad_registros'];
	
	//APLICAR CAMBIOS
	if($accion == "aplicar_cambios"){
		
		for($i=0;$i<$cantidad_registros;$i++){
			
			$query_upd = "UPDATE producto
			SET idproducto_marca = '$marca_row[$i]'
			, precio = '$precio_row[$i]'
			, idca_iva = '$iva_row[$i]'
			WHERE idproducto = '$idproducto_row[$i]'
			LIMIT 1";
			mysql_query($query_upd);
			
		}
		
		$accion = "buscar";
	}
	
	//VARIABLES DE FILTRADO
	$cod_interno = $_POST['cod_interno'];
	$cod_fabricante = $_POST['cod_fabricante'];
	$marca = $_POST['marca'];
	$proveedor = $_POST['proveedor'];
	$precio_desde = $_POST['precio_desde'];
	$precio_hasta = $_POST['precio_hasta'];
	
	if($cod_interno != ""){
		$filtro .= " AND cod_interno = '$cod_interno' ";
	}
	if($cod_fabricante != ""){
		$filtro .= " AND cod_fabricante = '$cod_fabricante' ";
	}
	if($proveedor != ""){
		$filtro .= " AND idproducto_proveedor = '$proveedor' ";
	}
	if($marca != ""){
		$filtro .= " AND idproducto_marca = '$marca' ";
	}
	if($precio_desde != ""){
		$filtro .= " AND precio >= '$precio_desde' ";
	}
	if($precio_hasta != ""){
		$filtro .= " AND precio <= '$precio_hasta' ";
	}
	
	?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

	function esPrecio(campo){
	   var caracteresValidos = "0123456789.";
	   var esNumero = true;
	   var caracter;
	
	   for (i = 0; i < campo.length && esNumero == true; i++){
	   
		  caracter = campo.charAt(i); 
		  if (caracteresValidos.indexOf(caracter) == -1){
			 esNumero = false;
		  }
	   }
	   return esNumero;
	}

	function ir_pagina(pag){
		formulario = document.form_lista;
		formulario.pag.value = pag;
		formulario.accion.value = "buscar";
		formulario.action = "";
		formulario.target = "";
		formulario.submit();
	}

	function mod6_select_idcarpeta(nivel){
		formulario = document.form_lista;
		
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
		
		formulario.action = "";
		formulario.target = "";
		formulario.accion.value = "buscar";
		formulario.submit();
	}
		
	function validar_form_lista(){
		formulario = document.form_lista;
		flag = true;
		
		if(esPrecio(formulario.precio_desde.value) == false && esPrecio(formulario.precio_hasta.value) == false){
			alert("Por favor verifique que haya ingresado bien el precio.\nSi desea ingresar decimales debe hacerlo de esta manera, por ejemplo: 800.99");
			flag = false;
		}else{
			if(formulario.precio_desde.value != "" && formulario.precio_hasta.value != ""){
				if(formulario.precio_desde.value > formulario.precio_hasta.value){
					aux = formulario.precio_hasta.value;
					formulario.precio_hasta.value = formulario.precio_desde.value;
					formulario.precio_desde.value = aux;
				}
			}
		}
		
		if(flag == true){
			formulario.action = "";
			formulario.target = "";
			formulario.pag.value = "1";
			formulario.accion.value = "buscar";
			formulario.submit();
		}
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
	
	
	function aplicar_cambios(){
		formulario = document.form_lista;
		formulario.action = "";
		formulario.target = "";
		formulario.accion.value = "aplicar_cambios";
		formulario.submit();
	
	};
	
	function imprimir_precios(){
		formulario = document.form_lista;
		formulario.action = "producto_ver_lista_de_precios_imprimir.php";
		formulario.target = "_blank";
		formulario.submit();
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto - Ver Lista de Precios </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center">
				  <form action="#" method="post" name="form_lista" id="form_lista">
          			  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="#999999">
                            <td height="40" colspan="2" align="left" bgcolor="#d8f6ee" class="titulo_medio_bold">&nbsp; Buscar por Carpeta<span class="detalle_medio_bold">
                              <input name="accion" type="hidden" id="accion" />
                            <span class="detalle_medio">
                              <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                            <span class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                              <input name="lista_fil" type="hidden" id="lista_fil" value="1" />
                              </span><span class="detalle_chico" style="color:#FF0000">
                              <input name="lista_orden" type="hidden" id="lista_orden" value="1" />
                            </span></span></span></span></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td width="50%" align="left" valign="top" bgcolor="#eafcf7">
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                  <tr>
                                    <td align="left" class="detalle_medio_bold">Seleccione  carpeta:</td>
                                  </tr>
                                </table>
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                  <tr>
                                    <td width="90" align="left" valign="middle" class="detalle_medio">Carpeta 1&ordm; </td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta" class="detalle_medio" id="mod6_idcarpeta" onchange="mod6_select_idcarpeta('1')">
                                        <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
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
                                    <td align="left" valign="middle" class="detalle_medio">Carpeta 2&ordm;</td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta2" class="detalle_medio" id="mod6_idcarpeta2" onchange="mod6_select_idcarpeta('2');">
                                        <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta'  AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
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
                                    <td align="left" valign="middle" class="detalle_medio">Carpeta 3&ordm; </td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta3" class="detalle_medio" id="mod6_idcarpeta3" onchange="mod6_select_idcarpeta('3')">
                                        <option value="">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta3 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta2' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
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
                                    <td align="left" valign="middle" class="detalle_medio">Carpeta 4&ordm; </td>
                                    <td align="left" valign="middle" class="style2"><span class="style10">
                                      <select name="mod6_idcarpeta4" class="detalle_medio" id="mod6_idcarpeta4">
                                        <option value="">--- Seleccionar Carpeta ---</option>
                                        <?
	  $query_mod6_idcarpeta4 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta3' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
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
                              </table></td>
                            <td width="50%" align="left" valign="top" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td colspan="2" class="detalle_medio_bold">Filtrar seg&uacute;n: </td>
                              </tr>
                              <tr>
                                <td width="32%" align="left" valign="middle" class="detalle_medio">Cod. Interno: </td>
                                <td width="68%"><label>
                                  <input name="cod_interno" type="text" class="detalle_medio" id="cod_interno" value="<?= $cod_interno ?>" size="8" />
                                </label></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Cod. Fabricante </td>
                                <td><label>
                                  <input name="cod_fabricante" type="text" class="detalle_medio" id="cod_fabricante" value="<?= $cod_fabricante ?>" size="8" />
                                </label></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Proveedor:</td>
                                <td><span class="style10">
                                  <select name="proveedor" class="detalle_medio" id="proveedor" style="width:200px;" >
                                    <option value="">Seleccionar Proveedor</option>
                                    <?
										  $query_idproducto_proveedor = "SELECT idproducto_proveedor, titulo 
										  FROM producto_proveedor
										  WHERE estado = '1'
										  ORDER BY titulo";
										  $result_idproducto_proveedor = mysql_query($query_idproducto_proveedor);
										  while ($rs_idproducto_proveedor = mysql_fetch_assoc($result_idproducto_proveedor)){
										  
										  		if($rs_idproducto_proveedor['idproducto_proveedor'] == $_POST['proveedor']){
													$idproducto_proveedor_sel = "selected";
												}else{
													$idproducto_proveedor_sel = "";
												}
										?>
                                    <option <?= $idproducto_proveedor_sel ?> value="<?= $rs_idproducto_proveedor['idproducto_proveedor'] ?>" >
                                    <?= $rs_idproducto_proveedor['titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Marca:</td>
                                <td><span class="style10">
                                  <select name="marca" class="detalle_medio" id="marca"  style="width:200px;" >
                                    <option value="">Seleccionar Marca</option>
                                    <?
										  $query_idproducto_marca = "SELECT idproducto_marca, titulo 
										  FROM producto_marca
										  WHERE estado = '1'
										  ORDER BY titulo";
										  $result_idproducto_marca = mysql_query($query_idproducto_marca);
										  while ($rs_idproducto_marca = mysql_fetch_assoc($result_idproducto_marca)){
										  
										  		if($rs_idproducto_marca['idproducto_marca'] == $marca){
													$idproducto_marca_sel = "selected";
												}else{
													$idproducto_marca_sel = "";
												}
										?>
                                    <option <?= $idproducto_marca_sel ?> value="<?= $rs_idproducto_marca['idproducto_marca'] ?>" >
                                    <?= $rs_idproducto_marca['titulo'] ?>
                                    </option>
                                    <?  } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Precio desde:</td>
                                <td class="detalle_medio"><input name="precio_desde" type="text" class="detalle_medio" id="precio_desde" value="<?= $precio_desde ?>" size="8" /> 
                                 &nbsp; hasta &nbsp;
                                  <input name="precio_hasta" type="text" class="detalle_medio" id="precio_hasta" value="<?= $precio_hasta ?>" size="8" /></td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr bgcolor="#999999">
                            <td height="30" colspan="2" align="left" valign="middle" bgcolor="#D8F6EE"><input name="Submit222" type="button" class="detalle_medio_bold" value="&gt;&gt; Buscar " onclick="validar_form_lista();" /></td>
                          </tr>
                    </table>
						<? if($sel_idcarpeta_bis || $filtro){ ?>
                        <br />
                    <input name="Button" type="button" class="detalle_medio_bold" onclick="imprimir_precios();" value="&lt;  Version Imprimible  &gt;" />
                        <br />
                        <br />
                        
                        <table width="100%" height="35" border="0" cellpadding="5" cellspacing="0">
                          <tr>
                            <td align="right" valign="middle" bgcolor="#FFD3A8"><label>
                              <input name="Button" type="button" class="detalle_medio_bold" value=" Aplicar cambios " onclick="javascript:aplicar_cambios();"/>
                            </label></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr bgcolor="ffddbc">
                            <td width="4%" height="40" align="center" bgcolor="#ffddbc" class="detalle_medio_bold">ID</td>
                            <td width="28%" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Titulo</td>
                            <td width="12%" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Cod. Interno </td>
                            <td width="16%" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Cod. Fabricante </td>
                            <td width="20%" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Marca</td>
                            <td width="12%" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">Precio</td>
                            <td width="8%" height="40" align="left" valign="middle" bgcolor="#ffddbc" class="detalle_medio_bold">IVA</td>
                          </tr>
                          <? 
							
								$colores = array("#fff0e1","#FFE1C4");
								$cont_colores = 0;
								$hay_lista = false;
								
								$c = 0;
								
								$query_lista = "SELECT DISTINCT P.idproducto, D.titulo, P.cod_interno, P.cod_fabricante, P.idproducto_marca, P.precio, P.idca_iva
								FROM producto P
								INNER JOIN producto_carpeta C ON P.idproducto = C.idproducto
								INNER JOIN producto_idioma_dato D ON P.idproducto = D.idproducto
								WHERE P.estado = 1 AND D.ididioma = 1 $sel_idcarpeta_bis $filtro
								ORDER BY P.idproducto, C.orden ASC";
								$result_lista = mysql_query($query_lista);
								
								while ($rs_lista = mysql_fetch_assoc($result_lista))
								{ 
									$hay_lista = true;
						  ?>
                          <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                            <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_chico"><a name="<?= $rs_lista['idproducto']; ?>" id="<?= $rs_lista['idproducto']; ?>"></a>
                              <input name="idproducto_row[<?= $c ?>]" type="hidden" id="idproducto_row[<?= $c ?>]" value="<?= $rs_lista['idproducto']; ?>" />
                                  <?=$rs_lista['idproducto']?>                            </td>
                            <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['titulo']?></td>
                            <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['cod_interno']?></td>
                            <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['cod_fabricante']?></td>
                            <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><span class="style10">
                              <select name="marca_row[<?= $c ?>]" class="detalle_medio" id="marca_row[<?= $c ?>]"  style="width:130px;">
                                <option value="0">Seleccionar Marca</option>
                                <?
										  $query_idproducto_marca = "SELECT idproducto_marca, titulo 
										  FROM producto_marca
										  WHERE estado = '1'
										  ORDER BY titulo";
										  $result_idproducto_marca = mysql_query($query_idproducto_marca);
										  while ($rs_idproducto_marca = mysql_fetch_assoc($result_idproducto_marca)){
										  		if($rs_idproducto_marca['idproducto_marca']==$rs_lista['idproducto_marca']){
													$idproducto_marca_sel = "selected";
												}else{
													$idproducto_marca_sel = "";
												}
										?>
                                <option <?= $idproducto_marca_sel ?> value="<?= $rs_idproducto_marca['idproducto_marca'] ?>" >
                                <?= $rs_idproducto_marca['titulo'] ?>
                                </option>
                                <?  } ?>
                              </select>
                            </span></td>
                            <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><input name="precio_row[<?= $c ?>]" type="text" class="detalle_medio" id="precio_row[<?= $c ?>]" value="<?= $rs_lista['precio'] ?>" size="6" /></td>
                            <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>"><select name="iva_row[<?= $c ?>]" id="iva_row[<?= $c ?>]" class="detalle_medio">
                              <? 
							  $query_iva = "SELECT * FROM ca_iva WHERE estado = 1";
							  $result_iva = mysql_query($query_iva);
							  while($rs_iva = mysql_fetch_assoc($result_iva)){
							  ?>
                              <option value="<?= $rs_iva['idca_iva'] ?>" <? if($rs_iva['idca_iva'] == $rs_lista['idca_iva']){ echo "selected"; } ?>>
                                <?= $rs_iva['titulo_iva'] ?>
                              </option>
                              <? } ?>
                            </select></td>
                          </tr>
                          <?
						  	//AUMENTO CONTADOR
							$c++;
							
						  	//CAMBIA COLOR DE LINEA
							if($cont_colores == 0){
								$cont_colores = 1;
							}else{
								$cont_colores = 0;
							};
											
							} 
							
							//SI NO HAY PRODUCTOS
							if($hay_lista == false){ 
						  ?>
                          <tr align="center" valign="middle">
                            <td  height="50" colspan="9" bgcolor="#fff0e1" class="detalle_medio_bold">No se han encontrado productos.</td>
                          </tr>
                          <? };//FIN WHILE ?>
                    </table>
                        <input name="query" type="hidden" id="query" value="<?= $query_lista ?>" />
                        <input name="cantidad_registros" type="hidden" id="cantidad_registros" value="<?= $c ?>" />
                        <? }; //FIN SI HAY BUSQUEDA ?>
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