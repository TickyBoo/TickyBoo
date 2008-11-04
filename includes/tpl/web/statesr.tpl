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
Reverse list of US states. Usage example:

HI is code of {include file='statesr.tpl' code='HI'}

*}{assign var="code" value=$code|upper}{if
 $code eq "AL"}Alabama{elseif
 $code eq "AK"}Alaska{elseif
 $code eq "AZ"}Arizona{elseif
 $code eq "AR"}Arkansas{elseif
 $code eq "CA"}California{elseif
 $code eq "CO"}Colorado{elseif
 $code eq "CT"}Connecticut{elseif
 $code eq "DC"}D.C.{elseif
 $code eq "DE"}Delaware{elseif
 $code eq "FL"}Florida{elseif
 $code eq "GA"}Georgia{elseif
 $code eq "HI"}Hawaii{elseif
 $code eq "ID"}Idaho{elseif
 $code eq "IL"}Illinois{elseif
 $code eq "IN"}Indiana{elseif
 $code eq "IA"}Iowa{elseif
 $code eq "KS"}Kansas{elseif
 $code eq "KY"}Kentucky{elseif
 $code eq "LA"}Louisiana{elseif
 $code eq "ME"}Maine{elseif
 $code eq "MD"}Maryland{elseif
 $code eq "MA"}Massachusetts{elseif
 $code eq "MI"}Michigan{elseif
 $code eq "MN"}Minnesota{elseif
 $code eq "MS"}Mississippi{elseif
 $code eq "MO"}Missouri{elseif
 $code eq "MT"}Montana{elseif
 $code eq "NE"}Nebraska{elseif
 $code eq "NV"}Nevada{elseif
 $code eq "NH"}New Hampshire{elseif
 $code eq "NJ"}New Jersey{elseif
 $code eq "NM"}New Mexico{elseif
 $code eq "NY"}New York{elseif
 $code eq "NC"}North Carolina{elseif
 $code eq "ND"}North Dakota{elseif
 $code eq "OH"}Ohio{elseif
 $code eq "OK"}Oklahoma{elseif
 $code eq "OR"}Oregon{elseif
 $code eq "PA"}Pennsylvania{elseif
 $code eq "RI"}Rhode Island{elseif
 $code eq "SC"}South Carolina{elseif
 $code eq "SD"}South Dakota{elseif
 $code eq "TN"}Tennessee{elseif
 $code eq "TX"}Texas{elseif
 $code eq "UT"}Utah{elseif
 $code eq "VT"}Vermont{elseif
 $code eq "VA"}Virginia{elseif
 $code eq "WA"}Washington{elseif
 $code eq "WV"}West Virginia{elseif
 $code eq "WI"}Wisconsin{elseif
 $code eq "WY"}Wyoming{/if}
