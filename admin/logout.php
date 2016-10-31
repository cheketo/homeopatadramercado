<?
	session_name("dreamsindesign_descubritelo_admin");
	session_start();
	unset($GLOBALS['$HTTP_SESSION_VARS[usuario_log]'], $GLOBALS['$HTTP_SESSION_VARS[idcusuario_perfil_log]'], $GLOBALS['$HTTP_SESSION_VARS[idsede_log]'], $GLOBALS['$_SESSION[iduser_admin_session]'], $GLOBALS['$_SESSION[nombre_session]']);
	session_destroy();
	$HTTP_SESSION_VARS['usuario_log']="";
	
	
	
	//UNSET COOKIE
	setcookie("inicio","",0);
	
	
	//FIN SETEAR COOKIE
	echo "<script>document.location.href=('index.php')</script>";
	//header("Location: index.php");
?>