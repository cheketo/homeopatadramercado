<?php

//Cantidad de titulares que trae: (0 trae el maximo posible)
$mod0001_limite = 15;

//Duracion en segundos de cada titular en pantalla: (solo cuando se usa cuando el limite no es 1)
$mod0001_duracion = 15;

//ruta de las imagenes
$mod0001_ruta = "imagen/titular/";

//ruta y archivo del swf base del top
switch ($_SESSION['ididioma_session']){
	case 1://Español
		$mod0001_swf = "skin/index/swf/top_esp.swf";
		break;
	case 2://Ingles
		$mod0001_swf = "skin/index/swf/top_ing.swf";
		break;
	case 3://Portugues
		$mod0001_swf = "skin/index/swf/top_por.swf";
		break;
	case 4://Frances
		$mod0001_swf = "skin/index/swf/top_fra.swf";
		break;
	case 5://Italiano
		$mod0001_swf = "skin/index/swf/top_ita.swf";
		break;
	case 6://Aleman
		$mod0001_swf = "skin/index/swf/top_ale.swf";
		break;
}

//ANCHO Y ALTO DEL TOP
$mod0001_imagesize = getimagesize($mod0001_swf);

//Da la fecha actual, sirve para determinar la fecha para la fecha de activacion del titular o bien la fecha de baja del titular.
$fecha_actual = date("Y-m-d");	

if($mod0001_limite == 0){
	$mod0001_limite = '';
}else{
	$mod0001_limite = 'LIMIT '.$mod0001_limite;
};


/*****************************************************************/
/*    http://ar.php.net/manual/es/function.natsort.php           */
/*    http://ar.php.net/manual/es/function.sizeof.php            */
/*    http://ar.php.net/manual/es/function.count.php             */
/*    http://ar.php.net/manual/es/function.array-rand.php        */
/*****************************************************************/

$cantidad_titulares = 0;
$cantidad_titulares += count($_SESSION['producto']['idtitular']);
$cantidad_titulares += count($_SESSION['carpeta']['idtitular']);
$cantidad_titulares += count($_SESSION['carpeta_padre']['idtitular']);
$cantidad_titulares += count($_SESSION['seccion']['idtitular']);
$cantidad_titulares += count($_SESSION['urlespecifica']['idtitular']);
$cantidad_titulares += count($_SESSION['generico']['idtitular']);

//echo $cantidad_titulares;
//print_r ($_SESSION['generico']['idtitular']);

//GENERO LOS ARRAYS SI ESTAN VACIOS
if($cantidad_titulares == 0){
	//CONSULTA POR TODOS LOS TITULARES Y LOS GUARDA EN ARRAYS EN SESION
	
	//CONTADORES
	$c = 0;
	$cp = 0;
	$p = 0;
	$s = 0;
	$g = 0;
	$u = 0;
	
	//CONSULTA
	$query_titular = "SELECT DISTINCT A.idtitular, A.ididioma, A.idcarpeta_padre, A.idcarpeta, A.idproducto, A.idseccion, A.foto, A.urlespecifica
	FROM titular A
	INNER JOIN titular_sede B ON A.idtitular = B.idtitular
	WHERE A.estado = 1 AND A.fecha_activacion <= '$fecha_actual' AND (A.fecha_baja >= '$fecha_actual' OR A.fecha_baja = '0000-00-00') AND A.ididioma = '$_SESSION[ididioma_session]' AND B.idsede = '$_SESSION[idsede_session]'
	ORDER BY RAND() 
	$mod0001_limite";
	$result_titular = mysql_query($query_titular);

	while($rs_titular = mysql_fetch_assoc($result_titular)){
		
		if($rs_titular['urlespecifica'] == ""){
		
			//SI TIENE IDCARPETA, LO INGRESO EN SU RESPECTIVO ARRAY
			if($rs_titular['idcarpeta'] != 0 && $rs_titular['urlespecifica'] == ""){
				$_SESSION['carpeta']['idtitular'][$c] = $rs_titular['idtitular'];
				$_SESSION['carpeta']['idcarpeta'][$c] = $rs_titular['idcarpeta'];
				$_SESSION['carpeta']['foto'][$c] = $rs_titular['foto'];
				$c++;
			}
			
			//SI TIENE IDCARPETA_PADRE, LO INGRESO EN SU RESPECTIVO ARRAY
			if($rs_titular['idcarpeta_padre'] != 0 && $rs_titular['urlespecifica'] == ""){
				$_SESSION['carpeta_padre']['idtitular'][$cp] = $rs_titular['idtitular'];
				$_SESSION['carpeta_padre']['idcarpeta_padre'][$cp] = $rs_titular['idcarpeta_padre'];
				$_SESSION['carpeta_padre']['foto'][$cp] = $rs_titular['foto'];
				$cp++;
			}
			
			//SI TIENE IDPRODUCTO, LO INGRESO EN SU RESPECTIVO ARRAY
			if($rs_titular['idproducto'] != 0 && $rs_titular['urlespecifica'] == ""){
				$_SESSION['producto']['idtitular'][$p] = $rs_titular['idtitular'];
				$_SESSION['producto']['idproducto'][$p] = $rs_titular['idproducto'];
				$_SESSION['producto']['foto'][$p] = $rs_titular['foto'];
				$p++;
			}
			
			//SI TIENE IDSECCION, LO INGRESO EN SU RESPECTIVO ARRAY
			if($rs_titular['idseccion'] != 0 && $rs_titular['urlespecifica'] == ""){
				$_SESSION['seccion']['idtitular'][$s] = $rs_titular['idtitular'];
				$_SESSION['seccion']['idseccion'][$s] = $rs_titular['idseccion'];
				$_SESSION['seccion']['foto'][$s] = $rs_titular['foto'];
				$s++;
			}
			
			//SI ES GENERICO, LO INGRESO EN SU RESPECTIVO ARRAY
			if($rs_titular['idcarpeta'] == 0 && $rs_titular['idproducto'] == 0 && $rs_titular['idseccion'] == 0 && $rs_titular['urlespecifica'] == ""){
				$_SESSION['generico']['idtitular'][$g] = $rs_titular['idtitular'];
				$_SESSION['generico']['foto'][$g] = $rs_titular['foto'];
				$g++;
			}
			
		}else{
			
			//SI ES URL ESPECIFICA, LO INGRESO EN SU RESPECTIVO ARRAY
			if($rs_titular['idcarpeta'] == 0 && $rs_titular['idproducto'] == 0 && $rs_titular['idseccion'] == 0 && $rs_titular['urlespecifica'] != ""){
				$_SESSION['urlespecifica']['idtitular'][$u] = $rs_titular['idtitular'];
				$_SESSION['urlespecifica']['url'][$u] = $rs_titular['urlespecifica'];
				$_SESSION['urlespecifica']['foto'][$u] = $rs_titular['foto'];
				$u++;
			}
			
		}
		
		
		
	}
}
//FIN

//MEZCLAR ARRAYS
if($_SESSION['carpeta']){
	shuffle($_SESSION['carpeta']['foto']);
	//print_r($_SESSION['carpeta']['foto']);
}
if($_SESSION['carpeta_padre']){
	shuffle($_SESSION['carpeta_padre']['foto']);
	//print_r($_SESSION['carpeta_padre']['foto']);
}
if($_SESSION['producto']){
	shuffle($_SESSION['producto']['foto']);
	//print_r($_SESSION['producto']['foto']);
}
if($_SESSION['seccion']){
	shuffle($_SESSION['seccion']['foto']);
	//print_r($_SESSION['seccion']['foto']);
}
if($_SESSION['generico']){
	shuffle($_SESSION['generico']['foto']);
	//print_r($_SESSION['generico']['foto']);
}
if($_SESSION['urlespecifica']){
	shuffle($_SESSION['urlespecifica']['foto']);
	//print_r($_SESSION['urlespecifica']['foto']);
}


$hacer_generico = false;
$idproducto = $_GET['idproducto'];

//SI HAY ARRAY DE PRODUCTO
if($idproducto){

	$vuelta = 0;	
	
	//INGRESO PRODUCTO
	if($idproducto && count($_SESSION['producto']['idtitular']) > 0){
		
		for($i=0;$i<count($_SESSION['producto']['idtitular']);$i++){
		
			if($_SESSION['producto']['idproducto'][$i] == $idproducto){
				if($vuelta > 0){
					$cargar_titular .= '§'.$_SESSION['producto']['foto'][$i];
				}else{
					$cargar_titular .= $_SESSION['producto']['foto'][$i];
				};
				$vuelta++;
			}
			
		}
		$hacer_generico = true;
		//echo "Producto: ".$cargar_titular;
	
	//SINO HAY PRODUCTO
	}else{
		
		$vuelta = 0;
		$idcarpeta = $_GET['idcarpeta'];
	
		//INGRESO CARPETA
		if($idcarpeta && count($_SESSION['carpeta']['idtitular']) > 0){

			for($i=0;$i<count($_SESSION['carpeta']['idtitular']);$i++){

				if($_SESSION['carpeta']['idcarpeta'][$i] == $idcarpeta){
					if($vuelta > 0){
						$cargar_titular .= '§'.$_SESSION['carpeta']['foto'][$i];
					}else{
						$cargar_titular .= $_SESSION['carpeta']['foto'][$i];
					};
					$vuelta++;
				}
				
			}
			$hacer_generico = true;
			//echo "Carpeta: ".$cargar_titular;
			
		//SINO HAY CARPETA
		}else{
			
			$vuelta = 0;
			$idcarpeta = $_GET['idcarpeta'];
		
			//INGRESO CARPETA_PADRE
			if($idcarpeta && count($_SESSION['carpeta_padre']['idtitular']) > 0){
			
				//BUSCO EL IDCARPETA_PADRE DE LA CARPETA PARA LUEGO COMPARAR
				$query_padre = "SELECT idcarpeta_padre
				FROM carpeta
				WHERE idcarpeta = '$idcarpeta' 
				LIMIT 1";
				$rs_padre = mysql_fetch_assoc(mysql_query($query_padre));
				
				for($i=0;$i<count($_SESSION['carpeta_padre']['idtitular']);$i++){
	
					if($_SESSION['carpeta_padre']['idcarpeta_padre'][$i] == $rs_padre['idcarpeta_padre']){
						if($vuelta > 0){
							$cargar_titular .= '§'.$_SESSION['carpeta_padre']['foto'][$i];
						}else{
							$cargar_titular .= $_SESSION['carpeta_padre']['foto'][$i];
						};
						$vuelta++;
					}
					
				}
				$hacer_generico = true;
				//echo "Carpeta Padre: ".$cargar_titular;
			
			//SINO HAY CARPETA PADRE	
			}else{
			
				$hacer_generico = false;
			
			}//FIN CARPETA PADRE
		
		}//FIN CARPETA
			
	}//FIN PRODUCTO

}//FIN ARRAY PRODUCTO



$idseccion = $_GET['idseccion'];

//SI HAY ARRAY DE SECCION
if($idseccion){	
	
	$vuelta = 0;
	
	//INGRESO SECCION
	if($idseccion && count($_SESSION['seccion']['idtitular']) > 0){
		
		for($i=0;$i<count($_SESSION['seccion']['idtitular']);$i++){
		
			if($_SESSION['seccion']['idseccion'][$i] == $idproducto){
				if($vuelta > 0){
					$cargar_titular .= '§'.$_SESSION['seccion']['foto'][$i];
				}else{
					$cargar_titular .= $_SESSION['seccion']['foto'][$i];
				};
				$vuelta++;
			}
			
		}
		$hacer_generico = true;
		//echo "Seccion: ".$cargar_titular;
	
	//SINO HAY SECCION
	}else{
		
		$vuelta = 0;
		$idcarpeta = $_GET['idcarpeta'];
	
		//INGRESO CARPETA
		if($idcarpeta && count($_SESSION['carpeta']['idtitular']) > 0){

			for($i=0;$i<count($_SESSION['carpeta']['idtitular']);$i++){

				if($_SESSION['carpeta']['idcarpeta'][$i] == $idcarpeta){
					if($vuelta > 0){
						$cargar_titular .= '§'.$_SESSION['carpeta']['foto'][$i];
					}else{
						$cargar_titular .= $_SESSION['carpeta']['foto'][$i];
					};
					$vuelta++;
				}
				
			}
			$hacer_generico = true;
			//echo "Carpeta: ".$cargar_titular;
			
		//SINO HAY CARPETA
		}else{
			
			$vuelta = 0;
			$idcarpeta = $_GET['idcarpeta'];
		
			//INGRESO CARPETA_PADRE
			if($idcarpeta && count($_SESSION['carpeta_padre']['idtitular']) > 0){
			
				//BUSCO EL IDCARPETA_PADRE DE LA CARPETA PARA LUEGO COMPARAR
				$query_padre = "SELECT idcarpeta_padre
				FROM carpeta
				WHERE idcarpeta = '$idcarpeta' 
				LIMIT 1";
				$rs_padre = mysql_fetch_assoc(mysql_query($query_padre));
				
				for($i=0;$i<count($_SESSION['carpeta_padre']['idtitular']);$i++){
	
					if($_SESSION['carpeta_padre']['idcarpeta_padre'][$i] == $rs_padre['idcarpeta_padre']){
						if($vuelta > 0){
							$cargar_titular .= '§'.$_SESSION['carpeta_padre']['foto'][$i];
						}else{
							$cargar_titular .= $_SESSION['carpeta_padre']['foto'][$i];
						};
						$vuelta++;
					}
					
				}
				$hacer_generico = true;
				//echo "Carpeta Padre: ".$cargar_titular;
			
			//SINO HAY CARPETA PADRE	
			}else{
			
				$hacer_generico = false;
			
			}//FIN CARPETA PADRE
		
		}//FIN CARPETA
			
	}//FIN SECCION
	
}//FIN ARRAY SECCION




$idcarpeta = $_GET['idcarpeta'];

//SI HAY ARRAY DE CARPETA
if($idcarpeta && !$idproducto && !idseccion){	
		
	$vuelta = 0;

	//INGRESO CARPETA
	if($idcarpeta && count($_SESSION['carpeta']['idtitular']) > 0){

		for($i=0;$i<count($_SESSION['carpeta']['idtitular']);$i++){

			if($_SESSION['carpeta']['idcarpeta'][$i] == $idcarpeta){
				if($vuelta > 0){
					$cargar_titular .= '§'.$_SESSION['carpeta']['foto'][$i];
				}else{
					$cargar_titular .= $_SESSION['carpeta']['foto'][$i];
				};
				$vuelta++;
			}
			
		}
		$hacer_generico = true;
		//echo "Carpeta: ".$cargar_titular;
		
	//SINO HAY CARPETA
	}else{
		
		$vuelta = 0;
		$idcarpeta = $_GET['idcarpeta'];
	
		//INGRESO CARPETA_PADRE
		if($idcarpeta && count($_SESSION['carpeta_padre']['idtitular']) > 0){
		
			//BUSCO EL IDCARPETA_PADRE DE LA CARPETA PARA LUEGO COMPARAR
			$query_padre = "SELECT idcarpeta_padre
			FROM carpeta
			WHERE idcarpeta = '$idcarpeta' 
			LIMIT 1";
			$rs_padre = mysql_fetch_assoc(mysql_query($query_padre));
			
			for($i=0;$i<count($_SESSION['carpeta_padre']['idtitular']);$i++){

				if($_SESSION['carpeta_padre']['idcarpeta_padre'][$i] == $rs_padre['idcarpeta_padre']){
					if($vuelta > 0){
						$cargar_titular .= '§'.$_SESSION['carpeta_padre']['foto'][$i];
					}else{
						$cargar_titular .= $_SESSION['carpeta_padre']['foto'][$i];
					};
					$vuelta++;
				}
				
			}
			$hacer_generico = true;
			//echo "Carpeta Padre: ".$cargar_titular;
		
		//SINO HAY CARPETA PADRE	
		}else{
		
			$hacer_generico = false;
		
		}//FIN CARPETA PADRE
	
	}//FIN CARPETA
	
}//FIN ARRAY CARPETA


//HACER URL ESPECIFICA
if($hacer_urlespecifica == false){
	$vuelta = 0;
	$file = basename($_SERVER['PHP_SELF']);
	
	//INGRESO URL ESPECIFICA
	if(count($_SESSION['urlespecifica']['idtitular']) > 0){
		
		for($i=0;$i<count($_SESSION['urlespecifica']['idtitular']);$i++){
		
			if($file == $_SESSION['urlespecifica']['url'][$i]){
				if($vuelta > 0){
					$cargar_titular .= '§'.$_SESSION['urlespecifica']['foto'][$vuelta];
				}else{
					$cargar_titular .= $_SESSION['urlespecifica']['foto'][$vuelta];
				};
				$vuelta++;
				$hacer_generico = true;
			}
	
		}
		//echo "Generico: ".$cargar_titular;
	
	}//FIN URL ESPECIFICA
}

//HACER GENERICO
if($hacer_generico == false){
	$vuelta = 0;
	
	//INGRESO GENERICO
	if(count($_SESSION['generico']['idtitular']) > 0){
		
		for($i=0;$i<count($_SESSION['generico']['idtitular']);$i++){
	
			if($vuelta > 0){
				$cargar_titular .= '§'.$_SESSION['generico']['foto'][$i];
			}else{
				$cargar_titular .= $_SESSION['generico']['foto'][$i];
			};
			$vuelta++;
	
		}
		//echo "Generico: ".$cargar_titular;
	
	}//FIN GENERICO
}



?>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="<?=$mod0001_imagesize[0]?>" height="<?=$mod0001_imagesize[1]?>">
        <param name="movie" value="<?= $mod0001_swf ?>" />
        <param name="quality" value="high" />
        <param name="wmode" value="opaque" />
        <param name="FlashVars" value="ruta=<?=$mod0001_ruta?>&duracion=<?=$mod0001_duracion?>&titular=<?= $cargar_titular; ?>" />
        <embed src="<?= $mod0001_swf ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="<?=$mod0001_imagesize[0]?>" height="<?=$mod0001_imagesize[1]?>" wmode="opaque" FlashVars="ruta=<?=$mod0001_ruta?>&duracion=<?=$mod0001_duracion?>&titular=<?= $cargar_titular; ?>"></embed>
      </object>

