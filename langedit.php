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
  session_name('langedit');

  session_start();
  if(function_exists("date_default_timezone_set")) {
    @date_default_timezone_set(date_default_timezone_get());
  }


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

  if ($_POST['load']) {
 //   If (!isset($_SESSION['diff1']) or $_SESSION['lang']<>$_POST['lang'] ) {
    $string1 = file_get_contents('includes/lang/site_en.inc');
      $diff1 = findinside($string1);
      if (file_exists( dirname(__FILE__)."/includes/lang/site_{$_POST['lang']}.inc")) {
        $string2 = file_get_contents("includes/lang/site_{$_POST['lang']}.inc");
        $diff2 = findinside($string2);
    } else {
        $diff2 = array();
    }

      $_SESSION['diff1'] = $diff1;
      $_SESSION['diff2'] = $diff2;
      $_SESSION['lang']  = $_POST['lang'];

//    } else {
//      $diff1= $_SESSION['diff1'];
      //$diff2= $_SESSION['diff2'];
//    }

  }
  if ($_POST['oper']=='edit') {
      $_SESSION['diff2'][$_POST['id']] = $_POST['lang2'];
     die("done");

  }elseif ($_POST['load']=='update_1') {
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

  }elseif ($_POST['load']=='update_2') {
     if (count($diff1)===0) {
       die('noting to update');
     } elseif (!is_writable("includes/lang/site_{$_POST['lang']}.inc")) {
       die('This file is not writable.');
     } else {
       $string2 .= "<"."?php\n";
       $string2 .= "// defines added at: ".date('c')."\n";
       foreach ($diff2 as $key =>$value) {
         $string2 .= "define('$key', $value);\n";
       }
       $diff= array_diff_key($diff1, $diff2);
       foreach ($diff as $key =>$value) {
         $string2 .= "define('$key', $value);\n";
       }
       $string2 .= "?>";
       file_put_contents("includes/lang/site_{$_POST['lang']}.inc",$string2, FILE_TEXT );

     }
     die("done");
  } elseif ($_POST['load']=='grid')  {
    $responce = array();
    $responce['page'] = 1;
    $responce['total'] = 1;
    $responce['records'] = count($diff1)+count($diff2);
    $responce['userdata'] = array();
    $i=0;

    foreach ($diff1 as $key =>$value) {
      $responce['rows'][$i]['id']=$key;
      $responce['rows'][$i]['cell']=array($key, htmlentities($value), htmlentities($diff2[$key]));
      $i++;
    }
    foreach ($diff2 as $key =>$value) {
      if(!array_key_exists($key, $diff1 )){
      $responce['rows'][$i]['id']=$key;
      $responce['rows'][$i]['cell']=array($key, "&nbsp;", htmlentities($value));
      $i++;
    }
    }
    echo json_encode($responce);
    exit;
  };
?>

<html>
	<head>
		<meta http-equiv="Content-Language" content="English" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel='stylesheet' href='css/flick/jquery-ui-1.8.1.custom.css' />
		<link rel="stylesheet" type="text/css" href="css/ui.jqgrid.css" media="screen" />


		<title>FusionTicket: Language editor </title>
		<link rel="stylesheet" type="text/css" href="css/ingrid.css" media="screen" />
		<script type="text/javascript" src="scripts/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery-ui-1.8.1.custom.min.js"></script>

		<script type="text/javascript" src="scripts/jquery/jquery.jqGrid.min.js"></script>


		<script type="text/javascript">
       $(document).ready(function() {
          var mycombo = $("#combo");
          var lang = mycombo.val();
          var lastsel;
          var mygrid1 = $("#table1").jqGrid({
            url:'langedit.php',
            datatype: 'JSON',
            mtype: 'POST',
            postData: {"load":"grid","lang":lang},
            colNames: ['LangKey','Default language','Editedable language'],
            colModel :[
                {name:'key',   index:'key',   width:200, sortable:false, resizable: false  },
                {name:'lang1', index:'lang1', width:470, sortable:false, resizable: false },
                {name:'lang2', index:'lang2', width:470, sortable:false, resizable: false,
                 editable:true, edittype: "textarea", editoptions: {rows:"2",cols:"51"} }],
            altRows: true,
            height: 400,
        		hiddengrid : true,
            forceFit   : true,
            rownumbers : false,
            rowNum:   -1,
        		footerrow : false,
        		viewrecords: true,
            editurl: "langedit.php?load=save",
            loadError: function(xhr,status,error) {
              alert(status+'-'+error);
            },
            onSelectRow: function(rowid,status) {
              if(rowid && rowid!==lastsel){
          		var ret = mygrid1.jqGrid('getRowData',rowid);
              $('#orgintext').val(ret.lang1);

                mygrid1.jqGrid('restoreRow',lastsel);
                mygrid1.jqGrid('editRow',rowid, true);
                lastsel=rowid;
            }
              }
            });

      		$('#update_2').click(function(){
             $.post("langedit.php", { load: "update_2", lang: lang }, function(data){
                alert(data);
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
  <table id="table1" class="scroll" cellpadding="0" cellspacing="0"></table>
  Orgin text:<br>
  <textarea id='orgintext' name=orgintext rows='4' cols='160'  ></textarea><br>
  <button id='update_2'>Update missing</button>
  </body>
</html>