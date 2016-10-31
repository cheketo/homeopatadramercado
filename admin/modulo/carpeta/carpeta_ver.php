<? 
	include ("../../0_mysql.php"); 
	
	//SI VIENE IDCARPETA POR GET
	if($_GET['idcarpeta']){
		$filtro_carpeta = " A.idcarpeta = '$_GET[idcarpeta]' ";
	}else{
		$filtro_carpeta = " A.idcarpeta_padre = 0 ";
	}
	
	//SI EL USUARIO ADMIN ES NIVEL 1
	if($_SESSION['idcusuario_perfil_log']=='1'){
		$usuario_perfil = 1;
	}else{
		$usuario_perfil = 2;
	}
	
	//CAMBIO DE ESTADO
	$estado = $_POST['estado'];
	$idcarpeta = $_POST['idcarpeta'];
	
	if($estado != "" && $idcarpeta != ""){
		$query = "UPDATE carpeta
		SET estado = $estado
		WHERE idcarpeta = '$idcarpeta'	
		LIMIT 1";
		mysql_query($query);
	}
	
	//CAMBIO DEL CAMPO RESTRINGIDO
	$restringido = $_POST['restringido'];
	$idcarpeta = $_POST['idcarpeta'];
	
	if($restringido != "" && $idcarpeta != ""){
			$query = "UPDATE carpeta
			SET restringido = $restringido
			WHERE idcarpeta = '$idcarpeta'	
			LIMIT 1";
			mysql_query($query);
			
			//NIVEL 1
			$query_sub = "SELECT idcarpeta 
			FROM carpeta
			WHERE idcarpeta_padre = '$idcarpeta'";
			$result_nivel_1 = mysql_query($query_sub);
			while($rs_nivel_1 = mysql_fetch_assoc($result_nivel_1)){
				
				$query = "UPDATE carpeta
				SET restringido = $restringido
				WHERE idcarpeta = '$rs_nivel_1[idcarpeta]'	
				LIMIT 1";
				mysql_query($query);
				
				//NIVEL 2
				$query_sub = "SELECT idcarpeta 
				FROM carpeta
				WHERE idcarpeta_padre = '$rs_nivel_1[idcarpeta]'";
				$result_nivel_2 = mysql_query($query_sub);
				while($rs_nivel_2 = mysql_fetch_assoc($result_nivel_2)){
					
					$query = "UPDATE carpeta
					SET restringido = $restringido
					WHERE idcarpeta = '$rs_nivel_2[idcarpeta]'	
					LIMIT 1";
					mysql_query($query);
					
					//NIVEL 3
					$query_sub = "SELECT idcarpeta 
					FROM carpeta
					WHERE idcarpeta_padre = '$rs_nivel_2[idcarpeta]'";
					$result_nivel_3 = mysql_query($query_sub);
					while($rs_nivel_3 = mysql_fetch_assoc($result_nivel_3)){
						
						$query = "UPDATE carpeta
						SET restringido = $restringido
						WHERE idcarpeta = '$rs_nivel_3[idcarpeta]'	
						LIMIT 1";
						mysql_query($query);
					
					}//FIN NIVEL 3
				}//FIN NIVEL 2
			}//FIN NIVEL 1
	}
	
	//CAMBIO SEGURO
	$seguro = $_POST['seguro'];
	$idcarpeta = $_POST['idcarpeta'];
	
	if($seguro != "" && $idcarpeta != ""){
		$query = "UPDATE carpeta
		SET seguro = $seguro
		WHERE idcarpeta = '$idcarpeta'	
		LIMIT 1";
		mysql_query($query);
	}
	
	//ELIMINAR
	$eliminar = $_POST['eliminar'];
	if($eliminar != "" && $idcarpeta != ""){
		
		//COMPROBAR QUE NO EXISTAN SUB CARPETAS ANTES DE ELIMINAR.
		$query_eliminar = "SELECT *
		FROM carpeta
		WHERE estado != '3' AND idcarpeta_padre = '$idcarpeta'";
		if(mysql_num_rows(mysql_query($query_eliminar)) == 0){
		
			$query = "UPDATE carpeta
			SET estado = '$eliminar'
			WHERE idcarpeta = '$idcarpeta'	
			LIMIT 1";
			mysql_query($query);
			
		}else{
			echo "<script>alert('No se pudo eliminar la carpeta porque posee subcarpetas.');</script>";
		}
	}

	//FILTRADO
	if($_POST['ordenar'] != ""){
		$_SESSION['ordenar'] = $_POST['ordenar'];
		$filtro_ordenar = $_SESSION['ordenar'];
	}else{
		$filtro_ordenar = $_SESSION['ordenar'];
	}
	
	if($_POST['filtro_idioma'] != ""){ 
		$_SESSION['filtro_idioma'] = $_POST['filtro_idioma'];
		$filtro_idioma = $_SESSION['filtro_idioma'];
	}else{
		$filtro_idioma = $_SESSION['filtro_idioma'];
	}
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
	}else{
		$obj_value = '';
		//FILTRADO
		if($_POST['filtro_sede'] != ""){ 
			$_SESSION['filtro_sede'] = $_POST['filtro_sede'];
			$filtro_sede = $_SESSION['filtro_sede'];
		}else{
			$filtro_sede = 0;
			$filtro_sede = $_SESSION['filtro_sede'];
		}
	}
	
	
	if($filtro_idioma != 0){
		$filtro_idioma = $filtro_idioma;
		$filtrado_idioma = " AND B.estado = '1' ";
	}else{
		$filtro_idioma = 1;
		$filtrado_idioma = "";
	}
	
	if($filtro_sede != 0){
		$filtrado_sede = " AND C.idsede = '".$filtro_sede."'";
		$filtrado_sede_inner = " INNER JOIN carpeta_sede C ON C.idcarpeta = A.idcarpeta ";
	}else{
		$filtrado_sede = "";
		$filtrado_sede_inner = "";
	}
	
	if($filtro_ordenar != ""){
		$filtrado_ordenar = $filtro_ordenar." ASC ";
	}else{
		$filtrado_ordenar = " A.orden ASC, B.nombre ";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_tips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style>
<script language="javascript">

	function cambiar_estado(estado, idcarpeta){
	formulario = document.form_lista;
	
	formulario.estado.value = estado;
	formulario.idcarpeta.value = idcarpeta;
	formulario.submit();
	};
	
	function confirm_cambiar_estado(estado, idcarpeta){
	formulario = document.form_lista;
		if (confirm('¿Esta seguro que desea eliminar el registro?')){
			formulario.eliminar.value = estado;
			formulario.idcarpeta.value = idcarpeta;
			formulario.submit();
		}
	};
	
	function cambiar_seguro(seguro, idcarpeta){
	formulario = document.form_lista;
	
	formulario.seguro.value = seguro;
	formulario.idcarpeta.value = idcarpeta;
	formulario.submit();
	};
	
	function cambiar_restringido(restringido, idcarpeta){
	formulario = document.form_lista;
	
	formulario.restringido.value = restringido;
	formulario.idcarpeta.value = idcarpeta;
	formulario.submit();
	};
	
</script>
<script type="text/javascript">

window.addEvent('domready', function(){

	//SLIDE
	var mySlide = new Fx.Slide('referencia'); mySlide.hide();
	
	$('btn_referencia').addEvent('click', function(e){
		e = new Event(e);
		mySlide.toggle();
		e.stop();
	});
	
	//TIPS	
	//var Tips1 = new Tips($$('.Tips1'));
	var Tips1 = new Tips($$('.Tips1'), {
	initialize:function(){
		this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 200, wait: false}).set(0);
	},
	onShow: function(toolTip) {
		this.fx.start(1);
	},
	onHide: function(toolTip) {
		this.fx.start(0);
	}
});


});
</script>
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Carpetas - Ver (&aacute;rbol) </td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#cccccc;" /></td>
              </tr>
            </table>
			<form action="" method="post" name="form_lista" id="form_lista" enctype="multipart/form-data">
			  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                      <td width="96%" height="30" bgcolor="#FFE9D2" class="detalle_medio_bold">Referencias </td>
                      <td width="4%" align="center" valign="middle" bgcolor="#FFE9D2" class="detalle_medio_bold"><a href="#" id="btn_referencia"><img src="../../imagen/btn_referencia.png" width="12" height="20" border="0" /></a></td>
                    </tr>
                    
                  </table>
                    <table id="referencia" width="100%" border="0" cellpadding="3" cellspacing="0">
                      <tr>
                        <td height="30" colspan="3" bgcolor="#FFF0E1" class="detalle_medio"><strong>Propiedades de las Carpetas </strong></td>
                      </tr>
                      <tr>
                        <td width="33%" bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td width="25%" align="right"><img src="../../imagen/b_habilitado.png" width="15" height="16" border="0" />&nbsp;&nbsp;<img src="../../imagen/b_deshabilitado.png" width="16" height="15" border="0" /></td>
                              <td width="75%" class="detalle_medio">Activada / Desactivada </td>
                            </tr>
                        </table></td>
                        <td width="34%" bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td width="24%" align="right"><img src="../../imagen/eye_on.png" width="16" height="16" />&nbsp;<img src="../../imagen/eye_off.png" width="16" height="16" /></td>
                              <td width="76%" class="detalle_medio">Con Foto / Sin Foto</td>
                            </tr>
                        </table></td>
                        <td width="33%" bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td width="22%" align="right"><img src="../../imagen/s_rights.png" width="16" height="16" />&nbsp;<img src="../../imagen/s_rights_b.png" width="14" height="14" /></td>
                              <td width="78%" class="detalle_medio">Restingida para NO usuarios</td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="25%" align="right"><img src="../../imagen/iconos/sede.png" width="14" height="20" border="0" /></td>
                            <td width="75%" class="detalle_medio">Sucursales</td>
                          </tr>
                        </table></td>
                        <td width="34%" bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="24%" align="right"><img src="../../imagen/iconos/idioma.png" width="18" height="20" border="0" /></td>
                            <td width="76%" class="detalle_medio">Idiomas</td>
                          </tr>
                        </table></td>
                        <td width="33%" bgcolor="#FFF0E1">
						<? if($usuario_perfil == 1){?>
						<table width="100%"  border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="22%" align="right"><img src="../../imagen/trash_hab.png" width="18" height="16" /> <img src="../../imagen/trash_deshab.png" width="18" height="16" /></td>
                            <td width="78%" class="detalle_medio">Sin seguro / Con seguro </td>
                          </tr>
                        </table>
						<? } ?></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="3" bgcolor="#FFF0E1" class="detalle_medio"><strong>Opciones de carpetas </strong></td>
                      </tr>
                      <tr>
                        <td bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td width="25%" align="right"><img src="../../imagen/trash.png" width="15" height="16" border="0" /></td>
                              <td width="75%" class="detalle_medio">Enviar a la papelera </td>
                            </tr>
                        </table></td>
                        <td bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td width="25%" align="right"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></td>
                              <td width="75%" class="detalle_medio">Editar</td>
                            </tr>
                        </table></td>
                        <td bgcolor="#FFF0E1">&nbsp;</td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              <br />
              <table width="100%" border="0" cellspacing="0" cellpadding="3">
                <tr>
                  <td width="82%" align="right" class="detalle_medio">Filtrar por idioma: </td>
                  <td width="18%" align="right"><select name="filtro_idioma" style="width:160px;" class="detalle_medio" id="filtro_idioma" onchange="javascript:document.form_lista.submit();">
				  <? 
						if($_POST['filtro_idioma'] == '0'){
							$sel_idioma = "selected";
						}else{
							$sel_idioma = "";
						}
				
					?>
                      <option value="0" <?= $sel_idioma ?>>- Seleccionar Idioma</option>
                      <? $query_idioma = "SELECT ididioma, titulo_idioma
						  FROM idioma 
						  WHERE estado = 1
						  ORDER BY titulo_idioma";
								  
						  $result_idioma = mysql_query($query_idioma);
						  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
						  	
								if($_POST['filtro_idioma'] == $rs_idioma['ididioma']){
									$sel_idioma = "selected";
								}else{
									$sel_idioma = "";
								}
						  
						  ?>
                      <option value="<?= $rs_idioma['ididioma'] ?>" <?= $sel_idioma ?>>
                        <?= $rs_idioma['titulo_idioma'] ?>
                    </option>
                      <? } ?>
                  </select></td>
                </tr>
                <tr>
                  <td align="right" class="detalle_medio">Filtrar por sucursales: </td>
                  <td align="right"><select name="filtro_sede" style="width:160px;" class="detalle_medio" id="filtro_sede" <?= $obj_value ?> onchange="javascript:document.form_lista.submit();">
                      <option value="0">- Seleccione Sucursal</option>
                      <? $query_sede = "SELECT idsede, titulo
						  FROM sede 
						  WHERE estado = 1
						  ORDER BY titulo";
								  
						  $result_sede = mysql_query($query_sede);
						  while($rs_sede = mysql_fetch_assoc($result_sede)){
						  	
								if($filtro_sede == $rs_sede['idsede']){
									$sel_sede = "selected";
								}else{
									$sel_sede = "";
								}
						  
						  ?>
                      <option value="<?= $rs_sede['idsede'] ?>" <?= $sel_sede ?>>
                        <?= $rs_sede['titulo'] ?>
                    </option>
                      <? } ?>
                  </select></td>
                </tr>
                <tr>
                  <td align="right" class="detalle_medio">Ordenar por: </td>
                  <td align="right"><select name="ordenar" class="detalle_medio" style="width:160px;" id="ordenar" onchange="javascript:document.form_lista.submit();">
				    <option value="" <? if($filtro_ordenar == ""){ echo "selected";} ?>>- Seleccione Orden </option>
                    <option value="B.nombre" <? if($filtro_ordenar == "B.nombre"){ echo "selected";} ?>>Nombre </option>
                    <option value="A.orden" <? if($filtro_ordenar == "A.orden"){ echo "selected";} ?>>N&deg; de Orden</option>
                  </select></td>
                </tr>
              </table>
              <br />
              <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr valign="middle"  bgcolor="fff0e1">
                    <td align="left" bgcolor="#fff0e1"><input name="estado" type="hidden" id="estado" />
					<input name="idcarpeta" type="hidden" id="idcarpeta"/>
					<input name="restringido" type="hidden" id="restringido"/>
					<input name="seguro" type="hidden" id="seguro"/>
					<input name="eliminar" type="hidden" id="eliminar" />
					<? 
					
	//PARAMETROS:
	$ruta_foto = "../../../imagen/carpeta/chica/"; //carpeta donde se alojan las fotos
	
	$hay = false;
	//PRIMER NIVEL DE CARPETAS
	$query_carpetas_1 = "SELECT DISTINCT A.idcarpeta, B.nombre, A.estado, A.seguro, A.foto , A.orden, A.restringido
	FROM carpeta A
	LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	$filtrado_sede_inner
	WHERE $filtro_carpeta AND A.estado <> 3 AND B.ididioma = '$filtro_idioma' $filtrado_idioma $filtrado_sede
	ORDER BY $filtrado_ordenar";

	$result_carpetas_1 = mysql_query($query_carpetas_1);
	while ($rs_carpetas_1 = mysql_fetch_assoc($result_carpetas_1))
	{
		$hay = true;				
					?>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="5%" bgcolor="#ffddbc"><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></td>
                        <td width="62%" bgcolor="#ffddbc" class="detalle_medio"><p><span class="detalle_chico">
                          <?= $rs_carpetas_1['orden'] ?>. </span> <span class="detalle_medio_bold">
                          <?= $rs_carpetas_1['nombre'] ?>
                        </span></p>                        </td>
                        <td width="33%" bgcolor="#ffddbc"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr>
                            <td width="72%" align="left" class="detalle_chico style1">Propiedades</td>
                            <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                          </tr>
                          <tr>
                            <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td align="left">
								  <? 
								   if($rs_carpetas_1['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_1['idcarpeta'].");\"><img src=\"../../imagen/b_habilitado.png\"  alt=\"Habilitado\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_1['idcarpeta'].");\"><img src=\"../../imagen/b_deshabilitado.png\"  alt=\"Deshabilitado\"  width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?> 
								  <? 
								   if($rs_carpetas_1['seguro'] == 1){ 
								   		if($usuario_perfil == 1){
									 		echo "&nbsp;<a href=\"javascript:cambiar_seguro(2,".$rs_carpetas_1['idcarpeta'].");\"><img src=\"../../imagen/trash_hab.png\"  alt=\"No Seguro (Permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}
								   }else{
								   		if($usuario_perfil == 1){
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(1,".$rs_carpetas_1['idcarpeta'].");\"><img src=\"../../imagen/trash_deshab.png\"  alt=\"Seguro (No se permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}
								   }
								  ?>
								  <? 
								   if($rs_carpetas_1['restringido'] == 1){ 
									  echo "<a href=\"javascript:cambiar_restringido(2,".$rs_carpetas_1['idcarpeta'].");\"><img src=\"../../imagen/s_rights.png\"  alt=\"Exclusivo solo para usuarios.\"  width=\"16\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_restringido(1,".$rs_carpetas_1['idcarpeta'].");\"><img src=\"../../imagen/s_rights_b.png\"  alt=\"Seccion de acesso público.\"  width=\"14\" height=\"14\" border=\"0\" /></a>";
								   }
								  ?>
								  <img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idiomas  ::
								  <?
								  $query_idioma = "SELECT B.titulo_idioma
								  FROM carpeta_idioma_dato A
								  INNER JOIN idioma B ON A.ididioma = B.ididioma
								  WHERE A.idcarpeta = $rs_carpetas_1[idcarpeta] AND A.estado = 1
								  ORDER BY B.titulo_idioma";
								  
								  $result_idioma = mysql_query($query_idioma);
								  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
								  	echo "&bull; ".$rs_idioma['titulo_idioma']."<br>";
								  }
								  ?>"/>
                                  <img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT C.titulo
								  FROM carpeta A
								  INNER JOIN carpeta_sede B ON A.idcarpeta = B.idcarpeta
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idcarpeta = '$rs_carpetas_1[idcarpeta]'
								  ORDER BY C.titulo";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" />								  
                                  <? 
								   if($rs_carpetas_1['foto'] != ""){ 
									  echo "<img src=\"../../imagen/eye_on.png\"  alt=\"Tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }else{
									  echo "<img src=\"../../imagen/eye_off.png\"  alt=\"No tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }
								  ?></td>
                                </tr>
                            </table></td>
                            <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td align="right"><a href="carpeta_editar.php?idcarpeta=<?= $rs_carpetas_1['idcarpeta'] ?>&forma=arbol"><img src="../../imagen/b_edit.png" alt="Editar" width="16" height="16" border="0" /></a>
								  <? 
								   if($rs_carpetas_1['seguro'] == 1){ 
									  echo "&nbsp;<a href=\"javascript:confirm_cambiar_estado(3,".$rs_carpetas_1['idcarpeta'].");\"><img src=\"../../imagen/trash.png\" alt=\"Eliminar\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?>								  </td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="5" colspan="3"></td>
                      </tr>
                    </table>
					<?
					
			//SEGUNDO NIVEL DE CARPETAS
			$query_carpetas_2 = "SELECT DISTINCT A.idcarpeta, B.nombre, A.estado, A.seguro, A.foto , A.orden, A.restringido
			FROM carpeta A
			LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
			$filtrado_sede_inner
			WHERE A.idcarpeta_padre = $rs_carpetas_1[idcarpeta] AND A.estado <> 3 AND B.ididioma = '$filtro_idioma' $filtrado_idioma $filtrado_sede
			ORDER BY $filtrado_ordenar";
		
			$result_carpetas_2 = mysql_query($query_carpetas_2);
			while ($rs_carpetas_2 = mysql_fetch_assoc($result_carpetas_2))
			{
					?>
	                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="6%" bgcolor="#FFEBD7">&nbsp;</td>
                        <td width="5%" bgcolor="#FFEBD7" class="detalle_medio"><p><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></p></td>
                        <td width="56%" bgcolor="#FFEBD7" class="detalle_medio"><span class="detalle_chico">
                          <?= $rs_carpetas_2['orden'] ?>. </span> <span class="detalle_medio_bold">
                        <?= $rs_carpetas_2['nombre'] ?>
                        </span></td>
                        <td width="33%" bgcolor="#FFEBD7"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td width="72%" align="left" class="detalle_chico style1">Propiedades</td>
                              <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                            </tr>
                            <tr>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="left"><? 
								   if($rs_carpetas_2['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_2['idcarpeta'].");\"><img src=\"../../imagen/b_habilitado.png\" alt=\"Habilitado\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_2['idcarpeta'].");\"><img src=\"../../imagen/b_deshabilitado.png\"  alt=\"Deshabilitado\"  width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?>
                                  <? 
								   if($rs_carpetas_2['seguro'] == 1){
								   		if($usuario_perfil == 1){ 
										  	echo "&nbsp;<a href=\"javascript:cambiar_seguro(2,".$rs_carpetas_2['idcarpeta'].");\"><img src=\"../../imagen/trash_hab.png\"  alt=\"No Seguro (Permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}
								   }else{
								   		if($usuario_perfil == 1){ 
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(1,".$rs_carpetas_2['idcarpeta'].");\"><img src=\"../../imagen/trash_deshab.png\"  alt=\"Seguro (No se permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}	
								   }
								  ?>
                                    <? 
								   if($rs_carpetas_2['restringido'] == 1){ 
									  echo "<a href=\"javascript:cambiar_restringido(2,".$rs_carpetas_2['idcarpeta'].");\"><img src=\"../../imagen/s_rights.png\"  alt=\"Exclusivo solo para usuarios.\"  width=\"16\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_restringido(1,".$rs_carpetas_2['idcarpeta'].");\"><img src=\"../../imagen/s_rights_b.png\"  alt=\"Seccion de acesso público.\"  width=\"14\" height=\"14\" border=\"0\" /></a>";
								   }
								  ?>
                                    <img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idiomas  ::
								  <?
								  $query_idioma = "SELECT B.titulo_idioma
								  FROM carpeta_idioma_dato A
								  INNER JOIN idioma B ON A.ididioma = B.ididioma
								  WHERE A.idcarpeta = $rs_carpetas_2[idcarpeta] AND A.estado = 1
								  ORDER BY B.titulo_idioma";
								  $result_idioma = mysql_query($query_idioma);
								  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
								  	echo "&bull; ".$rs_idioma['titulo_idioma']."<br>";
								  }
								  ?>"/> <img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT C.titulo
								  FROM carpeta A
								  INNER JOIN carpeta_sede B ON A.idcarpeta = B.idcarpeta
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idcarpeta = '$rs_carpetas_2[idcarpeta]'
								  ORDER BY C.titulo";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" />                                     
                                    <? 
								   if($rs_carpetas_2['foto'] != ""){ 
									  echo "<img src=\"../../imagen/eye_on.png\"  alt=\"Tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }else{
									  echo "<img src=\"../../imagen/eye_off.png\"  alt=\"No tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                              <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="right"><a href="carpeta_editar.php?idcarpeta=<?= $rs_carpetas_2['idcarpeta'] ?>&forma=arbol"><img src="../../imagen/b_edit.png" alt="Editar" width="16" height="16" border="0" /></a> <? 
								   if($rs_carpetas_2['seguro'] == 1){ 
									  echo "&nbsp;<a href=\"javascript:confirm_cambiar_estado(3,".$rs_carpetas_2['idcarpeta'].");\"><img src=\"../../imagen/trash.png\"  alt=\"Eliminar\"  width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="5" colspan="4"></td>
                      </tr>
                    </table>
	                <?
					
			//TERCER NIVEL DE CARPETAS
			$query_carpetas_3 = "SELECT DISTINCT A.idcarpeta, B.nombre, A.estado, A.seguro, A.foto , A.orden, A.restringido
			FROM carpeta A
			LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
			$filtrado_sede_inner
			WHERE A.idcarpeta_padre = $rs_carpetas_2[idcarpeta] AND A.estado <> 3 AND B.ididioma = '$filtro_idioma'  $filtrado_idioma $filtrado_sede
			ORDER BY $filtrado_ordenar";
		
			$result_carpetas_3 = mysql_query($query_carpetas_3);
			while ($rs_carpetas_3 = mysql_fetch_assoc($result_carpetas_3))
			{
					?>
	                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="12%" bgcolor="#FFF3E8">&nbsp;</td>
                        <td width="5%" bgcolor="#FFF3E8" class="detalle_medio"><p><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></p></td>
                        <td width="50%" bgcolor="#FFF3E8" class="detalle_medio"><span class="detalle_chico">
                          <?= $rs_carpetas_3['orden'] ?>. </span> <span class="detalle_medio_bold">
                        <?= $rs_carpetas_3['nombre'] ?>
                        </span></td>
                        <td width="33%" bgcolor="#FFF3E8"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td width="72%" align="left" class="detalle_chico style1">Propiedades</td>
                              <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                            </tr>
                            <tr>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="left"><? 
								   if($rs_carpetas_3['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_3['idcarpeta'].");\"><img src=\"../../imagen/b_habilitado.png\" alt=\"Habilitado\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_3['idcarpeta'].");\"><img src=\"../../imagen/b_deshabilitado.png\"  alt=\"Deshabilitado\"  width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?>
								  <? 
								   if($rs_carpetas_3['seguro'] == 1){
								   	  if($usuario_perfil == 1){ 
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(2,".$rs_carpetas_3['idcarpeta'].");\"><img src=\"../../imagen/trash_hab.png\"  alt=\"No Seguro (Permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
									  }
								   }else{
								      if($usuario_perfil == 1){ 
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(1,".$rs_carpetas_3['idcarpeta'].");\"><img src=\"../../imagen/trash_deshab.png\"  alt=\"Seguro (No se permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
									  }
								   }
								  ?>
                                  <? 
								   if($rs_carpetas_3['restringido'] == 1){ 
									  echo "<a href=\"javascript:cambiar_restringido(2,".$rs_carpetas_3['idcarpeta'].");\"><img src=\"../../imagen/s_rights.png\"  alt=\"Exclusivo solo para usuarios.\"  width=\"16\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_restringido(1,".$rs_carpetas_3['idcarpeta'].");\"><img src=\"../../imagen/s_rights_b.png\"  alt=\"Seccion de acesso público.\"  width=\"14\" height=\"14\" border=\"0\" /></a>";
								   }
								  ?>
                                    <img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idiomas  ::
								  <?
								  $query_idioma = "SELECT B.titulo_idioma
								  FROM carpeta_idioma_dato A
								  INNER JOIN idioma B ON A.ididioma = B.ididioma
								  WHERE A.idcarpeta = $rs_carpetas_3[idcarpeta] AND A.estado = 1
								  ORDER BY B.titulo_idioma";
								  $result_idioma = mysql_query($query_idioma);
								  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
								  	echo "&bull; ".$rs_idioma['titulo_idioma']."<br>";
								  }
								  ?>"/> <img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT C.titulo
								  FROM carpeta A
								  INNER JOIN carpeta_sede B ON A.idcarpeta = B.idcarpeta
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idcarpeta = '$rs_carpetas_3[idcarpeta]'
								  ORDER BY C.titulo";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" />                                    
                                    <? 
								   if($rs_carpetas_3['foto'] != ""){ 
									  echo "<img src=\"../../imagen/eye_on.png\"  alt=\"Tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }else{
									  echo "<img src=\"../../imagen/eye_off.png\"  alt=\"No tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                              <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="right"><a href="carpeta_editar.php?idcarpeta=<?= $rs_carpetas_3['idcarpeta'] ?>&forma=arbol"><img src="../../imagen/b_edit.png" alt="Editar" width="16" height="16" border="0" /></a>
                                    <? 
								   if($rs_carpetas_3['seguro'] == 1){ 
									  echo "&nbsp;<a href=\"javascript:confirm_cambiar_estado(3,".$rs_carpetas_3['idcarpeta'].");\"><img src=\"../../imagen/trash.png\"  alt=\"Eliminar\"  width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="5" colspan="4"></td>
                      </tr>
                    </table>
                    <?
					
			//CUARTO NIVEL DE CARPETAS
			$query_carpetas_4 = "SELECT DISTINCT A.idcarpeta, B.nombre, A.estado, A.seguro, A.foto , A.orden, A.restringido
			FROM carpeta A
			LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
			$filtrado_sede_inner
			WHERE A.idcarpeta_padre = $rs_carpetas_3[idcarpeta] AND A.estado <> 3 AND B.ididioma = '$filtro_idioma' $filtrado_idioma $filtrado_sede
			ORDER BY $filtrado_ordenar";
		
			$result_carpetas_4 = mysql_query($query_carpetas_4);
			while ($rs_carpetas_4 = mysql_fetch_assoc($result_carpetas_4))
			{
					?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="18%" bgcolor="#FFF8F0">&nbsp;</td>
                        <td width="5%" bgcolor="#FFF8F0" class="detalle_medio"><p><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></p></td>
                        <td width="44%" bgcolor="#FFF8F0" class="detalle_medio"><span class="detalle_chico">
                          <?= $rs_carpetas_4['orden'] ?>. </span> <span class="detalle_medio_bold">
                        <?= $rs_carpetas_4['nombre'] ?>
                        </span></td>
                        <td width="33%" bgcolor="#FFF8F0"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td width="72%" align="left" class="detalle_chico style1">Propiedades</td>
                              <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                            </tr>
                            <tr>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="left"><? 
								   if($rs_carpetas_4['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_4['idcarpeta'].");\"><img src=\"../../imagen/b_habilitado.png\" alt=\"Habilitado\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_4['idcarpeta'].");\"><img src=\"../../imagen/b_deshabilitado.png\" alt=\"Deshabilitado\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?>
								  <? 
								   if($rs_carpetas_4['seguro'] == 1){ 
								   		if($usuario_perfil == 1){
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(2,".$rs_carpetas_4['idcarpeta'].");\"><img src=\"../../imagen/trash_hab.png\"  alt=\"No Seguro (Permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}
								   }else{
								   		if($usuario_perfil == 1){
									  		echo "&nbsp;<a href=\"javascript:cambiar_seguro(1,".$rs_carpetas_4['idcarpeta'].");\"><img src=\"../../imagen/trash_deshab.png\"  alt=\"Seguro (No se permite eliminar)\"  width=\"18\" height=\"16\" border=\"0\" /></a>";
										}
								   }
								  ?>
                                      <? 
								   if($rs_carpetas_4['restringido'] == 1){ 
									  echo "<a href=\"javascript:cambiar_restringido(2,".$rs_carpetas_4['idcarpeta'].");\"><img src=\"../../imagen/s_rights.png\"  alt=\"Exclusivo solo para usuarios.\"  width=\"16\"  height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_restringido(1,".$rs_carpetas_4['idcarpeta'].");\"><img src=\"../../imagen/s_rights_b.png\"  alt=\"Seccion de acesso público.\"  width=\"14\" height=\"14\" border=\"0\" /></a>";
								   }
								  ?> <img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idiomas  ::
								  <?
								  $query_idioma = "SELECT B.titulo_idioma
								  FROM carpeta_idioma_dato A
								  INNER JOIN idioma B ON A.ididioma = B.ididioma
								  WHERE A.idcarpeta = $rs_carpetas_4[idcarpeta] AND A.estado = 1
								  ORDER BY B.titulo_idioma";
								  $result_idioma = mysql_query($query_idioma);
								  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
								  	echo "&bull; ".$rs_idioma['titulo_idioma']."<br>";
								  }
								  ?>"/>
								      <img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT C.titulo
								  FROM carpeta A
								  INNER JOIN carpeta_sede B ON A.idcarpeta = B.idcarpeta
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idcarpeta = '$rs_carpetas_4[idcarpeta]'
								  ORDER BY C.titulo";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" />
							        <? 
								   if($rs_carpetas_4['foto'] != ""){ 
									  echo "<img src=\"../../imagen/eye_on.png\"  alt=\"Tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }else{
									  echo "<img src=\"../../imagen/eye_off.png\"  alt=\"No tiene foto principal\"  width=\"16\" height=\"16\" border=\"0\" />";
								   }
								  ?>							        </td>
                                  </tr>
                              </table></td>
                              <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="right"><a href="carpeta_editar.php?idcarpeta=<?= $rs_carpetas_4['idcarpeta'] ?>&forma=arbol"><img src="../../imagen/b_edit.png" alt="Editar" width="16" height="16" border="0" /></a>
                                    <? 
								   if($rs_carpetas_4['seguro'] == 1){ 
									  echo "&nbsp;<a href=\"javascript:confirm_cambiar_estado(3,".$rs_carpetas_4['idcarpeta'].");\"><img src=\"../../imagen/trash.png\"  alt=\"Eliminar\"  width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td height="5" colspan="4"></td>
                      </tr>
                    </table>
                <? 
								}//FIN NIVEL 4 CARPETAS 
							}//FIN NIVEL 3 CARPETAS 
						}//FIN NIVEL 2 CARPETAS 
					}//FIN NIVEL 1 CARPETAS 
					?></td>
                </tr>
                  <? if($hay != true){ ?>
                  <tr valign="middle" bgcolor="fff0e1" >
                    <td height="50" align="center" bgcolor="#fff0e1" class="titulo_medio_bold">No se han encontrado carpetas.</td>
                  </tr>
                  <? } ?>
              </table>
                <input name="cantidad" type="hidden" id="cantidad" value="<?=$GLOBALS["cont_prod"]?>" />
            </form></td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>