<table width="212" border="0" cellpadding="0" cellspacing="5" bgcolor="#E5E5E5">
  <tr>
    <td bgcolor="#F7F7F9"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
        <? if($_SESSION['mail_session'] != ""){ ?>
        <tr>
          <td><table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
              <tr>
                <td width="86%" align="left" class="cuadro_login_texto">Bienvenido/a <strong>
                  <?= $_SESSION['nombre_session'] ?>
                  ! <br />
                  N&uacute;m. Cliente:
                  000<?= $_SESSION['iduser_web_session'] ?></strong></td>
                <td width="14%" align="right" class="copete_a"><a href="logout.php" target="_self"><img src="imagen/varios/logout.png" width="19" height="18" border="0" /></a></td>
              </tr>
              <tr>
                <td align="left" class="cuadro_login_texto">&nbsp; <img src="imagen/iconos/flecha_cursos.gif" width="5" height="5" />&nbsp; <a href="usuario_editar.php" target="_self"><span class="cuadro_login_texto"><u>Datos personales</u></span></a> </td>
                <td width="14%" align="right" class="copete_a">&nbsp;</td>
              </tr>
			 <?  if($rs_dato_sitio['ca_carrito_usar'] == 1){ ?>
              <tr>
                <td align="left" class="cuadro_login_texto">&nbsp; <img src="imagen/iconos/flecha_cursos.gif" width="5" height="5" />&nbsp; <a href="ca_mis_pedidos.php" target="_self"><span class="cuadro_login_texto"><u>Mis pedidos</u></span></a></td>
                <td width="14%" align="right" class="copete_a">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" class="cuadro_login_texto">&nbsp; <img src="imagen/iconos/flecha_cursos.gif" width="5" height="5" />&nbsp; <a href="ca_informar_pago.php" target="_self"><span class="cuadro_login_texto"><u>Informar mis pagos</u></span></a></td>
                <td width="14%" align="right" class="copete_a">&nbsp;</td>
              </tr>
			  <? } ?>
          </table></td>
        </tr>
        <? }else{ ?>
        <tr>
          <td><table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="cuadro_login_texto">
              <tr>
                <td width="86%" align="left" ><a href="registro.php?form=3"><strong>Registrarse</strong></a></td>
                <td width="14%" align="right" ><a href="registro.php?form=3"><img src="imagen/varios/registro_flecha_blanco.jpg" width="20" height="20" border="0" /></a></td>
              </tr>
              <tr>
                <td width="86%" align="left" bgcolor="#EEEEEE">Si ya se encuentra registrado. <a href="login.php"><strong>Ingrese aqu&iacute;. </strong></a></td>
                <td width="14%" align="right" bgcolor="#EEEEEE"><a href="login.php"><img src="imagen/varios/registro_flecha.jpg" width="20" height="20" border="0" /></a></td>
              </tr>
          </table></td>
        </tr>
        <? } ?>
      </table></td>
  </tr>
</table>
