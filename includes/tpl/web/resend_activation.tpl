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

{include file="header.tpl" name=!bcm_mbr!}
{if $smarty.post.email}
  {if $user->resend_activation_f($smarty.post.email) }
    <div class='success'>
    {!act_sent!}.</div>
  {else}
    <div class='error'>
    {!act_err!}</div>
  {/if}
{else}  
  <form action='resend_activation.php' method='post'>  
    {ShowFormToken name='resend_activation'}
    <table width='80%' align='center'>
      <tr><td class='title' colspan='2' align='center'>
        {!act_notarr!}
      </td></tr>
      <tr><td  colspan='2'>
        {!act_note!}<br><br>
      </td></tr>
      <tr><td>{!email!}</td>
      <td><input type='text' name='email' size='36'> &nbsp; <input type='submit' name='submit' value="{!act_send!}"></td></tr>
    </table>
  </form>
{/if}
{include file="footer.tpl"}
