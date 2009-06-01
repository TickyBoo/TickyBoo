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
{include file='header.tpl' name=!act_name! }
{if !$smarty.request.uar || !$user->activate()}
   {include file="just_registred.tpl"}
   <br>
   {if $smarty.request.sendnew eq 1}
     {user->resend_activation email=$user->email}
   {/if}
   <table border="0" cellpadding="5" cellspacing="5" width="600" class="login_table"  >
      <tr>
        <td colspan=2  class="TblLower">
           <h2>{!act_enter_title!}</h2>
        </td>
      </tr>
      <form action='{!PHP_SELF!}' method='post'>
        {ShowFormToken name='TryActivateUser'}
        <tr><td  colspan='2'>{!act_enter_code!}<br><br></td></tr>
        {if $errors}
          <tr><td colspan='2' class='error'>{$errors}<br><br></td></tr>
        {/if}
        <tr>
          <td>{!act_code!}</td>
          <td><input type='text' name='uar' value='{$smarty.request.uar}' size='40'> &nbsp; <input type='submit' name='submit' value="{!act_send!}"></td>
        </tr>
        <tr><td colspan='2'><a href='{!PHP_SELF!}?sendnew=1'>{!act_notarr!}</a></td></tr>
      </table>
   </form>
{else}

{/if}
