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
require_once "Auth/Auth.php";

class AdminPage extends AUIComponent{
  var $key=array();
  var $description=array();
  
  function AdminPage(){
  }

  function setWidth($width){
    $this->width=$width;
  }
  
  function addKey($kk){
    array_push($this->key,$kk);
  }

  
  function draw(){
    global $_SHOP;
     
      $this->drawHead();
      $this->drawChild($this->items["body"]);    
      $this->drawFoot();
  }

  function drawHead(){
    if(!$_SERVER["INTERFACE_LANG"]){
      $_SERVER["INTERFACE_LANG"]="de";
    }
    echo "<head>
    <meta HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=ISO-8859-1\">
    <META HTTP-EQUIV=\"Content-Language\" CONTENT=\"".$_SERVER["INTERFACE_LANG"]."\">
    <title>".$this->getTitle()."</title>
    <link rel='stylesheet' href='style.css'>
    <meta name=\"Keywords\" content=\"$content\">
    <meta name=\"robots\" content=\"NOINDEX,NOFOLLOW\">
    </head><body  leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 >
    <center> <h1>Administration</h1>
    <a  href='set_lang.php?lang=de'>Deutsch</a> <a  href='set_lang.php?lang=fr'>Français</a>    
    <a  href='set_lang.php?lang=it'>Italiano</a>
    <a  href='set_lang.php?lang=en'>English</a><br><br>";
 
  }


  function drawFoot(){
    echo "<br><h4>YOUR FOOT</h4>";
  } 


  
   function setTitle($tags){
    $this->title = $tags;
   } 
  
  function getTitle(){
     return $this->title;
   }

}
?>