<?
	/* Includes in HEAD */
	
	//SCRIPTS
	if (file_exists("modulo/0_includes/0_scripts.php")){
		require_once("modulo/0_includes/0_scripts.php"); //foto		
	}else if(file_exists("../../modulo/0_includes/0_scripts.php")){
		require_once("../../modulo/0_includes/0_scripts.php"); //foto		
	}

	if (file_exists("js/embeddedcontent.js")){ 
			echo '<script type="text/javascript" src="js/embeddedcontent.js" defer="defer"></script>';
	 }else if(file_exists("../../js/embeddedcontent.js")){ 
			echo '<script type="text/javascript" src="../../js/embeddedcontent.js" defer="defer"></script>';
	}
	
?>

<? /* if(file_exists("../../js/ie8/ie8.js")){ ?>
<!--[if lt IE 8]><script src="js/ie8/ie8.js" type="text/javascript"></script><![endif]-->
<? }else if (file_exists("/js/ie8/ie8.js")){ ?>
<!--[if lt IE 8]><script src="../../js/ie8/ie8.js" type="text/javascript"></script><![endif]-->
<? } */?>





