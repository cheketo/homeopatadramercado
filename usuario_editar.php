<? 
	include_once("0_include/0_mysql.php"); 

	$accion = $_POST['accion'];
	$iduser_web = $_SESSION['iduser_web_session'];
	
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$dia_nacimiento = $_POST['dia_fecha_nacimiento'];
	$mes_nacimiento = $_POST['mes_fecha_nacimiento'];
	$ano_nacimiento = $_POST['ano_fecha_nacimiento'];
	$fecha_nacimiento = $ano_nacimiento."-".$mes_nacimiento."-".$dia_nacimiento;
	$sexo = $_POST['sexo'];
	
	$calle = $_POST['calle'];
	$calle_numero = $_POST['calle_numero'];
	$piso = $_POST['piso'];
	$depto = $_POST['depto'];
	$edificio = $_POST['edificio'];
	$idpais = $_POST['idpais'];
	$idpais_provincia = $_POST['idpais_provincia'];
	$localidad = $_POST['localidad'];
	$cp = $_POST['cp'];
	
	if($accion == "modificarUsuario"){
		
		$query_update = "UPDATE user_web SET
		  nombre = '$nombre'
		, apellido  = '$apellido'
		, fecha_nacimiento = '$fecha_nacimiento'
		, sexo = '$sexo'
		, calle = '$calle'
		, calle_numero = '$calle_numero'
		, piso = '$piso'
		, depto = '$depto'
		, idpais = '$idpais'
		, idpais_provincia ='$idpais_provincia'
		, localidad = '$localidad'
		, cp = '$cp'
		, username = '$username'
		, password = '$password'
		WHERE iduser_web = '$iduser_web' ";
		$res = mysql_query($query_update);

	}
	
	if($accion == "modificarPais"){
	
		$query_update = "UPDATE user_web SET
		 idpais = '$idpais'
		, idpais_provincia = '0'
		WHERE iduser_web = '$iduser_web' ";
		mysql_query($query_update);
		
	}
	
	$query_user = "SELECT *
	FROM user_web
	WHERE iduser_web = '$iduser_web'";
	$rs_user = mysql_fetch_assoc(mysql_query($query_user));
	
	$fecha_nacimiento = split("-",$rs_user['fecha_nacimiento']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include_once("0_include/0_head.php"); ?>
<script type="text/javascript">
	
	function esNumerico(campo){
	
	   var caracteresValidos = "0123456789";
	   var esNumero = true;
	   var caracter;
	
	   for (i = 0; i < campo.length && esNumero == true; i++){
	   
		  caracter = campo.charAt(i); 
		  if (caracteresValidos.indexOf(caracter) == -1){
			 esNumero = false;
		  }
	   }
	   return esNumero;
	};

	
	function modificarUsuario(){
		var f = document.form;
		var flag = true;
		var error = '';
		
		//Nombre (*)
		if (f.nombre.value.length > 0 && f.nombre.value.length < 4)	{
			error = error + 'Debe ingresar su nombre.\n';
			flag = false;	
		}
		
		//Apellido (*)
		if (f.apellido.value.length > 0 && f.apellido.value.length < 4)	{
			error = error + 'Debe ingresar su apellido.\n'; 
			flag = false;	
		}
		
		//Username (*)
		if (f.username.value.length > 0 && f.username.value.length < 4)	{
			error = error + 'Debe ingresar su username.\n';
			flag = false;	
		}
		
		//Password (*)
		if(f.password.value.length > 0){
			if (f.password.value.length < 4 )	{
				error = error + "La contraseña debe ser de al menos 4 caracteres.\n";
				flag = false;
			}else{
				if(f.password.value != f.confirmar_password.value){
					error = error + "La contraseña no coincide con la contraseña de confirmación.\n";
					flag = false;
				}
			}
		}
		
		//Calle Numero
		if(esNumerico(f.calle_numero.value) == false){
			error = error + "El número de su domicilio debe ser númerico.\n";
			flag = false;
		}
		
		//Codigo Postal
		if(f.cp.value == ""){
			error = error + "Ingrese por favor su código postal.\n";
			flag = false;
		}
		
		if(flag == true){
			f.accion.value = "modificarUsuario";
			f.submit();
		}else{
			alert(error);
		}
	}

</script>
</head>

<body>
<div id="header"><? include_once("0_include/0_top.php"); ?></div>
<div id="cuerpo">
	<div id="barra_izq_imagen"></div>
	
  <div id="barra">
		<? include_once("0_include/0_barra.php"); ?>
  </div>
  <div id="contenido">
    <table width="100%" border="0" cellspacing="6" cellpadding="0">
      <tr>
        <td colspan="2" align="center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="640" height="40">
          <param name="movie" value="skin/index/swf/titulo.swf" />
          <param name="quality" value="high" />
          <param name="wmode" value="transparent" />
          <param name="FlashVars" value="titulo=Mis Datos Personales" />
          <embed src="skin/index/swf/titulo.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="640" height="40" FlashVars="titulo=Mis Datos Personales" wmode="transparent"></embed>
        </object></td>
      </tr>
      <tr>
        <td width="70%" align="left" valign="top"><form id="form" name="form" method="post" action="" enctype="multipart/form-data">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellpadding="7" cellspacing="0" bordercolor="#FCFCFC" bgcolor="#F7F7F7" class="tabla_producto">
              <tr>
                <td height="40" valign="middle" class="carpeta_producto_titulo_01"><label><strong>Modificar Mis Datos Personales </strong>
                    <input name="accion" type="hidden" id="accion" />
                    <br />
                  Recuerde que los campos con (*) son obligatorios.</label></td>
                </tr>
              <tr>
                <td align="center" class="carpeta_producto_titulo_01"><table width="95%" border="0" cellspacing="0" cellpadding="4">
                  <tr>
                    <td width="33%" align="left" valign="middle">* Nombre(s):</td>
                    <td width="67%" align="left" valign="middle"><label>
                      <input name="nombre" type="text" class="cuadro_login_texto" id="nombre" style="width:200px;" value="<?= $rs_user['nombre'] ?>" />
                    </label></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* Apellido(s):</td>
                    <td align="left" valign="middle"><input name="apellido" type="text" class="cuadro_login_texto" id="apellido" value="<?= $rs_user['apellido'] ?>" style="width:200px;" /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">&nbsp;&nbsp; Fecha Nacimiento:</td>
                    <td align="left" valign="middle"><span class="style2">
                      <select name="dia_fecha_nacimiento" size="1" class="cuadro_login_texto" id="dia_fecha_nacimiento">
                        <option value='00' ></option>
                        <?												
						for ($i=1;$i<32;$i++){
							if ($fecha_nacimiento[2] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                      </select>
                      <select name="mes_fecha_nacimiento" size="1" class="cuadro_login_texto" id="mes_fecha_nacimiento">
                        <option value='00' ></option>
                        <?						
                        for ($i=1;$i<13;$i++){
							if ($fecha_nacimiento[1] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                      </select>
                      <select name="ano_fecha_nacimiento" size="1" class="cuadro_login_texto" id="ano_fecha_nacimiento">
                        <option value='0000' ></option>
                        <?	
						$anioActual = date("Y");
                        for ($i=$anioActual-8;$i>($anioActual-80);$i--){
							if ($fecha_nacimiento[0] == $i){								     
								$sel_fecha_ano = "selected";
							}else{
								$sel_fecha_ano  = "";
							}	
							
                                print "<option $sel_fecha_ano value='" . $i . "'>" . $i . "</option>";
                        }
                        ?>
                      </select>
                      </font></span></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">&nbsp;&nbsp; Sexo:</td>
                    <td align="left" valign="middle"><select name="sexo" class="cuadro_login_texto" id="sexo" style="width:140px;">
                      <option value="N" <? if($rs_user['sexo'] == "N"){ echo "selected"; } ?>> - No seleccionado. </option>
                      <option value="M" <? if($rs_user['sexo'] == "M"){ echo "selected"; } ?>>Masculino</option>
                      <option value="F" <? if($rs_user['sexo'] == "F"){ echo "selected"; } ?>>Femenino</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">&nbsp;&nbsp; E-mail:</td>
                    <td align="left" valign="middle"><span class="detalle_medio">
                      <input name="mail" type="text" class="cuadro_login_texto" id="mail" style="width:200px;" value="<?= $rs_user['mail'] ?>" size="27" disabled="disabled">
                    </span></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="left" valign="middle">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* Calle:</td>
                    <td align="left" valign="middle"><input name="calle" type="text" class="cuadro_login_texto" id="calle" style="width:200px;" value="<?= $rs_user['calle'] ?>" /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* N&uacute;mero:</td>
                    <td align="left" valign="middle"><input name="numero" type="text" class="cuadro_login_texto" id="calle_numero" style="width:75px;" value="<?= $rs_user['calle_numero'] ?>"  /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">&nbsp;&nbsp; Depto:</td>
                    <td align="left" valign="middle"><input name="depto" type="text" class="cuadro_login_texto" id="depto" style="width:40px;" value="<?= $rs_user['depto'] ?>"  /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">&nbsp;&nbsp; Piso:</td>
                    <td align="left" valign="middle"><input name="piso" type="text" class="cuadro_login_texto" id="piso" style="width:40px;" value="<?= $rs_user['piso'] ?>"  /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* Cod. Postal: </td>
                    <td align="left" valign="middle"><input name="cp" type="text" class="cuadro_login_texto" id="cp" style="width:75px;" value="<?= $rs_user['cp'] ?>"  /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* Pa&iacute;s:</td>
                    <td align="left" valign="middle"><span class="detalle_a">
                      <select name="idpais" class="cuadro_login_texto" id="idpais" onchange="modificarUsuario();" style="width:200px">
                        <option value="0" <? if($idpais==0){ echo 'selected="selected"'; } ?>>--- Seleccione su pa&iacute;s</option>
                        <?
						 
				  $query_pais = "SELECT * 
				  FROM pais 
				  WHERE estado = 1 ORDER BY titulo ASC";
				  $result_pais = mysql_query($query_pais);
				  while($rs_pais = mysql_fetch_assoc($result_pais)){						  
					if ($rs_pais['idpais'] == $rs_user['idpais']){
						$idpais_sel = "selected";
					}else{					
						$idpais_sel = "";					
						if($rs_pais['idpais'] == $idpais_predeterminado && !$rs_user['idpais']){
							$idpais_sel = "selected";
						}						
					}	
			?>
                        <option <?= $idpais_sel ?> value="<?= $rs_pais['idpais'] ?>">
                        <?= $rs_pais['titulo'] ?>
                        </option>
                        <?  } ?>
                      </select>
                    </span></td>
                  </tr>
				  <? 
					if($rs_user['idpais']){
						$sel_prov = " AND idpais = ".$rs_user['idpais']." ";
					}else{
						$sel_prov = " AND idpais = 0";
					};
					
					$query_idpais_provincia = "SELECT * 
					FROM pais_provincia 
					WHERE estado = 1 $sel_prov ORDER BY titulo ASC";
					$result_idpais_provincia = mysql_query($query_idpais_provincia);
					$cant_prov = mysql_num_rows($result_idpais_provincia);	  
							
						if($cant_prov > 0){
					?>
                  <tr>
                    <td align="left" valign="middle">* Provincia:</td>
                    <td align="left" valign="middle"><span class="detalle_a">
					
                      <select name="idpais_provincia" class="cuadro_login_texto" id="idpais_provincia" style="width:200px">
                        <?
				 while ($rs_idpais_provincia = mysql_fetch_assoc($result_idpais_provincia)){	
				 	if ($rs_idpais_provincia['idpais_provincia'] == $rs_user['idpais_provincia']){
						$idpais_provincia_sel = "selected";
					}else{					
						$idpais_provincia_sel = "";						
					}						
			?>
                        <option <?=$idpais_provincia_sel?> value="<?= $rs_idpais_provincia['idpais_provincia'] ?>">
                        <?= $rs_idpais_provincia['titulo'] ?>
                        </option>
                        <?  } ?>
                      </select>
                    </span></td>
                  </tr>
				  <? } ?>
                  <tr>
                    <td align="left" valign="middle">* Localidad:</td>
                    <td align="left" valign="middle"><input name="localidad" type="text" class="cuadro_login_texto" id="localidad" style="width:200px;" value="<?= $rs_user['localidad'] ?>"  /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">&nbsp;</td>
                    <td align="left" valign="middle">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* Username:</td>
                    <td align="left" valign="middle"><input name="username" type="text" class="cuadro_login_texto" id="username" style="width:200px;" value="<?= $rs_user['username'] ?>" /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* Password</td>
                    <td align="left" valign="middle"><input name="password" type="password" class="cuadro_login_texto" id="password" style="width:200px;" value="<?= $rs_user['password'] ?>" /></td>
                  </tr>
                  <tr>
                    <td align="left" valign="middle">* Confirma Password: </td>
                    <td align="left" valign="middle"><input name="confirmar_password" type="password" class="cuadro_login_texto" id="confirmar_password" style="width:200px;" value="<?= $rs_user['password'] ?>" /></td>
                  </tr>
                </table></td>
                </tr>
              <tr>
                <td height="20" align="right" valign="middle" class="cuadro_login_texto"><a href="javascript:modificarUsuario();"><img src="imagen/botones/b-modificardomicilio.gif" width="72" height="10" border="0" /></a>&nbsp;&nbsp; </td>
              </tr>
            </table>
			</td>
          </tr>
        </table>
        </form>
		</td>
        <td width="30%" align="center" valign="top">
          <table width="100%" border="0" align="center" cellpadding="6" cellspacing="0" class="tabla_producto">
          <tbody>
            <tr>
              <td align="left" bgcolor="#F7F7F7"><span class="cuadro_login_texto"><strong><br />
                Disposici&oacute;n (Direcci&oacute;n Nacional de Protecci&oacute;n de Datos Personales) 10/2008</strong></span> <p> <span class="cuadro_login_texto">&quot;El titular de los datos personales tiene la facultad de ejercer el  derecho de acceso a los mismos en forma gratuita a intervalos no  inferiores a seis meses, salvo que se acredite un inter&eacute;s leg&iacute;timo al  efecto conforme lo establecido en el art&iacute;culo 14, inciso 3 de la Ley N&ordm;  25.326&quot; </span></p>
                <p class="cuadro_login_texto">&quot;La DIRECCION NACIONAL DE PROTECCION DE DATOS PERSONALES,  &oacute;rgano de control de la Ley N&ordm; 25.326, tiene la atribuci&oacute;n de atender  las denuncias y reclamos que se interpongan con relaci&oacute;n al  incumplimiento de las normas sobre protecci&oacute;n de datos personales&quot;.<br />
                  <br />
                </p></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
    </table>
  </div>
	<div id="barra_der_imagen"></div>
</div>
<div id="footer">
<? include("0_include/0_pie.php"); ?>
</div>
</body>
</html>