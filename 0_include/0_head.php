<script type="text/javascript" src="js/0_mootools.js"></script>

<link rel="stylesheet" href="skin/index/css/0_body.css" type="text/css" />
<link rel="stylesheet" href="skin/index/css/0_fonts_tiny.css" type="text/css" />
<link rel="stylesheet" href="skin/index/css/0_fonts.css" type="text/css" />
<link rel="stylesheet" href="skin/index/css/0_especial.css" type="text/css" />
<link rel="shortcut icon" href="" />

<link rel="posicionamiento en buscadores" content="http://www.didstudio.com.ar" />
<meta name="author" content="www.didstudio.com.ar">
<meta name="DC.Creator" content="www.didstudio.com.ar">
<meta name="copyright" content="didstudio.com.ar">
<meta name="Designer" content="didstudio.com.ar">
<meta name="distribution" content="all">
<meta name="robots" content="all">
<meta name="revisit" content="15 days"> 
<meta name="REVISIT-AFTER" content="15 days"> 
<meta http-equiv="Content-Type" content="text/html; ISO-8859-1">
<meta name="geo.position" content="-34.31;-58.30" />
<meta name="geo.country" content="AR" />
<meta name="geo.region" content="AR-B" />
<meta name="geo.placename" content="San Isidro" />
<meta name="distribution" content="global"> 
<meta http-equiv="Content-Language" content="es"/>
<meta name="DC.Language" scheme="RFC1766" content="Spanish" /> 
<? 	
	$meta_tag_title = $rs_dato_sitio['meta_tag_title'];		
	$meta_tag_description = $rs_dato_sitio['meta_tag_description'];	
	$meta_tag_keywords = $rs_dato_sitio['meta_tag_keywords'];
	
if($meta_tag_title_opcional){
	echo '<title>'.$meta_tag_title.' : '.strip_tags($meta_tag_title_opcional).'</title>
';
	echo '<META name=title content="'.$meta_tag_title.' : '.strip_tags($meta_tag_title_opcional).'">
';
}else{
	echo '<title>'.$meta_tag_title.'</title>
';
	echo '<META name=title content="'.$meta_tag_title.'">
';
}

if($meta_tag_description_opcional){
	echo '<META name=description content="'.strip_tags($meta_tag_description_opcional).'">
';
}else{
	echo '<META name=description content="'.$meta_tag_description.'">
';
}
	
if($meta_tag_keywords_opcional){
	echo '<META name=keywords content="'.strip_tags($meta_tag_keywords_opcional).$keywords_meta.'">
';
}else{
	echo '<META name=keywords content="'.$meta_tag_keywords.$keywords_meta.'">
';
}

///////////////////////////////////////
////SISTEMA DE AREA RESTRINGIDA
///////////////////////////////////////
$nombre_pagina = split('.php', basename($_SERVER['PHP_SELF']));


//CARRITO
if(!$_SESSION["iduser_web_session"]){
	if ($nombre_pagina[0] == 'ca_domicilio_envio' || $nombre_pagina[0] == 'ca_domicilio_facturacion' || $nombre_pagina[0] == 'ca_medio_de_cobro' || $nombre_pagina[0] == 'ca_orden_de_compra'){ 
		$url_login = ereg_replace('&', '~', $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);	
		$error = "Para realizar una compra on-line debe estar logueado.<br>Si Usted se encuentra registrado, ingrese su dirección de correo electrónico y contraseña.";  
		echo "<script>window.location.href=('login.php?url=".$url_login."&error=$error')</script>";
	}
}
//FIN CARRITO



if($nombre_pagina[0] != "login" && $_GET['modo'] != "previsualizar"){

	//cambia el & por ~ para que tome toda la ruta como una sola variable
	$url_login = ereg_replace('&', '~', $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);	
	
	//CHECKEO SI LA CARPETA ESTA RESTRINGIDA
	if($idcarpeta){
	
		$query_restringido = "SELECT A.restringido 
		FROM carpeta  A
		WHERE idcarpeta = '$idcarpeta' ";		
		$rs_restringido_1 = mysql_fetch_assoc(mysql_query($query_restringido));
		
		if(!$_SESSION["iduser_web_session"]){
			if ($rs_restringido_1['restringido']=='1'){ 
				$error = "Area restringida exclusiva para usuarios registrados.<br>Si Usted se encuentra registrado, ingrese su dirección de correo electrónico y contraseña.";  
				echo "<script>document.location.href = 'login.php?url=".$url_login."&error=$error';</script>";
			}
		}
		
	}
	
	//CHECKEO SI LA SECCION ESTA RESTRINGIDA
	if($idseccion){
	
		$query_restringido = "SELECT A.restringido 
		FROM seccion  A
		WHERE idseccion = '$idseccion' ";		
		$rs_restringido_2 = mysql_fetch_assoc(mysql_query($query_restringido));
		
		if(!$_SESSION["iduser_web_session"]){
			if ($rs_restringido_2['restringido']=='1'){ 
				$error = "Area restringida exclusiva para usuarios registrados.<br>Si Usted se encuentra registrado, ingrese su dirección de correo electrónico y contraseña.";  
				echo "<script>document.location.href='login.php?url=".$url_login."&error=$error';</script>";
			}
		}
		
	}
	
	//CHECKEO SI EL PRODUCTO ESTA RESTRINGIDO
	if($idproducto){
	
		$query_restringido = "SELECT A.restringido 
		FROM producto  A
		WHERE idproducto = '$idproducto' ";		
		$rs_restringido_3 = mysql_fetch_assoc(mysql_query($query_restringido));
		
		if(!$_SESSION["iduser_web_session"]){
			if ($rs_restringido_3['restringido']=='1'){ 
				$error = "Area restringida exclusiva para usuarios registrados.<br>Si Usted se encuentra registrado, ingrese su dirección de correo electrónico y contraseña.";  
				echo "<script>document.location.href='login.php?url=".$url_login."&error=$error';</script>";
			}
		}
	
	}	
}	
?>