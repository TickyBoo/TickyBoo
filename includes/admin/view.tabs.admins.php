<?PHP
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

require_once("admin/class.adminview.php");

class tabsAdminsView extends AdminView {

  function draw() {
      global $_SHOP;

    if(isset($_REQUEST['tab'])) {
      $_SESSION['_ADMIN_tab'] = (int)$_REQUEST['tab'];
    }
    $_SHOP->trace_subject .= "[tab:{$_SESSION['_ADMIN_tab']}]";
    $menu = array(con("admin_user_tab")=>0,
          				con("spoint_tab")=>1);
    echo $this->PrintTabMenu($menu, $_SESSION['_ADMIN_tab'], "left");

    switch ((int)$_SESSION['_ADMIN_tab']) {
     case 0:
         require_once ('view.adminusers.php');
         $viewer = new AdminUsersView($this->width);
         $viewer->draw();
         $this->addJQuery($viewer->getJQuery());
         break;

     case 1:
         require_once ('view.spoints.php');
         $viewer = new SpointsView($this->width);
         $viewer->draw();
         $this->addJQuery($viewer->getJQuery());
         break;
     }
  }
}

?>