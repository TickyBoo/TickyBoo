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
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.7.1.custom.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/pos.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/formatting.css" media="screen" />
		<script type="text/javascript" src="scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.ajaxmanager.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.validate.js"></script>
		<script type="text/javascript" src="scripts/jquery/DD_roundies.js"></script>
		<script type="text/javascript" src="scripts/pos.jquery.style.js"></script>
		<script type="text/javascript" src="scripts/pos.jquery.ajax.js"></script>		
		{literal}
		<script type="text/javascript">
			$(document).ready(function(){
 			});
		</script>
		{/literal}
	</head>
	
	<body>
		<div id="wrap">
			<div id="header">
				<img style="" src="images/fusion.png" border="0"/>
				<div class="loading">
					<img src="images/LoadingImageSmall.gif" width="48" height="47" alt="Loading data, please wait" />
				</div>
				<h2>Fusion Ticket - Box Office <span style="color:red; font-size:14px;"><i>[AJAX Beta]</i></span></h2>
				<div id="btn-middle">
					<button id="btn_home" class="ui-state-default ui-corner-all" type="button">{!pos_homepage!}</button></li>
					<button id="btn_order" class="ui-state-default ui-corner-all" type="button">{!pos_booktickets!}</button></li>
					<button id="btn_current_order" class="ui-state-default ui-corner-all" type="button">{!pos_currenttickets!}</button></li>
				</div>
			</div>

			<div id="right">

				{include file='index.tpl'}
				
			</div>
			<!--
			<div id="left"> 

				<h3>Categories :</h3>
				<ul>
					<li><a href="#">World Politics</a></li>
				</ul>

				<h3>Archives</h3>
				<ul>
					<li><a href="#">January 2007</a></li> 
				</ul>

			</div>
			-->
			
			<div style="clear: both;"> </div>

			<div id="footer">
				Powered by <a href="http://fusionticket.org">Fusion Ticket</a> - The Free Open Source Box Office
				<a href="http://fusionticket.org" ><img src="images/atom.png" width="38" height="39" style="float:right;" /></a>
				<!--
				<a href='http://jquery.com/' title='jQuery JavaScript Library'><img src='http://jquery.com/files/buttons/propal2.png' alt='jQuery JavaScript Library' title='jQuery JavaScript Library' style='border:none; float:left; display:block;'/></a>
				-->
			</div>
		</div>
		
	</body>
</html>