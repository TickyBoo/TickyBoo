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

require_once("admin/AdminView.php");
require_once("classes/ShopDB.php");
require_once("classes/PlaceMapPart.php");

class PlaceMapPartView extends AdminView {
    function __construct($width = 500)
    {
        $this->width = $width;
    }
    function zone_edit ($zone_ident, $pmp_id)
    {
        global $_SHOP;

        if (!$pmp = PlaceMapPart::load_full($pmp_id) or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
            return;
        }

        $doubles = $pmp->find_doubles($zone_ident);

        $this_zone = $pmp->zones[$zone_ident];
        // title
        echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>
            <tr><td class='admin_list_title' colspan='2'>
        	Zone: {$this_zone->pmz_name} ($this_zone->pmz_short_name)
        	<div class='admin_list_title'style='font-size:smaller;'>
        	{$pmp->pmp_name} ({$pmp->pmp_width}x{$pmp->pmp_height}) -
        	{$pmp->pm_name} -
        	{$pmp->ort_name} - {$pmp->event_name} - {$pmp->event_date}
        	</div>
        	</td></tr></table><br>";
            // numbering
        echo "<form action='{$_SERVER['PHP_SELF']}' method=POST name=thisform>
                <input type=hidden name=action value='pmz_save_num_pmp'>
              	<input type='hidden' name='pmp_id' value='$pmp_id'>
             	<input type='hidden' name='pmz_ident' value='$zone_ident'> ";
        // <input type='hidden' name='pm_ort_id' value='{$pm['pm_ort_id']}'>";
        $this->list_head(seat_numbering, 1);
        // echo "<table class='admin_list' width='$this->width' cellspacing='0' cellpadding='4'>\n";
        // echo "<tr><td class='admin_list_title'  align='center'>".seat_numbering."</td></tr>\n";
        echo "<tr><td align=center><table>";

        $zone_bounds = $pmp->zone_bounds($zone_ident);

        for($j = $zone_bounds['top'];$j <= $zone_bounds['bottom'];$j++) {
            echo "<tr>";;
            for($k = $zone_bounds['left'];$k <= $zone_bounds['right'];$k++) {
                $seat = $pmp->pmp_data[$j][$k];

                if ($z = $seat[PM_ZONE]) {
                    $zone = $zones[$z];
                    $col = "bgcolor='{$zone->pmz_color}'";
                } else {
                    $col = '';
                }

                echo "<td  $col>";
                if ($seat[PM_ZONE] == $zone_ident) {
                    if ($doubles[$j][$k]) {
                        echo "<input type='text' name='seat[$j][$k]' value='{$seat[PM_ROW]}/{$seat[PM_SEAT]}' size='4' style='font-size:8px;color:#cc0000;'>";
                    } else {
                        echo "<input type='text' name='seat[$j][$k]' value='{$seat[PM_ROW]}/{$seat[PM_SEAT]}' size='4' style='font-size:8px;'>";
                    }
                } else {
                    echo "&nbsp;";
                }
                echo "</td>\n";
            }
            echo "</tr>\n";
        }

        echo "</td></tr></table>";
        $this->form_foot(1);
        // echo "<tr><td align='center' class='admin_value' colspan='2'>
        // <input type=hidden name=action value='set_zone_num'>
        // <input type='submit' name='save' value='".save."'>
        // </tr></table><br>";
        echo "</form>";
        // auto numbering
        echo "<form action='{$_SERVER['PHP_SELF']}' method=POST>
  	<input type='hidden' name='pmp_id' value='$pmp_id'>
 	<input type='hidden' name='pmz_ident' value='$zone_ident'>
 	<input type='hidden' name='action' value='pmz_auto_num_pmp'>";

        $this->form_head(autonumber_pmz);

        if (!isset($data['first_row'])) {
            $data['first_row'] = 1;
        }
        if (!isset($data['step_row'])) {
            $data['step_row'] = 1;
        }
        if (!isset($data['first_seat'])) {
            $data['first_seat'] = 1;
        }
        if (!isset($data['step_seat'])) {
            $data['step_seat'] = 1;
        }

        $this->print_input('first_row', $data, $err, 3, 4);
        $this->print_input('step_row', $data, $err, 3, 4);
        $this->print_checkbox('inv_row', $data, $err);
        $this->print_input('first_seat', $data, $err, 3, 4);
        $this->print_input('step_seat', $data, $err, 3, 4);
        $this->print_checkbox('inv_seat', $data, $err);
        $this->print_checkbox('flip', $data, $err);
        $this->print_checkbox('keep', $data, $err);

        $this->form_foot();

        echo "</form><br>";

        echo "<center><a href='{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id=$pmp_id' class='link'>" . map . "</a></center>";
        $this->pmp_short_list($pmp->pm_id, $pmp->pmp_id);
    }

    function pmp_view_only ($pmp_id)
    {
        $this->pmp_view($pmp_id, 0, 0, true);
    }

    function pmp_view ($pmp_id, $sel_cat = 0, $sel_pmz = 0, $view_only = false)
    {
        global $_SHOP;

        if (!isset($pmp)) {
            $pmp = PlaceMapPart::load_full($pmp_id);
        }

        if ($pmp->pmp_organizer_id and $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
            return;
        }

        if ($_SHOP->organizer_id != $pmp->pmp_organizer_id) {
            $view_only = true;
        }

        if ($pmp->event_id and $pmp->event_status != 'unpub') {
            $view_only = true;
        }

        $stats = $pmp->get_stats();
        // infos
        echo "<table class='admin_form' width='$this->width' border=0 cellspacing='1' cellpadding='5'>
              <tr><td class='admin_list_title' colspan='2'>	{$pmp->pm_id} {$pmp->pm_name} {$pmp->ort_name}";

        if (!$view_only) {
            echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_pmp&pmp_id={$pmp->pmp_id}'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>";
        }

        echo "<br>
            	{$pmp->event_name} {$pmp->event_date} {$pmp->event_time}
            	</td></tr>
                     </table>";

        global $_SHOP;

        echo '<table border=0 cellpadding=0 cellspacing=0 width="100%">
               <tr><td align="center" height=3 class="admin_value" colspan="2"> </td></tr>
               <tr><td width="50%" align=left valign=top>';

        $this->list_head(categories, 6, '99%');
        $alt = 0;
        if (!empty($pmp->categories)) {
            foreach($pmp->categories as $ident => $category) {
                if ($stats->categories[$ident]) {
                    echo "<tr class='admin_list_row_$alt'>";
                    echo "<td class='admin_list_item' width=10 bgcolor='{$category->color_code}'>&nbsp;</td>\n";
                    echo "<td class='admin_list_item' >{$category->category_name}</td>\n";
                    echo "<td class='admin_list_item'>{$category->category_price} {$_SHOP->currency}</td>\n";
                    echo "<td class='admin_list_item' align='right'>{$stats->categories[$ident]}</td>\n";
                    echo "<td class='admin_list_item'>{$category->category_numbering}</td>\n";
                    if (!$view_only) {
                        echo "<td class='admin_list_item' width=18 align=right><a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id=$pmp_id&category_id={$category->category_ident}'><img height=15 src='images/checkbox-checked.gif' border='0' alt='" . view . "' title='" . view . "'></a></td>\n";
                    } else {
                        echo "<td></td>\n";
                    }
                    echo'</tr>';
                    $alt = ($alt + 1) % 2;
                }
            }
        }
        echo "</table>";

        echo '</td><td align=right valign=top>';
        // zones
        $alt = 0;
        $this->list_head(pm_zones, 5, '99%');

        if (!empty($pmp->zones)) {
            foreach($pmp->zones as $zone_ident => $zone) {
                if ($stats->zones[$zone_ident]) {
                    echo "<tr class='admin_list_row_$alt'>";
                    echo "<td class='admin_list_item'  width=10 bgcolor='{$zone->pmz_color}'>&nbsp;</td>\n";
                    echo "<td class='admin_list_item'>{$zone->pmz_ident}</td>\n";
                    echo "<td class='admin_list_item'>{$zone->pmz_name} ({$zone->pmz_short_name})</td>\n";
                    echo "<td class='admin_list_item' align='right'>{$stats->zones[$zone_ident]}</td>\n";

                    if (!$view_only) {
                        echo "<td class='admin_list_item' valign=middle width=35 align=right><a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id=$pmp_id&pmz_ident=$zone_ident'><img height=15 src='images/checkbox-checked.gif' border='0' alt='" . view . "' title='" . view . "'></a>\n";
                        echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=pmz_edit_num_pmp&pmp_id=$pmp_id&pmz_ident=$zone_ident'><img height=15 src='images/numbers.png' border='0' alt='" . edit . "' title='" . edit . "'></a></td>\n";
                    } else {
                        echo "<td>&nbsp;</td>";
                    }

                    echo'</tr>';
                    $alt = ($alt + 1) % 2;
                }
            }
        }
        echo '</table>';
        echo '</td></tr></table>';
        // map
        if (!$view_only) {
            echo
            "
    <script><!--
    function cc(col,state){
      form=window.document.thisform;
      for(r=0;r<{$pmp->pmp_height};r++){
        if(chk=form['seat['+r+']['+col+']']){
          chk.checked=state;
        }
      }
    }
    function rr(row,state){
      form=window.document.thisform;
      for(c=0;c<{$pmp->pmp_width};c++){
        if(chk=form['seat['+row+']['+c+']']){
          chk.checked=state;
        }
      }
    }
    --></script>
    ";
        }

        $zones = $pmp->zones;

        echo "<table  width='$this->width' cellspacing='1' cellpadding='4'>";
        if (!$view_only) {
            echo "<form name='thisform' method='post' action='{$_SERVER['PHP_SELF']}'>";
        }
        echo "<tr><td class='admin_list_title'>" . pm_part . ": {$pmp->pmp_name}";
        if (!$view_only) {
            echo " <a class='link' href='{$_SERVER['PHP_SELF']}?action=view_only_pmp&pmp_id={$pmp->pmp_id}'><img src='images/view.png' border='0' alt='" . view . "' title='" . view . "'></a>";
        }
        echo "</td></tr>
      <tr><td align=center>";

        switch ($pmp->pmp_scene) {
            case 'north':$scene_n = '<img src="' . scene_h . '">';
                break;
            case 'south':$scene_s = '<img src="' . scene_h . '">';
                break;
            case 'west':$scene_w = '<img src="' . scene_v . '">';
                break;
            case 'east':$scene_e = '<img src="' . scene_v . '">';
                break;
        }

        echo "<table cellspacing=0 cellpadding=0 border=0><tr><td colspan=3 align=center>$scene_n</td></tr>
	           <tr><td valign=middle>$scene_w</td><td>";

        if ($view_only) {
            echo "<table cellspacing=1 cellpadding=1>";
        } else {
            echo "<table cellspacing=0 cellpadding=0>";
        }

        if ($pmp->pmp_shift) {
            $cspan = 'colspan=2';
            $ml[1] = $mr[0] = '<td class="pm_none"><img src="images/dot.gif" width=5 height=1></td>';
            echo '<tr>';
            $width2 = ($pmp->pmp_width) * 2 + 1;
            for($k = 0;$k <= $width2;$k++) {
                echo '<td><img src="images/dot.gif" width=5 height=1></td>';
            }
            echo '<td></td></tr>';
        }

        for($j = 0;$j < $pmp->pmp_height;$j++) {
            echo "<tr>";
            echo $ml[$j % 2];

            for($k = 0;$k < $pmp->pmp_width;$k++) {
                $col = '';
                $chk = '';
                $sty = '';
                if ($z = $pmp->pmp_data[$j][$k][PM_ZONE]) {
                    if ($z == 'L') {
                        $sty = "border: 1px dashed #666666;background-color:#dddddd;";
                        $label = $pmp->pmp_data[$j][$k];
                        if ($view_only) {
                            if ($label[PM_LABEL_TYPE] == 'T' and $label[PM_LABEL_SIZE] > 0) {
                                $colspan = $label[PM_LABEL_SIZE];
                                if ($cspan) {
                                    $colspan *= 2;
                                }
                                echo "<td align=center style='$sty' colspan=$colspan>" . $label[PM_LABEL_TEXT] . "</td>";
                            } else if ($label[PM_LABEL_TYPE] == 'T' and !$label[PM_LABEL_SIZE]) {
                                continue;
                            } else {
                                echo "<td align=center style='$sty' $cspan>{$label[PM_LABEL_TYPE]}</td>";
                            }
                        } else {
                            echo "<td align=center style='$sty' $cspan><input type='checkbox' name='seat[$j][$k]' value=1 title=\"{$label[PM_LABEL_TYPE]} {$label[PM_LABEL_SIZE]} {$label[PM_LABEL_TEXT]}\"  style='border:0px;'></td>";
                        }
                        continue;
                    }

                    $zone = $zones[$z];

                    $col = "bgcolor='{$zone->pmz_color}'";

                    $cat_id = $pmp->pmp_data[$j][$k][PM_CATEGORY];
                    $category = $pmp->categories[$cat_id];

                    if ($cat_id) {
                        if ($pmp->pmp_data[$j - 1][$k][PM_CATEGORY] != $cat_id) {
                            $sty = "border-top:3px solid {$pmp->categories[$cat_id]->color_code};";
                        }

                        if ($pmp->pmp_data[$j + 1][$k][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-bottom:3px solid {$pmp->categories[$cat_id]->color_code};";
                        }

                        if ($pmp->pmp_data[$j][$k - 1][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-left:3px solid {$pmp->categories[$cat_id]->color_code};";
                        }

                        if ($pmp->pmp_data[$j][$k + 1][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-right:3px solid {$pmp->categories[$cat_id]->color_code};";
                        }

                        if ($sty) {
                            $sty = "style='$sty'";
                        }
                    }

                    if (($cat_id and $sel_cat == $cat_id) or ($z and $sel_pmz == $z)) {
                        $chk = 'checked';
                    }

                    if ($view_only) {
                        $row = $pmp->pmp_data[$j][$k][PM_ROW];
                        $seat = $pmp->pmp_data[$j][$k][PM_SEAT];

                        if ($row == ($pmp->pmp_data[$j][$k - 1][PM_ROW])) {
                            $row = '&nbsp;';
                        }
                        if ($seat == ($pmp->pmp_data[$j - 1][$k][PM_SEAT])) {
                            $seat = '&nbsp;';
                        }
                        if ($row or $seat) {
                            $num = "$row-$seat";
                        } else {
                            $num = "&nbsp;";
                        }

                        echo "<td align=center $col $sty $cspan>$num</td>";
                    } else {
                        echo "<td align=center $col $sty $cspan><input type='checkbox' name='seat[$j][$k]' value=1 $chk title=\"{$zone->pmz_name} {$pmp->pmp_data[$j][$k][PM_ROW]}/{$pmp->pmp_data[$j][$k][PM_SEAT]} {$category->category_name}\"  style='border:0px;background-color:{$zone->pmz_color}'></td>";
                    }
                } else {
                    if ($view_only) {
                        echo "<td $cspan></td>";
                    } else {
                        echo "<td $cspan><input type='checkbox' name='seat[$j][$k]' value=1  style='border:0px;'></td>";
                    }
                }
            }

            echo $mr[$j % 2];

            if ($view_only) {
                echo "<td></td></tr>\n";
            } else {
                echo "<td style='border-left:1px solid #666666'><input type='checkbox' onclick='rr($j,checked)' style='border:0px;'></td></tr>\n";
            }
        }

        if (!$view_only) {
            echo "<tr>";
            echo $ml[$j % 2];
            for($x = 0;$x < $pmp->pmp_width;$x++) {
                echo "<td style='border-top:1px solid #666666' $cspan><input type='checkbox' onclick='cc($x,checked)' style='border:0px;'></td>";
            }
            echo $mr[$j % 2];
            echo "<td></td></tr>";
        }

        echo "</table>";
        echo "</td><td valign=middle>$scene_e</td></tr>
              <tr><td colspan=3>$scene_s</td></tr></table>";

        echo "</td></tr></table>";

        if (!$view_only) {
            echo '<br><table border=0 cellpadding=0 cellspacing=0 width="100%">
          <tr><td width="50%" valign=top alighn=left>';
            // define category
            $this->form_head(categories, '99%');

            echo "<tr><td class='admin_name'  width='40%'>" . category . "</td>
        <td class='admin_value'>
        <select name='category_id'>\n";

            $sel[$sel_cat] = 'selected';
            if ($pmp->categories) {
                foreach($pmp->categories as $cat_id => $category) {
                    echo "<option value='{$cat_id}' {$sel[$cat_id]}>{$category->category_name}</option>\n";
                }
            }

            echo "</select>
          </td></tr>\n";

            echo "<tr><td align='center' class='admin_value' colspan='2'>
          <input type='button' name='def_cat_pmp' value='" . define . "'  onClick='this.form.action.value=\"def_cat_pmp\";this.form.submit();'>
  	  </td></tr></table>";

            echo '</td><td align=right valign=top>';
            // define zone
            $this->form_head(pm_zones, '99%');

            echo "<tr><td class='admin_name'  width='40%'>" . pm_zone . "</td>
          <td class='admin_value'>
          <select name='zone_id'>\n";

            $sel[$sel_pmz] = 'selected';
            if ($pmp->zones) {
                foreach($pmp->zones as $zone_id => $zone) {
                    echo "<option value='{$zone_id}' {$sel[$zone_id]}>{$zone->pmz_name}</option>\n";
                }
            }

            echo "</select>
          </td></tr>
          <tr><td align='center' class='admin_value' colspan='2'>

          <input type='button' name='def_pmz_pmp' value='" . define . "'  onClick='this.form.action.value=\"def_pmz_pmp\";this.form.submit();'>
      	  </td></tr></table>";

            echo "</td></tr>
             <tr><td align='center' height=3 class='admin_value' colspan='2'> </td></tr>
            <tr>
            <td width='50%' align=left valign=top>";
            // define labels
            $this->form_head(labels, '99%');
            $this->print_select_assoc('label_type', $data, $err,
                array('T' => label_type_text,
                    'RE' => label_type_row_east,
                    'RW' => label_type_row_west,
                    'SS' => label_type_seat_south,
                    'SN' => label_type_seat_north,
                    'E' => label_type_exit));
            $this->print_input('label_text', $data, $err, 20);

            echo "<tr><td align='center' class='admin_value' colspan='2'>

          <input type='button' name='def_label_pmp' value='" . define . "'  onClick='this.form.action.value=\"def_label_pmp\";this.form.submit();'>
      	  </td></tr></table>";

            echo '</td><td align=right valign=top>';
            // clear
            $this->form_head(clear, '99%');
            echo "<tr><td align='center' class='admin_value' colspan='2'>

          <input type='button' name='def_clear_pmp' value='" . clear . "'  onClick='this.form.action.value=\"def_clear_pmp\";this.form.submit();'>
      	  </td></tr></table>";

            echo "</td></tr></table>";

            echo "<input type='hidden' name='action' value='coucou'>
          <input type='hidden' name='pmp_id' value='$pmp_id'>
          </form>";
        } 
        // if($pmp->event_id){
        // echo "<a class='link' href='view_event.php?action=view&event_id={$pmp->event_id}'>".event."</a>";
        // }else{
        echo "<center><a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$pmp->pm_id}'>" . place_map . "</a>";
        // }
        $this->pmp_short_list($pmp->pm_id, $pmp->pmp_id, $view_only);
        echo "</center>";
    }

    function pmp_form (&$data, &$err)
    {
        echo "<form action='{$_SERVER['PHP_SELF']}' method=post>";

        $this->form_head(pm_part);
        $this->print_field_o('pmp_id', $data, $err);
        $this->print_input('pmp_name', $data, $err, 30, 50);

        if (!$data['pmp_id']) {
            $this->print_input('pmp_width', $data, $err, 4, 4);
            $this->print_input('pmp_height', $data, $err, 4, 4);
        }

        $this->print_select('pmp_scene', $data, $err, array('north', 'east', 'south', 'west'));
        $this->print_checkbox('pmp_shift', $data, $err, 30, 50);
        $this->form_foot();

        if ($data['pmp_id']) {
            echo "<input type=hidden name=pmp_id value={$data['pmp_id']}>";
            echo "<input type=hidden name=action value=update_pmp>";
        } else {
            echo "<input type=hidden name=action value=insert_pmp>";
        }

        echo "<input type=hidden name=pm_id value={$data['pm_id']}>";
        echo "</form>";
        echo "<br><center><a href='{$_SERVER['PHP_SELF']}?action=view_pm&pm_id={$data['pm_id']}' class=link>" . place_map . "</a></center>";
    }

    function pmp_check ($data, &$err)
    {
        if (!isset($data['pmp_name']) or (!$data['pmp_name'])) {
            $err['pmp_name'] = mandatory;
        }
        if (!$data['pmp_id']) {
            if (!isset($data['pmp_width']) or (!$data['pmp_width'])) {
                $err['pmp_width'] = mandatory;
            }
            if (!isset($data['pmp_height']) or (!$data['pmp_height'])) {
                $err['pmp_height'] = mandatory;
            }
        }
        return empty($err);
    }

    function pmp_list ($pm_id, $live = false)
    {
        global $_SHOP;

        require_once('classes/PlaceMap.php');
        if ($pm = PlaceMap::load($pm_id) and $pm->pm_organizer_id == $_SHOP->organizer_id) {
            $mine = true;
        }

        $alt = 0;
        echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4'>\n";
        echo "<tr><td class='admin_list_title' colspan='4' align='center'>" . pm_parts . "</td></tr>\n";

        $query = "select * from PlaceMapPart where pmp_pm_id=$pm_id";
        if (!$res = ShopDB::query($query)) {
            return;
        } while ($pmp = shopDB::fetch_array($res)) {
            echo "<tr class='admin_list_row_$alt'>";
            echo "<td class='admin_list_item' width=10>&nbsp;</td>\n";
            echo "<td class='admin_list_item' title='{$pmp['pmp_id']}' width='50%'>{$pmp['pmp_name']}</td>\n";
            echo "<td class='admin_list_item'>{$pmp['pmp_width']} &times; {$pmp['pmp_height']} (".$pmp['pmp_width'] * $pmp['pmp_height'].")</td>\n";

            echo "<td class='admin_list_item' width=60 align=right>\n";
            if ($mine) {
                echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id={$pmp['pmp_id']}'><img src='images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>\n";
                echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=split_pm&pm_id=$pm_id&pmp_id={$pmp['pmp_id']}&pm_id=$pm_id'><img src='images/copy_to_folder16.gif' border='0' alt='" . split_pm . "' title='" . split_pm . "'></a>\n";
                if (!$live) {
                    echo "<a class='link' href='javascript:if(confirm(\"" . delete_item . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_pmp&pmp_id={$pmp['pmp_id']}&pm_id=$pm_id\";}'><img src='images/trash.png' border='0' alt='" . remove . "' title='" . remove . "'></a>\n";
                }
            } else {
              echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id={$pmp['pmp_id']}'><img src='images/view.png' border='0' alt='" . view . "' title='" . view . "'></a>\n";
            }
            echo'</td></tr>';
            $alt = ($alt + 1) % 2;
        }

        if ($mine) {
            echo "<tr><td colspan=6 align=center><a class='link' href='{$_SERVER['PHP_SELF']}?action=add_pmp&pm_id=$pm_id'>" . add . "</a></td></tr>";
        }
        echo '</table>';
    }

    function pmp_short_list ($pm_id, $pmp_id = 0, $view_only = false)
    {
        if (!$pmps = PlaceMapPart::loadAll_short($pm_id) or count($pmps) < 2) {
            return;
        }

        if ($view_only) {
            $action = "view_only_pmp";
        } else {
            $action = "view_pmp";
        }

        echo"<br><br><center>";
        foreach($pmps as $pmp) {
            if ($pmp_id != $pmp->pmp_id) {
                echo "<a href='{$_SERVER['PHP_SELF']}?action=$action&pmp_id={$pmp->pmp_id}' class='link'>{$pmp->pmp_name}</a> | ";
            } else {
                echo "{$pmp->pmp_name} | ";
            }
        }
        echo"</center>";
    }

    function draw ()
    {
        global $_SHOP;

        if ($_GET['action'] == 'view_pmp' and $_GET['pmp_id'] > 0) {
            $this->pmp_view($_GET['pmp_id'], $_GET['category_id'], $_GET['pmz_ident']);
        } else
        if ($_GET['action'] == 'view_only_pmp' and $_GET['pmp_id'] > 0) {
            $this->pmp_view_only($_GET['pmp_id']);
        } else
        if ($_GET['action'] == 'remove_pmp' and $_GET['pmp_id'] > 0) {
            if (!$pmp = PlaceMapPart::load($_GET['pmp_id']) or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                return;
            }
            $pmp->delete();
            return true;
        } else if ($_GET['action'] == 'add_pmp' and $_GET['pm_id'] > 0) {
            require_once('classes/PlaceMap.php');
            if (!$pm = PlaceMap::load($_GET['pm_id']) or $pm->pm_organizer_id != $_SHOP->organizer_id) {
                return;
            }

            $this->pmp_form($_GET, $err);
        } else if ($_POST['action'] == 'insert_pmp' and $_POST['pm_id'] > 0) {
            require_once('classes/PlaceMap.php');
            if (!$pm = PlaceMap::load($_POST['pm_id']) or $pm->pm_organizer_id != $_SHOP->organizer_id) {
                return;
            }

            if (!$this->pmp_check($_POST, $err)) {
                $this->pmp_form($_POST, $err);
            } else {
                $pmp = new PlaceMapPart($_POST['pm_id'], $_POST['pmp_name'], $_POST['pmp_width'], $_POST['pmp_height']);
                $pmp->pmp_scene = $_POST['pmp_scene'];
                $pmp->pmp_shift = $_POST['pmp_shift'];
                $pmp->pmp_event_id = $pm->pm_event_id;
                $pmp->save();
                $this->pmp_view($pmp->pmp_id);
                //return true;
            }
        } else if ($_GET['action'] == 'edit_pmp' and $_GET['pmp_id'] > 0) {
            if ($pmp = PlaceMapPart::load($_GET['pmp_id'])) {
                if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                    return;
                }

                $data['pmp_id'] = $pmp->pmp_id;
                $data['pm_id'] = $pmp->pmp_pm_id;
                $data['pmp_name'] = $pmp->pmp_name;
                $data['pmp_scene'] = $pmp->pmp_scene;
                $data['pmp_shift'] = $pmp->pmp_shift;

                $this->pmp_form($data, $err);
            }
        } else if ($_POST['action'] == 'update_pmp' and $_POST['pmp_id'] > 0) {
            if (!$this->pmp_check($_POST, $err)) {
                $this->pmp_form($_POST, $err);
            } else {
                $pmp = PlaceMapPart::load($_POST['pmp_id']);
                if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                    return;
                }

                $pmp->pmp_name = $_POST['pmp_name'];
                $pmp->pmp_scene = $_POST['pmp_scene'];
                $pmp->pmp_shift = $_POST['pmp_shift'];
                $pmp->save();
                $this->pmp_view($_POST['pmp_id']);
                //return true;
            }
        } else if ($_POST['action'] == 'def_cat_pmp' and $_POST['pmp_id'] > 0 and $_POST['category_id'] > 0) {
            if (empty($_POST['seat']) or is_array($_POST['seat'])) {
                $pmp = PlaceMapPart::load($_POST['pmp_id']);
                if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                    return;
                }

                $pmp->set_category($_POST['category_id'], $_POST['seat']);
                $pmp->save();
            }
            $this->pmp_view($_POST['pmp_id']);
        } else if ($_POST['action'] == 'def_pmz_pmp' and $_POST['pmp_id'] > 0 and $_POST['zone_id'] > 0) {
            if (is_array($_POST['seat'])) {
                $pmp = PlaceMapPart::load($_POST['pmp_id']);
                if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                    return;
                }

                $pmp->set_zone($_POST['zone_id'], $_POST['seat']);
                $pmp->save();
            }
            $this->pmp_view($_POST['pmp_id']);
        } else if ($_POST['action'] == 'def_label_pmp' and $_POST['pmp_id'] > 0 and $_POST['label_type']) {
            if (is_array($_POST['seat'])) {
                $pmp = PlaceMapPart::load($_POST['pmp_id']);
                if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                    return;
                }
                $pmp->set_label($_POST['label_type'], $_POST['seat'], $_POST['label_text']);
                $pmp->save();
            }
            $this->pmp_view($_POST['pmp_id']);
        } else if ($_POST['action'] == 'def_clear_pmp' and $_POST['pmp_id'] > 0) {
            if (is_array($_POST['seat'])) {
                $pmp = PlaceMapPart::load($_POST['pmp_id']);
                if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                    return;
                }

                $pmp->clear($_POST['seat']);
                $pmp->save();
            }
            $this->pmp_view($_POST['pmp_id']);
        } else if ($_GET['action'] == 'pmz_edit_num_pmp' and $_GET['pmp_id'] and $_GET['pmz_ident']) {
            $this->zone_edit($_GET['pmz_ident'], $_GET['pmp_id']);
        } else if ($_POST['action'] == 'pmz_save_num_pmp' and $_POST['pmp_id'] and $_POST['pmz_ident']) {
            $pmp = PlaceMapPart::load($_POST['pmp_id']);
            if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                return;
            }

            $pmp->set_numbers($_POST['pmz_ident'], $_POST['seat']);
            $pmp->save();
            $this->zone_edit($_POST['pmz_ident'], $_POST['pmp_id']);
        } else if ($_POST['action'] == 'pmz_auto_num_pmp' and $_POST['pmp_id'] and $_POST['pmz_ident']) {
            if ($this->check_autonumbers($_POST, $err)) {
                $pmp = PlaceMapPart::load($_POST['pmp_id']);
                if (!$pmp or $pmp->pmp_organizer_id != $_SHOP->organizer_id) {
                    return;
                }

                $pmp->auto_numbers($_POST['pmz_ident'],
                    $_POST['first_row'], $_POST['step_row'], $_POST['inv_row'],
                    $_POST['first_seat'], $_POST['step_seat'], $_POST['inv_seat'],
                    $_POST['flip'], $_POST['keep']);
                $pmp->save();
                $this->zone_edit($_POST['pmz_ident'], $_POST['pmp_id']);
            }
        } else if ($_GET['action'] == 'clear_cache_pmp' and $_GET['pmp_id']) {
            PlaceMapPart::clear_cache($_GET['pmp_id']);
            $this->pmp_view($_GET['pmp_id'], $_GET['category_id'], $_GET['pmz_ident']);
        }
    }

    function check_autonumbers (&$data, &$err)
    {
        if (!isset($data['first_row']) or (!$data['first_row'])) {
            $err['first_row'] = mandatory;
        }
        if (!isset($data['first_row']) or (!$data['step_row'])) {
            $err['step_row'] = mandatory;
        }
        if (!isset($data['first_row']) or (!$data['inv_row'])) {
            $data['inv_row'] = 0;
        }
        if (!isset($data['first_row']) or (!$data['first_seat'])) {
            $err['first_seat'] = mandatory;
        }
        if (!isset($data['first_row']) or (!$data['step_seat'])) {
            $err['step_seat'] = mandatory;
        }
        if (!isset($data['first_row']) or (!$data['inv_seat'])) {
            $data['inv_seat'] = 0;
        }
        if (!isset($data['first_row']) or (!$data['flip'])) {
            $data['flip'] = 0;
        }
        if (!isset($data['first_row']) or (!$data['keep'])) {
            $data['keep'] = 0;
        }
        return empty($err);
    }
}

?>