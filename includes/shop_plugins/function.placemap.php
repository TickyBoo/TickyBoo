<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
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

function smarty_function_placemap($params, $smarty){

    $pz = preg_match(strtolower('/no|0|false/'), $params['print_zone']);
    $imagesize = is ($params['imagesize'], 16);
    return placeMapDraw($params['category'], $params['restrict'], !$pz, $params['area'], $imagesize);

}

function placeMapDraw($category, $restrict, $print_zone = true, $area = 'www', $imagesize = 16)
{
    global $_SHOP;

    $l_row = ' '.con('place_row').' ';
    $l_seat = ' '.con('place_seat').' ';

    $cat_ident = $category['category_ident'];
    $cat_num = 0;
    switch ($category['category_numbering']) {
        case 'both':
            $cat_num = 3;
            break;
        case 'rows':
            $cat_num = 2;
            break;
        case 'seat':
            $cat_num = 1;
            break;
    }
    $res = '';
    $pmp = PlaceMapPart::loadFull($category['category_pmp_id']);
  //  print_r($category);
    $cats = $pmp->categories;
    $zones = $pmp->zones;

    $pmp->check_cache();

    if ($restrict) {
        $bounds = $pmp->category_bounds($cat_ident);
        $left   = $bounds['left'];
        $right  = $bounds['right'];
        $top    = $bounds['top'];
        $bottom = $bounds['bottom'];
    } else {
        $left   = 0;
        $right  = $pmp->pmp_width - 1;
        $top    = 0;
        $bottom = $pmp->pmp_height - 1;
    }

/*    if ($pmp->pmp_shift) {
        $cspan = 'colspan=2';
        $ml[1] = $mr[0] = '<img src="{$_SHOP->images_url}dot.gif" style="width:5;height=10">';
        $res .= '<tr>';
        $width2 = ($right - $left) * 2 + 1;
        for ($k = 0; $k <= $width2; $k++) {
            $res .= '<img src="{$_SHOP->images_url}dot.gif" style="width:5;height:10">';
        }
        $res .= '<br/>';
    }
*/
//    print_r($pmp);
   $res .= '
<style type="text/css">
.seatmap {
   border:2px dashed transparent;
   width:'.($imagesize).'px;
   height:'.($imagesize).'px;
}
.pm_check {
  cursor:pointer;
}

.pm_check:hover {
  background-color:#c7d8c7;
  cursor:pointer;


 </style>
   ';
    // "POSITION:absolute; LEFT:" . (($k - $left)*($imagesize+1)) . "px; TOP: " . (($j-$top )*($imagesize+1)) . "px; ";
    for ($j = $top; $j <= $bottom; $j++) {
        for ($k = $left; $k <= $right; $k++) {
            $seat = $pmp->data[$j][$k];
            $sty ='';
             if ($seat[PM_ZONE] === 'L') {
/*                if ($seat[PM_LABEL_TYPE] == 'RE' and $irow = $pmp->data[$j][$k + 1][PM_ROW]) {
                    $res .= "<td $cspan class='label_RE ft-pm-cell'>$irow";
                } elseif ($seat[PM_LABEL_TYPE] == 'RW' and $irow = $pmp->data[$j][$k - 1][PM_ROW]) {
                    $res .= "<td $cspan class='label_RW ft-pm-cell'>$irow";
                } elseif ($seat[PM_LABEL_TYPE] == 'SS' and $iseat = $pmp->data[$j + 1][$k][PM_SEAT]) {
                    $res .= "<td $cspan class='label_SS ft-pm-cell'>$iseat";
                } elseif ($seat[PM_LABEL_TYPE] == 'SN' and $iseat = $pmp->data[$j - 1][$k][PM_SEAT]) {
                    $res .= "<td $cspan class='label_SN ft-pm-cell'>$iseat";
                } elseif (($seat[PM_LABEL_TYPE] == 'T') and !$seat[PM_LABEL_SIZE]) {
                    continue;
                } elseif ($seat[PM_LABEL_TYPE] == 'T' and $seat[PM_LABEL_SIZE] > 0) {
                    $label_size = $seat[PM_LABEL_SIZE];
                    if ($pmp->pmp_shift) {
                        $label_size *= 2;
                    }
                    $res .= "<td class='label_T ft-pm-cell' colspan='$label_size'>{$seat[PM_LABEL_TEXT]}";
                } else */
                if ($seat[PM_LABEL_TYPE] == 'E') {
                    $res .= "<img class='seatmap' src='{$_SHOP->images_url}exit.gif'>";
                }
            } elseif ($seat[PM_ZONE] and $seat[PM_CATEGORY]) {
                $zone = $zones[$seat[PM_ZONE]];
                $cat  = $cats[$seat[PM_CATEGORY]];
                $cat_id = $seat[PM_CATEGORY];
           //     $sty .= "background-color:{$zone->pmz_color};";

                if ($pmp->data[$j - 1][$k][PM_CATEGORY] != $cat_id) {
                    $sty .= "border-top-color: {$cat->category_color};";
                }

                if ($pmp->data[$j + 1][$k][PM_CATEGORY] != $cat_id) {
                    $sty .= "border-bottom-color: {$cat->category_color};";
                }

                if ($pmp->data[$j][$k - 1][PM_CATEGORY] != $cat_id) {
                    $sty .= "border-left-color: {$cat->category_color};";
                }

                if ($pmp->data[$j][$k + 1][PM_CATEGORY] != $cat_id) {
                    $sty .= "border-right-color: {$cat->category_color};";
                }
                $sty .= "; ";

                //Empty seats
                if ($seat[PM_STATUS] == PM_STATUS_FREE) {
                    if ($seat[PM_CATEGORY] == $cat_ident) {
                        $res .= "<input type='hidden' id='place{$seat[PM_ID]}' name='place[{$seat[PM_ID]}]' value='0'>";
                        $res .= "<img class='seatmap pm_check' style='{$sty}' id='seat{$seat[PM_ID]}' onclick='javascript:gridClick({$seat[PM_ID]});' src='{$_SHOP->images_url}seatfree.gif' title='";
                        if ($print_zone) {
                            $res .= $zone->pmz_name . ' ';
                        }
                        if (($cat_num & 2) and $seat[PM_ROW] != '0') {
                            $res .= $l_row . $seat[PM_ROW];
                        }
                        if (($cat_num & 1) and $seat[PM_SEAT] != '0') {
                            $res .= $l_seat . $seat[PM_SEAT];
                        }
                        $res .= "'>";
                    } else {
                      $res .= "<img class='seatmap' style='{$sty};background-color:Gainsboro' border=0 src='{$_SHOP->images_url}seatdisable.gif'>";
                    }
                    ////////////Reserved seats, they will only be selectable if you have area='pos' set in cat...tpl
                } elseif ($seat[PM_STATUS] == PM_STATUS_RESP && $area === 'pos' && $seat[PM_CATEGORY] == $cat_ident) {
                    $zone = $zones[$seat[PM_ZONE]];
                    $res .= "<img class='seatmap' style='{$sty}' src='{$_SHOP->images_url}seatselect.gif' title='";
                    if ($print_zone) {
                        $res .= $zone->pmz_name . ' ';
                    }
                    if (($cat_num & 2) and $seat[PM_ROW] != '0') {
                        $res .= $l_row . $seat[PM_ROW];
                    }
                    if (($cat_num & 1) and $seat[PM_SEAT] != '0') {
                        $res .= $l_seat . $seat[PM_SEAT];
                    }
                    $res .= "'>";
                } else {
                    $res .= "<img class='seatmap' style='{$sty}' src='{$_SHOP->images_url}seatused.gif'>";
                }
            } elseif ($seat[PM_ZONE]) {
                $res .= "<img class='seatmap' style='{$sty}' border=0 src='{$_SHOP->images_url}b.gif'>";
            } else  {
                $res .= "<img class='seatmap' style='{$sty}' border=0 src='{$_SHOP->images_url}dot.gif'>";
            }
  //          $res .= "\n";
        }
        $res .= $mr[$j % 2];
        $res .= "<br/>\n";
    }

    /*            <script language=\"JavaScript\" type=\"text/javascript\" src=\"wz_tooltip.js\"></script>    ";*/


    $l = $_SHOP->lang;
    /*
    switch ($pmp->pmp_scene) {
        case 'north':
            $res = "<table border=0 class=pm_table_ext>
                      <tr>
                        <td align='center' valign='middle'>
                           <img src='{$_SHOP->images_url}scene_h_$l.png'></td></tr>
                       <tr><td align=center valign=middle><div width=100% height=100&>$res</div></td></tr></table>";
            break;
        case 'south':
            $res = "<table border=0 class=pm_table_ext><tr><td align='center' valign='middle'>$res</td></tr>
            <tr><td align=center valign=middle><img src='{$_SHOP->images_url}scene_h_$l.png'></td></tr></table>";
            break;
        case 'east':
            $res = "<table border=0 class=pm_table_ext><tr><td align='center' valign='middle'>$res</td><td align=center valign=middle><img src='{$_SHOP->images_url}scene_v_$l.png'></td></tr></table>";
            break;
        case 'west':
            $res = "<table border=0 class=pm_table_ext><tr><td align='center' valign='middle'><img src='{$_SHOP->images_url}scene_v_$l.png'></td><td align='center' valign='middle' >$res</td></tr></table>";
            break;
    }*/
    $res .='
      <script>
function gridClick(id) {
  x = jQuery("#place"+id).val();
  if ( x == 0 ) {
    jQuery("#seat"+id).attr("src","'.$_SHOP->images_url.'seatselect.gif");
    jQuery("#place"+id).val(id);
  } else {
    jQuery("#seat"+id).attr("src","'.$_SHOP->images_url.'seatfree.gif");
    jQuery("#place"+id).val(0);
  }
}
</script>
';

    return $res;

}

?>