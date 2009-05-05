{*
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
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
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't 
 * clear to you.
 */
 *}

{include file="header.tpl" name=!pers_info! header=!user_notice!}
  {if $user_errors}
     <div class='error'>{$user_errors._error}</div><br>
  {/if}
<center>
  <form action='checkout.php?action=register' method=post >
    <table cellpadding="2" bgcolor='white' width='80%'>
      {include file="user_form.tpl"}
      <tr>
        <td colspan='2' class='TblHigher'>
          <div class='error'>{$user_errors.check_condition}</div>
          <input type='checkbox' class='checkbox' name='check_condition' value='check'>
          <a  href='agb.php' target='cond' style='text-decoration:underline'>{!check_cond!}</a>
        </td>
      </tr>
    </table>
    <table cellpadding="5" width='80%'>
      <tr>
        <td colspan='2' align='right'><input type='submit' name='submit_info' value='{!continue!}'></td>
      </tr>
    </table>
  </form><br>

  <form action='checkout.php?action=login' method=post>
    <table  cellpadding='2' bgcolor='white' width='80%'>
      <tr>
        <td colspan='2' class='TblHeader'> {!member!} </td>
      </tr>
      {if $login_error}
        <tr>
          <td colspan='2' class='error'> {$login_error} </td>
        </tr>
      {/if}
      <tr>
        <td width="120" class='TblLower'> {!email!} </td>
        <td class='TblHigher'><input type='text' name='username' size='30' ></td>
      </tr>
      <tr>
        <td  class='TblLower'> {!password!} </td>
        <td class='TblHigher'><input type='password' name='password' size='30'></td>
      </tr>
      <tr>
        <td colspan="2" class='TblLower'>{!pleaselogin!}</td>
      </tr>
    </table>
    <table cellpadding="5" width='80%'>
      <tr>
        <td  colspan="2" align='right'>
          <input type='submit' name='type' value='guest'>
          <input type='submit' name='submit_login' value='{!continue!}'>
        </td>
      </tr>
    </table>
  </form>
</center>