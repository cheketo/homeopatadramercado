<? 
	include_once("0_include/0_mysql.php"); 
	
	//CARGO PARÁMETROS DE CARPETA
	$query_par = "SELECT *
	FROM carpeta_parametro
	WHERE idcarpeta_parametro = 1";
	$rs_parametro_carpeta = mysql_fetch_assoc(mysql_query($query_par));
	
	//FUNCION PAGINAR 
	function paginar($actual, $total, $por_pagina, $enlace, $tipo) {
		$total_paginas = ceil($total/$por_pagina);
		$anterior = $actual - 1;
		$posterior = $actual + 1;
		if ($actual>1)
			$texto = "<a href=\"javascript:ir_pagina('$anterior','$tipo');\">&laquo;</a> ";
		else
			$texto = "<b>&laquo;</b> ";
		for ($i=1; $i<$actual; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i','$tipo');\">$i</a> ";
			$texto .= "<b>$actual</b> ";
		for ($i=$actual+1; $i<=$total_paginas; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i','$tipo');\">$i</a> ";
		if ($actual<$total_paginas)
			$texto .= "<a href=\"javascript:ir_pagina('$posterior','$tipo');\">&raquo;</a>";
		else
			$texto .= "<b>&raquo;</b>";
		return $texto;
	}
	
	//PAGINACION DE PRODUCTOS
	$cantidad_registros_prod = $_POST['cant_prod'];
	if(!$cantidad_registros_prod){
		$cantidad_registros_prod = $rs_parametro_carpeta['pag_producto'];
	}
	$pag_producto = $_POST['pag_producto'];
	if(!$pag_producto){
		$pag_producto = 1;
	}
	$puntero_prod = ($pag_producto-1) * $cantidad_registros_prod;
	
	//PAGINACION DE SECCION
	$cantidad_registros_sec = $_POST['cant_sec'];
	if(!$cantidad_registros_sec){
		$cantidad_registros_sec = $rs_parametro_carpeta['pag_seccion'];
	}
	$pag_seccion = $_POST['pag_seccion'];
	if(!$pag_seccion){
		$pag_seccion = 1;
	}
	$puntero_sec = ($pag_seccion-1) * $cantidad_registros_sec;

	// XAJAX
	function AgregarProducto($idproducto_post, $cantidad_post){
	
		$query_prod = "SELECT A.precio, B.alicuota 
		FROM producto A 
		INNER JOIN ca_iva B ON A.idca_iva = B.idca_iva
		WHERE A.idproducto = '$idproducto_post'";
		$rs_prod = mysql_fetch_assoc(mysql_query($query_prod));
		
		$precio_unitario = $rs_prod['precio'] + ($rs_prod['precio'] * ($rs_prod['alicuota']/100));
		
		if($_SESSION['ca_contador'] == ""){
			$_SESSION['ca_contador'] = 1;
		}
		$contador_carrito = $_SESSION['ca_contador'];
		
		//BUSCO SI YA ESTA EL PRODUCTO EN EL CARRITO
		$c = 1; 
		$existe = false;
		while($c <= $contador_carrito){
			if($_SESSION['ca_idproducto'][$c] == $idproducto_post){
				$existe = true;
				$id_carrito = $c;
			}
			$c++;
		}
		
		//SI NO EXISTE TODAVIA EN EL CARRITO
		if($existe == false){
			$_SESSION['ca_idproducto'][$contador_carrito] = $idproducto_post;
			$_SESSION['ca_cantidad'][$contador_carrito] = $cantidad_post;
			$_SESSION['ca_precio_unitario'][$contador_carrito] = $precio_unitario;
			$_SESSION['ca_contador']++;
		}else{ //SI YA EXISTE EN EL CARRITO
			$_SESSION['ca_cantidad'][$id_carrito] = $_SESSION['ca_cantidad'][$id_carrito] + $cantidad_post;
		}
		
		//CALCULO EL TOTAL
		$total = 0;
		$i = 0;
		while($i <= $contador_carrito){
			$total += $_SESSION['ca_precio_unitario'][$i] * $_SESSION['ca_cantidad'][$i];
			$i++;
		}
		
		//GUARDO EL TOTAL EN UNA VARIABLE DE SESION
		$_SESSION['ca_total'] = $total;
		
		//INICIO AJAX RESPONSE
		$response = new xajaxResponse();
		$response->call("ActualizarValores", round($total, 2));
		return $response;
		
	}
	
	
	$xajax = new xajax();
	//$xajax->setFlag("debug", true);
	$xajax->registerFunction("AgregarProducto");
	$xajax->processRequest();
	//FIN XAJAX


	// DATOS DE LA SECCION
	//idcarpeta, idseccion e idproducto vienen desde el 0_mysql

	// DATOS DE LA CARPETA	
	$query_carpeta = "SELECT 
	  A.idcarpeta
	, B.nombre
	, B.breve_descripcion
	, B.contenido
	, A.estado
	, A.idcarpeta_padre
	, A.campo_orden
	, A.ordenamiento
	, A.foto
	, A.orden
	FROM carpeta A
	LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	LEFT JOIN carpeta_sede C ON C.idcarpeta = A.idcarpeta
	WHERE A.idcarpeta = '$idcarpeta' AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]' ";
	$rs_carpeta = mysql_fetch_assoc(mysql_query($query_carpeta));
	
	//Pone un titulo opcional al sitio en los meta tag
	$meta_tag_title_opcional = $rs_carpeta['nombre'];
	
	//Pone una descripcion opcional al sitio en los meta tag
	if($rs_carpeta['breve_descripcion']){
		$meta_tag_description_opcional = $rs_carpeta['breve_descripcion'];
	}
	
	
	//MOTIVOS POR LOS QUE NO SE PUEDE MOSTRAR LA CARPETA
	if(!$idcarpeta){
		echo "ERROR: Carpeta no definida.";
		exit;
	}else if($rs_carpeta['estado'] != 1){
		echo "ERROR: Carpeta no habilitada para pública visualización.";
		exit;
	}
	
	//TOTAL DE PRODUCTOS:
	$fecha_hoy = date("Y-m-d");
	$query_producto = "SELECT A.idproducto, B.titulo, B.copete, A.foto, A.precio, A.idca_iva, A.compra
	FROM producto A
	INNER JOIN producto_idioma_dato B ON B.idproducto = A.idproducto
	INNER JOIN producto_sede C ON C.idproducto = A.idproducto
	INNER JOIN producto_carpeta D ON D.idproducto = A.idproducto
	WHERE D.idcarpeta = '$idcarpeta' AND A.estado = 1 AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]'  AND A.fecha_alta <= '$fecha_hoy'";
	$cantidad_total_prod = mysql_num_rows(mysql_query($query_producto));
	
	//TOTAL DE SECCION:
	$fecha_hoy = date("Y-m-d");
	$query_seccion = "SELECT A.idseccion, B.titulo, B.copete, A.foto
		FROM seccion A
		INNER JOIN seccion_idioma_dato B ON B.idseccion = A.idseccion
		INNER JOIN seccion_sede C ON C.idseccion = A.idseccion
		INNER JOIN seccion_carpeta D ON D.idseccion = A.idseccion
		WHERE D.idcarpeta = '$idcarpeta' AND A.estado = 1 AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]'  AND (A.fecha_baja >= '$fecha_hoy' OR A.fecha_baja = '0000-00-00') AND A.fecha_alta <= '$fecha_hoy' ";
	$cantidad_total_sec = mysql_num_rows(mysql_query($query_seccion));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	/*echo "Contador: ".$_SESSION['ca_contador']."<br>";
	$total = 0;
	$i = 1;
	while($i <= $_SESSION['ca_contador']){
			$total += $_SESSION['ca_precio_unitario'][$i] * $_SESSION['ca_cantidad'][$i];
			echo "Subtotal: ".$total." = precio: ".$_SESSION['ca_precio_unitario'][$i]." * cantidad: ".$_SESSION['ca_cantidad'][$i]." <br>";
			$i++;
	}*/
?>



<head>
<? $xajax->printJavascript("js/xajax/"); ?>

<? include_once("0_include/0_head.php"); ?>

<script type="text/javascript" src="js/overlay.js"></script>
<script type="text/javascript" src="js/multibox.js"></script>
<link href="css/0_multibox.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

	function ActualizarValores(valor){
		xajax.$('carrito_total').innerHTML = "Total: <b>$" + valor + "</b>";
	}
	
	function AddItem(id, cant){
		xajax_AgregarProducto(id,cant);
	}
	
	function ir_pagina(pag, tipo){
		formulario = document.form;
		if(tipo == 1){
			formulario.pag_producto.value = pag;
		}else if(tipo == 2){
			formulario.pag_seccion.value = pag;
		}
		formulario.submit();
	}
	
	var box3 = {};
	window.addEvent('domready', function(){
		box3 = new MultiBox('consulta', {descClassName: 'multiBoxDesc_consulta', useOverlay: true});
	});

</script>

</head>
<body>
<div id="header"><? include_once("0_include/0_top.php"); ?></div>
<div id="cuerpo">
  	<div id="contenido" align="center">
  	  <form action="" name="form" id="form" enctype="multipart/form-data" method="post" style="margin:0px;" >
      
        <div id="titulo-seccion" style="text-align:left; margin-top:20px;">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="700" height="56">
              <param name="movie" value="skin/index/swf/titulo.swf" />
              <param name="quality" value="high" />
              <param name="FlashVars" value="titulo=<?= $rs_carpeta['nombre'] ?>" />
              <param name="wmode" value="transparent" />
              <embed src="skin/index/swf/titulo.swf" FlashVars="titulo=<?= $rs_carpeta['nombre'] ?>" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="700" height="56"></embed>
            </object>
        </div>
        
        <div id="arbol-carpetas" style="padding-left:30px; margin-top:5px; margin-bottom:10px; text-align:left; width:50%; float:left;"><span class="registro_B">
          <?
		  
		/////////////////////////////////////////////////////
		//sistema de arbol horizontal o barra de navegacion//
		/////////////////////////////////////////////////////

			if($rs_carpeta['nombre'] == ""){
				$ruta = "";
			}else{
				$ruta = '<span class="Arial_11px_grisoscuro"><strong>'.$rs_carpeta['nombre'].'</strong></span>';
			}
			
			//AVERIGUO RUTA COMPLETA - NIVEL 1
			$query_1 = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta
			FROM carpeta A
			INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
			WHERE A.idcarpeta = '$rs_carpeta[idcarpeta_padre]' AND B.ididioma = '$_SESSION[ididioma_session]' 
			LIMIT 1";
			$result_query_1 = mysql_query($query_1);
			while($rs_query_1 = mysql_fetch_assoc($result_query_1)){ 
				
				$ruta = '<a href="'.variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_query_1['idcarpeta']).'"><span class="Arial_11px_grisoscuro">'.$rs_query_1['nombre']."</span></a> &raquo; ".$ruta;
				
				//NIVEL 2
				$query_2 = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta
				FROM carpeta A
				INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
				WHERE A.idcarpeta = '$rs_query_1[idcarpeta_padre]' AND B.ididioma = '$_SESSION[ididioma_session]'
				LIMIT 1";
				$result_query_2 = mysql_query($query_2);
				while($rs_query_2 = mysql_fetch_assoc($result_query_2)){ 
				
					$ruta = '<a href="'.variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_query_2['idcarpeta']).'"><span class="Arial_11px_grisoscuro">'.$rs_query_2['nombre'].'</span></a> &raquo; '.$ruta;
					
					//NIVEL 3
					$query_3 = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta
					FROM carpeta A
					INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
					WHERE A.idcarpeta = '$rs_query_2[idcarpeta_padre]' AND B.ididioma = '$_SESSION[ididioma_session]'
					LIMIT 1";
					$result_query_3 = mysql_query($query_3);
					while($rs_query_3 = mysql_fetch_assoc($result_query_3)){ 
					
						$ruta = '<a href="'.variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_query_3['idcarpeta']).'"><span class="Arial_11px_grisoscuro">'.$rs_query_3['nombre']."</span></a> &raquo; ".$ruta;
					
					}
				
				}
				
				
			}//FIN AVERIGUO RUTA COMPLETA
			
			//IMPRIMO RUTA
			echo $ruta;

		?>
        </span></div>
        
        <div id="separador" style="clear:both; height:1px; border-bottom:1px dotted #999999;"></div>
      
    	<table width="732" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
      
	
      <tr>	  
        <td align="left" valign="top"><? 
		///Carpetas

		//echo $rs_carpeta['campo_orden'];
		if($rs_carpeta['campo_orden'] == "orden"){
			$carpeta_orden = "A.orden ";
		}
		if($rs_carpeta['campo_orden'] == "titulo"){
			$carpeta_orden = "B.nombre ";
		}
		if($rs_carpeta['campo_orden'] == "fecha_alta"){
			$carpeta_orden = "A.idcarpeta ";//se ordena por id porque no existe la fecha de alta
		}
		
		$query_subcarpeta = "SELECT 
		  A.idcarpeta
		, A.foto
		, B.nombre
		, B.breve_descripcion
		FROM carpeta A
		INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
		INNER JOIN carpeta_sede C ON C.idcarpeta = A.idcarpeta
		WHERE A.idcarpeta_padre = '$idcarpeta' AND A.estado = 1 AND B.estado = 1 AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]' 
		ORDER BY $carpeta_orden $rs_carpeta[ordenamiento]";
		$result_subcarpeta = mysql_query($query_subcarpeta);
		$num_rows_subcarpeta = mysql_num_rows($result_subcarpeta);
		
		if($num_rows_subcarpeta > 0){ 
?>
          <br />
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <? 
					// columnas de subcarpeta
					if($idcarpeta == 1){ 
						$columnas_subcarpeta = 1; 
					}else{
						$columnas_subcarpeta = 2;
					}
					
					$cont_subcarpeta = 1;
					 while($rs_subcarpeta = mysql_fetch_assoc($result_subcarpeta)){
							
			  ?>
              <td width="<?= ceil(100/$columnas_subcarpeta) ?>%"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="margin-bottom:10px;">
                  <tr>
                    <? if($rs_subcarpeta['foto'] != ''){ ?>
                    <td width="5%" align="left" valign="top">
					
					<div class="marcoFoto"><?
						$imagen_carpeta =& new obj0001('0','imagen/carpeta/chica/',$rs_subcarpeta['foto'],'','','','','carpeta_ver.php?ididioma='.$_SESSION['ididioma_session'].'&idsede='.$_SESSION['idsede_session'].'&idcarpeta='.$rs_subcarpeta['idcarpeta'],'','titulo='.$rs_subcarpeta['nombre'],'wmode=Opaque',''); 
					?></div>					</td>
					<? } ?>
                    <td align="center" valign="middle"><table width="100%" border="0" cellpadding="4" cellspacing="0">
                      <tr>
                        <td width="3%" align="left" valign="top" class="titulo_b_chico">&nbsp;</td>
                        <td width="97%" align="left" valign="top" class="tituloCarpeta">
                        <a href="carpeta_ver.php?ididioma=<?= $_SESSION['ididioma_session'] ?>&idsede=<?= $_SESSION['idsede_session'] ?>&idcarpeta=<?= $rs_subcarpeta['idcarpeta'] ?>">
                        <span class="tituloCarpeta"><?= $rs_subcarpeta['nombre'] ?></span></a></td>
                      </tr>
					  <? if($rs_subcarpeta['breve_descripcion']!=''){ ?>
                      <tr>
                        <td align="left" valign="top" class="copete_a">&nbsp;</td>
                        <td align="left" valign="top" class="copete_a"><div align="justify" class="copeteCarpeta">
                            <? 
							$mod_resumen_contenido = stripslashes($rs_subcarpeta['breve_descripcion']);//contenido que va a tener el campo
							$caracteres_max = 270; //cantidad de caracteres maximos
							
							$caracteres = strlen($mod_resumen_contenido);	//cuenta los caracteres que posee el texto a mostrar		
							//verifica si los caracteres a introducir superan a los caracteres maximos
							if($caracteres < $caracteres_max){
								echo $mod_resumen_contenido ;			
							}else{				
								echo str_pad($newstr,$caracteres_max,$mod_resumen_contenido) . " ..."; 
							}	

						?>
                        </div></td>
                      </tr>
					  <? } ?>
                      <tr>
                        <td align="left" valign="top" class="copete_a">&nbsp;</td>
                        <td height="30" align="left" valign="middle" >
                        
                        <a href="<?= variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_subcarpeta['idcarpeta']) ?>"><div class="readMore"><?= $lbl_Vermas ?></div></a>
                        
                        <?
						if($idcarpeta == 1){
						
								$querySubCarpetas02 = "SELECT B.nombre, B.idcarpeta 
								FROM carpeta A
								INNER JOIN carpeta_idioma_dato B ON A.idcarpeta = B.idcarpeta
								WHERE A.idcarpeta_padre = '$rs_subcarpeta[idcarpeta]' AND B.ididioma = '$_SESSION[ididioma_session]' AND A.estado = 1 AND B.estado = 1 ";
								$resultSubCarpetas02 = mysql_query($querySubCarpetas02);
								while($rs_subcarpetas02 = mysql_fetch_assoc($resultSubCarpetas02)){
						?>
                        <a href="<?= variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_subcarpetas02['idcarpeta']) ?>"><div class="subCarpeta"><?= $rs_subcarpetas02['nombre'] ?></div></a>
                        	<? } ?>
                        <? } ?>                        </td>
                      </tr>
                    </table></td>
                  </tr>
              </table></td>
              <?
			  			if($cont_subcarpeta == $columnas_subcarpeta){ 
							echo "</tr><tr>";
							$cont_subcarpeta = 1;
						}else{
							$cont_subcarpeta++;
						}
								 
					}// WHILE SUBCARPETAS 
			  ?>
            </tr>
          </table>
          <? } //SI HAY RESULTADOS ?>
          
		 
		  <? if( ($rs_carpeta['breve_descripcion']!='' && $rs_parametro_carpeta['estado_breve_descripcion'] == "1") || ($rs_carpeta['contenido']!='' && $rs_parametro_carpeta['estado_contenido'] == "1") ){ // || $rs_carpeta['foto']!='' ?>
          <table width="100%" border="0" cellpadding="0" cellspacing="10" bgcolor="#DFFFFF" style="border-bottom:1px dotted #0099CC; border-top:1px dotted #0099CC; margin-bottom:10px; margin-top:15px;">
            <tr>
              <td align="left"><? if($rs_carpeta['foto']!=''){ ?>
                  <div class="marcoFoto" style=" overflow:auto; float:left; padding:8px;">
                    <?
                                    $imagen_carpeta =& new obj0001('0','imagen/carpeta/mediana/',$rs_carpeta['foto'],'','','','','','','titulo='.$rs_carpeta['nombre'],'wmode=Opaque',''); 
                                ?>
                  </div>
                <? } ?>
                  <div align="justify">
                    <? if($rs_carpeta['breve_descripcion']!='' && $rs_parametro_carpeta['estado_breve_descripcion'] == "1"){ ?>
                    <span class="copeteCarpeta" style="margin-top:10px;">
                      <?= stripslashes($rs_carpeta['breve_descripcion']) ?>
                    </span> <br />
                    <? } ?>
                    <? if($rs_carpeta['contenido']!='' && $rs_parametro_carpeta['estado_contenido'] == "1"){ ?>
                    <span class="copeteCarpeta" style="margin-top:10px;">
                      <?= html_entity_decode($rs_carpeta['contenido'], ENT_QUOTES); ?>
                    </span>
                    <? } ?>
                </div></td>
            </tr>
          </table>
          <? } ?>
<? 
		//Secciones
		if($rs_carpeta['campo_orden'] == "orden"){
			$seccion_orden = "D.".$rs_carpeta['campo_orden'];
		}
		if($rs_carpeta['campo_orden'] == "titulo"){
			$seccion_orden = "B.".$rs_carpeta['campo_orden'];
		}
		if($rs_carpeta['campo_orden'] == "fecha_alta"){
			$seccion_orden = "A.".$rs_carpeta['campo_orden'];
		}
		
		$cont=1;
		$fecha_hoy = date("Y-m-d");
		$query_seccion = "SELECT A.idseccion, B.titulo, B.copete, A.foto, A.precio, A.esnuevo
		FROM seccion A
		INNER JOIN seccion_idioma_dato B ON B.idseccion = A.idseccion
		INNER JOIN seccion_sede C ON C.idseccion = A.idseccion
		INNER JOIN seccion_carpeta D ON D.idseccion = A.idseccion
		WHERE B.estado = 1 AND D.idcarpeta = '$idcarpeta' AND A.estado = 1 AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]'  AND (A.fecha_baja >= '$fecha_hoy' OR A.fecha_baja = '0000-00-00') AND A.fecha_alta <= '$fecha_hoy'
		ORDER BY $seccion_orden $rs_carpeta[ordenamiento]
		LIMIT $puntero_sec,$cantidad_registros_sec";
		$result_seccion = mysql_query($query_seccion);
		$num_rows_seccion = mysql_num_rows($result_seccion);
		
		if($num_rows_seccion > 0){ 
		 
		
		  ?>
          <br />
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <?			
				while($rs_seccion = mysql_fetch_assoc($result_seccion)){					
			  ?>
			  <tr>
              <td align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                <tr>
                  <? if($rs_seccion['foto'] != ''){ ?>
                  <td width="5%" align="left" valign="top">
                  
                      <div class="marcoFoto"><?
                         $imagen_categoria =& new obj0001('0','imagen/seccion/chica/',$rs_seccion['foto'],'','','','',variable_ididioma_idsede('seccion_detalle.php?idseccion='.$rs_seccion['idseccion'].'&idcarpeta='.$idcarpeta),'','precio='.$rs_seccion['precio'].'&esNuevo='.$rs_seccion['esnuevo'],'Opaque',''); 
                      ?></div></td>   
                  <? } ?>
                  <td width="88%" colspan="2" align="right" valign="top" >
                  	<table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="3%" align="left" valign="top">&nbsp;</td>
                        <td width="97%" align="left" valign="top"><a href="<?= variable_ididioma_idsede('seccion_detalle.php?idseccion='.$rs_seccion['idseccion'].'&idcarpeta='.$idcarpeta) ?>" >

                          <span style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#069dc5;"><?= $rs_seccion['titulo'] ?></span>

                        </a></td>
                      </tr>
                      <? if($rs_seccion['copete']!=''){ ?>
                      <tr>
                        <td>&nbsp;</td>
                        <td align="left" valign="top" ><div align="left" class="copeteCarpeta">
                          <? 
							echo stripslashes($rs_seccion['copete']);
					/*
							$mod_resumen_contenido = $rs_seccion['copete'];//contenido que va a tener el campo
							$caracteres_max = 300; //cantidad de caracteres maximos
							
							$caracteres = strlen($mod_resumen_contenido);	//cuenta los caracteres que posee el texto a mostrar		
							//verifica si los caracteres a introducir superan a los caracteres maximos
							if($caracteres < $caracteres_max){
								echo $mod_resumen_contenido ;			
							}else{				
								echo str_pad($newstr,$caracteres_max,$mod_resumen_contenido) . " ..."; 
							}	
					*/

						?>
                        </div></td>
                      </tr>
                      <? } ?>
                      <tr>
                        <td>&nbsp;</td>
                        <td height="30" align="left" valign="middle" ><a href="<?= variable_ididioma_idsede('seccion_detalle.php?idseccion='.$rs_seccion['idseccion'].'&idcarpeta='.$idcarpeta) ?>"><span class="readMore" style="background-color:#069fc8; "><?= $lbl_Vermas ?></span></a></td>
                      </tr>
                  </table></td>
                </tr>
              </table></td></tr>
              <?		 
						
				}// WHILE de secciones 
		?>
          </table>
          <? 
			 }// FIN Si hay secciones
		 ?>
          <? if($cantidad_total_sec > $cantidad_registros_sec){ ?>
          <table width="100%" border="0" cellpadding="6" cellspacing="0" class="tabla_producto_carrito">
            <tr>
              <td align="center" bgcolor="#F5F5F5" class="carpeta_producto_titulo_01"><span class="detalle_medio_bold"> <span class="detalle_medio">
                <input name="pag_seccion" type="hidden" id="pag_seccion" value="<?= $_POST['pag_seccion'] ?>" />
                </span>
                    <? 
				
					echo paginar($pag_seccion, $cantidad_total_sec, $cantidad_registros_sec, $_SERVER['PHP_SELF'], 2);
				
			  ?>
              </span></td>
            </tr>
          </table>
          <br />
          <? } ?>
          <? //IN_PLANO
		  
			//productos
			
			if($rs_carpeta['campo_orden'] == "orden"){
				$prod_orden = "D.".$rs_carpeta['campo_orden'];
			}
			if($rs_carpeta['campo_orden'] == "titulo"){
				$prod_orden = "B.".$rs_carpeta['campo_orden'];
			}
			if($rs_carpeta['campo_orden'] == "fecha_alta"){
				$prod_orden = "A.".$rs_carpeta['campo_orden'];
			}
			
			$fecha_hoy = date("Y-m-d");
			$query_producto = "SELECT A.idproducto, B.titulo, B.copete, A.foto, A.precio, A.idca_iva, A.compra
			FROM producto A
			INNER JOIN producto_idioma_dato B ON B.idproducto = A.idproducto
			INNER JOIN producto_sede C ON C.idproducto = A.idproducto
			INNER JOIN producto_carpeta D ON D.idproducto = A.idproducto
			WHERE D.idcarpeta = '$idcarpeta' AND A.estado = 1 AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]'  AND A.fecha_alta <= '$fecha_hoy'
			ORDER BY $prod_orden $rs_carpeta[ordenamiento]
			LIMIT $puntero_prod,$cantidad_registros_prod";
			$result_producto = mysql_query($query_producto);
			$num_rows_producto = mysql_num_rows($result_producto);
			
			if($num_rows_producto > 0){ 
		
		?>
          <table width="210" border="0" cellpadding="0" cellspacing="0"  >
            <tr valign="top">
              <? 
					$columnas_producto = 3;// columnas de producto
					$cont_producto = 1;
					while($rs_producto = mysql_fetch_assoc($result_producto)){
							
			  ?>
              <td align="left" valign="top" class="ejemplo_12px"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#999999">
                  <tr>
                    <td align="center" valign="middle"><table width="213" height="150" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="left" valign="bottom">
						  <? if($rs_producto['foto']){ ?><table width="20" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="1"><img src="skin/index/imagen/0_marco/cuadrio_izq_arriba.jpg"  /></td>
                              <td background="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg"  /></td>
                              <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_arriba.jpg"  /></td>
                            </tr>
                            <tr>
                              <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"  /></td>
                              <td valign="top"><? $imagen_producto =& new obj0001('0','imagen/producto/chica/',$rs_producto['foto'],'180','120','','',variable_ididioma_idsede('producto_detalle.php?idproducto='.$rs_producto['idproducto'].'&idcarpeta='.$idcarpeta),'','titulo='.$rs_producto['titulo'],'wmode=Opaque',''); ?></td>
                              <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_der.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_der.jpg"  /></td>
                            </tr>
                            <tr>
                              <td width="1"><img src="skin/index/imagen/0_marco/cuadro_izq_abajo.jpg"  /></td>
                              <td background="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg"><img src="imagen/borde_bottom.jpg" width="1" height="13" /><img src="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg" /></td>
                              <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_abajo.jpg" /></td>
                            </tr>
                          </table><? } ?>
                          <div class="lightboxDesc image<?= $foto_extra_cont ?>"></div></td>
                        </tr>
                    </table></td>
                  </tr>
                  <? if($rs_producto['titulo'] != ''){ ?>
                  <tr>
                    <td align="center" class="detalle_inmueble" ><table width="100%" border="0" cellpadding="3" cellspacing="0">
                        <tr>
                          <td height="28" align="left" class="carpeta_producto_titulo_01"><a href="<?= variable_ididioma_idsede('producto_detalle.php?idproducto='.$rs_producto['idproducto'].'&idcarpeta='.$idcarpeta) ?>"></a>
                            <div  style="margin-left:5px; height:30px; overflow:hidden;"><a href="<?= variable_ididioma_idsede('producto_detalle.php?idproducto='.$rs_producto['idproducto'].'&idcarpeta='.$idcarpeta) ?>">
                             <span class="carpeta_producto_titulo_01"> <?= $rs_producto['titulo'] ?></span>
                            </a></div>                          </td>
                        </tr>
                        <tr>
                          <td align="left" class="carpeta_producto_titulo_01"><span class="carpeta_producto_precio_01">&nbsp; Precio: <strong>
                          <? 
						  if($rs_producto['precio'] > 0){
						  	echo "$".$rs_producto['precio'];
							
							$query_iva = "SELECT titulo_iva FROM ca_iva WHERE idca_iva = '$rs_producto[idca_iva]' AND estado = 1";
							$rs_iva = mysql_fetch_assoc(mysql_query($query_iva)); 
							  
							if($rs_iva['titulo_iva']){
								echo " ".$rs_iva['titulo_iva'];
							}
						  }else{
						  	echo "-";
						  }
						  ?>
						  </strong></span></td>
                        </tr>
						</table>
						<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_producto">
                        <tr>
                          <td height="15" align="left" class="carpeta_producto_titulo_01"><span class="registro_B">
						  <? if($rs_dato_sitio['ca_carrito_usar'] == 1){ ?>
						  <? if($rs_producto['compra'] ){
						  		if($rs_producto['precio'] > 0){
						  ?>
						  &nbsp; <a href="javascript:AddItem(<?= $rs_producto['idproducto'] ?>,1);"><img src="imagen/botones/b-comprarazul.gif" width="52" height="8" border="0" /></a>
						  <? 	}else{ //MOSTRAR BOTON DE COMPRA DESHABILITADO ?>
						  &nbsp; <img src="imagen/botones/b-comprar_off.jpg" width="52" height="8" />
						  <?
						  		}
						  	  } //FIN SI PERMITE COMPRA
						  ?><br />
						  <? } ?>
                          &nbsp; <a href="<?= variable_ididioma_idsede('producto_detalle.php?idproducto='.$rs_producto['idproducto'].'&idcarpeta='.$idcarpeta) ?>"><img src="imagen/botones/b-info.jpg" width="34" height="8" border="0" /></a></span></td>
                        </tr>
                      </table>                    </td>
                  </tr>
                  <? } ?>
              </table></td>
              <?
			  
					if($cont_producto == 3){ 
						echo "</tr><tr>";
						$cont_producto = 1;
					}else{
						$cont_producto++;
					};
							 
				}; //FIN WHILE 
											 
			?>
            </tr>
          </table>
        <? } ?>
		<? if($cantidad_total_prod > $cantidad_registros_prod){ ?>
        <table width="100%" border="0" cellpadding="6" cellspacing="0" class="tabla_producto_carrito">
          <tr>
            <td align="center" bgcolor="#F5F5F5" class="carpeta_producto_titulo_01"><span class="detalle_medio_bold">
              <span class="detalle_medio">
              <input name="pag_producto" type="hidden" id="pag_producto" value="<?= $_POST['pag_producto'] ?>" />
              </span>
              <? 
				
					echo paginar($pag_producto, $cantidad_total_prod, $cantidad_registros_prod, $_SERVER['PHP_SELF'], 1);
				
			  ?>
            </span></td>
          </tr>
        </table>
		<? } ?></td>		
        </tr>	
    </table>
	</form>
 	</div>
    <div id="footer"><? include("0_include/0_pie.php"); ?></div>
</div>
<? include("0_include/0_googleanalytics.php"); ?>
</body>
</html>