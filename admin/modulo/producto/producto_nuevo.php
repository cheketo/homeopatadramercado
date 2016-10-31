<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
	}else{
		$obj_value = '';
	}
	
	//VARIABLES:
	$titulo = $_POST['titulo'];
	$accion = $_POST['accion'];
	
	//FILTRADO
	//si biene por get idcarpeta, es para el menu de iconos del home
	if(isset($_GET['idcarpeta'])){	
		$filtro_get_idcarpeta = " AND A.idcarpeta = $_GET[idcarpeta]";
	}else{
		$filtro_get_idcarpeta = " AND A.idcarpeta_padre = 0";
	}
	
	//Sistema de selector de carpeta
	$mod6_idcarpeta = $_POST['mod6_idcarpeta'];
	$mod6_idcarpeta2 = $_POST['mod6_idcarpeta2'];
	$mod6_idcarpeta3 = $_POST['mod6_idcarpeta3'];
	$mod6_idcarpeta4 = $_POST['mod6_idcarpeta4'];
	
	if($mod6_idcarpeta4){
		$mod6_sel_idcarpeta = $mod6_idcarpeta4;
	}else{
		if($mod6_idcarpeta3){
			$mod6_sel_idcarpeta = $mod6_idcarpeta3;
		}else{
			if($mod6_idcarpeta2){
				$mod6_sel_idcarpeta = $mod6_idcarpeta2;
			}else{
				if($mod6_idcarpeta){
					$mod6_sel_idcarpeta = $mod6_idcarpeta;
				}
			}	
		}	
	}

	//CREAR PRODUCTO
	if($accion == "insertar_carpeta"){

		$fecha_alta = date("Y-m-d",time());
		
		//PLANTILLA E IVA
		$query_plantilla = "SELECT plantilla_producto, idca_iva
		FROM carpeta 
		WHERE idcarpeta = '$mod6_sel_idcarpeta' ";
		$rs_plantilla = mysql_fetch_assoc(mysql_query($query_plantilla));
		
		//INGRESO EN LA TABLA PRODUCTO
		$query_ingreso = "INSERT INTO producto (
		  fecha_alta
		, idca_iva			
		) VALUES (
		  '$fecha_alta'
		, '$rs_plantilla[idca_iva]'
		)";
		mysql_query($query_ingreso);
	
		//OBTENGO EL ID DEL PRODUCTO
		$query_max = "SELECT MAX(idproducto) as idproducto FROM producto";
		$rs_max = mysql_fetch_assoc(mysql_query($query_max));
		
		//COPIO A CARPETA
		$query_ingreso = "INSERT INTO producto_carpeta (
		  idcarpeta
		, idproducto
		) VALUES (
		  '$mod6_sel_idcarpeta'
		, '$rs_max[idproducto]'
		)";
		mysql_query($query_ingreso);
		
		//INGRESO DATOS DE LA CARPETA EN IDIOMA
		$query_idioma = "SELECT ididioma, reconocimiento_idioma, valor_defecto
		FROM idioma 
		WHERE estado = '1'
		ORDER BY ididioma";
		$result_idioma = mysql_query($query_idioma);
		while($rs_idioma = mysql_fetch_assoc($result_idioma)){
		
			//PLANTILLA E IVA
			/*$query_plantilla = "SELECT plantilla_producto, idca_iva
			FROM carpeta 
			WHERE idcarpeta = '$mod6_sel_idcarpeta' ";
			$rs_plantilla = mysql_fetch_assoc(mysql_query($query_plantilla));*/
			
			//TITULO IDIOMA
			if($rs_idioma['ididioma']==1){
				$titulo_producto = $titulo;
			}else{
				$titulo_producto = $titulo." (".$rs_idioma['reconocimiento_idioma'].")";
			}
			
			//ingreso en tabla carpeta idioma
			$query_idioma_ingreso = "INSERT INTO producto_idioma_dato (
			  idproducto
			, ididioma
			, titulo
			, detalle
			, estado
			) VALUES (
			  '$rs_max[idproducto]'
			, '$rs_idioma[ididioma]'
			, '$titulo_producto'
			, '$rs_plantilla[plantilla_producto]'
			, '$rs_idioma[valor_defecto]'
			)";
			$res = mysql_query($query_idioma_ingreso);
		
		}
		
		//LO ASIGNO PARA LAS SEDE DETERMINADAS
		$cantidad_sede = $_POST['cantidad_sede'];
		$sede = $_POST['sede'];
		
		for($c=0;$c<$cantidad_sede;$c++){
			$query_insert = "INSERT INTO producto_sede(
			  idproducto
			, idsede
			)VALUES(
			  '$rs_max[idproducto]'
			, '$sede[$c]'
			)";
			mysql_query($query_insert);
		
		}	
		
		// ABRIR VENTANA EDITAR PRODUCTO:
		echo "<script>document.location.href = 'producto_editar.php?idproducto=".$rs_max['idproducto']."';</script>";
	
	};
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="JavaScript" type="text/javascript">

function insertar_carpeta(){
	formulario = document.form;
	if (formulario.mod6_idcarpeta.value == "") {
		alert("Debe seleccionar una carpeta padre.");
	} else if (formulario.titulo.value == ""){
		alert("Debe ingresar el título del nuevo producto.");
	} else {	
		formulario.accion.value = 'insertar_carpeta';
		formulario.submit();
	};
};

</script>
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Producto - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
                <tr>
                  <td><form action="" method="post" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Seleccione un directorio para el producto:<span class="detalle_chico" style="color:#FF0000">
                            <input name="accion" type="hidden" id="accion" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">

<script language="JavaScript" type="text/javascript">
var i;
function cambia(paso){  

    var formulario = document.form; 
	var carpeta;
	var oCntrl;
	
	switch(paso){
		case 1:
			//alert("paso 1");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display  = "none";
			document.getElementById("tr_carpeta4").style.display  = "none";
			document.getElementById("mod6_idcarpeta3").value  = '';
			document.getElementById("mod6_idcarpeta4").value  = '';
			
			carpeta = formulario.mod6_idcarpeta.value;
			oCntrl = formulario.mod6_idcarpeta2;
			break;
			
		case 2:
			//alert("paso 2");
			document.getElementById("tr_carpeta2").style.display = "block";
			document.getElementById("tr_carpeta3").style.display = "block";
			document.getElementById("tr_carpeta4").style.display  = "none";
			document.getElementById("mod6_idcarpeta4").value  = '';
			
			carpeta = formulario.mod6_idcarpeta2.value;
			oCntrl = formulario.mod6_idcarpeta3;
			break;	
			
		case 3:
			//alert("paso 3");
			document.getElementById("tr_carpeta4").style.display = "block";
			document.getElementById("mod6_idcarpeta4").style.display  = "inline";
			
			carpeta = formulario.mod6_idcarpeta3.value;
			oCntrl = formulario.mod6_idcarpeta4;
			break;	
	}   
   
	//alert(carpeta);
	var txtVal = carpeta;
	
	while(oCntrl.length > 0) oCntrl.options[0]=null;  
	i = 0; 
	oCntrl.clear;  
	
	var selOpcion=new Option("--- Seleccionar Carpeta ---", '');  
	eval(oCntrl.options[i++]=selOpcion);  
	 
	<?
	$query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre, A.idcarpeta_padre
	FROM carpeta A
	INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	WHERE A.estado <> 3 AND A.idcarpeta_padre != '0' AND B.ididioma = '1'
	ORDER BY B.nombre";
	$result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
	while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2)){
	?>
	
	if ("<?= $rs_mod6_idcarpeta2['idcarpeta_padre'] ?>" == txtVal){  
		var selOpcion=new Option("<?= $rs_mod6_idcarpeta2['nombre'] ?>", "<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>");  
		eval(oCntrl.options[i++]=selOpcion);  
	}  

	<? } ?> 
}  
   
</script>
							<tr>
                            <td>
							<div id="tr_carpeta">
                              <div id="colum_carpeta">Carpeta 1&ordm;</div>
                              <div id="colum_selector"><span class="style10">
                                <select name="mod6_idcarpeta" class="detalle_medio" style="width:200px;" id="mod6_idcarpeta" onchange="cambia(1)">
                                  <option value="0" selected="selected">--- Seleccionar Carpeta</option>
                                  <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND B.ididioma = '1' $filtro_get_idcarpeta
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
	  {
	  	if ($carpeta1 == $rs_mod6_idcarpeta['idcarpeta'])
		{
			$mod6_sel_idcarpeta = "selected";
		}else{
			$mod6_sel_idcarpeta = "";
		}
?>
                                  <option  <? echo $mod6_sel_idcarpeta ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                                  <?= $rs_mod6_idcarpeta['nombre'] ?>
                                  </option>
                                  <?  } ?>
                                </select>
                              </span></div>
                            </div>
							<div style="clear:left; height:1px;"></div>
						  	<div id="tr_carpeta2" style="display:none">
                              <div id="colum_carpeta">Carpeta 2&ordm;</div>
                              <div id="colum_selector"><select name="mod6_idcarpeta2" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta2" onchange="cambia(2)"></select></div>
                            </div>
							<div style="clear:left; height:1px;"></div>
						    <div id="tr_carpeta3" style="display:none">
                              <div id="colum_carpeta">Carpeta 3&ordm;</div>
                              <div id="colum_selector"><select name="mod6_idcarpeta3" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta3" onchange="cambia(3)"></select></div>
                            </div>
							<div style="clear:left; height:1px;"></div>
						    <div id="tr_carpeta4" style="display:none">
                              <div id="colum_carpeta">Carpeta 4&ordm;</div>
                              <div id="colum_selector"><select name="mod6_idcarpeta4"style="width:200px;" class="detalle_medio" id="mod6_idcarpeta4" onchange=""></select></div>
                            </div>
							<div style="clear:left; height:1px;"></div>							</td></tr>
							</table>
                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                              <tr>
                                <td width="18%" align="right" valign="top">Para las sucursales: </td>
                                <td width="82%"><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                    <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1
								ORDER BY titulo";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
								?>
                                    <tr>
                                      <td width="5%"><input type="checkbox" id="sede[<?= $c ?>]" name="sede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? 
									  if(!$idsede_log){ 
									  	echo 'checked="checked"'; 
									  }else{
										  if($idsede_log != $rs_sede['idsede']){ 
											echo $obj_value; 
										  }else{ 
											echo 'checked="checked"'; 
										  } 
									  }
									  
									  ?>  /></td>
                                      <td width="95%"><?= $rs_sede['titulo'] ?></td>
                                    </tr>
                                    <? 
								$c++;
								} 
								?>
                                  </table>
                                    <span class="style2">
                                    <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" />
                                  </span></td>
                              </tr>
                            </table></td>
                        </tr>
                    </table>
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingrese el t&iacute;tulo del nuevo producto:</td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="detalle_medio">
                              <tr>
                                <td width="110" align="right" class="detalle_medio">T&iacute;tulo</td>
                                <td align="left"><input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?=$titulo?>" size="60" />
                                  &nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right">&nbsp;</td>
                                <td align="left" class="detalle_chico" style="color:#FF0000"><span class="detalle_chico" style="color:#FF0000">
                                  <input name="Submit2222" type="button" class="detalle_medio_bold" onclick="insertar_carpeta();" value="  &gt;&gt;  Ingresar     " />
                                </span></td>
                              </tr>
                          </table></td>
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