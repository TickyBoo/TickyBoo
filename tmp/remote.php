<?PHP
  include_once('..\includes\config\init_common.php');
  include_once('..\includes\config\init.php');
  include_once('..\includes\classes\PlaceMapPart.php');
  $pmp = PlaceMapPart::load_full(30);
  $stats = $pmp->get_stats();
  switch ($_GET['load']) {
     case 'zones' :
        echo "<table><tbody>";
        if (!empty($pmp->zones)) {
          foreach($pmp->zones as $zone_ident => $zone) {
            echo "  <tr id='{$zone->pmz_ident}' >\n";
            echo "    <td bgcolor='{$zone->pmz_color}'>&nbsp;</td>\n";
            echo "    <td>{$zone->pmz_name} ({$zone->pmz_short_name})</td>\n";
            echo "    <td align='right'>{$stats->zones[$zone_ident]}</td>\n";
            echo "    <td class='admin_list_item' valign='center' >\n";
            echo "      <a class='link' id='renumber' href='#'><img height=15 src='../admin/images/numbers.png' border='0' alt='" . edit . "' title='" . edit . "'></a>\n";
            echo "    </td>\n";
            echo '  </tr>';
          }
        } else {
          echo "  <tr id='???'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        }
        echo "</tbody></table>\n";
        break;
     case 'cats' :
        echo "<table><tbody>\n";
        if (!empty($pmp->categories)) {
          foreach($pmp->categories as $ident => $category) {
            echo "  <tr id ='{$ident}'>\n";
            echo "    <td bgcolor='{$category->color_code}'>&nbsp;</td>\n";
            echo "    <td>{$category->category_name}</td>\n";
            echo "    <td>{$category->category_price} {$_SHOP->currency}</td>\n";
            echo "    <td align='right'>{$stats->categories[$ident]}</td>\n";
            echo "    <td>{$category->category_numbering}</td>\n";
            echo '  </tr>';
          }
        } else {
          echo "  <tr id='???'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
        }
        echo "</tbody></table>";
        break;
    case 'grid':
        $type = (isset($_GET['type']))? (string)($_GET['type']):'';
        $myid = (isset($_GET['id']))? (int)($_GET['id']):0;

        echo "<table><tbody>\n";
        if ($pmp->pmp_shift) {
            $cspan = 'colspan=2';
            $ml[1] = $mr[0] = '<td class="pm_none"><img src="images/dot.gif" width=5 height=1></td>';
            echo '<tr>';
            $width2 = ($pmp->pmp_width) * 2 + 1;
            for($k = 0;$k <= $width2;$k++) {
                echo '<td><img src="../admin/images/dot.gif" width=5 height=1></td>';
            }
            echo "<td></td></tr>\n";
        }

        for($j = 0;$j < $pmp->pmp_height;$j++) {
            echo "<tr>";
            echo $ml[$j % 2];

            for($k = 0;$k < $pmp->pmp_width;$k++) {
                $col = '';
                $chk = '';
                $sty = 'nowrap; ';
                $label = $pmp->pmp_data[$j][$k];
                if ($z = $label[PM_ZONE]) {
                    if ($z == 'L') {
                        $sty = "border: 1px dashed #666666;background-color:#dddddd;";
                        if (($type==$label[PM_LABEL_TYPE])) {
                          $chk = 'checked';
                        }
                        echo "<td align=center style='$sty' $cspan><input type='checkbox' name='seat[$j][$k]' value=1 title=\"{$label[PM_LABEL_TYPE]} {$label[PM_LABEL_SIZE]} {$label[PM_LABEL_TEXT]}\" $chk ></td>"; //style='border:0px;'
                        continue;
                    }

                    $zone = $pmp->zones[$z];

                    $col = "bgcolor='{$zone->pmz_color}'";

                    $cat_id = $label[PM_CATEGORY];
                    $category = $pmp->categories[$cat_id];

                    if ($cat_id) {
                        if ($pmp->pmp_data[$j - 1][$k][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-top:3px solid {$category->color_code};";
                         }
                        if ($pmp->pmp_data[$j + 1][$k][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-bottom:3px solid {$category->color_code};";
                        }

                        if ($pmp->pmp_data[$j][$k - 1][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-left:3px solid {$category->color_code};";
                        }

                        if ($pmp->pmp_data[$j][$k + 1][PM_CATEGORY] != $cat_id) {
                            $sty .= "border-right:3px solid {$category->color_code};";
                        }

                        if ($sty) {
                            $sty = "style='$sty'";
                        }
                    }

                    if (($type=='C' and $myid == $cat_id) or ($type=='Z'  and $myid == $z)) {
                        $chk = 'checked';
                    }

                    echo "<td align=center $col $sty $cspan>
                      <input type='checkbox' name='seat[$j][$k]' $col value=1 $chk title=\"{$zone->pmz_name} {$pmp->pmp_data[$j][$k][PM_ROW]}/{$pmp->pmp_data[$j][$k][PM_SEAT]} {$category->category_name}\" ></td>";
                      
                } else {
                    echo "<td id='none' align=center bgcolor='#ffffff' $cspan><input type='checkbox' name='seat[$j][$k]' value=1 ></td>";// style='border: 3px;'
                }
            }
            echo "<td style='border:1px solid-right #666666 border:1px solid-left #666666' bgcolor='#666666'><input type='checkbox' onclick='rr($j,checked)' ></td></tr>\n";
        }

        echo "<tr>";
        echo $ml[$j % 2];
        for($x = 0;$x < $pmp->pmp_width;$x++) {
            echo "<td align=center style='border-top :1px solid #666666; border-bottom :1px solid #666666' bgcolor='#666666' $cspan><input type='checkbox' onclick='cc($x,checked)'></td>";
        }
        echo $mr[$j % 2];
        echo "<td></td></tr>\n";

        echo "</table>\n";
        break;
    default: print_r($pmp);
    
  }
?>