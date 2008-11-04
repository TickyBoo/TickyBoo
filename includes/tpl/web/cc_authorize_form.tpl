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
<form action='cc_authorize_pay.php' method='POST' onsubmit='this.submit.disabled=true;return true;'>
  <table class='cc_form' cellpadding="5">
    <tr>
      <td> {!cc_name!} </td>
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
      <td>{!cc_exp!} </td>
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
          <option value='08'>2008</option>
          <option value='09'>2009</option>
          <option value='10'>2010</option>
          <option value='11'>2011</option>
          <option value='12'>2012</option>
          <option value='13'>2013</option>
          <option value='14'>2014</option>
          <option value='15'>2015</option>
          <option value='16'>2016</option>
          <option value='17'>2017</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>{!cc_code!}</td>
      <td colspan='2'>
        <INPUT type='text' name='cc_code' value='{$smarty.post.cc_code}' size='4' lenght='4'>
      </td>
    </tr>
    <tr>
      <td colspan='3' align='center'>
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