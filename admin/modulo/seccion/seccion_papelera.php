<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtrado_sede = " AND C.idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
		$filtrado_sede = '';
	}
	
	//VARIABLES
	$eliminar = $_GET['eliminar'];
	$restaurar = $_GET['restaurar'];

	$ruta_foto_chica = "../../../imagen/seccion/chica/"; // la carpeta dentro de la carpeta "ruta_foto" donde se va a guardar la imagen mini
	$ruta_foto_mediana = "../../../imagen/seccion/mediana/"; // la ruta donde se va a guardar la foto	
	$ruta_foto_grande = "../../../imagen/seccion/grande/";
		
	$ruta_foto_extra_grande = "../../../imagen/seccion/extra_grande/"; // la ruta donde se va a guardar la foto
	$ruta_foto_extra_chica = "../../../imagen/seccion/extra_chica/"; // la carpeta dentro de la carpeta "ruta_foto" donde se va a guardar la imagen mini


	//Se borran los registros
	if($eliminar > 0){
		$arr_elminar = split( "-", $eliminar);
		for($i=0; $i<count($arr_elminar); $i++){
			
			$query_preliminar = "SELECT * FROM seccion WHERE idseccion = '$arr_elminar[$i]' ";
			$rs_preliminar = mysql_fetch_assoc(mysql_query($query_preliminar));
			
			//se borra la foto y la foto mini	
			if ( $rs_preliminar["foto"] ){
				if (file_exists($ruta_foto_chica.$rs_preliminar["foto"])){
					unlink ($ruta_foto_chica.$rs_preliminar["foto"]);
				}
				if (file_exists($ruta_foto_mediana.$rs_preliminar["foto"])){
					unlink ($ruta_foto_mediana.$rs_preliminar["foto"]);
				}
				if (file_exists($ruta_foto_grande.$rs_preliminar["foto"])){
					unlink ($ruta_foto_grande.$rs_preliminar["foto"]);
				}
			}
			
			//se borran las fotos_extra
			$query_eliminar_fotos = "SELECT foto FROM seccion_foto WHERE idseccion = '$arr_elminar[$i]' ";
			$result_eliminar_fotos = mysql_query($query_eliminar_fotos);
			while($rs_eliminar_fotos = mysql_fetch_assoc($result_eliminar_fotos)){
			
				if ( $rs_eliminar_fotos["foto"] ){
					if (file_exists($ruta_foto_extra_grande.$rs_eliminar_fotos["foto"])){
						unlink ($ruta_foto_extra_grande.$rs_eliminar_fotos["foto"]);
					}
					if (file_exists($ruta_foto_extra_chica.$rs_eliminar_fotos["foto"])){
						unlink ($ruta_foto_extra_chica.$rs_eliminar_fotos["foto"]);
					}
				}
				
			};
			
			//se borran los registros de las fotos extra
			$query_eliminar_fotos = "DELETE FROM seccion_foto WHERE idseccion = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar_fotos);
			
			//Se borra de las carpetas a las qe pertenece
			$query_eliminar = "DELETE FROM seccion_carpeta WHERE idseccion = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar);
			
			//Se borra la informacion de la seccion
			$query_eliminar = "DELETE FROM seccion_idioma_dato WHERE idseccion = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar);
			
			//Se eliminan las sedes que tiene asociados
			$query_eliminar = "DELETE FROM seccion_sede WHERE idseccion = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar);
			
			//por ultimo se borran los registros de la seccion
			$query_eliminar = "DELETE FROM seccion WHERE idseccion = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar);
			
			//redireccionar:
			echo "<script>window.location.href=('$PHP_SELF');</script>";	
			
		}; // fin for del array eliminar
	}; 
	//fin borrar

	//restaurar registros
	if($restaurar > 0){
		$arr_restaurar = split( "-", $restaurar);
		$cant_restaurar = 0;
		for($i=0; $i<count($arr_restaurar); $i++){
		
			//se obtienen datos de la seccion actual
			$query_secc = "SELECT B.titulo, C.idcarpeta
			FROM seccion A
			INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
			INNER JOIN seccion_carpeta C ON A.idseccion = C.idseccion
			WHERE A.idseccion = '$arr_restaurar[$i]' AND B.ididioma = 1";
			$rs_secc = mysql_fetch_assoc(mysql_query($query_secc));
		
			$query_padre = "SELECT B.nombre, A.estado 
			FROM carpeta A
			LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
			WHERE A.idcarpeta = $rs_secc[idcarpeta] ";
			$rs_padre = mysql_fetch_assoc(mysql_query($query_padre));
			$cant_padre = mysql_num_rows(mysql_query($query_padre));
			
			if($cant_padre == 0){
				//si la categoria padre fue borrada definitivamente:
				echo "<script>alert('No se pudo restaurar la sección \"$rs_secc[titulo]\" ya que la carpeta a la que pertenece fue eliminada.\\nPara restaurar la sección editela y asignela a una carpeta existente.');</script>";	
			}else{
	
				if($rs_padre['estado'] == 3){
					echo "<script>alert('No se pudo restaurar la sección \"$rs_secc[titulo]\" ya que la carpeta \"$rs_padre[nombre]\" a la que pertenece se encuentra en la papelera.\\nPara restaurar la sección editela y asignela a una carpeta existente, o bien restaure la carpeta de la papelera.');</script>";	
				}else{
					$query_restaurar = "UPDATE seccion SET estado = '2' WHERE idseccion = '$arr_restaurar[$i]' ";
					mysql_query($query_restaurar);
					$cant_restaurar++;
				};
	
			};
		}
		
		if($cant_restaurar > 1){
			echo "<script>alert('Se han restaurado las secciones. \\nLas mismas se encuentran desactivadas por defecto.\\nPuede activarlas desde el menú \"Secciones -> Ver (árbol)\" ')</script>";
		};
		
		if($cant_restaurar == 1){
			echo "<script>alert('Se ha restaurado la sección. \\nLa misma se encuentra desactivada por defecto.\\nPuede activarla desde el menú \"Secciones -> Ver (árbol)\" ')</script>";
		};
		
		echo "<script>window.location.href=('$PHP_SELF');</script>";	
	
	}
	//fin restaurar
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

</head>
<body>
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Secci&oacute;n - Papelera </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
              <script language="JavaScript" type="text/javascript">
function confirm_eliminar(id){
	if (confirm('¿Está seguro que desea borrar la seccion?')){
		window.location.href=('<?=$PHP_SELF?>?eliminar='+id);
	}
}
function seleccionar_todo(valor){
	formulario = window.form_lista.document;
	for(i=0; i<formulario.getElementById("cantidad").value; i++){
		check_actual = formulario.getElementById("checkbox["+i+"]");
		if (valor.checked == true){
			check_actual.checked = true;
		}else{
			check_actual.checked = false;
		}
	}
}
function restaurar_seleccion(){
	formulario = window.form_lista.document;
	arrSeleccion = "";
	hay = false;
	for(i=0; i<formulario.getElementById("cantidad").value; i++){
		check_actual = formulario.getElementById("checkbox["+i+"]");
		if (check_actual.checked == true){
			if(hay==false){
				arrSeleccion = check_actual.value;
				hay = true;
			}else{
				arrSeleccion = arrSeleccion+"-"+check_actual.value;
			};
		}
	}
	if(hay==true){
		if (confirm('¿Está seguro que desea restaurar las secciones seleccionadas?')){
			window.location.href=('<?=$PHP_SELF?>?restaurar='+arrSeleccion);
		}
	}else{
		alert("Debe seleccionar una o mas secciones.");
	};
};
function eliminar_seleccion(){
	formulario = window.form_lista.document;
	arrSeleccion = "";
	hay = false;
	for(i=0; i<formulario.getElementById("cantidad").value; i++){
		check_actual = formulario.getElementById("checkbox["+i+"]");
		if (check_actual.checked == true){
			if(hay==false){
				arrSeleccion = check_actual.value;
				hay = true;
			}else{
				arrSeleccion = arrSeleccion+"-"+check_actual.value;
			};
		}
	}
	if(hay==true){
		if (confirm('¿Está seguro que desea borrar las secciones seleccionadas?')){
			window.location.href=('<?=$PHP_SELF?>?eliminar='+arrSeleccion);
		}
	}else{
		alert("Debe seleccionar una o mas secciones.");
	};
}
            </script>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_lista" id="form_lista">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#ffddbc">
                          <td width="1%" height="40" align="center" class="detalle_medio_bold"><input name="selectall" type="checkbox" id="selectall" value="selectall" onclick="seleccionar_todo(this);" /></td>
                          <td width="1%" height="40" align="center" class="detalle_medio_bold">ID</td>
                          <td height="40" bgcolor="#ffddbc" class="detalle_medio_bold">Titulo</td>
                          <td height="40" align="center" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                          <td height="40" colspan="2" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
                        <?
	$colores = array("#fff0e1","#FFE1C4");
	$cont_colores = 0;
	$cont_secc = 0;
	$hay_lista = false;
	$query_lista = "SELECT DISTINCT s.*, si.titulo
	FROM seccion s
	INNER JOIN seccion_idioma_dato si ON s.idseccion = si.idseccion
	INNER JOIN seccion_sede C ON s.idseccion = C.idseccion
	WHERE s.estado = 3 AND si.ididioma = 1 $filtrado_sede
	ORDER BY idseccion DESC";
	$result_lista = mysql_query($query_lista);
	while ($rs_lista = mysql_fetch_assoc($result_lista))
	{ $hay_lista = true;
?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="1%" height="25" align="center"><input type="checkbox" name="checkbox[<?=$cont_secc?>]" value="<?= $rs_lista['idseccion']; ?>" onclick="window.form_lista.document.getElementById('selectall').checked = false;" /></td>
                          <td width="1%" height="25" align="center"><a name="<?= $rs_lista['idseccion']; ?>" id="<?= $rs_lista['idseccion']; ?>"></a>
                              <?=$rs_lista['idseccion']?></td>
                          <td height="25" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['titulo']?></td>
                          <td width="9%" height="25" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="seccion_papelera.php?restaurar=<?=$rs_lista['idseccion']?>"><img src="../../imagen/b_restaurar.png" alt="Restaurar" width="21" height="19" border="0" /></a></td>
                          <td width="5%" height="25" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="seccion_editar.php?idseccion=<?= $rs_lista['idseccion'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                          <td width="5%" height="25" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:confirm_eliminar(<?=$rs_lista['idseccion']?>)" class="style10"><img src="../../imagen/cruz.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                        </tr>
                        <?
		if($cont_colores == 0){
			$cont_colores = 1;
		}else{
			$cont_colores = 0;
		};
		
		$cont_secc++;
						
	}
	
				if($hay_lista == false){ ?>
                        <tr align="center" valign="middle" >
                          <td height="50" colspan="6" bgcolor="#fff0e1" class="detalle_medio_bold">No hay secciones en la papelera. </td>
                        </tr>
                        <? }; ?>
                        <tr align="center" valign="middle" height="50">
                          <td height="8" colspan="6" align="center" class="detalle_medio" style="color:#FF6600"></td>
                        </tr>
                        <tr align="center" valign="middle" height="50">
                          <td height="40" colspan="6" align="center" bgcolor="#FFF0E1" class="detalle_medio" style="color:#FF6600"><span class="detalle_medio" style="color:#FF6600"><a href="javascript:restaurar_seleccion();" style="color:#FF6600">Restablecer Seleccionados</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="javascript:eliminar_seleccion();" style="color:#FF6600">Eliminar Seleccionados </a></span></td>
                        </tr>
                        <?
	////////////////////////////////////////////////
	////Final del Modulo Admin de lista filtrado////
	////////////////////////////////////////////////
?>
                    </table>
                    <input name="cantidad" type="hidden" id="cantidad" value="<?=$cont_secc?>" />
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