<?php

class obj0005{

	//Variables
	var $idba_lugar = 0;
	var $idcategoria = 0;
	var $idproducto = 0;
	var $idseccion = 0;

	//Variables extras
	var $ruta = 0;
	var $fecha_hora_actual = 0;
	

	function obj0005($idba_lugar,$idcategoria,$idproducto,$idseccion){
	
		//Variable de IP
		$ip_navegacion = getenv("REMOTE_ADDR");
		
		//ruta donde se guardan los banners
		$ruta = 'imagen/banner/';
		
		//registra fecha y hora actual
		$fecha_hora_actual = date("Y-m-d H:i:s", time());
		
		//da la fecha actual, sirve para determinar la fecha para la fecha de activacion del titular o bien la fecha de baja del titular.
		$fecha_actual = date("Y-m-d");

		//esta variable sirve para filtrar en la consulta
		$filtro = "";
		
	if($idproducto || $idcategoria || $idseccion){
	  $error_ba_generico = 0;	
		if ($idproducto != 0){
			//Se fija que haya un banner asociado con esa seccion.
			//En caso de encontrarlo: lo filtra
			//En caso de NO encontrarlo: toma un banner generico
			$query_producto="SELECT * FROM ba_banner WHERE idproducto = '$idproducto'";
			$rs_producto=mysql_num_rows(mysql_query($query_producto));
			
			if($rs_producto > 0){
			$filtro .= " AND idproducto = '$idproducto'"; 
			//echo("Se encontro producto ");
			} else {
			$error_ba_generico = 1;//se genera variable de error para solicitar banner generico
			//echo("NO se encontro producto ");
			}
		}; 
		
		if ($idcategoria != 0){
			//Se fija que haya un banner asociado con esa seccion.
			//En caso de encontrarlo: lo filtra
			//En caso de NO encontrarlo: toma un banner generico donde cat, seccion y prod sean 0 (cero)
			$query_categoria="SELECT * FROM ba_banner WHERE idcategoria = '$idcategoria'";
			$rs_categoria=mysql_num_rows(mysql_query($query_categoria));
			
			if($rs_categoria > 0){
			$filtro .= " AND idcategoria = '$idcategoria'"; 
			//echo("Se encontro categoria ");
			} else {
			$error_ba_generico = 1;//se genera variable de error para solicitar banner generico
            //echo("NO se encontro categoria ");
	        }
		};
		
		if ($idseccion != 0){
			//Se fija que haya un banner asociado con esa seccion.
			//En caso de encontrarlo: lo filtra
			//En caso de NO encontrarlo: toma un banner generico
			$query_seccion="SELECT * FROM ba_banner WHERE idseccion = '$idseccion'";
			$rs_seccion=mysql_num_rows(mysql_query($query_seccion));
			
			if($rs_seccion > 0){
			$filtro .= " AND idseccion = '$idseccion'"; 
			//echo("Se encontro seccion ");
			} else {
			$error_ba_generico = 1; //se genera variable de error para solicitar banner generico
			//echo("NO se encontro seccion "); 
			}
	    };
		
		//Si no se encontro idseccion, ni idproducto ni idcategoria que se asocien con un banner cargamos genericos.
		if($error_ba_generico == 1){//Existe idcategoria o idseccion o idproducto, pero no corresponden a un banner.
		$filtro = " AND idproducto = 0 AND idcategoria = 0 AND idseccion = 0";
		} 
		
		//Comprueba que esten bien tomados los datos de seccion, producto y categoria.
		//echo('Sec:'.$idseccion.' Cat:'.$idcategoria.' Prod:'.$idproducto.'<br>');
			
		}else {//Si no existe ni idcategoria, ni idproducto ni idseccion
		$filtro = " AND idproducto = 0 AND idcategoria = 0 AND idseccion = 0";
		}
		

		
		
		//selecciona aleatoriamente un banner para la posicion dada, y segun categoria, seccion y producto.
		$query_aleatorio_obj0005 = "SELECT *
		FROM ba_banner  
		WHERE estado = 1 AND archivo != '' AND idba_lugar = $idba_lugar AND link != '' AND fecha_alta <= '$fecha_actual' AND (fecha_baja >= '$fecha_actual' OR fecha_baja = '0000-00-00') $filtro 
		ORDER BY RAND()
		LIMIT 1 ";
		$result_aleatorio_obj0005 = mysql_query($query_aleatorio_obj0005);
		$rs_aleatorio_obj0005 = mysql_fetch_assoc($result_aleatorio_obj0005);
		$rs_num_aleatorio_obj0005 = mysql_num_rows($result_aleatorio_obj0005);
		
		if($rs_num_aleatorio_obj0005 >= 1){ //Si encuentra un banner. Si no se hace esta comprobacion cuando no haya un banner va a registrar una vista en la tabla ba_login.


		//intruduce el banner.
		$banner =& new obj0001('0',$ruta,$rs_aleatorio_obj0005['archivo'],'','','','','ba_gourl.php?idba_banner='.$rs_aleatorio_obj0005['idba_banner'],'_blank','link=ba_gourl.php?idba_banner='.$rs_aleatorio_obj0005['idba_banner'],'',''); 
		
		//Registra la visualizacion del banner. Tipo 1=Vista, 2=Click			
		$query_ingreso_obj0005 = "INSERT INTO ba_login (
		  idba_banner
		, fecha
		, tipo
		, ip_navegacion
		) VALUES (
		  '$rs_aleatorio_obj0005[idba_banner]'
		, '$fecha_hora_actual'
		, '1'
		, '$ip_navegacion'
		)";
		mysql_query($query_ingreso_obj0005);
		}
		
	}//fin funcion

};//fin objeto ?>