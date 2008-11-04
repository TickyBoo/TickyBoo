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
{*
List of US states. Usage example:

<select name='state'>
<option value=''>--choose a state--</option>
{include file='states.tpl' selected='HI'}
</select>

*}

{if $selected}
	{assign var="s_$selected" value="selected"}
{elseif $default}
	{assign var="s_$default" value="selected"}
{/if}
<option Value="not applicable">------</option>
<option Value="AL" {$s_AL}>Alabama</option>
<option Value="AK" {$s_AK}>Alaska</option>
<option Value="AZ" {$s_AZ}>Arizona</option>
<option Value="AR" {$s_AR}>Arkansas</option>
<option Value="CA" {$s_CA}>California</option>
<option Value="CO" {$s_CO}>Colorado</option>
<option Value="CT" {$s_CT}>Connecticut</option>
<option Value="DC" {$s_DC}>D.C.</option>
<option Value="DE" {$s_DE}>Delaware</option>
<option Value="FL" {$s_FL}>Florida</option>
<option Value="GA" {$s_GA}>Georgia</option>
<option Value="HI" {$s_HI}>Hawaii</option>
<option Value="ID" {$s_ID}>Idaho</option>
<option Value="IL" {$s_IL}>Illinois</option>
<option Value="IN" {$s_IN}>Indiana</option>
<option Value="IA" {$s_IA}>Iowa</option>
<option Value="KS" {$s_KS}>Kansas</option>
<option Value="KY" {$s_KY}>Kentucky</option>
<option Value="LA" {$s_LA}>Louisiana</option>
<option Value="ME" {$s_ME}>Maine</option>
<option Value="MD" {$s_MD}>Maryland</option>
<option Value="MA" {$s_MA}>Massachusetts</option>
<option Value="MI" {$s_MI}>Michigan</option>
<option Value="MN" {$s_MN}>Minnesota</option>
<option Value="MS" {$s_MS}>Mississippi</option>
<option Value="MO" {$s_MO}>Missouri</option>
<option Value="MT" {$s_MT}>Montana</option>
<option Value="NE" {$s_NE}>Nebraska</option>
<option Value="NV" {$s_NV}>Nevada</option>
<option Value="NH" {$s_NH}>New Hampshire</option>
<option Value="NJ" {$s_NJ}>New Jersey</option>
<option Value="NM" {$s_NM}>New Mexico</option>
<option Value="NY" {$s_NY}>New York</option>
<option Value="NC" {$s_NC}>North Carolina</option>
<option Value="ND" {$s_ND}>North Dakota</option>
<option Value="OH" {$s_OH}>Ohio</option>
<option Value="OK" {$s_OK}>Oklahoma</option>
<option Value="OR" {$s_OR}>Oregon</option>
<option Value="PA" {$s_PA}>Pennsylvania</option>
<option Value="RI" {$s_RI}>Rhode Island</option>
<option Value="SC" {$s_SC}>South Carolina</option>
<option Value="SD" {$s_SD}>South Dakota</option>
<option Value="TN" {$s_TN}>Tennessee</option>
<option Value="TX" {$s_TX}>Texas</option>
<option Value="UT" {$s_UT}>Utah</option>
<option Value="VT" {$s_VT}>Vermont</option>
<option Value="VA" {$s_VA}>Virginia</option>
<option Value="WA" {$s_WA}>Washington</option>
<option Value="WV" {$s_WV}>West Virginia</option>
<option Value="WI" {$s_WI}>Wisconsin</option>
<option Value="WY" {$s_WY}>Wyoming</option>
