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
 {include file="header.tpl" name=!TestMe! }
{gui->startform name='testje' data=$smarty.post}

{gui->input type='text' name='user_firstname' size='30' maxlength='50'}
{gui->input type='text' name='user_zip' size='8'  maxlength='20'}
{gui->selectState name='user_state' }
{gui->selectCountry name='user_country'}

{gui->inputdate name='datefield'}
{gui->inputtime name='timefield' }
{gui->inputfile name='user_city'}
{gui->selectcolor name='user_Address'}
{gui->area name='user_status'}
{gui->captcha name='user_nospam' }

{gui->endform}
{gui->Navigation offset=$smarty.get.offset count=150 }