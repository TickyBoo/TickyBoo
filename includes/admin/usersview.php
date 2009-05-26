<?PHP
/* %%%copyright%%%     */
$licention = <<<EOQ
  FusionTicket - ticket reservation system
    Copyright (C) 2007-2009 Christopher Jenkins. All rights reserved.

  Original Design:
 	phpMyTicket - ticket reservation system
  	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.

  This file is part of fusionTicket.

  This file may be distributed and/or modified under the terms of the
  "GNU General Public License" version 3 as published by the Free
  Software Foundation and appearing in the file LICENSE included in
  the packaging of this file.

  This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
  THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.


  The "GNU General Public License" (GPL) is available at
  http://www.gnu.org/copyleft/gpl.html.

  Contact info@fusionticket.com if any conditions of this licencing isn't
  clear to you.
EOQ;

require_once("admin/AdminView.php");

class UsersView extends AdminView {

  function draw() {
    if(isset($_REQUEST['tab'])) {
      $_SESSION['_ADMIN_tab'] = (int)$_REQUEST['tab'];
    }

    $menu = array("Admins"=>"?tab=0", "Organizers"=>'?tab=1', "Sale points"=>"?tab=2", "Controlers"=>"?tab=3");
    echo $this->PrintTabMenu($menu, (int)$_SESSION['_ADMIN_tab'], "left");

    switch ((int)$_SESSION['_ADMIN_tab'])
       {
       case 0:
           require_once ('adminuserview.php');
           $viewer = new AdminUserView('100%');
           $viewer->draw('admin');
           break;

       case 1:
           require_once ('adminuserview.php');
           $viewer = new AdminUserView('100%');
           $viewer->draw('organizer');
           break;

       case 2:
           require_once ('SPointView.php');
           $viewer = new SPointView('100%');
           $viewer->draw();
           break;

       case 3:
           require_once ('ControlView.php');
           $viewer = new controlView('100%');
           $viewer->draw();
           break;

       }
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
  	      $str.= "<td valign=\"top\" class=\"$menuStyle\"><img src=\"".$_SHOP->root."images/left_arc.gif\"></td>\n";
  	      $str.= "<td nowrap=\"nowrap\" height=\"16\" align=\"center\" valign=\"middle\" class=\"$menuStyle\">\n";
  	      if($tabCount!=$activeTab) $str.= "<a class=\"UITabMenuTab\" href=\"$v\">";
                $str.= $k;
  	      if($tabCount!=$activeTab) $str.= "</a>";
  	      $str.= "</td>\n";
  	      $str.= "<td valign=\"top\" class=\"$menuStyle\"><img src=\"".$_SHOP->root."images/right_arc.gif\"></td>\n";
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