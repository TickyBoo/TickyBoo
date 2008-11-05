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
    }

    function drawHead()
    {
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
    <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=ISO-8859-1\">
    <META HTTP-EQUIV=\"Content-Language\" CONTENT=\"" . $_SERVER["INTERFACE_LANG"] . "\">
    <title>" . $this->getTitle() . "</title>
    <link rel='stylesheet' href='style.css'>

    <script><!--
    function init(){if(document.f && document.f.codebar){document.f.codebar.focus();}}
    --></script>
    </head><body  leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onload='init();'>
    <center><table style='border:#45436d 1px solid;' width='750'  cellspacing='0' cellpadding='0' bgcolor='#ffffff'>
    <tr><td  class='control_head'><img src='images/logo_control.png'></td></tr>
    <tr><td  valign='top' align='right' style='padding-left:20px;border-top:#45436d 1px solid;border-bottom:#45436d 1px solid; padding-bottom:5px; padding-top:5px;'>
    <a class='link_head' href='control.php?action=change_event'>[" . change_event . "]</a>
    <a class='link_head' href='control.php?action=search_form'>[" . search . "]</a>
    <a class='link_head' href='control.php?action=logout'>[" . logout . "]</a>&nbsp;&nbsp;</td>
    </tr><tr><td style='padding-left:20px;padding-right:20px;' valign='top' align='center'>";
    }

    function drawFoot()
    {
        echo "<br></td></tr></table>";
        echo "</center></body></html>";
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