{*
 * %%%copyright%%%
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
 *}
{include file='shop.tpl' nofooter='true'}

<script LANGUAGE="JavaScript">
 var leejoo_mess = '<br><span class="title">{!pay_accept!}!</span><br>{!order_id!}:<b>{$smarty.request.item_number}.</b><br>{!trx_id!}:  <b>{$smarty.request.txn_id}</b><br{!pay_mess!}';
 var leejoo_background = '#FFE2AE'
 var leejoo_bordure = '#000000'
 var leejoo_police = 'Verdana'
 var leejoo_police_taille = '12'
 var leejoo_police_color = '#000000'
 var leejoo_hauteur = 120
 var leejoo_largeur = 340
 var leejoo_box2 = 2;
 
{literal}
leejoo_classe = 'border-color:'+leejoo_bordure+';border-style:solid;border-width:1px;background:'+leejoo_background+';font-family:'+leejoo_police+';font-size:'+leejoo_police_taille+'px;color:'+leejoo_police_color;
 if(document.getElementById)
 	{
 	document.write('<DIV ID=leejoo_box1 STYLE="position:absolute;visibility:hidden;'+leejoo_classe+';width:20;height:'+(leejoo_hauteur+20)+';z-index:10"></DIV>');
 	document.write('<DIV ID=leejoo_box2 STYLE="position:absolute;visibility:hidden;'+leejoo_classe+';width:'+leejoo_box2+';height:'+leejoo_hauteur+';z-index:5');
 	if(document.all)
 		document.write(';padding:10');
 	document.write('"></DIV>');
 	document.write('<DIV ID=leejoo_box3 STYLE="position:absolute;visibility:hidden;'+leejoo_classe+';width:20;height:'+(leejoo_hauteur+20)+';z-index:10"></DIV>');
 	leejoo_Y = document.body.clientHeight;
 	leejoo_X = document.body.clientWidth;
 	leejoo_posX = Math.round(leejoo_X/2);
 	leejoo_posY = Math.round(leejoo_Y/2)-Math.round(leejoo_hauteur/2);
 	}

 function leejoo_deplace()
 	{
 	document.getElementById("leejoo_box1").style.left = leejoo_posX-20-(leejoo_box2/2);
 	document.getElementById("leejoo_box3").style.left = leejoo_posX+(leejoo_box2/2);
 	document.getElementById("leejoo_box2").style.left = leejoo_posX-(leejoo_box2/2)-5;
 	document.getElementById("leejoo_box2").style.width = leejoo_box2+10;
 	leejoo_box2 += 20;
 	if(leejoo_box2<leejoo_largeur)
 		setTimeout("leejoo_deplace()",2);
 	else
 		{
 		document.getElementById("leejoo_box2").innerHTML = '<CENTER>'+leejoo_mess+'<BR><BR><B><button type=button onclick="leejoo_close()"><FONT COLOR='+leejoo_police_color+'>OK</FONT></A></B></CENTER>'
 		}
 	}

 function leejoo_start()
 	{
 	if(document.getElementById)
 		{
 		document.getElementById("leejoo_box1").style.visibility = 'visible'
 		document.getElementById("leejoo_box2").style.visibility = 'visible'
 		document.getElementById("leejoo_box3").style.visibility = 'visible'
 		document.getElementById("leejoo_box1").style.top = leejoo_posY-10
 		document.getElementById("leejoo_box2").style.top = leejoo_posY
 		document.getElementById("leejoo_box3").style.top = leejoo_posY-10
 		leejoo_deplace();
 		}
 	}

 function leejoo_close()
 	{
 	if(document.getElementById)
 		{
 		document.getElementById("leejoo_box1").style.visibility = 'hidden'
 		document.getElementById("leejoo_box2").style.visibility = 'hidden'
 		document.getElementById("leejoo_box3").style.visibility = 'hidden'
 		document.getElementById("leejoo_box1").style.top = -600
 		document.getElementById("leejoo_box2").style.top = -600
 		document.getElementById("leejoo_box3").style.top = -600
 		leejoo_deplace();
 		location.href='shop.php';
 		}
 	}

  window.onload = leejoo_start;
</script>
 {/literal}
{include file="footer.tpl"}