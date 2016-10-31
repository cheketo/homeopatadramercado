<table width="212" border="0" cellpadding="0" cellspacing="5" bgcolor="#E5E5E5">
  <tr>
    <td bgcolor="#F7F7F9"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="50%">
		<a href="ca_carrito.php" target="_self">
		<img src="imagen/botones/cerrar_compra.jpg" width="79" height="29" border="0" />
		</a>
		
		</td>
        <td width="50%" class="cuadro_carrito_texto"><div id="carrito_total">Total: <strong>$<? 
		if($_SESSION['ca_total']){
			echo round($_SESSION['ca_total'],2);
		}else{
			echo "0.00";
		} ?></strong>
        </div>
		
		<div id="ver_carrito" style="height:15px; width:100%;">
			<a href="ca_carrito.php" >
				<span style="text-decoration:underline" class="cuadro_carrito_texto">ver productos</span>
			</a>
		</div>
		   
		  </td>
      </tr>
    </table></td>
  </tr>
</table>