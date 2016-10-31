<?
	require ("../../0_mysql.php");
	require ("../../js/pdfClass/class.ezpdf.php");
	
	$idproducto = $_GET['idproducto'];
	$ididioma = $_GET['ididioma'];
	
	function replace_chars($cadena){ 
	
		$cadena = ereg_replace('&aacute;', 'á', $cadena);
		$cadena = ereg_replace('&Aacute;', 'Á', $cadena);
		
		$cadena = ereg_replace('&eacute;', 'é', $cadena);
		$cadena = ereg_replace('&Eacute;', 'É', $cadena);
		
		$cadena = ereg_replace('&iacute;', 'í', $cadena);
		$cadena = ereg_replace('&Iacute;', 'Í', $cadena);
		
		$cadena = ereg_replace('&oacute;', 'ó', $cadena);
		$cadena = ereg_replace('&Oacute;', 'Ó', $cadena);
		
		$cadena = ereg_replace('&uacute;', 'ú', $cadena);
		$cadena = ereg_replace('&Uacute;', 'Ú', $cadena);
		
		$cadena = ereg_replace('&ntilde;', 'ñ', $cadena);
		$cadena = ereg_replace('&Ntilde;', 'Ñ', $cadena);
		
		$cadena = ereg_replace('&nbsp;', ' ', $cadena);

		return $cadena;
		
	};
	
	//CREO PDF, SETEO FONT Y MARGENES
	$pdf =& new Cezpdf("a4");
	$pdf->selectFont("../../js/pdfClass/fonts/Helvetica.afm");
	$pdf->ezSetCmMargins(1,1,1.5,1.5); 
	
	//CONSULTA
	$query = "SELECT 
	  A.idproducto
	, A.foto
	, A.idproducto_marca
	, A.precio
	, A.idca_iva
	, B.titulo
	, B.copete
	, B.estado AS estado_idioma
	, A.estado AS estado_producto
	, B.detalle
	, D.idcarpeta
	FROM producto A
	LEFT JOIN producto_idioma_dato B ON B.idproducto = A.idproducto
	LEFT JOIN producto_carpeta D ON D.idproducto = A.idproducto
	WHERE A.idproducto = '$idproducto' AND B.ididioma = '$ididioma'";
	$queEmp = mysql_query($query);
	$rs_producto = mysql_fetch_assoc($queEmp); 
	
	
	$titulo = html_entity_decode($rs_producto['titulo'], ENT_QUOTES)." \n";
	$copete = html_entity_decode($rs_producto['copete'], ENT_QUOTES)." \n\n\n";
	
	$detalle = replace_chars(strip_tags(str_replace("<br />","\n", html_entity_decode($rs_producto['detalle'], ENT_QUOTES))))." \n";
	
	$pdf->ezText($titulo, 16);
	$pdf->ezText($copete, 12);
	$pdf->ezText($detalle, 9);

	
	$pdf->ezStream();
	
?>