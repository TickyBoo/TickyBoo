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

{include file="header.tpl" name=!calendar!}
{assign var='length' value='15'}
{assign var=start_date value=$smarty.now|date_format:"%Y-%m-%d"}
<table class='table_dark'>
  {event start_date=$start_date sub='on' ort='on' load_organizer='on' place_map='on' order="event_date,event_time" first=$smarty.get.first length=$length}
    {assign var='month' value=$shop_event.event_date|date_format:"%B"}
    {if $month neq  $month1}
     <tr><td colspan='4' class='title' style='text-decoration:underline;'><br>{$shop_event.event_date|date_format:"%B %Y"}</td></tr>
     {assign var='month1' value=$month}
    {/if}
    <tr class='tr_{cycle values="0,1"}'>
      <td><a  href='index.php?event_id={$shop_event.event_id}'>
            {if $shop_event.event_pm_id}<img src='images/ticket.gif' border="0">
            {else}<img src='images/info.gif' border="0">{/if}
          </a>
      </td>
      <td ><a  href='index.php?event_id={$shop_event.event_id}'>{$shop_event.event_name}</a></td>
      <td>{$shop_event.event_date|date_format:!date_format!} - {$shop_event.event_time|date_format:!time_format!}</td>
      <td >{$shop_event.ort_name} {$shop_event.ort_city} {$shop_event.pm_name}</td>
      <td width="20">{if $shop_event.event_mp3}<a href='files/{$shop_event.event_mp3}'><img src='images/audio-small.png' border='0'></a>{/if}</td>
    </tr>
  {/event}
</table>
{include file="navigation.tpl" first=$smarty.get.first  tot_count=$shop_event.tot_count part_count=$shop_event.part_count length=$length}

{include file='footer.tpl'}
