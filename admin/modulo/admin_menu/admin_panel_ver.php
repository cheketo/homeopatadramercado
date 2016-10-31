<? 
	include ("../../0_mysql.php"); 
	
	//SI EL USUARIO ADMIN ES NIVEL 1
	if($_SESSION[idcusuario_perfil_log]=='1'){
		$usuario_perfil = 1;
	}else{
		$usuario_perfil = 2;
	}
	
	//VARIABLES
	$accion = $_POST['accion'];
	$habilitar = $_GET['habilitar'];
	$deshabilitar = $_GET['deshabilitar'];
	
	//CAMBIO DE ESTADO
	$estado = $_POST['estado'];
	$idadmin_menu = $_POST['idadmin_menu'];
	
	if($estado != "" && $idadmin_menu != "" && $accion == "cambiar_estado"){
		$query = "UPDATE admin_menu
		SET estado = $estado
		WHERE idadmin_menu = '$idadmin_menu'	
		LIMIT 1";
		mysql_query($query);
	}
	
	
	
	//ELIMINAR
	$eliminar = $_POST['eliminar'];
	if($eliminar != "" && $idadmin_menu != ""){
		
		//COMPROBAR QUE NO EXISTAN SUB CARPETAS ANTES DE ELIMINAR.
		$query_eliminar = "SELECT *
		FROM admin_menu
		WHERE idadmin_menu_padre = '$idadmin_menu' AND estado != 3";
		if(mysql_num_rows(mysql_query($query_eliminar)) == 0){
		
			$query = "UPDATE admin_menu
			SET estado = '$eliminar'
			WHERE idadmin_menu = '$idadmin_menu'	
			LIMIT 1";
			mysql_query($query);
			
		}else{
			echo "<script>alert('No se pudo eliminar la categoria del menu porque posee subcategorias.');</script>";
		}
	}

	//DESHABILITA PERFIL ICONO
	if ($_POST['estado'] =='2' && $_POST['iduser_admin_perfil'] && $_POST['idadmin_menu'] && $accion == "cambiar_perfil"){	 
		$query_eliminar = "DELETE FROM admin_menu_perfil WHERE iduser_admin_perfil = '$_POST[iduser_admin_perfil]' AND idadmin_menu = '$_POST[idadmin_menu]'";
		mysql_query($query_eliminar);
	}
	
	//HABILITA PERFIL ICONO
	if ($_POST['estado'] =='1' && $_POST['iduser_admin_perfil'] && $_POST['idadmin_menu'] && $accion == "cambiar_perfil"){	 
		$query_ingreso = "INSERT INTO admin_menu_perfil (
		  iduser_admin_perfil
		, idadmin_menu
		) VALUES (
		  '$_POST[iduser_admin_perfil]'
		, '$_POST[idadmin_menu]'
		)";
		mysql_query($query_ingreso);
	}
	
	//HABILITA OPCION ICONO
	if ($_POST['estado'] == '1' && $_POST['iduser_admin_perfil'] && $_POST['idadmin_opcion']){	 
		$query_ingreso = "INSERT INTO admin_opcion_perfil (
		  iduser_admin_perfil
		, idadmin_opcion
		) VALUES (
		  '$_POST[iduser_admin_perfil]'
		, '$_POST[idadmin_opcion]'
		)";
		mysql_query($query_ingreso);
	}
	
	//DESHABILITA OPCION ICONO
	if ($_POST['estado'] =='2' && $_POST['iduser_admin_perfil'] && $_POST['idadmin_opcion']){	 
		$query_eliminar = "DELETE FROM admin_opcion_perfil WHERE iduser_admin_perfil = '$_POST[iduser_admin_perfil]' AND idadmin_opcion = '$_POST[idadmin_opcion]'";
		mysql_query($query_eliminar);
	}
	
	
	if($_POST['eliminar'] != "" && $_POST['idadmin_opcion'] != ""){
		$query_eliminar = "DELETE FROM admin_opcion WHERE idadmin_opcion = '$_POST[idadmin_opcion]'";
		mysql_query($query_eliminar);
		
		$query_eliminar = "DELETE FROM admin_opcion_perfil WHERE idadmin_opcion = '$_POST[idadmin_opcion]'";
		mysql_query($query_eliminar);
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
<script language="javascript">

	function cambiar_estado(estado, idadmin_menu){
		formulario = document.form_lista;
		
		formulario.estado.value = estado;
		formulario.idadmin_menu.value = idadmin_menu;
		formulario.accion.value = "cambiar_estado";
		formulario.submit();
	};
	
	function confirm_cambiar_estado(estado, idadmin_menu){
	formulario = document.form_lista;
		if (confirm('¿Esta seguro que desea eliminar el registro?')){
			formulario.eliminar.value = estado;
			formulario.idadmin_menu.value = idadmin_menu;
			formulario.submit();
		}
	};
	function confirm_cambiar_estado_opcion(estado, idadmin_opcion){
	formulario = document.form_lista;
		if (confirm('¿Esta seguro que desea eliminar el registro?')){
			formulario.eliminar.value = estado;
			formulario.idadmin_opcion.value = idadmin_opcion;
			formulario.submit();
		}
	};
	
	function cambiar_seguro(seguro, idadmin_menu){
		formulario = document.form_lista;
		
		formulario.seguro.value = seguro;
		formulario.idadmin_menu.value = idadmin_menu;
		formulario.submit();
	};
	
	function cambiar_perfil(estado, idadmin_menu, iduser_admin_perfil){
		formulario = document.form_lista;
		
		formulario.estado.value = estado;
		formulario.idadmin_menu.value = idadmin_menu;
		formulario.iduser_admin_perfil.value = iduser_admin_perfil;
		formulario.accion.value = "cambiar_perfil";		
		formulario.submit();
	};
	function cambiar_opcion_perfil(estado, idadmin_opcion, iduser_admin_perfil){
		formulario = document.form_lista;
		
		formulario.estado.value = estado;
		formulario.idadmin_opcion.value = idadmin_opcion;
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
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style>
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Menu Iconos Admin -  Ver (&aacute;rbol)</td>
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
                        <td width="34%" bgcolor="#FFF0E1">&nbsp;</td>
                        <td width="33%" bgcolor="#FFF0E1">&nbsp;</td>
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
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td height="40" align="right" valign="middle"><input name="accion" type="hidden" id="accion" />
                  <input name="estado" type="hidden" id="estado" />
                    <input name="idadmin_menu" type="hidden" id="idadmin_menu"/>
                    <input name="idadmin_opcion" type="hidden" id="idadmin_opcion" />
                    <input name="seguro" type="hidden" id="seguro"/>
                    <input name="eliminar" type="hidden" id="eliminar" />
                    <input name="iduser_admin_perfil" type="hidden" id="iduser_admin_perfil" />
                  <input name="Button" type="button" class="detalle_medio_bold" onclick="javascript:window.location.href('admin_panel_nuevo.php')" value=" Nuevo Icono &raquo;" /></td>
                </tr>
              </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="7">
                <tr>
                  <td height="40" bgcolor="#FFE9D2" class="titulo_medio_bold">Iconos del Menu </td>
                </tr>
                <tr>
                  <td align="center" bgcolor="#FFF5EC"><? 
				  //Fotos Extra horizontal
				  $query_icono = "SELECT *
				  FROM admin_menu
				  WHERE estado != '3' AND icono = 1
				  ORDER BY orden";
				  $result_icono = mysql_query($query_icono);
				  $cant_icono = mysql_num_rows($result_icono);//indica la cantidad de fotos
				  
				  if($cant_icono > 0){//si la cantidad de iconos es 0, no lo muestra
			?>
                    <table width="100" border="0" cellpadding="0" cellspacing="8">
                      <tr valign="top">
                        <? 						
						$vuelta_icono = 1;//indicador inicial de vueltas, para el sistema de columnas
					  while( $rs_icono = mysql_fetch_assoc($result_icono) ){//while de foto extra horizontal		  					  
					
					?>
                        <td align="center" valign="top" bgcolor="#FFF0E1" class="ejemplo_12px"><a href="modulo/user_admin/usuario_admin_nuevo.php"></a>
                            <table width="100%" border="0" cellspacing="0" cellpadding="6" style="border-bottom:1px; border-bottom-color:#999999; border-bottom-style:solid">
                              <tr>
                                <td colspan="2" align="left" class="detalle_medio_bold"><strong>
                                  <?= $rs_icono['titulo']; ?>
                                                                </strong></td>
                              </tr>
                              <tr>
                                <td width="25%"><img src="../../imagen/admin_panel/<?= $rs_icono['foto']; ?>" border="0" /></td>
                                <td width="75%" align="left" valign="bottom" ><table width="100%" border="0" cellspacing="0" cellpadding="2">

                                  <tr>
                                    <td width="28%" align="right">
                                      <? 
								   if($rs_icono['estado'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_icono['idadmin_menu'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_icono['idadmin_menu'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?>
                                      <a href="admin_panel_editar.php?idadmin_menu=<?= $rs_icono['idadmin_menu'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a>
                                    <? 
									  echo "&nbsp;<a href=\"javascript:confirm_cambiar_estado(3,".$rs_icono['idadmin_menu'].");\"><img src=\"../../imagen/trash.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								  ?></td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table>
                            <table width="210" border="0" cellpadding="3" cellspacing="0">

                              <tr>
                                <td height="1" bgcolor="#FFE7CE" class="detalle_chico"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td width="24%" align="left" style="font:Arial, Helvetica, sans-serif; color:#333333; font-size:11px;">Perfil</td>
                                    <td width="76%" align="right"><? 
									//boton para el perfil de usuario									
									$query_perfil = "SELECT A.iduser_admin_perfil, A.titulo
									FROM user_admin_perfil A
									WHERE A.estado = 1 ";
									$result_perfil = mysql_query($query_perfil);		
									while ($rs_perfil = mysql_fetch_assoc($result_perfil)){
									
										$query_menu_perfil = "SELECT A.idadmin_menu_perfil
										FROM admin_menu_perfil A
										WHERE A.iduser_admin_perfil = $rs_perfil[iduser_admin_perfil] AND A.idadmin_menu = $rs_icono[idadmin_menu]";
										$result_menu_perfil = mysql_query($query_menu_perfil);	
										$rs_menu_perfil = mysql_fetch_assoc($result_menu_perfil);	
										$num_menu_perfil = mysql_num_rows($result_menu_perfil);	
										
										if ($num_menu_perfil > '0') {
											echo '&nbsp;<a style="color: #FF6600; " href="javascript:cambiar_perfil(2,'.$rs_icono['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_hab.png" border="0" title="'.$rs_perfil['titulo'].' :: Habilitado"></a>';			
										}else {		 
											echo '&nbsp;<a style="color: #000000; " href="javascript:cambiar_perfil(1,'.$rs_icono['idadmin_menu'].','.$rs_perfil['iduser_admin_perfil'].')"><img class="Tips1" src="../../imagen/iconos/user_perfil_deshab.png" border="0" title="'.$rs_perfil['titulo'].' :: Deshabilitado"></a>';
										};			
									};//fin perfil usuario
								  ?></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td height="-2" bgcolor="#FFD9B3" class="detalle_chico"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                  <tr>
                                    <td width="72%" height="20" align="left" valign="middle" style="font:Arial, Helvetica, sans-serif; color:#333333; font-size:11px;">Opciones del boton</td>
                                    <td width="28%" align="right" valign="middle" style="font:Arial, Helvetica, sans-serif; color:#333333; font-size:11px;"><a href="admin_opcion_nuevo.php?idadmin_menu=<?= $rs_icono['idadmin_menu'] ?>" target="_self">Nuevo</a></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td height="-2" bgcolor="#FFE7CE" class="detalle_chico"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                    <?
						//Opciones para cada boton									
						$query_opcion = "SELECT A.*
						FROM admin_opcion A
						WHERE A.idadmin_menu = $rs_icono[idadmin_menu]
						ORDER BY A.orden";
						$result_opcion = mysql_query($query_opcion);		
						while ($rs_opcion = mysql_fetch_assoc($result_opcion)){
				
				?>
                                    <tr>
                                      <td width="47%" align="left"><a href="../../<?= $rs_opcion['link']  ?>" target="_blank">
                                        <?= $rs_opcion['titulo']  ?>
                                      </a></td>
                                      <td width="25%" align="center"><? 
									//boton para el perfil de usuario									
									$query_perfil = "SELECT A.iduser_admin_perfil
									FROM user_admin_perfil A
									WHERE A.estado = 1 ";
									$result_perfil = mysql_query($query_perfil);		
									while ($rs_perfil = mysql_fetch_assoc($result_perfil)){
									
										$query_opcion_perfil = "SELECT A.idadmin_opcion_perfil
										FROM admin_opcion_perfil A
										WHERE A.iduser_admin_perfil = $rs_perfil[iduser_admin_perfil] AND A.idadmin_opcion = $rs_opcion[idadmin_opcion]";
										$result_opcion_perfil = mysql_query($query_opcion_perfil);	
										$rs_opcion_perfil = mysql_fetch_assoc($result_opcion_perfil);	
										$num_opcion_perfil = mysql_num_rows($result_opcion_perfil);	
										
										if ($num_opcion_perfil > '0') {
											echo '&nbsp;<a style="color: #FF6600; " href="javascript:cambiar_opcion_perfil(2,'.$rs_opcion['idadmin_opcion'].','.$rs_perfil['iduser_admin_perfil'].')">'.$rs_perfil['iduser_admin_perfil'].'</a>';			
										}else {		 
											echo '&nbsp;<a style="color: #000000; " href="javascript:cambiar_opcion_perfil(1,'.$rs_opcion['idadmin_opcion'].','.$rs_perfil['iduser_admin_perfil'].')">'.$rs_perfil['iduser_admin_perfil'].'</a>';
										};			
									};//fin perfil usuario
								  ?></td>
                                      <td width="28%" align="right"><a href="admin_opcion_editar.php?idadmin_opcion=<?= $rs_opcion['idadmin_opcion'] ?>" target="_self"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a>&nbsp; <a href="javascript:confirm_cambiar_estado_opcion(3,<?= $rs_opcion['idadmin_opcion'] ?>);"><img src="../../imagen/trash.png" width="15" height="16" border="0" /></a> </td>
                                    </tr>
                                    <? }// fin while opciones de boton ?>
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
                    <p>
                      <? }//Fin foto extra horizontal  ?>
                  </p></td>
                </tr>
              </table>
              <p>&nbsp;</p>
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