{*                  %%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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
 *}{include file="$_SHOP_theme/header.tpl" name=$name}
{literal}
<style>
  #simplemodal-overlay {background-color:#ffffff;}
  #simplemodal-container {background-color:#fff0f0; border:2px solid #004088; padding:12px;}
  #simplemodal-container a.modalCloseImg {
    background:url(images/unchecked.gif) no-repeat; /* adjust url as required */
    width:25px;
    height:29px;
    display:inline;
    z-index:3200;
    position:absolute;
    top:-15px;
    right:-18px;
    cursor:pointer;
  }
  
</style>

<script>
function BasicPopup(a)
{
	var url = a.href;
  if (win = window.open(url, a.target || "_blank", 'width=640,height=200,left=300,top=300,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0'))
	  { win.focus();
	    win.focus();
      return false; 
    }
}
</script>
{/literal}
<div style="display:none" id='showdialog'>this is a nice dialog is it not?</div>