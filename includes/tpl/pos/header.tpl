{*
/**
%%%copyright%%%
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
 */
 *}
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<title>FusionTicket: Box Office / Sale Point </title>
		<link rel='stylesheet' href='style.css' type='text/css'/>
		{literal}
		<script language="JavaScript">
		<!--
			browser_version= parseInt(navigator.appVersion);
			browser_type = navigator.appName;
			if (browser_type == "Microsoft Internet Explorer" && (browser_version >= 4)) {
				document.write("<link rel='stylesheet' href='style_ie.css' type='text/css'>");
			}else if (browser_type == "Netscape" && (browser_version >= 4)) {
				document.write("<link rel='stylesheet' href='style_nn.css' type='text/css'>");
			}else{
				document.write("<link rel='stylesheet' href='style_nn.css' type='text/css'>");
			}
		// -->
		</script>{/literal}
	</head>
	<body style="margin:0;">
		<center> 
			<table border='0' style="border:#45436d 1px solid;" width="750"  cellspacing="0" cellpadding="0" bgcolor="#ffffff" >
				<tr>
  					<td colspan='6' style='padding-left:20px;padding-bottom:5px;'>
	  					<a href='index.php'><img src="images/logo_vvs.png" border="0"/></a>
  					</td>
  				</tr>
  				<tr>
  					<td colspan="6">
  						<table>
							<tr>
  								<td width="33%" align="center">
	  								<a class="shop_link" style="font-size:16px;" href="index.php?action=home">{!pos_homepage!}</a>
								</td>
								<td width="34%" align="center">
									<a class="shop_link" style="font-size:16px;" href="index.php?process=on">{!pos_processtickets!}</a>
    							</td>
								<td width="33%" align="center">
									<a class="shop_link" style="font-size:16px;" href="index.php?action=book_tickets">{!pos_booktickets!}</a>
    							</td>
    						</tr>
  						</table>
  					</td>
  				</tr>
  				<tr>
					<td width='125' valign="top"  style='padding-left:20px;border-top:#45436d 1px solid;border-bottom:#45436d 1px solid; padding-bottom:5px; padding-top:5px;'>
						<a class='shop_link' href='index.php?action=show_evgroup'>{!event_groups!}</a>
					</td>
					<td width='125' valign="top" align='center' style="border-top:#45436d 1px solid;border-bottom:#45436d 1px solid;padding-bottom:5px; padding-top:5px;">
						<a class='shop_link' href='index.php?action=calendar'>{!calendar!}</a>
					</td>
					<td width='125'valign="top" align='center' style="border-top:#45436d 1px solid;border-bottom:#45436d 1px solid;padding-bottom:5px; padding-top:5px;">
						<a class='shop_link' href='index.php?action=view_cart'>{!shopping_cart_pos!}</a>
					</td>
					<td width='125' valign="top" align='center' style="border-top:#45436d 1px solid;border-bottom:#45436d 1px solid;padding-bottom:5px; padding-top:5px;">
						<a class='shop_link' href='index.php?action=view_orders'>{!orders!}</a>
					</td>
					<td width='125' valign="top" align='center' style="border-top:#45436d 1px solid;border-bottom:#45436d 1px solid;padding-bottom:5px; padding-top:5px;">
						<a class='shop_link' href='index.php?action=view_options'>{!preferences!}</a>
					</td>
					<td width='125' valign="top" align='center' style="border-top:#45436d 1px solid;border-bottom:#45436d 1px solid;padding-bottom:5px; padding-top:5px;padding-right:40px;">
						<a class='shop_link' href='index.php?action=logout'>{!logout!}</a>
					</td>
  				</tr>
  				<tr>
  					<td colspan='6' align='center'  style='padding-top:8px;padding-left:10px;padding-right:10px;padding-bottom:10px;'>

