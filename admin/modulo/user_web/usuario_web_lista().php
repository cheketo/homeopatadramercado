<? 
	include ("../../0_mysql.php"); 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$busqueda = $_POST['busqueda'];
	
	$iduser_web = $_POST['iduser_web'];
	$estado = $_POST['estado'];
	
	$eliminar = $_POST['eliminar'];


	//FUNCION PAGINAR
	function paginar($actual, $total, $por_pagina, $enlace) {
	  $total_paginas = ceil($total/$por_pagina);
		  $anterior = $actual - 1;
		  $posterior = $actual + 1;
		  $intervalo = 20;
		  
		  if ($actual>1)
			$texto = "<a href=\"javascript:ir_pagina('$anterior');\">&laquo;</a> ";
		  else
			$texto = "<b>&laquo;</b> ";
			
		  $comienzo = $actual-($intervalo/2);
		  if($comienzo < 1) $comienzo = 1;
		  for ($i=$comienzo; $i<$actual; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
		  
		  $texto .= "<b>$actual</b> ";
		  
		  $fin = $comienzo + $intervalo;
		  if($total_paginas <= $fin) $fin = $total_paginas;
		  for ($i=$actual+1; $i<=$fin; $i++)
			$texto .= "<a href=\"javascript:ir_pagina('$i');\">$i</a> ";
			
		  
		  if ($actual<$total_paginas)
			$texto .= "<a href=\"javascript:ir_pagina('$posterior');\">&raquo;</a>";
		  else
			$texto .= "<b>&raquo;</b>";
			
		  return $texto;
	}
    
	//CHEQUEO PARA PAGINAR
	$cantidad_registros = $_POST['cant'];
	if(!$cantidad_registros){
		$cantidad_registros = 25;
	}
	
	$pag = $_POST['pag'];
	if(!$pag){
		$pag = 1;
	}
	$puntero = ($pag-1) * $cantidad_registros;
	
	//BUSQUEDA
	if($busqueda){
		$filtro = " AND nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%' OR  mail LIKE '%$busqueda%' OR  username LIKE '%$busqueda%' OR  emp_denominacion LIKE '%$busqueda%' ";
	}

	//PAGINACION: Cantidad Total.
	$query_cant = "SELECT *
		FROM user_web
		WHERE estado != 4 $filtro
		ORDER BY iduser_web";
	$cantidad_total = mysql_num_rows(mysql_query($query_cant));
	
	//ACTUALIZAR ESTADO
	if($estado && $iduser_web){
		
		$query_estado = "UPDATE user_web SET estado = '$estado'
		WHERE iduser_web = '$iduser_web'
		LIMIT 1 ";
		mysql_query($query_estado);

	};
	
	//ELIMINAR
	if($eliminar){
		
		$query_eliminar = "DELETE FROM user_web WHERE iduser_web = '$eliminar' ";
		mysql_query($query_eliminar);

		$query_eliminar_seg = "DELETE FROM user_web_segmentacion WHERE iduser_web = '$eliminar' ";
		mysql_query($query_eliminar_seg);
		
	};
	
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
	
	function ir_pagina(pag){
		formulario = document.form_lista;
		formulario.pag.value = pag;
		formulario.submit();
	};
	
	function validar_form_lista(){
		formulario = document.form_lista;
		formulario.pag.value = "1";
		formulario.submit();
	};
	
	function buscar(){
		formulario = document.form_lista;
		/*if(formulario.busqueda.value != ""){*/
			formulario.pag.value = "1";
			formulario.submit();
		/*}else{
			alert("Introduzca el texto que desee buscar.");
		}*/
	};
	
	function cambiar_estado(estado, id){
		formulario = document.form_lista;
		
		formulario.estado.value = estado;
		formulario.iduser_web.value = id;
		formulario.submit();
	};

	function confirmar_eliminar(id, mail){
		formulario = document.form_lista;
			if (confirm('¿Esta seguro que desea eliminar este usuario: '+mail+' ?')){
				formulario.eliminar.value = id;
				formulario.submit();
			}
	};
	
	function lista_orden(filtro,orden){
		formulario = document.form_lista;
		formulario.lista_fil.value = filtro;
		formulario.lista_orden.value = orden;
		formulario.action = "";
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
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Web  - Lista </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="right"><form method="post" name="form_lista" id="form_lista">
                      <span class="detalle_chico" style="color:#FF0000">
                      <span class="detalle_medio">
                      <input name="eliminar" type="hidden" id="eliminar" />
                      <input name="iduser_web" type="hidden" id="iduser_web" />
                      <input name="estado" type="hidden" id="estado" />
                      <input name="pag" type="hidden" id="pag" value="<?= $_POST['pag'] ?>" />
                      </span>
                      <input name="accion" type="hidden" id="accion" value="1" />
                      </span>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="50%" align="left" valign="middle" class="detalle_medio"><label>
                            Buscar:
                            <input name="busqueda" type="text" class="detalle_medio" id="busqueda" style="width:180px;" value="<?= $busqueda ?>" />
                             <a href="javascript:buscar();"><img src="../../imagen/iconos/search_mini.png" width="16" height="16" border="0" /></a></label></td>
                          <td width="41%" align="right" valign="middle" class="detalle_medio">Registros por hoja </td>
                          <td width="9%" valign="middle" class="detalle_medio"><select name="cant" class="detalle_medio" onchange="validar_form_lista();">
                              <option value="25" <? if($cantidad_registros == 25){ echo "selected"; } ?> >25</option>
                              <option value="50" <? if($cantidad_registros == 50){ echo "selected"; } ?> >50</option>
                              <option value="100"<? if($cantidad_registros == 100){ echo "selected"; } ?> >100</option>
                          </select></td>
                        </tr>
                        <tr height="5">
                          <td colspan="2" align="right" valign="middle" class="detalle_medio"></td>
                          <td valign="middle" class="detalle_medio"></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#ffddbc">
                          <td width="4%" height="40" align="left" valign="middle" class="detalle_medio_bold">ID</td>
                          <td width="30%" height="40" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">E-mail</td>
                          <td width="33%" height="40" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">Nombre y apellido </td>
                          <td width="14%" height="40" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">Estado</td>
                          <td width="19%" height="40" align="left" valign="middle" class="detalle_medio_bold">Propiedades</td>
                        </tr>
        <?
	
		$cont_usuarios = 0;
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM user_web
		WHERE estado != 4 $filtro
		ORDER BY iduser_web
		LIMIT $puntero,$cantidad_registros ";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			$hay_lista = true;
			$cont_usuarios++;
		
		?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="4%" align="left" valign="middle" class="detalle_chico"><?=$rs_lista['iduser_web']?>
                              <input name="iduser_web_row[<?= $cont_usuarios ?>]" type="hidden" id="iduser_web_row[<?= $cont_usuarios ?>]" value="<?= $rs_lista['iduser_web'] ?>" />
                          <a name="ancla_<?=$rs_lista['iduser_web']?>" id="ancla_<?=$rs_lista['iduser_web']?>"></a></td>
                          <td width="30%" align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><div style="width:190px; max-width:190px; overflow:visible;">
                            <?=$rs_lista['mail']?>
                          </div>
                          </td>
                          <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['apellido'].", ".$rs_lista['nombre']?></td>
                          <td align="left" valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><? if($rs_lista['estado'] == 1){ ?> <img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" />
                            <? }else{ ?>
                            <a href="javascript:cambiar_estado(1,<?=$rs_lista['iduser_web']?>);"> <img src="../../imagen/b_habilitado_off.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                            <? } ?><? if($rs_lista['estado'] == 3){ ?> <img src="../../imagen/b_confirmar.png" alt="A Confirmar" width="15" height="16" border="0" />
<? }else{ ?>
<a href="javascript:cambiar_estado(3,<?=$rs_lista['iduser_web']?>);"> <img src="../../imagen/b_confirmar_off.png" alt="A Confirmar" width="15" height="16" border="0" /></a>
<? } ?><? if($rs_lista['estado'] == 2){ ?> <img src="../../imagen/b_deshabilitado.png" alt="Habilitado" width="15" height="16" border="0" />
<? }else{ ?>
<a href="javascript:cambiar_estado(2,<?=$rs_lista['iduser_web']?>);"> <img src="../../imagen/b_deshabilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
<? } ?></td>
                          <td align="left" valign="middle"><img src="../../imagen/iconos/idioma.png" width="18" height="20" class="Tips1" title="Idioma  ::
								  <?
								  $query_idioma = "SELECT titulo_idioma
								  FROM idioma
								  WHERE ididioma = $rs_lista[ididioma]";
								  $rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));

								  echo "&bull; ".$rs_idioma['titulo_idioma'];

								  ?>" /> <img src="../../imagen/iconos/sede.png" width="14" height="20"  class="Tips1" title="Sucursal  ::
								  <?
								  $query_sede = "SELECT titulo
								  FROM sede
								  WHERE idsede = $rs_lista[idsede]";
								  $rs_sede = mysql_fetch_assoc(mysql_query($query_sede));

								  echo "&bull; ".$rs_sede['titulo'];

								  ?>"  /> <img src="../../imagen/iconos/segmento.png" width="21" height="20"  class="Tips1" title="Segmentaciones  ::
								  <?
								  $query_sede = "SELECT B.titulo
								  FROM user_web_segmentacion A
								  INNER JOIN user_segmentacion B ON A.iduser_segmentacion = B.iduser_segmentacion
								  WHERE A.iduser_web = $rs_lista[iduser_web]";
								  $result = mysql_query($query_sede);
								  while($rs_sede = mysql_fetch_assoc($result)){
									  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }

								  ?>"  /> <a href="usuario_web_editar.php?iduser_web=<?= $rs_lista['iduser_web'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a> &nbsp;<a href="javascript:confirmar_eliminar(<?=$rs_lista['iduser_web']?>,'<?=$rs_lista['apellido'].", ".$rs_lista['nombre']?>');" class="style10"><img src="../../imagen/cruz.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                        </tr>
                        <?
		if($cont_colores == 0){
			$cont_colores = 1;
		}else{
			$cont_colores = 0;
		};
					
	} if($hay_lista == false){ ?>
                        <tr align="center" valign="middle" >
                          <td colspan="5" bgcolor="#fff0e1" height="50" class="detalle_medio_bold">No se han encontrado usuarios.</td>
                        </tr>
						 <? };	?>
                        <tr align="center" valign="middle">
                          <td colspan="5" class="titulo_medio_bold"><? echo paginar($pag, $cantidad_total, $cantidad_registros, $_SERVER['PHP_SELF']."?cant=".$cantidad_registros."&pag=" ); ?></td>
                        </tr>
                        <tr align="center" valign="middle" height="50">
                          <td colspan="5" bgcolor="#FFFFFF" class="detalle_medio">Cantidad total de usuarios:
                            <?= $cantidad_total ?></td>
                        </tr>
                    </table>
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