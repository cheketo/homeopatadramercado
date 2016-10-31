<? 

	include("../../0_mysql.php");
	
	//MODIFICAR SECCION:
	if($accion == "ingresar"){
			
			//INCORPORACION
			if ($_FILES['archivo']['name'] != ''){
		
				$archivo_ext = substr($_FILES['archivo']['name'],-4);
				
				if($archivo_ext == ".txt"){
				
					$archivo = "listado_newsletter.txt";
					
					if(file_exists($archivo)){
						unlink ($archivo);
					}
					
					if (!copy($_FILES['archivo']['tmp_name'], $archivo)){ //si hay error en la copia
						echo "<script>alert('Hubo un error al subir el archivo.')</script>";
					}else{
						$peso = number_format((filesize($archivo))/1024,2);
						if($peso==0){
							echo "<script>alert('El archivo fue subido incorrectamente.')</script>"; // se muestra el error.
						}
					}
				}else{
					echo "<script>alert('El archivo no es válido. Debe ser formato txt.')</script>";
				}	
			}else{
				echo "<script>alert('No hay archivo.')</script>";
			} //FIN INCORPORACION
			
			//VARIABLES
			$first = true;
			$cant_user_segmentacion = $_POST['cant_user_segmentacion'];
			$user_segmentacion =  $_POST['user_segmentacion'];
			
			for($i=1;$i<=$cant_user_segmentacion;$i++){
				
				if($user_segmentacion[$i] != ""){
					
					if($first == true){
						$str_segmentacion = $user_segmentacion[$i];
						$first = false;
					}else{
						$str_segmentacion .= "-".$user_segmentacion[$i];
					}
				
				}
				
			}
			
			echo "<script>document.location.href=('imp_importar.php?segmentacion=".$str_segmentacion."&tipo=".$_POST['tipo']."');</script>";		
	};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
<style type="text/css">
<!--
.style1 {font-size: 11px}
-->
</style>
<script language="javascript" type="text/javascript">
	function validar(){
		var f = document.frm;
		var flag = true;
		var error = "";
		var checks_sede = 0;
		var check_actual = false;
		var i = 0;
		
		//ARCHIVO
		if (f.archivo.value == "")	{
			error = error + 'Debe seleccionar el archivo a importar.\n';
			flag = false;	
		}
		
		for(i=1;i<=f.cant_user_segmentacion.value;i++){
			
			if(document.getElementById("user_segmentacion["+i+"]").checked){
				checks_sede = 1;
			}
			
		}
		
		if(!checks_sede){ 
		
			if (confirm('Usted no selecciono ninguna segmentación. \n ¿Esta seguro que desea continuar?')){
				//FINAL
				if (flag == true) {
					f.accion.value = "ingresar";
					f.submit(); 
				}else{
					alert(error);
				}
			}
			
		}else{
		
			//FINAL
			if (flag == true) {
				f.accion.value = "ingresar";
				f.submit(); 
			}else{
				alert(error);
			}
			
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
                <td height="40" valign="bottom" class="titulo_grande_bold">Importar Usuarios - Preconfiguraci&oacute;n </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form id="frm" name="frm" method="post" action="" enctype="multipart/form-data">
                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr>
                        <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Seleccione archivo de importaci&oacute;n:
                          <input name="accion" type="hidden" id="accion" value="" /></td>
                      </tr>
                      <tr>
                        <td bgcolor="#FFF0E1"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td colspan="2" align="right" valign="top" class="detalle_medio_bold"></td>
                            </tr>

                            <tr>
                              <td width="15%" align="right" valign="middle" class="detalle_medio">Archivo:</td>
                              <td width="85%" align="left" valign="top"><label>
                                <input name="archivo" type="file" class="detalle_medio" id="archivo" size="60" />
                              </label></td>
                            </tr>
                            <tr>
                              <td width="15%" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                              <td align="left" valign="top" class="detalle_medio"><label>
                                <input name="tipo" type="radio" value="mail" checked="checked" />
                                Solo mails. <br /></label>
								<? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                                <label><input name="tipo" type="radio" value="generico" />
                              Archivo multicampo.</label>
							  	<? } ?>
							  </td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="detalle_medio_bold">&nbsp;</td>
                              <td align="left" valign="top"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar();" value=" &raquo; Ingresar " /></td>
                            </tr>
                        </table></td>
                      </tr>
                    </table>
                                    <br />
                                    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                                      <tr bgcolor="#999999">
                                        <td height="40" bgcolor="#FFDDBC" class="titulo_medio_bold">Segmentaciones</td>
                                      </tr>
                                      <tr bgcolor="#999999">
                                        <td align="center" bgcolor="#FFF0E1"><table width="90%" border="0" cellspacing="0" cellpadding="3">
                                            <tr>
                                              <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                  <tr>
                                                    <td height="40" align="left" valign="bottom" class="detalle_medio_bold">Segmentaciones Generales de la web. (Internas) </td>
                                                  </tr>
                                                </table>
                                                  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
                                                    <?
							  $cont_user_segmentacion = 0;
							  $query_user_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = 2
							  ORDER BY titulo";
							  $result_user_segmentacion = mysql_query($query_user_segmentacion);
							  while ($rs_user_segmentacion = mysql_fetch_assoc($result_user_segmentacion))	  
							  {
							  	$cont_user_segmentacion++;
								
								if ($user_segmentacion == $rs_user_segmentacion['iduser_segmentacion'])
								{
									$mod6_sel_user_segmentacion = "selected";
								}else{
									$mod6_sel_user_segmentacion = "";
								}
							 ?>
                                                    <tr>
                                                      <td width="20" align="center" valign="middle"><input name="user_segmentacion[<?=$cont_user_segmentacion?>]" type="checkbox" id="user_segmentacion[<?=$cont_user_segmentacion?>]" value="<?=$rs_user_segmentacion['iduser_segmentacion']?>" /></td>
                                                      <td align="left" valign="middle" class="detalle_medio"><?=$rs_user_segmentacion['titulo']?></td>
                                                    </tr>
                                                    <?
								};
							 ?>
                                                </table></td>
                                              <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                  <tr>
                                                    <td height="40" align="left" valign="bottom" class="detalle_medio_bold"><span >Segmentaciones Especiales. </span></td>
                                                  </tr>
                                                </table>
                                                  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
                                                    <?
							  
							  $query_user_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = 1
							  ORDER BY titulo";
							  $result_user_segmentacion = mysql_query($query_user_segmentacion);
							  while ($rs_user_segmentacion = mysql_fetch_assoc($result_user_segmentacion))	  
							  {
							  	$cont_user_segmentacion++;
								
								if ($user_segmentacion == $rs_user_segmentacion['iduser_segmentacion'])
								{
									$mod6_sel_user_segmentacion = "selected";
								}else{
									$mod6_sel_user_segmentacion = "";
								}
							 ?>
                                                    <tr>
                                                      <td width="20" align="center" valign="middle"><input name="user_segmentacion[<?=$cont_user_segmentacion?>]" type="checkbox" id="user_segmentacion[<?=$cont_user_segmentacion?>]" value="<?=$rs_user_segmentacion['iduser_segmentacion']?>" /></td>
                                                      <td align="left" valign="middle" class="detalle_medio"><?=$rs_user_segmentacion['titulo']?></td>
                                                    </tr>
                                                    <?
								};
							 ?>
                                                </table></td>
                                            </tr>
                                          </table>
                                            <table width="90%" border="0" cellspacing="0" cellpadding="5">
                                              <tr>
                                                <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                    <tr>
                                                      <td height="30" align="left" valign="bottom" class="detalle_medio_bold">Segmentaciones Ocultas. (Para Bases de datos) </td>
                                                    </tr>
                                                  </table>
                                                    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="detalle_medio">
                                                      <?
							  
							  $query_user_segmentacion = "SELECT *
							  FROM user_segmentacion
							  WHERE estado = '1' AND tipo = 3
							  ORDER BY titulo";
							  $result_user_segmentacion = mysql_query($query_user_segmentacion);
							  while ($rs_user_segmentacion = mysql_fetch_assoc($result_user_segmentacion))	  
							  {
							  	$cont_user_segmentacion++;
								
								if ($user_segmentacion == $rs_user_segmentacion['iduser_segmentacion'])
								{
									$mod6_sel_user_segmentacion = "selected";
								}else{
									$mod6_sel_user_segmentacion = "";
								}
							 ?>
                                                      <tr>
                                                        <td width="20" align="center" valign="middle"><input name="user_segmentacion[<?=$cont_user_segmentacion?>]" type="checkbox" id="user_segmentacion[<?=$cont_user_segmentacion?>]" value="<?=$rs_user_segmentacion['iduser_segmentacion']?>" /></td>
                                                        <td align="left" valign="middle" class="detalle_medio"><?=$rs_user_segmentacion['titulo']?></td>
                                                      </tr>
                                                      <?
								};
							 ?>
                                                    </table></td>
                                                <td width="50%" align="left" valign="top">&nbsp;</td>
                                              </tr>
                                            </table>
                                            <table width="90%" border="0" cellspacing="0" cellpadding="4">
                                              <tr>
                                                <td height="30" align="left" class="detalle_11px style1"><a href="javascript:seleccionar_todo();" style="color:#990000">Seleccionar todo</a> &nbsp;/ &nbsp;<a href="javascript:deseleccionar_todo();" style="color:#990000">Deseleccionar todo 
                                                  <input name="cant_user_segmentacion" type="hidden" id="cant_user_segmentacion" value="<?=$cont_user_segmentacion?>" />
                                                </a></td>
                                              </tr>
                                          </table></td>
                                      </tr>
                                    </table>
									<script language="JavaScript" type="text/javascript">

function seleccionar_todo(){
	for(i=1; i<<?=$cont_user_segmentacion+1?>; i++){
		ncampo = "user_segmentacion["+i+"]";
		campo = document.getElementById(ncampo);
		campo.checked = true;
	}
}

function deseleccionar_todo(){
	for(i=1; i<<?=$cont_user_segmentacion+1?>; i++){
		ncampo = "user_segmentacion["+i+"]";
		campo = document.getElementById(ncampo);
		campo.checked = false;
	}
}

</script>
                  </form>
                  </td>
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