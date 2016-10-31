<?
	session_name("dreamsindesign_elpanal_admin");
	session_start(); 	
	session_cache_expire(10800);//es para que no expire la session, VERIFICAR EL FUNCIONAMIENTO, SOLO PARA LA PARTE ADMIN

	if($_SERVER["HTTP_HOST"] != 'localhost' && $_SERVER["HTTP_HOST"] != 'server' ){
		$mysql_user = "az000455";
		$mysql_password = "teno61SUsa";
		$mysql_base = "az000455_homeopatamercado";
		$mysql_host = "localhost";	
		$dominio = "http://www.homeopatadramercado.com.ar/"; //poner la barra al final ej: http://www.didstudio.com.ar/
		
	}else if($_SERVER["HTTP_HOST"] == 'server'){
		$mysql_user = "root";
		$mysql_password = "";
		$mysql_base = "homeopatamercado";
		$mysql_host = "localhost";
		$dominio = "http://server/elpanal/web/";//poner la barra al final ej: http://www.didstudio.com.ar/
		
	}else{
		$mysql_user = "";
		$mysql_password = "";
		$mysql_base = "homeopatamercado";
		$mysql_host = "localhost";
		$dominio = "http://localhost/elpanal/web/";//poner la barra al final ej: http://www.didstudio.com.ar/
	}

	//$dominio_name = "http://".$_SERVER["HTTP_HOST"].substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"))."/";
	$dominio_name = "http://serverb/00modulo_css/";

//Loguea al usuario en la base de datos
$mysql_link = mysql_connect($mysql_host,$mysql_user,$mysql_password) or die ("<script>document.open('0_mysql_error.php?error=1','_self');</script>");

//Establece la conexion con la base de datos
$mysql_db_selected = mysql_select_db($mysql_base,$mysql_link) or die ("<script>document.open('0_mysql_error.php?error=2','_self');</script>");

	//OBJETO 0001
	if(file_exists("modulo/0_includes/obj0001.php")){
		include_once("modulo/0_includes/obj0001.php"); 
	}else if(file_exists("../../modulo/0_includes/obj0001.php")){
		include_once("../../modulo/0_includes/obj0001.php"); 
	}
	
	if(!isset($clogin) && $clogin!=true){
		
		//chequeo de sesion
		if( !$HTTP_SESSION_VARS['usuario_log'] ){
			echo "<script>alert('Debes estar logueado para entrar a esta sección.');</script>";
			if (file_exists("clogout.php")){
				echo "<script>document.location.href=('clogout.php')</script>";
			}else if(file_exists("../../clogout.php")){
				echo "<script>document.location.href=('../../clogout.php')</script>";
			}
			/* Asegurarse de que no se ejecute el codigo adicional cuando se redireccione. */
			exit;
		};
	}

	//Da los datos del sitio, como ser el titulo, las palabras claves etc 
	$query_dato_sitio = "SELECT A.*
	FROM dato_sitio  A
	WHERE ididioma = '1' ";	
	$rs_dato_sitio = mysql_fetch_assoc(mysql_query($query_dato_sitio));	
	
?>