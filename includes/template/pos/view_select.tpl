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
<h2 style='float: left;'>{!pos_currenttickets!}</h2>
<div id="order-div" style="width:100%;">
<table>
  <tr>
  	<td>
      {!pos_listpaidunprocessed!}
  	</td>
  	<td>
  	  <a href="view.php?process=paid">{!pos_paidlist!}</a>
  	</td>
  </tr>
  <tr>
  	<td>
  	  {!pos_listpaidunsent!}
  	</td>
  	<td>
  	  <a href="view.php?process=processed">{!pos_unsentlist!}</a>
  	</td>
  </tr>
  <tr>
	<td>
	  {!pos_listpaidsent!}
	</td>
	<td>
	  <a href="view.php?process=sent">{!pos_sentlist!}</a>
	</td>
  </tr>
</table>
</div>