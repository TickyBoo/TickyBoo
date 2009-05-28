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

{if $usekasse}
  {if $smarty.post.submit_update}
    {user->update_member data=$smarty.post}
    
    {if count($user_errors) eq 0}
      <script>
        window.opener.location.href = window.opener.location.href;
        window.close();
      </script>
    {/if}
    {assign var='user_data' value=$smarty.post}
  {else}
    {assign var='user_data' value=$smarty.session._SHOP_USER}
  {/if}

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" >
<meta http-equiv="Content-Language" content="nl" >

<link rel="shortcut icon" href="images\favicon.ico" >
<link rel="icon" href="images\animated_favicon1.gif" type="image/gif" >
<link REL='stylesheet' HREF='style.php' TYPE='text/css' >

</head>
<body topmargin="0" leftmargin="0" bgcolor="#FFE2AE"> <br >
 {print_r var=$user_errors}
  <center>
  <form action="checkout.php" method='post'>
    {ShowFormToken name='UserUpdate'}
    <input type='hidden' name='action' value='edituser'>

  
{else}
  {assign var='user_data' value=$smarty.session._SHOP_USER}
  <form action="index.php" method='post'>
    {ShowFormToken name='UserUpdate'}
    <input type='hidden' name='action' value='update'>
    <input type='hidden' name='personal_page' value='details'>
{/if}
  <table cellpadding="3" class="main" bgcolor='white'>
    <input type='hidden' name='user_id' value={user->user_id}>
    
    {include file='user_form.tpl'}
    {if $user->is_member}
      <tr>
      	<td class='TblLower'>Current password </td>
          <td class='TblHigher'><input type='password' name='old_password' size='15'  maxlength='10'>
            <div class='error'>{$user_errors.old_password}</div>
         </td>
      </tr>
    {/if}
  </tr>
  </table>  <br>
  <div align="center">
     <input type='submit' name='submit_update' value='Update'>
  </div>
</form>