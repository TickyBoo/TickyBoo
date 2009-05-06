{*
 * %%%copyright%%%
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
 *}
<table border=0 cellpadding="3" bgcolor='white' width='90%'>
  <tr>
    {if $title eq "on"}
      <td class='TblHeader'>{!your_addr!}</td>
    {/if}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {user->user_firstname} {user->user_lastname}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {user->user_address}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {if $user->user_address1}
       {user->user_address1}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {/if}
     {user->user_zip} {user->user_city}
  </tr>
  <tr><td class='TblHigher' nowrap>
    {gui->viewcountry value=$user->user_country}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {user->user_email}</td></tr>
  <tr><td class='TblHigher' nowrap>
     <div align='right'><a href='?action=useredit' onclick="BasicPopup(this);" >{!edit!}</a></div>
  </td></tr>

</table>

{literal}
<script  type="text/javascript">
function BasicPopup(a)
{
//  window.status =a.className;
//	p = a.className.substring(a.className.lastIndexOf(' ')).split('.');
	var url = a.href;
	if (window.open(url, a.target || "_blank", 'toolbar=0,location=0,directories=0,status=0,menubar=0'.concat(
  	',width=', "640",	',height=',  "400",	',scrollbars=',  "1", ',resizable=', "1")))
		{ return false; }
}
</script>
{/literal}
