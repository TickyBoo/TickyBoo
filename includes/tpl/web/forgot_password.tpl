{*
 * %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 *}

<html>
<head>
<title>{!pwd_forgot!}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" >
<meta http-equiv="Content-Language" content="nl" >

<link rel="shortcut icon" href="images\favicon.ico" >
<link rel="icon" href="images\animated_favicon1.gif" type="image/gif" >
<link REL='stylesheet' HREF='style.php' TYPE='text/css' >

</head>
<body topmargin="0" leftmargin="0" bgcolor="#FFE2AE"> <br>
  <center>
    <table border="0" cellpadding="5" cellspacing="5" width="600" bgcolor='#ffefd2'>
      <tr>
        <td>
           <h2>{!forgot_password!}</h2>
        </td>
      </tr>
      <tr>
        <td valign=top>

{if $smarty.get.email}
  {if $user->forgot_password_f($smarty.get.email) }
    <div class='success'>{!pwd_is_sent!}.</div>
  {else}
    <div class='error'>{!pwd_err!}</div>
  {/if}
  <br><br>
</td>
</tr>
</table>
  <br>
  <button onclick="window.close();">Close</button>
{else}
  <form action='forgot_password.php' method='get'>
    <table width='100%' align='center'>
      <tr><td  colspan='2'>{!pwd_note!}<br><br></td></tr>
      <tr>
        <td>{!email!}</td>
        <td><input type='text' name='email' size='30'> &nbsp; <input type='submit' name='submit' value="{!pwd_send!}"></td>
      </tr>
    </table>
  </form>
 </td>
</tr>
</table>
{/if}
</center>
</body>
</html>
