<? require_once ("../../0_mysql.php"); 

	$accion = $_POST['accion'];
	$idba_banner = $_POST['idba_banner'];
	$idba_anunciante = $_POST['idba_anunciante'];
	$ruta_foto = "../../../imagen/banner/";
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
		$filtrado_sede = " AND E.idsede = '$filtro_sede' ";
	}else{
		$obj_value = '';
	}

	//CAMBIAR ESTADO
	if($accion == "cambiar_estado"){
		
		$estado = $_POST['estado'];
		$ididioma = $_POST['ididioma'];
		$idba_banner = $_POST['id'];
			
		$query_update = "UPDATE ba_banner
		SET estado = '$estado'
		WHERE idba_banner= '$idba_banner' AND ididioma = '$ididioma'
		LIMIT 1";
		mysql_query($query_update);
		
		$accion = "buscar";
		
	}
	
	//BORRAR TITULAR
	if($accion == "eliminar"){
	
		$ididioma_eliminar = $_POST['ididioma_eliminar'];
		$idba_banner = $_POST['id'];

		$query_preliminar = "SELECT * 
		FROM ba_banner
		WHERE idba_banner = '$idba_banner' AND ididioma = '$ididioma_eliminar' ";
		$rs_preliminar = mysql_fetch_assoc(mysql_query($query_preliminar));
		$cant = mysql_num_rows(mysql_query($query_preliminar));
		
		if (file_exists($ruta_foto.$rs_preliminar['archivo']) && $cant > 0){
			unlink($ruta_foto.$rs_preliminar['archivo']);
		};

		$query_eliminar = "DELETE FROM ba_banner WHERE idba_banner = '$idba_banner' AND ididioma = '$ididioma_eliminar' ";
		mysql_query($query_eliminar);
		
		$accion = "buscar";
		$idba_banner = "";

	}
	
	//SI HAY  POR GET REEMPLAZA LOS ANTERIORES
	if($_GET['idba_anunciante']){
		$idba_anunciante = $_GET['idba_anunciante'];
	}
	
	if($_GET['idba_banner']){
		$idba_banner = $_GET['idba_banner'];
	}

	//Introduzco las variables tomadas anteriormente en la cadena 'filtro' que sirve para pasarselo a la consulta.
	
	$filtro = "";
	if($idba_anunciante && $idba_anunciante!='todo'){
		$filtro .= " AND A.idba_anunciante = '$idba_anunciante' ";
	}
	if($idba_banner && $idba_anunciante!='todo'){
		$filtro .= " AND A.idba_banner = '$idba_banner' ";
	}
	if($_POST['ididioma']){
		$filtro .= " AND A.ididioma = '$_POST[ididioma]' ";
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

<script language="Javascript">

	function cambiar_estado(estado, idba_banner, ididioma){
		formulario = document.form_banner;
		
		formulario.estado.value = estado;
		formulario.id.value = idba_banner;
		formulario.ididioma.value = ididioma;
		
		formulario.accion.value = "cambiar_estado";
		formulario.submit();
		
	};
	
	function confirmar_eliminar(idba_banner, ididioma){
		if (confirm('¿Está seguro que desea borrar el banner?')){
			var formulario = document.form_banner;
			
			formulario.ididioma_eliminar.value = ididioma;
			formulario.id.value = idba_banner;
			formulario.accion.value = "eliminar";
			formulario.submit();
		}
	};
	
	function validar_form_banner(){
		formulario = document.form_banner;
		
		formulario.accion.value = "buscar";
		formulario.submit();
	};
	
	window.addEvent('domready', function(){
	
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
	<div class="fondo_tabla_principal" id="content">
	  <form name="form_banner" id="form_banner" method="post" action="">
            
			<table width="100%" border="0" cellspacing="0" cellpadding="12">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="40" valign="bottom" class="titulo_grande_bold"><span class="titulo_grade_bold">Banner - Ver </span></td>
                          </tr>
                          <tr>
                            <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#CCCCCC;"></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><input name="accion" type="hidden" id="accion" />
                        <input name="estado" type="hidden" id="estado" />
                      <input name="ididioma_eliminar" type="hidden" id="ididioma_eliminar" />
                      <input name="id" type="hidden" id="id" />
					  
					  <? if(!$_GET['idba_banner']){ ?>
					  <table width="100%" border="0" cellpadding="5" cellspacing="0" >
                          <tr>
                            <td height="40" colspan="4" valign="middle" bgcolor="#d8f6ee" class="titulo_medio_bold">Busqueda de banners                            </td>
                          </tr>
                          <tr>
                            <td colspan="4" valign="middle" bgcolor="#eafcf7" class=""><table width="100%" border="0" cellpadding="5" cellspacing="0">
                              <tr>
                                <td width="14%" class="detalle_medio">N&ordm; de Banner: </td>
                                <td width="86%" colspan="3"><input name="idba_banner" type="text" class="detalle_medio" id="idba_banner" value="<?= $_POST['idba_banner'] ?>" size="6" /></td>
                              </tr>
                              <tr>
                                <td width="14%" class="detalle_medio">Idioma:</td>
                                <td colspan="3"><span class="style2">
                                  <select name="ididioma" class="detalle_medio" id="ididioma" onchange="" style="width:200px;">
                                    <option value="" <? if($_POST['ididioma'] == ""){ echo "selected";} ?>>- Todos los idiomas </option>
                                    <?
										  $query_idioma = "SELECT *  
										  FROM idioma  
										  WHERE estado = 1
										  ORDER BY titulo_idioma";
										  $result_idioma = mysql_query($query_idioma);
										  while ($rs_idioma = mysql_fetch_assoc($result_idioma)){
										  
												if ($_POST['ididioma'] == $rs_idioma['ididioma']){ 
													$sel = "selected";
												}else{ 
													$sel = ""; 
												}
									?>
                                    <option  <?= $sel ?> value="<?= $rs_idioma['ididioma'] ?>">
                                      <?= $rs_idioma['titulo_idioma'] ?>
                                    </option>
                                    <? } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td class="detalle_medio">Anunciante:</td>
                                <td colspan="3"><span class="style2">
                                  <select name="idba_anunciante" class="detalle_medio" id="idba_anunciante" onchange="" style="width:200px;">

                                    <option value="todo" <? if($_POST['idba_anunciante'] == "todo"){ echo "selected";} ?>>- Todos los anunciantes</option>
                                    <?
										  $query_anunciantes = "SELECT *  
										  FROM ba_anunciante  
										  WHERE estado = 1
										  ORDER BY nombre";
										  $result_anunciantes = mysql_query($query_anunciantes);
										  while ($rs_anunciantes = mysql_fetch_assoc($result_anunciantes)){
										  
												if ($_POST['idba_anunciante'] == $rs_anunciantes['idba_anunciante']){ 
													$sel = "selected";
												}else{ 
													$sel = ""; 
												}
									?>
                                    <option  <?= $sel ?> value="<?= $rs_anunciantes['idba_anunciante'] ?>"><?= $rs_anunciantes['nombre'] ?></option>
                                    <? } ?>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td class="detalle_medio_bold">&nbsp;</td>
                                <td colspan="3"><span class="style2">
                                  <input name="Submit222" type="button" class="detalle_medio_bold" value="  &raquo; Buscar  " onclick="javascript:validar_form_banner()" />
                                </span></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
						<? } ?>
								
                      <br></td>
                    </tr>
                    <tr>
                      <td>
					  <? if($accion == "buscar" || $_GET['idba_banner']){ ?>
					  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                          <tr>
                            <td height="40" colspan="11" bgcolor="#d8f6ee" class="titulo_medio_bold">Banners Filtrados </td>
                          </tr>
                          <tr>
                            <td width="40" align="center" bgcolor="#DCF8ED" class="detalle_medio_bold">ID</td>
                            <td width="80" bgcolor="#DCF8ED" class="detalle_medio_bold">Anunciante</td>
                            <td width="304" bgcolor="#DCF8ED" class="detalle_medio_bold">Banner</td>
                            <td width="115" bgcolor="#DCF8ED" class="detalle_medio_bold">Posicion</td>
                            <td colspan="4" align="center" bgcolor="#DCF8ED" class="detalle_medio_bold">&nbsp;</td>
                          </tr>
                          <?

								//Realiza consulta, para mostrar los datos enunciados en la tabla EXCEPTO CLICKS REALIZADOS, CLICKS RESTANTES Y VISTAS.
								$query_mod1_lista = "SELECT DISTINCT A.*, B.idba_anunciante, B.nombre , C.*, D.titulo_idioma
								FROM ba_banner A
								INNER JOIN ba_anunciante B ON B.idba_anunciante = A.idba_anunciante 
								INNER JOIN ba_lugar C ON C.idba_lugar = A.idba_lugar
								INNER JOIN idioma D ON D.ididioma = A.ididioma
								INNER JOIN ba_banner_sede E ON E.idba_banner = A.idba_banner
								WHERE A.estado <> 3 $filtro $filtrado_sede
								ORDER BY A.idba_banner";
								
								$result_mod1_lista = mysql_query($query_mod1_lista);
								$color=2;
								while ($rs_mod1_lista = mysql_fetch_assoc($result_mod1_lista)){
								
									$hay_lista = true;
									if($color == 1){
										$bgcolor = "#DCF8ED";
										$color=2;
									}else{
										$bgcolor = "#eafcf7";
										$color=1;
									}
							
						  ?>
                          <tr valign="top" bgcolor="<?= $bgcolor ?>">
                            <td height="12" align="center" bgcolor="<?= $bgcolor ?>" class="detalle_chico"><?= $rs_mod1_lista['idba_banner'] ?>.<span class="detalle_medio_bold"><a name="<?= $rs_mod1_lista['idba_banner'] ?>"></a></span></td>
                            <td height="12" bgcolor="<?= $bgcolor ?>" class="detalle_medio"><?= $rs_mod1_lista['nombre'] ?></td>
                            <td bgcolor="<?= $bgcolor ?>" class="detalle_medio"><?= $rs_mod1_lista['titulo_idioma'] ?>
                              <br />
                              <? 
							if($rs_mod1_lista['archivo']){
								$imagen =& new obj0001('0','../../../imagen/banner/',$rs_mod1_lista['archivo'],'','200','','','../../../imagen/banner/'.$rs_mod1_lista['archivo'],'_blank','','',''); 
							}else{
							?>
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="11%" bgcolor="#FF0000"><img src="../../../imagen/varios/alert20px.png" width="24" height="20" /></td>
                                <td width="89%" bgcolor="#FF0000" class="detalle_medio_bold_white"> Atenci&oacute;n: No tiene un banner cargado.</td>
                              </tr>
                            </table>							
							<?	
							}	
							?></td>
                            <td align="left" bgcolor="<?= $bgcolor ?>" class="detalle_medio"><?= $rs_mod1_lista['nombre_lugar']; ?></td>
                            <td width="17" align="center" bgcolor="<?= $bgcolor ?>" class="detalle_medio">
							    <? if ($rs_mod1_lista['estado'] == '1') { ?>
                                <a href="javascript:cambiar_estado(2,<?= $rs_mod1_lista['idba_banner'] ?>,<?= $rs_mod1_lista['ididioma'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                                <? } else { ?>
                                <a href="javascript:cambiar_estado(1,<?= $rs_mod1_lista['idba_banner'] ?>,<?= $rs_mod1_lista['ididioma'] ?>);"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>
                                <? } ?></td>
                            <td width="18" align="center" bgcolor="<?= $bgcolor ?>" class="detalle_medio"><img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT DISTINCT C.titulo
								  FROM ba_banner A
								  INNER JOIN ba_banner_sede B ON A.idba_banner = B.idba_banner
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idba_banner = '$rs_mod1_lista[idba_banner]'
								  ORDER BY C.titulo";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" /></td>
                            <td width="16" align="right" bgcolor="<?= $bgcolor ?>" class="detalle_medio_bold"><a href="ba_banner_editar.php?idba_banner=<?= $rs_mod1_lista['idba_banner'] ?>&ididioma=<?= $rs_mod1_lista['ididioma'] ?>" class="style10"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a></td>
                            <td width="18" align="right" bgcolor="<?= $bgcolor ?>" class="detalle_medio_bold"><a href="javascript:confirmar_eliminar(<?= $rs_mod1_lista['idba_banner'] ?>,<?= $rs_mod1_lista['ididioma'] ?>)" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a></td>
                          </tr>
                          <? } ?>
                            <?  if($hay_lista == false){ ?>
                          <tr valign="top">
                            <td height="40" colspan="9" align="center" valign="middle" bgcolor="#eafcf7" class="detalle_medio_bold">No se encontraron banners. </td>
						  </tr>
                            <? } ?>
                      </table>
					   <? } ?>					  </td>
                    </tr>
                </table></td>
              </tr>
            </table>
	  </form>
  </div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>