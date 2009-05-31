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
 {include file="header.tpl" name=!becomemember! header=!memberinfo!}
<form action='index.php' method='post' id="user-register">
  <input type='hidden' name='action' value='register' />
  <input type='hidden' name='ismember' value='true' />
  <input type='hidden' name='register_user' value='on' />
  {ShowFormToken name='registeruser'}
  <center>
    <table class="table_dark" cellpadding="3" bgcolor='white' width='85%'>
      {include file="user_form.tpl"}
      	<tr>
  			<td class='TblLower' > {!confirmemail!}&nbsp;* </td>
  			<td class='TblHigher'>
		  		<input autocomplete='off' type='text' name='user_email2' size='30'  maxlength='50' value='{$user_data.user_email2|clean}'/>
			  	<span class='error'>{$user_errors.user_email}</span>
		  	</td>
		</tr>
      <tr>
        <td class='TblLower'>{!password!} *</td>
        <td class='TblHigher'><input autocomplete='off' type='password' name='password1' size='10' maxlength='10' id="password" />
           {!pwd_min!} <div class='error'>{$user_errors.password}</div>
        </td>
      </tr>
      <tr>
        <td class='TblLower'> {!confirmpassword!} *</td>
        <td class='TblHigher'><input autocomplete='off' type='password' name='password2' size='10'  maxlength='10' /></td>
      </tr>
      <tr>
        <td class='TblLower' valign='top' width='30%'> {!user_nospam!}&nbsp;*</td>
        <td class='TblHigher' valign='top'>
          <table cellpadding="0" cellspacing="0" width='400'>
            <tr>
              <td >
                <input type='text' name='user_nospam' size='10' maxlength="10" value='' />
				<br/>
                <sup> {!nospam_info!} </sup><br /><span class='error'>{$user_errors.user_nospam}</span>
              </td>
              <td align='center'>
                <img src="nospam.php?name=user_nospam" alt='' border='1' />
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br />
    <table class="table_dark" cellpadding="3" width='85%'>
      <tr>
        <td>
          <input type='checkbox' class='checkbox' name='check_condition' value='check' />
          <a href='agb.php' target='agb' style='text-decoration:underline'> {eval var=!agrement!}</a><div class='error'>{$user_errors.check_condition}</div>
        </td>
      </tr>
{*      <tr>
        <td>
          <input type='checkbox' class='checkbox' name='check_use' value='check' /> <span> {eval var=!mayuse!}</span> <div class='error'>{$user_errors.check_use}</div>
        </td>
      </tr> *}

    </table><br>
    <input type='submit' name='submit_register' value='{!signup!}'>
  </center>
</form>
    <br><br>&nbsp;
