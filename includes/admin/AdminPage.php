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

require_once("classes/AUIComponent.php");

class AdminPage extends AUIComponent {
    var $menu_width = 200;
    var $key = array();
    var $description = array();
    var $title = '';

    function AdminPage($width = 800, $title = '')
    {
        $this->width = $width;
        $this->title = $title;
    }

    function addKey($kk)
    {
        array_push($this->key, $kk);
    }

    function setmenu($menu)
    {
        $this->set("menu",$menu);
        if (is_object($menu)) {$menu->setWidth($this->menu_width-10);}
    }

    function setbody($body)
    {
        $this->set("body",$body);
    }

    function drawcontent()
    {
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
    }

    function draw()
    {
        global $_SHOP;

        $this->drawHead();
        $this->drawcontent();
        $this->drawFoot();
    }

    function drawHead()
    {
        global $_SHOP;
        if (!isset($_SERVER["INTERFACE_LANG"]) or !$_SERVER["INTERFACE_LANG"]) {
            $_SERVER["INTERFACE_LANG"] = $_SHOP->langs[0];
        }
        if (isset($_SHOP->system_status_off) and $_SHOP->system_status_off) {
            $this->errmsg = "<div class=error>SYSTEM IS HALTED</div>";
        }
         //+'&href={$_SERVER["REQUEST_URI"]}'
        echo "<head>
        <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=UTF-8\">
        <META HTTP-EQUIV=\"Content-Language\" CONTENT=\"" . $_SERVER["INTERFACE_LANG"] . "\">
        <title>" . $this->getTitle() . "</title>
        <link rel='stylesheet' href='admin.css'>
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
    }
        -->
		</script>
  </head>
  <body  leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 >";
        if (isset($this->errmsg)) echo "<div class=''error'>$this->errmsg</div>";
        echo "<center><table border='0' width='" . $this->width . "'  cellspacing='0' cellpadding='0' bgcolor='#ffffff' >
             <tr>
               <td  colspan='2' style='padding-left:20px;padding-bottom:5px;'>
               <img src='images/logo_admin.png'>
               </td>
             </tr>
            <tr><td style='padding-left:20px;border-top:#cccccc 1px solid;border-bottom:#cccccc 1px solid; padding-bottom:5px; padding-top:5px;' valign='bottom'>";
        $this->drawOrganizer();
        echo "</td>
                <td  align='right' style='padding-right:20px;border-top:#cccccc 1px solid;border-bottom:#cccccc 1px solid; '>";
        echo "<select name='setlang' onChange='set_lang(this)'>";

        $sel[$_SHOP->lang] = "selected";
        foreach($_SHOP->langs_names as $lang => $name) {
            echo"<option value='$lang' {$sel[$lang]}>$name</option>";
        }
        echo "</select>";

        echo"</td></tr></table><br>";
    }

    function drawFoot()
    {
    }

    function setTitle($tags)
    {
        $this->title = $tags;
    }

    function getTitle()
    {
        return $this->title;
    }

    function drawOrganizer ()
    {
        global $_SHOP;
        echo "<font color='#555555'><b>" . con('welcome') . " " . $_SHOP->organizer_data->organizer_name . "</b></font>";
    }
}
?>