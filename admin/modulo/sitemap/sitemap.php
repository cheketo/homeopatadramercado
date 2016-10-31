<?	 include ("../../0_mysql.php"); 

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Sitemap</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Herramientas Sitemap</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">                              
                              <tr>
                                <td colspan="2" valign="top" class="detalle_medio" ><p>Los principales buscadores se han puesto deacuerdo para <strong>poder definir   donde se encuentra el fichero sitemap.xml desde el archivo robots.txt </strong>con lo que con esto nos ahorramos el tener que estar dando de alta en   nuestras cuentas la direcci&oacute;n de este archivo. Por ejemplo en Webmaster Tools en   Google ya no hara falta indicar la localizaci&oacute;n de nuestro archivo sitemap.xml   ya que ha partir de ahora podremos hacerlo directamente desde nuestro archivo   robots.txt</p>
                                <p>Para hacerlo debemos debes de incluir unicamente la siguiente linea de c&oacute;digo   en el archivo robots.txt :</p>
                                <blockquote>
                                  <p>Sitemap: <a href="<?= $dominio ?>sitemap.xml" target="_blank">
                                  <?= $dominio ?>
                                  sitemap.xml </a></p>
                                </blockquote></td>
                              </tr>                              
                              <tr>
                                <td width="20%" height="10" align="left" valign="top" class="detalle_medio_bold">Buscador<br /></td>
                                <td width="80%" height="10" valign="top" class="detalle_medio_bold">Codigo</td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Ask:</td>
                                <td valign="top" class="detalle_medio"><span class="detalle_medio"><a href="http://submissions.ask.com/ping?sitemap=<?= $dominio ?>sitemap.xml" target="_blank">http://submissions.ask.com/ping?sitemap=
                                <?= $dominio ?>sitemap.xml
                                </a></span></td>
                              </tr>
                              <? if($rs_titular['foto'] != ''){ ?>
                              <? }; ?>
							  <?
							  //VERIFICO EL ANCHO Y ALTO
							  $titular_optimo = true;  
							  if($rs_titular['foto']){
								  $titularsize = getimagesize($ruta_foto.$rs_titular['foto']);
								  if($titularsize[0] != $ancho_adecuado || $titularsize[1] != $alto_adecuado){
									$titular_optimo = false;
								  }
							  }
							  ?>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Google:</td>
                                <td align="left" valign="middle" class="detalle_medio"><a href="http://www.google.com/webmasters/sitemaps/ping?sitemap=<?= $dominio ?>sitemap.xml" target="_blank">http://www.google.com/webmasters/sitemaps/ping?sitemap=<?= $dominio ?>sitemap.xml</a></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio_bold">No esta en funcionamiento <a href="http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?url=<?= $dominio ?>sitemap.xml" target="_blank"></a></td>
                              </tr>
                              <tr>
                                <td align="left" valign="middle" class="detalle_medio">Yahoo!:</td>
                                <td align="left" valign="middle" class="detalle_medio"><a href="http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?url=<?= $dominio ?>sitemap.xml" target="_blank">http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?url=
                                    <?= $dominio ?>
                                sitemap.xml</a></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="middle" class="detalle_medio">P.D. Recuerda que tu archivo sitemap.xml no puede superar los 10Mg ni contener   m&aacute;s de 50.000 URLs distintas. Lo que puedes hacer si esto te sucede es dividirlo   en varias partes.</td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                        <br />
                        <br />
                        <br />
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