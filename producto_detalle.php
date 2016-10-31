<? 	include("0_include/0_mysql.php");  
	
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
	
	
	
	// DATOS DEL PRODUCTO
	//idcarpeta, idseccion e idproducto vienen desde el 0_mysql
	$modo = $_GET['modo'];
	$idproducto = $_GET['idproducto'];

	
	//SI NO ESTA EN MODO DE PREVISUALIZACION FILTRA POR SEDE
	if($modo != "previsualizar"){
		$filtro_sede = " AND C.idsede = '$_SESSION[idsede_session]' ";
	}else{
		$filtro_sede = "";
	}
	
	$query_prod = "SELECT 
	  A.idproducto
	, A.foto
	, A.idproducto_marca
	, A.precio
	, A.compra
	, A.oferta
	, A.destacado
	, A.discontinuado
	, A.novedad
	, A.idca_iva
	, A.foto_extra_columna
	, B.titulo
	, B.copete
	, B.estado AS estado_idioma
	, A.estado AS estado_producto
	, B.detalle
	, D.idcarpeta
	FROM producto A
	LEFT JOIN producto_idioma_dato B ON B.idproducto = A.idproducto
	LEFT JOIN producto_sede C ON C.idproducto = A.idproducto
	LEFT JOIN producto_carpeta D ON D.idproducto = A.idproducto
	WHERE A.idproducto = '$idproducto' AND B.ididioma = '$_SESSION[ididioma_session]' $filtro_sede";
	$rs_producto = mysql_fetch_assoc(mysql_query($query_prod));
	$cantidad_rs = mysql_num_rows(mysql_query($query_prod));
	
	if(!$idcarpeta){
		$idcarpeta = $rs_producto['idcarpeta'];
	}
	
	//Pone un titulo opcional al sitio en los meta tag
	$meta_tag_title_opcional = $rs_producto['titulo'];
	
	//Pone una descripcion opcional al sitio en los meta tag
	if($rs_producto['copete']){
		$meta_tag_description_opcional = $rs_producto['copete'];
	}
	
	
	//MOTIVOS POR LOS QUE NO SE PUEDE MOSTRAR LA CARPETA
	if($cantidad_rs == 0){
		//REDIRECCIONAR A:
		echo "No se encontro el producto.<br>";
		exit;
	}
	
	if(!$idproducto){
		//REDIRECCIONAR A:
		echo "No se indico el idproducto.<br>";
		exit;
	}
	
	if($modo != "previsualizar"){
		if($rs_producto['estado_idioma'] != 1){
			//REDIRECCIONAR A:
			echo "Producto deshabilitado idioma.<br>";
			exit;
		}
		if($rs_producto['estado_producto'] != 1){
			//REDIRECCIONAR A:
			echo "Producto deshabilitado.<br>";
			exit;
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? $xajax->printJavascript("js/xajax/"); ?>
<script type="text/javascript">

	function ActualizarValores(valor){
		
		xajax.$('carrito_total').innerHTML = "Total: <b>$" + valor + "</b>";
		//<br>";

	}
	
	function AddItem(id, cant){
		xajax_AgregarProducto(id,cant);
	}

</script>

<? include_once("0_include/0_head.php"); ?>
<script type="text/javascript" src="js/overlay.js"></script>
<script type="text/javascript" src="js/multibox.js"></script>
<link href="css/0_multibox.css" rel="stylesheet" type="text/css" />


<script type="text/javascript">
	var box1 = {};
	window.addEvent('domready', function(){
		box1 = new MultiBox('ver', {descClassName: 'multiBoxDesc_ver', useOverlay: true});
	});
	
	var box2 = {};
	window.addEvent('domready', function(){
		box2 = new MultiBox('hor', {descClassName: 'multiBoxDesc_hor', useOverlay: true});
	});
	
	var box3 = {};
	window.addEvent('domready', function(){
		box3 = new MultiBox('consulta', {descClassName: 'multiBoxDesc_consulta', useOverlay: true});
	});
</script>
</head>
<body>
<div id="header"><? include_once("0_include/0_top.php"); ?></div>
<div id="cuerpo">
	<div id="barra_izq_imagen"></div>
	
	<div id="barra">
		<? include("0_include/0_barra.php"); ?>
	</div>
  <div id="contenido">
    	<table width="100%" border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td colspan="4">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="640" height="40">
          <param name="movie" value="skin/index/swf/titulo.swf" />
          <param name="quality" value="high" />
          <param name="wmode" value="transparent" />
          <param name="FlashVars" value="titulo=<?= $rs_producto['titulo'] ?>" />
          <embed src="skin/index/swf/titulo.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="640" height="40" FlashVars="titulo=<?= $rs_producto['titulo'] ?>" wmode="transparent" ></embed>
        </object></td>
      </tr>
      <tr>
        <td width="64%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <?
		  
		/////////////////////////////////////////////////////
		//sistema de arbol horizontal o barra de navegacion//
		/////////////////////////////////////////////////////
			
			/*
			if($rs_seccion['titulo'] == ""){
				$ruta = "";
			}else{
				$ruta = $rs_seccion['titulo'];
			}
			*/
			
			//AVERIGUO RUTA COMPLETA - NIVEL 1
			$query_1 = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta
			FROM carpeta A
			INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
			WHERE A.idcarpeta = '$idcarpeta' AND B.ididioma = '$_SESSION[ididioma_session]'
			LIMIT 1";
			$result_query_1 = mysql_query($query_1);
			while($rs_query_1 = mysql_fetch_assoc($result_query_1)){ 
				
				$ruta = '<a href="'.variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_query_1['idcarpeta']).'">'.$rs_query_1['nombre']."</a> ";
				
				//NIVEL 2
				$query_2 = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta
				FROM carpeta A
				INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
				WHERE A.idcarpeta = '$rs_query_1[idcarpeta_padre]' AND B.ididioma = '$_SESSION[ididioma_session]'
				LIMIT 1";
				$result_query_2 = mysql_query($query_2);
				while($rs_query_2 = mysql_fetch_assoc($result_query_2)){ 
				
					$ruta = '<a href="'.variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_query_2['idcarpeta']).'">'.$rs_query_2['nombre'].'</a> » '.$ruta;
					
					//NIVEL 3
					$query_3 = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta
					FROM carpeta A
					INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
					WHERE A.idcarpeta = '$rs_query_2[idcarpeta_padre]' AND B.ididioma = '$_SESSION[ididioma_session]'
					LIMIT 1";
					$result_query_3 = mysql_query($query_3);
					while($rs_query_3 = mysql_fetch_assoc($result_query_3)){ 
					
						$ruta = '<a href="'.variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_query_3['idcarpeta']).'">'.$rs_query_3['nombre']."</a> » ".$ruta;
					
					}
				
				}
				
				
			}//FIN AVERIGUO RUTA COMPLETA
			
			//IMPRIMO RUTA
			echo $ruta;

		?>        </td>
        <td width="17%" align="right"><a href="#descargas"></a></td>
        <td width="5%" align="center"><a href="producto_imprimir.php?idproducto=<?= $idproducto ?>" target="_blank"><img src="admin/imagen/iconos/printer26px.png" width="26" height="26" border="0" /></a></td>
        <td width="14%" align="left"><a href="consultar.php?idproducto=<?= $rs_producto['idproducto'] ?>&amp;idcarpeta=<?= $idcarpeta ?>&amp;url=<?= $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".ereg_replace('&', '|', $_SERVER['QUERY_STRING']); ?>" id="xx<?= $cont ?>" class="consulta" title="<?= $rs_producto['titulo'] ?>">| Consultar</a></td>
      </tr>
    </table>
	    <table width="100%" border="0" cellspacing="5" cellpadding="0">
          <tr>
            <td width="99%" align="left" valign="top" class="detalle_b">
                <table width="100%" border="0" cellspacing="1" cellpadding="0">
                 
                  <tr>
                    <td width="0%"><table width="200" border="0" cellpadding="0" cellspacing="0"  >
                      <tr valign="top">
                        <td align="left" valign="top" class="ejemplo_12px"><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#999999">
                            <tr>
                              <td align="center" valign="middle"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td align="left" valign="bottom"><? if($rs_producto['foto']!=''){ ?>
                                        <table width="20" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="1"><img src="skin/index/imagen/0_marco/cuadrio_izq_arriba.jpg"  /></td>
                                            <td background="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg" /></td>
                                            <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_arriba.jpg"  /></td>
                                          </tr>
                                          <tr>
                                            <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"  /></td>
                                            <td valign="top"><a href="imagen/producto/grande/<?= $rs_producto['foto'] ?>" rel="widht:400;height:300" id="v<?= $cont ?>" class="ver" ><img src="imagen/producto/mediana/<?= $rs_producto['foto'] ?>" border="0" /></a><div class="multiBoxDesc_ver v<?= $cont ?>"></div></td>
                                            <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_der.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_der.jpg" /></td>
                                          </tr>
                                          <tr>
                                            <td width="1"><img src="skin/index/imagen/0_marco/cuadro_izq_abajo.jpg"  /></td>
                                            <td background="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg"><img src="imagen/borde_bottom.jpg" width="1" height="13" /><img src="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg"  /></td>
                                            <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_abajo.jpg"  /></td>
                                          </tr>
                                        </table>
                                      <? } ?>
  <div class="lightboxDesc image<?= $foto_extra_cont ?>"></div></td></tr>
                              </table></td>
                            </tr>
                            <? if($rs_producto['titulo'] != ''){ ?>
                            <tr>
                              <td align="center" class="detalle_inmueble" ><table width="100%" border="0" cellpadding="3" cellspacing="0">

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
						  }
						  ?>
                                    </strong></span></td>
                                  </tr>
								  <? 
				  
								  $query_marca = "SELECT titulo FROM producto_marca WHERE idproducto_marca = '$rs_producto[idproducto_marca]' AND estado = 1";
								  $rs_marca = mysql_fetch_assoc(mysql_query($query_marca)); 
								  
								  if($rs_marca['titulo']){
									
								  ?>
                                  <tr>
                                    <td align="left" class="carpeta_producto_precio_01">&nbsp; Marca: <strong>
                                      <?= $rs_marca['titulo']; ?>
                                    </strong></td>
                                  </tr>
								  <? } ?>
                                </table>
								<? if($rs_dato_sitio['ca_carrito_usar'] == 1){ ?>
								<? if($rs_producto['compra']){ ?>
                                  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tabla_producto">
                                    <tr>
                                      <td height="15" align="left" bgcolor="#F7F7F7" class="carpeta_producto_titulo_01"><span class="registro_B">
                          <? 
						  		if($rs_producto['precio'] > 0){
						  ?>
							&nbsp; <a href="javascript:AddItem(<?= $idproducto ?>,1);"><img src="imagen/botones/b-comprarazul.gif" width="52" height="8" border="0" /></a>
							<? 	}else{ //MOSTRAR BOTON DE COMPRA DESHABILITADO ?>
							&nbsp; <img src="imagen/botones/b-comprar_off.jpg" width="52" height="8" />
							<?
						  		}
						  ?>
                                      </span></td>
                                    </tr>
                                </table>
								<? }// SI PERMITE COMPRA ?>
							<? }// FIN SI HABILITA POR PARAMETRO ?></td>
                            </tr>
                            <? } ?>
                        </table>
                        <br /></td>
                      </tr>
                    </table></td>
                    <td width="100%" align="center" valign="top">
					<? if($rs_producto['copete']!=''){ ?>
					<table width="100%" border="0" cellpadding="11" cellspacing="4">
                      <tr>
                        <td align="left" bgcolor="#F7F7F7" class="copete_a" ><div align="justify" class="producto_copete">
                          <?= $rs_producto['copete'] ?>
                        </div></td>
                      </tr>
                    </table>
					<? } ?>
					<table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td align="left">
						<? if($rs_producto['oferta']){ ?><img src="imagen/iconos/oferta.jpg" width="59" height="59" /><? } ?>
						<? if($rs_producto['destacado']){ ?><img src="imagen/iconos/destacado.jpg" width="59" height="59" /><? } ?>
						<? if($rs_producto['discontinuado']){ ?><img src="imagen/iconos/discontinuo.jpg" width="59" height="59" /><? } ?>
						<? if($rs_producto['novedad']){ ?><img src="imagen/iconos/novedad.jpg" width="59" height="59" /><? } ?>						</td>
                      </tr>
                    </table></td>
                  </tr>
                  
              </table>
                <table width="100%" border="0" cellpadding="5" cellspacing="0">

                  <tr>
                    <td width="100%" class="star_rating_titulo" ><div align="justify">
                      <?= html_entity_decode($rs_producto['detalle'], ENT_QUOTES); ?>
                    </div></td>
                  </tr>
                </table>
            <br /></td>
            <td width="1%" align="left" valign="top" class="detalle_b"><table width="5" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <? //Fotos Extra vertical
					  $query_foto_extra_vertical = "SELECT idproducto_foto, foto, titulo
					  FROM producto_foto
					  WHERE idproducto = '$idproducto' AND foto_extra_tipo = '2' AND ididioma = '$ididioma_session'
					  ORDER BY orden";
					  $result_foto_extra_vertical = mysql_query($query_foto_extra_vertical);
					  $cant_foto_extra_vertical = mysql_num_rows($result_foto_extra_vertical);//indica la cantidad de fotos
					  
					  if($cant_foto_extra_vertical > 0){//si la cantidad de fotos es 0, no lo muestra
				?>
                  <td align="right" valign="top"><table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
                      <tr valign="top">
                        <? 	
				  		$cont = 0;					
						$vuelta_foto = 1;//indicador inicial de vueltas, para el sistema de columnas
					  	while( $rs_foto_extra_vertical = mysql_fetch_assoc($result_foto_extra_vertical) ){//while de foto extra horizontal
					  		$cont++;	  					
					?>
                        <td align="center" valign="top" class="ejemplo_12px"><table width="20" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="1"><img src="skin/index/imagen/0_marco/cuadrio_izq_arriba.jpg"  /></td>
                              <td background="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg" /></td>
                              <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_arriba.jpg"  /></td>
                            </tr>
                            <tr>
                              <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"  /></td>
                              <td valign="top"><a href="imagen/producto/extra_grande/<?= $rs_foto_extra_vertical['foto'] ?>" rel="widht:400;height:300" id="v<?= $cont ?>" class="ver" title="<?= $rs_foto_extra_vertical['titulo'] ?>"><img src="imagen/producto/extra_chica/<?= $rs_foto_extra_vertical['foto'] ?>" border="0" /></a><div class="multiBoxDesc_ver v<?= $cont ?>"></div></td>
                              <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_der.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_der.jpg"  /></td>
                            </tr>
                          <tr>
                              <td width="1"><img src="skin/index/imagen/0_marco/cuadro_izq_abajo.jpg"  /></td>
                            <td background="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg"><img src="imagen/borde_bottom.jpg" width="1" height="13" /><img src="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg" /></td>
                            <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_abajo.jpg"  /></td>
                          </tr>
                        </table></td>
                        <?		
								if($vuelta_foto == 1){ //catidad de fotos extras por fila
									echo "</tr><tr>";
									$vuelta_foto = 1;
								}else{
									$vuelta_foto++;
								};							 
						}; //FIN WHILE foto extra ?>
                      </tr>
                  </table></td>
                  <? } ?>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                <tr>
                  <td align="left"><? 
					  //Fotos Extra horizontal
					  $query_foto_extra_horizontal = "SELECT idproducto_foto, foto, titulo
					  FROM producto_foto
					  WHERE idproducto = '$idproducto' AND foto_extra_tipo = '1' AND ididioma = '$ididioma_session'
					  ORDER BY orden";
					  $result_foto_extra_horizontal = mysql_query($query_foto_extra_horizontal);
					  $cant_foto_extra_horizontal = mysql_num_rows($result_foto_extra_horizontal);//indica la cantidad de fotos
					  
					  if($cant_foto_extra_horizontal > 0){//si la cantidad de fotos es 0, no lo muestra
				?>
                      <table width="72" border="0" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                    <? 	
						$cont = 0;				
						$vuelta_foto = 1;//indicador inicial de vueltas, para el sistema de columnas
					 	while( $rs_foto_extra_horizontal = mysql_fetch_assoc($result_foto_extra_horizontal) ){//while de foto extra horizontal
				
							$cont++;
					
					?>
                          <td align="center" valign="bottom" class="ejemplo_12px"><table width="9%" border="0" cellspacing="0" cellpadding="1">
                              <tr>
                                <td align="center" valign="bottom"><table width="20" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td width="1"><img src="skin/index/imagen/0_marco/cuadrio_izq_arriba.jpg"  /></td>
                                      <td background="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg"  /></td>
                                      <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_arriba.jpg"  /></td>
                                    </tr>
                                    <tr>
                                      <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"  /></td>
                                      <td valign="top"><a href="imagen/producto/extra_grande/<?= $rs_foto_extra_horizontal['foto'] ?>" rel="widht:400;height:300" id="h<?= $cont ?>" class="hor" title="<?= $rs_foto_extra_horizontal['titulo'] ?>"><img src="imagen/producto/extra_chica/<?= $rs_foto_extra_horizontal['foto'] ?>" border="0" /></a><div class="multiBoxDesc_hor h<?= $cont ?>"></div></td>
                                      <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_der.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_der.jpg"  /></td>
                                    </tr>
                                  <tr>
                                      <td width="1"><img src="skin/index/imagen/0_marco/cuadro_izq_abajo.jpg"  /></td>
                                    <td background="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg"><img src="imagen/borde_bottom.jpg" width="1" height="13" /><img src="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg" /></td>
                                    <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_abajo.jpg"  /></td>
                                  </tr>
                                </table></td>
                              </tr>
                          </table></td>
                        <?

						  		if($vuelta_foto == $rs_producto['foto_extra_columna']){ //catidad de fotos extras por fila
									echo "</tr><tr>";
									$vuelta_foto = 1;
								}else{
									$vuelta_foto++;
								};
										 
						}; //FIN WHILE foto extra ?>
                        </tr>
                      </table>
                    <? }//Fin foto extra horizontal  ?></td>
                </tr>
            </table></td>
          </tr>
        </table>
    <? 
		//CARGO PARÁMETROS DE CARPETA
		$query_par = "SELECT *
		FROM descarga_parametro
		WHERE iddescarga_parametro = 1";
		$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
		
		$ver_desc_siempre = $rs_parametro['ver_desc_siempre'];
		$ver_desc_user =  $rs_parametro['ver_desc_user'];
		
		if($idcarpeta){
			$filtro_carpeta = " OR idcarpeta = '$idcarpeta' ";
		}else{
			$filtro_carpeta = "";
		}
		
		$ruta_descarga = "descarga/";
		$ver_cuadro = true;
		$query_descarga = "SELECT *
		FROM descarga
		WHERE idproducto = '$idproducto' $filtro_carpeta
		ORDER BY idproducto";
		$result_descarga = mysql_query($query_descarga);
		$cant_result = mysql_num_rows($result_descarga);
		
		if($ver_desc_siempre == 1 || $cant_result > 0){
			
			if($ver_desc_user == 2){
				if($_SESSION['mail_session']){
					$ver_cuadro = true;
				}else{
					$ver_cuadro = false;
				}
			}else{
				$ver_cuadro = true;
			}
			
			
			if($ver_cuadro == true){
	?>
        <table width="100%" border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="640" height="40">
              <param name="movie" value="skin/index/swf/titulo.swf" />
              <param name="quality" value="high" />
              <param name="wmode" value="transparent" />
              <param name="FlashVars" value="titulo=Descargas" />
              <embed src="skin/index/swf/titulo.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="640" height="40" FlashVars="titulo=Descargas" wmode="transparent"></embed>
            </object></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="2%" height="35" bgcolor="#E6E6E6">&nbsp;</td>
                <td width="62%" height="35" bgcolor="#E6E6E6" class="carpeta_producto_titulo_01"><strong>Titulo</strong></td>
                <td width="16%" height="35" bgcolor="#E6E6E6" class="carpeta_producto_titulo_01"><strong>Tama&ntilde;o</strong></td>
                <td width="16%" height="35" bgcolor="#E6E6E6" class="carpeta_producto_titulo_01"><strong>Tipo</strong></td>
                <td width="4%" height="35" bgcolor="#E6E6E6" class="registro_A">&nbsp;</td>
              </tr>
				<?
				
				while($rs_descarga = mysql_fetch_assoc($result_descarga)){
				
					if($rs_descarga['restringido'] == 1){
						if($_SESSION['mail_session']){
							$link = "descarga/".$rs_descarga['archivo'];
							$target = 'target="_blank"';
						}else{
							$link = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/";
							$link = "login.php?url=producto_detalle.php?".ereg_replace('&', '~', $_SERVER['QUERY_STRING']);
							$target = 'target="_self"';
						}
					}else{
						$link = "descarga/".$rs_descarga['archivo'];
						$target = 'target="_blank"';
					}
				
				?>
              <tr>
                <td align="center" valign="middle" bgcolor="#F7F7F7"><img src="imagen/iconos/flecha_cursos.gif" width="5" height="5" /></td>
                <td align="left" valign="top" bgcolor="#F7F7F7" class="carpeta_producto_titulo_01"><?= $rs_descarga['titulo'] ?></td>
                <td align="left" valign="top" bgcolor="#F7F7F7" class="carpeta_producto_titulo_01"><? if(file_exists($ruta_descarga.$rs_descarga['archivo'])){ echo number_format((filesize($ruta_descarga.$rs_descarga['archivo']))/1024,2); echo " kb"; } ?></td>
                <td align="left" valign="top" bgcolor="#F7F7F7" class="carpeta_producto_titulo_01"><?
						   
						  $query = "SELECT B.titulo AS titulo_tipo
						  FROM descarga A
						  INNER JOIN descarga_tipo B ON A.idtipo_descarga = B.iddescarga_tipo
						  WHERE B.iddescarga_tipo = '$rs_descarga[idtipo_descarga]' "; 
						  $rs_descarga_tipo = mysql_fetch_assoc(mysql_query($query));
						  echo $rs_descarga_tipo['titulo_tipo'];
						  
						  ?></td>
                <td valign="top" bgcolor="#F7F7F7"><a href="<?= $link ?>" <?= $target ?> >
				<img src="imagen/iconos/descargar_fondo_azul.png" width="15" height="18" border="0" /></a></td>
              </tr>
			  <? if($rs_descarga['descripcion']){ ?>
              <tr>
                <td valign="top" bgcolor="#F7F7F7">&nbsp;</td>
                <td bgcolor="#F7F7F7" class="cuadro_login_texto"><div align="justify">
                  <?= $rs_descarga['descripcion'] ?>
                </div></td>
                <td align="left" valign="top" bgcolor="#F7F7F7" class="registro_B">&nbsp;</td>
                <td align="left" valign="top" bgcolor="#F7F7F7" class="registro_B">&nbsp;</td>
                <td valign="top" bgcolor="#F7F7F7">&nbsp;</td>
              </tr>
			  <? } ?>
			  <? } ?>
			  <? if($cant_result == 0){ ?>
              <tr>
                <td height="50" colspan="5" align="center" valign="middle" class="carpeta_producto_titulo_01"><strong>No hay descargas disponibles. </strong></td>
              </tr>
			  <? } ?>
            </table></td>
          </tr>
        </table>
	<?
	 		}
		} 
	?>
  </div>
	
	<div id="barra_der_imagen"></div>
</div>

<div id="footer">
<? include("0_include/0_pie.php"); ?>
</div>
</body>
</html>