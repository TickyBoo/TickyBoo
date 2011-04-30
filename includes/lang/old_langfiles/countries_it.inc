<?php
/*
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
 */

global $_COUNTRY_LIST;

$_COUNTRY_LIST=array(

 "af" => "Afghanistan" ,
 "al" => "Albania" ,
 "dz" => "Algeria" ,
 "ad" => "Andorra" ,
 "ao" => "Angola" ,
 "ai" => "Anguilla" ,
 "aq" => "Antartico" ,
 "ag" => "Antigua e Barbuda" ,
 "an" => "Antille Olandesi" ,
 "sa" => "Arabia Saudita" ,
 "ar" => "Argentina" ,
 "am" => "Armenia" ,
 "aw" => "Aruba" ,
 "au" => "Australia" ,
 "at" => "Austria" ,
 "az" => "Azerbaigian" ,
 "bs" => "Bahamas" ,
 "bh" => "Bahrein" ,
 "bd" => "Bangladesh" ,
 "bb" => "Barbados" ,
 "be" => "Belgio" ,
 "bz" => "Belize" ,
 "bj" => "Benin" ,
 "bm" => "Bermuda" ,
 "bt" => "Bhutan" ,
 "by" => "Bielorussia" ,
 "mm" => "Birmania" ,
 "bo" => "Bolivia" ,
 "ba" => "Bosnia-Erzegovina" ,
 "bw" => "Botswana" ,
 "bv" => "Bouvet (isola)" ,
 "br" => "Brasile" ,
 "bn" => "Brunei" ,
 "bg" => "Bulgaria" ,
 "bf" => "Burkina Faso" ,
 "bi" => "Burundi" ,
 "kh" => "Cambogia" ,
 "cm" => "Camerun" ,
 "ca" => "Canada" ,
 "cv" => "Capo Verde" ,
 "ky" => "Cayman (isole)" ,
 "cx" => "Christmas (isole)" ,
 "td" => "Ciad" ,
 "cl" => "Cile" ,
 "cn" => "Cina" ,
 "cy" => "Cipro" ,
 "va" => "Citt?del Vaticano" ,
 "cc" => "Cocos (isole)" ,
 "co" => "Colombia" ,
 "km" => "Comore" ,
 "cg" => "Congo" ,
 "ck" => "Cook (Isole)" ,
 "kp" => "Corea del nord" ,
 "kr" => "Corea del sud" ,
 "cr" => "Costa Rica" ,
 "ci" => "Costa d'Avorio" ,
 "hr" => "Croazia" ,
 "cu" => "Cuba" ,
 "dk" => "Danimarca" ,
 "dm" => "Dominica" ,
 "ec" => "Ecuador" ,
 "eg" => "Egitto" ,
 "sv" => "El Salvador" ,
 "ae" => "Emirati Arabi Uniti" ,
 "er" => "Eritrea" ,
 "ee" => "Estonia" ,
 "et" => "Etiopia" ,
 "fo" => "Faer Oer (isole)" ,
 "fk" => "Falkland (isole)" ,
 "fj" => "Figi" ,
 "ph" => "Filippine" ,
 "fi" => "Finlandia" ,
 "fr" => "Francia" ,
 "fx" => "Francia, Territori d' Oltre..." ,
 "ga" => "Gabon" ,
 "gm" => "Gambia" ,
 "ge" => "Georgia" ,
 "gs" => "Georgia del sud e Hawaii (i..." ,
 "de" => "Germania" ,
 "gh" => "Ghana" ,
 "jm" => "Giamaica" ,
 "jp" => "Giappone" ,
 "gi" => "Gibilterra" ,
 "dj" => "Gibuti" ,
 "jo" => "Giordania" ,
 "gr" => "Grecia" ,
 "gd" => "Grenada" ,
 "gl" => "Groenlandia" ,
 "gp" => "Guadalupa" ,
 "gu" => "Guam" ,
 "gt" => "Guatemala" ,
 "gn" => "Guinea" ,
 "gq" => "Guinea Equatoriale" ,
 "gw" => "Guinea-Bissau" ,
 "gy" => "Guyana" ,
 "gf" => "Guyana Francese" ,
 "ht" => "Haiti" ,
 "hm" => "Heard e Mc Donald (isole)" ,
 "hn" => "Honduras" ,
 "hk" => "Hong Kong" ,
 "in" => "India" ,
 "id" => "Indonesia" ,
 "ir" => "Iran" ,
 "iq" => "Iraq" ,
 "ie" => "Irlanda" ,
 "is" => "Islanda" ,
 "vg" => "Isole Vergini Britanniche" ,
 "il" => "Israele" ,
 "it" => "Italia" ,
 "yu" => "Iugoslava" ,
 "kz" => "Kazakistan" ,
 "ke" => "Kenya" ,
 "kg" => "Kirghizistan" ,
 "ki" => "Kiribati" ,
 "kw" => "Kuwait" ,
 "la" => "Laos" ,
 "ls" => "Lesotho" ,
 "lv" => "Lettonia" ,
 "lb" => "Libano" ,
 "lr" => "Liberia" ,
 "ly" => "Libia" ,
 "li" => "Liechtenstein" ,
 "lt" => "Lituania" ,
 "lu" => "Lussemburgo" ,
 "mo" => "Macao" ,
 "mk" => "Macedonia" ,
 "mg" => "Madagascar" ,
 "mw" => "Malawi" ,
 "my" => "Malaysia" ,
 "mv" => "Maldive" ,
 "ml" => "Mali" ,
 "mt" => "Malta" ,
 "mp" => "Marianne Settentrionali (is..." ,
 "ma" => "Marocco" ,
 "mh" => "Marshall" ,
 "mq" => "Martinica" ,
 "mr" => "Mauritania" ,
 "mu" => "Maurizio" ,
 "yt" => "Mayotte" ,
 "mx" => "Messico" ,
 "fm" => "Micronesia, Stati Federati di" ,
 "md" => "Moldavia" ,
 "mn" => "Mongolia" ,
 "ms" => "Montserrat" ,
 "mz" => "Mozambico" ,
 "na" => "Namibia" ,
 "nr" => "Nauru" ,
 "np" => "Nepal" ,
 "ni" => "Nicaragua" ,
 "ne" => "Niger" ,
 "ng" => "Nigeria" ,
 "nu" => "Niue" ,
 "nf" => "Norfolk (isole)" ,
 "no" => "Norvegia" ,
 "nc" => "Nuova Caledonia" ,
 "nz" => "Nuova Zelanda" ,
 "nl" => "Olanda" ,
 "om" => "Oman" ,
 "pk" => "Pakistan" ,
 "pw" => "Palau" ,
 "pa" => "Panama" ,
 "pg" => "Papua Nuova Guinea" ,
 "py" => "Paraguay" ,
 "pe" => "Per?															pn-Pitcairn (isole)" ,
 "pf" => "Polinesia Francese" ,
 "pl" => "Polonia" ,
 "pt" => "Portogallo" ,
 "mc" => "Principato di Monaco" ,
 "pr" => "Puerto Rico" ,
 "qa" => "Qatar" ,
 "gb" => "Regno Unito" ,
 "do" => "Reppublica Dominicana" ,
 "cz" => "Repubblica Ceca" ,
 "cf" => "Repubblica Centrafricana" ,
 "cd" => "Repubblica Democratica del ..." ,
 "za" => "Repubblica Sudafricana" ,
 "re" => "Reunion" ,
 "ro" => "Romania" ,
 "rw" => "Ruanda" ,
 "ru" => "Russia" ,
 "eh" => "Sahara Occidentale" ,
 "lc" => "Saint Lucia" ,
 "pm" => "Saint Pierre e Miquelon" ,
 "kn" => "Saint-Kitts e Nevis" ,
 "sb" => "Salomone (isole)" ,
 "as" => "Samoa" ,
 "ws" => "Samoa" ,
 "sm" => "San Marino" ,
 "vc" => "San Vicente e Grenadine" ,
 "sh" => "Sant Elena (isola)" ,
 "st" => "Sao Tom?e Principe" ,
 "sc" => "Seicelle" ,
 "sn" => "Senegal" ,
 "sl" => "Sierra Leone" ,
 "sg" => "Singapore" ,
 "sy" => "Siria" ,
 "sk" => "Slovacchia" ,
 "si" => "Slovenia" ,
 "so" => "Somalia" ,
 "es" => "Spagna" ,
 "lk" => "Sri Lanka" ,
 "us" => "Stati Uniti d'America" ,
 "sd" => "Sudan" ,
 "sr" => "Suriname" ,
 "sj" => "Svalbard e Isole Jan Mayen" ,
 "se" => "Svezia" ,
 "ch" => "Svizzera" ,
 "sz" => "Swaziland" ,
 "tj" => "Tagikistan" ,
 "tw" => "Taiwan" ,
 "tz" => "Tanzania" ,
 "th" => "Thailandia" ,
 "tp" => "Timor" ,
 "tg" => "Togo" ,
 "tk" => "Tokelau (isole)" ,
 "to" => "Tonga" ,
 "tt" => "Trinidad  e Tobago" ,
 "tn" => "Tunisia" ,
 "tr" => "Turchia" ,
 "tm" => "Turkmenistan" ,
 "tc" => "Turks e Caicos (isole)" ,
 "tv" => "Tuvalu (isole)" ,
 "ua" => "Ucraina" ,
 "ug" => "Uganda" ,
 "hu" => "Ungheria" ,
 "uy" => "Uruguay" ,
 "uz" => "Uzbekistan" ,
 "vu" => "Vanuatu" ,
 "ve" => "Venezuela" ,
 "vn" => "Viet Nam" ,
 "wf" => "Wallis e Futuna (isole)" ,
 "ye" => "Yemen" ,
 "zm" => "Zambia" ,
 "zw" => "Zimbabwe" ,
 "vi" => "isole Vergini Americane");
?>