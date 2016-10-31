<?
	require_once("0_mysql.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="css/0_body.css" rel="stylesheet" type="text/css" />
<link href="css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("0_head.php"); ?>
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style>
</head>
<body>
<div id="header">
  <? include("0_top.php"); ?>
</div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="10" style="background-image:url(imagen/cuerpo/fondo_cuerpo_centro.jpg); background-repeat:no-repeat;" >
        <tr>
          <td height="400" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="40" align="left" valign="bottom" class="titulo_grande_bold">Panel de Control </td>
            </tr>
            <tr>
              <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
            </tr>
          </table>
            <? 				  
				  //Fotos Extra horizontal
				  $query_icono = "SELECT A.*
				  FROM admin_menu A
				  INNER JOIN admin_menu_perfil B ON B.idadmin_menu = A.idadmin_menu
				  WHERE A.estado = '1' AND A.icono = 1 AND B.iduser_admin_perfil = '$HTTP_SESSION_VARS[idcusuario_perfil_log]'
				  ORDER BY A.orden";
				  $result_icono = mysql_query($query_icono);
				  $cant_icono = mysql_num_rows($result_icono);//indica la cantidad de fotos
				  
				  if($cant_icono > 0){//si la cantidad de iconos es 0, no lo muestra
			?>
            <table width="100%" border="0" cellpadding="5" cellspacing="5" align="center">
              <tr valign="top">
                <? 						
						$vuelta_icono = 1;//indicador inicial de vueltas, para el sistema de columnas
					  while( $rs_icono = mysql_fetch_assoc($result_icono) ){//while de foto extra horizontal		  					  
					
					?>
                <td width="250" align="center" valign="top" class="ejemplo_12px"><a href="modulo/user_admin/usuario_admin_nuevo.php"></a>
                    <table width="200" border="0" cellpadding="5" cellspacing="0" class="detalle_medio_bold" style=" border-bottom:1px; border-bottom-color:#CCCCCC; border-bottom-style:solid">
                      <tr>
                        <td align="left">                          .<?= $rs_icono['titulo']; ?>                        </td>
                      </tr>
                    </table>
                    <table width="200" border="0" cellpadding="5" cellspacing="0" bgcolor="#F7F7F7">
                      <tr>
                        <td width="57" align="center" valign="middle" class="detalle_chico"><img src="imagen/admin_panel/<?= $rs_icono['foto']; ?>" border="0" /></td>
                        <td width="123" align="left" valign="middle" class="detalle_chico"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                          <tr>
                            <td align="left" valign="middle" class="detalle_medio" style="font-size:11px"><?
						  	//Opciones para cada boton									
							$query_opcion = "SELECT A.*
							FROM admin_opcion A
							INNER JOIN admin_opcion_perfil B ON B.idadmin_opcion = A.idadmin_opcion
							WHERE A.idadmin_menu = $rs_icono[idadmin_menu] AND B.iduser_admin_perfil = '$HTTP_SESSION_VARS[idcusuario_perfil_log]'
							ORDER BY A.orden";
							$result_opcion = mysql_query($query_opcion);		
							while ($rs_opcion = mysql_fetch_assoc($result_opcion)){
                          		echo ' <b>.</b> <a href="'.$rs_opcion['link'].'">'.$rs_opcion['titulo'].'</a><br>';
							}
						  
						  ?></td>
                          </tr>
                        </table></td>
                      </tr>
                </table></td>
                <?		if($vuelta_icono == 3){ //catidad de fotos extras por fila
									echo "</tr><tr>";
									$vuelta_icono = 1;
								}else{
									$vuelta_icono++;
								};							 
						}; //FIN WHILE foto extra ?>
              </tr>
            </table>
           
              <? } ?>          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="detalle_chico"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio_bold" style=" border-bottom:1px; border-bottom-color:#CCCCCC; border-bottom-style:solid">
  <tr>
    <td align="left"> .Site Map
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#F7F7F7">
  <tr>
    <td width="48" align="center" valign="middle" class="detalle_chico"><img src="imagen/iconos/sitemap-icon.jpg" width="36" height="40" border="0" /></td>
    <td width="624" height="65" align="left" valign="middle" class="detalle_chico"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="middle" class="detalle_medio" style="font-size:11px"><? require_once("sitemap.php"); ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<? 
	$query = "SELECT usa_rating FROM seccion_parametro WHERE idseccion_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query));
	if($rs_parametro['usa_rating'] == 1){ 
?>
<br />
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio_bold" style=" border-bottom:1px; border-bottom-color:#CCCCCC; border-bottom-style:solid">
  <tr>
    <td align="left"> <p>.StarRating </p>
      </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#F7F7F7">
  <tr>
    <td width="48" align="center" valign="middle" class="detalle_chico"><img src="imagen/iconos/favorite.png" width="48" height="48" border="0" /></td>
    <td width="624" height="65" align="left" valign="middle" class="detalle_chico"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="middle" class="detalle_medio" style="font-size:11px"><? require_once("rating.php"); ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<? } ?>
</td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>