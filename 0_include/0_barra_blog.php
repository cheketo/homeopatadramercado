<div class="SubTitulos"><?= $lbl_10Post ?>:</div>
            <?
				$tagCloud = "";
				$queryPost = "SELECT A.*, B.*
				FROM seccion A
				INNER JOIN seccion_idioma_dato B ON A.idseccion = B.idseccion
				INNER JOIN seccion_carpeta C ON A.idseccion = C.idseccion
				WHERE C.idcarpeta = 42 AND A.estado = 1 AND B.estado = 1 AND B.ididioma = '1'
				ORDER BY fecha_alta DESC
				LIMIT 10";
				$resultPost = mysql_query($queryPost);
				while($rsPost = mysql_fetch_assoc($resultPost)){
				
					$Tags = split(",",$rsPost['keywords']);
					$cantTags = count($Tags);
					for($i=0;$i<$cantTags;$i++){
						
						$tagCloud .= '<a href="blogOpen.php?idseccion='.$rsPost['idseccion'].'"><span class="TagsCloud" >&bull; '.strtoupper($Tags[$i]).'</span></a>';
						
					}
			?>
            <a href="blogOpen.php?idseccion=<?= $rsPost['idseccion'] ?>"><div class="LatestPost"><span style="color:#333333"><?= strtoupper(date("M d Y",strtotime($rsPost['fecha_alta']))); ?>:</span> <?= strtoupper($rsPost['titulo']); ?></div></a>
            <?	} ?>
            <br />
            
			<div class="SubTitulos" style="margin-bottom:5px;"><?= $lbl_Follow ?>:</div>
            <table width="100%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td width="27%" align="center"><a href="http://www.facebook.com/pages/Buenos-Aires-Argentina/Travel-54/63364529344?ref=ts" target="_blank"><img src="imagen/varios/FaceBook_48x48.png" width="48" height="48" border="0" /></a></td>
                <td width="73%" align="left"><a href="http://twitter.com/Travel54" target="_blank"><img src="imagen/varios/Twitter_48x48.png" width="48" height="48" border="0" /></a></td>
              </tr>
            </table>
            <br />
            
            <div class="SubTitulos" style="margin-bottom:5px;"><?= $lbl_TagCloud ?>:</div>
            	<div style="padding:7px;">
				<?= $tagCloud ?>
           	</div>
