<? 
	if(file_exists("modulo/0_barra/0_barra_acordion.css")){
		$ruta = ""; 
	}else if(file_exists("../../modulo/0_barra/0_barra_acordion.css")){
		$ruta = "../../"; 
	}
?>

<link href="css/0_body.css" rel="stylesheet" type="text/css" />
<link href="css/0_fonts.css" rel="stylesheet" type="text/css" />
<div id="marco_top_izq"></div>
<div id="in_header">
  <table width="100%" height="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td rowspan="2" align="left" valign="bottom" ><span style="font-size:16px; color:#FFFFFF; font-family:Georgia, 'Times New Roman', Times, serif;">&nbsp; <img src="<?= $ruta ?>imagen/Mhax_logo.png"  />
        
    </span></td>
    <td align="right" valign="middle"><span class="detalle_medio_bold_white">
        <?= $_SESSION['usuario_log'] ?>
     | </span>
    <a href="<? if(file_exists("logout.php")){ echo ""; }else if(file_exists("../../logout.php")){ echo "../../"; } ?>logout.php" target="_parent" class="link_naranja_12px">Cerrar Sesi&oacute;n</a></td>
  </tr>
  <tr>
    <td align="right" valign="bottom" class="detalle_medio_bold_white"><span class="detalle_medio_bold_white">
      <?= $rs_dato_sitio['admin_titulo'] ?>
	  <?
	  	
		if($HTTP_SESSION_VARS['idsede_log'] != 0){
			$query = "SELECT titulo FROM sede WHERE idsede = '$HTTP_SESSION_VARS[idsede_log]' ";
			$rs_sede_name = mysql_fetch_assoc(mysql_query($query));
			echo " - ".$rs_sede_name['titulo'];
		}
	  ?><br />
    </span>
      <? 
	  /*
	echo $rs_dato_sitio['admin_titulo']."<br>";
	/////////////////////////////////////////////////////////////////////////////////////
	//MANUAL DE AYUDA PARA EL ADMIN
	/////////////////////////////////////////////////////////////////////////////////////
	$url_manual_top = array_pop(split("/",$_SERVER['PHP_SELF']));
	//echo "count: ".$url_manual;
	
	//Busca si existe ayuda para este php
	$query_manual_top = "SELECT A.*
	FROM ma_manual  A
	WHERE A.estado = 1 AND A.url_manual = '$url_manual_top'";	
	$result_manual_top = mysql_query($query_manual_top);
	$row_num_manual_top = mysql_num_rows($result_manual_top);
	$rs_manual_top = mysql_fetch_assoc($result_manual_top);
	
	if($row_num_manual_top > 0 ){
		if(file_exists("modulo/manual/ma_manual_ver_ind.php")){
			echo '<a href="modulo/manual/ma_manual_ver_ind.php?url_manual='.$url_manual_top.'" class="link_naranja_12px" target="_blank">Ver Ayuda</a>';
			if($_SESSION[idcusuario_perfil_log] == '1' ){
				echo '&nbsp;|&nbsp;<a href="modulo/manual/ma_manual_editar.php?url_manual_nuevo='.$url_manual_top.'" class="link_naranja_12px" target="_blank">Modificar Ayuda</a>';
			}
		}else if(file_exists("../../modulo/manual/ma_manual_ver_ind.php")){
			echo '<a href="../../modulo/manual/ma_manual_ver_ind.php?url_manual='.$url_manual_top.'" class="link_naranja_12px" target="_blank">Ver Ayuda</a>';
			if($_SESSION[idcusuario_perfil_log] == '1' ){
				echo '&nbsp;|&nbsp;<a href="../../modulo/manual/ma_manual_editar.php?url_manual_nuevo='.$url_manual_top.'" class="link_naranja_12px" target="_blank">Modificar Ayuda</a>';
			}
		}
	}else{
		if(file_exists("modulo/manual/ma_manual_nuevo.php")){
			if($_SESSION[idcusuario_perfil_log] == '1' ){
				echo '<a href="modulo/manual/ma_manual_nuevo.php?url_manual_nuevo='.$url_manual_top.'" class="link_naranja_12px" target="_blank">Crear Ayuda</a>';
			}
		}else if(file_exists("../../modulo/manual/ma_manual_nuevo.php")){
			if($_SESSION[idcusuario_perfil_log] == '1' ){
				echo '<a href="../../modulo/manual/ma_manual_nuevo.php?url_manual_nuevo='.$url_manual_top.'" class="link_naranja_12px" target="_blank">Crear Ayuda</a>';
			}
		}
	}
*/
?></td>
  </tr>
</table>
</div>
<div id="marco_top_der"></div>
