{*                  %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
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
 {literal}
<script  type="text/javascript">
function UserPopup(a)
{
	var url = a.href;
	if (window.open(url, a.target || "_blank", 'toolbar=0,location=0,directories=0,status=0,menubar=0'.concat(
  	',width=', "640",	',height=',  "400",	',scrollbars=',  "1", ',resizable=', "1")))
		{ return false; }
}
</script>
{/literal}

<table border=0 cellpadding="3" bgcolor='white' width='90%'>
  <tr>
    {if $title eq "on"}
      <td class='TblHeader'>
        {!your_addr!}</td>
    {/if}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {user->user_firstname|clean} {user->user_lastname|clean}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {user->user_address|clean}
  </tr>
  {if $user->user_address1|clean}
    <tr><td class='TblHigher' nowrap>
       {user->user_address1|clean}
    </tr>
  {/if}
  <tr><td class='TblHigher' nowrap>
     {user->user_zip|clean} {user->user_city|clean}
  </tr>
  <tr><td class='TblHigher' nowrap>
    {gui->viewcountry value=$user->user_country|clean nolabel=true}
  </tr>
  <tr><td class='TblHigher' nowrap>
     {user->user_email}</td></tr>
  <tr><td class='TblHigher' nowrap>
     <div align='right'><a target='editaddress' href='?action=useredit' onclick="UserPopup(this);" >{!edit!}</a></div>

  </td></tr>

</table>
