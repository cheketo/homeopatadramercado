<? 
	include ("../../0_mysql.php"); 
	
	//SI EL USUARIO ADMIN ES NIVEL 1
	if($_SESSION[idcusuario_perfil_log]=='1'){
		$usuario_perfil = 1;
	}else{
		$usuario_perfil = 2;
	}
	
	//VARIABLES
	$habilitar = $_GET['habilitar'];
	$deshabilitar = $_GET['deshabilitar'];
	
	//CAMBIO DE ESTADO
	$estado = $_POST['estado'];
	$idadmin_menu = $_POST['idadmin_menu'];
	
	if($estado != "" && $idadmin_menu != ""){
		$query = "UPDATE admin_menu
		SET estado = $estado
		WHERE idadmin_menu = '$idadmin_menu'	
		LIMIT 1";
		mysql_query($query);
		header("Location: #cat".$idadmin_menu);
	}
	
	//CAMBIO SEGURO
	$seguro = $_POST['seguro'];
	$idadmin_menu = $_POST['idadmin_menu'];
	
	if($seguro != "" && $idadmin_menu != ""){
		$query = "UPDATE admin_menu
		SET seguro = $seguro
		WHERE idadmin_menu = '$idadmin_menu'	
		LIMIT 1";
		mysql_query($query);
		header("Location: #cat".$idadmin_menu);
	}
	
	//ELIMINAR
	$eliminar = $_POST['eliminar'];
	if($eliminar != ""){
		
		//COMPROBAR QUE NO EXISTAN SUB CARPETAS ANTES DE ELIMINAR.
		$query_eliminar = "SELECT *
		FROM admin_menu
		WHERE idadmin_menu_padre = '$eliminar'";
		if(mysql_num_rows(mysql_query($query_eliminar)) == 0){
		
			$query = "DELETE FROM admin_menu
			WHERE idadmin_menu = '$eliminar'	
			LIMIT 1";
			mysql_query($query);
			
		}else{
			echo "<script>alert('No se pudo eliminar la categoria del menu porque posee subcategorias.');</script>";
		}
	}

	//Administra los perfiles de usuarios
	if ($_POST['estado_perfil'] =='2' && $_POST['iduser_admin_perfil'] && $_POST['idadmin_menu']){	 
		$query_eliminar = "DELETE FROM admin_menu_perfil WHERE iduser_admin_perfil = '$_POST[iduser_admin_perfil]' AND idadmin_menu = '$_POST[idadmin_menu]'";
		mysql_query($query_eliminar);
		header("Location: #cat".$idadmin_menu);
	}
	
	//PERFIL incorpora
	if ($_POST['estado_perfil'] =='1' && $_POST['iduser_admin_perfil'] && $_POST['idadmin_menu']){	 
		$query_ingreso = "INSERT INTO admin_menu_perfil (
		  iduser_admin_perfil
		, idadmin_menu
		) VALUES (
		  '$_POST[iduser_admin_perfil]'
		, '$_POST[idadmin_menu]'
		)";
		mysql_query($query_ingreso);
		header("Location: #cat".$idadmin_menu);
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

	function cambiar_estado(estado, idadmin_menu){
		formulario = document.form_lista;
		
		formulario.estado.value = estado;
		formulario.idadmin_menu.value = idadmin_menu;
		formulario.submit();
	};
	
	function confirmar_eliminar(idadmin_menu){
	formulario = document.form_lista;
		if (confirm('¿Esta seguro que desea eliminar el registro?')){
			formulario.eliminar.value = idadmin_menu;
			formulario.submit();
		}
	};
	
	function cambiar_seguro(seguro, idadmin_menu){
		formulario = document.form_lista;
		
		formulario.seguro.value = seguro;
		formulario.idadmin_menu.value = idadmin_menu;
		formulario.submit();
	};
	
	function cambiar_perfil(estado_perfil, idadmin_menu, iduser_admin_perfil){
		formulario = document.form_lista;
		
		formulario.estado_perfil.value = estado_perfil;
		formulario.idadmin_menu.value = idadmin_menu;
		formulario.iduser_admin_perfil.value = iduser_admin_perfil;		
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
                <td height="40" valign="bottom" class="titulo_grande_bold">  Menu Admin - Ver (&aacute;rbol)</td>
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
                        <td width="35%" bgcolor="#FFF0E1"><? if($usuario_perfil == 1){?>
                          <table width="100%"  border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td width="22%" align="right"><img src="../../imagen/iconos/user_perfil_hab.png" width="13" height="16" />&nbsp;<img src="../../imagen/iconos/user_perfil_deshab.png" width="13" height="16" /></td>
                              <td width="78%" class="detalle_medio">Perfil Habilitado / Deshabilitado </td>
                            </tr>
                          </table>
                        <? } ?></td>
                        <td width="32%" bgcolor="#FFF0E1">&nbsp;</td>
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
              <table width="100%" border="0" cellspacing="4" cellpadding="0">
                <tr>
                  <td align="right"><input name="Button" type="button" class="detalle_medio_bold" onclick="javascript:window.location.href('admin_menu_nuevo.php')" value=" Nuevo Boton &raquo; " /></td>
                </tr>
              </table>
              <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
          <tr valign="middle"  bgcolor="fff0e1">
                    <td align="left" bgcolor="#fff0e1">
					<input name="estado" type="hidden" id="estado" />
					<input name="idadmin_menu" type="hidden" id="idadmin_menu"/>					
					<input name="seguro" type="hidden" id="seguro"/>
					<input name="eliminar" type="hidden" id="eliminar" />
					<input name="estado_perfil" type="hidden" id="estado_perfil" />
					<input name="iduser_admin_perfil" type="hidden" id="iduser_admin_perfil" />
					<? 
					
	$hay = false;
	//PRIMER NIVEL DE CARPETAS
	$query_carpetas_1 = "SELECT DISTINCT A.idadmin_menu, A.titulo, A.seguro, A.orden, A.estado
	FROM admin_menu A		
	WHERE A.idadmin_menu_padre = 0 AND A.estado <> 3  AND A.icono = '2'
	ORDER BY A.orden ASC";

	$result_carpetas_1 = mysql_query($query_carpetas_1);
	while ($rs_carpetas_1 = mysql_fetch_assoc($result_carpetas_1))
	{
		$hay = true;				
					?>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="5%" bgcolor="#ffddbc"><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></td>
                        <td width="58%" bgcolor="#ffddbc" class="detalle_medio"><p><span class="detalle_chico">
                          <?= $rs_carpetas_1['orden'] ?>. </span> <span class="detalle_medio_bold">
                          <?= $rs_carpetas_1['titulo'] ?>
                        </span><a name="cat" id="cat<?= $rs_carpetas_1['idadmin_menu'] ?>"></a></p>                        </td>
                        <td width="37%" bgcolor="#ffddbc"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr>
                            <td width="31%" align="left" class="detalle_chico style1">Propiedades</td>
                            <td width="41%" align="center" class="detalle_chico style1">Perfil</td>
                            <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                          </tr>
                          <tr>
                            <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td align="left">
								  <? 
								   if($rs_carpetas_1['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_1['idadmin_menu'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_1['idadmin_menu'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                                </tr>
                            </table></td>
                            <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td align="center">
								<? 
									//boton para el perfil de usuario									
									$query_perfil = "SELECT A.iduser_admin_perfil, A.titulo
									FROM user_admin_perfil A
									WHERE A.estado = 1 ";
									$result_perfil = mysql_query($query_perfil);		
									while ($rs_perfil = mysql_fetch_assoc($result_perfil)){
									
										$query_menu_perfil = "SELECT A.idadmin_menu_perfil
										FROM admin_menu_perfil A
										WHERE A.iduser_admin_perfil = $rs_perfil[iduser_admin_perfil] AND A.idadmin_menu = $rs_carpetas_1[idadmin_menu]";
										$result_menu_perfil = mysql_query($query_menu_perfil);	
										$rs_menu_perfil = mysql_fetch_assoc($result_menu_perfil);	
										$num_menu_perfil = mysql_num_rows($result_menu_perfil);	
										
										if ($num_menu_perfil > '0') {
											echo '&nbsp;<a style="color: #FF6600; font-weight: bold;" href="javascript:cambiar_perfil(2,'.$rs_carpetas_1['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_hab.png" border="0" title="'.$rs_perfil['titulo'].' :: Habilitado"></a>';			
										}else {		 
											echo '&nbsp;<a style="color: #000000; font-weight: bold;" href="javascript:cambiar_perfil(1,'.$rs_carpetas_1['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_deshab.png" border="0" title="'.$rs_perfil['titulo'].' :: Deshabilitado"></a>';
										};			
									};//fin perfil usuario
								  ?>								  </td>
                              </tr>
                            </table></td>
                            <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td align="right"><a href="admin_menu_editar.php?idadmin_menu=<?= $rs_carpetas_1['idadmin_menu'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a>
								  <? echo "&nbsp;<a href=\"javascript:confirmar_eliminar(".$rs_carpetas_1['idadmin_menu'].");\"><img src=\"../../imagen/trash.png\" width=\"15\" height=\"16\" border=\"0\" /></a>"; ?></td>
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
			$query_carpetas_2 = "SELECT DISTINCT A.idadmin_menu, A.titulo, A.seguro, A.orden, A.estado
			FROM admin_menu A			
			WHERE A.idadmin_menu_padre = $rs_carpetas_1[idadmin_menu] AND A.estado <> 3 AND A.icono = '2'
			ORDER BY A.orden ASC";		
			$result_carpetas_2 = mysql_query($query_carpetas_2);
			while ($rs_carpetas_2 = mysql_fetch_assoc($result_carpetas_2))
			{
					?>
	                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="6%" bgcolor="#FFEBD7">&nbsp;</td>
                        <td width="5%" bgcolor="#FFEBD7" class="detalle_medio"><p><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></p></td>
                        <td width="52%" bgcolor="#FFEBD7" class="detalle_medio"><span class="detalle_chico">
                          <?= $rs_carpetas_2['orden'] ?>. </span> <span class="detalle_medio_bold">
                        <?= $rs_carpetas_2['titulo'] ?>
                        <a name="cat" id="cat<?= $rs_carpetas_2['idadmin_menu'] ?>"></a></span></td>
                        <td width="37%" bgcolor="#FFEBD7"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td width="31%" align="left" class="detalle_chico style1">Propiedades</td>
                              <td width="41%" align="center" class="detalle_chico style1">Perfil</td>
                              <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                            </tr>
                            <tr>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="left"><? 
								   if($rs_carpetas_2['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_2['idadmin_menu'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_2['idadmin_menu'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td align="center"><? 
									//boton para el perfil de usuario									
									$query_perfil = "SELECT A.iduser_admin_perfil, A.titulo
									FROM user_admin_perfil A
									WHERE A.estado = 1 ";
									$result_perfil = mysql_query($query_perfil);		
									while ($rs_perfil = mysql_fetch_assoc($result_perfil)){
									
										$query_menu_perfil = "SELECT A.idadmin_menu_perfil
										FROM admin_menu_perfil A
										WHERE A.iduser_admin_perfil = $rs_perfil[iduser_admin_perfil] AND A.idadmin_menu = $rs_carpetas_2[idadmin_menu]";
										$result_menu_perfil = mysql_query($query_menu_perfil);	
										$rs_menu_perfil = mysql_fetch_assoc($result_menu_perfil);	
										$num_menu_perfil = mysql_num_rows($result_menu_perfil);	
										
										if ($num_menu_perfil > '0') {
											echo '&nbsp;<a style="color: #FF6600; font-weight: bold;" href="javascript:cambiar_perfil(2,'.$rs_carpetas_2['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_hab.png" border="0" title="'.$rs_perfil['titulo'].' :: Habilitado"></a>';			
										}else {		 
											echo '&nbsp;<a style="color: #000000; font-weight: bold;" href="javascript:cambiar_perfil(1,'.$rs_carpetas_2['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_deshab.png" border="0" title="'.$rs_perfil['titulo'].' :: Deshabilitado"></a>';
										};			
									};//fin perfil usuario
								  ?>                                  </td>
                                </tr>
                              </table></td>
                              <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="right"><a href="admin_menu_editar.php?idadmin_menu=<?= $rs_carpetas_2['idadmin_menu'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a><a href="carpeta_editar.php?idcarpeta=<?= $rs_carpetas_2['idadmin_menu'] ?>"></a> <? 
									  echo "&nbsp;<a href=\"javascript:confirmar_eliminar(".$rs_carpetas_2['idadmin_menu'].");\"><img src=\"../../imagen/trash.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
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
			$query_carpetas_3 = "SELECT DISTINCT A.idadmin_menu, A.titulo, A.seguro, A.orden, A.estado
			FROM admin_menu A			
			WHERE A.idadmin_menu_padre = $rs_carpetas_2[idadmin_menu] AND A.estado <> 3 AND A.icono = '2'
			ORDER BY A.orden ASC";		
			$result_carpetas_3 = mysql_query($query_carpetas_3);
			while ($rs_carpetas_3 = mysql_fetch_assoc($result_carpetas_3))
			{
					?>
	                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="12%" bgcolor="#FFF3E8">&nbsp;</td>
                        <td width="5%" bgcolor="#FFF3E8" class="detalle_medio"><p><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></p></td>
                        <td width="46%" bgcolor="#FFF3E8" class="detalle_medio"><span class="detalle_chico">
                          <?= $rs_carpetas_3['orden'] ?>. </span> <span class="detalle_medio_bold">
                        <?= $rs_carpetas_3['titulo'] ?>
                        <a name="cat" id="cat<?= $rs_carpetas_3['idadmin_menu'] ?>"></a></span></td>
                        <td width="37%" bgcolor="#FFF3E8"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td width="31%" align="left" class="detalle_chico style1">Propiedades</td>
                              <td width="41%" align="center" class="detalle_chico style1">Perfil</td>
                              <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                            </tr>
                            <tr>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="left"><? 
								   if($rs_carpetas_3['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_3['idadmin_menu'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_3['idadmin_menu'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td align="center"><? 
									//boton para el perfil de usuario									
									$query_perfil = "SELECT A.iduser_admin_perfil, A.titulo
									FROM user_admin_perfil A
									WHERE A.estado = 1 ";
									$result_perfil = mysql_query($query_perfil);		
									while ($rs_perfil = mysql_fetch_assoc($result_perfil)){
									
										$query_menu_perfil = "SELECT A.idadmin_menu_perfil
										FROM admin_menu_perfil A
										WHERE A.iduser_admin_perfil = $rs_perfil[iduser_admin_perfil] AND A.idadmin_menu = $rs_carpetas_3[idadmin_menu]";
										$result_menu_perfil = mysql_query($query_menu_perfil);	
										$rs_menu_perfil = mysql_fetch_assoc($result_menu_perfil);	
										$num_menu_perfil = mysql_num_rows($result_menu_perfil);	
										
										if ($num_menu_perfil > '0') {
											echo '&nbsp;<a style="color: #FF6600; font-weight: bold;" href="javascript:cambiar_perfil(2,'.$rs_carpetas_3['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_hab.png" border="0" title="'.$rs_perfil['titulo'].' :: Habilitado"></a>';			
										}else {		 
											echo '&nbsp;<a style="color: #000000; font-weight: bold;" href="javascript:cambiar_perfil(1,'.$rs_carpetas_3['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_deshab.png" border="0" title="'.$rs_perfil['titulo'].' :: Deshabilitado"></a>';
										};			
									};//fin perfil usuario
								  ?>                                  </td>
                                </tr>
                              </table></td>
                              <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="right"><a href="admin_menu_editar.php?idadmin_menu=<?= $rs_carpetas_3['idadmin_menu'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a><a href="carpeta_editar.php?idcarpeta=<?= $rs_carpetas_3['idadmin_menu'] ?>"></a>
                                    <? 
									  echo "&nbsp;<a href=\"javascript:confirmar_eliminar(".$rs_carpetas_3['idadmin_menu'].");\"><img src=\"../../imagen/trash.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
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
			$query_carpetas_4 = "SELECT DISTINCT A.idadmin_menu, A.titulo, A.seguro, A.orden, A.estado
			FROM admin_menu A
			WHERE A.idadmin_menu_padre = $rs_carpetas_3[idadmin_menu] AND A.estado <> 3 AND A.icono = '2'
			ORDER BY A.orden ASC";		
			$result_carpetas_4 = mysql_query($query_carpetas_4);
			while ($rs_carpetas_4 = mysql_fetch_assoc($result_carpetas_4))
			{
					?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="18%" bgcolor="#FFF8F0">&nbsp;</td>
                        <td width="5%" bgcolor="#FFF8F0" class="detalle_medio"><p><img src="../../imagen/iconos/folder_xp25.png" width="25" height="25" /></p></td>
                        <td width="40%" bgcolor="#FFF8F0" class="detalle_medio"><span class="detalle_chico">
                          <?= $rs_carpetas_4['orden'] ?>. </span> <span class="detalle_medio_bold">
                        <?= $rs_carpetas_4['titulo'] ?>
                        <a name="cat" id="cat<?= $rs_carpetas_4['idadmin_menu'] ?>"></a></span></td>
                        <td width="37%" bgcolor="#FFF8F0"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                              <td width="31%" align="left" class="detalle_chico style1">Propiedades</td>
                              <td width="41%" align="center" class="detalle_chico style1">Perfil</td>
                              <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                            </tr>
                            <tr>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="left"><? 
								   if($rs_carpetas_4['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_carpetas_4['idadmin_menu'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_carpetas_4['idadmin_menu'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?></td>
                                  </tr>
                              </table></td>
                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td align="center"><? 
									//boton para el perfil de usuario									
									$query_perfil = "SELECT A.iduser_admin_perfil
									FROM user_admin_perfil A
									WHERE A.estado = 1 ";
									$result_perfil = mysql_query($query_perfil);		
									while ($rs_perfil = mysql_fetch_assoc($result_perfil)){
									
										$query_menu_perfil = "SELECT A.idadmin_menu_perfil
										FROM admin_menu_perfil A
										WHERE A.iduser_admin_perfil = $rs_perfil[iduser_admin_perfil] AND A.idadmin_menu = $rs_carpetas_4[idadmin_menu]";
										$result_menu_perfil = mysql_query($query_menu_perfil);	
										$rs_menu_perfil = mysql_fetch_assoc($result_menu_perfil);	
										$num_menu_perfil = mysql_num_rows($result_menu_perfil);	
										
										if ($num_menu_perfil > '0') {
											echo '&nbsp;<a style="color: #FF6600; font-weight: bold;" href="javascript:cambiar_perfil(2,'.$rs_carpetas_4['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')">'.$rs_perfil['iduser_admin_perfil'].'</a>';			
										}else {		 
											echo '&nbsp;<a style="color: #000000; font-weight: bold;" href="javascript:cambiar_perfil(1,'.$rs_carpetas_4['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')">'.$rs_perfil['iduser_admin_perfil'].'</a>';
										};			
									};//fin perfil usuario
								  ?>                                  </td>
                                </tr>
                              </table></td>
                              <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td align="right"><a href="admin_menu_editar.php?idadmin_menu=<?= $rs_carpetas_4['idadmin_menu'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a><a href="carpeta_editar.php?idcarpeta=<?= $rs_carpetas_4['idadmin_menu'] ?>"></a>
                                    <? echo "&nbsp;<a href=\"javascript:confirmar_eliminar(".$rs_carpetas_4['idadmin_menu'].");\"><img src=\"../../imagen/trash.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
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
                    <td align="center" class="titulo_medio_bold" height="50">No se han encontrado categorias para el menu.</td>
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