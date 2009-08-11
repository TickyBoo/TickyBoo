{*
/**
%%%copyright%%%
 *
 * FusionTicket - Free Ticket Sales Box Office
 * Copyright (C) 2007-2009 Christopher Jenkins. All rights reserved.
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
 * Contact info@fusionticket.com if any conditions of this licencing isn't 
 * clear to you.
 * Please goto fusionticket.org for more info and help.
 */
 *}
<html>
	
	<head>
		<meta http-equiv="Content-Language" content="English" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>FusionTicket: Box Office / Sale Point </title>

		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.7.2.custom.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/pos.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/formatting.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="../css/jquery.autocomplete.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="../css/ui.jqgrid.css" media="screen" />
		
		<script type="text/javascript" src="../scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery-ui-1.7.2.custom.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery.ajaxmanager.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery.validate.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery.autocomplete.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery.checkboxselect.js"></script>
		<script type="text/javascript" src="../scripts/jquery/DD_roundies.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery.json-1.3.min.js"></script>
		<script type="text/javascript" src="../scripts/jquery/i18n/grid.locale-en.js"></script>
		<script type="text/javascript" src="../scripts/jquery/jquery.jqGrid.min.js"></script>

		<script type="text/javascript" src="scripts/pos.jquery.style.js"></script>
		<script type="text/javascript" src="scripts/pos.jquery.ajax.js"></script>
		<script type="text/javascript" src="scripts/pos.jquery.order.js"></script>
    {literal}
			<script type="text/javascript">
				$(document).ready(function(){
					/*
					$.ajax({
						type:	"POST",
						url:	"ajax.php",
						dataType:"json",
						data:{"test":true},
						success:function(data, status){
							console.log("Status: "+status);
							console.log(data.status);
						}	
					});*/
	 			});
			</script>
		{/literal}
	</head>
	
	<body>
		<div id="wrap">
			<div id="header">
{*		{if $organizer->organizer_logo} <div align="left"><img src='../files/{$organizer->organizer_logo}'/> </div>{/if}*}
				<div class="loading">
					<img src="images/LoadingImageSmall.gif" width="16" height="16" alt="Loading data, please wait" />
				</div>
 				<img style="" src="images/fusion.png" border="0"/>
 				<h2>Box Office <span style="color:red; font-size:14px;"><i>[AJAX Beta]</i></span></h2>
			</div>
			<div id="navbar">
				<ul>
					<li><a href="index.php?action=order" accesskey="b" tabindex="11">{!pos_booktickets!}</a></li>
					<li><a href="index.php?process=on" accesskey="t" tabindex="12">{!pos_currenttickets!}</a></li>
				</ul>
			</div>
			<div id="right">