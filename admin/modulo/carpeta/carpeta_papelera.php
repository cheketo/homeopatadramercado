<? include ("../../0_mysql.php");  ?>
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
	}
	

	//VARIABLES
	$eliminar = $_GET['eliminar'];
	$restaurar = $_GET['restaurar'];
	
	$ruta_descarga = "../../../descarga/";
	$foto_ruta_chica = "../../../imagen/carpeta/chica/"; // la ruta donde se va a guardar la foto chica
	$foto_ruta_mediana = "../../../imagen/carpeta/mediana/"; // la ruta donde se va a guardar la foto mediana

	//Se borran los registros
	if($eliminar != ""){
		$arr_elminar = split("-", $eliminar);
		for($i=0; $i<count($arr_elminar); $i++){
			
			$query_preliminar = "SELECT foto 
			FROM carpeta 
			WHERE idcarpeta = '$arr_elminar[$i]' ";
			$rs_preliminar = mysql_fetch_assoc(mysql_query($query_preliminar));
			
			//se borra la foto	
			if($rs_preliminar["foto"]){
				if(file_exists($foto_ruta_chica.$rs_preliminar["foto"])){
					unlink ($foto_ruta_chica.$rs_preliminar["foto"]);
				}
				if(file_exists($foto_ruta_mediana.$rs_preliminar["foto"])){
					unlink ($foto_ruta_mediana.$rs_preliminar["foto"]);
				}
			}
			
			//por ultimo se borran los registros de la categoria
			$query_eliminar = "DELETE FROM carpeta WHERE idcarpeta = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar);
			
			//por ultimo se borran los registros de la categoria
			$query_eliminar = "DELETE FROM carpeta_ididioma_dato WHERE idcarpeta = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar);
			
			//por ultimo se borran los registros de la categoria
			$query_eliminar = "DELETE FROM carpeta_sede WHERE idcarpeta = '$arr_elminar[$i]' ";
			mysql_query($query_eliminar);
			
			//eliminar descargas
			$query_desc = "SELECT archivo FROM descarga WHERE idcarpeta = '$arr_elminar[$i]' ";
			$result_desc = mysql_query($query_desc);
			
			while($rs_desc = mysql_fetch_assoc($result_desc)){
				if(file_exists($ruta_descarga.$rs_desc["archivo"])){
					unlink ($ruta_descarga.$rs_desc["archivo"]);
				}
			}
			
			//redireccionar:
			echo "<script>document.location.href='$PHP_SELF';</script>";
			
		}; // fin for del array eliminar
	}; 
	//fin borrar

	//restaurar registros
	if($restaurar > 0){
		$arr_restaurar = split( "-", $restaurar);
		$cant_restaurar = 0;
		
		for($i=0; $i<count($arr_restaurar); $i++){
			
			//se restaura el registro
			$query_restaurar = "UPDATE carpeta SET estado = '2' WHERE idcarpeta = '$arr_restaurar[$i]' ";
			mysql_query($query_restaurar);
			$cant_restaurar++;
		}
		
		if($cant_restaurar > 1){
			echo "<script>alert('Se han restaurado las carpetas. \\nLas mismas se encuentran desactivadas por defecto.\\nPuede activarlas desde el menú \"Carpetas -> Ver (árbol)\" ')</script>";
		};
		
		if($cant_restaurar == 1){
			echo "<script>alert('Se ha restaurado la carpeta. \\nLa misma se encuentra desactivada por defecto.\\nPuede activarla desde el menú \"Carpetas -> Ver (árbol)\" ')</script>";
		};
		
		echo "<script>document.location.href='$PHP_SELF';</script>";	
	
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Carpeta - Papelera </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
              <script language="JavaScript" type="text/javascript">
function confirm_eliminar(id){
	if (confirm('¿Está seguro que desea borrar el registro?')){
		document.location.href= '<?=$PHP_SELF?>?eliminar='+id;
	}
}
function seleccionar_todo(valor){
	formulario = document.form_lista;
	for(i=0; i<document.getElementById("cantidad").value; i++){
		check_actual = document.getElementById("checkbox["+i+"]");
		if (valor.checked == true){
			check_actual.checked = true;
		}else{
			check_actual.checked = false;
		}
	}
}
function restaurar_seleccion(){
	formulario = document.form_lista;
	arrSeleccion = "";
	hay = false;
	for(i=0; i<document.getElementById("cantidad").value; i++){
		check_actual = document.getElementById("checkbox["+i+"]");
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
		if (confirm('¿Está seguro que desea restaurar los registros seleccionados?')){
			document.location.href='<?=$PHP_SELF?>?restaurar='+arrSeleccion;
		}
	}else{
		alert("Debe seleccionar uno o mas registros.");
	};
};
function eliminar_seleccion(){
	formulario = document.form_lista;
	arrSeleccion = "";
	hay = false;
	for(i=0; i<document.getElementById("cantidad").value; i++){
		check_actual = document.getElementById("checkbox["+i+"]");
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
		if (confirm('¿Está seguro que desea borrar los registros seleccionados?')){
			document.location.href='<?=$PHP_SELF?>?eliminar='+arrSeleccion;
		}
	}else{
		alert("Debe seleccionar uno o mas registros.");
	};
}
            </script>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_lista" id="form_lista">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="ffddbc">
                          <td width="1%" height="40" align="center" bgcolor="#ffddbc" class="detalle_medio_bold"><input name="selectall" type="checkbox" id="selectall" value="selectall" onclick="seleccionar_todo(this);" /></td>
                          <td width="1%" height="40" align="center" bgcolor="#ffddbc" class="detalle_medio_bold">ID</td>
                          <td height="40" bgcolor="#ffddbc" class="detalle_medio_bold">Titulo</td>
                          <td height="40" align="center" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                          <td height="40" colspan="2" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
                        <?
	$colores = array("#fff0e1","#FFE1C4");
	$cont_colores = 0;
	$cont_cat = 0;
	$hay_lista = false;
	$query_lista = "SELECT DISTINCT A.idcarpeta, B.nombre
	FROM carpeta A
	LEFT JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	LEFT JOIN carpeta_sede C ON A.idcarpeta = C.idcarpeta
	WHERE A.estado = 3 AND B.ididioma = '1' $filtrado_sede
	ORDER BY A.idcarpeta";
	
	$result_lista = mysql_query($query_lista);
	while ($rs_lista = mysql_fetch_assoc($result_lista))
	{ $hay_lista = true;
?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="1%" align="center"><input type="checkbox" name="checkbox[<?=$cont_cat?>]" value="<?= $rs_lista['idcarpeta']; ?>" onclick="window.form_lista.document.getElementById('selectall').checked = false;" /></td>
                          <td width="1%" align="center" class="detalle_chico"><a name="<?= $rs_lista['idcarpeta']; ?>" id="<?= $rs_lista['idcarpeta']; ?>"></a>
                              <?=$rs_lista['idcarpeta']?></td>
                          <td bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['nombre']?></td>
                          <td width="9%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="carpeta_papelera.php?restaurar=<?=$rs_lista['idcarpeta']?>"><img src="../../imagen/b_restaurar.png" alt="Restaurar" width="21" height="19" border="0" /></a></td>
                          <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="carpeta_editar.php?idcarpeta=<?= $rs_lista['idcarpeta'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a></td>
                          <td width="5%" align="center" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:confirm_eliminar(<?=$rs_lista['idcarpeta']?>)" class="style10"><img src="../../imagen/b_drop.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                        </tr>
                        <?
		if($cont_colores == 0){
			$cont_colores = 1;
		}else{
			$cont_colores = 0;
		};
		
		$cont_cat++;
						
	}
	
				if($hay_lista == false){ ?>
                        <tr align="center" valign="middle" >
                          <td colspan="6" bgcolor="#fff0e1" height="40" class="titulo_medio_bold">No hay carpetas en la papelera. </td>
                        </tr>
                        <? }; ?>
                        <tr align="center" valign="middle" >
                          <td colspan="6" align="center" class="detalle_medio" style="color:#FF6600" height="20"></td>
                        </tr>
                        <tr align="center" valign="middle" >
                          <td height="40" colspan="6" align="center" bgcolor="#FFF0E1" class="detalle_medio" style="color:#FF6600"><a href="javascript:restaurar_seleccion();" style="color:#FF6600">Restablecer Seleccionados</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="javascript:eliminar_seleccion();" style="color:#FF6600">Eliminar Seleccionados </a></td>
                        </tr>
                        <?
	////////////////////////////////////////////////
	////Final del Modulo Admin de lista filtrado////
	////////////////////////////////////////////////
?>
                    </table>
                    <input name="cantidad" type="hidden" id="cantidad" value="<?=$cont_cat?>" />
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