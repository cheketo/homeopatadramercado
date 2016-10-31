<script language="Javascript">

function popup(url,ancho,alto){
	var titulo = "PopUp";
	window.open(url, titulo, "toolbar=0,menubar=0,scrollbars=yes,location=0,status=0,resizable=1,height=" + alto + ",width=" + ancho + ",top=0");
};
	
function esNumerico(campo){

   var caracteresValidos = "0123456789";
   var esNumero = true;
   var caracter;

   for (i = 0; i < campo.length && esNumero == true; i++){
   
      caracter = campo.charAt(i); 
      if (caracteresValidos.indexOf(caracter) == -1){
         esNumero = false;
      }
   }
   return esNumero;
};

function esAlfanumerico(campo){

   var caracteresValidos = "0123456789qwertyuiopasdfghjklñzxcvbnmQWERTYUIOPASDFGHJKLÑZXCVBNM";
   var ok = true;
   var caracter;

   for (i = 0; i < campo.length && ok == true; i++){
   
      caracter = campo.charAt(i); 
      if (caracteresValidos.indexOf(caracter) == -1){
         ok = false;
      }
   }
   return ok;
};

function confirm_eliminar(url){
	if (confirm('¿ Está seguro que desea enviar el registro a la papelera ?')){
		window.location.href=(url)
	}
};

</script>