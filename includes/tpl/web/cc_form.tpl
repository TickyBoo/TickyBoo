{*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
*}
<form action='cc_pay.php' method='POST' onsubmit='this.submit.disabled=true;return true;'>
  <table class='cc_form' cellpadding="5">
    <tr>
      <td>{!cc_owner!}</td>
      <td colspan='2'>
        {if $smarty.post.cc_name}
          <INPUT type='text' name='cc_name' value='{$smarty.post.cc_name}'>
        {else}
          <INPUT type='text' name='cc_name' value='{user->user_firstname} {user->user_lastname}'>
        {/if}
      </td>
    </tr>
    <tr>
      <td>{!cc_number!}</td>
      <td colspan='2'>
        <INPUT type='text' name='cc_number' value='{$smarty.post.cc_number}'>
      </td>
    </tr>
    <tr>
      <td>{!cc_exp!}</td>
      <td>
        <select name='cc_month'>
          {section name="month" start=1 loop=12 }
            <option value='{$smarty.section.month.index|string_format:"%02d"}'>
              {"`$smarty.section.month.index`"|string_format:"2000-%02d-01"|date_format:"%B"}
            </option>
          {/section}
        </select>
      </td>
      <td>
        <select name='cc_year'>
          <option value='04'>2004</option>
          <option value='05'>2005</option>
          <option value='06'>2006</option>
          <option value='07'>2007</option>
          <option value='08'>2008</option>
          <option value='09'>2009</option>
          <option value='10'>2010</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>{!cc_check!}</td>
      <td colspan='2'>
         <INPUT type='text' name='cc_code' value='{$smarty.post.cc_code}' size='4' lenght='4'>
      </td>
    </tr>
    <tr>
      <td colspan='3' align='left'>
         <INPUT type='submit' name='submit' value='{!pay!}' >
      </td>
    </tr>
  </table>

  {if $smarty.post.order_id}
    <input type='hidden' name='order_id' value='{$smarty.post.order_id}'>
  {else}
    <input type='hidden' name='order_id' value='{$order_id}'>
  {/if}
</form>
