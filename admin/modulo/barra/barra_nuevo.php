<?
 	include ("../../0_mysql.php"); 
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = " AND D.idsede = '$idsede_log' ";
	}else{
		$obj_value = '';
	}
	
	//VARIABLES
	$check_ididioma = $_POST['ididioma'];
	$cantidad_idioma = $_POST['cantidad_idioma'];
	$check_idsede = $_POST['idsede'];
	$cantidad_sede = $_POST['cantidad_sede'];
	
	$idseccion = $_POST['idseccion'];
	$idproducto = $_POST['idproducto'];
	
	$tipo = $_POST['tipo'];
	$url = $_POST['url'];
	$titulo = $_POST['titulo'];
	//$foto = $_POST['foto'];
	$target = $_POST['target'];
	$orden = $_POST['orden'];
	
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
	
	//INGRESAR NUEVO BOTON
	if($accion == "ingresar" && $tipo != ""){
		
		switch($tipo){
			
			case 1: //CARPETA
				
				$var_id = $mod6_sel_idcarpeta;
				$var_link = "carpeta_ver.php?idcarpeta=".$var_id;
				$var_orden = $orden;
				
				//INCORPORAR A LAS TABLAS
				$query_insert = "INSERT INTO barra_menu
				(tipo, id, link, orden)
				VALUES
				('$tipo','$var_id','$var_link','$var_orden')";
				mysql_query($query_insert);
				
				//OBTENGO EL ID DEL INGRESADO
				$query_max = "SELECT MAX(idbarra_menu) as idbarra_menu FROM barra_menu";
				$rs_max = mysql_fetch_assoc(mysql_query($query_max));
				
				//INGRESO DATOS DE LA CARPETA EN IDIOMA
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						$query = "SELECT B.nombre, B.ididioma, B.estado
						FROM carpeta A
						INNER JOIN carpeta_idioma_dato B ON A.idcarpeta = B.idcarpeta
						WHERE A.idcarpeta = '$var_id' AND B.ididioma = '$check_ididioma[$c]'";
						$result = mysql_query($query);
						$rs_titulo = mysql_fetch_assoc($result);
						$var_titulo = $rs_titulo['nombre'];
						
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						, estado
						) VALUES (
						  '$rs_max[idbarra_menu]'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						, '$rs_titulo[estado]'
						)";
						mysql_query($query_idioma_ingreso);
					}//IF
				}//FOR
				
				break;
				
			case 2: //SECCION
			
				$var_id = $idseccion;
				$var_link = "seccion_detalle.php?idseccion=".$var_id;
				$var_orden = $orden;
				
				//INCORPORAR A LAS TABLAS
				$query_insert = "INSERT INTO barra_menu
				(tipo, id, link, orden)
				VALUES
				('$tipo','$var_id','$var_link','$var_orden')";
				mysql_query($query_insert);
				
				//OBTENGO EL ID DEL INGRESADO
				$query_max = "SELECT MAX(idbarra_menu) as idbarra_menu FROM barra_menu";
				$rs_max = mysql_fetch_assoc(mysql_query($query_max));
				
				//INGRESO DATOS DE LA CARPETA EN IDIOMA
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						$query = "SELECT B.titulo, B.ididioma, B.estado
						FROM seccion A
						INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
						WHERE A.idseccion = '$var_id' AND B.ididioma = '$check_ididioma[$c]'
						ORDER BY B.titulo ASC
						LIMIT 1";
						$result = mysql_query($query);
						$rs_titulo = mysql_fetch_assoc($result);
						$var_titulo = $rs_titulo['titulo'];
						
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						, estado
						) VALUES (
						  '$rs_max[idbarra_menu]'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						, '$rs_titulo[estado]'
						)";
						mysql_query($query_idioma_ingreso);
						
					}//IF
				}//FOR
				
				break;
				
			case 3: //PRODUCTO
			
				$var_id = $idproducto;
				$var_link = "producto_detalle.php?idproducto=".$var_id;
				$var_orden = $orden;
				
				//INCORPORAR A LAS TABLAS
				$query_insert = "INSERT INTO barra_menu
				(tipo, id, link, orden)
				VALUES
				('$tipo','$var_id','$var_link','$var_orden')";
				mysql_query($query_insert);
				
				//OBTENGO EL ID DEL INGRESADO
				$query_max = "SELECT MAX(idbarra_menu) as idbarra_menu FROM barra_menu";
				$rs_max = mysql_fetch_assoc(mysql_query($query_max));
				
				//INGRESO DATOS DE LA CARPETA EN IDIOMA
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
						$query = "SELECT B.titulo, B.ididioma, B.estado
						FROM producto A
						INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto
						WHERE A.idproducto = '$var_id' AND B.ididioma = '$check_ididioma[$c]'
						ORDER BY B.titulo ASC
						LIMIT 1";
						$result = mysql_query($query);
						$rs_titulo = mysql_fetch_assoc($result);
						$var_titulo = $rs_titulo['titulo'];
						
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						, estado
						) VALUES (
						  '$rs_max[idbarra_menu]'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						, '$rs_titulo[estado]'
						)";
						mysql_query($query_idioma_ingreso);
						
					}//IF
				}//FOR
				
				break;
				
			case 4: //LINK			
			case 5: //TITULO O FOTO

				$var_id = 0;
				$var_link = $url;
				$var_target = $target;
				$var_orden = $orden;
				$var_titulo = $titulo; 

				//INCORPORAR A LAS TABLAS
				$query_insert = "INSERT INTO barra_menu
				(tipo, id, link, target, orden)
				VALUES
				('$tipo','$var_id','$var_link','$var_target','$var_orden')";
				mysql_query($query_insert);
				
				//OBTENGO EL ID DEL INGRESADO
				$query_max = "SELECT MAX(idbarra_menu) as idbarra_menu FROM barra_menu";
				$rs_max = mysql_fetch_assoc(mysql_query($query_max));
				
				//INGRESO DATOS DE LA CARPETA EN IDIOMA
				for($c=0;$c<$cantidad_idioma;$c++){
					
					if($check_ididioma[$c] != ""){
					
						$query_idioma_ingreso = "INSERT INTO barra_menu_idioma (
						  idbarra_menu
						, ididioma
						, titulo
						) VALUES (
						  '$rs_max[idbarra_menu]'
						, '$check_ididioma[$c]'
						, '$var_titulo'
						)";
						mysql_query($query_idioma_ingreso);
						
						if ($_FILES['foto']['name'] != ''){
							
							$ruta_foto = "../../../imagen/barra/";
							$archivo_ext = substr($_FILES['foto']['name'],-4);
							$archivo_nombre = substr($_FILES['foto']['name'],0,strrpos($_FILES['foto']['name'], "."));
							
							$archivo = str_replace(".", "_", $archivo_nombre) . $archivo_ext;
							$archivo = strtolower($archivo);
							
								$querysel = "SELECT foto FROM barra_menu_idioma WHERE idbarra_menu = '$rs_max[idbarra_menu]' AND ididioma = '$check_ididioma[$c]' ";
								$rowfoto = mysql_fetch_row(mysql_query($querysel));
								
								if ( $rowfoto[0] ){
									if (file_exists($ruta_foto.$rowfoto[0])){
										unlink ($ruta_foto.$rowfoto[0]);
									}
								}
								
							$foto =  $rs_max['idbarra_menu'] . '-' . rand(0,999) . '-' . $archivo; //se captura el nombre del archivo de la foto
							
							if (!copy($_FILES['foto']['tmp_name'], $ruta_foto . $foto)){ //si hay error en la copia de la foto
								$error = "Hubo un error al subir la foto. "; // se crea la variable error con un mensaje de error.
								echo "<script>alert('".$error."')</script>"; // se muestra el error.
							}else{
								$imagesize = getimagesize($ruta_foto.$foto);
							
								if($imagesize[2]>0 && $imagesize[2]<=16){ //si es una imagen
									$peso = number_format((filesize($ruta_foto.$foto))/1024,2);
									
									if($peso==0){
										$error2 = "La foto fue subida incorrectamente. "; //se crea la variable error con un mensaje de error.
										echo "<script>alert('".$error2."')</script>"; // se muestra el error.
									}else{
									
										//ingreso de foto en tabla producto
										$query_upd = "UPDATE barra_menu_idioma SET 
										foto = '$foto' 	
										WHERE idbarra_menu = '$rs_max[idbarra_menu]' AND ididioma = '$check_ididioma[$c]'
										LIMIT 1";
										mysql_query($query_upd);
					
									};			
								
								}else{
								
									$error3 = "El archivo subido no corresponde a un tipo de imagen permitido. ";
									echo "<script>alert('".$error3."')</script>"; // se muestra el error.
									
									if(!unlink($ruta_foto.$foto)){ //se elimina el archivo subido
										$error4 = "El archivo no pudo elminarse. ";
										echo "<script>alert('".$error4."')</script>"; // se muestra el error.
									}else{
										$error5 = "El archivo fue elminado. ";
										echo "<script>alert('".$error5."')</script>"; // se muestra el error.
									};
								
								};
						
							};
						}
					}//IF
				}//FOR
				
				break;
		
		}//FIN SWITCH
		
		//LO ASIGNO PARA LAS SEDE DETERMINADAS
		for($c=0;$c<$cantidad_sede;$c++){
			
			if($check_idsede[$c] != ""){
				$query_sede = "INSERT INTO barra_menu_sede(
				  idbarra_menu
				, idsede
				)VALUES(
				  '$rs_max[idbarra_menu]'
				, '$check_idsede[$c]'
				)";
				mysql_query($query_sede);
			}
		
		}
		
		//REDIRECCIONO A EDITAR
		echo "<script>window.open('barra_editar.php?tipo=$tipo&idbarra_menu=$rs_max[idbarra_menu]','_self');</script>";
	
	}//FIN IF
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
<script type="text/javascript">

	function cambiar_estado(estado, id){
		formulario = document.form;
		
		formulario.estado.value = estado;
		formulario.idbarra_menu.value = id;
		formulario.submit();
	};
	
	function agregar_nuevo(){
		var formulario = document.form;
		var flag = true;
		var checks_sede = 0;
		var checks_idioma = 0;
		
		switch(formulario.tipo.value){
			case '1':
				if(formulario.mod6_idcarpeta.value == ""){
					alert("Debe seleccionar una carpeta.");
					flag = false;
				}

				break;
			case '2':
				if(formulario.idseccion.value == ""){
					alert("Debe seleccionar una seccion.");
					flag = false;
				}
				break;
			case '3':
				if(formulario.idproducto.value == ""){
					alert("Debe seleccionar una producto.");
					flag = false;
				}
				break;
			case '5':
				if(formulario.foto.value == ""){
					alert("Debe seleccionar una foto.");
					flag = false;
				}
			case '4':
				if(formulario.titulo.value == ""){
					alert("Debe introducir el titulo.");
					flag = false;
				}
				if(formulario.url.value == ""){
					alert("Debe introducir la URL.");
					flag = false;
				}
				break;
		
		}
		
		
		if(esNumerico(formulario.orden.value)==false){
			alert("El Orden debe ser númerico.");
			flag = false;
		}
		
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = document.getElementById("idsede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}
		
		for(i=0;i<formulario.cantidad_idioma.value;i++){
			
			check_actual = document.getElementById("ididioma["+i+"]");
			if (check_actual.checked == true){
				checks_idioma = 1;
			}
			
		}
		
		if(checks_sede == 0){ 
			alert("Debe seleccionar al menos una sucursal.");
			flag = false;
		}
		
		if(checks_idioma == 0){ 
			alert("Debe seleccionar al menos un idioma.");
			flag = false;
		}
		
		if(flag == true){
			formulario = document.form;
			formulario.accion.value = "ingresar";
			formulario.submit();
		}else{
			alert("No se ha podido crear el boton.");
		}
	}
	
	function mod6_select_idcarpeta(nivel){
		formulario = document.form;
		
		switch(nivel){
			case 1: 
			 formulario.mod6_idcarpeta2.value = "";
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 1");
			 break;
		
			case 2: 
			 formulario.mod6_idcarpeta3.value = "";
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 2");
			 break;
			 
			case 3:
			 formulario.mod6_idcarpeta4.value = ""; 
			 alert("Nivel 3");
			 break;
		}

		formulario.submit();
	}
	
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Barra Menu - Nuevo Elemento </td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#cccccc;"></td>
              </tr>
            </table>
              <form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="#ffddbc">
                    <td height="40" align="left" bgcolor="#FFE9D2" class="titulo_medio_bold">Nuevo elemento                    
                    <input name="accion" type="hidden" id="accion" /></td>
                  </tr>
                  <tr valign="middle" bgcolor="#fff0e1" height="50">
                    <td align="left" bgcolor="#fff0e1" ><table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr>
                        <td width="110" align="right" class="detalle_medio">Tipo de boton:</td>
                        <td class="detalle_medio"><select name="tipo" style=" width:200px;" class="detalle_medio" id="tipo" onchange="javascript:document.form.submit();">
                          <option selected="selected" value="">- Seleccionar Tipo -</option>
                          <option value="1" <? if($tipo == 1){ echo "selected";} ?>>Carpeta</option>
                          <option value="2" <? if($tipo == 2){ echo "selected";} ?>>Seccion</option>
                          <option value="3" <? if($tipo == 3){ echo "selected";} ?>>Producto</option>
                          <option value="4" <? if($tipo == 4){ echo "selected";} ?>>Link</option>
                          <option value="5" <? if($tipo == 5){ echo "selected";} ?>>Imagen Titulo</option>
                        </select></td>
                      </tr>

                    </table>
					  <br />
					  <? if($tipo != ""){
					  		
							switch ($tipo){
								case 1: $nombre_tipo = "Carpeta"; break;
								case 2: $nombre_tipo = "Sección"; break;
								case 3: $nombre_tipo = "Producto"; break;
								case 4: $nombre_tipo = "Link"; break;
								case 5: $nombre_tipo = "Imagen Titulo"; break;
							}
					  
					   ?>
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio_bold"><?= $nombre_tipo ?></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
					  <? } ?> <? if($tipo == 1){ ?>
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="detalle_medio">
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
                          <td><div id="tr_carpeta">
                              <div id="colum_carpeta">Carpeta 1&ordm;</div>
                            <div id="colum_selector"><span class="style10">
                              <select name="mod6_idcarpeta" class="detalle_medio" id="mod6_idcarpeta" onchange="cambia(1)">
                                <option value="" selected="selected">--- Seleccionar Carpeta ---</option>
                                <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
	  {
	  	if ($carpeta1 == $rs_mod6_idcarpeta['idcarpeta'])
		{
			$sel = "selected";
		}else{
			$sel = "";
		}
?>
                                <option  <? echo $sel ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                                <?= $rs_mod6_idcarpeta['nombre'] ?>
                                </option>
                                <?  } ?>
                              </select>
                            </span></div>
                          </div>
                              <div style="clear:left; height:1px;"></div>
                            <div id="tr_carpeta2" style="display:none">
                                <div id="colum_carpeta">Carpeta 2&ordm;</div>
                              <div id="colum_selector">
                                  <select name="mod6_idcarpeta2" class="detalle_medio" id="mod6_idcarpeta2" onchange="cambia(2)">
                                  </select>
                              </div>
                            </div>
                            <div style="clear:left; height:1px;"></div>
                            <div id="tr_carpeta3" style="display:none">
                                <div id="colum_carpeta">Carpeta 3&ordm;</div>
                              <div id="colum_selector">
                                  <select name="mod6_idcarpeta3" class="detalle_medio" id="mod6_idcarpeta3" onchange="cambia(3)">
                                  </select>
                              </div>
                            </div>
                            <div style="clear:left; height:1px;"></div>
                            <div id="tr_carpeta4" style="display:none">
                                <div id="colum_carpeta">Carpeta 4&ordm;</div>
                              <div id="colum_selector">
                                  <select name="mod6_idcarpeta4" class="detalle_medio" id="mod6_idcarpeta4" onchange="">
                                  </select>
                              </div>
                            </div>
                            <div style="clear:left; height:1px;"></div></td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo == 2 || $tipo == 3){ ?>
					  <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr>
                          <td width="110" align="right" valign="middle" class="detalle_medio">Carpeta 1&ordm; </td>
                          <td width="548" align="left" valign="middle" class="style2"><span class="style10">
                            <select name="mod6_idcarpeta" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta" onchange="mod6_select_idcarpeta('1')">
                              <option value="" selected="selected">- Seleccionar Carpeta</option>
                              <?
	  $query_mod6_idcarpeta = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = 0 AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta = mysql_query($query_mod6_idcarpeta);
	  while ($rs_mod6_idcarpeta = mysql_fetch_assoc($result_mod6_idcarpeta))	  
	  {
	  	if ($mod6_idcarpeta == $rs_mod6_idcarpeta['idcarpeta'])
		{
			$sel = "selected";
		}else{
			$sel = "";
		}
?>
                              <option  <? echo $sel ?> value="<?= $rs_mod6_idcarpeta['idcarpeta'] ?>">
                              <?= $rs_mod6_idcarpeta['nombre'] ?>
                              </option>
                              <?  } ?>
                            </select>
                          </span></td>
                        </tr>
                        <tr>
                          <? if($mod6_idcarpeta){ ?>
                          <td align="right" valign="middle" class="detalle_medio">Carpeta 2&ordm;</td>
                          <td align="left" valign="middle" class="style2"><span class="style10">
                            <select name="mod6_idcarpeta2" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta2" onchange="mod6_select_idcarpeta('2');">
                              <option value="" selected="selected">- Seleccionar Carpeta</option>
                              <?
	  $query_mod6_idcarpeta2 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta'  AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta2 = mysql_query($query_mod6_idcarpeta2);
	  while ($rs_mod6_idcarpeta2 = mysql_fetch_assoc($result_mod6_idcarpeta2))	  
	  {
	  	if ($mod6_idcarpeta2 == $rs_mod6_idcarpeta2['idcarpeta'])
		{
			$sel2 = "selected";
		}else{
			$sel2 = "";
		}
?>
                              <option  <? echo $sel2 ?> value="<?= $rs_mod6_idcarpeta2['idcarpeta'] ?>">
                              <?= $rs_mod6_idcarpeta2['nombre'] ?>
                              </option>
                              <?  } ?>
                            </select>
                          </span></td>
                          <?  } ?>
                        </tr>
                        <tr>
                          <? if($mod6_idcarpeta2 && $mod6_idcarpeta){ ?>
                          <td align="right" valign="middle" class="detalle_medio">Carpeta 3&ordm; </td>
                          <td align="left" valign="middle" class="style2"><span class="style10">
                            <select name="mod6_idcarpeta3" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta3" onchange="mod6_select_idcarpeta('3')">
                              <option value="">- Seleccionar Carpeta</option>
                              <?
	  $query_mod6_idcarpeta3 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta2' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta3 = mysql_query($query_mod6_idcarpeta3);
	  while ($rs_mod6_idcarpeta3 = mysql_fetch_assoc($result_mod6_idcarpeta3))	  
	  {
	  	if ($mod6_idcarpeta3 == $rs_mod6_idcarpeta3['idcarpeta'])
		{
			$sel3 = "selected";
		}else{
			$sel3 = "";
		}
?>
                              <option  <? echo $sel3 ?> value="<?= $rs_mod6_idcarpeta3['idcarpeta'] ?>">
                              <?= $rs_mod6_idcarpeta3['nombre'] ?>
                              </option>
                              <?  } ?>
                            </select>
                          </span></td>
                          <?  }   ?>
                        </tr>
                        <tr>
                          <? if($mod6_idcarpeta3 && $mod6_idcarpeta2  && $mod6_idcarpeta){ ?>
                          <td align="right" valign="middle" class="detalle_medio">Carpeta 4&ordm; </td>
                          <td align="left" valign="middle" class="style2"><span class="style10">
                            <select name="mod6_idcarpeta4" style="width:200px;" class="detalle_medio" id="mod6_idcarpeta4">
                              <option value="" selected="selected">- Seleccionar Carpeta</option>
                              <?
	  $query_mod6_idcarpeta4 = "SELECT A.idcarpeta, B.nombre 
	  FROM carpeta A
	  INNER JOIN carpeta_idioma_dato B ON B.idcarpeta = A.idcarpeta
	  WHERE A.estado <> 3 AND A.idcarpeta_padre = '$mod6_idcarpeta3' AND A.idcarpeta <> '$idcarpeta' AND B.ididioma = '1'
	  ORDER BY B.nombre";
	  $result_mod6_idcarpeta4 = mysql_query($query_mod6_idcarpeta4);
	  while ($rs_mod6_idcarpeta4 = mysql_fetch_assoc($result_mod6_idcarpeta4))	  
	  {
	  	if ($mod6_idcarpeta4 == $rs_mod6_idcarpeta4['idcarpeta'])
		{
			$sel4 = "selected";
		}else{
			$sel4 = "";
		}
?>
                              <option  <? echo $sel4 ?> value="<?= $rs_mod6_idcarpeta4['idcarpeta'] ?>">
                              <?= $rs_mod6_idcarpeta4['nombre'] ?>
                              </option>
                              <?  } ?>
                          </select>
                          </span></td>
                          <?  }   ?>
                        </tr>
                      </table> 
					  <? } ?>
					  <? if($tipo == 2){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Secciones:</td>
                          <td><label>
                          <select name="idseccion" style="width:450px; background:#FF6600; color:#FFFFFF; border:0;" class="detalle_medio" id="idseccion">
                            <option value="">- Seleccionar Seccion</option>
                            <?
							$query_seccion = "SELECT DISTINCT A.idseccion, B.titulo 
							FROM seccion A
							INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
							INNER JOIN seccion_carpeta C ON A.idseccion = C.idseccion
							INNER JOIN seccion_sede D ON A.idseccion = D.idseccion
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1 $filtro_sede";
							$result_seccion = mysql_query($query_seccion);
							while($rs_seccion = mysql_fetch_assoc($result_seccion)){
							?>
                            <option value="<?= $rs_seccion['idseccion'] ?>">
                              <?= $rs_seccion['titulo'] ?>
                            </option>
                            <?
							}
							?>
                          </select>
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo == 3){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Productos:</td>
                          <td><label>
                          <select name="idproducto" style="width:450px; background:#669966; color:#FFFFFF; border:0;" class="detalle_medio" id="idproducto">
                            <option value="">- Seleccionar Productos</option>
                            <?
							$query_producto = "SELECT DISTINCT A.idproducto, B.titulo 
							FROM producto A
							INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto
							INNER JOIN producto_carpeta C ON A.idproducto = C.idproducto
							INNER JOIN producto_sede D ON A.idproducto = D.idproducto
							WHERE B.ididioma = 1 AND C.idcarpeta = '$mod6_sel_idcarpeta' AND A.estado = 1 $filtro_sede";
							$result_producto = mysql_query($query_producto);
							while($rs_producto = mysql_fetch_assoc($result_producto)){
							?>
                            <option value="<?= $rs_producto['idproducto'] ?>">
                            <?= $rs_producto['titulo'] ?>
                            </option>
                            <?
							}
							?>
                          </select>
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo == 5 || $tipo == 4){ ?>
				      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Url:</td>
                          <td><label>
                          <input name="url" type="text" class="detalle_medio" id="url" size="70" />
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo == 5){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Foto:</td>
                          <td><label>
                          <input name="foto" type="file" class="detalle_medio" id="foto" size="55" />
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo == 5 || $tipo == 4){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Titulo:</td>
                          <td><label>
                          <input name="titulo" type="text" class="detalle_medio" id="titulo" size="70" />
                          </label></td>
                        </tr>
                      </table>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Target:</td>
                          <td><label>
                            <select name="target" class="detalle_medio" id="target">
                              <option value="_self" selected="selected">_self</option>
                              <option value="_blank">_blank</option>
                              <option value="_parent">_parent</option>
                              <option value="_top">_top</option>
                            </select>
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
					  <? if($tipo != ""){ ?>
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">Orden:</td>
                          <td><label>
                          <input name="orden" type="text" class="detalle_medio" id="orden" value="0" size="7" />
                          </label></td>
                        </tr>
                      </table>
					  <br />
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFE9D2">
                        <tr>
                          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="110" align="right" valign="top" class="detalle_medio">Sucursales: </td>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								
								?>
                                  <tr>
                                    <td width="5%"><input type="checkbox" id="idsede[<?= $c ?>]" name="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <? if($_GET['idsede'] == $rs_sede['idsede']){ echo "checked";} ?> <? if($idsede_log != $rs_sede['idsede']){ echo $obj_value;} ?>   /></td>
                                    <td width="95%" class="detalle_medio"><?= $rs_sede['titulo'] ?></td>
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
                          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td width="110" align="right" valign="top" class="detalle_medio">Idiomas: </td>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <?
								  		  $c=0;
										  $query_ididioma = "SELECT ididioma, titulo_idioma 
										  FROM idioma
										  WHERE estado = '1'
										  ORDER BY titulo_idioma";
										  $result_ididioma = mysql_query($query_ididioma);
										  while ($rs_ididioma = mysql_fetch_assoc($result_ididioma))	  
										  {

							?>
                                  <tr>
                                    <td width="4%"><input name="ididioma[<?= $c ?>]" type="checkbox" id="ididioma[<?= $c ?>]" value="<?= $rs_ididioma['ididioma'] ?>" <? if($_GET['ididioma'] == $rs_ididioma['ididioma']){ echo "checked";} ?> /></td>
                                    <td width="96%" class="detalle_medio"><?= $rs_ididioma['titulo_idioma'] ?></td>
                                  </tr>
                                  <?  $c++; } ?>
                                </table>
                                  <input name="cantidad_idioma" type="hidden" id="cantidad_idioma" value="<?= $c ?>" /></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table>
					  <br />
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="110" align="right" class="detalle_medio">&nbsp;</td>
                          <td><label>
                          <input name="Button" type="button" class="detalle_medio_bold" value=" Insertar &raquo;" onclick="javascript:agregar_nuevo()"/>
                          </label></td>
                        </tr>
                      </table>
					  <? } ?>
				    </td>
                  </tr>
                </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr align="center" valign="middle" height="50">
                    <td height="20" align="left" valign="top" class="detalle_medio" style="color:#FF6600"><br /></td>
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