<?php

class obj0005{

	//Variables
	var $idba_lugar = 0;

	//Variables extras
	var $ruta = 0;
	var $fecha_hora_actual = 0;
	

	function obj0005($idba_lugar){
	
		//Variable de IP
		$ip_navegacion = getenv("REMOTE_ADDR");
		
		//ruta donde se guardan los banners
		$ruta = 'imagen/banner/';
		
		//registra fecha y hora actual
		$fecha_hora_actual = date("Y-m-d H:i:s", time());
		
		//da la fecha actual, sirve para determinar la fecha para la fecha de activacion del titular o bien la fecha de baja del titular.
		$fecha_actual = date("Y-m-d");

		
		//selecciona aleatoriamente un banner para la posicion dada, y segun categoria, seccion y producto.
		$query_aleatorio_obj0005 = "SELECT *
		FROM ba_banner  A
		INNER JOIN ba_banner_sede B ON A.idba_banner = B.idba_banner
		WHERE A.estado = 1 AND A.ididioma = '$_SESSION[ididioma_session]' AND B.idsede = '$_SESSION[idsede_session]' AND A.archivo != '' AND A.idba_lugar = $idba_lugar AND A.fecha_alta <= '$fecha_actual' AND (A.fecha_baja >= '$fecha_actual' OR A.fecha_baja = '0000-00-00') 
		ORDER BY RAND()
		LIMIT 1 ";
		$result_aleatorio_obj0005 = mysql_query($query_aleatorio_obj0005);
		$rs_aleatorio_obj0005 = mysql_fetch_assoc($result_aleatorio_obj0005);
		$rs_num_aleatorio_obj0005 = mysql_num_rows($result_aleatorio_obj0005);
		
		if($rs_num_aleatorio_obj0005 >= 1){ //Si encuentra un banner. Si no se hace esta comprobacion cuando no haya un banner va a registrar una vista en la tabla ba_login.

			//intruduce el banner.
			$banner =& new obj0001('0',$ruta,$rs_aleatorio_obj0005['archivo'],'','','','',$rs_aleatorio_obj0005['link'],$rs_aleatorio_obj0005['target'],'','opaque',''); 

		
		}
				
	}//fin funcion

};//fin objeto ?>