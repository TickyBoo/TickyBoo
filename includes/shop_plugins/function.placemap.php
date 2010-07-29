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
    return placeMapDraw($params['category'], $params['restrict'], !$pz, $params['area'], $imagesize, $params['seatlimit']);

}

function placeMapDraw($category, $restrict, $print_zone = true, $area = 'www', $imagesize = 16, $seatlimit = 15) {
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
  .pm_seatmap {
    margin:0;padding:0;
    vertical-align:middle;
    text-align: center;
     border:0px dashed transparent;

     width:'.($imagesize).'px;
     height:'.($imagesize).'px;
     font-size: '.((int)($imagesize)/1.75).'px;
  }
  .pm_seatmap img {
     border:1px dashed transparent;
  }
  .pm_shiftright {
    margin:0;padding:0;
    vertical-align:middle;
    text-align: center;
     border:0px dashed transparent;
     width:'.((int)($imagesize/2)).'px;
     height:'.($imagesize).'px;
  }
  .pm_table {margin:5px;}
  .pm_info{width:100%;}
  .pm_box{width:600px; background-color:#FFFFFF; padding:10px;}
  .pm_nosale{background-color:#d2d2d2;width:14px; height:14px;}

  .pm_ruler {}

  .pm_free {background-color:#339900;width:14px; height:14px; border-right:#339900 1px solid;border-bottom:#339900 1px solid;padding:0px;}

  .pm_occupied {background-color:#FF0066;width:14px; height:14px;border-top:#000000 1px solid;border-left:#000000 1px solid;padding:0px;}

  .pm_none {padding:0px;zoom:1;}
  .pm_check {
    cursor:pointer;
  }

  .pm_first {
     clear:both;
  }

  .pm_check:hover {
    background-color:#4F07E2;
    cursor:pointer;
  }
</style>'."\n";
   $ml[1] = $ml[0] = '';
   $mr[1] = $mr[0] = '';
    if ($pmp->pmp_shift) {
      $cspan = "colspan='2'";
     // $ml[1] = "<td class='ShiftRight pm_seatmap'>z</td>";
       $ml[1] = $mr[0] = "<td class='pm_shiftright' ><img style='width:".((int)($imagesize/2))."px;' border=0 src='{$_SHOP->images_url}dot.gif' height='100%'></td>";
        $res .= '<tr>';
        $width2 = ($right - $left) * 2 + 2;
        for ($k = 0; $k <= $width2; $k++) {
            $res .= '<td class="pm_none"><img src="{$_SHOP->images_url}dot.gif" style="width:'.((int)($imagesize/2)).'px;" height="1"></td>';
        }
        $res .= '</tr>';

     } else {
      $cspan = "";
    }

    for ($j = $top; $j <= $bottom; $j++) {
       $first = '';
       $res .= '<tr>';
       $res .= $ml[$j % 2];

        for ($k = $left; $k <= $right; $k++) {
            $seat = $pmp->data[$j][$k];
            $sty ='';
            $res .= "<td {$cspan} class='pm_seatmap' >";
            if ($seat[PM_ZONE] === 'L') {
                if ($seat[PM_LABEL_TYPE] == 'RE' and $irow = $pmp->data[$j][$k + 1][PM_ROW]) {
                    $res .= "<div class='pm_seatmap'>$irow</div>";
                } elseif ($seat[PM_LABEL_TYPE] == 'RW' and $irow = $pmp->data[$j][$k - 1][PM_ROW]) {
                    $res .= "<div class='pm_seatmap'>$irow</div>";
                } elseif ($seat[PM_LABEL_TYPE] == 'SS' and $iseat = $pmp->data[$j + 1][$k][PM_SEAT]) {
                    $res .= "<div class='pm_seatmap'>$iseat</div>";
                } elseif ($seat[PM_LABEL_TYPE] == 'SN' and $iseat = $pmp->data[$j - 1][$k][PM_SEAT]) {
                    $res .= "<div class='pm_seatmap'>$iseat</div>";
                } else
                if ($seat[PM_LABEL_TYPE] == 'T' and $seat[PM_LABEL_SIZE] > 0) {
                  if (strlen($seat[PM_LABEL_TEXT])>3){
                     $res .= "<img class='pm_seatmap' src='{$_SHOP->images_url}info.gif' alt='{$seat[PM_LABEL_TEXT]}' title='{$seat[PM_LABEL_TEXT]}'>";
                  } else {
                     $res .= "<div class='pm_seatmap'>{$seat[PM_LABEL_TEXT]}</div>";
                  }
                } else
                if ($seat[PM_LABEL_TYPE] == 'E') {
                  $res .= "<img class='pm_seatmap' src='{$_SHOP->images_url}exit.gif' alt='exit' title='exit'>";
                } else {
                  $res .= "<img class='pm_seatmap' style='{$sty};border-color:red' border=0 src='{$_SHOP->images_url}dot.gif' title='{$seat[PM_LABEL_TYPE]}'>";
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
                        $res .= "<img class='pm_seatmap pm_check' style='{$sty}' id='seat{$seat[PM_ID]}' onclick='javascript:gridClick({$seat[PM_ID]});' src='{$_SHOP->images_url}seatfree.png' title='";
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
                      $res .= "<img class='pm_seatmap' style='{$sty};background-color:Gainsboro' border=0 src='{$_SHOP->images_url}seatdisable.png'>";
                    }
                    ////////////Reserved seats, they will only be selectable if you have area='pos' set in cat...tpl
                } elseif ($seat[PM_STATUS] == PM_STATUS_RESP && $area === 'pos' && $seat[PM_CATEGORY] == $cat_ident) {
                    $zone = $zones[$seat[PM_ZONE]];
                    $res .= "<img class='pm_seatmap' style='{$sty}' src='{$_SHOP->images_url}seatselect.png' title='";
                    if ($print_zone) {
                        $res .= $zone->pmz_name . ': ';
                    }
                    if (($cat_num & 2) and $seat[PM_ROW] != '0') {
                        $res .= $l_row . $seat[PM_ROW];
                    }
                    if (($cat_num & 1) and $seat[PM_SEAT] != '0') {
                        $res .= $l_seat . $seat[PM_SEAT];
                    }
                    $res .= "'>";
                } else {
                  if ($seat[PM_CATEGORY] != $cat_ident) {
                    $sty .= ';background-color:Gainsboro';
                  }
                  $res .= "<img class='pm_seatmap' style='{$sty}' src='{$_SHOP->images_url}seatused.png'>";
                }
            } elseif ($seat[PM_ZONE]) {
                $res .= "<img class='pm_seatmap' style='{$sty}' border=0 src='{$_SHOP->images_url}b.gif'>";
            } else  {
               $res .= "<img class='pm_seatmap' style='{$sty}' border=0 src='{$_SHOP->images_url}dot.gif' />";
            }
            $res .= "</td>";
            $first ='';
        }
        $res .= $mr[$j % 2]."</tr>";
    }

    /*            <script language=\"JavaScript\" type=\"text/javascript\" src=\"wz_tooltip.js\"></script>    ";*/


    $l = $_SHOP->lang;

    switch ($pmp->pmp_scene) {
        case 'south':
            $res = "<table border=0 cellspacing=0 cellpadding=0>
                      <tr>
                        <td>
                          <table class='pm_table' border=1  cellspacing=0 cellpadding=0>$res</table>
                        </td>
                      </tr>
                      <tr>
                        <td align='center' valign='middle'>
                          <img src='{$_SHOP->images_url}scene_h_$l.png'>
                        </td>
                      </tr>
                    </table>";
            break;
        case 'east':
           $res = "<table border=0 cellspacing=0 cellpadding=0>
                     <tr>
                       <td align='center' valign='middle'>
                         <img src='{$_SHOP->images_url}scene_v_$l.png'>
                       </td>
                       <td>
                         <table border=0 class='pm_table' cellspacing=0 cellpadding=0>$res</table>
                       </td>
                     </tr>
                   </table>";
            break;
        case 'west':
            $res = "<table border=0  cellspacing=0 cellpadding=0>
                      <tr>
                        <td>
                          <table border=0 class='pm_table' cellspacing=0 cellpadding=0>$res</table>
                        </td>
                        <td align='center' valign='middle'>
                          <img src='{$_SHOP->images_url}scene_v_$l.png'>
                        </td>
                      </tr>
                    </table>";
            break;
        default:
            $res = "<table border=0 cellspacing=0 cellpadding=0>
               <tr>
                 <td align='center' valign='middle'>
                   <img src='{$_SHOP->images_url}scene_h_$l.png'>
                 </td>
               </tr>
               <tr>
                 <td>
                   <table border=0 class='pm_table' cellspacing=0 cellpadding=0>$res</table>
                 </td>
               </tr>
             </table>";
    }
    $res .='
         <input id="maxseats" value="'.$seatlimit.'" type="hidden" size="3" maxlength="5">
         <script>
          function gridClick(id) {
            x = jQuery("#place"+id).val();
            c = jQuery("#maxseats").val();
            if ((x == 0) && (c >0)) {
              jQuery("#seat"+id).attr("src","'.$_SHOP->images_url.'seatselect.png");
              jQuery("#place"+id).val(id);
              c--;
            } else if (( x != 0) && (c < '.$seatlimit.' )) {
              jQuery("#seat"+id).attr("src","'.$_SHOP->images_url.'seatfree.png");
              jQuery("#place"+id).val(0);
              c++;
            } else if (c == 0) {
              alert("'.con('max_seats_reached').'");
            }
            jQuery("#maxseats").val(c);
          }
     </script>
';

    return $res;

}

?>