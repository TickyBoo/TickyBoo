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

{if not $first}
  {assign var="first" value="0"}
{/if}
<table class="table_midtone">
  <tr>
    <td align='center'>
      {if $first gt 0}
        <a href='{$smarty.server.SCRIPT_NAME}?{$condition}&first=0'>&nbsp;|&lt;&nbsp;</a>
        &nbsp;<a href='{$smarty.server.SCRIPT_NAME}?{$condition}&first={$first-$length}'>&nbsp;&lt;&lt;&nbsp;</a>&nbsp;
      {else}
        &nbsp;|&lt;&nbsp;&nbsp;&nbsp;&lt;&lt;&nbsp;&nbsp;
      {/if}
      {assign var="page" value=$first/$length+1}
      &nbsp;[

      {section start=1 loop=6 name="nav"}
        {assign var=prev value=$first-$length*6+$length*$smarty.section.nav.index}
        {if $prev ge 0}
          &nbsp;<a href='{$smarty.server.SCRIPT_NAME}?{$condition}&first={$prev}'>{$page-6+$smarty.section.nav.index}</a>
        {/if}
      {/section}

      &nbsp;<b>{$page}</b>

      {section start=1 loop=5 name="nav"}
        {assign var=nst value=$first+$length*$smarty.section.nav.index}
        {if $nst lt $tot_count}
          &nbsp;<a href='{$smarty.server.SCRIPT_NAME}?{$condition}&first={$nst}'>{$page+$smarty.section.nav.index}</a>
        {/if}
      {/section}
      &nbsp;]&nbsp;

      &nbsp;
      {if $first+$length lt $tot_count}
        <a href='{$smarty.server.SCRIPT_NAME}?{$condition}&first={$first+$length}'>&nbsp;&gt;&gt;&nbsp;</a>
	      {assign var="cml" value=$tot_count%$length}
	      {if $cml}
	        &nbsp;<a href='{$smarty.server.SCRIPT_NAME}?{$condition}&first={$tot_count-$cml}'>&nbsp;&gt;|&nbsp;</a>
        {else}
	        &nbsp;<a href='{$smarty.server.SCRIPT_NAME}?{$condition}&first={$tot_count-$length}'>&nbsp;&gt;|&nbsp;</a>
        {/if}
      {else}
        &nbsp;&gt;&gt;&nbsp;&nbsp;&gt;|&nbsp;
      {/if}
    </td>
  </tr>
</table>

