<?php
   function findinside( $filestring) {
        $string = file_get_contents($filestring);
        preg_match_all('/define\(["\']([a-zA-Z0-9_]+)["\'],[ ]*(.*?)\);/si',  $string, $m); //.'/i'
        return array_combine( $m[1],$m[2]);
    }

    $en = findinside('includes/lang/site_en.inc');
    $du = findinside('includes/lang/site_nlz.php');
    ksort($en, SORT_LOCALE_STRING);
    echo "<table><tbody>\n";
    foreach ($en as $key =>$value) {
      $keyx=(isset($du[$key]))?$key:"<b>$key</b>";
      echo "<tr id='$key'>\n  <td>$keyx</td>\n  <td>".htmlentities($value)."</td>\n  <td>";
      echo(isset($du[$key]))?htmlentities($du[$key]):"&nbsp;","</td>\n</tr>\n";
    }
    $diff= array_diff_key($du, $en);
    foreach ($diff as $key =>$value) {
      echo "<tr id='$key'>\n  <td><b>$key</b></td>\n  <td>&nbsp;</td>\n  <td>".htmlentities($value)."</td>\n</tr>\n";
    }
    echo "</tbody></table>";
?>
