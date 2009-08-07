<?php
/**
%%%copyright%%%
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
 */

if(isset($_REQUEST["inst_pg"])) {
  include "../includes/install/install.php";
  exit;
}
?>
<html><head><title>FusionTicket installation::Welcome</title>
<link rel="stylesheet" href="http://www.phpmyticket.com/spip/style.css" type="text/css">
</head>

<body>
<h1 class="titre-texte">Welcome to Fusion Ticket installation script!</h1>
<sub>Based on phpMyTicket</sub>

<table width='500' style='text-align:justify;'><tr><td>

<p>Welcome to <b>Fusion Ticket</b> - The opensource online box office software in php.
<p>Fusion Ticket is distributed under the GNU GPL v3 Licence.<br>
You are agreeing to this licence by installing this software.<br>
Therefore FusionTicket will not be responsible for any damages or loss of profit caused by this software or any other patch script included with this software.</p>
<p>This also means under the open software licence any modifactions to this script also fall under this licence.<br>
<br>Therefore you can <b>NOT</b> sell this script but are able to make money from <b>USING</b> it.<br>
There may be a proffesional version in the future.</p>

<p>
	<li><a href="http://fusionticket.org" target="_blank">Visit our site for help and info</a>
</p>

<p><a href="index.php?inst_pg=0">Install Fusion Ticket</a></p>
</td></tr></table>
</body>
</html>