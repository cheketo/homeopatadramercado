<?php

class obj0002{

	//Variables
	var $ver = 0;
	var $ruta = '';
	var $ruta_mini = '';
	var $imagenes = array();

	//Variables extras
	var $estilo = '';
	var $columnas = 0;
	var $error = '';
	var $swf_parametro = '';
	var $ancho_max = '';
	var $alto_max = '';
	var $cellpadding = 0;
	var $cellspacing = 0;

	function obj0002(
	 $ver
	,$ruta_mini	 
	,$ruta
	,$imagenes
	,$ancho_max
	,$alto_max
	,$columnas
	,$estilo
	,$cellpadding
	,$cellspacing
	){

	$cantidad = count($imagenes);
	$imagen_actual = 0;
	$filas = ceil($cantidad / $columnas);

	?>  
		<table align="center" <? if($estilo){ echo 'class="'.$estilo.'"'; } ?> cellpadding="<?=$cellpadding?>" cellspacing="<?=$cellspacing?>" >
		<? for($x=0; $x<$filas; $x++){ ?> 
		  <tr>
		  <? for($i=0; $i<$columnas; $i++){ if( $imagen_actual < $cantidad ){ ?>
			<td align="center" valign="middle"><? $imagen =& new obj0001('0',$ruta_mini,$imagenes[$imagen_actual],$ancho_max,$alto_max,'','',$ruta.$imagenes[$imagen_actual],'_blank','','wmode=opaque',''); ?></td>
		  <? } $imagen_actual++; } ?>
		  </tr>
		<? } ?>
	    </table>
	<?

		if($ver==1){//es la funcion ver, que permite ver todas las funciones usadas
		echo "<br>////////////////////////////////////////////////////<br>";
		echo "Ayuda del objeto obj0002 - Album<br>";
		echo "////////////////////////////////////////////////////<br><br>";
		
		echo "<strong>IMPORTANTE:</strong><br>";
		echo "1- Este objeto requiere del archivo '0_run_flash.js' para un correcto funcionamiento.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;El mismo permite la utilización de SWF sin el marco molesto.<br>
			  2- Este objeto requiere al objeto <strong>obj0001</strong>(foto). (ver objeto.php)<br /><br>";
		
		echo "<p>RT: indica si la variable posee retorno</p>";
		
		echo "<p>D: indica el valor por default</p><br><br>";

		echo "<p>1-[ver] => <strong>".$ver."</strong> //Muestra esta ayuda</p>";
		echo "<p>2-[ruta_mini] => <strong>".$ruta_mini."</strong> //Ruta de la foto pequeña (la que se muestra)</p>";
		echo "<p>3-[ruta] => <strong>".$ruta."</strong> //Ruta de la foto grande (la que se abre al hacer click en la pequeña)</p>";
		echo "<p>4-[imagenes] => <strong>".$imagenes."</strong> //Array conteniendo los nombres de archivo de las fotos. Ver ejemplos debajo.</p>";
		echo "<p>5-[ancho_max] => <strong>".$ancho_max."</strong> //Ancho máximo de las fotos pequeñas</p>";
		echo "<p>6-[alto_max] => <strong>".$alto_max."</strong> //Alto máximo de las fotos pequeñas</p>";
		echo "<p>7-[columnas] => <strong>".$columnas."</strong> //Cantidad de columnas del album generado</p>";
		echo "<p>8-[estilo] => <strong>".$estilo."</strong> //Nombre del estilo que se desea utilizar en la barra (sin el punto inicial)</p>";
		echo "<p>9-[cellpadding] => <strong>".$cellpadding."</strong> //Cellpadding de la tabla creada</p>";
		echo "<p>10-[cellspacing] => <strong>".$cellspacing."</strong> //Cellspacing de la tabla creada</p>";
		
		echo "<br><br><strong>EJEMPLOS:</strong><br>";
		echo "<br><br>1)- <strong>\$album =& new obj0002(1,'imagen/mini/','imagen/',array('0.jpg','2.jpg','3.jpg','4.jpg','5.jpg','6.jpg'),100,'',3,'album',5,0);</strong><br>
			  <br>En este caso se pasan todos los parametros en una sola linea. 
			  <br>El array con los nombres de las imagenes se crea directamente al ser pasado:
			  <br><strong><em>array('0.jpg','2.jpg','3.jpg','4.jpg','5.jpg','6.jpg')</em></strong>
			  ";
		echo "<br><br><br><br>2)- <strong>\$arrImagenes = array('0.jpg','2.jpg','3.jpg','4.jpg','5.jpg','6.jpg');</strong>
			  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>\$album =& new obj0002(1,'imagen/mini/','imagen/',\$arrImagenes,100,'',3,'album',5,0);</strong><br>
			  <br>En este caso primero se crea el array por separado, y luego se lo pasa al objeto.
			  <br>Esta forma es más util en la mayoría de los casos, más que nada si se desea sacar los nombres de las imagenes de una base de datos, ya que podemos crear el array con la consulta y luego pasarselo al objeto.
			  <br>En el siguiente ejemplo se genera y rellena un array por medio de una consulta a una supuesta tabla 'fotos' de un base de datos:
			  <br>
			  <br><strong>\$arrImagenes = <span style='color:0000FF'>array()</span>;</strong>
			  <br><strong>\$query_imagenes = <span style='color:cc0000'>\"SELECT * FROM fotos\"</span>;</strong>
			  <br><strong>\$result_imagenes = <span style='color:0000FF'>mysql_query(</span> \$query_imagenes<span style='color:0000FF'> )</span>;</strong>
			  <br><strong><span style='color:006600'>while</span>(\$rs_imagenes = <span style='color:0000FF'>mysql_fetch_assoc(</span> \$result_imagenes<span style='color:0000FF'> )</span> ){</strong>
			  <br><br><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:0000FF'>array_push</span>(\$arrImagenes,\$rs_imagenes[<span style='color:cc0000'>'foto'</span>]);</strong><br>
			  <br><strong>};</strong>
			  <br>
			  <br>Primero se crea el array <strong>arrImagenes</strong> vacío. 
			  <br>Luego por cada registro traido por la consulta se utiliza el comando <em>array_push</em> para agregar elementos al final del array. De esta forma se arma el array a ser utilizado luego en el album."; 
		
		}//fin funcion ver   

	}//fin funcion
	
};//fin objeto ?>