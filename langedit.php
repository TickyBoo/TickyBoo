<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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

  include_once('includes/config/init_common.php');

  function load() {
    global $_SHOP;
    $content = array();
    $dir = $_SHOP->includes_dir.DS.'lang';
	  if ($handle = opendir($dir)) {
		   while (false !== ($file = readdir($handle))) {
             if ($file != "." && $file != ".." && !is_dir($dir.$file) && preg_match("/^site_(.*?\w+).inc/", $file, $matches)&& $matches[1]!='en')
                { $content[$matches[1]] = $file ;}
          }
		   closedir($handle);
  	}
    print_r($content );
    return $content;
  }

  function findinside( $string) {
      preg_match_all('/define\(["\']([a-zA-Z0-9_]+)["\'],[ ]*(.*?)\);/si',  $string, $m); //.'/i'
      return array_combine( $m[1],$m[2]);
  }

  if (isset($_GET['load'])) {
    $string1 = file_get_contents('includes/lang/site_en.inc');
    $string2 = file_get_contents("includes/lang/site_{$_GET['lang']}.inc");

    $en = findinside($string1);
    $du = findinside($string2);

    $diff1= array_diff_key($en, $du);
    $diff2= array_diff_key($du, $en);
  }

  if ($_GET['load']=='update_1') {
     if (count($diff2)===0) {
       die('noting to update');
     } elseif (!is_writable('includes/lang/site_en.inc')) {
       die('This file is not writable.');
     } else {
       $string1 .= "<"."?php\n";
       $string1 .= "// defines added at: ".date('c')."\n";
       foreach ($diff2 as $key =>$value) {
         $string1 .= "define('$key', $value);\n";
       }
       $string1 .= "?>";
       file_put_contents('includes/lang/site_en.inc',$string1, FILE_TEXT );
     }
     die("done");

  }elseif ($_GET['load']=='update_2') {
     if (count($diff1)===0) {
       die('noting to update');
     } elseif (!is_writable("includes/lang/site_{$_GET['lang']}.inc")) {
       die('This file is not writable.');
     } else {
       $string2 .= "<"."?php\n";
       $string2 .= "// defines added at: ".date('c')."\n";
       foreach ($diff1 as $key =>$value) {
         $string2 .= "define('$key', $value);\n";
       }
       $string1 .= "?>";
       file_put_contents("includes/lang/site_{$_GET['lang']}.inc",$string1, FILE_TEXT );
     }
     die("done");
  } elseif ($_GET['load']=='grid')  {
    echo "<table><tbody>\n";
    foreach ($diff1 as $key =>$value) {
      echo "<tr id='$key'>\n  <td>$key</td>\n  <td>".htmlentities($value)."</td>\n  <td>&nbsp;</td>\n</tr>\n";
    }
    foreach ($diff2 as $key =>$value) {
      echo "<tr id='$key'>\n  <td>$key</td>\n  <td>&nbsp;</td>\n  <td>".htmlentities($value)."</td>\n</tr>\n";
    }
    If (count($diff1)==0 and count($diff2)==0) {
      echo "<tr id='???'>\n  <td>&nbsp;</td>\n  <td>no data</td>\n  <td>&nbsp;</td>\n</tr>\n";
    }
    echo "</tbody></table>";
    exit;
  };
?>

<html>
	<head>
		<meta http-equiv="Content-Language" content="English" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>FusionTicket: Language editor </title>
		<link rel="stylesheet" type="text/css" href="css/ingrid.css" media="screen" />
		<script type="text/javascript" src="scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/DD_roundies.js"></script>
    <script type="text/javascript" src="scripts/jquery/jquery.ingrid-0.9.2.js"></script>

		<script type="text/javascript">
       $(document).ready(function() {
          var mycombo = $("#combo");
          var lang = mycombo.val();
      		var mygrid1 = $("#table1").ingrid({
      			url: 'langedit.php',
      			extraParams: {load: 'grid', lang: lang } ,
      			height: 450,
      			headerHeight: 25,
      			savedStateLoad: true,
      			initialLoad: true,
      			colWidths: [150,475,475],		// width of each column
      			rowClasses: ['grid-row-style1','grid-row-style2'],
      			resizableCols: false,
      			rowSelection: false,
      			paging: false,
      			sorting: false
          });
          $('#secLang').text(mycombo.val());
      		$('#update_1').click(function(){
             $.get("langedit.php", { load: "update_1", lang: lang }, function(data){
                if (data== 'done') {
                  mygrid1.g.load({lang: lang });
                } else alert(data);}, "text");
      		});

      		$('#update_2').click(function(){
             $.get("langedit.php", { load: "update_2", lang: lang }, function(data){
                if (data== 'done') {
                  mygrid1.g.load({lang: lang });
                } else alert(data);}, "text");
      		});

      		$('#combo').change(function(){
      			lang = mycombo.val();
            mygrid1.g.load({lang: lang }, function(){
                $('#secLang').text(mycombo.val());
            }   );
      		});

       });

		</script>
 	</head>
	<body bgcolor='#FFFFFF'>
  Select the languagefile: <select id='combo'>
<?Php
  $opt = load();
  $sel['nl'] = " selected='selected' ";
  foreach($opt as $k=>$v) {
    echo "<option value='$k' $sel[$k] >" . $v . "</option>\n";
  }
?>
</select>
    <table id='table1' cellspacing='1' cellpadding='4'>
      <thead>
         <tr>
           <th>Key</th>
           <th>en</th>
           <th id='secLang'>nl</th>
         </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr>
          <th>&nbsp;</th>
          <th><button id='update_1'>Update missing</button></th>
          <th><button id='update_2'>Update missing</button></th>
        </tr>
      </tfoot>
    </table>
  </body>
</html>