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

if (!defined('ft_check')) {die('System intrusion ');}
require_once("admin/class.adminview.php");

class AdminUserView extends AdminView{

  function table (){
    global $_SHOP;
    $query="SELECT * FROM Admin where admin_status="._esc($this->admintype);
    if(!$res=ShopDB::query($query)){
      user_error(shopDB::error());
      return;
    }

    $alt=0;
    echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='4' border=0>\n";
    echo "<tr><td class='admin_list_title' colspan='1' align='left'>".con($this->admintype.'_user_title')."</td>";
    echo "<td colspan='1' align='right'>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add","add",3)."</td>";
    echo "</tr>\n";


    while($row=shopDB::fetch_assoc($res)){
      echo "<tr class='admin_list_row_$alt'>";
      echo "<td class='admin_list_item' width='550' >{$row['admin_login']}</td>\n";
      echo "<td class='admin_list_item'width='65' align='right' nowrap><nowrap>";
      echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit&admin_id={$row['admin_id']}","edit",2);
      echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove&admin_id={$row['admin_id']}\";}","remove",2,array('tooltiptext'=>"Delete {$row['admin_login']}?"));
      echo "</nowrap></td>\n";
      echo "</tr>\n";
      $alt=($alt+1)%2;
    }
    echo "</table>\n";
  }

  function form ($data, $err, $title, $add='add'){
    global $_SHOP;
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>\n";
    echo "<input type='hidden' name='admin_id' value='{$data['admin_id']}'/>\n";
    echo "<input type='hidden' name='action' value='save'/>\n";
    $this->form_head($title);
		$this->print_field_o( 'admin_id', $data );
    $this->print_input('admin_login',$data,$err,30,100);
    if ($this->admintype == 'pos') {
      if (!$data["kasse_name"]) $data["kasse_name"] = $data["user_lastname"];
  		$this->print_input( 'kasse_name', $data, $err, 30, 50 );

  		$this->print_input( 'user_address', $data, $err, 30, 75 );
  		$this->print_input( 'user_address1', $data, $err, 30, 75 );
  		$this->print_input( 'user_zip', $data, $err, 8, 20 );
  		$this->print_input( 'user_city', $data, $err, 30, 50 );
  		$this->print_input( 'user_state', $data, $err, 30, 50 );
  		$this->print_countrylist( 'user_country', $data['user_country'], $err );

  		$this->print_input( 'user_phone', $data, $err, 30, 50 );
  		$this->print_input( 'user_fax', $data, $err, 30, 50 );
  		$this->print_input( 'user_email', $data, $err, 30, 50 );

  		$this->print_select( 'user_prefs', $data, $err, array('pdt', 'pdf') );
    } elseif ($this->admintype == 'control') {
        if(is_string($data["control_event_ids"]) and $data["control_event_ids"]!=""){
          $event=explode(",",$data["control_event_ids"]);
        } elseif (is_array($data["control_event_ids"])) {
          $event= $data["control_event_ids"];
        }
        $query="select event_id,event_name,event_date,event_time
                from Event
                where event_pm_id is not null
      		  		and event_rep LIKE '%sub%'
      		  		AND event_status <> 'unpub'
      		  		AND event_date >= now()
                order by event_date,event_time";
        if(!$res=ShopDB::query($query)){
          user_error(shopDB::error());
          return;
        }

        echo "<tr><td  class='admin_name' width='40%' valign='top'>".con('control_event_ids')."</td>
              <td class='admin_value'>";//&nbsp;</td></tr><tr><td class='admin_value' colspan='2' align='center'>";
        echo "<select multiple size='10' name='control_event_ids[]'>";
        while($row=shopDB::fetch_assoc($res)){
          $sel=(in_array($row["event_id"], $event))?"selected":"";
          $date=formatAdminDate($row["event_date"]);
          $time=formatTime($row["event_time"]);
          echo "<option value='".$row["event_id"]."' $sel>$date $time ". $row["event_name"]."</option>";
        }
        echo "</select></td></tr>\n";

    }
   // var_dump($_SESSION);
    if ($_SESSION['_SHOP_AUTH_USER_NAME']<>$data['admin_login']){
      $this->print_select_assoc('admin_inuse',$data,$err,array('No'=>'no','Yes'=>'yes'));
    }

    $this->print_password ('password1', $data, $err);
    $this->print_password ('password2', $data, $err);
    $this->form_foot(2,$_SERVER['PHP_SELF']);
  }

  function draw ($admintype) {
    global $_SHOP;
    $this->admintype = $admintype;
    if ($_GET['action'] == 'add') {
       $adm = new Admins(true, $admintype);
       $row = (array)$adm;
       $this->form($row, $err, con($admintype.'_add_title'));
    } elseif ($_GET['action'] == 'edit' && $_GET['admin_id']){
      if ($adm = Admins::load($_REQUEST['admin_id'])) {
         $row = (array)$adm;
         $this->form($row, null, con($admintype.'_update_title'));
      } else $this->table();
    } elseif ($_POST['action'] == 'save') {
      if (!$adm = Admins::load($_POST['admin_id'])) {
         $adm = new Admins(true, $admintype);
      }
      if ($adm->fillPost() && $adm->saveEx()) {
        $this->table();
      } else {
        $this->form($_POST, null, con($admintype.((isset($_POST['admin_id']))?'_update_title':'_add_title')));
      }

    } elseif($_GET['action']=='remove' and $_GET['admin_id']){
      if($adm = Admins::load($_GET['admin_id']))
        $adm->delete();
      $this->table();
    } else {
        $this->table();
    }
  }

}
?>