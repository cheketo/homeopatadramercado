<? 	
	//INCLUDES
	include ("../../0_mysql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? 
	
	//VARIABLES
	$accion = $_POST['accion'];
	$idca_forma_pago = $_GET['idca_forma_pago'];
	$titulo = $_POST['titulo'];
	$descripcion = str_replace(' src="../../../imagen', ' src="imagen',  stripslashes($_POST['descripcion']));
	
		
	//ACTUALIZAR			
	if( $accion == "actualizar" ){
	
		$query_modficacion = "UPDATE ca_forma_pago SET
		  titulo_forma_pago = '$titulo'
		, descripcion = '$descripcion'
		WHERE idca_forma_pago = '$idca_forma_pago'
		LIMIT 1";
		mysql_query($query_modficacion);
		
		echo "<script>document.location.href= 'ca_forma_pago_ver.php';</script>";
		
	
	};
		
	//CONSULTA DE PROVINCIA
	$query = "SELECT * 
	FROM ca_forma_pago 
	WHERE idca_forma_pago = '$idca_forma_pago' ";
	$rs_forma_pago = mysql_fetch_assoc(mysql_query($query));
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link href="../../css/0_body.css" rel="stylesheet" type="text/css" />
<link href="../../css/0_fonts.css" rel="stylesheet" type="text/css" />
<? include("../../0_head.php"); ?>

<script language="javascript">

	function validar_form_preguntas(){
	var formulario = document.form_titular;
	var flag = true;
	var error = "";
		
		if(formulario.titulo.value == ''){
			error = error + "Debe ingresar el titulo de la forma de pago.\n" ;
			flag = false;
		}
		
		if(flag == true){
			formulario.accion.value = "actualizar";
			formulario.submit();
		}else{
			alert(error);
		}
	
	};
	
</script>

<!-- TinyMCE -->
<script type="text/javascript" src="../../js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

	var tinyMCEImageList = new Array(
		 ["American Express", "../../../imagen/logos/ic-fp-american-express.gif"]
		,["Banco de la Nacion Argentina", "../../../imagen/logos/ic-fp-banco-de-la-nacion-argentina.gif"]
		,["Banco Santander", "../../../imagen/logos/ic-fp-banco-santander-rio.gif"]
		,["Banco Galicia", "../../../imagen/logos/ic-fp-banco-galicia.gif"]
		,["Banco Frances", "../../../imagen/logos/ic-fp-bbva-banco-frances.gif"]
		,["Citibank", "../../../imagen/logos/ic-fp-citibank.gif"]
		,["Correo Argentino", "../../../imagen/logos/ic-fp-correo-argentino.gif"]
		,["Debito Automatico", "../../../imagen/logos/ic-fp-debito-automatico.gif"]
		,["Dinero Mail", "../../../imagen/logos/ic-fp-dineromail.gif"]
		,["MasterCard", "../../../imagen/logos/ic-fp-mastercard.gif"]
		,["Moneybookers", "../../../imagen/logos/ic-fp-moneybookers.gif"]
		,["Pago Fácil", "../../../imagen/logos/ic-fp-pagofacil.gif"]
		,["Pago Mis Cuentas", "../../../imagen/logos/ic-fp-pagomiscuentas.gif"]
		,["PayPal", "../../../imagen/logos/ic-fp-paypal-verified.gif"]
		,["RapiPago", "../../../imagen/logos/ic-fp-rapipago.gif"]
		,["Visa", "../../../imagen/logos/ic-fp-visa.gif"]
		,["Western Union", "../../../imagen/logos/ic-fp-western-union.gif"]
	);

	// O2k7 skin (silver)
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "descripcion",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "../../../css/0_fonts_tiny.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : tinyMCEImageList,
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});

</script>
<!-- /TinyMCE -->

</head>
<body>
<div id="header"><? include("../../0_top.php"); ?></div>
<div id="wrapper">
	<div id="marco_izq"></div>
	<div id="navigation"><? include("../../modulo/0_barra/0_barra.php"); ?></div>
	<div id="content">
	  <table width="100%" border="0" cellpadding="0" cellspacing="12" class="fondo_tabla_principal">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="40" valign="bottom" class="titulo_grande_bold">Formas de pago  - Editar </td>
              </tr>
              <tr>
                <td height="20" valign="top" class="titulo_grande_bold"><hr size="1" noshade="noshade" style="color:#CCCCCC;" /></td>
              </tr>
            </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><form action="" method="post" name="form_titular" id="form_titular">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                        <tr >
                          <td height="40" bgcolor="#d8f6ee" class="titulo_medio_bold">Editar forma de pago:
                            <input name="accion" type="hidden" id="accion" value="0" /></td>
                        </tr>
                        <tr>
                          <td valign="top" bgcolor="#eafcf7"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td width="14%" align="left" valign="middle" class="detalle_medio">Titulo:</td>
                                <td width="86%" align="left" valign="top"><label>
                                  <input name="titulo" type="text" class="detalle_medio" id="titulo" value="<?= $rs_forma_pago['titulo_forma_pago'] ?>" size="60" />
                                </label></td>
                              </tr>
                              <tr>
                                <td width="14%" align="left" valign="top" class="detalle_medio">Descripcion:</td>
                                <td align="left" valign="top" class="detalle_medio">                              </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right" valign="top" class="detalle_medio"><textarea name="descripcion" style="width:100%; height:300px;" class="detalle_medio" id="descripcion"><?= str_replace('src="imagen','src="../../../imagen', html_entity_decode($rs_forma_pago['descripcion'], ENT_QUOTES)); ?></textarea></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right" valign="top" class="detalle_medio_bold"><input name="Button" type="button" class="detalle_medio_bold" onclick="validar_form_preguntas();" value=" Modificar &raquo; " /></td>
                              </tr>
                          </table></td>
                        </tr>
                    </table>
                    </form></td>
                </tr>
            </table></td>
        </tr>
      </table>
	</div>
	<div id="marco_der"></div>
</div>
<div id="footer">
</div>
</body>
</html>