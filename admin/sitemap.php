<? 
	include_once("0_mysql.php"); 
	
function clean_string_sitemap($cadena){	
	$cadena = ereg_replace('_', '-', $cadena);
	$cadena = ereg_replace('á', 'a', $cadena);
	$cadena = ereg_replace('é', 'e', $cadena);
	$cadena = ereg_replace('í', 'i', $cadena);
	$cadena = ereg_replace('ó', 'o', $cadena);
	$cadena = ereg_replace('ú', 'u', $cadena);
	$cadena = ereg_replace('ü', 'u', $cadena);
	$cadena = ereg_replace('ñ', 'n', $cadena);
	
	# La función ereg_replace reemplaza todos lo que no sea números o letras
	$cadena = ereg_replace('[^A-Za-z0-9]', '-', $cadena);
	
	# strtolower transforma todo en minúsculas
	$cadena = strtolower($cadena);		
	return $cadena;		
};
	if($_GET['sitemap_actualizar']=='ok'){
		$_SESSION['sitemap_fecha'] == '';
	}
	
	//$sitemap_dominio = "http://localhost/00modulos_css/";
	$sitemap_dominio = $dominio;//viene del 0_mysql	
		
	$sitemap_archivo = '../sitemap.xml';
	$fecha = date("Y-m-d");	
	
	if($_SESSION['sitemap_fecha']){//es para que solo lo cargue una sola vez
		echo 'Se actualizo con exito el archivo de Site Map al '.$_SESSION['sitemap_fecha'].'  <a href="'.$sitemap_archivo.'" target="_blank" ><b> Abrir </b></a><br>';
		echo 'Actualizar el Site Map nuevamente <a href="inicio.php?sitemap_actualizar=ok"><b>Actualizar</b></a>';
	}else{
		$_SESSION['sitemap_fecha'] = $fecha;	
	
$sitemap_arbol = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
';

$query_carpeta = "SELECT B.nombre, A.idcarpeta_padre, A.idcarpeta, A.fecha_modificacion, B.ididioma, C.idsede
FROM carpeta A
INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
INNER JOIN carpeta_sede C ON C.idcarpeta = A.idcarpeta
INNER JOIN idioma E ON E.ididioma = B.ididioma
WHERE A.estado = 1 AND B.estado = 1  AND C.idsede != 0 AND E.publicar = 1 
ORDER BY C.idsede, B.nombre, B.ididioma";
$result_carpeta = mysql_query($query_carpeta);
$num_rows_carpeta = mysql_num_rows($result_carpeta);
while($rs_carpeta = mysql_fetch_assoc($result_carpeta)){ 
	
	/*
	// modificacion que permite que segun la sede tenga un dominio diferente
	switch($rs_carpeta['idsede']){		
		case 1:
		case 2:
		case 4:
		case 12:
		case 14:
			$sitemap_dominio = "http://www.gatodumas.com.ar/";
			break;
		case 13:
			$sitemap_dominio = "http://www.gatodumas.com.uy/";
			break;
		case 3:
			$sitemap_dominio = "http://www.gatodumas.com.co/";
			break;	
	}
	*/
	
	//$sitemap_link = $sitemap_dominio.'carpeta_ver.php?idcarpeta='.$rs_carpeta['idcarpeta'].'&amp;ididioma='.$rs_carpeta['ididioma'].'&amp;idsede='.$rs_carpeta['idsede'].'&amp;titulo='.clean_string_sitemap($rs_carpeta['nombre']);			
	$sitemap_link = $sitemap_dominio.clean_string_sitemap($rs_carpeta['nombre'])."_".$rs_carpeta['ididioma']."_".$rs_carpeta['idsede']."_c_".$rs_carpeta['idcarpeta'].".html";			
	
	
	if($rs_carpeta['fecha_modificacion'] == '0000-00-00'){
		$fecha_modificacion = $fecha;
	}else{
		$fecha_modificacion = $rs_carpeta['fecha_modificacion'];
	}
$sitemap_arbol .= '
  <url>
	<loc>'.$sitemap_link.'</loc>
	<lastmod>'.$fecha_modificacion.'</lastmod>
	<priority>1</priority>	
  </url>';
};	 


$query_seccion = "SELECT A.idseccion, A.fecha_modificacion, B.titulo, B.copete, A.foto, B.ididioma, C.idsede, D.idcarpeta
FROM seccion A
INNER JOIN seccion_idioma_dato B ON B.idseccion = A.idseccion
INNER JOIN seccion_sede C ON C.idseccion = A.idseccion
INNER JOIN seccion_carpeta D ON D.idseccion = A.idseccion
INNER JOIN idioma E ON E.ididioma = B.ididioma
WHERE A.estado = 1 AND B.estado = 1 AND C.idsede != 0 AND E.publicar = 1 
ORDER BY C.idsede, B.titulo, B.ididioma";
$result_seccion = mysql_query($query_seccion);
$num_rows_seccion = mysql_num_rows($result_seccion);

if($num_rows_seccion > 0){ 
while($rs_seccion = mysql_fetch_assoc($result_seccion)){
	
	/*
	// modificacion que permite que segun la sede tenga un dominio diferente
	switch($rs_seccion['idsede']){		
		case 1:
		case 2:
		case 4:
		case 12:
		case 14:
			$sitemap_dominio = "http://www.gatodumas.com.ar/";
			break;
		case 13:
			$sitemap_dominio = "http://www.gatodumas.com.uy/";
			break;
		case 3:
			$sitemap_dominio = "http://www.gatodumas.com.co/";
			break;	
	}
	*/
	
	//$sitemap_link = $sitemap_dominio.'seccion_detalle.php?idcarpeta='.$rs_seccion['idcarpeta'].'&amp;idseccion='.$rs_seccion['idseccion'].'&amp;ididioma='.$rs_seccion['ididioma'].'&amp;idsede='.$rs_seccion['idsede'].'&amp;titulo='.clean_string_sitemap($rs_seccion['titulo']);		
	$sitemap_link = $sitemap_dominio.clean_string_sitemap($rs_seccion['titulo'])."_".$rs_seccion['ididioma']."_".$rs_seccion['idsede']."_s_".$rs_seccion['idcarpeta']."_".$rs_seccion['idseccion'].".html";			
	
	if($rs_seccion['fecha_modificacion'] == '0000-00-00'){
		$fecha_modificacion = $fecha;
	}else{
		$fecha_modificacion = $rs_seccion['fecha_modificacion'];
	}
$sitemap_arbol .= '
  <url>
    <loc>'.$sitemap_link.'</loc>
	<lastmod>'.$fecha_modificacion.'</lastmod>
	<priority>0.8</priority>	
  </url>';
	}		 
}	


$query_producto = "SELECT A.idproducto, A.fecha_modificacion, B.titulo, B.copete, A.foto, B.ididioma, C.idsede, D.idcarpeta
FROM producto A
INNER JOIN producto_idioma_dato B ON B.idproducto = A.idproducto
INNER JOIN producto_sede C ON C.idproducto = A.idproducto
INNER JOIN producto_carpeta D ON D.idproducto = A.idproducto
INNER JOIN idioma E ON E.ididioma = B.ididioma
WHERE A.estado = 1 AND B.estado = 1 AND C.idsede != 0 AND E.publicar = 1 
ORDER BY C.idsede, B.titulo, B.ididioma";
$result_producto = mysql_query($query_producto);
$num_rows_producto = mysql_num_rows($result_producto);

if($num_rows_producto > 0){ 
	while($rs_producto = mysql_fetch_assoc($result_producto)){
	
		/*
	// modificacion que permite que segun la sede tenga un dominio diferente
		switch($rs_producto['idsede']){		
			case 1:
			case 2:
			case 4:
			case 12:
			case 14:
				$sitemap_dominio = "http://www.gatodumas.com.ar/";
				break;
			case 13:
				$sitemap_dominio = "http://www.gatodumas.com.uy/";
				break;
			case 3:
				$sitemap_dominio = "http://www.gatodumas.com.co/";
				break;
		
		}*/
		
		//$sitemap_link = $sitemap_dominio.'producto_detalle.php?idcarpeta='.$rs_producto['idcarpeta'].'&amp;idproducto='.$rs_producto['idproducto'].'&amp;ididioma='.$rs_producto['ididioma'].'&amp;idsede='.$rs_producto['idsede'].'&amp;titulo='.clean_string_sitemap($rs_producto['titulo']);		
		$sitemap_link = $sitemap_dominio.clean_string_sitemap($rs_producto['titulo'])."_".$rs_producto['ididioma']."_".$rs_producto['idsede']."_p_".$rs_producto['idcarpeta']."_".$rs_producto['idproducto'].".html";			
	
		if($rs_producto['fecha_modificacion'] == '0000-00-00'){
			$fecha_modificacion = $fecha;
		}else{
			$fecha_modificacion = $rs_producto['fecha_modificacion'];
		}
$sitemap_arbol .= '
  <url>
    <loc>'.$sitemap_link.'</loc>
	<lastmod>'.$fecha_modificacion.'</lastmod>
	<priority>0.8</priority>	
  </url>';
	}		 
}	

$sitemap_arbol .= '
</urlset>';

//echo "carpetas: ".$num_rows_carpeta."<br>";
//echo "secciones: ".$num_rows_seccion."<br>";
//echo "productos: ".$num_rows_producto."<br>";
$num_rows_total = $num_rows_carpeta + $num_rows_seccion + $num_rows_producto;
//echo "total lineas en arbol: ".$num_rows_total."<br><br><br>";
//echo $sitemap_arbol;

// Asegurarse primero de que el archivo existe y puede escribirse sobre el.
if (is_writable($sitemap_archivo)) {

    // En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adicion.
    // El apuntador de archivo se encuentra al final del archivo, asi que
    // alli es donde ira $contenido cuando llamemos fwrite().
    if (!$sitemap_gestor = fopen($sitemap_archivo, 'a')) {
         echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
    }else{
		//borra el contenido del archivo
		ftruncate ($sitemap_gestor,0);
	}
	
    // Escribir $contenido a nuestro arcivo abierto.
    if (fwrite($sitemap_gestor, $sitemap_arbol) === FALSE) {
        echo "No se puede escribir al archivo ($nombre_archivo)";
        exit;
    }
    
    echo '&Eacute;xito, se escribi&oacute el archivo ('.$sitemap_archivo.') <a href="'.$sitemap_archivo.'" target="_blank" > Abrir </a>';
    
    fclose($sitemap_gestor);

} else {
    echo "No se puede escribir sobre el archivo Site Map | $nombre_archivo";
}

}//si se cargo ya el sitemap por fecha

	echo '<br>Si desea solicitar a google que revise nuevamente su sitemap <a href="http://www.google.com/webmasters/sitemaps/ping?sitemap='.$sitemap_dominio.'sitemap.xml" target="_blank" > Click Aqui </a>';
?> 
