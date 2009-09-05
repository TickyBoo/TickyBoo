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
 
 *}{strip}
{if !$smarty.request.mode}
  {include file="header.tpl"}
  <iframe height='0' width='0' style='border  border=0 src='print.php?mode=true&order_id={$smarty.request.order_id}'></iframe>
  <center><br>
    <div class='printer'>
      <img src='images/printing.gif' /><br/>
      {!tickets_printing!}
    </div><br>
    <a href='print.php?mode=true&order_id={$smarty.request.order_id}'  class='shop_link' target='printer'>
      {!print_order!}
    </a>
    When you want to directly print the PDF's you not to setup our system.
  </center><br><br>
  {include file="footer.tpl"}
{else}
  {if $pos->user_prefs eq "stream" and $smarty.request.mode neq "doit"}
      <script type='text/javascript'>
        <!--
          F1 = window.open('print.php?order_id={$smarty.request.order_id}&mode=doit','printer','left=100'); 
          F1.focus();    
        -->
      </script>
  {else}
    {order->order_print mode=$pos->user_prefs order_id=$smarty.request.order_id}
  {/if}
{/if}
{/strip}
