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

class ControlPage extends AUIComponent {
    var $key = array();
    var $description = array();

    function ControlPage ()
    {
    }

    function setWidth ($width)
    {
        $this->width = $width;
    }

    function addKey ($kk)
    {
        array_push($this->key, $kk);
    }

    function draw ()
    {
        global $_SHOP;

        $this->drawHead();
        $this->drawChild($this->items["body"]);
        $this->drawFoot();
        orphanCheck();
        trace("End of page. \n\n\r");
    }

    function drawHead() {
      Global $_SHOP;
        if (!$_SERVER["INTERFACE_LANG"]) {
            $_SERVER["INTERFACE_LANG"] = "de";
        }
        $page = $_SERVER["REQUEST_URI"];
        $page_1 = substr($page, 3);
        foreach($this->key as $val) {
            if (!$content) {
                $content = $val;
            } else {
                $content .= "," . $val;
            }
        }

        echo "<html><head>
    <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=UTF-8\">
    <META HTTP-EQUIV=\"Content-Language\" CONTENT=\"" . $_SERVER["INTERFACE_LANG"] . "\">
    <title>" . $this->getTitle() . "</title>
    <link rel='stylesheet' href='style.css'>

    <script><!--
    function init(){if(document.f && document.f.codebar){document.f.codebar.focus();}}
    --></script>
    </head><body onload='init();'>";
    echo "  		<div id='wrap'>\n";
        echo "<div  id='header'>
        		<img src=\"".$_SHOP->images_url."logo.png\" border='0'/>
        		<h2>" . $this->getTitle() . "</h2>
               </div>";
        echo"<div id='navbar'>
				<ul>
					<li><a class='link_head' href='control.php?action=change_event'>". con('change_event')."</a></li>
          <li><a class='link_head' href='control.php?action=search_form'>" . con('search')."</a></li>
          <li><a class='link_head' href='control.php?action=logout'>" . con('logout') . "</a>&nbsp;&nbsp;</td></li>
        </ul>
      </div><br>";
    }

    function drawFoot()
    {
        echo "<br>";
    echo "<div id='footer'>
				Powered by <a href='http://fusionticket.org'>Fusion Ticket</a> - The Free Open Source Box Office
			</div>
		</div>
	</body>
</html>";
    }

    function setRobots($tags)
    {
        $this->robots = $tags;
    }

    function getRobots()
    {
        $doc = $_SERVER["PHP_SELF"];
        $arg = $_SERVER["QUERY_STRING"];
        if (strpos($arg, "dhtorder") > - 1) {
            return "NOINDEX,NOFOLLOW";
        } else {
            return $this->robots;
        }
    }

    function setTitle($tags)
    {
        $this->title = $tags;
    }

    function getTitle()
    {
        return $this->title;
    }
}

?>