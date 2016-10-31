<? if (file_exists("modulo/0_barra/0_barra_acordion.css")){ ?>
		<link href="modulo/0_barra/0_barra_acordion.css" rel="stylesheet" type="text/css" />
		<script language="javascript" type="text/javascript" src="js/0_mootools.js"></script>
<? 		$ruta = "";
	}else if(file_exists("../../modulo/0_barra/0_barra_acordion.css")){ ?>
		<link href="../../modulo/0_barra/0_barra_acordion.css" rel="stylesheet" type="text/css" />
		<script language="javascript" type="text/javascript" src="../../js/0_mootools.js"></script>
	<? $ruta = "../../"; 
	} ?>


<script type="text/javascript">
var accordion;
window.addEvent('domready', function(){

	accordion = new Accordion('div.titulo', 'div.submenu', {
		opacity: false,
		onActive: function(toggler, element){
			toggler.setStyle('color', '#ff3300');
		},
	 
		onBackground: function(toggler, element){
			toggler.setStyle('color', '#666');
		},
		show: <? if($desplegarbarra){ echo $desplegarbarra; }else{ echo "-1"; } ?>
	}, $('barra_menu'));

});

function reiniciarAcc(pInd){
	accordion.display(pInd);
}
</script>


<table width="100%" border="0" cellspacing="0" cellpadding="6">
  <tr>
    <td><div id="barra_menu">
	<?
	
	$query_barra = "SELECT A.idadmin_menu, A.titulo, A.link
	FROM admin_menu A
	LEFT JOIN admin_menu_perfil B ON B.idadmin_menu = A.idadmin_menu
	WHERE A.estado = 1 AND A.idadmin_menu_padre = '0' AND B.iduser_admin_perfil = $_SESSION[idcusuario_perfil_log] AND A.icono = '2'
	ORDER BY orden ASC";
	$result_barra = mysql_query($query_barra);
	
	while($rs_barra = mysql_fetch_assoc($result_barra)){
						
		$query_sub = "SELECT A.idadmin_menu, A.titulo, A.link 
		FROM admin_menu A
		LEFT JOIN admin_menu_perfil B ON B.idadmin_menu = A.idadmin_menu
		WHERE A.idadmin_menu_padre = $rs_barra[idadmin_menu] AND A.estado = '1' AND B.iduser_admin_perfil = $_SESSION[idcusuario_perfil_log] AND A.icono = '2'
		ORDER BY orden ASC";
		$result_sub = mysql_query($query_sub);
		$num_rows = mysql_num_rows($result_sub);
		
		if($num_rows == 0){
			
			if($rs_barra['link']){
				echo '<a href="'.$ruta.$rs_barra['link'].'" ><div class="boton"><span class="boton_titulo">&nbsp;'.strtoupper($rs_barra['titulo']).'</span></div></a>'; 
			}else{
				echo '<a href="javascript:;" ><div class="boton"><span class="boton_titulo">&nbsp;'.strtoupper($rs_barra['titulo']).'</span></div></a>'; 
			}
			
		}else{
			echo '<a href="javascript:void();" ><div class="titulo"><span class="boton_titulo">&nbsp;'.strtoupper($rs_barra['titulo']).'</span></div></a>';

			echo '<div class="submenu">';
			while($rs_sub = mysql_fetch_assoc($result_sub)){
			
				if($rs_sub['link']){
					echo '<a href="'.$ruta.$rs_sub['link'].'" ><div class="submenu_boton"><span class="submenu_texto">&nbsp; &bull; '.$rs_sub['titulo'].'</span></div></a>';
				}else{
					echo '<a href="javascript:;" ><div class="submenu_boton"><span class="submenu_texto_titulo">&nbsp;&nbsp;  '.$rs_sub['titulo'].'</span></div></a>';
					
					$query_barra_sub_subcat = "SELECT A.idadmin_menu, A.titulo, A.link 
					FROM admin_menu A
					LEFT JOIN admin_menu_perfil B ON B.idadmin_menu = A.idadmin_menu
					WHERE A.idadmin_menu_padre = $rs_sub[idadmin_menu] AND A.estado = '1' AND B.iduser_admin_perfil = $_SESSION[idcusuario_perfil_log] AND A.icono = '2'
					ORDER BY A.orden ASC";
					$result_barra_sub_subcat = mysql_query($query_barra_sub_subcat);
					
					while($rs_barra_sub_subcat = mysql_fetch_assoc($result_barra_sub_subcat)){
						echo '<a href="'.$ruta.$rs_barra_sub_subcat['link'].'" ><div class="submenu_boton"><span class="submenu_texto">&nbsp;&nbsp;&nbsp; &bull; '.$rs_barra_sub_subcat['titulo'].'</span></div></a>';
					}
				}
			}
			echo '</div>';
		}
					

	}
	
	?>
</div><img src="<?= $ruta ?>imagen/cuerpo/fondo_boton_barra_pie.jpg" /></td>
  </tr>
</table>