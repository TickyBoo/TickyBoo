<?PHP
/* %%%copyright%%%     */
$licention = <<<EOQ
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
EOQ;

require_once("admin/AdminView.php");
require_once("admin/OptionsView.php");
require_once("admin/OrganizerView.php");

class IndexView extends AdminView {

  function draw() {
    if(!isset($_REQUEST['tab'])) $_REQUEST['tab']=0;

    $menu = array("License"=>"?tab=0", "Information"=>"?tab=1", "Configuration"=>"?tab=2", "Owner"=>'?Tab=3');
    echo $this->PrintTabMenu($menu, (int)$_REQUEST['tab'], "left");

    switch ((int)$_REQUEST['tab'])
       {
       case 0:
           global $licention;
           $content .= nl2br(htmlspecialchars($licention));
           break;
       case 1:
           $content .= "<tr>\n<td>".$this->UI_Label("Fusion Ticket ".con('Current_version'))."</td>\n<td>".CURRENT_VERSION."</td>\n</tr>";
           $content .= "<tr>\n<td>".$this->UI_Label(con('InfoWebVersion'))."</td>\n<td>".$_SERVER['SERVER_SOFTWARE'] ."</td>\n</tr>";
           $content .= "<tr>\n<td>".$this->UI_Label(con('InfoPhpVersion'))."</td>\n<td>".phpversion ()."</td>\n</tr>";
           $content .= "<tr>\n<td>".$this->UI_Label(con('InfoMysqlVersion'))."</td>\n<td>".ShopDB::GetServerInfo ()."</td>\n</tr>";
           $content .= "<tr>\n<td>".$this->UI_Label(con('InfoUserCount'))."</td>\n<td>".$this->Users_Count ()."</td>\n</tr>";
           $content .= "<tr>\n<td>".$this->UI_Label(con('InfoGroupCount'))."</td>\n<td>".$this->Groups_Count ()."</td>\n</tr>";
           $content .= "<tr>\n<td>".$this->UI_Label(con('InfoVenueCount'))."</td>\n<td>".$this->Docs_Count ()."</td>\n</tr>";
           $content .= "<tr>\n<td>".$this->UI_Label(con('InfoEventCount'))."</td>\n<td>".$this->Files_Count ()."</td>\n</tr>";
           break;
       
       case 2:
           $viewer = new OptionsView();
           $viewer->draw();
           break;

       case 3:
           $viewer = new OrganizerView();
           $viewer->draw();
           break;
       }
    echo $content;
  }

 
  // make tab menus using html tables
  // vedanta_dot_barooah_at_gmail_dot_com

  function PrintTabMenu($linkArray, $activeTab=0, $menuAlign="center", $childArray=null, $childAlign="center") {
    Global $_SHOP;
  	$tabCount=0;
  	$str= "<table width=\"100%\" cellpadding=0 cellspacing=0 border=0>\n";
  	$str.= "<tr>\n";
  	if($menuAlign=="right")
                {
  	      $str.= "<td width=\"100%\" align=\"left\">&nbsp;</td>\n";
  	      }
  	foreach ($linkArray as $k => $v)
                {
  	      if($tabCount==$activeTab){$menuStyle="UITabMenuNavOn";}else{$menuStyle="UITabMenuNavOff";}
  	      $str.= "<td valign=\"top\" class=\"$menuStyle\"><img src=\"".$_SHOP->root.'images'.DS."left_arc.gif\"></td>\n";
  	      $str.= "<td nowrap=\"nowrap\" height=\"16\" align=\"center\" valign=\"middle\" class=\"$menuStyle\">\n";
  	      if($tabCount!=$activeTab) $str.= "<a class=\"UITabMenuTab\" href=\"$v\">";
                $str.= $k;
  	      if($tabCount!=$activeTab) $str.= "</a>";
  	      $str.= "</td>\n";
  	      $str.= "<td valign=\"top\" class=\"$menuStyle\"><img src=\"".$_SHOP->root.'images'.DS."right_arc.gif\"></td>\n";
  	      $str.= "<td width=\"1pt\">&nbsp;</td>\n";
  	      $tabCount++;
  	      }
  	if($menuAlign=="left")
                {
  	      $str.= "<td width=\"100%\" align=\"right\">&nbsp;</td>";
  	      }
  	$str.= "</tr>\n";
  	// create the child menu
  	if (is_array($childArray))
                {
  	      if($menuAlign=="left" || $menuAlign=="right"){$spaceCount=1;}else{$spaceCount=0;}
  	      $tabCount=count($linkArray)+$spaceCount;
  	      $str.= "<tr>\n";
  	      $str.= "<td colspan=\"$tabCount\" align=\"$childAlign\" class=\"UITabMenuChildMenu\">";
  	      foreach($childArray as $k => $v)
                     {
  		   $str.= "&nbsp;<a href=\"$v\" class=\"UITabMenuChildMenu\">$k</a>&nbsp|";
  	           }
                $str.= "</td>\n";
  	      $str.= "</tr>\n";
  	      }
  	$str.= "</table>\n";
	return $str;
}

 
}

?>