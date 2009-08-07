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
 *}
{php}
  function utime (){
    $time = explode( " ", microtime());
    $usec = (double)$time[0];
    $sec = (double)$time[1];
    return $sec + $usec;
  }
  $ustart = utime();

  global $smarty;
  $_SESSION["ustart"] = $ustart;
{/php}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>FusionTicket</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel='stylesheet' href='style.php' type='text/css' />
		
		<!-- Must be included in all templates -->
		
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		
		<script type="text/javascript" src="scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.validate.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.maskedinput-1.2.2.js"></script>
		
		<script type="text/javascript">
			var lang = new Object();
			lang.required = '{!mandatory!}';        lang.phone_long = '{!phone_long!}'; lang.phone_short = '{!phone_short!}';
			lang.fax_long = '{!fax_long!}';         lang.fax_short = '{!fax_short!}';
			lang.email_valid = '{!email_valid!}';   lang.email_match = '{!email_match!}';
			lang.pass_short = '{!pass_too_short!}'; lang.pass_match = '{!pass_match!}';
			lang.not_number = '{!not_number!}';     lang.condition ='{!check_condition!}';
		</script>
		<script type="text/javascript" src="scripts/shop.jquery.forms.js"></script>
		
		<script type="text/javascript" src="scripts/countdownpro.js" defer="defer"></script>
		
		<meta scheme="countdown1" name="d_hidezero" content="1" />
		<meta scheme="countdown1" name="h_hidezero" content="1" />
		<meta scheme="countdown1" name="m_hidezero" content="1" />
		<meta scheme="countdown1" name="s_hidezero" content="1" />
		<meta scheme="countdown1" name="event_msg" content="0! " />
		<meta scheme="countdown1" name="servertime" content="{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} GMT+00:00" />
		
		<!-- End Required Headers -->
	</head>

	<body class='main_side'>   <center>
		<div class="mainbody" align='left'>
			<img class="spacer" src='images/dot.gif' height="1px" />
			<br />
			<img src="images/fusion.png" align="bottom" />
			<br />

		<div id="navbar">
    		<ul>
     			<li>
 					<a href='index.php'>{!home!}</a>
				</li>
				<li>
					<a href='calendar.php'>{!calendar!}</a>
				</li>
				<li>
					<a href='programm.php'>{!program!}</a>
				</li>
			</ul>     <br>
  		<div align="right" style="vertical-align: top; width:100%; " >
  			<a href="?setlang=en">[en]</a>
  		</div>
		</div>
		
		<div class="maincontent">
			<table width='100%' border='0' cellpadding='0' cellspacing='0'>
  				<tr>
					<td valign='top' align='left'>
    {include file="Progressbar.tpl" name=$name}
						<br />
  						{if $name}
    						<h1>{$name}</h1>
  						{/if}
  						{if $header}
    						<div>{$header}</div>
  						{/if}