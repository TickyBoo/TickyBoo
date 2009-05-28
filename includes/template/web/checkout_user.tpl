{*
/**
%%%copyright%%%
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
 */
 *}

{include file="header.tpl" name=!pers_info! header=!user_notice!}
  {if $user_errors}
     <div class='error'>{$user_errors._error}</div><br>
  {/if}
{*  <div onclick='ShowRegister();'><input type='radio' onclick='ShowRegister();' id='showregister' /> click here to register as parton.  {!register_quest_here!} </div> *}
  <center>
  <form action='checkout.php' method='post'  >
    {ShowFormToken name='UserRegister'}
    <input type='hidden' name='action' value='register'>
    
    <table cellpadding="2" bgcolor='white' width='80%' id='guest'>
      <tr>
        <td colspan='2' class='TblHeader'> {!guest!} </td>
      </tr>
      <tr>
        <td colspan="2" class='TblHigher'>{!guest_info!}</td>
      </tr>
      <tr>
        <td colspan='2' class='TblLower'>
          <input type='checkbox' name='type' id='type' onclick='ShowPasswords(this);' value='check' {if $smarty.post.type} checked {/if} /> {!becomemember!}
        </td>
      </tr>
      {include file="user_form.tpl"}
      <tr id='passwords_tr1' >
        <td class='TblLower'>{!password1!} *</td>
        <td class='TblHigher'><input type='password' name='password1' size='10'  maxlength='10'>
           {!pwd_min!} <div class='error'>{$user_errors.password}</div>
        </td>
      </tr>
      <tr id='passwords_tr2'>
        <td class='TblLower'> {!confirmpassword!} *</td>
        <td class='TblHigher'><input type='password' name='password2' size='10'  maxlength='10'></td>
      </tr>
      <tr>
        <td class='TblLower' valign='top' width='30%'> {!user_nospam!}&nbsp;*</td>
        <td class='TblHigher' valign='top'>
          <table cellpadding="0" cellspacing="0" width='100%'>
            <tr>
              <td >
                <input type='text' name='user_nospam' size='10' maxlength="10" value='' ><br>
                <sup> {!nospam_info!} </sup><span class='error'>{$user_errors.user_nospam}</span>
              </td>
              <td align='center'>
                <img src="nospam.php?name=user_nospam" alt='' border=1>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan='2' class='TblHigher'>
          <input type='checkbox' class='checkbox' name='check_condition' value='check'> *
          <a  href='agb.php' target='cond'>{!check_cond!}</a>
          <div class='error'>{$user_errors.check_condition}</div>
        </td>
      </tr>
      <tr>
        <td colspan='2' align='right'><input type='submit' name='submit_info' value='{!continue!}'></td>
      </tr>
    </table>

  </form>
   </center>


<br>
{*  <div onclick='ShowLogin();' ><input type='radio' onclick='ShowLogin();' id='showlogin' /> click here to login as member. {!login_member_here!} </div> *}
  <center>
  
  <form action='checkout.php#member' method='post' >
    {ShowFormToken name='UserLogin'}
    <a name="member"></a>
    <table  cellpadding='2' bgcolor='white' width='80%' id='member' >
      <tr>
        <td colspan='2' class='TblHeader'> {!member!}
       </td>
      </tr>
      <tr>
        <td colspan="2" class='TblHigher'>{!member_info!}
       {if $login_error}
         <div class='error'> {$login_error.msg}{$login_error.info} </div>
       {/if}
        </td>
      </tr>
      <tr>
        <td width="120" class='TblLower' width='30%'> {!email!} </td>
        <td class='TblHigher'><input type='text' name='username' size='30'></td>
      </tr>
      <tr>
        <td class='TblLower'> {!password!} </td>
        <td class='TblHigher'><input type='password' name='password' size='30'></td>
      </tr>
      <tr>
        <td colspan="2" class='TblHigher'><a href='forgot_password.php' onclick='BasicPopup(this);' target='getpassword'>{!forgot_password!}</a></td>
      </tr>
      <tr>
        <td  colspan="2" align='right'>
          <input type='hidden' name='action' value='login'>
          <input type='submit' name='submit_login' value='{!continue!}'>
        </td>
      </tr>
    </table>
  </form>
  </center>
<hr>
{!user_notice!}<br><br>
{literal}
<script  type="text/javascript">
  function getElement(id){
       if(document.all) {return document.all(id);}
       if(document.getElementById) {return document.getElementById(id);}
			}
  function ShowPasswords(a){

       if(tr1=getElement('passwords_tr1')){
         if (a.checked) {
           tr1.style.display='';
         } else {
           tr1.style.display='none';
         }
       }
       if(tr2=getElement('passwords_tr2')){
         if (a.checked) {
           tr2.style.display='';
         } else {
           tr2.style.display='none';
         }
       }

  }
  function ShowLogin(){
       if(tr=getElement('showlogin')){
           tr.checked =true;
       }
       if(tr1=getElement('member')){
           tr1.style.display='';
       }
       if(tr2=getElement('guest')){
           tr2.style.display='none';
       }

  }
  function ShowRegister(){
       if(tr=getElement('showregister')){
           tr.checked =true;
       }
       if(tr1=getElement('member')){
           tr1.style.display='none';
       }
       if(tr2=getElement('guest')){
           tr2.style.display='';
       }

  }
  function HiddenBoth(){
       if(tr1=getElement('member')){
           tr1.style.display='none';
       }
       if(tr2=getElement('guest')){
           tr2.style.display='none';
       }
  }
  
  ShowPasswords(getElement('type'));
//  HiddenBoth();
  
</script>
{/literal}