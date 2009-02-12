{*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 *}
<table cellpadding='3' width='100%'>
  <tr>
  	<td colspan='4' class='title'>
	{#calendar#}
  	</td>
  </tr>
  <tr>
  	<td colspan='4' class='help'>
	To Book/Reserve Tickets, select an event from the list below.
  	</td>
  </tr>
{event start_date=$smarty.now|date_format:"%Y-%m-%d" ort='on' sub='on' stats='on' order="event_date,event_time"}
{assign var='month' value=$shop_event.event_date|date_format:"%B"}
  {if $month neq  $month1}
  {assign var='style' value="style='border-top:#45436d 1px solid; padding-top:10px;'"}
  <tr>
  	<td colspan='6' class='title' {$style}>{$shop_event.event_date|date_format:"%B %Y"}</td>
  </tr>
  {assign var='month1' value=$month}
  {/if}
  <tr >
	<td class='calendar'><a class='cal_link' href='shop.php?event_id={$shop_event.event_id}'>{$shop_event.event_name}</a></td>
	<td class='calendar'>{$shop_event.event_date|date_format:"%e %B"} - {$shop_event.event_time|date_format:" %Hh%M"}</td>
	<td class='calendar'>{$shop_event.ort_name}</td>
	<td class='calendar'>{$shop_event.ort_city}</td>
	<td class='calendar'>
	{if $shop_event.es_free gt 0}
  	  {if $shop_event.es_free/$shop_event.es_total ge 0.2}
    	<img src='images/green.png'> {$shop_event.es_free}/{$shop_event.es_total}
  	  {else}
    	<img src='images/orange.png'> {$shop_event.es_free}/{$shop_event.es_total}
	  {/if}
	{else}
  	  <img src='images/red.png'> event_sold_out
	{/if}
	</td>
	<td>{if $shop_event.event_mp3}<a href='{$shop_event.event_mp3}'><img src='images/audio-small.png' border='0'></a> {/if}</td>
  </tr>{assign var='style' value=""}
{/event}
</table>
