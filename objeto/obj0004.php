<?php

class obj0004{

	//PARAMETROS DE LA BARRA
	var $ver = 0;
	var $modo_titulo = 0;
	var $ruta_barra = "";
	var $boton_flash = "";
	var $titulo_flash = "";
	var $ancho_desplegable = 0;
	var $link_predeterminado = "javascript:;";


	function obj0004(
	 $ver
	,$modo_titulo
	,$ruta_barra
	,$boton_flash
	,$titulo_flash
	,$ancho_desplegable
	,$link_predeterminado
	){
	
	$cont_barra = 1;
	$cont_barra_inicial = 1;
	$longitud_barra = 0;
	$boton_flash_medidas = 0;
	$boton_flash_ancho = 0;
	$boton_flash_alto = 0;	
	$titulo_flash_medidas = 0;
	$titulo_flash_ancho = 0;
	$titulo_flash_alto = 0;
	$error = 0;
	$imagen_medidas = 0;
	$arrAlto = array();
	$ididioma_session = $_SESSION["ididioma_session"];
	
	//Detecta si esta logueado o no, para ver las areas restingidas
	if(!$_SESSION["iduser_web_session"]){
		$restringido_barra = 2;
	}else{
		$restringido_barra = 1;
	}	
	
	//se checkea el tamaño del boton basico (flash)
	if($boton_flash){
		if( file_exists($ruta_barra.$boton_flash) == true){
			$boton_flash_medidas = getimagesize($ruta_barra.$boton_flash);
			$boton_flash_ancho = $boton_flash_medidas[0];
			$boton_flash_alto = $boton_flash_medidas[1];
		}else{
			//MENSAJE DE ERROR SI FALTA EL BOTON FLASH
			echo "<script>alert('El archivo   ".$ruta_barra.$boton_flash."   no existe.')</script>";
		};
	}else{
		echo "<script>alert('No se ha especificado un valor para el BOTON flash. El mismo es requerido para el funcionamiento de la barra')</script>";
	};
	
	//se checkea el tamaño del titulo basico (flash)
	if($titulo_flash){
		if( file_exists($ruta_barra.$titulo_flash) == true){
			$titulo_flash_medidas = getimagesize($ruta_barra.$titulo_flash);
			$titulo_flash_ancho = $titulo_flash_medidas[0];
			$titulo_flash_alto = $titulo_flash_medidas[1];
		}else{
			//MENSAJE DE ERROR SI FALTA EL TITULO FLASH
			echo "<script>alert('El archivo   ".$ruta_barra.$titulo_flash."   no existe.')</script>";
		};
	}else{
		echo "<script>alert('No se ha especificado un valor para el TITULO flash. El mismo es requerido para el funcionamiento de la barra')</script>";
	};
	
	if( file_exists($ruta_barra."estilo.css") == true){
		?><link rel="stylesheet" href="<?=$ruta_barra?>estilo.css" tppabs="<?=$ruta_barra?>estilo.css" type="text/css"><?
	}else{
		//MENSAJE DE ERROR SI FALTA LA HOJA DE ESTILO DE LA BARRA (estilo.css)
		echo "<script>alert('El archivo   ".$ruta_barra."estilo.css   no existe.')</script>";
	};
?>
<script language="javascript">
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function P7_setMM2(){ //v2.0 by PVII
 //set the image over and down name convention
 document.p7TabOver="";
 document.p7TabDown="";
 var dt=false;if(document.getElementsByTagName){dt=true;}if(document.P7TabBar){return;}
 var i,k=-1,g,x,gg,tl,ts,ti,tm,tt,tsn,tu,el,args=P7_setMM2.arguments;
 P7TabProp=new Array();for(i=0;i<args.length;i++){P7TabProp[i]=args[i];}
 P7TabIM=new Array();P7TabSB=new Array();if(dt){tm=document.getElementsByTagName("IMG");
 }else{tm=document.images;}tm=document.images;tt=new Array();tt=tt.concat(tm);
 if(document.layers){for(i=0;i<document.layers.length;i++){ti=document.layers[i].document.images;
 if(ti){tt=tt.concat(ti);}for(x=0;x<document.layers[i].document.layers.length;x++){
 ti=document.layers[i].document.layers[x].document.images;if(ti){tt=tt.concat(ti);}}}tm=tt;}
 for(i=0;i<tm.length;i++){tl=tm[i].name; if(dt&&!tl){tl=tm[i].id;}
 if(tl.indexOf("p7TBim")==0){ts=tl.replace("p7TBim","");
 tsn="p7TBsub"+ts;k++;P7TabIM[k]=tl;if((g=MM_findObj(tsn))!=null){P7TabSB[k]=tsn;
 gg=(document.layers)?g:g.style;gg.visibility="hidden";}else{P7TabSB[k]='N';}}}
 document.P7_TBswapd=new Array();document.P7_TBswapo=new Array();for(i=0;i<P7TabIM.length;i++){
 g=MM_findObj(P7TabIM[i]);gg=g.src;g.p7TBim=g.src;tu=gg.lastIndexOf(".");
 g.p7TBimo=gg.substring(0,tu)+document.p7TabOver+gg.substring(tu,gg.length);
 g.p7TBimd=gg.substring(0,tu)+document.p7TabDown+gg.substring(tu,gg.length);
 if(P7TabProp[2]>1){document.P7_TBswapo[i]=new Image();document.P7_TBswapo[i].src=g.p7TBimo;}
 if(P7TabProp[2]>0){if(P7TabProp[2]==3){g.p7TBimd=g.p7TBimo;}document.P7_TBswapd[i]=new Image();
 document.P7_TBswapd[i].src=g.p7TBimd;}}if((g=MM_findObj('P7TabH'))!=null){gg=(document.layers)?g:g.style;
 gg.visibility="hidden";}if(dt&&P7TabProp[3]!='none'&&!window.opera){
 g=document.getElementsByTagName("A");for(i=0;i<g.length;i++){if(g[i].hasChildNodes()){el=g[i].firstChild;
 while (el){if(el.nodeType==3){gg=el.nodeValue;if(P7TabProp[3]==gg.replace("\n","")){
 g[i].className=P7TabProp[4];break;}}el=el.firstChild;}}}}document.P7TabBar=true;
}

function P7_trigMM2(bu){ //v2.0 by PVII
 if(document.P7wt){clearTimeout(document.P7wt);}
 if(!document.P7TabBar){return;}var i,g,d,dB=-1,tF=false,sF=false;
 for(i=0;i<P7TabSB.length;i++){sF=false;if((g=MM_findObj(P7TabSB[i]))!=null){g=MM_findObj(P7TabSB[i]);
 gg=(document.layers)?g:g.style;sF=true;}d=MM_findObj(P7TabIM[i]);if(P7TabIM[i]==P7TabProp[0]){
 dB=i;}if(P7TabIM[i]==bu){tF=true;if(sF){gg.visibility="visible";}if(P7TabProp[2]>0){
 if(i==dB){d.src=d.p7TBimd;}else if (P7TabProp[2]>1){d.src=d.p7TBimo;}}if((g=MM_findObj('P7TabH'))!=null){
 gg=(document.layers)?g:g.style;gg.visibility="visible";}}else{if(sF){gg.visibility="hidden";}
 if(P7TabProp[2]>0){d.src=d.p7TBim;}}}if(!tF){if(dB>-1){d=MM_findObj(P7TabIM[dB]);
 if((g=MM_findObj(P7TabSB[dB]))!=null&&P7TabProp[1]==0){gg=(document.layers)?g:g.style;
 gg.visibility="visible";}if(P7TabProp[2]>0){d.src=d.p7TBimd;}}
 if((g=MM_findObj('P7TabH'))!=null){gg=(document.layers)?g:g.style;gg.visibility="hidden";}}
}

function findPosX(obj)
  {
    var curleft = 0;
    if(obj.offsetParent)
        while(1) 
        {
          curleft += obj.offsetLeft;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
  }

  function findPosY(obj)
  {
    var curtop = 0;
    if(obj.offsetParent)
        while(1)
        {
          curtop += obj.offsetTop;
          if(!obj.offsetParent)
            break;
          obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
  }
</script>
<!--<body onLoad="P7_setMM2('none',1,2,'none','none'); ubicarMenu();" onResize="ubicarMenu()">-->
<?	

if($modo_titulo == 1){ //MODO TITULO HABILITADO

	
}else{ //MODO TITULO DESHABILITADO
	
	// BOTONES NIVEL 1 (FLASH)
	$llave = 0;
	$array_to_sort = array(); 
	$query_mod9_categoria1 = "SELECT A.idcategoria, B.titulo, A.posicion, A.posicion_barra, A.foto_barra, A.link
	FROM categoria A
	INNER JOIN categoria_idioma B ON B.idcategoria = A.idcategoria
	WHERE A.estado = 1 AND A.barra = 1 AND B.ididioma = $ididioma_session AND (restringido = '2' OR restringido = '$restringido_barra')
	ORDER BY A.posicion";
	$result_mod9_categoria1 = mysql_query($query_mod9_categoria1);
	while ($rs_mod9_categoria1 = mysql_fetch_assoc($result_mod9_categoria1))
	{
		$array_to_sort[$llave] = array('posicion_barra'=>$rs_mod9_categoria1['posicion_barra'],'idcategoria'=>$rs_mod9_categoria1['idcategoria'],'idseccion'=>'0','titulo'=>$rs_mod9_categoria1['titulo'],'foto_barra'=>$rs_mod9_categoria1['foto_barra'],'link'=>$rs_mod9_categoria1['link']);
		$llave ++;
	}
	
	$query_mod9_seccion1 = "SELECT A.idseccion, B.titulo, A.posicion, A.posicion_barra, A.idcategoria
	FROM seccion A
	LEFT JOIN seccion_idioma B ON B.idseccion = A.idseccion
	WHERE A.estado = 1 AND A.barra = 1 AND B.ididioma = $ididioma_session
	ORDER BY A.posicion";
	$result_mod9_seccion1 = mysql_query($query_mod9_seccion1);
	while ($rs_mod9_seccion1 = mysql_fetch_assoc($result_mod9_seccion1))
	{
		$link_seccion = "seccion_detalle.php?idseccion=".$rs_mod9_seccion1['idseccion'];
		$array_to_sort[$llave] = array('posicion_barra'=>$rs_mod9_seccion1['posicion_barra'],'idcategoria'=>'','idseccion'=>$rs_mod9_seccion1['idseccion'],'titulo'=>$rs_mod9_seccion1['titulo'],'foto_barra'=>$rs_mod9_seccion1['foto_barra'],'link'=>$link_seccion);
		$llave ++;
	}	

	$sort['direction'] = $_POST['sort_direction'] ? $_POST['sort_direction'] : 'SORT_ASC';
    $sort['field']       = $_POST['sort_field'] ? $_POST['sort_field'] : 'posicion_barra';      

    $sort_arr = array();
    foreach($array_to_sort AS $uniqid => $row){
        foreach($row AS $key=>$value){
            $sort_arr[$key][$uniqid] = $value;
        }
    }  
  
    array_multisort($sort_arr[$sort['field']], constant($sort['direction']), $array_to_sort);    
	
	for($i=0; $i<count($array_to_sort); $i++){
		$idcategoria_barra = $array_to_sort[$i]["idcategoria"];
		$idseccion_barra = $array_to_sort[$i]["idseccion"];
		$titulo_barra = $array_to_sort[$i]["titulo"];
		$foto_barra = $array_to_sort[$i]["foto_barra"];
		$link_barra = $array_to_sort[$i]["link"];
		
		$row_cantidad_total = 0;
		//SE CHEQUEA SI LA CATEGORIA POSEE HIJOS
		if($idcategoria_barra){
			$query_cantidad = "SELECT count(A.idcategoria) 
			FROM categoria A
			INNER JOIN categoria_idioma B ON B.idcategoria = A.idcategoria
			WHERE A.idcategoria_padre = $idcategoria_barra AND A.estado = '1' AND B.ididioma = $ididioma_session  AND (restringido = '2' OR restringido = '$restringido_barra')
			";
			$row_cantidad = mysql_fetch_row(mysql_query($query_cantidad));
			$row_cantidad_total += $row_cantidad[0];
		
			//SE CHEQUEA SI LA CATEGORIA SECCIONES CON LA OPCION BARRA			
			$query_cantidad_seccion = "SELECT count(A.idseccion) 
			FROM seccion A
			INNER JOIN seccion_idioma B ON B.idseccion = A.idseccion
			WHERE A.idcategoria = $idcategoria_barra AND A.estado = '1' AND A.barra_cat = '1' AND B.ididioma = $ididioma_session
			";
			$row_cantidad_seccion = mysql_fetch_row(mysql_query($query_cantidad_seccion));
			$row_cantidad_total += $row_cantidad_seccion[0];
		}		

		//SI LA CATEGORIA POSEE UN LINK ESPECIFICO (campo link de la tabla categoria), sino se usa el link predeterminado
		if ($link_barra) {
			$link_cat = $link_barra;
		}else{
			$link_cat = $link_predeterminado.'?idcategoria='.$idcategoria_barra;
		};		
		
		//SI LA CATEGORIA POSEE EL CAMPO FOTO_BARRA, SE UTILIZA DICHA IMAGEN COMO BOTON
		if ($foto_barra){ 
?>
			<div id="p7TBtrig<?=$cont_barra?>0" style="position:absolute; left: 0px; top: 0px; width: <?=$boton_flash_ancho?>px; z-index: 300; visibility:hidden;">
			<a href="<?=$link_cat?>" onMouseOver="P7_trigMM2('p7TBim<?=$cont_barra?>0')" onFocus="P7_trigMM2('p7TBim<?=$cont_barra?>0')">
			<? 
			//SE UITILIZA EL OBJETO OBJ0001 (imagen) para poder mostrar tanto imagenes como swf 
			$imagen[$cont_barra] =& new obj0001('0',$ruta_barra."foto_barra/",$foto_barra,'','','','','','',array('texto='.$titulo_barra,'href='.$link_cat,'over=javascript:P7_trigMM2("p7TBim'.$cont_barra.'0")'),'wmode=opaque',''); ?>
			</a>
			<img src="<?=$ruta_barra?>espacio.gif" name="p7TBim<?=$cont_barra?>0" id="p7TBim<?=$cont_barra?>0" width="0" height="0" border="0">
			</div><? $longitud_barra = $longitud_barra + $imagen[$cont_barra]->alto; $arrAlto[$cont_barra]= $imagen[$cont_barra]->alto; ?>
<?
		}else{ // CASO CONTRARIO SE USA EL BOTON EN FLASH 
?>
			<div id="p7TBtrig<?=$cont_barra?>0" style="position:absolute; left: 0px; top: 0px; width: <?=$boton_flash_ancho?>px; z-index: 300; visibility:hidden;">
			<script type="text/javascript">
			AC_FL_RunContent( 
				'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0',
				'width','<?=$boton_flash_ancho?>',
				'height','<?=$boton_flash_alto?>',
				'src','<?=$ruta_barra.substr($boton_flash,0,-4)?>',
				'quality','high',
				'WMode','Opaque',
				'FlashVars',"texto=<?=$titulo_barra?>&href=<?=$link_cat?>&over=javascript:P7_trigMM2('p7TBim<?=$cont_barra?>0')",
				'pluginspage','http://www.macromedia.com/go/getflashplayer',
				'movie','<?=$ruta_barra.substr($boton_flash,0,-4)?>'
			);
			</script>
			<img src="<?=$ruta_barra?>espacio.gif" name="p7TBim<?=$cont_barra?>0" id="p7TBim<?=$cont_barra?>0" width="0" height="0" border="0">	
			</div><? $longitud_barra = $longitud_barra + $boton_flash_alto; $arrAlto[$cont_barra]= $boton_flash_alto;?>
<? 
		}; //FIN IF FOTO_BARRA		
			//COMIENZO DEL NIVEL 2			
			if ($row_cantidad_total > 0) {
?>				<div id="p7TBsub<?=$cont_barra?>0" class="p7tbsub" style="position:absolute; left: 0px; top: 0px; width: <?=$ancho_desplegable?>px; z-index: 400; visibility: hidden">
<?			};

			// BOTONES NIVEL 2 (HTML DESPLEGABLE)
			if($idcategoria_barra){
				$query_barra_subcat = "SELECT A.idcategoria, B.titulo, A.link 
				FROM categoria A 
				INNER JOIN categoria_idioma B ON B.idcategoria = A.idcategoria
				WHERE A.idcategoria_padre = $idcategoria_barra AND A.estado = '1' AND B.ididioma = $ididioma_session  AND (restringido = '2' OR restringido = '$restringido_barra')
				ORDER BY A.posicion ASC";
				$result_barra_subcat = mysql_query($query_barra_subcat);
				while($rs_barra_subcat = mysql_fetch_assoc($result_barra_subcat)){
					//SI LA CATEGORIA POSEE UN LINK ESPECIFICO (campo link de la tabla categoria), sino se usa el link predeterminado
					if ($rs_barra_subcat['link']) {
						$link_subcat = $rs_barra_subcat['link'];
					}else{
						$link_subcat = $link_predeterminado.'?idcategoria='.$rs_barra_subcat['idcategoria'];
					};			
?>
  					<p><a style="font-size:11px" href="<?=$link_subcat?>" onMouseOver="clearTimeout(document.P7wt)"><?=$rs_barra_subcat['titulo']?></a></p>			
<?		
				};
				// BOTONES NIVEL 2 (HTML DESPLEGABLE) SECCION
				$query_barra_subcat_seccion = "SELECT A.idseccion, B.titulo
				FROM seccion A
				LEFT JOIN seccion_idioma B ON B.idseccion = A.idseccion
				WHERE A.estado = '1' and A.idcategoria = $idcategoria_barra AND A.barra_cat = '1' AND B.ididioma = $ididioma_session
				ORDER BY A.posicion ASC";
				$result_barra_subcat_seccion = mysql_query($query_barra_subcat_seccion);
				while($rs_barra_subcat_seccion = mysql_fetch_assoc($result_barra_subcat_seccion)){	
?>
					<p><a style="font-size:11px" href="seccion_detalle.php?idseccion=<?=$rs_barra_subcat_seccion['idseccion']?>" onMouseOver="clearTimeout(document.P7wt)"><?=$rs_barra_subcat_seccion['titulo']?></a></p>			
<?		
				};
			};
			
			if ($row_cantidad_total > 0) { 
?>					</div>
<?			};
	
	$cont_barra++;	
	};// BOTONES NIVEL 1 (FLASH) FIN
};//MODO TITULO FIN
	
?>
<script language="javascript">
	function ubicarMenu(){
		<? 
		 for ($i=1; $i<$cont_barra ; $i++){
		 	if($i==1){
				echo "arrAlto = [null,".$arrAlto[$i]."];";
			}else{
				echo "arrAlto[".$i."] = ".$arrAlto[$i].";";
			}
		 }
		?>
		posicion_izq = Number(findPosX(td_barra_izq));
		posicion_top = Number(findPosY(td_barra_izq));
		posicion_top_inicial = posicion_top;
		altoRollout = 0;
		for (i=<?=$cont_barra_inicial?>; i<<?=$cont_barra?>; i++){
			div_actual = "p7TBtrig"+i+"0";
			subdiv_actual = "p7TBsub"+i+"0";
			
			if(document.getElementById(div_actual)){
				document.getElementById(div_actual).style.left = posicion_izq;
				document.getElementById(div_actual).style.top = posicion_top;
				document.getElementById(div_actual).style.visibility = "visible";
			};

			if(document.getElementById(subdiv_actual)){
				document.getElementById(subdiv_actual).style.left = posicion_izq + <?=$boton_flash_ancho?>;
				document.getElementById(subdiv_actual).style.top = posicion_top;
				alto_subdiv_actual = document.getElementById(subdiv_actual).offsetHeight;
				if(alto_subdiv_actual + posicion_top > altoRollout){
					altoRollout = alto_subdiv_actual + posicion_top;
				};
			};
			
			posicion_top = posicion_top + arrAlto[i];
		};
			
		var winW = 630, winH = 460;
		
		if (parseInt(navigator.appVersion)>3) {
		 if (navigator.appName=="Netscape") {
		  winW = window.innerWidth;
		  winH = window.innerHeight;
		 }
		 if (navigator.appName.indexOf("Microsoft")!=-1) {
		  winW = document.body.offsetWidth;
		  winH = document.body.offsetHeight;
		 }
		}
	
		document.getElementById("rollout").width = winW-30;
		
		if(altoRollout > posicion_top_inicial + <?=$longitud_barra?>){
			document.getElementById("rollout").height = altoRollout+25;
		}else{
			document.getElementById("rollout").height = posicion_top_inicial + <?=$longitud_barra?>;
		};

	};
</script>
<div id="P7TabH" style="position:absolute; left: 0px; top: 0px; z-index: 200; visibility: hidden"><a href="javascript:;" onMouseOver="document.P7wt=setTimeout('P7_trigMM2()',300)"><img id="rollout" src="<?=$ruta_barra?>espacio.gif" alt="" width="100%" height="700" border="0"></a></div>
<table cellpadding="0" cellspacing="0">
	<tr><td id="td_barra_izq"><img src="<?=$ruta_barra?>espacio.gif" alt="" width="1" height="<?=$longitud_barra?>" border="0"></td></tr>
</table>
	<?
		//AYUDA:///////////////////////////////////////////////////////////////////
		if($ver==1){//es la funcion ver, que permite ver todas las funciones usadas
		echo "<p>///////////////////////////////////////////////<p>";
		echo "<p>Variables del objeto mod4_barra</p>";
		echo "<p>///////////////////////////////////////////////<p>";
		
		echo "IMPORTANTE:<br><br>";
		echo "<p>1 - Este objeto requiere conectarse a una base de datos que posea la tabla   CATEGORIA con POR LO MENOS los siguientes campos:<br />
			  - <strong>idcategoria</strong><br />
			  - <strong>idcategoria_padre</strong> (indica a que otra categoria pertenece esta categoria) <br />
			  - <strong>titulo</strong> (el titulo de la categoria) <br />
			  - <strong>foto_barra</strong> (indica si esta categor&iacute;a utiliza una imagen espec&iacute;fica al aparecer en la barra) <br />
			  - <strong>link</strong> (indica si al aparecer en la barra va a poseer un link especifico - sino se utiliza el indicado al crear el objeto- ) <br />
			  - <strong>barra</strong> (indica si esta categoria va a aparecer en la barra) <br />
			  - <strong>posicion</strong><br />
			  - <strong>estado</strong> (1- habilitado, 2- deshabilitado) <br />
			  <br />
			  2 - Este objeto requiere del archivo   '0_run_flash.js' para un correcto funcionamiento. El mismo permite la   utilizaci&oacute;n de SWF sin el marco molesto.<br />
			  </p>
			  3 - Este objeto requiere al objeto <strong>obj0001</strong>(foto) al utilizar el modo titulo. (ver objeto.php)<br />
			  </p><br>
			  ";

		echo "<br><strong>PARAMETROS QUE SE LE PASAN A LA BARRA:</strong><br>";	
		echo "D: indica el valor por default<br>";
		echo "<p>1-[ver] => <strong>".$ver."</strong> //muestra esta ayuda (0- no muestra / 1- muestra) </p>";
		echo "<p>2-[modo_titulo] => <strong>".$modo_titulo."</strong> //activa el modo titulo (0- no activado / 1- activado)</p>";
		echo "<p>3-[ruta_barra] => <strong>".$ruta_barra."</strong> //La carpeta donde se alojan todos los archivos que usa la barra</p>";
		echo "<p>4-[boton_flash] => <strong>".$boton_flash."</strong> //Nombre del archivo del boton flash</p>";
		echo "<p>5-[titulo_flash] => <strong>".$boton_flash."</strong> //Nombre del archivo del titulo flash</p>";		
		echo "<p>6-[ancho_desplegable] => <strong>".$ancho_desplegable."</strong> //El ancho en pixeles del submenu desplegable de la barra</p>";
		echo "<p>7-[link_predeterminado] => <strong>".$link_predeterminado."</strong> //Link donde enviaran los botones de la barra en caso de no tener un link especificado en la base de datos. A este link se le pasa por GET el idcategoria del boton</p>";
		echo "<p>///////////////////////////////////////////////</p><br><br>";
		echo "<strong>EJEMPLO:</strong><BR>";
		echo " \$barra =& new obj0004(1,0,'objeto/barra/','boton.swf','titulo.swf',150,'seccion.php');";
		}//fin funcion ver

	}//fin funcion
	
};//fin objeto 

?>