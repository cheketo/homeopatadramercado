<?
session_register("usuario");
session_register("idcusuario_perfil");
	//UNSET COOKIE
	setcookie("inicio","",0);
	//FIN SETEAR COOKIE
$usuario = 0;
$idcusuario_perfil = 0;
header('Location: index.php');
?>