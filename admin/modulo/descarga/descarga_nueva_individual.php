<? include ("../../0_mysql.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? 
	
	include ("../0_includes/0_clean_string.php"); 

	//localizacion de variables get y post:	
	$accion = $_POST['accion'];

	$ruta_descarga = "../../../descarga/";
	
	$titulo = $_POST['titulo'];
	$archivo = $_POST['archivo'];
	$idtipo_descarga = $_POST['idtipo_descarga'];
	
	$tipo_mostrar = $_GET['tipo_mostrar'];
	
	$idproducto = $_GET['idproducto'];
	$idseccion = $_GET['idseccion'];
	$idcarpeta = $_GET['idcarpeta'];
	$msj = "";
	
	//TOMO PARAMETROS DE LA DESCARGA
	$query_par = "SELECT *
	FROM descarga_parametro
	WHERE iddescarga_parametro = 1";
	$rs_parametro = mysql_fetch_assoc(mysql_query($query_par));
	
	if($rs_parametro['usa_prefijo'] == 1){
		$prefijo = $rs_parametro['prefijo'].'-';
	}else{
		$prefijo = "";
	}
	

	//INGRESO LA DESCARGA
	if($accion == "ingresar"){
		
		// INCORPORACION DE FOTO		
		if ($_FILES['archivo']['name'] != ''){
		
			$archivo_ext = substr($_FILES['archivo']['name'],-4);
			$archivo_nombre = substr($_FILES['archivo']['name'],0,strrpos($_FILES['archivo']['name'], "."));
			
			$archivo = clean_string($archivo_nombre) . $archivo_ext;
			$archivo = strtolower($archivo);
		
			$archivo = $prefijo.$archivo;
			if($rs_parametro['usa_random'] == 1){
				$archivo .= '('.rand(0,999).')';
			}
			
			if (!copy($_FILES['archivo']['tmp_name'], $ruta_descarga.$archivo)){ //si hay error en la copia de la foto
				echo "<script>alert('Hubo un error al subir el archivo. ')</script>"; // se muestra el error.
			}else{

				if($_FILES['archivo']['size'] > 0){
				
					$query_ingreso = "INSERT INTO descarga (
					  titulo
					, idtipo_descarga
					, archivo
					, idcarpeta
					, idseccion
					, idproducto
					, restringido
					, estado
					) VALUES (
					  '$titulo'
					, '$idtipo_descarga'
					, '$archivo'
					, '$idcarpeta'
					, '$idseccion'
					, '$idproducto'
					, '$rs_parametro[valor_restringido]'
					, '1'
					)";
					mysql_query($query_ingreso);
					
				}else{
					
					echo "<script>alert('El archivo fue subido incorrectamente.')</script>"; // se muestra el error.
					
					if(!unlink($ruta_descarga.$archivo)){ //se elimina el archivo subido
						echo "<script>alert('El archivo no pudo elminarse.')</script>";
					}else{
						echo "<script>alert('El archivo fue elminado.')</script>"; 
					}
					
				}
		
			}
		};//FIN FOTO
		
		$msj = "La descarga fue ingresada correctamente. Si desea incorporar una nueva complete nuevamente los siguiente campos, sino cierre esta ventana y presione el boton para actualizar la pantalla.";
		
	};


?>

<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_descarga(){
		var formulario = document.form;
		var flag = true;
		var error = "";
		
		if(formulario.titulo.value == ''){
			error = "Debe seleccionar el titulo.\n";
			flag = false;
		}
		
		if(formulario.archivo.value == ''){
			error = error + "Debe seleccionar el archivo.\n";
			flag = false;
		}
			
		if(flag == true){
			
			document.getElementById('load_div').style.display = 'block';
			document.getElementById('tr_titulo').style.display = 'none';
			document.getElementById('tr_tipo').style.display = 'none';
			document.getElementById('tr_archivo').style.display = 'none';
			document.getElementById('tr_cargar').style.display = 'none';
		
			formulario.accion.value = "ingresar";
			formulario.submit();
		}else{
			alert(error);
		}
	
	};
	
</script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #E1EDFF;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#999999">
                          <td height="40" bgcolor="#9DBFFF" class="titulo_medio_bold" >&nbsp;Ingresar nueva  Descarga <span class="style2">
                            <input name="accion" type="hidden" id="accion" value="" />
                          </span></td>
                        </tr>
                        <tr bgcolor="#999999">
                          <td bgcolor="#E1EDFF">
                              <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0">
							  <? if($msj){ ?>
                                <tr>
                                  <td width="77" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td width="571" valign="top" class="detalle_medio_bold"><?= $msj ?></td>
                                </tr>
								<? } ?>
                                <tr id="tr_titulo">
                                  <td align="right" valign="middle" class="detalle_medio">Titulo:</td>
                                  <td width="571" valign="top" class="style2"><input name="titulo" type="text" class="detalle_medio" id="titulo" size="60" maxlength="60" /></td>
                                </tr>
                                <tr id="tr_tipo">
                                  <td width="77" align="right" valign="middle" class="detalle_medio">Tipo:</td>
                                  <td valign="top" class="style2">
								  <select name="idtipo_descarga" class="detalle_medio" id="idtipo_descarga" style="width:200px;">
                                    <option value="0">- Seleccionar Tipo</option>
									<?
									$query = "SELECT * 
									FROM descarga_tipo
									WHERE estado = 1";
									$result = mysql_query($query);
									while($rs_query = mysql_fetch_assoc($result)){
									?>
									<option value="<?= $rs_query['iddescarga_tipo'] ?>"><?= $rs_query['titulo'] ?></option>
									<? } ?>
                                  </select></td>
                                </tr>
                                <tr id="tr_archivo">
                                  <td width="77" align="right" valign="middle" class="detalle_medio">Archivo:</td>
                                  <td valign="top" class="style2"><input name="archivo" type="file" class="detalle_medio" id="archivo" size="44" /></td>
                                </tr>
                                <tr id="tr_cargar">
                                  <td width="77" align="right" valign="middle" class="detalle_medio">&nbsp;</td>
                                  <td valign="top" class="style2"><input name="Submit" type="button" class="detalle_medio_bold" id="Submit" onclick="javascript:validar_descarga();" value=" Cargar &raquo; " /></td>
                                </tr>
                                <tr id="load_div" style="display:none">
                                  <td height="10" valign="top" class="style2">&nbsp;</td>
                                  <td height="10" align="left" valign="middle" class="style2"><div class="detalle_medio" ><img src="../../imagen/progress-bar.gif" width="220" height="19" /><br />&nbsp;&nbsp;Loading</div></td>
                                </tr>
                          </table></td>
                        </tr>
                    </table>
                    </form></td>
  </tr>
</table>
</body>
</html>
