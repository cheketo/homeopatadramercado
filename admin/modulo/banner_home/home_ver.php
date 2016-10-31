<? include ("../../0_mysql.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	//Si el usuario pertenece a una sede
	if($HTTP_SESSION_VARS['idsede_log'] != 0){
		$idsede_log = $HTTP_SESSION_VARS['idsede_log'];
		$obj_value = 'disabled="disabled"';
		$filtro_sede = " AND B.idsede = '$idsede_log' ";
	}else{
		$filtro_sede = '';
		$obj_value = '';
	}
	
	//LOCALIZACION DE VARIABLES
	$ruta_banner = "../../../imagen/banner_home/";
	
	//ELIMINAR
	if($accion == "eliminar"){
	
		$idhome = $_POST['idhome'];
		$ididioma = $_POST['ididioma'];
	
		$query_preliminar = "SELECT * 
		FROM se_home
		WHERE idhome = '$idhome' AND ididioma = '$ididioma' ";
		$rs_preliminar = mysql_fetch_assoc(mysql_query($query_preliminar));
	
		if(file_exists($ruta_banner.$rs_preliminar['archivo'])){
			unlink($ruta_banner.$rs_preliminar['archivo']);
		};
		
		$query_eliminar = "DELETE FROM se_home WHERE idhome = '$idhome' AND ididioma = '$ididioma' ";
		mysql_query($query_eliminar);
		
		
		$query_pre = "SELECT * 
		FROM se_home
		WHERE idhome = '$idhome'";
		if(mysql_num_rows(mysql_query($query_pre)) == 0){
			$query_eliminar = "DELETE FROM se_home_sede WHERE idhome = '$idhome' ";
			mysql_query($query_eliminar);
		}
	
	};
	
	
	//CAMBIAR ESTADO
	if($accion == "cambiar_estado"){
	
		$estado = $_POST['estado'];
		$idhome = $_POST['idhome'];
		$ididioma = $_POST['ididioma'];
		
		$query_estado = "UPDATE se_home SET estado = '$estado'
		WHERE idhome = '$idhome' AND ididioma = '$ididioma'
		LIMIT 1";
		mysql_query($query_estado);

	}		
	

	$colores = array("#fff0e1","#FFE1C4");
	$cont_colores = 0;
	$hay_lista = false;
	$query_lista = "SELECT DISTINCT A.*
	FROM se_home A
	INNER JOIN se_home_sede B ON A.idhome = B.idhome
	WHERE A.estado <> 3 $filtro_sede
	ORDER BY A.orden";
	$result_lista = mysql_query($query_lista);
	
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_tips.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/0_mootools.js"></script>

<? include("../../0_head.php"); ?>

<script language="javascript">

	function cambiar_estado(estado, ididioma, idhome){
	formulario = document.form_banner;
	
	formulario.estado.value = estado;
	formulario.idhome.value = idhome;
	formulario.ididioma.value = ididioma;
	
	formulario.accion.value = "cambiar_estado";
	
	formulario.submit();
	};
	
	function confirmar_eliminar(idhome, ididioma){
		if (confirm('¿Está seguro que desea borrar el banner home?')){
			var formulario = document.form_banner;
			
			formulario.ididioma.value = ididioma;
			formulario.idhome.value = idhome;
			formulario.accion.value = "eliminar";
			formulario.submit();
		}
	};

	window.addEvent('domready', function(){
		//TIPS	
		var Tips1 = new Tips($$('.Tips1'), {
			initialize:function(){
				this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 200, wait: false}).set(0);
			},
			onShow: function(toolTip) {
				this.fx.start(1);
			},
			onHide: function(toolTip) {
				this.fx.start(0);
			}
		});
	});
	
</script>
</head>
<body>
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Banner Home  - Ver </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
			<form action="" method="post" enctype="multipart/form-data" name="form_banner">
				<input name="idhome" type="hidden" id="idhome" value="" />
                <input name="ididioma" type="hidden" id="ididioma" value="" />
                <input name="estado" type="hidden" id="estado" value="" />
                <input name="accion" type="hidden" id="accion" value="" />
              <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                <tr bgcolor="ffddbc">
                  <td width="4%" height="40" align="center" bgcolor="#ffddbc" class="detalle_medio_bold">ID</td>
                  <td width="12%" height="40" bgcolor="#ffddbc" class="detalle_medio_bold">N&ordm; de Orden </td>
                  <td width="57%" height="40" align="left" bgcolor="#ffddbc" class="detalle_medio_bold">Banner</td>
                  <td width="27%" height="40" align="center" bgcolor="#ffddbc" class="detalle_medio_bold">&nbsp;</td>
                </tr>
                <? 
					while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
						$hay_lista = true;
				?>
                <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                  <td width="4%" align="center" valign="top" class="detalle_11px"><span><a name="<?= $rs_lista['idhome']; ?>" id="<?= $rs_lista['idhome']; ?>"></a>
                        <?=$rs_lista['idhome']?>.
                  </span></td>
                  <td align="center" valign="top" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><strong><?=$rs_lista['orden']?>
                  </strong></td>
                  <td align="left" valign="top" bgcolor="<?=$colores[$cont_colores]?>"><table border="2" cellpadding="1" cellspacing="0" bordercolor="#669966" bgcolor="#669966">
                    <tr>
                      <td style="border:1px solid #669966;"><? $imagen =& new obj0001('0',$ruta_banner,$rs_lista['archivo'],'','100','','','','','','',''); ?></td>
                    </tr>
                  </table>
                    <?
					 	if($rs_lista['detalle']){
							   echo "<br>".$rs_lista['detalle'];
						}	   
					?>
						   
				</td>
                  <td align="center" valign="top" bgcolor="<?=$colores[$cont_colores]?>">
				    <table width="100%" border="0" cellpadding="2" cellspacing="0" >
                    <tr>
                      <td width="50%" align="left" class="detalle_chico">Propiedades</td>
                      <td width="50%" align="right" class="detalle_chico">Opciones</td>
                    </tr>
                    <tr>
                      <td align="left">
					  	<? 
						if ($rs_lista['estado'] == '1') { 
                        	echo '<a href="javascript:cambiar_estado(2,'.$rs_lista['ididioma'].','.$rs_lista['idhome'].');"><img src="../../imagen/b_habilitado.png" alt="Habilitado" width="15" height="16" border="0" /></a>';
                        } else {
                        	echo '<a href="javascript:cambiar_estado(1,'.$rs_lista['ididioma'].','.$rs_lista['idhome'].');"><img src="../../imagen/b_deshabilitado.png" alt="Deshabilitado" width="16" height="15" border="0" /></a>';
                        } ?>  
				                  &nbsp;<img src="../../imagen/iconos/sede.png" width="14" height="20" class="Tips1" title="Sucursales :: <?
								  $query_sede = "SELECT DISTINCT C.titulo
								  FROM se_home A
								  INNER JOIN se_home_sede B ON A.idhome = B.idhome
								  INNER JOIN sede C ON B.idsede = C.idsede
								  WHERE B.idhome = '$rs_lista[idhome]'
								  ORDER BY C.idsede";
								  $result_sede = mysql_query($query_sede);
								  $vacio=true;
								  while($rs_sede = mysql_fetch_assoc($result_sede)){
								  	$vacio=false;
								  	echo "&bull; ".$rs_sede['titulo']."<br>";
								  }
								  if($vacio == true){ echo "No hay sucursales definidas.";}
								  ?>" /></td>
                      <td height="25" align="right" valign="middle"><a href="home_editar.php?idhome=<?= $rs_lista['idhome'] ?>&ididioma=<?= $rs_lista['ididioma'] ?>" target="_parent" class="style10"><img src="../../imagen/b_edit.png" alt="Modificar" width="16" height="16" border="0" /></a> <a href="titular_editar.php?idtitular=<?= $rs_lista['idtitular'] ?>&amp;ididioma=<?= $rs_lista['ididioma'] ?>"></a>&nbsp;<a href="javascript:confirmar_eliminar(<?= $rs_lista['idhome'] ?>,<?= $rs_lista['ididioma'] ?>)" class="style10"><img src="../../imagen/cruz.png" alt="Eliminar" width="16" height="16" border="0" /></a></td>
                    </tr>
                    </table>
				  <hr size="1" class="detalle_medio" />
                  <table width="100%" border="0" cellpadding="2" cellspacing="0" >
                    <tr>
                      <td width="30%" class="detalle_medio">Idioma:</td>
                      <td width="70%" align="right" style="color:#006699"><b>
                        <?
						   $query_idioma = "SELECT titulo_idioma
						   FROM idioma 
						   WHERE ididioma = '$rs_lista[ididioma]'
						   LIMIT 1";
						   $rs_idioma = mysql_fetch_assoc(mysql_query($query_idioma));
						   echo $rs_idioma['titulo_idioma'];
						?>
                      </b></td>
                    </tr>
                  </table></td>
                </tr>
                
                <?
				if($cont_colores == 0){
					$cont_colores = 1;
				}else{
					$cont_colores = 0;
				};
								
				} if($hay_lista == false){ 
				?>
				<tr align="center" valign="middle">
				  <td colspan="4" bgcolor="#fff0e1" height="50" class="detalle_medio_bold">No se han encontrado banners home.</td>
				</tr>
                <? }; ?>
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