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
  define('ft_check','langcheck');
  include_once('includes/config/init_common.php');

  function load() {
    $content = array();
    $dir = dirname(__FILE__)."/includes/lang";
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
  function fillxml ($key,$field1, $field2) {
  	echo "<row id='{$key}'>";
  	echo "<cell>{$key}</cell>";
  	echo "<cell><![CDATA[{$field1}]]></cell>";
  	echo "<cell><![CDATA[{$field2}]]></cell>";
  	echo "</row>";

  }
  function findinside( $string) {
      preg_match_all('/define\(["\']([a-zA-Z0-9_]+)["\'],[ ]*(.*?)\);/si',  $string, $m); //.'/i'
      return array_combine( $m[1],$m[2]);
  }

  if (isset($_GET['load'])) {
    $string1 = file_get_contents('includes/lang/site_en.inc');
    $en = findinside($string1);
    if (file_exists( dirname(__FILE__)."/includes/lang/site_{$_GET['lang']}.inc")) {
      $string2 = file_get_contents("includes/lang/site_{$_GET['lang']}.inc");
      $du = findinside($string2);
    } else {
      $du = array();
    }

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
       $string2 .= "?>";
       file_put_contents("includes/lang/site_{$_GET['lang']}.inc",$string2, FILE_TEXT );
     }
     die("done");
  } elseif ($_GET['load']=='grid')  {
    $responce = array();
    $responce['page'] = 1;
    $responce['total'] = 1;
    $responce['records'] = count($diff1)+count($diff2);
    $responce['userdata'] = array();
    $i=0;

    foreach ($diff1 as $key =>$value) {
      $responce['rows'][$i]['id']=$key;
      $responce['rows'][$i]['cell']=array($key, htmlentities($value), "&nbsp;");
      $i++;
    }
    foreach ($diff2 as $key =>$value) {
      $responce['rows'][$i]['id']=$key;
      $responce['rows'][$i]['cell']=array($key, "&nbsp;", htmlentities($value));
      $i++;
    }
    echo json_encode($responce);
    exit;
  };
?>

<html>
	<head>
		<meta http-equiv="Content-Language" content="English" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.7.2.custom.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/ui.jqgrid.css" media="screen" />


		<title>FusionTicket: Language editor </title>
		<link rel="stylesheet" type="text/css" href="css/ingrid.css" media="screen" />
		<script type="text/javascript" src="scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.2.custom.min.js"></script>

		<script type="text/javascript" src="scripts/jquery/jquery.jqGrid.min.js"></script>


		<script type="text/javascript">
       $(document).ready(function() {
          var mycombo = $("#combo");
          var lang = mycombo.val();

          var mygrid1 = $("#table1").jqGrid({
            url:'langedit.php',
            datatype: 'JSON',
            mtype: 'GET',
            postData: {"load":"grid","lang":lang},
            colNames: ['Expire_in','Lang_en','Other langCount'],
            colModel :[
                {name:'key',   index:'key',   width:200, sortable:false, resizable: false  },
                {name:'lang1', index:'lang1', width:475, sortable:false, resizable: false },
                {name:'lang2', index:'lang2', width:475, sortable:false, resizable: false }],
            altRows: true,
            height: 300,
        		hiddengrid : true,
            forceFit   : true,
            rownumbers : true,
            rowNum:   -1,
        		footerrow : false,
        		viewrecords: false,
            loadError: function(xhr,status,error) {
              alert(status+'-'+error);
            },
            onSelectRow: function(rowid,status) {
          		var ret = mygrid1.jqGrid('getRowData',rowid);
          	//	alert("id="+ret.key+" lang1="+ret.lang1+"...");
              $('#key').val(ret.key);
              $('#orgintext').val(ret.lang1);
              $('#changedtext').val(ret.lang2);
            }
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
          $("#lang-form").submit(function(){
            $(this).ajaxSubmit({
              data:{load:"save"},
              dataType: "json",
              success: function(data, status){
                alert('saved');
              }
            });
            return false;
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
  <table id="table1" class="scroll" cellpadding="0" cellspacing="0"></table>
  <form id='lang-form'>
  <input type='hidden' id='key' name='key' value=''>
  <input type='hidden' name='load' value='NewValue'>
  Orgin text:<br>

  <textarea id='orgintext' name=orgintext rows='4' cols='142'  ></textarea>  <br>
  Changed text:<br>
  <textarea id='changedtext' name=changedtext rows='4' cols='142'  ></textarea>  <br>
  <input type=submit
  </form>
  </body>
</html>