{*                  %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
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
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 * <html>
<head>
<title>{!pwd_forgot!}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" >
<meta http-equiv="Content-Language" content="nl" >

<link rel="shortcut icon" href="images\favicon.ico" >
<link rel="icon" href="images\animated_favicon1.gif" type="image/gif" >
<link rel='stylesheet' href='style.php' type='text/css' >

</head>
<body topmargin="0" leftmargin="0" style='align:left;'> <br>
<center> 
*}
  <h2>{!forgot_password!}</h2>
  <form action='index.php' method='post'>
    {ShowFormToken name='resendpassword'}
    {gui->hidden name='action value='resendpassword'}
    <table border="0" cellpadding="5" cellspacing="5" width="100%" class="login_table"  >
      <tr><td colspan='2'>{!pwd_note!}<br><br></td></tr>
      <tr>
        <td>{!user_email!}</td>
        <td>
          <input type='text' name='email' size='30'> &nbsp;
          <input type='submit' name='submit' value="{!pwd_send!}">
        </td>
      </tr>
    </table>
  </form>
{*
</center>

</body>
</html> *}