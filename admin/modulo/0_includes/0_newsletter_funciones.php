<?

function convertLatin1ToHtml($str) {
    $html_entities = array (
        "&" =>  "&amp;",        #ampersand   
        "á" =>  "&aacute;",     #latin small letter a
        "Â" =>  "&Acirc;",      #latin capital letter A
        "â" =>  "&acirc;",      #latin small letter a
        "Æ" =>  "&AElig;",      #latin capital letter AE
        "æ" =>  "&aelig;",      #latin small letter ae
        "À" =>  "&Agrave;",     #latin capital letter A
        "à" =>  "&agrave;",     #latin small letter a
        "Å" =>  "&Aring;",      #latin capital letter A
        "å" =>  "&aring;",      #latin small letter a
        "Ã" =>  "&Atilde;",     #latin capital letter A
        "ã" =>  "&atilde;",     #latin small letter a
        "Ä" =>  "&Auml;",       #latin capital letter A
        "ä" =>  "&auml;",       #latin small letter a
        "Ç" =>  "&Ccedil;",     #latin capital letter C
        "ç" =>  "&ccedil;",     #latin small letter c
        "É" =>  "&Eacute;",     #latin capital letter E
        "é" =>  "&eacute;",     #latin small letter e
        "Ê" =>  "&Ecirc;",      #latin capital letter E
        "ê" =>  "&ecirc;",      #latin small letter e
        "È" =>  "&Egrave;",     #latin capital letter E
		"Ó" =>  "&Oacute;",
		"ó" =>  "&oacute;",
		"Ò" =>  "&Ograve;",
		"ò" =>  "&ograve;",
		"Ô" =>  "&Ocirc;",     
        "ô" =>  "&ocirc;",
        "û" =>  "&ucirc;",      #latin small letter u
        "Ù" =>  "&Ugrave;",     #latin capital letter U
        "ù" =>  "&ugrave;",     #latin small letter u
        "Ü" =>  "&Uuml;",       #latin capital letter U
        "ü" =>  "&uuml;",       #latin small letter u
        "Ý" =>  "&Yacute;",     #latin capital letter Y
        "ý" =>  "&yacute;",     #latin small letter y
        "ÿ" =>  "&yuml;",       #latin small letter y
        "Ÿ" =>  "&Yuml;",       #latin capital letter Y
		"•" =>  "&bull;",       #latin capital letter Y
		"»" =>  "&raquo;",      #latin capital letter Y
		"«" =>  "&laquo;",
		"·" =>  "&middot;",
    );

    foreach ($html_entities as $key => $value) {
        $str = str_replace($key, $value, $str);
    }
    return $str;
} 


?>