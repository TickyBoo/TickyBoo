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
 <form action='index.php' method='post'>
<table>
<div class="help">{!pos_selectuser!}</div>
	<tr>
		<td align="left">
			<table width='50%' border='0' cellspacing='1' cellpadding='5' align='left' style='padding-left:50px;'>
				{include file="user_form.tpl"}
				{*<tr>
					<td class='user_item'>{!without_fee!}</td>
					<td  class='user_value'><input type='checkbox' class='checkbox' name='no_fee' value='1'></td>
				</tr>
				<tr>
					<td class='user_item'>{!pos_freeticket!}s</td>
					<td  class='user_value'><input type='checkbox' class='checkbox' name='no_cost' value='1'></td>
				</tr>
				<tr>
					<td colspan='2' align='center'>
		  			<input type='hidden' name='handling' value='{$smarty.post.handling}'>
		  			<input type='hidden' name='action' value='submit_info'>
		  			<input type='submit' name='submit_info' value='{#continue#}'>
					</td>
		  		</tr> *}
			</table>
		</td>
{*		<td>
			<table>
				<tr>
					<td colspan="2" class="title">
						{!pos_exsistingusers!}
					</td>
				</tr>
				<tr>
					<td colspan="2" class='user_item' valign="left">
						{!pos_userlist!}
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td valign="right">
						{assign var=users value=$pos->list_patrons() }
						<select size="28" name="exst_user">
						<option value="1" selected="selected">{!new_newuser!}</option>
						{$users}
						</select>
					</td>
				</tr>
			</table>
		</td> *}
	</tr>
</table>
</form>

