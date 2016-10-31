<?	

	function variable_ididioma_idsede($link_url){
			//quita el idsede y ididioma
			$link_a =ereg_replace('ididioma=[0-9]', '', $link_url);
			$link_b =ereg_replace('idsede=[0-9]', '', $link_a);					
			
			//split de las variables	
			$link_c = split("\?",$link_b);
			$link_d = split("&",$link_c[1]);
			$link_d_rec = '';
			
			//genera una variable con las variables que vienen por get
			for($i=0;$i<count($link_d);$i++){
				if($link_d[$i]!=''){
					 $link_d_rec .= "&".$link_d[$i];
				}
			}
			return $link_con = $link_c[0].'?ididioma='.$_SESSION["ididioma_session"].'&idsede='.$_SESSION["idsede_session"].$link_d_rec;			
	}
	
	function convertHTMLToLatin1($str) {
    $html_entities = array (
        "&amp;" => "&",
		"&aacute;" => "�",
		"&Acirc;" => "�",
		"&acirc;" => "�",
		"&Agrave;" => "�",
		"&agrave;" => "�",
		"&Atilde;" => "�",
		"&atilde;" => "�",
		"&Aacute;" => "�",
		"&aacute;" => "�",
		
		
        "&Ccedil;" =>  "�",     #latin capital letter C
        "&ccedil;" =>  "�",     #latin small letter c
        "&Eacute;" =>  "�",     #latin capital letter E
        "&eacute;" =>  "�",     #latin small letter e
        "&Egrave;" =>  "�",     #latin capital letter E
		"&Oacute;" =>  "�",
		"&oacute;" =>  "�",
		"&Ograve;" =>  "�",
		"&ograve;" =>  "�",
        "&Ugrave;" =>  "�",     #latin capital letter U
        "&ugrave;" =>  "�",     #latin small letter u

		"&bull;" =>  "�",       #latin capital letter Y
		"&raquo;" =>  "�",      #latin capital letter Y
		"&laquo;" =>  "�",
		"&middot;" =>  "�"
    );

    foreach ($html_entities as $key => $value) {
        $str = str_replace($key, $value, $str);
    }
    return $str;
} 

?>