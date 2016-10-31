<?
	session_name("ideas2_nombre_cliente");
	session_start();
	//session_destroy();
	
	//UNSET COOKIE
	setcookie("login_web","",0);
	//FIN SETEAR COOKIE
	
	$_SESSION['iduser_web_session']='';
	$_SESSION['mail_session']='';
	$_SESSION['nombre_session']='';
	
	echo "<script>document.location.href = 'index.php';</script>";
	//header("Location: index.php");
	
?>