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
 <!--LoginERROR.tpl-->
{if $login_error.error}
  {include file="header.tpl" name=!log_error!}
  <table class="main">
 	  <tr>
		  <td>
			  {if $login_error.msg eq "1"}
		  	  {!log_err_wrong_usr!}
			  {elseif $login_error.msg eq "2"}
			    {!log_err_not_act!}
			  {/if}
		  </td>
		</tr>
  </table>
{else}
  <script>window.location.href="{$_SHOP_root}";</script>
{/if}