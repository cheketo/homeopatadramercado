<? 	
	//INCLUDES
	include ("../../0_mysql.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 

	//VARIABLES		
	$accion = $_POST['accion'];
			   
	//BORRAR
	$eliminar = $_POST['eliminar'];
	
	if($accion == "eliminar" && $eliminar){	
		
		$query_eliminar = "DELETE 
		FROM encuesta 
		WHERE idencuesta = '$eliminar'";
		mysql_query($query_eliminar);
		
		$query_eliminar = "DELETE 
		FROM encuesta_opcion 
		WHERE idencuesta = '$eliminar'";
		mysql_query($query_eliminar);
		
		$query_eliminar = "DELETE 
		FROM encuesta_sede 
		WHERE idencuesta = '$eliminar'";
		mysql_query($query_eliminar);
		
		$query_eliminar = "DELETE 
		FROM encuesta_ip 
		WHERE idencuesta = '$eliminar'";
		mysql_query($query_eliminar);
		
	};
	
	//CAMBIAR ESTADO
	$idencuesta = $_POST['idencuesta'];
	$estado = $_POST['estado'];
	
	if($accion == "cambiar_estado" && $estado != "" && $idencuesta != ""){
		
		$query_estado = "UPDATE encuesta
		SET estado = '$estado'
		WHERE idencuesta = '$idencuesta'
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
	
	function cambiar_estado(estado, id){
		formulario = document.form_titular;
		
		formulario.estado.value = estado;
		formulario.idencuesta.value = id;
		formulario.accion.value = "cambiar_estado";
		formulario.submit();
	};
	
	function confirmar_eliminar(id){
	formulario = document.form_titular;
		if (confirm('¿Esta seguro que desea eliminar la encuesta?\nTenga en cuenta que se perderan los datos.')){
			formulario.eliminar.value = id;
			formulario.accion.value = "eliminar";
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Encuestas</td>
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
                      <tr bgcolor="#FFB76F">
                        <td width="4%" height="40" align="center" bgcolor="#FFD3A8" class="detalle_medio_bold">ID</td>
                        <td width="80%" height="40" bgcolor="#FFD3A8" class="detalle_medio_bold">Encuesta                        
                          <input name="estado" type="hidden" id="estado" />
                          <input name="eliminar" type="hidden" id="eliminar" />
                          <input name="idencuesta" type="hidden" id="idencuesta" />
                          <input name="accion" type="hidden" id="accion" /></td>
                        <td height="40" colspan="5" align="center" bgcolor="#FFD3A8" class="detalle_medio_bold">&nbsp;</td>
                      </tr>
                      <? 
 	
		$colores = array("#FFE1C4","#fff0e1");
		$cont_colores = 0;
		$hay_lista = false;
		$query_encuesta = "SELECT *
		FROM encuesta 
		WHERE estado <> 3
		ORDER BY idencuesta ASC";
		$result_encuesta = mysql_query($query_encuesta);
		while ($rs_encuesta = mysql_fetch_assoc($result_encuesta)){ 
			
			$hay_lista = true;			  
			  
	  
?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td width="4%" align="left" class="detalle_chico"><a name="<?= $rs_encuesta['idencuesta']; ?>" id="<?= $rs_encuesta['idencuesta']; ?>"></a>
                              <?=$rs_encuesta['idencuesta']?>.                        </td>
                        <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?= $rs_encuesta['pregunta']; ?></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>">
							<? if ($rs_encuesta['estado'] == '1') { ?>
                            <a href="javascript:cambiar_estado(2,<?= $rs_encuesta['idencuesta'] ?>);"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>
                            <? } else { ?>
                            <a href="javascript:cambiar_estado(1,<?= $rs_encuesta['idencuesta'] ?>);"><img src="../../imagen/b_habilitado_off.png" alt="Deshabilitado" width="15" height="16" border="0" /></a>
                            <? } ?></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="encuesta_editar.php?idencuesta=<?= $rs_encuesta['idencuesta'] ?>&ididioma=<?= $rs_encuesta['ididioma'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:confirmar_eliminar('<?= $rs_encuesta['idencuesta'] ?>')" class="style10"><img src="../../imagen/cruz.png" width="16" height="16" border="0" /></a></td>
                      </tr>
					  <?
					  	if($cont_colores == 0){
							$cont_colores = 1;
						}else{
							$cont_colores = 0;
						};
					  ?>
                      <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                        <td width="4%" align="left" class="detalle_chico">&nbsp;</td>
                        <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_11px">
							
								
							 
							
							<table width="100%" border="0" cellspacing="0" cellpadding="3">
							<? 
						
							$c=0;
							$query_opcion = "SELECT *
							FROM encuesta_opcion
							WHERE idencuesta = '$rs_encuesta[idencuesta]' ";
							$result_opcion = mysql_query($query_opcion);
							  
							while($rs_opcion = mysql_fetch_assoc($result_opcion)){
								$c++; 
							?>
                              <tr>
                                <td width="6%" class="detalle_11px"><?= $c ?>.</td>
                                <td width="57%" class="detalle_medio"><?= $rs_opcion['opcion']." (Votos: ".$rs_opcion['voto'].")<br>"; ?></td>
                                <td width="30%" class="detalle_medio">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
								  	<?
									
									if($rs_encuesta['voto_total'] > 0){
										$rojo = round( ($rs_opcion['voto'] / $rs_encuesta['voto_total']) * 100);
										$rojo_formato = number_format(($rs_opcion['voto'] / $rs_encuesta['voto_total']) * 100, 2, ',', '.');
										$gris = 100 - $porcentaje;
									}else{
										$gris = 100;
										$rojo = 0;
										$rojo_formato = number_format($rojo, 2, ',', '.');
									}
									
									?>
								  	<? if($rs_encuesta['voto_total'] > 0){ ?>
                                    <td width="<?= $rojo ?>%" bgcolor="#FF0000">&nbsp;</td>
									<? } ?>
                                    <td width="<?= $gris ?>%" bgcolor="#E9E9E9">&nbsp;</td>
                                  </tr>
                                </table></td>
                                <td width="7%" align="right" class="detalle_medio_bold"><?= $rojo_formato ?>%</td>
                              </tr>
							  <? } ?>
                              <tr>
                                <td class="detalle_11px">&nbsp;</td>
                                <td class="detalle_medio">&nbsp;</td>
                                <td class="detalle_medio">Total de votos: 
                                 <strong><?= $rs_encuesta['voto_total'] ?></strong>
                                </td>
                                <td align="right" class="detalle_medio_bold">&nbsp;</td>
                              </tr>
							
                          </table></td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>">&nbsp;</td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>">&nbsp;</td>
                        <td width="4%" align="center" bgcolor="<?=$colores[$cont_colores]?>">&nbsp;</td>
                      </tr>
                      <?
	
						if($cont_colores == 0){
							$cont_colores = 1;
						}else{
							$cont_colores = 0;
						};
						
	} if($hay_lista == false){ ?>
                      <tr align="center" valign="middle">
                        <td colspan="7" bgcolor="#fff0e1" height="50" class="detalle_medio_bold">No se han encontrado encuestas.</td>
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