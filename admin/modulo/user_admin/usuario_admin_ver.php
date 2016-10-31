<? 

	//INCLUDES
	include ("../../0_mysql.php");
    
	//VARIABLES
	$accion = $_POST['accion'];
	$iduser_admin = $_GET['iduser_admin'];
	$eliminar = $_GET['eliminar'];
	$intentos = $_GET['intentos'];
	
	//ELIMINAR
	if(isset($_GET['eliminar'])){
		
		$query_eliminar = "DELETE 
		FROM user_admin 
		WHERE iduser_admin = '$eliminar' ";
		mysql_query($query_eliminar);

		echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."');</script>";

	};
	
	//BLANQUEAR
	if($intentos=='1'){
		
		$query_estado = "UPDATE user_admin 
		SET intentos = '0'
		WHERE iduser_admin = $iduser_admin
		LIMIT 1 ";
		mysql_query($query_estado);
		
		//echo "<script>window.location.href=('".$_SERVER['PHP_SELF']."')</script>";
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

	function eliminar(id, mail){
		if (confirm('¿ Está seguro que desea eliminar al usuario '+mail+' ?')){
			window.location.href=('<?=$_SERVER['PHP_SELF']?>?eliminar='+id+'<?=$busqueda_sel?>');
		}
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
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Usuario Admin  - Lista </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_lista" id="form_lista">
                      <span class="detalle_chico" style="color:#FF0000">
                      <input name="accion" type="hidden" id="accion" value="1" />
                      <span class="detalle_chico" style="color:#FF0000">
                      <input name="lista_fil" type="hidden" id="lista_fil" value="1" />
                      </span><span class="detalle_chico" style="color:#FF0000">
                      <input name="lista_orden" type="hidden" id="lista_orden" value="1" />
                      </span></span>
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr bgcolor="#ffddbc">
                          <td width="4%" height="40" align="left" valign="middle" class="detalle_medio_bold">ID</td>
                          <td width="20%" height="40" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">Usuario</td>
                          <td width="29%" height="40" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">Perfil</td>
                          <td width="21%" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">Sucursal</td>
                          <td width="18%" height="40" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">Intentos</td>
                          <td height="40" colspan="2" align="left" valign="middle" bgcolor="ffddbc" class="detalle_medio_bold">&nbsp;</td>
                        </tr>
                        <?
	if(($lista_fil<>1)&&($lista_fil)){
		$lista_order_by_fil = $lista_fil;
		$lista_order_by_fil .= " ".$lista_orden;
	}else{
		$lista_order_by_fil = "iduser_admin";
	}
	
	//sistema de busqueda	
	$cont_usuarios = 0;
	$colores = array("#fff0e1","#FFE1C4");
	$cont_colores = 0;
	$hay_lista = false;
	$query_lista = "SELECT A.*, B.titulo AS titulo_perfil
	FROM user_admin A
	LEFT JOIN user_admin_perfil B ON B.iduser_admin_perfil = A.iduser_admin_perfil
	WHERE A.iduser_admin_perfil <> 1
	ORDER BY $lista_order_by_fil";
	$result_lista = mysql_query($query_lista);
	while ($rs_lista = mysql_fetch_assoc($result_lista)){ 
		$hay_lista = true;
		$cont_usuarios++;
?>
                        <tr valign="middle" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
                          <td width="4%" align="center" valign="top" class="detalle_chico"><?=$rs_lista['iduser_admin']?>
                              <input name="iduser_web_row[<?= $cont_usuarios ?>]" type="hidden" id="iduser_web_row[<?= $cont_usuarios ?>]" value="<?= $rs_lista['iduser_admin'] ?>" />
                          <a name="ancla_<?=$rs_lista['iduser_admin']?>" id="ancla_<?=$rs_lista['iduser_admin']?>"></a></td>
                          <td valign="top" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['usuario']?>
                            <a href="usuario_web_editar.php?iduser_web=<?= $rs_lista['iduser_admin'] ?>" target="_parent" class="style10"></a></td>
                          <td valign="top" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['titulo_perfil']?></td>
                          <td valign="top" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio">
						  <? 
						  
						  if($rs_lista['idsede'] != 0){
						  	$query_sede  = "SELECT titulo 
							FROM sede 
							WHERE estado = '1' AND idsede = '$rs_lista[idsede]'
							ORDER BY orden";
							$result_sede  = mysql_fetch_assoc(mysql_query($query_sede));
							echo $result_sede['titulo'];
						  }else{
						  	echo "Todas";
						  }
						
						  ?></td>
                          <td valign="top" bgcolor="<?=$colores[$cont_colores]?>" class="detalle_medio"><?=$rs_lista['intentos']?>
                            - <a href="<?= $_SERVER['PHP_SELF']."?iduser_admin=".$rs_lista['iduser_admin']."&intentos=1";?>">Blanquear</a> </td>
                          <td width="4%" align="center" valign="top" bgcolor="<?=$colores[$cont_colores]?>"><a href="usuario_admin_editar.php?iduser_admin=<?=$rs_lista['iduser_admin']?>"><img src="../../imagen/b_edit.png" width="16" height="16" border="0" /></a></td>
                          <td width="4%" align="center" valign="top" bgcolor="<?=$colores[$cont_colores]?>"><a href="javascript:eliminar(<?=$rs_lista['iduser_admin']?>,'<?=$rs_lista['usuario']?>');" class="style10"><img src="../../imagen/trash.png" alt="Eliminar" width="15" height="16" border="0" /></a></td>
                        </tr>
                        <?
		if($cont_colores == 0){
			$cont_colores = 1;
		}else{
			$cont_colores = 0;
		};
					
	} if($hay_lista == false){ ?>
                        <tr align="center" valign="middle">
                          <td colspan="11" bgcolor="#fff0e1"  height="50" class="detalle_medio_bold">No se han encontrado usuarios.</td>
                        </tr>
                        <? };	?>
                        <tr align="center" valign="middle" height="50">
                          <td colspan="9" bgcolor="#FFFFFF" class="detalle_medio">Cantidad de usuarios encontrados:
                            <?= $cont_usuarios ?></td>
                        </tr>
                        <?
	////////////////////////////////////////////////
	////Final del Modulo Admin de lista filtrado////
	////////////////////////////////////////////////
						?>
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