<? 
	include ("../../0_mysql.php"); 
	
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = $idsede_log;
	}else{
		$obj_value = '';
	}

	// localizacion de variables get y post:	
	$accion = $_POST['accion'];
	$ididioma = $_POST['ididioma'];
	$pregunta = $_POST['pregunta'];
	$cant_opciones = $_POST['cant_opciones'];
	
	$cantidad_sede = $_POST['cantidad_sede'];
	$idsede = $_POST['idsede'];

	// Ingreso del encuesta:
	if($accion == "ingresar"){
		
		$fecha_hoy = date("Y-m-d");
		
		//INGRESO ENCUESTA
		$query_ins_encuesta = "INSERT INTO encuesta (
		  ididioma
		, pregunta
		, estado
		, fecha_alta
		) VALUES (
		  '$ididioma'
		, '$pregunta'
		, '2'
		, '$fecha_hoy'
		)";
		$res_ins = mysql_query($query_ins_encuesta);
		
		//SI INGRESO ENCUESTA
		if($res_ins == 1){
		
			//LAST_INSERTED_ID
			$query_id = "SELECT LAST_INSERT_ID() as last_id";
			$rs_id = mysql_fetch_assoc(mysql_query($query_id));
		
			//INGRESO CANT DE OPCIONES
			for($e=0;$e<$cant_opciones;$e++){
				
				$n = $e + 1;
				$query_insert = "INSERT INTO encuesta_opcion(
				  idencuesta
				, opcion
				)VALUES(
				  '$rs_id[last_id]'
				, 'Opcion $n'
				)";
				mysql_query($query_insert);
			}//FIN OPCIONES
			
			//INGRESO A SEDES
			for($c=0;$c<$cantidad_sede;$c++){
				$query_insert = "INSERT INTO encuesta_sede(
				  idencuesta
				, idsede
				)VALUES(
				  '$rs_id[last_id]'
				, '$idsede[$c]'
				)";
				mysql_query($query_insert);
			}//FIN SEDE
			
			// REDIRECCIONAR A EDITAR ENCUESTA OPCIONES:
			echo "<script>document.location.href = 'encuesta_editar.php?idencuesta=".$rs_id['last_id']."&ididioma=".$ididioma."';</script>";
		
		}else{//SI HUBO UN ERROR AL INGRESAR LA ENCUESTA
			
			echo "ERROR: No se pudo crear la encuesta.";
			
		}//FIN SI INGRESO ENCUESTA
		
		
		
	};


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_encuesta(){
		var formulario = document.form;
		var flag = true;
		var checks_sede = 0;
		var error = '';
		
		if(formulario.pregunta.value == ''){
			error = "Debe ingresar la pregunta de la encuesta.\n";
			flag = false;
		}
		
		if(formulario.cant_opciones.value < 2 || esNumerico(formulario.cant_opciones.value) == false){
			error = error + "La cantidad de opciones que debe ingresar deben ser mayores a 2.\n";
			flag = false;
		}
		
		for(i=0;i<formulario.cantidad_sede.value;i++){
			
			check_actual = document.getElementById("idsede["+i+"]");
			if (check_actual.checked == true){
				checks_sede = 1;
			}
			
		}

		if(checks_sede == 0){ 
			error = error + "Debe seleccionar al menos una sucursal.\n";
			flag = false;
		}
			
		if(flag == true){
			formulario.accion.value = "ingresar";
			formulario.submit();
		}else{
			alert(error);
		}
	
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Encuesta - Nuevo</td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Ingresar nueva  encuesta: <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#FFF0E1">
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
                                <tr>
                                  <td width="111" align="right" valign="middle" class="detalle_medio">Pregunta:</td>
                                  <td width="547" valign="top" class="style2">
                                    <input name="pregunta" type="text" class="detalle_medio" id="pregunta" style="width:99%" />                                 </td>
                                </tr>
                                <tr>
                                  <td width="111" align="right" valign="middle" class="detalle_medio">Idioma:</td>
                                  <td valign="top" class="style2"><label>
                                    <select name="ididioma" class="detalle_medio" id="ididioma" style="width:120px;">
										<?
										$query_idioma = "SELECT titulo_idioma, ididioma
										FROM idioma
										WHERE estado = 1";
										$result_idioma = mysql_query($query_idioma);
										
										while($rs_idioma = mysql_fetch_assoc($result_idioma)){ ?>
                                      <option value="<?= $rs_idioma['ididioma'] ?>"><?= $rs_idioma['titulo_idioma'] ?></option>
									  	<? } ?>
                                    </select>
                                  </label></td>
                                </tr>
                                <tr>
                                  <td width="111" align="right" valign="middle" class="detalle_medio">Cant. de Opciones: </td>
                                  <td valign="top" class="style2"><label>
                                    <input name="cant_opciones" type="text" class="detalle_medio" id="cant_opciones" style="width:35px; text-align:center" value="2" />
                                  </label></td>
                                </tr>
                                <tr>
                                  <td width="111" align="right" valign="top" class="detalle_medio">Sucursales:</td>
                                  <td align="left" valign="middle" class="detalle_medio">
                                    <?
								$c=0;
								$query_sede = "SELECT idsede, titulo
								FROM sede
								WHERE estado = 1";
								$result_sede = mysql_query($query_sede);
								while($rs_sede = mysql_fetch_assoc($result_sede)){
								?>
                                    <input name="idsede[<?= $c ?>]" type="checkbox" id="idsede[<?= $c ?>]" value="<?= $rs_sede['idsede'] ?>" <?
									   if(!$idsede_log){ 
									  	echo 'checked="checked"'; 
									  }else{
										  if($idsede_log != $rs_sede['idsede']){ 
											echo $obj_value; 
										  }else{ 
											echo 'checked="checked"'; 
										  } 
									  }
									   ?> />
                                      <?= $rs_sede['titulo'] ?>
                                      <br />
                                    <? 
								$c++;
								} 
								?>
                                    <input name="cantidad_sede" type="hidden" id="cantidad_sede" value="<?= $c ?>" /></td>
                                </tr>
                                <tr>
                                  <td height="10" colspan="2" valign="top" class="style2"></td>
                                </tr>
                                <tr>
                                  <td valign="top" class="style2">&nbsp;</td>
                                  <td align="left" valign="middle" class="style2"><input name="Submit" type="button" class="detalle_medio_bold" id="Submit" onclick="validar_encuesta();" value=" Insertar Encuesta &raquo; " /></td>
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