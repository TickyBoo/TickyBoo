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


if (!defined('ft_check')) {die('System intrusion ');}
require_once("classes/AUIComponent.php");

class AdminPage extends AUIComponent {
  var $menu_width = 200;
  var $key = array();
  var $description = array();
  var $title = '';

  function AdminPage($width = 800, $title = '') {
      $this->width = $width;
      $this->title = $title;
  }

  function addKey($kk) {
      array_push($this->key, $kk);
  }

  public function setJQuery($script){
    $this->set('jquery',$script);
  }

  function setMenu($menu) {
    $this->set("menu",$menu);
    if (is_object($menu)) {$menu->setWidth($this->menu_width-10);}
  }

  function setBody(&$body) {
    $this->set("body",$body);
  }

  function drawContent() {
    echo "<table border=0 width='" . $this->width . "' class='aui_bico'><tr>";
    if ($menu = $this->items["menu"]) {
      echo "<td class='aui_bico_menu' width='" . $this->menu_width . "' valign=top>\n";
      $this->drawChild($menu);
      echo "</td>";
    }
    echo "<td class=aui_bico_body valign=top>";

    $body = $this->items["body"];
    if (is_object($body)) {
      If ($menu) {
        $body->setWidth($this->width - $this->menu_width);
      } else {
        $body->setWidth($this->width);
      }
    }
    $this->drawChild($body);
    echo"</td></tr></table>\n";


    if(is_object($body)){
      $this->setJQuery($body->getJQuery());
    }
  }

  function draw() {
    ob_start();
    $this->drawcontent();
    $content = ob_get_contents();
    ob_end_clean();

    $this->drawHead();
    echo $content;
    $this->drawFoot();
  }

  function drawHead(){
    global $_SHOP;
    if (!isset($_SERVER["INTERFACE_LANG"]) or !$_SERVER["INTERFACE_LANG"]) {
        $_SERVER["INTERFACE_LANG"] = $_SHOP->langs[0];
    }
    if (isset($_SHOP->system_status_off) and $_SHOP->system_status_off) {
        AddWarning('system_halted');
    }
     //+'&href={$_SERVER["REQUEST_URI"]}'
    echo "<head>
    <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=UTF-8\">
    <META HTTP-EQUIV=\"Content-Language\" CONTENT=\"" . $_SERVER["INTERFACE_LANG"] . "\">
    <title>" . $this->getTitle() . "</title>
    <link rel='stylesheet' href='../css/flick/jquery-ui-1.8.6.custom.css' />
    <link rel='stylesheet' href='../css/jquery.tooltip.css' />
    <link rel='stylesheet' href='admin.css' />
    <script type=\"text/javascript\" src=\"../scripts/jquery/jquery.min.js\"></script>
    <script type=\"text/javascript\" src=\"../scripts/jquery/jquery-ui-1.8.6.custom.min.js\"></script>
    <script type=\"text/javascript\" src=\"../scripts/jquery/jquery.dimensions.min.js\"></script>
    <script type=\"text/javascript\" src=\"../scripts/jquery/jquery.tooltip.min.js\"></script>
    <script type=\"text/javascript\" src=\"../scripts/jquery/jquery.caret.js\"></script>
    <script><!--
      function set_lang(box)
      {
      	lang = box.options[box.selectedIndex].value;
      	if (lang) location.href = '?setlang='+lang;
      }
      // Author: Matt Kruse <matt@mattkruse.com>
      // WWW: http://www.mattkruse.com/
      TabNext();
      // Function to auto-tab field
      // Arguments:
      // obj :  The input object (this)
      // event: Either 'up' or 'down' depending on the keypress event
      // len  : Max length of field - tab when input reaches this length
      // next_field: input object to get focus after this one
      var field_length=0;
      function TabNext(obj, event, len, next_field) {
        if (event == \"down\") {
          field_length=obj.value.length;
        }
        else if (event == \"up\") {
          if (obj.value.length != field_length) {
            field_length=obj.value.length;
            if (field_length == len) {
              next_field.focus();
            }
          }
        }
      }";
      echo '
      $(document).ready(function(){
        var msg = '."'".printMsg('__Warning__', null, false)."'".';
        if(msg) {
          $("#error-text").html(msg);
          $("#error-message").show();
          setTimeout(function(){$("#error-message").hide();}, 5000);
        }
        var msg = '."'".printMsg('__Notice__', null, false)."'".';
        if(msg) {
          $("#notice-text").html(msg);
          $("#notice-message").show();
          setTimeout(function(){$("#notice-message").hide();}, 5000);
        }
      });
      ';
      echo "

          -->
    </script>
  </head>
  <body >
  	<div id='wrap'>\n";
      echo "<div  id='header'>
              <img src=\"".$_SHOP->images_url."logo.png\" border='0'/>
             <h2>".con('administration')."</h2>
             </div>";
      echo"<div id='navbar'><table width='100%'>
          <tr><td>&nbsp;";
      $this->drawOrganizer();
      echo "</td><td  align='right'>&nbsp;";
  //        echo "<select name='setlang' onChange='set_lang(this)'>";

  //        $sel[$_SHOP->lang] = "selected";
  //        foreach($_SHOP->langs_names as $lang => $name) {
  //            echo"<option value='$lang' {$sel[$lang]}>$name</option>";
  //        }
  //        echo "</select>";
      echo"</td></tr></table></div><br>";
      echo'
        <DIV style="MARGIN-TOP: 0.35em;MARGIN-Bottom: 0.35em; DISPLAY: none" id=error-message class="ui-state-error ui-corner-all" title="Order Error Message">
  <P><SPAN style="FLOAT: left; MARGIN-RIGHT: 0.3em" class="ui-icon ui-icon-alert"></SPAN><div id=error-text>ffff<br>tttttcv ttt </div> </P></DIV>
  <DIV style="MARGIN-TOP: 0.35em; MARGIN-Bottom: 0.35em; DISPLAY: none" id=notice-message class="ui-state-highlight ui-corner-all" title="Order Notice Message">
  <P><SPAN style="FLOAT: left; MARGIN-RIGHT: 0.3em" class="ui-icon ui-icon-info"></SPAN><div id=notice-text>fff</div> </P></DIV>
    ';
  }

  function drawFoot() {
    global $_SHOP;

   /// print_r($_SHOP->Messages);
    echo "
      <br><br>
      <script type=\"text/javascript\">
        $(document).ready(function(){
          $(\"a[class*='has-tooltip']\").tooltip({
            delay:40,
            showURL:false,
            bodyHandler: function() {
              if($(this).children('div').html() != ''){
                return $(this).children('div').html();
              }else{
                return false;
              }
            }
          });
          ". is($this->items['jquery'],'') ."
        });
      </script>
      <div id='footer'>
     		 <!-- To comply with our GPL please keep the following link in the footer of your site -->
				 Powered by <a href='http://fusionticket.org'>Fusion Ticket</a> - The Free Open Source Box Office
			</div>
		</div>
	</body>
</html>";
  }

  function setTitle($tags){
      $this->title = $tags;
  }

  function getTitle(){
      return $this->title;
  }

  function drawOrganizer () {
      global $_SHOP;
      echo "<font color='#555555'><b>" . con('welcome') . " " .
        ((is_object($_SHOP->organizer_data))?
          $_SHOP->organizer_data->organizer_name:
          $_SHOP->organizer_data['organizer_name']) . "</b></font>";
  }
}
?>