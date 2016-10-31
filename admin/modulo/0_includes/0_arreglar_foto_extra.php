<?
//hay que detectar si funciona la web, si no funciona mandar a web de mantenimiento
if($_SERVER["HTTP_HOST"] == 'server:82'){
	$user = "";
	$passwd = "";
	$base = "diemer";
	mysql_connect("localhost","$user" ,"$passwd")  or die("No conecta a la base de datos"); 
	mysql_select_db("$base") or die( "No selecciona la base de datos");
}else{
	$user = "ideas2n_ideas2";
	$passwd = "pato";
	$base = "ideas2n_diemer";
	mysql_connect("localhost","$user" ,"$passwd")  or die("No conecta a la base de datos"); 
	mysql_select_db("$base") or die( "No selecciona la base de datos");
}
//carga de scripts:
//require_once("0_scripts.php");

if( $_GET['accion'] == "arreglar_producto" ){

	$carpeta_mediana_actual = "../imagen/producto/mediana/";
	$carpeta_chica_actual = "../imagen/producto/chica/";
	$carpeta_grande = "../imagen/producto/extra_grande/";
	$carpeta_chica = "../imagen/producto/extra_chica/";
	
	$query_extras = "SELECT * FROM producto_foto ";
	$result_extras = mysql_query($query_extras);
	while( $rs_extras = mysql_fetch_assoc($result_extras) ){

		copy($carpeta_mediana_actual.$rs_extras['foto'],$carpeta_grande.$rs_extras['foto']);
		copy($carpeta_chica_actual.$rs_extras['foto'],$carpeta_chica.$rs_extras['foto']);
		
		unlink($carpeta_mediana_actual.$rs_extras['foto']);
		unlink($carpeta_chica_actual.$rs_extras['foto']);
		
	};
	
};

if( $_GET['accion'] == "arreglar_seccion" ){

	$carpeta_mediana_actual = "../imagen/seccion/mediana/";
	$carpeta_chica_actual = "../imagen/seccion/chica/";
	$carpeta_grande = "../imagen/seccion/extra_grande/";
	$carpeta_chica = "../imagen/seccion/extra_chica/";
	
	$query_extras = "SELECT * FROM seccion_foto ";
	$result_extras = mysql_query($query_extras);
	while( $rs_extras = mysql_fetch_assoc($result_extras) ){

		copy($carpeta_mediana_actual.$rs_extras['foto'],$carpeta_grande.$rs_extras['foto']);
		copy($carpeta_chica_actual.$rs_extras['foto'],$carpeta_chica.$rs_extras['foto']);
		
		unlink($carpeta_mediana_actual.$rs_extras['foto']);
		unlink($carpeta_chica_actual.$rs_extras['foto']);
		
	};
	
};

?>