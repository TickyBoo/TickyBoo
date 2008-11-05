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
 
 *}<form action='shop.php' method=post>
<div class='user_inscription'>
<table width='380' border='0' cellspacing='1' cellpadding='5' align='center' >
<tr> 
<td colspan="2" class="title">    
    {!pers_info!}
</td>
</tr>
<tr> 
<td class='user_item'>     
    {!lastname!}
</td>
  <td><input type='text' name='user_lastname' size='30' maxlength='50' value='{$user_data.user_lastname}'><span class='error'>{$user_errors.user_lastname}</span></td>
</tr>
<tr> 
  <td class='user_item'>     
    {!firstname!}
</td>
  <td><input type='text' name='user_firstname' size='30'  maxlength='50' value='{$user_data.user_firstname}'><span class='error'>{$user_errors.user_firstname}</span></td>
</tr>
<tr> 
  <td class='user_item'>    
    {!address!}
</td>
  <td><input type='text' name='user_addresse' size='30'  maxlength='75' value='{$user_data.user_addresse}'><span class='error'>{$user_errors.user_addresse}</span></td>
</tr>
<tr> 
  <td class='user_item'> 
    {!address1!}
</td>
  <td><input type='text' name='user_addresse1' size='30'  maxlength='75' value='{$user_data.user_addresse1}'><span class='error'>{$user_errors.user_addresse1}</span></td>
</tr>
<tr> 
  <td class='user_item'>    
    {!zip!}
</td>
  <td><input type='text' name='user_zip' size='8'  maxlength='20' value='{$user_data.user_zip}'><span class='error'>{$user_errors.user_zip}</span></td>
</tr>
<tr> 
  <td class='user_item'>     
    {!city!}
</td>
  <td><input type='text' name='user_city' size='30'  maxlength='50' value='{$user_data.user_city}'><span class='error'>{$user_errors.user_city}</span></td>
</tr>
<tr>
<td class='user_item'>    
    {!country!}
</td>
<td>
{country assign=clist}
<select name='user_country'>
{foreach key=code item=name from=$clist}
<option value='{$code}'>{$name}</option>
{/foreach}
</select><span class='error'>{$user_errors.user_country}</span>
   </td></tr>	
<tr>
<td class='user_item'>    
   {!without_fee!}</td>
<td  class='user_value'>
<input type='checkbox' class='checkbox' name='no_fee' value='1'>
</td>
</tr>
 <tr> 
  <td colspan='2' align='center'><input type='submit' name='submit_info' value='{!continue!}'></td>
</tr>
</table>
