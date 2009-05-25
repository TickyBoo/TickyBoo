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
 
 *}{*
Reverse of countries in english. Usage example:


CH is {include file='countries_enr.tpl' code='ch'}
*}{assign var=code value=$code|lower
}{if
 $code eq "af"}Afghanistan{elseif
 $code eq "al"}Albania{elseif
 $code eq "dz"}Algeria{elseif
 $code eq "as"}American Samoa{elseif
 $code eq "ad"}Andorra{elseif
 $code eq "ao"}Angola{elseif
 $code eq "ai"}Anguilla{elseif
 $code eq "aq"}Antarctica{elseif
 $code eq "ag"}Antigua and Barbuda{elseif
 $code eq "ar"}Argentina{elseif
 $code eq "am"}Armenia{elseif
 $code eq "aw"}Aruba{elseif
 $code eq "au"}Australia{elseif
 $code eq "at"}Austria{elseif
 $code eq "az"}Azerbaijan{elseif
 $code eq "bs"}Bahamas{elseif
 $code eq "bh"}Bahrain{elseif
 $code eq "bd"}Bangladesh{elseif
 $code eq "bb"}Barbados{elseif
 $code eq "by"}Belarus{elseif
 $code eq "be"}Belgium{elseif
 $code eq "bz"}Belize{elseif
 $code eq "bj"}Benin{elseif
 $code eq "bm"}Bermuda{elseif
 $code eq "bt"}Bhutan{elseif
 $code eq "bo"}Bolivia{elseif
 $code eq "ba"}Bosnia and Herzegovina{elseif
 $code eq "bw"}Botswana{elseif
 $code eq "bv"}Bouvet Island{elseif
 $code eq "br"}Brazil{elseif
 $code eq "bn"}Brunei{elseif
 $code eq "bg"}Bulgaria{elseif
 $code eq "bf"}Burkina Faso{elseif
 $code eq "bi"}Burundi{elseif
 $code eq "kh"}Cambodia{elseif
 $code eq "cm"}Cameroon{elseif
 $code eq "ca"}Canada{elseif
 $code eq "cv"}Cape Verde{elseif
 $code eq "ky"}Cayman Islands{elseif
 $code eq "cf"}Central African Republic{elseif
 $code eq "td"}Chad{elseif
 $code eq "cl"}Chile{elseif
 $code eq "cn"}China{elseif
 $code eq "cx"}Christmas Island{elseif
 $code eq "cc"}Cocos (Keeling) Islands{elseif
 $code eq "co"}Colombia{elseif
 $code eq "km"}Comores{elseif
 $code eq "cg"}Congo{elseif
 $code eq "cd"}Congo, democratic republic of{elseif
 $code eq "ck"}Cook Islands{elseif
 $code eq "cr"}Costa Rica{elseif
 $code eq "ci"}Cote d'Ivoire{elseif
 $code eq "hr"}Croatia (local name: Hrvatska){elseif
 $code eq "cu"}Cuba{elseif
 $code eq "cy"}Cyprus{elseif
 $code eq "cz"}Czech Republic{elseif
 $code eq "dk"}Denmark{elseif
 $code eq "dj"}Djibouti{elseif
 $code eq "dm"}Dominica{elseif
 $code eq "do"}Dominican Republic{elseif
 $code eq "tp"}East Timor{elseif
 $code eq "ec"}Ecuador{elseif
 $code eq "eg"}Egypt{elseif
 $code eq "sv"}El Salvador{elseif
 $code eq "gq"}Equatorial Guinea{elseif
 $code eq "er"}Eritrea{elseif
 $code eq "ee"}Estonia{elseif
 $code eq "et"}Ethiopia{elseif
 $code eq "fk"}Falkland Islands (Malvinas){elseif
 $code eq "fo"}Faroe Islands{elseif
 $code eq "fj"}Fiji{elseif
 $code eq "fi"}Finland{elseif
 $code eq "fr"}France{elseif
 $code eq "fx"}France, Metropolitan{elseif
 $code eq "gf"}French Guiana{elseif
 $code eq "pf"}French Polynesia{elseif
 $code eq "ga"}Gabon{elseif
 $code eq "gm"}Gambia{elseif
 $code eq "ge"}Georgia{elseif
 $code eq "de"}Germany{elseif
 $code eq "gh"}Ghana{elseif
 $code eq "gi"}Gibraltar{elseif
 $code eq "gr"}Greece{elseif
 $code eq "gl"}Greenland{elseif
 $code eq "gd"}Grenada{elseif
 $code eq "gp"}Guadeloupe{elseif
 $code eq "gu"}Guam{elseif
 $code eq "gt"}Guatemala{elseif
 $code eq "gn"}Guinea{elseif
 $code eq "gw"}Guinea-Bissau{elseif
 $code eq "gy"}Guyana{elseif
 $code eq "ht"}Haiti{elseif
 $code eq "hm"}Heard and Mc Donald Islands{elseif
 $code eq "hn"}Honduras{elseif
 $code eq "hk"}Hong Kong{elseif
 $code eq "hu"}Hungary{elseif
 $code eq "is"}Iceland{elseif
 $code eq "in"}India{elseif
 $code eq "id"}Indonesia{elseif
 $code eq "ir"}Iran (Islamic Republic of){elseif
 $code eq "iq"}Iraq{elseif
 $code eq "ie"}Ireland{elseif
 $code eq "il"}Israel{elseif
 $code eq "it"}Italy{elseif
 $code eq "jm"}Jamaica{elseif
 $code eq "jp"}Japan{elseif
 $code eq "jo"}Jordan{elseif
 $code eq "kz"}Kazakhstan{elseif
 $code eq "ke"}Kenya{elseif
 $code eq "ki"}Kiribati{elseif
 $code eq "kp"}Korea, Democratic People's ...{elseif
 $code eq "kr"}Korea, Republic of{elseif
 $code eq "kw"}Kuwait{elseif
 $code eq "kg"}Kyrgyzstan{elseif
 $code eq "la"}Lao People's Democratic Rep...{elseif
 $code eq "lv"}Lativa{elseif
 $code eq "lb"}Lebanon{elseif
 $code eq "ls"}Lesotho{elseif
 $code eq "lr"}Liberia{elseif
 $code eq "ly"}Libya{elseif
 $code eq "li"}Liechtenstein{elseif
 $code eq "lt"}Lithuania{elseif
 $code eq "lu"}Luxenbourg{elseif
 $code eq "mo"}Macau{elseif
 $code eq "mk"}Macedonia{elseif
 $code eq "mg"}Madagascar{elseif
 $code eq "mw"}Malawi{elseif
 $code eq "my"}Malaysia{elseif
 $code eq "mv"}Maldives{elseif
 $code eq "ml"}Mali{elseif
 $code eq "mt"}Malta{elseif
 $code eq "mh"}Marshall Islands{elseif
 $code eq "mq"}Martinique{elseif
 $code eq "mr"}Mauritania{elseif
 $code eq "mu"}Mauritius{elseif
 $code eq "yt"}Mayotte{elseif
 $code eq "mx"}Mexico{elseif
 $code eq "fm"}Micronesia, Federated State...{elseif
 $code eq "md"}Moldova, Republic of{elseif
 $code eq "mc"}Monaco{elseif
 $code eq "mn"}Mongolia{elseif
 $code eq "ms"}Montserrat{elseif
 $code eq "ma"}Morocco{elseif
 $code eq "mz"}Mozambique{elseif
 $code eq "mm"}Myanmar{elseif
 $code eq "na"}Namibia{elseif
 $code eq "nr"}Nauru{elseif
 $code eq "np"}Nepal{elseif
 $code eq "nl"}Netherlands{elseif
 $code eq "an"}Netherlands Antilles{elseif
 $code eq "nc"}New Caledonia{elseif
 $code eq "nz"}New Zealand{elseif
 $code eq "ni"}Nicaragua{elseif
 $code eq "ne"}Niger{elseif
 $code eq "ng"}Nigeria{elseif
 $code eq "nu"}Niue{elseif
 $code eq "nf"}Norfolk Island{elseif
 $code eq "mp"}Northern Mariana Islands{elseif
 $code eq "no"}Norway{elseif
 $code eq "om"}Oman{elseif
 $code eq "pk"}Pakistan{elseif
 $code eq "pw"}Palau{elseif
 $code eq "pa"}Panama{elseif
 $code eq "pg"}Papua New Guinea{elseif
 $code eq "py"}Paraguay{elseif
 $code eq "pe"}Peru{elseif
 $code eq "ph"}Philippines{elseif
 $code eq "pn"}Pitcairn{elseif
 $code eq "pl"}Poland{elseif
 $code eq "pt"}Portugal{elseif
 $code eq "pr"}Puerto Rico{elseif
 $code eq "qa"}Qatar{elseif
 $code eq "re"}Reunion{elseif
 $code eq "ro"}Romania{elseif
 $code eq "ru"}Russian Federation{elseif
 $code eq "rw"}Rwanda{elseif
 $code eq "kn"}Saint Kitts and Nevis{elseif
 $code eq "lc"}Saint Lucia{elseif
 $code eq "vc"}Saint Vincent and the Grena...{elseif
 $code eq "ws"}Samoa{elseif
 $code eq "sm"}San Marino{elseif
 $code eq "st"}Sao Tome and Principe{elseif
 $code eq "sa"}Saudi Arabia{elseif
 $code eq "sn"}Senegal{elseif
 $code eq "sc"}Seychelles{elseif
 $code eq "sl"}Sierra Leone{elseif
 $code eq "sg"}Singapore{elseif
 $code eq "sk"}Slovakia (Slovak Republic){elseif
 $code eq "si"}Slovenia{elseif
 $code eq "sb"}Solomon Islands{elseif
 $code eq "so"}Somalia{elseif
 $code eq "za"}South Africa{elseif
 $code eq "gs"}South Georgia and the South...{elseif
 $code eq "es"}Spain{elseif
 $code eq "lk"}Sri Lanka{elseif
 $code eq "sh"}St. Helena{elseif
 $code eq "pm"}St. Pierre and Miquelon{elseif
 $code eq "sd"}Sudan{elseif
 $code eq "sr"}Surinam{elseif
 $code eq "sj"}Svalbard and Jan Mayen Islands{elseif
 $code eq "sz"}Swaziland{elseif
 $code eq "se"}Sweden{elseif
 $code eq "ch"}Switzerland{elseif
 $code eq "sy"}Syrian Arab Republic{elseif
 $code eq "tw"}Taiwan, Province of China{elseif
 $code eq "tj"}Tajikistan{elseif
 $code eq "tz"}Tanzania, United Republic of{elseif
 $code eq "th"}Thailand{elseif
 $code eq "tg"}Togo{elseif
 $code eq "tk"}Tokelau{elseif
 $code eq "to"}Tonga{elseif
 $code eq "tt"}Trinidad and Tobago{elseif
 $code eq "tn"}Tunisia{elseif
 $code eq "tr"}Turkey{elseif
 $code eq "tm"}Turkmenistan{elseif
 $code eq "tc"}Turks and Caicos Islands{elseif
 $code eq "tv"}Tuvalu{elseif
 $code eq "ug"}Uganda{elseif
 $code eq "ua"}Ukraine{elseif
 $code eq "ae"}United Arab Emirates{elseif
 $code eq "gb"}United Kingdom{elseif
 $code eq "us"}United States{elseif
 $code eq "uy"}Uruguay{elseif
 $code eq "uz"}Uzbekistan{elseif
 $code eq "vu"}Vanuatu{elseif
 $code eq "va"}Vatican City State{elseif
 $code eq "ve"}Venezuela{elseif
 $code eq "vn"}Vietnam{elseif
 $code eq "vi"}Virgin Islands  (U.S.){elseif
 $code eq "vg"}Virgin Islands (British){elseif
 $code eq "wf"}Wallis and Futuna Islands{elseif
 $code eq "eh"}Western Sahara{elseif
 $code eq "ye"}Yemen{elseif
 $code eq "yu"}Yugoslavia{elseif
 $code eq "zm"}Zambia{elseif
 $code eq "zw"}Zimbabwe{/if}
