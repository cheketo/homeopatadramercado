<? 
		$cabeceras ="MIME-Version: 1.0\r\n";
		$cabeceras.="Content-type: text/html; charset=iso-8859-1\r\n";
		$cabeceras.="From: "."consultas@homeopatadramercado.com.ar"."\r\n";
		$cabeceras.="Reply-To: ".$_POST['email']."\r\n";
		$cabeceras.="Subject: Mail de testeo "."pepe@mail.com"."\r\n";


		$enviado=mail("alejandro@didstudio.com.ar", "mail de teste", "mail de testeo", $cabeceras); 

			if($enviado == true){
				echo "<script>alert('enviado=ok');</script>";
			 }


?>