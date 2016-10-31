<?	
	session_name("dreamsindesign_homeopatamercado");
	session_start();
	
	if($_SERVER["HTTP_HOST"] != 'localhost'){
		$mysql_user = "az000455";
		$mysql_password = "teno61SUsa";
		$mysql_base = "az000455_homeopatamercado";
		$mysql_host = "localhost";
		$dominioSite = "http://www.homeopatadramercado.com.ar/";
	
	}else{
		$mysql_user = "root";
		$mysql_password = "";
		$mysql_base = "homeopatamercado";
		$mysql_host = "localhost";
		
	}
	
	
	//Loguea al usuario en la base de datos
	$mysql_link = mysql_connect($mysql_host,$mysql_user,$mysql_password) or die ("<script>window.open('0_include/0_mysql_error.php?error=1','_self');</script>");
	
	//Establece la conexion con la base de datos
	$mysql_db_selected = mysql_select_db($mysql_base,$mysql_link) or die ("<script>window.open('0_include/0_mysql_error.php?error=2','_self');</script>");
	
	//Se usa para todo el sitio
	$idcarpeta = $_GET['idcarpeta'];
	$idseccion = $_GET['idseccion'];
	$idproducto = $_GET['idproducto'];
	
	if(!$_GET['ididioma']){
		$ididioma = 1;		
	}else{
		$ididioma = $_GET['ididioma'];
	}
	
	if(!$_GET['idsede']){
		$idsede = 1;		
	}else{
		$idsede = $_GET['idsede'];	
	}
	
	//da los datos del sitio, como ser el titulo, las palabras claves etc 
	if(!$_SESSION['rs_dato_sitio_session'] || $_SESSION['ididioma_session'] != $ididioma){
		$query_dato_sitio = "SELECT A.*
		FROM dato_sitio  A
		WHERE ididioma = '$ididioma' ";		
		$rs_dato_sitio = mysql_fetch_assoc(mysql_query($query_dato_sitio));	
		$_SESSION['rs_dato_sitio_session'] = $rs_dato_sitio;
	}else{
		$rs_dato_sitio = $_SESSION['rs_dato_sitio_session'];
	}
	
	//SETTING DE IDIOMA
	if(isset($_GET['ididioma'])){
		$_SESSION['ididioma_session'] = $_GET['ididioma'];		
	} else if(!$_SESSION['ididioma_session']){
		$_SESSION['ididioma_session'] = 1;
	}	
	
	//SETTING DE SUCURSAL
	if(isset($_GET['idsede'])){
		$_SESSION['idsede_session'] = $_GET['idsede'];
	} else if(!$_SESSION['idsede_session']){
		$_SESSION['idsede_session'] = 1;
	}
	
	//si el sitio esta en mantenimiento redirecciona con un mensaje
	if($rs_dato_sitio['mantenimiento']=='2'){
		echo "<script>window.open('0_mysql_error.php?color=FF0000','_self');</script>";//Error Rojo
	}
	
	//SI TIENE COOKIES SE LOGUEA AUTOMATICAMENTE
	if(isset($_COOKIE['login_web'])) {

		$login_web_datos = $_COOKIE['login_web'];
		$login_web = split("::",$login_web_datos);
		
		$query = "SELECT iduser_web, mail, nombre
		FROM user_web A	
		WHERE username ='$login_web[0]' AND password='$login_web[1]' AND estado = '1' ";
		$result = mysql_query($query);
		$cant = mysql_num_rows($result);
		
		if ($cant == 0)	{
			$error = "Usuario Invalido";
		}else{
			$row = mysql_fetch_row($result);
			$_SESSION['iduser_web_session']="$row[0]";
			$_SESSION['mail_session']="$row[1]";
			$_SESSION['nombre_session']="$row[2]";
			
			//SETEO COOKIE
			if($cookie){
				setcookie ("login_web", "$usuario::$password::$row[2]", time() + (7 * 86400), "","",0);
			}
			//FIN SETEAR COOKIE

		}

	}
	
	// Objetos utilizados:
	if(file_exists("objeto/obj0001.php")){
		include_once("objeto/obj0001.php"); //Foto
	}
	/*
	if(file_exists("objeto/obj0002.php")){
		include_once("objeto/obj0002.php"); //Galeria de imagenes
	}
	if(file_exists("objeto/obj0004.php")){
		include_once("objeto/obj0004.php"); //Barra (menu de la izquierda)
	}	
	*/
	if(file_exists("objeto/obj0005.php")){
		include_once("objeto/obj0005.php"); //Banner
	}	
	if(file_exists("objeto/obj0006.php")){
		include_once("objeto/obj0006.php"); //Titulo en flash
	}
	/*
	if(file_exists("objeto/obj0007.php")){
		//include_once("objeto/obj0007.php"); //Fotoin (botones carga imagenes con efecto)
	}
	*/
	if(file_exists("0_include/0_function.php")){
		include_once("0_include/0_function.php"); //Funciones varias
	}
	
	//XAJAX
	if(file_exists("js/xajax/xajax_core/xajax.inc.php")){
		require("js/xajax/xajax_core/xajax.inc.php");
	}
	
	
	//VARIABLES GLOBALES DEL SITIO SEGÚN EL IDIOMA
	switch($_SESSION['ididioma_session']){
		
		case 1: //ESPAÑOL
			
			//SECCIÓN: INICIO
			$banner_filename = "banner_home_esp.swf";
			$banner_bookhotel = "banner_bookyourhotel_esp.swf";
			
			$lbl_Top10 = "TOP 10 DESTINOS";
			$lbl_Hotel = "HOTELES DESTACADOS";
			$lbl_Blog = "ÚLTIMAS NOTICIAS DE NUESTRO BLOG!";
			
			$lbl_BsAs = "Buenos Aires";
			$lbl_BsAs_Slogan = "Tango, Noche & Estilo";
			$lbl_Cala = "El Calafate";
			$lbl_Cala_Slogan = "Su Majestad El Glaciar Perito Moreno";
			$lbl_Igua = "Iguazú";
			$lbl_Igua_Slogan = "Bienvenidos a la Selva!";
			$lbl_Bari = "Bariloche & Villa La Angostura";
			$lbl_Bari_Slogan = "Los Hermosos Lagos";
			$lbl_Mend = "Mendoza";
			$lbl_Mend_Slogan = "Vino en la Montaña";
			
			$lbl_Salt = "Salta & Jujuy";
			$lbl_Salt_Slogan = "La Encantadora Siesta";
			$lbl_Ushu = "Ushuaia";
			$lbl_Ushu_Slogan = "El Fin del Mundo";
			$lbl_PtoM = "Puerto Madryn";
			$lbl_PtoM_Slogan = "Salven Las Ballenas!";
			$lbl_PtaE = "Punta del Este";
			$lbl_PtaE_Slogan = "Glamour en la Playa!";
			$lbl_Colo = "Colonia del Sacramento";
			$lbl_Colo_Slogan = "El tunel del tiempo!";
			
			//SECCIÓN: CONTACTO
			$lbl_Oficina = "Nuestra Oficina";
			$lbl_FormContacto = "Formulario de Contacto";
			
			$lbl_Direccion = "Dirección";
			$lbl_TelFax = "Tel/Fax";
			$lbl_DirectTel = "US Teléfono Directo";
			$lbl_Nombre = "Nombre";
			$lbl_Pais = "País";
			$lbl_Mensaje = "Mensaje";
			
			$msj_OK = "Su mensaje ha sido enviado con éxito.<br />Muchas gracias por escribirnos. Le responderemos a la brevedad.";
			$msj_NO = "No se pudo enviar su mensaje.<br />Por favor, intente nuevamente más tarde.";
			
			$btn_enviar = "enviar";
			
			//SECCIÓN: RESERVAR
			$lbl_FormContactoReserva = "Formulario de Contacto / Reserva";
			$msg_Reserva = "Si desea realizar una reserva o consultar acerca de un Hotel o un Tour, complete el siguiente formulario y nos comunicaremos con usted a la brevedad. ";
			$lbl_NomApe = "Nombre y Apellido";
			$lbl_Tel = "Teléfono";
			$lbl_Nacionalidad = "Nacionalidad";
			$lbl_NumPeople = "Cantidad de personas";
			$lbl_Seleccionar = "Seleccione";
			$msj_Enviado = "Su mensaje ha sido enviado con éxito.";
			$msj_Error = "Ha habido un error al enviar la consulta. Por favor aguarde unos minutos e intente nuevamente.";
			
			$alert_01 = "Debe completar su Nombre y Apellido";
			$alert_02 = "Debe completar su e-mail";
			$alert_03 = "A su mail le falta el @";
			$alert_04 = "A su mail le falta el .com o .com.ar ";
			$alert_05 = "Debe completar su pais";
			$alert_06 = "Debe completar la cantidad de Personas";
			$alert_07 = "Debe completar las fechas de Check In y Check Out";
			$alert_08 = "Debe completar su mensaje";
			
			$btn_enviar2 = "Enviar";
			$btn_borrar = "Borrar";
			
			//SECIÓN: CARPETA_VER / SECCION_DETALLE
			$lbl_Fotos = "Fotos";
			$lbl_Vermas = "leer más";
			$lbl_Imprimir = "Imprimir";
			$lbl_Reservar = "¡RESERVAR AHORA!";
			$lbl_StarRating = "¿Qué te pareció ésta información?";
			
			//BLOG
			$btn_Share = "Compartir/Favoritos";
			$lbl_Pags = "Páginas";
			$lbl_10Post = "Últimos 10 Posts";
			$lbl_Follow = "Seguinos en";
			$lbl_TagCloud = "Nube de Tags";
			$btn_Volver = "Volver";
			
			break;
			
		case 2: //ENGLISH
			
			//SECCIÓN: INICIO
			$banner_filename = "banner_home_ing.swf";
			$banner_bookhotel = "banner_bookyourhotel_ing.swf";
			
			$lbl_Top10 = "TOP 10 DESTINATIONS";
			$lbl_Hotel = "FEATURED HOTELS";
			$lbl_Blog = "LATEST NEWS FROM OUR BLOG!";
			
			$lbl_BsAs = "Buenos Aires";
			$lbl_BsAs_Slogan = "Tango, Nightlife & Style";
			$lbl_Cala = "El Calafate";
			$lbl_Cala_Slogan = "Her Majesty The Perito Moreno Glacier";
			$lbl_Igua = "Iguazu Falls";
			$lbl_Igua_Slogan = "Welcome to the Jungle!";
			$lbl_Bari = "Bariloche & Villa La Angostura";
			$lbl_Bari_Slogan = "The Beautiful Lakes";
			$lbl_Mend = "Mendoza";
			$lbl_Mend_Slogan = "Wine in the Mountains ";
			
			$lbl_Salt = "Salta & Jujuy";
			$lbl_Salt_Slogan = "The Charming Siesta";
			$lbl_Ushu = "Ushuaia";
			$lbl_Ushu_Slogan = "The End of the World";
			$lbl_PtoM = "Puerto Madryn";
			$lbl_PtoM_Slogan = "Save the Whales!";
			$lbl_PtaE = "Punta del Este";
			$lbl_PtaE_Slogan = "Glamour on the beach!";
			$lbl_Colo = "Colonia del Sacramento";
			$lbl_Colo_Slogan = "The Perfect Day Trip!";
			
			//SECCIÓN: CONTACTO
			$lbl_Oficina = "Our Office";
			$lbl_FormContacto = "Contact Form";
			
			$lbl_Direccion = "Address";
			$lbl_TelFax = "Phone/Fax";
			$lbl_DirectTel = "US Direct Phone";
			$lbl_Nombre = "Name";
			$lbl_Pais = "Country";
			$lbl_Mensaje = "Message";
			
			$msj_OK = "Your message has been sent successfully.<br />Thank you very much for writing. We will contact you as soon as possible.";
			$msj_NO = "Could not send your message. <br /> Please try again later.";
			
			$btn_enviar = "send";
			
			//SECCIÓN: RESERVAR
			$lbl_FormContactoReserva = "Contact / Booking Form";
			$msg_Reserva = "If you want to book a Hotel or a Tour, or just ask about them, please fill the next form and we will contact you as soon as possible.";
			$lbl_NomApe = "Name and Last Name";
			$lbl_Tel = "Phone";
			$lbl_Nacionalidad = "Nacionality";
			$lbl_NumPeople = "Number of people";
			$lbl_Seleccionar = "Select";
			$msj_Enviado = "Your message has been sent successfully!";
			$msj_Error = "There was an error sending the message. Please wait a few minutes and try again.";
			
			$alert_01 = "You must complete your Name and Surname";
			$alert_02 = "You must complete your e-mail";
			$alert_03 = "There is missing the @ in your mail address";
			$alert_04 = "There is missing the '.com or .com.ar' in your mail address ";
			$alert_05 = "You must complete your country";
			$alert_06 = "You must complete the number of people";
			$alert_07 = "You must complete your the dates in Check In and Check Out";
			$alert_08 = "You must complete your message";
			
			$btn_enviar2 = "Send";
			$btn_borrar = "Clear";
			
			//SECIÓN: CARPETA_VER / SECCION_DETALLE
			$lbl_Fotos = "Photos";
			$lbl_Vermas = "read more";
			$lbl_Imprimir = "Print";
			$lbl_Reservar = "BOOK NOW!";
			$lbl_StarRating = "How much interesting did you found this information?";
			
			//BLOG
			$btn_Share = "Share/Bookmark";
			$lbl_Pags = "Pages";
			$lbl_10Post = "10 Latest Posts";
			$lbl_Follow = "Follow Us";
			$lbl_TagCloud = "Tag Cloud";
			$btn_Volver = "Back";
		
			break;
			
		case 3: //PORTUGUES
		
			//SECCIÓN: INICIO
			$banner_filename = "banner_home_por.swf";
			$banner_bookhotel = "banner_bookyourhotel_por.swf";
			
			$lbl_Top10 = "TOP 10 DESTINOS";
			$lbl_Hotel = "HOTÉIS DESTAQUES";
			$lbl_Blog = "ÚLTIMAS NOTÍCIAS DO NOSSO BLOG!";
			
			$lbl_BsAs = "Buenos Aires";
			$lbl_BsAs_Slogan = "Tango, Noite & Estilo";
			$lbl_Cala = "El Calafate";
			$lbl_Cala_Slogan = "Sua Majestade o Glaciar Perito Moreno";
			$lbl_Igua = "Iguaçu";
			$lbl_Igua_Slogan = "Bem-vindos à Selva!";
			$lbl_Bari = "Bariloche & Villa La Angostura";
			$lbl_Bari_Slogan = "Os Lagos Maravilhosos";
			$lbl_Mend = "Mendoza";
			$lbl_Mend_Slogan = "Vinho nas Montanhas";
			
			$lbl_Salt = "Salta & Jujuy";
			$lbl_Salt_Slogan = "A sesta encantadora";
			$lbl_Ushu = "Ushuaia";
			$lbl_Ushu_Slogan = "O Fim do Mundo";
			$lbl_PtoM = "Puerto Madryn";
			$lbl_PtoM_Slogan = "Salvem as Baleias!";
			$lbl_PtaE = "Punta del Este";
			$lbl_PtaE_Slogan = "Glamour na Praia !";
			$lbl_Colo = "Colonia del Sacramento";
			$lbl_Colo_Slogan = "O Túnel do Tempo!";
			
			//SECCIÓN: CONTACTO
			$lbl_Oficina = "Nosso Escritório";
			$lbl_FormContacto = "Formulário de Contato";
			
			$lbl_Direccion = "Endereço";
			$lbl_TelFax = "Tel/Fax";
			$lbl_DirectTel = "US Direct Telefone";
			$lbl_Nombre = "Nome";
			$lbl_Pais = "País";
			$lbl_Mensaje = "Mensagem";
			
			$msj_OK = "Sua mensagem foi enviada com sucesso. <br /> Muito obrigado por escrever. Entraremos em contato o mais breve possível.";
			$msj_NO = "Não foi possível enviar sua mensagem. <br /> Por favor, tente novamente mais tarde.";
			
			$btn_enviar = "enviar";
			
			//SECCIÓN: RESERVAR
			$lbl_FormContactoReserva = "Formulário de Contato / Reserva";
			$msg_Reserva = "Se você quiser reservar ou obter informações sobre um hotel ou uma excursão, preencha o formulário abaixo e entraremos em contato em breve. ";
			$lbl_NomApe = "Nome e Apelido";
			$lbl_Tel = "Telefone";
			$lbl_Nacionalidad = "Nacionalidade";
			$lbl_NumPeople = "Número de pessoas";
			$lbl_Seleccionar = "Selecionar";
			$msj_Enviado = "Sua mensagem foi enviada com sucesso!";
			$msj_Error = "Houve um erro ao enviar a mensagem. Por favor, aguarde alguns minutos e tente novamente.";
			
			$alert_01 = "Você deve preencher seu nome e sobrenome";
			$alert_02 = "Você deve preencher seu e-mail";
			$alert_03 = "A su mail le falta el @";
			$alert_04 = "A su mail le falta el .com o .com.ar ";
			$alert_05 = "Você deve completar o seu país";
			$alert_06 = "Você deve completar o número de pessoas";
			$alert_07 = "As datas devem preencher o Check In e Check Out";
			$alert_08 = "Você deve completar a sua mensagem";
			
			$btn_enviar2 = "Enviar";
			$btn_borrar = "Excluir";
			
			//SECIÓN: CARPETA_VER / SECCION_DETALLE
			$lbl_Fotos = "Fotos";
			$lbl_Vermas = "leia mais";
			$lbl_Imprimir = "Impressão";
			$lbl_Reservar = "¡RESERVE AGORA!";
			$lbl_StarRating = "¿Como você achou essa informação?";
			
			//BLOG
			$btn_Share = "Compartilhar/Favoritos";
			$lbl_Pags = "Páginas";
			$lbl_10Post = "Últimos 10 Posts";
			$lbl_Follow = "Siga-nos";
			$lbl_TagCloud = "Nuvem de Tags";
			$btn_Volver = "Retorno";
			
			break;
			
	}
	
	
?>