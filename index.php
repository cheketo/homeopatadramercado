<? include_once("0_include/0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<? include_once("0_include/0_head.php"); ?>
</head>

<body>
<div id="header"><? include_once("0_include/0_top.php"); ?></div>
<div id="cuerpo">
  <div id="contenido" align="center">

	<div id="titulo-bienvenida" >
	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="56">
        <param name="movie" value="skin/index/swf/titulo_chico.swf" />
        <param name="quality" value="high" />
        <param name="FlashVars" value="titulo=Bienvenido" />
        <param name="wmode" value="transparent" />
        <embed src="skin/index/swf/titulo.swf" FlashVars="titulo=Bienvenido" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="700" height="56"></embed>
      </object>
    </div>
    <div id="titulo-articulos">
	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="56">
        <param name="movie" value="skin/index/swf/titulo_chico.swf" />
        <param name="quality" value="high" />
        <param name="FlashVars" value="titulo=Art&iacute;culos" />
        <param name="wmode" value="transparent" />
        <embed src="skin/index/swf/titulo.swf" FlashVars="titulo=Art&iacute;culos" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="700" height="56"></embed>
      </object>
    </div>

    <div id="txt-bienvenida" class="Arial_12px_Gris_Line17">
      <strong>Soy la Dra. Silvia Mercado y me es grato recibirlo en mi   p&aacute;gina.</strong>       Seguramente lleg&oacute; hasta aqu&iacute; buscando una medicina diferente a la   convencional.      &iexcl;Lo felicito!, usted est&aacute; dando el primer paso para   encontrarse con la Homeopat&iacute;a Unicista Hahnemanniana, una medicina descubierta   hace 200 a&ntilde;os por el Dr. Christian Samuel Hahnemann.<br />
      <br />
      Con su visi&oacute;n   vitalista, Hahnemann consider&oacute; al ser humano dotado de un cuerpo f&iacute;sico y una   fuerza o principio vital que le da vida y que en estado de equilibrio dispensa   salud pero al desequilibrarse, enferma. El estr&eacute;s de la vida cotidiana, tanto   emocional (preocupaciones, penas, angustias, temores), como f&iacute;sico o qu&iacute;mico   (fr&iacute;o, calor, poluci&oacute;n ambiental, efectos adversos de ciertos medicamentos,   etc.), va desvitaliz&aacute;ndolo y aparecen las enfermedades.<br />
      <br />
      Los remedios   homeop&aacute;ticos, elaborados con una t&eacute;cnica especial de diluci&oacute;n y sucusi&oacute;n y   utilizando sustancias naturales de los tres reinos de la Naturaleza, ejercen su   acci&oacute;n sobre dicho principio intentando estabilizar al organismo en su conjunto   para restituir el estado de sanidad, sin efectos adversos.<br />
      <br />
      Para mayor   informaci&oacute;n est&aacute;n a su disposici&oacute;n algunos art&iacute;culos en esta misma p&aacute;gina y,   desde ya, puede contactarse conmigo ante cualquier otra aclaraci&oacute;n. Que tenga un d&iacute;a pleno de vitalidad.<br />
    </div>
    <div id="txt-articulos" >
	<?  $query_articulos = "SELECT * FROM seccion_idioma_dato ORDER BY idseccion DESC LIMIT 2";
		$result_articulos = mysql_query($query_articulos);
		$cont=0;
		while($rs_articulos=mysql_fetch_assoc($result_articulos)){
			$cont++;
	?>

   	  <div id="titular-articulo-<?=$cont?>" class="Arial_18px_Celeste" style="margin-bottom:15px; "><a href="seccion_detalle.php?ididioma=1&idsede=1&idseccion=<?=$rs_articulos['idseccion']?>&idcarpeta=42" style="text-decoration:none;"><span class="Arial_18px_Celeste"><?=$rs_articulos['titulo']?></span></a></div>
      <div id="copete-articulo-<?=$cont?>" class="Arial_12px_Gris_Line17" style="margin-bottom:15px; "><?=$rs_articulos['copete']?></div>
      <div id="leermas-articulo-<?=$cont?>" class="Arial_12px_CelesteClaro" style="margin-bottom:15px; "><a href="seccion_detalle.php?ididioma=1&idsede=1&idseccion=<?=$rs_articulos['idseccion']?>&idcarpeta=42" style="text-decoration:none;"><span class="Arial_12px_CelesteClaro"><u><em>leer m&aacute;s &raquo;</em></u></span></a></div>
     <?
	 }
	 ?>
    </div>
    <div style="width:100%; border-bottom:1px solid #c7c7c7; height:0px; clear:both; margin:0px;"></div>

    <div id="titulo-consultorios" >
	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="56">
        <param name="movie" value="skin/index/swf/titulo_chico.swf" />
        <param name="quality" value="high" />
        <param name="FlashVars" value="titulo=Consultorios" />
        <param name="wmode" value="transparent" />
        <embed src="skin/index/swf/titulo.swf" FlashVars="titulo=Consultorios" wmode="transparent" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="700" height="56"></embed>
      </object>
    </div>

    <div style=" width:187px; float:left; padding-top:50px;"><img src="imagen/varios/consultorios.jpg" width="187" height="122" /></div>
    <div style=" width:582px; float:left; border-left:1px solid #c7c7c7; text-align:left;">

      <div id="titular-caballito" class="Arial_18px_Celeste"><span style="font-size:12px;">en</span> Caballito /</div>

      	<div id="detalle-consultorio">
        	<div class="Arial_12px_Celeste">Direcci&oacute;n:</div>
            <div class="Arial_12px_Gris">
            	Rosario 441. Piso 5, <br />Depto "B". Ciudad Aut. de Buenos Aires.
			      <br />
                Tel&eacute;fono: 4902-2947.
            </div>
      </div>
        <div id="detalle-consultorio">
        	<div class="Arial_12px_Celeste">Turnos en el:</div>
            <div class="Arial_12px_Gris">
            	4631-1833 de Lunes a Viernes ma&ntilde;ana y tarde.
			</div>
        </div>
        <div id="detalle-consultorio">
        	<div class="Arial_12px_Celeste">D&iacute;as de atenci&oacute;n:</div>
            <div class="Arial_12px_Gris">
            	&raquo; Martes de 16 a 19.30hs.<br />
                &raquo; Miercoles de 15 a 19.30hs.<br />
                &raquo; Jueves de 9 a 12.30hs.
			</div>
        </div>


      <div style="clear:both; height:40px;"></div>

      <div id="titular-palermo" class="Arial_18px_Celeste"><span style="font-size:12px;">en</span> Palermo /</div>

      	<div id="detalle-consultorio">
        	<div class="Arial_12px_Celeste">Direcci&oacute;n:</div>
            <div class="Arial_12px_Gris">
            	Av. Santa Fe 3778. Piso 6, Depto 604. Ciudad Aut. de Buenos Aires.<br />Tel&eacute;fono: 4831-9240.
            </div>
      </div>
        <div id="detalle-consultorio">
        	<div class="Arial_12px_Celeste">Turnos en el:</div>
            <div class="Arial_12px_Gris">
            	4831-9240 de Lunes a Viernes de 15 a 19 hs.			</div>
      </div>
        <div id="detalle-consultorio">
        	<div class="Arial_12px_Celeste">D&iacute;as de atenci&oacute;n:</div>
            <div class="Arial_12px_Gris">
            	&raquo; Jueves de 14 a 17.30hs.
			</div>
        </div>

    </div>


  </div>
  <div id="footer" style="margin-top:10px;"><? include("0_include/0_pie.php"); ?></div>
</div>
<? include("0_include/0_googleanalytics.php"); ?>
</body>
</html>