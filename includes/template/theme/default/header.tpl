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
{config_load file="shop_$_SHOP_lang.conf"}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>FusionTicket</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel='stylesheet' href='style.php' type='text/css' />
		
		<script type="text/javascript" src="scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.validate.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.maskedinput-1.2.2.js"></script>
		<script type="text/javascript" src="scripts/shop.jquery.forms.js"></script>
		
		<script type="text/javascript" src="scripts/countdownpro.js" defer="defer"></script>
		
		<meta scheme="countdown1" name="d_hidezero" content="1" />
		<meta scheme="countdown1" name="h_hidezero" content="1" />
		<meta scheme="countdown1" name="m_hidezero" content="1" />
		<meta scheme="countdown1" name="s_hidezero" content="1" />
		<meta scheme="countdown1" name="event_msg" content="0! " />
		<meta scheme="countdown1" name="servertime" content="{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} GMT+00:00" />
	</head>

	<body class='main_side'>
		<div class="mainbody">
			<img class="spacer" src='images/dot.gif' height="1px" />
			<br />
			<img src="images/fusion.png" align="bottom" />
			<br />

		<div id="navbar">
    		<ul>
     			<li>
 					<a href='index.php'>{#home#}</a>
				</li>
				<li>
					<a href='calendar.php'>{#calendar#}</a>
				</li>
				<li>
					<a href='programm.php'>{#program#}</a>
				</li>
			</ul>
			<br />
		</div>
		
		<div align="right" style="vertical-align: top; width:100%; " >
			<a href="?setlang=en">[en]</a>
		</div>
		
		<div class="maincontent">
			<table width='100%' border='0' cellpadding='0' cellspacing='0'>
  				<tr>
					<td valign='top' align='left'>
						<br />
  						{if $name}
    						<h1>{$name}</h1>
  						{/if}
  						{if $header}
    						<div>{$header}</div>
  						{/if}