<? 	include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	
	//VARIABLES
	$filtro_idioma = $_POST['filtro_idioma'];
	$filtro_sede = $_POST['filtro_sede'];
	$accion = $_POST['accion'];
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
		$filtrado_sede = " AND C.idsede = '$filtro_sede' ";
	}else{
		$obj_value = '';
		//FILTRADO
		if($_POST['filtro_sede'] != 0){ 
			$_SESSION['filtro_sede'] = $_POST['filtro_sede'];
			$filtro_sede = $_SESSION['filtro_sede'];
			$filtrado_sede = " AND C.idsede = '$filtro_sede' ";
		}else{
			$filtrado_sede = "";
			$filtro_sede = 0;
			$filtro_sede = $_SESSION['filtro_sede'];
		}
	}
	
	
	
	if($_POST['filtro_idioma'] != 0){ 
		$_SESSION['filtro_idioma'] = $_POST['filtro_idioma'];
		$filtro_idioma = $_SESSION['filtro_idioma'];
		$filtrado_idioma = " AND B.estado = '1' AND B.ididioma = '$filtro_idioma' ";
	}else{
		$filtrado_idioma = " AND B.ididioma = '1' ";
		$filtro_idioma = $_SESSION['filtro_idioma'];
	}
	
	
	//REORDENAR
	if($accion == "reordenar"){
	
		$orden_row = $_POST['orden_row'];
		$idbarra_pie_row = $_POST['idbarra_pie_row'];
		$cantidad = $_POST['cantidad'];
		
		for($i=0;$i<$cantidad;$i++){
			
			$query_upd = "UPDATE barra_pie
			SET orden = '$orden_row[$i]'
			WHERE idbarra_pie = '$idbarra_pie_row[$i]'";
			mysql_query($query_upd);
			
		}
	}
	
	//CAMBIO DE ESTADO
	$estado = $_POST['estado'];
	$idbarra_pie = $_POST['idbarra_pie'];
	
	if($estado != "" && $idbarra_pie != ""){
		$query = "UPDATE barra_pie
		SET estado = '$estado'
		WHERE idbarra_pie = '$idbarra_pie'	
		LIMIT 1";
		mysql_query($query);
	}
	
	
	//ELIMINAR
	$eliminar = $_POST['eliminar'];
	
	if($eliminar != ""){
		
		$query = "DELETE FROM barra_pie
		WHERE idbarra_pie = '$eliminar'";
		mysql_query($query);
		
		$query = "DELETE FROM barra_pie_sede
		WHERE idbarra_pie = '$eliminar'";
		mysql_query($query);
		
		$query = "DELETE FROM barra_pie_idioma
		WHERE idbarra_pie = '$eliminar'";
		mysql_query($query);
		
	}
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_tips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>
<script type="text/javascript">

	function cambiar_estado(estado, id){
		formulario = document.form;
		
		formulario.estado.value = estado;
		formulario.idbarra_pie.value = id;
		formulario.submit();
	};
	
	function ver_barra(){
		if(document.form.filtro_idioma.value != 0 && document.form.filtro_sede.value != 0){
			document.form.submit();
		}else{
			alert("Seleccione el idioma y sucursal de la barra que desea ver.");
		}
	}
	
	function cambiar_restringido(restringido, idbarra_menu){
		formulario = document.form;
		
		formulario.restringido.value = restringido;
		formulario.idbarra_menu.value = idbarra_menu;
		formulario.submit();
	};
	
	function confirmar_eliminar_elemento(idbarra_menu){
	formulario = document.form;
		if (confirm('¿Esta seguro que desea eliminar el registro?\n Si elimina este elemento se eliminará en sus respectivos idiomas y sede también.\n Si lo que desea hacer es quitarlo, ingrese a "Editar" y configurelo segun idioma y sede.')){
			formulario.eliminar.value = idbarra_menu;
			formulario.submit();
		}
	};
	
	function reordenar(){
		formulario = document.form;
		formulario.accion.value = "reordenar";
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Barra Pie - Ver </td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#cccccc;"></td>
              </tr>
            </table>
              <form action="" method="post" name="form" id="form">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr align="center" valign="middle" height="50">
                    <td height="20" align="left" valign="top" class="detalle_medio" style="color:#FF6600">
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
                                      <td width="25%" align="right"><img src="../../imagen/iconos/sede.png" width="14" height="20" border="0" /></td>
                                      <td width="75%" class="detalle_medio">Sucursales</td>
                                    </tr>
                                  </table></td>
                                  <td width="33%" bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                                    <tr>
                                      <td width="24%" align="right"><img src="../../imagen/iconos/idioma.png" width="18" height="20" border="0" /></td>
                                      <td width="76%" class="detalle_medio">Idiomas</td>
                                    </tr>
                                  </table></td>
                                </tr>

                                <tr>
                                  <td height="30" colspan="3" bgcolor="#FFF0E1" class="detalle_medio"><strong>Opciones de carpetas </strong></td>
                                </tr>
                                <tr>
                                  <td bgcolor="#FFF0E1"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td width="25%" align="right"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></td>
                                        <td width="75%" class="detalle_medio">Eliminar</td>
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
                          <td width="18%" align="right"><select name="filtro_idioma" style="width:160px;" class="detalle_medio" id="filtro_idioma" onchange="javascript:document.form.submit();">
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
                          <td align="right"><select name="filtro_sede" style="width:160px;" class="detalle_medio" id="filtro_sede" <?= $obj_value ?> onchange="javascript:document.form.submit();">
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
                      </table>
                      <br />
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td height="40" align="left" valign="middle" bgcolor="#FFE9D2" class="titulo_medio_bold">&nbsp; V&iacute;nculos</td>
                              <td align="right" valign="middle" bgcolor="#FFE9D2"><span class="titulo_medio_bold"><span class="detalle_medio_bold">
                                <input name="eliminar" type="hidden" id="eliminar" value=""/>
                              </span>
                                  <input name="idbarra_pie" type="hidden" id="idbarra_pie" />
                                  <input name="estado" type="hidden" id="estado" />
                                  <input name="accion" type="hidden" id="accion" value="" />
                                  
                              </span>
							  <? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                                <input name="Button2" type="button" class="detalle_medio_bold" value="Nuevo Boton &raquo;" onclick="window.open('barra_pie_nuevo.php','_self');" />
                                <? } ?>
&nbsp;
<input name="Button22" type="button" class="detalle_medio_bold" value="Reordenar botones &raquo;" onclick="javascript:reordenar();" /></td>
                            </tr>
                            <tr>
                              <td colspan="2" bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                <?
								$c=0;
								$query_barra = "SELECT DISTINCT A.link, A.idbarra_pie, A.orden, A.estado as estado_barra , B.titulo
								FROM barra_pie A
								INNER JOIN barra_pie_idioma B ON A.idbarra_pie = B.idbarra_pie
								INNER JOIN barra_pie_sede C ON A.idbarra_pie = C.idbarra_pie
								WHERE 1=1 $filtrado_sede $filtrado_idioma
								ORDER BY A.orden ASC"; //AND B.ididioma = '$filtro_idioma' AND C.idsede = '$filtro_sede'
								$result_barra = mysql_query($query_barra);
								while($rs_barra = mysql_fetch_assoc($result_barra)){
								?>
								<tr>
                                  <td width="4%" align="center" valign="middle"><img src="../../imagen/arrow.gif" width="14" height="12" />
                                  <input name="idbarra_pie_row[<?= $c ?>]" type="hidden" id="idbarra_pie_row[<?= $c ?>]" value="<?= $rs_barra['idbarra_pie'] ?>" /></td>
                                  <td width="72%" class="detalle_medio" ><span class="detalle_chico">
                                    <input name="orden_row[<?= $c ?>]" type="text" class="detalle_chico" id="orden_row[<?= $c ?>]" style="width:18px; height:12px; text-align:center; line-height:12px; border:1px solid #CCCCCC; " value="<?= $rs_barra['orden'] ?>" />
                                    . </span><span style="color:#000000"><?= $rs_barra['titulo'] ?></span>
                                  <br />
                                  <span class="detalle_chico" style="color:#666666">
                                  <?= $rs_barra['link'] ?>
                                  </span></td>
                                  <td width="24%" align="right" class="detalle_medio"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="72%" align="left" class="detalle_chico style1">Propiedades</td>
                                      <td width="28%" align="right" class="detalle_chico style1">Opciones</td>
                                    </tr>
                                    <tr>
                                      <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td align="left" valign="middle"><? 
								   if($rs_barra['estado_barra'] == 1){ 
									  echo "<a href=\"javascript:cambiar_estado(2,".$rs_barra['idbarra_pie'].");\"><img src=\"../../imagen/b_habilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }else{
									  echo "<a href=\"javascript:cambiar_estado(1,".$rs_barra['idbarra_pie'].");\"><img src=\"../../imagen/b_deshabilitado.png\" width=\"15\" height=\"16\" border=\"0\" /></a>";
								   }
								  ?>&nbsp; <img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idiomas  ::
								  <?
								  $query_idioma = "SELECT B.titulo_idioma
								  FROM barra_pie_idioma A
								  INNER JOIN idioma B ON A.ididioma = B.ididioma
								  WHERE A.idbarra_pie = $rs_barra[idbarra_pie] AND A.estado = 1
								  ORDER BY B.titulo_idioma";
								  
								  $result_idioma = mysql_query($query_idioma);
								  while($rs_idioma = mysql_fetch_assoc($result_idioma)){
								  	echo "&bull; ".$rs_idioma['titulo_idioma']."<br>";
								  }
								  ?>"/> <img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT C.titulo
								  FROM barra_pie A
								  INNER JOIN barra_pie_sede B ON A.idbarra_pie = B.idbarra_pie
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idbarra_pie = '$rs_barra[idbarra_pie]'
								  ORDER BY C.titulo";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" /> </td>
                                          </tr>
                                      </table></td>
                                      <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td align="right"><a href="barra_pie_editar.php?idbarra_pie=<?= $rs_barra['idbarra_pie'] ?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a> &nbsp;<a href="javascript:confirmar_eliminar_elemento(<?= $rs_barra['idbarra_pie'] ?>);"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                  </table>                                    </td>
								</tr>
								<? $c++; 
								} ?>
                              </table></td>
                            </tr>
                      </table>
                      <input name="cantidad" type="hidden" id="cantidad" value="<?= $c ?>" /></td>
                  </tr>
                </table>
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