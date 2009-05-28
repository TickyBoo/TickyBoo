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
List of countries in english. Usage example:

<select name='county'>
<option value=''>--choose a country--</option>
{include file='countries_en.tpl' selected='ch'}
</select>
*}


{assign var="c_$selected" value="selected"}

<option value="gb" {$c_gb}>United Kingdom</option>
<option value="af" {$c_af}>Afghanistan</option>
<option value="al" {$c_al}>Albania</option>
<option value="dz" {$c_dz}>Algeria</option>
<option value="as" {$c_as}>American Samoa</option>
<option value="ad" {$c_ad}>Andorra</option>
<option value="ao" {$c_ao}>Angola</option>
<option value="ai" {$c_ai}>Anguilla</option>
<option value="aq" {$c_aq}>Antarctica</option>
<option value="ag" {$c_ag}>Antigua and Barbuda</option>
<option value="ar" {$c_ar}>Argentina</option>
<option value="am" {$c_am}>Armenia</option>
<option value="aw" {$c_aw}>Aruba</option>
<option value="au" {$c_au}>Australia</option>
<option value="at" {$c_at}>Austria</option>
<option value="az" {$c_az}>Azerbaijan</option>
<option value="bs" {$c_bs}>Bahamas</option>
<option value="bh" {$c_bh}>Bahrain</option>
<option value="bd" {$c_bd}>Bangladesh</option>
<option value="bb" {$c_bb}>Barbados</option>
<option value="by" {$c_by}>Belarus</option>
<option value="be" {$c_be}>Belgium</option>
<option value="bz" {$c_bz}>Belize</option>
<option value="bj" {$c_bj}>Benin</option>
<option value="bm" {$c_bm}>Bermuda</option>
<option value="bt" {$c_bt}>Bhutan</option>
<option value="bo" {$c_bo}>Bolivia</option>
<option value="ba" {$c_ba}>Bosnia and Herzegovina</option>
<option value="bw" {$c_bw}>Botswana</option>
<option value="bv" {$c_bv}>Bouvet Island</option>
<option value="br" {$c_br}>Brazil</option>
<option value="bn" {$c_bn}>Brunei</option>
<option value="bg" {$c_bg}>Bulgaria</option>
<option value="bf" {$c_bf}>Burkina Faso</option>
<option value="bi" {$c_bi}>Burundi</option>
<option value="kh" {$c_kh}>Cambodia</option>
<option value="cm" {$c_cm}>Cameroon</option>
<option value="ca" {$c_ca}>Canada</option>
<option value="cv" {$c_cv}>Cape Verde</option>
<option value="ky" {$c_ky}>Cayman Islands</option>
<option value="cf" {$c_cf}>Central African Republic</option>
<option value="td" {$c_td}>Chad</option>
<option value="cl" {$c_cl}>Chile</option>
<option value="cn" {$c_cn}>China</option>
<option value="cx" {$c_cx}>Christmas Island</option>
<option value="cc" {$c_cc}>Cocos (Keeling) Islands</option>
<option value="co" {$c_co}>Colombia</option>
<option value="km" {$c_km}>Comores</option>
<option value="cg" {$c_cg}>Congo</option>
<option value="cd" {$c_cd}>Congo, democratic republic of</option>
<option value="ck" {$c_ck}>Cook Islands</option>
<option value="cr" {$c_cr}>Costa Rica</option>
<option value="ci" {$c_ci}>Cote d'Ivoire</option>
<option value="hr" {$c_hr}>Croatia (local name: Hrvatska)</option>
<option value="cu" {$c_cu}>Cuba</option>
<option value="cy" {$c_cy}>Cyprus</option>
<option value="cz" {$c_cz}>Czech Republic</option>
<option value="dk" {$c_dk}>Denmark</option>
<option value="dj" {$c_dj}>Djibouti</option>
<option value="dm" {$c_dm}>Dominica</option>
<option value="do" {$c_do}>Dominican Republic</option>
<option value="tp" {$c_tp}>East Timor</option>
<option value="ec" {$c_ec}>Ecuador</option>
<option value="eg" {$c_eg}>Egypt</option>
<option value="sv" {$c_sv}>El Salvador</option>
<option value="gq" {$c_gq}>Equatorial Guinea</option>
<option value="er" {$c_er}>Eritrea</option>
<option value="ee" {$c_ee}>Estonia</option>
<option value="et" {$c_et}>Ethiopia</option>
<option value="fk" {$c_fk}>Falkland Islands (Malvinas)</option>
<option value="fo" {$c_fo}>Faroe Islands</option>
<option value="fj" {$c_fj}>Fiji</option>
<option value="fi" {$c_fi}>Finland</option>
<option value="fr" {$c_fr}>France</option>
<option value="fx" {$c_fx}>France, Metropolitan</option>
<option value="gf" {$c_gf}>French Guiana</option>
<option value="pf" {$c_pf}>French Polynesia</option>
<option value="ga" {$c_ga}>Gabon</option>
<option value="gm" {$c_gm}>Gambia</option>
<option value="ge" {$c_ge}>Georgia</option>
<option value="de" {$c_de}>Germany</option>
<option value="gh" {$c_gh}>Ghana</option>
<option value="gi" {$c_gi}>Gibraltar</option>
<option value="gr" {$c_gr}>Greece</option>
<option value="gl" {$c_gl}>Greenland</option>
<option value="gd" {$c_gd}>Grenada</option>
<option value="gp" {$c_gp}>Guadeloupe</option>
<option value="gu" {$c_gu}>Guam</option>
<option value="gt" {$c_gt}>Guatemala</option>
<option value="gn" {$c_gn}>Guinea</option>
<option value="gw" {$c_gw}>Guinea-Bissau</option>
<option value="gy" {$c_gy}>Guyana</option>
<option value="ht" {$c_ht}>Haiti</option>
<option value="hm" {$c_hm}>Heard and Mc Donald Islands</option>
<option value="hn" {$c_hn}>Honduras</option>
<option value="hk" {$c_hk}>Hong Kong</option>
<option value="hu" {$c_hu}>Hungary</option>
<option value="is" {$c_is}>Iceland</option>
<option value="in" {$c_in}>India</option>
<option value="id" {$c_id}>Indonesia</option>
<option value="ir" {$c_ir}>Iran (Islamic Republic of)</option>
<option value="iq" {$c_iq}>Iraq</option>
<option value="ie" {$c_ie}>Ireland</option>
<option value="il" {$c_il}>Israel</option>
<option value="it" {$c_it}>Italy</option>
<option value="jm" {$c_jm}>Jamaica</option>
<option value="jp" {$c_jp}>Japan</option>
<option value="jo" {$c_jo}>Jordan</option>
<option value="kz" {$c_kz}>Kazakhstan</option>
<option value="ke" {$c_ke}>Kenya</option>
<option value="ki" {$c_ki}>Kiribati</option>
<option value="kp" {$c_kp}>Korea, Democratic People's ...</option>
<option value="kr" {$c_kr}>Korea, Republic of</option>
<option value="kw" {$c_kw}>Kuwait</option>
<option value="kg" {$c_kg}>Kyrgyzstan</option>
<option value="la" {$c_la}>Lao People's Democratic Rep...</option>
<option value="lv" {$c_lv}>Lativa</option>
<option value="lb" {$c_lb}>Lebanon</option>
<option value="ls" {$c_ls}>Lesotho</option>
<option value="lr" {$c_lr}>Liberia</option>
<option value="ly" {$c_ly}>Libya</option>
<option value="li" {$c_li}>Liechtenstein</option>
<option value="lt" {$c_lt}>Lithuania</option>
<option value="lu" {$c_lu}>Luxenbourg</option>
<option value="mo" {$c_mo}>Macau</option>
<option value="mk" {$c_mk}>Macedonia</option>
<option value="mg" {$c_mg}>Madagascar</option>
<option value="mw" {$c_mw}>Malawi</option>
<option value="my" {$c_my}>Malaysia</option>
<option value="mv" {$c_mv}>Maldives</option>
<option value="ml" {$c_ml}>Mali</option>
<option value="mt" {$c_mt}>Malta</option>
<option value="mh" {$c_mh}>Marshall Islands</option>
<option value="mq" {$c_mq}>Martinique</option>
<option value="mr" {$c_mr}>Mauritania</option>
<option value="mu" {$c_mu}>Mauritius</option>
<option value="yt" {$c_yt}>Mayotte</option>
<option value="mx" {$c_mx}>Mexico</option>
<option value="fm" {$c_fm}>Micronesia, Federated State...</option>
<option value="md" {$c_md}>Moldova, Republic of</option>
<option value="mc" {$c_mc}>Monaco</option>
<option value="mn" {$c_mn}>Mongolia</option>
<option value="ms" {$c_ms}>Montserrat</option>
<option value="ma" {$c_ma}>Morocco</option>
<option value="mz" {$c_mz}>Mozambique</option>
<option value="mm" {$c_mm}>Myanmar</option>
<option value="na" {$c_na}>Namibia</option>
<option value="nr" {$c_nr}>Nauru</option>
<option value="np" {$c_np}>Nepal</option>
<option value="nl" {$c_nl}>Netherlands</option>
<option value="an" {$c_an}>Netherlands Antilles</option>
<option value="nc" {$c_nc}>New Caledonia</option>
<option value="nz" {$c_nz}>New Zealand</option>
<option value="ni" {$c_ni}>Nicaragua</option>
<option value="ne" {$c_ne}>Niger</option>
<option value="ng" {$c_ng}>Nigeria</option>
<option value="nu" {$c_nu}>Niue</option>
<option value="nf" {$c_nf}>Norfolk Island</option>
<option value="mp" {$c_mp}>Northern Mariana Islands</option>
<option value="no" {$c_no}>Norway</option>
<option value="om" {$c_om}>Oman</option>
<option value="pk" {$c_pk}>Pakistan</option>
<option value="pw" {$c_pw}>Palau</option>
<option value="pa" {$c_pa}>Panama</option>
<option value="pg" {$c_pg}>Papua New Guinea</option>
<option value="py" {$c_py}>Paraguay</option>
<option value="pe" {$c_pe}>Peru</option>
<option value="ph" {$c_ph}>Philippines</option>
<option value="pn" {$c_pn}>Pitcairn</option>
<option value="pl" {$c_pl}>Poland</option>
<option value="pt" {$c_pt}>Portugal</option>
<option value="pr" {$c_pr}>Puerto Rico</option>
<option value="qa" {$c_qa}>Qatar</option>
<option value="re" {$c_re}>Reunion</option>
<option value="ro" {$c_ro}>Romania</option>
<option value="ru" {$c_ru}>Russian Federation</option>
<option value="rw" {$c_rw}>Rwanda</option>
<option value="kn" {$c_kn}>Saint Kitts and Nevis</option>
<option value="lc" {$c_lc}>Saint Lucia</option>
<option value="vc" {$c_vc}>Saint Vincent and the Grena...</option>
<option value="ws" {$c_ws}>Samoa</option>
<option value="sm" {$c_sm}>San Marino</option>
<option value="st" {$c_st}>Sao Tome and Principe</option>
<option value="sa" {$c_sa}>Saudi Arabia</option>
<option value="sn" {$c_sn}>Senegal</option>
<option value="sc" {$c_sc}>Seychelles</option>
<option value="sl" {$c_sl}>Sierra Leone</option>
<option value="sg" {$c_sg}>Singapore</option>
<option value="sk" {$c_sk}>Slovakia (Slovak Republic)</option>
<option value="si" {$c_si}>Slovenia</option>
<option value="sb" {$c_sb}>Solomon Islands</option>
<option value="so" {$c_so}>Somalia</option>
<option value="za" {$c_za}>South Africa</option>
<option value="gs" {$c_gs}>South Georgia and the South...</option>
<option value="es" {$c_es}>Spain</option>
<option value="lk" {$c_lk}>Sri Lanka</option>
<option value="sh" {$c_sh}>St. Helena</option>
<option value="pm" {$c_pm}>St. Pierre and Miquelon</option>
<option value="sd" {$c_sd}>Sudan</option>
<option value="sr" {$c_sr}>Surinam</option>
<option value="sj" {$c_sj}>Svalbard and Jan Mayen Islands</option>
<option value="sz" {$c_sz}>Swaziland</option>
<option value="se" {$c_se}>Sweden</option>
<option value="ch" {$c_ch}>Switzerland</option>
<option value="sy" {$c_sy}>Syrian Arab Republic</option>
<option value="tw" {$c_tw}>Taiwan, Province of China</option>
<option value="tj" {$c_tj}>Tajikistan</option>
<option value="tz" {$c_tz}>Tanzania, United Republic of</option>
<option value="th" {$c_th}>Thailand</option>
<option value="tg" {$c_tg}>Togo</option>
<option value="tk" {$c_tk}>Tokelau</option>
<option value="to" {$c_to}>Tonga</option>
<option value="tt" {$c_tt}>Trinidad and Tobago</option>
<option value="tn" {$c_tn}>Tunisia</option>
<option value="tr" {$c_tr}>Turkey</option>
<option value="tm" {$c_tm}>Turkmenistan</option>
<option value="tc" {$c_tc}>Turks and Caicos Islands</option>
<option value="tv" {$c_tv}>Tuvalu</option>
<option value="ug" {$c_ug}>Uganda</option>
<option value="ua" {$c_ua}>Ukraine</option>
<option value="ae" {$c_ae}>United Arab Emirates</option>
<option value="us" {$c_us}>United States</option>
<option value="uy" {$c_uy}>Uruguay</option>
<option value="uz" {$c_uz}>Uzbekistan</option>
<option value="vu" {$c_vu}>Vanuatu</option>
<option value="va" {$c_va}>Vatican City State</option>
<option value="ve" {$c_ve}>Venezuela</option>
<option value="vn" {$c_vn}>Vietnam</option>
<option value="vi" {$c_vi}>Virgin Islands  (U.S.)</option>
<option value="vg" {$c_vg}>Virgin Islands (British)</option>
<option value="wf" {$c_wf}>Wallis and Futuna Islands</option>
<option value="eh" {$c_eh}>Western Sahara</option>
<option value="ye" {$c_ye}>Yemen</option>
<option value="yu" {$c_yu}>Yugoslavia</option>
<option value="zm" {$c_zm}>Zambia</option>
<option value="zw" {$c_zw}>Zimbabwe</option>