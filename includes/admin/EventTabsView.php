<?PHP
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

require_once("admin/AdminView.php");

class EventTabsView extends AdminView {

  function draw() {
    $_SESSION['_EVENT_tab'] = (isset($_REQUEST['tab']))? (int)$_REQUEST['tab']:((isset($_SESSION['_EVENT_tab']))?$_SESSION['_EVENT_tab']:0);

    $menu = array(con('ort_admin')=>"?tab=0", con("Groups")=>'?tab=1',
                  "Events"=>"?tab=2", "History"=>"?tab=3");
    echo $this->PrintTabMenu($menu, (int)$_SESSION['_EVENT_tab'], "left");

    switch ((int)$_SESSION['_EVENT_tab'])
       {
       case 0:
           require_once ('OrtView.php');
           $viewer = new OrtView($this->width);
           $viewer->draw();
           break;

       case 1:
           require_once ('EventGroupView.php');
           $viewer = new EventGroupView($this->width);
           $viewer->draw();
           break;

       case 2:
           require_once ('EventPropsView.php');
           $viewer = new EventPropsView($this->width);
           $viewer->draw(false);
           break;

       case 3:
           require_once ('EventPropsView.php');
           $viewer = new EventpropsView($this->width);
           $viewer->draw(true);
           break;

       }
  }
}
?>