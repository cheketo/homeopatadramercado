<? 	
	//INCLUDES
	include ("../../0_mysql.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	//VARIABLES		
	$accion = $_POST['accion'];
	$titulo = $_POST['titulo'];
	$tipo = $_POST['tipo'];
	
	//INGRESAR
	if( $accion == "ingresar" ){
	
		$query_ingresar = "INSERT INTO user_segmentacion (
		  titulo
		, tipo
		) VALUES (
		  '$titulo'
		, '$tipo'
		)";
		mysql_query($query_ingresar);
	
	};
			   
	//BORRAR
	$eliminar = $_POST['eliminar'];
	
	if($eliminar){	
	
		$query_eliminar = "DELETE 
		FROM user_segmentacion 
		WHERE iduser_segmentacion = '$eliminar'";
		mysql_query($query_eliminar);
		
		$query_eliminar = "DELETE 
		FROM user_web_segmentacion 
		WHERE iduser_segmentacion = '$eliminar'";
		mysql_query($query_eliminar);
		
	};
	
	//CAMBIAR ESTADO
	$iduser_segmentacion = $_POST['iduser_segmentacion'];
	$estado = $_POST['estado'];
	
	if($estado != "" && $iduser_segmentacion != ""){
		$query_estado = "UPDATE user_segmentacion
		SET estado = '$estado'
		WHERE iduser_segmentacion	= '$iduser_segmentacion'
		LIMIT 1";
		mysql_query($query_estado);
	
	}
	
?>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_form_preguntas(){
	formulario = document.form_titular;
	
		if(formulario.titulo.value == ''){
			alert("Debe ingresar el titulo de la segmentación.");
		}else{
			formulario.accion.value = "ingresar";
			formulario.submit();
		}

	};
	
	function cambiar_estado(estado, id){
		formulario = document.form_titular;
		
		formulario.estado.value = estado;
		formulario.iduser_segmentacion.value = id;
		formulario.submit();
	};
	
	function confirmar_eliminar(id){
	formulario = document.form_titular;
		if (confirm('¿Esta seguro que desea eliminar la segmentación?')){
			formulario.eliminar.value = id;
			formulario.submit();
		}
	};

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
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Web Segmentaciones</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
				   <form action="" method="post" name="form_titular" id="form_titular">
				     <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                       <tr bgcolor="#999999">
                         <td height="40" bgcolor="#FFD3A8" class="titulo_medio_bold">Ingresar nueva Segmentaci&oacute;n:
                           <input name="accion" type="hidden" id="accion" value="0" />
                           <input name="estado" type="hidden" id="estado" value="" />
                           <input name="iduser_segmentacion" type="hidden" id="iduser_segmentacion" />
                           <input name="eliminar" type="hidden" id="eliminar" /></td>
                       </tr>
                       <tr bgcolor="#999999">
                         <td bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                             <tr>
                               <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                             </tr>
                             <tr>
                               <td width="15%" align="right" valign="middle" class="detalle_medio">Titulo:</td>
                               <td width="85%" align="left" valign="top"><label><input name="titulo" type="text" class="detalle_medio" id="titulo" style="width:98%" /></label></td>
                             </tr>
                             <tr>
                               <td width="15%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                               <td align="left" valign="top" class="detalle_medio">
							   <? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?><label>
                                 <input name="tipo" type="radio" value="2" />
                                Interna web. <br />
                                  <? } ?>
                               <input name="tipo" type="radio" value="1" checked="checked" /> 
                               Visible para usuarios web.
<br />
                               <input name="tipo" type="radio" value="3" />
                               De origen. 
							   <br />
							   <input name="tipo" type="radio" value="4" />
Especiales. </label></td>
                             </tr>
                             <tr>
                               <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                               <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form_preguntas();" value=" Ingresar &raquo; " /></td>
                             </tr>
                         </table></td>
                       </tr>
                     </table>
				     <br />
				     <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                       <tr bgcolor="#FFB76F">
                         <td height="35" colspan="8" align="left" bgcolor="#FFD3A8" class="detalle_medio_bold">Segmentaciones internas web.</td>
                       </tr>
                       <? 
 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM user_segmentacion 
		WHERE estado <> 3 AND tipo = 2
		ORDER BY titulo";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			
			$hay_lista = true;			  
			  
	  
?>
                       <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                         <td width="5%" align="left" class="detalle_chico"><a name="<?= $rs_lista['iduser_admin_perfil']; ?>" id="<?= $rs_lista['iduser_admin_perfil']; ?>"></a>
                             <?=$rs_lista['iduser_segmentacion']?>. </td>
                         <td width="45%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                           <?= $rs_lista['titulo']; ?>
                         </div></td>
                         <td width="38%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_11px">Usuarios: <b><?
						 
						 	$query_lista = "SELECT *
							FROM user_web_segmentacion 
							WHERE iduser_segmentacion = '$rs_lista[iduser_segmentacion]'";
							echo $num_result = mysql_num_rows(mysql_query($query_lista));
						 
						  ?></b></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                             <a href="javascript:cambiar_estado(2,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                             <? } else { ?>
                             <a href="javascript:cambiar_estado(1,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                             <? } ?></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?> <a href="usuario_web_segmentacion_editar.php?iduser_segmentacion=<?= $rs_lista['iduser_segmentacion'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /> </a><? } ?></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if($rs_lista['seguro'] == 2){ ?>
                             <a href="javascript:confirmar_eliminar('<?= $rs_lista['iduser_segmentacion'] ?>')" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a>
                             <? } ?></td>
                       </tr>
                       <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                       <tr align="center" valign="middle">
                         <td colspan="8" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado segmentaciones.</td>
                       </tr>
                       <? };
	?>
                     </table>
				     <br />
				     <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                       <tr bgcolor="#FFB76F">
                         <td height="40" colspan="8" align="left" bgcolor="#FFD3A8" class="detalle_medio_bold">Segmentaciones visibles para usuarios web. </td>
                       </tr>
                       <tr bgcolor="#FFB76F">
                         <td height="30" colspan="8" align="left" bgcolor="#FFDDBB" class="detalle_11px"><u>Atenci&oacute;n:</u> Es la segmentaci&oacute;n que aparecer&aacute; en el contacto, donde el usuario seleccionar&aacute; sus &aacute;reas de interes.</td>
                       </tr>
                       
                       <? 
 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM user_segmentacion 
		WHERE estado <> 3 AND tipo = 1 
		ORDER BY titulo";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			
			$hay_lista = true;			  
			  
	  
?>
                       <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                         <td width="4%" align="left" class="detalle_chico"><a name="<?= $rs_lista['iduser_admin_perfil']; ?>" id="<?= $rs_lista['iduser_admin_perfil']; ?>"></a>
                             <?=$rs_lista['iduser_segmentacion']?>. </td>
                         <td width="46%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                           <?= $rs_lista['titulo']; ?>
                         </div>
                         </td>
                         <td width="38%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><span class="detalle_11px">Usuarios: <b>
                           <?
						 
						 	$query_lista = "SELECT *
							FROM user_web_segmentacion 
							WHERE iduser_segmentacion = '$rs_lista[iduser_segmentacion]'";
							echo $num_result = mysql_num_rows(mysql_query($query_lista));
						 
						  ?>
                         </b></span></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                             <a href="javascript:cambiar_estado(2,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                             <? } else { ?>
                             <a href="javascript:cambiar_estado(1,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                             <? } ?></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="usuario_web_segmentacion_editar.php?iduser_segmentacion=<?= $rs_lista['iduser_segmentacion'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if($rs_lista['seguro'] == 2){ ?>
                             <a href="javascript:confirmar_eliminar('<?= $rs_lista['iduser_segmentacion'] ?>')" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a>
                             <? } ?></td>
                       </tr>
                       <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                       <tr align="center" valign="middle">
                         <td colspan="8" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado segmentaciones.</td>
                       </tr>
                       <? };
	?>
                     </table>
				     <br />
				     <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                       <tr bgcolor="#FFB76F">
                         <td height="40" colspan="8" align="left" bgcolor="#FFD3A8" class="detalle_medio_bold">Segmentaciones de Origen. </td>
                       </tr>
                       <tr bgcolor="#FFB76F">
                         <td height="30" colspan="8" align="left" bgcolor="#FFDDBB" class="detalle_11px"><u>Atenci&oacute;n:</u> En esta segmentaci&oacute;n se especificar&aacute; el origen de los usuarios.</td>
                       </tr>
                       <? 
 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM user_segmentacion 
		WHERE estado <> 3 AND tipo = 3
		ORDER BY titulo";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			
			$hay_lista = true;			  
			  
	  
?>
                       <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                         <td width="4%" align="left" class="detalle_chico"><a name="<?= $rs_lista['iduser_admin_perfil']; ?>" id="<?= $rs_lista['iduser_admin_perfil']; ?>"></a>
                             <?=$rs_lista['iduser_segmentacion']?>. </td>
                         <td width="46%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                           <?= $rs_lista['titulo']; ?>
                         </div>                         </td>
                         <td width="38%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><span class="detalle_11px">Usuarios: <b>
                           <?
						 
						 	$query_lista = "SELECT *
							FROM user_web_segmentacion 
							WHERE iduser_segmentacion = '$rs_lista[iduser_segmentacion]'";
							echo $num_result = mysql_num_rows(mysql_query($query_lista));
						 
						  ?>
                         </b></span></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                             <a href="javascript:cambiar_estado(2,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                             <? } else { ?>
                             <a href="javascript:cambiar_estado(1,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                             <? } ?></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="usuario_web_segmentacion_editar.php?iduser_segmentacion=<?= $rs_lista['iduser_segmentacion'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if($rs_lista['seguro'] == 2){ ?>
                             <a href="javascript:confirmar_eliminar('<?= $rs_lista['iduser_segmentacion'] ?>')" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a>
                             <? } ?></td>
                       </tr>
                       <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                       <tr align="center" valign="middle">
                         <td colspan="8" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado segmentaciones.</td>
                       </tr>
                       <? };
	?>
                     </table>
				     <br />
				     <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                       <tr bgcolor="#FFB76F">
                         <td height="40" colspan="8" align="left" bgcolor="#FFD3A8" class="detalle_medio_bold">Segmentaciones Especiales. </td>
                       </tr>
                       <tr bgcolor="#FFB76F">
                         <td height="30" colspan="8" align="left" bgcolor="#FFDDBB" class="detalle_11px"><u>Atenci&oacute;n:</u> Son aquellas segmentaciones para uso interno de la empresa que no ser&aacute;n visibles para el usuario web.</td>
                       </tr>
                       <? 
 	
		$colores = array("#fff0e1","#FFE1C4");
		$cont_colores = 0;
		$hay_lista = false;
		$query_lista = "SELECT *
		FROM user_segmentacion 
		WHERE estado <> 3 AND tipo = 4
		ORDER BY titulo";
		$result_lista = mysql_query($query_lista);
		while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
			
			$hay_lista = true;			  
			  
	  
?>
                       <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                         <td width="4%" align="left" class="detalle_chico"><a name="<?= $rs_lista['iduser_admin_perfil']; ?>" id="<?= $rs_lista['iduser_admin_perfil']; ?>"></a>
                             <?=$rs_lista['iduser_segmentacion']?>
                           . </td>
                         <td width="46%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><div style="width:97%; height:14px; overflow:hidden">
                             <?= $rs_lista['titulo']; ?>
                         </div></td>
                         <td width="38%" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><span class="detalle_11px">Usuarios: <b>
                           <?
						 
						 	$query_lista = "SELECT *
							FROM user_web_segmentacion 
							WHERE iduser_segmentacion = '$rs_lista[iduser_segmentacion]'";
							echo $num_result = mysql_num_rows(mysql_query($query_lista));
						 
						  ?>
                         </b></span></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if ($rs_lista['estado'] == '1') { 
						//estado 1 activo, 2 inactivo, 3 borrado
						  ?>
                             <a href="javascript:cambiar_estado(2,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                             <? } else { ?>
                             <a href="javascript:cambiar_estado(1,<?= $rs_lista['iduser_segmentacion'] ?>);"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                             <? } ?></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="usuario_web_segmentacion_editar.php?iduser_segmentacion=<?= $rs_lista['iduser_segmentacion'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                         <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><? if($rs_lista['seguro'] == 2){ ?>
                             <a href="javascript:confirmar_eliminar('<?= $rs_lista['iduser_segmentacion'] ?>')" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a>
                             <? } ?></td>
                       </tr>
                       <?
	if($cont_colores == 0){
		$cont_colores = 1;
	}else{
		$cont_colores = 0;
	};
					
	} if($hay_lista == false){ ?>
                       <tr align="center" valign="middle">
                         <td colspan="8" bgcolor="fff0e1" height="50" class="detalle_medio_bold">No se han encontrado segmentaciones.</td>
                       </tr>
                       <? };
	?>
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