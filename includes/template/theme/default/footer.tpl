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
 </td>
    <td width='210px' align='right' valign="top"><br>
	{include file="user_login_block.tpl"} <br>
	{include file="cart_resume.tpl"}<br>
	</td>
  </tr>
</table>
</div>
	<div class="footer">
		<hr width="100%" />
{php}

GLOBAL $_SHOP;
$link = ShopDB::$link;
printf("System status: %s \n", mysqli_stat($link));

mysqli_close($link);
if (function_exists('sys_getloadavg')) {
    	$loadArray = sys_getloadavg();
    	$load= "Load: ".$loadArray[0]." / ".$loadArray[1]." / ".$loadArray[2];
    } else {
    	$load=@file_get_contents('/proc/loadavg');
    }
    if($load) {
      echo "Date: ".date('d.m.Y H:i:s')." ".$load;
    }
$start=$_SESSION["ustart"];
$end = utime(); $run = $end - $start;
echo " Page expelled in " . substr($run, 0, 5) . " secs.";
echo "<hr>";
{/php}

		<table width="100%">
		<tr>
		<!-- To comply with our GPL please keep the following link in the footer of your site -->
  		<td width='27'>
	  		<img src="images/atom.png" height='20' width='23' />
		  </td>
		  <td  class="copy" valign="top">
        Copyright 2009<br />
		    Powered By <a href="http://www.fusionticket.org"> Fusion Ticket</a> - Free Open Source Online Box Office
		  </td>
		</tr>
		</table>
	</div>
</div>
</body>
</html>
