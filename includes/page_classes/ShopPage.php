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

require_once("page_classes/AUIComponent.php");

class ShopPage extends AUIComponent{
  var $key=array();
  var $description=array();
  
  function ShopPage(){
  }
  
  function setWidth($width){
    $this->width=$width;
  }
  
  function addKey($kk){
    array_push($this->key,$kk);
  }

  
  function draw(){
    $this->drawHead();
    echo "<table width='700'><tr><td>";
    $this->drawChild($this->items["body"]);
    echo "</td></tr></table>";    
    $this->drawFoot();
  }

  function drawHead(){
    if(!$_SERVER["INTERFACE_LANG"]){
      $_SERVER["INTERFACE_LANG"]="de";
    }
    $page=$_SERVER["REQUEST_URI"];
    $page_1=substr($page,3);
    foreach($this->key as $val){
      if(!$content){
        $content=$val;
      }else{
        $content.=",".$val;
      }
    }

    echo "<head>
    <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=ISO-8859-1\">
    <META HTTP-EQUIV=\"Content-Language\" CONTENT=\"".$_SERVER["INTERFACE_LANG"]."\">
    <title>".$this->getTitle()."</title>
    <link rel='stylesheet' href='style.css'>
    <script language=\"JavaScript\"><!--
       browser_version= parseInt(navigator.appVersion);
       browser_type = navigator.appName;
       if (browser_type == \"Microsoft Internet Explorer\" && (browser_version >= 4)) {
          document.write(\"<link REL='stylesheet' HREF='style_ie.css' TYPE='text/css'>\");
       }else if (browser_type == \"Netscape\" && (browser_version >= 4)) {
          document.write(\"<link REL='stylesheet' HREF='style_nn.css' TYPE='text/css'>\");
       }else{
         document.write(\"<link REL='stylesheet' HREF='style_nn.css' TYPE='text/css'>\");
        
       }
      // --></script>   
    
    <meta name=\"Keywords\" content=\"$content\">
    <meta name=\"robots\" content=\"".$this->getRobots()."\">
    </head><body  leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 >
    <center><table width='700' border='0' bgcolor='#666666'> 
    <tr><td  class='shop_head' rowspan='2'><img src='images/logo.png'></td><td>&nbsp;</td></tr>
    <tr><td valign='bottom' align='right' width='120' style='padding-right:5px;'>
    <a class='link_head' href='set_lang.php?lang=de'>[de]</a> 
    <a class='link_head' href='set_lang.php?lang=fr'>[fr]</a>    
    <a class='link_head' href='set_lang.php?lang=it'>[it]</a>
    <a class='link_head' href='set_lang.php?lang=en'>[en]</a></td></tr></table><br>";
 
  }


  function drawFoot(){
    echo "";
  } 


  function setRobots($tags){
    $this->robots = $tags;
  } 
  
  function getRobots(){
      $doc = $_SERVER["PHP_SELF"];
      $arg = $_SERVER["QUERY_STRING"]; 
      if(strpos($arg,"dhtorder")>-1){
        return "NOINDEX,NOFOLLOW";
      }else{
         return $this->robots;
      }
  }
  
   function setTitle($tags){
    $this->title = $tags;
   } 
  
  function getTitle(){
     return $this->title;
   }

}
?>