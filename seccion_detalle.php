<? 	include("0_include/0_mysql.php"); 

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
	
	//CHEQUEO PARA PAGINAR
	$cantidad_registros = $_POST['cant'];
	if(!$cantidad_registros){
		$cantidad_registros = 5;
	}
	
	$pag = $_POST['pag'];
	if(!$pag){
		$pag = 1;
	}
	$puntero = ($pag-1) * $cantidad_registros;

	// DATOS DE LA SECCION
	// idcarpeta, idseccion e idproducto vienen desde el 0_mysql
	$modo = $_GET['modo'];
	$idseccion = $_GET['idseccion'];
	$accion = $_POST['accion'];
	$fecha = date("Y-m-d");
	
	//ENVIAR COMENTARIO
	if($accion == "enviar_comentario"){
		
		$nombre = $_POST['nombre'];
		$mail = $_POST['email'];
		
		include("0_include/0_creacion_mail.php");
		$comentario = str_replace(chr(13), "<br>", addslashes($_POST['comentario']));
		
		//BUSCO ID DEL USUARIO
		if($_SESSION['mail_session'] != ""){
			$query_user = "SELECT iduser_web, mail, nombre, apellido
			FROM user_web 
			WHERE mail = '$_SESSION[mail_session]' 
			LIMIT 1";
			$rs_user = mysql_fetch_assoc(mysql_query($query_user));
			$iduser_web_comen = $rs_user['iduser_web'];
		}else{
			$query_check = "SELECT mail FROM user_web WHERE mail = '$mail'";
			if(mysql_num_rows(mysql_query($query_check))==0){
			
					$query_insert_user = "INSERT INTO user_web ( 
						nombre
					  ,	mail
					) VALUES ( 
						  '$nombre'
						, '$mail'
					)";
					$rs_insert_user = mysql_query($query_insert_user);
			}
			$query_user = "SELECT *
			FROM user_web 
			WHERE mail = '$mail' 
			LIMIT 1";
			$rs_user = mysql_fetch_assoc(mysql_query($query_user));
			$iduser_web_comen = $rs_user['iduser_web'];
		}
		
		//BAJO PARAMETROS GENERALES (ESTADO POR DEFECTO)
		$query_gral = "SELECT estado_comentario_defecto 
		FROM seccion_parametro 
		WHERE idseccion_parametro = '1' LIMIT 1";
		$rs_gral = mysql_fetch_assoc(mysql_query($query_gral));
			
		//BAJO PARAMETROS DE LA SECCION (MAIL DEL MODERADOR)
		$query_mod = "SELECT mail_moderador 
		FROM seccion 
		WHERE idseccion = '$idseccion' LIMIT 1";
		$rs_mod = mysql_fetch_assoc(mysql_query($query_mod));
		
		//GUARDO COMENTARIO
		$query_insert = "INSERT INTO seccion_comentario (
		  idseccion
		, ididioma
		, iduser_web
		, fecha_alta
		, comentario
		, estado
		) VALUES (
		  '$idseccion'
		, '$ididioma_session'
		, '$iduser_web_comen'
		, '$fecha'
		, '$comentario'
		, '$rs_gral[estado_comentario_defecto]'
		)";
		$res = mysql_query($query_insert);
		
		if($res == 1){
		
			//ID DEL COMENTARIO
			$query_id = "SELECT MAX(idseccion_comentario) AS id FROM seccion_comentario";
			$rs_id = mysql_fetch_assoc(mysql_query($query_id));
		
			//ENVIO MAIL AL MODERADOR
			$dominio = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/";
			$headers = "Content-type: text/html;\n Content-Type: image/jpg;\n Content-Transfer-Encoding: base64;\n charset=iso-8859-1\n";
			$headers .= "From: ".$rs_dato_sitio['reg_mail_desde']."\r\n";
			$asunto = $rs_dato_sitio['reg_titulo']." - Nuevo comentario ingresado.";
			
			
			$cuerpo_msj = "El siguiente comentario ha sido ingresado:<br><br>";
			$cuerpo_msj .= '"'.$comentario.'"<br><br>';
			if($iduser_web_comen == 0){
				$cuerpo_msj .= "Usuario: Anonimo.<br>";
			}else{
				$cuerpo_msj .= "Usuario: ".$rs_user['nombre']." ".$rs_user['apellido']." (".$rs_user['mail'].")<br>";
			}
			$cuerpo_msj .= "Fecha: ".date("d/m/Y").'<br><br>';
			$cuerpo_msj .= '<b><a href="'.$dominio.'comentario.php?idseccion_comentario='.$rs_id['id'].'&estado=1" target="_blank">Aceptar</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="'.$dominio.'comentario.php?idseccion_comentario='.$rs_id['id'].'&estado=2" target="_blank">Rechazar</a></b>';
			$cuerpo_msj .= '<b>|&nbsp;&nbsp; <a href="'.$dominio.'comentario.php?idseccion_comentario='.$rs_id['id'].'&accion=editar" target="_blank">Editar</a><br>'; 
			
			$mensaje = mail_personalizado($dominio, $asunto, $cuerpo_msj);
			
			//SE ENVIA EL MAIL
			/*mail($rs_mod['mail_moderador'], $asunto, $mensaje, $headers);*/
			
			//MENSAJE DE INGRESADO CORRECTO
			$msj = "Su comentario ha sido ingresado correctamente.<br> Su mensaje será revisado por un moderador.";
			
		}else{
		
			$msj = "Su comentario no se ha podido ingresar. Intente nuevamente más tarde.";
		
		}

		$accion = "";
	}
	
	//SI NO ESTA EN MODO DE PREVISUALIZACION FILTRA POR SEDE
	if($modo != "previsualizar"){
		$filtro_sede = " AND C.idsede = '$_SESSION[idsede_session]' ";
	}else{
		$filtro_sede = "";
	}
	
	$query_seccion = "SELECT 
	  A.idseccion
	, A.foto
	, A.usa_rating
	, A.habilita_comentario
	, A.foto_extra_columna
	, A.esnuevo
	, A.precio
	, B.titulo
	, B.copete
	, B.estado AS estado_idioma
	, A.estado AS estado_seccion
	, B.banner
	, B.detalle
	, D.idcarpeta
	FROM seccion A
	LEFT JOIN seccion_idioma_dato B ON B.idseccion = A.idseccion
	LEFT JOIN seccion_sede C ON C.idseccion = A.idseccion
	LEFT JOIN seccion_carpeta D ON D.idseccion = A.idseccion
	WHERE A.idseccion = '$idseccion' AND B.ididioma = '$_SESSION[ididioma_session]' $filtro_sede";
	$rs_seccion = mysql_fetch_assoc(mysql_query($query_seccion));
	$cantidad_rs = mysql_num_rows(mysql_query($query_seccion));
	
	if(!$idcarpeta){
		$idcarpeta = $rs_seccion['idcarpeta'];
	}
	
	//Pone un titulo opcional al sitio en los meta tag
	$meta_tag_title_opcional = $rs_seccion['titulo'];
	
	//Pone una descripcion opcional al sitio en los meta tag
	if($rs_seccion['copete']){
		$meta_tag_description_opcional = $rs_seccion['copete'];
	}
	
	
	//MOTIVOS POR LOS QUE NO SE PUEDE MOSTRAR LA CARPETA
	if($cantidad_rs == 0){
		//REDIRECCIONAR A:
		echo "No se encontro la seccion.<br>";
		exit;
	}
	
	if(!$idseccion){
		//REDIRECCIONAR A:
		echo "No se indico el idseccion.<br>";
		exit;
	}
	
	if($modo != "previsualizar"){
		if($rs_seccion['estado_idioma'] != 1){
			//REDIRECCIONAR A:
			echo "Seccion deshabilitado idioma.<br>";
			exit;
		}
		if($rs_seccion['estado_seccion'] != 1){
			//REDIRECCIONAR A:
			echo "Seccion deshabilitada.<br>";
			exit;
		}
	}
	
	//PARAMETROS GRALES
	$query_param_gral = "SELECT restringe_lectura, restringe_escritura, usar_comentarios, usa_rating, rating_restringido
	FROM seccion_parametro
	WHERE idseccion_parametro = '1' ";
	$rs_param_gral = mysql_fetch_assoc(mysql_query($query_param_gral));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include_once("0_include/0_head.php"); ?>


<script type="text/javascript" src="js/overlay.js"></script>
<script type="text/javascript" src="js/multibox.js"></script>
<link href="css/0_multibox.css" rel="stylesheet" type="text/css" />


<script type="text/javascript">

	
		function validarEmail(valor)
		{
			document.getElementById("alertEmailNoSeleccionado").style.display = "none";
			document.getElementById("alertValidarEmailNoSeleccionado").style.display = "none";
		
			if (document.getElementById("email").value == "")
			{
			   document.getElementById("alertEmailNoSeleccionado").style.display = "block";
			}else{
				if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(valor)){
					return true;

				} else {
					document.getElementById("alertValidarEmailNoSeleccionado").style.display = "block";
					return false;
				}
			}
		};


	function validarNombre(valor){
		document.getElementById("alertValidarNombreNoSeleccionado").style.display = "none";
		document.getElementById("alertNombreNoSeleccionado").style.display = "none";
		
		if (valor == ""){
			document.getElementById("alertNombreNoSeleccionado").style.display = "block";
			return false;
		}else{
			if (valor.length < 3){
				document.getElementById("alertValidarNombreNoSeleccionado").style.display = "block";
				return false;
			}else{
				return true;
			}
		} 
	
	};
	
	
	function validarMensaje(valor){
		document.getElementById("alertComentarioNoSeleccionado").style.display = "none";
		
		if (valor == ""){
			document.getElementById("alertComentarioNoSeleccionado").style.display = "block";
			return false;
		}else{
			return true;
			
		} 
		
	};


function enviar_comentario(){
	datos_ok = true;
	
	
	if (validarNombre(document.form_comentario.nombre.value)==false) {
		datos_ok = false;
	} 
	
	if (validarMensaje(document.form_comentario.comentario.value)==false) {
		datos_ok = false;
	} 
	
	if (validarEmail(document.form_comentario.email.value)==false) {
		datos_ok = false;
	}
	
	if(datos_ok == true){
		document.form_comentario.accion.value = "enviar_comentario";
		document.form_comentario.submit(); 
	}
	
};
	
	function ir_pagina(pag){
		formulario = document.form_comentario;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function validar_form_lista(){
		formulario = document.form_comentario;
		formulario.pag.value = "1";
		formulario.submit();
	};
	
	function descarga(res, url){
		if(res == 1){
			if (confirm('La descarga se encuentra restringida. Para poder descargar el archivo\ndebe loguearse. ¿Desea loguearse ahora?')){
				document.location.href=(url);
			}
		}else{
			document.location.href=(url);
		}
	};

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
  <div id="contenido">
  
	<div id="titulo-seccion" style="text-align:left; margin-top:20px;">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="700" height="56">
              <param name="movie" value="skin/index/swf/titulo.swf" />
              <param name="quality" value="high" />
              <param name="FlashVars" value="titulo=<?= $rs_seccion['titulo'] ?>" />
                <param name="wmode" value="transparent" />
              <embed src="skin/index/swf/titulo.swf" FlashVars="titulo=<?= $rs_seccion['titulo'] ?>" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="700" height="56"></embed>
            </object>
    </div>
        
        <div id="arbol-carpetas" style="padding-left:30px; margin-top:5px; margin-bottom:10px; text-align:left; width:50%; float:left;">
          <?
		  
		/////////////////////////////////////////////////////
		//sistema de arbol horizontal o barra de navegacion//
		/////////////////////////////////////////////////////
			
			
			if($rs_seccion['titulo'] == ""){
				$ruta = "";
			}else{
				$ruta = '<span class="Arial_11px_grisoscuro"> &raquo; <strong>'.$rs_seccion['titulo'].'</strong></span>';
			}
			
			
			//AVERIGUO RUTA COMPLETA - NIVEL 1
			$query_1 = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta
			FROM carpeta A
			INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
			WHERE A.idcarpeta = '$idcarpeta' AND B.ididioma = '$_SESSION[ididioma_session]'
			LIMIT 1";
			$result_query_1 = mysql_query($query_1);
			while($rs_query_1 = mysql_fetch_assoc($result_query_1)){ 
				
				$ruta = '<a href="'.variable_ididioma_idsede('carpeta_ver.php?idcarpeta='.$rs_query_1['idcarpeta']).'"><span class="Arial_11px_grisoscuro">'.$rs_query_1['nombre']."</span></a> ".$ruta;
				
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
        </div>
        <div id="barra-herramientas" style="margin-top:5px; margin-bottom:10px; text-align:right; width:45%; float:right;">
        
        <a href="seccion_imprimir.php?idseccion=<?= $idseccion ?>" target="_blank">
        	<span class="BookNow"><strong>IMPRIMIR</strong></span>
        </a> 
        
        <a href="javascript:history.back();">
        	<span class="GoBack"><strong>VOLVER</strong></span>
        </a>
          
        </div>
        
        <div id="separador" style="clear:both; height:1px; border-bottom:1px dotted #999999;"></div>
        
        <? if($rs_seccion['banner']!=''){ ?>
        <div id="banner-seccion" style="padding-top:10px; padding-bottom:10px; text-align:center;">
        	<? $imagen =& new obj0001('0','imagen/seccion/banner/',$rs_seccion['banner'],'','','','','','','','wmode=transparent',''); ?>
        </div>
        <? } ?>
        
    <div id="descripcion" class="textoSeccion" style="text-align:left; margin-top:10px; padding:10px; width:750px; line-height:16px; overflow:hidden;">
        	
            <? if($rs_seccion['foto']!=''){ ?>
              <? /* FOTO PRINCIPAL */ ?>
              <div style="margin-right:20px; margin-bottom:10px; float:left;" class="marcoFoto"><? $imagen =& new obj0001('0','imagen/seccion/mediana/',$rs_seccion['foto'],'','','','','','','precio='.$rs_seccion['precio'].'&esNuevo='.$rs_seccion['esnuevo'],'Opaque',''); ?></div>
            <? } ?>
            
            
            <? 
				/* FOTOS EXTRA VERTICAL */
				$query_foto_extra_vertical = "SELECT idseccion_foto, foto, titulo
				FROM seccion_foto
				WHERE idseccion = '$idseccion' AND foto_extra_tipo = '2' AND ididioma = '1'
				ORDER BY orden";
				$result_foto_extra_vertical = mysql_query($query_foto_extra_vertical);
				$cant_foto_extra_vertical = mysql_num_rows($result_foto_extra_vertical);
			  
				if($cant_foto_extra_vertical > 0){//si la cantidad de fotos es 0, no lo muestra
			 ?>
             	<div id="fotos-verticales" style="width:150px; float:right; text-align:right; margin-left:10px;">
             <?   
                	
				  		$cont = 0;					
						$vuelta_foto = 1;//indicador inicial de vueltas, para el sistema de columnas
					  	while( $rs_foto_extra_vertical = mysql_fetch_assoc($result_foto_extra_vertical) ){//while de foto extra horizontal
					  		$cont++;	  					
			?>
            	
                <a href="imagen/seccion/extra_grande/<?= $rs_foto_extra_vertical['foto'] ?>" rel="widht:400;height:300" id="v<?= $cont ?>" class="ver" title="<?= $rs_foto_extra_vertical['titulo'] ?>"><div style="margin-bottom:10px; text-align:center;" class="marcoFoto"><img src="imagen/seccion/extra_chica/<?= $rs_foto_extra_vertical['foto'] ?>" border="0" /></div></a><div class="multiBoxDesc_ver v<?= $cont ?>"></div>
                
            <?						 
						}; //FIN WHILE foto extra
			?>
            	</div>
            <?
             	}//FIN IF hay fotos verticales
			?>
            
        
      	<?= html_entity_decode($rs_seccion['detalle'], ENT_QUOTES); ?>
            
      </div>
        
      <div id="separador" style="clear:both; height:0px;"></div>  
        
          <? 
			if($rs_param_gral['usa_rating'] == 1){ // ESTA HABILITADA LA CALIFICACION EN PARAMETRO GENERAL?
			
				if($rs_seccion['usa_rating'] == 1){ // SI ESTA HABILITADA EN ESTA SECCION
				
					if($rs_param_gral['rating_restringido'] == 1){
						if($_SESSION['iduser_web_session']){
								$habilita_rating = true;
						}else{
							$habilita_rating = false;
						}
					}else{
						$habilita_rating = true;
					}
				
					if($habilita_rating == true){ // NECESITA ESTAR LOGUEADO PARA CALIFICAR?
		  ?>
            <div id="star-rating" style="padding:5px; margin-top:10px; border:1px dotted #00FFFF; margin-bottom:10px;">
              <iframe src="0_include/star_rating/star_rating.php?idseccion=<?= $idseccion ?>" width="100%" height="62" scrolling="no" frameborder="0"></iframe>
            </div>
		  <? 
		  			}
		  		}
			}
		  ?>
          
          
          
          
          
		  
            <? 
					  //Fotos Extra horizontal
					  $query_foto_extra_horizontal = "SELECT idseccion_foto, foto, titulo
					  FROM seccion_foto
					  WHERE idseccion = '$idseccion' AND foto_extra_tipo = '1' AND ididioma = '$_SESSION[ididioma_session]'
					  ORDER BY orden";
					  $result_foto_extra_horizontal = mysql_query($query_foto_extra_horizontal);
					  $cant_foto_extra_horizontal = mysql_num_rows($result_foto_extra_horizontal);//indica la cantidad de fotos
					  
					  if($cant_foto_extra_horizontal > 0){//si la cantidad de fotos es 0, no lo muestra
				?>
            <div style="margin-top:10px; margin-bottom:10px; text-align:center;">
            
                <div style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#069dc5; padding:8px; margin-bottom:15px; border-bottom:1px solid #999999;">
                  <div style=" text-align:left;">Im&aacute;genes<strong><a name="descargas" id="descargas2"></a></strong></div>
                </div>
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
                            <td background="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_arriba.jpg" width="15" height="15" /></td>
                            <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_arriba.jpg"  /></td>
                          </tr>
                          <tr>
                            <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_izq.jpg"  /></td>
                            <td valign="top"><div style="height:80px; overflow:hidden;"> <a href="imagen/seccion/extra_grande/<?= $rs_foto_extra_horizontal['foto'] ?>" rel="widht:400;height:300" id="h<?= $cont ?>" class="hor" title="<?= $rs_foto_extra_horizontal['titulo'] ?>"><img src="imagen/seccion/extra_chica/<?= $rs_foto_extra_horizontal['foto'] ?>" border="0" /></a>
                                  <div class="multiBoxDesc_hor h<?= $cont ?>"></div>
                            </div></td>
                            <td width="1" background="skin/index/imagen/0_marco/cuadro_centro_der.jpg"><img src="skin/index/imagen/0_marco/cuadro_centro_der.jpg"  /></td>
                          </tr>
                        <tr>
                            <td width="1"><img src="skin/index/imagen/0_marco/cuadro_izq_abajo.jpg"  /></td>
                          <td background="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg"><img src="imagen/borde_bottom.jpg" width="1" height="13" /><img src="skin/index/imagen/0_marco/cuadro_centro_abajo.jpg" width="15" height="15" /></td>
                          <td width="1"><img src="skin/index/imagen/0_marco/cuadro_der_abajo.jpg"  /></td>
                        </tr>
                      </table></td>
                    </tr>
                </table></td>
                <?		if($vuelta_foto == $rs_seccion['foto_extra_columna']){ //catidad de fotos extras por fila
									echo "</tr><tr>";
									$vuelta_foto = 1;
								}else{
									$vuelta_foto++;
								};							 
						}; //FIN WHILE foto extra ?>
              </tr>
            </table>
            </div>
		    <? }//Fin foto extra horizontal  ?>
          
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
		WHERE idseccion = '$idseccion' $filtro_carpeta
		ORDER BY idseccion";
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
    	
          <div style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#069dc5; padding:8px; border-bottom:1px solid #999999;">
            <div style=" text-align:left;">Descargas<strong><a name="descargas" id="descargas"></a></strong></div>
            </div>
          <table width="770" border="0" cellpadding="0" cellspacing="5" style="margin-top:10px; border:1px dotted #00FFFF; margin-bottom:10px;">
          <tr>
            <td align="center">
            
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
				<?
				
				while($rs_descarga = mysql_fetch_assoc($result_descarga)){
				
					if($rs_descarga['restringido'] == 1){
						if($_SESSION['mail_session']){
							$link = "descarga/".$rs_descarga['archivo'];
							$target = 'target="_blank"';
						}else{
							$link = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/";
							$link = "login.php?url=seccion_detalle.php?".ereg_replace('&', '~', $_SERVER['QUERY_STRING']);
							$target = 'target="_self"';
						}
					}else{
						$link = "descarga/".$rs_descarga['archivo'];
						$target = 'target="_blank"';
					}
				
				?>
              <tr>
                <td width="3%" align="center" bgcolor="#F7F7F7"><img src="imagen/iconos/arrow.gif" width="14" height="12" /></td>
                <td width="47%" align="left" valign="middle" bgcolor="#F7F7F7" class="carpeta_producto_titulo_01"><a href="<?= $link ?>" <?= $target ?> ><span class="carpeta_producto_titulo_01"><?= $rs_descarga['titulo'] ?></span></a></td>
                <td width="20%" align="left" valign="middle" bgcolor="#F7F7F7" class="carpeta_producto_titulo_01">
                <? 
						
							if(file_exists($ruta_descarga.$rs_descarga['archivo'])){ 
								$peso_kb = filesize($ruta_descarga.$rs_descarga['archivo'])/1024;

								if($peso_kb < 1024){
									echo number_format($peso_kb, 2)." kb.";
								}else{
									echo number_format($peso_kb/1024, 2)." mb.";
								}
							}
						
						?>                </td>
                <td width="27%" align="left" valign="middle" bgcolor="#F7F7F7" class="carpeta_producto_titulo_01"><?
						   
						  $query = "SELECT B.titulo AS titulo_tipo
						  FROM descarga A
						  INNER JOIN descarga_tipo B ON A.idtipo_descarga = B.iddescarga_tipo
						  WHERE B.iddescarga_tipo = '$rs_descarga[idtipo_descarga]' "; 
						  $rs_descarga_tipo = mysql_fetch_assoc(mysql_query($query));
						  echo $rs_descarga_tipo['titulo_tipo'];
						  
						  ?></td>
                <td width="3%" bgcolor="#F7F7F7"><a href="javascript:descarga('<?= $rs_descarga['restringido'] ?>','<?= $link ?>');" target="_blank" ><img src="imagen/iconos/softicon.gif" width="20" height="20" border="0" /></a></td>
              </tr>
			  <? if($rs_descarga['descripcion']){ ?>
              <tr>
                <td align="center" bgcolor="#F7F7F7">&nbsp;</td>
                <td bgcolor="#F7F7F7" class="registro_B"><div align="justify" class="cuadro_login_texto">
                  <?= $rs_descarga['descripcion'] ?>
                </div></td>
                <td bgcolor="#F7F7F7" class="registro_B">&nbsp;</td>
                <td bgcolor="#F7F7F7" class="registro_B">&nbsp;</td>
                <td bgcolor="#F7F7F7">&nbsp;</td>
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
	<? 
	
	//SI ESTAN HABILITADOS LOS COMENTARIOS
	if($rs_seccion['habilita_comentario'] == 1 && $rs_param_gral['usar_comentarios'] == 1){
	
	?>
	<form id="form_comentario" name="form_comentario" method="post" action="">
	<?
		
		//LECTURA
		if($rs_param_gral['restringe_lectura'] == 1){
			if($_SESSION['mail_session'] != ""){
				$habilita_lectura = 1;
			}else{
				$habilita_lectura = 2;
			}
		}else{
			$habilita_lectura = 1;
		}
		
		//ESCRITURA
		if($rs_param_gral['restringe_escritura'] == 1){
			if($_SESSION['mail_session'] != ""){
				$habilita_escritura = 1;
			}else{
				$habilita_escritura = 2;
			}
		}else{
			$habilita_escritura = 1;
		}
		
		//PAGINACION: Cantidad Total.
			$query_cant = "SELECT A.*
			FROM seccion_comentario A
			WHERE A.idseccion = '$idseccion' AND A.ididioma = '$ididioma_session' AND A.estado = 1
			ORDER BY A.fecha_alta DESC";
			$cantidad_total = mysql_num_rows(mysql_query($query_cant));
		
	
		$query_comen = "SELECT A.*
		FROM seccion_comentario A
		WHERE A.idseccion = '$idseccion' AND A.ididioma = '$ididioma_session' AND A.estado = 1
		ORDER BY A.fecha_alta DESC
		LIMIT $puntero,$cantidad_registros";
		$cant_comen = mysql_num_rows(mysql_query($query_comen));
		$result_comen = mysql_query($query_comen);
		
	?>
    <div style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#069dc5; padding:8px; border-bottom:1px solid #999999;">
    <div style="float:left; width:50%; text-align:left;">Comentarios<strong>
      <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
    </strong></div>
    <div style="float:left; width:50%; text-align:right;"><span class="detalle_medio">
      <select name="cant" class="numDestino" onchange="validar_form_lista();" style="width:50px;">
        <option value="5" <? if($cantidad_registros == 5){ echo "selected"; } ?> >5</option>
        <option value="10" <? if($cantidad_registros == 10){ echo "selected"; } ?> >10</option>
        <option value="20"<? if($cantidad_registros == 20){ echo "selected"; } ?> >20</option>
      </select>
    </span></div>
    <div style="clear:both;"></div>
    </div>
	<? if($msj){ ?>
    <br />
    <table width="640" border="0" align="center" cellpadding="6" cellspacing="0">
      <tr>
        <td align="left" bgcolor="#CEFFFF" class="registro_A"><?= $msj ?></td>
      </tr>
    </table>
	<? } ?>
	<? 

		//SI SE PUEDE LEER
		if($habilita_lectura == 1){
		
			//SI HAY COMENTARIOS
			if($cant_comen > 0){
		
				while($rs_comen = mysql_fetch_assoc($result_comen)){ ?>
    <br />
    <div id="comentario" style="width:640px;margin:auto; padding-bottom:15px; border-bottom:1px dotted #069dc5;">
    	<div id="img-comilla" style="width:12%; float:left; text-align:center;"><img src="imagen/varios/comilla.png" width="43" height="48" /></div>
        <div id="txt-comentario" style="float:left; width:88%; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#5e5e5e; text-align:justify; margin-bottom:15px; "><span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">&quot;<?= $rs_comen['comentario'] ?>&quot;</span></div>
        <div id="txt-firma" style=" float:right; text-align:right;">
        	<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#069dc5; font-weight:bold;"><?
			
			$query_user = "SELECT nombre, apellido, mail
			FROM user_web
			WHERE iduser_web = '$rs_comen[iduser_web]' ";
			$rs_user = mysql_fetch_assoc(mysql_query($query_user));
			$cant_user = mysql_num_rows(mysql_query($query_user));
			
			if($cant_user > 0){
				if($rs_user['apellido']!=""){
					echo $rs_user['nombre']." ".$rs_user['apellido']; 
				}else{
					echo $rs_user['nombre']; 				
				}
			}else{
				echo "Anonimo";
			}
			?></span><br />
        	<span style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#999999;"><? $fecha = split("-", $rs_comen['fecha_alta']); echo $fecha[2]."/".$fecha[1]."/".$fecha[0]; ?></span>        
        </div>
        <div style="clear:both;"></div>
    </div>
    
	  <? 
	  			} // FIN WHILE
	?>
	  <table width="640" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr>
          <td align="center" valign="middle" class="registro_B"><span class="titulo_medio_bold"><? echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']."?cant=".$cantidad_registros."&pag=" ); ?></span></td>
        </tr>
      </table>
	  <?	
			}else{ 
	  ?>
		<table width="640" border="0" align="center" cellpadding="6" cellspacing="0">
          <tr>
            <td height="50" align="center" class="Arial_12px_grisoscuro"><strong>No hay comentarios.</strong></td>
          </tr>
   	</table>
  		<?	} //FIN SI HAY COMENTARIOS
	
		}else{//SI SE PUEDE LEER
	  ?>  
	    <table width="640" border="0" align="center" cellpadding="6" cellspacing="0">
          <tr>
            <td height="50" align="center" class="registro_A">Necesita <a href="login.php?url=seccion_detalle.php?<?= ereg_replace('&', '~', $_SERVER['QUERY_STRING']) ?>" target="_self">loguearse</a> para leer los mensajes.</td>
          </tr>
        </table>
    <? } ?>
	<? if($habilita_escritura == 1){ ?>
    <table width="640" border="0" align="center" cellpadding="6" cellspacing="0">
    <tr>
      <td width="10%" height="50" align="right" class="registro_A">
	  
        
          <span class="SubTitulos">Nombre:</span>      </td>
      <td width="25%" align="left">
          <input name="nombre" type="text" id="nombre" onchange="validarNombre(this.value);" />
   		   <div style="display:none;" id="alertNombreNoSeleccionado" class="divAlerts">Ingrese un nombre.</div>
          <div style="display:none;" id="alertValidarNombreNoSeleccionado" class="divAlerts">Ingrese un nombre v&aacute;lido.</div>     	</td>
      <td width="11%" align="right"><span class="registro_A"><span class="SubTitulos">Email:</span></span></td>
      <td align="left"><input name ="email" type="text" id="email" onchange="validarEmail(this.value);" />
        <div style="display:none;" id="alertEmailNoSeleccionado" class="divAlerts">Ingrese un email.</div>
        <div style="display:none;" id="alertValidarEmailNoSeleccionado" class="divAlerts">Ingrese un email v&aacute;lido.</div></td>
    </tr>
  </table>
  <table width="640" border="0" align="center" cellpadding="6" cellspacing="0">
    <tr>
      <td height="50" align="left" class="registro_A">
	  
        
          <span class="SubTitulos">Escriba su comentario:</span>
          <input name="accion" type="hidden" id="accion" />
          <textarea name="comentario" rows="6" class="Arial_12px_grisoscuro" id="comentario" style="width:620px; border:1px solid #999999; padding:10px;" onchange="validarMensaje(this.value);"></textarea>
	  		<div style="display:none;" id="alertComentarioNoSeleccionado" class="divAlerts">Ingrese un comentario.</div>
      </td>
      </tr>
      <tr>
      <td height="50" align="left" class="registro_A">
      <input name="Submit" type="button" class="readMore" style="border: 0px solid #069dc5; " value=" Enviar Comentario  &raquo;" onclick="javascript:enviar_comentario();" /></td>
    </tr>
  </table>
  <? }else{ ?>
  <table width="640" border="0" align="center" cellpadding="6" cellspacing="0">
    <tr>
      <td height="50" align="center" class="registro_A">Necesita <a href="login.php?url=seccion_detalle.php?<?= ereg_replace('&', '~', $_SERVER['QUERY_STRING']) ?>" target="_self">loguearse</a> para escribir mensajes.</td>
    </tr>
  </table>
  <? } ?>
  </form>
<? }//FIN SI ESTAN HABILITADOS LOS COMENTARIOS ?>
  </div>
	<div id="footer"><? include("0_include/0_pie.php"); ?></div>
</div>
<? include("0_include/0_googleanalytics.php"); ?>
</body>
</html>