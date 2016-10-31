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
			$texto .= "<b>[ $actual ]</b> ";
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
		//$cantidad_registros_sec = $rs_parametro_carpeta['pag_seccion'];
		$cantidad_registros_sec = 6;
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

</script>
<? include_once("0_include/0_head.php"); ?>
</head>
<body>
<div id="header"><? include_once("0_include/0_top.php"); ?></div>
<div id="cuerpo">
  	<div id="contenido" align="center">
	<form action="" name="form" id="form" enctype="multipart/form-data" method="post" style="margin:0px;" >
    	<table width="732" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
      <tr>	  
        <td width="180" align="left" valign="top">
        
        	<? include("0_include/0_barra_blog.php"); ?>
            
          </td>		
        <td width="20" align="left" valign="top">&nbsp;</td>
        <td width="532" align="left" valign="top"><a name="up" ></a><? 
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
		
		
		$fecha_hoy = date("Y-m-d");
		$query_seccion = "SELECT A.idseccion, B.titulo, B.copete, A.foto, A.precio, A.esnuevo, A.fecha_alta, B.keywords
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
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <?			
				while($rs_seccion = mysql_fetch_assoc($result_seccion)){					
			  ?>
            <tr>
              <td align="center" valign="top">
              
			  	<div style="padding:7px; background-color:#F3F3F3; margin-bottom:10px;" >
				  
                  <div align="left" style="text-align:left; margin-bottom:10px; margin-top:8px; margin-left:2px;">
                  	<a href="<?= variable_ididioma_idsede('blogOpen.php?idseccion='.$rs_seccion['idseccion'].'&idcarpeta='.$idcarpeta) ?>" >
                   	  <span class="tituloPost"><?= $rs_seccion['titulo'] ?></span>                    </a>                  </div>
                  
                  <div align="left" style="text-align:left; margin-left:5px;"> 
               		  <span class="FechaPost"><?= strtoupper(date("M d Y",strtotime($rs_seccion['fecha_alta']))); ?></span>                  </div>
                  <div align="left" style="text-align:left; margin-bottom:3px; margin-left:5px;"> 
                  		<span class="FechaPost">TAGS:</span> <span class="TagsPost"><?= strtoupper($rs_seccion['keywords']); ?></span>                  </div>
                  
				  <? if($rs_seccion['foto'] != ''){ ?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:1px dotted #CCCCCC; ">
                      <tr>
                        <td align="center" valign="top">
                          <div class="marcoFotoBlog" align="center" style="text-align:center; overflow:hidden; margin-top:10px; width:450px; overflow:hidden;"><? $imagen_categoria =& new obj0001('0','imagen/seccion/grande/',$rs_seccion['foto'],'450','','','',variable_ididioma_idsede('blogOpen.php?idseccion='.$rs_seccion['idseccion'].'&idcarpeta='.$idcarpeta),'','precio='.$rs_seccion['precio'].'&esNuevo='.$rs_seccion['esnuevo'],'wmode=Opaque',''); ?></div>                        </td>
                      </tr>
                    </table>
                   <? } ?>
                   
                    <? if($rs_seccion['copete']!=''){ ?>
                    <div align="left" class="textoBlog" style="text-align:left; margin-bottom:15px; margin-top:8px; margin-left:5px;">
                      <?=$rs_seccion['copete']; ?>
                    </div>
                    <? } ?>
                    
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      
                      <tr>
                        <td width="64%" height="30" align="left"><a href="<?= variable_ididioma_idsede('blogOpen.php?idseccion='.$rs_seccion['idseccion'].'&idcarpeta='.$idcarpeta) ?>"><span class="BookNow"><?= $lbl_Vermas ?></span></a>
                        
                        	<!-- AddToAny BEGIN -->
                            <a class="a2a_dd" href="http://www.addtoany.com/share_save?linkurl=www.travel-54.com.ar&amp;linkname=" target="_blank">
                            <span class="Share"><?= $btn_Share ?></span></a>
                            <script type="text/javascript">
                            var a2a_config = a2a_config || {};
                            a2a_config.linkurl = "www.travel-54.com.ar";
                            </script>
                            <script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
                            <!-- AddToAny END -->                        </td>
                        <td width="36%" align="right">&nbsp;</td>
                      </tr>
                    </table>
              	</div>              </td>
            </tr>
            <?		 
						
				}// WHILE de secciones 
		?>
          </table>
          <? 
			 }// FIN Si hay secciones
		 ?><? if($cantidad_total_sec > $cantidad_registros_sec){ ?>
          <table width="100%" border="0" cellpadding="6" cellspacing="0" style="margin-bottom:10px; margin-top:10px;">
            <tr>
              <td align="left" bgcolor="#F5F5F5" class="carpeta_producto_titulo_01"><span class="detalle_medio_bold"> <span class="detalle_medio">
					<?= $lbl_Pags ?>: 
                <input name="pag_seccion" type="hidden" id="pag_seccion" value="<?= $_POST['pag_seccion'] ?>" />
                </span>
                    <? 
				
					echo paginar($pag_seccion, $cantidad_total_sec, $cantidad_registros_sec, $_SERVER['PHP_SELF'], 2);
				
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