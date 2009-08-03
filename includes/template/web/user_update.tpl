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
<!-- user_update.tpl -->
{if $usekasse}
	{if $smarty.post.submit_update}
	   	{if count($user_errors) eq 0}
	    	<script>
	        	window.opener.location.href = window.opener.location.href;
	        	window.close();
	      	</script>
	   	{/if}
    	{assign var='user_data' value=$smarty.post}
  	{else}
    	{assign var='user_data' value=$user->asarray()}
  	{/if}

<html>
	<head>
		<title></title>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<meta http-equiv="Content-Language" content="nl" />

			<link rel="shortcut icon" href="images\favicon.ico" />
			<link rel="icon" href="images\animated_favicon1.gif" type="image/gif" />
			<link rel='stylesheet' href='style.php' type='text/css' />
	</head>
	
	<body topmargin="0" leftmargin="0" bgcolor="#FFE2AE"> 
	<br />
	<center>
		<form action="checkout.php" method='post'>
			{ShowFormToken name='UserUpdate'}
 			<input type='hidden' name='action' value='useredit' />
{else}

  <form action="index.php" method='post' id="update_user">
   	{ShowFormToken name='UserUpdate'}
   	<input type='hidden' name='action' value='update' />
   	<input type='hidden' name='personal_page' value='details' />
{/if}
 	<input type='hidden' name='user_id' value='{user->user_id}' />
	<table cellpadding="3" class="main" bgcolor='white'>
		{include file='user_form.tpl'}
   	{if $user->is_member}
      <tr>
     		<td class='TblLower'>{!old_password!} </td>
     		<td class='TblHigher'>
		      <input autocomplete='off'  type='password' name='old_password' size='10'  maxlength='10' />
          <div class='error'>{$user_errors.old_password}</div>
       	</td>
     	</tr>
     	{if !$usekasse}
        <tr id='passwords_tr1' >
          <td class='TblLower'>{!new_password!} (opt.)</td>
          <td class='TblHigher'>
             <input autocomplete='off' type='password' name='password1' size='10' maxlength='10' id="password" />
             {!pwd_min!}
             <div class='error'>{$user_errors.password}</div>
          </td>
        </tr>
        <tr id='passwords_tr2'>
          <td class='TblLower'> {!password2!}</td>
          <td class='TblHigher'><input autocomplete='off' type='password' name='password2' size='10'  maxlength='10' /></td>
        </tr>
      {/if}
		{/if}
  </table>
	<br />
	
	<div align="center">
   	<input type='submit' name='submit_update' value='Update' />
  </div>
</form>