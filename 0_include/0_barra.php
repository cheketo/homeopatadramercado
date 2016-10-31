<? include_once("0_mysql.php"); include_once("0_function.php");  ?>
<link href="skin/index/css/0_barra.css" rel="stylesheet" type="text/css" />
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
		show: -1
	}, $('barra_menu'));

});

function reiniciarAcc(pInd){
	accordion.display(pInd);
}
</script>
<div id="barra_menu">
	<?

	$cantidad_caracteres_por_boton = 30;
	
	//NIVEL 1
	$query_barra_1 = "SELECT A.idbarra_menu, A.link, A.idbarra_menu, A.tipo, B.titulo, A.restringido, A.target, B.foto, A.id
	FROM barra_menu A
	INNER JOIN barra_menu_idioma B ON A.idbarra_menu = B.idbarra_menu
	INNER JOIN barra_menu_sede C ON A.idbarra_menu = C.idbarra_menu
	WHERE A.idbarra_padre = '0' AND A.estado = '1' AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]' 
	ORDER BY A.orden ASC ";
	$result_barra_1 = mysql_query($query_barra_1);
	while($rs_barra_1 = mysql_fetch_assoc($result_barra_1)){
	
		//SEGUNDO NIVEL
		$query_barra = "SELECT A.link, A.idbarra_menu, A.tipo, B.titulo, A.restringido, A.target, B.foto, A.id
		FROM barra_menu A
		INNER JOIN barra_menu_idioma B ON A.idbarra_menu = B.idbarra_menu
		INNER JOIN barra_menu_sede C ON A.idbarra_menu = C.idbarra_menu
		WHERE A.idbarra_padre = '$rs_barra_1[idbarra_menu]' AND A.estado = '1' AND B.ididioma = '$_SESSION[ididioma_session]' AND C.idsede = '$_SESSION[idsede_session]'
		ORDER BY A.orden ASC ";
		$result_barra = mysql_query($query_barra);
		$num_rows = mysql_num_rows($result_barra);
		
		//SI NO CONTIENE SUBNIVELES
		if($num_rows == 0){
			
			switch($rs_barra_1['tipo']){
					case 1: //CARPETA
						
						if($rs_barra_1['restringido'] == 1){
							if($_SESSION['mail_session'] != ""){
								//CARPETA
								echo '<a href="'.variable_ididioma_idsede($rs_barra_1['link']).'" target="'.$rs_barra_1['target'].'"><div class="boton">'.$rs_barra_1['titulo'].'/div></a>';
							}
						}else{
							//CARPETA
							echo '<a href="'.variable_ididioma_idsede($rs_barra_1['link']).'" target="'.$rs_barra_1['target'].'"><div class="boton" >'.html_entity_decode($rs_barra_1['titulo']).'</div></a>';
						}
						break;
						
					case 2: //SECCION
					case 3: //PRODUCTO
					case 4: //LINK
					
						//VERIFICACION DE LONGITUD
						if(strlen($rs_barra_1['titulo'])<27){
							$class = "boton";
						}else{
							$class = "boton_doble";
						}
						
						//VERIFICACION DE RESTRINGIDO
						if($rs_barra_1['restringido'] == 1){
							if($_SESSION['mail_session'] != ""){
								//BOTON
								echo '<a href="'.variable_ididioma_idsede($rs_barra_1['link']).'" target="'.$rs_barra_1['target'].'"><div class="'.$class.'">'.$rs_barra_1['titulo'].'</div></a>';
							}
						}else{
							//BOTON
							echo '<a href="'.variable_ididioma_idsede($rs_barra_1['link']).'" target="'.$rs_barra_1['target'].'"><div class="'.$class.'">'.$rs_barra_1['titulo'].'</div></a>';
						}
						
						break;
						
					case 5:	//IMAGEN
					
						//VERIFICACION DE RESTRINGIDO
						if($rs_barra_1['restringido'] == 1){
							if($_SESSION['mail_session'] != ""){
								
								//BOTON
								if($rs_barra_1['link']){
									echo '<a href="'.variable_ididioma_idsede($rs_barra_1['link']).'" target="'.$rs_barra_1['target'].'"><img src="imagen/barra/'.$rs_barra_1['foto'].'" border="0" ></a><br>';
								}else{
									echo '<img src="imagen/barra/'.$rs_barra_1['foto'].'"><br>';
								}
								
							}
						}else{
						
							//BOTON
							if($rs_barra_1['link']){
								echo '<a href="'.variable_ididioma_idsede($rs_barra_1['link']).'" target="'.$rs_barra_1['target'].'"><img src="imagen/barra/'.$rs_barra_1['foto'].'" border="0" ></a><br>';
							}else{
								echo '<img src="imagen/barra/'.$rs_barra_1['foto'].'"><br>';
							}
						}
						break;
			}					
			
		}else{// SI CONTIENE SUBNIVELES ENTONCES CREA LA ESTRUCTURA DEL SUBMENU
		
			echo '<a href="javascript:void();" ><div class="titulo"><span class="titulo_parche">'.$rs_barra_1['titulo'].'</span></div></a>';

			echo '<div class="submenu">';
			while($rs_barra = mysql_fetch_assoc($result_barra)){
			
				switch($rs_barra['tipo']){
					case 1: //CARPETA
						
						if($rs_barra['restringido'] == 1){
							if($_SESSION['mail_session'] != ""){
								//CARPETA
								echo '<a href="'.variable_ididioma_idsede($rs_barra['link']).'" target="'.$rs_barra['target'].'"><div class="submenu_boton">'.$rs_barra['titulo'].'</div></a>';
							}
						}else{
							//CARPETA
							echo '<a href="'.variable_ididioma_idsede($rs_barra['link']).'" target="'.$rs_barra['target'].'"><div class="submenu_boton" >'.html_entity_decode($rs_barra['titulo']).'</div></a>';
						}
						break;
						
					case 2: //SECCION
					case 3: //PRODUCTO
					case 4: //LINK
					
						//VERIFICACION DE LONGITUD
						if(strlen($rs_barra['titulo'])<27){
							$class = "boton";
						}else{
							$class = "boton_doble";
						}
						
						//VERIFICACION DE RESTRINGIDO
						if($rs_barra['restringido'] == 1){
							if($_SESSION['mail_session'] != ""){
								//BOTON
								echo '<a href="'.variable_ididioma_idsede($rs_barra['link']).'" target="'.$rs_barra['target'].'"><div class="submenu_boton">'.$rs_barra['titulo'].'</div></a>';
							}
						}else{
							//BOTON
							echo '<a href="'.variable_ididioma_idsede($rs_barra['link']).'" target="'.$rs_barra['target'].'"><div class="submenu_boton">'.$rs_barra['titulo'].'</div></a>';
						}
						
						break;
						
					case 5:	//IMAGEN
					
						//VERIFICACION DE RESTRINGIDO
						if($rs_barra['restringido'] == 1){
							if($_SESSION['mail_session'] != ""){
								
								//BOTON
								if($rs_barra['link']){
									echo '<a href="'.variable_ididioma_idsede($rs_barra['link']).'" target="'.$rs_barra['target'].'"><img src="imagen/barra/'.$rs_barra['foto'].'" border="0" ></a><br>';
								}else{
									echo '<img src="imagen/barra/'.$rs_barra['foto'].'"><br>';
								}
								
							}
						}else{
						
							//BOTON
							if($rs_barra['link']){
								echo '<a href="'.variable_ididioma_idsede($rs_barra['link']).'" target="'.$rs_barra['target'].'"><img src="imagen/barra/'.$rs_barra['foto'].'" border="0" ></a><br>';
							}else{
								echo '<img src="imagen/barra/'.$rs_barra['foto'].'"><br>';
							}
						}
						break;
				}
			
			}
			echo '</div>';
			
		}//FIN SEGUNDO NIVEL
		
	}//FIN NIVEL 1
	
?><br />
<? 
	if($rs_dato_sitio['ca_carrito_usar'] == 1){
		include_once("0_include/0_carrito_estado.php"); 
		echo "<br />";
	}
?>
<? include_once("0_include/0_login_estado.php"); ?>
</div>