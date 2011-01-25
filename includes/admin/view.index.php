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
require_once("admin/view.options.php");
require_once("admin/view.organizer.php");
require_once("admin/view.versionutil.php");

class IndexView extends AdminView {

  function draw() {
    GLOBAL $_SHOP;
    if(isset($_REQUEST['tab'])) {
      $_SESSION['_INDEX_tab'] = (int)$_REQUEST['tab'];
    }
    $_SHOP->trace_subject .= "[tab:{$_SESSION['_INDEX_tab']}]";
    plugin::call('AddACLResource','index_default_info', 'organizer' );
   plugin::call('AddACLResource','index_default_specs', 'admin' );
    plugin::call('AddACLResource','index_owner_info', 'admin' );
    plugin::call('AddACLResource','index_settings_info','admin' );
    plugin::call('AddACLResource','index_upgrade_system','admin' );
    if ($_SHOP->admin->isAllowed('index_default_info')) { $menu[con("index_admin_tab")]=0;}
    if ($_SHOP->admin->isAllowed('index_owner_info')) $menu[con("owner_tab")]=1;
    if ($_SHOP->admin->isAllowed('index_settings_info')) $menu[con("shopconfig_tab")]=2;
    if($_SHOP->software_updater_enabled){
      if ($_SHOP->admin->isAllowed('index_upgrade_system')) $menu[con("version_updater")]=3;
    }
    if (!in_array((int)$_SESSION['_INDEX_tab'], array_values($menu))) {
      $_SESSION['_INDEX_tab'] = reset($menu);
    }

    echo $this->PrintTabMenu($menu, $_SESSION['_INDEX_tab'], "left");

    switch ((int)$_SESSION['_INDEX_tab']){
      case 0:
        //$licention = file_get_contents (ROOT."licence.txt");
        $licention = "";
        //$this->form_head(con("grapes_copyright").con('current_version').'&nbsp;'.CURRENT_VERSION,$this->width,1);
        echo "<table cellpadding='2'><tr><td>".con("grapes_copyright")."</td></tr>";
        echo "<tr><td class='admin_value'>" ;
        echo "<p><pre>",htmlspecialchars($licention),'</pre></p>';
        echo "</td></tr>";
		    echo "</table>\n<br>";
        if ($_SHOP->admin->isAllowed('index_default_specs')) {
          $this->form_head( con('system_summary'),$this->width,2);
          self::$labelwidth = '25%';
          //$this->print_field('InfoWebVersion',  $_SERVER['SERVER_SOFTWARE']);
          //$this->print_field('InfoPhpVersion',  phpversion ());
          //$this->print_field('InfoMysqlVersion',ShopDB::GetServerInfo ());
          //$this->print_field('InfoMysqlDB'     ,$_SHOP->db_name);
          $this->print_field('InfoAdminCount',  $this->Admins_Count ());
          $this->print_field('InfoUserCount',   $this->Users_Count ());
          $this->print_field('InfoEventCount',  $this->Events_Count ());
          echo "</table>\n";
        }
        break;

      case 1 :
        $viewer = new OrganizerView($this->width);
        $viewer->draw();
        break;

      case 2:
        $viewer = new OptionsView($this->width);
        $viewer->draw();
        break;
      case 3:
        $viewer = new VersionUtilView($this->width);
        $viewer->draw();
        break;
    }
  }

  function Users_Count () {
 	  $sql = "SELECT count(user_status) as count,user_status, IF(active IS NOT NULL,'yes','no') as active
  	       	FROM User left join auth on auth.user_id=User.user_id
            group by user_status, IF(active IS NOT NULL,'yes','no')";
 		if(!$res=ShopDB::query($sql)){
			return FALSE;
		}

		while($data=shopDB::fetch_row($res)){
      $part[$data[1]][$data[2]]=$data[0];
		}

    return vsprintf(con('index_user_count'),array($part[1]['no'],$part[3]['no'],$part[2]['yes'],$part[2]['no'],$part[2]['yes']+$part[2]['no']));
  }

  function Groups_Count (){
    return 'not impented yet';
  }

  function Venues_Count () {
 	  $sql = "SELECT count(*)
  	       	FROM Ort";
 		if(!$result=ShopDB::query_one_row($sql)){
			return FALSE;
		}
    return vsprintf(con('index_ort_count'),$result);

  }

  function Events_Count (){
    $part = array('pub'=>0, 'unpub'=>0, 'nosal'=>0,'trash'=>0,'total'=>0);
 	  $sql = "SELECT count(event_status) as count, event_status
  	       	FROM Event
            group by event_status";
 		if(!$res=ShopDB::query($sql)){
			return FALSE;
		}

		while($data=shopDB::fetch_row($res)){
      $part['total'] += $data[0];
      $part[$data[1]]=$data[0];
		}

    return vsprintf(con('index_events_count'),$part);
  }

  function admins_Count (){
    $part = array('admin'=>0, 'organizer'=>0, 'pos'=>0, 'control'=>0,'total'=>0);
 	  $sql = "SELECT count(admin_status) as count, admin_status
  	       	FROM Admin
  	       	group by admin_status";
 		if(!$res=ShopDB::query($sql)){
			return FALSE;
		}

		while($data=shopDB::fetch_row($res)){ //print_r($daTA);
      $part['total'] += $data[0];
      $part[$data[1]]=$data[0];
		}

    return vsprintf(con('index_admins_count'),$part);
  }

}

?>