<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *  phpMyTicket - ticket reservation system
 *   Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
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
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 */

if (!defined('ft_check')) {die('System intrusion ');}

class install_settings {
  static function precheck($Install) {
    return true;
  }

  static function postcheck($Install) {
    Install_Request(Array('secure_site','timezone','trace_on'),'SHOP');
    Install_Request(Array('fixed_url'));
    return true;
  }

  static function display($Install) {
    Install_Form_Open ($Install->return_pg,'','Login to update you system');
    $_SESSION['SHOP']['secure_site'] = is($_SESSION['SHOP']['secure_site'], true);
    $secure    = ($_SESSION['SHOP']['secure_site'])?"checked='checked'":'';
    $fixed_url = ($_SESSION['SHOP']['root'])?"checked='checked'":'';
    $selectedzone = is($_SESSION['SHOP']['timezone'], date_default_timezone_get());
    echo "<table cellpadding=\"1\" cellspacing=\"2\" width=\"100%\">
            <tr>
               <td colspan=\"2\" class=\"admin_info\" >
                 Fusion ticket is designed to be used with SSL certificated (HTTPS) checkout pages. We strongly suggest to enable this on your site.
                 When You have no SSL on your site then you can turn this off.
              </td>
            </tr>
            <tr>
              <td width='30%'>Use secure checkout:</td>
              <td><input type='checkbox' {$secure} name='secure_site' value='1'></td>
            </tr>
            <tr> <td height='6px'></td> </tr>
            <tr>
               <td colspan=\"2\" class=\"admin_info\" >
                 By turning off the fixed root url (<i>\$_SHOP->root</i> or <i>\$_SHOP->root_secure</i>) fusion ticket will automatically set this values for you. <br> We stongly advice you to disable this.
              </td>
            </tr>
            <tr>
              <td width='30%'>fixed root url:</td>
              <td><input type='checkbox' {$fixed_url} name='fixed_url' value='1'></td>
            </tr>
            <tr> <td height='6px'></td> </tr>
            <tr>
              <td colspan=\"2\" class=\"admin_info\" >
                 On of the extra think we will us is the possiblity to set the right timezone where you host your system. Please set your timezone below.
              </td>
            </tr>
            <tr>
              <td>Timezone:</td>
              <td><select name=\"timezone\">".self::timezonechoice($selectedzone)."'</select></td>
            </tr>
            <tr> <td height='6px'></td> </tr>
            <tr>
              <td colspan=\"2\" class=\"admin_info\" >
                 You can help us a lot by automaticly sending trace log's. To get better idea of the possible problems we have created this trace log in our system. We do like te opportunity to get those logs when the system find a new problem.
              </td>
            </tr>
            <tr>
              <td>Trace log:</td>
              <td><select name=\"trace_on\"><option value='SEND'>eMail orphan errors</option><option value='ALL'>Alway log traces</option><option value='0'>Disable (faster response)</option> </select></td>
            </tr>
          </table>";
    Install_Form_Buttons ();
    Install_Form_Close ();
  }

  function timezonechoice($selectedzone) {
    $all = timezone_identifiers_list();

    $i = 0;
    foreach($all AS $zone) {
      $zone = explode('/',$zone);
      $zonen[$i]['continent'] = isset($zone[0]) ? $zone[0] : '';
      $zonen[$i]['city'] = isset($zone[1]) ? $zone[1] : '';
      $zonen[$i]['subcity'] = isset($zone[2]) ? $zone[2] : '';
      $i++;
    }

    asort($zonen);
    $structure = '';
    foreach($zonen AS $zone) {
      extract($zone);
      if($continent == 'Africa' || $continent == 'America' || $continent == 'Antarctica' || $continent == 'Arctic' || $continent == 'Asia' || $continent == 'Atlantic' || $continent == 'Australia' || $continent == 'Europe' || $continent == 'Indian' || $continent == 'Pacific') {
        if(!isset($selectcontinent)) {
          $structure .= '<optgroup label="'.$continent.'">'; // continent
        } elseif($selectcontinent != $continent) {
          $structure .= '</optgroup><optgroup label="'.$continent.'">'; // continent
        }

        if(isset($city) != ''){
          if (!empty($subcity) != ''){
            $city = $city . '/'. $subcity;
          }
          $structure .= "<option ".((($continent.'/'.$city)==$selectedzone)?'selected="selected "':'')." value=\"".($continent.'/'.$city)."\">".str_replace('_',' ',$city)."</option>"; //Timezone
        } else {
          if (!empty($subcity) != ''){
            $city = $city . '/'. $subcity;
          }
          $structure .= "<option ".(($continent==$selectedzone)?'selected="selected "':'')." value=\"".$continent."\">".$continent."</option>"; //Timezone
        }

        $selectcontinent = $continent;
      }
    }
    $structure .= '</optgroup>';
    return $structure;
  }
}
?>