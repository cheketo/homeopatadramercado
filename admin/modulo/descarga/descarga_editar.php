<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?
	
	//VARIABLES
	$accion = $_POST['accion'];
	$iddescarga = $_GET['iddescarga'];
	$titulo = $_POST['titulo'];
	$idtipo_descarga = $_POST['idtipo_descarga'];
	$descripcion = $_POST['descripcion'];
	$archivo = $_POST['archivo'];
	
	//GUARDAR CAMBIOS
	if($accion == "guardar_cambios"){
		
		if($archivo){
			$filtro_archivo = " , archivo = '$archivo' ";
		}else{
			$filtro_archivo = "";
		}
		
		//INGRESO
		$query_upd = "UPDATE descarga SET
		  titulo = '$titulo'
		, idtipo_descarga = '$idtipo_descarga'
		, descripcion = '$descripcion' 
		$filtro_archivo
		WHERE iddescarga = '$iddescarga'
		LIMIT 1";
		mysql_query($query_upd);
	
	}//FIN
	
	//CONSULTA
	$query_descarga = "SELECT A.*
	FROM descarga A
	WHERE A.iddescarga = '$iddescarga'";
	$rs_descarga = mysql_fetch_assoc(mysql_query($query_descarga));
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>
<script type="text/javascript">
	
	function guardar_cambios(){
		var formulario = document.form;
		var flag = true;
		
		if(formulario.titulo.value == ""){
			alert("Debe introducir el titulo.");
			flag = false;
		}
			
		if(flag == true){
			formulario.accion.value = "guardar_cambios";
			formulario.submit();
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
                <td height="40" valign="bottom" class="titulo_grande_bold"> Descarga - Editar </td>
              </tr>
              <tr>
                <td valign="top" class="titulo_grande_bold"><hr size="1" noshade style="color:#cccccc;"></td>
              </tr>
            </table>
              <form action="" method="post" enctype="multipart/form-data" name="form" id="form">
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                  <tr bgcolor="#ffddbc">
                    <td height="40" align="left" bgcolor="#FFE9D2" class="titulo_medio_bold">Editar datos de la descarga                 
                      <input name="accion" type="hidden" id="accion" /></td>
                  </tr>
                  <tr valign="middle" bgcolor="#fff0e1" height="50">
                    <td align="left" bgcolor="#fff0e1" ><table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="105" align="right" class="detalle_medio">Titulo:</td>
                            <td width="553">
                              <input name="titulo" type="text" style="width:99%" class="detalle_medio" id="titulo" size="70" value="<?= $rs_descarga['titulo'] ?>" />                          </td>
                        </tr>
						<? if($HTTP_SESSION_VARS['idcusuario_perfil_log'] == 1){ ?>
                        <tr>
                          <td width="105" align="right" class="detalle_medio">Nombre archivo:</td>
                          <td><input name="archivo" type="text" style="width:99%" class="detalle_medio" id="archivo" size="70" value="<?= $rs_descarga['archivo'] ?>" /></td>
                        </tr>
						<? } ?>
                      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
					      <tr>
					        <td width="105" align="right" class="detalle_medio">Tipo:</td>
                            <td width="553"><label><span class="style2">
                            <select name="idtipo_descarga" class="detalle_medio" id="idtipo_descarga" style="width:200px;">
                              <option value="0">- Seleccionar Tipo</option>
                              <?
									$query = "SELECT * 
									FROM descarga_tipo
									WHERE estado = 1";
									$result = mysql_query($query);
									while($rs_query = mysql_fetch_assoc($result)){
									?>
                              <option value="<?= $rs_query['iddescarga_tipo'] ?>" <? if($rs_query['iddescarga_tipo'] == $rs_descarga['idtipo_descarga']){ echo "selected"; } ?>>
                                <?= $rs_query['titulo'] ?>
                              </option>
                              <? } ?>
                            </select>
                            </span></label></td>
                          </tr>
				      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="105" align="right" valign="top" class="detalle_medio">Descripci&oacute;n:</td>
                            <td width="553">
                              <textarea name="descripcion" style="width:99%; height:80px;" class="detalle_medio" id="descripcion"><?= $rs_descarga['descripcion'] ?></textarea>
                          </td>
                        </tr>
                      </table>
					    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="105" align="right" class="detalle_medio">&nbsp;</td>
                            <td width="553"><label>
                            <input name="Button" type="button" class="detalle_medio_bold" value=" Guardar Cambios &raquo;" onclick="javascript:guardar_cambios()"/>
                          </label></td>
                        </tr>
                      </table>
				    </td>
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