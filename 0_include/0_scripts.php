
<script language="Javascript">
//FUNCIONES JAVASCRIPT
function popup(url,ancho,alto,scrollbars){
	var titulo = "PopUp";
	window.open(url, titulo, "toolbar=0,menubar=0,scrollbars=" + scrollbars + ",location=0,status=0,resizable=1,height=" + alto + ",width=" + ancho + ",top=0");
}
	
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
function confirm_eliminar(url){
	if (confirm('¿ Esta seguro que desea eliminar el registro ?')){
		window.location.href=(url)
	}
}
</script>
