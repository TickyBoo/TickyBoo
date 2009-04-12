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
{*
Replication is not allowed under the Open source software act, this file
may be edited but may not be used as yours or redistributed.
*}

<form action="index.php?personal_page=details&action=update" method=post >
  <table cellpadding="3" class="main">

    {if $smarty.session.id}
    <tr>
      <td colspan="2">
        <h4 align="center">{$smarty.session.id}
          {assign var=$smarty.session.id value=''}
   	    </h4>
      </td>
    </tr>
    {/if}
    <tr>
      <td class='TblLower'>First Name</td>
      <td><input type="text" name="user_firstname" size='30' maxlength='50' value="{user->user_firstname}" /><span class='error'>{$user_errors.user_firstname}</span></td>
    </tr>
    <tr>
      <td class='TblLower'>Last Name</td>
      <td class='TblHigher'><input type="text" name="user_lastname" size='30' maxlength='50' value="{user->user_lastname}" /><span class='error'>{$user_errors.user_lastname}</span></td>
    </tr>
    <tr>
      <td class='TblLower'>Address 1</td>
      <td class='TblHigher'><input type="text" name="user_addresse" size='30'  maxlength='75' value="{user->user_address}" /><span class='error'>{$user_errors.user_addresse}</span></td>
    </tr>
    <tr>
      <td class='TblLower'>Address 2</td>
      <td class='TblHigher'><input type="text" name="user_uddresse1" size='30'  maxlength='75' value="{user->user_address2}" /></td>
    </tr>
    <tr>
      <td class='TblLower'>Post Code</td>
  	  <td class='TblHigher'><input type='text' name='user_zip' size='8'  maxlength='20' value='{$user->user_zip}'><span class='error'>{$user_errors.user_zip}</span></td>
  	</tr>
  	<tr>
      <td class='TblLower'>City</td>
      <td class='TblHigher'><input type="text" name="user_city" size='30'  maxlength='50' value="{user->user_city}" /><span class='error'>{$user_errors.user_city}</span></td>
    </tr>
    <tr>
      <td class='TblLower'>County</td>
      <td class='TblHigher'><input type="text" name="user_state" size='30'  maxlength='50' value="{$user->user_state}" /><span class='error'>{$user_errors.user_state}</span></td>
    </tr>
    <tr>
      <td class='TblLower' >Country</td>
      <td class='TblHigher'>
    <select name='user_country'>
      {include file="countries.tpl" selected=$user->user_country}
    </select><span class='error'>{$user_errors.user_country}</span>
    </td>
    </tr>
    <tr>
      <td class='TblLower'>Phone Number</td>
      <td class='TblHigher'><input type="text" name="user_phone" size='30'  maxlength='50' value="{user->user_phone}" /></td>
    </tr>
    <tr>
      <td class='TblLower'>Fax</td>
      <td class='TblHigher'><input type="text" name="user_fax" size='30'  maxlength='50' value="{user->user_fax}" /></td>
    </tr>
    <tr>
      <td class='TblLower'>Email</td>
      <td class='TblHigher'><input name="user_email" type="text" value="{user->user_email}" size='30'  maxlength='50' readonly="true" d /><span class='error'>{$user_errors.user_email}</span></td>
    </tr>
    <tr>
    	<td class='TblLower'>Current password </td>
        <td class='TblHigher'><input type='password' name='password1' size='15'  maxlength='10'>
          <div class='error'>{$user_errors.password}</div>
       </td>
    </tr>
    </tr>
  </table>
  <div align="center">
     <input type='submit' name='submit_update' value='Update'>
  </div>
</form>