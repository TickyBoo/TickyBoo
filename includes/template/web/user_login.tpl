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
 *}
{if $smarty.get.action eq 'login'}
	{user->login username=$smarty.post.username password=$smarty.post.password uri=$smarty.post.uri}
{elseif $smarty.get.action eq 'logout'}
	{user->logout}
{/if}
{if !$user->logged}

  <form method='post' action='index.php' style='margin-top:0px;'>
    <input type="hidden" name="action" value="login">
    {ShowFormToken name='login'}

    {include file="header.tpl" name=!login! header=!memberinfo!}

    {if $smarty.get.action neq "logout" and $smarty.get.action neq "login"}
      <input type="hidden" name="uri" value="{$smarty.server.REQUEST_URI}">
    {/if}
    <center>
      {if $login_error}
        <div style="width:'80%';" class='error' align='left'>
            {$login_error.msg}{$login_error.info}
        </div>
          <br>
      {/if}
      <table border="0" cellpadding="3" class="login_table" bgcolor='white' width='80%'>
      	<tr>
      		<td width='30%' class="TblLower">{!email!}</td>
      		<td class="TblHigher" ><input type='input' name='username' size=20 ></td>
      	</tr>
      	<tr>
      		<td  class="TblLower">{!password!}</td>
      		<td class="TblHigher" ><input type='password' name='password' size=20 ></td>
      	</tr>
      	<tr>
      		<td colspan=2 class="TblLower">
      			<li><a  href='index.php?register_user=on'>{!register!}</a></li>
      		</td>
      	</tr>
      	<tr>
      		<td colspan=2 class="TblLower">
      			<li><a target='forgotpass'  onclick='BasicPopup(this);' href='forgot_password.php'>{!forgot_pwd!}</a></li>
      		</td>
      	</tr>
      </table>
      <table border="0" width='80%'>
      	<tr>
      		<td align='right'> <input type='submit' value='{!login!}'>	</td>
      	</tr>
      </table>
    </center>
  </form>
{/if}