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
 *}<html>
<head>
  <title>{!pwd_forgot!}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" >
  <meta http-equiv="Content-Language" content="nl" >

  <link rel="shortcut icon" href="images\favicon.ico" >
  <link rel="icon" href="images\animated_favicon1.gif" type="image/gif" >

  <link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.7.2.custom.css" media="screen" />
  <link rel='stylesheet' href='style.php' type='text/css' >

  <script type="text/javascript" src="scripts/jquery/jquery-1.4.min.js"></script>
  <script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.2.custom.min.js"></script>


</head>
<body topmargin="0" leftmargin="0" style='align:left;'> <br>
<h1>{!forgot_password!}</h1><br>

  <div id="error-message" title='{!order_error_message!}' class="ui-state-error ui-corner-all" style="padding: 1em; margin-bottom: .7em; display:none; " >
     <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        <span id='error-text'>ffff</span>
     </p>
  </div>
  <div id="notice-message" title='{!order_notice_message!}' class="ui-state-highlight ui-corner-all" style=" padding: 1em; margin-bottom: .7em; display:none; " >
     <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
        <span id='notice-text'>fff</span>
     </p>
  </div>
  {if $smarty.post.submit AND $user->forgot_password_f($smarty.post.email) }
    <button onclick="window.close();">{!close!}</button>
  {else}
    {gui->StartForm width="100%" class="login_table" action='forgot_password.php' method='post' name='resendpassword'}
        <tr><td colspan='2'>{!pwd_note!}<br><br></td></tr>
        <tr>
          <td>{!user_email!}</td>
          <td>
            <input type='text' name='email' size='30'>{printMsg key='email'}
          </td>
        </tr>
        {gui->EndForm title=!pwd_send!  noreset=true}
      </table>
    </form>
  {/if}
{literal}
<script type="text/javascript">
$(document).ready(function(){
    //  var msg = ' errors';
      var msg = '{/literal}{printMsg key='__Warning__' addspan=false}{literal}';
      if(msg) {
        $("#error-text").html(msg);
        $("#error-message").show();
        setTimeout(function(){$("#error-message").hide();}, 10000);
      }
      var msg = '{/literal}{printMsg key='__Notice__' addspan=false}{literal}';
      if(msg) {
        $("#notice-text").html(msg);
        $("#notice-message").show();
      }
    });

</script>
{/literal}

</body>
</html>