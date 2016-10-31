<?

	function html_orden_compra(){
	
	$html_orden_compra = '<table width="430" cellspacing="0" cellpadding="6"  bgcolor="#FFFFFF" style="border:1px solid #CCCCCC;">
                  <tr>
                    <td colspan="3"><table width="100%" border="0" cellpadding="6" cellspacing="0" class="tabla_producto_carrito">
                      <tr>
					    <td width="60%" ><span class="nombre_empresa_orden_compra">
					    '.$_SESSION['rs_dato_sitio_session']['emp_nombre'].'
					    </span><br />  
                        <span class="cuadro_login_texto">
						'.$_SESSION['rs_dato_sitio_session']['emp_datos_generales'].'</span></td>
                        <td width="8%" align="center" valign="top" bgcolor="#F5F5F5" class="nombre_empresa_orden_compra style1">X</td>
                        <td width="32%" align="right" valign="bottom"><p class="cuadro_login_texto">Fecha: <strong>'.date("d/m/Y").'</strong><br />
                        No v&aacute;lido como factura. </p>                          </td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="3"><table width="100%" border="0" cellpadding="1" cellspacing="0">
                      <tr>
                        <td width="16%" align="left" valign="middle" class="carpeta_producto_titulo_01">Nombre:</td>
                        <td colspan="3" align="left" valign="middle" ><div class="cuadro_login_texto" style="padding:3px; border-bottom:1px dashed #666666; height:16px;">'.$_SESSION['ca_contacto_nombre'].' '.$_SESSION['ca_contacto_apellido'].'</div></td>
                        </tr>';
						
							$idca_domicilio_envio = $_SESSION['ca_iddomicilio_envio'];
							$query_envio = "SELECT calle, numero, localidad
							FROM ca_domicilio
							WHERE idca_domicilio = '$idca_domicilio_envio' ";
							$rs_envio = mysql_fetch_assoc(mysql_query($query_envio));
	$html_orden_compra .= '					
                      <tr>
                        <td align="left" valign="middle" class="carpeta_producto_titulo_01">Domicilio:</td>
                        <td width="41%" align="left" valign="middle" class="cuadro_login_texto" ><div style="padding:3px; border-bottom:1px dashed #666666; overflow:hidden; height:16px;">'.$rs_envio['calle'].' '.$rs_envio['numero'].'</div></td>
                        <td width="17%" align="right" valign="bottom" class="carpeta_producto_titulo_01">Localidad:</td>
                        <td width="26%" align="left" valign="middle" class="cuadro_login_texto" ><div style="padding:3px; border-bottom:1px dashed #666666; overflow:hidden; height:16px;">&nbsp;'.$rs_envio['localidad'].'</div></td>
                      </tr>
                      <tr>
                        <td align="left" valign="middle" class="carpeta_producto_titulo_01">Telefono:</td>
                        <td colspan="3" align="left" valign="middle" class="cuadro_login_texto" ><div style="padding:3px; border-bottom:1px dashed #666666; height:16px;">'.$_SESSION['ca_contacto_telefono'].'</div></td>
                        </tr>
                    </table>
                      <br /></td>
                  </tr>
                  <tr>
                    <td width="14%" bgcolor="#F5F5F5" class="carpeta_producto_titulo_01"><strong>Cantidad</strong></td>
                    <td width="63%" bgcolor="#F5F5F5" class="carpeta_producto_titulo_01"><strong>Descripci&oacute;n</strong></td>
                    <td width="23%" bgcolor="#F5F5F5" class="carpeta_producto_titulo_01"><strong>Precio Unitario </strong></td>
                  </tr>';

	$total = 0;
	$i = 1;
	while($i < $_SESSION['ca_contador']){
		
		$id = $_SESSION['ca_idproducto'][$i];
		$query_producto = "SELECT B.titulo
		FROM producto A 
		INNER JOIN producto_idioma_dato B ON A.idproducto = B.idproducto 
		WHERE A.idproducto = '$id'";
		$rs_producto = mysql_fetch_assoc(mysql_query($query_producto));
		
		$ca_subtotal += $_SESSION['ca_precio_unitario'][$i] * $_SESSION['ca_cantidad'][$i];
					
				  
		$html_orden_compra .= '	
                  <tr>
                    <td align="center" valign="top" class="cuadro_login_texto"><strong>
                      '.$_SESSION['ca_cantidad'][$i].'
                    </strong></td>
                    <td align="left" valign="top" class="cuadro_login_texto">'.$rs_producto['titulo'].'</td>
                    <td align="left" valign="top" class="carpeta_producto_titulo_01">$ '.round($_SESSION['ca_precio_unitario'][$i],2).'.-</td>
                  </tr>';
				
		$i++; 
	}
				  
	$html_orden_compra .= '	
                  <tr>
                    <td>&nbsp;</td>
                    <td align="right" class="carpeta_producto_titulo_01">Gastos de envio: </td>
                    <td align="left" class="carpeta_producto_titulo_01"><em>A confirmar. </em></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="right" class="carpeta_producto_titulo_01"><strong>Total: </strong></td>
                    <td bgcolor="#EEEEEE" class="carpeta_producto_titulo_01"><strong>$
                        '.round($ca_subtotal, 2).'
                    </strong></td>
                  </tr>
                </table>';
				
	return $html_orden_compra;
	
	};
	
	
	function html_datos_envio(){
	
	$html_datos_envio = '<table cellspacing="0" width="430" cellpadding="6" style="border:1px solid #CCCCCC;">
                    <tr>
                      <td width="100%" bgcolor="#F7F7F7"><table width="100%" border="0" cellpadding="6" cellspacing="0" class="tabla_producto_carrito">
                          <tr>
                            <td class="carpeta_producto_titulo_01" ><strong>Direcci&oacute;n de env&iacute;o </strong>';
							
							
								$idca_domicilio_envio = $_SESSION['ca_iddomicilio_envio'];
								$query_envio = "SELECT *
								FROM ca_domicilio
								WHERE idca_domicilio = '$idca_domicilio_envio' ";
								$rs_envio = mysql_fetch_assoc(mysql_query($query_envio));
								
	$html_datos_envio .= '</td>
                            </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7F7F7"><table width="100%" border="0" cellpadding="1" cellspacing="0">
                          <tr>
                            <td align="left" valign="top" class="cuadro_login_texto" >
                              '.$rs_envio['calle'].' '.$rs_envio['numero'];
							  
                               if($rs_envio['piso']){ $html_datos_envio .= ' - Piso: '.$rs_envio['piso']; }
                               if($rs_envio['depto']){ $html_datos_envio .= ' - Depto: '.$rs_envio['depto']; } 
                               if($rs_envio['edificio']){ $html_datos_envio .= ' - Edificio: '.$rs_envio['edificio']; } 
    $html_datos_envio .= '<br />';
                               
							  
							  if($rs_envio['idpais']){
							  	
								$query_pais = "SELECT titulo
								FROM pais
								WHERE idpais = '$rs_envio[idpais]' ";
								$rs_pais = mysql_fetch_assoc(mysql_query($query_pais));
								
							  	$html_datos_envio .= $rs_pais['titulo'].". "; 
							  }
							  
							  if($rs_envio['idpais_provincia']){
							  	
								$query_pais_provincia = "SELECT titulo
								FROM pais_provincia
								WHERE idpais_provincia = '$rs_envio[idpais_provincia]' ";
								$rs_pais_provincia = mysql_fetch_assoc(mysql_query($query_pais_provincia));
								
							  	$html_datos_envio .= $rs_pais_provincia['titulo'].". "; 
							  } 
							  
							  
                               if($rs_envio['cp']){ $html_datos_envio .= " - Cod. Postal: ".$rs_envio['cp']; } 
                               if($rs_envio['localidad']){ $html_datos_envio .= " <br>Localidad: ".$rs_envio['localidad']; } 
							   
	$html_datos_envio .= '	 </td>
                            </tr>
                        </table>
                          </td>
                    </tr>
                  </table>';
				  
	return $html_datos_envio;
	
	};
	
	
	function html_datos_facturacion(){
	
	$html_datos_facturacion = '<table width="430" cellspacing="0" cellpadding="6" style="border:1px solid #CCCCCC;">
                    <tr>
                      <td width="100%" bgcolor="#F7F7F7"><table width="100%" border="0" cellpadding="6" cellspacing="0" class="tabla_producto_carrito">
                          <tr>
                            <td class="carpeta_producto_titulo_01" ><strong>Datos de Facturaci&oacute;n:  ';
                             
								$idca_domicilio_facturacion = $_SESSION['ca_iddomicilio_facturacion'];
								$query_facturacion = "SELECT *
								FROM ca_domicilio
								WHERE idca_domicilio = '$idca_domicilio_facturacion' ";
								$rs_facturacion = mysql_fetch_assoc(mysql_query($query_facturacion));
								
							
   $html_datos_facturacion .= ' </strong></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7F7F7"><table width="100%" border="0" cellpadding="1" cellspacing="0">
                          <tr>
                            <td width="16%" align="left" valign="bottom" ><span class="cuadro_login_texto">';
							
                              $html_datos_facturacion .= $rs_facturacion['calle']." ".$rs_facturacion['numero'];
							  
                              if($rs_facturacion['piso']){ $html_datos_facturacion .= " - Piso: ".$rs_facturacion['piso']; }
                              if($rs_facturacion['depto']){ $html_datos_facturacion .= " - Depto: ".$rs_facturacion['depto']; } 
                              if($rs_facturacion['edificio']){ $html_datos_facturacion .= " - Edificio: ".$rs_facturacion['edificio']; } 
   $html_datos_facturacion .= '<br />';
                               
							  
							  if($rs_facturacion['idpais']){
							  	
								$query_pais = "SELECT titulo
								FROM pais
								WHERE idpais = '$rs_facturacion[idpais]' ";
								$rs_pais = mysql_fetch_assoc(mysql_query($query_pais));
								
							  	$html_datos_facturacion .= $rs_pais['titulo'].". "; 
							  }
							  
							  if($rs_facturacion['idpais_provincia']){
							  	
								$query_pais_provincia = "SELECT titulo
								FROM pais_provincia
								WHERE idpais_provincia = '$rs_facturacion[idpais_provincia]' ";
								$rs_pais_provincia = mysql_fetch_assoc(mysql_query($query_pais_provincia));
								
							  	$html_datos_facturacion .= $rs_pais_provincia['titulo'].". "; 
							  } 
							  
							  
                              if($rs_facturacion['cp']){ $html_datos_facturacion .= " - Cod. Postal: ".$rs_facturacion['cp']; } 
                              if($rs_facturacion['localidad']){ $html_datos_facturacion .= " <br>Localidad: ".$rs_facturacion['localidad']; } 
							  
   $html_datos_facturacion .= '<br />';
                              
							  
							  if(!$rs_facturacion['idca_tipo_contribuyente']){ 
							  		$html_datos_facturacion .= " <br>Tipo Contribuyente: Consumidor Final.";
							  }else{
							  		$query_tipo = "SELECT *
									FROM ca_tipo_contribuyente
									WHERE idca_tipo_contribuyente = '$rs_facturacion[idca_tipo_contribuyente]'";
									$rs_tipo = mysql_fetch_assoc(mysql_query($query_tipo));
									$html_datos_facturacion .= " <br />Tipo Contribuyente: ".$rs_tipo['titulo'].".";
							  }
							  
							  if($rs_facturacion['denominacion']){ $html_datos_facturacion .= " <br>Empresa: ".$rs_facturacion['denominacion']; } 
                              if($rs_facturacion['cuit']){ $html_datos_facturacion .= " <br>CUIT: ".$rs_facturacion['cuit']; } 
   $html_datos_facturacion .= '</span></td>
                            </tr>
                      </table></td>
                    </tr>
                  </table>';
	return $html_datos_facturacion;
	
	};
	
	
	function html_forma_pago(){
	
	$html_forma_pago = '<table width="430" cellspacing="0" cellpadding="6" style="border:1px solid #CCCCCC;">
                    <tr>
                      <td width="100%" bgcolor="#F7F7F7"><table width="100%" border="0" cellpadding="6" cellspacing="0" class="tabla_producto_carrito">
                          <tr>
                            <td class="carpeta_producto_titulo_01" ><strong>Forma de Pago  </strong>';
                                
								$idca_forma_pago = $_SESSION['ca_idforma_pago'];
								
								$query_forma_pago = "SELECT *
								FROM ca_forma_pago
								WHERE idca_forma_pago = '$idca_forma_pago' ";
								$rs_forma_pago = mysql_fetch_assoc(mysql_query($query_forma_pago));
								
	$html_forma_pago .= '</td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7F7F7"><table width="100%" border="0" cellpadding="1" cellspacing="0">
                          <tr>
                            <td align="left" valign="top" class="cuadro_login_texto" ><strong>'.$rs_forma_pago['titulo_forma_pago'].'</strong>
                                <br />
                                <br />
                                '.$rs_forma_pago['descripcion'].'
                                <br />
                                <hr size="1" />
                                <strong>Datos de Contacto:</strong><br /><br />
								Nombre y apellido: '.$_SESSION['ca_contacto_nombre'].'<br />';
								
								if($_SESSION['ca_autorizacion_recibo'] != ""){ 
									$html_forma_pago .= "<i><br>Se autoriza a cualquier persona que se encuentre en el domicilio de envío a recibir el pedido.<br><br></i>"; 
								} 
								
   $html_forma_pago .= ' 		Telefono: '.$_SESSION['ca_contacto_telefono'].'<br />
								Movil: '.$_SESSION['ca_contacto_movil'].'<br />
								Horario: '.$_SESSION['ca_contacto_horario'].'<br />
								Comentario: <br>'.$_SESSION['ca_contacto_comentario'].'<br />
                            </td>
                          </tr>
                      </table></td>
                    </tr>
                  </table>';
				  
	return $html_forma_pago;
	
	};
	
	function estado_pedido($idestado){
	
		$query = "SELECT titulo, descripcion
		FROM ca_estado_pedido
		WHERE idca_estado_pedido = '$idestado' ";
		$rs_estado_pedido = mysql_fetch_assoc(mysql_query($query));
	
		return $rs_estado_pedido;
	}
	
	function estado_pago($idestado_pago){
		
		switch($idestado_pago){
			case 1: $estado_pago = "No pagado."; break;
			case 2: $estado_pago = "Sin confirmar."; break;
			case 3: $estado_pago = "Pago Confirmado."; break;
			case 4: $estado_pago = "Informe de pago rechazado. No se pudo confirmar su pago."; break;
		}
	
		return $estado_pago;
	}

?>